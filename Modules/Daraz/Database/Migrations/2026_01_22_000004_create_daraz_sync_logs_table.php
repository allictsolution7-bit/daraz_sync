<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates audit trail for sync operations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('daraz_sync_logs')) {
            Schema::create('daraz_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('daraz_store_id')->constrained('daraz_stores')->onDelete('cascade');
                $table->foreignId('daraz_product_mapping_id')->nullable()
                      ->constrained('daraz_product_mappings')->onDelete('set null');

                $table->enum('type', [
                    'stock_push',      // Push stock to Daraz
                    'stock_pull',      // Pull stock from Daraz
                    'bulk_sync',       // Bulk sync operation
                    'token_refresh',   // Token refresh
                    'connection_test', // Connection test
                ]);
                $table->enum('direction', ['to_daraz', 'from_daraz'])->nullable();
                $table->enum('status', ['success', 'failed', 'pending', 'partial']);

                // Details
                $table->integer('quantity_before')->nullable();
                $table->integer('quantity_after')->nullable();
                $table->json('request_data')->nullable();
                $table->json('response_data')->nullable();
                $table->text('error_message')->nullable();

                // Stats for bulk operations
                $table->integer('items_processed')->nullable();
                $table->integer('items_succeeded')->nullable();
                $table->integer('items_failed')->nullable();

                $table->timestamp('created_at');

                $table->index(['daraz_store_id', 'created_at']);
                $table->index('status');
                $table->index('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daraz_sync_logs');
    }
};
