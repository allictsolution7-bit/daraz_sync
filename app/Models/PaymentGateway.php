<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'provider', 'name', 'is_enabled', 'is_live',
        'public_key', 'secret_key', 'webhook_secret',
        'currency', 'transaction_fee_percent', 'transaction_fee_fixed',
        'supported_currencies', 'additional_config', 'sort_order',
    ];

    protected $casts = [
        'is_enabled'              => 'boolean',
        'is_live'                 => 'boolean',
        'public_key'              => 'encrypted',
        'secret_key'              => 'encrypted',
        'webhook_secret'          => 'encrypted',
        'supported_currencies'    => 'array',
        'additional_config'       => 'array',
        'transaction_fee_percent' => 'decimal:2',
        'transaction_fee_fixed'   => 'decimal:2',
    ];

    protected $hidden = ['secret_key', 'webhook_secret'];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function calculateFee(float $amount): float
    {
        $percent = (float) $this->transaction_fee_percent;
        $fixed   = (float) $this->transaction_fee_fixed;
        return round(($amount * $percent / 100) + $fixed, 2);
    }
}
