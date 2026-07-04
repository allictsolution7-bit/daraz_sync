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
        Schema::create('basic_shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('flat_rate', 8, 2)->default(80.00);
            $table->json('shipping_options')->nullable()->comment('Stores options as {"key": {"name": "Name", "cost": 80.00, "active": true}}');
            $table->decimal('free_shipping_threshold', 8, 2)->default(1500.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_shipping_settings');
    }
};
