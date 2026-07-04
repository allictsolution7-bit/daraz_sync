<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PostSubCategory;

class PostController extends Controller
{
    public function Add()
    {
        $posts = Post::all();
        $categories = PostCategory::all();
        $subcategories = PostSubCategory::all();
        return view('admin.post.add', compact('categories', 'subcategories', 'posts'));
    }



    public function ValidatePostSlug(Request $request)
    {
        $slug = $request->query('slug');
        // Check if slug already exists in the database
        $exists = Post::where('slug', $slug)->exists();
        return response()->json(['valid' => !$exists]); // If exists, return false
    }


    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'post_category_id' => 'required|exists:post_categories,id', // Parent category
            'post_sub_category_id' => 'nullable|exists:post_sub_categories,id', // Subcategory
            'title' => 'required|string|max:255', // Post title
            'content' => 'required', // Post content
            'slug' => 'nullable|string|max:255', // Optional slug
            'meta_title' => 'nullable|string|max:255', // Optional meta title
            'meta_description' => 'nullable|string', // Optional meta description
            'canonical_url' => 'nullable|url', // Optional canonical URL
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:2048', // Featured image
            'image_alt' => 'nullable|string|max:255', // Alt text for the image
            'tags' => 'nullable|string', // Optional tags
        ]);

        // Create the post
        $post = new Post();
        $post->post_category_id = $request->post_category_id;
        $post->post_sub_category_id = $request->post_sub_category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->meta_title = $request->meta_title;
        $post->meta_description = $request->meta_description;
        $post->canonical_url = $request->canonical_url;
        $post->tags = $request->tags;
        $post->created_by = auth()->id();

        // Generate unique slug
        $baseSlug = Str::slug($request->slug ?: $request->title);
        $slug = $baseSlug;

        $existingCount = Post::where('slug', 'like', $baseSlug . '%')->count();
        if ($existingCount > 0) {
            $slug = $baseSlug . '-' . ($existingCount + 1);
        }

        $post->slug = $slug;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        $post->image_alt = $request->image_alt;

        $post->save();

        return redirect()->back()->with('success', 'Post created successfully!');
    }



    public function Index()
    {
        $posts = Post::with(['postCategory', 'postSubCategory'])->get();
        return view('admin.post.index', compact('posts'));
    }

    public function View($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.view', compact('post'));
    }


    public function Edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = PostCategory::all();
        $subcategories = PostSubCategory::all();
        return view('admin.post.edit', compact('post', 'categories', 'subcategories'));
    }



    // public function update(Request $request, Post $post)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'post_category_id' => 'required|exists:post_categories,id', // Parent category
    //         'post_sub_category_id' => 'nullable|exists:post_sub_categories,id', // Subcategory
    //         'title' => 'required|string|max:255', // Post title
    //         'content' => 'required', // Post content
    //         'slug' => 'nullable|string|max:255', // Optional slug
    //         'meta_title' => 'nullable|string|max:255', // Optional meta title
    //         'meta_description' => 'nullable|string', // Optional meta description
    //         'canonical_url' => 'nullable|url', // Optional canonical URL
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Featured image
    //         'image_alt' => 'nullable|string|max:255', // Alt text for the image
    //         'tags' => 'nullable|string', // Optional tags
    //     ]);

    //     // Update post fields
    //     $post->post_category_id = $request->post_category_id;
    //     $post->post_sub_category_id = $request->post_sub_category_id;
    //     $post->title = $request->title;
    //     $post->content = $request->content;
    //     $post->meta_title = $request->meta_title;
    //     $post->meta_description = $request->meta_description;
    //     $post->canonical_url = $request->canonical_url;
    //     $post->tags = $request->tags;

    //     // Make sure created_by is set if it's null
    //     if (!$post->created_by) {
    //         $post->created_by = auth()->id();
    //     }

    //     // Update slug if title or slug field is provided
    //     if ($request->slug || $request->title !== $post->title) {
    //         $baseSlug = Str::slug($request->slug ?: $request->title);
    //         $slug = $baseSlug;

    //         // Check for existing slugs in posts, excluding the current post
    //         $existingCount = Post::where('slug', 'like', $baseSlug . '%')->where('id', '!=', $post->id)->count();

    //         if ($existingCount > 0) {
    //             $slug = $baseSlug . '-' . ($existingCount + 1);
    //         }

    //         $post->slug = $slug;
    //     }

    //     // Handle image upload
    //     if ($request->hasFile('image')) {
    //         // Delete the old image if it exists
    //         if ($post->image && \Storage::disk('public')->exists($post->image)) {
    //             \Storage::disk('public')->delete($post->image);
    //         }

    //         // Store the new image
    //         $path = $request->file('image')->store('posts', 'public');
    //         $post->image = $path;
    //     }

    //     // Update alt text for the image
    //     $post->image_alt = $request->image_alt;

    //     // Save the updated post
    //     $post->save();

    //     // Redirect to the post view page instead of back to the form
    //     return redirect()->route('admin.post.view', $post->id)->with('success', 'Post updated successfully!');
    // }

    public function update(Request $request, $id)
    {
        // Find the post by ID instead of relying on route model binding
        $post = Post::findOrFail($id);

        // Validate the request
        $request->validate([
            'post_category_id' => 'required|exists:post_categories,id',
            'post_sub_category_id' => 'nullable|exists:post_sub_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);

        // Update post fields
        $post->post_category_id = $request->post_category_id;
        $post->post_sub_category_id = $request->post_sub_category_id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->meta_title = $request->meta_title;
        $post->meta_description = $request->meta_description;
        $post->canonical_url = $request->canonical_url;
        $post->tags = $request->tags;

        // Make sure created_by is set if it's null
        if (!$post->created_by) {
            $post->created_by = auth()->id();
        }

        // Only update slug if explicitly provided or if title has changed
        if (($request->filled('slug') && $request->slug !== $post->slug) ||
            ($request->title !== $post->title && !$request->filled('slug'))
        ) {
            $baseSlug = Str::slug($request->slug ?: $request->title);
            $slug = $baseSlug;

            // Check for existing slugs in posts, excluding the current post
            $existingPost = Post::where('slug', $baseSlug)
                ->where('id', '!=', $post->id)
                ->first();

            if ($existingPost) {
                // Find a unique slug by appending numbers
                $counter = 1;
                while (Post::where('slug', $baseSlug . '-' . $counter)
                    ->where('id', '!=', $post->id)
                    ->exists()
                ) {
                    $counter++;
                }
                $slug = $baseSlug . '-' . $counter;
            }

            $post->slug = $slug;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            // Store the new image
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
        }

        // Update alt text for the image
        $post->image_alt = $request->image_alt;

        // Save the updated post
        $post->save();

        // Redirect to the post view page
        return redirect()->route('admin.post.view', $post->id)->with('success', 'Post updated successfully!');
    }


    public function Destroy($id)
    {
        $post = Post::findOrFail($id);

        // Delete the image file from the 'public/categories' folder if it exists
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        // Delete the category
        $post->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Post deleted successfully!');
    }
}
