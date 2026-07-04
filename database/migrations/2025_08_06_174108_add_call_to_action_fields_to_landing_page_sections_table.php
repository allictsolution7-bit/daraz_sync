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
            $table->string('cta_title')->nullable()->after('single_image_alt');
            $table->string('cta_subtitle')->nullable()->after('cta_title');
            $table->string('cta_button_text')->nullable()->after('cta_subtitle');
            $table->string('cta_phone_number')->nullable()->after('cta_button_text');
            $table->string('cta_background_color')->nullable()->after('cta_phone_number');
            $table->string('cta_button_color')->nullable()->after('cta_background_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->dropColumn([
                'cta_title',
                'cta_subtitle', 
                'cta_button_text',
                'cta_phone_number',
                'cta_background_color',
                'cta_button_color'
            ]);
        });
    }
};
