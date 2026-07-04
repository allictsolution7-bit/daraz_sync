<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'description' => 'Just Do It - Leading sports and lifestyle brand',
                'website' => 'https://www.nike.com',
                'status' => true,
                'meta_title' => 'Nike - Just Do It',
                'meta_description' => 'Leading sports and lifestyle brand offering innovative athletic footwear, apparel, and equipment.',
                'meta_keywords' => 'nike, sports, athletic, footwear, apparel, just do it'
            ],
            [
                'name' => 'Adidas',
                'description' => 'Impossible is Nothing - Global sportswear manufacturer',
                'website' => 'https://www.adidas.com',
                'status' => true,
                'meta_title' => 'Adidas - Impossible is Nothing',
                'meta_description' => 'Global sportswear manufacturer known for innovative athletic footwear and apparel.',
                'meta_keywords' => 'adidas, sportswear, athletic, footwear, impossible is nothing'
            ],
            [
                'name' => 'Apple',
                'description' => 'Think Different - Technology and innovation leader',
                'website' => 'https://www.apple.com',
                'status' => true,
                'meta_title' => 'Apple - Think Different',
                'meta_description' => 'Technology company that designs, develops, and sells consumer electronics, software, and online services.',
                'meta_keywords' => 'apple, technology, iphone, mac, ipad, think different'
            ],
            [
                'name' => 'Samsung',
                'description' => 'Do What You Can\'t - Electronics and technology company',
                'website' => 'https://www.samsung.com',
                'status' => true,
                'meta_title' => 'Samsung - Do What You Can\'t',
                'meta_description' => 'Global electronics and technology company offering innovative products and solutions.',
                'meta_keywords' => 'samsung, electronics, technology, smartphones, tvs, do what you cant'
            ],
            [
                'name' => 'Generic',
                'description' => 'Quality products without brand premium',
                'website' => null,
                'status' => true,
                'meta_title' => 'Generic Products',
                'meta_description' => 'Quality products offered without brand premium pricing.',
                'meta_keywords' => 'generic, unbranded, quality, affordable'
            ]
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }
    }
}
