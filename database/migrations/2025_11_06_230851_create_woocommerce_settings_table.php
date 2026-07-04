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
        Schema::create('woo_commerce_settings', function (Blueprint $table) {
            $table->id();
            $table->string('connection_method')->default('api'); // 'api' or 'database'
            
            // API Settings
            $table->string('api_url')->nullable();
            $table->text('consumer_key')->nullable();
            $table->text('consumer_secret')->nullable();
            $table->string('api_version')->default('wc/v3');
            $table->boolean('verify_ssl')->default(true);
            
            // Database Settings
            $table->string('db_host')->nullable();
            $table->string('db_port')->default('3306');
            $table->string('db_database')->nullable();
            $table->string('db_username')->nullable();
            $table->text('db_password')->nullable();
            $table->string('db_table_prefix')->default('wp_');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('woo_commerce_settings');
    }
};
