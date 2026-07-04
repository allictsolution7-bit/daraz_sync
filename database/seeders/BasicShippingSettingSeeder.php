<?php

namespace Database\Seeders;

use App\Models\BasicShippingSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasicShippingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BasicShippingSetting::create([
            'flat_rate' => 80.00,
            'shipping_options' => [
                'inside_dhaka' => ['name' => 'Inside Dhaka', 'cost' => 80.00, 'active' => true, 'position' => 1],
                'outside_dhaka' => ['name' => 'Outside Dhaka', 'cost' => 110.00, 'active' => true, 'position' => 2],
            ],
            'free_shipping_threshold' => 1500.00,
        ]);
    }
}
