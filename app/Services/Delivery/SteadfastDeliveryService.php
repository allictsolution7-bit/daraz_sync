<?php

namespace App\Services\Delivery;

use Illuminate\Support\Facades\Http;

class SteadfastDeliveryService implements DeliveryServiceInterface
{
    protected $credentials;
    protected $baseUrl = 'https://portal.packzy.com/api/v1';

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
        $this->baseUrl = rtrim($credentials['base_url'] ?? 'https://portal.packzy.com/api/v1', '/');
    }

    protected function getHeaders()
    {
        return [
            'Api-Key' => $this->credentials['api_key'] ?? '',
            'Secret-Key' => $this->credentials['secret_key'] ?? '',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * 1. Place a single order (POST /create_order)
     */
    public function createOrder(array $orderData)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/create_order', $orderData);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Order Creation Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 2. Place bulk orders (POST /create_order/bulk-order)
     * Max 500 items. $orders is array of items.
     */
    public function createBulkOrder(array $orders)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/create_order/bulk-order', [
                'data' => json_encode(array_slice($orders, 0, 500))
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Bulk Order Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 3. Check Delivery Status
     * Tries CID -> Invoice -> Tracking Code
     */
    public function trackOrder(string $trackingId)
    {
        // i) Try by consignment ID
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_cid/' . $trackingId);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        // ii) Try by invoice ID
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_invoice/' . $trackingId);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        // iii) Try by tracking code
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_trackingcode/' . $trackingId);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Steadfast Status Check Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 4. Check Current Balance (GET /get_balance)
     */
    public function getBalance()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/get_balance');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Balance Fetch Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 5. Create Return Request (POST /create_return_request)
     */
    public function createReturnRequest(string $trackingIdentifier, ?string $reason = null)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/create_return_request', array_filter([
                'consignment_id' => $trackingIdentifier,
                'reason' => $reason
            ]));

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Return Request Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 6. Get Return Requests (GET /get_return_requests)
     */
    public function getReturnRequests()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/get_return_requests');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Fetch Return Requests Failed: ' . ($response->json('message') ?? $response->body()));
    }

    /**
     * 7. Get Payments (GET /payments)
     */
    public function getPayments()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/payments');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Steadfast Fetch Payments Failed: ' . ($response->json('message') ?? $response->body()));
    }

    public function getCities()
    {
        throw new \Exception('Not supported for Steadfast');
    }
    public function getZones($cityId)
    {
        throw new \Exception('Not supported for Steadfast');
    }
    public function getAreas($zoneId)
    {
        throw new \Exception('Not supported for Steadfast');
    }
    public function getStores()
    {
        throw new \Exception('Not supported for Steadfast');
    }
}