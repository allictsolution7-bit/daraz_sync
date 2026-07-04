<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * 
 *
 * @property int $id
 * @property string $license_key
 * @property string|null $domain
 * @property array<array-key, mixed>|null $modules
 * @property int $landing_page_limit
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_synced_at
 * @property \Illuminate\Support\Carbon|null $support_start_date
 * @property int $support_duration Support duration in days
 * @property \Illuminate\Support\Carbon|null $support_end_date
 * @property \Illuminate\Support\Carbon|null $update_start_date
 * @property int $update_duration Update access duration in days
 * @property \Illuminate\Support\Carbon|null $update_end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License activeSupportOnly()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License activeUpdatesOnly()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereLandingPageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereLastSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereLicenseKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereModules($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereSupportDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereSupportEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereSupportStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdateDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdateEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdateStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|License whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class License extends Model
{
    protected $fillable = [
        'license_key',
        'domain',
        'modules',
        'landing_page_limit',
        'expiry_date',
        'status',
        'last_synced_at',
        'support_start_date',
        'support_duration',
        'support_end_date',
        'update_start_date',
        'update_duration',
        'update_end_date',
    ];

    protected $casts = [
        'modules' => 'array',
        'expiry_date' => 'date',
        'last_synced_at' => 'datetime',
        'support_start_date' => 'date',
        'support_end_date' => 'date',
        'update_start_date' => 'date',
        'update_end_date' => 'date',
    ];

    /**
     * Check if the license is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    /**
     * Check if the license is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if a specific module is enabled
     */
    public function hasModule(string $module): bool
    {
        return in_array($module, $this->modules ?? []);
    }

    /**
     * Check if the license needs to be synced (older than 7 days)
     */
    public function needsSync(): bool
    {
        if (!$this->last_synced_at) {
            return true;
        }
        
        return (int) $this->last_synced_at->diffInDays(now()) >= 7;
    }

    /**
     * Get the current landing page count
     */
    public function getCurrentLandingPageCount(): int
    {
        return \App\Models\LandingPage::count();
    }

    /**
     * Check if more landing pages can be created
     */
    public function canCreateLandingPage(): bool
    {
        if (!$this->hasModule('landing_page')) {
            return false;
        }

        return $this->getCurrentLandingPageCount() < $this->landing_page_limit;
    }

    /**
     * Get remaining landing page quota
     */
    public function getRemainingLandingPageQuota(): int
    {
        return max(0, $this->landing_page_limit - $this->getCurrentLandingPageCount());
    }

    /**
     * Get the active license (singleton pattern)
     */
    public static function getActive(): ?self
    {
        return static::where('status', 'active')->first();
    }

    /**
     * Scope for active licenses
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expired licenses
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    /**
     * Check if support period is active
     */
    public function isSupportActive(): bool
    {
        if (!$this->support_start_date) {
            return false;
        }

        if ($this->isSupportLifetime()) {
            return $this->support_start_date->isPast() || $this->support_start_date->isToday();
        }

        return $this->support_end_date && now()->between($this->support_start_date, $this->support_end_date);
    }

    /**
     * Check if support period has expired
     */
    public function isSupportExpired(): bool
    {
        if ($this->isSupportLifetime()) {
            return false;
        }

        return $this->support_end_date && $this->support_end_date->isPast();
    }

    /**
     * Get remaining support days
     */
    public function getSupportRemainingDays(): int
    {
        if ($this->isSupportLifetime()) {
            return 0;
        }

        if (!$this->support_end_date || $this->isSupportExpired()) {
            return 0;
        }

        return (int) now()->diffInDays($this->support_end_date);
    }

    /**
     * Check if update period is active
     */
    public function isUpdateActive(): bool
    {
        if (!$this->update_start_date) {
            return false;
        }

        if ($this->isUpdateLifetime()) {
            return $this->update_start_date->isPast() || $this->update_start_date->isToday();
        }

        return $this->update_end_date && now()->between($this->update_start_date, $this->update_end_date);
    }

    /**
     * Check if update period has expired
     */
    public function isUpdateExpired(): bool
    {
        if ($this->isUpdateLifetime()) {
            return false;
        }

        return $this->update_end_date && $this->update_end_date->isPast();
    }

    /**
     * Get remaining update days
     */
    public function getUpdateRemainingDays(): int
    {
        if ($this->isUpdateLifetime()) {
            return 0;
        }

        if (!$this->update_start_date || $this->isUpdateExpired()) {
            return 0;
        }

        return (int) now()->diffInDays($this->update_end_date);
    }

    /**
     * Check if license has full access (license + support active)
     */
    public function hasFullAccess(): bool
    {
        return $this->isActive() && $this->isSupportActive();
    }

    /**
     * Check if license can access updates
     */
    public function canAccessUpdates(): bool
    {
        return $this->isActive() && $this->isUpdateActive();
    }

    /**
     * Get support status information
     */
    public function getSupportStatus(): array
    {
        if (!$this->support_start_date) {
            return [
                'active' => false,
                'start_date' => null,
                'end_date' => null,
                'duration' => $this->support_duration,
                'remaining_days' => 0,
                'status' => 'not_configured'
            ];
        }

        $isLifetime = $this->isSupportLifetime();
        $isActive = $this->isSupportActive();
        $remainingDays = $isLifetime ? null : $this->getSupportRemainingDays();

        return [
            'active' => $isActive,
            'start_date' => $this->support_start_date->format('Y-m-d'),
            'end_date' => $this->support_end_date?->format('Y-m-d'),
            'duration' => $this->support_duration,
            'remaining_days' => $remainingDays,
            'status' => $isActive ? 'active' : ($this->isSupportExpired() ? 'expired' : 'pending'),
            'lifetime' => $isLifetime,
            'expired' => $this->isSupportExpired(),
        ];
    }

    /**
     * Get update status information
     */
    public function getUpdateStatus(): array
    {
        if (!$this->update_start_date) {
            return [
                'active' => false,
                'start_date' => null,
                'end_date' => null,
                'duration' => $this->update_duration,
                'remaining_days' => 0,
                'status' => 'not_configured'
            ];
        }

        $isLifetime = $this->isUpdateLifetime();
        $isActive = $this->isUpdateActive();
        $remainingDays = $isLifetime ? null : $this->getUpdateRemainingDays();

        return [
            'active' => $isActive,
            'start_date' => $this->update_start_date->format('Y-m-d'),
            'end_date' => $this->update_end_date?->format('Y-m-d'),
            'duration' => $this->update_duration,
            'remaining_days' => $remainingDays,
            'status' => $isActive ? 'active' : ($this->isUpdateExpired() ? 'expired' : 'pending'),
            'lifetime' => $isLifetime,
            'expired' => $this->isUpdateExpired(),
        ];
    }

    /**
     * Get support status text for display
     */
    public function getSupportStatusText(): string
    {
        $status = $this->getSupportStatus();
        
        if (!empty($status['lifetime']) && $status['status'] === 'active') {
            return 'Active (lifetime)';
        }

        switch ($status['status']) {
            case 'active':
                return "Active ({$status['remaining_days']} days remaining)";
            case 'expired':
                return 'Expired';
            case 'pending':
                return 'Pending';
            default:
                return 'Not configured';
        }
    }

    /**
     * Get update status text for display
     */
    public function getUpdateStatusText(): string
    {
        $status = $this->getUpdateStatus();
        
        if (!empty($status['lifetime']) && $status['status'] === 'active') {
            return 'Active (lifetime)';
        }

        switch ($status['status']) {
            case 'active':
                return "Active ({$status['remaining_days']} days remaining)";
            case 'expired':
                return 'Expired';
            case 'pending':
                return 'Pending';
            default:
                return 'Not configured';
        }
    }

    protected function isSupportLifetime(): bool
    {
        return $this->support_start_date
            && $this->support_end_date === null
            && ($this->support_duration === 0 || $this->support_duration === null);
    }

    protected function isUpdateLifetime(): bool
    {
        return $this->update_start_date
            && $this->update_end_date === null
            && ($this->update_duration === 0 || $this->update_duration === null);
    }

    /**
     * Scope for licenses with active support only
     */
    public function scopeActiveSupportOnly($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('support_end_date')
                        ->where('support_start_date', '<=', now())
                        ->where('support_end_date', '>=', now());
                })->orWhere(function ($inner) {
                    $inner->whereNull('support_end_date')
                        ->where(function ($duration) {
                            $duration->where('support_duration', 0)
                                ->orWhereNull('support_duration');
                        })
                        ->where('support_start_date', '<=', now());
                });
            });
    }

    /**
     * Scope for licenses with active updates only
     */
    public function scopeActiveUpdatesOnly($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('update_end_date')
                        ->where('update_start_date', '<=', now())
                        ->where('update_end_date', '>=', now());
                })->orWhere(function ($inner) {
                    $inner->whereNull('update_end_date')
                        ->where(function ($duration) {
                            $duration->where('update_duration', 0)
                                ->orWhereNull('update_duration');
                        })
                        ->where('update_start_date', '<=', now());
                });
            });
    }
}
