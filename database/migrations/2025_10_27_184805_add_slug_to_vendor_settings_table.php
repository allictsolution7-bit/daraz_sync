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
        // Add column if it doesn't exist
        if (!Schema::hasColumn('vendor_settings', 'store_slug')) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->string('store_slug')->nullable()->after('business_name')->comment('Unique URL slug for vendor store');
            });
        }
        
        // Generate slugs for existing vendors
        DB::statement("
            UPDATE vendor_settings vs
            JOIN users u ON vs.vendor_id = u.id
            SET vs.store_slug = LOWER(REPLACE(REPLACE(REPLACE(u.name, ' ', '-'), '.', ''), ',', ''))
            WHERE vs.store_slug IS NULL
        ");
        
        // Add unique constraint if it doesn't exist
        $indexes = DB::select("SHOW INDEXES FROM vendor_settings WHERE Key_name = 'vendor_settings_store_slug_unique'");
        if (empty($indexes)) {
            Schema::table('vendor_settings', function (Blueprint $table) {
                $table->unique('store_slug');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_settings', function (Blueprint $table) {
            $table->dropColumn('store_slug');
        });
    }
};
