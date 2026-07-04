<?php

namespace App\Http\Controllers\Admin;

use App\Models\PostCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostCategoryController extends Controller
{
    public function Add()
    {
        $categories = PostCategory::all();
        return view('admin.post.category.add', compact('categories'));
    }

    public function PostCategorySlug(Request $request)
    {
        $slug = $request->query('slug');
        // Check if slug already exists in the database
        $exists = PostCategory::where('slug', $slug)->exists();
        return response()->json(['valid' => !$exists]); // If exists, return false
    }

    public function Store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'is_featured' => 'required|boolean',
        ]);

        // Create the category instance
        $category = new PostCategory();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->canonical_url = $request->canonical_url;
        $category->is_featured = $request->is_featured;
        $category->image_alt = $request->image_alt;
        $category->created_by = auth()->id(); // Assuming you want to track the user who created it

        // Generate and validate unique slug
        $baseSlug = Str::slug($request->slug ?: $request->name);
        $slug = $baseSlug;
        $count = PostCategory::where('slug', 'like', $baseSlug . '%')->count();

        if ($count > 0) {
            $slug = $baseSlug . '-' . ($count + 1);
        }

        $category->slug = $slug;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('postcategories', 'public');
            $category->image = $path;
        }

        // Save the category
        $category->save();


        // Redirect with success message
        return redirect()->back()->with('success', 'Post Category added successfully!');
    }

    public function Index()
    {
        $categories = PostCategory::all();
        return view('admin.post.category.index', compact('categories'));
    }

    public function View($id)
    {
        $category = PostCategory::findOrFail($id);
        return view('admin.post.category.view', compact('category'));
    }


    public function Edit($id)
    {
        $category = PostCategory::findOrFail($id);
        $subcategory = PostCategory::findOrFail($id);
        return view('admin.post.category.edit', compact('category'));
    }



    public function Update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|unique:post_categories,name,' . $id,
            'slug' => 'required|unique:post_categories,slug,' . $id,
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'is_featured' => 'required|boolean',
        ]);

        // Find the category
        $category = PostCategory::findOrFail($id);

        // Update fields
        $category->name = $request->name;
        $category->description = $request->description;
        $category->slug = $request->slug;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->canonical_url = $request->canonical_url;
        $category->image_alt = $request->image_alt;
        $category->is_featured = $request->is_featured;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Store the new image
            $category->image = $request->file('image')->store('postcategories', 'public');
        }

        // Save the updated category
        $category->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Category updated successfully!');
    }



    public function Destroy($id)
    {
        $category = PostCategory::findOrFail($id);

        // Delete the image file from the 'public/categories' folder if it exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Delete the category
        $category->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function getSubcategories($categoryId)
    {
        $category = PostCategory::with('postsubcategories')->find($categoryId);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return response()->json($category->postsubcategories);
    }
}
