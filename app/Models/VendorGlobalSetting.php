<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VendorGlobalSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'label',
        'description',
        'options',
        'is_public',
    ];

    protected $casts = [
        'options' => 'array',
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key with caching
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "vendor_global_setting:{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, ?string $type = null): void
    {
        $setting = self::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'type' => $type ?? 'string',
            ]);
        }

        // Clear cache
        Cache::forget("vendor_global_setting:{$key}");
    }

    /**
     * Cast value to appropriate type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
            case 'decimal':
                return (float) $value;
            case 'array':
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }

    /**
     * Get all settings grouped by category
     */
    public static function getAll(): array
    {
        return self::all()
            ->groupBy('category')
            ->map(function ($settings) {
                return $settings->mapWithKeys(function ($setting) {
                    return [$setting->key => [
                        'key' => $setting->key,
                        'value' => self::castValue($setting->value, $setting->type),
                        'raw_value' => $setting->value,  // Keep original string value for forms
                        'type' => $setting->type,
                        'label' => $setting->label,
                        'description' => $setting->description,
                        'options' => $setting->options,
                    ]];
                });
            })
            ->toArray();
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory(string $category): array
    {
        return self::where('category', $category)
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => self::castValue($setting->value, $setting->type)];
            })
            ->toArray();
    }

    /**
     * Get all public settings (for frontend)
     */
    public static function getPublicSettings(): array
    {
        return self::where('is_public', true)
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => self::castValue($setting->value, $setting->type)];
            })
            ->toArray();
    }

    /**
     * Convenience method: Get global commission rate
     */
    public static function getGlobalCommissionRate(): float
    {
        return (float) self::get('vendor_global_commission_rate', 15.00);
    }

    /**
     * Convenience method: Get commission limits
     */
    public static function getCommissionLimits(): array
    {
        return [
            'min' => (float) self::get('vendor_min_commission_rate', 10.00),
            'max' => (float) self::get('vendor_max_commission_rate', 30.00),
            'default' => self::getGlobalCommissionRate(),
        ];
    }

    /**
     * Convenience method: Get withdrawal limits
     */
    public static function getWithdrawalLimits(): array
    {
        return [
            'min_amount' => (float) self::get('vendor_min_withdrawal_amount', 500.00),
            'processing_days' => (int) self::get('vendor_withdrawal_processing_days', 7),
        ];
    }

    /**
     * Convenience method: Check if vendor system is enabled
     */
    public static function isVendorSystemEnabled(): bool
    {
        return (bool) self::get('vendor_system_enabled', false);
    }

    /**
     * Convenience method: Get vendor product limit
     */
    public static function getProductLimit(): int
    {
        return (int) self::get('vendor_max_products_per_vendor', 0); // 0 = unlimited
    }

    /**
     * Clear all setting caches
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("vendor_global_setting:{$setting->key}");
        }
    }

    /**
     * Boot method to clear cache on update
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget("vendor_global_setting:{$setting->key}");
        });

        static::deleted(function ($setting) {
            Cache::forget("vendor_global_setting:{$setting->key}");
        });
    }
}
