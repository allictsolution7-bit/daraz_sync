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
        if (!Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('wallet_balance', 12, 2)->default(0.00);
            });
        }

        if (!Schema::hasTable('vendor_wallet_transactions')) {
            Schema::create('vendor_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('type', ['recharge_request', 'admin_grant', 'transfer_sent', 'transfer_received', 'deduction']);
                $table->decimal('amount', 12, 2);
                $table->string('payment_method')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('proof_file')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
                $table->text('admin_note')->nullable();
                $table->boolean('is_seen')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_wallet_transactions');
        if (Schema::hasColumn('users', 'wallet_balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }
    }
};
