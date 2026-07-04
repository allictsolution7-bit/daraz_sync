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
            // Payment type: full_paid, partial, due
            $table->string('payment_type')->default('full_paid')->after('payment_status');
            // Amount paid by customer
            $table->decimal('paid_amount', 14, 2)->default(0)->after('payment_type');
            // Amount still due from customer
            $table->decimal('due_amount', 14, 2)->default(0)->after('paid_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'paid_amount', 'due_amount']);
        });
    }
};
