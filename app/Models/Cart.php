<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $qunt
 * @property int $price
 * @property int|null $option_id
 * @property int|null $combination_id
 * @property int|null $combo_offer_id
 * @property array<array-key, mixed>|null $combo_selections
 * @property int|null $user_id
 * @property string|null $guest_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ComboOffer|null $comboOffer
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\VariationCombination|null $variationCombination
 * @property-read \App\Models\VariationOption|null $variationOption
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereComboOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereComboSelections($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGuestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereQunt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected $fillable = [
        'product_id',
        'qunt',
        'price',
        'option_id',
        'combination_id',
        'combo_offer_id',
        'combo_selections',
        'user_id',
        'guest_id',
    ];

    protected $casts = [
        'combo_selections' => 'array',
    ];

    /**
     * Get the combo selections as an array.
     */
    public function getComboSelectionsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return $value ?: [];
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

    public function comboOffer()
    {
        return $this->belongsTo(ComboOffer::class);
    }
}
