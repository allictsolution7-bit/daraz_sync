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
 * @property-read string $provider_description
 * @property-read string $provider_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereCredentials($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckerIntegration whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FraudCheckerIntegration extends Model
{
    protected $fillable = ['provider', 'credentials', 'is_active'];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the provider display name
     */
    public function getProviderNameAttribute(): string
    {
        return match ($this->provider) {
            'hoorin' => 'Hoorin',
            'bdcourier' => 'BD Courier',
            default => ucfirst($this->provider)
        };
    }

    /**
     * Get the provider description
     */
    public function getProviderDescriptionAttribute(): string
    {
        return match ($this->provider) {
            'hoorin' => 'Hoorin Courier Search API - Provides courier delivery summaries and fraud detection',
            'bdcourier' => 'BD Courier API - Courier status checking and fraud detection service',
            default => 'Unknown provider'
        };
    }
}
