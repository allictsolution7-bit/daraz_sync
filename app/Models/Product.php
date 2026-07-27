<?php

namespace App\Models;

use App\Models\Writer;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\BasicShippingRule;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $is_featured
 * @property string|null $description
 * @property string|null $short_description
 * @property string $thumb_image
 * @property array<array-key, mixed>|null $images
 * @property string|null $tags
 * @property numeric|null $old_price
 * @property numeric|null $offer
 * @property numeric|null $product_cost Product cost/purchase price
 * @property numeric|null $wholesale_price Wholesale price for bulk buyers
 * @property int $status
 * @property string $product_type
 * @property string|null $digital_file
 * @property int|null $download_limit
 * @property string|null $external_url
 * @property string|null $affiliate_commission
 * @property int|null $quantity
 * @property string $stock_status
 * @property bool $manage_stock
 * @property int $low_stock_threshold
 * @property int $category_id
 * @property int|null $sub_category_id
 * @property int|null $brand_id
 * @property string|null $video_url
 * @property array<array-key, mixed>|null $seo SEO metadata for the product
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComboOffer> $activeComboOffers
 * @property-read int|null $active_combo_offers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerReview> $activeReviews
 * @property-read int|null $active_reviews_count
 * @property-read \App\Models\Book|null $book
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComboOffer> $comboOffers
 * @property-read int|null $combo_offers_count
 * @property-read mixed $average_rating
 * @property-read mixed $formatted_seo
 * @property-read mixed $rating_distribution
 * @property-read mixed $review_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\order_item> $order_items
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CustomerReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\SubCategory|null $subCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariationCombination> $variationCombinations
 * @property-read int|null $variation_combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variation> $variations
 * @property-read int|null $variations_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAffiliateCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDigitalFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDownloadLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereExternalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLowStockThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereManageStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOldPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStockStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereThumbImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWholesalePrice($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'thumb_image',
        'slug',
        'sku',
        'images',
        'tags',
        'is_featured',
        'old_price',
        'offer',
        'product_cost',
        'wholesale_price',
        'status',
        'quantity',
        'weight',
        'stock_status',
        'manage_stock',
        'low_stock_threshold',
        'category_id',
        'sub_category_id',
        'brand_id',
        'product_type',
        'digital_file',
        'download_limit',
        'external_url',
        'affiliate_commission',
        'video_url',
        'seo',
        'views_total',
        'views_unique',
        // Vendor fields
        'vendor_id',
        'created_by',
        'approval_status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'vendor_proposed_commission',
        'vendor_commission_rate',
        'commission_note',
    ];

    protected $casts = [
        'images' => 'array',
        'seo' => 'array',
        'manage_stock' => 'boolean',
        'low_stock_threshold' => 'integer',
        'quantity' => 'integer',
        'old_price' => 'decimal:2',
        'offer' => 'decimal:2',
        'product_cost' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'views_total' => 'integer',
        'views_unique' => 'integer',
        // Vendor casts
        'approved_at' => 'datetime',
        'vendor_proposed_commission' => 'decimal:2',
        'vendor_commission_rate' => 'decimal:2',
    ];

    /**
     * Get properly formatted SEO data
     */
    public function getFormattedSeoAttribute()
    {
        $seo = $this->seo;
        
        // If SEO is a string, try to decode it
        if (is_string($seo)) {
            $seo = json_decode($seo, true);
        }
        
        // If SEO is null or empty, return empty array
        if (empty($seo) || !is_array($seo)) {
            return [];
        }
        
        return $seo;
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function variationCombinations()
    {
        return $this->hasMany(VariationCombination::class);
    }

    public function comboOffers()
    {
        return $this->hasMany(ComboOffer::class);
    }

    public function activeComboOffers()
    {
        return $this->hasMany(ComboOffer::class)->where('is_active', true);
    }

    public function order_items()
    {
        return $this->hasMany(order_item::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Add relationship to SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // ==========================================
    // MULTIPLE CATEGORY RELATIONSHIPS (HYBRID APPROACH)
    // ==========================================

    /**
     * Additional categories (many-to-many via pivot table)
     * Primary category is still in category_id column for backward compatibility
     */
    public function additionalCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category_pivot', 'product_id', 'category_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_category_pivot.sort_order');
    }

    /**
     * Additional sub categories (many-to-many via pivot table)
     * Primary sub category is still in sub_category_id column for backward compatibility
     */
    public function additionalSubCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_subcategory_pivot', 'product_id', 'sub_category_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_subcategory_pivot.sort_order');
    }

    /**
     * Third level categories (many-to-many via pivot table)
     */
    public function thirdCategories()
    {
        return $this->belongsToMany(ThirdCategory::class, 'product_third_category_pivot', 'product_id', 'third_category_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_third_category_pivot.sort_order');
    }

    /**
     * Get all categories (primary + additional)
     */
    public function getAllCategories()
    {
        $categories = collect([$this->category])->filter();
        return $categories->merge($this->additionalCategories)->unique('id');
    }

    /**
     * Get all subcategories (primary + additional)
     */
    public function getAllSubCategories()
    {
        $subcategories = collect([$this->subCategory])->filter();
        return $subcategories->merge($this->additionalSubCategories)->unique('id');
    }

    /**
     * Scope: Filter by category (checks both primary category_id AND additional categories)
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where(function($q) use ($categoryId) {
            $q->where('category_id', $categoryId)
              ->orWhereHas('additionalCategories', function($subQ) use ($categoryId) {
                  $subQ->where('product_categories.id', $categoryId);
              });
        });
    }

    /**
     * Scope: Filter by subcategory (checks both primary sub_category_id AND additional subcategories)
     */
    public function scopeInSubCategory($query, $subCategoryId)
    {
        return $query->where(function($q) use ($subCategoryId) {
            $q->where('sub_category_id', $subCategoryId)
              ->orWhereHas('additionalSubCategories', function($subQ) use ($subCategoryId) {
                  $subQ->where('sub_categories.id', $subCategoryId);
              });
        });
    }

    /**
     * Scope: Filter by third category
     */
    public function scopeInThirdCategory($query, $thirdCategoryId)
    {
        return $query->whereHas('thirdCategories', function($q) use ($thirdCategoryId) {
            $q->where('third_categories.id', $thirdCategoryId);
        });
    }

    // Add relationship to Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function book()
    {
        return $this->hasOne(Book::class, 'product_id');
    }

    /**
     * Get all reviews for this product.
     */
    public function reviews()
    {
        return $this->hasMany(CustomerReview::class);
    }

    /**
     * Get only active reviews for this product.
     */
    public function activeReviews()
    {
        return $this->hasMany(CustomerReview::class)->where('is_active', true);
    }

    /**
     * Get the average rating for this product.
     */
    public function getAverageRatingAttribute()
    {
        return $this->activeReviews()->avg('rating') ?? 0;
    }

    /**
     * Get the total number of reviews for this product.
     */
    public function getReviewCountAttribute()
    {
        return $this->activeReviews()->count();
    }

    /**
     * Get rating distribution for this product.
     */
    public function getRatingDistributionAttribute()
    {
        return $this->activeReviews()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();
    }

    /**
     * Check if user has purchased this product.
     */
    public function hasUserPurchased($userId)
    {
        return $this->order_items()
            ->whereHas('order', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 'completed');
            })
            ->exists();
    }

    /**
     * Check if user has reviewed this product.
     */
    public function hasUserReviewed($userId)
    {
        return $this->reviews()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get shipping rules for this product.
     */
    public function shippingRules(): MorphMany
    {
        return $this->morphMany(BasicShippingRule::class, 'ruleable');
    }

    /**
     * Query scope for eager loading product card data
     * This reduces N+1 queries significantly
     * 
     * NOTE: variation_combinations has NO 'price' column!
     * It only has: regular_price, offer_price, product_cost, wholesale_price
     */
    public function scopeWithProductCardData($query)
    {
        return $query->with([
            'variationCombinations:id,product_id,regular_price,offer_price',
            'variations:id,product_id',
            'variations.options:id,variation_id,name,price',
            'book:id,product_id,publisher_id',
            'book.writers:id,name',
        ])
        ->withAvg('activeReviews as active_reviews_avg_rating', 'rating')
        ->withCount('activeReviews as active_reviews_count');
    }

    /**
     * Get price range for variable products (pre-calculated)
     * This prevents recalculating prices in the view for every product
     * 
     * Actual DB columns:
     * variation_combinations: regular_price, offer_price (NO 'price' column!)
     * variation_options: price only
     */
    public function getPriceRangeAttribute()
    {
        if ($this->product_type !== 'variable') {
            return null;
        }
        
        $offerPrices = [];
        $regularPrices = [];
        
        // Get prices from variation combinations (preferred)
        if ($this->relationLoaded('variationCombinations') && $this->variationCombinations->isNotEmpty()) {
            foreach ($this->variationCombinations as $combination) {
                // offer_price is the sale/discounted price (or use regular_price if no offer)
                $offerPrice = $combination->offer_price ?? $combination->regular_price ?? 0;
                // regular_price is the original price
                $regularPrice = $combination->regular_price ?? 0;
                
                if ($offerPrice > 0) {
                    $offerPrices[] = $offerPrice;
                }
                if ($regularPrice > 0) {
                    $regularPrices[] = $regularPrice;
                }
            }
        }
        
        // Fallback to variations if no combinations found
        if (empty($offerPrices) && $this->relationLoaded('variations') && $this->variations->isNotEmpty()) {
            foreach ($this->variations as $variation) {
                if ($variation->relationLoaded('options') && $variation->options->isNotEmpty()) {
                    foreach ($variation->options as $option) {
                        // variation_options only has 'price' column
                        $price = $option->price ?? 0;
                        
                        if ($price > 0) {
                            $offerPrices[] = $price;
                            $regularPrices[] = $price;
                        }
                    }
                }
            }
        }
        
        // Final fallback to product's own price
        if (empty($offerPrices)) {
            $fallbackOfferPrice = $this->offer ?? $this->old_price ?? 0;
            if ($fallbackOfferPrice > 0) {
                $offerPrices[] = $fallbackOfferPrice;
            }
        }
        
        return [
            'min_offer' => !empty($offerPrices) ? min($offerPrices) : 0,
            'max_offer' => !empty($offerPrices) ? max($offerPrices) : 0,
            'min_regular' => !empty($regularPrices) ? min($regularPrices) : 0,
            'max_regular' => !empty($regularPrices) ? max($regularPrices) : 0,
        ];
    }

    /**
     * Get discount percentage (pre-calculated)
     * This prevents recalculating discount in the view for every product
     * 
     * Actual DB columns:
     * variation_combinations: regular_price, offer_price (NO 'price' column!)
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->product_type === 'variable' && $this->relationLoaded('variationCombinations') && $this->variationCombinations->isNotEmpty()) {
            $maxRegularPrice = 0;
            $minOfferPrice = 0;

            foreach ($this->variationCombinations as $combination) {
                // regular_price is the original price
                $regularPrice = $combination->regular_price ?? 0;
                // offer_price is the sale/discounted price (or use regular_price if no offer)
                $offerPrice = $combination->offer_price ?? $combination->regular_price ?? 0;

                if ($regularPrice > $maxRegularPrice) {
                    $maxRegularPrice = $regularPrice;
                }

                if ($offerPrice > 0 && ($minOfferPrice === 0 || $offerPrice < $minOfferPrice)) {
                    $minOfferPrice = $offerPrice;
                }
            }

            if ($maxRegularPrice > 0 && $minOfferPrice > 0 && $minOfferPrice < $maxRegularPrice) {
                return round((($maxRegularPrice - $minOfferPrice) / $maxRegularPrice) * 100);
            }
        } else {
            // Simple product discount
            if (isset($this->old_price) && isset($this->offer) && $this->old_price > 0 && $this->offer < $this->old_price) {
                return round((($this->old_price - $this->offer) / $this->old_price) * 100);
            }
        }
        
        return null;
    }

    // ==========================================
    // VENDOR RELATIONSHIPS & METHODS
    // ==========================================

    /**
     * Vendor who uploaded this product
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Admin/User who created this product record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Admin who approved this product
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope: Only approved products
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope: Only pending products
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope: Products for specific vendor
     */
    public function scopeForVendor($query, int $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    /**
     * Scope: Products safe for public display
     * Excludes unapproved vendor products (shows admin products + approved vendor products only)
     * If MultiVendor module is disabled, only shows admin products
     * Use this scope for all client-facing product queries
     */
    public function scopeForPublicDisplay($query)
    {
        // If MultiVendor module is disabled, only show admin products
        if (!module_enabled('MultiVendor')) {
            return $query->whereNull('vendor_id');
        }

        return $query->where(function($q) {
            $q->whereNull('vendor_id') // Admin products (always visible)
              ->orWhere('approval_status', 'approved'); // Only approved vendor products
        });
    }

    /**
     * Check if product is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if product is pending approval
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Scope: Products accessible by a specific user/admin in the backend Product Catalog
     */
    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) {
            return $query;
        }

        // Only include Admin products (exclude vendor products)
        $query->whereNull('products.vendor_id');

        // Super admins see all admin products
        if (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('Super Admin'))) {
            return $query;
        }

        // Regular admins see products created by them
        return $query->where('products.created_by', $user->id);
    }
}
