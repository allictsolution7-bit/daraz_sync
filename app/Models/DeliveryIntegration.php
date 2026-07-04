<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $provider
 * @property array<array-key, mixed> $credentials
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryIntegration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DeliveryIntegration extends Model
{
    protected $fillable = ['provider', 'credentials', 'is_active'];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];
}
