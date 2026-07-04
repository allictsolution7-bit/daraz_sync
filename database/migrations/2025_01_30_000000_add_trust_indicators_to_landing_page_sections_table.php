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
            // Trust indicators fields for hero section
            $table->json('trust_indicators')->nullable()->after('badge_color');
            $table->boolean('show_trust_indicators')->default(true)->after('trust_indicators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->dropColumn(['trust_indicators', 'show_trust_indicators']);
        });
    }
}; 