<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedOrderAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'ip_address',
        'user_agent',
        'block_reason',
        'validation_errors',
        'blocked_by_module',
        'request_data',
        'blocked_at',
    ];

    protected $casts = [
        'validation_errors' => 'array',
        'request_data' => 'array',
        'blocked_at' => 'datetime',
    ];

    /**
     * Scope for recent attempts
     */
    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('blocked_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope by phone number
     */
    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Scope by IP address
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Scope by blocking reason
     */
    public function scopeByReason($query, $reason)
    {
        return $query->where('block_reason', $reason);
    }

    /**
     * Get formatted reason
     */
    public function getFormattedReasonAttribute()
    {
        $reasons = [
            'duplicate' => 'Duplicate Order',
            'fake' => 'Fake Data Detected',
            'fraud' => 'Fraud Detected',
        ];

        return $reasons[$this->block_reason] ?? 'Unknown';
    }

    /**
     * Get module name
     */
    public function getModuleNameAttribute()
    {
        $modules = [
            '1' => 'Duplicate Protection',
            '2' => 'Fake Order Protection',
            '3' => 'Fraud & Scam Protection',
        ];

        return $modules[$this->blocked_by_module] ?? 'Unknown';
    }

    /**
     * Log a blocked attempt
     */
    public static function logAttempt(array $data, string $reason, string $module, array $errors = [])
    {
        return self::create([
            'name' => $data['name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'block_reason' => $reason,
            'validation_errors' => $errors,
            'blocked_by_module' => $module,
            'request_data' => $data,
            'blocked_at' => now(),
        ]);
    }

    /**
     * Get statistics
     */
    public static function getStats($hours = 24)
    {
        $attempts = self::recent($hours)->get();

        return [
            'total_blocked' => $attempts->count(),
            'by_reason' => [
                'duplicate' => $attempts->where('block_reason', 'duplicate')->count(),
                'fake' => $attempts->where('block_reason', 'fake')->count(),
                'fraud' => $attempts->where('block_reason', 'fraud')->count(),
            ],
            'unique_phones' => $attempts->pluck('phone')->unique()->count(),
            'unique_ips' => $attempts->pluck('ip_address')->unique()->count(),
            'top_blocked_phones' => $attempts->groupBy('phone')
                ->map->count()
                ->sortDesc()
                ->take(10),
        ];
    }
}

