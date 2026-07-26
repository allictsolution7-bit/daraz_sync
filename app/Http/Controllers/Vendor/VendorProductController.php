<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Writer;
use App\Models\Publisher;
use App\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    protected function checkVendorAdminProductAccess($vendor): bool
    {
        if (!$vendor) return false;

        $vendorSettings = $vendor->vendorSettings;
        if ($vendorSettings && $vendorSettings->canAccessAdminProducts()) {
            return true;
        }
        if (method_exists($vendor, 'can') && $vendor->can('vendor.access_admin_products')) {
            return true;
        }

        if (method_exists($vendor, 'roles') && $vendor->roles) {
            foreach ($vendor->roles as $role) {
                if ($role->permissions && $role->permissions->contains('name', 'vendor.access_admin_products')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Display vendor's products or parent admin products
     */
    public function index(Request $request)
    {
        $vendor = auth()->user();
        $canAccessAdminProducts = $this->checkVendorAdminProductAccess($vendor);

        $source = $request->get('source', 'my_products');

        $allocationsQuery = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)->get();
        $allocatedProductIds = $allocationsQuery->pluck('product_id')->toArray();
        $productAllocations = $allocationsQuery->keyBy('product_id');

        if ($source === 'admin_products' && $canAccessAdminProducts) {
            $adminId = $vendor->created_by;
            $query = Product::where(function ($q) use ($adminId) {
                if ($adminId) {
                    $q->where('created_by', $adminId)
                      ->orWhere('vendor_id', $adminId)
                      ->orWhereNull('vendor_id');
                } else {
                    $q->whereNull('vendor_id');
                }
            })->with(['category', 'subCategory', 'brand', 'variationCombinations']);
        } else {
            $source = 'my_products';
            $query = Product::where(function ($q) use ($vendor, $allocatedProductIds) {
                $q->where('vendor_id', $vendor->id)
                  ->orWhereIn('id', $allocatedProductIds);
            })->with(['category', 'subCategory', 'brand', 'variationCombinations']);

            if ($request->filled('status')) {
                $query->where('approval_status', $request->status);
            }
        }

        // Category Filter
        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;
            $query->where(function($q) use ($categoryId) {
                $q->where('category_id', $categoryId)
                  ->orWhereHas('additionalCategories', function($catQ) use ($categoryId) {
                      $catQ->where('product_categories.id', $categoryId);
                  });
            });
        }

        // SubCategory Filter
        if ($request->filled('sub_category_id')) {
            $subCategoryId = $request->sub_category_id;
            $query->where(function($q) use ($subCategoryId) {
                $q->where('sub_category_id', $subCategoryId)
                  ->orWhereHas('additionalSubCategories', function($catQ) use ($subCategoryId) {
                      $catQ->where('sub_categories.id', $subCategoryId);
                  });
            });
        }

        // Third Category / Child Subcategory Filter
        if ($request->filled('third_category_id')) {
            $thirdCategoryId = $request->third_category_id;
            $query->whereHas('thirdCategories', function($q) use ($thirdCategoryId) {
                $q->where('third_categories.id', $thirdCategoryId);
            });
        }

        // Product Type Filter
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Price Range Filters
        if ($request->filled('price_min')) {
            $query->where('old_price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('old_price', '<=', $request->price_max);
        }

        // Date Range Filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Keyword Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $categories = ProductCategory::orderBy('name')->get();
        $subCategories = collect();
        if ($request->filled('category_id')) {
            $subCategories = SubCategory::where('product_category_id', $request->category_id)->orderBy('name')->get();
        }

        $thirdCategories = collect();
        if ($request->filled('sub_category_id')) {
            $thirdCategories = \App\Models\ThirdCategory::where('sub_category_id', $request->sub_category_id)->orderBy('name')->get();
        }

        $viewMode = $request->get('view_mode', 'table'); // 'table' or 'grouped'
        $copiedProductTitles = Product::whereIn('id', $allocatedProductIds)->pluck('title')->toArray();

        if ($viewMode === 'grouped') {
            $allProducts = (clone $query)->latest()->get();
            $groupedProducts = $allProducts->groupBy(function($prod) {
                return $prod->category ? $prod->category->name : 'Uncategorized';
            })->map(function($categoryGroup) {
                return $categoryGroup->groupBy(function($prod) {
                    return $prod->subCategory ? $prod->subCategory->name : 'General / No Subcategory';
                });
            });
            $products = $query->latest()->paginate(50)->withQueryString();
        } else {
            $groupedProducts = collect();
            $products = $query->latest()->paginate(20)->withQueryString();
        }

        return view('vendor.products.index', compact(
            'products', 
            'canAccessAdminProducts', 
            'source', 
            'copiedProductTitles', 
            'allocatedProductIds',
            'productAllocations',
            'categories',
            'subCategories',
            'thirdCategories',
            'viewMode',
            'groupedProducts'
        ));
    }

    /**
     * Copy / duplicate a parent admin product to vendor's catalog with stock allocation and wallet fund deduction
     */
    public function copy(Request $request, Product $product)
    {
        $vendor = auth()->user();
        $canAccessAdminProducts = $this->checkVendorAdminProductAccess($vendor);

        if (!$canAccessAdminProducts) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'You do not have permission to copy parent admin products.');
        }

        $adminId = $vendor->created_by;
        if ($product->vendor_id && $product->vendor_id != $adminId) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'Unauthorized product copy request.');
        }

        // Check if vendor has already copied this product
        $alreadyCopied = Product::where('vendor_id', $vendor->id)
            ->where('title', $product->title)
            ->exists();

        if ($alreadyCopied) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'You have already copied "' . $product->title . '" to your catalog. Please select a different product.');
        }

        // Calculate requested stock quantity & cost
        $totalQuantity = 0;
        $totalCost = 0;
        $combStockMap = []; // combination_id => quantity

        $product->load('variationCombinations');

        if ($product->product_type === 'variable' && $product->variationCombinations->isNotEmpty()) {
            $requestedCombinations = $request->input('combinations', []);
            $quantitiesReq = $request->input('quantities.' . $product->id . '.variations', []);
            
            foreach ($product->variationCombinations as $comb) {
                $qty = 0;
                if (isset($quantitiesReq[$comb->id])) {
                    $qty = (int)$quantitiesReq[$comb->id];
                } elseif (isset($requestedCombinations[$comb->id]['quantity'])) {
                    $qty = (int)$requestedCombinations[$comb->id]['quantity'];
                }

                if ($qty > 0) {
                    if ($comb->stock_quantity !== null && $qty > $comb->stock_quantity) {
                        return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                            ->with('error', 'Requested quantity (' . $qty . ') exceeds available admin stock (' . $comb->stock_quantity . ') for combination options.');
                    }

                    $unitCost = (float)(
                        ($comb->wholesale_price > 0) ? $comb->wholesale_price : 
                        (($comb->product_cost > 0) ? $comb->product_cost : 
                        (($comb->offer_price > 0) ? $comb->offer_price : ($comb->regular_price ?? 0)))
                    );
                    $combStockMap[$comb->id] = [
                        'quantity' => $qty,
                        'unit_cost' => $unitCost,
                        'total_cost' => $qty * $unitCost
                    ];
                    $totalQuantity += $qty;
                    $totalCost += ($qty * $unitCost);
                }
            }

            if ($totalQuantity <= 0) {
                return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                    ->with('error', 'Please enter a valid stock quantity for at least one variation option.');
            }
        } else {
            $qty = 0;
            if ($request->has('quantities.' . $product->id . '.quantity')) {
                $qty = (int)$request->input('quantities.' . $product->id . '.quantity');
            } else {
                $qty = (int)$request->input('quantity', 0);
            }

            if ($qty <= 0) {
                return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                    ->with('error', 'Please specify how many stock units you wish to purchase.');
            }

            if ($product->quantity !== null && $product->manage_stock && $qty > $product->quantity) {
                return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                    ->with('error', 'Requested quantity (' . $qty . ') exceeds available admin stock (' . $product->quantity . ').');
            }

            $unitCost = (float)(
                ($product->wholesale_price > 0) ? $product->wholesale_price : 
                (($product->product_cost > 0) ? $product->product_cost : 
                (($product->offer > 0) ? $product->offer : ($product->old_price ?? 0)))
            );
            $totalQuantity = $qty;
            $totalCost = $qty * $unitCost;
        }

        // Wallet Balance Check
        $walletBalance = (float)($vendor->wallet_balance ?? 0);
        if ($walletBalance < $totalCost) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Insufficient wallet balance. Total stock cost is ৳' . number_format($totalCost, 2) . ', but your wallet balance is ৳' . number_format($walletBalance, 2) . '. Please recharge your wallet first.');
        }

        DB::beginTransaction();
        try {
            $vendorSettings = $vendor->vendorSettings;

            // Respect vendor auto-approve settings or default to pending admin approval
            $autoApprove = $vendorSettings && method_exists($vendorSettings, 'shouldAutoApproveProducts') 
                ? $vendorSettings->shouldAutoApproveProducts() 
                : false;

            if ($autoApprove) {
                $statusTarget = 'approved';
                $trxStatus = 'approved';
                $flashType = 'success';
                $flashMessage = 'Product "' . $product->title . '" stock allocated (' . $totalQuantity . ' units)! ৳' . number_format($totalCost, 2) . ' deducted from your wallet.';
            } else {
                $statusTarget = 'pending';
                $trxStatus = 'pending';
                $flashType = 'warning';
                $flashMessage = 'Product "' . $product->title . '" stock copy requested (' . $totalQuantity . ' units)! ৳' . number_format($totalCost, 2) . ' held from wallet. Admin stock will transfer upon approval.';
            }

            // Record allocation without replicating Product row
            $allocation = \App\Models\VendorProductAllocation::create([
                'vendor_id' => $vendor->id,
                'product_id' => $product->id,
                'requested_quantity' => $totalQuantity,
                'allocated_quantity' => $autoApprove ? $totalQuantity : 0,
                'variation_allocations' => !empty($combStockMap) ? $combStockMap : null,
                'total_cost' => $totalCost,
                'status' => $trxStatus,
            ]);

            // Deduct fund from vendor's wallet balance
            $vendor->decrement('wallet_balance', $totalCost);

            // Record transaction in VendorWalletTransaction
            \App\Models\VendorWalletTransaction::create([
                'vendor_id' => $vendor->id,
                'product_id' => $product->id,
                'admin_id' => $adminId,
                'type' => 'stock_purchase',
                'amount' => $totalCost,
                'payment_method' => 'Wallet',
                'transaction_id' => 'TRX-PRD-' . strtoupper(Str::random(8)),
                'status' => $trxStatus,
                'admin_note' => 'Stock Purchase: Requested product "' . $product->title . '" (' . $totalQuantity . ' units)',
                'is_seen' => true,
            ]);

            DB::commit();

            return redirect()->route('vendor.products.index', ['source' => 'my_products'])
                ->with($flashType, $flashMessage);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Product Copy Exception: ' . $e->getMessage());
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Could not copy "' . $product->title . '". Error: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Copy multiple parent admin products at once with default 1 unit stock (or available stock) per product
     */
    public function bulkCopy(Request $request)
    {
        $vendor = auth()->user();
        $canAccessAdminProducts = $this->checkVendorAdminProductAccess($vendor);

        if (!$canAccessAdminProducts) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'You do not have permission to copy parent admin products.');
        }

        $productIds = $request->input('product_ids', []);
        if (empty($productIds) || !is_array($productIds)) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'No products selected for bulk copy.');
        }

        $quantities = $request->input('quantities', []); // e.g. [product_id => qty] or [product_id => [comb_id => qty]]

        $adminId = $vendor->created_by;
        $allocatedProductIds = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)
            ->pluck('product_id')
            ->toArray();

        $products = Product::whereIn('id', $productIds)
            ->where(function($q) use ($adminId) {
                if ($adminId) {
                    $q->where('vendor_id', $adminId)->orWhereNull('vendor_id');
                } else {
                    $q->whereNull('vendor_id');
                }
            })
            ->whereNotIn('id', $allocatedProductIds)
            ->with('variationCombinations')
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Selected products have already been copied or are unavailable.');
        }

        $totalCost = 0;
        $totalItemsCount = 0;
        $productCopyData = [];

        foreach ($products as $product) {
            $prodCost = 0;
            $prodQty = 0;
            $combStockMap = [];
            $pReq = $quantities[$product->id] ?? null;

            if ($product->product_type === 'variable' && $product->variationCombinations->isNotEmpty()) {
                foreach ($product->variationCombinations as $comb) {
                    $qty = 1;
                    if (is_array($pReq) && isset($pReq['variations'][$comb->id])) {
                        $qty = max(0, (int)$pReq['variations'][$comb->id]);
                    } elseif (is_numeric($pReq)) {
                        $qty = max(0, (int)$pReq);
                    }

                    if ($qty > 0) {
                        $unitCost = (float)(
                            ($comb->wholesale_price > 0) ? $comb->wholesale_price : 
                            (($comb->product_cost > 0) ? $comb->product_cost : 
                            (($comb->offer_price > 0) ? $comb->offer_price : ($comb->regular_price ?? 0)))
                        );
                        $combStockMap[$comb->id] = [
                            'quantity' => $qty,
                            'unit_cost' => $unitCost,
                            'total_cost' => $qty * $unitCost
                        ];
                        $prodQty += $qty;
                        $prodCost += ($qty * $unitCost);
                    }
                }
            } else {
                $qty = 1;
                if (is_numeric($pReq)) {
                    $qty = max(1, (int)$pReq);
                } elseif (is_array($pReq) && isset($pReq['quantity'])) {
                    $qty = max(1, (int)$pReq['quantity']);
                }

                $unitCost = (float)(
                    ($product->wholesale_price > 0) ? $product->wholesale_price : 
                    (($product->product_cost > 0) ? $product->product_cost : 
                    (($product->offer > 0) ? $product->offer : ($product->old_price ?? 0)))
                );
                $prodQty = $qty;
                $prodCost = $qty * $unitCost;
            }

            if ($prodQty <= 0) {
                continue; // Skip products with 0 stock requested
            }

            $totalCost += $prodCost;
            $totalItemsCount++;
            $productCopyData[] = [
                'product' => $product,
                'total_qty' => $prodQty,
                'total_cost' => $prodCost,
                'comb_map' => $combStockMap,
            ];
        }

        if (empty($productCopyData)) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Please specify a valid quantity for at least one variation/product.');
        }

        // Wallet Balance Check
        $walletBalance = (float)($vendor->wallet_balance ?? 0);
        if ($walletBalance < $totalCost) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Insufficient wallet balance for bulk copy. Total required cost for ' . $totalItemsCount . ' products is ৳' . number_format($totalCost, 2) . ', but your wallet balance is ৳' . number_format($walletBalance, 2) . '. Please recharge your wallet first.');
        }

        DB::beginTransaction();
        try {
            $vendorSettings = $vendor->vendorSettings;
            $autoApprove = $vendorSettings && method_exists($vendorSettings, 'shouldAutoApproveProducts') 
                ? $vendorSettings->shouldAutoApproveProducts() 
                : false;

            $statusTarget = $autoApprove ? 'approved' : 'pending';
            $trxStatus = $autoApprove ? 'approved' : 'pending';

            foreach ($productCopyData as $item) {
                $product = $item['product'];
                $prodQty = $item['total_qty'];
                $prodCost = $item['total_cost'];
                $combMap = $item['comb_map'];

                \App\Models\VendorProductAllocation::create([
                    'vendor_id' => $vendor->id,
                    'product_id' => $product->id,
                    'requested_quantity' => $prodQty,
                    'allocated_quantity' => $autoApprove ? $prodQty : 0,
                    'variation_allocations' => !empty($combMap) ? $combMap : null,
                    'total_cost' => $prodCost,
                    'status' => $trxStatus,
                ]);

                \App\Models\VendorWalletTransaction::create([
                    'vendor_id' => $vendor->id,
                    'product_id' => $product->id,
                    'admin_id' => $adminId,
                    'type' => 'stock_purchase',
                    'amount' => $prodCost,
                    'payment_method' => 'Wallet',
                    'transaction_id' => 'TRX-PRD-' . strtoupper(Str::random(8)),
                    'status' => $trxStatus,
                    'admin_note' => 'Bulk Stock Purchase: Requested product "' . $product->title . '" (' . $prodQty . ' units)',
                    'is_seen' => true,
                ]);
            }

            // Deduct total cost from vendor's wallet balance
            $vendor->decrement('wallet_balance', $totalCost);

            DB::commit();

            $msgType = $autoApprove ? 'success' : 'warning';
            $msgContent = 'Successfully requested stock for ' . $totalItemsCount . ' products! ৳' . number_format($totalCost, 2) . ' deducted/held from your wallet balance.';

            return redirect()->route('vendor.products.index', ['source' => 'my_products'])
                ->with($msgType, $msgContent);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Bulk Product Copy Exception: ' . $e->getMessage());
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Bulk copy failed: ' . $e->getMessage());
        }
    }

    /**
     * Return/Cancel product allocation request and refund vendor wallet
     */
    public function returnAllocation(Request $request, $productId)
    {
        $vendor = auth()->user();
        $allocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)
            ->where('product_id', $productId)
            ->first();

        if (!$allocation) {
            return redirect()->back()->with('error', 'Allocation request not found.');
        }

        DB::beginTransaction();
        try {
            $totalCost = (float)($allocation->total_cost ?? 0);
            $productTitle = $allocation->product->title ?? 'Product';

            // Find matching pending stock_purchase transaction if any
            $trx = \App\Models\VendorWalletTransaction::where('vendor_id', $vendor->id)
                ->where('product_id', $productId)
                ->where('type', 'stock_purchase')
                ->where('status', 'pending')
                ->latest()
                ->first();

            $refundAmount = $trx ? (float)$trx->amount : $totalCost;

            if ($refundAmount > 0) {
                // Refund wallet balance
                $vendor->increment('wallet_balance', $refundAmount);

                // Log refund transaction
                \App\Models\VendorWalletTransaction::create([
                    'vendor_id' => $vendor->id,
                    'product_id' => $productId,
                    'admin_id' => $vendor->created_by,
                    'type' => 'stock_purchase_refund',
                    'amount' => $refundAmount,
                    'payment_method' => 'Wallet',
                    'transaction_id' => 'REF-' . strtoupper(Str::random(8)),
                    'status' => 'approved',
                    'admin_note' => 'Vendor return/cancel stock request for "' . $productTitle . '". Refunded ৳' . number_format($refundAmount, 2),
                    'is_seen' => true,
                ]);

                if ($trx) {
                    $trx->update(['status' => 'rejected']);
                }
            }

            // Delete the allocation record
            $allocation->delete();

            DB::commit();

            return redirect()->route('vendor.products.index', ['source' => 'my_products'])
                ->with('success', 'Product stock request for "' . $productTitle . '" has been returned/cancelled. ৳' . number_format($refundAmount, 2) . ' refunded to your wallet.');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Return Allocation Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to return product: ' . $e->getMessage());
        }
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        // Check if vendor can add more products
        $canAdd = $this->vendorService->canAddProduct($vendor->id);
        
        if (!$canAdd['can_add']) {
            return redirect()->route('vendor.products.index')
                ->with('error', $canAdd['message']);
        }

        // Get commission settings
        $commissionSettings = [
            'default' => $vendorSettings->getDefaultCommissionRate(),
            'min' => $vendorSettings->getMinCommissionRate(),
            'max' => $vendorSettings->getMaxCommissionRate(),
        ];

        $categories = ProductCategory::where('status', 'active')
            ->with(['subCategories' => function($query) {
                $query->where('status', 1)
                    ->orderBy('name')
                    ->with(['thirdCategories' => function($q) {
                        $q->where('status', true)->ordered();
                    }]);
            }])
            ->orderBy('name')
            ->get(['id', 'name']);

        $sub_categories = SubCategory::where('status', 1)->get(['id', 'name']);
        $brands = Brand::where('status', 1)->get(['id', 'name']);
        $writers = Writer::select('id', 'name')->get();
        $publishers = Publisher::select('id', 'name')->get();

        return view('vendor.products.create', compact(
            'categories',
            'sub_categories',
            'brands',
            'writers',
            'publishers',
            'commissionSettings'
        ));
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        // Check if vendor can add products
        $canAdd = $this->vendorService->canAddProduct($vendor->id);
        if (!$canAdd['can_add']) {
            return redirect()->back()->with('error', $canAdd['message']);
        }

        // Base validation rules
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type' => 'required|in:simple,variable',
            'quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0.001',
            'manage_stock' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumb_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_proposed_commission' => 'nullable|numeric|min:0|max:100',
        ];

        // Add product type specific validation
        if ($request->product_type === 'variable') {
            $validationRules['old_price'] = 'nullable|numeric|min:0';
            $validationRules['offer'] = 'nullable|numeric|min:0';
            $validationRules['variations'] = 'required|array';
            $validationRules['variations.*.name'] = 'required|string|max:255';
            $validationRules['variations.*.options'] = 'required|array';
            $validationRules['variations.*.options.*.name'] = 'required|string|max:255';
            $validationRules['combinations'] = 'nullable|array';
            $validationRules['combinations.*.regular_price'] = 'required|numeric|min:0';
            $validationRules['combinations.*.offer_price'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.product_cost'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.wholesale_price'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.stock_quantity'] = 'required|integer|min:0';
            $validationRules['combinations.*.short_description'] = 'nullable|string|max:1000';
            $validationRules['combinations.*.featured_image'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
            $validationRules['combinations.*.gallery_images.*'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
            $validationRules['combinations.*.key'] = 'nullable|string';
        } else {
            // Simple product requires pricing
            $validationRules['old_price'] = 'required|numeric|min:0';
            $validationRules['offer'] = 'required|numeric|min:0';
            $validationRules['product_cost'] = 'nullable|numeric|min:0';
            $validationRules['wholesale_price'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($validationRules);

        // Validate proposed commission
        if ($request->vendor_proposed_commission) {
            $commissionValidation = $this->vendorService->validateProposedCommission(
                $vendor->id,
                $request->vendor_proposed_commission
            );

            if (!$commissionValidation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $commissionValidation['message']);
            }
        }

        // Handle thumbnail upload - match admin format for consistency
        if ($request->hasFile('thumb_image')) {
            $thumbImage = $request->file('thumb_image');
            $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
            $thumbImage->storeAs('public', $thumbImagePath);
            $validated['thumb_image'] = $thumbImagePath;
        }

        // Handle gallery images - match admin format
        $galleryImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public', $galleryImagePath);
                $galleryImages[] = $galleryImagePath;
            }
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        // Set vendor-specific fields
        $validated['vendor_id'] = $vendor->id;
        $validated['created_by'] = $vendor->id;
        
        // IMPORTANT: Always require admin approval for vendor products
        // Only auto-approve if explicitly enabled in vendor settings
        $approvalStatus = $vendorSettings->shouldAutoApproveProducts() 
            ? 'approved' 
            : 'pending';
        
        $validated['approval_status'] = $approvalStatus;
        
        // Force status based on approval - vendor cannot override this
        if ($approvalStatus === 'approved') {
            $validated['approved_at'] = now();
            $validated['status'] = 1; // Active
        } else {
            $validated['status'] = 0; // Inactive until approved
        }

        // Set commission rate
        if ($request->vendor_proposed_commission) {
            $validated['vendor_proposed_commission'] = $request->vendor_proposed_commission;
            $validated['vendor_commission_rate'] = $request->vendor_proposed_commission;
        } else {
            $validated['vendor_commission_rate'] = $vendorSettings->getDefaultCommissionRate();
        }

        // Prepare product data
        $productData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'product_type' => $validated['product_type'],
            'thumb_image' => $validated['thumb_image'],
            'images' => !empty($galleryImages) ? json_encode($galleryImages) : null,
            'old_price' => $validated['old_price'] ?? null,
            'offer' => $validated['offer'] ?? null,
            'product_cost' => $validated['product_cost'] ?? null,
            'wholesale_price' => $validated['wholesale_price'] ?? null,
            'quantity' => $validated['quantity'] ?? null,
            'weight' => $validated['weight'] ?? 0.5,
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'vendor_id' => $validated['vendor_id'],
            'approval_status' => $validated['approval_status'],
            'approved_at' => $validated['approved_at'] ?? null,
            'vendor_proposed_commission' => $validated['vendor_proposed_commission'] ?? null,
            'vendor_commission_rate' => $validated['vendor_commission_rate'],
        ];

        // Create product
        $product = Product::create($productData);

        // Process variations if this is a variable product
        if ($request->product_type === 'variable' && $request->has('variations') && is_array($request->input('variations'))) {
            $allVariationOptions = [];
            
            foreach ($request->input('variations') as $variationIndex => $variationData) {
                $variation = new \App\Models\Variation(['name' => $variationData['name']]);
                $product->variations()->save($variation);

                $variationOptions = [];
                
                foreach ($variationData['options'] as $optionIndex => $optionData) {
                    $option = new \App\Models\VariationOption([
                        'name' => $optionData['name'],
                        'description' => null,
                        'featured_image' => null,
                        'images' => null,
                        'stock_quantity' => 0,
                        'price' => 0
                    ]);
                    $variation->options()->save($option);

                    $variationOptions[] = $option;

                    \App\Models\ProductVariationOption::create([
                        'product_id' => $product->id,
                        'variation_option_id' => $option->id,
                        'price' => 0
                    ]);
                }
                
                $allVariationOptions[] = $variationOptions;
            }
            
            // Generate combinations
            $this->generateVariationCombinations($product, $allVariationOptions, $request->input('combinations', []), $request);
        }

        $message = $approvalStatus === 'approved' 
            ? 'Product created and auto-approved successfully! It is now live on the store.' 
            : 'Product submitted successfully! It will remain inactive until admin approves it.';

        return redirect()->route('vendor.products.index')->with('success', $message);
    }

    /**
     * Generate variation combinations (copied from admin controller)
     */
    protected function generateVariationCombinations($product, $allVariationOptions, $combinationsData, $request)
    {
        // Generate Cartesian product of all variation options
        $combinations = $this->cartesianProduct($allVariationOptions);
        
        foreach ($combinations as $index => $combination) {
            $combinationKey = implode('_', array_map(fn($opt) => $opt->name, $combination));
            $combinationIds = array_map(fn($opt) => $opt->id, $combination);
            
            // Get the rich data from the request for this combination
            $combinationInput = $combinationsData[$index] ?? [];
            
            // Handle featured image upload for this combination
            $featuredImagePath = null;
            if ($request->hasFile("combinations.{$index}.featured_image")) {
                $featuredImage = $request->file("combinations.{$index}.featured_image");
                $featuredImagePath = 'product/' . time() . '-' . $featuredImage->getClientOriginalName();
                $featuredImage->storeAs('public', $featuredImagePath);
            }
            
            // Handle gallery images for this combination
            $galleryImagePaths = [];
            if ($request->hasFile("combinations.{$index}.gallery_images")) {
                foreach ($request->file("combinations.{$index}.gallery_images") as $galleryImage) {
                    $galleryImagePath = 'product/' . time() . '-' . $galleryImage->getClientOriginalName();
                    $galleryImage->storeAs('public', $galleryImagePath);
                    $galleryImagePaths[] = $galleryImagePath;
                }
            }
            
            // Create the combination record with rich data
            \App\Models\VariationCombination::create([
                'product_id' => $product->id,
                'combination_string' => $combinationKey,
                'variation_option_ids' => json_encode($combinationIds),
                'regular_price' => $combinationInput['regular_price'] ?? 0,
                'offer_price' => $combinationInput['offer_price'] ?? null,
                'product_cost' => $combinationInput['product_cost'] ?? null,
                'wholesale_price' => $combinationInput['wholesale_price'] ?? null,
                'stock_quantity' => $combinationInput['stock_quantity'] ?? 0,
                'short_description' => $combinationInput['short_description'] ?? null,
                'featured_image' => $featuredImagePath,
                'gallery_images' => !empty($galleryImagePaths) ? json_encode($galleryImagePaths) : null,
            ]);
        }
    }

    /**
     * Helper: Cartesian product for generating all combinations
     */
    protected function cartesianProduct($arrays)
    {
        if (count($arrays) === 1) {
            return array_map(fn($item) => [$item], $arrays[0]);
        }

        $result = [[]];
        foreach ($arrays as $optionsArray) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($optionsArray as $option) {
                    $temp[] = array_merge($resultItem, [$option]);
                }
            }
            $result = $temp;
        }
        
        return $result;
    }

    /**
     * Get subcategories for a category (AJAX)
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = SubCategory::where('product_category_id', $categoryId)
            ->get(['id', 'name', 'slug']);
        
        return response()->json($subcategories);
    }

    /**
     * Get third categories for a subcategory (AJAX)
     */
    public function getThirdcategories($subCategoryId)
    {
        $thirdCategories = \App\Models\ThirdCategory::where('sub_category_id', $subCategoryId)
            ->get(['id', 'name', 'slug']);
        
        return response()->json($thirdCategories);
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        $vendorSettings = $vendor->vendorSettings;

        // Get commission settings
        $commissionSettings = [
            'default' => $vendorSettings && method_exists($vendorSettings, 'getDefaultCommissionRate') ? $vendorSettings->getDefaultCommissionRate() : 15.0,
            'min' => $vendorSettings && method_exists($vendorSettings, 'getMinCommissionRate') ? $vendorSettings->getMinCommissionRate() : 5.0,
            'max' => $vendorSettings && method_exists($vendorSettings, 'getMaxCommissionRate') ? $vendorSettings->getMaxCommissionRate() : 30.0,
        ];

        $categories = ProductCategory::where('status', 1)->get();
        // Get subcategories from primary category, or all if no primary category
        $subCategories = $product->category_id 
            ? SubCategory::where('product_category_id', $product->category_id)->get()
            : SubCategory::where('status', true)->get();
        $brands = Brand::where('status', 1)->get();

        return view('vendor.products.edit', compact(
            'product',
            'categories',
            'subCategories',
            'brands',
            'commissionSettings'
        ));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        // Check if product can be edited (if already approved)
        if ($product->isApproved() && !$vendor->vendorSettings->can_edit_after_approval) {
            return redirect()->back()->with('error', 'You cannot edit approved products.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'old_price' => 'required|numeric|min:0',
            'offer' => 'required|numeric|min:0',
            'product_cost' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'manage_stock' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_proposed_commission' => 'nullable|numeric|min:0|max:100',
        ]);

        // Validate proposed commission if changed
        if ($request->vendor_proposed_commission && 
            $request->vendor_proposed_commission != $product->vendor_commission_rate) {
            
            $commissionValidation = $this->vendorService->validateProposedCommission(
                $vendor->id,
                $request->vendor_proposed_commission
            );

            if (!$commissionValidation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $commissionValidation['message']);
            }

            $validated['vendor_proposed_commission'] = $request->vendor_proposed_commission;
            // Don't auto-update vendor_commission_rate - let admin approve
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumb_image')) {
            // Delete old image
            if ($product->thumb_image) {
                Storage::disk('public')->delete($product->thumb_image);
            }
            
            $thumbImage = $request->file('thumb_image');
            $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
            $thumbImage->storeAs('public', $thumbImagePath);
            $validated['thumb_image'] = $thumbImagePath;
        }

        // Handle gallery images - append to existing images
        if ($request->hasFile('images')) {
            // Get existing images (handle both array and JSON string)
            $existingImages = $product->images;
            if (is_string($existingImages)) {
                $existingImages = json_decode($existingImages, true) ?? [];
            }
            $existingImages = is_array($existingImages) ? $existingImages : [];
            
            // Add new images
            foreach ($request->file('images') as $image) {
                $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public', $galleryImagePath);
                $existingImages[] = $galleryImagePath;
            }
            
            $validated['images'] = json_encode($existingImages);
        }

        // If product was approved and edited, set back to pending
        if ($product->isApproved()) {
            $validated['approval_status'] = 'pending';
            $validated['status'] = 0; // Deactivate until re-approved
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        // Delete thumbnail image
        if ($product->thumb_image) {
            Storage::disk('public')->delete($product->thumb_image);
        }
        
        // Delete gallery images (handle both array and JSON string formats)
        if ($product->images) {
            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}

