<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.customers', [
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

        $ordersQuery = order::query()->whereBetween('orders.created_at', [$range['start'], $range['end']]);
        $ordersQuery = $this->applyOrderFilters($ordersQuery, $filters);

        return response()->json([
            'range' => [
                'start' => $range['start']->toDateString(),
                'end' => $range['end']->toDateString(),
                'label' => $range['label'],
            ],
            'summary' => $this->buildSummary($ordersQuery, $range),
            'top_customers' => $this->topCustomers($ordersQuery),
            'city_mix' => $this->cityMix($ordersQuery),
            'source_mix' => $this->sourceMix($ordersQuery),
            'payment_mix' => $this->paymentMix($ordersQuery),
            'segments' => $this->orderSegments($ordersQuery),
            'districts' => $this->districtHeatmap($ordersQuery),
        ]);
    }

    private function buildSummary($ordersQuery, array $range): array
    {
        $orders = (clone $ordersQuery)->whereNotNull('phone');

        $uniqueCustomers = (clone $orders)->select('phone')->groupBy('phone')->count();
        $totalOrders = (clone $orders)->count();
        $revenue = (clone $orders)->sum('total');

        // New customers: first ever order date falls within range
        $firstOrders = order::select('phone', DB::raw('MIN(created_at) as first_order_at'))
            ->whereNotNull('phone')
            ->groupBy('phone')
            ->havingBetween('first_order_at', [$range['start'], $range['end']])
            ->pluck('phone')
            ->toArray();

        $newCustomers = 0;
        if (!empty($firstOrders)) {
            $newCustomers = (clone $orders)->whereIn('phone', $firstOrders)->select('phone')->groupBy('phone')->count();
        }

        $repeatCustomersData = (clone $orders)
            ->select('phone', DB::raw('COUNT(*) as order_count'))
            ->groupBy('phone')
            ->get();

        $repeatCustomers = $repeatCustomersData->where('order_count', '>', 1)->count();
        $repeatRate = $uniqueCustomers > 0 ? round(($repeatCustomers / $uniqueCustomers) * 100, 2) : 0.0;
        $avgOrdersPerCustomer = $uniqueCustomers > 0 ? round($totalOrders / $uniqueCustomers, 2) : 0.0;
        $avgRevenuePerCustomer = $uniqueCustomers > 0 ? round($revenue / $uniqueCustomers, 2) : 0.0;

        return [
            'unique_customers' => $uniqueCustomers,
            'new_customers' => $newCustomers,
            'repeat_customers' => $repeatCustomers,
            'repeat_rate' => $repeatRate,
            'orders' => $totalOrders,
            'revenue' => (float) $revenue,
            'avg_orders_per_customer' => $avgOrdersPerCustomer,
            'avg_revenue_per_customer' => $avgRevenuePerCustomer,
        ];
    }

    private function topCustomers($ordersQuery)
    {
        return (clone $ordersQuery)
            ->whereNotNull('phone')
            ->select(
                'phone',
                DB::raw('MAX(name) as name'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('MAX(created_at) as last_order')
            )
            ->groupBy('phone')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->name ?? 'Unknown',
                    'phone' => $row->phone,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                    'last_order' => Carbon::parse($row->last_order)->toDateString(),
                ];
            });
    }

    private function cityMix($ordersQuery)
    {
        return (clone $ordersQuery)
            ->whereNotNull('city')
            ->select(
                'city',
                DB::raw('COUNT(DISTINCT phone) as customers'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('city')
            ->orderByDesc('orders')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'city' => $row->city,
                    'customers' => (int) $row->customers,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            });
    }

    private function sourceMix($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'order_source',
                DB::raw('COUNT(DISTINCT phone) as customers'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('order_source')
            ->orderByDesc('orders')
            ->get()
            ->map(function ($row) {
                return [
                    'order_source' => $row->order_source ?? 'Unknown',
                    'customers' => (int) $row->customers,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            });
    }

    private function paymentMix($ordersQuery)
    {
        return (clone $ordersQuery)
            ->select(
                'payment_method',
                DB::raw('COUNT(DISTINCT phone) as customers'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($row) {
                return [
                    'payment_method' => $row->payment_method ?? 'Not set',
                    'customers' => (int) $row->customers,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            });
    }

    private function districtHeatmap($ordersQuery)
    {
        return (clone $ordersQuery)
            ->whereNotNull('city')
            ->select(
                'city',
                DB::raw('COUNT(DISTINCT phone) as customers'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('city')
            ->orderByDesc('orders')
            ->limit(20)
            ->get()
            ->map(function ($row) {
                return [
                    'district' => $row->city,
                    'customers' => (int) $row->customers,
                    'orders' => (int) $row->orders,
                    'revenue' => (float) $row->revenue,
                ];
            });
    }

    private function orderSegments($ordersQuery)
    {
        $buckets = [
            '1' => 0,
            '2-3' => 0,
            '4-5' => 0,
            '6+' => 0,
        ];

        $counts = (clone $ordersQuery)
            ->whereNotNull('phone')
            ->select('phone', DB::raw('COUNT(*) as order_count'))
            ->groupBy('phone')
            ->get();

        foreach ($counts as $row) {
            $c = $row->order_count;
            if ($c == 1) {
                $buckets['1']++;
            } elseif ($c >= 2 && $c <= 3) {
                $buckets['2-3']++;
            } elseif ($c >= 4 && $c <= 5) {
                $buckets['4-5']++;
            } else {
                $buckets['6+']++;
            }
        }

        return $buckets;
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
            default:
                $start = $now->copy()->subDays(29)->startOfDay();
                $end = $now->copy()->endOfDay();
                $label = 'Last 30 days';
                break;
        }

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

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
