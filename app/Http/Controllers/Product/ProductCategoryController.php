<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    // Category page 
    public function index()
    {
        $categories = ProductCategory::get();
        return view('admin.product.category.index', compact('categories'));
    }

    // Category Create 
    public function create()
    {
        return view('admin.product.category.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',
            'slug' => 'nullable|string|max:255|unique:product_categories,slug',
            'description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle file upload for image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('category_images', $imageName, 'public');
        }

        // Handle file upload for background image
        $backgroundImagePath = null;
        if ($request->hasFile('background_image')) {
            $backgroundImage = $request->file('background_image');
            $backgroundImageName = time() . '_' . uniqid() . '.' . $backgroundImage->getClientOriginalExtension();
            $backgroundImagePath = $backgroundImage->storeAs('category_background_images', $backgroundImageName, 'public');
        }

        // Create a new product category
        ProductCategory::create([
            'name' => $request->name,
            'slug'      => $request->input('slug') ?: Str::slug($request->name),
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'image' => $imagePath,
            'background_image' => $backgroundImagePath,
            'status' => $request->status ?: 'active', // Default to active if not provided
        ]);

        return redirect()->route('admin.product_categories.create')->with('success', 'Product category created successfully.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view("admin.product.category.edit", compact("productCategory"));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        // Validate the request
        $request->validate([
            'name'             => 'required',
            'slug'             => 'nullable|string|max:255|unique:product_categories,slug,' . $productCategory->id,
            'description'      => 'nullable',
            'meta_title'       => 'nullable',
            'meta_description' => 'nullable',
            'meta_keywords'    => 'nullable',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'status'           => 'required|in:active,inactive',
        ]);
        // Handle file upload for image
        $imagePath = $request->old_image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('category_images', $imageName, 'public');
        }

        // Handle file upload for background image
        $backgroundImagePath = $request->old_background_image;
        if ($request->hasFile('background_image')) {
            $bgImage = $request->file('background_image');
            $bgImageName = time() . '_' . uniqid() . '.' . $bgImage->getClientOriginalExtension();
            $backgroundImagePath = $bgImage->storeAs('category_background_images', $bgImageName, 'public');
        }

        $productCategory->name = $request->name;
        $productCategory->slug = $request->input('slug') ?: Str::slug($request->name);
        $productCategory->description = $request->description;
        $productCategory->meta_title = $request->meta_title;
        $productCategory->meta_description = $request->meta_description;
        $productCategory->meta_keywords = $request->meta_keywords;
        $productCategory->status = $request->status;
        $productCategory->image = $imagePath;
        $productCategory->background_image = $backgroundImagePath;
        $productCategory->save();
        flash("Category Update successfully.");
        return redirect()->back();
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory) {
            $productCategory->delete();
            flash("Category Deleted.");
            return redirect()->back();
        }
        flash("No Category Found", "error");
        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:product_categories,id'],
        ]);

        $categories = ProductCategory::whereIn('id', $validated['ids'])->get();

        if ($categories->isEmpty()) {
            flash('No categories found for deletion.', 'error');
            return redirect()->back();
        }

        $deletedCount = 0;

        foreach ($categories as $category) {
            $category->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            flash()->success("{$deletedCount} category(s) deleted successfully.");
        } else {
            flash('No categories were deleted.', 'warning');
        }

        return redirect()->back();
    }
    
    /**
     * Check if a slug is available for product categories
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
        
        // Check if slug exists in product_categories table
        $exists = ProductCategory::where('slug', $slug)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug already exists' : 'Slug is available'
        ]);
    }

    /**
     * Get subcategories for a specific product category
     */
    public function getSubcategories($categoryId)
    {
        $category = ProductCategory::with('subCategories')->find($categoryId);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category->subCategories);
    }
}
