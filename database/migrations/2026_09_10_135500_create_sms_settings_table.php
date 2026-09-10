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
        if (!Schema::hasTable('sms_settings')) {
            Schema::create('sms_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->unique();
                $table->string('default_gateway')->default('bulksmsbd'); // 'bulksmsbd', 'awaj'
                $table->boolean('is_enabled')->default(true);

                // BulkSMSBD configuration
                $table->string('bulksmsbd_url')->nullable()->default('https://bulksmsbd.net/api/smsapi');
                $table->text('bulksmsbd_api_key')->nullable();
                $table->string('bulksmsbd_sender_id')->nullable();

                // Awaj SMS configuration
                $table->string('awaj_url')->nullable()->default('https://api.awajdigital.com/api');
                $table->text('awaj_api_key')->nullable();
                $table->string('awaj_sender_id')->nullable();
                $table->string('awaj_client_id')->nullable();
                $table->text('awaj_secret_key')->nullable();

                // Event notification triggers
                $table->boolean('notify_product_sold')->default(false);
                $table->boolean('notify_user_created')->default(false);
                $table->boolean('notify_admin_expiry')->default(true);
                $table->integer('admin_expiry_days_before')->default(3);
                $table->boolean('notify_order_status_change')->default(false);

                // Customizable SMS Message Templates
                $table->text('template_product_sold')->nullable();
                $table->text('template_user_created')->nullable();
                $table->text('template_admin_expiry')->nullable();
                $table->text('template_order_status')->nullable();

                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};
