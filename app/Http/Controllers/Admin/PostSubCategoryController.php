<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use App\Models\PostSubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostSubCategoryController extends Controller
{
    public function Add()
    {
        $categories = PostSubCategory::all();
        return view('admin.post.subcategory.add', compact('categories'));
    }

    public function PostSubCategorySlug(Request $request)
    {
        $slug = $request->query('slug');
        // Check if slug already exists in the database
        $exists = PostSubCategory::where('slug', $slug)->exists();
        return response()->json(['valid' => !$exists]); // If exists, return false
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'post_category_id' => 'required|exists:post_categories,id', // Validate parent category
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
        ]);

        // Create the subcategory instance
        $subcategory = new PostSubCategory();
        $subcategory->post_category_id = $request->post_category_id; // Set parent category
        $subcategory->name = $request->name;
        $subcategory->description = $request->description;
        $subcategory->meta_title = $request->meta_title;
        $subcategory->meta_description = $request->meta_description;
        $subcategory->canonical_url = $request->canonical_url;
        $subcategory->image_alt = $request->image_alt;
        $subcategory->created_by = auth()->id();

        // Generate and validate unique slug
        $baseSlug = Str::slug($request->slug ?: $request->name);
        $slug = $baseSlug;

        // Check for existing slugs in subcategories table
        $count = PostSubCategory::where('slug', 'like', $baseSlug . '%')->count();

        if ($count > 0) {
            $slug = $baseSlug . '-' . ($count + 1);
        }

        $subcategory->slug = $slug;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store in subcategories folder to keep separate from main categories
            $path = $request->file('image')->store('postsubcategories', 'public');
            $subcategory->image = $path;
        }

        // Save the subcategory
        $subcategory->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Sub Category added successfully!');
    }


    public function Index()
    {
        $subcategories = PostSubCategory::all();
        return view('admin.post.subcategory.index', compact('subcategories'));
    }

    public function View($id)
    {
        $subcategory = PostSubCategory::findOrFail($id);
        return view('admin.post.subcategory.view', compact('subcategory'));
    }


    public function Edit($id)
    {
        $subcategory = PostSubCategory::findOrFail($id);
        $categories = PostCategory::all();
        return view('admin.post.subcategory.edit', compact('subcategory', 'categories'));
    }



    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'post_category_id' => 'required|exists:post_categories,id', // Validate parent category
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
        ]);

        // Find the subcategory to update
        $subcategory = PostSubCategory::findOrFail($id);

        // Update fields
        $subcategory->post_category_id = $request->post_category_id; // Update parent category
        $subcategory->name = $request->name;
        $subcategory->description = $request->description;
        $subcategory->meta_title = $request->meta_title;
        $subcategory->meta_description = $request->meta_description;
        $subcategory->canonical_url = $request->canonical_url;
        $subcategory->image_alt = $request->image_alt;

        // Generate and validate unique slug
        $baseSlug = Str::slug($request->slug ?: $request->name);
        $slug = $baseSlug;

        // Check if the slug already exists for other subcategories
        $existingSlugCount = PostSubCategory::where('slug', $slug)
            ->where('id', '!=', $id)
            ->count();

        if ($existingSlugCount > 0) {
            $slug = $baseSlug . '-' . ($existingSlugCount + 1);
        }

        $subcategory->slug = $slug;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($subcategory->image) {
                Storage::disk('public')->delete($subcategory->image);
            }

            // Store the new image
            $path = $request->file('image')->store('postsubcategories', 'public');
            $subcategory->image = $path;
        }

        // Save the changes
        $subcategory->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Sub Category updated successfully!');
    }




    public function Destroy($id)
    {
        $category = PostSubCategory::findOrFail($id);

        // Delete the image file from the 'public/categories' folder if it exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete the category
        $category->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Sub Category deleted successfully!');
    }
}
