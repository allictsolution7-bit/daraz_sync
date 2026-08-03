<?php

namespace App\Http\Controllers\Admin;

use App\Models\Writer;
use App\Models\Publisher;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Variation;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\VariationOption;
use App\Models\VariationCombination;
use App\Http\Controllers\Controller;
use App\Models\ProductVariationOption;
use Illuminate\Support\Facades\Storage;
use App\Services\StockManagementService;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{

    // Add this property to the class
    protected $stockService;

    // Add this constructor
    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        // For server-side DataTables, view can fetch via AJAX
        $products = Product::forUser()->limit(50)->get(['id', 'title', 'thumb_image', 'old_price']);
        return view('admin.product.index', compact('products'));
    }

    public function data(Request $request)
    {
        $query = Product::query()
            ->forUser()
            ->with([
                'variationCombinations:id,product_id,regular_price,offer_price',
                'category',
                'additionalCategories',
                'additionalSubCategories',
                'thirdCategories'
            ])
            ->select([
                'products.id', 
                'products.title', 
                'products.slug',
                'products.thumb_image', 
                'products.old_price',
                'products.offer',
                'products.status',
                'products.product_type',
                'products.category_id',
                'products.created_at',
                'product_categories.name as category_name',
                'sub_categories.name as sub_category_name',
                'products.views_total',
                'products.views_unique'
            ])
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id')
            ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id');

        return DataTables::eloquent($query)
            ->filter(function ($q) use ($request) {
                $search = $request->input('search.value');
                if (!empty($search)) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('products.title', 'like', "%{$search}%")
                            ->orWhere('products.id', $search)
                            ->orWhere('product_categories.name', 'like', "%{$search}%")
                            ->orWhere('sub_categories.name', 'like', "%{$search}%");
                    });
                }

                // Filter by primary category
                if ($request->filled('primary_category_id')) {
                    $categoryId = $request->primary_category_id;
                    $q->where(function($subQ) use ($categoryId) {
                        $subQ->where('products.category_id', $categoryId)
                             ->orWhereHas('additionalCategories', function($catQ) use ($categoryId) {
                                 $catQ->where('product_categories.id', $categoryId);
                             });
                    });
                }
                
                // Filter by subcategory (primary or additional)
                if ($request->filled('subcategory_id')) {
                    $subCategoryId = $request->subcategory_id;
                    $q->where(function($subQ) use ($subCategoryId) {
                        $subQ->where('products.sub_category_id', $subCategoryId)
                             ->orWhereHas('additionalSubCategories', function($catQ) use ($subCategoryId) {
                                 $catQ->where('sub_categories.id', $subCategoryId);
                             });
                    });
                }
                
                // Filter by third category
                if ($request->filled('third_category_id')) {
                    $thirdCategoryId = $request->third_category_id;
                    $q->whereHas('thirdCategories', function($subQ) use ($thirdCategoryId) {
                        $subQ->where('third_categories.id', $thirdCategoryId);
                    });
                }

                if ($request->filled('status')) {
                    $q->where('products.status', $request->status);
                }

                if ($request->filled('product_type')) {
                    $q->where('products.product_type', $request->product_type);
                }

                if ($request->filled('price_min')) {
                    $q->where('products.old_price', '>=', $request->price_min);
                }

                if ($request->filled('price_max')) {
                    $q->where('products.old_price', '<=', $request->price_max);
                }

                if ($request->filled('date_from')) {
                    $q->whereDate('products.created_at', '>=', $request->date_from);
                }

                if ($request->filled('date_to')) {
                    $q->whereDate('products.created_at', '<=', $request->date_to);
                }
            })
            ->addColumn('checkbox', function($product) {
                return '<input type="checkbox" class="product-checkbox" value="' . $product->id . '">';
            })
            ->addColumn('category_display', function($product) {
                // Collect all categories (primary + additional)
                $allCategories = collect();
                
                // Add primary category if exists
                if ($product->category) {
                    $allCategories->push($product->category);
                }
                
                // Add additional categories from pivot table
                if ($product->additionalCategories && $product->additionalCategories->count() > 0) {
                    $allCategories = $allCategories->merge($product->additionalCategories);
                }
                
                // Remove duplicates by ID
                $allCategories = $allCategories->filter()->unique('id');
                
                // Format display
                if ($allCategories->count() > 0) {
                    $categoryNames = $allCategories->pluck('name')->implode(', ');
                    $subCategory = $product->sub_category_name;
                    
                    return $subCategory ? $categoryNames . ' > ' . $subCategory : $categoryNames;
                }
                
                // Fallback if no categories found
                $category = $product->category_name ?? 'N/A';
                $subCategory = $product->sub_category_name;
                
                return $subCategory ? $category . ' > ' . $subCategory : $category;
            })
            ->addColumn('price_display', function($product) {
                // For variable products, calculate min-max price from variations
                if ($product->product_type === 'variable' && $product->variationCombinations->isNotEmpty()) {
                    $regularPrices = [];
                    $offerPrices = [];

                    foreach ($product->variationCombinations as $combination) {
                        $regular = $combination->regular_price ? (float) $combination->regular_price : null;
                        $offer = $combination->offer_price ? (float) $combination->offer_price : null;

                        if (!is_null($regular) && $regular > 0) {
                            $regularPrices[] = $regular;
                        }

                        if (!is_null($offer) && $offer > 0) {
                            $offerPrices[] = $offer;
                        }
                    }

                    $prices = !empty($offerPrices) ? $offerPrices : $regularPrices;

                    if (!empty($prices)) {
                        $minPrice = min($prices);
                        $maxPrice = max($prices);

                        if (abs($minPrice - $maxPrice) < 0.01) {
                            return number_format($minPrice, 2, '.', '');
                        }

                        return number_format($minPrice, 2, '.', '') . ' - ' . number_format($maxPrice, 2, '.', '');
                    }

                    return null;
                }

                // For simple products, prefer offer price when available
                $basePrice = $product->offer ?? $product->old_price;

                if (!is_null($basePrice)) {
                    return number_format((float) $basePrice, 2, '.', '');
                }

                return null;
            })
            ->addColumn('actions', function($product) {
                return '
                    <a href="' . route('admin.items.edit', $product->id) . '" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="' . route('product.single', ['id' => $product->id, 'slug' => $product->slug ?: \Illuminate\Support\Str::slug($product->title)]) . '" class="btn btn-sm btn-outline-info" title="View in Frontend" target="_blank">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary copy-link-btn" title="Copy Link" data-url="' . route('product.single', ['id' => $product->id, 'slug' => $product->slug ?: \Illuminate\Support\Str::slug($product->title)]) . '">
                        <i class="fas fa-copy"></i>
                    </button>
                    <form action="' . route('admin.items.destroy', $product->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\')">
                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                        <input type="hidden" name="_method" value="DELETE" />
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->addColumn('views', function($product) {
                $total = number_format($product->views_total ?? 0);
                $unique = number_format($product->views_unique ?? 0);
                return $total . ' / ' . $unique;
            })
            ->rawColumns(['checkbox', 'actions'])
            ->toJson();
    }

    public function productCreate()
    {
        // Load categories with subcategories and third categories for tree view
        $categories = ProductCategory::where('status', 'active')
            ->with(['subCategories' => function($query) {
                $query->where('status', 1)
                    ->orderBy('name')
                    ->with(['thirdCategories' => function($q) {
                        $q->where('status', true)->ordered();
                    }]);
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
            
        $sub_categories = SubCategory::where('status', 1)->get([
            'id',
            'name'
        ]);
        $brands = Brand::where('status', true)->get(['id', 'name']);
        $writers = Writer::select('id', 'name')->get();
        $publishers = Publisher::select('id', 'name')->get();

        return view('admin.product.create', compact('categories', 'sub_categories', 'brands', 'writers', 'publishers'));
    }


    public function store(Request $request)
    {
        // return $request;
        // Prepare validation rules based on product type
        $validationRules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'thumb_image' => 'required|mimes:jpeg,png,jpg,gif,bmp,svg,tiff,webp, avif', // no limit
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif,bmp,svg,tiff,webp, avif', // no limit
            'quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0.001',
            'status' => 'required|in:0,1',
            'product_type' => 'required|in:simple,variable,digital,affiliate',
            // Primary category/subcategory (optional for backward compatibility)
            'category_id' => 'nullable|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            // NEW: Validation for additional categories (Hybrid Approach)
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => 'exists:product_categories,id',
            'additional_subcategories' => 'nullable|array',
            'additional_subcategories.*' => 'exists:sub_categories,id',
            'third_categories' => 'nullable|array',
            'third_categories.*' => 'exists:third_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'video_url' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'writers' => 'nullable|array',
            'publisher' => 'nullable|exists:publishers,id',
            // SEO validation rules
            'seo' => 'nullable|array',
            'seo.meta_title' => 'nullable|string|max:60',
            'seo.meta_description' => 'nullable|string|max:160',
            'seo.meta_keywords' => 'nullable|string|max:255',
            'seo.canonical_url' => 'nullable|url',
            // 'seo.meta_robots' => 'nullable|string|in:index,follow,noindex,follow,index,nofollow,noindex,nofollow',
            'seo.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'seo.schema_markup' => 'nullable|string',
        ];

        // Add product type specific validation rules
        if ($request->product_type === 'variable') {
            $variationRules = [
                'old_price' => 'nullable|numeric|min:0',
                'offer' => 'nullable|numeric|min:0',
                'variations' => 'required|array',
                'variations.*.name' => 'required|string|max:255',
                'variations.*.options' => 'required|array',
                'variations.*.options.*.name' => 'required|string|max:255',
                // Validation for combination data
                'combinations' => 'nullable|array',
                'combinations.*.regular_price' => 'nullable|numeric|min:0',
                'combinations.*.offer_price' => 'nullable|numeric|min:0',
                'combinations.*.product_cost' => 'nullable|numeric|min:0',
                'combinations.*.wholesale_price' => 'nullable|numeric|min:0',
                'combinations.*.reseller_price' => 'nullable|numeric|min:0',
                'combinations.*.stock_quantity' => 'nullable|numeric|min:0',
                'combinations.*.short_description' => 'nullable|string|max:1000',
                'combinations.*.featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:15360',
                'combinations.*.gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:15360',
                'combinations.*.key' => 'nullable|string',
            ];
            $validationRules = array_merge($validationRules, $variationRules);
        } elseif ($request->product_type === 'digital') {
            $digitalRules = [
                'old_price' => 'required|numeric|min:0',
                'offer' => 'required|numeric|min:0',
                'digital_file' => 'required|file|max:102400', // 100MB limit for digital files
                'download_limit' => 'nullable|integer|min:0',
            ];
            $validationRules = array_merge($validationRules, $digitalRules);
        } elseif ($request->product_type === 'affiliate') {
            $affiliateRules = [
                'old_price' => 'required|numeric|min:0',
                'offer' => 'required|numeric|min:0',
                'external_url' => 'required|url',
                'affiliate_commission' => 'nullable|numeric|min:0',
            ];
            $validationRules = array_merge($validationRules, $affiliateRules);
        }

        $request->validate($validationRules);

        // Ensure at least one category is selected (either primary or additional)
        if (empty($request->input('category_id')) && 
            empty($request->input('additional_categories'))) {
            return redirect()->back()
                ->withErrors(['category_id' => 'Please select at least one category (primary or additional).'])
                ->withInput();
        }

        ##--Store thumbnail image
        $thumbImage = $request->file('thumb_image');
        $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
        $thumbImage->storeAs('public', $thumbImagePath);
        ##--Store gallery images
        $galleryImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public', $galleryImagePath);
                $galleryImages[] = $galleryImagePath;
            }
        }

        // Handle digital file if product type is digital
        $digitalFilePath = null;
        if ($request->product_type === 'digital' && $request->hasFile('digital_file')) {
            $digitalFile = $request->file('digital_file');
            $digitalFilePath = 'product/digital/' . time() . '-' . $digitalFile->getClientOriginalName();
            $digitalFile->storeAs('public', $digitalFilePath);
        }

        // Create product with only the relevant fields based on product type
        $productData = [
            'title' => $request->input('title'),
            'slug' => $request->input('slug') ?: Str::slug($request->input('title')),
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'short_description' => $request->input('short_description'),
            'product_type' => $request->input('product_type'),
            'thumb_image' => $thumbImagePath,
            'images' => json_encode($galleryImages, JSON_THROW_ON_ERROR),
            'old_price' => $request->input('old_price'),
            'offer' => $request->input('offer'),
            'product_cost' => $request->input('product_cost'),
            'wholesale_price' => $request->input('wholesale_price'),
            'reseller_price' => $request->input('reseller_price'),
            'quantity' => $request->input('quantity'),
            'weight' => $request->input('weight', 0.5),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'brand_id' => $request->input('brand_id'),
            'video_url' => $request->input('video_url'),
            'tags' => $request->input('tags'),
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'created_by' => auth()->id(),
        ];

        // Add product type specific fields
        if ($request->product_type === 'digital' && $request->hasFile('digital_file')) {
            $digitalFile = $request->file('digital_file');
            $digitalFilePath = 'product/digital/' . time() . '-' . $digitalFile->getClientOriginalName();
            $digitalFile->storeAs('public', $digitalFilePath);

            $productData['digital_file'] = $digitalFilePath;
            $productData['download_limit'] = $request->input('download_limit');
        } elseif ($request->product_type === 'affiliate') {
            $productData['external_url'] = $request->input('external_url');
            $productData['affiliate_commission'] = $request->input('affiliate_commission');
        }

        $product = Product::create($productData);

        // Handle SEO data if provided
        if ($request->has('seo') && is_array($request->input('seo'))) {
            $seoData = $request->input('seo');
            
            // Handle SEO OG image upload
            if ($request->hasFile('seo.og_image')) {
                $seoOgImage = $request->file('seo.og_image');
                $seoOgImagePath = 'product/seo/' . time() . '-' . $seoOgImage->getClientOriginalName();
                $seoOgImage->storeAs('public', $seoOgImagePath);
                $seoData['og_image'] = $seoOgImagePath;
            }
            
            // Remove the temporary field used for preserving existing image
            unset($seoData['existing_og_image']);
            
            // Store SEO data - the model cast will handle JSON encoding
            $product->update(['seo' => $seoData]);
        }

        // NEW: Save custom delivery and warranty settings if submitted
        if ($request->has('settings')) {
            foreach ($request->input('settings', []) as $key => $val) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['group' => 'general', 'key' => $key],
                    ['value' => $val]
                );
            }
        }

        // NEW: Sync additional categories if provided (Hybrid Approach)
        if ($request->has('additional_categories')) {
            $additionalCategories = [];
            foreach ($request->input('additional_categories', []) as $index => $categoryId) {
                // Don't duplicate primary category
                if ($categoryId && $categoryId != $product->category_id) {
                    $additionalCategories[$categoryId] = ['sort_order' => $index];
                }
            }
            $product->additionalCategories()->sync($additionalCategories);
        }

        // NEW: Sync additional subcategories if provided
        if ($request->has('additional_subcategories')) {
            $additionalSubcategories = [];
            foreach ($request->input('additional_subcategories', []) as $index => $subCategoryId) {
                // Don't duplicate primary subcategory
                if ($subCategoryId && $subCategoryId != $product->sub_category_id) {
                    $additionalSubcategories[$subCategoryId] = ['sort_order' => $index];
                }
            }
            $product->additionalSubCategories()->sync($additionalSubcategories);
        }

        // NEW: Sync third categories if provided
        if ($request->has('third_categories')) {
            $thirdCategories = [];
            foreach ($request->input('third_categories', []) as $index => $thirdCategoryId) {
                if ($thirdCategoryId) {
                    $thirdCategories[$thirdCategoryId] = ['sort_order' => $index];
                }
            }
            $product->thirdCategories()->sync($thirdCategories);
        }

        if(isset($request->isbn)) {
            // TODO:: Add validations to this request.
            $book = $product->book()->create([
                'product_id' => $product->id,
                'subject' => $request->input('subject'),
                'edition' => $request->input('edition'),
                'publisher_id' => $request->input('publisher'),
                'isbn' => $request->input('isbn'),
                'pages' => $request->input('pages'),
                'cover' => $request->input('cover'),
                'country' => $request->input('country'),
                'language' => $request->input('language')
            ]);

            if(isset($request->writers) && is_array($request->writers)) {
                $book->writers()->sync($request->writers);
            }

            if($request->hasFile('sample_path')) {
                $sampleFile = $request->file('sample_path');
                $sampleFilePath = 'product/book-samples/' . time() . '-' . $sampleFile->getClientOriginalName();
                $sampleFile->storeAs('public', $sampleFilePath);
                $book->update(['sample_path' => $sampleFilePath]);
            }
        }

        if ($product->product_type === 'simple' && $product->manage_stock) {
            $this->stockService->updateSimpleProductStock(
                $product,
                $request->input('quantity'),
                'initial',
                null,
                'Initial stock'
            );
        }

        // Process variations only if they exist in the request
        if ($request->product_type === 'variable' && $request->has('variations') && is_array($request->input('variations'))) {
            $allVariationOptions = []; // Store all variation options for combination generation
            
            foreach ($request->input('variations') as $variationIndex => $variationData) {
                $variation = new Variation(['name' => $variationData['name']]);
                $product->variations()->save($variation);

                $variationOptions = []; // Store options for this specific variation
                
                foreach ($variationData['options'] as $optionIndex => $optionData) {
                    // Options now only store names - rich data is in combinations
                    $option = new VariationOption([
                        'name' => $optionData['name'],
                        'description' => null, // Rich data moved to combinations
                        'featured_image' => null,
                        'images' => null,
                        'stock_quantity' => 0, // Default placeholder
                        'price' => 0 // Default placeholder
                    ]);
                    $variation->options()->save($option);

                    // Store option for combination generation
                    $variationOptions[] = $option;

                    ProductVariationOption::create([
                        'product_id' => $product->id,
                        'variation_option_id' => $option->id,
                        'price' => 0 // Placeholder - real pricing is in combinations
                    ]);
                }
                
                // Add this variation's options to the master array
                $allVariationOptions[] = $variationOptions;
            }
            
            // Generate all possible combinations using Cartesian product
            $this->generateVariationCombinations($product, $allVariationOptions, $request->input('combinations', []), $request);
        }
        return redirect()->route('admin.items.index')->with('success', 'Product created successfully.');
    }

    /**
     * Generate all possible combinations of variation options
     */
    private function generateVariationCombinations($product, $allVariationOptions, $customCombinations = [], $request = null)
    {
        if (empty($allVariationOptions)) {
            return;
        }

        // Generate Cartesian product of all variation options
        $combinations = $this->cartesianProduct($allVariationOptions);

        foreach ($combinations as $index => $combination) {
            // Extract option IDs and names
            $optionIds = [];
            $optionNames = [];
            
            foreach ($combination as $option) {
                $optionIds[] = $option->id;
                $optionNames[] = $option->name;
            }
            
            // Default to 0 for required fields if not provided
            $defaultPrice = 0; // Default base price for combinations
            $defaultStock = 0;  // Default stock quantity

            // Generate unique combination key
            $combinationKey = VariationCombination::generateCombinationKey($product->id, $optionIds);

            // Check if custom combination data exists for this combination
            $customRegularPrice = $defaultPrice;
            $customOfferPrice = null;
            $customStock = $defaultStock;
            $customDescription = null;
            $customFeaturedImage = null;
            
            if (isset($customCombinations[$index])) {
                $customData = $customCombinations[$index];
                
                // Use custom regular price if provided, otherwise default to 0
                if (isset($customData['regular_price']) && $customData['regular_price'] !== '' && $customData['regular_price'] !== null) {
                    $customRegularPrice = is_numeric($customData['regular_price']) ? floatval($customData['regular_price']) : $defaultPrice;
                } else {
                    $customRegularPrice = $defaultPrice;
                }
                
                // Use custom offer price if provided
                if (isset($customData['offer_price']) && is_numeric($customData['offer_price']) && $customData['offer_price'] > 0) {
                    $customOfferPrice = floatval($customData['offer_price']);
                }
                
                // Use custom stock if provided, otherwise default to 0
                if (isset($customData['stock_quantity']) && $customData['stock_quantity'] !== '' && $customData['stock_quantity'] !== null) {
                    $customStock = is_numeric($customData['stock_quantity']) ? intval($customData['stock_quantity']) : $defaultStock;
                } else {
                    $customStock = $defaultStock;
                }
                
                // Use custom description if provided
                if (isset($customData['short_description']) && !empty($customData['short_description'])) {
                    $customDescription = $customData['short_description'];
                }
            }

            // Handle featured image upload for this combination
            if ($request && $request->hasFile("combinations.{$index}.featured_image")) {
                $imageFile = $request->file("combinations.{$index}.featured_image");
                if ($imageFile && $imageFile->isValid() && $imageFile->getSize() > 0) {
                    $imagePath = 'product/combinations/' . time() . '-' . $imageFile->getClientOriginalName();
                    $imageFile->storeAs('public', $imagePath);
                    $customFeaturedImage = $imagePath;
                }
                // If no valid featured image was uploaded, keep default
            }

            // Handle gallery images upload for this combination
            $customGalleryImages = [];
            if ($request && $request->hasFile("combinations.{$index}.gallery_images")) {
                $galleryFiles = $request->file("combinations.{$index}.gallery_images");
                foreach ($galleryFiles as $galleryFile) {
                    if ($galleryFile && $galleryFile->isValid() && $galleryFile->getSize() > 0) {
                        $galleryPath = 'product/combinations/gallery/' . time() . '-' . $galleryFile->getClientOriginalName();
                        $galleryFile->storeAs('public', $galleryPath);
                        $customGalleryImages[] = $galleryPath;
                    }
                }
            }

            // Create the combination record with custom or default values
            VariationCombination::create([
                'product_id' => $product->id,
                'variation_options' => $optionIds,
                'combination_key' => $combinationKey,
                //'price' => $customRegularPrice, // Keep for backward compatibility
                'regular_price' => $customRegularPrice,
                'offer_price' => $customOfferPrice,
                'product_cost' => $customData['product_cost'] ?? null,
                'wholesale_price' => $customData['wholesale_price'] ?? null,
                'stock_quantity' => $customStock,
                'short_description' => $customDescription,
                'featured_image' => $customFeaturedImage,
                'gallery_images' => !empty($customGalleryImages) ? $customGalleryImages : null,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Generate Cartesian product of arrays
     */
    private function cartesianProduct($arrays)
    {
        $result = [[]];
        
        foreach ($arrays as $property => $propertyValues) {
            $tmp = [];
            foreach ($result as $resultItem) {
                foreach ($propertyValues as $propertyValue) {
                    $tmp[] = array_merge($resultItem, [$propertyValue]);
                }
            }
            $result = $tmp;
        }
        
        return $result;
    }

    /**
     * Generate all possible combinations for update (handles existing combinations)
     */
    private function generateVariationCombinationsForUpdate($product, $allVariationOptions, $customCombinations = [], $request = null)
    {
        if (empty($allVariationOptions)) {
            return;
        }

        // Get existing combinations to preserve data
        $existingCombinations = VariationCombination::where('product_id', $product->id)
            ->get()
            ->keyBy('id');

        // Generate Cartesian product of all variation options
        $combinations = $this->cartesianProduct($allVariationOptions);
        $updatedCombinationIds = [];

        foreach ($combinations as $index => $combination) {
            // Extract option IDs and names
            $optionIds = [];
            foreach ($combination as $option) {
                $optionIds[] = $option->id;
            }
            
            // Generate unique combination key
            $combinationKey = VariationCombination::generateCombinationKey($product->id, $optionIds);

            // Get custom data if provided
            $customData = $customCombinations[$index] ?? [];
            $existingCombinationId = $customData['id'] ?? null;

            // Find existing combination by ID or create new
            $existingCombination = null;
            if ($existingCombinationId && isset($existingCombinations[$existingCombinationId])) {
                $existingCombination = $existingCombinations[$existingCombinationId];
                $updatedCombinationIds[] = $existingCombinationId;
            }

            // Prepare combination data, preserving existing values where not updated
            $combinationData = [];

            // Handle regular_price: if provided (even as empty string), set to 0 if empty/null
            if (isset($customData['regular_price'])) {
                if ($customData['regular_price'] === '' || $customData['regular_price'] === null) {
                    $combinationData['regular_price'] = 0;
                } else {
                    $combinationData['regular_price'] = is_numeric($customData['regular_price']) ? floatval($customData['regular_price']) : 0;
                }
            }
            
            if (isset($customData['offer_price']) && is_numeric($customData['offer_price'])) {
                $combinationData['offer_price'] = floatval($customData['offer_price']);
            }
            
            if (isset($customData['product_cost']) && is_numeric($customData['product_cost'])) {
                $combinationData['product_cost'] = floatval($customData['product_cost']);
            }
            
            if (isset($customData['wholesale_price']) && is_numeric($customData['wholesale_price'])) {
                $combinationData['wholesale_price'] = floatval($customData['wholesale_price']);
            }
            
            if (isset($customData['reseller_price']) && is_numeric($customData['reseller_price'])) {
                $combinationData['reseller_price'] = floatval($customData['reseller_price']);
            }
            
            // Handle stock_quantity: if provided (even as empty string), set to 0 if empty/null
            if (isset($customData['stock_quantity'])) {
                if ($customData['stock_quantity'] === '' || $customData['stock_quantity'] === null) {
                    $combinationData['stock_quantity'] = 0;
                } else {
                    $combinationData['stock_quantity'] = is_numeric($customData['stock_quantity']) ? intval($customData['stock_quantity']) : 0;
                }
            }
            
            if (isset($customData['short_description'])) {
                $combinationData['short_description'] = $customData['short_description'];
            }

            // Handle featured image - only if new one uploaded
            if ($request && $request->hasFile("combinations.{$index}.featured_image")) {
                $imageFile = $request->file("combinations.{$index}.featured_image");
                if ($imageFile && $imageFile->isValid() && $imageFile->getSize() > 0) {
                    $imagePath = 'product/combinations/' . time() . '-' . $imageFile->getClientOriginalName();
                    $imageFile->storeAs('public', $imagePath);
                    $combinationData['featured_image'] = $imagePath;
                }
                // If no valid featured image was uploaded, keep existing image
            }

            // Handle gallery images - only if new ones uploaded
            if ($request && $request->hasFile("combinations.{$index}.gallery_images")) {
                $galleryFiles = $request->file("combinations.{$index}.gallery_images");
                $hasValidGalleryImages = false;
                $galleryPaths = [];
                
                // Check if any valid gallery images were uploaded
                foreach ($galleryFiles as $galleryFile) {
                    if ($galleryFile && $galleryFile->isValid() && $galleryFile->getSize() > 0) {
                        $hasValidGalleryImages = true;
                        $galleryPath = 'product/combinations/gallery/' . time() . '-' . $galleryFile->getClientOriginalName();
                        $galleryFile->storeAs('public', $galleryPath);
                        $galleryPaths[] = $galleryPath;
                    }
                }
                
                // Only update gallery images if valid images were uploaded
                if ($hasValidGalleryImages) {
                    $combinationData['gallery_images'] = $galleryPaths;
                }
                // If no valid gallery images were uploaded, keep existing images
            }

            // Always update these fields to maintain consistency
            $combinationData['product_id'] = $product->id;
            $combinationData['variation_options'] = $optionIds;
            $combinationData['combination_key'] = $combinationKey;
            $combinationData['is_active'] = true;

            if ($existingCombination) {
                // Update only if we have changes
                if (!empty($combinationData)) {
                    $existingCombination->update($combinationData);
                }
            } else {
                // For new combinations, set defaults for required fields if not provided
                if (!isset($combinationData['regular_price'])) {
                    $combinationData['regular_price'] = 0; // Default price
                }
                if (!isset($combinationData['stock_quantity'])) {
                    $combinationData['stock_quantity'] = 0; // Default stock
                }
                VariationCombination::create($combinationData);
            }
        }

        // Optionally deactivate combinations that no longer exist
        // Instead of deleting, we just mark them as inactive to preserve order history
        if (!empty($updatedCombinationIds)) {
            VariationCombination::where('product_id', $product->id)
                ->whereNotIn('id', $updatedCombinationIds)
                ->update(['is_active' => false]);
        }
    }

    /**
     * Safely update variations and combinations for variable products.
     * This method preserves combination IDs and only updates content as needed.
     */
    private function updateVariationsAndCombinationsSafely($product, $request)
    {
        // Remove old variations/options only if not present in the new request
        // (This logic is already handled in the update method for variations/options)

        // Build allVariationOptions array for the current request
        $allVariationOptions = [];
        foreach ($request->input('variations') as $variationIndex => $variationData) {
            $variation = \App\Models\Variation::firstOrCreate([
                'id' => $variationData['id'] ?? null,
                'product_id' => $product->id
            ], [
                'name' => $variationData['name'],
                'product_id' => $product->id
            ]);
            $variation->name = $variationData['name'];
            $variation->save();

            $variationOptions = [];
            foreach ($variationData['options'] as $optionIndex => $optionData) {
                $option = \App\Models\VariationOption::firstOrCreate([
                    'id' => $optionData['id'] ?? null,
                    'variation_id' => $variation->id
                ], [
                    'name' => $optionData['name'],
                    'variation_id' => $variation->id,
                    'price' => 0, // Placeholder - real pricing is in combinations
                    'stock_quantity' => 0, // Placeholder
                    'description' => null,
                    'featured_image' => null,
                    'images' => null
                ]);
                $option->name = $optionData['name'];
                $option->save();
                $variationOptions[] = $option;
                \App\Models\ProductVariationOption::updateOrCreate([
                    'product_id' => $product->id,
                    'variation_option_id' => $option->id
                ], [
                    'price' => 0 // Placeholder, real price is in combinations
                ]);
            }
            $allVariationOptions[] = $variationOptions;
        }

        // Now update ONLY the manually submitted combinations (no auto-generation)
        $this->updateManualCombinationsOnly($product, $request->input('combinations', []), $request);
    }
    
    /**
     * Update ONLY the manually submitted combinations (no Cartesian product auto-generation)
     */
    private function updateManualCombinationsOnly($product, $submittedCombinations, $request)
    {
        if (empty($submittedCombinations)) {
            return;
        }

        // Handle deletions first
        if ($request->has('combinations_to_delete')) {
            $idsToDelete = $request->input('combinations_to_delete');
            VariationCombination::whereIn('id', $idsToDelete)->delete();
        }

        foreach ($submittedCombinations as $index => $combinationData) {
            // Skip if this is marked for deletion
            if (isset($combinationData['_delete'])) {
                continue;
            }

            $combinationKey = $combinationData['key'] ?? null;
            
            if (!$combinationKey) {
                continue; // Skip combinations without a key
            }

            // Determine variation option IDs
            $optionIds = [];
            
            // If this is an existing combination, get option IDs from the database
            if (isset($combinationData['id']) && $combinationData['id']) {
                $existingCombination = VariationCombination::find($combinationData['id']);
                if ($existingCombination) {
                    $optionIds = $existingCombination->variation_options ?? [];
                }
            }
            
            // If we still don't have option IDs, try to extract from the key
            if (empty($optionIds)) {
                // Key format could be:
                // 1. "product_id_option1_option2..." (old format with numeric IDs)
                // 2. "Small_Red" (new format with option names)
                $keyParts = explode('_', $combinationKey);
                
                // Check if first part is numeric (product ID) and remove it
                if (!empty($keyParts) && is_numeric($keyParts[0])) {
                    array_shift($keyParts);
                }
                
                // Try to resolve option names to IDs
                if (!empty($keyParts)) {
                    // If parts are numeric, use them as IDs
                    if (is_numeric($keyParts[0])) {
                        $optionIds = array_map('intval', $keyParts);
                    } else {
                        // Parts are option names - look them up
                        $optionIds = \App\Models\VariationOption::whereIn('name', $keyParts)
                            ->whereHas('variation', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })
                            ->pluck('id')
                            ->toArray();
                    }
                }
            }

            // Prepare combination data
            $data = [
                'product_id' => $product->id,
                'combination_key' => $combinationKey,
                'variation_options' => $optionIds, // JSON field
                'regular_price' => $combinationData['regular_price'] ?? 0,
                'offer_price' => $combinationData['offer_price'] ?? null,
                'product_cost' => $combinationData['product_cost'] ?? null,
                'wholesale_price' => $combinationData['wholesale_price'] ?? null,
                'reseller_price' => $combinationData['reseller_price'] ?? null,
                'stock_quantity' => $combinationData['stock_quantity'] ?? 0,
                'short_description' => $combinationData['short_description'] ?? null,
            ];

            // Handle featured image - consistent with create method
            if ($request->hasFile("combinations.{$index}.featured_image")) {
                $imageFile = $request->file("combinations.{$index}.featured_image");
                if ($imageFile && $imageFile->isValid() && $imageFile->getSize() > 0) {
                    $imagePath = 'product/combinations/' . time() . '-' . $imageFile->getClientOriginalName();
                    $imageFile->storeAs('public', $imagePath);
                    $data['featured_image'] = $imagePath;
                }
            }

            // Handle gallery images
            if ($request->hasFile("combinations.{$index}.gallery_images")) {
                $galleryFiles = $request->file("combinations.{$index}.gallery_images");
                $galleryPaths = [];
                
                foreach ($galleryFiles as $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        $galleryPath = 'product/combinations/gallery/' . time() . '-' . $file->getClientOriginalName();
                        $file->storeAs('public', $galleryPath);
                        $galleryPaths[] = $galleryPath;
                    }
                }
                
                if (!empty($galleryPaths)) {
                    $data['gallery_images'] = json_encode($galleryPaths);
                }
            }

            // Check if this combination already exists (update) or is new (create)
            if (isset($combinationData['id']) && $combinationData['id']) {
                // Update existing combination - PRESERVE THE ID
                $existingCombination = VariationCombination::find($combinationData['id']);
                
                if ($existingCombination) {
                    // Only update fields that were actually provided
                    foreach ($data as $key => $value) {
                        if ($value !== null || $key === 'offer_price' || $key === 'product_cost' || $key === 'wholesale_price' || $key === 'reseller_price') {
                            $existingCombination->$key = $value;
                        }
                    }
                    $existingCombination->save();
                }
            } else {
                // Create new combination
                // The variation_options field is already in $data as JSON
                $newCombination = VariationCombination::create($data);
            }
        }
    }

    public function edit(Product $product)
    {
        // Load categories with subcategories and third categories for tree view
        $categories = ProductCategory::where('status', 'active')
            ->with(['subCategories' => function($query) {
                $query->where('status', 1)
                    ->orderBy('name')
                    ->with(['thirdCategories' => function($q) {
                        $q->where('status', true)->ordered();
                    }]);
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
            
        $sub_categories = SubCategory::where('status', 1)->get([
            'id',
            'name'
        ]);
        $brands = Brand::where('status', true)->get(['id', 'name']);
        $variations = Variation::with("options")->where("product_id", $product->id)->get();
        
        // Load existing combinations for variable products
        $combinations = collect();
        if ($product->product_type === 'variable') {
            $combinations = VariationCombination::where('product_id', $product->id)->get();
        }
        
        // NEW: Eager load additional categories for edit form
        $product->load(['additionalCategories', 'additionalSubCategories', 'thirdCategories']);
        
        // Ensure SEO data is properly decoded for the view
        if ($product->seo && is_string($product->seo)) {
            $product->seo = json_decode($product->seo, true) ?: [];
        } elseif (!$product->seo) {
            $product->seo = [];
        }
        
        // Ensure images data is properly handled for the view
        if ($product->images && is_string($product->images)) {
            $product->images = json_decode($product->images, true) ?: [];
        } elseif (!$product->images) {
            $product->images = [];
        }
        
        return view("admin.product.edit", compact('product', 'sub_categories', 'categories', 'brands', 'variations', 'combinations'));
    }

    public function update(Request $request, Product $product)
    {
        // Prepare validation rules based on product type
        $validationRules = [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048', // 2MB limit
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048', // 2MB limit for each image
            'quantity' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
            // Primary category/subcategory (optional for backward compatibility)
            'category_id' => 'nullable|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            // NEW: Validation for additional categories (Hybrid Approach)
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => 'exists:product_categories,id',
            'additional_subcategories' => 'nullable|array',
            'additional_subcategories.*' => 'exists:sub_categories,id',
            'third_categories' => 'nullable|array',
            'third_categories.*' => 'exists:third_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'video_url' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'writers' => 'nullable|array',
            'publisher' => 'nullable|exists:publishers,id',
            // SEO validation rules
            'seo' => 'nullable|array',
            'seo.meta_title' => 'nullable|string|max:60',
            'seo.meta_description' => 'nullable|string|max:160',
            'seo.meta_keywords' => 'nullable|string|max:255',
            'seo.canonical_url' => 'nullable|url',
            // 'seo.meta_robots' => 'nullable|string|in:index,follow,noindex,follow,index,nofollow,noindex,nofollow',
            'seo.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'seo.schema_markup' => 'nullable|string',
        ];

        // Add product type specific validation rules
        if ($product->product_type === 'variable') {
            $variationRules = [
                'old_price' => 'nullable|numeric|min:0',
                'offer' => 'nullable|numeric|min:0',
                'variations' => 'required|array',
                'variations.*.name' => 'required|string|max:255',
                'variations.*.options' => 'required|array',
                'variations.*.options.*.name' => 'required|string|max:255',
                // Validation for combination data
                'combinations' => 'nullable|array',
                'combinations.*.regular_price' => 'nullable|numeric|min:0',
                'combinations.*.offer_price' => 'nullable|numeric|min:0',
                'combinations.*.product_cost' => 'nullable|numeric|min:0',
                'combinations.*.wholesale_price' => 'nullable|numeric|min:0',
                'combinations.*.reseller_price' => 'nullable|numeric|min:0',
                'combinations.*.stock_quantity' => 'nullable|numeric|min:0',
                'combinations.*.short_description' => 'nullable|string|max:1000',
                'combinations.*.featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:15360',
                'combinations.*.gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:15360',
                'combinations.*.key' => 'nullable|string',
            ];
            $validationRules = array_merge($validationRules, $variationRules);
        } elseif ($product->product_type === 'digital') {
            $digitalRules = [
                'old_price' => 'required|numeric|min:0',
                'offer' => 'required|numeric|min:0',
                'digital_file' => 'nullable|file|max:102400', // 100MB limit for digital files
                'download_limit' => 'nullable|integer|min:0',
            ];
            $validationRules = array_merge($validationRules, $digitalRules);
        } elseif ($product->product_type === 'affiliate') {
            $affiliateRules = [
                'old_price' => 'required|numeric|min:0',
                'offer' => 'required|numeric|min:0',
                'external_url' => 'required|url',
                'affiliate_commission' => 'nullable|numeric|min:0',
            ];
            $validationRules = array_merge($validationRules, $affiliateRules);
        }

        $request->validate($validationRules);

        // Ensure at least one category is selected (either primary or additional)
        if (empty($request->input('category_id')) && 
            empty($request->input('additional_categories'))) {
            return redirect()->back()
                ->withErrors(['category_id' => 'Please select at least one category (primary or additional).'])
                ->withInput();
        }

        // Handle thumbnail image - only update if a new file is uploaded
        $thumbImagePath = $product->thumb_image;
        if ($request->hasFile('thumb_image')) {
            $thumbImage = $request->file('thumb_image');
            if ($thumbImage && $thumbImage->isValid() && $thumbImage->getSize() > 0) {
                $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
                $thumbImage->storeAs('public', $thumbImagePath);
            }
            // If no valid thumbnail was uploaded, keep existing thumbnail
        }

        // Handle gallery images - only update if new files are uploaded
        // Properly decode existing images from JSON or array
        $galleryImages = [];
        if ($product->images) {
            if (is_string($product->images)) {
                $galleryImages = json_decode($product->images, true) ?: [];
            } elseif (is_array($product->images)) {
                $galleryImages = $product->images;
            }
        }
        
        if ($request->hasFile('images')) {
            $uploadedImages = $request->file('images');
            $hasValidImages = false;
            
            // Check if any valid images were uploaded
            foreach ($uploadedImages as $image) {
                if ($image && $image->isValid() && $image->getSize() > 0) {
                    $hasValidImages = true;
                    break;
                }
            }
            
            // Only reset gallery if valid images were uploaded
            if ($hasValidImages) {
                $galleryImages = []; // Reset gallery if new images are uploaded
                foreach ($uploadedImages as $image) {
                    if ($image && $image->isValid() && $image->getSize() > 0) {
                        $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                        $image->storeAs('public', $galleryImagePath);
                        $galleryImages[] = $galleryImagePath;
                    }
                }
            }
            // If no valid images were uploaded, keep existing images
        }

        // Prepare product data for update
        $productData = [
            'title' => $request->input('title'),
            'slug' => $request->input('slug') ?: Str::slug($request->input('title')),
            'sku' => $request->input('sku'),
            'description' => $request->input('description'),
            'short_description' => $request->input('short_description'),
            'thumb_image' => $thumbImagePath,
            'images' => json_encode($galleryImages, JSON_THROW_ON_ERROR),
            'old_price' => $request->input('old_price'),
            'offer' => $request->input('offer'),
            'product_cost' => $request->input('product_cost'),
            'wholesale_price' => $request->input('wholesale_price'),
            'reseller_price' => $request->input('reseller_price'),
            'quantity' => $request->input('quantity'),
            'weight' => $request->input('weight', 0.5),
            'status' => $request->input('status'),
            'category_id' => $request->input('category_id'),
            'sub_category_id' => $request->input('sub_category_id'),
            'brand_id' => $request->input('brand_id'),
            'video_url' => $request->input('video_url'),
            'tags' => $request->input('tags'),
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ];

        // Handle product type specific fields
        if ($product->product_type === 'digital') {
            // Handle digital file update
            $digitalFilePath = $product->digital_file;
            if ($request->hasFile('digital_file')) {
                $digitalFile = $request->file('digital_file');
                if ($digitalFile && $digitalFile->isValid() && $digitalFile->getSize() > 0) {
                    $digitalFilePath = 'product/digital/' . time() . '-' . $digitalFile->getClientOriginalName();
                    $digitalFile->storeAs('public', $digitalFilePath);
                }
                // If no valid digital file was uploaded, keep existing file
            }

            $productData['digital_file'] = $digitalFilePath;
            $productData['download_limit'] = $request->input('download_limit');
        } elseif ($product->product_type === 'affiliate') {
            $productData['external_url'] = $request->input('external_url');
            $productData['affiliate_commission'] = $request->input('affiliate_commission');
        }

        // Update the product
        $update = $product->update($productData);

        // Handle SEO data if provided
        if ($request->has('seo') && is_array($request->input('seo'))) {
            $seoData = $request->input('seo');
            
            // Get existing SEO data to preserve unchanged fields
            $existingSeo = $product->formatted_seo;
            
            // Handle SEO OG image upload
            if ($request->hasFile('seo.og_image')) {
                $seoOgImage = $request->file('seo.og_image');
                if ($seoOgImage && $seoOgImage->isValid() && $seoOgImage->getSize() > 0) {
                    $seoOgImagePath = 'product/seo/' . time() . '-' . $seoOgImage->getClientOriginalName();
                    $seoOgImage->storeAs('public', $seoOgImagePath);
                    $seoData['og_image'] = $seoOgImagePath;
                } else {
                    // Preserve existing OG image path if no valid image is uploaded
                    $seoData['og_image'] = $seoData['existing_og_image'] ?? $existingSeo['og_image'] ?? null;
                }
            } else {
                // Preserve existing OG image path if no new image is uploaded
                $seoData['og_image'] = $seoData['existing_og_image'] ?? $existingSeo['og_image'] ?? null;
            }
            
            // Remove the temporary field used for preserving existing image
            unset($seoData['existing_og_image']);
            
            // Store SEO data - the model cast will handle JSON encoding
            $product->update(['seo' => $seoData]);
        }

        // NEW: Sync additional categories if provided (Hybrid Approach)
        if ($request->has('additional_categories')) {
            $additionalCategories = [];
            foreach ($request->input('additional_categories', []) as $index => $categoryId) {
                // Don't duplicate primary category
                if ($categoryId && $categoryId != $product->category_id) {
                    $additionalCategories[$categoryId] = ['sort_order' => $index];
                }
            }
            $product->additionalCategories()->sync($additionalCategories);
        } else {
            // If field is not present in request, don't clear existing - only clear if explicitly empty array
            // This allows partial updates without losing data
        }

        // NEW: Sync additional subcategories if provided
        if ($request->has('additional_subcategories')) {
            $additionalSubcategories = [];
            foreach ($request->input('additional_subcategories', []) as $index => $subCategoryId) {
                // Don't duplicate primary subcategory
                if ($subCategoryId && $subCategoryId != $product->sub_category_id) {
                    $additionalSubcategories[$subCategoryId] = ['sort_order' => $index];
                }
            }
            $product->additionalSubCategories()->sync($additionalSubcategories);
        }

        // Save custom delivery and warranty settings if submitted
        if ($request->has('settings')) {
            foreach ($request->input('settings', []) as $key => $val) {
                \App\Models\SiteSetting::updateOrCreate(
                    ['group' => 'general', 'key' => $key],
                    ['value' => $val]
                );
            }
        }

        if ($update) {

            $book = $product?->book()?->where('product_id', $product?->id)?->first();
            if (isset($request->isbn) && isset($book)) {
                // TODO:: Add validations to this request.
                $book->update([
                    'product_id' => $product->id,
                    'subject' => $request->input('subject'),
                    'edition' => $request->input('edition'),
                    'publisher_id' => $request->input('publisher'),
                    'isbn' => $request->input('isbn'),
                    'pages' => $request->input('pages'),
                    'cover' => $request->input('cover'),
                    'country' => $request->input('country'),
                    'language' => $request->input('language')
                ]);

                if (isset($request->writers) && is_array($request->writers)) {
                    $book->writers()->sync($request->writers);
                }

                if ($request->hasFile('sample_path')) {
                    $sampleFile = $request->file('sample_path');
                    if ($sampleFile && $sampleFile->isValid() && $sampleFile->getSize() > 0) {
                        // Delete old sample file if exists
                        if(File::exists($book->sample_path)) {
                            File::delete($book->sample_path);
                        }
                        
                        if(File::exists('/storage/' . $book->sample_path)) {
                            File::delete('/storage/' . $book->sample_path);
                        }

                        $sampleFilePath = 'product/book-samples/' . time() . '-' . $sampleFile->getClientOriginalName();
                        $sampleFile->storeAs('public', $sampleFilePath);
                        $product->book->update(['sample_path' => $sampleFilePath]);
                    }
                    // If no valid sample file was uploaded, keep existing file
                }
            }


            // Handle stock management for simple products
            if ($product->product_type === 'simple' && $product->manage_stock) {
                $oldQuantity = $product->getOriginal('quantity');
                $newQuantity = $request->input('quantity');
                $difference = $newQuantity - $oldQuantity;

                if ($difference != 0) {
                    $this->stockService->updateSimpleProductStock(
                        $product,
                        $difference,
                        'adjustment',
                        null,
                        'Stock adjustment during product update'
                    );
                }
            }

            // Handle variations and combinations for variable products
            if ($product->product_type === 'variable' && $request->has('variations')) {
                $this->updateVariationsAndCombinationsSafely($product, $request);
            }

            return redirect()->route('admin.items.index')->with('success', 'Product updated successfully.');
        }

        return back()->with('error', 'Failed to update product.');
    }


    public function destroy(Product $product)
    {
        if ($product) {
            // Delete thumbnail image
            if ($product->thumb_image && Storage::disk('public')->exists($product->thumb_image)) {
                Storage::disk('public')->delete($product->thumb_image);
            }

            // Delete gallery images
            if ($product->images) {
                $galleryImages = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                if (is_array($galleryImages)) {
                    foreach ($galleryImages as $image) {
                        if (Storage::disk('public')->exists($image)) {
                            Storage::disk('public')->delete($image);
                        }
                    }
                }
            }

            // Delete digital file if product is digital
            if ($product->product_type === 'digital' && $product->digital_file) {
                if (Storage::disk('public')->exists($product->digital_file)) {
                    Storage::disk('public')->delete($product->digital_file);
                }
            }

            // Delete variation images if product is variable
            if ($product->product_type === 'variable') {
                foreach ($product->variations as $variation) {
                    foreach ($variation->options as $option) {
                        // Delete featured image
                        if ($option->featured_image && Storage::disk('public')->exists($option->featured_image)) {
                            Storage::disk('public')->delete($option->featured_image);
                        }

                        // Delete option images
                        if (!empty($option->images)) {
                            $optionImages = $this->normalizeToArray($option->images);
                            foreach ($optionImages as $image) {
                                if (Storage::disk('public')->exists($image)) {
                                    Storage::disk('public')->delete($image);
                                }
                            }
                        }
                    }
                }

                // Also delete variation combinations images
                $combinations = VariationCombination::where('product_id', $product->id)->get();
                foreach ($combinations as $combination) {
                    if (!empty($combination->featured_image) && Storage::disk('public')->exists($combination->featured_image)) {
                        Storage::disk('public')->delete($combination->featured_image);
                    }
                    $gallery = $this->normalizeToArray($combination->gallery_images);
                    if (!empty($gallery)) {
                        foreach ($gallery as $image) {
                            if ($image && Storage::disk('public')->exists($image)) {
                                Storage::disk('public')->delete($image);
                            }
                        }
                    }
                }
            }

            // Now delete the product
            $product->delete();
            flash("Product deleted successfully.");
        } else {
            flash("No Product Found!");
        }
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        // Support JSON or form data
        $productIds = $request->input('product_ids');
        if ($productIds === null) {
            $json = json_decode($request->getContent(), true);
            $productIds = $json['product_ids'] ?? [];
        }

        if (empty($productIds) || !is_array($productIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No products selected for deletion.'
            ], 422);
        }

        // Ensure IDs are integers
        $productIds = array_map('intval', $productIds);

        // Fetch products to handle storage cleanup similar to destroy()
        $products = Product::forUser()->whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            // Delete thumbnail image
            if ($product->thumb_image && Storage::disk('public')->exists($product->thumb_image)) {
                Storage::disk('public')->delete($product->thumb_image);
            }

            // Delete gallery images
            if (!empty($product->images)) {
                $galleryImages = $this->normalizeToArray($product->images);
                foreach ($galleryImages as $image) {
                    if ($image && Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            // Delete digital file if product is digital
            if ($product->product_type === 'digital' && $product->digital_file) {
                if (Storage::disk('public')->exists($product->digital_file)) {
                    Storage::disk('public')->delete($product->digital_file);
                }
            }

            // Delete variation images if product is variable
            if ($product->product_type === 'variable') {
                foreach ($product->variations as $variation) {
                    foreach ($variation->options as $option) {
                        if ($option->featured_image && Storage::disk('public')->exists($option->featured_image)) {
                            Storage::disk('public')->delete($option->featured_image);
                        }
                        if (!empty($option->images)) {
                            $optionImages = $this->normalizeToArray($option->images);
                            foreach ($optionImages as $image) {
                                if ($image && Storage::disk('public')->exists($image)) {
                                    Storage::disk('public')->delete($image);
                                }
                            }
                        }
                    }
                }

                // Also delete variation combinations images
                $combinations = VariationCombination::where('product_id', $product->id)->get();
                foreach ($combinations as $combination) {
                    if (!empty($combination->featured_image) && Storage::disk('public')->exists($combination->featured_image)) {
                        Storage::disk('public')->delete($combination->featured_image);
                    }
                    $gallery = $this->normalizeToArray($combination->gallery_images);
                    if (!empty($gallery)) {
                        foreach ($gallery as $image) {
                            if ($image && Storage::disk('public')->exists($image)) {
                                Storage::disk('public')->delete($image);
                            }
                        }
                    }
                }
            }
        }

        // Delete the products
        Product::forUser()->whereIn('id', $productIds)->delete();

        return response()->json([
            'success' => true,
            'message' => count($productIds) . ' products have been deleted successfully.'
        ]);
    }

    public function bulkStatusToggle(Request $request)
    {
        $productIds = $request->input('product_ids', []);
        $status = $request->input('status', 0);

        if (empty($productIds) || !is_array($productIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No products selected for status update.'
            ], 422);
        }

        $productIds = array_map('intval', $productIds);
        
        Product::forUser()->whereIn('id', $productIds)->update(['status' => $status]);

        $statusText = $status ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => count($productIds) . ' products have been ' . $statusText . ' successfully.'
        ]);
    }

    public function exportSelected(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return response()->json(['error' => 'No products selected']);
        }

        $products = Product::forUser()->whereIn('id', $selectedIds)->get();

        $filename = 'products_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'Title', 'Description', 'Price', 'Status', 'Category', 'Sub Category', 
                'Quantity', 'Product Type', 'Featured', 'Created At'
            ]);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->title,
                    $product->description,
                    $product->price,
                    $product->status,
                    $product->category ? $product->category->name : 'N/A',
                    $product->subCategory ? $product->subCategory->name : 'N/A',
                    $product->quantity,
                    $product->product_type,
                    $product->is_featured ? 'Yes' : 'No',
                    $product->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));
        $limit = (int) $request->input('limit', 10);
        $limit = max(1, min($limit, 20));

        $productsQuery = Product::query()
            ->forUser()
            ->select('id', 'title', 'thumb_image', 'product_type', 'old_price', 'offer', 'status')
            ->where(function ($q) {
                $q->where('status', true)
                  ->orWhere('status', 1)
                  ->orWhere('status', 'active')
                  ->orWhere('status', 'Active')
                  ->orWhere('status', 'ACTIVE');
            });

        if ($request->boolean('initial') || $query === '') {
            $products = $productsQuery->latest()->limit($limit)->get();
        } else {
            $products = $productsQuery
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                        ->orWhere('id', $query)
                        ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit($limit)
                ->get();
        }

        $products = $products->map(function ($product) {
            $defaultPrice = $product->offer ?? $product->old_price ?? 0;

            return [
                'id' => $product->id,
                'title' => $product->title,
                'product_type' => $product->product_type,
                'regular_price' => $product->old_price,
                'offer_price' => $product->offer,
                'default_price' => $defaultPrice,
                'thumb_image_url' => $product->thumb_image ? asset('storage/' . $product->thumb_image) : null,
            ];
        });

        return response()->json(['products' => $products]);
    }

    /**
     * Check if a slug is available for products
     */
    public function checkSlugAvailability(Request $request)
    {
        $slug = $request->input('slug');
        
        if (empty($slug)) {
            return response()->json([
                'available' => false,
                'message' => 'Slug cannot be empty'
            ]);
        }
        
        // Check if slug exists in products table
        $exists = Product::where('slug', $slug)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug already exists' : 'Slug is available'
        ]);
    }

    /**
     * Normalize mixed value to a simple array of strings (paths).
     */
    private function normalizeToArray($value): array
    {
        if (empty($value)) {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        if ($value instanceof \Illuminate\Support\Collection) {
            return $value->toArray();
        }
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        if (is_iterable($value)) {
            return iterator_to_array($value);
        }
        if (method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        return [];
    }
}
