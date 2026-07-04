<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\ShippingRule;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run()
    {
        // Create Dhaka Zone
        $dhakaZone = ShippingZone::create([
            'name' => 'Dhaka City',
            'regions' => json_encode(['Dhaka', 'Gulshan', 'Banani', 'Mirpur', 'Uttara']),
            'is_active' => true
        ]);

        // Create Chittagong Zone
        $chittagongZone = ShippingZone::create([
            'name' => 'Chittagong',
            'regions' => json_encode(['Chittagong', 'Agrabad', 'Halishahar']),
            'is_active' => true
        ]);

        // Create Other Cities Zone
        $otherZone = ShippingZone::create([
            'name' => 'Other Cities',
            'regions' => json_encode(['Sylhet', 'Rajshahi', 'Khulna', 'Barisal']),
            'is_active' => true
        ]);

        // Shipping Rules for Dhaka
        ShippingRule::create([
            'zone_id' => $dhakaZone->id,
            'name' => 'Dhaka Standard Delivery',
            'type' => 'location_min_amount',
            'min_amount' => 1000,
            'shipping_cost' => 60,
            'priority' => 1,
            'is_active' => true
        ]);

        // Free shipping for Dhaka over 2000
        ShippingRule::create([
            'zone_id' => $dhakaZone->id,
            'name' => 'Dhaka Free Shipping',
            'type' => 'location_min_amount',
            'min_amount' => 2000,
            'shipping_cost' => 0,
            'priority' => 2,
            'is_active' => true
        ]);

        // Chittagong Shipping Rules
        ShippingRule::create([
            'zone_id' => $chittagongZone->id,
            'name' => 'Chittagong Standard',
            'type' => 'location_paid',
            'shipping_cost' => 130,
            'priority' => 1,
            'is_active' => true
        ]);

        // Other Cities Rules
        ShippingRule::create([
            'zone_id' => $otherZone->id,
            'name' => 'Other Cities Standard',
            'type' => 'location_paid',
            'shipping_cost' => 150,
            'priority' => 1,
            'is_active' => true
        ]);

        // Global Free Shipping Rule
        ShippingRule::create([
            'name' => 'Global Free Shipping',
            'type' => 'global_min_amount',
            'min_amount' => 5000,
            'shipping_cost' => 0,
            'priority' => 10,
            'is_active' => true
        ]);
    }
}