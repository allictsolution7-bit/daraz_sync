<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // General Settings
        $generalSettings = [
            'site_name' => 'Thikana Shop',
            'logo_width' => '150',
            'mobile_logo_width' => '120',
            'gtm_id' => 'GTM-N6R8CHHV',
            'preloader_show' => '1',
            'preloader_text' => 'Thikana Shop',
            'primary_color' => '#F02627',
            'secondary_color' => '#113056',
            'accent_color' => '#F02627',
            'phone_number' => '+8801723-000000',
            'whatsapp_number' => '+8801723-000000',
            'contact_email' => 'info@thikanashop.com',
            'address' => 'Dhaka, Bangladesh',
            'contact_heading' => 'Get In Touch',
            'contact_location' => '27 Shaptak Square, Level-7, Holding-02, Road-27, Dhanmondi, Dhaka',
            'contact_support_email' => 'contact@uddoktaecommerce.com',
            'contact_whatsapp' => '+8801304224233',
            'contact_map_url' => 'https://www.google.com/maps/embed?...',
            'facebook_url' => 'https://facebook.com/thikanashop',
            'twitter_url' => 'https://twitter.com/thikanashop',
            'instagram_url' => 'https://instagram.com/thikanashop',
            'linkedin_url' => 'https://linkedin.com/company/thikanashop',
            'youtube_url' => 'https://youtube.com/c/thikanashop',
            'global_video_url' => '',
            'top_header_bar_show' => '1',
            'top_header_bar_left' => 'অনলাইন বই দোকানে আপনাকে স্বাগতম!',
            'top_header_bar_email' => 'example@gmail.com',
            'top_header_bar_phone' => '+8801723-000000',
            'header_layout' => 'v1',
            'main_navigation_show' => '1',
            'footer_copyright' => '© 2025 Thikana . All Rights Reserved. Developed By SOFTEB.COM',
            'newsletter_show' => '1',
            'free_shipping_amount' => '0',
            'show_delivery_info' => '1',
            'delivery_info' => '<ul><li>Free delivery on orders above ৳500</li><li>Standard delivery: 2-3 business days</li><li>Express delivery: 1-2 business days</li></ul>',
            'show_order_timeline' => '1',
            'view_all_button_text' => 'View More',
        ];

        foreach ($generalSettings as $key => $value) {
            SiteSetting::set('general', $key, $value);
        }

        // SEO Settings
        $seoSettings = [
            'default_meta_title' => 'Thikana Shop - Your Ultimate Fashion Destination',
            'default_meta_description' => 'Discover the latest fashion trends at Thikana Shop. Shop premium quality clothing, accessories, and more at competitive prices. Fast shipping, secure payments, and excellent customer service.',
            'default_meta_keywords' => 'fashion, clothing, accessories, online shopping, thikana shop',
            'site_author' => 'Thikana Shop',
            'twitter_username' => '@thikanashop',
            'google_search_console' => '',
            'bing_webmaster' => '',
            'yandex_webmaster' => '',
            'robots_meta' => 'index, follow',
            'canonical_base' => url('/'),
            'enable_schema' => '1',
            'organization_schema' => '',
            'auto_generate_sitemap' => '1',
            'sitemap_include_products' => '1',
            'sitemap_include_categories' => '1',
            'sitemap_include_pages' => '1',
            'sitemap_include_blog' => '1',
            'sitemap_include_blog_categories' => '1',
            'sitemap_include_writers' => '1',
            'sitemap_include_publishers' => '1',
        ];

        foreach ($seoSettings as $key => $value) {
            SiteSetting::set('seo', $key, $value);
        }

        // Footer Settings
        $footerSettings = [
            'about_website' => 'Thikana Shop is your premier destination for fashion and lifestyle products. We offer a wide range of high-quality clothing, accessories, and more at competitive prices.',
        ];

        foreach ($footerSettings as $key => $value) {
            SiteSetting::set('footer', $key, $value);
        }

        // Ecommerce Settings
        $ecommerceSettings = [
            'cod' => '1',
            'bkash' => '1',
            'nagad' => '1',
            'rocket' => '1',
        ];

        foreach ($ecommerceSettings as $key => $value) {
            SiteSetting::set('ecommerce', $key, $value);
        }
    }
}