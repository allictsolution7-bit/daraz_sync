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
        Schema::table('pages', function (Blueprint $table) {
            $table->json('seo')->nullable()->after('content');
            $table->string('meta_title')->nullable()->after('seo');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('canonical_url')->nullable()->after('meta_keywords');
            $table->string('meta_robots')->default('index,follow')->after('canonical_url');
            $table->string('og_image')->nullable()->after('meta_robots');
            $table->string('og_image_alt')->nullable()->after('og_image');
            $table->text('schema_markup')->nullable()->after('og_image_alt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'seo',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'meta_robots',
                'og_image',
                'og_image_alt',
                'schema_markup'
            ]);
        });
    }
};
