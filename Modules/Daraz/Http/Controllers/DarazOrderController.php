<?php

namespace Modules\Daraz\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Services\DarazApiService;
use Illuminate\Support\Facades\Log;

class DarazOrderController extends Controller
{
    protected DarazApiService $apiService;

    public function __construct(DarazApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display the Daraz Orders page.
     */
    public function index(Request $request)
    {
        $stores = DarazStore::forCurrentUser()
            ->active()
            ->get();

        $selectedStoreId = $request->get('store_id', $stores->first()?->id);

        return view('daraz::orders.index', compact('stores', 'selectedStoreId'));
    }

    /**
     * Fetch orders live from Daraz API.
     */
    public function fetchOrders(Request $request)
    {
        $storeId = $request->get('store_id');
        $store = DarazStore::forCurrentUser()->find($storeId);

        if (!$store || !$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not selected or not connected.',
            ], 400);
        }

        $params = [
            'created_after' => $request->get('created_after'),
            'created_before' => $request->get('created_before'),
            'update_after' => $request->get('update_after'),
            'update_before' => $request->get('update_before'),
            'status' => $request->get('status'),
            'limit' => (int) $request->get('limit', 20),
            'offset' => (int) $request->get('offset', 0),
        ];

        $response = $this->apiService->getOrders($store, $params);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Failed to fetch orders from Daraz API.',
                'raw' => $response,
            ], 400);
        }

        $rawOrders = $response['data']['orders'] ?? $response['data'] ?? [];

        return response()->json([
            'success' => true,
            'orders' => $rawOrders,
            'raw' => $response,
        ]);
    }

    /**
     * Fetch order items for a specific order_id.
     */
    public function fetchOrderItems(Request $request, string $orderId)
    {
        $storeId = $request->get('store_id');
        $store = DarazStore::forCurrentUser()->find($storeId);

        if (!$store || !$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected.',
            ], 400);
        }

        $response = $this->apiService->getOrderItems($store, $orderId);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Failed to fetch order items.',
            ], 400);
        }

        $items = $response['data'] ?? [];
        if (isset($items['order_items'])) {
            $items = $items['order_items'];
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'items' => $items,
        ]);
    }

    /**
     * Fetch order logistics details from Daraz API (/order/logistic/get).
     */
    public function fetchOrderLogistic(Request $request, string $orderId)
    {
        $storeId = $request->get('store_id');
        $store = DarazStore::forCurrentUser()->find($storeId);

        if (!$store || !$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected.',
            ], 400);
        }

        $response = $this->apiService->getOrderLogistic($store, $orderId);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Failed to fetch order logistics information.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'order_id' => $orderId,
            'logistic' => $response['data'] ?? [],
        ]);
    }

    /**
     * Download or view document (Invoice, AWB/ShippingLabel, PickList/CarrierManifest).
     */
    public function downloadDocument(Request $request)
    {
        $storeId = $request->get('store_id');
        $orderId = $request->get('order_id');
        $type = $request->get('type', 'invoice'); // awb, invoice, picklist
        $orderItemIdsParam = $request->get('order_item_ids'); // Optional comma separated or array

        $store = DarazStore::forCurrentUser()->find($storeId);

        if (!$store || !$store->isConnected()) {
            return response()->html('<h3>Error: Store is not connected or unauthorized.</h3>', 400);
        }

        // Map human-friendly doc types to Daraz API doc types
        $docType = match ($type) {
            'awb', 'shipping_label', 'shippingLabel' => 'shippingLabel',
            'picklist', 'manifest', 'carrierManifest' => 'carrierManifest',
            default => 'invoice',
        };

        // If order_item_ids not passed explicitly, query /order/items/get first
        $orderItemIds = [];
        if (!empty($orderItemIdsParam)) {
            $orderItemIds = is_array($orderItemIdsParam) 
                ? $orderItemIdsParam 
                : explode(',', $orderItemIdsParam);
        } else if (!empty($orderId)) {
            $itemsResponse = $this->apiService->getOrderItems($store, $orderId);
            if ($itemsResponse['success']) {
                $rawItems = $itemsResponse['data']['order_items'] ?? $itemsResponse['data'] ?? [];
                foreach ($rawItems as $item) {
                    if (isset($item['order_item_id'])) {
                        $orderItemIds[] = $item['order_item_id'];
                    }
                }
            }
        }

        if (empty($orderItemIds)) {
            return response()->html("<h3>Error: Could not retrieve Order Item IDs for Order ID #{$orderId}.</h3>", 400);
        }

        $docResponse = $this->apiService->getOrderDocument($store, $docType, $orderItemIds);

        if (!$docResponse['success']) {
            $err = htmlspecialchars($docResponse['error'] ?? 'Document generation failed');
            return response()->html("<h3>Daraz API Error: {$err}</h3>", 400);
        }

        $data = $docResponse['data']['document'] ?? $docResponse['data'] ?? [];
        $htmlContent = $data['html'] ?? $data['file'] ?? null;
        $mimeType = $data['mime_type'] ?? 'text/html';

        if (!$htmlContent && is_string($data)) {
            $htmlContent = $data;
        }

        if (!$htmlContent) {
            return response()->html('<h3>Document content empty or missing in Daraz response.</h3>', 404);
        }

        // Add auto-print script if requested
        if ($request->has('print')) {
            $htmlContent .= '<script>window.onload = function() { window.print(); };</script>';
        }

        return response($htmlContent, 200)
            ->header('Content-Type', $mimeType);
    }
}
