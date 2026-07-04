<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $phone
 * @property array<array-key, mixed> $fraud_check_data
 * @property int $risk_score
 * @property string $risk_level
 * @property int $total_parcels
 * @property int $delivered_parcels
 * @property int $canceled_parcels
 * @property numeric $delivery_success_rate
 * @property array<array-key, mixed>|null $risk_factors
 * @property array<array-key, mixed>|null $provider_results
 * @property string|null $recommendation
 * @property bool $has_courier_history
 * @property \Illuminate\Support\Carbon $last_checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $customer_type
 * @property-read string $formatted_last_checked
 * @property-read string $risk_level_badge_class
 * @property-read string $risk_level_display
 * @property-read string $success_rate_display
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult byRiskLevel($riskLevel)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult highRisk()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult recent($days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereCanceledParcels($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereDeliveredParcels($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereDeliverySuccessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereFraudCheckData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereHasCourierHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereLastCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereProviderResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereRecommendation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereRiskFactors($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereRiskLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereRiskScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereTotalParcels($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FraudCheckResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FraudCheckResult extends Model
{
    protected $fillable = [
        'phone',
        'fraud_check_data',
        'risk_score',
        'risk_level',
        'total_parcels',
        'delivered_parcels',
        'canceled_parcels',
        'delivery_success_rate',
        'risk_factors',
        'provider_results',
        'recommendation',
        'has_courier_history',
        'last_checked_at'
    ];

    protected $casts = [
        'fraud_check_data' => 'array',
        'risk_factors' => 'array',
        'provider_results' => 'array',
        'last_checked_at' => 'datetime',
        'delivery_success_rate' => 'decimal:2',
        'has_courier_history' => 'boolean'
    ];

    protected $appends = [
        'risk_level_badge_class',
        'risk_level_display',
        'success_rate_display',
        'formatted_last_checked',
        'relative_last_checked',
        'is_stale',
    ];

    /**
     * Get orders associated with this fraud check result
     */
    public function orders()
    {
        return $this->hasMany(order::class, 'fraud_check_result_id');
    }

    /**
     * Get risk level badge class
     */
    public function getRiskLevelBadgeClassAttribute(): string
    {
        return match ($this->risk_level) {
            'high' => 'badge bg-danger',
            'medium' => 'badge bg-warning',
            'low' => 'badge bg-info',
            'very_low' => 'badge bg-success',
            default => 'badge bg-secondary'
        };
    }

    /**
     * Get risk level display name
     */
    public function getRiskLevelDisplayAttribute(): string
    {
        return match ($this->risk_level) {
            'high' => 'High Risk',
            'medium' => 'Medium Risk',
            'low' => 'Low Risk',
            'very_low' => 'Very Low Risk',
            default => 'Unknown'
        };
    }

    /**
     * Check if this result is stale (older than 30 days)
     */
    public function isStale(): bool
    {
        return $this->last_checked_at
            ? $this->last_checked_at->diffInDays(now()) > 30
            : false;
    }

    /**
     * Get formatted last checked date
     */
    public function getFormattedLastCheckedAttribute(): string
    {
        return $this->last_checked_at
            ? $this->last_checked_at->format('d M, Y h:i A')
            : '';
    }

    public function getRelativeLastCheckedAttribute(): string
    {
        return $this->last_checked_at?->diffForHumans() ?? '';
    }

    public function getIsStaleAttribute(): bool
    {
        return $this->isStale();
    }

    /**
     * Scope to get results by risk level
     */
    public function scopeByRiskLevel($query, $riskLevel)
    {
        return $query->where('risk_level', $riskLevel);
    }

    /**
     * Scope to get high risk results
     */
    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_level', ['high', 'medium']);
    }

    /**
     * Scope to get recent results
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('last_checked_at', '>=', now()->subDays($days));
    }

    /**
     * Get customer type description
     */
    public function getCustomerTypeAttribute(): string
    {
        return $this->has_courier_history ? 'Returning Customer' : 'New Customer';
    }

    /**
     * Get success rate display with context
     */
    public function getSuccessRateDisplayAttribute(): string
    {
        if (!$this->has_courier_history) {
            return '0% (New Customer)';
        }
        
        return $this->delivery_success_rate . '% Success';
    }
}
