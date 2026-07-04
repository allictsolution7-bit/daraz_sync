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
        Schema::create('landing_page_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('landing_page_id'); // Foreign key to landing_pages
            $table->string('section_type'); // 'feature', 'review', 'benefit', 'testimonials', etc.
            $table->string('title')->nullable(); // Section title
            $table->text('description')->nullable(); // Section description
            $table->string('icon')->nullable(); // Icon (emoji or class name)
            $table->string('reviewer_name')->nullable(); // For review sections
            $table->json('testimonials')->nullable(); // For testimonials section (array of testimonials)
            $table->json('benefits')->nullable(); // For benefit items array
            $table->string('image')->nullable(); // Section image
            $table->string('image_alt')->nullable(); // Alt text for image
            $table->string('background_color')->nullable(); // Background color
            $table->string('text_color')->nullable(); // Text color
            $table->integer('countdown_hours')->nullable(); // For countdown section
            $table->boolean('countdown_repeat')->default(false); // For countdown section
            $table->integer('position')->default(0); // Display order within section
            $table->boolean('status')->default(true); // Active/Inactive
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('landing_page_id')->references('id')->on('landing_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_sections');
    }
}; 