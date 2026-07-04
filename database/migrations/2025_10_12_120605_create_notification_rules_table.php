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
        Schema::create('notification_rules', function (Blueprint $table) {
            $table->id();
            $table->string('event')->index(); // order_placed, order_shipped, low_stock, etc.
            $table->string('name');
            $table->boolean('is_enabled')->default(true);
            $table->json('channels')->nullable(); // ['email', 'sms', 'push', 'database']
            $table->string('recipient_type')->default('customer'); // customer, admin, vendor
            $table->json('conditions')->nullable(); // e.g., {"order_amount": ">1000", "product_category": "electronics"}
            $table->string('email_template_key')->nullable(); // Reference to email_templates
            $table->string('sms_template_key')->nullable(); // Reference to sms_templates
            $table->integer('delay_minutes')->default(0); // Send notification after X minutes
            $table->integer('priority')->default(5); // 1-10 (10 = highest)
            $table->json('additional_config')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['event', 'is_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_rules');
    }
};

