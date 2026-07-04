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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_category_id'); // Foreign key to categories
            $table->unsignedBigInteger('post_sub_category_id')->nullable(); // Optional foreign key to subcategories
            $table->string('title'); // Post title
            $table->text('content'); // Main content of the post
            $table->string('slug')->unique(); // SEO-friendly slug
            $table->string('meta_title')->nullable(); // SEO meta title
            $table->text('meta_description')->nullable(); // SEO meta description
            $table->string('canonical_url')->nullable(); // Canonical URL
            $table->string('image')->nullable(); // Post featured image
            $table->string('image_alt')->nullable(); // Alt text for the featured image
            $table->string('tags')->nullable(); // Related tags for the post
            $table->unsignedBigInteger('created_by'); // Foreign key for creator
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('post_category_id')->references('id')->on('post_categories')->onDelete('cascade');
            $table->foreign('post_sub_category_id')->references('id')->on('post_sub_categories')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
