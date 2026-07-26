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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'parent_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('parent_product_id')->nullable()->after('vendor_id')->constrained('products')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'parent_product_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropForeign(['parent_product_id']);
                $table->dropColumn('parent_product_id');
            });
        }
    }
};
