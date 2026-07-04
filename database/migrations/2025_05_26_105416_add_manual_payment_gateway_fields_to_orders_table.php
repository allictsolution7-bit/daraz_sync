<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Bkash payment fields
            $table->string('bkash_number')->nullable();
            $table->string('bkash_transaction_id')->nullable();
            $table->decimal('bkash_charge', 14, 2)->default(0);
            
            // Nagad payment fields
            $table->string('nagad_number')->nullable();
            $table->string('nagad_transaction_id')->nullable();
            $table->decimal('nagad_charge', 14, 2)->default(0);
            
            // Rocket payment fields
            $table->string('rocket_number')->nullable();
            $table->string('rocket_transaction_id')->nullable();
            $table->decimal('rocket_charge', 14, 2)->default(0);
            
            // Total amount including payment gateway charges
            $table->decimal('total_with_charge', 14, 2)->default(0);
            // Payment Status
            $table->enum('payment_status', ['pending', 'paid', 'failed','transaction_not_matched'])->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'bkash_number',
                'bkash_transaction_id',
                'bkash_charge',
                'nagad_number',
                'nagad_transaction_id',
                'nagad_charge',
                'rocket_number',
                'rocket_transaction_id',
                'rocket_charge',
                'total_with_charge',
                'payment_status',
            ]);
        });
    }
};