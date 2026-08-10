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
        \Illuminate\Support\Facades\DB::table('vendor_global_settings')->insert([
            [
                'key' => 'commission_by_category',
                'value' => '{}',
                'type' => 'json',
                'category' => 'commission',
                'label' => 'Commission by Category',
                'description' => 'Map of Category IDs to specific commission rate percentage (e.g. {"1": 10.00, "2": 15.00}). Empty maps fall back to global default.',
                'options' => null,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'commission_by_role',
                'value' => json_encode([
                    'wholeseller' => 10.00,
                    'retailer' => 15.00,
                    'vendor' => 15.00,
                    'reseller' => 5.00,
                ]),
                'type' => 'json',
                'category' => 'commission',
                'label' => 'Commission by Partner Role',
                'description' => 'Default commission rate percentage by partner role/type. Falls back to global default if not set.',
                'options' => null,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('vendor_global_settings')->whereIn('key', ['commission_by_category', 'commission_by_role'])->delete();
    }
};
