<?php

namespace Modules\Daraz\Services;

use App\Models\Product;
use App\Models\VariationCombination;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Jobs\SyncStockToDaraz;
use Modules\Daraz\Jobs\PullStockFromDaraz;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Models\DarazProductMapping;
use Modules\Daraz\Models\DarazSyncLog;

class DarazStockSyncService
{
    protected DarazApiService $apiService;
    protected DarazAuthService $authService;

    public function __construct()
    {
        $this->apiService = new DarazApiService();
        $this->authService = new DarazAuthService();
    }

    /**
     * Queue stock sync for a product/combination to all mapped Daraz stores.
     */
    public function queueStockSync(Product $product, ?VariationCombination $combination = null): void
    {
        $mappings = DarazProductMapping::enabled()
            ->forProduct($product->id, $combination?->id)
            ->with('store')
            ->get();

        foreach ($mappings as $mapping) {
            if ($mapping->store->is_active) {
                dispatch(new SyncStockToDaraz($mapping));
            }
        }
    }

    /**
     * Push stock to Daraz for a single mapping.
     */
    public function pushStockToDaraz(DarazProductMapping $mapping): array
    {
        $store = $mapping->store;

        // Ensure valid token
        if (!$this->authService->ensureValidToken($store)) {
            $error = 'Invalid or expired token. Please re-authorize the store.';
            $mapping->updateSyncStatus('failed', null, $error);
            return ['success' => false, 'error' => $error];
        }

        $currentStock = $mapping->getEffectiveStock();
        $previousStock = $mapping->last_synced_quantity ?? 0;

        // Check if sync is needed
        if ($currentStock === $previousStock && $mapping->last_sync_status === 'success') {
            return ['success' => true, 'message' => 'Stock already in sync', 'skipped' => true];
        }

        try {
            // Daraz API requires the numeric SkuId - resolve via item_id + seller_sku
            $itemId = $mapping->daraz_item_id;
            $sellerSku = $mapping->seller_sku ?? $mapping->daraz_sku;

            $result = $this->apiService->updateStock($store, $itemId, $sellerSku, $currentStock);

            if ($result['success']) {
                $mapping->updateSyncStatus('success', $currentStock);

                DarazSyncLog::logStockPush(
                    $store,
                    $mapping,
                    $previousStock,
                    $currentStock,
                    'success',
                    ['item_id' => $itemId, 'seller_sku' => $sellerSku, 'quantity' => $currentStock],
                    $result['data'] ?? null
                );

                Log::info('Daraz Sync: Stock pushed successfully', [
                    'mapping_id' => $mapping->id,
                    'seller_sku' => $sellerSku,
                    'quantity' => $currentStock,
                ]);

                return ['success' => true, 'quantity' => $currentStock];
            } else {
                $error = $result['error'] ?? 'Unknown error';
                $mapping->updateSyncStatus('failed', null, $error);

                DarazSyncLog::logStockPush(
                    $store,
                    $mapping,
                    $previousStock,
                    $currentStock,
                    'failed',
                    ['item_id' => $itemId, 'seller_sku' => $sellerSku, 'quantity' => $currentStock],
                    $result['data'] ?? null,
                    $error
                );

                return ['success' => false, 'error' => $error];
            }

        } catch (\Exception $e) {
            $error = $e->getMessage();
            $mapping->updateSyncStatus('failed', null, $error);

            DarazSyncLog::logStockPush(
                $store,
                $mapping,
                $previousStock,
                $currentStock,
                'failed',
                null,
                null,
                $error
            );

            Log::error('Daraz Sync Exception', [
                'mapping_id' => $mapping->id,
                'error' => $error,
            ]);

            return ['success' => false, 'error' => $error];
        }
    }

