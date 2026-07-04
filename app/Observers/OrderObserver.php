<?php

namespace App\Observers;

use App\Models\order;
use App\Services\VendorService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Handle the order "created" event.
     * Calculate commission rates for vendor items when order is placed
     * 
     * NOTE: Order items are created AFTER the order in the controller,
     * so this method will gracefully skip if no items exist yet.
     * Vendor data can be processed by calling processVendorCommissions() manually.
     */
    public function created(order $order): void
    {
        // Try to assign vendor data if items exist
        // This will gracefully return if no items are found
        $this->assignVendorDataToOrderItems($order);
    }
    
    /**
     * Public method to process vendor commissions for an order
     * Can be called manually after order items are created
     */
    public static function processVendorCommissions(order $order): void
    {
        $service = app(VendorService::class);
        $observer = new self($service);
        $observer->assignVendorDataToOrderItems($order);
    }

    /**
     * Handle the order "updated" event.
     * Process vendor earnings when order is completed/delivered
     */
    public function updated(order $order): void
    {
        // Check if order status changed to delivered/completed
        if ($order->isDirty('status')) {
            $newStatus = $order->status;
            $oldStatus = $order->getOriginal('status');

            // When order is marked as delivered, process vendor earnings
            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $this->processVendorEarnings($order);
            }
        }
    }

    /**
     * Assign vendor data to order items when order is created
     */
    protected function assignVendorDataToOrderItems(order $order): void
    {
        // Refresh the order to get fresh data
        $order->refresh();
        
        // Load order items if not already loaded
        if (!$order->relationLoaded('orderItems')) {
            $order->load('orderItems.product');
        }
        
        // Check if orderItems exist and is not null
        if (!$order->orderItems || $order->orderItems->isEmpty()) {
            Log::debug("No order items found for order", [
                'order_id' => $order->id,
            ]);
            return;
        }

        foreach ($order->orderItems as $item) {
            // Check if this item's product belongs to a vendor
            if ($item->product && $item->product->vendor_id) {
                $product = $item->product;
                
                // Get the commission rate for this product
                $commissionRate = $this->vendorService->calculateProductCommission($product);
                
                // Update order item with vendor info
                $item->update([
                    'vendor_id' => $product->vendor_id,
                    'vendor_commission_rate' => $commissionRate,
                ]);

                // Calculate commission amounts immediately
                $item->calculateCommission();

                Log::info("Vendor data assigned to order item", [
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'vendor_id' => $product->vendor_id,
                    'commission_rate' => $commissionRate,
                ]);
            }
        }
    }

    /**
     * Process vendor earnings when order is delivered
     */
    protected function processVendorEarnings(order $order): void
    {
        try {
            // Process all vendor items in this order
            $this->vendorService->processCompletedOrderItems($order->id);

            Log::info("Vendor earnings processed for order", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to process vendor earnings for order", [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

