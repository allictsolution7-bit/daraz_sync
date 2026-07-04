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
            $table->unsignedBigInteger('fraud_check_result_id')->nullable()->after('delivery_data');
            $table->boolean('fraud_check_completed')->default(false)->after('fraud_check_result_id');
            $table->timestamp('fraud_check_at')->nullable()->after('fraud_check_completed');
            
            // Foreign key constraint will be added in a separate migration after fraud_check_results table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'fraud_check_result_id',
                'fraud_check_completed',
                'fraud_check_at'
            ]);
        });
    }
};
