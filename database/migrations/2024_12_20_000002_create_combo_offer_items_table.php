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
        Schema::create('combo_offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combo_offer_id');
            $table->unsignedBigInteger('product_id'); // Product that can be selected in this combo
            $table->unsignedBigInteger('variation_combination_id')->nullable(); // Specific variation combination (optional)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('combo_offer_id')
                  ->references('id')
                  ->on('combo_offers')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('variation_combination_id')
                  ->references('id')
                  ->on('variation_combinations')
                  ->onDelete('cascade');

            $table->index(['combo_offer_id', 'is_active']);
            $table->index(['product_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offer_items');
    }
}; 