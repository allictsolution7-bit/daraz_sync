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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->boolean('has_mega_menu')->default(false)->after('status');
            $table->json('mega_menu_config')->nullable()->after('has_mega_menu');
            $table->json('mega_menu_headers')->nullable()->after('mega_menu_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['has_mega_menu', 'mega_menu_config', 'mega_menu_headers']);
        });
    }
};
