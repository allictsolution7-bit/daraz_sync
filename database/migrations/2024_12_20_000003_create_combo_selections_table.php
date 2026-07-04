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
        Schema::create('combo_selections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combo_offer_id');
            $table->unsignedBigInteger('product_id'); // Selected product
            $table->unsignedBigInteger('variation_combination_id')->nullable(); // Selected variation combination
            $table->integer('quantity')->default(1);
            $table->string('session_id')->nullable(); // For guest users
            $table->unsignedBigInteger('user_id')->nullable(); // For logged in users
            $table->unsignedBigInteger('cart_id')->nullable(); // Link to cart if exists
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

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->index(['session_id']);
            $table->index(['user_id']);
            $table->index(['cart_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_selections');
    }
}; 