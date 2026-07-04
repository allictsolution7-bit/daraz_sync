<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $config = config('security.headers');

        // Strict-Transport-Security (HSTS)
        if ($config['hsts']['enabled']) {
            $hsts = 'max-age=' . $config['hsts']['max_age'];
            if ($config['hsts']['include_subdomains']) {
                $hsts .= '; includeSubDomains';
            }
            if ($config['hsts']['preload']) {
                $hsts .= '; preload';
            }
            $response->headers->set('Strict-Transport-Security', $hsts);
        }

        // Content-Security-Policy (CSP)
        if ($config['csp']['enabled']) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://code.jquery.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.datatables.net; " .
                   "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com https://fonts.bunny.net https://cdn.datatables.net; " .
                   "img-src 'self' data: https: http:; " .
                   "font-src 'self' https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com https://fonts.bunny.net https://fonts.gstatic.com; " .
                   "connect-src 'self' https://www.google-analytics.com; " .
                   "frame-src 'self' https://www.googletagmanager.com; " .
                   "object-src 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self'; " .
                   "frame-ancestors 'self';";
            
            if ($config['csp']['report_uri']) {
                $csp .= "; report-uri " . $config['csp']['report_uri'];
            }
            
            $headerName = $config['csp']['report_only'] ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
            $response->headers->set($headerName, $csp);
        }

        // X-Frame-Options
        if ($config['frame_options']['enabled']) {
            $response->headers->set('X-Frame-Options', $config['frame_options']['value']);
        }

        // X-Content-Type-Options
        if ($config['content_type_options']['enabled']) {
            $response->headers->set('X-Content-Type-Options', $config['content_type_options']['value']);
        }

        // Referrer-Policy
        if ($config['referrer_policy']['enabled']) {
            $response->headers->set('Referrer-Policy', $config['referrer_policy']['value']);
        }

        // Permissions-Policy - DISABLED
        // if ($config['permissions_policy']['enabled']) {
        //     $response->headers->set('Permissions-Policy', $config['permissions_policy']['value']);
        // }

        // X-XSS-Protection
        if ($config['xss_protection']['enabled']) {
            $response->headers->set('X-XSS-Protection', $config['xss_protection']['value']);
        }

        // Additional security headers
        $response->headers->set('X-Download-Options', 'noopen');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        return $response;
    }
} 