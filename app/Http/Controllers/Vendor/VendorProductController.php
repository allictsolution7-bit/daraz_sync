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

        // Check Spatie roles (if wholeseller/reseller role exists in DB)
        if ($vendor->hasRole('wholeseller') || $vendor->hasRole('reseller')) {
            return true;
        }

        $vendorSettings = $vendor->vendorSettings;

        if (!$vendorSettings) return false;

        // If they are vendor retailer or retailer role: allow access to add wholesell products by default
        if ($vendor->isVendorRetailer() || $vendor->hasRole('retailer')) {
            return true;
        }

        // Check vendor_type stored in additional_config JSON
        $additionalConfig = $vendorSettings->additional_config ?? [];
        $vendorType = $additionalConfig['vendor_type'] ?? null;
        if (in_array($vendorType, ['wholeseller', 'reseller'])) {
            return true;
        }

        // Check explicit can_access_admin_products flag
        if ($vendorSettings->can_access_admin_products) {
            return true;
        }

        // Check consignment access
        if ($vendorSettings->is_consignment && $vendorSettings->canAccessAdminProducts()) {
            return true;
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

        $source = $request->get('source');
        
        // Detect wholeseller/reseller via role OR vendor_type in additional_config
        $vendorSettings = $vendor->vendorSettings;
        $additionalConfig = $vendorSettings?->additional_config ?? [];
        $vendorType = $additionalConfig['vendor_type'] ?? null;
        $isWholeseller = $vendor->hasRole('wholeseller') || $vendor->hasRole('reseller')
            || in_array($vendorType, ['wholeseller', 'reseller']);

        if ($isWholeseller) {
            $canAccessAdminProducts = true;
        }

        $source = $source ?: 'my_products';

        $allocationsQuery = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)->get();
        $allocatedProductIds = $allocationsQuery->pluck('product_id')->toArray();
        $productAllocations = $allocationsQuery->keyBy('product_id');

        if ($source === 'admin_products' && $canAccessAdminProducts) {
            if ($vendor->isVendorRetailer() || $vendor->hasRole('retailer')) {
                // Retailer viewing wholesale products: show approved products from other wholesellers
                $wholesellerIds = \App\Models\User::role('wholeseller')->pluck('id')->toArray();
                $configWholesellerIds = \App\Models\VendorSetting::all()->filter(function($setting) {
                    return ($setting->additional_config['vendor_type'] ?? null) === 'wholeseller';
                })->pluck('vendor_id')->toArray();
                $allWholesellerIds = array_unique(array_merge($wholesellerIds, $configWholesellerIds));

                $query = Product::whereIn('vendor_id', $allWholesellerIds)
                    ->where('vendor_id', '!=', $vendor->id)
                    ->where('approval_status', 'approved')
                    ->where('status', 1);
            } else {
                $adminId = $vendor->created_by;
                $query = Product::where(function ($q) use ($adminId) {
                    if ($adminId) {
                        $q->where('created_by', $adminId)
                          ->whereNull('vendor_id');
                    } else {
                        $q->whereNull('vendor_id');
                    }
                });
            }

            $query->with(['category', 'subCategory', 'brand', 'variationCombinations.wholesaleTiers', 'wholesaleTiers']);
        } else {
            $source = 'my_products';
            $query = Product::where('vendor_id', $vendor->id)
                ->with(['category', 'subCategory', 'brand', 'variationCombinations.wholesaleTiers', 'wholesaleTiers']);

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
        $vendorProductTitles = Product::where('vendor_id', $vendor->id)->pluck('title')->toArray();
        $vendorParentIds = Product::where('vendor_id', $vendor->id)->whereNotNull('parent_product_id')->pluck('parent_product_id')->toArray();

        $allCopiedIds = array_unique(array_merge($allocatedProductIds, $vendorParentIds));
        $allCopiedTitles = array_unique(array_merge($copiedProductTitles, $vendorProductTitles));

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
            'allCopiedIds',
            'allCopiedTitles',
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
        $isRetailer = $vendor->isVendorRetailer() || $vendor->hasRole('retailer');
        $isWholesellerProduct = false;
        if ($product->vendor_id) {
            $productOwner = \App\Models\User::find($product->vendor_id);
            if ($productOwner) {
                $ownerSettings = $productOwner->vendorSettings;
                $isWholesellerProduct = $productOwner->hasRole('wholeseller') || 
                    ($ownerSettings && ($ownerSettings->additional_config['vendor_type'] ?? null) === 'wholeseller');
            }
        }

        if ($product->vendor_id && $product->vendor_id != $adminId) {
            if (!($isRetailer && $isWholesellerProduct)) {
                return redirect()->route('vendor.products.index')
                    ->with('error', 'Unauthorized product copy request.');
            }
        }

        // Check if vendor has already copied this product
        $alreadyCopied = Product::where('vendor_id', $vendor->id)
            ->where(function($q) use ($product) {
                $q->where('parent_product_id', $product->id)
                  ->orWhere('title', $product->title);
            })
            ->exists();

        // Calculate requested stock quantity & cost
        $totalQuantity = 0;
        $totalCost = 0;
        $combStockMap = []; // combination_id => quantity

        $product->load(['variationCombinations.wholesaleTiers', 'wholesaleTiers']);

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

                    $baseCost = (float)(
                        ($comb->wholesale_price > 0) ? $comb->wholesale_price : 
                        (($comb->product_cost > 0) ? $comb->product_cost : 
                        (($comb->offer_price > 0) ? $comb->offer_price : ($comb->regular_price ?? 0)))
                    );

                    $unitCost = $baseCost;
                    if ($comb->wholesaleTiers && $comb->wholesaleTiers->isNotEmpty()) {
                        $sortedTiers = $comb->wholesaleTiers->sortByDesc('min_quantity');
                        foreach ($sortedTiers as $tier) {
                            if ($qty >= (int)$tier->min_quantity) {
                                $unitCost = (float)$tier->price;
                                break;
                            }
                        }
                    }

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

            $baseCost = (float)(
                ($product->wholesale_price > 0) ? $product->wholesale_price : 
                (($product->product_cost > 0) ? $product->product_cost : 
                (($product->offer > 0) ? $product->offer : ($product->old_price ?? 0)))
            );

            $unitCost = $baseCost;
            if ($product->wholesaleTiers && $product->wholesaleTiers->isNotEmpty()) {
                $sortedTiers = $product->wholesaleTiers->sortByDesc('min_quantity');
                foreach ($sortedTiers as $tier) {
                    if ($qty >= (int)$tier->min_quantity) {
                        $unitCost = (float)$tier->price;
                        break;
                    }
                }
            }

            $totalQuantity = $qty;
            $totalCost = $qty * $unitCost;
        }

        // Wallet Balance Check
        $walletBalance = (float)($vendor->wallet_balance ?? 0);
        $vendorSettings = $vendor->vendorSettings;
        $allowNegative = $vendorSettings && !empty($vendorSettings->additional_config['allow_negative_balance']);

        if (!$allowNegative && $walletBalance < $totalCost) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Insufficient wallet balance. Total stock cost is ৳' . number_format($totalCost, 2) . ', but your wallet balance is ৳' . number_format($walletBalance, 2) . '. Please recharge your wallet first.');
        }

        DB::beginTransaction();
        try {
            $vendorSettings = $vendor->vendorSettings;

            // Copying/purchasing parent admin catalog products is always auto-approved
            $autoApprove = true;

            if ($autoApprove) {
                $trxStatus = 'approved';
                $flashType = 'success';
                $flashMessage = ($alreadyCopied ? 'Additional stock' : 'Product "' . $product->title . '" stock') . ' allocated (' . $totalQuantity . ' units)! ৳' . number_format($totalCost, 2) . ' deducted from your wallet.';
            } else {
                $trxStatus = 'pending';
                $flashType = 'warning';
                $flashMessage = 'Stock purchase requested (' . $totalQuantity . ' units)! ৳' . number_format($totalCost, 2) . ' held from wallet. Admin stock will transfer upon approval.';
            }

            // Find or create allocation
            $allocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)
                ->where('product_id', $product->id)
                ->first();

            if ($allocation) {
                $allocation->increment('requested_quantity', $totalQuantity);
                if ($autoApprove) {
                    $allocation->increment('allocated_quantity', $totalQuantity);
                }
                $allocation->increment('total_cost', $totalCost);
                if (!empty($combStockMap)) {
                    $existingVarAlloc = $allocation->variation_allocations ?? [];
                    foreach ($combStockMap as $cId => $cData) {
                        if (isset($existingVarAlloc[$cId])) {
                            $existingVarAlloc[$cId]['quantity'] += $cData['quantity'];
                            $existingVarAlloc[$cId]['total_cost'] += $cData['total_cost'];
                        } else {
                            $existingVarAlloc[$cId] = $cData;
                        }
                    }
                    $allocation->variation_allocations = $existingVarAlloc;
                    $allocation->save();
                }
            } else {
                $allocation = \App\Models\VendorProductAllocation::create([
                    'vendor_id' => $vendor->id,
                    'product_id' => $product->id,
                    'requested_quantity' => $totalQuantity,
                    'allocated_quantity' => $autoApprove ? $totalQuantity : 0,
                    'variation_allocations' => !empty($combStockMap) ? $combStockMap : null,
                    'total_cost' => $totalCost,
                    'status' => $trxStatus,
                ]);
            }

            // Deduct fund from vendor's wallet balance
            $vendor->decrement('wallet_balance', $totalCost);

            // Record transaction in VendorWalletTransaction
            \App\Models\VendorWalletTransaction::create([
                'vendor_id' => $vendor->id,
                'product_id' => $product->id,
                'admin_id' => $product->vendor_id ?? $adminId,
                'type' => 'stock_purchase',
                'amount' => $totalCost,
                'payment_method' => 'Wallet',
                'transaction_id' => 'TRX-PRD-' . strtoupper(Str::random(8)),
                'status' => $trxStatus,
                'admin_note' => ($alreadyCopied ? 'Additional Stock Purchase: ' : 'Stock Purchase: ') . 'Requested product "' . $product->title . '" (' . $totalQuantity . ' units)',
                'is_seen' => true,
            ]);

            // Adjust stocks (+ from vendor stock, - from admin stock)
            if ($autoApprove) {
                // Deduct stock from Admin product (-)
                if ($product->manage_stock && $product->quantity !== null) {
                    $product->decrement('quantity', $totalQuantity);
                }

                // Deduct stock from Admin variation combinations if variable (-)
                if ($product->product_type === 'variable' && !empty($combStockMap)) {
                    foreach ($combStockMap as $cId => $cData) {
                        $combModel = \App\Models\VariationCombination::find($cId);
                        if ($combModel && $combModel->stock_quantity !== null) {
                            $combModel->decrement('stock_quantity', $cData['quantity']);
                        }
                    }
                }

                // Increase stock of vendor's product copy (+)
                $vendorProduct = Product::where('vendor_id', $vendor->id)
                    ->where(function($q) use ($product) {
                        $q->where('parent_product_id', $product->id)
                          ->orWhere('title', $product->title);
                    })
                    ->first();

                if ($vendorProduct) {
                    $vendorProduct->increment('quantity', $totalQuantity);

                    if ($vendorProduct->product_type === 'variable' && !empty($combStockMap)) {
                        foreach ($combStockMap as $cId => $cData) {
                            $adminComb = \App\Models\VariationCombination::find($cId);
                            if ($adminComb) {
                                $vendorComb = \App\Models\VariationCombination::where('product_id', $vendorProduct->id)
                                    ->where('combination_key', $adminComb->combination_key)
                                    ->first();
                                if ($vendorComb) {
                                    $vendorComb->increment('stock_quantity', $cData['quantity']);
                                }
                            }
                        }
                    }
                } else {
                    // Create vendor product copy if not created yet
                    $this->vendorService->approveAllocation($allocation->id, $adminId ?? 1);
                }
            }

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

        $isRetailer = $vendor->isVendorRetailer() || $vendor->hasRole('retailer');
        if ($isRetailer) {
            $wholesellerIds = \App\Models\User::role('wholeseller')->pluck('id')->toArray();
            $configWholesellerIds = \App\Models\VendorSetting::all()->filter(function($setting) {
                return ($setting->additional_config['vendor_type'] ?? null) === 'wholeseller';
            })->pluck('vendor_id')->toArray();
            $allWholesellerIds = array_unique(array_merge($wholesellerIds, $configWholesellerIds));

            $products = Product::whereIn('id', $productIds)
                ->where(function($q) use ($allWholesellerIds, $adminId) {
                    $q->whereIn('vendor_id', $allWholesellerIds)
                      ->orWhereNull('vendor_id');
                    if ($adminId) {
                        $q->orWhere('vendor_id', $adminId);
                    }
                })
                ->whereNotIn('id', $allocatedProductIds)
                ->with(['variationCombinations.wholesaleTiers', 'wholesaleTiers'])
                ->get();
        } else {
            $products = Product::whereIn('id', $productIds)
                ->where(function($q) use ($adminId) {
                    if ($adminId) {
                        $q->where('vendor_id', $adminId)->orWhereNull('vendor_id');
                    } else {
                        $q->whereNull('vendor_id');
                    }
                })
                ->whereNotIn('id', $allocatedProductIds)
                ->with(['variationCombinations.wholesaleTiers', 'wholesaleTiers'])
                ->get();
        }

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
                        $baseCost = (float)(
                            ($comb->wholesale_price > 0) ? $comb->wholesale_price : 
                            (($comb->product_cost > 0) ? $comb->product_cost : 
                            (($comb->offer_price > 0) ? $comb->offer_price : ($comb->regular_price ?? 0)))
                        );

                        $unitCost = $baseCost;
                        if ($comb->wholesaleTiers && $comb->wholesaleTiers->isNotEmpty()) {
                            $sortedTiers = $comb->wholesaleTiers->sortByDesc('min_quantity');
                            foreach ($sortedTiers as $tier) {
                                if ($qty >= (int)$tier->min_quantity) {
                                    $unitCost = (float)$tier->price;
                                    break;
                                }
                            }
                        }

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

                $baseCost = (float)(
                    ($product->wholesale_price > 0) ? $product->wholesale_price : 
                    (($product->product_cost > 0) ? $product->product_cost : 
                    (($product->offer > 0) ? $product->offer : ($product->old_price ?? 0)))
                );

                $unitCost = $baseCost;
                if ($product->wholesaleTiers && $product->wholesaleTiers->isNotEmpty()) {
                    $sortedTiers = $product->wholesaleTiers->sortByDesc('min_quantity');
                    foreach ($sortedTiers as $tier) {
                        if ($qty >= (int)$tier->min_quantity) {
                            $unitCost = (float)$tier->price;
                            break;
                        }
                    }
                }

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
        $vendorSettings = $vendor->vendorSettings;
        $allowNegative = $vendorSettings && !empty($vendorSettings->additional_config['allow_negative_balance']);

        if (!$allowNegative && $walletBalance < $totalCost) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Insufficient wallet balance for bulk copy. Total required cost for ' . $totalItemsCount . ' products is ৳' . number_format($totalCost, 2) . ', but your wallet balance is ৳' . number_format($walletBalance, 2) . '. Please recharge your wallet first.');
        }

        DB::beginTransaction();
        try {
            $vendorSettings = $vendor->vendorSettings;
            // Bulk copying/purchasing parent admin catalog products is always auto-approved
            $autoApprove = true;

            $statusTarget = $autoApprove ? 'approved' : 'pending';
            $trxStatus = $autoApprove ? 'approved' : 'pending';

            foreach ($productCopyData as $item) {
                $product = $item['product'];
                $prodQty = $item['total_qty'];
                $prodCost = $item['total_cost'];
                $combMap = $item['comb_map'];

                $allocation = \App\Models\VendorProductAllocation::create([
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
                    'admin_id' => $product->vendor_id ?? $adminId,
                    'type' => 'stock_purchase',
                    'amount' => $prodCost,
                    'payment_method' => 'Wallet',
                    'transaction_id' => 'TRX-PRD-' . strtoupper(Str::random(8)),
                    'status' => $trxStatus,
                    'admin_note' => 'Bulk Stock Purchase: Requested product "' . $product->title . '" (' . $prodQty . ' units)',
                    'is_seen' => true,
                ]);

                // Adjust stocks & copy product copy if auto-approved
                if ($autoApprove) {
                    // Deduct stock from Admin product (-)
                    if ($product->manage_stock && $product->quantity !== null) {
                        $product->decrement('quantity', $prodQty);
                    }

                    // Deduct stock from Admin variation combinations if variable (-)
                    if ($product->product_type === 'variable' && !empty($combMap)) {
                        foreach ($combMap as $cId => $cData) {
                            $combModel = \App\Models\VariationCombination::find($cId);
                            if ($combModel && $combModel->stock_quantity !== null) {
                                $combModel->decrement('stock_quantity', $cData['quantity']);
                            }
                        }
                    }

                    // Create separate vendor product copy
                    $this->vendorService->approveAllocation($allocation->id, $adminId ?? 1);
                }
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

        // Target either direct allocation or allocation linked via parent_product_id
        $allocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)
            ->where(function($q) use ($productId) {
                $q->where('product_id', $productId);
            })->first();

        // Also check vendor product copy
        $vendorProduct = \App\Models\Product::where('vendor_id', $vendor->id)
            ->where(function($q) use ($productId) {
                $q->where('id', $productId)->orWhere('parent_product_id', $productId);
            })->first();

        if (!$allocation && !$vendorProduct) {
            return redirect()->back()->with('error', 'Allocation request or product not found.');
        }

        $parentProductId = $vendorProduct ? ($vendorProduct->parent_product_id ?? $vendorProduct->id) : ($allocation->product_id ?? $productId);
        $parentProduct = \App\Models\Product::find($parentProductId);

        DB::beginTransaction();
        try {
            $totalCost = (float)($allocation->total_cost ?? 0);
            $totalQty = max(1, (int)($allocation->requested_quantity ?? ($vendorProduct->quantity ?? 1)));
            $unitPrice = $totalQty > 0 ? ($totalCost / $totalQty) : 0;

            if ($unitPrice <= 0 && $parentProduct) {
                $unitPrice = (float)(
                    ($parentProduct->wholesale_price > 0) ? $parentProduct->wholesale_price : 
                    (($parentProduct->product_cost > 0) ? $parentProduct->product_cost : 
                    (($parentProduct->offer > 0) ? $parentProduct->offer : ($parentProduct->old_price ?? 0)))
                );
            }

            $returnQty = (int)$request->input('return_quantity', $vendorProduct ? $vendorProduct->quantity : $totalQty);
            $returnQty = max(1, min($returnQty, $vendorProduct ? $vendorProduct->quantity : $totalQty));

            $refundAmount = $returnQty * $unitPrice;
            $productTitle = $vendorProduct ? $vendorProduct->title : ($parentProduct->title ?? 'Product');

            if ($refundAmount > 0) {
                // Refund wallet balance
                $vendor->increment('wallet_balance', $refundAmount);

                // Log refund transaction
                \App\Models\VendorWalletTransaction::create([
                    'vendor_id' => $vendor->id,
                    'product_id' => $parentProductId,
                    'admin_id' => $vendor->created_by,
                    'type' => 'stock_purchase_refund',
                    'amount' => $refundAmount,
                    'payment_method' => 'Wallet',
                    'transaction_id' => 'REF-' . strtoupper(Str::random(8)),
                    'status' => 'approved',
                    'admin_note' => 'Returned ' . $returnQty . ' stock units for "' . $productTitle . '". Refunded ৳' . number_format($refundAmount, 2),
                    'is_seen' => true,
                ]);
            }

            // Return stock to parent admin product
            if ($parentProduct) {
                $parentProduct->increment('quantity', $returnQty);
            }

            // Reduce or delete vendor product & allocation
            if ($vendorProduct) {
                if ($vendorProduct->quantity <= $returnQty) {
                    $vendorProduct->delete();
                } else {
                    $vendorProduct->decrement('quantity', $returnQty);
                }
            }

            if ($allocation) {
                if ($allocation->requested_quantity <= $returnQty) {
                    $allocation->delete();
                } else {
                    $allocation->decrement('requested_quantity', $returnQty);
                    $allocation->decrement('allocated_quantity', $returnQty);
                    $allocation->decrement('total_cost', $refundAmount);
                }
            }

            DB::commit();

            return redirect()->route('vendor.products.index', ['source' => 'my_products'])
                ->with('success', 'Successfully returned ' . $returnQty . ' unit(s) of "' . $productTitle . '" to Admin. ৳' . number_format($refundAmount, 2) . ' refunded to your wallet.');

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
            'return_period' => 'nullable|integer|min:0',
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
            'return_period' => $validated['return_period'] ?? 0,
        ];

        // Create product
        $product = Product::create($productData);

        // Store wholesale tiers for simple product
        $tiersInput = $request->input('wholesale_tiers');
        if (is_string($tiersInput)) {
            $tiersInput = json_decode($tiersInput, true);
        }
        if (is_array($tiersInput)) {
            foreach ($tiersInput as $tier) {
                if (isset($tier['min_quantity']) && isset($tier['price']) && (int)$tier['min_quantity'] > 0 && (float)$tier['price'] >= 0) {
                    $product->wholesaleTiers()->create([
                        'min_quantity' => (int)$tier['min_quantity'],
                        'price' => (float)$tier['price']
                    ]);
                }
            }
        }

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

        // Ensure vendor owns product or has stock allocation for this product
        $hasAllocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)->where('product_id', $product->id)->exists();
        if ($product->vendor_id != $vendor->id && !$hasAllocation) {
            abort(403, 'Permission required: (vendor.products.edit). You do not own this product.');
        }

        $vendorSettings = $vendor->vendorSettings;

        // Check if product can be edited (defaulted to true now, but will require re-approval upon update)
        $canEditApproved = true;
        if ($product->isApproved() && !$canEditApproved) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'You cannot edit approved products.');
        }

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

        // Ensure vendor owns product or has stock allocation for this product
        $hasAllocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)->where('product_id', $product->id)->exists();
        if ($product->vendor_id != $vendor->id && !$hasAllocation) {
            abort(403, 'Permission required: (vendor.products.edit). You do not own this product.');
        }

        // Check if product can be edited (defaulted to true now, but will require re-approval upon update)
        $vendorSettings = $vendor->vendorSettings;
        $canEditApproved = true;
        if ($product->isApproved() && !$canEditApproved) {
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
            'wholesale_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'manage_stock' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_proposed_commission' => 'nullable|numeric|min:0|max:100',
            'return_period' => 'nullable|integer|min:0',
            'seo.meta_title' => 'nullable|string|max:60',
            'seo.meta_description' => 'nullable|string|max:160',
            'seo.meta_keywords' => 'nullable|string|max:255',
            'seo.canonical_url' => 'nullable|url|max:500',
            'seo.meta_robots' => 'nullable|string|max:50',
            'seo.og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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

        // Protect inventory fields if product was copied from parent admin catalog
        if ($product->parent_product_id) {
            unset($validated['quantity'], $validated['manage_stock'], $validated['low_stock_threshold']);
        }

        // Handle SEO data
        $seoInput = $request->input('seo', []);
        $seoData = is_array($product->seo) ? $product->seo : (json_decode($product->seo, true) ?? []);

        $seoData['meta_title']       = $seoInput['meta_title'] ?? null;
        $seoData['meta_description'] = $seoInput['meta_description'] ?? null;
        $seoData['meta_keywords']    = $seoInput['meta_keywords'] ?? null;
        $seoData['canonical_url']    = $seoInput['canonical_url'] ?? null;
        $seoData['meta_robots']      = $seoInput['meta_robots'] ?? 'index,follow';

        // Handle OG image upload
        if ($request->hasFile('seo.og_image')) {
            $ogFile = $request->file('seo.og_image');
            $ogPath = 'product/og/' . time() . '-' . $ogFile->getClientOriginalName();
            $ogFile->storeAs('public', $ogPath);
            $seoData['og_image'] = $ogPath;
        } elseif (!empty($seoInput['existing_og_image'])) {
            $seoData['og_image'] = $seoInput['existing_og_image'];
        }

        $validated['seo'] = $seoData;

        // Check vendor auto-approve settings (only auto-approve edits if auto_approve_products is enabled)
        $autoApprove = $vendorSettings && $vendorSettings->auto_approve_products;
        
        if ($autoApprove) {
            $validated['approval_status'] = 'approved';
            $validated['status'] = 1;
        } else {
            // By default, if edited, it needs to be approved again
            $validated['approval_status'] = 'pending';
            $validated['status'] = 0; // Deactivate until re-approved
        }

        $isCreator = ($product->created_by === $vendor->id) || ($product->vendor_id === $vendor->id);
        if ($isCreator) {
            $validated['pay_advance_delivery'] = $request->has('pay_advance_delivery') ? 1 : 0;
        }

        $product->update($validated);

        // Store/Sync wholesale tiers for simple product
        $product->wholesaleTiers()->delete();
        $tiersInput = $request->input('wholesale_tiers');
        if (is_string($tiersInput)) {
            $tiersInput = json_decode($tiersInput, true);
        }
        if (is_array($tiersInput)) {
            foreach ($tiersInput as $tier) {
                if (isset($tier['min_quantity']) && isset($tier['price']) && (int)$tier['min_quantity'] > 0 && (float)$tier['price'] >= 0) {
                    $product->wholesaleTiers()->create([
                        'min_quantity' => (int)$tier['min_quantity'],
                        'price' => (float)$tier['price']
                    ]);
                }
            }
        }

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $vendor = auth()->user();

        // Ensure vendor owns product or has stock allocation for this product
        $hasAllocation = \App\Models\VendorProductAllocation::where('vendor_id', $vendor->id)->where('product_id', $product->id)->exists();
        if ($product->vendor_id != $vendor->id && !$hasAllocation) {
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

