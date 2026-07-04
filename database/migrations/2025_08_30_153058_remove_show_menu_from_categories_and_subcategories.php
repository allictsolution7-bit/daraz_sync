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
        // Remove show_menu column from product_categories table
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('show_menu');
        });

        // Remove show_menu column from sub_categories table
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('show_menu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add show_menu column back to product_categories table
        Schema::table('product_categories', function (Blueprint $table) {
            $table->boolean('show_menu')->default(false);
        });

        // Add show_menu column back to sub_categories table
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->boolean('show_menu')->default(false);
        });
    }
};
