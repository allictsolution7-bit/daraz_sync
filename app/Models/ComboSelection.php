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
 * @property int $quantity
 * @property string|null $session_id
 * @property int|null $user_id
 * @property int|null $cart_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ComboOffer $comboOffer
 * @property-read mixed $effective_product
 * @property-read mixed $price
 * @property-read mixed $total_price
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\VariationCombination|null $variationCombination
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection forComboOffer($comboOfferId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection forSession($sessionId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereComboOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ComboSelection whereVariationCombinationId($value)
 * @mixin \Eloquent
 */
class ComboSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'combo_offer_id',
        'product_id',
        'variation_combination_id',
        'quantity',
        'session_id',
        'user_id',
        'cart_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the combo offer for this selection.
     */
    public function comboOffer()
    {
        return $this->belongsTo(ComboOffer::class);
    }

    /**
     * Get the product for this selection.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation combination for this selection.
     */
    public function variationCombination()
    {
        return $this->belongsTo(VariationCombination::class);
    }

    /**
     * Get the user for this selection.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the effective product (either specific variation or base product).
     */
    public function getEffectiveProductAttribute()
    {
        return $this->variationCombination ?? $this->product;
    }

    /**
     * Get the price for this selection.
     */
    public function getPriceAttribute()
    {
        if ($this->variationCombination) {
            return $this->variationCombination->effective_price;
        }
        return $this->product->offer ?? $this->product->old_price;
    }

    /**
     * Get the total price for this selection (price * quantity).
     */
    public function getTotalPriceAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Scope to get selections for a specific session.
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope to get selections for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get selections for a specific combo offer.
     */
    public function scopeForComboOffer($query, $comboOfferId)
    {
        return $query->where('combo_offer_id', $comboOfferId);
    }
} 