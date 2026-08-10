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
            'key' => 'commission_calculation_method',
            'value' => 'category',
            'type' => 'string',
            'category' => 'commission',
            'label' => 'Commission Calculation Method',
            'description' => 'Preferred method for calculating platform commission automatically.',
            'options' => json_encode(['category' => 'Category-based', 'role' => 'Role-based']),
            'is_public' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('vendor_global_settings')->where('key', 'commission_calculation_method')->delete();
    }
};
