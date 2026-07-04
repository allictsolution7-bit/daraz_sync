<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorWithdrawal extends Model
{
    protected $fillable = [
        'vendor_id',
        'amount',
        'method',
        'account_details',
        'note',
        'status',
        'admin_note',
        'approved_at',
        'paid_at',
        'processed_by',
        'rejection_reason',
        'processed_at',
        'transaction_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'account_details' => 'array',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Vendor who requested withdrawal
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Admin who processed this withdrawal
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Order items paid in this withdrawal
     */
    public function orderItems()
    {
        return $this->hasMany(order_item::class, 'paid_in_withdrawal_id');
    }

    /**
     * Check if pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Scope: Pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: For specific vendor
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Get account number from account_details
     */
    public function getAccountNumberAttribute()
    {
        return $this->account_details['account_number'] ?? null;
    }

    /**
     * Get account name from account_details
     */
    public function getAccountNameAttribute()
    {
        return $this->account_details['account_name'] ?? null;
    }

    /**
     * Get bank name from account_details
     */
    public function getBankNameAttribute()
    {
        return $this->account_details['bank_name'] ?? null;
    }

    /**
     * Get branch name from account_details
     */
    public function getBranchNameAttribute()
    {
        return $this->account_details['branch_name'] ?? null;
    }

    /**
     * Get routing number from account_details
     */
    public function getRoutingNumberAttribute()
    {
        return $this->account_details['routing_number'] ?? null;
    }

    /**
     * Get payout method (alias for method)
     */
    public function getPayoutMethodAttribute()
    {
        return $this->method;
    }

    /**
     * Get notes (alias for note)
     */
    public function getNotesAttribute()
    {
        return $this->note;
    }
}

