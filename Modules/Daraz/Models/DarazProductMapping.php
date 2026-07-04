<?php

namespace Modules\Daraz\Models;

use App\Models\Product;
use App\Models\VariationCombination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DarazProductMapping extends Model
{
    protected $table = 'daraz_product_mappings';

    protected $fillable = [
        'daraz_store_id',
        'product_id',
        'variation_combination_id',
        'daraz_item_id',
        'daraz_sku',
        'seller_sku',
        'sync_enabled',
        'stock_buffer',
        'last_synced_quantity',
        'daraz_quantity',
        'last_synced_at',
        'last_sync_status',
        'last_sync_error',
    ];

    protected $casts = [
        'sync_enabled' => 'boolean',
        'stock_buffer' => 'integer',
        'last_synced_quantity' => 'integer',
        'daraz_quantity' => 'integer',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the Daraz store for this mapping.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(DarazStore::class, 'daraz_store_id');
    }

    /**
     * Get the Thikana product for this mapping.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation combination for this mapping (if variable product).
     */
    public function variationCombination(): BelongsTo
    {
        return $this->belongsTo(VariationCombination::class);
    }

    /**
     * Get sync logs for this mapping.
     */
    public function syncLogs(): HasMany
    {
        return $this->hasMany(DarazSyncLog::class, 'daraz_product_mapping_id');
    }

    /**
     * Get current Thikana stock for this mapping.
     */
    public function getCurrentStock(): int
    {
        if ($this->variation_combination_id && $this->variationCombination) {
            return $this->variationCombination->stock_quantity ?? 0;
        }
        return $this->product->quantity ?? 0;
    }

    /**
     * Get effective stock (current stock minus buffer).
     */
    public function getEffectiveStock(): int
    {
        $stock = $this->getCurrentStock();
        $effective = $stock - $this->stock_buffer;
        return max(0, $effective);
    }

    /**
     * Get the Thikana SKU for this mapping.
     */
    public function getThikanaSku(): ?string
    {
        if ($this->variation_combination_id && $this->variationCombination) {
            return $this->variationCombination->sku;
        }
        return $this->product->sku ?? null;
    }

    /**
     * Get product title for display.
     */
    public function getProductTitleAttribute(): string
    {
        $title = $this->product->title ?? 'Unknown Product';

        if ($this->variationCombination) {
            $variation = $this->variationCombination->display_name;
            if ($variation) {
                $title .= ' - ' . $variation;
            }
        }

        return $title;
    }

    /**
     * Check if mapping needs sync (stock differs).
     */
    public function needsSync(): bool
    {
        $currentStock = $this->getEffectiveStock();
        return $this->last_synced_quantity !== $currentStock;
    }

    /**
     * Update sync status after operation.
     */
    public function updateSyncStatus(string $status, ?int $quantity = null, ?string $error = null): void
    {
        $this->update([
            'last_sync_status' => $status,
            'last_synced_at' => now(),
            'last_synced_quantity' => $quantity ?? $this->getEffectiveStock(),
            'last_sync_error' => $error,
        ]);
    }

    /**
     * Scope to get enabled mappings.
     */
    public function scopeEnabled($query)
    {
        return $query->where('sync_enabled', true);
    }

    /**
     * Scope to get mappings for a specific store.
     */
    public function scopeForStore($query, $storeId)
    {
        return $query->where('daraz_store_id', $storeId);
    }

    /**
     * Scope to get mappings for a specific product.
     */
    public function scopeForProduct($query, $productId, $combinationId = null)
    {
        return $query->where('product_id', $productId)
            ->when($combinationId, fn($q) => $q->where('variation_combination_id', $combinationId));
    }

    /**
     * Scope to get mappings that need sync.
     */
    public function scopeNeedsSync($query)
    {
        // This is a simplified check - actual comparison needs to be done after loading
        return $query->enabled();
    }

    /**
     * Find mappings by Daraz SKU.
     */
    public static function findByDarazSku(string $sku, ?int $storeId = null)
    {
        return static::query()
            ->when($storeId, fn($q) => $q->where('daraz_store_id', $storeId))
            ->where('daraz_sku', $sku)
            ->first();
    }

    /**
     * Find or create mapping by SKU matching.
     */
    public static function findOrCreateBySku(
        DarazStore $store,
        string $darazItemId,
        string $darazSku,
        ?string $sellerSku = null
    ): ?self {
        // First try to find existing mapping
        $existing = static::where('daraz_store_id', $store->id)
            ->where('daraz_sku', $darazSku)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Try to match by SKU
        $sku = $sellerSku ?? $darazSku;

        // Check variation combinations first
        $combination = VariationCombination::where('sku', $sku)->first();
        if ($combination) {
            return static::create([
                'daraz_store_id' => $store->id,
                'product_id' => $combination->product_id,
                'variation_combination_id' => $combination->id,
                'daraz_item_id' => $darazItemId,
                'daraz_sku' => $darazSku,
                'seller_sku' => $sellerSku,
            ]);
        }

        // Check simple products
        $product = Product::where('sku', $sku)->first();
        if ($product) {
            return static::create([
                'daraz_store_id' => $store->id,
                'product_id' => $product->id,
                'variation_combination_id' => null,
                'daraz_item_id' => $darazItemId,
                'daraz_sku' => $darazSku,
                'seller_sku' => $sellerSku,
            ]);
        }

        return null;
    }
}
