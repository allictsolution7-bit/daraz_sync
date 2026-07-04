<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('variation_combinations', function (Blueprint $table) {
            // Check and add columns only if they don't exist
            if (!Schema::hasColumn('variation_combinations', 'regular_price')) {
                $table->decimal('regular_price', 10, 2)->nullable()->after('combination_key');
            }
            
            if (!Schema::hasColumn('variation_combinations', 'offer_price')) {
                $table->decimal('offer_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('variation_combinations', 'short_description')) {
                $table->text('short_description')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'long_description')) {
                $table->text('long_description')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'featured_image')) {
                $table->string('featured_image')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'gallery_images')) {
                $table->json('gallery_images')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            
            if (!Schema::hasColumn('variation_combinations', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });
        
        // Copy existing price data to regular_price if needed
        if (Schema::hasColumn('variation_combinations', 'price') && Schema::hasColumn('variation_combinations', 'regular_price')) {
            DB::statement('UPDATE variation_combinations SET regular_price = price WHERE regular_price IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variation_combinations', function (Blueprint $table) {
            $columnsToCheck = [
                'regular_price', 'offer_price', 'short_description', 'long_description',
                'featured_image', 'gallery_images', 'meta_title', 'meta_description', 'sort_order'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('variation_combinations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
