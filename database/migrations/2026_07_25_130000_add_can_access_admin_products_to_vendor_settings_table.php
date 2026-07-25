<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('vendor_settings') && !Schema::hasColumn('vendor_settings', 'can_access_admin_products')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->boolean('can_access_admin_products')->default(false)->after('can_see_customer_info');
            });
        }

        // Ensure Spatie Permission exists
        Permission::firstOrCreate([
            'name' => 'vendor.access_admin_products',
            'guard_name' => 'web',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vendor_settings') && Schema::hasColumn('vendor_settings', 'can_access_admin_products')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->dropColumn('can_access_admin_products');
            });
        }
    }
};
