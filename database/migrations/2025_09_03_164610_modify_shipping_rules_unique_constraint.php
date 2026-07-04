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
        Schema::table('basic_shipping_rules', function (Blueprint $table) {
            // Drop the existing unique constraint
            $table->dropUnique('unique_active_rule');
            
            // Add a new unique constraint that allows multiple delivery_area rules
            // but prevents duplicate non-delivery_area rules
            $table->unique(['ruleable_type', 'ruleable_id', 'rule_type', 'is_active', 'delivery_area_slug'], 'unique_active_rule_with_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basic_shipping_rules', function (Blueprint $table) {
            // Drop the new constraint
            $table->dropUnique('unique_active_rule_with_area');
            
            // Restore the original constraint
            $table->unique(['ruleable_type', 'ruleable_id', 'rule_type', 'is_active'], 'unique_active_rule');
        });
    }
};