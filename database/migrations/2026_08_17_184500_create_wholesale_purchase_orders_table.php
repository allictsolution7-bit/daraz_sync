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
        if (!Schema::hasTable('wholesale_purchase_orders')) {
            Schema::create('wholesale_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                
                // Buyer Details
                $table->unsignedBigInteger('buyer_tenant_id')->nullable();
                $table->string('buyer_subdomain')->nullable()->comment('Subdomain of buying store');
                $table->unsignedBigInteger('buyer_admin_id')->nullable()->comment('User ID of buying admin');
                $table->string('buyer_admin_name')->nullable();
                $table->string('buyer_admin_phone')->nullable();
                $table->string('buyer_admin_email')->nullable();
                $table->text('buyer_shipping_address')->nullable()->comment('Destination delivery address for physical goods');
                
                // Seller (Product Owner) Details
                $table->unsignedBigInteger('seller_tenant_id')->nullable();
                $table->string('seller_subdomain')->comment('Subdomain of selling store/tenant');
                $table->unsignedBigInteger('seller_admin_id')->nullable()->comment('User ID of product creator in seller DB');
                $table->string('seller_admin_name')->nullable();
                
                // Product Details
                $table->unsignedBigInteger('product_id')->comment('Product ID in seller tenant DB');
                $table->string('product_title');
                $table->string('product_thumb_image')->nullable();
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->integer('quantity')->default(1);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('platform_commission', 10, 2)->default(0);
                $table->decimal('seller_earnings', 10, 2)->default(0);
                
                // Payment Details (Super Admin Gateway)
                $table->string('payment_gateway')->default('BKASH');
                $table->string('sender_phone')->nullable();
                $table->string('trx_id')->nullable();
                $table->string('payment_status')->default('pending')->comment('pending, approved, rejected');
                $table->unsignedBigInteger('approved_by_superadmin_id')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('rejection_reason')->nullable();
                
                // Fulfillment Details (Seller Shipping to Buyer)
                $table->string('fulfillment_status')->default('pending')->comment('pending, processing, shipped, delivered, cancelled');
                $table->string('courier_name')->nullable();
                $table->string('tracking_number')->nullable();
                $table->text('seller_notes')->nullable();
                
                // Local Created Product & Seller Order Link
                $table->unsignedBigInteger('buyer_local_product_id')->nullable()->comment('Product ID created in buyer store');
                $table->unsignedBigInteger('seller_order_id')->nullable()->comment('Order ID inserted into seller tenant DB');
                $table->json('metadata')->nullable();
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesale_purchase_orders');
    }
};
