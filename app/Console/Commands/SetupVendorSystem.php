<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\VendorSetting;
use App\Models\VendorGlobalSetting;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SetupVendorSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vendor:setup {--demo : Create demo vendor account}';

    /**
     * The console command description.
     */
    protected $description = 'Setup the multi-seller vendor system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Multi-Seller Vendor System...');
        $this->newLine();

        // Step 1: Check migrations
        $this->info('Step 1: Checking migrations...');
        if (!$this->checkMigrations()) {
            if ($this->confirm('Migrations not run. Run them now?', true)) {
                $this->call('migrate');
            } else {
                $this->error('❌ Migrations required. Aborting.');
                return 1;
            }
        }
        $this->info('✅ Migrations OK');
        $this->newLine();

        // Step 2: Run seeder
        $this->info('Step 2: Seeding roles, permissions, and settings...');
        if ($this->confirm('Run VendorSystemSeeder?', true)) {
            $this->call('db:seed', ['--class' => 'VendorSystemSeeder']);
            $this->info('✅ Seeded successfully');
        }
        $this->newLine();

        // Step 3: Clear cache
        $this->info('Step 3: Clearing cache...');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->info('✅ Cache cleared');
        $this->newLine();

        // Step 4: Enable vendor system
        $this->info('Step 4: Enabling vendor system...');
        if ($this->confirm('Enable vendor system now?', true)) {
            VendorGlobalSetting::set('vendor_system_enabled', true, 'boolean');
            $this->info('✅ Vendor system enabled!');
        }
        $this->newLine();

        // Step 5: Create demo vendor (optional)
        if ($this->option('demo') || $this->confirm('Create demo vendor account?', false)) {
            $this->createDemoVendor();
        }

        $this->newLine();
        $this->info('🎉 Vendor system setup complete!');
        $this->newLine();
        
        $this->displaySummary();

        return 0;
    }

    /**
     * Check if vendor system migrations have been run
     */
    protected function checkMigrations(): bool
    {
        try {
            // Check if vendor_withdrawals table exists
            return \Schema::hasTable('vendor_withdrawals') && 
                   \Schema::hasTable('vendor_balance_ledgers') &&
                   \Schema::hasTable('vendor_global_settings');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create demo vendor account
     */
    protected function createDemoVendor(): void
    {
        $this->info('Creating demo vendor account...');

        $email = $this->ask('Vendor email', 'vendor@demo.com');
        
        // Check if user exists
        if (User::where('email', $email)->exists()) {
            $this->warn('⚠️  User with this email already exists!');
            return;
        }

        $password = $this->secret('Vendor password (min 8 chars)') ?: 'password';
        $name = $this->ask('Vendor name', 'Demo Vendor');
        $businessName = $this->ask('Business name', 'Demo Store');
        $phone = $this->ask('Phone', '01700000000');

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
        ]);

        // Assign vendor role
        $user->assignRole('vendor');

        // Create vendor settings
        VendorSetting::create([
            'vendor_id' => $user->id,
            'business_name' => $businessName,
            'business_email' => $email,
            'business_phone' => $phone,
            'is_active' => true,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $this->newLine();
        $this->info('✅ Demo vendor created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Email', $email],
                ['Password', '********'],
                ['Name', $name],
                ['Business', $businessName],
                ['Status', 'Active & Verified'],
            ]
        );
    }

    /**
     * Display setup summary
     */
    protected function displaySummary(): void
    {
        $this->info('📊 System Summary:');
        $this->table(
            ['Item', 'Status'],
            [
                ['Vendor System', VendorGlobalSetting::isVendorSystemEnabled() ? '✅ Enabled' : '❌ Disabled'],
                ['Global Commission', VendorGlobalSetting::getGlobalCommissionRate() . '%'],
                ['Min Withdrawal', '৳' . VendorGlobalSetting::get('vendor_min_withdrawal_amount', 500)],
                ['Total Vendors', User::role('vendor')->count()],
            ]
        );

        $this->newLine();
        $this->info('🔗 Access URLs:');
        $this->line('  Vendor Panel: ' . url('/vendor/dashboard'));
        $this->line('  Admin Vendors: ' . url('/admin/vendors'));
        $this->line('  Product Approval: ' . url('/admin/vendor-products'));
        $this->line('  Withdrawals: ' . url('/admin/vendor-withdrawals'));
        $this->line('  Global Settings: ' . url('/admin/vendor-settings/global'));
    }
}

