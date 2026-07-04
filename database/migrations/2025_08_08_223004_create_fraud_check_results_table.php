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
        Schema::create('fraud_check_results', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique(); // Phone number for which fraud check was performed
            $table->json('fraud_check_data'); // Complete fraud check response data
            $table->integer('risk_score')->default(0); // Calculated risk score (0-100)
            $table->string('risk_level')->default('unknown'); // very_low, low, medium, high
            $table->integer('total_parcels')->default(0);
            $table->integer('delivered_parcels')->default(0);
            $table->integer('canceled_parcels')->default(0);
            $table->decimal('delivery_success_rate', 5, 2)->default(0); // Percentage
            $table->json('risk_factors')->nullable(); // Array of risk factors
            $table->json('provider_results')->nullable(); // Results from each provider
            $table->string('recommendation')->nullable(); // System recommendation
            $table->timestamp('last_checked_at'); // When this fraud check was last performed
            $table->timestamps();
            
            // Index for faster phone number lookups
            $table->index('phone');
            $table->index('risk_level');
            $table->index('last_checked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_check_results');
    }
};
