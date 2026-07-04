<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Uses raw SQL instead of ->change() to avoid requiring doctrine/dbal package.
     */
    public function up(): void
    {
        // Make category_id nullable using raw SQL (works without doctrine/dbal)
        DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This might fail if there are existing NULL values
        // In that case, you would need to set a default category_id before running down()
        DB::statement('ALTER TABLE products MODIFY category_id BIGINT UNSIGNED NOT NULL');
    }
};

