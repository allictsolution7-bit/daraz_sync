<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $variation_id
 * @property string $name
 * @property string|null $description
 * @property string|null $featured_image
 * @property array<array-key, mixed>|null $images
 * @property int $stock_quantity
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Variation $variation
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationOption whereVariationId($value)
 * @mixin \Eloquent
 */
class VariationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_id',
        'name',
        'description',
        'featured_image',
        'images',
        'stock_quantity',
        'price',
    ];

    protected $casts = [
        'images' => 'array',
        'stock_quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
