<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncCourierStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courier:sync-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync status for active orders sent to couriers (Steadfast, Pathao, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Courier Status Sync...");

        // Fetch orders that are sent to a courier but not in a final status (Delivered/Cancelled)
        $orders = Order::with('order_items')->whereNotNull('delivery_data')
            ->whereNotIn('order_status', ['delivered', 'cancelled', 'returned'])
            ->get();

        $updatedCount = 0;

        foreach ($orders as $order) {
            $deliveryData = $order->delivery_data;
            if (!is_array($deliveryData)) {
                $deliveryData = json_decode($deliveryData, true) ?? [];
            }

            $provider = $deliveryData['courier_provider'] ?? null;
            $consignmentId = $deliveryData['consignment_id'] ?? null;

            if (!$provider || !$consignmentId) {
                continue;
            }

            try {
                $isSelfDelivery = ($deliveryData['delivery_by'] ?? null) === 'reseller';
                $vendorId = $order->order_items->firstWhere('vendor_id', '!=', null)->vendor_id ?? ($order->user_id ?? 1);
                $delivery = DeliveryServiceManager::forProvider($provider, $vendorId, $isSelfDelivery);
                if (!$delivery) {
                    continue;
                }
                $response = $delivery->trackOrder((string)$consignmentId);

                if ($provider === 'steadfast' && isset($response['delivery_status'])) {
                    $statusText = is_string($response['delivery_status']) 
                        ? ucfirst($response['delivery_status']) 
                        : ($response['delivery_status']['status'] ?? 'Unknown');

                    $order->courier_status = $statusText;
                    $order->courier_status_slug = strtolower(str_replace(' ', '_', $statusText));
                    $order->courier_status_updated_at = now();
                    $order->courier_status_details = $response;

                    // If status is unknown/invalid/not found/cancelled, we should clear delivery data to allow re-delivery!
                    $rawStatus = strtolower($order->courier_status_slug);
                    if (in_array($rawStatus, ['unknown', 'not_found', 'invalid', '404', 'cancelled'])) {
                        $order->courier_status = null;
                        $order->courier_status_slug = null;
                        $order->courier_status_details = null;
                        $order->delivery_data = null;
                    } else {
                        // If delivered, update order status to delivered
                        if (in_array(strtolower($statusText), ['delivered', 'partial_delivered', 'completed'])) {
                            $order->order_status = 'delivered';
                        }
                    }

                    $order->save();
                    $updatedCount++;
                } elseif ($provider === 'pathao' && isset($response['data']['order_status'])) {
                    $statusText = $response['data']['order_status'];
                    $order->courier_status = $statusText;
                    $order->courier_status_slug = $response['data']['order_status_slug'] ?? strtolower(str_replace(' ', '_', $statusText));
                    $order->courier_status_updated_at = now();
                    $order->courier_status_details = $response;

                    // If status is unknown/invalid/not found/cancelled, we should clear delivery data to allow re-delivery!
                    $rawStatus = strtolower($order->courier_status_slug);
                    if (in_array($rawStatus, ['unknown', 'not_found', 'invalid', '404', 'cancelled'])) {
                        $order->courier_status = null;
                        $order->courier_status_slug = null;
                        $order->courier_status_details = null;
                        $order->delivery_data = null;
                    } else {
                        if (strtolower($statusText) === 'delivered') {
                            $order->order_status = 'delivered';
                        }
                    }

                    $order->save();
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Courier sync error for order {$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Sync completed. Updated {$updatedCount} orders.");
        return 0;
    }
}
