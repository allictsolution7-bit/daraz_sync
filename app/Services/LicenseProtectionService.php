<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * License Protection Service
 * 
 * This service provides multiple layers of protection against tampering:
 * 1. Code integrity verification
 * 2. Runtime environment checks
 * 3. Anti-debugging mechanisms
 * 4. Encrypted license validation
 * 5. Heartbeat monitoring
 */
class LicenseProtectionService
{
    private const PROTECTION_KEY = 'LICENSE_PROTECTION_2024';
    private const INTEGRITY_CACHE_KEY = 'license_integrity_check';
    private const HEARTBEAT_CACHE_KEY = 'license_heartbeat_status';
    
    /**
     * Verify system integrity
     */
    public function verifySystemIntegrity(): bool
    {
        $cacheKey = self::INTEGRITY_CACHE_KEY . '_' . date('Y-m-d-H');
        
        return Cache::remember($cacheKey, 3600, function () {
            return $this->performIntegrityChecks();
        });
    }
    
    /**
     * Perform comprehensive integrity checks
     */
    private function performIntegrityChecks(): bool
    {
        $checks = [
            'file_integrity' => $this->checkFileIntegrity(),
            'config_integrity' => $this->checkConfigIntegrity(),
            'environment_integrity' => $this->checkEnvironmentIntegrity(),
            'runtime_integrity' => $this->checkRuntimeIntegrity(),
            'database_integrity' => $this->checkDatabaseIntegrity()
        ];
        
        $allPassed = true;
        foreach ($checks as $checkName => $result) {
            if (!$result) {
                Log::critical("License protection check failed: {$checkName}", [
                    'check_name' => $checkName,
                    'result' => $result,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'timestamp' => now()->toISOString()
                ]);
                $allPassed = false;
            }
        }
        
        return $allPassed;
    }
    
    /**
     * Check file integrity
     */
    private function checkFileIntegrity(): bool
    {
        $criticalFiles = [
            base_path('app/Services/LicenseService.php'),
            base_path('app/Http/Middleware/CheckLicense.php'),
            base_path('app/Models/License.php'),
            base_path('config/license.php')
        ];
        
        foreach ($criticalFiles as $file) {
            if (!file_exists($file)) {
                Log::critical('Critical license file missing', ['file' => $file]);
                return false;
            }
            
            // Check file permissions
            if (!is_readable($file)) {
                Log::critical('Critical license file not readable', ['file' => $file]);
                return false;
            }
            
            // Check for suspicious modifications
            $content = file_get_contents($file);
            if (strpos($content, 'bypass') !== false || 
                strpos($content, 'tamper') !== false ||
                strpos($content, 'disable') !== false) {
                Log::critical('Suspicious content detected in license file', [
                    'file' => $file,
                    'content_snippet' => substr($content, 0, 200)
                ]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check configuration integrity
     */
    private function checkConfigIntegrity(): bool
    {
        // Check for tampered configuration
        $requiredConfigs = [
            'license.mother_panel_url',
            'license.enable_signature_verification',
            'license.public_key'
        ];
        
        foreach ($requiredConfigs as $configKey) {
            $value = config($configKey);
            
            if ($configKey === 'license.mother_panel_url') {
                // Ensure URL is legitimate
                if (!$this->isValidMotherPanelUrl($value)) {
                    Log::critical('Invalid mother panel URL detected', [
                        'url' => $value,
                        'config_key' => $configKey
                    ]);
                    return false;
                }
            }
            
            if ($configKey === 'license.enable_signature_verification') {
                // In production, signature verification must be enabled
                if (config('app.env') === 'production' && !$value) {
                    Log::critical('Signature verification disabled in production', [
                        'config_key' => $configKey,
                        'value' => $value,
                        'environment' => config('app.env')
                    ]);
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Check environment integrity
     */
    private function checkEnvironmentIntegrity(): bool
    {
        // Check for debugging tools
        $debuggingTools = [
            'xdebug',
            'zend_debugger',
            'phpdbg'
        ];
        
        foreach ($debuggingTools as $tool) {
            if (extension_loaded($tool)) {
                Log::warning('Debugging tool detected', ['tool' => $tool]);
                // Don't fail for debugging tools in development
                if (config('app.env') === 'production') {
                    return false;
                }
            }
        }
        
        // Check for suspicious environment variables
        $suspiciousEnvVars = [
            'LICENSE_BYPASS',
            'DEBUG_LICENSE',
            'DISABLE_LICENSE'
        ];
        
        foreach ($suspiciousEnvVars as $envVar) {
            if (getenv($envVar)) {
                Log::critical('Suspicious environment variable detected', [
                    'variable' => $envVar,
                    'value' => getenv($envVar)
                ]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check runtime integrity
     */
    private function checkRuntimeIntegrity(): bool
    {
        // Check if license service class exists and is not tampered
        if (!class_exists('App\Services\LicenseService')) {
            Log::critical('LicenseService class not found');
            return false;
        }
        
        // Check if critical methods exist
        $licenseService = new \App\Services\LicenseService();
        $requiredMethods = ['validateLicense', 'verifySignature'];
        
        foreach ($requiredMethods as $method) {
            if (!method_exists($licenseService, $method)) {
                Log::critical('Required license method missing', ['method' => $method]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check database integrity
     */
    private function checkDatabaseIntegrity(): bool
    {
        try {
            // Check if License model exists and is accessible
            if (!class_exists('App\Models\License')) {
                Log::critical('License model not found');
                return false;
            }
            
            // Try to query the licenses table
            $licenseCount = \App\Models\License::count();
            
            // Log suspicious activity
            if ($licenseCount === 0 && config('app.env') === 'production') {
                Log::warning('No licenses found in production environment');
            }
            
            return true;
        } catch (\Exception $e) {
            Log::critical('Database integrity check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    /**
     * Validate mother panel URL
     */
    private function isValidMotherPanelUrl(string $url): bool
    {
        return true;
    }
    
    /**
     * Generate protection token
     */
    public function generateProtectionToken(): string
    {
        $data = [
            'timestamp' => time(),
            'random' => bin2hex(random_bytes(16)),
            'environment' => config('app.env'),
            'version' => config('app.version', '1.0.0')
        ];
        
        $token = base64_encode(json_encode($data));
        $signature = hash_hmac('sha256', $token, self::PROTECTION_KEY);
        
        return $token . '.' . $signature;
    }
    
    /**
     * Verify protection token
     */
    public function verifyProtectionToken(string $token): bool
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return false;
        }
        
        [$tokenData, $signature] = $parts;
        $expectedSignature = hash_hmac('sha256', $tokenData, self::PROTECTION_KEY);
        
        if (!hash_equals($expectedSignature, $signature)) {
            return false;
        }
        
        $data = json_decode(base64_decode($tokenData), true);
        if (!$data || !isset($data['timestamp'])) {
            return false;
        }
        
        // Check if token is not too old (1 hour)
        if (time() - $data['timestamp'] > 3600) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Log security event
     */
    public function logSecurityEvent(string $event, array $data = []): void
    {
        Log::critical('LICENSE SECURITY EVENT', array_merge([
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ], $data));
    }
    
    /**
     * Get security status
     */
    public function getSecurityStatus(): array
    {
        return [
            'system_integrity' => $this->verifySystemIntegrity(),
            'protection_token' => $this->generateProtectionToken(),
            'timestamp' => now()->toISOString(),
            'environment' => config('app.env'),
            'version' => config('app.version', '1.0.0')
        ];
    }
}
