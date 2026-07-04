<?php

namespace Database\Seeders;

use App\Models\FraudProtectionSetting;
use Illuminate\Database\Seeder;

class FraudProtectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FraudProtectionSetting::updateOrCreate(
            ['id' => 1],
            [
                // Module 1: Duplicate Order Protection
                'duplicate_protection_enabled' => false,
                'order_interval_enabled' => false,
                'order_interval_minutes' => 60,
                'pending_order_restriction_enabled' => false,
                
                // Module 2: Fake Order Protection
                'fake_protection_enabled' => false,
                'phone_validation_enabled' => false,
                'allowed_phone_lengths' => [11, 12, 14],
                'block_sequential_numbers' => false,
                'block_repeated_names' => false,
                'block_gibberish_names' => false,
                'custom_pattern_enabled' => false,
                'blocked_phone_patterns' => [],
                'blocked_name_patterns' => ['test', 'demo', 'fake', 'sample'],
                
                // Module 3: Fraud & Scam Protection
                'fraud_protection_enabled' => false,
                'blacklist_enabled' => false,
                'blacklisted_phones' => [
                    '01700000000',
                    '01711111111',
                    '01722222222',
                ],
                'blacklisted_ips' => [],
                'ip_rate_limiting_enabled' => false,
                'ip_max_orders_per_hour' => 5,
                'courier_success_check_enabled' => false,
                'min_success_rate' => 20.00,
                'max_bad_history_rate' => 80.00,
                
                // Alert & Notifications
                'send_admin_alerts' => false,
                'admin_alert_email' => null,
                'log_blocked_attempts' => true,
                
                // Custom Messages (Bengali)
                'duplicate_order_message' => 'আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
                'fake_data_message' => 'অবৈধ তথ্য সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।',
                'fraud_detected_message' => 'নিরাপত্তা কারণে আপনার অর্ডার ব্লক করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
                'blacklist_message' => 'আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
            ]
        );

        $this->command->info('✅ Fraud Protection settings seeded successfully!');
    }
}

