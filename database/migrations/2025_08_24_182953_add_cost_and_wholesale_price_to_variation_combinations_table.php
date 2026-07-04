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
        Schema::table('variation_combinations', function (Blueprint $table) {
            // Add product cost field for variations
            $table->decimal('product_cost', 10, 2)->nullable()->after('offer_price')->comment('Variation cost/purchase price');
            
            // Add wholesale price field for variations
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('product_cost')->comment('Variation wholesale price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variation_combinations', function (Blueprint $table) {
            $table->dropColumn(['product_cost', 'wholesale_price']);
        });
    }
};