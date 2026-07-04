<?php

namespace App\Events;

use App\Models\order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnlinePaymentCompleted
{
    use Dispatchable, SerializesModels;

    public order $order;
    public string $provider;
    public ?string $gatewayTransactionId;

    public function __construct(order $order, string $provider, ?string $gatewayTransactionId = null)
    {
        $this->order = $order;
        $this->provider = $provider;
        $this->gatewayTransactionId = $gatewayTransactionId;
    }
}
