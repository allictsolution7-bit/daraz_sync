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
            $table->string('video_url')->nullable()->after('countdown_repeat');
            $table->string('video_title')->nullable()->after('video_url');
            $table->text('video_description')->nullable()->after('video_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_title', 'video_description']);
        });
    }
};
