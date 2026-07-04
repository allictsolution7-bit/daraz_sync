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
        Schema::create('fraud_protection_settings', function (Blueprint $table) {
            $table->id();
            
            // ========================================
            // MODULE 1: DUPLICATE ORDER PROTECTION
            // ========================================
            $table->boolean('duplicate_protection_enabled')->default(false);
            
            // Order Interval Restriction
            $table->boolean('order_interval_enabled')->default(false);
            $table->integer('order_interval_minutes')->default(60)->comment('Minutes to wait before next order');
            
            // Pending Order Restriction
            $table->boolean('pending_order_restriction_enabled')->default(false);
            
            // ========================================
            // MODULE 2: FAKE ORDER PROTECTION
            // ========================================
            $table->boolean('fake_protection_enabled')->default(false);
            
            // Phone Number Validation
            $table->boolean('phone_validation_enabled')->default(false);
            $table->json('allowed_phone_lengths')->nullable()->comment('e.g., [11, 12, 14]');

            // Name Validation
            $table->boolean('name_validation_enabled')->default(false);
            $table->unsignedSmallInteger('name_min_length')->default(2);
            $table->unsignedSmallInteger('name_max_length')->default(100);
            $table->boolean('name_disallow_numeric')->default(true);
            
            // Smart Fake Data Detection
            $table->boolean('block_sequential_numbers')->default(false)->comment('Block 000000, 111111, etc.');
            $table->boolean('block_repeated_names')->default(false)->comment('Block aaaa, bbbb, etc.');
            $table->boolean('block_gibberish_names')->default(false)->comment('Block random text like asdfgh');
            
            // Custom Pattern Blacklist
            $table->boolean('custom_pattern_enabled')->default(false);
            $table->json('blocked_phone_patterns')->nullable()->comment('Custom phone patterns to block');
            $table->json('blocked_name_patterns')->nullable()->comment('Custom name patterns to block');

            // Address Validation
            $table->boolean('address_validation_enabled')->default(false);
            $table->unsignedSmallInteger('address_min_length')->default(10);
            
            // ========================================
            // MODULE 3: FRAUD & SCAM PROTECTION
            // ========================================
            $table->boolean('fraud_protection_enabled')->default(false);
            
            // Phone & IP Blacklist
            $table->boolean('blacklist_enabled')->default(false);
            $table->json('blacklisted_phones')->nullable();
            $table->json('blacklisted_ips')->nullable();
            
            // IP Rate Limiting
            $table->boolean('ip_rate_limiting_enabled')->default(false);
            $table->integer('ip_max_orders_per_hour')->default(5)->comment('Max orders per IP per hour');
            
            // Courier Success Rate Check
            $table->boolean('courier_success_check_enabled')->default(false);
            $table->decimal('min_success_rate', 5, 2)->default(20.00)->comment('Minimum courier success rate %');
            $table->decimal('max_bad_history_rate', 5, 2)->default(80.00)->comment('Maximum bad history rate %');
            
            // ========================================
            // ALERT & NOTIFICATION SETTINGS
            // ========================================
            $table->boolean('send_admin_alerts')->default(false);
            $table->string('admin_alert_email')->nullable();
            $table->boolean('log_blocked_attempts')->default(true);
            
            // Custom Messages
            $table->text('duplicate_order_message')->nullable();
            $table->text('fake_data_message')->nullable();
            $table->text('fraud_detected_message')->nullable();
            $table->text('blacklist_message')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_protection_settings');
    }
};

