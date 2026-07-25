<?php

namespace Modules\Daraz\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VariationCombination;
use Illuminate\Http\Request;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Models\DarazProductMapping;
use Modules\Daraz\Services\DarazApiService;
use Modules\Daraz\Services\DarazAuthService;
use Modules\Daraz\Services\DarazStockSyncService;

class DarazProductMappingController extends Controller
{
    protected DarazApiService $apiService;
    protected DarazAuthService $authService;
    protected DarazStockSyncService $syncService;

    public function __construct(
        DarazApiService $apiService,
        DarazAuthService $authService,
        DarazStockSyncService $syncService
    ) {
        $this->apiService = $apiService;
        $this->authService = $authService;
        $this->syncService = $syncService;
    }

    /**
     * Display a listing of mappings.
     */
    public function index(Request $request)
    {
        $stores = DarazStore::forCurrentUser()->active()->get();
        $selectedStoreId = $request->get('store_id');

        $mappings = DarazProductMapping::forCurrentUser()
            ->with(['store', 'product', 'variationCombination'])
            ->when($selectedStoreId, fn($q) => $q->where('daraz_store_id', $selectedStoreId))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('daraz::mappings.index', compact('mappings', 'stores', 'selectedStoreId'));
    }

    /**
     * Show the form for creating a new mapping.
     */
    public function create(Request $request)
    {
        $stores = DarazStore::forCurrentUser()->active()->get();
        $selectedStoreId = $request->get('store_id');

        return view('daraz::mappings.create', compact('stores', 'selectedStoreId'));
    }

    /**
     * Store a newly created mapping.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'daraz_store_id' => 'required|exists:daraz_stores,id',
            'product_id' => 'required|exists:products,id',
            'variation_combination_id' => 'nullable|exists:variation_combinations,id',
            'daraz_item_id' => 'required|string|max:255',
            'daraz_sku' => 'required|string|max:255',
            'seller_sku' => 'nullable|string|max:255',
            'sync_enabled' => 'boolean',
            'stock_buffer' => 'integer|min:0|max:9999',
        ]);

        // Check for duplicate mapping
        $exists = DarazProductMapping::where('daraz_store_id', $validated['daraz_store_id'])
            ->where('product_id', $validated['product_id'])
            ->where('variation_combination_id', $validated['variation_combination_id'] ?? null)
            ->exists();

        if ($exists) {
            flash()->error('This product is already mapped to this store.');
            return back()->withInput();
        }

        $mapping = DarazProductMapping::create([
            'daraz_store_id' => $validated['daraz_store_id'],
            'product_id' => $validated['product_id'],
            'variation_combination_id' => $validated['variation_combination_id'] ?? null,
            'daraz_item_id' => $validated['daraz_item_id'],
            'daraz_sku' => $validated['daraz_sku'],
            'seller_sku' => $validated['seller_sku'] ?? null,
            'sync_enabled' => $validated['sync_enabled'] ?? true,
            'stock_buffer' => $validated['stock_buffer'] ?? 0,
        ]);

        flash()->success('Product mapping created successfully.');

        return redirect()->route('admin.daraz.mappings.index', ['store_id' => $validated['daraz_store_id']]);
    }

    /**
     * Show the form for editing the specified mapping.
     */
    public function edit(DarazProductMapping $mapping)
    {
        $mapping->load(['store', 'product', 'variationCombination']);

        return view('daraz::mappings.edit', compact('mapping'));
    }

    /**
     * Update the specified mapping.
     */
    public function update(Request $request, DarazProductMapping $mapping)
    {
        $validated = $request->validate([
            'daraz_item_id' => 'required|string|max:255',
            'daraz_sku' => 'required|string|max:255',
            'seller_sku' => 'nullable|string|max:255',
            'sync_enabled' => 'boolean',
            'stock_buffer' => 'integer|min:0|max:9999',
        ]);

        $mapping->update([
            'daraz_item_id' => $validated['daraz_item_id'],
            'daraz_sku' => $validated['daraz_sku'],
            'seller_sku' => $validated['seller_sku'] ?? null,
            'sync_enabled' => $validated['sync_enabled'] ?? true,
            'stock_buffer' => $validated['stock_buffer'] ?? 0,
        ]);

        flash()->success('Product mapping updated successfully.');

        return back();
    }

    /**
     * Remove the specified mapping.
     */
    public function destroy(DarazProductMapping $mapping)
    {
        $storeId = $mapping->daraz_store_id;
        $mapping->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Mapping deleted.']);
        }

        flash()->success('Product mapping deleted successfully.');

