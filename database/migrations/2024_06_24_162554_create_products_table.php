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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string ("slug");
            $table->boolean  ("is_featured")->default (false);
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('thumb_image');
            $table->json('images')->nullable();
            $table->string('tags');
            $table->decimal('old_price', 8, 2)->nullable();
            $table->decimal('offer', 8, 2)->nullable();
            $table->boolean('status')->default (true);
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
