<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property array<array-key, mixed> $variation_options
 * @property string $combination_key
 * @property numeric $regular_price
 * @property numeric|null $offer_price
 * @property numeric|null $product_cost Variation cost/purchase price
 * @property numeric|null $wholesale_price Variation wholesale price
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $featured_image
 * @property array<array-key, mixed>|null $gallery_images
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int $stock_quantity
 * @property string|null $sku
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sort_order
 * @property-read mixed $discount_percentage
 * @property-read mixed $display_name
 * @property-read mixed $effective_price
 * @property-read mixed $featured_image_url
 * @property-read mixed $formatted_offer_price
 * @property-read mixed $formatted_price
 * @property-read mixed $formatted_regular_price
 * @property-read mixed $gallery_image_urls
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination inStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereCombinationKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereFeaturedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereGalleryImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereOfferPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereProductCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereRegularPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereVariationOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VariationCombination whereWholesalePrice($value)
 * @mixin \Eloquent
 */
class VariationCombination extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_options',
        'combination_key',
        'price', // Keep for backward compatibility
        'regular_price',
        'offer_price',
        'product_cost',
        'wholesale_price',
        'reseller_price',
        'stock_quantity',
        'short_description',
        'long_description',
        'featured_image',
        'gallery_images',
        'meta_title',
        'meta_description',
        'sku',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'variation_options' => 'array',
        'price' => 'decimal:2', // Keep for backward compatibility
        'regular_price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'product_cost' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'reseller_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'gallery_images' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the product that owns this combination.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variation options that make up this combination.
     */
    public function getVariationOptionsModels()
    {
        if (empty($this->variation_options)) {
            return collect();
        }

        return VariationOption::whereIn('id', $this->variation_options)->get();
    }

    /**
     * Get a formatted display name for this combination.
     */
    public function getDisplayNameAttribute()
    {
        $options = is_array($this->variation_options) ? $this->variation_options : json_decode($this->variation_options, true);
        
        if (!$options) {
            return '';
        }

        // Get all variation options
        $variationOptions = VariationOption::whereIn('id', $options)->with('variation')->get();
        
        // Group by variation
        $grouped = $variationOptions->groupBy(function($option) {
            return $option->variation->name;
        });

        // Format as "Size: Large, Color: Red"
        return $grouped->map(function($options, $variationName) {
            $optionNames = $options->pluck('name')->implode(', ');
            return "$variationName: $optionNames";
        })->implode(' • ');
    }

    /**
     * Generate a unique combination key from variation options.
     */
    public static function generateCombinationKey($productId, $variationOptionIds)
    {
        sort($variationOptionIds); // Sort to ensure consistent key generation
        return $productId . '_' . implode('_', $variationOptionIds);
    }

    /**
     * Check if this combination is in stock.
     */
    public function isInStock()
    {
        return $this->is_active && $this->stock_quantity > 0;
    }

    /**
     * Get the effective price (offer price if available, otherwise regular price).
     */
    public function getEffectivePriceAttribute()
    {
        return $this->offer_price ?? $this->regular_price ?? $this->price;
    }

    /**
     * Get the formatted effective price.
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->effective_price, 2);
    }

    /**
     * Get the formatted regular price.
     */
    public function getFormattedRegularPriceAttribute()
    {
        return number_format($this->regular_price ?? $this->price, 2);
    }

    /**
     * Get the formatted offer price.
     */
    public function getFormattedOfferPriceAttribute()
    {
        return $this->offer_price ? number_format($this->offer_price, 2) : null;
    }

    /**
     * Check if this combination has an offer.
     */
    public function hasOffer()
    {
        return !is_null($this->offer_price) && $this->offer_price < ($this->regular_price ?? $this->price);
    }

    /**
     * Get discount percentage if on offer.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->hasOffer()) {
            return 0;
        }

        $regularPrice = $this->regular_price ?? $this->price;
        return round((($regularPrice - $this->offer_price) / $regularPrice) * 100);
    }

    /**
     * Get the featured image URL or fallback to product image.
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        
        // Fallback to product's featured image
        return $this->product ? asset('storage/' . $this->product->thumb_image) : null;
    }

    /**
     * Get gallery images URLs.
     */
    public function getGalleryImageUrlsAttribute()
    {
        if (!$this->gallery_images || !is_array($this->gallery_images)) {
            return [];
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->gallery_images);
    }

    /**
     * Scope to get active combinations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get in-stock combinations.
     */
    public function scopeInStock($query)
    {
        return $query->where('is_active', true)->where('stock_quantity', '>', 0);
    }

    /**
     * Get option names as array for display
     */
    public function getOptionNamesArray()
    {
        if (empty($this->variation_options)) {
            return [];
        }

        return VariationOption::whereIn('id', $this->variation_options)->pluck('name')->toArray();
    }

    public function wholesaleTiers()
    {
        return $this->hasMany(ProductWholesaleTier::class, 'variation_combination_id')->orderBy('min_quantity');
    }
} 