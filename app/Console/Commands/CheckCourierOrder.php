<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Console\Command;

class CheckCourierOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courier:check-order {order_id : The order ID to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check courier status and attempt to retrieve consignment ID for an order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        $order = Order::find($orderId);

        if (!$order) {
            $this->error("Order {$orderId} not found!");
            return 1;
        }

        $this->info("=== Order {$orderId} Courier Check ===");
        $this->line("");
        
        // Display current delivery data
        $this->info("Current Delivery Data:");
        $deliveryData = $order->delivery_data ?? [];
        $this->table(
            ['Key', 'Value'],
            collect($deliveryData)->map(function ($value, $key) {
                if (is_array($value)) {
                    return [$key, json_encode($value, JSON_PRETTY_PRINT)];
                }
                return [$key, $value ?? 'NULL'];
            })->toArray()
        );

        // Check if courier provider is set
        if (!isset($deliveryData['courier_provider'])) {
            $this->warn("No courier provider set for this order.");
            return 0;
        }

        $courierProvider = $deliveryData['courier_provider'];
        $this->info("Courier Provider: {$courierProvider}");
        
        // Check if consignment_id exists
        $consignmentId = $deliveryData['consignment_id'] ?? null;
        
        if ($consignmentId) {
            $this->info("Consignment ID: {$consignmentId}");
            
            // Try to fetch current status
            if ($this->confirm('Do you want to fetch the current courier status?', true)) {
                $this->info("Fetching status from {$courierProvider}...");
                try {
                    $delivery = DeliveryServiceManager::forProvider($courierProvider);
                    $response = $delivery->trackOrder($consignmentId);
                    
                    $this->info("Tracking Response:");
                    $this->line(json_encode($response, JSON_PRETTY_PRINT));
                    
                    // Update order with latest status
                    if ($courierProvider === 'pathao' && isset($response['data']['order_status'])) {
                        $order->courier_status = $response['data']['order_status'];
                        $order->courier_status_slug = $response['data']['order_status_slug'] ?? strtolower(str_replace(' ', '_', $response['data']['order_status']));
                        $order->courier_status_updated_at = now();
                        $order->courier_status_details = $response;
                        $order->save();
                        $this->info("✓ Order status updated!");
                    } elseif ($courierProvider === 'steadfast' && isset($response['delivery_status'])) {
                        $statusText = is_string($response['delivery_status']) 
                            ? ucfirst($response['delivery_status']) 
                            : ($response['delivery_status']['status'] ?? 'Unknown');
                        $order->courier_status = $statusText;
                        $order->courier_status_slug = strtolower(str_replace(' ', '_', $statusText));
                        $order->courier_status_updated_at = now();
                        $order->courier_status_details = $response;
                        $order->save();
                        $this->info("✓ Order status updated!");
                    }
                } catch (\Exception $e) {
                    $this->error("Error tracking order: " . $e->getMessage());
                }
            }
        } else {
            $this->warn("No consignment ID found for this order!");
            $this->line("");
            
            // Check if there's a courier_response with data
            $courierResponse = $deliveryData['courier_response'] ?? [];
            if (!empty($courierResponse)) {
                $this->info("Courier Response exists:");
                $this->line(json_encode($courierResponse, JSON_PRETTY_PRINT));
                
                // Try to extract consignment_id from response
                $extractedConsignmentId = null;
                if ($courierProvider === 'pathao') {
                    $extractedConsignmentId = $courierResponse['data']['consignment_id'] 
                        ?? $courierResponse['consignment_id'] 
                        ?? $courierResponse['data']['order']['consignment_id'] 
                        ?? null;
                } elseif ($courierProvider === 'steadfast') {
                    $extractedConsignmentId = $courierResponse['consignment']['consignment_id'] 
                        ?? $courierResponse['consignment_id'] 
                        ?? null;
                }
                
                if ($extractedConsignmentId) {
                    $this->info("Found consignment ID in response: {$extractedConsignmentId}");
                    
                    if ($this->confirm('Do you want to update the order with this consignment ID?', true)) {
                        $deliveryData['consignment_id'] = $extractedConsignmentId;
                        $deliveryData['tracking_code'] = $extractedConsignmentId;
                        $order->delivery_data = $deliveryData;
                        $order->save();
                        $this->info("✓ Order updated with consignment ID!");
                    }
                } else {
                    $this->warn("Could not extract consignment ID from response.");
                }
            } else {
                $this->warn("No courier response data found.");
                $this->line("");
                $this->warn("This order may need to be re-sent to the courier.");
            }
        }

        return 0;
    }
}

