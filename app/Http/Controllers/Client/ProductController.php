<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SiteSetting;
use App\Models\ProductCategory;
use App\Services\SettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index($id, $slug): View
    {

        
        $product = Product::where('status', 1)->where('id', $id)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->with([
                'variationCombinations',
                'additionalCategories',
                'additionalSubCategories.category',
                'thirdCategories.subCategory.category'
            ])
            ->firstOrFail();
        // Get all category IDs (primary + additional) for related products
        $categoryIds = collect();
        if ($product->category_id) {
            $categoryIds->push($product->category_id);
        }
        if ($product->additionalCategories) {
            $categoryIds = $categoryIds->merge($product->additionalCategories->pluck('id'));
        }
        $categoryIds = $categoryIds->unique()->filter();
        
        // Get related products from same categories (primary or additional)
        $related_products = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($categoryIds) {
                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('category_id', $categoryIds);
                      });
                }
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->withProductCardData()
            ->take(5)
            ->inRandomOrder()
            ->get();
        
        // Get additional related products for the new section
        $additional_related_products = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($categoryIds) {
                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('category_id', $categoryIds);
                      });
                }
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->withProductCardData()
            ->take(8)
            ->inRandomOrder()
            ->get();
        
        // Get product reviews and statistics
        $reviews = $product->activeReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $reviewStats = [
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'rating_distribution' => $product->rating_distribution,
        ];

        // Check if product has combo offers
        $hasComboOffers = \App\Models\ComboOffer::where('product_id', $product->id)
            ->where('is_active', true)
            ->exists();

        // Prepare variation combinations data with images for frontend
        $variationCombinationsData = [];
        if ($product->product_type === 'variable' && $product->variationCombinations) {
            foreach ($product->variationCombinations as $combination) {
                // Skip inactive combinations
                if (!$combination->is_active) {
                    continue;
                }
                
                // Ensure variation_options is an array of integers
                $variationOptions = $combination->variation_options;
                if (is_string($variationOptions)) {
                    $variationOptions = json_decode($variationOptions, true) ?? [];
                }
                // Convert all option IDs to integers for consistent comparison
                $variationOptions = array_map('intval', (array)$variationOptions);
                
                $variationCombinationsData[] = [
                    'id' => (int)$combination->id,
                    'variation_options' => $variationOptions,
                    'combination_key' => $combination->combination_key,
                    'regular_price' => (float)$combination->regular_price,
                    'offer_price' => $combination->offer_price ? (float)$combination->offer_price : null,
                    'stock_quantity' => (int)$combination->stock_quantity,
                    'short_description' => $combination->short_description,
                    'featured_image' => $combination->featured_image,
                    'gallery_images' => $combination->gallery_images,
                    'is_active' => (bool)$combination->is_active,
                    'price' => (float)($combination->offer_price ?? $combination->regular_price), // Add fallback price field
                ];
            }
        }

        // Set SEO meta data using the new product SEO system
        $seoData = $this->getProductSeoData($product);

        // Optimize category sliders with batch queries
        $sliderCategories1 = $this->buildCategorySlider([12, 8], 12);
        $SingleProductSliderCategories = $this->buildCategorySlider([12, 8], 12);

        return view('frontend.singelProduct', array_merge(compact(
            'product',
            'related_products',
            'additional_related_products',
            'reviews',
            'reviewStats',
            'sliderCategories1',
            'SingleProductSliderCategories',
            'variationCombinationsData',
            'hasComboOffers'
        ), $seoData));
    }

    /**
     * Record a product view (total + optional unique).
     */
    public function trackView(Request $request, Product $product)
    {
        $isUnique = $request->boolean('unique');

        $product->increment('views_total');

        if ($isUnique) {
            $product->increment('views_unique');
        }

        return response()->noContent();
    }

    public function newindex($id, $slug): View
    {
        $product = Product::where('status', 1)->where('id', $id)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->with([
                'additionalCategories',
                'additionalSubCategories.category',
                'thirdCategories.subCategory.category'
            ])
            ->first();
        // Get all category IDs (primary + additional) for related products
        $categoryIds = collect();
        if ($product->category_id) {
            $categoryIds->push($product->category_id);
        }
        if ($product->additionalCategories) {
            $categoryIds = $categoryIds->merge($product->additionalCategories->pluck('id'));
        }
        $categoryIds = $categoryIds->unique()->filter();
        
        // Get related products from same categories (primary or additional)
        $related_products = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($categoryIds) {
                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('category_id', $categoryIds);
                      });
                }
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->withProductCardData()
            ->take(6)
            ->inRandomOrder()
            ->get();
        
        // Get additional related products for the new section
        $additional_related_products = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->where(function($q) use ($categoryIds) {
                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('category_id', $categoryIds);
                      });
                }
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->withProductCardData()
            ->take(8)
            ->inRandomOrder()
            ->get();
        
        // Get product reviews and statistics
        $reviews = $product->activeReviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        $reviewStats = [
            'average_rating' => $product->average_rating,
            'review_count' => $product->review_count,
            'rating_distribution' => $product->rating_distribution,
        ];

        // Prepare variation combinations data with images for frontend
        $variationCombinationsData = [];
        if ($product->product_type === 'variable' && $product->variationCombinations) {
            foreach ($product->variationCombinations as $combination) {
                // Skip inactive combinations
                if (!$combination->is_active) {
                    continue;
                }
                
                // Ensure variation_options is an array of integers
                $variationOptions = $combination->variation_options;
                if (is_string($variationOptions)) {
                    $variationOptions = json_decode($variationOptions, true) ?? [];
                }
                // Convert all option IDs to integers for consistent comparison
                $variationOptions = array_map('intval', (array)$variationOptions);
                
                $variationCombinationsData[] = [
                    'id' => (int)$combination->id,
                    'variation_options' => $variationOptions,
                    'combination_key' => $combination->combination_key,
                    'regular_price' => (float)$combination->regular_price,
                    'offer_price' => $combination->offer_price ? (float)$combination->offer_price : null,
                    'stock_quantity' => (int)$combination->stock_quantity,
                    'short_description' => $combination->short_description,
                    'featured_image' => $combination->featured_image,
                    'gallery_images' => $combination->gallery_images,
                    'is_active' => (bool)$combination->is_active,
                    'price' => (float)($combination->offer_price ?? $combination->regular_price), // Add fallback price field
                ];
            }
        }

        // Set SEO meta data using the new product SEO system
        $seoData = $this->getProductSeoData($product);

        // Single product slider categories - optimized with batch queries
        $SingleProductSliderCategories = [];
        $enableBottomCategorySlider = SettingsService::get('single_product', 'enable_bottom_category_slider', '0');
        
        if ($enableBottomCategorySlider == '1') {
            $categoryOrder = json_decode(SettingsService::get('single_product', 'bottom_category_slider_categories_order', '[]'), true);
            $productsPerCategory = (int)SettingsService::get('single_product', 'bottom_category_slider_products_per_category', 12);
            
            if (!empty($categoryOrder)) {
                $categoryIds = [];
                foreach ($categoryOrder as $itemKey) {
                    if (str_starts_with($itemKey, 'category-')) {
                        $catId = (int)str_replace('category-', '', $itemKey);
                        $categoryIds[] = $catId;
                    }
                }

                // Batch load categories and products (includes products from additional categories)
                if (!empty($categoryIds)) {
                    $categories = ProductCategory::whereIn('id', $categoryIds)->get()->keyBy('id');
                    $allProducts = Product::where('status', 1)
                        ->where(function($q) use ($categoryIds) {
                            $q->whereIn('category_id', $categoryIds)
                              ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                                  $subQ->whereIn('category_id', $categoryIds);
                              });
                        })
                        ->forPublicDisplay() // Only show approved vendor products or admin products
                        ->with('additionalCategories') // Eager load for filtering
                        ->select(['id', 'title', 'thumb_image', 'old_price', 'offer', 'product_type', 'category_id'])
                        ->withProductCardData()
                        ->get();
                    
                    // Group by primary category_id for display, but include products from additional categories
                    $groupedProducts = collect();
                    foreach ($categoryIds as $catId) {
                        $catProducts = $allProducts->filter(function($p) use ($catId) {
                            return $p->category_id == $catId 
                                || ($p->additionalCategories && $p->additionalCategories->pluck('id')->contains($catId));
                        });
                        $groupedProducts[$catId] = $catProducts;
                    }

                    foreach ($categoryOrder as $itemKey) {
                        if (str_starts_with($itemKey, 'category-')) {
                            $catId = (int)str_replace('category-', '', $itemKey);
                            $category = $categories->get($catId);
                            
                            if ($category) {
                                $products = $groupedProducts->get($catId, collect())->take($productsPerCategory);
                                $SingleProductSliderCategories[] = [
                                    'category' => $category,
                                    'products' => $products,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return view('frontend.singelProduct', array_merge(compact(
            'product',
            'related_products',
            'additional_related_products',
            'reviews',
            'reviewStats',
            'SingleProductSliderCategories',
            'variationCombinationsData'
        ), $seoData));
    }

    /**
     * Build category slider data efficiently with batch queries
     */
    private function buildCategorySlider($categoryIds, $productsPerCategory)
    {
        if (empty($categoryIds)) {
            return [];
        }

        // Batch load categories and products (includes products from additional categories)
        $categories = ProductCategory::whereIn('id', $categoryIds)->get()->keyBy('id');
        $allProducts = Product::where('status', 1)
            ->where(function($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds)
                  ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                      $subQ->whereIn('category_id', $categoryIds);
                  });
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->with('additionalCategories') // Eager load for filtering
            ->select(['id', 'title', 'thumb_image', 'old_price', 'offer', 'product_type', 'category_id'])
            ->withProductCardData()
            ->get();
        
        // Group by primary category_id for display, but include products from additional categories
        $groupedProducts = collect();
        foreach ($categoryIds as $catId) {
            $catProducts = $allProducts->filter(function($p) use ($catId) {
                return $p->category_id == $catId 
                    || ($p->additionalCategories && $p->additionalCategories->pluck('id')->contains($catId));
            });
            $groupedProducts[$catId] = $catProducts;
        }

        $sliderCategories = [];
        foreach ($categoryIds as $catId) {
            $category = $categories->get($catId);
            if ($category) {
                $products = $groupedProducts->get($catId, collect())->take($productsPerCategory);
                $sliderCategories[] = [
                    'category' => $category,
                    'products' => $products,
                ];
            }
        }

        return $sliderCategories;
    }

    /**
     * Get SEO data for any page
     */
    private function getSeoData($title = null, $description = null, $keywords = null, $image = null, $type = 'website')
    {
        return [
            'metaTitle' => $title ?? SettingsService::getDefaultMetaTitle(),
            'metaDescription' => $description ?? SettingsService::getDefaultMetaDescription(),
            'metaKeywords' => $keywords ?? SettingsService::getDefaultMetaKeywords(),
            'ogImage' => $image ?? SettingsService::getDefaultOgImage(),
            'ogType' => $type,
            'metaRobots' => SettingsService::getRobotsMeta(),
            'canonicalUrl' => url()->current(),
        ];
    }

    /**
     * Get SEO data for products with custom fields support
     */
    private function getProductSeoData($product)
    {
        // Use the formatted SEO data from the model
        $customSeo = $product->formatted_seo;
        
        // Use custom SEO data if available, otherwise fallback to defaults
        $metaTitle = ($customSeo['meta_title'] ?? null) ?? 
            ($product->title . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop'));
            
        $metaDescription = ($customSeo['meta_description'] ?? null) ?? 
            (Str::limit(strip_tags($product->short_description ?: $product->description), 150));
            
        $metaKeywords = ($customSeo['meta_keywords'] ?? null) ?? 
            ($product->title . ', ' . SettingsService::getDefaultMetaKeywords());
            
        $ogImage = ($customSeo['og_image'] ?? null) ?? 
            ($product->thumb_image ? asset('storage/' . $product->thumb_image) : SettingsService::getDefaultOgImage());
            
        $canonicalUrl = $customSeo['canonical_url'] ?? url()->current();
        $metaRobots = $customSeo['meta_robots'] ?? SettingsService::getRobotsMeta();
        $ogTitle = $customSeo['og_title'] ?? $metaTitle;
        $ogDescription = $customSeo['og_description'] ?? $metaDescription;
        $twitterCardType = $customSeo['twitter_card_type'] ?? 'summary_large_image';
        $schemaMarkup = $customSeo['schema_markup'] ?? $this->generateProductSchema($product);

        return [
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaKeywords' => $metaKeywords,
            'ogImage' => $ogImage,
            'ogType' => 'product',
            'metaRobots' => $metaRobots,
            'canonicalUrl' => $canonicalUrl,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'twitterCardType' => $twitterCardType,
            'schemaMarkup' => $schemaMarkup,
        ];
    }

    /**
     * Generate product schema markup
     */
    private function generateProductSchema($product)
    {
        if (!SettingsService::isSchemaEnabled()) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->title,
            'description' => strip_tags($product->short_description ?: $product->description),
            'image' => $product->thumb_image ? asset('storage/' . $product->thumb_image) : null,
            'url' => url()->current(),
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand ?? 'Thikana Shop'
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->offer_price ?: $product->old_price,
                'priceCurrency' => 'BDT',
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => url()->current()
            ]
        ];

        // Add aggregate rating if reviews exist
        if ($product->review_count > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->average_rating,
                'reviewCount' => $product->review_count,
                'bestRating' => 5,
                'worstRating' => 1
            ];
        }

        return json_encode($schema);
    }
}
