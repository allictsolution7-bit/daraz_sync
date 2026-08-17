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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'global_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('global_price', 10, 2)->nullable()->after('reseller_price')->comment('Global/SaaS B2B wholesale price');
            });
        }

        if (Schema::hasTable('variation_combinations') && !Schema::hasColumn('variation_combinations', 'global_price')) {
            Schema::table('variation_combinations', function (Blueprint $table) {
                $table->decimal('global_price', 10, 2)->nullable()->after('reseller_price')->comment('Variation global/SaaS B2B wholesale price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'global_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('global_price');
            });
        }

        if (Schema::hasTable('variation_combinations') && Schema::hasColumn('variation_combinations', 'global_price')) {
            Schema::table('variation_combinations', function (Blueprint $table) {
                $table->dropColumn('global_price');
            });
        }
    }
};
