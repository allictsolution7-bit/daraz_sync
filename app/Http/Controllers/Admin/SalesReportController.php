<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.sales', [
            'statusOptions' => $this->getStatusOptions(),
            'paymentMethods' => $this->getDistinctValues('payment_method'),
            'orderSources' => $this->getDistinctValues('order_source'),
            'defaultRange' => 'last_30_days',
        ]);
    }

    public function data(Request $request)
    {
        $validated = $request->validate([
            'preset' => 'nullable|string|in:today,yesterday,last_7_days,last_30_days,this_month,last_month,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'statuses' => 'nullable|array',
            'statuses.*' => 'string',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string',
            'order_sources' => 'nullable|array',
            'order_sources.*' => 'string',
        ]);

        $range = $this->resolveDateRange(
            $validated['preset'] ?? 'last_30_days',
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        $filters = [
            'statuses' => $validated['statuses'] ?? [],
            'payment_methods' => $validated['payment_methods'] ?? [],
            'order_sources' => $validated['order_sources'] ?? [],
        ];

        $ordersQuery = order::query()->whereBetween('created_at', [$range['start'], $range['end']]);
        $ordersQuery = $this->applyOrderFilters($ordersQuery, $filters);

        return response()->json([
            'range' => [
                'start' => $range['start']->toDateString(),
                'end' => $range['end']->toDateString(),
                'label' => $range['label'],
            ],
            'summary' => $this->buildSummary($ordersQuery, $filters, $range),
            'trend' => $this->buildTrend($ordersQuery, $range['start'], $range['end']),
            'status_breakdown' => $this->buildStatusBreakdown($ordersQuery),
            'payment_breakdown' => $this->buildPaymentBreakdown($ordersQuery),
            'source_breakdown' => $this->buildSourceBreakdown($ordersQuery),
            'top_products' => $this->buildTopProducts($filters, $range['start'], $range['end']),
            'payment_reliability' => $this->buildPaymentReliability($ordersQuery),
            'source_risk' => $this->buildSourceRisk($ordersQuery),
            'courier_performance' => $this->buildCourierPerformance($ordersQuery),
            'order_timing' => $this->buildOrderTiming($ordersQuery),
        ]);
    }

    private function resolveDateRange(string $preset, ?string $startDate, ?string $endDate): array
    {
        $now = Carbon::now();

        switch ($preset) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Today';
                break;
            case 'yesterday':
                $start = $now->copy()->subDay()->startOfDay();
                $end = $now->copy()->subDay()->endOfDay();
                $label = 'Yesterday';
                break;
            case 'last_7_days':
                $start = $now->copy()->subDays(6)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 7 days';
                break;
            case 'last_30_days':
                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfDay();
                $label = 'This month';
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                $label = 'Last month';
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate)->startOfDay();
                    $end = Carbon::parse($endDate)->endOfDay();
                    $label = $start->format('M d, Y') . ' - ' . $end->format('M d, Y');
                    break;
                }
                // fall back to default if custom is incomplete
            default:
                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                break;
        }

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        // Prevent unbounded range that can overload the chart
        if ($start->lt($end->copy()->subDays(365))) {
            $start = $end->copy()->subDays(365)->startOfDay();
        }

        return [
            'start' => $start,
            'end' => $end,
            'label' => $label,
        ];
    }

    private function applyOrderFilters($query, array $filters)
    {
        if (!empty($filters['statuses'])) {
            $query->whereIn('orders.status', $filters['statuses']);
        }

        if (!empty($filters['payment_methods'])) {
            $query->whereIn('orders.payment_method', $filters['payment_methods']);
        }

        if (!empty($filters['order_sources'])) {
            $query->whereIn('orders.order_source', $filters['order_sources']);
        }

        return $query;
    }

    private function buildSummary($ordersQuery, array $filters, array $range): array
    {
        $totalOrders = (clone $ordersQuery)->count();
        $grossSales = (clone $ordersQuery)->sum('total');
        $discountTotal = (clone $ordersQuery)->sum('discount');
        $shippingTotal = (clone $ordersQuery)->sum('shipping');

        $netRevenue = $grossSales - $discountTotal + $shippingTotal;

        $cogsQuery = order_item::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereRaw('orders.created_at BETWEEN ? AND ?', [$range['start'], $range['end']]);

        $cogsQuery = $this->applyOrderFilters($cogsQuery, $filters);
        // Use saved COGS snapshot from order_items (captured at order time)
        $cogs = (float) ($cogsQuery->sum('order_items.total_cost') ?? 0);

        $profit = $netRevenue - $cogs;
        $profitMargin = $netRevenue > 0 ? round(($profit / $netRevenue) * 100, 2) : 0.0;

        $codMethods = ['cod', 'cash_on_delivery'];
        $codOrdersQuery = (clone $ordersQuery)->whereIn('payment_method', $codMethods);
        $prepaidOrdersQuery = (clone $ordersQuery)->whereNotIn('payment_method', $codMethods);

        $codOrdersCount = (clone $codOrdersQuery)->count();
        $codRevenue = (clone $codOrdersQuery)->sum('total');
        $prepaidOrdersCount = (clone $prepaidOrdersQuery)->count();
        $prepaidRevenue = (clone $prepaidOrdersQuery)->sum('total');

        $deliveredRevenue = (clone $ordersQuery)->where('status', 'delivered')->sum('total');
        $okOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $returnedOrders = (clone $ordersQuery)->whereIn('status', ['returned', 'return'])->count();
        $refundedOrdersQuery = (clone $ordersQuery)->whereIn('payment_status', ['refunded', 'failed', 'transaction_not_matched']);
        $refundedOrders = (clone $refundedOrdersQuery)->count();
        $refundedRevenue = (clone $refundedOrdersQuery)->sum('total');
        $paidOrders = (clone $ordersQuery)->where('payment_status', 'paid')->count();
        $unitsSold = $this->countUnitsSold($filters, $range['start'], $range['end']);
        $comboOrders = (clone $ordersQuery)->where('is_combo_order', true)->count();

        $customerCounts = (clone $ordersQuery)
            ->select('phone', DB::raw('COUNT(*) as order_count'))
            ->groupBy('phone')
            ->get();
        $uniqueCustomers = $customerCounts->count();
        $repeatCustomers = $customerCounts->where('order_count', '>', 1)->count();
        $repeatRate = $uniqueCustomers > 0 ? round(($repeatCustomers / $uniqueCustomers) * 100, 2) : 0.0;

        return [
            'orders' => $totalOrders,
            'gross_sales' => (float) $grossSales,
            'avg_order_value' => $totalOrders > 0 ? round($grossSales / $totalOrders, 2) : 0.0,
            'discount' => (float) $discountTotal,
            'shipping' => (float) $shippingTotal,
            'avg_shipping' => $totalOrders > 0 ? round($shippingTotal / $totalOrders, 2) : 0.0,
            'net_revenue' => (float) $netRevenue,
            'cogs' => $cogs,
            'profit' => (float) $profit,
            'profit_margin' => $profitMargin,
            'delivered_revenue' => (float) $deliveredRevenue,
            'open_revenue' => max((float) $grossSales - (float) $deliveredRevenue, 0),
            'paid_orders' => $paidOrders,
            'unpaid_orders' => max($totalOrders - $paidOrders, 0),
            'units_sold' => $unitsSold,
            'ok_orders' => $okOrders,
            'cancelled_orders' => $cancelledOrders,
            'returned_orders' => $returnedOrders,
            'refunded_orders' => $refundedOrders,
            'refunded_revenue' => (float) $refundedRevenue,
            'cod_orders' => $codOrdersCount,
            'cod_revenue' => (float) $codRevenue,
            'prepaid_orders' => $prepaidOrdersCount,
            'prepaid_revenue' => (float) $prepaidRevenue,
            'combo_orders' => $comboOrders,
            'unique_customers' => $uniqueCustomers,
            'repeat_customers' => $repeatCustomers,
            'repeat_rate' => $repeatRate,
        ];
    }

    private function buildTrend($ordersQuery, Carbon $start, Carbon $end): array
    {
        $rows = (clone $ordersQuery)
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total) as revenue'),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
                DB::raw("SUM(CASE WHEN payment_status IN ('refunded','failed','transaction_not_matched') THEN 1 ELSE 0 END) as refunded_count"),
                DB::raw("SUM(CASE WHEN payment_status IN ('refunded','failed','transaction_not_matched') THEN total ELSE 0 END) as refunded_amount")
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get();

        $points = $rows->count();

        // Downsample if too many data points to avoid rendering lag
        if ($points > 120) {
            $grouped = $rows->groupBy(function ($row) {
                return Carbon::parse($row->day)->format('Y-m');
            })->map(function ($group, $key) {
                $labelDate = Carbon::createFromFormat('Y-m-d', $group->first()->day)->startOfMonth();
                return [
                    'label' => $labelDate->format('M Y'),
                    'orders' => $group->sum('order_count'),
                    'revenue' => $group->sum('revenue'),
                    'cancelled' => $group->sum('cancelled'),
                    'refunded_count' => $group->sum('refunded_count'),
                    'refunded_amount' => $group->sum('refunded_amount'),
                ];
            })->sortKeys();

            return [
                'labels' => $grouped->pluck('label')->values()->toArray(),
                'orders' => $grouped->pluck('orders')->map(fn ($v) => (int) $v)->values()->toArray(),
                'revenue' => $grouped->pluck('revenue')->map(fn ($v) => (float) $v)->values()->toArray(),
                'cancelled' => $grouped->pluck('cancelled')->map(fn ($v) => (int) $v)->values()->toArray(),
                'refunded_count' => $grouped->pluck('refunded_count')->map(fn ($v) => (int) $v)->values()->toArray(),
                'refunded_amount' => $grouped->pluck('refunded_amount')->map(fn ($v) => (float) $v)->values()->toArray(),
            ];
        }

        if ($points > 90) {
            $grouped = $rows->groupBy(function ($row) {
                return Carbon::parse($row->day)->format('o-W'); // ISO week
            })->map(function ($group, $key) {
                $firstDay = Carbon::parse($group->first()->day);
                $label = 'Wk ' . $firstDay->isoWeek() . ' ' . $firstDay->format('Y');
                return [
                    'label' => $label,
                    'orders' => $group->sum('order_count'),
                    'revenue' => $group->sum('revenue'),
                    'cancelled' => $group->sum('cancelled'),
                    'refunded_count' => $group->sum('refunded_count'),
                    'refunded_amount' => $group->sum('refunded_amount'),
                ];
            })->sortKeys();

            return [
                'labels' => $grouped->pluck('label')->values()->toArray(),
                'orders' => $grouped->pluck('orders')->map(fn ($v) => (int) $v)->values()->toArray(),
                'revenue' => $grouped->pluck('revenue')->map(fn ($v) => (float) $v)->values()->toArray(),
                'cancelled' => $grouped->pluck('cancelled')->map(fn ($v) => (int) $v)->values()->toArray(),
                'refunded_count' => $grouped->pluck('refunded_count')->map(fn ($v) => (int) $v)->values()->toArray(),
                'refunded_amount' => $grouped->pluck('refunded_amount')->map(fn ($v) => (float) $v)->values()->toArray(),
            ];
        }

        return [
            'labels' => $rows->pluck('day')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
            'orders' => $rows->pluck('order_count')->map(fn ($value) => (int) $value)->toArray(),
            'revenue' => $rows->pluck('revenue')->map(fn ($value) => (float) $value)->toArray(),
            'cancelled' => $rows->pluck('cancelled')->map(fn ($value) => (int) $value)->toArray(),
            'refunded_count' => $rows->pluck('refunded_count')->map(fn ($value) => (int) $value)->toArray(),
            'refunded_amount' => $rows->pluck('refunded_amount')->map(fn ($value) => (float) $value)->toArray(),
        ];
    }

    private function buildStatusBreakdown($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'status',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('status')
            ->orderByDesc('orders')
            ->get()
            ->map(function ($row) {
                return [
                    'status' => $row->status,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            })
            ->values();
    }

    private function buildPaymentBreakdown($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($row) {
                return [
                    'payment_method' => $row->payment_method ?? 'Not set',
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                    'avg_order_value' => $row->orders > 0 ? round($row->revenue / $row->orders, 2) : 0.0,
                ];
            })
            ->values();
    }

    private function buildPaymentReliability($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as orders'),
                DB::raw("SUM(CASE WHEN payment_status IN ('failed','transaction_not_matched') THEN 1 ELSE 0 END) as failed"),
                DB::raw("SUM(CASE WHEN payment_status = 'refunded' THEN 1 ELSE 0 END) as refunded")
            )
            ->groupBy('payment_method')
            ->orderByDesc('failed')
            ->get()
            ->map(function ($row) {
                $failedRate = $row->orders > 0 ? round(($row->failed / $row->orders) * 100, 2) : 0.0;
                return [
                    'payment_method' => $row->payment_method ?? 'Not set',
                    'orders' => (int) $row->orders,
                    'failed' => (int) $row->failed,
                    'refunded' => (int) $row->refunded,
                    'failed_rate' => $failedRate,
                ];
            })
            ->values();
    }

    private function buildSourceRisk($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'order_source',
                DB::raw('COUNT(*) as orders'),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
                DB::raw("SUM(CASE WHEN status IN ('returned','return') THEN 1 ELSE 0 END) as returned")
            )
            ->groupBy('order_source')
            ->orderByDesc('orders')
            ->get()
            ->map(function ($row) {
                $cancelRate = $row->orders > 0 ? round(($row->cancelled / $row->orders) * 100, 2) : 0.0;
                $returnRate = $row->orders > 0 ? round(($row->returned / $row->orders) * 100, 2) : 0.0;
                return [
                    'order_source' => $row->order_source ?? 'Unknown',
                    'orders' => (int) $row->orders,
                    'cancelled' => (int) $row->cancelled,
                    'returned' => (int) $row->returned,
                    'cancel_rate' => $cancelRate,
                    'return_rate' => $returnRate,
                ];
            })
            ->values();
    }

    private function buildCourierPerformance($ordersQuery)
    {
        $providerExpr = "JSON_UNQUOTE(JSON_EXTRACT(delivery_data, '$.courier_provider'))";

        $rows = (clone $ordersQuery)
            ->selectRaw("$providerExpr as courier_provider")
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw("SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered")
            ->selectRaw("SUM(CASE WHEN status IN (\"returned\",\"return\",\"cancelled\") THEN 1 ELSE 0 END) as exceptions")
            ->groupByRaw($providerExpr)
            ->orderByDesc('orders')
            ->get();

        return $rows->map(function ($row) {
            return [
                'courier_provider' => $row->courier_provider ?? 'Unassigned',
                'orders' => (int) $row->orders,
                'delivered' => (int) $row->delivered,
                'exceptions' => (int) $row->exceptions,
            ];
        })->values();
    }

    private function buildOrderTiming($ordersQuery)
    {
        $rows = (clone $ordersQuery)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total) as revenue'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy(DB::raw('HOUR(created_at)'))
            ->get();

        $labels = [];
        $orders = [];
        $revenue = [];
        for ($h = 0; $h < 24; $h++) {
            $labels[] = Carbon::createFromTime($h, 0)->format('g A'); // 12-hour format
            $match = $rows->firstWhere('hour', $h);
            $orders[] = $match ? (int) $match->orders : 0;
            $revenue[] = $match ? (float) $match->revenue : 0.0;
        }

        return [
            'labels' => $labels,
            'orders' => $orders,
            'revenue' => $revenue,
        ];
    }

    private function buildSourceBreakdown($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'order_source',
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('order_source')
            ->orderByDesc('orders')
            ->get()
            ->map(function ($row) {
                return [
                    'order_source' => $row->order_source ?? 'Unknown',
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            })
            ->values();
    }

    private function buildTopProducts(array $filters, Carbon $start, Carbon $end)
    {
        $query = order_item::with('product:id,title')
            ->select(
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as units'),
                DB::raw('SUM(order_items.sub_total) as revenue')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('order_items.product_id')
            ->orderByDesc('revenue')
            ->limit(8);

        $query = $this->applyOrderFilters($query, $filters);

        return $query->get()->map(function ($row) {
            return [
                'product_id' => $row->product_id,
                'product_title' => $row->product->title ?? 'Product #' . $row->product_id,
                'units' => (int) $row->units,
                'revenue' => (float) $row->revenue,
                'avg_price' => $row->units > 0 ? round($row->revenue / $row->units, 2) : 0.0,
            ];
        });
    }

    private function countUnitsSold(array $filters, Carbon $start, Carbon $end): int
    {
        $query = order_item::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end]);

        $query = $this->applyOrderFilters($query, $filters);

        return (int) $query->sum('order_items.quantity');
    }

    private function getStatusOptions(): array
    {
        return [
            'pending',
            'phone_not_rcv',
            'follow_up',
            'processing',
            'ready_for_delivery',
            'shipped',
            'delivered',
            'on_hold',
            'cancelled',
        ];
    }

    private function getDistinctValues(string $column): array
    {
        return order::query()
            ->select($column)
            ->whereNotNull($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column)
            ->toArray();
    }
}
