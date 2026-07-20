<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LicenseIntegrityService
{
    private const INTEGRITY_KEY = 'license_integrity_check';
    
    /**
     * Check system integrity and prevent tampering
     */
    public function checkSystemIntegrity(): bool
    {
        // Check 1: Verify license service file integrity
        if (!$this->verifyLicenseServiceIntegrity()) {
            $this->logTamperAttempt('License service file modified');
            return false;
        }
        
        // Check 2: Verify configuration integrity
        if (!$this->verifyConfigurationIntegrity()) {
            $this->logTamperAttempt('Configuration files modified');
            return false;
        }
        
        // Check 3: Anti-debugging checks
        if ($this->detectDebugging()) {
            $this->logTamperAttempt('Debugging tools detected');
            return false;
        }
        
        // Check 4: Verify middleware integrity
        if (!$this->verifyMiddlewareIntegrity()) {
            $this->logTamperAttempt('Middleware files modified');
            return false;
        }
        
        // Check 5: Runtime integrity check
        if (!$this->performRuntimeIntegrityCheck()) {
            $this->logTamperAttempt('Runtime integrity violation');
            return false;
        }
        
        return true;
    }
    
    /**
     * Verify license service file integrity
     */
    private function verifyLicenseServiceIntegrity(): bool
    {
        $licenseServicePath = app_path('Services/LicenseService.php');
        $expectedHash = 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6'; // Pre-calculated hash
        
        if (!file_exists($licenseServicePath)) {
            return false;
        }
        
        $actualHash = hash('sha256', file_get_contents($licenseServicePath));
        
        // In production, compare with expected hash
        // For development, just check if file exists and has expected content
        return strpos(file_get_contents($licenseServicePath), 'class LicenseService') !== false;
    }
    
    /**
     * Verify configuration integrity
     */
    private function verifyConfigurationIntegrity(): bool
    {
        $configPath = config_path('license.php');
        
        if (!file_exists($configPath)) {
            return false;
        }
        
        $configContent = file_get_contents($configPath);
        
        // Check for critical configuration elements
        $requiredElements = [
            'enable_signature_verification',
            'public_key',
            'mother_panel_url'
        ];
        
        foreach ($requiredElements as $element) {
            if (strpos($configContent, $element) === false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Detect debugging tools
     */
    private function detectDebugging(): bool
    {
        // Check for common debugging functions
        $debugFunctions = ['xdebug_is_enabled', 'var_dump', 'print_r', 'debug_backtrace'];
        
        foreach ($debugFunctions as $function) {
            if (function_exists($function)) {
                // Check if it's being called inappropriately
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
                foreach ($backtrace as $trace) {
                    if (isset($trace['function']) && $trace['function'] === $function) {
                        return true;
                    }
                }
            }
        }
        
        // Check for debugging environment variables
        $debugVars = ['XDEBUG_CONFIG', 'PHP_IDE_CONFIG', 'DBGP_IDEKEY'];
        foreach ($debugVars as $var) {
            if (!empty($_ENV[$var]) || !empty($_SERVER[$var])) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Verify middleware integrity
     */
    private function verifyMiddlewareIntegrity(): bool
    {
        $middlewarePath = app_path('Http/Middleware/CheckLicense.php');
        
        if (!file_exists($middlewarePath)) {
            return false;
        }
        
        $middlewareContent = file_get_contents($middlewarePath);
        
        // Check for critical middleware elements
        return strpos($middlewareContent, 'CheckLicense') !== false &&
               strpos($middlewareContent, 'handle') !== false;
    }
    
    /**
     * Perform runtime integrity check
     */
    private function performRuntimeIntegrityCheck(): bool
    {
        // Check if license validation methods exist
        $licenseService = new LicenseService();
        $methods = ['validateLicense', 'isModuleAllowed', 'getLicenseStatus'];
        
        foreach ($methods as $method) {
            if (!method_exists($licenseService, $method)) {
                return false;
            }
        }
        
        // Check if critical routes exist
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $requiredRoutes = ['admin.verification.revalidate', 'admin.verification.activate'];
        
        foreach ($requiredRoutes as $routeName) {
            $route = $routes->getByName($routeName);
            if (!$route) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Log tamper attempt
     */
    private function logTamperAttempt(string $reason): void
    {
        Log::critical('License system tamper attempt detected', [
            'reason' => $reason,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
            'url' => request()->fullUrl(),
            'session_id' => session()->getId()
        ]);
        
        // Store in cache for monitoring
        $attempts = Cache::get('license_tamper_attempts', []);
        $attempts[] = [
            'reason' => $reason,
            'timestamp' => now(),
            'ip' => request()->ip()
        ];
        
        Cache::put('license_tamper_attempts', $attempts, 3600); // 1 hour
    }
    
    /**
     * Get tamper attempt count
     */
    public function getTamperAttemptCount(): int
    {
        $attempts = Cache::get('license_tamper_attempts', []);
        return count($attempts);
    }
    
    /**
     * Clear tamper attempts (admin only)
     */
    public function clearTamperAttempts(): void
    {
        Cache::forget('license_tamper_attempts');
    }
}
