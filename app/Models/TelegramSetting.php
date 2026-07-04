<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TelegramSetting extends Model
{
    protected $fillable = [
        'enabled',
        'bot_token',
        'chat_id',
        'notify_new_order',
        'notify_landing_page_order',
        'notify_cart_order',
        'notify_order_status_change',
        'order_message_template',
        'timeout',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'notify_new_order' => 'boolean',
        'notify_landing_page_order' => 'boolean',
        'notify_cart_order' => 'boolean',
        'notify_order_status_change' => 'boolean',
        'timeout' => 'integer',
    ];

    /**
     * Get cached settings (singleton pattern for performance)
     */
    public static function getSettings()
    {
        return Cache::remember('telegram_settings', 3600, function () {
            return self::first() ?? new self([
                'enabled' => false,
                'timeout' => 3,
            ]);
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache()
    {
        Cache::forget('telegram_settings');
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

