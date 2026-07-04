<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Delayed Event Settings - Configuration for the delayed purchase event system.
     * This allows COD and manual payment orders to fire purchase events only after confirmation.
     */
    public function up(): void
    {
        Schema::create('delayed_event_settings', function (Blueprint $table) {
            $table->id();

            // Master toggle for the entire feature
            $table->boolean('is_enabled')->default(false);

            // PixelFly API Configuration
            $table->string('pixelfly_api_key', 255)->nullable();
            $table->string('pixelfly_endpoint', 255)->default('https://track.pixelfly.io/e');

            // Payment methods that should use delayed events (JSON array)
            // e.g., ["cod", "bkash", "nagad", "rocket"]
            $table->json('enabled_payment_methods')->nullable();

            $table->timestamps();
        });

        // Insert default settings
        \DB::table('delayed_event_settings')->insert([
            'is_enabled' => false,
            'pixelfly_api_key' => null,
            'pixelfly_endpoint' => 'https://track.pixelfly.io/e',
            'enabled_payment_methods' => json_encode(['cod']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delayed_event_settings');
    }
};