        return redirect()->route('admin.daraz.mappings.index', ['store_id' => $storeId]);
    }

    /**
     * Toggle sync enabled for a mapping.
     */
    public function toggleSync(DarazProductMapping $mapping)
    {
        $mapping->update(['sync_enabled' => !$mapping->sync_enabled]);

        return response()->json([
            'success' => true,
            'sync_enabled' => $mapping->sync_enabled,
            'message' => $mapping->sync_enabled ? 'Sync enabled.' : 'Sync disabled.',
        ]);
    }

    /**
     * Auto-map products by SKU matching.
     */
    public function autoMap(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:daraz_stores,id',
        ]);

        $store = DarazStore::findOrFail($validated['store_id']);

        if (!$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected. Please authorize first.',
            ]);
        }

        $stats = $this->syncService->autoMapProducts($store);

        return response()->json([
            'success' => true,
            'message' => "Auto-mapping complete. Fetched: {$stats['fetched']}, Mapped: {$stats['mapped']}, Skipped: {$stats['skipped']}",
            'stats' => $stats,
        ]);
    }

    /**
     * Bulk delete mappings.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'mapping_ids' => 'required|array',
            'mapping_ids.*' => 'exists:daraz_product_mappings,id',
        ]);

        DarazProductMapping::whereIn('id', $validated['mapping_ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => count($validated['mapping_ids']) . ' mappings deleted.',
        ]);
    }

    /**
     * Search Thikana products (AJAX).
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');
        $storeId = $request->get('store_id');

        if (strlen($query) < 2) {
            return response()->json(['products' => []]);
        }

        // Get IDs of already mapped products for this store
        $mappedProductIds = [];
        $mappedCombinationIds = [];

        if ($storeId) {
            $mappedProductIds = DarazProductMapping::where('daraz_store_id', $storeId)
                ->whereNull('variation_combination_id')
                ->pluck('product_id')
                ->toArray();

            $mappedCombinationIds = DarazProductMapping::where('daraz_store_id', $storeId)
                ->whereNotNull('variation_combination_id')
                ->pluck('variation_combination_id')
                ->toArray();
        }

        $user = auth()->user();

        $products = Product::where('status', 1)
            ->when($user && $user->isVendor(), fn($q) => $q->where('vendor_id', $user->id))
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['variationCombinations' => function ($q) {
                $q->where('is_active', true);
            }])
            ->limit(20)
            ->get();

        $results = [];

        foreach ($products as $product) {
            // Skip simple products if already mapped
            if ($product->product_type !== 'variable' && in_array($product->id, $mappedProductIds)) {
                continue;
            }

            $productData = [
                'id' => $product->id,
                'name' => $product->title,
                'sku' => $product->sku ?? null,
                'quantity' => $product->quantity ?? 0,
                'variations' => [],
            ];

            // If product has variations
            if ($product->product_type === 'variable' && $product->variationCombinations->count() > 0) {
                foreach ($product->variationCombinations as $combination) {
                    // Skip if already mapped
                    if (in_array($combination->id, $mappedCombinationIds)) {
                        continue;
                    }

                    $productData['variations'][] = [
                        'id' => $combination->id,
                        'name' => $combination->display_name ?? $combination->sku ?? 'Variation',
                        'stock_quantity' => $combination->stock_quantity ?? 0,
                    ];
                }

                // Only add variable product if it has unmapped variations
                if (!empty($productData['variations'])) {
                    $results[] = $productData;
                }
            } else {
                // Simple product
                $results[] = $productData;
            }
        }

        return response()->json(['products' => $results]);
    }

    /**
     * Fetch products from Daraz (AJAX).
     */
    public function fetchDarazProducts(Request $request, DarazStore $store)
    {
        if (!$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected.',
            ]);
        }

        // Ensure valid token
        if (!$this->authService->ensureValidToken($store)) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired. Please re-authorize.',
            ]);
        }

        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 50);
        $search = $request->get('search', '');

        $result = $this->apiService->getProducts($store, $offset, $limit);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to fetch products.',
            ]);
        }

        $products = $result['data']['products'] ?? [];

        // Filter by search if provided
        if ($search) {
            $products = array_filter($products, function ($product) use ($search) {
                $name = $product['attributes']['name'] ?? '';
                return stripos($name, $search) !== false;
            });
        }

        // Format for display
        $formatted = [];
        foreach ($products as $product) {
            $itemId = $product['item_id'] ?? $product['ItemId'] ?? null;
            $name = $product['attributes']['name'] ?? 'Unknown Product';
            $skus = $product['skus'] ?? $product['Skus'] ?? [];

            foreach ($skus as $sku) {
                $formatted[] = [
                    'item_id' => $itemId,
                    'name' => $name,
                    'shop_sku' => $sku['ShopSku'] ?? $sku['shop_sku'] ?? '',
                    'seller_sku' => $sku['SellerSku'] ?? $sku['seller_sku'] ?? '',
                    'quantity' => $sku['quantity'] ?? $sku['Quantity'] ?? 0,
                    'price' => $sku['price'] ?? $sku['Price'] ?? 0,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'products' => $formatted,
            'total' => $result['data']['total_products'] ?? count($formatted),
        ]);
    }
}
