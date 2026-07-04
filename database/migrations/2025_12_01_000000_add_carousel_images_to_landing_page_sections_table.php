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
            if (!Schema::hasColumn('landing_page_sections', 'carousel_images')) {
                $table->json('carousel_images')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            if (Schema::hasColumn('landing_page_sections', 'carousel_images')) {
                $table->dropColumn('carousel_images');
            }
        });
    }
};
