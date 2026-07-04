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
        Schema::create('telegram_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);
            $table->string('bot_token')->nullable();
            $table->string('chat_id')->nullable();
            $table->boolean('notify_new_order')->default(true);
            $table->boolean('notify_landing_page_order')->default(true);
            $table->boolean('notify_cart_order')->default(true);
            $table->boolean('notify_order_status_change')->default(false);
            $table->text('order_message_template')->nullable();
            $table->integer('timeout')->default(3)->comment('HTTP timeout in seconds');
            $table->timestamps();
        });

        // Insert default record
        DB::table('telegram_settings')->insert([
            'enabled' => false,
            'bot_token' => env('TELEGRAM_BOT_TOKEN'),
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'notify_new_order' => true,
            'notify_landing_page_order' => true,
            'notify_cart_order' => true,
            'notify_order_status_change' => false,
            'timeout' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_settings');
    }
};
