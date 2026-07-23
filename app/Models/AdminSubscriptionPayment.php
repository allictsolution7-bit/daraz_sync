<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSubscriptionPayment extends Model
{
    protected $table = 'admin_subscription_payments';

    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'expiry_date' => 'date',
    ];
}
