<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WholesalePurchaseOrder;
use App\Models\SaaSTenant;
use App\Models\Product;
use App\Models\User;
use App\Services\GlobalProductService;
use Illuminate\Http\Request;
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

        $query = WholesalePurchaseOrder::query()->orderByDesc('created_at');

        // Non-superadmin filters
        if (!$isSuperAdmin) {
            $query->where(function($q) use ($user, $currentSubdomain) {
                if ($user) {
                    $q->where('buyer_admin_id', $user->id);
                }
                if ($currentSubdomain) {
                    $q->orWhere('buyer_subdomain', $currentSubdomain)
                      ->orWhere('seller_subdomain', $currentSubdomain);
                }
            });
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

        // Statistics
        $pendingCount = WholesalePurchaseOrder::where('payment_status', 'pending')->count();
        $approvedCount = WholesalePurchaseOrder::where('payment_status', 'approved')->count();
        $totalVolume = WholesalePurchaseOrder::where('payment_status', 'approved')->sum('total_amount');

        return view('admin.wholesale_orders.index', compact(
            'orders',
            'isSuperAdmin',
            'pendingCount',
            'approvedCount',
            'totalVolume',
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
        ]);

        $sourceSubdomain = $request->subdomain;
        $productId = (int)$request->product_id;
        $quantity = (int)$request->quantity;

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

            // Determine unit wholesale price
            $globalPrice = floatval($product->global_price ?? 0);
            $wholesalePrice = floatval($product->wholesale_price ?? 0);
            $productCost = floatval($product->product_cost ?? 0);
            $sellingPrice = floatval($product->offer ?? ($product->old_price ?? ($product->price ?? 0)));

            $unitPrice = ($globalPrice > 0) ? $globalPrice : (($wholesalePrice > 0) ? $wholesalePrice : (($productCost > 0) ? $productCost : $sellingPrice));

            // Platform wholesale commission
            $globalCommissionPercent = floatval(DB::table('settings')->where('key', 'global_wholesale_commission')->value('value') ?? 0);
            if ($tenant->commission_rate !== null && $tenant->commission_rate !== '') {
                $globalCommissionPercent = floatval($tenant->commission_rate);
            }

            $commissionPerUnit = ($globalCommissionPercent > 0) ? ($unitPrice * ($globalCommissionPercent / 100)) : 0;
            $finalUnitPrice = $unitPrice + $commissionPerUnit;
            $totalAmount = $finalUnitPrice * $quantity;
            $platformCommissionTotal = $commissionPerUnit * $quantity;
            $sellerEarnings = $unitPrice * $quantity;

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
                    'base_unit_price' => $unitPrice,
                    'commission_percent' => $globalCommissionPercent,
                    'tenant_name' => $tenant->name,
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

        DB::beginTransaction();
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
                    'address' => $order->buyer_shipping_address ?: 'Store Delivery Address',
                    'total' => $order->seller_earnings,
                    'discount' => 0,
                    'shipping' => 0,
                    'payment_method' => 'B2B Wholesale (Super Admin Paid)',
                    'payment_status' => 'Paid',
                    'order_source' => 'B2B Wholesale Network',
                    'status' => 'Processing',
                    'admin_note' => "📦 B2B WHOLESALE ORDER #{$order->order_number}\nBuyer: {$order->buyer_admin_name} (@{$order->buyer_subdomain})\nPhone: {$order->buyer_admin_phone}\nAddress: {$order->buyer_shipping_address}\nPayment of ৳{$order->seller_earnings} verified by Super Admin.\nPlease pack and ship {$order->quantity} pcs of product #{$order->product_id} ({$order->product_title}).",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Insert into seller order_items
                DB::connection('tenant_temp')->table('order_items')->insert([
                    'order_id' => $sellerOrderId,
                    'product_id' => $order->product_id,
                    'quantity' => $order->quantity,
                    'price' => $order->unit_price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $order->seller_order_id = $sellerOrderId;
            }

            // 2. Add/Replicate product with purchased stock into Buyer's Catalog
            $copyResult = $this->globalProductService->copyProductToStore(
                $order->seller_subdomain,
                $order->product_id,
                'purchase',
                $order->quantity,
                null,
                $order->buyer_admin_id
            );

            if (!empty($copyResult['product_id'])) {
                $order->buyer_local_product_id = $copyResult['product_id'];
            }

            $order->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Payment for {$order->order_number} approved! Delivery order dispatched to seller (@{$order->seller_subdomain}) and {$order->quantity} units allocated to buyer store.",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
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

        return response()->json([
            'success' => true,
            'message' => "Fulfillment status for {$order->order_number} updated to " . ucfirst($order->fulfillment_status) . "."
        ]);
    }
}
