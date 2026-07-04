<?php

namespace App\Http\Controllers;

use App\Events\OnlinePaymentCompleted;
use App\Models\order;
use App\Models\PaymentTransaction;
use App\Services\PaymentGateway\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    /**
     * Initiate payment for an existing order.
     * Called by frontend JS after order is created.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'provider' => 'required|string',
        ]);

        $order = order::with('products')->findOrFail($request->order_id);

        if ($order->payment_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Order already paid']);
        }

        $gateway = PaymentGatewayManager::forProvider($request->provider);

        if (!$gateway) {
            Log::warning('Payment gateway not available', ['provider' => $request->provider, 'order_id' => $order->id]);
            return response()->json(['success' => false, 'message' => 'Payment gateway not available'], 400);
        }

        try {
            $result = $gateway->initiatePayment($order);
        } catch (\Exception $e) {
            Log::error('Payment initiation exception', [
                'provider' => $request->provider,
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
            ], 500);
        }

        // Log the initiation attempt
        PaymentTransaction::create([
            'order_id'         => $order->id,
            'provider'         => $request->provider,
            'type'             => 'initiate',
            'status'           => $result->status,
            'gateway_order_id' => $result->gatewayOrderId,
            'amount'           => $order->total_with_charge,
            'currency'         => 'BDT',
            'raw_response'     => $result->rawResponse,
        ]);

        // Store gateway_order_id on the order for callback matching
        if ($result->gatewayOrderId) {
            $order->update([
                'gateway_order_id' => $result->gatewayOrderId,
                'payment_method'   => $request->provider,
            ]);
        }

        if ($result->redirectUrl) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $result->redirectUrl,
            ]);
        }

        Log::warning('Payment initiation failed', [
            'provider' => $request->provider,
            'order_id' => $order->id,
            'status'   => $result->status,
            'message'  => $result->message,
        ]);

        return response()->json([
            'success' => false,
            'message' => $result->message ?? 'Failed to initiate payment',
        ], 400);
    }

    /**
     * Handle callback (customer returns from gateway).
     * EPS redirects here with query params: Status, MerchantTransactionId
     */
    public function callback(Request $request, string $provider)
    {
        $gateway = PaymentGatewayManager::forProvider($provider);

        if (!$gateway) {
            return redirect()->route('home')->with('error', 'Payment gateway not available');
        }

        $result = $gateway->handleCallback($request->all());

        // Find the order by gateway_order_id (merchantTransactionId for EPS)
        $gatewayOrderId = $request->get('MerchantTransactionId')
            ?? $request->get('merchantTransactionId')
            ?? $request->get('tran_id')
            ?? $request->get('paymentID');

        $order = order::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$order) {
            Log::error('Payment callback: order not found', [
                'provider'         => $provider,
                'gateway_order_id' => $gatewayOrderId,
                'payload'          => $request->all(),
            ]);
            return redirect()->route('home')->with('error', 'Order not found');
        }

        // Log the callback
        PaymentTransaction::create([
            'order_id'               => $order->id,
            'provider'               => $provider,
            'type'                   => 'callback',
            'status'                 => $result->status,
            'gateway_transaction_id' => $result->gatewayTransactionId,
            'gateway_order_id'       => $gatewayOrderId,
            'amount'                 => $result->amount,
            'currency'               => 'BDT',
            'raw_response'           => $result->rawResponse,
        ]);

        if ($result->success && $result->status === 'completed') {
            $order->update([
                'payment_status'         => 'paid',
                'payment_type'           => 'full_paid',
                'paid_amount'            => $result->amount ?? $order->total_with_charge,
                'due_amount'             => 0,
                'gateway_transaction_id' => $result->gatewayTransactionId,
            ]);

            event(new OnlinePaymentCompleted(
                $order->load('order_items.product', 'user'),
                $provider,
                $result->gatewayTransactionId
            ));

            return redirect()->route('order.thankYou', $order->id);
        }

        // Payment failed or cancelled
        $order->update(['payment_status' => 'failed']);

        return redirect()->route('order.thankYou', $order->id)
            ->with('payment_error', $result->message ?? 'Payment was not completed');
    }

    /**
     * Handle webhook/IPN (server-to-server notification).
     */
    public function webhook(Request $request, string $provider)
    {
        $gateway = PaymentGatewayManager::forProvider($provider);

        if (!$gateway) {
            return response('Gateway not found', 404);
        }

        $signature = $request->header('X-Signature')
            ?? $request->header('X-Webhook-Signature');

        $result = $gateway->handleWebhook($request->all(), $signature);

        $gatewayOrderId = $request->get('MerchantTransactionId')
            ?? $request->get('tran_id')
            ?? $request->get('paymentID');

        $order = order::where('gateway_order_id', $gatewayOrderId)->first();

        if ($order) {
            PaymentTransaction::create([
                'order_id'               => $order->id,
                'provider'               => $provider,
                'type'                   => 'webhook',
                'status'                 => $result->status,
                'gateway_transaction_id' => $result->gatewayTransactionId,
                'gateway_order_id'       => $gatewayOrderId,
                'amount'                 => $result->amount,
                'raw_response'           => $result->rawResponse,
            ]);

            if ($result->success && $result->status === 'completed' && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status'         => 'paid',
                    'payment_type'           => 'full_paid',
                    'paid_amount'            => $result->amount ?? $order->total_with_charge,
                    'due_amount'             => 0,
                    'gateway_transaction_id' => $result->gatewayTransactionId,
                ]);

                event(new OnlinePaymentCompleted(
                    $order->load('order_items.product', 'user'),
                    $provider,
                    $result->gatewayTransactionId
                ));
            }
        }

        return response('OK', 200);
    }
}
