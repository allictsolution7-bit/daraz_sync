<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $table = 'sms_settings';

    protected $fillable = [
        'user_id',
        'default_gateway',
        'is_enabled',
        'bulksmsbd_url',
        'bulksmsbd_api_key',
        'bulksmsbd_sender_id',
        'awaj_url',
        'awaj_api_key',
        'awaj_sender_id',
        'awaj_client_id',
        'awaj_secret_key',
        'notify_product_sold',
        'notify_user_created',
        'notify_admin_expiry',
        'admin_expiry_days_before',
        'notify_order_status_change',
        'template_product_sold',
        'template_user_created',
        'template_admin_expiry',
        'template_order_status',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'notify_product_sold' => 'boolean',
        'notify_user_created' => 'boolean',
        'notify_admin_expiry' => 'boolean',
        'notify_order_status_change' => 'boolean',
        'admin_expiry_days_before' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Default message templates
     */
    public static function defaultTemplate(string $event): string
    {
        return match ($event) {
            'product_sold' => "Dear {customer_name}, thank you for your order #{order_id} of {total_amount} BDT. We are processing your items. - {store_name}",
            'user_created' => "Welcome to {store_name}, {user_name}! Your account has been created successfully.",
            'admin_expiry' => "Dear {admin_name}, your portal subscription will expire in {days_left} day(s) on {expiry_date}. Please renew to prevent service disruption.",
            'order_status' => "Dear {customer_name}, your order #{order_id} status has been updated to: {status}. - {store_name}",
            default => '',
        };
    }

    /**
     * Retrieve or create effective settings for a given user/portal or fallback to central/default
     */
    public static function getSettingsForUser(?int $userId = null): self
    {
        if ($userId) {
            $userSetting = self::where('user_id', $userId)->first();
            if ($userSetting) {
                return $userSetting;
            }
        }

        // Global / Super Admin default settings (user_id IS NULL)
        $centralSetting = self::whereNull('user_id')->first();
        if ($centralSetting) {
            return $centralSetting;
        }

        // Create default central settings if none exist
        return self::create([
            'user_id' => null,
            'default_gateway' => 'bulksmsbd',
            'is_enabled' => true,
            'bulksmsbd_url' => env('SMS_API_URL', 'https://bulksmsbd.net/api/smsapi'),
            'bulksmsbd_api_key' => env('SMS_API_KEY', ''),
            'bulksmsbd_sender_id' => env('SMS_SENDER_ID', ''),
            'awaj_url' => 'https://api.awajdigital.com/api',
            'notify_product_sold' => false,
            'notify_user_created' => false,
            'notify_admin_expiry' => true,
            'admin_expiry_days_before' => 3,
            'template_product_sold' => self::defaultTemplate('product_sold'),
            'template_user_created' => self::defaultTemplate('user_created'),
            'template_admin_expiry' => self::defaultTemplate('admin_expiry'),
            'template_order_status' => self::defaultTemplate('order_status'),
        ]);
    }
}
