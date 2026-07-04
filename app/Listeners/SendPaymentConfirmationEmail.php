<?php

namespace App\Listeners;

use App\Events\OnlinePaymentCompleted;
use App\Mail\OrderPaymentConfirmation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendPaymentConfirmationEmail
{
    public function handle(OnlinePaymentCompleted $event): void
    {
        try {
            $order = $event->order->load('order_items.product', 'user');
            $user = $order->user;

            if (!$user || !$user->email) {
                return;
            }

            // Skip guest placeholder emails (guest_xxxx@domain.com)
            if (Str::startsWith($user->email, 'guest_')) {
                return;
            }

            Mail::to($user->email)->send(new OrderPaymentConfirmation($order));
        } catch (\Exception $e) {
            Log::warning('Payment confirmation email failed: ' . $e->getMessage(), [
                'order_id' => $event->order->id,
                'provider' => $event->provider,
            ]);
        }
    }
}
