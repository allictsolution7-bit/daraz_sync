<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->string('sub_id')->unique();
            $table->string('plan');
            $table->string('cycle');
            $table->string('price');
            $table->string('gateway');
            $table->string('phone')->nullable();
            $table->string('trx_id')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_subscription_payments');
    }
};
