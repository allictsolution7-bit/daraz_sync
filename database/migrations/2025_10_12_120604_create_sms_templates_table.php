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
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // otp, order_confirmation, shipping, etc.
            $table->string('name');
            $table->text('message');
            $table->boolean('is_enabled')->default(true);
            $table->json('available_variables')->nullable(); // {{code}}, {{order_id}}, etc.
            $table->string('category')->nullable(); // authentication, orders, notifications
            $table->timestamps();
            
            $table->index('key');
            $table->index(['category', 'is_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
