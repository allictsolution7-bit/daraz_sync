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
        $this->baseUrl = $credentials['base_url'] ?? 'https://portal.packzy.com/api/v1';
    }

    protected function getHeaders()
    {
        return [
            'Api-Key' => $this->credentials['api_key'] ?? '',
            'Secret-Key' => $this->credentials['secret_key'] ?? '',
            'Content-Type' => 'application/json',
        ];
    }

    public function createOrder(array $orderData)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/create_order', $orderData);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to create order: ' . $response->body());
    }

    public function createBulkOrder(array $orders)
    {
        $response = \Illuminate\Support\Facades\Http::withHeaders($this->getHeaders())
            ->post($this->baseUrl . '/create_order/bulk-order', [
                'data' => json_encode($orders)
            ]);
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to create bulk order: ' . $response->body());
    }

    public function trackOrder(string $trackingId)
    {
        // Try by consignment ID first (most common)
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_cid/' . $trackingId);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        // Try by invoice ID as fallback
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_invoice/' . $trackingId);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        // Try by tracking code as final fallback
        $response = Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/status_by_trackingcode/' . $trackingId);
            
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Failed to track order: ' . $response->body());
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

    public function getBalance()
    {
        $response = \Illuminate\Support\Facades\Http::withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/get_balance');
        if ($response->successful()) {
            return $response->json();
        }
        throw new \Exception('Failed to fetch balance: ' . $response->body());
    }

    public function getStores()
    {
        throw new \Exception('Not supported for Steadfast');
    }
} 