<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WooCommerceSetting extends Model
{
    protected $table = 'woo_commerce_settings';
    
    protected $fillable = [
        'connection_method',
        'api_url',
        'consumer_key',
        'consumer_secret',
        'api_version',
        'verify_ssl',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'db_table_prefix',
    ];

    protected $casts = [
        'verify_ssl' => 'boolean',
    ];

    /**
     * Get cached settings (singleton pattern for performance)
     */
    public static function getSettings()
    {
        return Cache::remember('woocommerce_settings', 3600, function () {
            return self::first() ?? new self([
                'connection_method' => 'api',
                'api_version' => 'wc/v3',
                'verify_ssl' => true,
                'db_port' => '3306',
                'db_table_prefix' => 'wp_',
            ]);
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('woocommerce_settings');
    }

    /**
     * Override save to clear cache
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);
        self::clearCache();
        return $result;
    }

    /**
     * Override delete to clear cache
     */
    public function delete()
    {
        $result = parent::delete();
        self::clearCache();
        return $result;
    }
}
