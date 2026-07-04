<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $user_id
 * @property string $reviewer_name
 * @property string|null $reviewer_email
 * @property string|null $reviewer_image
 * @property \Illuminate\Support\Carbon $review_date
 * @property int $rating
 * @property string $product_name
 * @property string|null $product_image
 * @property string $review_text
 * @property array<array-key, mixed>|null $review_images
 * @property bool $is_active
 * @property bool $is_verified_purchase
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview forProduct($productId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereIsVerifiedPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereProductImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewerImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereReviewerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerReview whereUserId($value)
 * @mixin \Eloquent
 */
class CustomerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'reviewer_name',
        'reviewer_email',
        'reviewer_image',
        'review_date',
        'rating',
        'product_name',
        'product_image',
        'review_text',
        'review_images',
        'is_active',
        'is_verified_purchase'
    ];

    protected $casts = [
        'review_date' => 'date',
        'is_active' => 'boolean',
        'is_verified_purchase' => 'boolean',
        'review_images' => 'array',
    ];

    /**
     * Get the product that the review belongs to.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active reviews.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get reviews for a specific product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Get the average rating for a product.
     */
    public static function getAverageRating($productId)
    {
        return self::where('product_id', $productId)
            ->where('is_active', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Get the total number of reviews for a product.
     */
    public static function getReviewCount($productId)
    {
        return self::where('product_id', $productId)
            ->where('is_active', true)
            ->count();
    }

    /**
     * Get rating distribution for a product.
     */
    public static function getRatingDistribution($productId)
    {
        return self::where('product_id', $productId)
            ->where('is_active', true)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();
    }
}