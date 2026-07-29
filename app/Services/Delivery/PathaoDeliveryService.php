<?php

namespace App\Services\Delivery;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PathaoDeliveryService implements DeliveryServiceInterface
{
    protected $credentials;
    protected $baseUrl;
    protected $accessToken;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->baseUrl = $credentials['base_url'] ?? 'https://courier-api-sandbox.pathao.com';
    }

    /**
     * Issue or retrieve cached access token from Pathao API
     */
    public function issueAccessToken()
    {
        $cacheKey = 'pathao_access_token_' . md5($this->credentials['client_id'] ?? '');

        if (Cache::has($cacheKey)) {
            $this->accessToken = Cache::get($cacheKey);
            return ['access_token' => $this->accessToken];
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
                'client_id' => $this->credentials['client_id'] ?? '',
                'client_secret' => $this->credentials['client_secret'] ?? '',
                'grant_type' => 'password',
                'username' => $this->credentials['username'] ?? '',
                'password' => $this->credentials['password'] ?? '',
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->accessToken = $data['access_token'];
            $expiresIn = ($data['expires_in'] ?? 432000) - 300; // Cache 5 minutes less than expiry
            Cache::put($cacheKey, $this->accessToken, max(60, $expiresIn));
            return $data;
        }

        throw new \Exception('Pathao Auth Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * Create an order using Pathao API
     */
    public function createOrder(array $orderData)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = Http::withToken($this->accessToken)
            ->post($this->baseUrl . '/aladdin/api/v1/orders', $orderData);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to create order: ' . $response->body());
    }

    public function trackOrder(string $trackingId)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = Http::withToken($this->accessToken)
            ->get($this->baseUrl . "/aladdin/api/v1/orders/{$trackingId}/info");
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to track order: ' . $response->body());
    }

    /**
     * Fetch area list for a given zone from Pathao API
     */
    public function getAreas($zoneId)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->get($this->baseUrl . "/aladdin/api/v1/zones/{$zoneId}/area-list");
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch areas: ' . $response->body());
    }

    /**
     * Test method to demonstrate issuing a token and creating an order
     */
    public static function testSandboxOrder()
    {
        $service = new self([
            'base_url' => 'https://courier-api-sandbox.pathao.com',
            'client_id' => '7N1aMJQbWm',
            'client_secret' => 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39',
            'username' => 'test@pathao.com',
            'password' => 'lovePathao',
        ]);
        $service->issueAccessToken();
        // Simplified order data - city, zone, area are now optional
        $orderData = [
            'store_id' => 55945, // Valid store_id
            'merchant_order_id' => 'TEST123',
            'recipient_name' => 'Demo Recipient',
            'recipient_phone' => '01979542054',
            'recipient_address' => 'Uttara , Sector -24, Dhaka',
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => 'Need to Delivery before 5 PM',
            'item_quantity' => 1,
            'item_weight' => '0.5',
            'item_description' => 'this is a Cloth item, price- 3000',
            'amount_to_collect' => 900
        ];
        return $service->createOrder($orderData);
    }

    /**
     * Fetch merchant store info from Pathao API
     */
    public function getStores()
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->get($this->baseUrl . '/aladdin/api/v1/stores');
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch stores: ' . $response->body());
    }

    /**
     * Create a bulk order using Pathao API
     */
    public function createBulkOrder(array $orders)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->post($this->baseUrl . '/aladdin/api/v1/orders/bulk', [
                'orders' => $orders
            ]);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to create bulk order: ' . $response->body());
    }

    /**
     * Calculate price for an order using Pathao API
     */
    public function calculatePrice(array $priceData)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->post($this->baseUrl . '/aladdin/api/v1/merchant/price-plan', $priceData);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to calculate price: ' . $response->body());
    }

    /**
     * Static test for tracking an order
     */
    public static function testTrackOrder($consignmentId)
    {
        $service = new self([
            'base_url' => 'https://courier-api-sandbox.pathao.com',
            'client_id' => '7N1aMJQbWm',
            'client_secret' => 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39',
            'username' => 'test@pathao.com',
            'password' => 'lovePathao',
        ]);
        $service->issueAccessToken();
        return $service->trackOrder($consignmentId);
    }

    /**
     * Static test for creating a bulk order
     */
    public static function testBulkOrder()
    {
        $service = new self([
            'base_url' => 'https://courier-api-sandbox.pathao.com',
            'client_id' => '7N1aMJQbWm',
            'client_secret' => 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39',
            'username' => 'test@pathao.com',
            'password' => 'lovePathao',
        ]);
        $service->issueAccessToken();
        // Simplified orders - city, zone, area are now optional
        $orders = [
            [
                'store_id' => 55945,
                'merchant_order_id' => 'BULKTEST1',
                'recipient_name' => 'Bulk Recipient One',
                'recipient_phone' => '01979542054',
                'recipient_address' => 'Uttara , Sector -24, Dhaka',
                'delivery_type' => 48,
                'item_type' => 2,
                'special_instruction' => 'Bulk order 1',
                'item_quantity' => 1,
                'item_weight' => '0.5',
                'item_description' => 'Bulk item 1',
                'amount_to_collect' => 500
            ],
            [
                'store_id' => 55945,
                'merchant_order_id' => 'BULKTEST2',
                'recipient_name' => 'Bulk Recipient Two',
                'recipient_phone' => '01979542054',
                'recipient_address' => 'Uttara , Sector -24, Dhaka',
                'delivery_type' => 48,
                'item_type' => 2,
                'special_instruction' => 'Bulk order 2',
                'item_quantity' => 2,
                'item_weight' => '1.0',
                'item_description' => 'Bulk item 2',
                'amount_to_collect' => 1000
            ]
        ];
        return $service->createBulkOrder($orders);
    }

    /**
     * Static test for calculating price
     */
    public static function testCalculatePrice()
    {
        $service = new self([
            'base_url' => 'https://courier-api-sandbox.pathao.com',
            'client_id' => '7N1aMJQbWm',
            'client_secret' => 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39',
            'username' => 'test@pathao.com',
            'password' => 'lovePathao',
        ]);
        $service->issueAccessToken();
        $priceData = [
            'store_id' => 55945,
            'item_type' => 2,
            'delivery_type' => 48,
            'item_weight' => 0.5
            // city and zone are optional for price calculation
        ];
        return $service->calculatePrice($priceData);
    }

    /**
     * Fetch city list from Pathao API
     */
    public function getCities()
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->get($this->baseUrl . '/aladdin/api/v1/city-list');
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch cities: ' . $response->body());
    }

    /**
     * Fetch zone list for a given city from Pathao API
     */
    public function getZones($cityId)
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->get($this->baseUrl . "/aladdin/api/v1/cities/{$cityId}/zone-list");
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch zones: ' . $response->body());
    }

    public function getBalance()
    {
        if (!$this->accessToken) {
            $this->issueAccessToken();
        }
        $response = \Illuminate\Support\Facades\Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
            ->get($this->baseUrl . '/aladdin/api/v1/merchant/balance'); // Adjust endpoint as needed
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch Pathao balance: ' . $response->body());
    }
}
