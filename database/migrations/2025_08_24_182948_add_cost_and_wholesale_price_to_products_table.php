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
            // Add product cost field (what we pay for the product)
            $table->decimal('product_cost', 10, 2)->nullable()->after('offer')->comment('Product cost/purchase price');
            
            // Add wholesale price field (price for wholesale customers)
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('product_cost')->comment('Wholesale price for bulk buyers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_cost', 'wholesale_price']);
        });
    }
};