<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // COGS snapshot at order time
            $table->decimal('unit_cost', 10, 2)->default(0)->after('sub_total');
            $table->decimal('total_cost', 10, 2)->default(0)->after('unit_cost');
        });

        // Backfill existing order items with current product cost
        DB::statement("
            UPDATE order_items
            LEFT JOIN variation_combinations ON order_items.combination_id = variation_combinations.id
            JOIN products ON order_items.product_id = products.id
            SET
                order_items.unit_cost = COALESCE(variation_combinations.product_cost, products.product_cost, 0),
                order_items.total_cost = order_items.quantity * COALESCE(variation_combinations.product_cost, products.product_cost, 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['unit_cost', 'total_cost']);
        });
    }
};
