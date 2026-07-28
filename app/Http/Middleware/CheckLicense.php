<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use App\Services\LicenseIntegrityService;
use App\Services\LicenseHeartbeatService;
use App\Services\LicenseProtectionService;
use App\Services\InstallationTrackingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckLicense
{
    protected $licenseService;
    protected $integrityService;
    protected $heartbeatService;
    protected $protectionService;
    protected $installationTrackingService;

    public function __construct(
        LicenseService $licenseService,
        LicenseIntegrityService $integrityService,
        LicenseHeartbeatService $heartbeatService,
        LicenseProtectionService $protectionService,
        InstallationTrackingService $installationTrackingService
    ) {
        $this->licenseService = $licenseService;
        $this->integrityService = $integrityService;
        $this->heartbeatService = $heartbeatService;
        $this->protectionService = $protectionService;
        $this->installationTrackingService = $installationTrackingService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $module  The module to check (pos, landing_page, etc.)
     */
    public function handle(Request $request, Closure $next, string $module = null): Response
    {
        // Skip license check for specific routes (like license activation)
        if ($this->shouldSkipLicenseCheck($request)) {
            return $next($request);
        }

        // Heartbeat Check: Send heartbeat every 6 hours (skip in dev mode)
        if (!env('LICENSE_DEV_MODE', false)) {
            $this->heartbeatService->startHeartbeat();
        }

        // Security Check 1: Enhanced system integrity (only if strict security is enabled)
        if (config('license.strict_security_checks', false)) {
            if (!$this->protectionService->verifySystemIntegrity()) {
                Log::critical('License middleware: Enhanced system integrity check failed');
                return $this->handleSecurityViolation($request, 'System integrity violation detected');
            }
            
            // Security Check 1.5: Legacy integrity check
            if (!$this->integrityService->checkSystemIntegrity()) {
                Log::critical('License middleware: Legacy system integrity check failed');
                return $this->handleSecurityViolation($request, 'System integrity violation detected');
            }
        } else {
            Log::info('License middleware: Strict security checks disabled, skipping integrity checks');
        }

        // Security Check 2: Tamper attempt monitoring (relaxed when strict security is disabled)
        $tamperAttempts = $this->integrityService->getTamperAttemptCount();
        $tamperThreshold = config('license.strict_security_checks', false) ? 5 : 100; // Much higher threshold when strict security is disabled
        
        if ($tamperAttempts > $tamperThreshold) {
            Log::critical('License middleware: Excessive tamper attempts', [
                'attempt_count' => $tamperAttempts,
                'threshold' => $tamperThreshold,
                'ip' => $request->ip()
            ]);
            return $this->handleSecurityViolation($request, 'Security violation: Multiple tamper attempts detected');
        }

        // Security Check 3: Suspicious request detection (relaxed when strict security is disabled)
        if (config('license.strict_security_checks', false) && $this->detectSuspiciousRequest($request)) {
            return $this->handleSecurityViolation($request, 'Suspicious request pattern detected');
        }

        // If no specific module is provided, just check if any license is active
        if (!$module) {
            $licenseStatus = $this->licenseService->getLicenseStatus();
            if (!$licenseStatus['valid']) {
                // Track unlicensed usage attempt
                $this->installationTrackingService->trackUnlicensedUsage(
                    $request->route()->getName() ?? $request->path(),
                    $request->ip(),
                    $request->userAgent()
                );
                
                return $this->handleInvalidLicense($request, $licenseStatus['message'] ?? 'Invalid license');
            }
            return $next($request);
        }

        // Check if the specific module is allowed
        if (!$this->licenseService->isModuleAllowed($module)) {
            $message = "Access denied. The '{$module}' module is not available in your license.";
            return $this->handleInvalidLicense($request, $message);
        }

        // For premium modules, also check support status
        $premiumModules = ['pos', 'landing_page', 'multi_vendor'];
        if (in_array($module, $premiumModules)) {
            if (!$this->licenseService->isSupportActive()) {
                $supportStatus = $this->licenseService->getSupportStatus();
                $message = $supportStatus['expired'] 
                    ? "Your support period has ended. Please renew to continue using the '{$module}' module."
                    : "Support period is required for the '{$module}' module.";
                return $this->handleInvalidLicense($request, $message);
            }
        }

        return $next($request);
    }

    /**
     * Check if license check should be skipped for certain routes
     */
    protected function shouldSkipLicenseCheck(Request $request): bool
    {
        $skipRoutes = [
            'admin.verification.*',
            'license.*',
            'admin.dashboard', // Allow access to dashboard to configure license
        ];

        foreach ($skipRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle invalid license response
     */
    protected function handleInvalidLicense(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'redirect' => route('admin.verification.index')
            ], 403);
        }

        // For web requests, redirect to license page with error message
        return redirect()
            ->route('admin.verification.index')
            ->with('error', $message);
    }

    /**
     * Handle security violation response
     */
    protected function handleSecurityViolation(Request $request, string $message): Response
    {
        Log::critical('Security violation detected in license middleware', [
            'message' => $message,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'timestamp' => now()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Security Violation',
                'message' => 'Access denied due to security policy violation',
                'request_id' => uniqid('sec_', true)
            ], 403);
        }

        return response()->view('errors.security-violation', [
            'message' => $message,
            'request_id' => uniqid('sec_', true)
        ], 403);
    }

    /**
     * Detect suspicious request patterns
     */
    protected function detectSuspiciousRequest(Request $request): bool
    {
        // Check for common bypass attempts
        $suspiciousParams = ['bypass_license', 'disable_license', 'skip_validation', 'debug_mode'];
        foreach ($suspiciousParams as $param) {
            if ($request->has($param)) {
                Log::warning('Suspicious request parameter detected', [
                    'parameter' => $param,
                    'value' => $request->get($param),
                    'ip' => $request->ip()
                ]);
                return true;
            }
        }

        // Check for suspicious headers
        $suspiciousHeaders = ['X-Bypass-License', 'X-Disable-Validation', 'X-Debug-Mode'];
        foreach ($suspiciousHeaders as $header) {
            if ($request->hasHeader($header)) {
                Log::warning('Suspicious header detected', [
                    'header' => $header,
                    'value' => $request->header($header),
                    'ip' => $request->ip()
                ]);
                return true;
            }
        }

        // Check for suspicious user agents
        $suspiciousUserAgents = ['curl', 'wget', 'python', 'bot'];
        $userAgent = strtolower($request->userAgent());
        foreach ($suspiciousUserAgents as $suspicious) {
            if (strpos($userAgent, $suspicious) !== false) {
                Log::warning('Suspicious user agent detected', [
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);
                return true;
            }
        }

        return false;
    }
}
