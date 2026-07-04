<?php

namespace Modules\Daraz\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Models\DarazStore;

class DarazApiService
{
    /**
     * Make a signed API request to Daraz.
     */
    public function request(DarazStore $store, string $action, array $params = [], string $method = 'POST'): array
    {
        if (!$store->access_token) {
            throw new \Exception('Store is not connected. Please authorize first.');
        }

        // Build full URL: base URL + action path (e.g., https://api.daraz.com.bd/rest/seller/get)
        $url = rtrim($store->getApiBaseUrl(), '/') . $action;
        $signedParams = $this->signRequest($store, $action, $params);

        try {
            $timeout = config('daraz.api.timeout', 30);

            $response = match(strtoupper($method)) {
                'GET' => Http::timeout($timeout)->get($url, $signedParams),
                default => Http::timeout($timeout)->asForm()->post($url, $signedParams),
            };

            $data = $response->json();

            if ($response->failed()) {
                Log::error('Daraz API Error', [
                    'store_id' => $store->id,
                    'action' => $action,
                    'status' => $response->status(),
                    'response' => $data,
                ]);

                return [
                    'success' => false,
                    'error' => $data['message'] ?? $data['error_description'] ?? 'API request failed',
                    'code' => $data['code'] ?? $response->status(),
                    'data' => $data,
                ];
            }

            // Check for API-level errors
            if (isset($data['code']) && $data['code'] !== '0') {
                Log::error('Daraz API Error Response', [
                    'store_id' => $store->id,
                    'action' => $action,
                    'code' => $data['code'],
                    'message' => $data['message'] ?? 'Unknown',
                    'full_response' => $data,
                ]);

                return [
                    'success' => false,
                    'error' => $data['message'] ?? 'Unknown API error',
                    'code' => $data['code'],
                    'data' => $data,
                ];
            }

            return [
                'success' => true,
                'data' => $data['data'] ?? $data,
                'raw' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('Daraz API Exception', [
                'store_id' => $store->id,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => 'EXCEPTION',
            ];
        }
    }

    /**
     * Sign a request with HMAC-SHA256.
     */
    protected function signRequest(DarazStore $store, string $action, array $params = []): array
    {
        $systemParams = [
            'app_key' => $store->app_key,
            'timestamp' => $this->getTimestamp(),
            'sign_method' => 'sha256',
            'access_token' => $store->access_token,
        ];

        $allParams = array_merge($systemParams, $params);

        // Sort parameters alphabetically
        ksort($allParams);

        // Build string to sign: action + concatenated key-value pairs
        $stringToSign = $action;
        foreach ($allParams as $key => $value) {
            if ($value !== null && $value !== '') {
                $stringToSign .= $key . $value;
            }
        }

        // Generate signature
        $signature = strtoupper(hash_hmac('sha256', $stringToSign, $store->app_secret));

        $allParams['sign'] = $signature;

        return $allParams;
    }

    /**
     * Get current timestamp in milliseconds (Unix epoch).
     * Daraz API requires timestamp in milliseconds, not formatted date.
     */
    protected function getTimestamp(): string
    {
        return (string) (int) (microtime(true) * 1000);
    }

    /**
     * Get products from Daraz store.
     */
    public function getProducts(DarazStore $store, int $offset = 0, int $limit = 100): array
    {
        return $this->request($store, '/products/get', [
            'filter' => 'all',
            'offset' => $offset,
            'limit' => $limit,
        ], 'GET');
    }

    /**
     * Get a single product by item ID.
     */
    public function getProduct(DarazStore $store, string $itemId): array
    {
        return $this->request($store, '/product/item/get', [
            'item_id' => $itemId,
        ], 'GET');
    }

    /**
     * Update price and quantity for products.
     *
     * @param DarazStore $store
     * @param array $skuData Array of ['seller_sku' => 'SKU', 'quantity' => 10, 'price' => 100.00]
     */
    public function updatePriceQuantity(DarazStore $store, array $skuData): array
    {
        $payload = $this->buildUpdatePayload($skuData);

        Log::info('Daraz API: Updating price/quantity', [
            'store_id' => $store->id,
            'sku_data' => $skuData,
            'payload' => $payload,
        ]);

        return $this->request($store, '/product/price_quantity/update', [
            'payload' => $payload,
        ]);
    }

    /**
     * Update only stock quantity.
     * Daraz API requires the numeric SkuId (not SellerSku or ShopSku).
     * This method fetches the product first to resolve the numeric SkuId.
     */
    public function updateStock(DarazStore $store, string $itemId, string $sellerSku, int $quantity): array
    {
        // Fetch product to get the numeric SkuId
        $numericSkuId = $this->resolveSkuId($store, $itemId, $sellerSku);

        if (!$numericSkuId) {
            return [
                'success' => false,
                'error' => "Could not resolve numeric SkuId for SellerSku: {$sellerSku}",
            ];
        }

        Log::info('Daraz API: Resolved SkuId', [
            'item_id' => $itemId,
            'seller_sku' => $sellerSku,
            'resolved_sku_id' => $numericSkuId,
        ]);

        return $this->updatePriceQuantity($store, [
            [
                'sku_id' => $numericSkuId,
                'quantity' => $quantity,
            ]
        ]);
    }

    /**
     * Resolve the numeric SkuId by fetching the product from Daraz.
     */
    protected function resolveSkuId(DarazStore $store, string $itemId, string $sellerSku): ?string
    {
        $result = $this->getProduct($store, $itemId);

        if (!$result['success']) {
            Log::error('Daraz API: Failed to fetch product for SkuId resolution', [
                'item_id' => $itemId,
                'error' => $result['error'] ?? 'Unknown',
            ]);
            return null;
        }

        $data = $result['data'] ?? [];
        $rawResponse = $result['raw'] ?? [];

        // Log full response structure for debugging
        Log::info('Daraz API: Product response for SkuId resolution', [
            'item_id' => $itemId,
            'data_keys' => is_array($data) ? array_keys($data) : 'not_array',
            'raw_keys' => is_array($rawResponse) ? array_keys($rawResponse) : 'not_array',
            'full_data' => $data,
        ]);

        // Handle different response structures
        $skus = $data['skus'] ?? $data['Skus'] ?? [];

        // If data is nested inside a product array
        if (empty($skus) && isset($data['products'])) {
            $product = $data['products'][0] ?? [];
            $skus = $product['skus'] ?? $product['Skus'] ?? [];
        }

        // If data itself is a single product with nested item
        if (empty($skus) && isset($data['item_id'])) {
            $skus = $data['skus'] ?? $data['Skus'] ?? [];
        }

        // Try raw response data path
        if (empty($skus) && isset($rawResponse['data'])) {
            $rawData = $rawResponse['data'];
            $skus = $rawData['skus'] ?? $rawData['Skus'] ?? [];

            if (empty($skus) && isset($rawData['products'])) {
                $product = $rawData['products'][0] ?? [];
                $skus = $product['skus'] ?? $product['Skus'] ?? [];
            }
        }

        foreach ($skus as $sku) {
            $skuSellerSku = $sku['SellerSku'] ?? $sku['seller_sku'] ?? null;
            $skuShopSku = $sku['ShopSku'] ?? $sku['shop_sku'] ?? null;
            $skuId = $sku['SkuId'] ?? $sku['sku_id'] ?? $sku['skuId'] ?? null;

            Log::info('Daraz API: Checking SKU match', [
                'sku_seller' => $skuSellerSku,
                'sku_shop' => $skuShopSku,
                'sku_id' => $skuId,
                'looking_for' => $sellerSku,
            ]);

            if ($skuSellerSku === $sellerSku || $skuShopSku === $sellerSku) {
                return (string) $skuId;
            }
        }

        Log::warning('Daraz API: SkuId not found in product', [
            'item_id' => $itemId,
            'seller_sku' => $sellerSku,
            'skus_count' => count($skus),
            'available_skus' => $skus,
        ]);

        return null;
    }

    /**
     * Build XML payload for update request.
     * Note: Daraz deprecated SellerSku, now requires SkuId (Shop SKU).
     */
    protected function buildUpdatePayload(array $skuData): string
    {
        $skusXml = '';
        foreach ($skuData as $sku) {
            $skusXml .= '<Sku>';

            // Use SkuId (Shop SKU) - Daraz deprecated SellerSku
            $skusXml .= '<SkuId>' . htmlspecialchars($sku['sku_id']) . '</SkuId>';

            if (isset($sku['quantity'])) {
                $skusXml .= '<Quantity>' . (int)$sku['quantity'] . '</Quantity>';
            }

            if (isset($sku['price'])) {
                $skusXml .= '<Price>' . number_format((float)$sku['price'], 2, '.', '') . '</Price>';
            }

            if (isset($sku['sale_price'])) {
                $skusXml .= '<SalePrice>' . number_format((float)$sku['sale_price'], 2, '.', '') . '</SalePrice>';
            }

            $skusXml .= '</Sku>';
        }

        return '<?xml version="1.0" encoding="UTF-8"?><Request><Product><Skus>' . $skusXml . '</Skus></Product></Request>';
    }

    /**
     * Get category tree from Daraz.
     */
    public function getCategoryTree(DarazStore $store): array
    {
        return $this->request($store, '/category/tree/get');
    }

    /**
     * Get seller information.
     */
    public function getSeller(DarazStore $store): array
    {
        return $this->request($store, '/seller/get', [], 'GET');
    }

    /**
     * Test connection by getting seller info.
     */
    public function testConnection(DarazStore $store): array
    {
        return $this->getSeller($store);
    }
}
