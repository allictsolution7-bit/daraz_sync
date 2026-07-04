<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerReview;
use App\Models\Product;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products to add reviews to
        $products = Product::take(5)->get();

        if ($products->isEmpty()) {
            $this->command->info('No products found. Please create some products first.');
            return;
        }

        $reviews = [
            [
                'reviewer_name' => 'আহমেদ হাসান',
                'reviewer_email' => 'ahmed@example.com',
                'rating' => 5,
                'review_text' => 'অত্যন্ত ভালো পণ্য! গুণমান অনেক ভালো এবং দামও যুক্তিসঙ্গত। আমি সত্যিই সন্তুষ্ট।',
                'is_verified_purchase' => true,
            ],
            [
                'reviewer_name' => 'ফাতেমা বেগম',
                'reviewer_email' => 'fatema@example.com',
                'rating' => 4,
                'review_text' => 'পণ্যটি ভালো, তবে আরও উন্নত করা যেতে পারে। সামগ্রিকভাবে সন্তুষ্ট।',
                'is_verified_purchase' => true,
            ],
            [
                'reviewer_name' => 'রহমান আলী',
                'reviewer_email' => 'rahman@example.com',
                'rating' => 5,
                'review_text' => 'দারুণ পণ্য! আমি আমার বন্ধুদেরকেও এই পণ্য কেনার পরামর্শ দিয়েছি।',
                'is_verified_purchase' => false,
            ],
            [
                'reviewer_name' => 'নাজমা আক্তার',
                'reviewer_email' => 'nazma@example.com',
                'rating' => 3,
                'review_text' => 'পণ্যটি ঠিক আছে, তবে আমার প্রত্যাশার চেয়ে কিছুটা কম।',
                'is_verified_purchase' => true,
            ],
            [
                'reviewer_name' => 'ইমরান খান',
                'reviewer_email' => 'imran@example.com',
                'rating' => 5,
                'review_text' => 'সেরা পণ্য! আমি আগেও কিনেছি এবং আবার কিনব। খুবই সুপারিশ করি।',
                'is_verified_purchase' => true,
            ],
        ];

        foreach ($products as $product) {
            foreach ($reviews as $reviewData) {
                CustomerReview::create([
                    'product_id' => $product->id,
                    'reviewer_name' => $reviewData['reviewer_name'],
                    'reviewer_email' => $reviewData['reviewer_email'],
                    'rating' => $reviewData['rating'],
                    'review_text' => $reviewData['review_text'],
                    'review_date' => now()->subDays(rand(1, 30)),
                    'product_name' => $product->title,
                    'product_image' => $product->thumb_image,
                    'is_verified_purchase' => $reviewData['is_verified_purchase'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Sample product reviews created successfully!');
    }
}
