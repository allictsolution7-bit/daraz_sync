<?php

namespace App\Services\PaymentGateway;

use Exception;
use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use App\Services\PaymentGateway\Gateways\EpsGateway;

class PaymentGatewayManager
{
    /**
     * Resolve a gateway instance from provider name and config.
     */
    public static function resolve(string $provider, array $config): PaymentGatewayInterface
    {
        return match ($provider) {
            'eps' => new EpsGateway($config),
            // Future gateways: just add one line here
            // 'bkash_auto' => new BkashAutoGateway($config),
            // 'sslcommerz' => new SSLCommerzGateway($config),
            null    => throw new Exception('Payment provider not specified'),
            default => throw new Exception("Unsupported payment provider: {$provider}"),
        };
    }

    /**
     * Resolve a gateway from the database by provider name.
     */
    public static function forProvider(string $provider): ?PaymentGatewayInterface
    {
        $gateway = PaymentGateway::where('provider', $provider)
            ->where('is_enabled', true)
            ->first();

        if (!$gateway) {
            return null;
        }

        $config = [
            'public_key'     => $gateway->public_key,
            'secret_key'     => $gateway->secret_key,
            'webhook_secret' => $gateway->webhook_secret,
            'is_live'        => $gateway->is_live,
            'currency'       => $gateway->currency,
            'fee_percent'    => $gateway->transaction_fee_percent,
            'fee_fixed'      => $gateway->transaction_fee_fixed,
            'additional'     => $gateway->additional_config ?? [],
        ];

        return self::resolve($provider, $config);
    }

    /**
     * Get all enabled automated gateways (for displaying in checkout).
     */
    public static function getEnabledGateways()
    {
        return PaymentGateway::enabled()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Check if a payment method is an automated gateway (vs manual COD/bkash/nagad/rocket).
     */
    public static function isAutomatedGateway(string $paymentMethod): bool
    {
        return PaymentGateway::where('provider', $paymentMethod)
            ->where('is_enabled', true)
            ->exists();
    }
}
