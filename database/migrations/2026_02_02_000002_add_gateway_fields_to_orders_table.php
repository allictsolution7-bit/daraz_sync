<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('gateway_order_id')->nullable()->after('payment_status');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_order_id');

            $table->index('gateway_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['gateway_order_id']);
            $table->dropColumn(['gateway_order_id', 'gateway_transaction_id']);
        });
    }
};
