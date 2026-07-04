<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property string|null $description
 * @property-read int|null $items_count
 * @property numeric $combo_price
 * @property numeric $original_price
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComboOfferItem> $activeItems
 * @property-read int|null $active_items_count
 * @property-read mixed $discount_amount
 * @property-read mixed $discount_percentage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComboOfferItem> $items
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComboSelection> $selections
 * @property-read int|null $selections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer forProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereComboPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboOffer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ComboOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'title',
        'description',
        'items_count',
        'combo_price',
        'original_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'items_count' => 'integer',
        'combo_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the product that owns this combo offer.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the items available in this combo offer.
     */
    public function items()
    {
        return $this->hasMany(ComboOfferItem::class)->orderBy('sort_order');
    }

    /**
     * Get active items available in this combo offer.
     */
    public function activeItems()
    {
        return $this->hasMany(ComboOfferItem::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get the selections for this combo offer.
     */
    public function selections()
    {
        return $this->hasMany(ComboSelection::class);
    }

    /**
     * Get the discount amount for this combo.
     */
    public function getDiscountAmountAttribute()
    {
        return $this->original_price - $this->combo_price;
    }

    /**
     * Get the discount percentage for this combo.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->original_price <= 0) {
            return 0;
        }
        return round(($this->discount_amount / $this->original_price) * 100);
    }

    /**
     * Check if this combo offer is valid.
     */
    public function isValid()
    {
        return $this->is_active && $this->activeItems()->count() >= $this->items_count;
    }

    /**
     * Scope to get active combo offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get combo offers for a specific product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
} 