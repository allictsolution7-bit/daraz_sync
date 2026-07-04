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
        Schema::create('product_third_category_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('third_category_id')->constrained('third_categories')->cascadeOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['product_id', 'third_category_id']);
            // Indexes for performance
            $table->index('third_category_id');
            $table->index(['third_category_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_third_category_pivot');
    }
};
