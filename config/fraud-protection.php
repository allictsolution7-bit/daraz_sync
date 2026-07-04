<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Fraud Protection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for the Fraud Protection System.
    | You can override these values from the admin panel.
    |
    */

    'defaults' => [
        
        // Module 1: Duplicate Order Protection
        'duplicate_protection' => [
            'enabled' => env('FRAUD_DUPLICATE_ENABLED', false),
            'order_interval_minutes' => env('FRAUD_ORDER_INTERVAL', 60),
            'pending_restriction' => env('FRAUD_PENDING_RESTRICTION', false),
        ],

        // Module 2: Fake Order Protection
        'fake_protection' => [
            'enabled' => env('FRAUD_FAKE_ENABLED', false),
            'allowed_phone_lengths' => [11, 12, 14],
            'block_sequential' => env('FRAUD_BLOCK_SEQUENTIAL', false),
            'block_repeated_names' => env('FRAUD_BLOCK_REPEATED', false),
            'block_gibberish' => env('FRAUD_BLOCK_GIBBERISH', false),
        ],

        // Module 3: Fraud & Scam Protection
        'fraud_protection' => [
            'enabled' => env('FRAUD_SCAM_ENABLED', false),
            'ip_max_orders_per_hour' => env('FRAUD_IP_MAX_ORDERS', 5),
            'min_success_rate' => env('FRAUD_MIN_SUCCESS_RATE', 20.0),
            'max_bad_history_rate' => env('FRAUD_MAX_BAD_RATE', 80.0),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Error Messages
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'duplicate_order' => 'আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
        'fake_data' => 'অবৈধ তথ্য সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।',
        'fraud_detected' => 'নিরাপত্তা কারণে আপনার অর্ডার ব্লক করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
        'blacklist' => 'আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।',
        'invalid_phone' => 'ফোন নম্বর ১১, ১২, বা ১৪ ডিজিটের হতে হবে।',
        'invalid_name' => 'অবৈধ নাম সনাক্ত করা হয়েছে। অনুগ্রহ করে আপনার প্রকৃত নাম দিন।',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pattern Detection Rules
    |--------------------------------------------------------------------------
    */

    'patterns' => [
        
        // Keyboard patterns to detect as gibberish
        'keyboard_patterns' => [
            'asdf', 'qwer', 'zxcv', 'hjkl', 
            'asdfgh', 'qwerty', 'zxcvbn',
            '1234', '12345', 'abcd', 'abcde',
        ],

        // Common test/fake words
        'test_words' => [
            'test', 'demo', 'fake', 'sample', 
            'dummy', 'trial', 'example',
        ],

        // Suspicious name patterns (regex)
        'suspicious_names' => [
            '/^(.)\1+$/',           // Single character repeated (aaaa)
            '/(.)\1{4,}/',          // 5+ consecutive same chars
            '/^\d+$/',              // Only numbers
            '/^[a-z]{1,3}$/',       // Too short (a, ab, abc)
        ],

        // Suspicious phone patterns (regex)
        'suspicious_phones' => [
            '/^(\d)\1+$/',          // All same digits (0000000000)
            '/(\d)\1{5,}/',         // 6+ consecutive same digits
            '/^0{5,}/',             // Starts with 5+ zeros
            '/^1{5,}/',             // Starts with 5+ ones
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    */

    'rate_limiting' => [
        'cache_driver' => env('CACHE_DRIVER', 'file'),
        'cache_prefix' => 'fraud_protection',
        'ip_tracking_duration' => 60, // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => true,
        'channel' => env('FRAUD_LOG_CHANNEL', 'daily'),
        'log_level' => env('FRAUD_LOG_LEVEL', 'warning'),
        'include_request_data' => true,
        'include_ip' => true,
        'include_user_agent' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert Configuration
    |--------------------------------------------------------------------------
    */

    'alerts' => [
        'email' => [
            'enabled' => env('FRAUD_EMAIL_ALERTS', false),
            'to' => env('FRAUD_ALERT_EMAIL', 'admin@example.com'),
            'subject' => 'Fraud Alert: Suspicious Order Blocked',
        ],
        'sms' => [
            'enabled' => env('FRAUD_SMS_ALERTS', false),
            'to' => env('FRAUD_ALERT_PHONE', null),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Whitelisting (Override Protection)
    |--------------------------------------------------------------------------
    */

    'whitelist' => [
        'enabled' => env('FRAUD_WHITELIST_ENABLED', false),
        'phones' => [
            // Add trusted phone numbers here
            // '01712345678',
        ],
        'ips' => [
            // Add trusted IPs here
            // '127.0.0.1',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Settings
    |--------------------------------------------------------------------------
    */

    'advanced' => [
        // Minimum vowels in name (for gibberish detection)
        'min_vowels_in_name' => 1,
        
        // Maximum consecutive same characters allowed
        'max_consecutive_chars' => 4,
        
        // Minimum name length
        'min_name_length' => 2,
        
        // Maximum name length
        'max_name_length' => 100,
        
        // Courier history minimum orders to check
        'min_orders_for_history_check' => 3,
        
        // Cache duration for IP rate limiting (minutes)
        'ip_cache_duration' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Development/Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => [
        'enabled' => env('FRAUD_DEBUG', false),
        'bypass_in_local' => env('FRAUD_BYPASS_LOCAL', false),
        'log_all_validations' => env('FRAUD_LOG_ALL', false),
    ],
];

