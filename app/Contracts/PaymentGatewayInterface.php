<?php

namespace App\Contracts;

use App\Models\order;
use App\Services\PaymentGateway\PaymentResult;

interface PaymentGatewayInterface
{
    public function getProvider(): string;

    public function getName(): string;

    public function initiatePayment(order $order, array $options = []): PaymentResult;

    public function handleCallback(array $payload): PaymentResult;

    public function handleWebhook(array $payload, ?string $signature = null): PaymentResult;

    public function verifyPayment(string $gatewayTransactionId): PaymentResult;

    public function refund(string $gatewayTransactionId, float $amount, string $reason = ''): PaymentResult;

    public function calculateFee(float $amount): float;

    public function supportsCurrency(string $currency): bool;
}
