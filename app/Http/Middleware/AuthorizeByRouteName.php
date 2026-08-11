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
        if ($user && !$user->relationLoaded('roles')) {
            $user->load('roles.permissions', 'permissions');
        }
        $route = $request->route();

        if (!$route || !$user) {
            return $next($request);
        }

        $name = $route->getName();
        if (!$name) {
            return $next($request);
        }

        // Allow subcategory, helper routes, subscription payments, courier balance, and admin profile for all admin users
        if (
            $name === 'admin.get-product-subcategories' || 
            $name === 'admin.third-categories.by-subcategories' ||
            $name === 'admin.items.combination.save-wholesale-tiers' ||
            str_starts_with($name, 'admin.subscription-payments') || 
            str_starts_with($name, 'admin.profile') || 
            str_starts_with($name, 'admin.steadfast') ||
            str_starts_with($name, 'admin.pathao') ||
            str_starts_with($name, 'admin.delivery') ||
            str_starts_with($name, 'admin.support-tickets')
        ) {
            return $next($request);
        }

        // Direct mapping for assigned orders route
        if ($name === 'admin.asigned.orders' || $name === 'asigned.orders') {
            if ($user->can('orders.asigned') || $user->can('orders.assigned') || $user->can('asigned.orders') || $user->can('assigned.orders') || $user->can('orders.view') || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin')))) {
                return $next($request);
            }
            abort(403, 'Permission required: (orders.assigned). Please ask an administrator to grant this permission.');
        }

        // Direct mapping for vendor orders route
        if ($name === 'admin.vendor-orders.index' || $name === 'admin.vendor-orders.data' || $name === 'vendor-orders.index' || $name === 'vendor-orders.data') {
            if ($user->can('vendor_orders.view') || $user->can('orders.view') || $user->can('vendor_orders.index') || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('admin')))) {
                return $next($request);
            }
            abort(403, 'Permission required: (vendor_orders.view). Please ask an administrator to grant this permission.');
        }

        // Direct mapping for reseller orders route — accessible to all admins with orders.view or admin role
        if (str_starts_with($name, 'admin.reseller-orders')) {
            if (
                $user->can('reseller_orders.view') ||
                $user->can('orders.view') ||
                $user->can('vendor_orders.view') ||
                (method_exists($user, 'hasRole') && (
                    $user->hasRole('super_admin') ||
                    $user->hasRole('super admin') ||
                    $user->hasRole('admin')
                ))
            ) {
                return $next($request);
            }
            abort(403, 'Permission required: (reseller_orders.view). Please ask an administrator to grant this permission.');
        }

        // Direct mapping for basic shipping settings route
        if (str_starts_with($name, 'admin.basic.shipping.settings') || str_starts_with($name, 'basic.shipping.settings')) {
            if ($user->can('shipping.basic.view') || $user->can('shipping.basic.update') || $user->can('basic_shipping.view') || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('admin')))) {
                return $next($request);
            }
            abort(403, 'Permission required: (shipping.basic.view). Please ask an administrator to grant this permission.');
        }

        // Direct mapping for delayed purchase events queue routes
        if (str_starts_with($name, 'admin.delayed-events') || str_starts_with($name, 'delayed-events')) {
            if ($user->can('delayed_events.view') || $user->can('orders.view') || (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('admin')))) {
                return $next($request);
            }
            abort(403, 'Permission required: (delayed_events.view). Please ask an administrator to grant this permission.');
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
            'items' => 'products',
            'post' => 'posts',
            'landing-pages' => 'landing_pages',
            'postsubcategory' => 'post_subcategories',
            'category' => 'categories',
            'catalog-groups' => 'product_categories',
            'catalog-tiers' => 'sub_categories',
            'catalog-levels' => 'sub_categories',
            'third-categories' => 'sub_categories',
            'third_categories' => 'sub_categories',
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
            'search-products' => 'search_products',
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

        $vendorPerm = str_starts_with($permission, 'vendor.') ? $permission : 'vendor.' . $permission;
        $standardPerm = str_replace('vendor.', '', $permission);

        // Explicit route action mappings for vendor routes (e.g. vendor.products.edit, vendor.products.create, vendor.products.delete)
        $routePermission = str_replace(['.update', '.destroy'], ['.edit', '.delete'], $name);
        $routePermissionSnake = str_replace(['.update', '.destroy'], ['.edit', '.delete'], $permission);

        if (
            $user->can($permission) || 
            $user->can($vendorPerm) || 
            $user->can($standardPerm) || 
            $user->can($name) ||
            $user->can($routePermission) ||
            $user->can($routePermissionSnake) ||
            $hasOrderAssignedPermission || 
            ($isPackagesView && $isAdminUser) || 
            (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin')))
        ) {
            return $next($request);
        }

        abort(403, 'Permission required: (' . $permission . '). Please ask an administrator to grant this permission.');
    }
}
