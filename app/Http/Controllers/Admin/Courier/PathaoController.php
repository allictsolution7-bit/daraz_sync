<?php

namespace App\Http\Controllers\Admin\Courier;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PathaoController extends Controller
{
    public function getCourierCities(Request $request)
    {
        $courier = $request->courier;

        $service = DeliveryServiceManager::forProvider($courier);
        $cities = $service?->getCities();
        return response()->json($cities['data']['data'] ?? []);
    }

    public function getCourierZones(Request $request)
    {
        $courier = $request->courier;
        $cityId = $request->city_id;
        $service = DeliveryServiceManager::forProvider($courier);
        $zones = $service->getZones($cityId);
        return response()->json($zones['data']['data'] ?? []);
    }

    public function getCourierAreas(Request $request)
    {
        $courier = $request->courier;
        $zoneId = $request->zone_id;
        $service = DeliveryServiceManager::forProvider($courier);
        $areas = $service->getAreas($zoneId);
        return response()->json($areas['data']['data'] ?? []);
    }

    public function sendToCourier(Request $request)
    {
        $order = order::findOrFail($request->order_id);
        $userId = Auth::id();
        $delivery = DeliveryServiceManager::forProvider('pathao', $userId);

        // Get default store_id from credentials
        $integration = DeliveryServiceManager::getIntegration('pathao', $userId);
        $storeId = $integration?->credentials['store_id'] ?? null;

        if (!$storeId) {
            return response()->json([
                'success' => false,
                'message' => 'Pathao store_id not configured. Please add it in delivery integration settings.'
            ]);
        }

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
            'store_id'            => (int) $storeId,
            'merchant_order_id'   => (string) $order->id,
            'recipient_name'      => $order->name,
            'recipient_phone'     => $order->phone,
            'recipient_address'   => $order->address,
            'delivery_type'       => 48, // Normal delivery
            'item_type'           => 2,  // Parcel
            'special_instruction' => $order->courier_note ?? '',
            'item_quantity'       => $order->order_items->sum('quantity'),
            'item_weight'         => (float) $weight,
            'item_description'    => $order->order_items->pluck('product.title')->implode(', '),
            'amount_to_collect'   => (int) round(match($order->payment_type) {
                'full_paid' => 0,
                'partial' => $order->due_amount,
                default => $order->total_with_charge, // 'due' or old orders
            }),
        ];

