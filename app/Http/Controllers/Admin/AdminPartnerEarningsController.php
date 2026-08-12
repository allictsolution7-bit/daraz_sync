<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\order_item;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPartnerEarningsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $search = $request->get('search');
        $partnerType = $request->get('partner_type'); // 'reseller', 'vendor', or null/all
        $partnerId = $request->get('partner_id');

        // Base query for order items
        $query = order_item::with(['order', 'vendor', 'product'])
            ->whereNotNull('vendor_id')
            ->whereHas('order', function($q) {
                $q->where('status', 'delivered')
                  ->where('payment_status', 'paid');
            });

        // Apply Date Filters
        if ($startDate) {
            $query->whereHas('order', function($q) use ($startDate) {
                $q->whereDate('created_at', '>=', Carbon::parse($startDate));
            });
        }
        if ($endDate) {
            $query->whereHas('order', function($q) use ($endDate) {
                $q->whereDate('created_at', '<=', Carbon::parse($endDate));
            });
        }

        // Apply Partner Type Filter
        if ($partnerType === 'reseller') {
            $query->whereHas('vendor', function($q) {
                $q->role('reseller');
            });
        } elseif ($partnerType === 'vendor') {
            $query->whereHas('vendor', function($q) {
                $q->role('vendor');
            });
        }

        // Apply Specific Partner Filter
        if ($partnerId) {
            $query->where('vendor_id', $partnerId);
        }

        // Apply Search Filter (Order Number, Customer Name, Partner Name, Product Title)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($orderQ) use ($search) {
                    $orderQ->where('order_number', 'like', "%{$search}%")
                           ->orWhere('name', 'like', "%{$search}%")
                           ->orWhere('phone', 'like', "%{$search}%");
                })->orWhereHas('vendor', function($vendorQ) use ($search) {
                    $vendorQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('product', function($prodQ) use ($search) {
                    $prodQ->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Calculate Totals using cloned queries for accuracy
        $totalsQuery = clone $query;
        
        $totalResellerEarnings = (clone $totalsQuery)->whereHas('vendor', function($q) {
            $q->role('reseller');
        })->sum('vendor_earning');

        $totalVendorEarnings = (clone $totalsQuery)->whereHas('vendor', function($q) {
            $q->role('vendor');
        })->sum('vendor_earning');

        $totalPlatformCommission = (clone $totalsQuery)->sum('vendor_commission_amount');
        
        $totalSalesAmount = (clone $totalsQuery)->sum('sub_total');

        // Paginated items
        $earnings = $query->latest()->paginate(25)->withQueryString();

        // Get list of partners for filters
        $partners = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['reseller', 'vendor']);
        })->get();

        return view('admin.vendors.earnings', compact(
            'earnings',
            'totalResellerEarnings',
            'totalVendorEarnings',
            'totalPlatformCommission',
            'totalSalesAmount',
            'partners',
            'startDate',
            'endDate',
            'search',
            'partnerType',
            'partnerId'
        ));
    }
}
