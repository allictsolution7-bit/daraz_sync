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
            // Drop and recreate the columns to ensure they are nullable
            $table->dropColumn([
                'heading',
                'sub_heading', 
                'primary_text',
                'hero_image',
                'hero_image_alt',
                'badge_text',
                'badge_color'
            ]);
        });

        Schema::table('landing_page_sections', function (Blueprint $table) {
            // Recreate the columns as nullable
            $table->string('heading')->nullable()->after('description');
            $table->text('sub_heading')->nullable()->after('heading');
            $table->text('primary_text')->nullable()->after('sub_heading');
            $table->string('hero_image')->nullable()->after('primary_text');
            $table->string('hero_image_alt')->nullable()->after('hero_image');
            $table->string('badge_text')->nullable()->after('hero_image_alt');
            $table->string('badge_color')->nullable()->after('badge_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->dropColumn([
                'heading',
                'sub_heading', 
                'primary_text',
                'hero_image',
                'hero_image_alt',
                'badge_text',
                'badge_color'
            ]);
        });
    }
};
