<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LicenseIntegrityService;
use App\Services\LicenseHeartbeatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class LicenseSecurityController extends Controller
{
    protected $integrityService;
    protected $heartbeatService;

    public function __construct(
        LicenseIntegrityService $integrityService,
        LicenseHeartbeatService $heartbeatService
    ) {
        $this->integrityService = $integrityService;
        $this->heartbeatService = $heartbeatService;
    }

    /**
     * Display license security dashboard
     */
    public function index()
    {
        $securityData = [
            'tamper_attempts' => $this->getTamperAttempts(),
            'heartbeat_status' => $this->heartbeatService->getHeartbeatStatus(),
            'integrity_checks' => $this->getIntegrityChecks(),
            'security_logs' => $this->getSecurityLogs(),
            'system_health' => $this->getSystemHealth()
        ];

        return view('admin.license.security', compact('securityData'));
    }

    /**
     * Get tamper attempts data
     */
    private function getTamperAttempts(): array
    {
        $attempts = Cache::get('license_tamper_attempts', []);
        
        return [
            'total_attempts' => count($attempts),
            'recent_attempts' => array_slice($attempts, -10), // Last 10 attempts
            'attempts_by_ip' => $this->groupAttemptsByIP($attempts),
            'attempts_by_reason' => $this->groupAttemptsByReason($attempts)
        ];
    }

    /**
     * Get integrity check results
     */
    private function getIntegrityChecks(): array
    {
        return [
            'system_integrity' => $this->integrityService->checkSystemIntegrity(),
            'last_check' => now()->toISOString(),
            'check_history' => Cache::get('license_integrity_history', [])
        ];
    }

    /**
     * Get security logs
     */
    private function getSecurityLogs(): array
    {
        // In a real implementation, you'd query the logs table or log files
        return [
            'recent_violations' => $this->getRecentViolations(),
            'suspicious_requests' => $this->getSuspiciousRequests(),
            'failed_attempts' => $this->getFailedAttempts()
        ];
    }

    /**
     * Get system health status
     */
    private function getSystemHealth(): array
    {
        return [
            'license_status' => $this->getLicenseStatus(),
            'database_status' => $this->checkDatabaseStatus(),
            'file_system_status' => $this->checkFileSystemStatus(),
            'network_status' => $this->checkNetworkStatus()
        ];
    }

    /**
     * Group tamper attempts by IP
     */
    private function groupAttemptsByIP(array $attempts): array
    {
        $grouped = [];
        foreach ($attempts as $attempt) {
            $ip = $attempt['ip'] ?? 'Unknown';
            $grouped[$ip] = ($grouped[$ip] ?? 0) + 1;
        }
        return $grouped;
    }

    /**
     * Group tamper attempts by reason
     */
    private function groupAttemptsByReason(array $attempts): array
    {
        $grouped = [];
        foreach ($attempts as $attempt) {
            $reason = $attempt['reason'] ?? 'Unknown';
            $grouped[$reason] = ($grouped[$reason] ?? 0) + 1;
        }
        return $grouped;
    }

    /**
     * Get recent security violations
     */
    private function getRecentViolations(): array
    {
        // This would typically query your logs
        return [
            [
                'type' => 'Tamper Attempt',
                'message' => 'License service file modified',
                'timestamp' => now()->subMinutes(30),
                'ip' => '192.168.1.100'
            ],
            [
                'type' => 'Suspicious Request',
                'message' => 'Bypass parameter detected',
                'timestamp' => now()->subHours(2),
                'ip' => '10.0.0.50'
            ]
        ];
    }

    /**
     * Get suspicious requests
     */
    private function getSuspiciousRequests(): array
    {
        return [
            [
                'user_agent' => 'curl/7.68.0',
                'ip' => '192.168.1.100',
                'timestamp' => now()->subMinutes(15),
                'reason' => 'Suspicious user agent'
            ]
        ];
    }

    /**
     * Get failed attempts
     */
    private function getFailedAttempts(): array
    {
        return [
            [
                'type' => 'License Validation',
                'count' => 3,
                'last_attempt' => now()->subMinutes(45),
                'ip' => '10.0.0.50'
            ]
        ];
    }

    /**
     * Get license status
     */
    private function getLicenseStatus(): array
    {
        $licenseService = new \App\Services\LicenseService();
        return $licenseService->getLicenseStatus();
    }

    /**
     * Check database status
     */
    private function checkDatabaseStatus(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check file system status
     */
    private function checkFileSystemStatus(): bool
    {
        $criticalFiles = [
            app_path('Services/LicenseService.php'),
            app_path('Http/Middleware/CheckLicense.php'),
            config_path('license.php')
        ];

        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check network status
     */
    private function checkNetworkStatus(): bool
    {
        try {
            $response = \Http::timeout(5)->get('https://uddoktaecommerce.com/api/licenses/health');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear tamper attempts
     */
    public function clearTamperAttempts()
    {
        $this->integrityService->clearTamperAttempts();
        
        return response()->json([
            'success' => true,
            'message' => 'Tamper attempts cleared successfully'
        ]);
    }

    /**
     * Force integrity check
     */
    public function forceIntegrityCheck()
    {
        $result = $this->integrityService->checkSystemIntegrity();
        
        // Store result in history
        $history = Cache::get('license_integrity_history', []);
        $history[] = [
            'timestamp' => now(),
            'result' => $result,
                'triggered_by' => Auth::check() ? Auth::user()->name : 'System'
        ];
        
        Cache::put('license_integrity_history', array_slice($history, -50), 86400); // Keep last 50 checks
        
        return response()->json([
            'success' => true,
            'result' => $result,
            'message' => $result ? 'System integrity verified' : 'System integrity issues detected'
        ]);
    }

    /**
     * Get security statistics
     */
    public function getSecurityStats()
    {
        $stats = [
            'tamper_attempts_24h' => $this->getTamperAttemptsLast24Hours(),
            'failed_validations_24h' => $this->getFailedValidationsLast24Hours(),
            'heartbeat_uptime' => $this->getHeartbeatUptime(),
            'security_score' => $this->calculateSecurityScore()
        ];

        return response()->json($stats);
    }

    /**
     * Get tamper attempts in last 24 hours
     */
    private function getTamperAttemptsLast24Hours(): int
    {
        $attempts = Cache::get('license_tamper_attempts', []);
        $last24Hours = now()->subHours(24);
        
        return count(array_filter($attempts, function($attempt) use ($last24Hours) {
            return isset($attempt['timestamp']) && $attempt['timestamp']->gt($last24Hours);
        }));
    }

    /**
     * Get failed validations in last 24 hours
     */
    private function getFailedValidationsLast24Hours(): int
    {
        // This would typically query your logs
        return 0;
    }

    /**
     * Get heartbeat uptime percentage
     */
    private function getHeartbeatUptime(): float
    {
        $status = $this->heartbeatService->getHeartbeatStatus();
        return $status['is_healthy'] ? 100.0 : 0.0;
    }

    /**
     * Calculate security score
     */
    private function calculateSecurityScore(): int
    {
        $score = 100;
        
        // Deduct points for tamper attempts
        $tamperAttempts = count(Cache::get('license_tamper_attempts', []));
        $score -= min($tamperAttempts * 5, 50);
        
        // Deduct points for failed heartbeat
        if (!$this->heartbeatService->isHeartbeatHealthy()) {
            $score -= 20;
        }
        
        // Deduct points for integrity issues
        if (!$this->integrityService->checkSystemIntegrity()) {
            $score -= 30;
        }
        
        return max($score, 0);
    }

    /**
     * Export security logs
     */
    public function exportSecurityLogs()
    {
        $securityData = [
            'tamper_attempts' => $this->getTamperAttempts(),
            'heartbeat_status' => $this->heartbeatService->getHeartbeatStatus(),
            'integrity_checks' => $this->getIntegrityChecks(),
            'security_logs' => $this->getSecurityLogs(),
            'system_health' => $this->getSystemHealth(),
            'exported_at' => now()->toISOString(),
            'exported_by' => Auth::check() ? Auth::user()->name : 'System'
        ];

        $filename = 'security_logs_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->json($securityData, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}
