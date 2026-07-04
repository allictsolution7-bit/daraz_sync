<?php

namespace App\Mail;

use App\Models\order;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public order $order;
    public string $siteName;

    public function __construct(order $order)
    {
        $this->order = $order;
        $this->siteName = SiteSetting::get('general', 'site_name', config('app.name', 'Thikana Shop'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order #{$this->order->id} - Payment Confirmed",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-payment-confirmation',
        );
    }
}
