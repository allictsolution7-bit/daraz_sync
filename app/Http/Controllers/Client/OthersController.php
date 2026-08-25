<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Banner;
use App\Models\CustomerReview;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Services\WriterService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OthersController extends Controller
{

    public function index()
    {
        // Get all site settings in one query using the optimized SettingsService
        $homepage = SettingsService::group('homepage');
        $siteSettings = SettingsService::group('general');

        // Get featured products with optimized query using new scope
        $featuredProducts = Product::where('status', 1)
            ->where('is_featured', 1)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type'])
            ->withProductCardData() // NEW: Uses optimized query scope
            ->limit(5)
            ->get();

        // Get random products more efficiently using new scope
        $randomProducts = Product::where('status', 1)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type'])
            ->withProductCardData() // NEW: Uses optimized query scope
            ->limit(20)
            ->get();

        // Get latest products for the Latest Products section using new scope
        $initialCount = $homepage['latest_products_initial_count'] ?? 12;
        $totalProducts = Product::where('status', 1)->forPublicDisplay()->count();
        $latestProducts = Product::where('status', 1)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type'])
            ->withProductCardData() // NEW: Uses optimized query scope
            ->orderBy('created_at', 'desc')
            ->limit($initialCount)
            ->get();

        // Combine multiple single queries into batch queries
        $batchData = $this->getBatchData($homepage);

        // Optimize category slider queries by batching all category IDs first
        $allCategoryIds = $this->getAllCategoryIds($homepage);
        $categoryMap = $this->getCategoryMap($allCategoryIds);

        // Homepage Category v2 Location 1 - Get from settings
        $sliderCategories1 = $this->buildCategorySlider(
            $homepage['enable_products_by_category_v2_location1'] ?? '0',
            $homepage['products_by_category_v2_location1_order'] ?? '[]',
            $homepage['products_by_category_v2_location1_products_per_category'] ?? 12,
            $categoryMap
        );

        // Homepage Category v2 Location 2 - Get from settings
        $sliderCategories2 = $this->buildCategorySlider(
            $homepage['enable_products_by_category_v2_location2'] ?? '0',
            $homepage['products_by_category_v2_location2_order'] ?? '[]',
            $homepage['products_by_category_v2_location2_products_per_category'] ?? 12,
            $categoryMap
        );

        // Homepage Category v2 Location 3 - Get from settings
        $sliderCategories3 = $this->buildCategorySlider(
            $homepage['enable_products_by_category_v2_location3'] ?? '0',
            $homepage['products_by_category_v2_location3_order'] ?? '[]',
            $homepage['products_by_category_v2_location3_products_per_category'] ?? 12,
            $categoryMap
        );

        // First set of 3 categories for single product bottom - Get from settings
        $SingleProductSliderCategories = $this->buildCategorySlider(
            SettingsService::get('single_product', 'enable_bottom_category_slider', '0'),
            SettingsService::get('single_product', 'bottom_category_slider_categories_order', '[]'),
            SettingsService::get('single_product', 'bottom_category_slider_products_per_category', 12),
            $categoryMap
        );

        $sliderCategories = ProductCategory::where('status', 1)
            ->orderBy('id', 'asc')
            ->take(30)
            ->get();

        // Mega menu categories for slider (only when needed)
        $sliderLayout = $homepage['slider_layout'] ?? 'category_slider';
        $enableMainSlider = !empty($homepage['enable_main_slider_section']) && $homepage['enable_main_slider_section'];
        $sliderMegaCategories = collect();
        $sliderMegaCategoryFlags = [];
        $sliderMegaCategoryProducts = [];
        if ($enableMainSlider && $sliderLayout === 'category_slider') {
            $sliderMegaCategories = ProductCategory::where('status', 1)
                ->orderBy('id', 'asc')
                ->with(['subCategories' => function ($query) {
                    $query->where('status', 1)
                        ->orderBy('id', 'asc')
                        ->with(['thirdCategories' => function ($subQuery) {
                            $subQuery->active()->ordered();
                        }]);
                }])
                ->take(30)
                ->get();

            // Preload latest products for categories with few sub/third categories
            foreach ($sliderMegaCategories as $cat) {
                $subCount = $cat->subCategories->count();
                $thirdCount = $cat->subCategories->sum(function ($sub) {
                    return $sub->thirdCategories->count();
                });
                $totalChildren = $subCount + $thirdCount;

                $isSmall = $totalChildren < 10;
                $sliderMegaCategoryFlags[$cat->id] = $isSmall;

                if ($isSmall) {
                    // Preload products for small trees to avoid empty UI
                    $subIds = $cat->subCategories->pluck('id')->all();
                    $thirdIds = $cat->subCategories->flatMap(function ($sub) {
                        return $sub->thirdCategories->pluck('id');
                    })->all();

                    $products = Product::where('status', 1)
                        ->forPublicDisplay()
                        ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type', 'sub_category_id'])
                        ->withProductCardData()
                        ->where(function ($q) use ($cat, $subIds, $thirdIds) {
                            $q->inCategory($cat->id);

                            if (!empty($subIds)) {
                                $q->orWhere(function ($subQ) use ($subIds) {
                                    $subQ->whereIn('sub_category_id', $subIds)
                                        ->orWhereHas('additionalSubCategories', function ($addSubQ) use ($subIds) {
                                            $addSubQ->whereIn('sub_categories.id', $subIds);
                                        });
                                });
                            }

                            if (!empty($thirdIds)) {
                                $q->orWhereHas('thirdCategories', function ($thirdQ) use ($thirdIds) {
                                    $thirdQ->whereIn('third_categories.id', $thirdIds);
                                });
                            }
                        })
                        ->orderBy('created_at', 'desc')
                        ->limit(6)
                        ->get();

                    $catProductGroups = [
                        'all' => $products,
                    ];

                    foreach ($cat->subCategories as $sub) {
                        $subProducts = Product::where('status', 1)
                            ->forPublicDisplay()
                            ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type', 'sub_category_id'])
                            ->withProductCardData()
                            ->where(function ($q) use ($sub) {
                                $q->where('sub_category_id', $sub->id)
                                    ->orWhereHas('additionalSubCategories', function ($addSubQ) use ($sub) {
                                        $addSubQ->where('sub_categories.id', $sub->id);
                                    });
                            })
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();

                        $catProductGroups[$sub->id] = $subProducts;
                    }

                    $sliderMegaCategoryProducts[$cat->id] = $catProductGroups;
                }
            }
        }

        $writers = (new WriterService)->getBestWriters();

        $publishers = \App\Models\Publisher::select(['id', 'name', 'logo'])
            ->take(10)
            ->get();

        // Set SEO meta data with optimized settings service
        $metaTitle = SettingsService::getDefaultMetaTitle();
        $metaDescription = SettingsService::getDefaultMetaDescription();
        $metaKeywords = SettingsService::getDefaultMetaKeywords();
        $ogImage = SettingsService::getDefaultOgImage();
        $ogType = 'website';

        return view('frontend.index', array_merge([
            'products' => $randomProducts,
            'features_products' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'totalProducts' => $totalProducts,
            'metaTitle' => $metaTitle,
            'metaDescription' => $metaDescription,
            'metaKeywords' => $metaKeywords,
            'ogImage' => $ogImage,
            'ogType' => $ogType,
            'homepage' => $homepage,
            'sliderCategories1' => $sliderCategories1,
            'sliderCategories2' => $sliderCategories2,
            'sliderCategories3' => $sliderCategories3,
            'SingleProductSliderCategories' => $SingleProductSliderCategories,
            'sliderCategories' => $sliderCategories,
            'sliderMegaCategories' => $sliderMegaCategories,
            'sliderMegaCategoryFlags' => $sliderMegaCategoryFlags,
            'sliderMegaCategoryProducts' => $sliderMegaCategoryProducts,
            'writers' => $writers,
            'publishers' => $publishers,
        ], $batchData));
    }

    /**
     * Get all category IDs from homepage settings to batch query them
     */
    private function getAllCategoryIds($homepage)
    {
        $categoryIds = [];

        // Extract from featured and products by category orders
        $featuredOrder = json_decode($homepage['featured_category_order'] ?? '[]', true);
        $productsByCategoryOrder = json_decode($homepage['products_by_category_order'] ?? '[]', true);

        foreach (array_merge($featuredOrder, $productsByCategoryOrder) as $item) {
            if (str_starts_with($item, 'category-')) {
                $categoryIds[] = (int)str_replace('category-', '', $item);
            }
        }

        // Extract from category slider locations
        $locations = [
            'products_by_category_v2_location1_order',
            'products_by_category_v2_location2_order',
            'products_by_category_v2_location3_order'
        ];

        foreach ($locations as $location) {
            $order = json_decode($homepage[$location] ?? '[]', true);
            foreach ($order as $item) {
                if (str_starts_with($item, 'category-')) {
                    $categoryIds[] = (int)str_replace('category-', '', $item);
                }
            }
        }

        // Extract from single product bottom category slider
        $bottomOrder = json_decode(SettingsService::get('single_product', 'bottom_category_slider_categories_order', '[]'), true);
        foreach ($bottomOrder as $item) {
            if (str_starts_with($item, 'category-')) {
                $categoryIds[] = (int)str_replace('category-', '', $item);
            }
        }

        return array_unique($categoryIds);
    }

    /**
     * Get category map for efficient lookups
     */
    private function getCategoryMap($categoryIds)
    {
        if (empty($categoryIds)) {
            return collect();
        }

        return ProductCategory::whereIn('id', $categoryIds)
            ->get()
            ->keyBy('id');
    }

    /**
     * Build category slider data efficiently
     */
    private function buildCategorySlider($enabled, $orderJson, $productsPerCategory, $categoryMap)
    {
        if ($enabled != '1') {
            return [];
        }

        $order = json_decode($orderJson, true);
        if (empty($order)) {
            return [];
        }

        $sliderCategories = [];
        $categoryIds = [];

        // Extract category IDs first
        foreach ($order as $itemKey) {
            if (str_starts_with($itemKey, 'category-')) {
                $catId = (int)str_replace('category-', '', $itemKey);
                $categoryIds[] = $catId;
            }
        }

        // Batch load products for all categories (includes products from additional categories)
        $allProducts = Product::where('status', 1)
            ->where(function($q) use ($categoryIds) {
                $q->whereIn('category_id', $categoryIds)
                  ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                      $subQ->whereIn('product_categories.id', $categoryIds);
                  });
            })
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->with('additionalCategories') // Eager load for grouping
            ->select(['id', 'title', 'thumb_image', 'old_price', 'offer', 'product_type', 'category_id'])
            ->with([
                'variations:id,product_id', 
                'variations.options:id,variation_id,name,price',
                'variationCombinations:id,product_id,regular_price,offer_price'
            ])
            ->withCount([
                'activeReviews as review_count'
            ])
            ->withAvg([
                'activeReviews as average_rating'
            ], 'rating')
            ->get();
        
        // Group by category - include products from additional categories
        $groupedProducts = collect();
        foreach ($categoryIds as $catId) {
            $catProducts = $allProducts->filter(function($p) use ($catId) {
                return $p->category_id == $catId 
                    || ($p->additionalCategories && $p->additionalCategories->pluck('id')->contains($catId));
            });
            $groupedProducts[$catId] = $catProducts;
        }

        // Build slider data
        foreach ($order as $itemKey) {
            if (str_starts_with($itemKey, 'category-')) {
                $catId = (int)str_replace('category-', '', $itemKey);
                $category = $categoryMap->get($catId);
                
                if ($category) {
                    $products = $groupedProducts->get($catId, collect())->take($productsPerCategory);
                    $sliderCategories[] = [
                        'category' => $category,
                        'products' => $products,
                    ];
                }
            }
        }

        return $sliderCategories;
    }

    /**
     * Get batch data to reduce individual database queries
     */
    private function getBatchData($homepage)
    {
        // Get all required IDs from homepage settings
        $featuredOrder = json_decode($homepage['featured_category_order'] ?? '[]', true);
        $productsByCategoryOrder = json_decode($homepage['products_by_category_order'] ?? '[]', true);

        // Extract category and subcategory IDs
        $categoryIds = [];
        $subcategoryIds = [];
        $productIds = [];

        foreach (array_merge($featuredOrder, $productsByCategoryOrder) as $item) {
            if (str_starts_with($item, 'category-')) {
                $categoryIds[] = (int)str_replace('category-', '', $item);
            } elseif (str_starts_with($item, 'subcategory-')) {
                $subcategoryIds[] = (int)str_replace('subcategory-', '', $item);
            }
        }

        // Get featured sections product IDs
        $sections = ['best_selling', 'editors_pick', 'trending'];
        foreach ($sections as $section) {
            $order = json_decode($homepage[$section . '_products_order'] ?? '[]', true);
            $productIds = array_merge($productIds, $order);
        }

        // Batch fetch all required data
        $categories = ProductCategory::whereIn('id', $categoryIds)
            ->orWhereNotNull('image')
            ->get()
            ->keyBy('id');

        $subcategories = SubCategory::whereIn('id', $subcategoryIds)
            ->with('product_category')
            ->get()
            ->keyBy('id');

        $products = Product::whereIn('id', $productIds)
            ->where('status', 1)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->with([
                'variations:id,product_id', 
                'variations.options:id,variation_id,name,price',
                'variationCombinations:id,product_id,regular_price,offer_price'
            ])
            ->withCount([
                'activeReviews as review_count'
            ])
            ->withAvg([
                'activeReviews as average_rating'
            ], 'rating')
            ->get()
            ->keyBy('id');

        // Get T-shirt category and products in one optimized query
        $tshirtCategory = ProductCategory::where('name', 'like', '%T-shirt%')->first();
        $tshirtProducts = [];
        if ($tshirtCategory) {
            $tshirtProducts = Product::where('status', 1)
                ->where('category_id', $tshirtCategory->id)
                ->forPublicDisplay() // Only show approved vendor products or admin products
                ->select(['id', 'title', 'thumb_image', 'offer', 'product_type'])
                ->with([
                    'variations:id,product_id', 
                    'variations.options:id,variation_id,name',
                    'variationCombinations:id,product_id,offer_price,regular_price'
                ])
                ->limit(4)
                ->get();
        }

        // Get other required data
        $activities = Activities::all();
        $sliders = Slider::where('status', '1')
            ->orderBy('position', 'asc')
            ->get();

        if ($sliders->isEmpty()) {
            try {
                $siteName = SettingsService::get('general', 'site_name', 'Our Store');
                Slider::create([
                    'title' => 'Welcome to ' . $siteName,
                    'description' => 'Discover our exclusive range of high-quality products designed for your comfort and style.',
                    'button_text' => 'Shop Now',
                    'button_url' => '/shop',
                    'image' => 'sliders/vVV0cwK97XSfpTwKjDFLWK47JN1ug2JCzrVnnJeE.webp',
                    'overlay_color' => 'rgba(0, 0, 0, 0.4)',
                    'position' => 1,
                    'status' => 1,
                ]);
                Slider::create([
                    'title' => 'Trending Collections',
                    'description' => 'Explore the latest arrivals and premium selections tailored just for you.',
                    'button_text' => 'Explore More',
                    'button_url' => '/shop',
                    'image' => 'sliders/D3t6TPKxOuda0FQZDc1aaOxqOOG0jTKd4i58m5bS.webp',
                    'overlay_color' => 'rgba(0, 0, 0, 0.35)',
                    'position' => 2,
                    'status' => 1,
                ]);
                $sliders = Slider::where('status', '1')->orderBy('position', 'asc')->get();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Could not auto-seed sliders: " . $e->getMessage());
            }
        }

        $customerReviews = CustomerReview::where('is_active', true)
            ->orderBy('review_date', 'desc')
            ->limit(6)
            ->get();

        if ($customerReviews->isEmpty()) {
            try {
                CustomerReview::create([
                    'reviewer_name' => 'Rafiqul Islam',
                    'reviewer_email' => 'rafiq@example.com',
                    'reviewer_image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                    'review_date' => now()->subDays(5)->toDateString(),
                    'rating' => 5,
                    'product_name' => 'Premium Collection',
                    'product_image' => 'https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'review_text' => 'The quality of the product is exceptional. The fit is perfect and the design is elegant. Highly recommended!',
                    'is_active' => 1,
                    'is_verified_purchase' => 1,
                ]);
                CustomerReview::create([
                    'reviewer_name' => 'Tanvir Ahmed',
                    'reviewer_email' => 'tanvir@example.com',
                    'reviewer_image' => 'https://randomuser.me/api/portraits/men/44.jpg',
                    'review_date' => now()->subDays(2)->toDateString(),
                    'rating' => 5,
                    'product_name' => 'Exclusive Collection',
                    'product_image' => 'https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
                    'review_text' => 'Fast delivery and very good packaging. Product matches the description exactly.',
                    'is_active' => 1,
                    'is_verified_purchase' => 1,
                ]);
                $customerReviews = CustomerReview::where('is_active', true)->orderBy('review_date', 'desc')->limit(6)->get();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning("Could not auto-seed reviews: " . $e->getMessage());
            }
        }

        // Build featured items efficiently
        $featuredItems = collect($featuredOrder)->map(function ($item) use ($categories, $subcategories) {
            if (str_starts_with($item, 'category-')) {
                $id = (int)str_replace('category-', '', $item);
                $cat = $categories->get($id);
                if ($cat) {
                    $cat->type = 'category';
                    return $cat;
                }
            } elseif (str_starts_with($item, 'subcategory-')) {
                $id = (int)str_replace('subcategory-', '', $item);
                $sub = $subcategories->get($id);
                if ($sub) {
                    $sub->type = 'subcategory';
                    return $sub;
                }
            }
            return null;
        })->filter();

        $productsByCategoryItems = collect($productsByCategoryOrder)->map(function ($item) use ($categories, $subcategories) {
            if (str_starts_with($item, 'category-')) {
                $id = (int)str_replace('category-', '', $item);
                $cat = $categories->get($id);
                if ($cat) {
                    $cat->type = 'category';
                    return $cat;
                }
            } elseif (str_starts_with($item, 'subcategory-')) {
                $id = (int)str_replace('subcategory-', '', $item);
                $sub = $subcategories->get($id);
                if ($sub) {
                    $sub->type = 'subcategory';
                    return $sub;
                }
            }
            return null;
        })->filter();

        // Build featured sections efficiently
        $featuredSections = [];
        foreach ($sections as $section) {
            $order = json_decode($homepage[$section . '_products_order'] ?? '[]', true);
            $featuredSections[$section] = collect($order)->map(function ($id) use ($products) {
                return $products->get($id);
            })->filter();
        }

        return [
            'activities' => $activities,
            'categories' => $categories->where('image', '!=', null)->take(8),
            'tshirt' => $tshirtProducts,
            'sliders' => $sliders,
            'customerReviews' => $customerReviews,
            'featuredItems' => $featuredItems,
            'productsByCategoryItems' => $productsByCategoryItems,
            'featuredSections' => $featuredSections,
        ];
    }

    public function shop(Request $request, $category = null, $sub_category = null, $third_category = null)
    {
        $categories = ProductCategory::with("subCategory")->get();
        $products = Product::where("status", 1)
            ->forPublicDisplay(); // Only show approved vendor products or admin products
        $globalBgImage = SettingsService::get('homepage', 'global_category_bg');
        $data = [];
        
        // Filter by specific product IDs if provided
        if ($request->has('products') && $request->get('products')) {
            $productIds = is_array($request->get('products')) ? $request->get('products') : explode(',', $request->get('products'));
            $productIds = array_filter(array_map('intval', $productIds));
            if (!empty($productIds)) {
                $products = $products->whereIn('id', $productIds);
            }
        }
        
        // Initialize category and sub_category objects
        $data["category_obj"] = null;
        $data["sub_category_obj"] = null;
        $data["third_category_obj"] = null;
        $data["sub_categories"] = collect();
        
        // Load all subcategories if no category filter is applied (for main shop page)
        if (empty($category) && !$request->has('category')) {
            $data["sub_categories"] = SubCategory::select("id", "name", "slug", "product_category_id")
                ->where("status", true)
                ->orderBy("name")
                ->get();
        }
        
        //apply filter
        if ($category) {
            $categorySlugs = is_array($category) ? $category : [$category];
            $selectedCategories = ProductCategory::whereIn("slug", $categorySlugs)->get();
            
            if ($selectedCategories->count() > 0) {
                $data["category_obj"] = $selectedCategories->first(); // For backward compatibility
                $data["selected_categories"] = $selectedCategories;
                
                // Get sub-categories for all selected categories
                $categoryIds = $selectedCategories->pluck('id');
                $sub_categories = SubCategory::select("name", "slug")->whereIn("product_category_id", $categoryIds)->get();
                $data["sub_categories"] = $sub_categories;
                
                // NEW: Check both primary category_id AND additional categories via pivot table
                // ALSO check if product is in any subcategory of the selected categories (Implicit membership)
                $relatedSubCategoryIds = SubCategory::whereIn('product_category_id', $categoryIds)->pluck('id');
                
                $products = $products->where(function($q) use ($categoryIds, $relatedSubCategoryIds) {
                    $q->whereIn("category_id", $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('product_categories.id', $categoryIds);
                      });
                      
                    if ($relatedSubCategoryIds->count() > 0) {
                        $q->orWhereIn("sub_category_id", $relatedSubCategoryIds)
                          ->orWhereHas('additionalSubCategories', function($subQ) use ($relatedSubCategoryIds) {
                              $subQ->whereIn('sub_categories.id', $relatedSubCategoryIds);
                          });
                    }
                });
            }
        }
        
        // Handle category filter from query parameter (for search functionality)
        if ($request->has('category') && $request->get('category')) {
            $categoryId = $request->get('category');
            $selectedCategory = ProductCategory::find($categoryId);
            
            if ($selectedCategory) {
                $data["category_obj"] = $selectedCategory;
                $data["selected_categories"] = collect([$selectedCategory]);
                
                // Get sub-categories for the selected category
                $sub_categories = SubCategory::select("name", "slug")->where("product_category_id", $categoryId)->get();
                $data["sub_categories"] = $sub_categories;
                
                // NEW: Check both primary category_id AND additional categories via pivot table
                // ALSO check if product is in any subcategory of the selected category
                $relatedSubCategoryIds = SubCategory::where('product_category_id', $categoryId)->pluck('id');
                
                $products = $products->where(function($q) use ($categoryId, $relatedSubCategoryIds) {
                    $q->where("category_id", $categoryId)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryId) {
                          $subQ->where('product_categories.id', $categoryId);
                      });
                      
                    if ($relatedSubCategoryIds->count() > 0) {
                        $q->orWhereIn("sub_category_id", $relatedSubCategoryIds)
                          ->orWhereHas('additionalSubCategories', function($subQ) use ($relatedSubCategoryIds) {
                              $subQ->whereIn('sub_categories.id', $relatedSubCategoryIds);
                          });
                    }
                });
            }
        }
        //apply sub category filter
        if (!empty($sub_category)) {
            $sub_cat = SubCategory::where("slug", $sub_category)->first();
            if ($sub_cat) {
                $data["sub_category_obj"] = $sub_cat;
                
                // NEW: Check both primary sub_category_id AND additional subcategories via pivot table
                // ALSO check if product is in any third category of the selected subcategory
                $relatedThirdCategoryIds = \App\Models\ThirdCategory::where('sub_category_id', $sub_cat->id)->pluck('id');
                
                $products = $products->where(function($q) use ($sub_cat, $relatedThirdCategoryIds) {
                    $q->where("sub_category_id", $sub_cat->id)
                      ->orWhereHas('additionalSubCategories', function($subQ) use ($sub_cat) {
                          $subQ->where('sub_categories.id', $sub_cat->id);
                      });
                      
                    if ($relatedThirdCategoryIds->count() > 0) {
                        $q->orWhereHas('thirdCategories', function($subQ) use ($relatedThirdCategoryIds) {
                            $subQ->whereIn('third_categories.id', $relatedThirdCategoryIds);
                        });
                    }
                });
            }
        }
        
        // NEW: Third category filter from URL path
        if (!empty($third_category)) {
            // Validate that third category belongs to the subcategory in URL
            $third_cat = null;
            if (!empty($sub_category) && $data["sub_category_obj"]) {
                // Verify third category belongs to the subcategory
                $third_cat = \App\Models\ThirdCategory::where("slug", $third_category)
                    ->where("sub_category_id", $data["sub_category_obj"]->id)
                    ->with(['subCategory.category'])
                    ->first();
            } else {
                // If no subcategory in URL, just find by slug
                $third_cat = \App\Models\ThirdCategory::where("slug", $third_category)
                    ->with(['subCategory.category'])
                    ->first();
            }
            
            if ($third_cat) {
                $data["third_category_obj"] = $third_cat;
                
                // Ensure subcategory object is set if not already
                if (!$data["sub_category_obj"] && $third_cat->subCategory) {
                    $data["sub_category_obj"] = $third_cat->subCategory;
                    
                    // Also set category object if not set
                    if (!$data["category_obj"] && $third_cat->subCategory->category) {
                        $data["category_obj"] = $third_cat->subCategory->category;
                    }
                }
                
                // Filter products by third category
                $products = $products->whereHas('thirdCategories', function($q) use ($third_cat) {
                    $q->where('third_categories.id', $third_cat->id);
                });
            }
        }
        
        // NEW: Third category filter from query parameter
        if ($request->has('third_category') && $request->get('third_category')) {
            $thirdCategorySlugs = is_array($request->get('third_category')) 
                ? $request->get('third_category') 
                : [$request->get('third_category')];
                
            if (!empty($thirdCategorySlugs)) {
                $thirdCategoryIds = \App\Models\ThirdCategory::whereIn("slug", $thirdCategorySlugs)->pluck('id');
                
                if ($thirdCategoryIds->count() > 0) {
                    $products = $products->whereHas('thirdCategories', function($q) use ($thirdCategoryIds) {
                        $q->whereIn('third_categories.id', $thirdCategoryIds);
                    });
                }
            }
        }
        // Filter by writer
        if ($request->has('writer')) {
            $writerIds = is_array($request->get('writer')) ? $request->get('writer') : [$request->get('writer')];
            if (!empty($writerIds)) {
                $products = $products->whereHas('book.writers', function($q) use ($writerIds) {
                    $q->whereIn('writers.id', $writerIds);
                });
            }
        }
        // Filter by publisher
        if ($request->has('publisher')) {
            $publisherIds = is_array($request->get('publisher')) ? $request->get('publisher') : [$request->get('publisher')];
            if (!empty($publisherIds)) {
                $products = $products->whereHas('book', function($q) use ($publisherIds) {
                    $q->whereIn('publisher_id', $publisherIds);
                });
            }
        }
        // Filter by rating
        if ($request->has('rating') && $request->get('rating')) {
            $ratings = is_array($request->get('rating')) ? $request->get('rating') : [$request->get('rating')];
            if (!empty($ratings)) {
                $products = $products->where(function($query) use ($ratings) {
                    foreach ($ratings as $rating) {
                        $query->orWhereHas('reviews', function($q) use ($rating) {
                            $q->where('is_active', true)
                              ->where('rating', '>=', $rating);
                        });
                    }
                });
            }
        }
        
        // Filter by brand
        if ($request->has('brand') && $request->get('brand')) {
            $brandIds = is_array($request->get('brand')) ? $request->get('brand') : [$request->get('brand')];
            if (!empty($brandIds)) {
                $products = $products->whereIn('brand_id', $brandIds);
            }
        }
        
        // Filter by search query
        if ($request->has('search') && $request->get('search')) {
            $searchQuery = $request->get('search');
            $products = $products->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('short_description', 'like', "%{$searchQuery}%")
                  ->orWhere('tags', 'like', "%{$searchQuery}%");
            });
        }
        
        $data["price_min"] = 0;
        $data["price_max"] = 1000;
        if ($request->get("price_min") !== '' && $request->get("price_max") !== '' && $request->has("price_min") && $request->has("price_max")) {
            $data["price_min"] = $request->get("price_min");
            $data["price_max"] = $request->get("price_max");
            if ($request->get("price_max") === 1000) {
                $products = $products->whereBetween("old_price", [
                    $request->get("price_min"),
                    100000
                ]);
            } else {
                $products = $products->whereBetween("old_price", [
                    intval($request->get("price_min")),
                    intval($request->get("price_max"))
                ]);
            }
        }
        //sorting
        $data["sort_value"] = "";
        if ($request->has("sort")) {
            if ($request->get("sort") === "price_desc") {
                $products = $products->orderBy("old_price", "DESC");
            } elseif ($request->get("sort") === "price_asc") {
                $products = $products->orderBy("old_price", "asc");
            } else {
                $products = $products->orderBy("updated_at", "DESC");
            }
            $data["sort_value"] = $request->get("sort");
        }
        // Eager load all relationships including additional categories and activeReviews for filtering
        $products = $products->withProductCardData()
            ->with(['additionalCategories', 'additionalSubCategories', 'thirdCategories', 'activeReviews:id,product_id,rating'])
            ->get();
        $data["products"] = $products;
        $data["categories"] = $categories;
        $data["selected_category"] = $category;
        $data["selected_sub_category"] = $sub_category;
        $data["selected_third_category"] = $third_category;
        $data["globalBgImage"] = $globalBgImage;
        // Also fetch all writers and publishers for the sidebar
        $data['writers'] = \App\Models\Writer::all();
        $data['publishers'] = \App\Models\Publisher::all();
        $data['brands'] = Brand::where('status', true)->get();

        // Calculate filter counts for better UX
        $data['filter_counts'] = [
            'total_products' => $products->count(),
            'categories' => [],
            'writers' => [],
            'publishers' => [],
            'brands' => [],
            'sub_categories' => [],
            'third_categories' => [],
            'ratings' => []
        ];

        // Calculate category counts (includes both primary and additional)
        foreach ($data['categories'] as $category) {
            $data['filter_counts']['categories'][$category->id] = $products->filter(function($product) use ($category) {
                return $product->category_id == $category->id 
                    || ($product->relationLoaded('additionalCategories') && $product->additionalCategories->pluck('id')->contains($category->id));
            })->count();
        }

        // Calculate writer counts
        foreach ($data['writers'] as $writer) {
            $data['filter_counts']['writers'][$writer->id] = $products->filter(function($product) use ($writer) {
                return $product->book && $product->book->writers->contains($writer->id);
            })->count();
        }

        // Calculate publisher counts
        foreach ($data['publishers'] as $publisher) {
            $data['filter_counts']['publishers'][$publisher->id] = $products->filter(function($product) use ($publisher) {
                return $product->book && $product->book->publisher_id == $publisher->id;
            })->count();
        }
        
        // Calculate brand counts
        foreach ($data['brands'] as $brand) {
            $data['filter_counts']['brands'][$brand->id] = $products->filter(function($product) use ($brand) {
                return $product->brand_id == $brand->id;
            })->count();
        }

        // Calculate sub-category counts (includes both primary and additional)
        if (isset($data['sub_categories']) && $data['sub_categories']->count() > 0) {
            foreach ($data['sub_categories'] as $sub_category) {
                $data['filter_counts']['sub_categories'][$sub_category->slug] = $products->filter(function($product) use ($sub_category) {
                    return $product->sub_category_id == $sub_category->id 
                        || ($product->relationLoaded('additionalSubCategories') && $product->additionalSubCategories->pluck('id')->contains($sub_category->id));
                })->count();
            }
        } elseif (empty($category) && !$request->has('category')) {
            // If on main shop page without category filter, calculate counts for all subcategories
            $allSubCategories = SubCategory::select("id", "name", "slug")
                ->where("status", true)
                ->orderBy("name")
                ->get();
            
            foreach ($allSubCategories as $sub_category) {
                $data['filter_counts']['sub_categories'][$sub_category->slug] = $products->filter(function($product) use ($sub_category) {
                    return $product->sub_category_id == $sub_category->id 
                        || ($product->relationLoaded('additionalSubCategories') && $product->additionalSubCategories->pluck('id')->contains($sub_category->id));
                })->count();
            }
        }
        
        // NEW: Get third categories for filter sidebar
        $data['third_categories'] = collect();
        if (isset($data['third_category_obj']) && $data['third_category_obj']) {
            // If a third category is selected, show all third categories from the same subcategory
            $data['third_categories'] = \App\Models\ThirdCategory::where('sub_category_id', $data['third_category_obj']->sub_category_id)
                ->where('status', true)
                ->ordered()
                ->get();
        } elseif (isset($data['sub_category_obj']) && $data['sub_category_obj']) {
            $data['third_categories'] = \App\Models\ThirdCategory::where('sub_category_id', $data['sub_category_obj']->id)
                ->where('status', true)
                ->ordered()
                ->get();
        } elseif (isset($data['sub_categories']) && $data['sub_categories']->count() > 0) {
            $subCategoryIds = $data['sub_categories']->pluck('id');
            $data['third_categories'] = \App\Models\ThirdCategory::whereIn('sub_category_id', $subCategoryIds)
                ->where('status', true)
                ->ordered()
                ->get();
        }
        
        // NEW: Calculate third category counts
        $data['filter_counts']['third_categories'] = [];
        if ($data['third_categories']->count() > 0) {
            foreach ($data['third_categories'] as $third_category) {
                $data['filter_counts']['third_categories'][$third_category->slug] = $products->filter(function($product) use ($third_category) {
                    return $product->relationLoaded('thirdCategories') && $product->thirdCategories->pluck('id')->contains($third_category->id);
                })->count();
            }
        }

        // Calculate rating counts
        for ($rating = 1; $rating <= 5; $rating++) {
            $data['filter_counts']['ratings'][$rating] = $products->filter(function($product) use ($rating) {
                return $product->activeReviews->contains(function($review) use ($rating) {
                    return $review->rating >= $rating;
                });
            })->count();
        }

        // Add search query to data for display
        $data["search_query"] = $request->get('search');
        
        // Add SEO data
        $seoTitle = null;
        $seoDescription = null;
        $seoKeywords = null;

        // Handle search query SEO
        if ($request->has('search') && $request->get('search')) {
            $searchQuery = $request->get('search');
            if ($data["category_obj"]) {
                // Search within specific category
                $seoTitle = 'Search Results for "' . $searchQuery . '" in ' . $data["category_obj"]->name . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
                $seoDescription = 'Search results for "' . $searchQuery . '" in ' . $data["category_obj"]->name . ' category at ' . SettingsService::get('general', 'site_name', 'Thikana Shop') . '. Find the best deals and latest products.';
                $seoKeywords = $searchQuery . ', ' . $data["category_obj"]->name . ', search, ' . SettingsService::getDefaultMetaKeywords();
            } else {
                // General search
                $seoTitle = 'Search Results for "' . $searchQuery . '" - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
                $seoDescription = 'Search results for "' . $searchQuery . '" at ' . SettingsService::get('general', 'site_name', 'Thikana Shop') . '. Find the best deals and latest products.';
                $seoKeywords = $searchQuery . ', search, ' . SettingsService::getDefaultMetaKeywords();
            }
        } elseif ($category && $data["category_obj"]) {
            $seoTitle = $data["category_obj"]->name . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
            $seoDescription = 'Shop ' . $data["category_obj"]->name . ' products at ' . SettingsService::get('general', 'site_name', 'Thikana Shop') . '. Find the best deals and latest trends.';
            $seoKeywords = $data["category_obj"]->name . ', ' . SettingsService::getDefaultMetaKeywords();
        }

        if ($third_category && $data["third_category_obj"]) {
            $seoTitle = $data["third_category_obj"]->name . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
            $seoDescription = 'Shop ' . $data["third_category_obj"]->name . ' products at ' . SettingsService::get('general', 'site_name', 'Thikana Shop') . '. Find the best deals and latest trends.';
            $seoKeywords = $data["third_category_obj"]->name . ', ' . SettingsService::getDefaultMetaKeywords();
        } elseif ($sub_category && $data["sub_category_obj"]) {
            $seoTitle = $data["sub_category_obj"]->name . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
            $seoDescription = 'Shop ' . $data["sub_category_obj"]->name . ' products at ' . SettingsService::get('general', 'site_name', 'Thikana Shop') . '. Find the best deals and latest trends.';
            $seoKeywords = $data["sub_category_obj"]->name . ', ' . SettingsService::getDefaultMetaKeywords();
        }

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords);
        $data = array_merge($data, $seoData);

        return view("frontend.shop", $data);
    }

    public function shopFilter(Request $request)
    {
        $products = Product::where("status", 1)
            ->forPublicDisplay(); // Only show approved vendor products or admin products
        $category = $request->get('category');
        $sub_category = $request->get('sub_category');
        
        // Apply category filter (checks both primary AND additional categories)
        if ($category && $category !== '') {
            $categorySlugs = is_array($category) ? $category : [$category];
            if (!empty($categorySlugs)) {
                $categoryIds = ProductCategory::whereIn("slug", $categorySlugs)->pluck('id');
                if ($categoryIds->count() > 0) {
                // Get related subcategories for implicit filtering
                $relatedSubCategoryIds = SubCategory::whereIn('product_category_id', $categoryIds)->pluck('id');
                
                $products = $products->where(function($q) use ($categoryIds, $relatedSubCategoryIds) {
                    $q->whereIn("category_id", $categoryIds)
                      ->orWhereHas('additionalCategories', function($subQ) use ($categoryIds) {
                          $subQ->whereIn('product_categories.id', $categoryIds);
                      });
                      
                    if ($relatedSubCategoryIds->count() > 0) {
                        $q->orWhereIn("sub_category_id", $relatedSubCategoryIds)
                          ->orWhereHas('additionalSubCategories', function($subQ) use ($relatedSubCategoryIds) {
                              $subQ->whereIn('sub_categories.id', $relatedSubCategoryIds);
                          });
                    }
                });
            }
            }
        }
        
        // Apply sub category filter (checks both primary AND additional subcategories)
        if (!empty($sub_category)) {
            $subCategorySlugs = is_array($sub_category) ? $sub_category : [$sub_category];
            if (!empty($subCategorySlugs)) {
                $subCategoryIds = SubCategory::whereIn("slug", $subCategorySlugs)->pluck('id');
                if ($subCategoryIds->count() > 0) {
                // Get related third categories for implicit filtering
                $relatedThirdCategoryIds = \App\Models\ThirdCategory::whereIn('sub_category_id', $subCategoryIds)->pluck('id');
                
                $products = $products->where(function($q) use ($subCategoryIds, $relatedThirdCategoryIds) {
                    $q->whereIn("sub_category_id", $subCategoryIds)
                      ->orWhereHas('additionalSubCategories', function($subQ) use ($subCategoryIds) {
                          $subQ->whereIn('sub_categories.id', $subCategoryIds);
                      });
                      
                    if ($relatedThirdCategoryIds->count() > 0) {
                        $q->orWhereHas('thirdCategories', function($subQ) use ($relatedThirdCategoryIds) {
                            $subQ->whereIn('third_categories.id', $relatedThirdCategoryIds);
                        });
                    }
                });
            }
        }
    }
        
        // NEW: Apply third category filter
        if ($request->has('third_category') && $request->get('third_category')) {
            $thirdCategorySlugs = is_array($request->get('third_category')) 
                ? $request->get('third_category') 
                : [$request->get('third_category')];
                
            if (!empty($thirdCategorySlugs)) {
                $thirdCategoryIds = \App\Models\ThirdCategory::whereIn("slug", $thirdCategorySlugs)->pluck('id');
                if ($thirdCategoryIds->count() > 0) {
                    $products = $products->whereHas('thirdCategories', function($q) use ($thirdCategoryIds) {
                        $q->whereIn('third_categories.id', $thirdCategoryIds);
                    });
                }
            }
        }
        
        // Filter by writer
        if ($request->has('writer') && $request->get('writer')) {
            $writerIds = is_array($request->get('writer')) ? $request->get('writer') : [$request->get('writer')];
            if (!empty($writerIds)) {
                $products = $products->whereHas('book.writers', function($q) use ($writerIds) {
                    $q->whereIn('writers.id', $writerIds);
                });
            }
        }
        
        // Filter by publisher
        if ($request->has('publisher') && $request->get('publisher')) {
            $publisherIds = is_array($request->get('publisher')) ? $request->get('publisher') : [$request->get('publisher')];
            if (!empty($publisherIds)) {
                $products = $products->whereHas('book', function($q) use ($publisherIds) {
                    $q->whereIn('publisher_id', $publisherIds);
                });
            }
        }
        
        // Filter by rating
        if ($request->has('rating') && $request->get('rating')) {
            $ratings = is_array($request->get('rating')) ? $request->get('rating') : [$request->get('rating')];
            if (!empty($ratings)) {
                $products = $products->where(function($query) use ($ratings) {
                    foreach ($ratings as $rating) {
                        $query->orWhereHas('reviews', function($q) use ($rating) {
                            $q->where('is_active', true)
                              ->where('rating', '>=', $rating);
                        });
                    }
                });
            }
        }
        
        // Filter by brand
        if ($request->has('brand') && $request->get('brand')) {
            $brandIds = is_array($request->get('brand')) ? $request->get('brand') : [$request->get('brand')];
            if (!empty($brandIds)) {
                $products = $products->whereIn('brand_id', $brandIds);
            }
        }
        
        // Price filter
        if ($request->has("price_min") && $request->has("price_max")) {
            $priceMin = $request->get("price_min");
            $priceMax = $request->get("price_max");
            
            if ($priceMax == 1000) {
                $products = $products->whereBetween("old_price", [$priceMin, 100000]);
            } else {
                $products = $products->whereBetween("old_price", [intval($priceMin), intval($priceMax)]);
            }
        }
        
        // Sorting
        if ($request->has("sort")) {
            if ($request->get("sort") === "price_desc") {
                $products = $products->orderBy("old_price", "DESC");
            } elseif ($request->get("sort") === "price_asc") {
                $products = $products->orderBy("old_price", "asc");
            } else {
                $products = $products->orderBy("updated_at", "DESC");
            }
        } else {
            $products = $products->orderBy("updated_at", "DESC");
        }
        
        // Eager load all relationships including additional categories
        $products = $products->withProductCardData()
            ->with(['additionalCategories', 'additionalSubCategories', 'thirdCategories'])
            ->get();
        
        $html = '';
        if ($products->count() > 0) {
            foreach ($products as $product) {
                $html .= view('frontend.partials.product-item', [
                    'product' => $product,
                    'badge' => 'SHOP',
                ])->render();
            }
        } else {
            $html = '<h2 class="no-products">No products found</h2>';
        }
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $products->count()
        ]);
    }

    /**
     * Load more latest products via AJAX
     */
    public function loadLatestProducts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 12);
        
        // Calculate offset based on initial count from settings
        $initialCount = SettingsService::get('homepage', 'latest_products_initial_count', 12);
        $offset = $initialCount + (($page - 2) * $perPage);

        // Get latest products
        $products = Product::where('status', 1)
            ->forPublicDisplay() // Only show approved vendor products or admin products
            ->withProductCardData()
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $html = '';
        if ($products->count() > 0) {
            foreach ($products as $product) {
                $html .= view('frontend.partials.product-item', [
                    'product' => $product,
                    'badge' => 'NEW',
                ])->render();
            }
        } else {
            $html = '<div class="col-12 text-center"><p class="text-muted">No more products to load</p></div>';
        }

        // Check if there are more products available
        $totalProducts = Product::where('status', 1)->forPublicDisplay()->count();
        $hasMore = ($offset + $perPage) < $totalProducts;

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $products->count(),
            'hasMore' => $hasMore
        ]);
    }

    /**
     * Load mega menu products for a primary category (AJAX)
     */
    public function loadMegaCategoryProducts(Request $request)
    {
        $categoryId = (int) $request->input('category_id');
        if (!$categoryId) {
            return response()->json(['success' => false, 'message' => 'Invalid category'], 400);
        }

        $category = ProductCategory::with(['subCategories.thirdCategories'])->find($categoryId);
        if (!$category || $category->status != 1) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $subIds = $category->subCategories->pluck('id')->all();
        $thirdIds = $category->subCategories->flatMap(function ($sub) {
            return $sub->thirdCategories->pluck('id');
        })->all();

        $products = Product::where('status', 1)
            ->forPublicDisplay()
            ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type'])
            ->withProductCardData()
            ->where(function ($q) use ($category, $subIds, $thirdIds) {
                // Primary and additional categories
                $q->inCategory($category->id);

                // Subcategories (primary + additional)
                if (!empty($subIds)) {
                    $q->orWhere(function ($subQ) use ($subIds) {
                        $subQ->whereIn('sub_category_id', $subIds)
                            ->orWhereHas('additionalSubCategories', function ($addSubQ) use ($subIds) {
                                $addSubQ->whereIn('sub_categories.id', $subIds);
                            });
                    });
                }

                // Third categories
                if (!empty($thirdIds)) {
                    $q->orWhereHas('thirdCategories', function ($thirdQ) use ($thirdIds) {
                        $thirdQ->whereIn('third_categories.id', $thirdIds);
                    });
                }
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Fallback: if no products found in this tree, show latest sitewide to avoid empty UI
        if ($products->isEmpty()) {
            $products = Product::where('status', 1)
                ->forPublicDisplay()
                ->select(['id', 'title', 'slug', 'thumb_image', 'old_price', 'offer', 'product_type'])
                ->withProductCardData()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        $html = view('frontend.partials.mega-products-grid', [
            'products' => $products
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $products->count()
        ]);
    }

    public function page($slug)
    {
        $page = Page::where("status", 1)->where("slug", $slug)->first();
        if (!$page) {
            abort(404);
        }

        // Add SEO data for the page
        $seoTitle = $page->title . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = $page->meta_description ?? substr(strip_tags($page->content), 0, 160);
        $seoKeywords = $page->meta_keywords ?? SettingsService::getDefaultMetaKeywords();
        
        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords);
        
        return view("frontend.page", array_merge(compact("page"), $seoData));
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
    private function getProductSeoData($product, $title = null, $description = null, $keywords = null, $image = null)
    {
        // Use the formatted SEO data from the model
        $customSeo = $product->formatted_seo;
        
        // Use custom SEO data if available, otherwise fallback to defaults
        $metaTitle = $title ?? 
            ($customSeo['meta_title'] ?? null) ?? 
            ($product->title . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop'));
            
        $metaDescription = $description ?? 
            ($customSeo['meta_description'] ?? null) ?? 
            (Str::limit(strip_tags($product->short_description ?: $product->description), 150));
            
        $metaKeywords = $keywords ?? 
            ($customSeo['meta_keywords'] ?? null) ?? 
            ($product->title . ', ' . SettingsService::getDefaultMetaKeywords());
            
        $ogImage = $image ?? 
            ($customSeo['og_image'] ?? null) ?? 
            ($product->thumb_image ? asset('storage/' . $product->thumb_image) : SettingsService::getDefaultOgImage());
            
        $canonicalUrl = $customSeo['canonical_url'] ?? url()->current();
        $metaRobots = $customSeo['meta_robots'] ?? SettingsService::getRobotsMeta();
        $ogTitle = $customSeo['og_title'] ?? $metaTitle;
        $ogDescription = $customSeo['og_description'] ?? $metaDescription;
        $twitterCardType = $customSeo['twitter_card_type'] ?? 'summary_large_image';
        $schemaMarkup = $customSeo['schema_markup'] ?? null;

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
}
