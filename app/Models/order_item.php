<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int|null $option_id
 * @property int|null $combination_id
 * @property int $quantity
 * @property string $price
 * @property string $sub_total
 * @property string|null $others
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\order $order
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\VariationCombination|null $variationCombination
 * @property-read \App\Models\VariationOption|null $variationOption
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item groupByProduct()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order_item whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class order_item extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'option_id',
        'combination_id',
        'quantity',
        'price',
        'sub_total',
        'unit_cost',
        'total_cost',
        'others',
        // Vendor fields
        'vendor_id',
        'vendor_commission_rate',
        'vendor_commission_amount',
        'vendor_earning',
        'vendor_paid',
        'vendor_paid_at',
        'paid_in_withdrawal_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'vendor_commission_rate' => 'decimal:2',
        'vendor_commission_amount' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
        'vendor_paid' => 'boolean',
        'vendor_paid_at' => 'datetime',
    ];

    // Removed protected $with to prevent unnecessary eager loading
    // Use explicit eager loading in queries: ->with(['product', 'variationCombination'])
    // The order model already eager loads products via relationship definition

    public function order()
    {
        return $this->belongsTo(order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOption()
    {
        return $this->belongsTo(VariationOption::class, 'option_id');
    }

    public function variationCombination()
    {
        return $this->belongsTo(VariationCombination::class, 'combination_id');
    }

    // Add a scope to group items by product
    public function scopeGroupByProduct($query)
    {
        return $query->select('product_id')
            ->selectRaw('GROUP_CONCAT(CONCAT(quantity, "x ", COALESCE(variation_options.name, "")) SEPARATOR ", ") as variations')
            ->leftJoin('variation_options', 'order_items.option_id', '=', 'variation_options.id')
            ->groupBy('product_id');
    }

    // Helper method to get formatted variation text
    public function getVariationText()
    {
        if ($this->variationOption) {
            return sprintf(
                "Variation: %s: %s × %d",
                $this->variationOption->variation->name ?? '',
                $this->variationOption->name ?? '',
                $this->quantity
            );
        }
        return sprintf("Quantity: %d", $this->quantity);
    }

    // ==========================================
    // VENDOR RELATIONSHIPS & METHODS
    // ==========================================

    /**
     * Vendor who owns this item
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Withdrawal this item was paid in
     */
    public function withdrawal()
    {
        return $this->belongsTo(VendorWithdrawal::class, 'paid_in_withdrawal_id');
    }

    /**
     * Calculate commission and earnings
     * Call this when order is completed
     */
    public function calculateCommission(): void
    {
        if (!$this->vendor_id || !$this->vendor_commission_rate) {
            return;
        }

        // Commission amount (what platform keeps)
        $this->vendor_commission_amount = ($this->sub_total * $this->vendor_commission_rate) / 100;

        // Vendor earning (what vendor gets)
        $this->vendor_earning = $this->sub_total - $this->vendor_commission_amount;

        $this->save();
    }

    /**
     * Mark this item as paid to vendor
     */
    public function markAsPaid(int $withdrawalId): void
    {
        $this->update([
            'vendor_paid' => true,
            'vendor_paid_at' => now(),
            'paid_in_withdrawal_id' => $withdrawalId,
        ]);
    }

    /**
     * Scope: Unpaid items
     */
    public function scopeUnpaid($query)
    {
        return $query->where('vendor_paid', false)
                    ->whereNotNull('vendor_id');
    }

    /**
     * Scope: Items for specific vendor
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}
