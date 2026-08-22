<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminLayoutComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $userId = $user->id;

        $isSuperAdmin = (
            (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('Super Admin'))) ||
            ($user->is_super_admin ?? false) ||
            ($user->role ?? '') === 'super_admin' ||
            ($user->email ?? '') === 'admin@purnobd.com'
        );

        $cacheKey = "admin_layout_stats_v3_{$userId}_" . ($isSuperAdmin ? 'sa' : 'admin');

        // Cache for 20 seconds to make page navigation instant while keeping badge counts fresh
        $stats = Cache::remember($cacheKey, 20, function () use ($user, $userId, $isSuperAdmin) {
            $lastReadAt = Cache::get("admin_notif_last_read_{$userId}");

            // 1. Unread chat count
            $unreadChatCount = \App\Models\ChatMessage::where('is_read', false)
                ->where('sender_id', '!=', $user->id)
                ->whereHas('chatRoom', function ($q) use ($user) {
                    $q->where('customer_id', $user->id)
                      ->orWhere('vendor_id', $user->id);
                })
                ->count();

            // 2. Pending recharge requests
            if ($isSuperAdmin) {
                $headerPendingPaymentsQuery = \App\Models\VendorWalletTransaction::where('status', 'pending')
                    ->where('type', 'recharge_request');
            } else {
                $adminVendorIds = \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                $headerPendingPaymentsQuery = \App\Models\VendorWalletTransaction::where('status', 'pending')
                    ->where('type', 'recharge_request')
                    ->whereIn('vendor_id', $adminVendorIds);
            }
            $headerPendingPayments = (clone $headerPendingPaymentsQuery)->with('vendor')->latest()->limit(5)->get();
            $headerPendingCount = $lastReadAt 
                ? (clone $headerPendingPaymentsQuery)->where('created_at', '>', $lastReadAt)->count()
                : (clone $headerPendingPaymentsQuery)->count();
            $totalPendingPaymentsCount = (clone $headerPendingPaymentsQuery)->count();

            // 3. Pending products
            if ($isSuperAdmin) {
                $headerPendingProductsQuery = \App\Models\Product::whereNotNull('vendor_id')
                    ->where('approval_status', 'pending');
            } else {
                $adminVendorIds = $adminVendorIds ?? \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                $headerPendingProductsQuery = \App\Models\Product::whereIn('vendor_id', $adminVendorIds)
                    ->where('approval_status', 'pending');
            }
            $headerPendingProducts = (clone $headerPendingProductsQuery)->with('vendor')->latest()->limit(5)->get();
            $headerPendingProdCount = $lastReadAt
                ? (clone $headerPendingProductsQuery)->where('created_at', '>', $lastReadAt)->count()
                : (clone $headerPendingProductsQuery)->count();

            // 4. Pending vendor & customer orders
            if ($isSuperAdmin) {
                $headerVendorOrdersQuery = \App\Models\order::where('status', 'pending');
            } else {
                $adminVendorIds = $adminVendorIds ?? \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                $adminProductIds = \App\Models\Product::where('created_by', $user->id)->pluck('id')->toArray();

                $headerVendorOrdersQuery = \App\Models\order::where('status', 'pending')
                    ->whereHas('orderItems', function ($q) use ($adminVendorIds, $adminProductIds) {
                        $q->where(function ($subQ) use ($adminVendorIds, $adminProductIds) {
                            $hasCondition = false;
                            if (!empty($adminVendorIds)) {
                                $subQ->whereIn('vendor_id', $adminVendorIds)
                                     ->orWhereHas('product', function ($pq) use ($adminVendorIds) {
                                         $pq->whereIn('vendor_id', $adminVendorIds);
                                     });
                                $hasCondition = true;
                            }
                            if (!empty($adminProductIds)) {
                                if ($hasCondition) {
                                    $subQ->orWhereHas('product', function ($pq) use ($adminProductIds) {
                                        $pq->whereIn('parent_product_id', $adminProductIds);
                                    });
                                } else {
                                    $subQ->whereHas('product', function ($pq) use ($adminProductIds) {
                                        $pq->whereIn('parent_product_id', $adminProductIds);
                                    });
                                }
                            }
                        });
                    });
            }

            $headerVendorOrders = (clone $headerVendorOrdersQuery)->latest()->limit(5)->get();
            $headerVendorOrderCount = $lastReadAt
                ? (clone $headerVendorOrdersQuery)->where('created_at', '>', $lastReadAt)->count()
                : (clone $headerVendorOrdersQuery)->count();
            $totalVendorOrderCount = (clone $headerVendorOrdersQuery)->count();

            // 5. B2B Wholesale Order Notifications
            $headerWholesaleQuery = \App\Models\WholesalePurchaseOrder::query();
            if ($isSuperAdmin) {
                $headerWholesaleQuery->where('payment_status', 'pending');
            } else {
                $headerWholesaleQuery->where('payment_status', 'approved')
                    ->where('fulfillment_status', 'processing')
                    ->where(function($q) use ($user) {
                        $q->where('seller_admin_id', $user->id)
                          ->orWhere('seller_admin_name', 'like', "%{$user->email}%")
                          ->orWhere('seller_admin_name', 'like', "%{$user->name}%");
                    });
            }
            $headerWholesaleOrders = (clone $headerWholesaleQuery)->latest()->limit(5)->get();
            $headerWholesaleCount = $lastReadAt
                ? (clone $headerWholesaleQuery)->where('created_at', '>', $lastReadAt)->count()
                : (clone $headerWholesaleQuery)->count();
            $totalWholesaleCount = (clone $headerWholesaleQuery)->count();

            // 6. Cross-Database Notification Aggregation for Super Admin (Across All Tenant DBs)
            if ($isSuperAdmin) {
                try {
                    $tenants = \App\Models\SaaSTenant::where('is_active', true)->get();
                    $currentDb = config('database.connections.mysql.database');

                    foreach ($tenants as $tenant) {
                        $tDbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                        if ($tDbName === $currentDb) {
                            continue;
                        }

                        try {
                            config(['database.connections.tenant_noti_temp' => array_merge(
                                config('database.connections.mysql'),
                                ['database' => $tDbName]
                            )]);
                            \Illuminate\Support\Facades\DB::purge('tenant_noti_temp');

                            // Pending Wholesale Orders from tenant DB
                            $tWholesale = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                ->table('wholesale_purchase_orders')
                                ->where('payment_status', 'pending')
                                ->latest('id')
                                ->limit(5)
                                ->get()
                                ->map(function($w) use ($tenant) {
                                    $w->tenant_subdomain = $tenant->subdomain;
                                    $w->tenant_name = $tenant->name;
                                    $w->created_at = isset($w->created_at) ? \Carbon\Carbon::parse($w->created_at) : null;
                                    return $w;
                                });
                            if ($tWholesale->isNotEmpty()) {
                                $headerWholesaleOrders = $headerWholesaleOrders->concat($tWholesale);
                                $tWholesaleCount = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                    ->table('wholesale_purchase_orders')
                                    ->where('payment_status', 'pending');
                                $totalWholesaleCount += (clone $tWholesaleCount)->count();
                                $headerWholesaleCount += $lastReadAt
                                    ? (clone $tWholesaleCount)->where('created_at', '>', $lastReadAt)->count()
                                    : (clone $tWholesaleCount)->count();
                            }

                            // Pending Store Orders from tenant DB
                            $tOrders = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                ->table('orders')
                                ->where('status', 'pending')
                                ->latest('id')
                                ->limit(5)
                                ->get()
                                ->map(function($o) use ($tenant) {
                                    $o->tenant_subdomain = $tenant->subdomain;
                                    $o->tenant_name = $tenant->name;
                                    $o->created_at = isset($o->created_at) ? \Carbon\Carbon::parse($o->created_at) : null;
                                    return $o;
                                });
                            if ($tOrders->isNotEmpty()) {
                                $headerVendorOrders = $headerVendorOrders->concat($tOrders);
                                $tOrdersCount = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                    ->table('orders')
                                    ->where('status', 'pending');
                                $totalVendorOrderCount += (clone $tOrdersCount)->count();
                                $headerVendorOrderCount += $lastReadAt
                                    ? (clone $tOrdersCount)->where('created_at', '>', $lastReadAt)->count()
                                    : (clone $tOrdersCount)->count();
                            }

                            // Pending Products from tenant DB
                            $tProducts = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                ->table('products')
                                ->where('approval_status', 'pending')
                                ->latest('id')
                                ->limit(5)
                                ->get()
                                ->map(function($p) use ($tenant) {
                                    $p->tenant_subdomain = $tenant->subdomain;
                                    $p->tenant_name = $tenant->name;
                                    $p->created_at = isset($p->created_at) ? \Carbon\Carbon::parse($p->created_at) : null;
                                    return $p;
                                });
                            if ($tProducts->isNotEmpty()) {
                                $headerPendingProducts = $headerPendingProducts->concat($tProducts);
                                $tProductsCount = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                    ->table('products')
                                    ->where('approval_status', 'pending');
                                $headerPendingProdCount += $lastReadAt
                                    ? (clone $tProductsCount)->where('created_at', '>', $lastReadAt)->count()
                                    : (clone $tProductsCount)->count();
                            }

                            // Pending Recharge Requests from tenant DB
                            $tRecharges = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                ->table('vendor_wallet_transactions')
                                ->where('status', 'pending')
                                ->where('type', 'recharge_request')
                                ->latest('id')
                                ->limit(5)
                                ->get()
                                ->map(function($tx) use ($tenant) {
                                    $tx->tenant_subdomain = $tenant->subdomain;
                                    $tx->tenant_name = $tenant->name;
                                    $tx->created_at = isset($tx->created_at) ? \Carbon\Carbon::parse($tx->created_at) : null;
                                    return $tx;
                                });
                            if ($tRecharges->isNotEmpty()) {
                                $headerPendingPayments = $headerPendingPayments->concat($tRecharges);
                                $tRechargesCount = \Illuminate\Support\Facades\DB::connection('tenant_noti_temp')
                                    ->table('vendor_wallet_transactions')
                                    ->where('status', 'pending')
                                    ->where('type', 'recharge_request');
                                $totalPendingPaymentsCount += (clone $tRechargesCount)->count();
                                $headerPendingCount += $lastReadAt
                                    ? (clone $tRechargesCount)->where('created_at', '>', $lastReadAt)->count()
                                    : (clone $tRechargesCount)->count();
                            }
                        } catch (\Throwable $tenantDbEx) {
                            // Silently ignore unreachable tenant database
                        }
                    }
                } catch (\Throwable $saasEx) {
                    // Fallback to central DB notifications
                }
            }

            $headerTotalCount = $headerPendingCount + $headerPendingProdCount + $headerVendorOrderCount + $headerWholesaleCount;

            // 5. Sidebar pending support tickets
            $pendingTicketCount = \App\Models\SupportTicket::where('status', 'open')->count();

            // 6. Sidebar pending vendor verification
            $pendingVendorCount = \App\Models\VendorSetting::where('is_verified', false)
                ->where('additional_config->verification_submitted', true)
                ->count();

            // 7. Cached Logo
            $siteLogo = \App\Models\SiteSetting::getLogo();

            // 8. License status
            $licenseService = app(\App\Services\LicenseService::class);
            $licenseStatus = $licenseService->getLicenseStatus();

            return [
                'unreadChatCount' => $unreadChatCount,
                'headerPendingCount' => $headerPendingCount,
                'headerPendingPayments' => $headerPendingPayments,
                'headerPendingProducts' => $headerPendingProducts,
                'headerPendingProdCount' => $headerPendingProdCount,
                'headerVendorOrders' => $headerVendorOrders,
                'headerVendorOrderCount' => $headerVendorOrderCount,
                'totalVendorOrderCount' => $totalVendorOrderCount ?? $headerVendorOrderCount,
                'totalPendingPaymentsCount' => $totalPendingPaymentsCount ?? $headerPendingCount,
                'totalWholesaleCount' => $totalWholesaleCount ?? $headerWholesaleCount,
                'headerWholesaleOrders' => $headerWholesaleOrders,
                'headerWholesaleCount' => $headerWholesaleCount,
                'headerTotalCount' => $headerTotalCount,
                'pendingTicketCount' => $pendingTicketCount,
                'pendingVendorCount' => $pendingVendorCount,
                'siteLogo' => $siteLogo,
                'licenseService' => $licenseService,
                'licenseStatus' => $licenseStatus,
            ];
        });

        $view->with($stats);
    }
}
