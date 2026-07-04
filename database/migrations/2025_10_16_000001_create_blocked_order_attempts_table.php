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
        Schema::create('blocked_order_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            // Blocking reason
            $table->string('block_reason')->nullable()->comment('duplicate|fake|fraud');
            $table->json('validation_errors')->nullable();
            
            // Module that blocked
            $table->string('blocked_by_module')->nullable()->comment('1|2|3');
            
            // Request data
            $table->json('request_data')->nullable();
            
            // Timestamps
            $table->timestamp('blocked_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('phone');
            $table->index('ip_address');
            $table->index('blocked_at');
            $table->index('block_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_order_attempts');
    }
};

