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
        // ==========================================
        // 1. MODIFY PRODUCTS TABLE
        // ==========================================
        if (!Schema::hasColumn('products', 'vendor_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Vendor ownership
                $table->foreignId('vendor_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                
                // Approval workflow
                $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
                $table->timestamp('approved_at')->nullable()->after('approval_status');
                $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
                $table->text('rejection_reason')->nullable()->after('approved_by');
                
                // Commission fields
                $table->decimal('vendor_proposed_commission', 5, 2)->nullable()->after('rejection_reason')->comment('Commission % vendor proposes');
                $table->decimal('vendor_commission_rate', 5, 2)->nullable()->after('vendor_proposed_commission')->comment('Final approved commission %');
                $table->text('commission_note')->nullable()->after('vendor_commission_rate')->comment('Admin note about commission');
                
                // Indexes
                $table->index('vendor_id');
                $table->index('approval_status');
            });
        }

        // ==========================================
        // 2. CREATE VENDOR_WITHDRAWALS TABLE (must be before order_items modification)
        // ==========================================
        if (!Schema::hasTable('vendor_withdrawals')) {
            Schema::create('vendor_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            
            // Withdrawal details
            $table->decimal('amount', 10, 2)->comment('Withdrawal amount');
            $table->enum('method', ['bank', 'bkash', 'nagad', 'rocket', 'other'])->default('bank')->comment('Payout method');
            $table->json('account_details')->nullable()->comment('Account number, bank name, etc.');
            $table->text('note')->nullable()->comment('Vendor note');
            
            // Status tracking
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_note')->nullable()->comment('Admin note/rejection reason');
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // Processing
            $table->string('transaction_id')->nullable()->comment('Payment transaction ID');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete()->comment('Admin who processed');
            
            $table->timestamps();
            
            // Indexes
            $table->index('vendor_id');
            $table->index('status');
            $table->index('created_at');
            });
        }

        // ==========================================
        // 3. MODIFY ORDER_ITEMS TABLE
        // ==========================================
        if (!Schema::hasColumn('order_items', 'vendor_id')) {
            Schema::table('order_items', function (Blueprint $table) {
            // Vendor tracking
            $table->foreignId('vendor_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            
            // Commission & earnings
            $table->decimal('vendor_commission_rate', 5, 2)->nullable()->after('sub_total')->comment('Commission % at time of order');
            $table->decimal('vendor_commission_amount', 10, 2)->nullable()->after('vendor_commission_rate')->comment('Platform commission amount');
            $table->decimal('vendor_earning', 10, 2)->nullable()->after('vendor_commission_amount')->comment('Vendor earning from this item');
            
            // Payment tracking
            $table->boolean('vendor_paid')->default(false)->after('vendor_earning')->comment('Has vendor been paid for this item?');
            $table->timestamp('vendor_paid_at')->nullable()->after('vendor_paid');
            $table->foreignId('paid_in_withdrawal_id')->nullable()->after('vendor_paid_at')->constrained('vendor_withdrawals')->nullOnDelete();
            
            // Indexes
            $table->index('vendor_id');
            $table->index(['vendor_id', 'vendor_paid']);
            });
        }

        // ==========================================
        // 4. CREATE VENDOR_BALANCE_LEDGERS TABLE
        // ==========================================
        if (!Schema::hasTable('vendor_balance_ledgers')) {
            Schema::create('vendor_balance_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            
            // Transaction details
            $table->enum('transaction_type', ['sale', 'withdrawal', 'refund', 'adjustment', 'bonus', 'penalty'])->comment('Type of transaction');
            $table->decimal('amount', 10, 2)->comment('Transaction amount (negative for debits)');
            $table->decimal('balance_after', 10, 2)->comment('Balance after this transaction');
            
            // Reference (polymorphic to order_item, withdrawal, etc.)
            $table->string('reference_type')->nullable()->comment('Model class name');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('Referenced model ID');
            
            // Notes
            $table->text('description')->nullable()->comment('Transaction description');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Admin who created manual entry');
            
            $table->timestamps();
            
            // Indexes
            $table->index('vendor_id');
            $table->index('transaction_type');
            $table->index(['reference_type', 'reference_id']);
            $table->index('created_at');
            });
        }

        // ==========================================
        // 5. UPDATE VENDOR_SETTINGS TABLE
        // ==========================================
        if (!Schema::hasColumn('vendor_settings', 'default_commission_rate')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
            // Commission overrides (vendor-specific)
            $table->decimal('default_commission_rate', 5, 2)->nullable()->after('commission_fixed')->comment('Vendor default commission (overrides global)');
            $table->decimal('custom_min_commission_rate', 5, 2)->nullable()->after('default_commission_rate')->comment('Min commission for this vendor');
            $table->decimal('custom_max_commission_rate', 5, 2)->nullable()->after('custom_min_commission_rate')->comment('Max commission for this vendor');
            
            // Withdrawal overrides
            $table->decimal('custom_min_withdrawal_amount', 10, 2)->nullable()->after('custom_max_commission_rate')->comment('Min withdrawal for this vendor');
            
            // Payout details (updated structure)
            $table->enum('payout_method', ['bank', 'bkash', 'nagad', 'rocket'])->nullable()->after('custom_min_withdrawal_amount');
            $table->string('payout_account_number', 50)->nullable()->after('payout_method');
            $table->string('payout_account_name', 255)->nullable()->after('payout_account_number');
            $table->string('payout_bank_name', 255)->nullable()->after('payout_account_name');
            $table->string('payout_branch_name', 255)->nullable()->after('payout_bank_name');
            $table->string('payout_routing_number', 50)->nullable()->after('payout_branch_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order
        Schema::table('vendor_settings', function (Blueprint $table) {
            $table->dropColumn([
                'default_commission_rate',
                'custom_min_commission_rate',
                'custom_max_commission_rate',
                'custom_min_withdrawal_amount',
                'payout_method',
                'payout_account_number',
                'payout_account_name',
                'payout_bank_name',
                'payout_branch_name',
                'payout_routing_number',
            ]);
        });

        Schema::dropIfExists('vendor_withdrawals');
        Schema::dropIfExists('vendor_balance_ledgers');

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['paid_in_withdrawal_id']);
            $table->dropForeign(['vendor_id']);
            $table->dropColumn([
                'vendor_id',
                'vendor_commission_rate',
                'vendor_commission_amount',
                'vendor_earning',
                'vendor_paid',
                'vendor_paid_at',
                'paid_in_withdrawal_id',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'vendor_id',
                'approval_status',
                'approved_at',
                'approved_by',
                'rejection_reason',
                'vendor_proposed_commission',
                'vendor_commission_rate',
                'commission_note',
            ]);
        });
    }
};

