<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\order;
use App\Models\order_item;
use App\Models\VariationCombination;
use App\Models\ProductCategory;
use App\Services\StockManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorPOSController extends Controller
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display the POS interface for Resellers
     */
    public function index()
    {
        $vendor = Auth::user();
        if (!$vendor || !$vendor->hasRole('reseller')) {
            abort(403, 'POS access is only available for Reseller accounts.');
        }

        $categories = ProductCategory::where('status', 'active')
            ->orderBy('name')
            ->get();
            
        $paymentMethods = [
            'cod' => 'Cash on Delivery (COD)',
            'cash' => 'Cash',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket'
        ];

        return view('vendor.pos.index', compact('categories', 'paymentMethods'));
    }

    /**
     * Search admin catalog products for POS (AJAX) applying reseller markup percentages
     */
    public function searchProducts(Request $request)
    {
        try {
            $vendor = Auth::user();
            if (!$vendor || !$vendor->hasRole('reseller')) {
                return response()->json(['error' => true, 'message' => 'Unauthorized'], 403);
            }

            $primaryCategoryId = $request->input('primary_category_id', $request->input('category_id'));
            $subcategoryId = $request->input('subcategory_id');
            $thirdCategoryId = $request->input('third_category_id');
            $perPage = max(1, (int) $request->input('per_page', 20));
            $page = max(1, (int) $request->input('page', 1));

            $variationStockSubquery = VariationCombination::selectRaw('product_id, SUM(stock_quantity) as total_stock')
                ->where('is_active', true)
                ->groupBy('product_id');

            $variableQuantityExpression = 'COALESCE(variation_stock.total_stock, 0)';
            $simpleQuantityExpression = 'COALESCE(products.quantity, 0)';
            $computedQuantityExpression = "CASE WHEN products.product_type = 'variable' THEN $variableQuantityExpression ELSE $simpleQuantityExpression END";
            $lowStockConditionExpression = "products.manage_stock = 1 AND products.low_stock_threshold IS NOT NULL AND products.low_stock_threshold > 0 AND $computedQuantityExpression <= products.low_stock_threshold";
            $computedStatusExpression = "CASE WHEN $computedQuantityExpression <= 0 THEN 'out_of_stock' WHEN $lowStockConditionExpression THEN 'low_stock' ELSE 'in_stock' END";

            // Resellers see their admin's catalog products
            $adminId = $vendor->created_by;
            $query = Product::with([
                    'category:id,name',
                    'variationCombinations' => function ($q) {
                        $q->where('is_active', true);
                    }
                ])
                ->leftJoinSub($variationStockSubquery, 'variation_stock', function ($join) {
                    $join->on('products.id', '=', 'variation_stock.product_id');
                })
                ->where('products.status', 1)
                ->where(function ($q) {
                    $q->where('products.approval_status', 'approved')
                      ->orWhere('products.approval_status', 'draft');
                })
                ->where(function ($q) use ($adminId) {
                    if ($adminId) {
                        $q->where('products.created_by', $adminId)
                          ->whereNull('products.vendor_id');
                    } else {
                        $q->whereNull('products.vendor_id');
                    }
                })
                ->select('products.id', 'products.title', 'products.product_type', 'products.category_id', 'products.sub_category_id', 'products.thumb_image', 'products.quantity', 'products.manage_stock', 'products.stock_status', 'products.low_stock_threshold', 'products.offer', 'products.old_price', 'products.product_cost', 'products.wholesale_price', 'products.reseller_price')
                ->selectRaw("$computedQuantityExpression as computed_quantity")
                ->selectRaw("$computedStatusExpression as computed_stock_status");

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('products.title', 'LIKE', "%{$search}%")
                      ->orWhere('products.id', $search);
                });
            }

            if ($primaryCategoryId) {
                $query->where(function($q) use ($primaryCategoryId) {
                    $q->where('products.category_id', $primaryCategoryId)
                      ->orWhereHas('additionalCategories', function($subQ) use ($primaryCategoryId) {
                          $subQ->where('product_categories.id', $primaryCategoryId);
                      });
                });
            }

            if ($subcategoryId) {
                $query->where(function($q) use ($subcategoryId) {
                    $q->where('products.sub_category_id', $subcategoryId)
                      ->orWhereHas('additionalSubCategories', function($subQ) use ($subcategoryId) {
                          $subQ->where('sub_categories.id', $subcategoryId);
                      });
                });
            }

            if ($thirdCategoryId) {
                $query->whereHas('thirdCategories', function($q) use ($thirdCategoryId) {
                    $q->where('third_categories.id', $thirdCategoryId);
                });
            }

            if ($request->filled('stock_status')) {
                if ($request->stock_status === 'on_backorder') {
                    $query->where('products.stock_status', 'on_backorder');
                } else {
                    $query->whereRaw("$computedStatusExpression = ?", [$request->stock_status]);
                }
            }

            $products = $query->orderBy('products.title')
                ->paginate($perPage, ['*'], 'page', $page);

            $markupPct = (float)($vendor->vendorSettings?->reseller_markup_pct ?? 10.00);

            $productCollection = $products->getCollection();
            return response()->json([
                'products' => $productCollection->map(function($product) use ($markupPct) {
                    $computedStatus = $product->computed_stock_status ?? 'in_stock';
                    $isBackorder = ($product->stock_status ?? null) === 'on_backorder';
                    $inStock = $computedStatus !== 'out_of_stock' || $isBackorder;

                    $data = [
                        'id' => $product->id,
                        'title' => $product->title,
                        'product_type' => $product->product_type,
                        'category' => $product->category->name ?? 'No Category',
                        'image' => $product->thumb_image ? asset('storage/' . $product->thumb_image) : null,
                        'manage_stock' => $product->manage_stock,
                        'computed_stock_status' => $isBackorder ? 'on_backorder' : $computedStatus,
                    ];

                    if ($product->product_type === 'variable') {
                        $variations = $product->variationCombinations;
                        $data['variations'] = $variations->map(function($combo) use ($product, $markupPct) {
                            // Calculate reseller calculated price
                            $adminResellerPrice = (float)($combo->reseller_price ?? 0);
                            if ($adminResellerPrice <= 0) {
                                $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                $prodCost = (float)($combo->product_cost > 0 ? $combo->product_cost : ($product->product_cost ?? 0));
                                if ($prodCost > 0) {
                                    $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                } else {
                                    $adminResellerPrice = (float)($combo->wholesale_price > 0 ? $combo->wholesale_price : ($combo->offer_price ?? $combo->regular_price ?? 0));
                                }
                            }
                            $finalPrice = $adminResellerPrice + ceil($adminResellerPrice * ($markupPct / 100));

                            return [
                                'id' => $combo->id,
                                'display_name' => $combo->display_name ?? 'Variation',
                                'price' => $finalPrice,
                                'reseller_price' => $adminResellerPrice,
                                'regular_price' => $combo->regular_price ?? 0,
                                'offer_price' => $combo->offer_price,
                                'product_cost' => $combo->product_cost ?? 0,
                                'wholesale_price' => $combo->wholesale_price ?? 0,
                                'stock_quantity' => $combo->stock_quantity ?? 0,
                                'in_stock' => !$product->manage_stock || ($combo->stock_quantity ?? 0) > 0
                            ];
                        })->values();

                        $firstVariation = $data['variations']->first();
                        $data['in_stock'] = $inStock;
                        $data['price'] = $firstVariation ? $firstVariation['price'] : 0;
                        $data['reseller_price'] = $firstVariation ? $firstVariation['reseller_price'] : 0;
                        $data['stock_quantity'] = (int) ($product->computed_quantity ?? 0);
                    } else {
                        // Calculate simple product calculated reseller price
                        $adminResellerPrice = (float)($product->reseller_price ?? 0);
                        if ($adminResellerPrice <= 0) {
                            $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                            $prodCost = (float)($product->product_cost ?? 0);
                            if ($prodCost > 0) {
                                $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                            } else {
                                $adminResellerPrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0)));
                            }
                        }
                        $finalPrice = $adminResellerPrice + ceil($adminResellerPrice * ($markupPct / 100));

                        $quantity = (int) ($product->computed_quantity ?? $product->quantity ?? 0);
                        $data['price'] = $finalPrice;
                        $data['reseller_price'] = $adminResellerPrice;
                        $data['regular_price'] = $product->old_price ?? 0;
                        $data['offer_price'] = $product->offer;
                        $data['product_cost'] = $product->product_cost ?? 0;
                        $data['wholesale_price'] = $product->wholesale_price ?? 0;
                        $data['stock_quantity'] = $quantity;
                        $data['in_stock'] = $inStock;
                    }

                    return $data;
                })->values(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('POS SEARCH ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => true,
                'message' => 'Failed to search products: ' . $e->getMessage(),
                'products' => []
            ], 500);
        }
    }

    /**
     * Create order from Reseller POS page which logs under reseller and maps to admin orders
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
            'customer_city' => 'nullable|string',
            'payment_method' => 'required|string|in:cod,cash,bkash,nagad,rocket',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.combination_id' => 'nullable|exists:variation_combinations,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string|in:paid,pending',
            'delivery_type' => 'nullable|string|in:self,admin',
        ]);

        try {
            $reseller = Auth::user();
            if (!$reseller || !$reseller->hasRole('reseller')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            return DB::transaction(function () use ($request, $reseller) {
                // Find or create customer
                $generatedPassword = null;
                $customer = User::where('phone', $request->customer_phone)->first();
                if (!$customer) {
                    $generatedPassword = Str::random(8);
                    $customer = User::create([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone,
                        'email' => $request->customer_email ?: $this->generateUniqueEmail(),
                        'address' => $request->customer_address ?? '',
                        'city' => $request->customer_city ?? '',
                        'upazila' => '',
                        'password' => bcrypt($generatedPassword),
                        'otp_verified' => true,
                        'created_by' => $reseller->id
                    ]);
                    
                    // Assign customer role if Spatie role package is used or role column exists
                    try {
                        $customer->assignRole('user');
                    } catch (\Exception $e) {
                        // ignore if role configuration differs
                    }

                    // Log details to php system error log (console) for future SMS integration
                    error_log("NEW USER CREATED VIA POS - Name: {$customer->name}, Phone: {$customer->phone}, Password: {$generatedPassword}");
                }

                // Verify stock availability
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        if (!$this->stockService->checkStock($product, $combination, $item['quantity'])) {
                            throw new \Exception("Insufficient stock for {$product->title} - {$combination->display_name}");
                        }
                    } else {
                        if (!$this->stockService->checkStock($product, null, $item['quantity'])) {
                            throw new \Exception("Insufficient stock for {$product->title}");
                        }
                    }
                }

                // Calculate total reseller cost to deduct from wallet if self-delivery
                $resellerOrderCost = 0;
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        $adminResellerPrice = $combination ? (float)($combination->reseller_price ?? 0) : 0;
                        if ($adminResellerPrice <= 0 && $combination) {
                            $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                            $prodCost = (float)($combination->product_cost > 0 ? $combination->product_cost : ($product->product_cost ?? 0));
                            if ($prodCost > 0) {
                                $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                            } else {
                                $adminResellerPrice = (float)($combination->wholesale_price > 0 ? $combination->wholesale_price : ($combination->product_cost > 0 ? $combination->product_cost : 0));
                            }
                        }
                        $unitCost = $adminResellerPrice;
                    } else {
                        $adminResellerPrice = (float)($product->reseller_price ?? 0);
                        if ($adminResellerPrice <= 0) {
                            $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                            $prodCost = (float)($product->product_cost ?? 0);
                            if ($prodCost > 0) {
                                $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                            } else {
                                $adminResellerPrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->product_cost > 0 ? $product->product_cost : 0));
                            }
                        }
                        $unitCost = $adminResellerPrice;
                    }

                    $resellerOrderCost += $unitCost * $item['quantity'];
                }

                $isSelfDelivery = $request->delivery_type === 'self';
                if ($isSelfDelivery) {
                    if ($reseller->wallet_balance < $resellerOrderCost) {
                        throw new \Exception("Insufficient wallet balance. You need at least ৳" . number_format($resellerOrderCost, 2) . " but you only have ৳" . number_format($reseller->wallet_balance, 2) . ".");
                    }
                    
                    // Deduct from reseller's wallet balance
                    $reseller->decrement('wallet_balance', $resellerOrderCost);
                    
                    // Log transaction
                    \App\Models\VendorWalletTransaction::create([
                        'vendor_id' => $reseller->id,
                        'type' => 'reseller_pos_payment',
                        'amount' => $resellerOrderCost,
                        'status' => 'approved',
                        'admin_note' => "Paid for Reseller POS Order (Self-Delivery). Products cost: ৳" . number_format($resellerOrderCost, 2),
                    ]);
                }

                 // Create Order
                 $order = order::create([
                     'name' => $customer->name,
                     'phone' => $customer->phone,
                     'address' => $customer->address ?? '',
                     'city' => $customer->city ?? '',
                     'upazila' => '',
                     'user_id' => $customer->id,
                     'status' => $isSelfDelivery ? 'processing' : 'pending', // Self-delivered orders start as processing (approved), admin delivered orders start as pending
                     'order_source' => 'Reseller POS',
                     'payment_method' => $request->payment_method,
                     'total' => $request->total,
                     'discount' => $request->discount ?? 0,
                     'shipping' => $request->shipping ?? 0,
                     'message' => $request->notes,
                     'assigned_to' => $reseller->created_by, // Automatically assign to the reseller's admin
                     'payment_status' => ($request->payment_method === 'cod' && !$isSelfDelivery) ? 'pending' : ($isSelfDelivery ? 'paid' : ($request->payment_status === 'paid' ? 'paid' : 'pending')),
                     'delivery_data' => [
                         'amount_paid' => $request->amount_paid ?? 0,
                         'delivery_by' => $isSelfDelivery ? 'reseller' : 'admin',
                         'reseller_cost_deducted' => $isSelfDelivery ? $resellerOrderCost : 0,
                     ]
                 ]);

                // Create items & deduct stock
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    
                    $othersData = [
                        'name' => $product->title,
                        'is_pos_order' => true,
                        'reseller_id' => $reseller->id,
                        'reseller_name' => $reseller->name,
                        'pos_created_at' => now()->toISOString()
                    ];
                    
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        if ($combination) {
                            $othersData['variation_display_name'] = $combination->display_name;
                            $othersData['variation_options'] = $combination->variation_options;
                        }
                    }

                    // Base Cost of product is what admin set as reseller_price (defaulting to product cost + reseller_price_percent)
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        $adminResellerPrice = $combination ? (float)($combination->reseller_price ?? 0) : 0;
                        if ($adminResellerPrice <= 0 && $combination) {
                            $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                            $prodCost = (float)($combination->product_cost > 0 ? $combination->product_cost : ($product->product_cost ?? 0));
                            if ($prodCost > 0) {
                                $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                            } else {
                                $adminResellerPrice = (float)($combination->wholesale_price > 0 ? $combination->wholesale_price : ($combination->product_cost > 0 ? $combination->product_cost : 0));
                            }
                        }
                        $unitCost = $adminResellerPrice;
                    } else {
                        $adminResellerPrice = (float)($product->reseller_price ?? 0);
                        if ($adminResellerPrice <= 0) {
                            $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                            $prodCost = (float)($product->product_cost ?? 0);
                            if ($prodCost > 0) {
                                $adminResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                            } else {
                                $adminResellerPrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->product_cost > 0 ? $product->product_cost : 0));
                            }
                        }
                        $unitCost = $adminResellerPrice;
                    }

                    // reseller_commission = selling_price - admin_cost
                    $resellerEarning = ($item['price'] - $unitCost) * $item['quantity'];

                    order_item::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'combination_id' => $item['combination_id'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'sub_total' => $item['price'] * $item['quantity'],
                        'unit_cost' => $unitCost,
                        'total_cost' => $unitCost * $item['quantity'],
                        'others' => json_encode($othersData),
                        
                        // Vendor commission fields to record reseller share
                        'vendor_id' => $reseller->id,
                        'vendor_commission_rate' => 0.00,
                        'vendor_commission_amount' => 0.00,
                        'vendor_earning' => $resellerEarning,
                        'vendor_paid' => false,
                    ]);

                    // Deduct Stock
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        if ($combination) {
                            $this->stockService->updateVariationCombinationStock(
                                $combination,
                                -$item['quantity'],
                                'sale',
                                $order->id,
                                "Reseller POS Sale - Order #{$order->id}",
                                'Reseller POS'
                            );
                        }
                    } else {
                        $this->stockService->updateSimpleProductStock(
                            $product,
                            -$item['quantity'],
                            'sale',
                            $order->id,
                            "Reseller POS Sale - Order #{$order->id}",
                            'Reseller POS'
                        );
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Reseller POS order submitted successfully! Your Admin will verify and process it.',
                    'order' => [
                        'id' => $order->id,
                        'total' => $order->total,
                        'customer_name' => $order->name,
                        'payment_method' => $order->payment_method,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s')
                    ]
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function generateUniqueEmail()
    {
        return 'customer_' . time() . '_' . rand(1000, 9999) . '@pos.com';
    }
}
