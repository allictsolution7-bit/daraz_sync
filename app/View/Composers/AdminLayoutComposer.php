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
            ($user->role ?? '') === 'super_admin'
        );

        $cacheKey = "admin_layout_stats_v2_{$userId}_" . ($isSuperAdmin ? 'sa' : 'admin');

        // Cache for 30 seconds to make page navigation instant while keeping badge counts fresh
        $stats = Cache::remember($cacheKey, 30, function () use ($user, $isSuperAdmin) {
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
                $headerPendingCount = \App\Models\VendorWalletTransaction::where('status', 'pending')
                    ->where('type', 'recharge_request')
                    ->count();
                $headerPendingPayments = \App\Models\VendorWalletTransaction::with('vendor')
                    ->where('status', 'pending')
                    ->where('type', 'recharge_request')
                    ->latest()->limit(5)->get();
            } else {
                $adminVendorIds = \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                $headerPendingCount = \App\Models\VendorWalletTransaction::where('status', 'pending')
                    ->where('type', 'recharge_request')
                    ->whereIn('vendor_id', $adminVendorIds)
                    ->count();
                $headerPendingPayments = \App\Models\VendorWalletTransaction::with('vendor')
                    ->where('status', 'pending')
                    ->where('type', 'recharge_request')
                    ->whereIn('vendor_id', $adminVendorIds)
                    ->latest()->limit(5)->get();
            }

            // 3. Pending products
            if ($isSuperAdmin) {
                $headerPendingProducts = \App\Models\Product::with('vendor')
                    ->whereNotNull('vendor_id')
                    ->where('approval_status', 'pending')
                    ->latest()->limit(5)->get();
                $headerPendingProdCount = \App\Models\Product::whereNotNull('vendor_id')
                    ->where('approval_status', 'pending')
                    ->count();
            } else {
                $adminVendorIds = $adminVendorIds ?? \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                $headerPendingProducts = \App\Models\Product::with('vendor')
                    ->whereIn('vendor_id', $adminVendorIds)
                    ->where('approval_status', 'pending')
                    ->latest()->limit(5)->get();
                $headerPendingProdCount = \App\Models\Product::whereIn('vendor_id', $adminVendorIds)
                    ->where('approval_status', 'pending')
                    ->count();
            }

            // 4. Pending vendor orders
            if ($isSuperAdmin) {
                $headerVendorOrdersQuery = \App\Models\order::whereHas('orderItems', function ($q) {
                    $q->whereNotNull('vendor_id');
                })->where('status', 'pending');
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

            $headerVendorOrders = $headerVendorOrdersQuery->latest()->limit(5)->get();
            $headerVendorOrderCount = $headerVendorOrdersQuery->count();

            // 4. B2B Wholesale Order Notifications
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
            $headerWholesaleOrders = $headerWholesaleQuery->latest()->limit(5)->get();
            $headerWholesaleCount = $headerWholesaleQuery->count();

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
