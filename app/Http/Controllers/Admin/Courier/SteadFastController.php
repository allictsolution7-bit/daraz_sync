<?php

namespace App\Http\Controllers\Admin\Courier;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SteadFastController extends Controller
{
    /**
     * Format phone number to clean 11 digit mobile string
     */
    protected function formatPhone(?string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone ?? '');
        if (str_starts_with($cleaned, '880') && strlen($cleaned) === 13) {
            $cleaned = substr($cleaned, 2);
        }
        return $cleaned;
    }

    public function sendToCourier(Request $request)
    {
        $order = order::with('order_items.product')->findOrFail($request->order_id);
        $userId = Auth::id();
        $delivery = DeliveryServiceManager::forProvider('steadfast', $userId);

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => 'Steadfast integration not configured for your account. Please set up API keys in Delivery Settings.'
            ]);
        }

        // Calculate weight from order items
        $weight = 0;
        foreach ($order->order_items as $item) {
            $productWeight = $item->product->weight ?? 0.5;
            $weight += ($productWeight * $item->quantity);
        }
        if ($weight < 0.5) {
            $weight = 0.5;
        }

        // Calculate COD amount
        $codAmount = (float) match($order->payment_type) {
            'full_paid' => 0,
            'partial' => $order->due_amount,
            default => ($order->total_with_charge ?? $order->total ?? 0),
        };

        // Format parameters strictly adhering to Steadfast API specification
        $orderData = [
            'invoice'           => (string) ($order->invoice_no ?? $order->order_number ?? $order->id),
            'recipient_name'    => Str::limit($order->name ?? 'Customer', 98, ''),
            'recipient_phone'   => $this->formatPhone($order->phone),
            'recipient_address' => Str::limit($order->address ?? 'N/A', 248, ''),
            'cod_amount'        => $codAmount,
            'note'              => Str::limit($order->courier_note ?? '', 200, ''),
            'item_description'  => Str::limit($order->order_items->pluck('product.title')->filter()->implode(', '), 240, ''),
        ];

        try {
            $response = $delivery->createOrder($orderData);

            // Save delivery data to order
            $deliveryData = $order->delivery_data ?? [];
            $deliveryData['courier_provider'] = 'steadfast';
            $deliveryData['courier_response'] = $response;
            
            // Extract consignment_id and tracking_code from API response
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
        $userId = Auth::id();
        $delivery = DeliveryServiceManager::forProvider('steadfast', $userId);

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => 'Steadfast integration not configured for your account.'
            ]);
        }

        $bulkOrders = [];

        foreach ($orders as $order) {
            $codAmount = (float) match($order->payment_type) {
                'full_paid' => 0,
                'partial' => $order->due_amount,
                default => ($order->total_with_charge ?? $order->total ?? 0),
            };

            $bulkOrders[] = [
                'invoice'           => (string) ($order->invoice_no ?? $order->order_number ?? $order->id),
                'recipient_name'    => Str::limit($order->name ?? 'Customer', 98, ''),
                'recipient_phone'   => $this->formatPhone($order->phone),
                'recipient_address' => Str::limit($order->address ?? 'N/A', 248, ''),
                'cod_amount'        => $codAmount,
                'note'              => Str::limit($order->courier_note ?? '', 200, ''),
            ];
        }

        try {
            $response = $delivery->createBulkOrder($bulkOrders);

            // Save delivery data for each order
            foreach ($orders as $index => $order) {
                $result = $response['data'][$index] ?? $response[$index] ?? [];
                
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
        $userId = Auth::id();
        $delivery = DeliveryServiceManager::forProvider('steadfast', $userId);

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => 'Steadfast integration not configured for your account.'
            ]);
        }

        try {
            $response = $delivery->getBalance();
            return response()->json([
                'success' => true,
                'balance' => $response['current_balance'] ?? 0,
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
            $order = order::findOrFail($orderId);
            
            $trackingId = $order->delivery_data['consignment_id'] 
                ?? $order->delivery_data['tracking_code'] 
                ?? $order->id;
            
            if (!$trackingId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tracking information found for this order'
                ]);
            }

            $userId = Auth::id();
            $delivery = DeliveryServiceManager::forProvider('steadfast', $userId);

            if (!$delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Steadfast integration not configured for your account.'
                ]);
            }

            $response = $delivery->trackOrder((string) $trackingId);
            
            if (isset($response['delivery_status'])) {
                $statusText = is_string($response['delivery_status']) 
                    ? ucfirst(str_replace('_', ' ', $response['delivery_status']))
                    : ($response['delivery_status']['status'] ?? 'Unknown');
                
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
