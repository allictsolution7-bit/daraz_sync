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
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // twilio, nexmo, bulksms, etc.
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->text('api_key')->nullable(); // Will be encrypted in model
            $table->text('api_secret')->nullable(); // Will be encrypted in model
            $table->string('sender_id')->nullable();
            $table->string('api_url')->nullable();
            $table->decimal('cost_per_sms', 8, 4)->default(0);
            $table->json('supported_countries')->nullable();
            $table->json('additional_config')->nullable();
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_providers');
    }
};
