<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $variation_option_id
 * @property string|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VariationOption|null $variation_option
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductVariationOption whereVariationOptionId($value)
 * @mixin \Eloquent
 */
class ProductVariationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_option_id',
        'price'
    ];

    public function variation_option()
    {
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }
}
