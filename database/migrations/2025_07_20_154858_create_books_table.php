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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('subject')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->string('isbn')->unique();
            $table->string('edition')->unique();
            $table->integer('pages')->nullable();
            $table->string('cover')->nullable();
            $table->string('country')->nullable();
            $table->string('language')->nullable();
            $table->string('sample_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
