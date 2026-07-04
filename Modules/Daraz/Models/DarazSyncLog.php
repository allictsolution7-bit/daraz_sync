<?php

namespace Modules\Daraz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DarazSyncLog extends Model
{
    protected $table = 'daraz_sync_logs';

    public $timestamps = false;

    protected $fillable = [
        'daraz_store_id',
        'daraz_product_mapping_id',
        'type',
        'direction',
        'status',
        'quantity_before',
        'quantity_after',
        'request_data',
        'response_data',
        'error_message',
        'items_processed',
        'items_succeeded',
        'items_failed',
        'created_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'items_processed' => 'integer',
        'items_succeeded' => 'integer',
        'items_failed' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Boot method to set created_at.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    /**
     * Get the store for this log.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(DarazStore::class, 'daraz_store_id');
    }

    /**
     * Get the product mapping for this log.
     */
    public function productMapping(): BelongsTo
    {
        return $this->belongsTo(DarazProductMapping::class, 'daraz_product_mapping_id');
    }

    /**
     * Create a stock push log.
     */
    public static function logStockPush(
        DarazStore $store,
        DarazProductMapping $mapping,
        int $quantityBefore,
        int $quantityAfter,
        string $status,
        ?array $requestData = null,
        ?array $responseData = null,
        ?string $errorMessage = null
    ): self {
        return static::create([
            'daraz_store_id' => $store->id,
            'daraz_product_mapping_id' => $mapping->id,
            'type' => 'stock_push',
            'direction' => 'to_daraz',
            'status' => $status,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'request_data' => $requestData,
            'response_data' => $responseData,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Create a stock pull log.
     */
    public static function logStockPull(
        DarazStore $store,
        DarazProductMapping $mapping,
        int $quantityBefore,
        int $quantityAfter,
        string $status,
        ?array $requestData = null,
        ?array $responseData = null,
        ?string $errorMessage = null
    ): self {
        return static::create([
            'daraz_store_id' => $store->id,
            'daraz_product_mapping_id' => $mapping->id,
            'type' => 'stock_pull',
            'direction' => 'from_daraz',
            'status' => $status,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'request_data' => $requestData,
            'response_data' => $responseData,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Create a bulk sync log.
     */
    public static function logBulkSync(
        DarazStore $store,
        string $direction,
        string $status,
        int $processed,
        int $succeeded,
        int $failed,
        ?string $errorMessage = null
    ): self {
        return static::create([
            'daraz_store_id' => $store->id,
            'daraz_product_mapping_id' => null,
            'type' => 'bulk_sync',
            'direction' => $direction,
            'status' => $status,
            'items_processed' => $processed,
            'items_succeeded' => $succeeded,
            'items_failed' => $failed,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Create a token refresh log.
     */
    public static function logTokenRefresh(
        DarazStore $store,
        string $status,
        ?string $errorMessage = null
    ): self {
        return static::create([
            'daraz_store_id' => $store->id,
            'daraz_product_mapping_id' => null,
            'type' => 'token_refresh',
            'direction' => null,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Create a connection test log.
     */
    public static function logConnectionTest(
        DarazStore $store,
        string $status,
        ?array $responseData = null,
        ?string $errorMessage = null
    ): self {
        return static::create([
            'daraz_store_id' => $store->id,
            'daraz_product_mapping_id' => null,
            'type' => 'connection_test',
            'direction' => null,
            'status' => $status,
            'response_data' => $responseData,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Scope to get logs for a specific store.
     */
    public function scopeForStore($query, $storeId)
    {
        return $query->where('daraz_store_id', $storeId);
    }

    /**
     * Scope to get logs by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get failed logs.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to get successful logs.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope to get recent logs.
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Clean up old logs.
     */
    public static function cleanOldLogs(int $days = null): int
    {
        $days = $days ?? config('daraz.logging.retention_days', 30);
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Get status badge class for display.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'success' => 'bg-success',
            'failed' => 'bg-danger',
            'pending' => 'bg-warning',
            'partial' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    /**
     * Get type label for display.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'stock_push' => 'Stock Push',
            'stock_pull' => 'Stock Pull',
            'bulk_sync' => 'Bulk Sync',
            'token_refresh' => 'Token Refresh',
            'connection_test' => 'Connection Test',
            default => ucfirst($this->type),
        };
    }
}
