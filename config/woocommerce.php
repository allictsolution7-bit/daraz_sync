<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WooCommerce Migration Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for migrating data from WooCommerce to this system.
    | You can use either REST API or direct database connection.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Connection Method
    |--------------------------------------------------------------------------
    |
    | Choose how to connect to WooCommerce:
    | - 'api': Use WooCommerce REST API (recommended)
    | - 'database': Direct database connection
    |
    */

    'connection_method' => env('WC_CONNECTION_METHOD', 'api'), // 'api' or 'database'

    /*
    |--------------------------------------------------------------------------
    | WooCommerce REST API Configuration
    |--------------------------------------------------------------------------
    |
    | Required when connection_method is 'api'
    |
    */

    'api' => [
        'url' => env('WC_API_URL', 'https://simascreation.com'),
        'consumer_key' => env('WC_CONSUMER_KEY', ''),
        'consumer_secret' => env('WC_CONSUMER_SECRET', ''),
        'version' => env('WC_API_VERSION', 'wc/v3'),
        'verify_ssl' => env('WC_VERIFY_SSL', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | WooCommerce Database Configuration
    |--------------------------------------------------------------------------
    |
    | Required when connection_method is 'database'
    |
    */

    'database' => [
        'host' => env('WC_DB_HOST', '127.0.0.1'),
        'port' => env('WC_DB_PORT', '3306'),
        'database' => env('WC_DB_DATABASE', ''),
        'username' => env('WC_DB_USERNAME', ''),
        'password' => env('WC_DB_PASSWORD', ''),
        'table_prefix' => env('WC_TABLE_PREFIX', 'wp_'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Settings
    |--------------------------------------------------------------------------
    |
    | General migration settings
    |
    */

    'migration' => [
        // Chunk size for batch processing
        'chunk_size' => env('WC_MIGRATION_CHUNK_SIZE', 50),

        // Whether to update existing records or skip them
        'update_existing' => env('WC_UPDATE_EXISTING', false),

        // Default category ID to use if category not found
        'default_category_id' => env('WC_DEFAULT_CATEGORY_ID', null),

        // Whether to download images from WooCommerce
        'download_images' => env('WC_DOWNLOAD_IMAGES', true),

        // Image download directory
        'images_directory' => env('WC_IMAGES_DIRECTORY', 'public/product'),

        // Whether to preserve WooCommerce IDs (create mapping table)
        'preserve_woocommerce_ids' => env('WC_PRESERVE_IDS', true),

        // Log migration progress
        'log_progress' => env('WC_LOG_PROGRESS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Mapping
    |--------------------------------------------------------------------------
    |
    | Map WooCommerce fields to your system's fields
    |
    */

    'mapping' => [
        // Product status mapping (WooCommerce → Your System)
        // Note: status is a boolean field (1 = active, 0 = inactive)
        'product_status' => [
            'publish' => 1,  // active
            'draft' => 0,    // inactive
            'private' => 0,  // inactive
            'pending' => 0,  // inactive
        ],

        // Order status mapping (WooCommerce → Your System)
        'order_status' => [
            'pending' => 'pending',
            'processing' => 'processing',
            'on-hold' => 'on_hold',
            'completed' => 'delivered',
            'cancelled' => 'cancelled',
            'refunded' => 'cancelled',
            'failed' => 'cancelled',
        ],

        // Stock status mapping
        'stock_status' => [
            'instock' => 'in_stock',
            'outofstock' => 'out_of_stock',
            'onbackorder' => 'on_backorder',
        ],
    ],
];

