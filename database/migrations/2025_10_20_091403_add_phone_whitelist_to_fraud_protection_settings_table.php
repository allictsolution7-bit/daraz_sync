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
        Schema::table('fraud_protection_settings', function (Blueprint $table) {
            // Phone Number Whitelist
            $table->boolean('phone_whitelist_enabled')->default(false)->after('phone_validation_enabled')->comment('Enable phone number whitelist validation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fraud_protection_settings', function (Blueprint $table) {
            $table->dropColumn('phone_whitelist_enabled');
        });
    }
};