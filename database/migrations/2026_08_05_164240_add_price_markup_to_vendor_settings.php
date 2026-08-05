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
        Schema::table('vendor_settings', function (Blueprint $table) {
            $table->decimal('sale_price_markup_pct', 5, 2)->default(10.00)->after('payout_routing_number')->comment('Markup added to cost for Sale Price');
            $table->decimal('old_price_markup_pct', 5, 2)->default(25.00)->after('sale_price_markup_pct')->comment('Markup added to cost for Old Price');
            $table->decimal('wholesale_price_markup_pct', 5, 2)->default(5.00)->after('old_price_markup_pct')->comment('Markup added to cost for Wholesale Price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_settings', function (Blueprint $table) {
            $table->dropColumn([
                'sale_price_markup_pct',
                'old_price_markup_pct',
                'wholesale_price_markup_pct'
            ]);
        });
    }
};
