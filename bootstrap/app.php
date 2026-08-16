<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Http\Middleware\AuthorizeByRouteName;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Resolve SaaS Tenant early based on subdomain
        $middleware->prepend(\App\Http\Middleware\IdentifyTenant::class);

        // Apply security headers globally
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        $middleware->alias([
            'auth'             => \App\Http\Middleware\Authenticate::class,
            'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'              => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed'           => \App\Http\Middleware\ValidateSignature::class,
            'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'authorize.by_route' => AuthorizeByRouteName::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'license' => \App\Http\Middleware\CheckLicense::class,
            'license.landing_page' => \App\Http\Middleware\CheckLandingPageLimit::class,
            'license.support' => \App\Http\Middleware\CheckSupport::class,
            'license.updates' => \App\Http\Middleware\CheckUpdateAccess::class,
            'TrackInstallation' => \App\Http\Middleware\TrackInstallation::class,
            // Vendor middleware
            'vendor' => \App\Http\Middleware\EnsureUserIsVendor::class,
            'vendor.verified' => \App\Http\Middleware\EnsureVendorIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
