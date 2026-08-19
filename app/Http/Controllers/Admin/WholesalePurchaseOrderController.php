<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WholesalePurchaseOrder;
use App\Models\SaaSTenant;
use App\Models\Product;
use App\Models\User;
use App\Services\GlobalProductService;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WholesalePurchaseOrderController extends Controller
{
    protected GlobalProductService $globalProductService;

    public function __construct(GlobalProductService $globalProductService)
    {
        $this->globalProductService = $globalProductService;
    }

    /**
     * Display listing of wholesale purchase orders.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user && (
            $user->hasRole('super_admin') || 
            $user->hasRole('super admin') || 
            ($user->role ?? '') === 'super_admin'
        );

        $currentSubdomain = request()->route('subdomain') 
            ?? session('current_subdomain') 
            ?? ($request->attributes->get('tenant') ? $request->attributes->get('tenant')->subdomain : null);

        $tab = $request->input('tab', $isSuperAdmin ? 'all' : 'purchases');
        if (!$isSuperAdmin && !in_array($tab, ['purchases', 'sales'])) {
            $tab = 'purchases';
        }

        $query = WholesalePurchaseOrder::query()->orderByDesc('created_at');

        // Scoping
        if (!$isSuperAdmin) {
            if ($tab === 'sales') {
                $query->where('payment_status', 'approved')
                    ->where(function($q) use ($user, $currentSubdomain) {
                        if ($user) {
                            $q->where('seller_admin_id', $user->id)
                              ->orWhere('seller_admin_name', 'like', "%{$user->email}%")
                              ->orWhere('seller_admin_name', 'like', "%{$user->name}%");
                        }
                        if ($currentSubdomain) {
                            $q->orWhere('seller_subdomain', $currentSubdomain);
                        }
                    });
            } else {
                // Default: purchases
                $query->where(function($q) use ($user, $currentSubdomain) {
                    if ($user) {
                        $q->where('buyer_admin_id', $user->id)
                          ->orWhere('buyer_admin_email', $user->email);
                    }
                    if ($currentSubdomain) {
                        $q->orWhere('buyer_subdomain', $currentSubdomain);
                    }
                });
            }
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Fulfillment Filter
        if ($request->filled('fulfillment')) {
            $query->where('fulfillment_status', $request->fulfillment);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('product_title', 'like', "%{$search}%")
                  ->orWhere('buyer_admin_name', 'like', "%{$search}%")
                  ->orWhere('seller_subdomain', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Statistics Scoped by Tab
        if ($tab === 'sales') {
            $salesBaseQuery = WholesalePurchaseOrder::where('payment_status', 'approved');
            if (!$isSuperAdmin) {
                $salesBaseQuery->where(function($q) use ($user, $currentSubdomain) {
                    if ($user) {
                        $q->where('seller_admin_id', $user->id)
                          ->orWhere('seller_admin_name', 'like', "%{$user->email}%")
                          ->orWhere('seller_admin_name', 'like', "%{$user->name}%");
                    }
                    if ($currentSubdomain) $q->orWhere('seller_subdomain', $currentSubdomain);
                });
            }

            $pendingCount = (clone $salesBaseQuery)->where('fulfillment_status', 'processing')->count();
            $approvedCount = (clone $salesBaseQuery)->whereIn('fulfillment_status', ['shipped', 'delivered'])->count();
            $totalVolume = (clone $salesBaseQuery)->sum($isSuperAdmin ? 'total_amount' : 'seller_earnings');
            $totalProfit = (clone $salesBaseQuery)->sum('platform_commission');
        } elseif ($tab === 'purchases') {
            $purchasesBaseQuery = WholesalePurchaseOrder::query();
            if (!$isSuperAdmin) {
                $purchasesBaseQuery->where(function($q) use ($user, $currentSubdomain) {
                    if ($user) {
                        $q->where('buyer_admin_id', $user->id)
                          ->orWhere('buyer_admin_email', $user->email);
                    }
                    if ($currentSubdomain) $q->orWhere('buyer_subdomain', $currentSubdomain);
                });
            }

            $pendingCount = (clone $purchasesBaseQuery)->where('payment_status', 'pending')->count();
            $approvedCount = (clone $purchasesBaseQuery)->where('payment_status', 'approved')->count();
            $totalVolume = (clone $purchasesBaseQuery)->where('payment_status', 'approved')->sum('total_amount');
            $totalProfit = (clone $purchasesBaseQuery)->where('payment_status', 'approved')->sum('platform_commission');
        } else {
            // Super Admin All Tab
            $pendingCount = WholesalePurchaseOrder::where('payment_status', 'pending')->count();
            $approvedCount = WholesalePurchaseOrder::where('payment_status', 'approved')->count();
            $totalVolume = WholesalePurchaseOrder::where('payment_status', 'approved')->sum('total_amount');
            $totalProfit = WholesalePurchaseOrder::where('payment_status', 'approved')->sum('platform_commission');
        }

        $myPurchasesCount = 0;
        $mySalesCount = 0;
        if ($user) {
            $myPurchasesCount = WholesalePurchaseOrder::where(function($q) use ($user, $currentSubdomain) {
                $q->where('buyer_admin_id', $user->id)->orWhere('buyer_admin_email', $user->email);
                if ($currentSubdomain) $q->orWhere('buyer_subdomain', $currentSubdomain);
            })->count();

            $mySalesCount = WholesalePurchaseOrder::where('payment_status', 'approved')
                ->where(function($q) use ($user, $currentSubdomain) {
                    $q->where('seller_admin_id', $user->id)
                      ->orWhere('seller_admin_name', 'like', "%{$user->email}%")
                      ->orWhere('seller_admin_name', 'like', "%{$user->name}%");
                    if ($currentSubdomain) $q->orWhere('seller_subdomain', $currentSubdomain);
                })->count();
        }

        return view('admin.wholesale_orders.index', compact(
            'orders',
            'isSuperAdmin',
            'pendingCount',
            'approvedCount',
            'totalVolume',
            'totalProfit',
            'tab',
            'myPurchasesCount',
            'mySalesCount',
            'currentSubdomain'
        ));
    }

    /**
     * Checkout / Submit new wholesale purchase order with super admin payment.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|string',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:500',
            'contact_phone' => 'required|string|max:30',
            'gateway' => 'required|string|max:50',
            'sender_phone' => 'nullable|string|max:30',
            'trx_id' => 'required|string|max:100',
            'payment_screenshot' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif|max:8192',
        ]);

        $sourceSubdomain = $request->subdomain;
        $productId = (int)$request->product_id;
        $quantity = (int)$request->quantity;

        // Handle Payment Screenshot Upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            try {
                $file = $request->file('payment_screenshot');
                $filename = 'wpo_receipt_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('uploads/wholesale_receipts', $filename, 'public');
                $screenshotPath = 'storage/' . $path;
            } catch (\Throwable $e) {
                Log::warning("Failed to store payment screenshot: " . $e->getMessage());
            }
        }

        $tenant = SaaSTenant::where('subdomain', $sourceSubdomain)->first();
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => "Source tenant [@{$sourceSubdomain}] not found."], 404);
        }

        $sourceDb = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;

        try {
            $this->globalProductService->connectToTenantDatabase($sourceDb);
            $product = DB::connection('tenant_temp')->table('products')->where('id', $productId)->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => "Product #{$productId} not found in tenant @{$sourceSubdomain}."], 404);
            }

            // Determine unit wholesale price & markup
            $globalPrice = floatval($product->global_price ?? 0);
            $wholesalePrice = floatval($product->wholesale_price ?? 0);
            $productCost = floatval($product->product_cost ?? 0);
            $resellerPrice = floatval($product->reseller_price ?? 0);
            $sellingPrice = floatval($product->offer ?? ($product->old_price ?? ($product->price ?? 0)));

            $tenantGlobalMarkupPercent = 10.0;
            try {
                $gSetting = DB::connection('tenant_temp')
                    ->table('site_settings')
                    ->where('group', 'single_product')
                    ->where('key', 'global_price_percent')
                    ->first();
                if ($gSetting && is_numeric($gSetting->value)) {
                    $tenantGlobalMarkupPercent = floatval($gSetting->value);
                }
            } catch (\Throwable $e) {
                $tenantGlobalMarkupPercent = 10.0;
            }

            // Check if seller product is Wholeseller or Admin created
            $isWholeseller = !empty($product->vendor_id) && $product->vendor_id > 0;
            if ($isWholeseller) {
                if ($wholesalePrice > 0) {
                    $basePrice = $wholesalePrice;
                } elseif ($globalPrice > 0) {
                    $basePrice = $globalPrice;
                } elseif ($productCost > 0) {
                    $basePrice = $productCost;
                } else {
                    $basePrice = $sellingPrice;
                }
            } else {
                if ($globalPrice > 0) {
                    $basePrice = $globalPrice;
                } elseif ($productCost > 0) {
                    $basePrice = $productCost * (1 + ($tenantGlobalMarkupPercent / 100));
                } elseif ($wholesalePrice > 0) {
                    $basePrice = $wholesalePrice;
                } elseif ($resellerPrice > 0) {
                    $basePrice = $resellerPrice;
                } else {
                    $basePrice = $sellingPrice;
                }
            }

            // Platform wholesale commission
            $globalCommissionPercent = 0.0;
            try {
                $setting = DB::table('site_settings')
                    ->where('key', 'wholesale_commission')
                    ->where(function ($q) {
                        $q->where('group', 'saas')
                          ->orWhere('group', 'like', '%_saas');
                    })
                    ->orderBy('id', 'desc')
                    ->first();

                if ($setting && is_numeric($setting->value) && floatval($setting->value) > 0) {
                    $globalCommissionPercent = floatval($setting->value);
                } else {
                    $alt = DB::table('site_settings')
                        ->where('key', 'saas_wholesale_commission_rate')
                        ->value('value');
                    if (is_numeric($alt) && floatval($alt) > 0) {
                        $globalCommissionPercent = floatval($alt);
                    }
                }
            } catch (\Throwable $e) {
                $globalCommissionPercent = 0.0;
            }

            $tenantCommissionPercent = ($tenant->commission_rate !== null && $tenant->commission_rate !== '') 
                ? floatval($tenant->commission_rate) 
                : $globalCommissionPercent;

            $commissionPerUnit = ($tenantCommissionPercent > 0) ? ($basePrice * ($tenantCommissionPercent / 100)) : 0;
            $finalUnitPrice = $basePrice + $commissionPerUnit;
            $totalAmount = $finalUnitPrice * $quantity;
            $platformCommissionTotal = $commissionPerUnit * $quantity;
            $sellerEarnings = $basePrice * $quantity;

            // Creator info in seller DB
            $sellerCreatorId = $product->created_by ?: ($product->vendor_id ?: 1);
            $sellerCreatorName = "Tenant Admin #{$sellerCreatorId}";
            try {
                $sellerUser = DB::connection('tenant_temp')->table('users')->where('id', $sellerCreatorId)->first();
                if ($sellerUser) {
                    $sellerCreatorName = $sellerUser->name . ' (' . $sellerUser->email . ')';
                }
            } catch (\Throwable $e) {}

            // Current Buyer Info
            $buyerUser = auth()->user();
            $buyerSubdomain = request()->route('subdomain') 
                ?? session('current_subdomain') 
                ?? ($request->attributes->get('tenant') ? $request->attributes->get('tenant')->subdomain : 'main');

            $orderNumber = 'WPO-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $order = WholesalePurchaseOrder::create([
                'order_number' => $orderNumber,
                'buyer_tenant_id' => null,
                'buyer_subdomain' => $buyerSubdomain,
                'buyer_admin_id' => $buyerUser?->id,
                'buyer_admin_name' => $buyerUser?->name ?? 'Store Admin',
                'buyer_admin_phone' => $request->contact_phone,
                'buyer_admin_email' => $buyerUser?->email,
                'buyer_shipping_address' => $request->shipping_address,
                'seller_tenant_id' => $tenant->id,
                'seller_subdomain' => $sourceSubdomain,
                'seller_admin_id' => $sellerCreatorId,
                'seller_admin_name' => $sellerCreatorName,
                'product_id' => $productId,
                'product_title' => $product->title,
                'product_thumb_image' => $product->thumb_image,
                'unit_price' => $finalUnitPrice,
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
                'platform_commission' => $platformCommissionTotal,
                'seller_earnings' => $sellerEarnings,
                'payment_gateway' => strtoupper($request->gateway),
                'sender_phone' => $request->sender_phone,
                'trx_id' => $request->trx_id,
                'payment_status' => 'pending',
                'fulfillment_status' => 'pending',
                'metadata' => [
                    'base_unit_price' => $basePrice,
                    'commission_percent' => $tenantCommissionPercent,
                    'tenant_name' => $tenant->name,
                    'payment_screenshot' => $screenshotPath,
                    'submitted_at' => now()->toDateTimeString(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'message' => "Wholesale purchase order {$orderNumber} submitted successfully! Super Admin will verify your payment and dispatch the delivery order to @{$sourceSubdomain}.",
                'data' => $order
            ]);

        } catch (\Throwable $e) {
            Log::error("Wholesale checkout error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error submitting wholesale purchase order: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a bulk checkout for multiple wholesale products simultaneously.
     */
    public function bulkCheckout(Request $request)
    {
        $request->validate([
            'items' => 'required',
            'shipping_address' => 'required|string',
            'contact_phone' => 'required|string',
            'gateway' => 'required|string',
            'trx_id' => 'required|string',
            'sender_phone' => 'nullable|string',
            'payment_screenshot' => 'nullable|image|max:5120',
        ]);

        $rawItems = $request->input('items');
        if (is_string($rawItems)) {
            $items = json_decode($rawItems, true) ?: [];
        } else {
            $items = (array)$rawItems;
        }

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'No products selected for wholesale purchase.'], 422);
        }

        try {
            $screenshotPath = null;
            if ($request->hasFile('payment_screenshot')) {
                $screenshotPath = $request->file('payment_screenshot')->store('wholesale/payments', 'public');
            }

            // Central Platform Commission
            $globalCommissionPercent = 25.0;
            try {
                $settingsService = app(\App\Services\SettingsService::class);
                if (method_exists($settingsService, 'getGlobalCommission')) {
                    $globalCommissionPercent = floatval($settingsService->getGlobalCommission());
                } else {
                    $alt = DB::table('site_settings')->where('key', 'saas_wholesale_commission_rate')->value('value');
                    if (is_numeric($alt) && floatval($alt) > 0) {
                        $globalCommissionPercent = floatval($alt);
                    }
                }
            } catch (\Throwable $e) {
                $globalCommissionPercent = 0.0;
            }

            $buyerUser = auth()->user();
            $buyerSubdomain = request()->route('subdomain') 
                ?? session('current_subdomain') 
                ?? ($request->attributes->get('tenant') ? $request->attributes->get('tenant')->subdomain : 'main');

            $createdOrders = [];
            $batchCode = strtoupper(Str::random(5));

            foreach ($items as $index => $item) {
                $sourceSubdomain = $item['subdomain'] ?? '';
                $productId = (int)($item['product_id'] ?? 0);
                $quantity = max(1, (int)($item['quantity'] ?? 1));

                if (empty($sourceSubdomain) || $productId <= 0) continue;

                $tenant = SaaSTenant::where('subdomain', $sourceSubdomain)->first();
                if (!$tenant) continue;

                $sourceDb = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                $this->globalProductService->connectToTenantDatabase($sourceDb);

                $product = DB::connection('tenant_temp')->table('products')->where('id', $productId)->first();
                if (!$product) continue;

                $basePrice = floatval($product->global_price ?? $product->wholesale_price ?? $product->product_cost ?? 0);
                if ($basePrice <= 0) {
                    $basePrice = floatval($product->reseller_price ?? $product->offer ?? $product->price ?? 0);
                }

                $tenantCommissionPercent = ($tenant->commission_rate !== null && $tenant->commission_rate !== '') 
                    ? floatval($tenant->commission_rate) 
                    : $globalCommissionPercent;

                $commissionPerUnit = ($tenantCommissionPercent > 0) ? ($basePrice * ($tenantCommissionPercent / 100)) : 0;
                $finalUnitPrice = $basePrice + $commissionPerUnit;
                $totalAmount = $finalUnitPrice * $quantity;
                $platformCommissionTotal = $commissionPerUnit * $quantity;
                $sellerEarnings = $basePrice * $quantity;

                $sellerCreatorId = $product->created_by ?: ($product->vendor_id ?: 1);
                $sellerCreatorName = "Tenant Admin #{$sellerCreatorId}";
                try {
                    $sellerUser = DB::connection('tenant_temp')->table('users')->where('id', $sellerCreatorId)->first();
                    if ($sellerUser) {
                        $sellerCreatorName = $sellerUser->name . ' (' . $sellerUser->email . ')';
                    }
                } catch (\Throwable $e) {}

                $orderNumber = 'WPO-' . date('Ymd') . '-' . strtoupper(Str::random(6));

                $order = WholesalePurchaseOrder::create([
                    'order_number' => $orderNumber,
                    'buyer_tenant_id' => null,
                    'buyer_subdomain' => $buyerSubdomain,
                    'buyer_admin_id' => $buyerUser?->id,
                    'buyer_admin_name' => $buyerUser?->name ?? 'Store Admin',
                    'buyer_admin_phone' => $request->contact_phone,
                    'buyer_admin_email' => $buyerUser?->email,
                    'buyer_shipping_address' => $request->shipping_address,
                    'seller_tenant_id' => $tenant->id,
                    'seller_subdomain' => $sourceSubdomain,
                    'seller_admin_id' => $sellerCreatorId,
                    'seller_admin_name' => $sellerCreatorName,
                    'product_id' => $productId,
                    'product_title' => $product->title,
                    'product_thumb_image' => $product->thumb_image,
                    'unit_price' => $finalUnitPrice,
                    'quantity' => $quantity,
                    'total_amount' => $totalAmount,
                    'platform_commission' => $platformCommissionTotal,
                    'seller_earnings' => $sellerEarnings,
                    'payment_gateway' => strtoupper($request->gateway),
                    'sender_phone' => $request->sender_phone,
                    'trx_id' => $request->trx_id,
                    'payment_status' => 'pending',
                    'fulfillment_status' => 'pending',
                    'metadata' => [
                        'batch_code' => $batchCode,
                        'base_unit_price' => $basePrice,
                        'commission_percent' => $tenantCommissionPercent,
                        'tenant_name' => $tenant->name,
                        'payment_screenshot' => $screenshotPath,
                        'submitted_at' => now()->toDateTimeString(),
                    ],
                ]);

                $createdOrders[] = $orderNumber;
            }

            if (empty($createdOrders)) {
                return response()->json(['success' => false, 'message' => 'Unable to create purchase orders for selected items.'], 400);
            }

            return response()->json([
                'success' => true,
                'count' => count($createdOrders),
                'order_numbers' => $createdOrders,
                'message' => "Successfully submitted " . count($createdOrders) . " wholesale purchase orders! Super Admin will verify your payment and dispatch fulfillment to suppliers.",
            ]);

        } catch (\Throwable $e) {
            Log::error("Bulk wholesale checkout error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Error processing bulk wholesale order: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Super Admin approves/accepts payment for a wholesale purchase order.
     */
    public function approve(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !($user->hasRole('super_admin') || $user->hasRole('super admin') || ($user->role ?? '') === 'super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Super Admin access required.'], 403);
        }

        $order = WholesalePurchaseOrder::findOrFail($id);

        if ($order->payment_status === 'approved') {
            return response()->json(['success' => false, 'message' => 'Order is already approved.'], 400);
        }

        $ownsTransaction = false;
        if (DB::transactionLevel() === 0) {
            DB::beginTransaction();
            $ownsTransaction = true;
        }

        try {
            $order->payment_status = 'approved';
            $order->approved_by_superadmin_id = $user->id;
            $order->approved_at = now();
            $order->fulfillment_status = 'processing';

            // 1. Dispatch Delivery Order into Seller's Tenant Database
            $sellerTenant = SaaSTenant::where('subdomain', $order->seller_subdomain)->first();
            if ($sellerTenant) {
                $sellerDb = $sellerTenant->db_name ?: 'purnobd_' . $sellerTenant->subdomain;
                $this->globalProductService->connectToTenantDatabase($sellerDb);

                $sellerOrderId = DB::connection('tenant_temp')->table('orders')->insertGetId([
                    'name' => $order->buyer_admin_name ?: 'Wholesale Buyer Admin',
                    'phone' => $order->buyer_admin_phone ?: '01700000000',
                    'address' => Str::limit($order->buyer_shipping_address ?: 'Store Delivery Address', 240, '...'),
                    'total' => $order->seller_earnings,
                    'discount' => 0,
                    'shipping' => 0,
                    'payment_method' => 'B2B Wholesale (Super Admin Paid)',
                    'payment_status' => 'Paid',
                    'order_source' => 'B2B Wholesale Network',
                    'status' => 'Processing',
                    'admin_note' => Str::limit("B2B Wholesale #{$order->order_number} | Buyer: {$order->buyer_admin_name} (@{$order->buyer_subdomain}) | Paid ৳{$order->seller_earnings}", 190, '...'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert into seller order_items
                $sellerUnitPrice = $order->quantity > 0 ? ($order->seller_earnings / $order->quantity) : $order->unit_price;
                $sellerSubTotal = $sellerUnitPrice * $order->quantity;
                DB::connection('tenant_temp')->table('order_items')->insert([
                    'order_id' => $sellerOrderId,
                    'product_id' => $order->product_id,
                    'quantity' => $order->quantity,
                    'price' => $sellerUnitPrice,
                    'sub_total' => $sellerSubTotal,
                    'others' => "B2B Wholesale Order #{$order->order_number}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $order->seller_order_id = $sellerOrderId;
            }

            // 2. Credit Seller Admin's Wallet Balance
            $sellerUser = null;
            if ($order->seller_admin_id) {
                $sellerUser = \App\Models\User::find($order->seller_admin_id);
            }
            if (!$sellerUser && $order->seller_subdomain) {
                $sellerTenantObj = SaaSTenant::where('subdomain', $order->seller_subdomain)->first();
                if ($sellerTenantObj && $sellerTenantObj->admin_id) {
                    $sellerUser = \App\Models\User::find($sellerTenantObj->admin_id);
                }
            }
            if (!$sellerUser && !empty($order->seller_admin_name)) {
                if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $order->seller_admin_name, $m)) {
                    $sellerUser = \App\Models\User::where('email', $m[0])->first();
                }
            }

            if ($sellerUser && $order->seller_earnings > 0) {
                $sellerUser->increment('wallet_balance', $order->seller_earnings);

                // Create recorded transaction in central database
                try {
                    \App\Models\VendorWalletTransaction::create([
                        'vendor_id' => $sellerUser->id,
                        'product_id' => $order->buyer_local_product_id ?: $order->product_id,
                        'admin_id' => $user->id,
                        'type' => 'wholesale_earning',
                        'amount' => $order->seller_earnings,
                        'payment_method' => 'B2B Wholesale Settlement',
                        'transaction_id' => $order->order_number,
                        'status' => 'approved',
                        'admin_note' => "B2B Wholesale payout for #{$order->order_number} ({$order->quantity} pcs)",
                        'is_seen' => false,
                    ]);
                } catch (\Throwable $we) {
                    Log::warning("Could not record VendorWalletTransaction: " . $we->getMessage());
                }
            }

            $order->save();

            if ($ownsTransaction && DB::transactionLevel() > 0) {
                DB::commit();
            }

            return response()->json([
                'success' => true,
                'message' => "Payment for {$order->order_number} approved! ৳" . number_format($order->seller_earnings, 2) . " credited to seller wallet, delivery order dispatched to (@{$order->seller_subdomain}), and {$order->quantity} units allocated to buyer store.",
            ]);

        } catch (\Throwable $e) {
            if ($ownsTransaction && DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            Log::error("Super admin wholesale order approval error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Failed to approve order: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Super Admin rejects payment for a wholesale purchase order.
     */
    public function reject(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !($user->hasRole('super_admin') || $user->hasRole('super admin') || ($user->role ?? '') === 'super_admin')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Super Admin access required.'], 403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $order = WholesalePurchaseOrder::findOrFail($id);
        $order->payment_status = 'rejected';
        $order->rejection_reason = $request->reason ?: 'Payment verification failed or invalid Transaction ID.';
        $order->fulfillment_status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => "Order {$order->order_number} has been rejected."
        ]);
    }

    /**
     * Seller Admin or Super Admin updates fulfillment status (Shipped, Tracking, Delivered).
     */
    public function updateFulfillment(Request $request, $id)
    {
        $order = WholesalePurchaseOrder::findOrFail($id);

        $request->validate([
            'fulfillment_status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'courier_name' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'seller_notes' => 'nullable|string|max:500',
        ]);

        $order->fulfillment_status = $request->fulfillment_status;
        if ($request->filled('courier_name')) $order->courier_name = $request->courier_name;
        if ($request->filled('tracking_number')) $order->tracking_number = $request->tracking_number;
        if ($request->filled('seller_notes')) $order->seller_notes = $request->seller_notes;
        $order->save();

        if ($order->fulfillment_status === 'delivered') {
            $this->provisionDeliveredProductToBuyerStore($order);
        }

        return response()->json([
            'success' => true,
            'message' => "Fulfillment status for {$order->order_number} updated to " . ucfirst($order->fulfillment_status) . ($order->fulfillment_status === 'delivered' ? ". Product inventory allocated to buyer store catalog!" : ".")
        ]);
    }

    /**
     * Printable Wholesale Delivery Invoice & Packing Slip.
     */
    public function invoice($id)
    {
        $order = WholesalePurchaseOrder::findOrFail($id);
        $siteLogo = \App\Models\SiteSetting::getLogo();

        return view('admin.wholesale_orders.invoice', compact('order', 'siteLogo'));
    }

    /**
     * Delete a rejected wholesale purchase order (Only buyer who ordered it or super admin).
     */
    public function destroy($id)
    {
        $order = WholesalePurchaseOrder::findOrFail($id);

        if ($order->payment_status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Only rejected wholesale purchase orders can be deleted.'
            ], 403);
        }

        $user = auth()->user();
        $currentSubdomain = \App\Services\TenantContext::currentSubdomain();

        $isBuyer = false;
        if ($user) {
            $isBuyer = ($order->buyer_admin_id == $user->id) || ($order->buyer_admin_email === $user->email);
        }
        if ($currentSubdomain && $order->buyer_subdomain === $currentSubdomain) {
            $isBuyer = true;
        }

        $isSuperAdmin = $user && ($user->role === 'super_admin' || $user->is_super_admin || $user->email === 'sabbir@purnobd.com');

        if (!$isBuyer && !$isSuperAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Only the buyer store administrator who ordered this product can delete this rejected record.'
            ], 403);
        }

        $orderNum = $order->order_number;
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => "Rejected order {$orderNum} has been deleted successfully."
        ]);
    }

    /**
     * Dispatch wholesale order(s) to Steadfast or Pathao courier.
     */
    public function sendToCourier(Request $request)
    {
        $request->validate([
            'provider' => 'required|string|in:steadfast,pathao',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'required',
        ]);

        $provider = $request->provider;
        $orderIds = $request->order_ids;
        $vendorId = Auth::id() ?: 1;

        $delivery = DeliveryServiceManager::forProvider($provider, $vendorId, false);
        if (!$delivery) {
            $delivery = DeliveryServiceManager::forProvider($provider, 1, false);
        }

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => ucfirst($provider) . ' integration is not configured. Please configure your API keys in Delivery Integration Settings.'
            ], 422);
        }

        $orders = WholesalePurchaseOrder::whereIn('id', $orderIds)->get();
        $dispatchedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            $rawPhone = $order->buyer_admin_phone ?: ($order->sender_phone ?: '');
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($phone, '880') && strlen($phone) === 13) {
                $phone = substr($phone, 2);
            }
            if (strlen($phone) < 11 || empty($phone)) {
                $phone = '01700000000';
            }

            $recipientName = $order->buyer_admin_name ?: ($order->buyer_subdomain ? 'Store @' . $order->buyer_subdomain : 'Wholesale Buyer');
            $address = $order->buyer_shipping_address ?: 'Buyer Store Warehouse Address';
            $itemDesc = ($order->product_title ?: 'Wholesale Stock') . ' (Qty: ' . $order->quantity . ' pcs)';

            $invoice = $order->order_number;
            if (!empty($order->tracking_number)) {
                $invoice .= '-' . time();
            }

            try {
                $orderData = [
                    'invoice'           => $invoice,
                    'recipient_name'    => Str::limit($recipientName, 90, ''),
                    'recipient_phone'   => $phone,
                    'recipient_address' => Str::limit($address, 240, ''),
                    'cod_amount'        => 0, // Prepaid wholesale order
                    'note'              => 'B2B Wholesale Shipment #' . $order->order_number,
                    'item_description'  => Str::limit($itemDesc, 200, ''),
                    'total_lot'         => max(1, (int)$order->quantity),
                ];

                $response = $delivery->createOrder($orderData);

                $consignmentId = $response['consignment']['consignment_id'] 
                    ?? $response['consignment_id'] 
                    ?? $response['data']['consignment_id'] 
                    ?? $response['tracking_code'] 
                    ?? null;

                $order->courier_name = ucfirst($provider);
                if ($consignmentId) {
                    $order->tracking_number = (string)$consignmentId;
                }
                $order->fulfillment_status = 'shipped';
                $order->save();

                $dispatchedCount++;
            } catch (\Throwable $e) {
                $errors[] = "#{$order->order_number}: " . $e->getMessage();
            }
        }

        if ($dispatchedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "Successfully dispatched {$dispatchedCount} wholesale order(s) to " . ucfirst($provider) . "!",
                'errors' => $errors
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Courier dispatch failed: " . implode(', ', $errors)
        ], 422);
    }

    /**
     * Sync delivery status for wholesale orders with live courier APIs.
     */
    public function syncCourierStatus(Request $request)
    {
        $vendorId = Auth::id() ?: 1;
        $orderIds = $request->input('order_ids', []);

        $query = WholesalePurchaseOrder::query();
        if (!empty($orderIds)) {
            $query->whereIn('id', $orderIds);
        } else {
            // Check all non-final wholesale orders that have tracking numbers or couriers
            $query->whereNotNull('tracking_number')
                  ->whereNotIn('fulfillment_status', ['delivered', 'cancelled']);
        }

        $orders = $query->get();
        if ($orders->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No active wholesale shipments pending courier synchronization.',
                'updated_count' => 0
            ]);
        }

        $updatedCount = 0;
        $statusSummary = [];

        foreach ($orders as $order) {
            $trackingId = trim($order->tracking_number ?? '');
            $courierName = strtolower(trim($order->courier_name ?? 'steadfast'));
            if (empty($trackingId)) continue;

            $provider = str_contains($courierName, 'pathao') ? 'pathao' : 'steadfast';

            try {
                $delivery = DeliveryServiceManager::forProvider($provider, $vendorId, false);
                if (!$delivery) {
                    $delivery = DeliveryServiceManager::forProvider($provider, 1, false);
                }
                if (!$delivery) continue;

                $response = $delivery->trackOrder((string)$trackingId);

                $statusText = '';
                if ($provider === 'steadfast' && isset($response['delivery_status'])) {
                    $statusText = is_string($response['delivery_status']) 
                        ? ucfirst($response['delivery_status']) 
                        : ($response['delivery_status']['status'] ?? '');
                } elseif ($provider === 'pathao' && isset($response['data']['order_status'])) {
                    $statusText = $response['data']['order_status'];
                }

                if (!empty($statusText)) {
                    $cleanStatus = strtolower($statusText);
                    if (in_array($cleanStatus, ['delivered', 'partial_delivered', 'completed'])) {
                        $order->fulfillment_status = 'delivered';
                        $order->save();
                        $this->provisionDeliveredProductToBuyerStore($order);
                        $updatedCount++;
                        $statusSummary[] = "#{$order->order_number} -> Delivered";
                    } elseif (in_array($cleanStatus, ['in_transit', 'in review', 'shipped', 'picked_up', 'out_for_delivery'])) {
                        if ($order->fulfillment_status !== 'shipped') {
                            $order->fulfillment_status = 'shipped';
                            $order->save();
                            $updatedCount++;
                        }
                    } elseif (in_array($cleanStatus, ['cancelled', 'returned'])) {
                        $order->fulfillment_status = 'cancelled';
                        $order->save();
                        $updatedCount++;
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Wholesale courier track error for order #{$order->order_number}: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => $updatedCount > 0 
                ? "Synchronized {$updatedCount} wholesale shipment(s) with courier network!" 
                : "All wholesale shipments checked. No status updates reported by courier yet.",
            'updated_count' => $updatedCount,
            'summary' => $statusSummary
        ]);
    }

    /**
     * Provision or update the copied product in buyer admin catalog upon physical delivery.
     */
    protected function provisionDeliveredProductToBuyerStore(WholesalePurchaseOrder $order): ?Product
    {
        try {
            // Guard against duplicate stock crediting for the same wholesale order
            if (str_contains($order->seller_notes ?? '', '[STOCK_CREDITED]')) {
                return $order->buyer_local_product_id ? Product::find($order->buyer_local_product_id) : null;
            }

            $buyerProduct = null;
            if ($order->buyer_local_product_id) {
                $buyerProduct = Product::find($order->buyer_local_product_id);
            }

            if (!$buyerProduct && !empty($order->buyer_admin_id)) {
                $buyerProduct = Product::where('source_product_id', $order->product_id)
                    ->where(function($q) use ($order) {
                        $q->where('created_by', $order->buyer_admin_id)
                          ->orWhere('copied_by_admin_id', $order->buyer_admin_id);
                    })
                    ->first();
            }

            if ($buyerProduct) {
                $buyerProduct->increment('quantity', (int)$order->quantity);
                $buyerProduct->manage_stock = 1;
                $buyerProduct->stock_status = 'in_stock';
                $buyerProduct->status = 1;
                $buyerProduct->approval_status = 'approved';
                $buyerProduct->save();

                $order->buyer_local_product_id = $buyerProduct->id;
                $order->seller_notes = trim(($order->seller_notes ?? '') . ' [STOCK_CREDITED]');
                $order->save();
                return $buyerProduct;
            }

            // Copy product afresh into the buyer store catalog
            if (!empty($order->seller_subdomain) && !empty($order->product_id)) {
                $copyRes = $this->globalProductService->copyProductToStore(
                    $order->seller_subdomain,
                    (int)$order->product_id,
                    'purchase',
                    (int)$order->quantity,
                    null,
                    $order->buyer_admin_id
                );

                if ($copyRes['success'] && !empty($copyRes['product_id'])) {
                    $order->buyer_local_product_id = $copyRes['product_id'];
                    $order->seller_notes = trim(($order->seller_notes ?? '') . ' [STOCK_CREDITED]');
                    $order->save();
                    return Product::find($copyRes['product_id']);
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed to provision delivered product to buyer store for order #{$order->order_number}: " . $e->getMessage());
        }

        return null;
    }
}
