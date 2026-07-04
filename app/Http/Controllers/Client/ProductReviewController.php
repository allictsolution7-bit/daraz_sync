<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CustomerReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a new product review.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10|max:1000',
            'reviewer_name' => 'required_if:user_id,null|string|max:255',
            'reviewer_email' => 'required_if:user_id,null|email|max:255',
            'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'reviewer_name.required_if' => 'নাম প্রয়োজন।',
            'reviewer_email.required_if' => 'ইমেইল প্রয়োজন।',
            'reviewer_email.email' => 'সঠিক ইমেইল ঠিকানা দিন।',
            'rating.required' => 'রেটিং নির্বাচন করুন।',
            'rating.min' => 'রেটিং ১-৫ এর মধ্যে হতে হবে।',
            'rating.max' => 'রেটিং ১-৫ এর মধ্যে হতে হবে।',
            'review_text.required' => 'মতামত লিখুন।',
            'review_text.min' => 'মতামত কমপক্ষে ১০ অক্ষর হতে হবে।',
            'review_text.max' => 'মতামত সর্বোচ্চ ১০০০ অক্ষর হতে পারে।',
            'review_images.*.image' => 'শুধুমাত্র ছবি আপলোড করুন।',
            'review_images.*.max' => 'ছবির আকার সর্বোচ্চ ২MB হতে পারে।',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ভ্যালিডেশন ত্রুটি',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::findOrFail($request->product_id);
        $user = Auth::user();

        // Check if user has already reviewed this product
        if ($user && $product->hasUserReviewed($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'আপনি ইতিমধ্যে এই পণ্যের রিভিউ দিয়েছেন।'
            ], 400);
        }

        // Check if user has purchased this product (for verified purchase badge)
        $isVerifiedPurchase = false;
        if ($user) {
            $isVerifiedPurchase = $product->hasUserPurchased($user->id);
        }

        // Handle image uploads
        $reviewImages = [];
        if ($request->hasFile('review_images')) {
            foreach ($request->file('review_images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/reviews', $imageName);
                $reviewImages[] = 'storage/reviews/' . $imageName;
            }
        }

        // Create the review
        $reviewData = [
            'product_id' => $product->id,
            'user_id' => $user ? $user->id : null,
            'reviewer_name' => $user ? $user->name : $request->reviewer_name,
            'reviewer_email' => $user ? $user->email : $request->reviewer_email,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'review_images' => $reviewImages,
            'review_date' => now(),
            'product_name' => $product->title,
            'product_image' => $product->thumb_image,
            'is_verified_purchase' => $isVerifiedPurchase,
            'is_active' => true,
        ];

        // Add reviewer image if user has one
        if ($user && $user->profile_photo_path) {
            $reviewData['reviewer_image'] = $user->profile_photo_path;
        }

        $review = CustomerReview::create($reviewData);

        return response()->json([
            'success' => true,
            'message' => 'আপনার রিভিউ সফলভাবে জমা হয়েছে। ধন্যবাদ!',
            'review' => $review->load('user'),
            'product_stats' => [
                'average_rating' => $product->fresh()->average_rating,
                'review_count' => $product->fresh()->review_count,
            ]
        ]);
    }

    /**
     * Get reviews for a specific product.
     */
    public function getProductReviews($productId)
    {
        $product = Product::findOrFail($productId);
        
        $reviews = $product->activeReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $ratingDistribution = $product->rating_distribution;

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'product_stats' => [
                'average_rating' => $product->average_rating,
                'review_count' => $product->review_count,
                'rating_distribution' => $ratingDistribution,
            ]
        ]);
    }

    /**
     * Check if user can review a product.
     */
    public function canReview($productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $canReview = true;
        $hasPurchased = false;
        $hasReviewed = false;

        if ($user) {
            $hasPurchased = $product->hasUserPurchased($user->id);
            $hasReviewed = $product->hasUserReviewed($user->id);
            $canReview = !$hasReviewed; // Can't review if already reviewed
        }

        return response()->json([
            'success' => true,
            'can_review' => $canReview,
            'has_purchased' => $hasPurchased,
            'has_reviewed' => $hasReviewed,
            'is_authenticated' => $user ? true : false,
        ]);
    }
} 