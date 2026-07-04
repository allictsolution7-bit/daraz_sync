<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostSubCategory;
use App\Models\Comment;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BlogFrontController extends Controller
{
    public function blog(): View|\Illuminate\Http\JsonResponse
    {
        // Regular posts
        $posts = Post::with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get featured categories
        $featuredCategories = PostCategory::where('is_featured', 1)->get();

        // Get posts from featured categories
        $featuredCategoryPosts = [];
        foreach ($featuredCategories as $category) {
            $categoryPosts = Post::with(['postcategory', 'postsubcategory', 'user'])
                ->where('post_category_id', $category->id)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            if ($categoryPosts->count() > 0) {
                $featuredCategoryPosts[$category->id] = [
                    'category' => $category,
                    'posts' => $categoryPosts
                ];
            }
        }

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = 'Blog - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Explore our latest articles, tips, and insights about books, authors, and publishing industry.';
        $seoKeywords = 'blog, articles, books, authors, publishing, reading';
        $seoImage = SettingsService::getDefaultOgImage();
        $seoType = 'website';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        // Check if this is an AJAX request
        if (request()->ajax()) {
            $view = view('frontend.blog.partials.post-items', compact('posts'))->render();
            return response()->json(['html' => $view]);
        }

        return view('frontend.blog.index', array_merge(compact(
            'posts',
            'categories',
            'recentPosts',
            'featuredCategoryPosts'
        ), $seoData));
    }

    public function loadMoreFeatured(Request $request)
    {
        $categoryId = $request->input('category');
        $loaded = $request->input('loaded', 0);

        // Get more posts for this specific category
        $morePosts = Post::with(['postcategory', 'postsubcategory', 'user'])
            ->where('post_category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->skip($loaded)
            ->take(3)
            ->get();

        // Check if there are more posts after this batch
        $totalPosts = Post::where('post_category_id', $categoryId)->count();
        $hasMore = ($loaded + $morePosts->count()) < $totalPosts;

        // Get the category name for the response
        $categoryName = PostCategory::find($categoryId)->name ?? '';

        $view = view('frontend.blog.partials.post-items', ['posts' => $morePosts])->render();

        return response()->json([
            'html' => $view,
            'count' => $morePosts->count(),
            'hasMore' => $hasMore,
            'categoryName' => $categoryName
        ]);
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 1);
        
        $posts = Post::with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'page', $page);

        $view = view('frontend.blog.partials.post-items', compact('posts'))->render();
        
        return response()->json(['html' => $view]);
    }

    public function blogShow($slug): View
    {
        $post = Post::where('slug', $slug)
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->firstOrFail();

        // Get paginated comments for this post
        $comments = Comment::where('post_id', $post->id)
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get related posts from the same category
        $relatedPosts = Post::where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->take(6)
            ->get();

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = $post->meta_title ?? $post->title . ' - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = $post->meta_description ?? substr(strip_tags($post->content), 0, 160);
        $seoKeywords = SettingsService::getDefaultMetaKeywords();
        $seoImage = $post->image ? asset($post->image) : SettingsService::getDefaultOgImage();
        $seoType = 'article';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        return view('frontend.blog.show', array_merge(compact(
            'post',
            'relatedPosts',
            'categories',
            'recentPosts',
            'comments'
        ), $seoData));
    }

    public function blogCategory($slug): View
    {
        $category = PostCategory::where('slug', $slug)
            ->firstOrFail();

        $posts = Post::where('post_category_id', $category->id)
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = $category->name . ' - Blog - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Browse all blog posts in the ' . $category->name . ' category.';
        $seoKeywords = $category->name . ', blog, articles, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = $category->image ? asset('storage/' . $category->image) : SettingsService::getDefaultOgImage();
        $seoType = 'website';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        return view('frontend.blog.index', array_merge(compact(
            'posts',
            'category',
            'categories',
            'recentPosts'
        ), $seoData));
    }

    public function blogSubcategory($categorySlug, $subcategorySlug): View
    {
        $category = PostCategory::where('slug', $categorySlug)
            ->firstOrFail();

        $subcategory = PostSubCategory::where('slug', $subcategorySlug)
            ->where('post_category_id', $category->id)
            ->firstOrFail();

        $posts = Post::where('post_sub_category_id', $subcategory->id)
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = $subcategory->name . ' - ' . $category->name . ' - Blog - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Browse all blog posts in the ' . $subcategory->name . ' subcategory.';
        $seoKeywords = $subcategory->name . ', ' . $category->name . ', blog, articles, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = $subcategory->image ? asset('storage/' . $subcategory->image) : SettingsService::getDefaultOgImage();
        $seoType = 'website';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        return view('frontend.blog.index', array_merge(compact(
            'category',
            'subcategory',
            'posts',
            'categories',
            'recentPosts'
        ), $seoData));
    }

    public function blogTag($tag): View
    {
        $posts = Post::where('tags', 'like', '%' . $tag . '%')
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = ucfirst($tag) . ' - Blog - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Explore our articles tagged with ' . $tag . ' - tips, insights, and information about books and publishing.';
        $seoKeywords = $tag . ', blog, ' . $tag . ' articles, ' . $tag . ' tips, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = SettingsService::getDefaultOgImage();
        $seoType = 'website';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        return view('frontend.blog.tag', array_merge(compact(
            'posts',
            'tag',
            'categories',
            'recentPosts'
        ), $seoData));
    }

    public function search(Request $request): View|RedirectResponse
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('blog.index');
        }

        $posts = Post::where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%');
            })
            ->with(['postcategory', 'postsubcategory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = PostCategory::withCount('posts')->get();
        $recentPosts = Post::latest()->take(5)->get();

        // Set SEO data
        $seoTitle = 'Search Results for "' . $query . '" - ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = 'Search results for "' . $query . '" in our blog.';
        $seoKeywords = $query . ', search, blog, articles, ' . SettingsService::getDefaultMetaKeywords();
        $seoImage = SettingsService::getDefaultOgImage();
        $seoType = 'website';

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, $seoType);

        return view('frontend.blog.search', array_merge(compact(
            'posts',
            'query',
            'categories',
            'recentPosts'
        ), $seoData));
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
}