    /**
     * Pull stock from Daraz and update Thikana.
     */
    public function pullStockFromDaraz(DarazProductMapping $mapping): array
    {
        $store = $mapping->store;

        // Ensure valid token
        if (!$this->authService->ensureValidToken($store)) {
            return ['success' => false, 'error' => 'Invalid or expired token'];
        }

        try {
            // Get product from Daraz
            $result = $this->apiService->getProduct($store, $mapping->daraz_item_id);

            if (!$result['success']) {
                return ['success' => false, 'error' => $result['error'] ?? 'Failed to fetch product'];
            }

            // Find the specific SKU in the response
            $darazStock = $this->extractStockFromResponse($result['data'], $mapping->daraz_sku);

            if ($darazStock === null) {
                return ['success' => false, 'error' => 'SKU not found in Daraz response'];
            }

            $currentThikanaStock = $mapping->getCurrentStock();
            $previousDarazStock = $mapping->daraz_quantity ?? $darazStock;

            // Check if Daraz stock changed externally
            if ($previousDarazStock !== $darazStock && $darazStock !== $currentThikanaStock) {
                // Daraz stock changed (likely a sale on Daraz)
                $stockDiff = $previousDarazStock - $darazStock; // Positive = items sold

                if ($stockDiff > 0) {
                    // Items were sold on Daraz, reduce Thikana stock
                    $newThikanaStock = max(0, $currentThikanaStock - $stockDiff);
                    $this->updateThikanaStock($mapping, $newThikanaStock);

                    DarazSyncLog::logStockPull(
                        $store,
                        $mapping,
                        $currentThikanaStock,
                        $newThikanaStock,
                        'success',
                        ['daraz_stock' => $darazStock],
                        $result['data'] ?? null
                    );

                    Log::info('Daraz Sync: Stock pulled from Daraz', [
                        'mapping_id' => $mapping->id,
                        'daraz_stock' => $darazStock,
                        'thikana_before' => $currentThikanaStock,
                        'thikana_after' => $newThikanaStock,
                    ]);
                }
            }

            // Update tracked Daraz quantity
            $mapping->update(['daraz_quantity' => $darazStock]);

            return [
                'success' => true,
                'daraz_stock' => $darazStock,
                'thikana_stock' => $mapping->getCurrentStock(),
            ];

        } catch (\Exception $e) {
            Log::error('Daraz Pull Exception', [
                'mapping_id' => $mapping->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Extract stock quantity from Daraz API response.
     */
    protected function extractStockFromResponse(array $data, string $sku): ?int
    {
        // Navigate through the response structure
        $skus = $data['skus'] ?? $data['Skus'] ?? [];

        if (is_array($skus)) {
            foreach ($skus as $skuData) {
                $skuCode = $skuData['SellerSku'] ?? $skuData['seller_sku'] ?? $skuData['ShopSku'] ?? null;
                if ($skuCode === $sku || ($skuData['SkuId'] ?? null) === $sku) {
                    return (int)($skuData['quantity'] ?? $skuData['Quantity'] ?? 0);
                }
            }
        }

        return null;
    }

    /**
     * Update Thikana stock for a mapping.
     */
    protected function updateThikanaStock(DarazProductMapping $mapping, int $newStock): void
    {
        if ($mapping->variation_combination_id) {
            $mapping->variationCombination->update(['stock_quantity' => $newStock]);
        } else {
            $mapping->product->update(['quantity' => $newStock]);
        }
    }

    /**
     * Bulk sync all mappings for a store.
     */
    public function bulkSyncStore(DarazStore $store, string $direction = 'to_daraz'): array
    {
        $mappings = $store->productMappings()->enabled()->get();

        $stats = [
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($mappings as $mapping) {
            $stats['processed']++;

            $result = $direction === 'to_daraz'
                ? $this->pushStockToDaraz($mapping)
                : $this->pullStockFromDaraz($mapping);

            if ($result['success'] ?? false) {
                $stats['succeeded']++;
            } else {
                $stats['failed']++;
                $stats['errors'][] = [
                    'mapping_id' => $mapping->id,
                    'product' => $mapping->product_title,
                    'error' => $result['error'] ?? 'Unknown error',
                ];
            }
        }

        // Log bulk operation
        DarazSyncLog::logBulkSync(
            $store,
            $direction,
            $stats['failed'] > 0 ? ($stats['succeeded'] > 0 ? 'partial' : 'failed') : 'success',
            $stats['processed'],
            $stats['succeeded'],
            $stats['failed'],
            $stats['failed'] > 0 ? json_encode($stats['errors']) : null
        );

        // Update store last sync time
        $store->update(['last_synced_at' => now()]);

        return $stats;
    }

    /**
     * Sync all stores that are due for sync.
     */
    public function syncDueStores(): array
    {
        $stores = DarazStore::dueForSync()->get();

        $results = [];
        foreach ($stores as $store) {
            $results[$store->id] = [
                'store' => $store->name,
                'push' => $this->bulkSyncStore($store, 'to_daraz'),
                'pull' => $this->bulkSyncStore($store, 'from_daraz'),
            ];
        }

        return $results;
    }

    /**
     * Auto-map products by matching SKUs.
     */
    public function autoMapProducts(DarazStore $store): array
    {
        // Ensure valid token
        if (!$this->authService->ensureValidToken($store)) {
            return ['success' => false, 'error' => 'Invalid token'];
        }

        $stats = [
            'fetched' => 0,
            'mapped' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $offset = 0;
        $limit = 100;

        do {
            $result = $this->apiService->getProducts($store, $offset, $limit);

            if (!$result['success']) {
                $stats['errors'][] = $result['error'] ?? 'Failed to fetch products';
                break;
            }

            $products = $result['data']['products'] ?? [];
            $stats['fetched'] += count($products);

            foreach ($products as $product) {
                $itemId = $product['item_id'] ?? $product['ItemId'] ?? null;
                $skus = $product['skus'] ?? $product['Skus'] ?? [];

                foreach ($skus as $sku) {
                    $darazSku = $sku['ShopSku'] ?? $sku['shop_sku'] ?? null;
                    $sellerSku = $sku['SellerSku'] ?? $sku['seller_sku'] ?? null;

                    if (!$darazSku || !$itemId) continue;

                    // Try to create mapping
                    $mapping = DarazProductMapping::findOrCreateBySku($store, $itemId, $darazSku, $sellerSku);

                    if ($mapping) {
                        $stats['mapped']++;
                    } else {
                        $stats['skipped']++;
                    }
                }
            }

            $offset += $limit;
            $hasMore = count($products) >= $limit;

        } while ($hasMore);

        return $stats;
    }
}
