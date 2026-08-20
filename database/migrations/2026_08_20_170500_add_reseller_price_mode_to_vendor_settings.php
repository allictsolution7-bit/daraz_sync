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
        if (Schema::hasTable('vendor_settings') && !Schema::hasColumn('vendor_settings', 'reseller_price_mode')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->string('reseller_price_mode', 50)
                    ->default('markup')
                    ->after('reseller_markup_pct')
                    ->comment('Reseller price calculation mode: markup or admin_selling_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vendor_settings') && Schema::hasColumn('vendor_settings', 'reseller_price_mode')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->dropColumn('reseller_price_mode');
            });
        }
    }
};
