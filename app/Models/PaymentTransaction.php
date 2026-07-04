<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id', 'provider', 'type', 'status',
        'gateway_order_id', 'gateway_transaction_id',
        'amount', 'currency', 'raw_response',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'raw_response' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(order::class);
    }
}
