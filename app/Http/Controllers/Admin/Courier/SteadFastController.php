<?php

namespace App\Http\Controllers\Admin\Courier;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Http\Request;
use Psy\Readline\Hoa\Console;

class SteadFastController extends Controller
{
    public function sendToCourier(Request $request)
    {
        $order = order::with('order_items.product')->findOrFail($request->order_id);
        $delivery = DeliveryServiceManager::forProvider('steadfast');

        // Calculate weight from order items (using product weight)
        $weight = 0;
        foreach ($order->order_items as $item) {
            $productWeight = $item->product->weight ?? 0.5;
            $weight += ($productWeight * $item->quantity);
        }
        if ($weight < 0.5) {
            $weight = 0.5; // Minimum weight
        }

        $orderData = [
            'invoice'           => $order->id,
            'recipient_name'    => $order->name,
            'recipient_phone'   => $order->phone,
            'recipient_address' => $order->address,
            'cod_amount'        => (float) match($order->payment_type) {
                'full_paid' => 0,
                'partial' => $order->due_amount,
                default => $order->total_with_charge, // 'due' or old orders
            },
            'note'              => $order->courier_note ?? '',
            'item_description'  => $order->order_items->pluck('product.title')->implode(', '),
            'item_weight'       => (float) $weight,
        ];

        try {
            $response = $delivery->createOrder($orderData);

            // Save delivery data to order
            $deliveryData = $order->delivery_data ?? [];
            $deliveryData['courier_provider'] = 'steadfast';
            $deliveryData['courier_response'] = $response;
            
            // Extract consignment_id and tracking_code
            $consignmentId = $response['consignment']['consignment_id'] 
                ?? $response['consignment_id'] 
                ?? null;
            $trackingCode = $response['consignment']['tracking_code'] 
                ?? $response['tracking_code'] 
                ?? null;
            
            if ($consignmentId) {
                $deliveryData['consignment_id'] = $consignmentId;
            }
            if ($trackingCode) {
                $deliveryData['tracking_code'] = $trackingCode;
            }
            
            $order->delivery_data = $deliveryData;
            
            // Set initial courier status only if we have consignment_id
            if ($consignmentId) {
                $order->courier_status = 'Order Created';
                $order->courier_status_slug = 'order_created';
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
            }
            
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order sent to Steadfast successfully!',
                'courier_response' => $response,
                'consignment_id' => $consignmentId,
                'tracking_code' => $trackingCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendBulkToCourier(Request $request)
    {
        $orders = order::whereIn('id', $request->order_ids)->get();
        $delivery = DeliveryServiceManager::forProvider('steadfast');
        $bulkOrders = [];

        foreach ($orders as $order) {
            // Calculate weight from order items (using product weight)
            $weight = 0;
            foreach ($order->order_items as $item) {
                $productWeight = $item->product->weight ?? 0.5;
                $weight += ($productWeight * $item->quantity);
            }
            if ($weight < 0.5) {
                $weight = 0.5; // Minimum weight
            }

            $bulkOrders[] = [
                'invoice'           => $order->id,
                'recipient_name'    => $order->name,
                'recipient_phone'   => $order->phone,
                'recipient_address' => $order->address,
                'cod_amount'        => (float) match($order->payment_type) {
                    'full_paid' => 0,
                    'partial' => $order->due_amount,
                    default => $order->total_with_charge,
                },
                'note'              => $order->courier_note ?? '',
                'item_description'  => $order->order_items->pluck('product.title')->implode(', '),
                'item_weight'       => (float) $weight,
            ];
        }

        try {
            $response = $delivery->createBulkOrder($bulkOrders);

            // Save delivery data for each order
            foreach ($orders as $index => $order) {
                $result = $response['data'][$index] ?? $response[$index] ?? [];
                
                // Extract consignment_id and tracking_code
                $consignmentId = $result['consignment_id'] 
                    ?? $result['consignment']['consignment_id'] 
                    ?? null;
                $trackingCode = $result['tracking_code'] 
                    ?? $result['consignment']['tracking_code'] 
                    ?? null;
                
                $order->delivery_data = array_merge($order->delivery_data ?? [], [
                    'courier_provider' => 'steadfast',
                    'consignment_id'   => $consignmentId,
                    'tracking_code'    => $trackingCode,
                    'courier_response' => $result,
                ]);
                
                // Set initial courier status only if we have consignment_id
                if ($consignmentId) {
                    $order->courier_status = 'Order Created';
                    $order->courier_status_slug = 'order_created';
                    $order->courier_status_updated_at = now();
                    $order->courier_status_details = $result;
                }
                
                $order->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Bulk orders sent to Steadfast successfully!',
                'courier_response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function getBalance()
    {
        $delivery = \App\Services\Delivery\DeliveryServiceManager::forProvider('steadfast');
        try {
            $response = $delivery->getBalance();
            return response()->json([
                'success' => true,
                'balance' => $response['current_balance'] ?? null,
                'raw' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getCourierOrderStatus($orderId)
    {
        try {
            $order = \App\Models\Order::findOrFail($orderId);
            
            // Check for consignment_id, tracking_code, or use order ID as invoice
            $trackingId = $order->delivery_data['consignment_id'] 
                ?? $order->delivery_data['tracking_code'] 
                ?? $order->id;
            
            if (!$trackingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tracking information found for this order'
                ]);
            }

            $delivery = \App\Services\Delivery\DeliveryServiceManager::forProvider('steadfast');
            $response = $delivery->trackOrder($trackingId);
            
            // Steadfast API returns: {"status": 200, "delivery_status": "in_review"}
            if (isset($response['delivery_status'])) {
                $statusText = is_string($response['delivery_status']) 
                    ? ucfirst(str_replace('_', ' ', $response['delivery_status']))
                    : ($response['delivery_status']['status'] ?? 'Unknown');
                
                // Update order with latest status
                $order->courier_status = $statusText;
                $order->courier_status_slug = is_string($response['delivery_status']) 
                    ? $response['delivery_status'] 
                    : strtolower(str_replace(' ', '_', $statusText));
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
                $order->save();
                
                return response()->json([
                    'success' => true,
                    'status' => $statusText,
                    'status_slug' => $order->courier_status_slug,
                    'updated_at' => $order->courier_status_updated_at->diffForHumans(),
                    'raw' => $response
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch status from courier'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
