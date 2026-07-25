<?php

namespace Modules\Daraz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class DarazStore extends Model
{
    protected $table = 'daraz_stores';

    protected $fillable = [
        'vendor_id',
        'name',
        'country_code',
        'app_key',
        'app_secret',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_active',
        'auto_sync',
        'sync_interval',
        'last_synced_at',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_sync' => 'boolean',
        'token_expires_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'settings' => 'array',
        'sync_interval' => 'integer',
    ];

    protected $hidden = ['app_secret', 'access_token', 'refresh_token'];

    /**
     * Encrypt app_secret when setting.
     */
    public function setAppSecretAttribute($value): void
    {
        $this->attributes['app_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt app_secret when getting.
     */
    public function getAppSecretAttribute($value): ?string
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt access_token when setting.
     */
    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt access_token when getting.
     */
    public function getAccessTokenAttribute($value): ?string
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Encrypt refresh_token when setting.
     */
    public function setRefreshTokenAttribute($value): void
    {
        $this->attributes['refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt refresh_token when getting.
     */
    public function getRefreshTokenAttribute($value): ?string
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the vendor user that owns this store connection.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'vendor_id');
    }

    /**
     * Get product mappings for this store.
     */
    public function productMappings(): HasMany
    {
        return $this->hasMany(DarazProductMapping::class, 'daraz_store_id');
    }

    /**
     * Scope to filter stores for a specific vendor.
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope to filter stores for the current authenticated user.
     * - Super Admin: Sees ALL stores across the entire system.
     * - Vendor / Admin / Manager / Staff: Sees ONLY stores connected by their user ID (or vendor account).
     */
    public function scopeForCurrentUser($query)
    {
        $user = auth()->user();
        if (!$user) {
            return $query;
        }

        // Super Admin sees all stores
        if ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->id == 1) {
            return $query;
        }

        // Everyone else (Admin, Vendor, Manager, Staff, etc.) sees stores they connected/own
        return $query->where('vendor_id', $user->id);
    }

    /**
     * Get sync logs for this store.
     */
    public function syncLogs(): HasMany
    {
        return $this->hasMany(DarazSyncLog::class, 'daraz_store_id');
    }

    /**
     * Get the API base URL for this store's country.
     */
    public function getApiBaseUrl(): string
    {
        $countries = config('daraz.countries', []);
        return $countries[$this->country_code]['api_url'] ?? 'https://api.daraz.com.bd/rest';
    }

    /**
     * Get the OAuth authorization URL for this store's country.
     */
    public function getAuthUrl(): string
    {
        $countries = config('daraz.countries', []);
        return $countries[$this->country_code]['auth_url'] ?? 'https://api.daraz.com.bd/oauth/authorize';
    }

    /**
     * Get the country name.
     */
    public function getCountryNameAttribute(): string
    {
        $countries = config('daraz.countries', []);
        return $countries[$this->country_code]['name'] ?? $this->country_code;
    }

    /**
     * Check if the access token is expired.
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return true;
        }
        return $this->token_expires_at->isPast();
    }

    /**
     * Check if token expires soon (within 1 hour).
     */
    public function isTokenExpiringSoon(): bool
    {
        if (!$this->token_expires_at) {
            return true;
        }
        return $this->token_expires_at->subHour()->isPast();
    }

    /**
     * Check if store has valid credentials.
     */
    public function hasValidCredentials(): bool
    {
        return !empty($this->app_key) && !empty($this->app_secret);
    }

    /**
     * Check if store is connected (has access token).
     */
    public function isConnected(): bool
    {
        return !empty($this->access_token) && !$this->isTokenExpired();
    }

    /**
     * Get connection status for display.
     */
    public function getConnectionStatusAttribute(): string
    {
        if (!$this->hasValidCredentials()) {
            return 'not_configured';
        }
        if (!$this->access_token) {
            return 'not_connected';
        }
        if ($this->isTokenExpired()) {
            return 'token_expired';
        }
        if ($this->isTokenExpiringSoon()) {
            return 'token_expiring';
        }
        return 'connected';
    }

    /**
     * Get all available countries.
     */
    public static function getCountries(): array
    {
        return config('daraz.countries', []);
    }

    /**
     * Scope to get only active stores.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get stores with auto-sync enabled.
     */
    public function scopeAutoSync($query)
    {
        return $query->where('auto_sync', true)->where('is_active', true);
    }

    /**
     * Scope to get stores due for sync.
     */
    public function scopeDueForSync($query)
    {
        return $query->autoSync()
            ->where(function ($q) {
                $q->whereNull('last_synced_at')
                  ->orWhereRaw('last_synced_at < DATE_SUB(NOW(), INTERVAL sync_interval MINUTE)');
            });
    }
}
