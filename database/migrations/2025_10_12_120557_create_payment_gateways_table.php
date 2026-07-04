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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // stripe, paypal, sslcommerz, bkash, etc.
            $table->string('name'); // Display name
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_live')->default(false); // sandbox vs live mode
            $table->text('public_key')->nullable(); // Will be encrypted in model
            $table->text('secret_key')->nullable(); // Will be encrypted in model
            $table->text('webhook_secret')->nullable(); // Will be encrypted in model
            $table->string('currency', 3)->default('BDT');
            $table->decimal('transaction_fee_percent', 5, 2)->default(0);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0);
            $table->json('supported_currencies')->nullable();
            $table->json('additional_config')->nullable(); // Provider-specific settings
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('is_enabled');
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
