<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class VendorRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates all roles and permissions needed for the multi-seller system.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // STEP 1: Create Roles
        // ==========================================
        
        $roles = [
            'super_admin',
            'admin',
            'vendor',
            'vendor_staff',
            'customer',
            'reseller',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );
        }

        $this->command->info('✅ Roles created successfully');

        // ==========================================
        // STEP 2: Create Permissions
        // ==========================================
        
        $permissions = [
            // Vendor Dashboard Permissions
            'vendor.dashboard.view',
            
            // Vendor Product Permissions
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            
            // Vendor Order Permissions (VIEW ONLY - platform manages orders)
            'vendor.orders.view',
            
            // Vendor Financial Permissions
            'vendor.balance.view',
            'vendor.withdrawals.create',
            'vendor.withdrawals.cancel',
            
            // Vendor Reports Permissions
            'vendor.reports.sales',
            'vendor.reports.products',
            
            // Vendor Settings Permissions
            'vendor.profile.edit',
            
            // Module Feature Permissions: Point of Sale (POS)
            'admin.pos.access',
            'admin.pos.create-order',
            'admin.pos.manage-settings',

            // Module Feature Permissions: Multi-Vendor Marketplace
            'admin.multi-vendor.access',
            'admin.multi-vendor.manage-vendors',
            'admin.multi-vendor.approve-products',
            'admin.multi-vendor.process-withdrawals',
            'admin.multi-vendor.global-config',

            // Module Feature Permissions: Landing Page Builder
            'admin.landing-pages.access',
            'admin.landing-pages.create',
            'admin.landing-pages.edit',
            'admin.landing-pages.delete',

            // Module Feature Permissions: Blog & Content System
            'admin.blogs.access',
            'admin.blogs.create',
            'admin.blogs.edit',
            'admin.blogs.delete',

            // Admin Vendor Management Permissions
            'admin.vendors.view',
            'admin.vendors.create',
            'admin.vendors.edit',
            'admin.vendors.delete',
            'admin.vendors.approve',
            'admin.vendors.suspend',
            
            // Admin Partner Items / Product Approval Permissions
            'admin.products.view-all',
            'admin.products.approve',
            'admin.products.reject',
            'admin.products.edit-commission',
            
            // Admin Partner Payouts & Financial Permissions
            'admin.withdrawals.view',
            'admin.withdrawals.approve',
            'admin.withdrawals.reject',
            'admin.withdrawals.complete',
            'admin.commissions.view',
            'admin.commissions.edit',
            
            // Admin Partner Global Configurations
            'admin.vendor-settings.global',
            'admin.vendor-settings.update',
            
            // Admin Reports Permissions
            'admin.reports.vendors',
            'admin.reports.commissions',
            'admin.vendor-balance.view',
            'admin.vendor-balance.adjust',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $this->command->info('✅ Permissions created successfully');

        // ==========================================
        // STEP 3: Assign Permissions to Roles
        // ==========================================
        
        // Super Admin - All permissions
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());
        
        // Admin - All admin permissions + view vendor permissions
        $admin = Role::findByName('admin');
        $adminPermissions = Permission::where('name', 'like', 'admin.%')->get();
        $admin->givePermissionTo($adminPermissions);
        $admin->givePermissionTo([
            'vendor.dashboard.view',
            'vendor.products.view',
            'vendor.orders.view',
            'vendor.balance.view',
            'vendor.reports.sales',
            'vendor.reports.products',
        ]);
        
        // Vendor - All vendor permissions
        $vendor = Role::findByName('vendor');
        $vendorPermissions = Permission::where('name', 'like', 'vendor.%')->get();
        $vendor->givePermissionTo($vendorPermissions);
        
        // Vendor Staff - Limited vendor permissions (no financial access)
        $vendorStaff = Role::findByName('vendor_staff');
        $vendorStaff->givePermissionTo([
            'vendor.dashboard.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.orders.view',  // Can view orders (read-only)
            'vendor.reports.products',
        ]);
        
        // Customer - No special vendor permissions needed
        $customer = Role::findByName('customer');
        // Customers don't need special vendor permissions in this model

        $this->command->info('✅ Permissions assigned to roles successfully');

        // ==========================================
        // STEP 4: Display Summary
        // ==========================================
        
        $this->command->newLine();
        $this->command->info('📊 Multi-Seller Roles & Permissions Summary:');
        $this->command->table(
            ['Role', 'Permissions Count'],
            [
                ['super_admin', $superAdmin->permissions->count()],
                ['admin', $admin->permissions->count()],
                ['vendor', $vendor->permissions->count()],
                ['vendor_staff', $vendorStaff->permissions->count()],
                ['customer', $customer->permissions->count()],
            ]
        );
        
        $this->command->newLine();
        $this->command->info('✅ All roles and permissions have been seeded successfully!');
        $this->command->newLine();
        $this->command->warn('⚠️  Remember to assign roles to users:');
        $this->command->line('   $user->assignRole(\'vendor\');');
    }
}

