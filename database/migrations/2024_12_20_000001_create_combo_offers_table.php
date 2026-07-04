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
        Schema::create('combo_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Base product for the combo
            $table->string('title'); // e.g., "Choose Any 4 Drop Shoulder"
            $table->text('description')->nullable();
            $table->integer('items_count'); // Number of items customer can select (e.g., 3, 4)
            $table->decimal('combo_price', 10, 2); // Total price for the combo
            $table->decimal('original_price', 10, 2); // Original total price if bought separately
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->index(['product_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_offers');
    }
}; 