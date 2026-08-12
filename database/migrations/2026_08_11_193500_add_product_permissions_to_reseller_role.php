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

        $reseller = Role::findByName('reseller');
        if ($reseller) {
            $reseller->givePermissionTo([
                'vendor.products.view',
                'vendor.products.create',
                'vendor.products.edit',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $reseller = Role::findByName('reseller');
        if ($reseller) {
            $reseller->revokePermissionTo([
                'vendor.products.view',
                'vendor.products.create',
                'vendor.products.edit',
            ]);
        }
    }
};
