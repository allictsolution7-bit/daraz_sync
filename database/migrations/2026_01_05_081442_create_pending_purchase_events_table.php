<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores purchase events for COD orders that should only be
     * fired to analytics platforms (Meta, GA4 via PixelFly) after order confirmation.
     */
    public function up(): void
    {
        Schema::create('pending_purchase_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->json('event_data');           // Full purchase event payload
            $table->timestamp('fired_at')->nullable();  // When event was sent to PixelFly
            $table->boolean('fire_failed')->default(false);  // If firing failed
            $table->text('fire_error')->nullable();  // Error message if failed
            $table->timestamps();

            // Index for finding unfired events
            $table->index(['fired_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_purchase_events');
    }
};
