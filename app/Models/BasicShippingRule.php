<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BasicShippingRule extends Model
{
    use HasFactory;

    protected $table = 'basic_shipping_rules';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    protected $fillable = [
        'ruleable_type',
        'ruleable_id',
        'rule_type',
        'rule_value',
        'free_shipping_threshold',
        'delivery_area_name',
        'delivery_area_slug',
        'conditions',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'rule_value' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent ruleable model (Product or LandingPage).
     */
    public function ruleable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get only active rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by priority (highest first).
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    /**
     * Get rules for a specific model instance.
     */
    public static function forModel($model)
    {
        return static::where('ruleable_type', get_class($model))
                    ->where('ruleable_id', $model->id)
                    ->active()
                    ->byPriority();
    }

    /**
     * Rule type constants for better code readability.
     */
    const RULE_TYPE_OVERRIDE = 'override';
    const RULE_TYPE_FREE_SHIPPING = 'free_shipping';
    const RULE_TYPE_CUSTOM_COST = 'custom_cost';
    const RULE_TYPE_PERCENTAGE = 'percentage';
    const RULE_TYPE_CONDITIONAL = 'conditional';
    const RULE_TYPE_DELIVERY_AREA = 'delivery_area';
}