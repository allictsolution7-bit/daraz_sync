<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Console\Command;

class ResendCourierOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courier:resend-order {order_id : The order ID to resend} {--provider= : Force specific courier provider (pathao/steadfast)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-send an order to the courier service (useful for fixing missing consignment IDs)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::with('order_items.product')->find($orderId);

        if (!$order) {
            $this->error("Order {$orderId} not found!");
            return 1;
        }

        $this->info("=== Re-sending Order {$orderId} to Courier ===");
        $this->line("");
        
        // Determine courier provider
        $provider = $this->option('provider') ?? $order->delivery_data['courier_provider'] ?? null;
        
        if (!$provider) {
            $provider = $this->choice(
                'Select courier provider',
                ['pathao', 'steadfast'],
                0
            );
        }

        $this->info("Courier Provider: {$provider}");
        
        // Confirm before sending
        if (!$this->confirm("Are you sure you want to send this order to {$provider}?", true)) {
            $this->warn("Operation cancelled.");
            return 0;
        }

        try {
            $delivery = DeliveryServiceManager::forProvider($provider);

            // Calculate weight from order items
            $weight = 0;
            foreach ($order->order_items as $item) {
                $productWeight = $item->product->weight ?? 0.5;
                $weight += ($productWeight * $item->quantity);
            }
            if ($weight < 0.5) {
                $weight = 0.5;
            }

            // Build order data based on provider
            if ($provider === 'pathao') {
                $integration = \App\Models\DeliveryIntegration::where('provider', 'pathao')->where('is_active', true)->first();
                $storeId = $integration?->credentials['store_id'] ?? null;

                if (!$storeId) {
                    $this->error('Pathao store_id not configured. Please add it in delivery integration settings.');
                    return 1;
                }

                $orderData = [
                    'store_id'            => (int) $storeId,
                    'merchant_order_id'   => (string) $order->id,
                    'recipient_name'      => $order->name,
                    'recipient_phone'     => $order->phone,
                    'recipient_address'   => $order->address,
                    'delivery_type'       => 48,
                    'item_type'           => 2,
                    'special_instruction' => $order->courier_note ?? '',
                    'item_quantity'       => $order->order_items->sum('quantity'),
                    'item_weight'         => (float) $weight,
                    'item_description'    => $order->order_items->pluck('product.title')->implode(', '),
                    'amount_to_collect'   => (int) round($order->total_with_charge),
                ];
            } else {
                // Steadfast
                $orderData = [
                    'invoice'           => $order->id,
                    'recipient_name'    => $order->name,
                    'recipient_phone'   => $order->phone,
                    'recipient_address' => $order->address,
                    'cod_amount'        => (float) $order->total_with_charge,
                    'note'              => $order->courier_note ?? '',
                    'item_description'  => $order->order_items->pluck('product.title')->implode(', '),
                    'item_weight'       => (float) $weight,
                ];
            }

            $this->info("Sending order to {$provider}...");
            $this->line(json_encode($orderData, JSON_PRETTY_PRINT));
            $this->line("");

            $response = $delivery->createOrder($orderData);

            $this->info("Response received:");
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            $this->line("");

            // Extract consignment ID
            $consignmentId = null;
            $trackingCode = null;

            if ($provider === 'pathao') {
                $consignmentId = $response['data']['consignment_id'] 
                    ?? $response['consignment_id'] 
                    ?? $response['data']['order']['consignment_id'] 
                    ?? null;
                $trackingCode = $consignmentId;
            } else {
                $consignmentId = $response['consignment']['consignment_id'] 
                    ?? $response['consignment_id'] 
                    ?? null;
                $trackingCode = $response['consignment']['tracking_code'] 
                    ?? $response['tracking_code'] 
                    ?? null;
            }

            // Update order
            $deliveryData = $order->delivery_data ?? [];
            $deliveryData['courier_provider'] = $provider;
            $deliveryData['courier_response'] = $response;
            $deliveryData['consignment_id'] = $consignmentId;
            $deliveryData['tracking_code'] = $trackingCode ?? $consignmentId;
            $order->delivery_data = $deliveryData;

            if ($consignmentId) {
                $order->courier_status = 'Order Created';
                $order->courier_status_slug = 'order_created';
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
            }

            $order->save();

            if ($consignmentId) {
                $this->newLine();
                $this->info("✓ Order sent successfully!");
                $this->info("Consignment ID: {$consignmentId}");
                if ($trackingCode && $trackingCode !== $consignmentId) {
                    $this->info("Tracking Code: {$trackingCode}");
                }
            } else {
                $this->warn("Order sent but consignment ID not found in response!");
                $this->warn("Please check the logs and response above.");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Error sending order: " . $e->getMessage());
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}

