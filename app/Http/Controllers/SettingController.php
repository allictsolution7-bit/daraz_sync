<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Writer;
use App\Models\Publisher;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        // Clear settings cache to ensure fresh data
        SettingsService::clearCache();
        
        // Use SettingsService for consistent data loading
        $settings = SettingsService::group('general');
        $homepage = SettingsService::group('homepage');
        $header = SettingsService::group('header');
        $footer = SettingsService::group('footer');
        $seo = SettingsService::group('seo');
        $single_product = SettingsService::group('single_product');
        $registration = SettingsService::group('registration');
        $mobile_nav = SettingsService::group('mobile_nav');

        // For category/subcategory selection, fetch all categories/subcategories
        $categories = \App\Models\ProductCategory::all();
        $subcategories = \App\Models\SubCategory::all();
        $products = \App\Models\Product::where('status', 1)->get();

        return view('admin.settings.index', compact('settings', 'homepage', 'header', 'footer', 'seo', 'single_product', 'registration', 'mobile_nav', 'categories', 'subcategories', 'products'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'nullable|array',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:512',
            'global_category_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'seo_og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'featured_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'featured_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'featured_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'featured_image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'homepage.slider_side_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
        ]);

        // Save general settings (legacy)
        foreach ($request->input('settings', []) as $key => $value) {
            SettingsService::set('general', $key, $value);
        }

        // Save grouped settings (homepage, header, footer, etc.)
        foreach ($request->input('homepage', []) as $key => $value) {
            // Extra safe: If it's an array, encode as JSON
            if (is_array($value)) {
                $value = json_encode($value);
            }
            // If it's a string, save as-is (hidden input sends JSON string)
            SettingsService::set('homepage', $key, $value);
        }
        foreach ($request->input('header', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('header', $key, $value);
        }
        foreach ($request->input('footer', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('footer', $key, $value);
        }
        // Save SEO settings
        foreach ($request->input('seo', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('seo', $key, $value);
        }
        // Save ecommerce settings
        foreach ($request->input('ecommerce', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('ecommerce', $key, $value);
        }
        // Save single product settings
        foreach ($request->input('single_product', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('single_product', $key, $value);
        }
        // Save registration settings
        foreach ($request->input('registration', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('registration', $key, $value);
        }
        
        // Save mobile navigation settings
        foreach ($request->input('mobile_nav', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('mobile_nav', $key, $value);
        }

        // Save navigation settings (mega menu, etc.)
        foreach ($request->input('navigation', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('navigation', $key, $value);
        }

        // Save layout settings (container width, etc.)
        foreach ($request->input('layout', []) as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            SettingsService::set('layout', $key, $value);
        }

        // Handle Logo Upload
        if ($request->hasFile('site_logo')) {
            $logo = $request->file('site_logo');
            $logoPath = $logo->store('logos', 'public');

            // Delete old logo if exists
            $oldLogo = SettingsService::get('general', 'logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            SettingsService::set('general', 'logo', $logoPath);
        }

        // Handle Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $faviconPath = $favicon->store('favicons', 'public');

            // Delete old favicon if exists (check both new and legacy locations)
            $oldHeaderFavicon = SettingsService::get('header', 'favicon');
            if ($oldHeaderFavicon) {
                Storage::disk('public')->delete($oldHeaderFavicon);
            }

            $legacyFavicon = SettingsService::get('general', 'favicon');
            if ($legacyFavicon && $legacyFavicon !== $oldHeaderFavicon) {
                Storage::disk('public')->delete($legacyFavicon);
            }

            // Store under header group (primary) and general group (legacy compatibility)
            SettingsService::set('header', 'favicon', $faviconPath);
            SettingsService::set('general', 'favicon', $faviconPath);
        }

        // Handle Global Category/Subcategory Banner Image Upload
        if ($request->hasFile('global_category_bg')) {
            $globalBg = $request->file('global_category_bg');
            $globalBgPath = $globalBg->store('category_background_images', 'public');

            // Delete old global bg if exists
            $oldGlobalBg = SettingsService::get('homepage', 'global_category_bg');
            if ($oldGlobalBg) {
                Storage::disk('public')->delete($oldGlobalBg);
            }

            SettingsService::set('homepage', 'global_category_bg', $globalBgPath);
        }

        // Handle Featured Images Upload and Deletion
        for ($i = 1; $i <= 4; $i++) {
            $imageKey = "featured_image_{$i}";
            $deleteKey = "delete_featured_image_{$i}";
            
            // Check if image should be deleted
            if ($request->input("homepage.{$deleteKey}") == '1') {
                $oldImage = SettingsService::get('homepage', $imageKey);
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
                SettingsService::set('homepage', $imageKey, null);
                SettingsService::set('homepage', "{$imageKey}_alt", null);
                SettingsService::set('homepage', "{$imageKey}_link", null);
                continue;
            }
            
            // Handle new image upload
            if ($request->hasFile($imageKey)) {
                $image = $request->file($imageKey);
                $imagePath = $image->store('featured_images', 'public');

                // Delete old image if exists
                $oldImage = SettingsService::get('homepage', $imageKey);
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }

                SettingsService::set('homepage', $imageKey, $imagePath);
            }
        }

        // Handle Slider Side Image Upload/Deletion (for slider_with_one_image layout)
        if ($request->input('homepage.slider_side_image_delete') == '1') {
            $oldSide = SettingsService::get('homepage', 'slider_side_image');
            if ($oldSide) {
                Storage::disk('public')->delete($oldSide);
            }
            SettingsService::set('homepage', 'slider_side_image', null);
            SettingsService::set('homepage', 'slider_side_image_alt', null);
            SettingsService::set('homepage', 'slider_side_image_link', null);
        } elseif ($request->hasFile('homepage.slider_side_image')) {
            $sideImage = $request->file('homepage.slider_side_image');
            $sideImagePath = $sideImage->store('slider_side_image', 'public');

            // Delete old side image if exists
            $oldSide = SettingsService::get('homepage', 'slider_side_image');
            if ($oldSide) {
                Storage::disk('public')->delete($oldSide);
            }

            SettingsService::set('homepage', 'slider_side_image', $sideImagePath);
        }



        // Handle SEO OG Image Upload
        if ($request->hasFile('seo_og_image')) {
            $seoOgImage = $request->file('seo_og_image');
            $seoOgImagePath = $seoOgImage->store('seo', 'public');

            // Delete old SEO OG image if exists
            $oldSeoOgImage = SettingsService::get('seo', 'default_og_image');
            if ($oldSeoOgImage) {
                Storage::disk('public')->delete($oldSeoOgImage);
            }

            SettingsService::set('seo', 'default_og_image', $seoOgImagePath);
        }

        // Clear settings cache after update
        SettingsService::clearCache();
        
        // Clear product settings cache so changes appear immediately
        \Cache::forget('product_card_settings');
        
        // Preserve tab parameter in redirect
        $tab = $request->input('tab');
        if ($tab) {
            return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Settings updated successfully.');
        }
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Generate sitemap via AJAX
     */
    public function generateSitemap(Request $request)
    {
        try {
            // Get sitemap settings
            $includeProducts = \App\Models\SiteSetting::get('seo', 'sitemap_include_products', '1') == '1';
            $includeCategories = \App\Models\SiteSetting::get('seo', 'sitemap_include_categories', '1') == '1';
            $includePages = \App\Models\SiteSetting::get('seo', 'sitemap_include_pages', '1') == '1';
            $includeBlog = \App\Models\SiteSetting::get('seo', 'sitemap_include_blog', '1') == '1';
            $includeBlogCategories = \App\Models\SiteSetting::get('seo', 'sitemap_include_blog_categories', '1') == '1';
            $includeWriters = \App\Models\SiteSetting::get('seo', 'sitemap_include_writers', '1') == '1';
            $includePublishers = \App\Models\SiteSetting::get('seo', 'sitemap_include_publishers', '1') == '1';

            $sitemap = \Spatie\Sitemap\Sitemap::create();
            $urlsCount = 0;

            // Add home page
            $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/')
                ->setLastModificationDate(\Carbon\Carbon::yesterday())
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0));
            $urlsCount++;

            // Add shop page
            $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/shop')
                ->setLastModificationDate(\Carbon\Carbon::yesterday())
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9));
            $urlsCount++;

            // Add product categories if enabled
            if ($includeCategories) {
                $categories = \App\Models\ProductCategory::all();
                foreach ($categories as $category) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/shop/{$category->slug}")
                        ->setLastModificationDate($category->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8));
                    $urlsCount++;
                }
            }

            // Add subcategories if enabled
            if ($includeCategories) {
                $subcategories = \App\Models\SubCategory::with('product_category')->get();
                foreach ($subcategories as $subcategory) {
                    if ($subcategory->product_category) {
                        $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/shop/{$subcategory->product_category->slug}/{$subcategory->slug}")
                            ->setLastModificationDate($subcategory->updated_at)
                            ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7));
                        $urlsCount++;
                    }
                }
            }

            // Add products if enabled
            if ($includeProducts) {
                $products = \App\Models\Product::where('status', 1)->get();
                foreach ($products as $product) {
                    $slug = $this->createSlug($product->title);
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/product/{$product->id}/{$slug}")
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7));
                    $urlsCount++;
                }
            }

            // Add pages if enabled
            if ($includePages) {
                $pages = \App\Models\Page::all();
                foreach ($pages as $page) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/{$page->slug}")
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6));
                    $urlsCount++;
                }
            }

            // Add blog main page if enabled
            if ($includeBlog) {
                $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/blog")
                    ->setLastModificationDate(\Carbon\Carbon::yesterday())
                    ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.8));
                $urlsCount++;
            }

            // Add blog posts if enabled
            if ($includeBlog) {
                $posts = \App\Models\Post::get();
                foreach ($posts as $post) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/blog/{$post->slug}")
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7));
                    $urlsCount++;
                }
            }

            // Add blog categories if enabled
            if ($includeBlogCategories) {
                $postCategories = \App\Models\PostCategory::all();
                foreach ($postCategories as $category) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/blog/category/{$category->slug}")
                        ->setLastModificationDate($category->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6));
                    $urlsCount++;
                }
            }

            // Add writers if enabled
            if ($includeWriters) {
                $writers = \App\Models\Writer::all();
                foreach ($writers as $writer) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/writers/{$writer->id}")
                        ->setLastModificationDate($writer->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.5));
                    $urlsCount++;
                }
            }

            // Add publishers if enabled
            if ($includePublishers) {
                $publishers = \App\Models\Publisher::all();
                foreach ($publishers as $publisher) {
                    $sitemap->add(\Spatie\Sitemap\Tags\Url::create("/publishers/{$publisher->id}")
                        ->setLastModificationDate($publisher->updated_at)
                        ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.5));
                    $urlsCount++;
                }
            }

            // Save the sitemap
            $sitemap->writeToFile(public_path('sitemap.xml'));
            
            // Get file size
            $fileSize = number_format(filesize(public_path('sitemap.xml')) / 1024, 2);
            $generatedAt = now()->format('Y-m-d H:i:s');

            return response()->json([
                'success' => true,
                'message' => 'Sitemap generated successfully!',
                'urls_count' => $urlsCount,
                'file_size' => $fileSize,
                'generated_at' => $generatedAt
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate sitemap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create slug from text
     */
    private function createSlug($text)
    {
        return strtolower(
            preg_replace('/[^\w-]+/', '-', $text)
        );
    }
}
