<?php

namespace App\Observers;

use App\Models\order;
use App\Services\VendorService;
use App\Services\SMSService;
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

        // Send Product Sold SMS notification to customer if enabled in settings
        $this->sendOrderSmsNotifications($order);
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
     * Process vendor earnings when order is completed/delivered and paid
     */
    public function updated(order $order): void
    {
        $statusChanged = $order->isDirty('status');
        $paymentChanged = $order->isDirty('payment_status');

        $isDelivered = $order->status === 'delivered';
        $isPaid = $order->payment_status === 'paid';

        // Check if the order is now BOTH delivered AND paid
        $isCurrentlyEligible = $isDelivered && $isPaid;

        // Check if the order WAS both delivered AND paid before this update
        $wasDelivered = $order->getOriginal('status') === 'delivered';
        $wasPaid = $order->getOriginal('payment_status') === 'paid';
        $wasEligible = $wasDelivered && $wasPaid;

        if ($isCurrentlyEligible && !$wasEligible) {
            // Transitioned to eligible -> Credit earnings
            $this->processVendorEarnings($order);
        } elseif (!$isCurrentlyEligible && $wasEligible) {
            // Transitioned away from eligible -> Reverse earnings
            $this->reverseVendorEarnings($order);
        }

        // Send Order Status Update SMS if status changed and option is enabled in settings
        if ($statusChanged && !empty($order->phone)) {
            try {
                $smsService = app(SMSService::class);
                $smsService->sendEventSMS('order_status', $order->phone, [
                    'customer_name' => $order->name,
                    'order_id' => $order->id,
                    'status' => ucfirst($order->status),
                ]);
            } catch (\Throwable $e) {
                Log::error("OrderObserver: Failed to dispatch order status SMS", [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
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

    /**
     * Reverse vendor earnings when order becomes unpaid or undelivered
     */
    protected function reverseVendorEarnings(order $order): void
    {
        try {
            // Find vendor items in this order that were already credited/paid
            $vendorItems = $order->orderItems()
                ->whereNotNull('vendor_id')
                ->where('vendor_paid', true)
                ->get();

            if ($vendorItems->isEmpty()) {
                return;
            }

            foreach ($vendorItems as $item) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($item) {
                    $item->update([
                        'vendor_paid' => false,
                        'vendor_paid_at' => null,
                    ]);

                    if ($item->vendor_earning > 0) {
                        // Decrement vendor wallet balance
                        $vendorUser = \App\Models\User::find($item->vendor_id);
                        if ($vendorUser) {
                            $vendorUser->decrement('wallet_balance', $item->vendor_earning);
                        }

                        // Create a ledger entry for adjustment/reversal
                        $currentBalance = $this->vendorService->calculateBalance($item->vendor_id);
                        $newBalance = $currentBalance - $item->vendor_earning;

                        \App\Models\VendorBalanceLedger::create([
                            'vendor_id' => $item->vendor_id,
                            'transaction_type' => 'withdrawal', // acts as a debit
                            'amount' => $item->vendor_earning,
                            'balance_after' => $newBalance,
                            'order_id' => $item->order_id,
                            'order_item_id' => $item->id,
                            'description' => "Earning reversal from Order #{$item->order_id} - {$item->product->title}",
                        ]);
                    }
                });
            }

            Log::info("Vendor earnings reversed for order", [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to reverse vendor earnings for order", [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send Product Sold SMS notification to customer if enabled in settings
     */
    protected function sendOrderSmsNotifications(order $order): void
    {
        try {
            if (empty($order->phone)) {
                return;
            }

            // Determine if order belongs to a specific vendor/portal
            $senderUserId = null;
            if ($order->relationLoaded('orderItems') && $order->orderItems && $order->orderItems->isNotEmpty()) {
                $vendorId = $order->orderItems->first()->product?->vendor_id;
                if ($vendorId) {
                    $senderUserId = $vendorId;
                }
            }

            $smsService = app(SMSService::class);
            $smsService->sendEventSMS('product_sold', $order->phone, [
                'customer_name' => $order->name,
                'order_id' => $order->id,
                'total_amount' => $order->total_with_charge ?: $order->total,
            ], $senderUserId);
        } catch (\Throwable $e) {
            Log::error("OrderObserver: Failed to send product sold SMS", [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

