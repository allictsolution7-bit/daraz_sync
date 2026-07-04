<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates table for mapping Thikana products to Daraz products.
     */
    public function up(): void
    {
        if (!Schema::hasTable('daraz_product_mappings')) {
            Schema::create('daraz_product_mappings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('daraz_store_id')->constrained('daraz_stores')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('variation_combination_id')->nullable()
                      ->constrained('variation_combinations')->onDelete('cascade');

                // Daraz identifiers
                $table->string('daraz_item_id'); // Daraz Product/Item ID
                $table->string('daraz_sku'); // Daraz SKU (for variation)
                $table->string('seller_sku')->nullable(); // Seller's SKU on Daraz

                // Sync settings
                $table->boolean('sync_enabled')->default(true);
                $table->integer('stock_buffer')->default(0); // Reserve stock buffer

                // Tracking
                $table->integer('last_synced_quantity')->nullable();
                $table->integer('daraz_quantity')->nullable(); // Last known Daraz stock
                $table->timestamp('last_synced_at')->nullable();
                $table->string('last_sync_status')->nullable(); // success, failed, pending
                $table->text('last_sync_error')->nullable();

                $table->timestamps();

                // Indexes
                $table->unique(['daraz_store_id', 'product_id', 'variation_combination_id'], 'unique_mapping');
                $table->index(['daraz_store_id', 'daraz_item_id']);
                $table->index('daraz_sku');
                $table->index('seller_sku');
                $table->index('sync_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daraz_product_mappings');
    }
};
