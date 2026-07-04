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
            $table->string('benefit_title')->nullable()->after('title');
            $table->string('testimonials_title')->nullable()->after('benefit_title');
            $table->string('feature_list_title')->nullable()->after('testimonials_title');
            $table->string('pricing_title')->nullable()->after('feature_list_title');
            $table->string('countdown_title')->nullable()->after('pricing_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            $table->dropColumn(['benefit_title', 'testimonials_title', 'feature_list_title', 'pricing_title', 'countdown_title']);
        });
    }
};
