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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Product::class)->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 14, 2);
            $table->decimal('sub_total', 14, 2);
            $table->string ("others")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
