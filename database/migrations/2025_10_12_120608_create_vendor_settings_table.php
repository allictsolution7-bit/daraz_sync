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
        Schema::create('vendor_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete(); // Reference to users table
            $table->string('business_name')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('tax_id')->nullable(); // VAT/Tax registration number
            $table->string('business_license')->nullable(); // License/registration number
            $table->text('business_license_document')->nullable(); // Path to uploaded document
            
            // Commission & Payout
            $table->decimal('commission_percent', 5, 2)->default(15.00); // Platform commission
            $table->decimal('commission_fixed', 10, 2)->default(0);
            $table->decimal('min_payout_amount', 10, 2)->default(500.00);
            $table->enum('payout_schedule', ['weekly', 'biweekly', 'monthly'])->default('monthly');
            $table->text('bank_details')->nullable(); // Encrypted JSON: bank name, account, routing, etc.
            
            // Permissions
            $table->boolean('auto_approve_products')->default(false);
            $table->boolean('can_edit_after_approval')->default(false);
            $table->boolean('can_manage_orders')->default(true);
            $table->boolean('can_create_coupons')->default(false);
            $table->boolean('can_see_customer_info')->default(true);
            
            // Status & Verification
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Preferences
            $table->json('notification_preferences')->nullable(); // Email, SMS preferences
            $table->json('shipping_methods')->nullable(); // Vendor's available shipping methods
            $table->json('return_policy')->nullable(); // Vendor-specific return policy
            $table->json('additional_config')->nullable();
            
            $table->timestamps();

            $table->unique('vendor_id');
            $table->index(['is_active', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_settings');
    }
};

