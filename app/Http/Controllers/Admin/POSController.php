<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\order;
use App\Models\order_item;
use App\Models\VariationCombination;
use App\Models\ProductCategory;
use App\Services\StockManagementService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class POSController extends Controller
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display the POS interface
     */
    public function index()
    {
        $categories = ProductCategory::where('status', 'active')
            ->orderBy('name')
            ->get();
        $orderSources = [
            'Physical Store' => 'Physical Store',
            'Website' => 'Website', 
            'WhatsApp' => 'WhatsApp',
            'Messenger' => 'Messenger',
            'Phone Call' => 'Phone Call',
            'Instagram' => 'Instagram',
            'Facebook' => 'Facebook'
        ];
        
        $paymentMethods = [
            'cod' => 'Cash on Delivery (COD)',
            'cash' => 'Cash',
            'card' => 'Card',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            'bank_transfer' => 'Bank Transfer'
        ];

        return view('admin.pos.index', compact('categories', 'orderSources', 'paymentMethods'));
    }

    /**
     * Search products for POS (AJAX)
     */
    public function searchProducts(Request $request)
    {
        try {
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

            $query = Product::with([
                    'category',
                    'variationCombinations' => function ($q) {
                        $q->where('is_active', true);
                    },
                    'additionalCategories',
                    'additionalSubCategories',
                    'thirdCategories',
                ])
                ->leftJoinSub($variationStockSubquery, 'variation_stock', function ($join) {
                    $join->on('products.id', '=', 'variation_stock.product_id');
                })
                ->where('products.status', 1)
                ->select('products.*')
                ->selectRaw("$computedQuantityExpression as computed_quantity")
                ->selectRaw("$computedStatusExpression as computed_stock_status");

            // Search by title or ID
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('products.title', 'LIKE', "%{$search}%")
                      ->orWhere('products.id', $search);
                });
            }

            // Primary category filter (including additional categories)
            if ($primaryCategoryId) {
                $query->where(function($q) use ($primaryCategoryId) {
                    $q->where('products.category_id', $primaryCategoryId)
                      ->orWhereHas('additionalCategories', function($subQ) use ($primaryCategoryId) {
                          $subQ->where('product_categories.id', $primaryCategoryId);
                      });
                });
            }

            // Subcategory filter (including additional subcategories)
            if ($subcategoryId) {
                $query->where(function($q) use ($subcategoryId) {
                    $q->where('products.sub_category_id', $subcategoryId)
                      ->orWhereHas('additionalSubCategories', function($subQ) use ($subcategoryId) {
                          $subQ->where('sub_categories.id', $subcategoryId);
                      });
                });
            }

            // Third category filter
            if ($thirdCategoryId) {
                $query->whereHas('thirdCategories', function($q) use ($thirdCategoryId) {
                    $q->where('third_categories.id', $thirdCategoryId);
                });
            }

            // Stock status filter
            if ($request->filled('stock_status')) {
                if ($request->stock_status === 'on_backorder') {
                    $query->where('products.stock_status', 'on_backorder');
                } else {
                    $query->whereRaw("$computedStatusExpression = ?", [$request->stock_status]);
                }
            }

            // Product type filter
            if ($request->filled('product_type')) {
                $query->where('products.product_type', $request->product_type);
            }

            // Low stock only filter
            if ($request->filled('low_stock_only') && $request->low_stock_only == '1') {
                $query->whereRaw("$computedStatusExpression = 'low_stock'");
            }

            $products = $query->orderBy('products.title')
                ->paginate($perPage, ['*'], 'page', $page);

            $productCollection = $products->getCollection();
            return response()->json([
                'products' => $productCollection->map(function($product) {
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
                        $data['variations'] = $variations->map(function($combo) use ($product) {
                            return [
                                'id' => $combo->id,
                                'display_name' => $combo->display_name ?? 'Variation',
                                'price' => $combo->effective_price ?? $combo->offer_price ?? $combo->regular_price ?? 0,
                                'regular_price' => $combo->regular_price ?? 0,
                                'offer_price' => $combo->offer_price,
                                'product_cost' => $combo->product_cost ?? 0,
                                'wholesale_price' => $combo->wholesale_price ?? 0,
                                'stock_quantity' => $combo->stock_quantity ?? 0,
                                'in_stock' => !$product->manage_stock || ($combo->stock_quantity ?? 0) > 0
                            ];
                        })->values();

                        $firstVariation = $variations->first();
                        $data['in_stock'] = $inStock;
                        $data['price'] = $firstVariation?->effective_price
                            ?? $firstVariation?->offer_price
                            ?? $firstVariation?->regular_price
                            ?? 0;
                        $data['stock_quantity'] = (int) ($product->computed_quantity ?? 0);
                    } else {
                        $quantity = (int) ($product->computed_quantity ?? $product->quantity ?? 0);
                        $data['price'] = $product->offer ?? $product->old_price ?? 0;
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
            return response()->json([
                'error' => true,
                'message' => 'Failed to search products: ' . $e->getMessage(),
                'products' => []
            ], 500);
        }
    }

    /**
     * Search customers for POS (AJAX)
     */
    public function searchCustomers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->limit(10)->get(['id', 'name', 'phone', 'email', 'address', 'city']);

        return response()->json([
            'customers' => $customers
        ]);
    }

    /**
     * Create order from POS
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
            'customer_city' => 'nullable|string',
            'order_source' => 'required|string',
            'payment_method' => 'required|string|in:cod,cash,card,bkash,nagad,rocket,bank_transfer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.combination_id' => 'nullable|exists:variation_combinations,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            
            // Payment gateway validations
            'bkash_number' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_transaction_id' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_charge' => 'nullable|numeric|min:0',
            
            'nagad_number' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_transaction_id' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_charge' => 'nullable|numeric|min:0',
            
            'rocket_number' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_transaction_id' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_charge' => 'nullable|numeric|min:0',

            // Payment type validation
            'payment_type' => 'nullable|string|in:full_paid,partial,due',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // Handle customer
                if ($request->customer_id) {
                    $customer = User::find($request->customer_id);
                } else {
                    // Create new customer
                    $customer = User::create([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone,
                        'email' => $request->customer_email ?: $this->generateUniqueEmail(),
                        'address' => $request->customer_address ?? '',
                        'city' => $request->customer_city ?? '',
                        'upazila' => '', // Required field for users table
                        'password' => bcrypt('password'), // Default password
                        'otp_verified' => true // Skip OTP for POS orders
                    ]);
                }

                // Validate stock availability for all items
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

                // Create order
                $order = order::create([
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'address' => $customer->address ?? '',
                    'city' => $customer->city ?? '',
                    'upazila' => '', // Required field, set empty for POS orders
                    'user_id' => $customer->id,
                    'status' => 'confirmed', // POS orders are immediately confirmed
                    'order_source' => $request->order_source,
                    'payment_method' => $request->payment_method,
                    'total' => $request->total,
                    'discount' => $request->discount ?? 0,
                    'shipping' => $request->shipping ?? 0,
                    'message' => $request->notes,
                    // 'ip_address' => null,
                    
                    // Payment gateway fields
                    'bkash_number' => $request->payment_method === 'bkash' ? $request->bkash_number : null,
                    'bkash_transaction_id' => $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null,
                    'bkash_charge' => $request->payment_method === 'bkash' ? ($request->bkash_charge ?? 0) : 0,
                    
                    'nagad_number' => $request->payment_method === 'nagad' ? $request->nagad_number : null,
                    'nagad_transaction_id' => $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null,
                    'nagad_charge' => $request->payment_method === 'nagad' ? ($request->nagad_charge ?? 0) : 0,
                    
                    'rocket_number' => $request->payment_method === 'rocket' ? $request->rocket_number : null,
                    'rocket_transaction_id' => $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null,
                    'rocket_charge' => $request->payment_method === 'rocket' ? ($request->rocket_charge ?? 0) : 0,

                    'total_with_charge' => $request->total, // Total already includes charges from frontend

                    // Payment type fields (Full Paid, Partial, Due)
                    'payment_type' => $request->payment_type ?? 'full_paid',
                    'paid_amount' => $request->paid_amount ?? $request->total,
                    'due_amount' => $request->due_amount ?? 0,
                    'payment_status' => ($request->payment_type ?? 'full_paid') === 'full_paid' ? 'paid' : 'pending',
                ]);

                // Create order items and update stock
                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    
                    // Get product details for others field
                    $othersData = [
                        'name' => $product->title,
                        'is_pos_order' => true,
                        'pos_created_at' => now()->toISOString()
                    ];
                    
                    // Add variation info if it's a variable product
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        if ($combination) {
                            $othersData['variation_display_name'] = $combination->display_name;
                            $othersData['variation_options'] = $combination->variation_options;
                        }
                    }

                    // Snapshot COGS at order time
                    $unitCost = ($item['combination_id'] && isset($combination))
                        ? ($combination->product_cost ?? $product->product_cost ?? 0)
                        : ($product->product_cost ?? 0);

                    order_item::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'combination_id' => $item['combination_id'] ?? null,
                        'option_id' => null, // Legacy field, not used for combinations
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'sub_total' => $item['price'] * $item['quantity'],
                        'unit_cost' => $unitCost,
                        'total_cost' => $unitCost * $item['quantity'],
                        'others' => json_encode($othersData),
                    ]);

                    // Update stock
                    if ($item['combination_id']) {
                        $combination = VariationCombination::find($item['combination_id']);
                        if ($combination) {
                            $this->stockService->updateVariationCombinationStock(
                                $combination,
                                -$item['quantity'],
                                'sale',
                                $order->id,
                                "POS Sale - Order #{$order->id}",
                                'POS Order'
                            );
                        }
                    } else {
                        $this->stockService->updateSimpleProductStock(
                            $product,
                            -$item['quantity'],
                            'sale',
                            $order->id,
                            "POS Sale - Order #{$order->id}",
                            'POS Order'
                        );
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'order' => [
                        'id' => $order->id,
                        'total' => $order->total,
                        'customer_name' => $order->name,
                        'payment_method' => $order->payment_method,
                        'payment_type' => $order->payment_type,
                        'paid_amount' => $order->paid_amount,
                        'due_amount' => $order->due_amount,
                        'order_source' => $order->order_source,
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

    /**
     * Get recent POS orders
     */
    public function recentOrders()
    {
        $orders = Order::with(['order_items.product', 'user'])
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->name,
                    'total' => $order->total,
                    'payment_method' => $order->payment_method,
                    'order_source' => $order->order_source,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y H:i'),
                    'items_count' => $order->order_items->count(),
                    'items' => $order->order_items->map(function($item) {
                        return [
                            'product_name' => $item->product->title ?? 'Unknown Product',
                            'quantity' => $item->quantity,
                            'price' => $item->price
                        ];
                    })
                ];
            })
        ]);
    }

    /**
     * Generate unique email for guest customers
     */
    private function generateUniqueEmail(): string
    {
        do {
            $email = 'pos_customer_' . time() . '_' . rand(1000, 9999) . '@thikana.shop';
        } while (User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Get POS stats for dashboard
     */
    public function getStats()
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        // Today's stats
        $todayOrders = Order::whereDate('created_at', $today)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->count();
        
        $todayRevenue = Order::whereDate('created_at', $today)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->sum('total');

        // This week's stats
        $weekOrders = Order::where('created_at', '>=', $thisWeek)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->count();
        
        $weekRevenue = Order::where('created_at', '>=', $thisWeek)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->sum('total');

        // This month's stats
        $monthOrders = Order::where('created_at', '>=', $thisMonth)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->count();
        
        $monthRevenue = Order::where('created_at', '>=', $thisMonth)
            ->whereIn('order_source', ['Physical Store', 'WhatsApp', 'Messenger', 'Phone Call', 'Instagram', 'Facebook'])
            ->sum('total');

        return response()->json([
            'today' => [
                'orders' => $todayOrders,
                'revenue' => number_format($todayRevenue, 2)
            ],
            'week' => [
                'orders' => $weekOrders,
                'revenue' => number_format($weekRevenue, 2)
            ],
            'month' => [
                'orders' => $monthOrders,
                'revenue' => number_format($monthRevenue, 2)
            ]
        ]);
    }

    /**
     * Print receipt PDF for an order
     */
    public function printReceipt($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [80, 200], // 80mm thermal receipt
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-receipt', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Stream PDF
            return response($mpdf->Output("receipt-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="receipt-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating receipt PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice PDF for an order
     */
    public function printInvoice($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-invoices.pdf-invoice-v3', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Stream PDF
            return response($mpdf->Output("invoice-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="invoice-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download receipt as PDF
     */
    public function downloadReceipt($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [80, 200], // 80mm thermal receipt
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-receipt', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Download PDF
            return response($mpdf->Output("receipt-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="receipt-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating receipt PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download invoice as PDF
     */
    public function downloadInvoice($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-invoices.pdf-invoice-v3', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Download PDF
            return response($mpdf->Output("invoice-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="invoice-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating invoice PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print package slip PDF for an order
     */
    public function printPackageSlip($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [80, 150], // 80mm thermal package slip
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-package-slip', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Stream PDF
            return response($mpdf->Output("package-slip-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="package-slip-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating package slip PDF: ' . $e->getMessage());
        }
    }

    /**
     * Download package slip PDF for an order
     */
    public function downloadPackageSlip($orderId)
    {
        try {
            $order = Order::with(['order_items.product', 'order_items.variationCombination', 'user'])
                          ->findOrFail($orderId);

            // Create mPDF instance with Bangla font support
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [80, 150], // 80mm thermal package slip
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'default_font' => 'dejavusans',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]);

            // Get site settings
            $siteSettings = [
                'site_name' => SettingsService::getSiteName(),
                'contact_email' => SettingsService::getContactEmail(),
                'phone_number' => SettingsService::get('general', 'phone_number', '+8801779542054'),
                'address' => SettingsService::get('general', 'address', 'Dhaka, Bangladesh'),
                'website' => config('app.url', 'www.thikana.shop'),
            ];

            // Get HTML content
            $html = view('admin.pos.pdf-package-slip', compact('order', 'siteSettings'))->render();
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Download PDF
            return response($mpdf->Output("package-slip-{$order->id}.pdf", 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="package-slip-' . $order->id . '.pdf"');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating package slip PDF: ' . $e->getMessage());
        }
    }
}
