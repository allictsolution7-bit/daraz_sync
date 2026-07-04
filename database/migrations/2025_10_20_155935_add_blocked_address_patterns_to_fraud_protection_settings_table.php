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
            // Add blocked address patterns
            $table->json('blocked_address_patterns')->nullable()->after('blocked_name_patterns')->comment('Custom address patterns to block');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fraud_protection_settings', function (Blueprint $table) {
            $table->dropColumn('blocked_address_patterns');
        });
    }
};