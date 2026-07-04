<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUpdateAccess
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
        // Skip update check for specific routes (like license activation)
        if ($this->shouldSkipUpdateCheck($request)) {
            return $next($request);
        }

        if (!$this->licenseService->canAccessUpdates()) {
            $updateStatus = $this->licenseService->getUpdateStatus();
            
            $message = $updateStatus['expired'] 
                ? 'Your update period has ended. Please renew to receive system updates.'
                : 'Update period is not active. System updates are not available.';

            return $this->handleNoUpdateAccess($request, $message);
        }

        return $next($request);
    }

    /**
     * Check if update check should be skipped for certain routes
     */
    protected function shouldSkipUpdateCheck(Request $request): bool
    {
        $skipRoutes = [
            'admin.license.*',
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
     * Handle no update access response
     */
    protected function handleNoUpdateAccess(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_type' => 'update_access_denied',
                'redirect' => route('admin.license.index')
            ], 403);
        }

        // For web requests, redirect to license page with error message
        return redirect()
            ->route('admin.license.index')
            ->with('error', $message);
    }
}
