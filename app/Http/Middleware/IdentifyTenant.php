<?php

namespace App\Http\Middleware;

use App\Models\SaaSTenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request and resolve the tenant dynamically.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rawHost = strtolower($request->header('Host') ?: $request->getHost());
        // Strip any port number (e.g. sabbir.localhost:8000 -> sabbir.localhost)
        $host = preg_replace('/:\d+$/', '', $rawHost);

        // Fast-path bypass for bare localhost / 127.0.0.1 without subdomain
        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true) && !$request->has('subdomain')) {
            return $next($request);
        }

        try {
            $tenant = null;

            // 0. Check explicit subdomain query parameter or header if provided (e.g. ?subdomain=sabbir)
            $explicitSubdomain = $request->query('subdomain') ?: $request->header('X-Tenant-Subdomain');
            if ($explicitSubdomain) {
                $tenant = SaaSTenant::where('subdomain', strtolower($explicitSubdomain))->where('is_active', true)->first();
            }

            // 1. Try finding by custom domain first (e.g. mystore.com, shop.customdomain.com)
            if (!$tenant) {
                $cleanHost = preg_replace('/^www\./i', '', $host);
                $tenant = SaaSTenant::where(function($q) use ($host, $cleanHost) {
                    $q->where('custom_domain', $host)
                      ->orWhere('custom_domain', $cleanHost)
                      ->orWhere('custom_domain', 'www.' . $cleanHost);
                })->where('is_active', true)->first();
            }

            // 2. If not matched, extract and check by subdomain (e.g. sabbir.localhost or sabbir.purnobd.com)
            if (!$tenant) {
                $subdomain = $this->extractSubdomain($host);
                if ($subdomain) {
                    $tenant = SaaSTenant::where('subdomain', $subdomain)->where('is_active', true)->first();
                }
            }

            if ($tenant) {
                $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;

                // Switch default mysql database to tenant's database
                Config::set('database.connections.mysql.database', $dbName);
                DB::purge('mysql');
                DB::reconnect('mysql');

                // Share tenant instance
                $request->attributes->set('tenant', $tenant);
                view()->share('currentTenant', $tenant);
            }
        } catch (\Throwable $e) {
            Log::warning("Tenant database switch failed for host [{$host}]: " . $e->getMessage());
        }

        return $next($request);
    }

    /**
     * Extract tenant subdomain from host.
     */
    protected function extractSubdomain(string $host): ?string
    {
        // Don't process IP addresses
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return null;
        }

        $parts = explode('.', $host);

        // If localhost with subdomain (e.g. sabbir.localhost)
        if (count($parts) === 2 && $parts[1] === 'localhost') {
            $subdomain = strtolower($parts[0]);
            return !in_array($subdomain, ['www', 'admin', 'api', 'app', 'central']) ? $subdomain : null;
        }

        // If standard domain (e.g. sabbir.purnobd.com or sabbir.domain.test)
        if (count($parts) >= 3) {
            $subdomain = strtolower($parts[0]);
            return !in_array($subdomain, ['www', 'admin', 'api', 'app', 'mail', 'cpanel', 'webmail', 'central']) ? $subdomain : null;
        }

        return null;
    }
}
