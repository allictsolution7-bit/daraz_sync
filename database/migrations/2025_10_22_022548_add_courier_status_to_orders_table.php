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
        Schema::table('orders', function (Blueprint $table) {
            // Add courier status tracking fields
            $table->string('courier_status')->nullable()->after('status')->comment('Current courier delivery status');
            $table->string('courier_status_slug')->nullable()->after('courier_status')->comment('Courier status slug for API calls');
            $table->timestamp('courier_status_updated_at')->nullable()->after('courier_status_slug')->comment('When courier status was last updated');
            $table->json('courier_status_details')->nullable()->after('courier_status_updated_at')->comment('Full courier status response for reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier_status',
                'courier_status_slug', 
                'courier_status_updated_at',
                'courier_status_details'
            ]);
        });
    }
};