<?php

namespace App\Http\Middleware;

use App\Services\InstallationTrackingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackInstallation
{
    protected $installationTrackingService;

    public function __construct(InstallationTrackingService $installationTrackingService)
    {
        $this->installationTrackingService = $installationTrackingService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking on local development/localhost to prevent network timeouts
        $isLocal = in_array($request->getHost(), ['localhost', '127.0.0.1', '::1']) || 
                   str_ends_with($request->getHost(), '.localhost') || 
                   env('LICENSE_DEV_MODE', true);

        if ($isLocal) {
            return $next($request);
        }

        // Track installation on first access (only once, skip in dev mode)
        if (!env('LICENSE_DEV_MODE', false) && $this->shouldTrackInstallation($request)) {
            try {
                $this->installationTrackingService->trackInstallation();
            } catch (\Exception $e) {
                Log::error('Failed to track installation', [
                    'error' => $e->getMessage(),
                    'url' => $request->fullUrl()
                ]);
            }
        }

        return $next($request);
    }

    /**
     * Determine if we should track installation
     */
    private function shouldTrackInstallation(Request $request): bool
    {
        // Only track on first access to admin dashboard or main pages
        $trackableRoutes = [
            'admin.dashboard',
            'admin.verification.index',
            'admin.verification.activate'
        ];

        // Check if this is a trackable route
        foreach ($trackableRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        // Also track if accessing root admin URL
        if ($request->is('admin') || $request->is('admin/')) {
            return true;
        }

        return false;
    }
}
