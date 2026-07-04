<?php

return [
    'name' => 'Daraz',

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'timeout' => 30, // seconds
        'retry_attempts' => 3,
        'retry_delay' => 1000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Country-specific API URLs
    |--------------------------------------------------------------------------
    */
    'countries' => [
        'BD' => [
            'name' => 'Bangladesh',
            'api_url' => 'https://api.daraz.com.bd/rest',
            'auth_url' => 'https://api.daraz.com.bd/oauth/authorize',
        ],
        'PK' => [
            'name' => 'Pakistan',
            'api_url' => 'https://api.daraz.pk/rest',
            'auth_url' => 'https://api.daraz.pk/oauth/authorize',
        ],
        'LK' => [
            'name' => 'Sri Lanka',
            'api_url' => 'https://api.daraz.lk/rest',
            'auth_url' => 'https://api.daraz.lk/oauth/authorize',
        ],
        'NP' => [
            'name' => 'Nepal',
            'api_url' => 'https://api.daraz.com.np/rest',
            'auth_url' => 'https://api.daraz.com.np/oauth/authorize',
        ],
        'MM' => [
            'name' => 'Myanmar',
            'api_url' => 'https://api.shop.com.mm/rest',
            'auth_url' => 'https://api.shop.com.mm/oauth/authorize',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync Settings
    |--------------------------------------------------------------------------
    */
    'sync' => [
        'default_interval' => 30, // minutes
        'batch_size' => 50, // products per batch
        'queue_connection' => 'default',
        'stock_buffer_default' => 0, // default reserve buffer
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'retention_days' => 30, // auto-delete logs older than this
    ],
];
