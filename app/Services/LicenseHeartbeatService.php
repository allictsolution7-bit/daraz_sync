<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Exception;

class LicenseHeartbeatService
{
    private const HEARTBEAT_INTERVAL = 21600; // 6 hours
    private const HEARTBEAT_KEY = 'license_heartbeat';
    private const MAX_MISSED_HEARTBEATS = 3;
    
    /**
     * Start license heartbeat monitoring
     */
    public function startHeartbeat(): void
    {
        $lastHeartbeat = Cache::get(self::HEARTBEAT_KEY, 0);
        $now = time();
        
        if ($now - $lastHeartbeat > self::HEARTBEAT_INTERVAL) {
            $this->sendHeartbeat();
        }
    }
    
    /**
     * Send heartbeat to mother panel
     */
    private function sendHeartbeat(): void
    {
        try {
            $licenseService = new LicenseService();
            $license = \App\Models\License::first();
            
            if (!$license) {
                return;
            }
            
            $heartbeatData = [
                'license_key' => $license->license_key,
                'domain' => request()->getHost(),
                'timestamp' => time(),
                'system_info' => $this->getSystemInfo(),
                'heartbeat_id' => $this->generateHeartbeatId()
            ];
            
            $motherUrl = config('license.mother_panel_url', 'https://uddoktaecommerce.com/api/licenses/validate');
            $heartbeatUrl = str_replace('/api/licenses/validate', '/api/licenses/heartbeat', $motherUrl);
            if (!str_contains($heartbeatUrl, '/api/licenses/heartbeat')) {
                $heartbeatUrl = rtrim($motherUrl, '/') . '/heartbeat';
            }

            $response = Http::timeout(5)->post($heartbeatUrl, [
                'license_key' => $license->license_key,
                'domain' => request()->getHost(),
                'security_data' => [
                    'system_info' => $heartbeatData['system_info'],
                    'heartbeat_id' => $heartbeatData['heartbeat_id'],
                    'tamper_attempts' => 0, // TODO: Get from tamper service
                    'last_activity' => now()->toISOString(),
                    'php_version' => $heartbeatData['system_info']['php_version'],
                    'memory_usage' => $heartbeatData['system_info']['memory_usage'],
                    'database_status' => $heartbeatData['system_info']['database_status']
                ],
                'timestamp' => $heartbeatData['timestamp'],
                'signature' => $this->generateHeartbeatSignature($heartbeatData)
            ]);
            
            if ($response->successful()) {
                Cache::put(self::HEARTBEAT_KEY, time(), self::HEARTBEAT_INTERVAL * 2);
                $this->resetMissedHeartbeats();
                
                Log::info('License heartbeat sent successfully', [
                    'license_key' => $license->license_key,
                    'timestamp' => now()
                ]);
            } else {
                $this->recordMissedHeartbeat();
            }
            
        } catch (Exception $e) {
            Log::error('License heartbeat failed', ['error' => $e->getMessage()]);
            $this->recordMissedHeartbeat();
        }
    }
    
    /**
     * Record missed heartbeat
     */
    private function recordMissedHeartbeat(): void
    {
        $missedCount = Cache::get('license_missed_heartbeats', 0);
        $missedCount++;
        
        Cache::put('license_missed_heartbeats', $missedCount, self::HEARTBEAT_INTERVAL * 2);
        
        if ($missedCount >= self::MAX_MISSED_HEARTBEATS) {
            $this->handleMissedHeartbeats();
        }
    }
    
    /**
     * Handle excessive missed heartbeats
     */
    private function handleMissedHeartbeats(): void
    {
        Log::critical('Excessive missed heartbeats detected', [
            'missed_count' => Cache::get('license_missed_heartbeats', 0),
            'timestamp' => now()
        ]);
        
        // Optionally disable certain features or show warning
        Cache::put('license_heartbeat_failed', true, 3600);
        
        // Send alert to admin
        $this->sendHeartbeatAlert();
    }
    
    /**
     * Reset missed heartbeat count
     */
    private function resetMissedHeartbeats(): void
    {
        Cache::forget('license_missed_heartbeats');
        Cache::forget('license_heartbeat_failed');
    }
    
    /**
     * Generate heartbeat ID
     */
    private function generateHeartbeatId(): string
    {
        return hash('sha256', time() . request()->getHost() . config('app.key'));
    }
    
    /**
     * Generate heartbeat signature
     */
    private function generateHeartbeatSignature(array $data): string
    {
        return hash_hmac('sha256', json_encode($data), config('app.key'));
    }
    
    /**
     * Get system information
     */
    private function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_usage' => memory_get_usage(true),
            'disk_free_space' => disk_free_space('/'),
            'active_users' => $this->getActiveUserCount(),
            'database_status' => $this->checkDatabaseStatus()
        ];
    }
    
    /**
     * Get active user count
     */
    private function getActiveUserCount(): int
    {
        try {
            return \App\Models\User::where('last_activity_at', '>', now()->subMinutes(30))->count();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Check database status
     */
    private function checkDatabaseStatus(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Send heartbeat alert to admin
     */
    private function sendHeartbeatAlert(): void
    {
        try {
            // Send email alert to admin
            $adminEmail = config('mail.admin_email', 'admin@thikana.com');
            
            \Mail::raw('License heartbeat failed. Please check the system.', function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                        ->subject('License Heartbeat Alert - Thikana');
            });
            
        } catch (Exception $e) {
            Log::error('Failed to send heartbeat alert', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Check if heartbeat is healthy
     */
    public function isHeartbeatHealthy(): bool
    {
        return !Cache::has('license_heartbeat_failed');
    }
    
    /**
     * Get heartbeat status
     */
    public function getHeartbeatStatus(): array
    {
        $lastHeartbeat = Cache::get(self::HEARTBEAT_KEY, 0);
        $missedCount = Cache::get('license_missed_heartbeats', 0);
        
        return [
            'last_heartbeat' => $lastHeartbeat ? date('Y-m-d H:i:s', $lastHeartbeat) : 'Never',
            'missed_heartbeats' => $missedCount,
            'is_healthy' => $this->isHeartbeatHealthy(),
            'next_heartbeat' => $lastHeartbeat ? date('Y-m-d H:i:s', $lastHeartbeat + self::HEARTBEAT_INTERVAL) : 'Unknown'
        ];
    }
}
