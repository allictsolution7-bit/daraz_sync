<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delayed_event_settings', function (Blueprint $table) {
            $table->string('firing_method', 20)->default('pixelfly')->after('is_enabled');
            $table->string('sgtm_endpoint')->nullable()->after('pixelfly_endpoint');
            $table->string('sgtm_measurement_id')->nullable()->after('sgtm_endpoint');
            $table->string('sgtm_api_secret')->nullable()->after('sgtm_measurement_id');
        });
    }

    public function down(): void
    {
        Schema::table('delayed_event_settings', function (Blueprint $table) {
            $table->dropColumn(['firing_method', 'sgtm_endpoint', 'sgtm_measurement_id', 'sgtm_api_secret']);
        });
    }
};
