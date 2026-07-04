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
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Page title
            $table->string('slug')->unique(); // URL slug
            $table->string('heading'); // Main heading
            $table->text('sub_heading')->nullable(); // Sub heading
            $table->text('primary_text')->nullable(); // Primary text content
            $table->string('order_button_text')->default('এখনই কিনুন'); // Order button text
            $table->string('order_form_title')->default('অর্ডার করুন'); // Order form title
            $table->string('order_place_button_text')->default('অর্ডার Confirm করুন'); // Order place button text
            $table->string('order_button_url')->default('#order-section'); // Scroll target for order section
            $table->unsignedBigInteger('product_id'); // Associated product
            $table->json('product_details')->nullable(); // Product details as JSON
            $table->json('customer_reviews')->nullable(); // Customer reviews as JSON
            $table->string('hero_image')->nullable(); // Hero section image
            $table->string('hero_image_alt')->nullable(); // Alt text for hero image
            $table->string('badge_text')->nullable(); // Badge text (e.g., "১০০% প্রাকৃতিক")
            $table->string('badge_color')->default('#ffd54f'); // Badge color
            $table->boolean('status')->default(true); // Active/Inactive status
            $table->integer('position')->default(0); // Display order
            $table->unsignedBigInteger('created_by')->nullable(); // Created by user
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
}; 