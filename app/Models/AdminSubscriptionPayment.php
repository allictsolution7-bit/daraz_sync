<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSubscriptionPayment extends Model
{
    protected $table = 'admin_subscription_payments';

    protected $fillable = [
        'sub_id',
        'plan',
        'cycle',
        'price',
        'gateway',
        'phone',
        'trx_id',
        'status',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];
}
