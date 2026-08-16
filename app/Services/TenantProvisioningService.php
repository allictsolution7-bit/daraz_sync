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
     * @return SaaSTenant
     */
    public function provision(string $name, string $subdomain, ?string $customDbName = null, $adminUser = null): SaaSTenant
    {
        $cleanSubdomain = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $subdomain));
        $dbName = $customDbName ?: 'purnobd_' . $cleanSubdomain;

        // 1. Create MySQL Database if not exists
        $this->createDatabase($dbName);

        // 2. Configure dynamic connection 'tenant_temp'
        $this->configureTenantConnection($dbName);

        // 3. Run all migrations on the new tenant database
        $this->runMigrations();

        // 4. Seed initial essential data (Roles, Settings, Admin User)
        $this->seedInitialData($name, $adminUser);

        // 5. Register in central saas_tenants table
        $tenant = SaaSTenant::updateOrCreate(
            ['subdomain' => $cleanSubdomain],
            [
                'name' => $name,
                'db_name' => $dbName,
                'is_active' => true,
            ]
        );

        return $tenant;
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
     * Seed initial roles, permissions, settings, and admin user.
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
            if ($adminUser) {
                $email = is_object($adminUser) ? $adminUser->email : ($adminUser['email'] ?? null);
                $phone = is_object($adminUser) ? $adminUser->phone : ($adminUser['phone'] ?? null);
                $password = is_object($adminUser) ? $adminUser->password : ($adminUser['password'] ?? Hash::make('12345678'));
                $userName = is_object($adminUser) ? $adminUser->name : ($adminUser['name'] ?? $name);

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
        } catch (\Throwable $e) {
            Log::warning("Initial seeding for tenant database had warning: " . $e->getMessage());
        }
    }
}
