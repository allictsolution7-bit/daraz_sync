<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider');
            $table->string('type');       // 'initiate', 'callback', 'webhook', 'verify', 'refund'
            $table->string('status');     // 'pending', 'completed', 'failed', 'cancelled'
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->decimal('amount', 14, 2)->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'provider']);
            $table->index('gateway_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
