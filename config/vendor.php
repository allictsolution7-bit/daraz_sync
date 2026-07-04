<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vendor System Configuration
    |--------------------------------------------------------------------------
    |
    | Global defaults for the multi-seller/vendor system.
    | Individual vendors can override these in their vendor_settings.
    |
    */

    // ==========================================
    // GLOBAL COMMISSION SETTINGS
    // ==========================================
    'commission' => [
        // Default commission % for all vendors (if not set in vendor_settings)
        'global_rate' => env('VENDOR_GLOBAL_COMMISSION_RATE', 15.00),
        
        // Minimum commission % vendors can propose
        'min_rate' => env('VENDOR_MIN_COMMISSION_RATE', 10.00),
        
        // Maximum commission % vendors can propose
        'max_rate' => env('VENDOR_MAX_COMMISSION_RATE', 30.00),
    ],

    // ==========================================
    // GLOBAL WITHDRAWAL SETTINGS
    // ==========================================
    'withdrawal' => [
        // Minimum amount vendors can withdraw (in BDT)
        'min_amount' => env('VENDOR_MIN_WITHDRAWAL_AMOUNT', 500.00),
        
        // Maximum amount per withdrawal (0 = no limit)
        'max_amount' => env('VENDOR_MAX_WITHDRAWAL_AMOUNT', 0),
        
        // Auto-approve withdrawals under this amount (0 = always manual)
        'auto_approve_threshold' => env('VENDOR_AUTO_APPROVE_WITHDRAWAL_THRESHOLD', 0),
        
        // Withdrawal processing time (business days)
        'processing_days' => env('VENDOR_WITHDRAWAL_PROCESSING_DAYS', 3),
    ],

    // ==========================================
    // PRODUCT APPROVAL SETTINGS
    // ==========================================
    'products' => [
        // Auto-approve all vendor products (false = manual approval)
        'auto_approve' => env('VENDOR_AUTO_APPROVE_PRODUCTS', false),
        
        // Auto-approve products from verified vendors only
        'auto_approve_verified_only' => env('VENDOR_AUTO_APPROVE_VERIFIED_VENDORS', false),
        
        // Require admin approval for commission rate changes
        'require_commission_approval' => env('VENDOR_REQUIRE_COMMISSION_APPROVAL', true),
    ],

    // ==========================================
    // VENDOR REGISTRATION SETTINGS
    // ==========================================
    'registration' => [
        // Allow new vendor registrations
        'enabled' => env('VENDOR_REGISTRATION_ENABLED', true),
        
        // Require email verification
        'require_email_verification' => env('VENDOR_REQUIRE_EMAIL_VERIFICATION', true),
        
        // Auto-activate vendors after registration (false = manual approval)
        'auto_activate' => env('VENDOR_AUTO_ACTIVATE', false),
    ],

    // ==========================================
    // PAYOUT METHODS
    // ==========================================
    'payout_methods' => [
        'bank' => 'Bank Transfer',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
        'rocket' => 'Rocket',
        'other' => 'Other',
    ],

    // ==========================================
    // NOTIFICATIONS
    // ==========================================
    'notifications' => [
        // Notify vendor when product is approved/rejected
        'product_approval' => env('VENDOR_NOTIFY_PRODUCT_APPROVAL', true),
        
        // Notify vendor when order contains their product
        'new_order' => env('VENDOR_NOTIFY_NEW_ORDER', true),
        
        // Notify vendor when order is completed (they earn money)
        'order_completed' => env('VENDOR_NOTIFY_ORDER_COMPLETED', true),
        
        // Notify vendor when withdrawal is processed
        'withdrawal_processed' => env('VENDOR_NOTIFY_WITHDRAWAL_PROCESSED', true),
    ],

    // ==========================================
    // DASHBOARD SETTINGS
    // ==========================================
    'dashboard' => [
        // Days to show in dashboard stats
        'stats_days' => 30,
        
        // Number of recent orders to show
        'recent_orders_limit' => 10,
        
        // Number of recent products to show
        'recent_products_limit' => 5,
    ],

];

