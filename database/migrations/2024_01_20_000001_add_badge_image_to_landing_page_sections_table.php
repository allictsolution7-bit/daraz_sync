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
            // Add badge_image field for hero section
            $table->string('badge_image')->nullable()->after('hero_video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            // Drop badge_image field
            $table->dropColumn('badge_image');
        });
    }
};
