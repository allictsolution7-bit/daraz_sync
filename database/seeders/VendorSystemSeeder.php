<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\VendorGlobalSetting;

class VendorSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. CREATE VENDOR PERMISSIONS
        // ==========================================
        $this->createPermissions();

        // ==========================================
        // 2. ASSIGN PERMISSIONS TO ROLES
        // ==========================================
        $this->assignPermissionsToRoles();

        // ==========================================
        // 3. SEED GLOBAL VENDOR SETTINGS
        // ==========================================
        $this->seedGlobalSettings();
    }

    /**
     * Create vendor-related permissions
     */
    protected function createPermissions(): void
    {
        $permissions = [
            // Vendor Product Permissions
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',

            // Vendor Order Permissions (read-only for consignment model)
            'vendor.orders.view',

            // Vendor Withdrawal Permissions
            'vendor.withdrawals.view',
            'vendor.withdrawals.create',
            'vendor.withdrawals.cancel',

            // Vendor Dashboard Permissions
            'vendor.dashboard.view',
            'vendor.earnings.view',
            'vendor.profile.edit',

            // Admin Vendor Management Permissions
            'admin.vendors.view',
            'admin.vendors.create',
            'admin.vendors.edit',
            'admin.vendors.delete',
            'admin.vendors.verify',
            'admin.vendors.toggle-status',

            // Admin Product Approval Permissions
            'admin.vendor-products.view',
            'admin.vendor-products.approve',
            'admin.vendor-products.reject',
            'admin.vendor-products.edit-commission',

            // Admin Withdrawal Management Permissions
            'admin.vendor-withdrawals.view',
            'admin.vendor-withdrawals.approve',
            'admin.vendor-withdrawals.reject',
            'admin.vendor-withdrawals.complete',

            // Admin Vendor Settings Permissions
            'admin.vendor-settings.view',
            'admin.vendor-settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('✅ Vendor permissions created!');
    }

    /**
     * Assign permissions to roles
     */
    protected function assignPermissionsToRoles(): void
    {
        // Get or create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $vendor = Role::firstOrCreate(['name' => 'vendor']);
        $vendorStaff = Role::firstOrCreate(['name' => 'vendor_staff']);

        // Super Admin - All permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - All vendor management permissions
        $admin->givePermissionTo([
            'admin.vendors.view',
            'admin.vendors.create',
            'admin.vendors.edit',
            'admin.vendors.delete',
            'admin.vendors.verify',
            'admin.vendors.toggle-status',
            'admin.vendor-products.view',
            'admin.vendor-products.approve',
            'admin.vendor-products.reject',
            'admin.vendor-products.edit-commission',
            'admin.vendor-withdrawals.view',
            'admin.vendor-withdrawals.approve',
            'admin.vendor-withdrawals.reject',
            'admin.vendor-withdrawals.complete',
            'admin.vendor-settings.view',
            'admin.vendor-settings.edit',
        ]);

        // Vendor - Own products, orders (view), withdrawals
        $vendor->givePermissionTo([
            'vendor.dashboard.view',
            'vendor.products.view',
            'vendor.products.create',
            'vendor.products.edit',
            'vendor.products.delete',
            'vendor.orders.view',
            'vendor.earnings.view',
            'vendor.withdrawals.view',
            'vendor.withdrawals.create',
            'vendor.withdrawals.cancel',
            'vendor.profile.edit',
        ]);

        // Vendor Staff - View only (if you want to support staff)
        $vendorStaff->givePermissionTo([
            'vendor.dashboard.view',
            'vendor.products.view',
            'vendor.orders.view',
            'vendor.earnings.view',
            'vendor.withdrawals.view',
        ]);

        $this->command->info('✅ Permissions assigned to roles!');
    }

    /**
     * Seed global vendor settings with default values
     */
    protected function seedGlobalSettings(): void
    {
        $settings = [
            // System Settings
            [
                'key' => 'vendor_system_enabled',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'system',
                'label' => 'Enable Multi-Seller System',
                'description' => 'Master switch to enable/disable the entire vendor system',
                'is_public' => true,
            ],

            // Commission Settings
            [
                'key' => 'vendor_global_commission_rate',
                'value' => '15.00',
                'type' => 'decimal',
                'category' => 'commission',
                'label' => 'Global Commission Rate (%)',
                'description' => 'Default commission percentage for all vendors',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_min_commission_rate',
                'value' => '10.00',
                'type' => 'decimal',
                'category' => 'commission',
                'label' => 'Minimum Commission Rate (%)',
                'description' => 'Minimum commission vendors can propose',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_max_commission_rate',
                'value' => '30.00',
                'type' => 'decimal',
                'category' => 'commission',
                'label' => 'Maximum Commission Rate (%)',
                'description' => 'Maximum commission vendors can propose',
                'is_public' => false,
            ],

            // Withdrawal Settings
            [
                'key' => 'vendor_min_withdrawal_amount',
                'value' => '500.00',
                'type' => 'decimal',
                'category' => 'withdrawal',
                'label' => 'Minimum Withdrawal Amount',
                'description' => 'Minimum amount vendors can withdraw',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_withdrawal_processing_days',
                'value' => '7',
                'type' => 'integer',
                'category' => 'withdrawal',
                'label' => 'Withdrawal Processing Days',
                'description' => 'Expected days to process withdrawal requests',
                'is_public' => true,
            ],

            // Product Settings
            [
                'key' => 'vendor_auto_approve_products',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'product',
                'label' => 'Auto-Approve All Products',
                'description' => 'Automatically approve all vendor products (not recommended)',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_auto_approve_verified_vendors',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'product',
                'label' => 'Auto-Approve for Verified Vendors',
                'description' => 'Automatically approve products from verified vendors',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_max_products_per_vendor',
                'value' => '0',
                'type' => 'integer',
                'category' => 'product',
                'label' => 'Max Products Per Vendor',
                'description' => 'Maximum number of products a vendor can list (0 = unlimited)',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_require_product_approval',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'product',
                'label' => 'Require Product Approval',
                'description' => 'Products must be approved before going live',
                'is_public' => false,
            ],

            // Registration Settings
            [
                'key' => 'vendor_registration_enabled',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'registration',
                'label' => 'Enable Vendor Registration',
                'description' => 'Allow new vendors to register',
                'is_public' => true,
            ],
            [
                'key' => 'vendor_registration_requires_approval',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'registration',
                'label' => 'Require Registration Approval',
                'description' => 'New vendor registrations require admin approval',
                'is_public' => false,
            ],

            // Notification Settings
            [
                'key' => 'vendor_notify_on_product_approval',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'notification',
                'label' => 'Notify on Product Approval',
                'description' => 'Send notification when products are approved/rejected',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_notify_on_withdrawal_status',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'notification',
                'label' => 'Notify on Withdrawal Status',
                'description' => 'Send notification on withdrawal status changes',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_notify_on_order',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'notification',
                'label' => 'Notify on New Order',
                'description' => 'Notify vendors when their products are ordered',
                'is_public' => false,
            ],

            // Display Settings
            [
                'key' => 'vendor_show_vendor_name_on_product',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'display',
                'label' => 'Show Vendor Name on Products',
                'description' => 'Display vendor business name on product pages',
                'is_public' => true,
            ],
            [
                'key' => 'vendor_enable_vendor_stores',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'display',
                'label' => 'Enable Vendor Store Pages',
                'description' => 'Allow vendors to have their own store pages',
                'is_public' => true,
            ],

            // Dashboard Settings
            [
                'key' => 'vendor_dashboard_recent_products',
                'value' => '5',
                'type' => 'integer',
                'category' => 'dashboard',
                'label' => 'Recent Products Count',
                'description' => 'Number of recent products to show on dashboard',
                'is_public' => false,
            ],
            [
                'key' => 'vendor_dashboard_recent_orders',
                'value' => '5',
                'type' => 'integer',
                'category' => 'dashboard',
                'label' => 'Recent Orders Count',
                'description' => 'Number of recent orders to show on dashboard',
                'is_public' => false,
            ],

            // Payout Methods
            [
                'key' => 'vendor_payout_methods',
                'value' => json_encode(['bank', 'bkash', 'nagad', 'rocket']),
                'type' => 'json',
                'category' => 'payout',
                'label' => 'Available Payout Methods',
                'description' => 'Comma-separated list of available payout methods (bank, bkash, nagad, rocket)',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            VendorGlobalSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Global vendor settings seeded!');
    }
}

