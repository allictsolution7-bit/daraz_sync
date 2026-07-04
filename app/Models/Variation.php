<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariationOption> $options
 * @property-read int|null $options_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name'
    ];

    public function options()
    {
        return $this->hasMany(VariationOption::class);
    }
}