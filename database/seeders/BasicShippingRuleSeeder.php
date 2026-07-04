<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BasicShippingRule;
use App\Models\Product;
use App\Models\LandingPage;

class BasicShippingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products and landing pages for sample rules
        $products = Product::where('status', 1)->take(3)->get();
        $landingPages = LandingPage::where('status', 1)->take(2)->get();

        // Create sample shipping rules for products
        if ($products->count() > 0) {
            // Free shipping for first product
            BasicShippingRule::create([
                'ruleable_type' => Product::class,
                'ruleable_id' => $products[0]->id,
                'rule_type' => 'override',
                'rule_value' => 0,
                'priority' => 10,
                'is_active' => true,
            ]);

            // Custom cost for second product
            if ($products->count() > 1) {
                BasicShippingRule::create([
                    'ruleable_type' => Product::class,
                    'ruleable_id' => $products[1]->id,
                    'rule_type' => 'custom_cost',
                    'rule_value' => 50.00,
                    'priority' => 5,
                    'is_active' => true,
                ]);
            }

            // Free shipping threshold for third product
            if ($products->count() > 2) {
                BasicShippingRule::create([
                    'ruleable_type' => Product::class,
                    'ruleable_id' => $products[2]->id,
                    'rule_type' => 'free_shipping',
                    'free_shipping_threshold' => 800.00,
                    'priority' => 8,
                    'is_active' => true,
                ]);
            }
        }

        // Create sample shipping rules for landing pages
        if ($landingPages->count() > 0) {
            // Free shipping for first landing page
            BasicShippingRule::create([
                'ruleable_type' => LandingPage::class,
                'ruleable_id' => $landingPages[0]->id,
                'rule_type' => 'override',
                'rule_value' => 0,
                'priority' => 15,
                'is_active' => true,
            ]);

            // Percentage-based shipping for second landing page
            if ($landingPages->count() > 1) {
                BasicShippingRule::create([
                    'ruleable_type' => LandingPage::class,
                    'ruleable_id' => $landingPages[1]->id,
                    'rule_type' => 'percentage',
                    'rule_value' => 5.0, // 5% of order total
                    'priority' => 7,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Sample shipping rules created successfully!');
    }
}
