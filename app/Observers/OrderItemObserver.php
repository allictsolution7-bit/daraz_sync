<?php

namespace App\Observers;

use App\Models\order_item;
use App\Services\VendorService;
use Illuminate\Support\Facades\Log;

class OrderItemObserver
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Handle the order_item "created" event.
     * Assign vendor data when order item is created
     */
    public function created(order_item $orderItem): void
    {
        // Check if this item's product belongs to a vendor
        if (!$orderItem->product) {
            // Load product if not already loaded
            $orderItem->load('product');
        }

        // If product has no vendor, skip
        if (!$orderItem->product || !$orderItem->product->vendor_id) {
            return;
        }

        try {
            $product = $orderItem->product;
            
            // Get the commission rate for this product
            $commissionRate = $this->vendorService->calculateProductCommission($product);
            
            // Update order item with vendor info
            $orderItem->update([
                'vendor_id' => $product->vendor_id,
                'vendor_commission_rate' => $commissionRate,
            ]);

            // Calculate commission amounts immediately
            $orderItem->calculateCommission();

            Log::info("Vendor data assigned to order item", [
                'order_id' => $orderItem->order_id,
                'item_id' => $orderItem->id,
                'product_id' => $product->id,
                'vendor_id' => $product->vendor_id,
                'commission_rate' => $commissionRate,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to assign vendor data to order item", [
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

