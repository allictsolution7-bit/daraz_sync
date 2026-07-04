<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $combo_offer_id
 * @property int $product_id
 * @property int|null $variation_combination_id
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ComboOffer $comboOffer
 * @property-read mixed $effective_product
 * @property-read mixed $price
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\VariationCombination|null $variationCombination
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereComboOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOfferItem whereVariationCombinationId($value)
 * @mixin \Eloquent
 */
class ComboOfferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'combo_offer_id',
        'product_id',
        'variation_combination_id',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the combo offer that owns this item.
     */
    public function comboOffer()
    {
        return $this->belongsTo(ComboOffer::class);
    }

    /**
     * Get the product for this combo item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation combination for this combo item.
     */
    public function variationCombination()
    {
        return $this->belongsTo(VariationCombination::class);
    }

    /**
     * Get the effective product (either specific variation or base product).
     */
    public function getEffectiveProductAttribute()
    {
        return $this->variationCombination ?? $this->product;
    }

    /**
     * Get the price for this item.
     */
    public function getPriceAttribute()
    {
        if ($this->variationCombination) {
            return $this->variationCombination->effective_price;
        }
        return $this->product->offer ?? $this->product->old_price;
    }

    /**
     * Check if this item is available for selection.
     */
    public function isAvailable()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->variationCombination) {
            return $this->variationCombination->isInStock();
        }

        return $this->product->quantity > 0;
    }

    /**
     * Scope to get active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 