<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sub_categories = SubCategory::with("category")->get();
        return view("admin.product.subCategory.index", compact("sub_categories"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "slug" => "nullable|string|max:255|unique:sub_categories,slug",
            "category_id" => "required|exists:product_categories,id",
            "description" => "nullable|string",
            "image" => "nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048",
            "background_image" => "nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048",
            "meta_title" => "nullable|string|max:255",
            "meta_description" => "nullable|string",
            "meta_keywords" => "nullable|string|max:255",
            "status" => "required|boolean",
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?: Str::slug($request->name);
        
        // Ensure slug uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (SubCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $subCategory = SubCategory::create([
            "name" => $request->name,
            "slug" => $slug,
            "product_category_id" => $request->category_id,
            "description" => $request->description,
            "meta_title" => $request->meta_title,
            "meta_description" => $request->meta_description,
            "meta_keywords" => $request->meta_keywords,
            "status" => $request->status ?: 1, // Default to active (1) if not provided
        ]);

        // Handle image uploads
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
            $subCategory->update(['image' => $imagePath]);
        }

        if ($request->hasFile('background_image')) {
            $backgroundImagePath = $request->file('background_image')->store('subcategory_background_images', 'public');
            $subCategory->update(['background_image' => $backgroundImagePath]);
        }

        if ($subCategory) {
            flash()->success("SubCategory created successfully.");
        } else {
            flash()->error("SubCategory could not be created.");
        }
        
        return redirect()->route("admin.sub-categories.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::select([
            "name",
            "id"
        ])->where("status", true)->get();
        return view("admin.product.subCategory.create", compact("categories"));
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = ProductCategory::get();
        return view("admin.product.subCategory.edit", compact("subCategory", "categories"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "slug" => "nullable|string|max:255|unique:sub_categories,slug," . $subCategory->id,
            "category_id" => "required|exists:product_categories,id",
            "description" => "nullable|string",
            "image" => "nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048",
            "background_image" => "nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048",
            "meta_title" => "nullable|string|max:255",
            "meta_description" => "nullable|string",
            "meta_keywords" => "nullable|string|max:255",
            "status" => "required|boolean",
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?: Str::slug($request->name);
        
        // Ensure slug uniqueness (excluding current record)
        $originalSlug = $slug;
        $counter = 1;
        while (SubCategory::where('slug', $slug)->where('id', '!=', $subCategory->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $subCategory->name = $request->name;
        $subCategory->slug = $slug;
        $subCategory->product_category_id = $request->category_id;
        $subCategory->description = $request->description;
        $subCategory->meta_title = $request->meta_title;
        $subCategory->meta_description = $request->meta_description;
        $subCategory->meta_keywords = $request->meta_keywords;
        $subCategory->status = $request->status;

        // Handle image uploads
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($subCategory->image && Storage::disk('public')->exists($subCategory->image)) {
                Storage::disk('public')->delete($subCategory->image);
            }
            $imagePath = $request->file('image')->store('subcategory_images', 'public');
            $subCategory->image = $imagePath;
        }

        if ($request->hasFile('background_image')) {
            // Delete old background image if exists
            if ($subCategory->background_image && Storage::disk('public')->exists($subCategory->background_image)) {
                Storage::disk('public')->delete($subCategory->background_image);
            }
            $backgroundImagePath = $request->file('background_image')->store('subcategory_background_images', 'public');
            $subCategory->background_image = $backgroundImagePath;
        }

        $subCategory->save();
        
        flash()->success("SubCategory updated successfully.");
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        if ($subCategory) {
            // Delete associated images
            if ($subCategory->image && Storage::disk('public')->exists($subCategory->image)) {
                Storage::disk('public')->delete($subCategory->image);
            }
            if ($subCategory->background_image && Storage::disk('public')->exists($subCategory->background_image)) {
                Storage::disk('public')->delete($subCategory->background_image);
            }
            
            $subCategory->delete();
            flash()->success('SubCategory deleted successfully.');
        }
        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:sub_categories,id'],
        ]);

        $subCategories = SubCategory::whereIn('id', $validated['ids'])->get();

        if ($subCategories->isEmpty()) {
            flash()->error('No sub categories found for deletion.');
            return redirect()->back();
        }

        $deletedCount = 0;

        foreach ($subCategories as $subCategory) {
            if ($subCategory->image && Storage::disk('public')->exists($subCategory->image)) {
                Storage::disk('public')->delete($subCategory->image);
            }
            if ($subCategory->background_image && Storage::disk('public')->exists($subCategory->background_image)) {
                Storage::disk('public')->delete($subCategory->background_image);
            }

            $subCategory->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            flash()->success("{$deletedCount} sub category(s) deleted successfully.");
        } else {
            flash()->warning('No sub categories were deleted.');
        }

        return redirect()->back();
    }

    /**
     * Check slug availability for AJAX requests
     */
    public function checkSlugAvailability(Request $request)
    {
        $slug = $request->query('slug');
        
        if (!$slug) {
            return response()->json(['available' => false, 'message' => 'Slug is required']);
        }

        $exists = SubCategory::where('slug', $slug)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug already exists' : 'Slug is available'
        ]);
    }
}
