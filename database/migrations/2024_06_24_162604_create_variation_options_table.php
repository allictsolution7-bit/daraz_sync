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
        Schema::create('variation_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('images')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->foreign('variation_id')
                  ->references('id')
                  ->on('variations')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation_options');
    }
};
