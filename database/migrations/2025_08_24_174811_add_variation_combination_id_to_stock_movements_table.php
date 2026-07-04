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
        Schema::table('stock_movements', function (Blueprint $table) {
            // Add variation_combination_id column after variation_option_id
            $table->unsignedBigInteger('variation_combination_id')->nullable()->after('variation_option_id');
            
            // Add foreign key constraint
            $table->foreign('variation_combination_id')
                  ->references('id')
                  ->on('variation_combinations')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['variation_combination_id']);
            
            // Drop the column
            $table->dropColumn('variation_combination_id');
        });
    }
};