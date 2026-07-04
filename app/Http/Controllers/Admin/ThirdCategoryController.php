<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\ThirdCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ThirdCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $third_categories = ThirdCategory::with([
            'subCategory.category',
            'products' => function($query) {
                $query->with(['category', 'additionalCategories']);
            }
        ])->ordered()->get();
        return view('admin.product.thirdCategory.index', compact('third_categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sub_categories = SubCategory::with('category')
            ->where('status', true)
            ->select(['id', 'name', 'product_category_id'])
            ->orderBy('name')
            ->get();
        return view('admin.product.thirdCategory.create', compact('sub_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?: Str::slug($request->name);
        
        // Ensure slug uniqueness within the same sub category
        $originalSlug = $slug;
        $counter = 1;
        while (ThirdCategory::where('sub_category_id', $request->sub_category_id)
            ->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $thirdCategory = ThirdCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'sub_category_id' => $request->sub_category_id,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->status ?: true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        // Handle image uploads
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('third_category_images', 'public');
            $thirdCategory->update(['image' => $imagePath]);
        }

        if ($request->hasFile('background_image')) {
            $backgroundImagePath = $request->file('background_image')->store('third_category_background_images', 'public');
            $thirdCategory->update(['background_image' => $backgroundImagePath]);
        }

        if ($thirdCategory) {
            flash()->success('Third Category created successfully.');
        } else {
            flash()->error('Third Category could not be created.');
        }
        
        return redirect()->route('admin.third-categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ThirdCategory $thirdCategory)
    {
        $thirdCategory->load('subCategory.category');
        return view('admin.product.thirdCategory.show', compact('thirdCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThirdCategory $thirdCategory)
    {
        $sub_categories = SubCategory::with('category')
            ->select(['id', 'name', 'product_category_id'])
            ->orderBy('name')
            ->get();
        return view('admin.product.thirdCategory.edit', compact('thirdCategory', 'sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThirdCategory $thirdCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?: Str::slug($request->name);
        
        // Ensure slug uniqueness within the same sub category (excluding current record)
        $originalSlug = $slug;
        $counter = 1;
        while (ThirdCategory::where('sub_category_id', $request->sub_category_id)
            ->where('slug', $slug)
            ->where('id', '!=', $thirdCategory->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $thirdCategory->name = $request->name;
        $thirdCategory->slug = $slug;
        $thirdCategory->sub_category_id = $request->sub_category_id;
        $thirdCategory->description = $request->description;
        $thirdCategory->meta_title = $request->meta_title;
        $thirdCategory->meta_description = $request->meta_description;
        $thirdCategory->meta_keywords = $request->meta_keywords;
        $thirdCategory->status = $request->status;
        $thirdCategory->sort_order = $request->sort_order ?? 0;

        // Handle image uploads
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($thirdCategory->image && Storage::disk('public')->exists($thirdCategory->image)) {
                Storage::disk('public')->delete($thirdCategory->image);
            }
            $imagePath = $request->file('image')->store('third_category_images', 'public');
            $thirdCategory->image = $imagePath;
        }

        if ($request->hasFile('background_image')) {
            // Delete old background image if exists
            if ($thirdCategory->background_image && Storage::disk('public')->exists($thirdCategory->background_image)) {
                Storage::disk('public')->delete($thirdCategory->background_image);
            }
            $backgroundImagePath = $request->file('background_image')->store('third_category_background_images', 'public');
            $thirdCategory->background_image = $backgroundImagePath;
        }

        $thirdCategory->save();
        
        flash()->success('Third Category updated successfully.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThirdCategory $thirdCategory)
    {
        if ($thirdCategory) {
            // Delete associated images
            if ($thirdCategory->image && Storage::disk('public')->exists($thirdCategory->image)) {
                Storage::disk('public')->delete($thirdCategory->image);
            }
            if ($thirdCategory->background_image && Storage::disk('public')->exists($thirdCategory->background_image)) {
                Storage::disk('public')->delete($thirdCategory->background_image);
            }
            
            $thirdCategory->delete();
            flash()->success('Third Category deleted successfully.');
        }
        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:third_categories,id'],
        ]);

        $thirdCategories = ThirdCategory::whereIn('id', $validated['ids'])->get();

        if ($thirdCategories->isEmpty()) {
            flash()->error('No third categories found for deletion.');
            return redirect()->back();
        }

        $deletedCount = 0;

        foreach ($thirdCategories as $thirdCategory) {
            if ($thirdCategory->image && Storage::disk('public')->exists($thirdCategory->image)) {
                Storage::disk('public')->delete($thirdCategory->image);
            }
            if ($thirdCategory->background_image && Storage::disk('public')->exists($thirdCategory->background_image)) {
                Storage::disk('public')->delete($thirdCategory->background_image);
            }

            $thirdCategory->delete();
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            flash()->success("{$deletedCount} third category(s) deleted successfully.");
        } else {
            flash()->warning('No third categories were deleted.');
        }

        return redirect()->back();
    }

    /**
     * Get third categories by subcategory (AJAX endpoint for product form)
     */
    public function getBySubCategory(Request $request)
    {
        $subCategoryIds = $request->input('sub_category_ids', []);
        
        if (empty($subCategoryIds)) {
            return response()->json([]);
        }

        $thirdCategories = ThirdCategory::whereIn('sub_category_id', $subCategoryIds)
            ->where('status', true)
            ->ordered()
            ->select('id', 'name', 'sub_category_id')
            ->get();

        return response()->json($thirdCategories);
    }

    /**
     * Check slug availability for AJAX requests
     */
    public function checkSlugAvailability(Request $request)
    {
        $slug = $request->query('slug');
        $subCategoryId = $request->query('sub_category_id');
        
        if (!$slug || !$subCategoryId) {
            return response()->json(['available' => false, 'message' => 'Slug and sub category are required']);
        }

        $exists = ThirdCategory::where('slug', $slug)
            ->where('sub_category_id', $subCategoryId)
            ->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Slug already exists' : 'Slug is available'
        ]);
    }
}
