<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default namespace for your modules.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Path to the stubs directory for module generation.
    |
    */
    'stubs' => [
        'enabled' => false,
        'path' => base_path('stubs/modules'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Paths
    |--------------------------------------------------------------------------
    |
    | Where modules are stored. Can be an array for multiple locations.
    |
    */
    'paths' => [
        'modules' => base_path('Modules'),
        'assets' => public_path('modules'),
        'migration' => base_path('database/migrations'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Configuration
    |--------------------------------------------------------------------------
    |
    | Directories to scan for modules.
    |
    */
    'scan' => [
        'enabled' => true,
        'paths' => [
            base_path('Modules'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Generator
    |--------------------------------------------------------------------------
    |
    | Configuration for module generation.
    |
    */
    'generator' => [
        'config' => ['path' => 'Config', 'generate' => true],
        'command' => ['path' => 'Console', 'generate' => false],
        'migration' => ['path' => 'Database/Migrations', 'generate' => true],
        'seeder' => ['path' => 'Database/Seeders', 'generate' => false],
        'factory' => ['path' => 'Database/Factories', 'generate' => false],
        'model' => ['path' => 'Models', 'generate' => false],
        'routes' => ['path' => 'Routes', 'generate' => true],
        'controller' => ['path' => 'Http/Controllers', 'generate' => true],
        'filter' => ['path' => 'Http/Middleware', 'generate' => false],
        'request' => ['path' => 'Http/Requests', 'generate' => false],
        'provider' => ['path' => 'Providers', 'generate' => true],
        'assets' => ['path' => 'Resources/assets', 'generate' => false],
        'lang' => ['path' => 'Resources/lang', 'generate' => false],
        'views' => ['path' => 'Resources/views', 'generate' => true],
        'test' => ['path' => 'Tests/Unit', 'generate' => false],
        'test-feature' => ['path' => 'Tests/Feature', 'generate' => false],
        'repository' => ['path' => 'Repositories', 'generate' => false],
        'event' => ['path' => 'Events', 'generate' => false],
        'listener' => ['path' => 'Listeners', 'generate' => false],
        'policies' => ['path' => 'Policies', 'generate' => false],
        'rules' => ['path' => 'Rules', 'generate' => false],
        'jobs' => ['path' => 'Jobs', 'generate' => false],
        'emails' => ['path' => 'Emails', 'generate' => false],
        'notifications' => ['path' => 'Notifications', 'generate' => false],
        'resource' => ['path' => 'Transformers', 'generate' => false],
        'component-view' => ['path' => 'Resources/views/components', 'generate' => false],
        'component-class' => ['path' => 'View/Components', 'generate' => false],
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Cache module information for better performance.
    |
    */
    'cache' => [
        'enabled' => true,
        'key' => 'modules',
        'lifetime' => 60, // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Modules
    |--------------------------------------------------------------------------
    |
    | List of all available modules in the system.
    | This is used for license validation and module management.
    |
    */
    'available' => [
        'POS' => [
            'name' => 'Point of Sale',
            'description' => 'Complete POS system for physical store management',
            'license_key' => 'pos',
            'version' => '2.6.0',
            'is_premium' => true,
            'requires_support' => true,
        ],
        'MultiVendor' => [
            'name' => 'Multi-Vendor Marketplace',
            'description' => 'Multi-vendor marketplace system for managing sellers, products, commissions, and withdrawals',
            'license_key' => 'multi_vendor',
            'version' => '2.6.0',
            'is_premium' => true,
            'requires_support' => true,
        ],
        'LandingPage' => [
            'name' => 'Landing Page Builder',
            'description' => 'Create custom landing pages for products',
            'license_key' => 'landing_page',
            'version' => '2.6.0',
            'is_premium' => true,
            'requires_support' => true,
        ],
        'Blog' => [
            'name' => 'Blog System',
            'description' => 'Blog and content management system',
            'license_key' => 'blog',
            'version' => '2.6.0',
            'is_premium' => false,
            'requires_support' => false,
        ],
        'Daraz' => [
            'name' => 'Daraz Stock Sync',
            'description' => 'Sync stock/inventory with multiple Daraz seller stores (BD, PK, LK, NP)',
            'license_key' => null,
            'version' => '1.0.0',
            'is_premium' => false,
            'requires_support' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Activator Settings
    |--------------------------------------------------------------------------
    |
    | Settings for module activation. Uses ModuleManager for hybrid activation.
    |
    */
    'activator' => [
        'statuses_file' => base_path('modules_statuses.json'),
        'cache_key' => 'module_statuses',
        'cache_lifetime' => 1440, // 24 hours in minutes
    ],

];
