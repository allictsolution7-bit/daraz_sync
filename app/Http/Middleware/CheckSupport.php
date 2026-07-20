<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSupport
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip support check for specific routes (like license activation)
        if ($this->shouldSkipSupportCheck($request)) {
            return $next($request);
        }

        if (!$this->licenseService->isSupportActive()) {
            $supportStatus = $this->licenseService->getSupportStatus();
            
            $message = $supportStatus['expired'] 
                ? 'Your support period has ended. Please renew to continue using premium features.'
                : 'Support period is not active. Premium features are not available.';

            return $this->handleInactiveSupport($request, $message);
        }

        return $next($request);
    }

    /**
     * Check if support check should be skipped for certain routes
     */
    protected function shouldSkipSupportCheck(Request $request): bool
    {
        $skipRoutes = [
            'admin.verification.*',
            'license.*',
            'admin.dashboard',
            'admin.settings.*',
        ];

        foreach ($skipRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle inactive support response
     */
    protected function handleInactiveSupport(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_type' => 'support_expired',
                'redirect' => route('admin.verification.index')
            ], 403);
        }

        // For web requests, redirect to license page with error message
        return redirect()
            ->route('admin.verification.index')
            ->with('error', $message);
    }
}