        try {
            $response = $delivery->createOrder($orderData);

            // Save delivery data to order
            $deliveryData = $order->delivery_data ?? [];
            $deliveryData['courier_provider'] = 'pathao';
            $deliveryData['courier_response'] = $response;
            
            // Extract consignment_id from response
            $consignmentId = $response['data']['consignment_id'] 
                ?? $response['consignment_id'] 
                ?? $response['data']['order']['consignment_id'] 
                ?? null;
            
            if ($consignmentId) {
                $deliveryData['consignment_id'] = $consignmentId;
                $deliveryData['tracking_code'] = $consignmentId;
                $deliveryData['tracking_url'] = "https://merchant.pathao.com/tracking?consignment_id=" . $consignmentId;
                
                // Set initial courier status
                $order->courier_status = 'Order Created';
                $order->courier_status_slug = 'order_created';
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
            }
            
            $order->delivery_data = $deliveryData;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order sent to Pathao successfully!',
                'courier_response' => $response,
                'consignment_id' => $consignmentId
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
        $delivery = DeliveryServiceManager::forProvider('pathao');

        $userId = Auth::id();
        $integration = DeliveryServiceManager::getIntegration('pathao', $userId);
        $storeId = $integration?->credentials['store_id'] ?? null;

        if (!$storeId) {
            return response()->json([
                'success' => false,
                'message' => 'Pathao store_id not configured. Please add it in delivery integration settings.'
            ]);
        }

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
                'store_id'            => (int) $storeId,
                'merchant_order_id'   => (string) $order->id,
                'recipient_name'      => $order->name,
                'recipient_phone'     => $order->phone,
                'recipient_address'   => $order->address,
                'delivery_type'       => 48, // Normal delivery
                'item_type'           => 2,  // Parcel
                'special_instruction' => $order->courier_note ?? '',
                'item_quantity'       => $order->order_items->sum('quantity'),
                'item_weight'         => (float) $weight,
                'item_description'    => $order->order_items->pluck('product.title')->implode(', '),
                'amount_to_collect'   => (int) round(match($order->payment_type) {
                    'full_paid' => 0,
                    'partial' => $order->due_amount,
                    default => $order->total_with_charge,
                }),
            ];
        }

        try {
            // If only 1 order, use single order API for immediate response
            if (count($orders) === 1) {
                return $this->sendToCourier(new Request(['order_id' => $orders[0]->id]));
            }
            
            $response = $delivery->createBulkOrder($bulkOrders);

            // Check if response is async (202 Accepted)
            $isAsync = isset($response['code']) && $response['code'] == 202;
            
            if ($isAsync) {
                // Mark orders as pending courier confirmation
                foreach ($orders as $order) {
                    $order->delivery_data = array_merge($order->delivery_data ?? [], [
                        'courier_provider' => 'pathao',
                        'consignment_id'   => null,
                        'tracking_code'    => null,
                        'courier_response' => $response,
                        'bulk_order_status' => 'pending',
                        'bulk_order_submitted_at' => now()->toDateTimeString()
                    ]);
                    $order->save();
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk orders submitted to Pathao. Consignment IDs will be available later.',
                    'async' => true,
                    'courier_response' => $response
                ]);
            }

            // Synchronous response with immediate consignment IDs
            foreach ($orders as $index => $order) {
                $result = $response['data'][$index] ?? [];
                
                $consignmentId = $result['consignment_id'] 
                    ?? $result['order']['consignment_id'] 
                    ?? null;
                
                $order->delivery_data = array_merge($order->delivery_data ?? [], [
                    'courier_provider' => 'pathao',
                    'consignment_id'   => $consignmentId,
                    'tracking_code'    => $consignmentId,
                    'tracking_url'     => $consignmentId ? "https://merchant.pathao.com/tracking?consignment_id=" . $consignmentId : null,
                    'courier_response' => $result,
                ]);
                
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
                'message' => 'Bulk orders sent to Pathao successfully!',
                'courier_response' => $response
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Pathao Bulk Send Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Pathao Error: ' . $e->getMessage()
            ]);
        }
    }

    public function saveSendCourier(Request $request, $orderId)
    {
        $order = order::findOrFail($orderId);

        // Save courier info to delivery_data
        $order->delivery_data = [
            'courier_provider'   => $request->courier_provider,
            'courier_city_id'    => $request->courier_city_id,
            'courier_city_name'  => $request->courier_city_name,
            'courier_zone_id'    => $request->courier_zone_id,
            'courier_zone_name'  => $request->courier_zone_name,
            'courier_area_id'    => $request->courier_area_id,
            'courier_area_name'  => $request->courier_area_name,
        ];
        $order->save();

        // Send to courier using your service
        try {
            $service = \App\Services\Delivery\DeliveryServiceManager::forProvider($request->courier_provider);

            $response = $service->createOrder([
                'store_id'           => $request->pathao_store_id,
                'merchant_order_id'  => $order->id,
                'recipient_name'     => $order->name,
                'recipient_phone'    => $order->phone,
                'recipient_address'  => $order->address,
                'recipient_city'     => $request->courier_city_id,
                'recipient_zone'     => $request->courier_zone_id,
                'recipient_area'     => $request->courier_area_id,
                'delivery_type'      => 48, // TODO: Set dynamically if needed
                'item_type'          => 2,  // TODO: Set dynamically if needed
                'special_instruction' => $order->courier_note ?? '', // Courier note for delivery instructions
                'item_quantity'      => $order->order_items->sum('quantity'), // Or however you store quantity
                'item_weight' => $order->order_items->sum('weight') ?: 0.5,
                'item_description'   => $order->order_items->pluck('product.title')->implode(', '), // Or your logic
                'amount_to_collect' => (int) round($order->total_with_charge), // Or your logic for COD
            ]);

            if (isset($response['data']['consignment_id'])) {
                $deliveryData = $order->delivery_data ?? [];
                $deliveryData['consignment_id'] = $response['data']['consignment_id'];
                $deliveryData['courier_provider'] = 'pathao';
                $order->delivery_data = $deliveryData;
                
                // Set initial courier status
                $order->courier_status = 'Order Created';
                $order->courier_status_slug = 'order_created';
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
                $order->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Order sent to courier!',
                'courier_response' => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getPathaoStores()
    {
        $service = \App\Services\Delivery\DeliveryServiceManager::forProvider('pathao');
        $stores = $service->getStores();
        // Return only the array of stores
        return response()->json($stores['data']['data'] ?? []);
    }

    public function getBalance()
    {
        $delivery = \App\Services\Delivery\DeliveryServiceManager::forProvider('pathao');
        try {
            $response = $delivery->getBalance();
            return response()->json([
                'success' => true,
                'balance' => $response['data']['balance'] ?? null,
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
            $order = \App\Models\order::findOrFail($orderId);
            
            if (!isset($order->delivery_data['consignment_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No courier consignment ID found for this order'
                ]);
            }

            $delivery = \App\Services\Delivery\DeliveryServiceManager::forProvider('pathao');
            $response = $delivery->trackOrder($order->delivery_data['consignment_id']);
            
            if (isset($response['data']['order_status'])) {
                // Update order with latest status
                $statusText = $response['data']['order_status'];
                $statusSlug = $response['data']['order_status_slug'] ?? strtolower(str_replace(' ', '_', $statusText));
                
                $order->courier_status = $statusText;
                $order->courier_status_slug = $statusSlug;
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
                $order->save();
                
                return response()->json([
                    'success' => true,
                    'status' => $statusText,
                    'status_slug' => $statusSlug,
                    'updated_at' => $order->courier_status_updated_at->diffForHumans(),
                    'raw' => $response
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to fetch status from courier'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
