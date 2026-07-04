<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $regions
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ShippingRule> $shippingRules
 * @property-read int|null $shipping_rules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereRegions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingZone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'regions',
        'is_active'
    ];

    protected $casts = [
        'regions' => 'array',
        'is_active' => 'boolean'
    ];

    public function shippingRules()
    {
        return $this->hasMany(ShippingRule::class, 'zone_id');
    }

    public function hasOverlappingRules($minWeight, $maxWeight, $excludeRuleId = null)
    {
        $query = $this->shippingRules()
            ->where(function ($query) use ($minWeight, $maxWeight) {
                $query->whereBetween('min_weight', [$minWeight, $maxWeight])
                    ->orWhereBetween('max_weight', [$minWeight, $maxWeight]);
            });
    
        if ($excludeRuleId) {
            $query->where('id', '!=', $excludeRuleId);
        }
    
        return $query->exists();
    }
}