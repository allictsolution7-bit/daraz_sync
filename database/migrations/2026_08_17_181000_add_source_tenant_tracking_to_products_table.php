<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'source_tenant_subdomain')) {
                    $table->string('source_tenant_subdomain')->nullable()->after('parent_product_id')->comment('Origin SaaS subdomain');
                }
                if (!Schema::hasColumn('products', 'source_product_id')) {
                    $table->unsignedBigInteger('source_product_id')->nullable()->after('source_tenant_subdomain')->comment('Product ID in source tenant database');
                }
                if (!Schema::hasColumn('products', 'source_creator_id')) {
                    $table->unsignedBigInteger('source_creator_id')->nullable()->after('source_product_id')->comment('Original admin/vendor ID who created product in source tenant');
                }
                if (!Schema::hasColumn('products', 'source_creator_name')) {
                    $table->string('source_creator_name')->nullable()->after('source_creator_id')->comment('Original creator display name or email');
                }
                if (!Schema::hasColumn('products', 'copied_by_admin_id')) {
                    $table->unsignedBigInteger('copied_by_admin_id')->nullable()->after('source_creator_name')->comment('Admin ID in current store who copied/purchased product');
                }
                if (!Schema::hasColumn('products', 'source_metadata')) {
                    $table->json('source_metadata')->nullable()->after('copied_by_admin_id')->comment('Extended tracking details, copy mode, origin details');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = ['source_tenant_subdomain', 'source_product_id', 'source_creator_id', 'source_creator_name', 'copied_by_admin_id', 'source_metadata'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('products', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
