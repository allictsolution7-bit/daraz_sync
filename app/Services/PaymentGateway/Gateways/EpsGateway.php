<?php

namespace App\Services\PaymentGateway\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\order;
use App\Services\PaymentGateway\PaymentResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EpsGateway implements PaymentGatewayInterface
{
    protected array $config;
    protected string $baseUrl;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = ($config['is_live'] ?? false)
            ? 'https://pgapi.eps.com.bd/v1'
            : 'https://sandboxpgapi.eps.com.bd/v1';
    }

    public function getProvider(): string
    {
        return 'eps';
    }

    public function getName(): string
    {
        return 'EPS - Easy Payment System';
    }

    public function initiatePayment(order $order, array $options = []): PaymentResult
    {
        $token = $this->getToken();

        if (!$token) {
            return PaymentResult::failed('Failed to obtain EPS authentication token');
        }

        $transactionId = 'THK' . $order->id . time();
        $xHash = $this->generateHash($transactionId);

        $productNames = [];
        foreach ($order->products as $product) {
            $productNames[] = $product->title;
        }

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'x-hash'        => $xHash,
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(30)->post($this->baseUrl . '/EPSEngine/InitializeEPS', [
            'merchantId'            => $this->config['additional']['merchant_id'] ?? '',
            'storeId'               => $this->config['additional']['store_id'] ?? '',
            'CustomerOrderId'       => 'ORD-' . $order->id,
            'merchantTransactionId' => $transactionId,
            'transactionTypeId'     => 1, // Web
            'totalAmount'           => (float) $order->total_with_charge,
            'successUrl'            => route('payment.callback', ['provider' => 'eps']),
            'failUrl'               => route('payment.callback', ['provider' => 'eps']),
            'cancelUrl'             => route('payment.callback', ['provider' => 'eps']),
            'customerName'          => $order->name ?? 'Customer',
            'customerEmail'         => $order->email ?? 'customer@example.com',
            'customerAddress'       => $order->address ?? '',
            'customerCity'          => $order->city ?? 'Dhaka',
            'customerState'         => $order->city ?? 'Dhaka',
            'customerPostcode'      => $order->postcode ?? '1200',
            'customerCountry'       => 'BD',
            'customerPhone'         => $order->phone ?? '',
            'productName'           => implode(', ', $productNames) ?: 'Order #' . $order->id,
            'productProfile'        => 'general',
            'productCategory'       => 'ecommerce',
        ]);

        $data = $response->json();

        if (!empty($data['RedirectURL'])) {
            return PaymentResult::redirect(
                $data['RedirectURL'],
                $transactionId, // Store merchantTransactionId as gateway_order_id for callback lookup
            );
        }

        $errorMsg = $data['ErrorMessage'] ?? $data['errorMessage'] ?? 'Failed to initialize EPS payment';
        Log::error('EPS InitializeEPS failed', ['response' => $data, 'order_id' => $order->id]);

        return PaymentResult::failed($errorMsg, $data ?? []);
    }

    public function handleCallback(array $payload): PaymentResult
    {
        $status = strtoupper($payload['Status'] ?? $payload['status'] ?? '');
        $merchantTransactionId = $payload['MerchantTransactionId'] ?? $payload['merchantTransactionId'] ?? '';

        if ($status === 'CANCEL' || $status === 'CANCELED') {
            return PaymentResult::cancelled('Payment was cancelled');
        }

        if ($status === 'FAILED' || $status === 'FAILURE') {
            return PaymentResult::failed('Payment failed', $payload);
        }

        // For SUCCESS or any other status, verify via API to be safe
        if ($merchantTransactionId) {
            return $this->verifyPayment($merchantTransactionId);
        }

        return PaymentResult::failed('No transaction ID received from EPS callback', $payload);
    }

    public function handleWebhook(array $payload, ?string $signature = null): PaymentResult
    {
        // EPS uses redirect-based callbacks, not server-to-server webhooks
        return $this->handleCallback($payload);
    }

    public function verifyPayment(string $gatewayTransactionId): PaymentResult
    {
        $token = $this->getToken();

        if (!$token) {
            return PaymentResult::failed('Failed to obtain EPS token for verification');
        }

        $xHash = $this->generateHash($gatewayTransactionId);

        $response = Http::withHeaders([
            'x-hash'        => $xHash,
            'Authorization' => 'Bearer ' . $token,
        ])->timeout(30)->get($this->baseUrl . '/EPSEngine/CheckMerchantTransactionStatus', [
            'merchantTransactionId' => $gatewayTransactionId,
        ]);

        $data = $response->json();
        $status = strtoupper($data['Status'] ?? $data['status'] ?? '');
        $amount = (float) ($data['TotalAmount'] ?? $data['totalAmount'] ?? 0);

        if ($status === 'SUCCESS' || $status === 'COMPLETED') {
            return PaymentResult::completed(
                gatewayTransactionId: $data['MerchantTransactionId'] ?? $gatewayTransactionId,
                amount: $amount,
                raw: $data,
            );
        }

        if ($status === 'CANCEL' || $status === 'CANCELED') {
            return PaymentResult::cancelled('Payment was cancelled');
        }

        $errorMsg = $data['ErrorMessage'] ?? $data['errorMessage'] ?? 'Payment verification failed';
        return PaymentResult::failed($errorMsg, $data ?? []);
    }

    public function refund(string $gatewayTransactionId, float $amount, string $reason = ''): PaymentResult
    {
        // EPS refund API not documented - to be implemented when available
        return PaymentResult::failed('Refund not yet supported for EPS');
    }

    public function calculateFee(float $amount): float
    {
        $percent = (float) ($this->config['fee_percent'] ?? 0);
        $fixed   = (float) ($this->config['fee_fixed'] ?? 0);
        return round(($amount * $percent / 100) + $fixed, 2);
    }

    public function supportsCurrency(string $currency): bool
    {
        return $currency === 'BDT';
    }

    /**
     * Generate HMAC-SHA512 hash as required by EPS API.
     */
    protected function generateHash(string $data): string
    {
        $hashKey = $this->config['additional']['hash_key'] ?? '';
        return base64_encode(hash_hmac('sha512', $data, $hashKey, true));
    }

    /**
     * Get authentication token from EPS API.
     */
    protected function getToken(): ?string
    {
        $username = $this->config['additional']['username'] ?? '';
        $xHash = $this->generateHash($username);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-hash'       => $xHash,
        ])->timeout(15)->post($this->baseUrl . '/Auth/GetToken', [
            'userName' => $username,
            'password' => $this->config['additional']['password'] ?? '',
        ]);

        $data = $response->json();

        if (!empty($data['token'])) {
            return $data['token'];
        }

        Log::error('EPS GetToken failed', ['response' => $data]);
        return null;
    }
}
