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
        Schema::create('basic_shipping_rules', function (Blueprint $table) {
            $table->id();
            $table->string('ruleable_type'); // 'App\Models\Product' or 'App\Models\LandingPage'
            $table->unsignedBigInteger('ruleable_id'); // Product ID or Landing Page ID
            $table->enum('rule_type', ['override', 'free_shipping', 'custom_cost', 'percentage', 'conditional', 'delivery_area']);
            $table->decimal('rule_value', 10, 2)->nullable(); // Cost amount or percentage
            $table->decimal('free_shipping_threshold', 10, 2)->nullable();
            $table->string('delivery_area_name')->nullable(); // Display name like "Inside Dhaka", "Outside Dhaka"
            $table->string('delivery_area_slug')->nullable(); // Slug like "inside_dhaka", "outside_dhaka"
            $table->json('conditions')->nullable(); // Future flexibility for complex rules
            $table->integer('priority')->default(0); // Rule priority when multiple rules exist
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for performance
            $table->index(['ruleable_type', 'ruleable_id'], 'idx_ruleable');
            $table->index(['priority'], 'idx_priority');
            $table->index(['is_active'], 'idx_active');
            
            // Unique constraint to prevent duplicate active rules of same type
            $table->unique(['ruleable_type', 'ruleable_id', 'rule_type', 'is_active'], 'unique_active_rule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_shipping_rules');
    }
};
