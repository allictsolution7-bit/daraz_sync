<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status', 'orders_status_index');
            $table->index('created_at', 'orders_created_at_index');
            $table->index(['status', 'created_at'], 'orders_status_created_at_index');
            $table->index('is_combo_order', 'orders_is_combo_order_index');
            $table->index('total', 'orders_total_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('vendor_id', 'products_vendor_id_index');
            $table->index('parent_product_id', 'products_parent_product_id_index');
            $table->index('created_by', 'products_created_by_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_status_index');
            $table->dropIndex('orders_created_at_index');
            $table->dropIndex('orders_status_created_at_index');
            $table->dropIndex('orders_is_combo_order_index');
            $table->dropIndex('orders_total_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_vendor_id_index');
            $table->dropIndex('products_parent_product_id_index');
            $table->dropIndex('products_created_by_index');
        });
    }
};
