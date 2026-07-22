<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AuthorizeByRouteName
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $route = $request->route();

        if (!$route || !$user) {
            return $next($request);
        }

        $name = $route->getName();
        if (!$name) {
            return $next($request);
        }

        // Allow subscription payments for all admin users
        if (str_starts_with($name, 'admin.subscription-payments')) {
            return $next($request);
        }

        // Direct mapping for assigned orders route
        if ($name === 'admin.asigned.orders' || $name === 'asigned.orders') {
            if ($user->can('orders.asigned') || $user->can('orders.assigned') || $user->can('asigned.orders') || $user->can('assigned.orders') || $user->can('orders.view') || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin')))) {
                return $next($request);
            }
            abort(403);
        }

        $parts = explode('.', $name);
        if (count($parts) < 2 || $parts[0] !== 'admin') {
            return $next($request);
        }

        // Determine resource and action, supporting nested resources like admin.shipping.zones.index
        $resource = $parts[1] ?? null;
        $action = implode('.', array_slice($parts, 2)); // may be empty or contain dots

        // If no explicit action provided (admin.resource), treat as index
        if ($action === '') {
            $action = 'index';
        }

        // If action has sub-resource like "zones.index", treat resource as resource.subresource
        if (str_contains($action, '.')) {
            $actionParts = explode('.', $action);
            // move the first action segment into resource (e.g., shipping.zones)
            $resource = $resource . '.' . array_shift($actionParts);
            $action = implode('.', $actionParts);
        }

        // Resource aliases to keep permission names consistent
        $resourceAliases = [
            'product' => 'products',
            'post' => 'posts',
            'landing-pages' => 'landing_pages',
            'postsubcategory' => 'post_subcategories',
            'category' => 'categories',
            'orders.reports' => 'orders',
            'customers.reports' => 'orders',
            'menus.items' => 'menu_items',
            'roles-permissions' => 'roles_permissions',
            // Basic shipping routes use admin.basic.shipping.settings.* → map to shipping.basic.* permissions
            'basic.shipping.settings' => 'shipping.basic',
            'basic_shipping' => 'shipping.basic',
        ];

        // Special-case report routes to use dedicated permissions
        if ($resource === 'orders' && str_starts_with($action, 'reports')) {
            $resource = 'reports.sales';
        } elseif ($resource === 'customers' && str_starts_with($action, 'reports')) {
            $resource = 'reports.customers';
        }

        // Normalize resource: kebab -> snake, apply alias, keep dotted subresources (e.g., shipping.zones)
        $normalize = function (string $segment): string {
            return str_replace('-', '_', $segment);
        };

        $normalizedResource = collect(explode('.', $resource))
            ->map(fn ($seg) => $normalize($seg))
            ->implode('.');

        $resourceKey = $resourceAliases[$resource] ?? $resourceAliases[$normalizedResource] ?? $normalizedResource;

        $map = [
            // read-only
            'index' => 'view',
            'show' => 'view',
            'data' => 'view',
            'search' => 'view',
            'report' => 'view',
            'reports' => 'view',
            'results' => 'results',
            'result-details' => 'result_details',
            'payment-gateway' => 'view',
            // create
            'create' => 'create',
            'store' => 'create',
            'add' => 'create',
            // update
            'edit' => 'update',
            'update' => 'update',
            'toggle-status' => 'update',
            'update-positions' => 'update',
            // delete
            'destroy' => 'delete',
            'delete' => 'delete',
            'bulk-delete' => 'bulk_delete',
            // common custom actions used in this app
            'export-selected' => 'export_selected',
            'update-item' => 'update_item',
            'updateStatus' => 'update_status',
            'updateNote' => 'update_note',
            'get-variation-combinations' => 'get_variation_combinations',
            'convert' => 'convert',
            'convert-page' => 'convert_page',
            'calculator' => 'calculate',
            'calculate' => 'calculate',
            'toggle' => 'toggle',
            'integration' => 'integrate',
            'test-connection' => 'test_connection',
            'check-phone' => 'check_phone',
            'check-order-fraud' => 'check_order',
            'refresh-result' => 'refresh_result',
            'sendToCourier' => 'send',
            'sendBulkToCourier' => 'send_bulk',
            'saveSendCourier' => 'save_send',
            'courier-cities' => 'cities',
            'courier-zones' => 'zones',
            'courier-areas' => 'areas',
            'courierStatus' => 'status',
            'balance' => 'balance',
        ];

        $suffix = $map[$action] ?? null;
        if (!$suffix) {
            // Fallback: normalize action name to snake_case as suffix
            $suffix = Str::snake(str_replace('-', '_', $action));
        }

        $permission = $resourceKey . '.' . $suffix;

        // Special mapping for roles and permissions routes to align with database seeds
        if (str_starts_with($resourceKey, 'roles_permissions')) {
            if (str_contains($resourceKey, 'role')) {
                $permission = 'roles.manage';
            } elseif (str_contains($resourceKey, 'permission')) {
                $permission = 'permissions.manage';
            } elseif (str_contains($resourceKey, 'user_roles') || str_contains($resourceKey, 'user')) {
                $permission = 'user_roles.assign';
            } else {
                $permission = 'roles_permissions.view';
            }
        }

        $hasOrderAssignedPermission = ($resourceKey === 'orders' || str_contains($name, 'asigned')) && 
            ($user->can('orders.assigned') || $user->can('orders.asigned') || $user->can('asigned.orders'));

        // Allow packages subscription view for all admin accounts
        $isPackagesView = $request->get('view') === 'packages';
        $isAdminUser = method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('super_admin') || $user->hasRole('super admin') || ($user->role ?? '') === 'admin');

        if ($user->can($permission) || $hasOrderAssignedPermission || ($isPackagesView && $isAdminUser) || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin')))) {
            return $next($request);
        }

        abort(403);
    }
}
