<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipping_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->nullable()->constrained('shipping_zones')->onDelete('cascade');
            $table->string('name');
            $table->string('type');
            $table->decimal('min_amount', 10, 2)->nullable();
            $table->integer('min_items')->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('min_weight', 8, 2)->nullable();
            $table->decimal('max_weight', 8, 2)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        Schema::create('product_shipping_rule', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_rule_id')->constrained()->onDelete('cascade');
            $table->primary(['product_id', 'shipping_rule_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_shipping_rule');
        Schema::dropIfExists('shipping_rules');
    }
};