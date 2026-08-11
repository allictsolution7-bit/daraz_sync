<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $roles = [
            'reseller',
            'vendor',
            'paid_vendor',
            'retailer',
            'wholeseller',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Permissions
        $permissions = [
            // Vendor Dashboard
            'vendor.dashboard.view',

            // Vendor Chats
            'vendor.chats.view',

            // Vendor Products
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',

            // Vendor POS
            'vendor.pos.view',
            'vendor.pos.create',

            // Vendor POS Orders / Reseller Orders
            'vendor.orders.reseller.view',
            'vendor.orders.reseller.create',
            'vendor.orders.reseller.edit',
            'vendor.orders.reseller.delete',

            // Vendor Customers
            'vendor.customers.view',
            'vendor.customers.create',
            'vendor.customers.edit',
            'vendor.customers.delete',

            // Vendor Orders (all orders containing vendor products)
            'vendor.orders.view',
            'vendor.orders.toggle-payment-status',
            'vendor.orders.earnings',

            // Vendor Wallet & Withdrawals
            'vendor.wallet.view',
            'vendor.wallet.recharge',
            'vendor.wallet.transfer',
            'vendor.withdrawals.view',
            'vendor.withdrawals.create',
            'vendor.withdrawals.cancel',

            // Vendor Settings & Profile
            'vendor.profile.view',
            'vendor.profile.edit',

            // Vendor Support / Ticket System
            'vendor.support.view',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. Assign Default Permissions to Roles

        // Reseller default permissions
        $reseller = Role::findByName('reseller');
        $reseller->syncPermissions([
            'vendor.dashboard.view',
            'vendor.chats.view',
            'vendor.pos.view',
            'vendor.pos.create',
            'vendor.orders.reseller.view',
            'vendor.orders.reseller.create',
            'vendor.orders.reseller.edit',
            'vendor.orders.reseller.delete',
            'vendor.customers.view',
            'vendor.customers.create',
            'vendor.customers.edit',
            'vendor.customers.delete',
            'vendor.orders.view',
            'vendor.orders.earnings',
            'vendor.profile.view',
            'vendor.profile.edit',
            'vendor.support.view',
        ]);

        // Wholeseller default permissions
        $wholeseller = Role::findByName('wholeseller');
        $wholeseller->syncPermissions([
            'vendor.dashboard.view',
            'vendor.chats.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            'vendor.orders.view',
            'vendor.orders.earnings',
            'vendor.wallet.view',
            'vendor.wallet.recharge',
            'vendor.wallet.transfer',
            'vendor.withdrawals.view',
            'vendor.withdrawals.create',
            'vendor.withdrawals.cancel',
            'vendor.profile.view',
            'vendor.profile.edit',
            'vendor.support.view',
        ]);

        // Retailer default permissions (same as vendor/wholeseller, potentially no advanced wallet features unless consignment is enabled)
        $retailer = Role::findByName('retailer');
        $retailer->syncPermissions([
            'vendor.dashboard.view',
            'vendor.chats.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            'vendor.orders.view',
            'vendor.orders.earnings',
            'vendor.profile.view',
            'vendor.profile.edit',
            'vendor.support.view',
        ]);

        // Paid Vendor default permissions
        $paidVendor = Role::findByName('paid_vendor');
        $paidVendor->syncPermissions([
            'vendor.dashboard.view',
            'vendor.chats.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            'vendor.orders.view',
            'vendor.orders.earnings',
            'vendor.profile.view',
            'vendor.profile.edit',
            'vendor.support.view',
        ]);

        // Standard Vendor default permissions
        $vendor = Role::findByName('vendor');
        $vendor->syncPermissions([
            'vendor.dashboard.view',
            'vendor.chats.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            'vendor.orders.view',
            'vendor.orders.earnings',
            'vendor.profile.view',
            'vendor.profile.edit',
            'vendor.support.view',
        ]);

        // Automatically give all vendor permissions to Super Admin and Admin
        $superAdmin = Role::whereName('super_admin')->first() ?? Role::whereName('super admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $admin = Role::whereName('admin')->first() ?? Role::whereName('Admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse in production usually, but we can detach permissions
    }
};
