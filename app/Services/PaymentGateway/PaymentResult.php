<?php

namespace App\Services\PaymentGateway;

class PaymentResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $status,                  // 'pending', 'completed', 'failed', 'cancelled'
        public readonly ?string $gatewayTransactionId = null,
        public readonly ?string $gatewayOrderId = null,
        public readonly ?string $redirectUrl = null,
        public readonly ?float $amount = null,
        public readonly ?string $currency = null,
        public readonly ?string $message = null,
        public readonly array $rawResponse = [],
    ) {}

    public static function redirect(string $url, ?string $gatewayOrderId = null): self
    {
        return new self(
            success: true,
            status: 'pending',
            redirectUrl: $url,
            gatewayOrderId: $gatewayOrderId,
        );
    }

    public static function completed(string $gatewayTransactionId, float $amount, array $raw = []): self
    {
        return new self(
            success: true,
            status: 'completed',
            gatewayTransactionId: $gatewayTransactionId,
            amount: $amount,
            rawResponse: $raw,
        );
    }

    public static function failed(string $message, array $raw = []): self
    {
        return new self(
            success: false,
            status: 'failed',
            message: $message,
            rawResponse: $raw,
        );
    }

    public static function cancelled(string $message = 'Payment cancelled by user'): self
    {
        return new self(
            success: false,
            status: 'cancelled',
            message: $message,
        );
    }
}
