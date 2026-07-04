<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBalanceLedger extends Model
{
    protected $fillable = [
        'vendor_id',
        'transaction_type',
        'amount',
        'balance_after',
        'order_id',
        'order_item_id',
        'withdrawal_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Vendor relationship
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Admin who created this entry
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Polymorphic relationship to reference (order, withdrawal, etc.)
     */
    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Credit transactions (vendor earns money)
     */
    public function scopeCredit($query)
    {
        return $query->whereIn('transaction_type', ['sale', 'bonus', 'adjustment']);
    }

    /**
     * Scope: Debit transactions (vendor loses money)
     */
    public function scopeDebit($query)
    {
        return $query->whereIn('transaction_type', ['withdrawal', 'refund', 'penalty']);
    }

    /**
     * Scope: For specific vendor
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Check if this is a credit transaction
     */
    public function isCredit(): bool
    {
        return in_array($this->transaction_type, ['sale', 'bonus', 'adjustment']);
    }

    /**
     * Check if this is a debit transaction
     */
    public function isDebit(): bool
    {
        return in_array($this->transaction_type, ['withdrawal', 'refund', 'penalty']);
    }
}

