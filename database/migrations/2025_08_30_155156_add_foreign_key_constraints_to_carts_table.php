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
        Schema::table('carts', function (Blueprint $table) {
            // First, modify product_id to unsignedBigInteger to match products table
            $table->unsignedBigInteger('product_id')->change();
            
            // Add foreign key constraints to prevent orphaned cart items
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // Note: combo_offer_id and combination_id already have foreign key constraints
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Remove foreign key constraint
            $table->dropForeign(['product_id']);
            
            // Revert product_id back to integer
            $table->integer('product_id')->change();
        });
    }
};
