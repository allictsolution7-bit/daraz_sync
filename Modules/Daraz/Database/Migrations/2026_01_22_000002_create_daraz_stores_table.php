<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates table for storing Daraz seller account credentials.
     */
    public function up(): void
    {
        if (!Schema::hasTable('daraz_stores')) {
            Schema::create('daraz_stores', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Store display name
                $table->string('country_code', 2)->default('BD'); // BD, PK, LK, NP, MM
                $table->string('app_key'); // Daraz App Key
                $table->text('app_secret'); // Encrypted App Secret
                $table->text('access_token')->nullable(); // Encrypted Access Token
                $table->text('refresh_token')->nullable(); // Encrypted Refresh Token
                $table->timestamp('token_expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('auto_sync')->default(true); // Auto sync enabled
                $table->integer('sync_interval')->default(30); // Minutes between syncs
                $table->timestamp('last_synced_at')->nullable();
                $table->json('settings')->nullable(); // Store-specific settings
                $table->timestamps();

                $table->index('country_code');
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daraz_stores');
    }
};
