<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'admin_id',
        'type',
        'amount',
        'payment_method',
        'transaction_id',
        'proof_file',
        'status',
        'admin_note',
        'is_seen',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_seen' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
