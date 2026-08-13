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
        $user = Auth::user();
        try {
            $this->processResellerWalletDeduction($order, $user);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        $isSelfDelivery = ($order->delivery_data['delivery_by'] ?? null) === 'reseller';
        $vendorId = $order->order_items->firstWhere('vendor_id', '!=', null)->vendor_id ?? Auth::id();
        $delivery = DeliveryServiceManager::forProvider('steadfast', $vendorId, $isSelfDelivery);

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

        $itemNames = $order->order_items->map(function($item) {
            return ($item->product->title ?? $item->product_name ?? 'Item') . ($item->quantity > 1 ? ' x' . $item->quantity : '');
        })->filter()->implode(', ');

        $totalLot = $order->order_items->sum('quantity') ?: 1;

        $baseInvoice = (string) ($order->invoice_no ?? $order->order_number ?? $order->id);
        // If order had previous delivery_data (e.g. was previously sent/cancelled), append unique suffix so Steadfast accepts it
        $invoice = !empty($order->delivery_data['consignment_id']) || !empty($order->delivery_data['courier_response'])
            ? $baseInvoice . '-' . time()
            : $baseInvoice;

        // Format parameters strictly adhering to Steadfast API specification
        $orderData = [
            'invoice'           => $invoice,
            'recipient_name'    => Str::limit($order->name ?? 'Customer', 98, ''),
            'recipient_phone'   => $this->formatPhone($order->phone),
            'recipient_address' => Str::limit($order->address ?? 'N/A', 248, ''),
            'cod_amount'        => $codAmount,
            'note'              => Str::limit($order->courier_note ?? '', 200, ''),
            'item_description'  => Str::limit($itemNames ?: 'Products', 240, ''),
            'total_lot'         => $totalLot,
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

            $trackingUrl = $response['consignment']['tracking_url']
                ?? $response['consignment']['tracking_link']
                ?? $response['tracking_url']
                ?? $response['tracking_link']
                ?? ($trackingCode ? "https://steadfast.com.bd/tl/" . $trackingCode : null);
            
            if ($consignmentId) {
                $deliveryData['consignment_id'] = $consignmentId;
            }
            if ($trackingCode) {
                $deliveryData['tracking_code'] = $trackingCode;
            }
            if ($trackingUrl) {
                $deliveryData['tracking_url'] = $trackingUrl;
            }
            
            $order->delivery_data = $deliveryData;
            
            if ($consignmentId) {
                $order->courier_status = 'Order Created';
                $order->courier_status_slug = 'order_created';
                $order->courier_status_updated_at = now();
                $order->courier_status_details = $response;
            }
            
            $order->save();

            \Illuminate\Support\Facades\Log::info('Steadfast API Response (Single Send)', [
                'order_id' => $order->id,
                'consignment_id' => $consignmentId,
                'tracking_code' => $trackingCode,
                'response' => $response
            ]);

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
        $user = Auth::user();
        try {
            foreach ($orders as $order) {
                $this->processResellerWalletDeduction($order, $user);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        $firstOrder = $orders->first();
        $isSelfDelivery = $firstOrder ? (($firstOrder->delivery_data['delivery_by'] ?? null) === 'reseller') : false;
        $vendorId = $firstOrder ? ($firstOrder->order_items->firstWhere('vendor_id', '!=', null)->vendor_id ?? Auth::id()) : Auth::id();
        $delivery = DeliveryServiceManager::forProvider('steadfast', $vendorId, $isSelfDelivery);

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

            $itemNames = $order->order_items->map(function($item) {
                return ($item->product->title ?? $item->product_name ?? 'Item') . ($item->quantity > 1 ? ' x' . $item->quantity : '');
            })->filter()->implode(', ');

            $totalLot = $order->order_items->sum('quantity') ?: 1;

            $baseInvoice = (string) ($order->invoice_no ?? $order->order_number ?? $order->id);
            $invoice = !empty($order->delivery_data['consignment_id']) || !empty($order->delivery_data['courier_response'])
                ? $baseInvoice . '-' . time()
                : $baseInvoice;

            $bulkOrders[] = [
                'invoice'           => $invoice,
                'recipient_name'    => Str::limit($order->name ?? 'Customer', 98, ''),
                'recipient_phone'   => $this->formatPhone($order->phone),
                'recipient_address' => Str::limit($order->address ?? 'N/A', 248, ''),
                'cod_amount'        => $codAmount,
                'note'              => Str::limit($order->courier_note ?? '', 200, ''),
                'item_description'  => Str::limit($itemNames ?: 'Products', 240, ''),
                'total_lot'         => $totalLot,
            ];
        }

        try {
            $response = $delivery->createBulkOrder($bulkOrders);

            // Save delivery data for each order
            foreach ($orders as $index => $order) {
                // Bulk API response can return array of items, or data key indexed by order invoice / array index
                $result = $response['data'][$index] 
                    ?? $response[$index] 
                    ?? $response['data'][$order->invoice_no] 
                    ?? $response['data'][$order->id] 
                    ?? $response;
                
                $consignmentId = $result['consignment_id'] 
                    ?? $result['consignment']['consignment_id'] 
                    ?? ($result['status'] == 200 ? ($result['id'] ?? null) : null);

                $trackingCode = $result['tracking_code'] 
                    ?? $result['consignment']['tracking_code'] 
                    ?? $consignmentId;
                
                $trackingUrl = $result['tracking_url']
                    ?? $result['tracking_link']
                    ?? $result['consignment']['tracking_url']
                    ?? $result['consignment']['tracking_link']
                    ?? ($trackingCode ? "https://steadfast.com.bd/tl/" . $trackingCode : null);

                $order->delivery_data = array_merge($order->delivery_data ?? [], [
                    'courier_provider' => 'steadfast',
                    'consignment_id'   => $consignmentId,
                    'tracking_code'    => $trackingCode,
                    'tracking_url'     => $trackingUrl,
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
        $user = Auth::user();
        $disableFallback = $user && $user->hasRole('reseller');
        $delivery = DeliveryServiceManager::forProvider('steadfast', $userId, $disableFallback);

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

            $isSelfDelivery = ($order->delivery_data['delivery_by'] ?? null) === 'reseller';
            $vendorId = $order->order_items->firstWhere('vendor_id', '!=', null)->vendor_id ?? Auth::id();
            $delivery = DeliveryServiceManager::forProvider('steadfast', $vendorId, $isSelfDelivery);

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

                $rawStatus = strtolower($order->courier_status_slug);
                $isCleared = false;
                if (in_array($rawStatus, ['unknown', 'not_found', 'invalid', '404', 'cancelled'])) {
                    $order->courier_status = null;
                    $order->courier_status_slug = null;
                    $order->courier_status_details = null;
                    $order->delivery_data = null;
                    $isCleared = true;
                }
                
                $order->save();
                
                return response()->json([
                    'success' => true,
                    'status' => $isCleared ? 'Cleared' : $statusText,
                    'status_slug' => $isCleared ? null : $order->courier_status_slug,
                    'updated_at' => $order->courier_status_updated_at ? $order->courier_status_updated_at->diffForHumans() : now()->diffForHumans(),
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

    private function processResellerWalletDeduction($order, $user)
    {
        if ($user && $user->hasRole('reseller')) {
            $deliveryData = is_string($order->delivery_data) 
                ? json_decode($order->delivery_data, true) 
                : ($order->delivery_data ?? []);

            $deducted = $deliveryData['reseller_cost_deducted'] ?? 0;
            if ($deducted <= 0) {
                $cost = (float) $order->order_items()->sum('total_cost');
                if ($cost > 0) {
                    if ($user->wallet_balance < $cost) {
                        throw new \Exception("Insufficient wallet balance for Order #{$order->id}. You need at least ৳" . number_format($cost, 2) . " but you only have ৳" . number_format($user->wallet_balance, 2) . ".");
                    }

                    // Decrement wallet balance
                    $user->decrement('wallet_balance', $cost);

                    // Log transaction
                    \App\Models\VendorWalletTransaction::create([
                        'vendor_id' => $user->id,
                        'type' => 'reseller_pos_payment',
                        'amount' => $cost,
                        'status' => 'approved',
                        'admin_note' => "Paid for Reseller POS Order #{$order->id} (Self-Dispatch). Products cost: ৳" . number_format($cost, 2),
                    ]);

                    $deliveryData['reseller_cost_deducted'] = $cost;
                }
            }

            // Always ensure delivery_by is set to reseller
            $deliveryData['delivery_by'] = 'reseller';
            $order->delivery_data = $deliveryData;
            $order->status = 'processing'; // Mark order as approved/processing
            $order->save();
        }
    }
}
