<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLandingPageLimit
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
        // Only check for POST requests (creation) and specific routes
        if (!$this->shouldCheckLimit($request)) {
            return $next($request);
        }

        if (!$this->licenseService->canCreateLandingPage()) {
            $licenseStatus = $this->licenseService->getLicenseStatus();
            $message = sprintf(
                'Landing page limit reached. You can create %d landing pages but %d are already created.',
                $licenseStatus['landing_page_limit'] ?? 0,
                $licenseStatus['landing_page_used'] ?? 0
            );

            return $this->handleLimitExceeded($request, $message);
        }

        return $next($request);
    }

    /**
     * Check if we should verify the landing page limit
     */
    protected function shouldCheckLimit(Request $request): bool
    {
        // Check for creation routes
        if ($request->isMethod('POST') && $request->routeIs('admin.landing-pages.store')) {
            return true;
        }

        // Check for specific creation endpoints
        $creationRoutes = [
            'admin.landing-pages.store',
            'api.landing-pages.store',
            'landing-pages.store',
        ];

        foreach ($creationRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle limit exceeded response
     */
    protected function handleLimitExceeded(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'error_type' => 'landing_page_limit_exceeded'
            ], 403);
        }

        // For web requests, redirect back with error message
        return redirect()
            ->back()
            ->withInput()
            ->with('error', $message);
    }
}
