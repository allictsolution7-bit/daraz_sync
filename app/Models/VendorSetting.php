<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VendorSetting extends Model
{
    protected $fillable = [
        'vendor_id',
        'business_name',
        'store_slug',
        'business_email',
        'business_phone',
        'business_address',
        'tax_id',
        'business_license',
        'business_license_document',
        'commission_percent',
        'commission_fixed',
        'min_payout_amount',
        'payout_schedule',
        'bank_details',
        'auto_approve_products',
        'can_edit_after_approval',
        'can_manage_orders',
        'can_create_coupons',
        'can_see_customer_info',
        'is_active',
        'is_verified',
        'verified_at',
        'verified_by',
        'notification_preferences',
        'shipping_methods',
        'return_policy',
        'additional_config',
        // New fields for multi-seller system
        'default_commission_rate',
        'custom_min_commission_rate',
        'custom_max_commission_rate',
        'custom_min_withdrawal_amount',
        'payout_method',
        'payout_account_number',
        'payout_account_name',
        'payout_bank_name',
        'payout_branch_name',
        'payout_routing_number',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'commission_fixed' => 'decimal:2',
        'min_payout_amount' => 'decimal:2',
        'bank_details' => 'encrypted', // Encrypted JSON
        'auto_approve_products' => 'boolean',
        'can_edit_after_approval' => 'boolean',
        'can_manage_orders' => 'boolean',
        'can_create_coupons' => 'boolean',
        'can_see_customer_info' => 'boolean',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'notification_preferences' => 'array',
        'shipping_methods' => 'array',
        'return_policy' => 'array',
        'additional_config' => 'array',
        // New multi-seller casts
        'default_commission_rate' => 'decimal:2',
        'custom_min_commission_rate' => 'decimal:2',
        'custom_max_commission_rate' => 'decimal:2',
        'custom_min_withdrawal_amount' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating vendor settings
        static::creating(function ($vendorSetting) {
            if (empty($vendorSetting->store_slug) && $vendorSetting->vendor) {
                $vendorSetting->store_slug = Str::slug($vendorSetting->vendor->name);
                
                // Ensure uniqueness
                $count = 1;
                $originalSlug = $vendorSetting->store_slug;
                while (self::where('store_slug', $vendorSetting->store_slug)->exists()) {
                    $vendorSetting->store_slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Update slug when business_name changes (optional)
        static::updating(function ($vendorSetting) {
            if ($vendorSetting->isDirty('business_name') && !$vendorSetting->isDirty('store_slug')) {
                $newSlug = Str::slug($vendorSetting->business_name);
                
                // Ensure uniqueness
                $count = 1;
                $originalSlug = $newSlug;
                while (self::where('store_slug', $newSlug)->where('id', '!=', $vendorSetting->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $count;
                    $count++;
                }
                
                $vendorSetting->store_slug = $newSlug;
            }
        });
    }

    /**
     * Get the vendor user
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the admin who verified this vendor
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Calculate commission for given amount
     */
    public function calculateCommission(float $amount): float
    {
        $commission = 0;

        // Percentage commission
        if ($this->commission_percent > 0) {
            $commission += ($amount * $this->commission_percent) / 100;
        }

        // Fixed commission
        if ($this->commission_fixed > 0) {
            $commission += $this->commission_fixed;
        }

        return $commission;
    }

    /**
     * Calculate vendor payout (amount minus commission)
     */
    public function calculatePayout(float $amount): array
    {
        $commission = $this->calculateCommission($amount);
        $payout = $amount - $commission;

        return [
            'order_amount' => $amount,
            'commission' => $commission,
            'vendor_payout' => $payout,
        ];
    }

    /**
     * Get vendor settings by vendor ID
     */
    public static function getByVendor(int $vendorId)
    {
        return self::where('vendor_id', $vendorId)->first();
    }

    /**
     * Get all active vendors
     */
    public static function getActiveVendors()
    {
        return self::where('is_active', true)
            ->where('is_verified', true)
            ->with('vendor')
            ->get();
    }

    /**
     * Check if vendor has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->{$permission} ?? false;
    }

    /**
     * Get bank details (decrypted)
     */
    public function getBankDetailsArray(): ?array
    {
        if (!$this->bank_details) {
            return null;
        }

        // If using encrypted cast, it will be automatically decrypted
        if (is_string($this->bank_details)) {
            return json_decode($this->bank_details, true);
        }

        return $this->bank_details;
    }

    /**
     * Check if vendor can be paid out
     */
    public function canPayout(float $pendingAmount): bool
    {
        return $this->is_active 
            && $this->is_verified 
            && $pendingAmount >= $this->min_payout_amount;
    }

    /**
     * Verify the vendor
     */
    public function verify(int $adminId): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $adminId,
        ]);
    }

    // ==========================================
    // MULTI-SELLER HELPER METHODS
    // ==========================================

    /**
     * Get minimum commission rate for this vendor
     * Returns: custom value OR global setting
     */
    public function getMinCommissionRate(): float
    {
        return $this->custom_min_commission_rate 
            ?? VendorGlobalSetting::get('vendor_min_commission_rate', 10.00);
    }

    /**
     * Get maximum commission rate for this vendor
     * Returns: custom value OR global setting
     */
    public function getMaxCommissionRate(): float
    {
        return $this->custom_max_commission_rate 
            ?? VendorGlobalSetting::get('vendor_max_commission_rate', 30.00);
    }

    /**
     * Get default commission rate for this vendor
     * Returns: vendor's default OR global default
     */
    public function getDefaultCommissionRate(): float
    {
        return $this->default_commission_rate 
            ?? VendorGlobalSetting::get('vendor_global_commission_rate', 15.00);
    }

    /**
     * Validate if commission rate is within vendor's limits
     */
    public function validateCommissionRate(float $rate): bool
    {
        return $rate >= $this->getMinCommissionRate() 
            && $rate <= $this->getMaxCommissionRate();
    }

    /**
     * Get minimum withdrawal amount for this vendor
     * Returns: custom value OR global setting
     */
    public function getMinWithdrawalAmount(): float
    {
        return $this->custom_min_withdrawal_amount 
            ?? VendorGlobalSetting::get('vendor_min_withdrawal_amount', 500.00);
    }

    /**
     * Validate if withdrawal amount meets vendor's minimum
     */
    public function validateWithdrawalAmount(float $amount): bool
    {
        return $amount >= $this->getMinWithdrawalAmount();
    }

    /**
     * Check if vendor can withdraw given amount
     */
    public function canWithdraw(float $amount): bool
    {
        return $this->is_active 
            && $this->is_verified 
            && $this->validateWithdrawalAmount($amount);
    }

    /**
     * Check if vendor's products should be auto-approved
     * Priority:
     * 1. Vendor-specific setting
     * 2. Global verified-vendor setting
     * 3. Global auto-approve setting
     */
    public function shouldAutoApproveProducts(): bool
    {
        if ($this->auto_approve_products) {
            return true;
        }
        
        if (VendorGlobalSetting::get('vendor_auto_approve_verified_vendors', false) 
            && $this->is_verified) {
            return true;
        }
        
        return VendorGlobalSetting::get('vendor_auto_approve_products', false);
    }

    /**
     * Check if vendor has reached product limit
     */
    public function hasReachedProductLimit(): bool
    {
        $maxProducts = VendorGlobalSetting::get('vendor_max_products_per_vendor', 0);
        
        if ($maxProducts == 0) {
            return false; // Unlimited
        }
        
        $productCount = $this->vendor->products()->count();
        
        return $productCount >= $maxProducts;
    }

    /**
     * Get effective settings for vendor (for display)
     */
    public function getEffectiveSettings(): array
    {
        return [
            'default_commission' => $this->getDefaultCommissionRate(),
            'min_commission' => $this->getMinCommissionRate(),
            'max_commission' => $this->getMaxCommissionRate(),
            'min_withdrawal' => $this->getMinWithdrawalAmount(),
            'auto_approve_products' => $this->shouldAutoApproveProducts(),
            'product_limit' => VendorGlobalSetting::get('vendor_max_products_per_vendor', 0),
            'product_count' => $this->vendor->products()->count(),
            'has_reached_limit' => $this->hasReachedProductLimit(),
        ];
    }
}

