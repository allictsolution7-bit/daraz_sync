<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Delayed Event Setting Model
 *
 * Manages configuration for the delayed purchase event system.
 * This feature delays firing purchase events for COD/manual payment orders
 * until they are confirmed by admin.
 *
 * @property int $id
 * @property bool $is_enabled
 * @property string|null $pixelfly_api_key
 * @property string $pixelfly_endpoint
 * @property array|null $enabled_payment_methods
 */
class DelayedEventSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'firing_method',
        'pixelfly_api_key',
        'pixelfly_endpoint',
        'sgtm_endpoint',
        'sgtm_measurement_id',
        'sgtm_api_secret',
        'enabled_payment_methods',
        'enabled_order_sources',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'enabled_payment_methods' => 'array',
        'enabled_order_sources' => 'array',
    ];

    /**
     * Cache key for settings
     */
    const CACHE_KEY = 'delayed_event_settings';

    /**
     * Get the settings instance (singleton pattern with caching)
     */
    public static function getSettings(): self
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::first() ?? self::createDefault();
        });
    }

    /**
     * Create default settings if none exist
     */
    protected static function createDefault(): self
    {
        return self::create([
            'is_enabled' => false,
            'firing_method' => 'pixelfly',
            'pixelfly_endpoint' => 'https://track.pixelfly.io/e',
            'enabled_payment_methods' => ['cod'],
        ]);
    }

    /**
     * Clear the settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Check if the feature is enabled
     */
    public static function isFeatureEnabled(): bool
    {
        return self::getSettings()->is_enabled;
    }

    /**
     * Check if a payment method should use delayed events
     */
    public static function isPaymentMethodEnabled(string $paymentMethod): bool
    {
        $settings = self::getSettings();

        if (!$settings->is_enabled) {
            return false;
        }

        $enabledMethods = $settings->enabled_payment_methods ?? [];
        return in_array($paymentMethod, $enabledMethods);
    }

    /**
     * Get the PixelFly API key
     */
    public static function getApiKey(): ?string
    {
        return self::getSettings()->pixelfly_api_key;
    }

    /**
     * Get the PixelFly endpoint
     */
    public static function getEndpoint(): string
    {
        return self::getSettings()->pixelfly_endpoint ?? 'https://track.pixelfly.io/e';
    }

    /**
     * Get the firing method (pixelfly or sgtm)
     */
    public static function getFiringMethod(): string
    {
        return self::getSettings()->firing_method ?? 'pixelfly';
    }

    /**
     * Check if using sGTM firing method
     */
    public static function isSgtmMode(): bool
    {
        return self::getFiringMethod() === 'sgtm';
    }

    /**
     * Check if using PixelFly firing method
     */
    public static function isPixelflyMode(): bool
    {
        return self::getFiringMethod() === 'pixelfly';
    }

    /**
     * Get the sGTM endpoint URL
     */
    public static function getSgtmEndpoint(): ?string
    {
        return self::getSettings()->sgtm_endpoint;
    }

    /**
     * Get the GA4 Measurement ID
     */
    public static function getMeasurementId(): ?string
    {
        return self::getSettings()->sgtm_measurement_id;
    }

    /**
     * Get the sGTM API secret
     */
    public static function getSgtmApiSecret(): ?string
    {
        return self::getSettings()->sgtm_api_secret;
    }

    /**
     * Get the firing method display name
     */
    public static function getFiringMethodLabel(): string
    {
        return self::isSgtmMode() ? 'sGTM' : 'PixelFly';
    }

    /**
     * Get all available payment methods for the checkbox list
     */
    public static function getAvailablePaymentMethods(): array
    {
        return [
            'cod' => 'Cash on Delivery (COD)',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
        ];
    }

    /**
     * Check if an order source should use delayed events (for offline/POS orders)
     */
    public static function isOrderSourceEnabled(string $orderSource): bool
    {
        $settings = self::getSettings();

        if (!$settings->is_enabled) {
            return false;
        }

        $enabledSources = $settings->enabled_order_sources ?? [];
        return in_array($orderSource, $enabledSources);
    }

    /**
     * Get all available order sources for the checkbox list (offline sources)
     */
    public static function getAvailableOrderSources(): array
    {
        return [
            'Physical Store' => 'Physical Store',
            'Phone Call' => 'Phone Call',
            'WhatsApp' => 'WhatsApp',
            'Messenger' => 'Messenger',
            'Facebook' => 'Facebook',
            'Instagram' => 'Instagram',
        ];
    }

    /**
     * Map order source to Facebook action_source
     * @see https://developers.facebook.com/docs/marketing-api/conversions-api/parameters/server-event#action-source
     */
    public static function getActionSourceForOrderSource(string $orderSource): string
    {
        return match ($orderSource) {
            'Physical Store' => 'physical_store',
            'Phone Call' => 'phone_call',
            'WhatsApp', 'Messenger', 'Facebook', 'Instagram' => 'chat',
            'Website' => 'website',
            default => 'other',
        };
    }

    /**
     * Check if an order source is considered offline (non-website)
     */
    public static function isOfflineSource(string $orderSource): bool
    {
        $offlineSources = array_keys(self::getAvailableOrderSources());
        return in_array($orderSource, $offlineSources);
    }

    /**
     * Boot method to clear cache on save
     */
    protected static function booted(): void
    {
        static::saved(function () {
            self::clearCache();
        });
    }
}
