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
        Schema::table('landing_page_sections', function (Blueprint $table) {
            // Add badge width fields for hero section
            $table->integer('badge_desktop_width')->nullable()->after('badge_image')->default(100);
            $table->integer('badge_mobile_width')->nullable()->after('badge_desktop_width')->default(80);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            // Drop badge width fields
            $table->dropColumn([
                'badge_desktop_width',
                'badge_mobile_width',
            ]);
        });
    }
};
