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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->index(); // Store code (e.g., 'HQ', 'BR1', 'BR2')
            $table->string('slug')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('BD');
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false); // Main/Headquarters store
            $table->json('opening_hours')->nullable(); // {"monday": "9:00-18:00", "tuesday": "9:00-18:00", ...}
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete(); // Store manager
            $table->string('image')->nullable(); // Store photo
            $table->json('features')->nullable(); // ['parking', 'wifi', 'delivery', 'pickup']
            $table->json('settings')->nullable(); // Store-specific settings
            $table->integer('sort_order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'is_primary']);
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};

