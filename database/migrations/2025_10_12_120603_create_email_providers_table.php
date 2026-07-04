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
        Schema::create('email_providers', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // smtp, sendgrid, mailgun, ses, etc.
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Will be encrypted in model
            $table->string('encryption')->nullable(); // tls, ssl, none
            $table->string('from_email');
            $table->string('from_name');
            $table->text('api_key')->nullable(); // For SendGrid, Mailgun, etc.
            $table->json('additional_config')->nullable();
            $table->timestamps();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_providers');
    }
};
