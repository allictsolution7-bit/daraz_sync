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
        Schema::rename('product_shipping_rules', 'basic_shipping_rules');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('basic_shipping_rules', 'product_shipping_rules');
    }
};