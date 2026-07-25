<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Writer;
use App\Models\Publisher;
use App\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorProductController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    protected function checkVendorAdminProductAccess($vendor): bool
    {
        if (!$vendor) return false;

        $vendorSettings = $vendor->vendorSettings;
        if ($vendorSettings && method_exists($vendorSettings, 'canAccessAdminProducts')) {
            if ($vendorSettings->canAccessAdminProducts()) {
                return true;
            }
        }

        try {
            if (method_exists($vendor, 'hasPermissionTo') && $vendor->hasPermissionTo('vendor.access_admin_products')) {
                return true;
            }
            if (method_exists($vendor, 'can') && $vendor->can('vendor.access_admin_products')) {
                return true;
            }
        } catch (\Throwable $e) {}

        if (method_exists($vendor, 'roles') && $vendor->roles) {
            foreach ($vendor->roles as $role) {
                if ($role->permissions && $role->permissions->contains('name', 'vendor.access_admin_products')) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Display vendor's products or parent admin products
     */
    public function index(Request $request)
    {
        $vendor = auth()->user();
        $canAccessAdminProducts = $this->checkVendorAdminProductAccess($vendor);

        $source = $request->get('source', 'my_products');

        if ($source === 'admin_products' && $canAccessAdminProducts) {
            $adminId = $vendor->created_by;
            $query = Product::where(function ($q) use ($adminId) {
                if ($adminId) {
                    $q->where('vendor_id', $adminId)->orWhereNull('vendor_id');
                } else {
                    $q->whereNull('vendor_id');
                }
            })->with(['category', 'subCategory', 'brand', 'variationCombinations']);
        } else {
            $source = 'my_products';
            $query = Product::forVendor($vendor->id)
                ->with(['category', 'subCategory', 'brand', 'variationCombinations']);

            if ($request->has('status')) {
                $query->where('approval_status', $request->status);
            }
        }

        $copiedProductTitles = Product::where('vendor_id', $vendor->id)->pluck('title')->toArray();
        $products = $query->latest()->paginate(20)->withQueryString();

        return view('vendor.products.index', compact('products', 'canAccessAdminProducts', 'source', 'copiedProductTitles'));
    }

    /**
     * Copy / duplicate a parent admin product to vendor's catalog
     */
    public function copy(Request $request, Product $product)
    {
        $vendor = auth()->user();
        $canAccessAdminProducts = $this->checkVendorAdminProductAccess($vendor);

        if (!$canAccessAdminProducts) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'You do not have permission to copy parent admin products.');
        }

        $adminId = $vendor->created_by;
        if ($product->vendor_id && $product->vendor_id != $adminId) {
            return redirect()->route('vendor.products.index')
                ->with('error', 'Unauthorized product copy request.');
        }

        // Check if vendor has already copied this product
        $alreadyCopied = Product::where('vendor_id', $vendor->id)
            ->where('title', $product->title)
            ->exists();

        if ($alreadyCopied) {
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'You have already copied "' . $product->title . '" to your catalog. Please select or try a different product.');
        }

        try {
            $vendorSettings = $vendor->vendorSettings;

            $newProduct = $product->replicate([
                'views_total',
                'views_unique',
            ]);

            $newProduct->title = $product->title;
            $newProduct->slug = Str::slug($product->title) . '-v' . $vendor->id . '-' . Str::random(4);
            if ($product->sku) {
                $newProduct->sku = $product->sku . '-V' . $vendor->id . '-' . rand(100, 999);
            }
            $newProduct->vendor_id = $vendor->id;

            // Respect vendor auto-approve settings or default to pending admin approval
            $autoApprove = $vendorSettings && method_exists($vendorSettings, 'shouldAutoApproveProducts') 
                ? $vendorSettings->shouldAutoApproveProducts() 
                : false;

            if ($autoApprove) {
                $newProduct->approval_status = 'approved';
                $newProduct->approved_at = now();
                $newProduct->status = 1; // Active
                $statusTarget = 'approved';
                $flashType = 'success';
                $flashMessage = 'Product "' . $newProduct->title . '" copied to your catalog and automatically approved!';
            } else {
                $newProduct->approval_status = 'pending';
                $newProduct->approved_at = null;
                $newProduct->status = 0; // Inactive until admin approves
                $statusTarget = 'pending';
                $flashType = 'warning';
                $flashMessage = 'Product "' . $newProduct->title . '" copied to your catalog! It has been submitted to your Admin for approval and is currently pending review.';
            }

            $newProduct->save();

            // Copy variation combinations if present
            if ($product->relationLoaded('variationCombinations') || $product->variationCombinations()->exists()) {
                foreach ($product->variationCombinations as $combination) {
                    $newCombination = $combination->replicate();
                    $newCombination->product_id = $newProduct->id;

                    $opts = $combination->variation_options;
                    if (is_string($opts)) {
                        $optsArr = json_decode($opts, true) ?? explode('_', $opts);
                    } else {
                        $optsArr = (array)$opts;
                    }

                    if (method_exists(\App\Models\VariationCombination::class, 'generateCombinationKey')) {
                        $newCombination->combination_key = \App\Models\VariationCombination::generateCombinationKey($newProduct->id, $optsArr);
                    } else {
                        $newCombination->combination_key = $newProduct->id . '_' . (is_array($optsArr) ? implode('_', $optsArr) : $optsArr);
                    }

                    if ($combination->sku) {
                        $newCombination->sku = $combination->sku . '-V' . $vendor->id . '-' . rand(100, 999);
                    }
                    $newCombination->save();
                }
            }

            return redirect()->route('vendor.products.index', ['source' => 'my_products', 'status' => $statusTarget])
                ->with($flashType, $flashMessage);

        } catch (\Throwable $e) {
            \Log::error('Product Copy Exception: ' . $e->getMessage());
            return redirect()->route('vendor.products.index', ['source' => 'admin_products'])
                ->with('error', 'Could not copy "' . $product->title . '". You have already copied this product or a duplicate SKU exists. Please try a different product.');
        }
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        // Check if vendor can add more products
        $canAdd = $this->vendorService->canAddProduct($vendor->id);
        
        if (!$canAdd['can_add']) {
            return redirect()->route('vendor.products.index')
                ->with('error', $canAdd['message']);
        }

        // Get commission settings
        $commissionSettings = [
            'default' => $vendorSettings->getDefaultCommissionRate(),
            'min' => $vendorSettings->getMinCommissionRate(),
            'max' => $vendorSettings->getMaxCommissionRate(),
        ];

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

        $sub_categories = SubCategory::where('status', 1)->get(['id', 'name']);
        $brands = Brand::where('status', 1)->get(['id', 'name']);
        $writers = Writer::select('id', 'name')->get();
        $publishers = Publisher::select('id', 'name')->get();

        return view('vendor.products.create', compact(
            'categories',
            'sub_categories',
            'brands',
            'writers',
            'publishers',
            'commissionSettings'
        ));
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        // Check if vendor can add products
        $canAdd = $this->vendorService->canAddProduct($vendor->id);
        if (!$canAdd['can_add']) {
            return redirect()->back()->with('error', $canAdd['message']);
        }

        // Base validation rules
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type' => 'required|in:simple,variable',
            'quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0.001',
            'manage_stock' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumb_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_proposed_commission' => 'nullable|numeric|min:0|max:100',
        ];

        // Add product type specific validation
        if ($request->product_type === 'variable') {
            $validationRules['old_price'] = 'nullable|numeric|min:0';
            $validationRules['offer'] = 'nullable|numeric|min:0';
            $validationRules['variations'] = 'required|array';
            $validationRules['variations.*.name'] = 'required|string|max:255';
            $validationRules['variations.*.options'] = 'required|array';
            $validationRules['variations.*.options.*.name'] = 'required|string|max:255';
            $validationRules['combinations'] = 'nullable|array';
            $validationRules['combinations.*.regular_price'] = 'required|numeric|min:0';
            $validationRules['combinations.*.offer_price'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.product_cost'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.wholesale_price'] = 'nullable|numeric|min:0';
            $validationRules['combinations.*.stock_quantity'] = 'required|integer|min:0';
            $validationRules['combinations.*.short_description'] = 'nullable|string|max:1000';
            $validationRules['combinations.*.featured_image'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
            $validationRules['combinations.*.gallery_images.*'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
            $validationRules['combinations.*.key'] = 'nullable|string';
        } else {
            // Simple product requires pricing
            $validationRules['old_price'] = 'required|numeric|min:0';
            $validationRules['offer'] = 'required|numeric|min:0';
            $validationRules['product_cost'] = 'nullable|numeric|min:0';
            $validationRules['wholesale_price'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($validationRules);

        // Validate proposed commission
        if ($request->vendor_proposed_commission) {
            $commissionValidation = $this->vendorService->validateProposedCommission(
                $vendor->id,
                $request->vendor_proposed_commission
            );

            if (!$commissionValidation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $commissionValidation['message']);
            }
        }

        // Handle thumbnail upload - match admin format for consistency
        if ($request->hasFile('thumb_image')) {
            $thumbImage = $request->file('thumb_image');
            $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
            $thumbImage->storeAs('public', $thumbImagePath);
            $validated['thumb_image'] = $thumbImagePath;
        }

        // Handle gallery images - match admin format
        $galleryImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public', $galleryImagePath);
                $galleryImages[] = $galleryImagePath;
            }
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        // Set vendor-specific fields
        $validated['vendor_id'] = $vendor->id;
        
        // IMPORTANT: Always require admin approval for vendor products
        // Only auto-approve if explicitly enabled in vendor settings
        $approvalStatus = $vendorSettings->shouldAutoApproveProducts() 
            ? 'approved' 
            : 'pending';
        
        $validated['approval_status'] = $approvalStatus;
        
        // Force status based on approval - vendor cannot override this
        if ($approvalStatus === 'approved') {
            $validated['approved_at'] = now();
            $validated['status'] = 1; // Active
        } else {
            $validated['status'] = 0; // Inactive until approved
        }

        // Set commission rate
        if ($request->vendor_proposed_commission) {
            $validated['vendor_proposed_commission'] = $request->vendor_proposed_commission;
            $validated['vendor_commission_rate'] = $request->vendor_proposed_commission;
        } else {
            $validated['vendor_commission_rate'] = $vendorSettings->getDefaultCommissionRate();
        }

        // Prepare product data
        $productData = [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'product_type' => $validated['product_type'],
            'thumb_image' => $validated['thumb_image'],
            'images' => !empty($galleryImages) ? json_encode($galleryImages) : null,
            'old_price' => $validated['old_price'] ?? null,
            'offer' => $validated['offer'] ?? null,
            'product_cost' => $validated['product_cost'] ?? null,
            'wholesale_price' => $validated['wholesale_price'] ?? null,
            'quantity' => $validated['quantity'] ?? null,
            'weight' => $validated['weight'] ?? 0.5,
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'video_url' => $validated['video_url'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'vendor_id' => $validated['vendor_id'],
            'approval_status' => $validated['approval_status'],
            'approved_at' => $validated['approved_at'] ?? null,
            'vendor_proposed_commission' => $validated['vendor_proposed_commission'] ?? null,
            'vendor_commission_rate' => $validated['vendor_commission_rate'],
        ];

        // Create product
        $product = Product::create($productData);

        // Process variations if this is a variable product
        if ($request->product_type === 'variable' && $request->has('variations') && is_array($request->input('variations'))) {
            $allVariationOptions = [];
            
            foreach ($request->input('variations') as $variationIndex => $variationData) {
                $variation = new \App\Models\Variation(['name' => $variationData['name']]);
                $product->variations()->save($variation);

                $variationOptions = [];
                
                foreach ($variationData['options'] as $optionIndex => $optionData) {
                    $option = new \App\Models\VariationOption([
                        'name' => $optionData['name'],
                        'description' => null,
                        'featured_image' => null,
                        'images' => null,
                        'stock_quantity' => 0,
                        'price' => 0
                    ]);
                    $variation->options()->save($option);

                    $variationOptions[] = $option;

                    \App\Models\ProductVariationOption::create([
                        'product_id' => $product->id,
                        'variation_option_id' => $option->id,
                        'price' => 0
                    ]);
                }
                
                $allVariationOptions[] = $variationOptions;
            }
            
            // Generate combinations
            $this->generateVariationCombinations($product, $allVariationOptions, $request->input('combinations', []), $request);
        }

        $message = $approvalStatus === 'approved' 
            ? 'Product created and auto-approved successfully! It is now live on the store.' 
            : 'Product submitted successfully! It will remain inactive until admin approves it.';

        return redirect()->route('vendor.products.index')->with('success', $message);
    }

    /**
     * Generate variation combinations (copied from admin controller)
     */
    protected function generateVariationCombinations($product, $allVariationOptions, $combinationsData, $request)
    {
        // Generate Cartesian product of all variation options
        $combinations = $this->cartesianProduct($allVariationOptions);
        
        foreach ($combinations as $index => $combination) {
            $combinationKey = implode('_', array_map(fn($opt) => $opt->name, $combination));
            $combinationIds = array_map(fn($opt) => $opt->id, $combination);
            
            // Get the rich data from the request for this combination
            $combinationInput = $combinationsData[$index] ?? [];
            
            // Handle featured image upload for this combination
            $featuredImagePath = null;
            if ($request->hasFile("combinations.{$index}.featured_image")) {
                $featuredImage = $request->file("combinations.{$index}.featured_image");
                $featuredImagePath = 'product/' . time() . '-' . $featuredImage->getClientOriginalName();
                $featuredImage->storeAs('public', $featuredImagePath);
            }
            
            // Handle gallery images for this combination
            $galleryImagePaths = [];
            if ($request->hasFile("combinations.{$index}.gallery_images")) {
                foreach ($request->file("combinations.{$index}.gallery_images") as $galleryImage) {
                    $galleryImagePath = 'product/' . time() . '-' . $galleryImage->getClientOriginalName();
                    $galleryImage->storeAs('public', $galleryImagePath);
                    $galleryImagePaths[] = $galleryImagePath;
                }
            }
            
            // Create the combination record with rich data
            \App\Models\VariationCombination::create([
                'product_id' => $product->id,
                'combination_string' => $combinationKey,
                'variation_option_ids' => json_encode($combinationIds),
                'regular_price' => $combinationInput['regular_price'] ?? 0,
                'offer_price' => $combinationInput['offer_price'] ?? null,
                'product_cost' => $combinationInput['product_cost'] ?? null,
                'wholesale_price' => $combinationInput['wholesale_price'] ?? null,
                'stock_quantity' => $combinationInput['stock_quantity'] ?? 0,
                'short_description' => $combinationInput['short_description'] ?? null,
                'featured_image' => $featuredImagePath,
                'gallery_images' => !empty($galleryImagePaths) ? json_encode($galleryImagePaths) : null,
            ]);
        }
    }

    /**
     * Helper: Cartesian product for generating all combinations
     */
    protected function cartesianProduct($arrays)
    {
        if (count($arrays) === 1) {
            return array_map(fn($item) => [$item], $arrays[0]);
        }

        $result = [[]];
        foreach ($arrays as $optionsArray) {
            $temp = [];
            foreach ($result as $resultItem) {
                foreach ($optionsArray as $option) {
                    $temp[] = array_merge($resultItem, [$option]);
                }
            }
            $result = $temp;
        }
        
        return $result;
    }

    /**
     * Get subcategories for a category (AJAX)
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = SubCategory::where('category_id', $categoryId)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();
        
        return response()->json($subcategories);
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        $vendorSettings = $vendor->vendorSettings;

        // Get commission settings
        $commissionSettings = [
            'default' => $vendorSettings->getDefaultCommissionRate(),
            'min' => $vendorSettings->getMinCommissionRate(),
            'max' => $vendorSettings->getMaxCommissionRate(),
        ];

        $categories = ProductCategory::where('status', 1)->get();
        // Get subcategories from primary category, or all if no primary category
        $subCategories = $product->category_id 
            ? SubCategory::where('category_id', $product->category_id)->get()
            : SubCategory::where('status', true)->get();
        $brands = Brand::where('status', 1)->get();

        return view('vendor.products.edit', compact(
            'product',
            'categories',
            'subCategories',
            'brands',
            'commissionSettings'
        ));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        // Check if product can be edited (if already approved)
        if ($product->isApproved() && !$vendor->vendorSettings->can_edit_after_approval) {
            return redirect()->back()->with('error', 'You cannot edit approved products.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:product_categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'old_price' => 'required|numeric|min:0',
            'offer' => 'required|numeric|min:0',
            'product_cost' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'manage_stock' => 'nullable|boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'video_url' => 'nullable|url',
            'thumb_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'vendor_proposed_commission' => 'nullable|numeric|min:0|max:100',
        ]);

        // Validate proposed commission if changed
        if ($request->vendor_proposed_commission && 
            $request->vendor_proposed_commission != $product->vendor_commission_rate) {
            
            $commissionValidation = $this->vendorService->validateProposedCommission(
                $vendor->id,
                $request->vendor_proposed_commission
            );

            if (!$commissionValidation['valid']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $commissionValidation['message']);
            }

            $validated['vendor_proposed_commission'] = $request->vendor_proposed_commission;
            // Don't auto-update vendor_commission_rate - let admin approve
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumb_image')) {
            // Delete old image
            if ($product->thumb_image) {
                Storage::disk('public')->delete($product->thumb_image);
            }
            
            $thumbImage = $request->file('thumb_image');
            $thumbImagePath = 'product/' . time() . '-' . $thumbImage->getClientOriginalName();
            $thumbImage->storeAs('public', $thumbImagePath);
            $validated['thumb_image'] = $thumbImagePath;
        }

        // Handle gallery images - append to existing images
        if ($request->hasFile('images')) {
            // Get existing images (handle both array and JSON string)
            $existingImages = $product->images;
            if (is_string($existingImages)) {
                $existingImages = json_decode($existingImages, true) ?? [];
            }
            $existingImages = is_array($existingImages) ? $existingImages : [];
            
            // Add new images
            foreach ($request->file('images') as $image) {
                $galleryImagePath = 'product/' . time() . '-' . $image->getClientOriginalName();
                $image->storeAs('public', $galleryImagePath);
                $existingImages[] = $galleryImagePath;
            }
            
            $validated['images'] = json_encode($existingImages);
        }

        // If product was approved and edited, set back to pending
        if ($product->isApproved()) {
            $validated['approval_status'] = 'pending';
            $validated['status'] = 0; // Deactivate until re-approved
        }

        $product->update($validated);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's product
        if ($product->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        // Delete thumbnail image
        if ($product->thumb_image) {
            Storage::disk('public')->delete($product->thumb_image);
        }
        
        // Delete gallery images (handle both array and JSON string formats)
        if ($product->images) {
            $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}

