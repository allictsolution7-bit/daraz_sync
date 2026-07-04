<?php

namespace App\Services\Delivery;

use Exception;
use App\Models\DeliveryIntegration;

class DeliveryServiceManager
{
    public static function resolve($provider, $credentials): DeliveryServiceInterface
    {
        return match ($provider) {
            'pathao' => new PathaoDeliveryService($credentials),
            'steadfast' => new SteadfastDeliveryService($credentials),
            null => throw new Exception('Not found'),
            default => throw new Exception("Unsupported provider")
        };
    }

    /**
     * Resolve a delivery service for a given provider (system-wide, not per user)
     */
    public static function forProvider($provider): ?DeliveryServiceInterface
    {
        $integration = \App\Models\DeliveryIntegration::where('provider', $provider)->first();
        if(! isset($integration)) return null;
        return self::resolve($provider, $integration?->credentials ?? []);
    }

    /**
     * @deprecated Use forProvider instead
     */
    public static function forUser($user, $provider): DeliveryServiceInterface
    {
        // Deprecated: always use forProvider for single-store systems
        return self::forProvider($provider);
    }
}