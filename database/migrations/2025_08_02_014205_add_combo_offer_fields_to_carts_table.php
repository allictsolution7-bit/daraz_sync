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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_offer_id')->nullable()->after('combination_id');
            $table->json('combo_selections')->nullable()->after('combo_offer_id');
            
            // Add foreign key constraint
            $table->foreign('combo_offer_id')->references('id')->on('combo_offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['combo_offer_id']);
            $table->dropColumn(['combo_offer_id', 'combo_selections']);
        });
    }
};
