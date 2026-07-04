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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'free_shipping', 'buy_x_get_y'])->default('percentage');
            $table->decimal('value', 10, 2)->default(0); // Discount amount or percentage
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount_amount', 10, 2)->nullable(); // Max discount cap for percentage
            $table->integer('usage_limit')->nullable(); // Total usage limit (null = unlimited)
            $table->integer('usage_limit_per_user')->default(1);
            $table->integer('times_used')->default(0);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('applicable_to', ['all', 'categories', 'products', 'brands'])->default('all');
            $table->json('applicable_ids')->nullable(); // Category/Product/Brand IDs
            $table->json('excluded_ids')->nullable(); // Excluded items
            $table->boolean('exclude_sale_items')->default(false);
            $table->boolean('first_order_only')->default(false);
            $table->json('user_groups')->nullable(); // Apply to specific user groups
            $table->json('additional_config')->nullable();
            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index(['valid_from', 'valid_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

