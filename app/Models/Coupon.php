<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'times_used',
        'valid_from',
        'valid_to',
        'is_active',
        'applicable_to',
        'applicable_ids',
        'excluded_ids',
        'exclude_sale_items',
        'first_order_only',
        'user_groups',
        'additional_config'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'exclude_sale_items' => 'boolean',
        'first_order_only' => 'boolean',
        'applicable_ids' => 'array',
        'excluded_ids' => 'array',
        'user_groups' => 'array',
        'additional_config' => 'array',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'times_used' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    /**
     * Check if coupon is currently valid
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Check validity period
        if ($this->valid_from && $now < $this->valid_from) {
            return false;
        }

        if ($this->valid_to && $now > $this->valid_to) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount for given order amount
     */
    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;

        switch ($this->type) {
            case 'percentage':
                $discount = ($orderAmount * $this->value) / 100;
                if ($this->max_discount_amount) {
                    $discount = min($discount, $this->max_discount_amount);
                }
                break;

            case 'fixed':
                $discount = $this->value;
                break;

            case 'free_shipping':
                // Shipping discount should be calculated elsewhere
                $discount = 0;
                break;
        }

        return $discount;
    }

    /**
     * Find coupon by code
     */
    public static function findByCode(string $code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Get active coupons
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('valid_to')
                    ->orWhere('valid_to', '>=', now());
            })
            ->get();
    }

    /**
     * Increment usage counter
     */
    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }
}

