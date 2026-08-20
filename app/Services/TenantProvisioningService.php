<?php

namespace App\Services;

use App\Models\SaaSTenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TenantProvisioningService
{
    /**
     * Provision a brand-new SaaS tenant database with all schema tables and initial data.
     *
     * @param string $name
     * @param string $subdomain
     * @param string|null $customDbName
     * @param mixed|null $adminUser User instance or array
     * @param string|null $customDomain
     * @return SaaSTenant
     */
    public function provision(string $name, string $subdomain, ?string $customDbName = null, $adminUser = null, ?string $customDomain = null): SaaSTenant
    {
        $cleanCustomDomain = $customDomain ? strtolower(trim(preg_replace('#^https?://#i', '', rtrim($customDomain, '/')))) : null;

        // If subdomain is provided, sanitize it; otherwise derive from custom domain
        if (!empty($subdomain)) {
            $cleanSubdomain = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $subdomain));
        } elseif (!empty($cleanCustomDomain)) {
            $domainParts = explode('.', $cleanCustomDomain);
            $cleanSubdomain = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $domainParts[0]));
        } else {
            $cleanSubdomain = 'tenant_' . time();
        }

        // Database naming: custom, or based on subdomain/custom domain
        $dbName = $customDbName ?: ('purnobd_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $cleanSubdomain));

        // 1. Create MySQL Database if not exists
        $this->createDatabase($dbName);

        // 2. Clone master database schema and initial reference datasets
        $this->cloneMasterSchema($dbName);

        // 3. Configure dynamic connection 'tenant_temp'
        $this->configureTenantConnection($dbName);

        // 4. Run any pending migrations on the tenant database safely
        $this->runMigrations();

        // 5. Seed initial essential data (Roles, Settings, Admin User)
        $this->seedInitialData($name, $adminUser);

        // 6. Register in central saas_tenants table
        $tenant = SaaSTenant::updateOrCreate(
            ['subdomain' => $cleanSubdomain],
            [
                'name' => $name,
                'custom_domain' => $cleanCustomDomain,
                'db_name' => $dbName,
                'is_active' => true,
            ]
        );

        return $tenant;
    }

    /**
     * Re-provision or repair an existing tenant database.
     */
    public function reprovisionExisting(SaaSTenant $tenant, $adminUser = null): bool
    {
        $dbName = $tenant->db_name ?: ('purnobd_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $tenant->subdomain));

        // 1. Ensure DB exists
        $this->createDatabase($dbName);

        // 2. Clone missing tables / schema from master
        $this->cloneMasterSchema($dbName);

        // 3. Configure dynamic connection
        $this->configureTenantConnection($dbName);

        // 4. Run migrations
        $this->runMigrations();

        // 5. Seed essential data
        $this->seedInitialData($tenant->name, $adminUser);

        return true;
    }

    /**
     * Create MySQL database.
     */
    protected function createDatabase(string $dbName): void
    {
        try {
            $charset = config('database.connections.mysql.charset', 'utf8mb4');
            $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');
            
            DB::connection('central')->statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation}");
            Log::info("Tenant Database [{$dbName}] created or verified successfully.");
        } catch (\Throwable $e) {
            Log::error("Failed to create database [{$dbName}]: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clone all master tables and essential config data to the tenant database.
     */
    protected function cloneMasterSchema(string $dbName): void
    {
        try {
            $masterDb = config('database.connections.mysql.database');
            if (empty($masterDb) || $masterDb === $dbName) {
                return;
            }

            // Get all base tables from the master database
            $tables = DB::connection('central')->select("SHOW FULL TABLES FROM `{$masterDb}` WHERE Table_Type = 'BASE TABLE'");
            $key = "Tables_in_{$masterDb}";

            foreach ($tables as $tbl) {
                $tableName = $tbl->$key ?? array_values((array)$tbl)[0] ?? null;
                if (!$tableName) {
                    continue;
                }

                // Exclude central-only tables if necessary
                if ($tableName === 'saas_tenants') {
                    continue;
                }

                // Create table schema matching master table exactly
                DB::connection('central')->statement("CREATE TABLE IF NOT EXISTS `{$dbName}`.`{$tableName}` LIKE `{$masterDb}`.`{$tableName}`");
            }

            // Copy essential preset / reference tables data if empty in target
            $tablesToCopy = [
                'roles',
                'permissions',
                'role_has_permissions',
                'site_settings',
                'ecommerce_settings',
                'pos_settings',
                'sms_templates',
                'email_templates',
                'notification_rules',
                'delayed_event_settings',
                'cities',
                'delivery_locations',
                'product_categories',
                'sub_categories',
                'third_categories',
                'brands',
                'sliders',
                'banners',
                'customer_reviews',
                'activities',
                'menus',
                'menu_items',
                'pages',
                'migrations',
            ];

            foreach ($tablesToCopy as $tableName) {
                try {
                    $targetCount = DB::connection('central')->select("SELECT COUNT(*) as cnt FROM `{$dbName}`.`{$tableName}`");
                    if (!empty($targetCount) && ($targetCount[0]->cnt ?? 0) == 0) {
                        DB::connection('central')->statement("INSERT IGNORE INTO `{$dbName}`.`{$tableName}` SELECT * FROM `{$masterDb}`.`{$tableName}`");
                    }
                } catch (\Throwable $copyEx) {
                    // Ignore non-critical table copy warnings
                    Log::warning("Could not copy table {$tableName} to {$dbName}: " . $copyEx->getMessage());
                }
            }

            Log::info("Cloned master schema from [{$masterDb}] to [{$dbName}] successfully.");
        } catch (\Throwable $e) {
            Log::error("Failed to clone schema to [{$dbName}]: " . $e->getMessage());
            // We won't rethrow here so subsequent migrations can still execute
        }
    }

    /**
     * Setup tenant_temp database connection.
     */
    protected function configureTenantConnection(string $dbName): void
    {
        $defaultConfig = config('database.connections.mysql');
        $defaultConfig['database'] = $dbName;
        
        Config::set('database.connections.tenant_temp', $defaultConfig);
        DB::purge('tenant_temp');
        DB::reconnect('tenant_temp');
    }

    /**
     * Run all migrations on tenant_temp.
     */
    protected function runMigrations(): void
    {
        try {
            Artisan::call('migrate', [
                '--database' => 'tenant_temp',
                '--path' => 'database/migrations',
                '--force' => true,
            ]);
            Log::info("Tenant database migrations completed: " . Artisan::output());
        } catch (\Throwable $e) {
            Log::error("Migration failed on tenant database: " . $e->getMessage());
        }
    }

    /**
     * Seed initial roles, permissions, settings, sliders, reviews, and admin user.
     */
    protected function seedInitialData(string $name, $adminUser = null): void
    {
        try {
            // Seed Spatie Roles & Permissions on tenant_temp
            $roles = ['super_admin', 'admin', 'vendor', 'wholeseller', 'reseller', 'customer'];
            $now = now();
            
            foreach ($roles as $role) {
                DB::connection('tenant_temp')->table('roles')->updateOrInsert(
                    ['name' => $role, 'guard_name' => 'web'],
                    ['created_at' => $now, 'updated_at' => $now]
                );
            }

            // Create or sync the Tenant Admin user in the tenant's users table
            $email = is_object($adminUser) ? $adminUser->email : ($adminUser['email'] ?? null);
            $phone = is_object($adminUser) ? $adminUser->phone : ($adminUser['phone'] ?? null);
            $password = is_object($adminUser) ? $adminUser->password : ($adminUser['password'] ?? Hash::make('12345678'));
            $userName = is_object($adminUser) ? $adminUser->name : ($adminUser['name'] ?? $name);

            if (!empty($email)) {
                $existingUser = DB::connection('tenant_temp')->table('users')->where('email', $email)->first();
                if (!$existingUser) {
                    $userId = DB::connection('tenant_temp')->table('users')->insertGetId([
                        'name' => $userName,
                        'email' => $email,
                        'phone' => $phone,
                        'password' => $password,
                        'otp_verified' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    // Assign super_admin / admin role in tenant_temp
                    $adminRole = DB::connection('tenant_temp')->table('roles')->where('name', 'super_admin')->first()
                        ?: DB::connection('tenant_temp')->table('roles')->where('name', 'admin')->first();

                    if ($adminRole && $userId) {
                        DB::connection('tenant_temp')->table('model_has_roles')->updateOrInsert([
                            'role_id' => $adminRole->id,
                            'model_type' => 'App\\Models\\User',
                            'model_id' => $userId,
                        ]);
                    }
                }
            }

            // Ensure Default Sliders in Tenant Database
            try {
                if (DB::connection('tenant_temp')->table('sliders')->count() == 0) {
                    DB::connection('tenant_temp')->table('sliders')->insert([
                        [
                            'title' => 'Welcome to ' . $name,
                            'description' => 'Discover our exclusive range of high-quality products designed for your comfort and style.',
                            'button_text' => 'Shop Now',
                            'button_url' => '/shop',
                            'image' => 'sliders/vVV0cwK97XSfpTwKjDFLWK47JN1ug2JCzrVnnJeE.webp',
                            'overlay_color' => 'rgba(0, 0, 0, 0.4)',
                            'position' => 1,
                            'status' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                        [
                            'title' => 'Trending Collections',
                            'description' => 'Explore our latest arrivals and premium selections tailored just for you.',
                            'button_text' => 'Explore More',
                            'button_url' => '/shop',
                            'image' => 'sliders/D3t6TPKxOuda0FQZDc1aaOxqOOG0jTKd4i58m5bS.webp',
                            'overlay_color' => 'rgba(0, 0, 0, 0.35)',
                            'position' => 2,
                            'status' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("Slider initial seeding warning: " . $e->getMessage());
            }

            // Ensure Default Customer Reviews in Tenant Database
            try {
                if (DB::connection('tenant_temp')->table('customer_reviews')->count() == 0) {
                    DB::connection('tenant_temp')->table('customer_reviews')->insert([
                        [
                            'reviewer_name' => 'Rafiqul Islam',
                            'reviewer_email' => 'rafiq@example.com',
                            'reviewer_image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                            'review_date' => now()->subDays(5)->toDateString(),
                            'rating' => 5,
                            'product_name' => 'Premium Collection',
                            'product_image' => 'https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                            'review_text' => 'The quality of the product is exceptional. The fit is perfect and the design is elegant. Highly recommended!',
                            'is_active' => 1,
                            'is_verified_purchase' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                        [
                            'reviewer_name' => 'Tanvir Ahmed',
                            'reviewer_email' => 'tanvir@example.com',
                            'reviewer_image' => 'https://randomuser.me/api/portraits/men/44.jpg',
                            'review_date' => now()->subDays(2)->toDateString(),
                            'rating' => 5,
                            'product_name' => 'Exclusive Collection',
                            'product_image' => 'https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                            'review_text' => 'Fast delivery and very good packaging. Product matches the description exactly.',
                            'is_active' => 1,
                            'is_verified_purchase' => 1,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("Customer review initial seeding warning: " . $e->getMessage());
            }

            // Customize Tenant Site Settings
            try {
                $settingsToUpdate = [
                    ['group' => 'general', 'key' => 'site_name', 'value' => $name],
                    ['group' => 'general', 'key' => 'site_title', 'value' => $name],
                    ['group' => 'general', 'key' => 'company_name', 'value' => $name],
                    ['group' => 'general', 'key' => 'top_header_bar_text', 'value' => 'Welcome to ' . $name],
                    ['group' => 'seo', 'key' => 'meta_title', 'value' => $name . ' - Online Store'],
                ];

                if (!empty($email)) {
                    $settingsToUpdate[] = ['group' => 'general', 'key' => 'top_header_bar_email', 'value' => $email];
                    $settingsToUpdate[] = ['group' => 'general', 'key' => 'contact_email', 'value' => $email];
                    $settingsToUpdate[] = ['group' => 'general', 'key' => 'contact_support_email', 'value' => $email];
                }

                if (!empty($phone)) {
                    $settingsToUpdate[] = ['group' => 'general', 'key' => 'top_header_bar_phone', 'value' => $phone];
                    $settingsToUpdate[] = ['group' => 'general', 'key' => 'contact_phone', 'value' => $phone];
                }

                foreach ($settingsToUpdate as $st) {
                    DB::connection('tenant_temp')->table('site_settings')->updateOrInsert(
                        ['group' => $st['group'], 'key' => $st['key']],
                        ['value' => $st['value'], 'updated_at' => $now]
                    );
                }
            } catch (\Throwable $e) {
                Log::warning("Site settings customization warning: " . $e->getMessage());
            }
        } catch (\Throwable $e) {
            Log::warning("Initial seeding for tenant database had warning: " . $e->getMessage());
        }
    }
}
