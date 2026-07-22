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
        $url = "http://bulksmsbd.net/api/getBalanceApi";
        $api_key = "000000000000000"; // Replace with your actual API key

        $data = [
            "api_key" => $api_key
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true); // Assuming the API returns JSON data
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
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Calculate date range based on selection
        $dates = $this->getDateRange($dateRange, $startDate, $endDate);
        $startDate = $dates['start'];
        $endDate = $dates['end'];

        // Get filtered data based on date range
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $NewtotalOrders = Order::whereIn('status', ['pending'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $orderslist = Order::whereBetween('created_at', [$startDate, $endDate])->get();
        $balance = $this->getBalance();
        $totalSales = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total');

        // For chart - adjust based on date range
        $chartData = $this->getChartData($startDate, $endDate, $dateRange);
        $labels = $chartData['labels'];
        $data = $chartData['data'];

        // --- Order status statistics for dashboard ---
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

        // Get periods for status statistics
        $periods = $this->getPeriodsForRange($startDate, $endDate, $dateRange);
        $orderStatusMonthlyCounts = [];
        $orderStatusMonthlyTrends = [];

        foreach ($statuses as $status) {
            $counts = [];
            foreach ($periods as $period) {
                $counts[] = Order::where('status', $status)
                    ->whereBetween('created_at', [$period['start'], $period['end']])
                    ->count();
            }
            $orderStatusMonthlyCounts[$status] = $counts;

            // Calculate period-over-period trends
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

        // Generate period labels
        $monthLabels = collect($periods)->map(function ($period) use ($dateRange) {
            return $this->getPeriodLabel($period, $dateRange);
        })->toArray();

        // Additional data for the view
        $selectedDateRange = $dateRange;
        $customStartDate = $request->get('start_date', '');
        $customEndDate = $request->get('end_date', '');

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
            'orderslist',
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
            'customEndDate'
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
        return view('admin.users.create');
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
            'otp_verified' => 'boolean',
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
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully!');
    }

    // Edit User
    public function usersedit(Request $request)
    {
        $user = User::findOrFail($request->id);
        return view('admin.users.edit', compact('user'));
    }

    // Update User
    public function usersupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'otp_verified' => 'boolean',
        ]);

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

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if (method_exists($user, 'syncRoles')) {
            try {
                if ($request->has('roles') && is_array($request->roles)) {
                    $user->syncRoles($request->roles);
                } else if ($request->filled('role')) {
                    $user->syncRoles([$request->role]);
                }
            } catch (\Throwable $e) {
                \Log::warning("Could not sync roles: " . $e->getMessage());
            }
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully!');
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
}
