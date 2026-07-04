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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_option_id')->nullable();
            $table->integer('quantity');
            $table->enum('type', ['initial', 'adjustment', 'sale', 'return', 'restock', 'damage', 'inventory_count']);
            $table->unsignedBigInteger('reference_id')->nullable(); // Order ID, etc.
            $table->string('reference_type')->nullable(); // Order, etc.
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};