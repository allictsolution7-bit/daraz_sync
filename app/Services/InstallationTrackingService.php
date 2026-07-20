<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Installation Tracking Service
 * 
 * Tracks ALL installations, even unlicensed ones, and reports to mother panel
 */
class InstallationTrackingService
{
    private const TRACKING_PATH = '/api/installations/track';
    private const TRACKING_CACHE_KEY = 'installation_tracking_sent';
    
    private function getMotherPanelUrl(): string
    {
        $base = rtrim(config('license.mother_panel_url') ?? '', '/');
        // Strip any path suffix to get the bare base URL
        $baseUrl = preg_replace('#/api/.*$#', '', $base);
        return $baseUrl . self::TRACKING_PATH;
    }

    /**
     * Track installation attempt
     */
    public function trackInstallation(): void
    {
        // Only track once per installation
        if (Cache::has(self::TRACKING_CACHE_KEY)) {
            return;
        }
        
        try {
            $installationData = $this->gatherInstallationData();
            
            $response = Http::timeout(5)->post($this->getMotherPanelUrl(), [
                'installation_data' => $installationData,
                'timestamp' => now()->timestamp,
                'tracking_type' => 'installation_attempt'
            ]);
            
            if ($response->successful()) {
                // Mark as tracked to avoid duplicate tracking
                Cache::put(self::TRACKING_CACHE_KEY, true, 86400 * 365); // 1 year
                
                Log::info('Installation tracking sent to mother panel', [
                    'domain' => $installationData['domain'],
                    'ip' => $installationData['ip_address']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to track installation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Track license activation attempt
     */
    public function trackLicenseActivation(string $licenseKey, bool $success, ?string $errorMessage = null): void
    {
        try {
            $installationData = $this->gatherInstallationData();
            
            $response = Http::timeout(5)->post($this->getMotherPanelUrl(), [
                'installation_data' => $installationData,
                'license_key' => $licenseKey,
                'activation_success' => $success,
                'error_message' => $errorMessage,
                'timestamp' => now()->timestamp,
                'tracking_type' => 'license_activation'
            ]);
            
            Log::info('License activation tracking sent to mother panel', [
                'license_key' => $licenseKey,
                'success' => $success,
                'domain' => $installationData['domain']
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to track license activation', [
                'license_key' => $licenseKey,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Track unlicensed usage attempts
     */
    public function trackUnlicensedUsage(string $route, string $ipAddress, string $userAgent): void
    {
        try {
            $installationData = $this->gatherInstallationData();
            
            $response = Http::timeout(5)->post($this->getMotherPanelUrl(), [
                'installation_data' => $installationData,
                'route_attempted' => $route,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => now()->timestamp,
                'tracking_type' => 'unlicensed_usage_attempt'
            ]);
            
            Log::warning('Unlicensed usage attempt tracked', [
                'route' => $route,
                'ip' => $ipAddress,
                'domain' => $installationData['domain']
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to track unlicensed usage', [
                'route' => $route,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Gather installation data
     */
    private function gatherInstallationData(): array
    {
        return [
            'domain' => request()->getHost(),
            'app_url' => config('app.url'),
            'environment' => config('app.env'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'installation_date' => now()->toISOString(),
            'has_license' => \App\Models\License::count() > 0,
            'license_count' => \App\Models\License::count(),
            'active_license_count' => \App\Models\License::where('status', 'active')->count()
        ];
    }
    
    /**
     * Send periodic installation status (even without license)
     */
    public function sendInstallationStatus(): void
    {
        try {
            $installationData = $this->gatherInstallationData();
            
            $response = Http::timeout(5)->post($this->getMotherPanelUrl(), [
                'installation_data' => $installationData,
                'timestamp' => now()->timestamp,
                'tracking_type' => 'installation_status'
            ]);
            
            Log::info('Installation status sent to mother panel', [
                'domain' => $installationData['domain'],
                'has_license' => $installationData['has_license']
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send installation status', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
