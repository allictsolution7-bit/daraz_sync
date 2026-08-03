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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('reseller_price', 10, 2)->nullable()->after('wholesale_price')->comment('Price for resellers');
        });

        Schema::table('variation_combinations', function (Blueprint $table) {
            $table->decimal('reseller_price', 10, 2)->nullable()->after('wholesale_price')->comment('Variation price for resellers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('reseller_price');
        });

        Schema::table('variation_combinations', function (Blueprint $table) {
            $table->dropColumn('reseller_price');
        });
    }
};
