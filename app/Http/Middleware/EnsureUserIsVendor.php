<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has vendor role
        if (!$user->isVendor()) {
            abort(403, 'Access denied. Vendor access only.');
        }

        // Check if vendor settings exist and account is active
        $vendorSettings = $user->vendorSettings;
        
        if (!$vendorSettings) {
            abort(403, 'Vendor account not properly configured.');
        }

        if (!$vendorSettings->is_active) {
            abort(403, 'Your vendor account is inactive. Please contact support.');
        }

        return $next($request);
    }
}

