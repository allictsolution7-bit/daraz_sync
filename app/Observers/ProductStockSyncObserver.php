<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\BackupSetting;
use App\Services\GoogleSheetSyncService;
use Illuminate\Support\Facades\Log;

class ProductStockSyncObserver
{
    /**
     * Handle the Product "saved" event.
     */
    public function saved(Product $product): void
    {
        // Only trigger if auto sync is enabled and sheet is configured
        $enabled = BackupSetting::get('google_sheet_auto_sync_enabled', false);
        if (!$enabled) {
            return;
        }

        // Avoid infinite loop if we are currently running background import
        if (defined('SYNCING_GOOGLE_SHEET_JOB')) {
            return;
        }

        // Only sync if quantity or price was dirty
        if ($product->wasChanged(['quantity', 'offer', 'old_price', 'product_cost', 'status', 'title'])) {
            try {
                $service = app(GoogleSheetSyncService::class);
                $service->pushProductToSheet($product);
            } catch (\Exception $e) {
                Log::error('Failed to sync product to Google Sheet on save: ' . $e->getMessage());
            }
        }
    }
}
