<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $variation_option_id
 * @property int|null $variation_combination_id
 * @property int $quantity
 * @property string $type
 * @property int|null $reference_id
 * @property string|null $reference_type
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\VariationCombination|null $variationCombination
 * @property-read \App\Models\VariationOption|null $variationOption
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereVariationCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereVariationOptionId($value)
 * @mixin \Eloquent
 */
class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_option_id',
        'variation_combination_id',
        'quantity',
        'type',
        'reference_id',
        'reference_type',
        'notes',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOption()
    {
        return $this->belongsTo(VariationOption::class);
    }

    public function variationCombination()
    {
        return $this->belongsTo(VariationCombination::class, 'variation_combination_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}