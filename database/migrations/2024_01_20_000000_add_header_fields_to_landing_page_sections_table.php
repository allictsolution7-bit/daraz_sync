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
        if (!Schema::hasTable('landing_page_sections')) {
            return;
        }

        Schema::table('landing_page_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('landing_page_sections', 'header_logo')) {
                // Header Section Fields
                $table->string('header_logo')->nullable()->after('section_type');
                $table->string('header_logo_alt')->nullable()->after('header_logo');
                $table->enum('header_alignment', ['flex-start', 'center', 'flex-end'])->default('center')->after('header_logo_alt');
                
                // Button 1 Fields
                $table->string('header_button1_text')->nullable()->after('header_alignment');
                $table->string('header_button1_url')->nullable()->after('header_button1_text');
                $table->string('header_button1_color')->nullable()->after('header_button1_url');
                $table->string('header_button1_text_color')->nullable()->after('header_button1_color');
                $table->boolean('header_button1_active')->default(true)->after('header_button1_text_color');
                
                // Button 2 Fields
                $table->string('header_button2_text')->nullable()->after('header_button1_active');
                $table->string('header_button2_url')->nullable()->after('header_button2_text');
                $table->string('header_button2_color')->nullable()->after('header_button2_url');
                $table->string('header_button2_text_color')->nullable()->after('header_button2_color');
                $table->boolean('header_button2_active')->default(true)->after('header_button2_text_color');
                
                // Logo Width Fields
                $table->integer('header_desktop_logo_width')->default(200)->after('header_button2_active');
                $table->integer('header_mobile_logo_width')->default(150)->after('header_desktop_logo_width');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_page_sections', function (Blueprint $table) {
            // Drop Header Section Fields
            $table->dropColumn([
                'header_logo',
                'header_logo_alt',
                'header_alignment',
                'header_button1_text',
                'header_button1_url',
                'header_button1_color',
                'header_button1_text_color',
                'header_button1_active',
                'header_button2_text',
                'header_button2_url',
                'header_button2_color',
                'header_button2_text_color',
                'header_button2_active',
                'header_desktop_logo_width',
                'header_mobile_logo_width',
            ]);
        });
    }
};
