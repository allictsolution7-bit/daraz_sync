<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    /**
     * Display vendor's orders (read-only)
     */
    public function index(Request $request)
    {
        $vendor = auth()->user();

        // Get order IDs that contain vendor's products
        $orderIds = order_item::forVendor($vendor->id)
            ->distinct('order_id')
            ->pluck('order_id');

        $query = order::whereIn('id', $orderIds)
            ->with(['customer', 'orderItems' => function ($query) use ($vendor) {
                $query->forVendor($vendor->id);
            }]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order ID or customer
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(order $order)
    {
        $vendor = auth()->user();

        // Check if vendor has items in this order
        $vendorItems = $order->orderItems()
            ->forVendor($vendor->id)
            ->get();

        if ($vendorItems->isEmpty()) {
            abort(403, 'You do not have access to this order.');
        }

        $order->load(['customer', 'orderItems.product', 'orderItems.vendor']);

        return view('vendor.orders.show', compact('order', 'vendorItems'));
    }

    /**
     * Get earnings summary
     */
    public function earnings(Request $request)
    {
        $vendor = auth()->user();

        // Get all vendor's order items
        $query = order_item::forVendor($vendor->id)
            ->whereNotNull('vendor_earning');

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $items = $query->with(['order', 'product'])->latest()->paginate(50);

        // Calculate totals
        $totalEarnings = $query->sum('vendor_earning');
        $totalCommission = $query->sum('vendor_commission_amount');
        $totalSales = $query->sum('sub_total');
        $paidEarnings = order_item::forVendor($vendor->id)
            ->where('vendor_paid', true)
            ->sum('vendor_earning');

        $stats = [
            'total_sales' => $totalSales,
            'total_earnings' => $totalEarnings,
            'total_commission' => $totalCommission,
            'paid' => $paidEarnings,
            'unpaid' => $totalEarnings - $paidEarnings,
        ];

        return view('vendor.orders.earnings', compact('items', 'stats'));
    }

    /**
     * Show reseller's own POS orders (Reseller POS Orders page)
     */
    public function resellerOrders(Request $request)
    {
        $reseller = auth()->user();

        // Orders placed via this reseller's POS — identified by vendor_id in order_items
        $orderIds = order_item::where('vendor_id', $reseller->id)
            ->whereNotNull('others')
            ->whereRaw("JSON_EXTRACT(others, '$.is_pos_order') = true")
            ->distinct()
            ->pluck('order_id');

        $query = order::whereIn('id', $orderIds)
            ->where('order_source', 'Reseller POS')
            ->with(['order_items.product']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $allOrders = order::whereIn('id', $orderIds)->where('order_source', 'Reseller POS');
        $statusCounts = [
            'all'        => (clone $allOrders)->count(),
            'pending'    => (clone $allOrders)->where('status', 'pending')->count(),
            'processing' => (clone $allOrders)->where('status', 'processing')->count(),
            'delivered'  => (clone $allOrders)->where('status', 'delivered')->count(),
            'cancelled'  => (clone $allOrders)->where('status', 'cancelled')->count(),
        ];

        // Earnings summary
        $totalEarnings = order_item::where('vendor_id', $reseller->id)
            ->whereIn('order_id', $orderIds)
            ->sum('vendor_earning');
        $paidEarnings = order_item::where('vendor_id', $reseller->id)
            ->whereIn('order_id', $orderIds)
            ->where('vendor_paid', true)
            ->sum('vendor_earning');

        $orders = $query->latest()->paginate(20);

        return view('vendor.orders.reseller', compact(
            'orders', 'statusCounts', 'totalEarnings', 'paidEarnings'
        ));
    }
}

