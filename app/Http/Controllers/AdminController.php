<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LicenseService;
use App\Services\SMSService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendSampleSMS()
    {
        // Define a sample phone number and message
        $number = "8801779542054"; // Replace with a valid number
        $message = "Your Thikana verification code is- 501598";

        // Send the SMS using the SMSService
        $response = $this->smsService->sendSMS($number, $message);

        // Return the response for testing purposes
        return response()->json(['message' => 'SMS sent successfully', 'response' => $response]);
    }


    public function getBalance()
    {
        $apiKey = setting('sms', 'api_key', '000000000000000');
        if (empty($apiKey) || $apiKey === '000000000000000') {
            return ['balance' => '0.00', 'response_code' => 200];
        }

        return \Illuminate\Support\Facades\Cache::remember('bulksms_balance_' . md5($apiKey), 300, function () use ($apiKey) {
            try {
                $url = "http://bulksmsbd.net/api/getBalanceApi";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ["api_key" => $apiKey]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

                $response = curl_exec($ch);
                curl_close($ch);

                return json_decode($response, true) ?? ['balance' => '0.00'];
            } catch (\Throwable $e) {
                return ['balance' => '0.00'];
            }
        });
    }

    // Dashboard Page

    public function admin(Request $request, LicenseService $licenseService)
    {
        $licenseStatus = $licenseService->getLicenseStatus();

        if (!($licenseStatus['valid'] ?? false)) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $licenseStatus['message'] ?? 'License required',
                    'redirect' => route('admin.verification.index'),
                ], 403);
            }

            return view('admin.dashboard');
        }

        // Get date range from request or set default
        $dateRange = $request->get('date_range', 'last_30_days');
        $customStartDate = $request->get('start_date', '');
        $customEndDate = $request->get('end_date', '');

        // Calculate date range based on selection
        $dates = $this->getDateRange($dateRange, $customStartDate, $customEndDate);
        $startDate = $dates['start'];
        $endDate = $dates['end'];

        $cacheKey = "admin_dashboard_data_v3_" . md5($dateRange . '_' . $startDate->toDateTimeString() . '_' . $endDate->toDateTimeString());

        $dashboardData = \Illuminate\Support\Facades\Cache::remember($cacheKey, 30, function () use ($startDate, $endDate, $dateRange) {
            // 1. Basic metrics with quick aggregates
            $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            $NewtotalOrders = Order::where('status', 'pending')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
            $balance = $this->getBalance();
            $totalSales = (float) Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');

            // 2. Chart data
            $chartData = $this->getChartData($startDate, $endDate, $dateRange);
            $labels = $chartData['labels'];
            $data = $chartData['data'];

            // 3. Order status statistics for dashboard
            $statuses = [
                'pending',
                'phone_not_rcv',
                'follow_up',
                'processing',
                'ready_for_delivery',
                'delivered',
                'on_hold',
                'shipped',
                'cancelled'
            ];

            $periods = $this->getPeriodsForRange($startDate, $endDate, $dateRange);
            $orderStatusMonthlyCounts = [];
            $orderStatusMonthlyTrends = [];

            // Group status counts efficiently using a single query
            $rawStatusCounts = Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, created_at')
                ->get();

            foreach ($statuses as $status) {
                $statusRows = $rawStatusCounts->where('status', $status);
                $counts = [];
                foreach ($periods as $period) {
                    $counts[] = $statusRows->whereBetween('created_at', [$period['start'], $period['end']])->count();
                }
                $orderStatusMonthlyCounts[$status] = $counts;

                $trends = [];
                for ($i = 1; $i < count($counts); $i++) {
                    $prev = $counts[$i - 1];
                    $curr = $counts[$i];
                    if ($prev > 0) {
                        $trends[] = round((($curr - $prev) / $prev) * 100, 2);
                    } else {
                        $trends[] = $curr > 0 ? 100 : 0;
                    }
                }
                $orderStatusMonthlyTrends[$status] = $trends;
            }

            $monthLabels = collect($periods)->map(function ($period) use ($dateRange) {
                return $this->getPeriodLabel($period, $dateRange);
            })->toArray();

            // 4. User Registration Trend
            $userTrendData = [];
            $userTrendLabels = [];
            $diffInDays = $startDate->diffInDays($endDate);
            $interval = max(1, round($diffInDays / 6));
            $rawUsers = User::whereBetween('created_at', [$startDate, $endDate])->select('created_at')->get();
            for ($i = 0; $i <= 6; $i++) {
                $pStart = (clone $startDate)->addDays($i * $interval)->startOfDay();
                $pEnd = (clone $startDate)->addDays(($i + 1) * $interval)->endOfDay();
                if ($pEnd->gt($endDate)) {
                    $pEnd = $endDate;
                }
                $userTrendLabels[] = $pStart->format($diffInDays <= 7 ? 'D' : ($diffInDays <= 60 ? 'd M' : 'M Y'));
                $userTrendData[] = $rawUsers->whereBetween('created_at', [$pStart, $pEnd])->count();
            }

            // 5. Category Matrix
            $topCategories = \App\Models\order_item::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->select('product_categories.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'))
                ->groupBy('product_categories.name')
                ->orderByDesc('total_qty')
                ->limit(5)
                ->get();
            
            $categoryNames = $topCategories->pluck('name')->toArray();
            $categoryCounts = $topCategories->pluck('total_qty')->map(fn($v) => (int)$v)->toArray();
            if (empty($categoryNames)) {
                $categoryNames = ['Software', 'Hardware', 'Services', 'Consulting', 'Licensing'];
                $categoryCounts = [0, 0, 0, 0, 0];
            }

            // 6. Response time and customer retention
            $avgTimeMinutes = Order::where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_time')
                ->value('avg_time');
            $responseTimeText = $avgTimeMinutes ? (round($avgTimeMinutes / 60, 1) . ' hrs') : '2.4 hrs';

            $totalCustomers = Order::whereBetween('created_at', [$startDate, $endDate])->distinct('phone')->count('phone');
            $returningCustomers = \Illuminate\Support\Facades\DB::table('orders')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select('phone', \Illuminate\Support\Facades\DB::raw('COUNT(*) as order_count'))
                ->groupBy('phone')
                ->having('order_count', '>', 1)
                ->get()
                ->count();
            $retentionRate = ($totalCustomers > 0) ? round(($returningCustomers / $totalCustomers) * 100, 1) : 84.2;

            $lowStockCount = \App\Models\Product::where('manage_stock', true)
                ->whereRaw('quantity <= low_stock_threshold')
                ->where('stock_status', 'in_stock')
                ->count();

            return compact(
                'totalOrders',
                'totalUsers',
                'balance',
                'totalSales',
                'labels',
                'data',
                'orderStatusMonthlyCounts',
                'orderStatusMonthlyTrends',
                'monthLabels',
                'statuses',
                'NewtotalOrders',
                'userTrendData',
                'userTrendLabels',
                'categoryNames',
                'categoryCounts',
                'responseTimeText',
                'retentionRate',
                'lowStockCount'
            );
        });

        extract($dashboardData);
        $selectedDateRange = $dateRange;

        // If this is an AJAX request, return JSON data
        if ($request->ajax()) {
            return response()->json([
                'totalOrders' => $totalOrders,
                'totalUsers' => $totalUsers,
                'totalSales' => number_format($totalSales, 2),
                'NewtotalOrders' => $NewtotalOrders,
                'labels' => $labels,
                'data' => $data,
                'orderStatusMonthlyCounts' => $orderStatusMonthlyCounts,
                'orderStatusMonthlyTrends' => $orderStatusMonthlyTrends,
                'monthLabels' => $monthLabels,
                'dateRange' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                    'label' => $this->getDateRangeLabel($dateRange, $startDate, $endDate)
                ]
            ]);
        }

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalUsers',
            'balance',
            'totalSales',
            'labels',
            'data',
            'orderStatusMonthlyCounts',
            'orderStatusMonthlyTrends',
            'monthLabels',
            'statuses',
            'NewtotalOrders',
            'selectedDateRange',
            'customStartDate',
            'customEndDate',
            'userTrendData',
            'userTrendLabels',
            'categoryNames',
            'categoryCounts',
            'responseTimeText',
            'retentionRate',
            'lowStockCount'
        ));
    }

    /**
     * Calculate date range based on selection
     */
    private function getDateRange($dateRange, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();
        
        switch ($dateRange) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'yesterday':
                return [
                    'start' => $now->copy()->subDay()->startOfDay(),
                    'end' => $now->copy()->subDay()->endOfDay()
                ];
            case 'last_7_days':
                return [
                    'start' => $now->copy()->subDays(6)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'last_15_days':
                return [
                    'start' => $now->copy()->subDays(14)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'last_30_days':
                return [
                    'start' => $now->copy()->subDays(29)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'last_month':
                return [
                    'start' => $now->copy()->subMonth()->startOfMonth(),
                    'end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'this_year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'custom':
                return [
                    'start' => $startDate ? Carbon::parse($startDate)->startOfDay() : $now->copy()->subDays(29)->startOfDay(),
                    'end' => $endDate ? Carbon::parse($endDate)->endOfDay() : $now->copy()->endOfDay()
                ];
            default:
                return [
                    'start' => $now->copy()->subDays(29)->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
        }
    }

    /**
     * Get chart data based on date range
     */
    private function getChartData($startDate, $endDate, $dateRange)
    {
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 7) {
            // Daily data for week or less
            $orders = Order::selectRaw("DATE(created_at) as date, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $labels = $orders->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('M j');
            })->toArray();
            $data = $orders->pluck('total')->toArray();
        } elseif ($diffInDays <= 31) {
            // Daily data for month or less
            $orders = Order::selectRaw("DATE(created_at) as date, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            $labels = $orders->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('M j');
            })->toArray();
            $data = $orders->pluck('total')->toArray();
        } else {
            // Monthly data for longer periods
            $orders = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            
            $labels = $orders->pluck('month')->map(function($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M Y');
            })->toArray();
            $data = $orders->pluck('total')->toArray();
        }
        
        return compact('labels', 'data');
    }

    /**
     * Get periods for status statistics
     */
    private function getPeriodsForRange($startDate, $endDate, $dateRange)
    {
        $periods = [];
        $diffInDays = $startDate->diffInDays($endDate);
        
        if ($diffInDays <= 7) {
            // Daily periods
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $periods[] = [
                    'start' => $current->copy()->startOfDay(),
                    'end' => $current->copy()->endOfDay()
                ];
                $current->addDay();
            }
        } elseif ($diffInDays <= 31) {
            // Weekly periods
            $current = $startDate->copy()->startOfWeek();
            while ($current <= $endDate) {
                $weekEnd = $current->copy()->endOfWeek();
                if ($weekEnd > $endDate) $weekEnd = $endDate->copy();
                
                $periods[] = [
                    'start' => $current->copy(),
                    'end' => $weekEnd
                ];
                $current->addWeek();
            }
        } else {
            // Monthly periods
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $monthEnd = $current->copy()->endOfMonth();
                if ($monthEnd > $endDate) $monthEnd = $endDate->copy();
                
                $periods[] = [
                    'start' => $current->copy(),
                    'end' => $monthEnd
                ];
                $current->addMonth();
            }
        }
        
        return $periods;
    }

    /**
     * Get period label
     */
    private function getPeriodLabel($period, $dateRange)
    {
        $diffInDays = $period['start']->diffInDays($period['end']);
        
        if ($diffInDays == 0) {
            return $period['start']->format('M j');
        } elseif ($diffInDays <= 7) {
            return $period['start']->format('M j') . ' - ' . $period['end']->format('M j');
        } else {
            return $period['start']->format('M Y');
        }
    }

    /**
     * Get date range label for display
     */
    private function getDateRangeLabel($dateRange, $startDate, $endDate)
    {
        switch ($dateRange) {
            case 'today': return 'Today';
            case 'yesterday': return 'Yesterday';
            case 'last_7_days': return 'Last 7 Days';
            case 'last_15_days': return 'Last 15 Days';
            case 'last_30_days': return 'Last 30 Days';
            case 'this_week': return 'This Week';
            case 'this_month': return 'This Month';
            case 'last_month': return 'Last Month';
            case 'this_year': return 'This Year';
            case 'custom': return $startDate->format('M j, Y') . ' - ' . $endDate->format('M j, Y');
            default: return 'Last 30 Days';
        }
    }


    // Enhanced admin() method for your controller
    // public function admin()
    // {
    //     $totalOrders = Order::count();
    //     $totalUsers = User::count();
    //     $orderslist = Order::get();
    //     $balance = $this->getBalance();
    //     $totalSales = Order::sum('total');

    //     // For chart (existing)
    //     $orders = Order::selectRaw("MONTHNAME(created_at) as month, COUNT(*) as total, MONTH(created_at) as month_number")
    //         ->groupBy('month', 'month_number')
    //         ->orderBy('month_number')
    //         ->get();

    //     $labels = $orders->pluck('month')->toArray();
    //     $data = $orders->pluck('total')->toArray();

    //     // --- Enhanced Order Status Statistics ---
    //     $statuses = [
    //         'pending',
    //         'phone_not_rcv',
    //         'follow_up',
    //         'processing',
    //         'ready_for_delivery',
    //         'delivered',
    //         'on_hold',
    //         'shipped',
    //         'cancelled'
    //     ];

    //     // Status configuration for dynamic behavior
    //     $statusConfig = [
    //         'pending' => [
    //             'label' => 'Pending',
    //             'icon' => 'fas fa-clock',
    //             'isNegative' => false,
    //             'color' => 'warning'
    //         ],
    //         'phone_not_rcv' => [
    //             'label' => 'Call Not Received',
    //             'icon' => 'fas fa-phone-slash',
    //             'isNegative' => true,
    //             'color' => 'warning'
    //         ],
    //         'follow_up' => [
    //             'label' => 'Follow Up',
    //             'icon' => 'fas fa-redo-alt',
    //             'isNegative' => false,
    //             'color' => 'info'
    //         ],
    //         'processing' => [
    //             'label' => 'Processing',
    //             'icon' => 'fas fa-cogs',
    //             'isNegative' => false,
    //             'color' => 'primary'
    //         ],
    //         'ready_for_delivery' => [
    //             'label' => 'Ready For Delivery',
    //             'icon' => 'fas fa-box-open',
    //             'isNegative' => false,
    //             'color' => 'success'
    //         ],
    //         'delivered' => [
    //             'label' => 'Delivered',
    //             'icon' => 'fas fa-check-circle',
    //             'isNegative' => false,
    //             'color' => 'success'
    //         ],
    //         'on_hold' => [
    //             'label' => 'On Hold',
    //             'icon' => 'fas fa-pause-circle',
    //             'isNegative' => true,
    //             'color' => 'secondary'
    //         ],
    //         'shipped' => [
    //             'label' => 'Shipped',
    //             'icon' => 'fas fa-shipping-fast',
    //             'isNegative' => false,
    //             'color' => 'info'
    //         ],
    //         'cancelled' => [
    //             'label' => 'Cancelled',
    //             'icon' => 'fas fa-times-circle',
    //             'isNegative' => true,
    //             'color' => 'danger'
    //         ]
    //     ];

    //     // Get last 6 months (including current)
    //     $months = collect();
    //     for ($i = 5; $i >= 0; $i--) {
    //         $months->push(now()->copy()->subMonths($i)->format('Y-m'));
    //     }

    //     $orderStatusMonthlyCounts = [];
    //     $orderStatusMonthlyTrends = [];

    //     foreach ($statuses as $status) {
    //         $counts = [];

    //         // Get counts for each month
    //         foreach ($months as $month) {
    //             [$year, $mon] = explode('-', $month);
    //             $counts[] = Order::where('status', $status)
    //                 ->whereYear('created_at', $year)
    //                 ->whereMonth('created_at', $mon)
    //                 ->count();
    //         }

    //         $orderStatusMonthlyCounts[$status] = $counts;

    //         // Calculate month-over-month trends
    //         $trends = [];
    //         for ($i = 1; $i < count($counts); $i++) {
    //             $prev = $counts[$i - 1];
    //             $curr = $counts[$i];

    //             if ($prev > 0) {
    //                 $trends[] = round((($curr - $prev) / $prev) * 100, 2);
    //             } else {
    //                 $trends[] = $curr > 0 ? 100 : 0;
    //             }
    //         }

    //         $orderStatusMonthlyTrends[$status] = $trends;
    //     }

    //     // For labels (e.g., ['Jan 2024', 'Feb 2024', ...])
    //     $monthLabels = $months->map(function ($m) {
    //         return \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y');
    //     })->toArray();

    //     // Calculate summary statistics
    //     $totalCurrentMonth = array_sum(array_column($orderStatusMonthlyCounts, count($orderStatusMonthlyCounts['pending']) - 1));
    //     $totalPreviousMonth = array_sum(array_column($orderStatusMonthlyCounts, count($orderStatusMonthlyCounts['pending']) - 2));

    //     $overallTrend = $totalPreviousMonth > 0
    //         ? round((($totalCurrentMonth - $totalPreviousMonth) / $totalPreviousMonth) * 100, 2)
    //         : ($totalCurrentMonth > 0 ? 100 : 0);

    //     // Identify problematic statuses (negative statuses with upward trends)
    //     $problemStatuses = [];
    //     foreach ($statuses as $status) {
    //         if ($statusConfig[$status]['isNegative']) {
    //             $latestTrend = end($orderStatusMonthlyTrends[$status]);
    //             if ($latestTrend > 10) { // More than 10% increase in negative status
    //                 $problemStatuses[] = [
    //                     'status' => $status,
    //                     'label' => $statusConfig[$status]['label'],
    //                     'trend' => $latestTrend,
    //                     'count' => end($orderStatusMonthlyCounts[$status])
    //                 ];
    //             }
    //         }
    //     }

    //     return view('admin.dashboard', compact(
    //         'totalOrders',
    //         'totalUsers',
    //         'orderslist',
    //         'balance',
    //         'totalSales',
    //         'labels',
    //         'data',
    //         'orderStatusMonthlyCounts',
    //         'orderStatusMonthlyTrends',
    //         'monthLabels',
    //         'statuses',
    //         'statusConfig',
    //         'overallTrend',
    //         'problemStatuses'
    //     ));
    // }

    // Optional: Helper method to get status insights
    // public function getStatusInsights()
    // {
    //     $insights = [];

    //     // Get current month data
    //     $currentMonth = now()->format('Y-m');
    //     [$year, $month] = explode('-', $currentMonth);

    //     $statusCounts = Order::selectRaw('status, COUNT(*) as count')
    //         ->whereYear('created_at', $year)
    //         ->whereMonth('created_at', $month)
    //         ->groupBy('status')
    //         ->pluck('count', 'status');

    //     $totalOrders = $statusCounts->sum();

    //     if ($totalOrders > 0) {
    //         foreach ($statusCounts as $status => $count) {
    //             $percentage = round(($count / $totalOrders) * 100, 2);
    //             $insights[$status] = [
    //                 'count' => $count,
    //                 'percentage' => $percentage,
    //                 'status' => $status
    //             ];
    //         }
    //     }

    //     return $insights;
    // }


    // Dashboard Profile Page
    public function profile()
    {
        return view('admin.profile');
    }

    // Create User Page
    public function create()
    {
        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();
        $allRoles = \Spatie\Permission\Models\Role::all();
        if (!$isSuperAdmin) {
            $allRoles = $allRoles->filter(function($role) {
                $r = strtolower($role->name);
                return !str_contains($r, 'super') && !str_contains($r, 'admin');
            });
        }
        return view('admin.users.create', compact('allRoles', 'isSuperAdmin'));
    }

    // Store User
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'otp_verified' => 'nullable|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'upazila' => $request->upazila,
            'city' => $request->city,
            'otp_verified' => $request->has('otp_verified') ? 1 : 0,
            'created_by' => auth()->id(),
        ]);

        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();

        $selectedRoles = [];
        if ($request->has('roles') && is_array($request->roles)) {
            $selectedRoles = $request->roles;
        } elseif ($request->filled('role')) {
            $selectedRoles = [$request->role];
        }

        if (!$isSuperAdmin) {
            // Non-superadmin cannot assign admin or super admin roles
            $selectedRoles = array_filter($selectedRoles, function($roleName) {
                $r = strtolower($roleName);
                return !str_contains($r, 'super') && !str_contains($r, 'admin');
            });
        }

        if (empty($selectedRoles)) {
            $selectedRoles = ['customer'];
        }

        if (method_exists($user, 'syncRoles')) {
            try {
                $user->syncRoles($selectedRoles);
            } catch (\Throwable $e) {
                \Log::warning("Could not sync roles: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    // Edit User
    public function usersedit(Request $request)
    {
        $user = User::findOrFail($request->id);
        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();
        $allRoles = \Spatie\Permission\Models\Role::all();
        if (!$isSuperAdmin) {
            $allRoles = $allRoles->filter(function($role) {
                $r = strtolower($role->name);
                return !str_contains($r, 'super') && !str_contains($r, 'admin');
            });
        }

        // Get user's current assigned template
        $userTemplateId = $user->template_id;
        if (empty($userTemplateId)) {
            $userTemplateId = \App\Models\SiteSetting::get('vendor_' . $user->id . '_homepage', 'template_id');
        }
        if (empty($userTemplateId)) {
            // Check if user is associated with a tenant
            $subdomain = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)$user->name)));
            $tenant = \App\Models\SaaSTenant::where('subdomain', $subdomain)->orWhere('name', $user->name)->first();
            if ($tenant) {
                $tDbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                try {
                    config(['database.connections.tenant_tpl_temp' => array_merge(
                        config('database.connections.mysql'),
                        ['database' => $tDbName]
                    )]);
                    \Illuminate\Support\Facades\DB::purge('tenant_tpl_temp');
                    $tTpl = \Illuminate\Support\Facades\DB::connection('tenant_tpl_temp')
                        ->table('site_settings')
                        ->where('group', 'homepage')
                        ->where('key', 'template_id')
                        ->value('value');
                    if ($tTpl) {
                        $userTemplateId = $tTpl;
                    }
                } catch (\Throwable $ex) {}
            }
        }
        if (empty($userTemplateId)) {
            $userTemplateId = setting('homepage', 'template_id', '1');
        }

        return view('admin.users.edit', compact('user', 'allRoles', 'isSuperAdmin', 'userTemplateId'));
    }

    // Update User
    public function usersupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();

        // If target user is a super admin and current user is not super admin, forbid modification
        if (!$isSuperAdmin && $user->isSuperAdmin()) {
            return redirect()->route('admin.users')
                ->withErrors(['error' => 'You do not have permission to modify a Super Administrator account.']);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'otp_verified' => 'nullable|boolean',
            'template_id' => 'nullable|string|in:1,2,3,4,5,6,7,8,9,10',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
            'role' => 'nullable|string|exists:roles,name',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'upazila' => $request->upazila,
            'city' => $request->city,
            'otp_verified' => $request->has('otp_verified') ? 1 : 0,
        ];

        if ($isSuperAdmin && $request->filled('template_id')) {
            $userData['template_id'] = (string)$request->template_id;
        }

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Sync template to vendor and tenant DB if super admin modified it
        if ($isSuperAdmin && $request->filled('template_id')) {
            $tplId = (string)$request->template_id;
            \App\Models\SiteSetting::set('vendor_' . $user->id . '_homepage', 'template_id', $tplId);

            if ($user->id === 1 || $user->isSuperAdmin()) {
                \App\Models\SiteSetting::set('homepage', 'template_id', $tplId);
            }

            // Sync with tenant DB if exists
            $subdomain = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)$user->name)));
            $tenant = \App\Models\SaaSTenant::where('subdomain', $subdomain)->orWhere('name', $user->name)->first();
            if ($tenant) {
                $tDbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                try {
                    config(['database.connections.tenant_tpl_temp' => array_merge(
                        config('database.connections.mysql'),
                        ['database' => $tDbName]
                    )]);
                    \Illuminate\Support\Facades\DB::purge('tenant_tpl_temp');
                    \Illuminate\Support\Facades\DB::connection('tenant_tpl_temp')
                        ->table('site_settings')
                        ->updateOrInsert(
                            ['group' => 'homepage', 'key' => 'template_id'],
                            ['value' => $tplId, 'updated_at' => now()]
                        );
                } catch (\Throwable $ex) {
                    \Log::warning("Failed to update tenant DB template: " . $ex->getMessage());
                }
            }

            \App\Services\SettingsService::clearCache();
        }

        if (method_exists($user, 'syncRoles')) {
            try {
                $requestedRoles = [];
                if ($request->has('roles') && is_array($request->roles)) {
                    $requestedRoles = $request->roles;
                } elseif ($request->filled('role')) {
                    $requestedRoles = [$request->role];
                }

                if (!$isSuperAdmin) {
                    // Filter out any super or admin roles from requested
                    $requestedRoles = array_filter($requestedRoles, function($rName) {
                        $r = strtolower($rName);
                        return !str_contains($r, 'super') && !str_contains($r, 'admin');
                    });
                    
                    // If target user already has administrative roles that current admin cannot touch, preserve them
                    $existingAdminRoles = $user->getRoleNames()->filter(function($rName) {
                        $r = strtolower($rName);
                        return str_contains($r, 'super') || str_contains($r, 'admin');
                    })->toArray();
                    
                    $finalRoles = array_unique(array_merge($requestedRoles, $existingAdminRoles));
                    $user->syncRoles($finalRoles);
                } else {
                    $user->syncRoles($requestedRoles);
                }
            } catch (\Throwable $e) {
                \Log::warning("Could not sync roles: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'User profile & website template updated successfully!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Delete User
    public function usersdestroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully!');
    }

    // Bulk Delete Users
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->input('user_ids');
        $deletedCount = User::whereIn('id', $userIds)->delete();

        return redirect()->route('admin.users')
            ->with('success', "Successfully deleted {$deletedCount} user(s)!");
    }

    // Dashboard Users Page
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Dashboard product Create Page


    // Dashboard product Create Page


    public function checkemail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }

    public function markNotificationsRead(Request $request)
    {
        $userId = auth()->id();
        if ($userId) {
            \Illuminate\Support\Facades\Cache::forever("admin_notif_last_read_{$userId}", now());
            \Illuminate\Support\Facades\Cache::forget("admin_layout_stats_v3_{$userId}_sa");
            \Illuminate\Support\Facades\Cache::forget("admin_layout_stats_v3_{$userId}_admin");
        }

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }
}
