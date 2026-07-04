<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory as ProductSubCategory;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap using admin panel settings...');

        // Get sitemap settings from admin panel
        $includeProducts = \App\Models\SiteSetting::get('seo', 'sitemap_include_products', '1') == '1';
        $includeCategories = \App\Models\SiteSetting::get('seo', 'sitemap_include_categories', '1') == '1';
        $includePages = \App\Models\SiteSetting::get('seo', 'sitemap_include_pages', '1') == '1';
        $includeBlog = \App\Models\SiteSetting::get('seo', 'sitemap_include_blog', '1') == '1';
        $includeBlogCategories = \App\Models\SiteSetting::get('seo', 'sitemap_include_blog_categories', '1') == '1';
        $includeWriters = \App\Models\SiteSetting::get('seo', 'sitemap_include_writers', '1') == '1';
        $includePublishers = \App\Models\SiteSetting::get('seo', 'sitemap_include_publishers', '1') == '1';

        $sitemap = Sitemap::create();
        $urlsCount = 0;

        // Add home page
        $sitemap->add(Url::create('/')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));
        $urlsCount++;
        $this->info('Added home page');

        // Add shop page
        $sitemap->add(Url::create('/shop')
            ->setLastModificationDate(Carbon::yesterday())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9));
        $urlsCount++;
        $this->info('Added shop page');

        // Add product categories if enabled
        if ($includeCategories) {
            $categories = ProductCategory::all();
            $this->info('Adding ' . $categories->count() . ' product categories to sitemap');
            foreach ($categories as $category) {
                $sitemap->add(Url::create("/shop/{$category->slug}")
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8));
                $urlsCount++;
            }

            // Add subcategories
            $subcategories = ProductSubCategory::with('product_category')->get();
            $this->info('Adding ' . $subcategories->count() . ' product subcategories to sitemap');
            foreach ($subcategories as $subcategory) {
                if ($subcategory->product_category) {
                    $sitemap->add(Url::create("/shop/{$subcategory->product_category->slug}/{$subcategory->slug}")
                        ->setLastModificationDate($subcategory->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7));
                    $urlsCount++;
                }
            }
        } else {
            $this->info('Skipping product categories (disabled in settings)');
        }

        // Add products if enabled
        if ($includeProducts) {
            $products = Product::where('status', 1)->get();
            $this->info('Adding ' . $products->count() . ' products to sitemap');
            foreach ($products as $product) {
                $slug = $this->createSlug($product->title);
                $sitemap->add(Url::create("/product/{$product->id}/{$slug}")
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping products (disabled in settings)');
        }

        // Add pages if enabled
        if ($includePages) {
            $pages = Page::all();
            $this->info('Adding ' . $pages->count() . ' pages to sitemap');
            foreach ($pages as $page) {
                $sitemap->add(Url::create("/{$page->slug}")
                    ->setLastModificationDate($page->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping pages (disabled in settings)');
        }

        // Add blog main page if enabled
        if ($includeBlog) {
            $sitemap->add(Url::create("/blog")
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8));
            $urlsCount++;
            $this->info('Added blog main page');

            // Add blog posts
            $posts = Post::get();
            $this->info('Adding ' . $posts->count() . ' blog posts to sitemap');
            foreach ($posts as $post) {
                $sitemap->add(Url::create("/blog/{$post->slug}")
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping blog (disabled in settings)');
        }

        // Add blog categories if enabled
        if ($includeBlogCategories) {
            $postCategories = PostCategory::all();
            $this->info('Adding ' . $postCategories->count() . ' blog categories to sitemap');
            foreach ($postCategories as $category) {
                $sitemap->add(Url::create("/blog/category/{$category->slug}")
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping blog categories (disabled in settings)');
        }

        // Add writers if enabled
        if ($includeWriters) {
            $writers = \App\Models\Writer::all();
            $this->info('Adding ' . $writers->count() . ' writers to sitemap');
            foreach ($writers as $writer) {
                $sitemap->add(Url::create("/writers/{$writer->id}")
                    ->setLastModificationDate($writer->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.5));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping writers (disabled in settings)');
        }

        // Add publishers if enabled
        if ($includePublishers) {
            $publishers = \App\Models\Publisher::all();
            $this->info('Adding ' . $publishers->count() . ' publishers to sitemap');
            foreach ($publishers as $publisher) {
                $sitemap->add(Url::create("/publishers/{$publisher->id}")
                    ->setLastModificationDate($publisher->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.5));
                $urlsCount++;
            }
        } else {
            $this->info('Skipping publishers (disabled in settings)');
        }

        // Save the sitemap to public path
        $sitemap->writeToFile(public_path('sitemap.xml'));
        
        $fileSize = number_format(filesize(public_path('sitemap.xml')) / 1024, 2);
        
        $this->info("Sitemap generated successfully!");
        $this->info("Total URLs: {$urlsCount}");
        $this->info("File size: {$fileSize} KB");
        $this->info("File location: " . public_path('sitemap.xml'));
    }

    private function createSlug($text)
    {
        return strtolower(
            preg_replace('/[^\w-]+/', '-', $text)
        );
    }
}