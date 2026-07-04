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
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('primary_color')->nullable()->default('#007bff')->after('status');
            $table->string('secondary_color')->nullable()->default('#6c757d')->after('primary_color');
            $table->string('accent_color')->nullable()->default('#28a745')->after('secondary_color');
            $table->string('order_button_color')->nullable()->default('#dc3545')->after('accent_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'accent_color', 'order_button_color']);
        });
    }
};
