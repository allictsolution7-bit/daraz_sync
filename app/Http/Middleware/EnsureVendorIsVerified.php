<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isVendor()) {
            abort(403, 'Vendor access only.');
        }

        $vendorSettings = $user->vendorSettings;

        if (!$vendorSettings || !$vendorSettings->is_verified) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your vendor account is not verified yet. Please wait for admin approval.',
                ], 403);
            }

            return redirect()->route('vendor.dashboard')
                ->with('error', 'Your vendor account is not verified yet. Please wait for admin approval.');
        }

        return $next($request);
    }
}

