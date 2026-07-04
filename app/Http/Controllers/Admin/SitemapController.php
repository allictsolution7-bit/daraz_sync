<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory as ProductSubCategory;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        // Add home page
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Add shop page
        $sitemap->add(Url::create('/shop')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));

        // Add all product categories
        $categories = ProductCategory::all();
        foreach ($categories as $category) {
            $sitemap->add(Url::create("/shop/{$category->slug}")
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        }

        // Add all subcategories
        $subcategories = ProductSubCategory::all();
        foreach ($subcategories as $subcategory) {
            // Get the category from the subcategory's category_id
            $category = ProductCategory::find($subcategory->product_category_id);
            if ($category) {
                $sitemap->add(Url::create("/shop/{$category->slug}/{$subcategory->slug}")
                    ->setLastModificationDate($subcategory->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7));
            }
        }


        // Add all products
        $products = Product::get();
        foreach ($products as $product) {
            $slug = $this->createSlug($product->title);
            $sitemap->add(Url::create("/product/{$product->id}/{$slug}")
                ->setLastModificationDate($product->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7));
        }

        // Add all pages
        $pages = Page::all();
        foreach ($pages as $page) {
            $sitemap->add(Url::create("/{$page->slug}")
                ->setLastModificationDate($page->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.6));
        }

        // Add blog main page
        $sitemap->add(Url::create("/blog")
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.8));

        // Add all blog posts
        $posts = Post::get();
        foreach ($posts as $post) {
            $sitemap->add(Url::create("/blog/{$post->slug}")
                ->setLastModificationDate($post->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7));
        }

        // Add all blog categories
        $postCategories = PostCategory::all();
        foreach ($postCategories as $category) {
            $sitemap->add(Url::create("/blog/category/{$category->slug}")
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.6));
        }

        // Add all blog tags
        // $postTags = PostTag::all();
        // foreach ($postTags as $tag) {
        //     $sitemap->add(Url::create("/blog/tag/{$tag->slug}")
        //         ->setLastModificationDate($tag->updated_at)
        //         ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        //         ->setPriority(0.5));
        // }

        return $sitemap->toResponse(request());
    }

    private function createSlug($text)
    {
        return strtolower(
            preg_replace('/[^\w-]+/', '-', $text)
        );
    }
}
