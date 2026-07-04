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
        Schema::create('vendor_global_settings', function (Blueprint $table) {
            $table->id();
            
            // Setting identification
            $table->string('key')->unique()->comment('Unique setting key');
            $table->text('value')->nullable()->comment('Setting value');
            
            // Type casting
            $table->enum('type', ['string', 'integer', 'float', 'decimal', 'boolean', 'array', 'json'])->default('string')->comment('Value type for casting');
            
            // Organization
            $table->string('category', 50)->default('general')->comment('Setting category (commission, withdrawal, limits, etc.)');
            $table->string('label', 255)->nullable()->comment('Human-readable label');
            $table->text('description')->nullable()->comment('Setting description');
            
            // Validation/Options
            $table->json('options')->nullable()->comment('Validation rules or select options');
            
            // Visibility
            $table->boolean('is_public')->default(false)->comment('Can be accessed by vendors/frontend?');
            
            $table->timestamps();
            
            // Indexes
            $table->index('category');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_global_settings');
    }
};

