<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('vendor_wallet_transactions')) {
            DB::statement("ALTER TABLE vendor_wallet_transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vendor_wallet_transactions')) {
            DB::statement("ALTER TABLE vendor_wallet_transactions MODIFY COLUMN type ENUM('recharge_request', 'admin_grant', 'transfer_sent', 'transfer_received', 'deduction', 'stock_purchase', 'stock_purchase_refund') NOT NULL");
        }
    }
};
