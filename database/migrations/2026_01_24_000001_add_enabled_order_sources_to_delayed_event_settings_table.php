<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add enabled_order_sources column for offline/POS order purchase events.
     * This allows offline orders (Physical Store, Phone Call, WhatsApp, etc.)
     * to fire purchase events to Facebook as offline conversions.
     */
    public function up(): void
    {
        Schema::table('delayed_event_settings', function (Blueprint $table) {
            // Order sources that should use delayed events (JSON array)
            // e.g., ["Physical Store", "Phone Call", "WhatsApp", "Messenger"]
            $table->json('enabled_order_sources')->nullable()->after('enabled_payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delayed_event_settings', function (Blueprint $table) {
            $table->dropColumn('enabled_order_sources');
        });
    }
};
