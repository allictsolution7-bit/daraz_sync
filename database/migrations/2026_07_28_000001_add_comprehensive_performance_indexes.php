<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add indexes on orders table for phone, payment_method, order_source, and composite indexes
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'phone')) {
                $table->index('phone', 'orders_phone_index');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->index('payment_method', 'orders_payment_method_index');
            }
            if (Schema::hasColumn('orders', 'order_source')) {
                $table->index('order_source', 'orders_order_source_index');
            }
            $table->index(['assigned_to', 'status'], 'orders_assigned_to_status_index');
        });

        // 2. Add indexes on order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['order_id', 'product_id'], 'order_items_order_product_index');
        });

        // 3. Add indexes on products table for title search and approval status
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'approval_status')) {
                $table->index('approval_status', 'products_approval_status_index');
            }
            if (Schema::hasColumn('products', 'title')) {
                $table->index('title', 'products_title_index');
            }
        });

        // 4. Add indexes on vendor_wallet_transactions table
        if (Schema::hasTable('vendor_wallet_transactions')) {
            Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
                $table->index(['status', 'type'], 'vwt_status_type_index');
            });
        }

        // 5. Add indexes on fraud_check_results table
        if (Schema::hasTable('fraud_check_results')) {
            Schema::table('fraud_check_results', function (Blueprint $table) {
                if (Schema::hasColumn('fraud_check_results', 'phone')) {
                    $table->index('phone', 'fcr_phone_index');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_phone_index');
            $table->dropIndex('orders_payment_method_index');
            $table->dropIndex('orders_order_source_index');
            $table->dropIndex('orders_assigned_to_status_index');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_product_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_approval_status_index');
            $table->dropIndex('products_title_index');
        });

        if (Schema::hasTable('vendor_wallet_transactions')) {
            Schema::table('vendor_wallet_transactions', function (Blueprint $table) {
                $table->dropIndex('vwt_status_type_index');
            });
        }

        if (Schema::hasTable('fraud_check_results')) {
            Schema::table('fraud_check_results', function (Blueprint $table) {
                $table->dropIndex('fcr_phone_index');
            });
        }
    }
};
