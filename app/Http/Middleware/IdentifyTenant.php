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
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);

        if ($subdomain) {
            try {
                $tenant = SaaSTenant::where('subdomain', $subdomain)->where('is_active', true)->first();

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
                Log::warning("Tenant database switch failed for subdomain [{$subdomain}]: " . $e->getMessage());
            }
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
