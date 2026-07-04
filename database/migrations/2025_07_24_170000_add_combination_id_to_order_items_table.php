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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'combination_id')) {
                $table->unsignedBigInteger('combination_id')->nullable()->after('option_id');
                $table->foreign('combination_id')
                    ->references('id')
                    ->on('variation_combinations')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'combination_id')) {
                $table->dropForeign(['combination_id']);
                $table->dropColumn('combination_id');
            }
        });
    }
}; 