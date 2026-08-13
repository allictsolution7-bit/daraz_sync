<?php

namespace App\Services\Delivery;

use Exception;
use App\Models\DeliveryIntegration;
use Illuminate\Support\Facades\Auth;

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
     * Resolve a delivery service for a given provider scoped to a specific user/vendor ID
     */
    public static function forProvider($provider, $userId = null, $disableFallback = false): ?DeliveryServiceInterface
    {
        $userId = $userId ?: Auth::id();

        // 1. Try to find active integration for specific user ID
        $integration = DeliveryIntegration::where('provider', $provider)
            ->where('is_active', true)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        // 2. Fallback to any active integration if specific user has no configuration
        if (!$integration && !$disableFallback) {
            $integration = DeliveryIntegration::where('provider', $provider)
                ->where('is_active', true)
                ->first();
        }

        if (!isset($integration)) return null;
        return self::resolve($provider, $integration->credentials ?? []);
    }

    /**
     * Get integration record for user or fallback
     */
    public static function getIntegration($provider, $userId = null, $disableFallback = false): ?DeliveryIntegration
    {
        $userId = $userId ?: Auth::id();

        $integration = DeliveryIntegration::where('provider', $provider)
            ->where('is_active', true)
            ->when($userId, function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();

        if (!$integration && !$disableFallback) {
            $integration = DeliveryIntegration::where('provider', $provider)
                ->where('is_active', true)
                ->first();
        }

        return $integration;
    }

    public static function forUser($user, $provider): ?DeliveryServiceInterface
    {
        $userId = is_object($user) ? $user->id : $user;
        return self::forProvider($provider, $userId);
    }
}