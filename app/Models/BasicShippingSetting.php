<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $flat_rate
 * @property array<array-key, mixed>|null $shipping_options Stores options as {"key": {"name": "Name", "cost": 80.00, "active": true, "position": 1}}
 * @property string $free_shipping_threshold
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereFlatRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereFreeShippingThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereShippingOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BasicShippingSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BasicShippingSetting extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'flat_rate', 'shipping_options', 'free_shipping_threshold'];

    protected $casts = [
        'shipping_options' => 'array',
    ];
}
