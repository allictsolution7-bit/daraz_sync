<?php

namespace App\Services\Delivery;

interface DeliveryServiceInterface
{
    public function createOrder(array $orderData);
    public function trackOrder(string $trackingId);
    public function getCities();
    public function getZones($cityId);
    public function getAreas($zoneId);
    public function createBulkOrder(array $orders);
    public function getBalance();
    public function getStores();
}