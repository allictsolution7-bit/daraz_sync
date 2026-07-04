<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudProtectionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        // Module 1: Duplicate Order Protection
        'duplicate_protection_enabled',
        'order_interval_enabled',
        'order_interval_minutes',
        'pending_order_restriction_enabled',
        
        // Module 2: Fake Order Protection
        'fake_protection_enabled',
        'phone_validation_enabled',
        'phone_whitelist_enabled',
        'allowed_phone_lengths',
        'name_validation_enabled',
        'name_min_length',
        'name_max_length',
        'name_disallow_numeric',
        'block_sequential_numbers',
        'block_repeated_names',
        'block_gibberish_names',
        'custom_pattern_enabled',
        'blocked_phone_patterns',
        'blocked_name_patterns',
        'blocked_address_patterns',
        'address_validation_enabled',
        'address_min_length',
        
        // Module 3: Fraud & Scam Protection
        'fraud_protection_enabled',
        'blacklist_enabled',
        'blacklisted_phones',
        'blacklisted_ips',
        'ip_rate_limiting_enabled',
        'ip_max_orders_per_hour',
        'courier_success_check_enabled',
        'min_success_rate',
        'max_bad_history_rate',
        
        // Alert & Notifications
        'send_admin_alerts',
        'admin_alert_email',
        'log_blocked_attempts',
        
        // Custom Messages
        'duplicate_order_message',
        'fake_data_message',
        'fraud_detected_message',
        'blacklist_message',
    ];

    protected $casts = [
        'duplicate_protection_enabled' => 'boolean',
        'order_interval_enabled' => 'boolean',
        'pending_order_restriction_enabled' => 'boolean',
        'fake_protection_enabled' => 'boolean',
        'phone_validation_enabled' => 'boolean',
        'phone_whitelist_enabled' => 'boolean',
        'allowed_phone_lengths' => 'array',
        'name_validation_enabled' => 'boolean',
        'name_min_length' => 'integer',
        'name_max_length' => 'integer',
        'name_disallow_numeric' => 'boolean',
        'block_sequential_numbers' => 'boolean',
        'block_repeated_names' => 'boolean',
        'block_gibberish_names' => 'boolean',
        'custom_pattern_enabled' => 'boolean',
        'blocked_phone_patterns' => 'array',
        'blocked_name_patterns' => 'array',
        'blocked_address_patterns' => 'array',
        'address_validation_enabled' => 'boolean',
        'address_min_length' => 'integer',
        'fraud_protection_enabled' => 'boolean',
        'blacklist_enabled' => 'boolean',
        'blacklisted_phones' => 'array',
        'blacklisted_ips' => 'array',
        'ip_rate_limiting_enabled' => 'boolean',
        'courier_success_check_enabled' => 'boolean',
        'min_success_rate' => 'decimal:2',
        'max_bad_history_rate' => 'decimal:2',
        'send_admin_alerts' => 'boolean',
        'log_blocked_attempts' => 'boolean',
    ];

    /**
     * Get the singleton instance of fraud protection settings
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'duplicate_protection_enabled' => false,
                'fake_protection_enabled' => false,
                'fraud_protection_enabled' => false,
                'allowed_phone_lengths' => [11, 12, 14],
                'order_interval_minutes' => 60,
                'ip_max_orders_per_hour' => 5,
                'min_success_rate' => 20.00,
                'max_bad_history_rate' => 80.00,
                'duplicate_order_message' => 'আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
                'fake_data_message' => 'অবৈধ তথ্য সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।',
                'fraud_detected_message' => 'নিরাপত্তা কারণে আপনার অর্ডার ব্লক করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
                'blacklist_message' => 'আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
            ]);
        }
        
        return $settings;
    }

    /**
     * Check if any module is enabled
     */
    public function isAnyModuleEnabled(): bool
    {
        return $this->duplicate_protection_enabled || 
               $this->fake_protection_enabled || 
               $this->fraud_protection_enabled;
    }
}

