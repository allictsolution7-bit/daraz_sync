<?php

namespace App\Services;

use App\Models\SiteSetting;

class SettingsService
{
    private static $settings = null;
    private static $initialized = false;

    /**
     * Initialize settings - load all settings from database once
     */
    public static function initialize()
    {
        if (!self::$initialized) {
            $rawSettings = SiteSetting::all();
            
            // Map settings into groups, strip the vendor_{id}_ prefix for in-memory access if it matches the current user
            $userId = auth()->id();
            $vendorPrefix = $userId ? 'vendor_' . $userId . '_' : '';
            
            $settingsGrouped = [];
            foreach ($rawSettings as $s) {
                $group = $s->group;
                if ($userId && strpos($group, 'vendor_') === 0) {
                    // Check if it belongs to the current user
                    if (strpos($group, $vendorPrefix) === 0) {
                        $group = substr($group, strlen($vendorPrefix));
                    } else {
                        // Skip other users' settings in this session's memory
                        continue;
                    }
                }
                $settingsGrouped[$group][$s->key] = $s->value;
            }
            
            self::$settings = $settingsGrouped;
            self::$initialized = true;
        }
    }

    /**
     * Get a setting value
     */
    public static function get($group, $key, $default = null)
    {
        self::initialize();
        return self::$settings[$group][$key] ?? $default;
    }

    /**
     * Get all settings for a group
     */
    public static function group($group)
    {
        self::initialize();
        return self::$settings[$group] ?? [];
    }

    /**
     * Get all settings
     */
    public static function all()
    {
        self::initialize();
        return self::$settings;
    }

    /**
     * Set a setting value (also updates the in-memory cache)
     */
    public static function set($group, $key, $value)
    {
        $userId = auth()->id();
        $dbGroup = $group;
        if ($userId && strpos($group, 'vendor_') !== 0) {
            $dbGroup = 'vendor_' . $userId . '_' . $group;
        }
        
        $setting = SiteSetting::updateOrCreate(
            ['group' => $dbGroup, 'key' => $key],
            ['value' => $value]
        );

        // Update in-memory cache
        if (self::$initialized) {
            self::$settings[$group][$key] = $value;
        }

        return $setting;
    }

    /**
     * Clear the settings cache (useful after updates)
     */
    public static function clearCache()
    {
        self::$settings = null;
        self::$initialized = false;
    }

    // Convenience methods for commonly used settings
    public static function getSiteName()
    {
        return self::get('general', 'site_name', 'Thikana Shop');
    }

    public static function getContactEmail()
    {
        return self::get('general', 'contact_email', 'info@thikana.com');
    }

    public static function getPhoneNumber()
    {
        return self::get('general', 'phone_number', '+880 1304224233');
    }

    public static function getLogo()
    {
        $logo = self::get('general', 'logo');
        return $logo ? asset($logo) : asset('default-logo.png');
    }

    public static function getFavicon()
    {
        $favicon = self::get('header', 'favicon');

        if (!$favicon) {
            // Legacy fallback (older installs stored under general group)
            $favicon = self::get('general', 'favicon');
        }

        if ($favicon) {
            $publicPath = public_path($favicon);
            if (file_exists($publicPath)) {
                return asset($favicon);
            }

            $storagePath = storage_path('app/public/' . $favicon);
            if (file_exists($storagePath)) {
                return asset('storage/' . $favicon);
            }
        }
        
        // Fallback to public/favicon.ico if exists, otherwise new/logo.png
        if (file_exists(public_path('favicon.ico'))) {
            return asset('favicon.ico');
        }
        
        return asset('new/logo.png');
    }

    public static function getPrimaryColor()
    {
        return self::get('general', 'primary_color', '#F02627');
    }

    public static function getSecondaryColor()
    {
        return self::get('general', 'secondary_color', '#113056');
    }

    public static function getAccentColor()
    {
        return self::get('general', 'accent_color', '#F02627');
    }

    public static function getViewAllButtonText()
    {
        return self::get('general', 'view_all_button_text', 'View More');
    }

    // SEO Helper Methods
    public static function getDefaultMetaTitle()
    {
        return self::get('seo', 'default_meta_title', 'Thikana Shop - Your Ultimate Fashion Destination');
    }

    public static function getDefaultMetaDescription()
    {
        return self::get('seo', 'default_meta_description', 'Discover the latest fashion trends at Thikana Shop. Shop premium quality clothing, accessories, and more at competitive prices. Fast shipping, secure payments, and excellent customer service.');
    }

    public static function getDefaultMetaKeywords()
    {
        return self::get('seo', 'default_meta_keywords', 'fashion, clothing, accessories, online shopping, thikana shop');
    }

    public static function getDefaultOgImage()
    {
        $ogImage = self::get('seo', 'default_og_image');
        return $ogImage ? asset($ogImage) : asset('new/logo.png');
    }

    public static function getSiteAuthor()
    {
        return self::get('seo', 'site_author', 'Thikana Shop');
    }

    public static function getTwitterUsername()
    {
        return self::get('seo', 'twitter_username', '@thikanashop');
    }

    public static function getRobotsMeta()
    {
        return self::get('seo', 'robots_meta', 'index, follow');
    }

    public static function getGtmId()
    {
        return self::get('general', 'gtm_id', 'GTM-MMMMMMM');
    }

    // Additional convenience methods that were missing
    public static function isSchemaEnabled()
    {
        return self::get('seo', 'enable_schema', '1') == '1';
    }

    public static function getOrganizationSchema()
    {
        return self::get('seo', 'organization_schema', '');
    }

    public static function getCanonicalBase()
    {
        return self::get('seo', 'canonical_base', url('/'));
    }

    public static function getWhatsAppNumber()
    {
        return self::get('general', 'whatsapp_number', '+8801779542054');
    }

    public static function getAddress()
    {
        return self::get('general', 'address', '123 Fashion Street, Dhaka 1230, Bangladesh');
    }

    public static function getAboutWebsite()
    {
        return self::get('general', 'about_website', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Autem eos esse ipsum impedit consectetur blanditiis sed accusantium eius magnam inventore nulla d');
    }

    public static function getFacebookUrl()
    {
        return self::get('general', 'facebook_url');
    }

    public static function getInstagramUrl()
    {
        return self::get('general', 'instagram_url');
    }

    public static function getTwitterUrl()
    {
        return self::get('general', 'twitter_url');
    }

    public static function getYoutubeUrl()
    {
        return self::get('general', 'youtube_url');
    }

    public static function getFreeShippingAmount()
    {
        return self::get('general', 'free_shipping_amount', 0);
    }

    public static function getLogoWidth()
    {
        return self::get('general', 'logo_width', '140px');
    }

    public static function getMobileLogoWidth()
    {
        return self::get('general', 'mobile_logo_width', '140px');
    }

    public static function getProductCardBorderRadius()
    {
        return self::get('general', 'product_card_border_radius', '10px');
    }

    public static function getProductCardBoxShadow()
    {
        return self::get('general', 'product_card_box_shadow', '0 5px 15px rgba(0, 0, 0, 0.05)');
    }

    public static function getProductCardBorder()
    {
        return self::get('general', 'product_card_border', '1px solid #efefef');
    }

    public static function getProductImageHeight()
    {
        return self::get('general', 'product_image_height', '240px');
    }

    public static function getProductImagePadding()
    {
        return self::get('general', 'product_image_padding', '5px');
    }

    public static function getProductImageHeightTablet()
    {
        return self::get('general', 'product_image_height_tablet', '200px');
    }

    public static function getProductImageHeightMobile()
    {
        return self::get('general', 'product_image_height_mobile', 'auto');
    }

    public static function getSectionHeaderPadding()
    {
        return self::get('general', 'section_header_padding', '2px 5px');
    }

    public static function getSectionHeaderCustomBorder()
    {
        return self::get('general', 'section_header_custom_border');
    }

    public static function getSectionHeaderBorderRadius()
    {
        return self::get('general', 'section_header_border_radius', '8px');
    }

    public static function getSectionHeaderLeftBarColor()
    {
        return self::get('general', 'section_header_left_bar_color', 'var(--primary-color)');
    }

    public static function getSectionHeaderLeftBar()
    {
        return self::get('general', 'section_header_left_bar', '0');
    }

    public static function getPreloaderShow()
    {
        return self::get('general', 'preloader_show', '1');
    }

    public static function getPreloaderText()
    {
        return self::get('general', 'preloader_text', 'Thikana Shop');
    }

    public static function getTopHeaderBarShow()
    {
        return self::get('general', 'top_header_bar_show', '1');
    }

    public static function getTopHeaderBarLeft()
    {
        return self::get('general', 'top_header_bar_left', 'Welcome to Thikana Shop');
    }

    public static function getTopHeaderBarEmail()
    {
        return self::get('general', 'top_header_bar_email', 'example@gmail.com');
    }

    public static function getTopHeaderBarPhone()
    {
        return self::get('general', 'top_header_bar_phone', '+8801723-000000');
    }

    public static function getHeaderLayout()
    {
        return self::get('general', 'header_layout', 'v1');
    }

    public static function getMainNavigationShow()
    {
        return self::get('general', 'main_navigation_show', '1');
    }

    /**
     * Check if mega menu feature is globally enabled
     */
    public static function isMegaMenuEnabled(): bool
    {
        return self::get('navigation', 'mega_menu_enabled', '0') == '1';
    }

    /**
     * Get mega menu hover delay (milliseconds)
     */
    public static function getMegaMenuHoverDelay(): int
    {
        return (int) self::get('navigation', 'mega_menu_hover_delay', '150');
    }

    /**
     * Get mega menu animation type
     */
    public static function getMegaMenuAnimation(): string
    {
        return self::get('navigation', 'mega_menu_animation', 'fade');
    }

    /**
     * Get mega menu background color
     */
    public static function getMegaMenuBgColor(): string
    {
        return self::get('navigation', 'mega_menu_bg_color', '#ffffff');
    }

    /**
     * Get mega menu header color
     */
    public static function getMegaMenuHeaderColor(): string
    {
        return self::get('navigation', 'mega_menu_header_color', '#333333');
    }

    /**
     * Get main container max width (for .base-container)
     */
    public static function getContainerMaxWidth(): string
    {
        return self::get('layout', 'container_max_width', '1340');
    }

    /**
     * Get container padding for responsive breakpoints
     */
    public static function getContainerPadding(): string
    {
        return self::get('layout', 'container_padding', '15');
    }

    public static function getMainNavBoxShadow()
    {
        $preset = self::get('general', 'main_nav_box_shadow_preset', 'default');
        $custom = self::get('general', 'main_nav_box_shadow', '');
        
        // If custom shadow is set and preset is custom, use custom value
        if ($preset === 'custom' && !empty($custom)) {
            return $custom;
        }
        
        // Use preset values
        $presetShadows = [
            'none' => 'none',
            'default' => '0 3px 5px rgba(57, 63, 72, 0.3)',
            'subtle' => '0 1px 3px rgba(0, 0, 0, 0.1)',
            'medium' => '0 4px 8px rgba(0, 0, 0, 0.15)',
            'strong' => '0 6px 20px rgba(0, 0, 0, 0.25)',
            'elegant' => '0 8px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05)',
        ];
        
        return $presetShadows[$preset] ?? $presetShadows['default'];
    }

    public static function getShowScrollToTopDesktop()
    {
        return self::get('general', 'show_scroll_to_top_desktop', '1');
    }

    public static function getShowProductDetailsSection()
    {
        return self::get('general', 'show_product_details_section', '1');
    }

    public static function getCategoryLabelText()
    {
        return self::get('general', 'category_label_text', 'বিষয়');
    }

    public static function getWriterLabelText()
    {
        return self::get('general', 'writer_label_text', 'লেখক');
    }

    public static function getPublisherLabelText()
    {
        return self::get('general', 'publisher_label_text', 'প্রকাশক');
    }

    public static function getIsbnLabelText()
    {
        return self::get('general', 'isbn_label_text', 'আইএসবিএন');
    }

    public static function getPagesLabelText()
    {
        return self::get('general', 'pages_label_text', 'পৃষ্ঠা');
    }

    public static function getLanguageLabelText()
    {
        return self::get('general', 'language_label_text', 'ভাষা');
    }

    public static function getShowProductMetaSection()
    {
        return self::get('general', 'show_product_meta_section', '1');
    }

    public static function getShowSkuField()
    {
        return self::get('general', 'show_sku_field', '1');
    }

    public static function getShowAvailabilityField()
    {
        return self::get('general', 'show_availability_field', '1');
    }

    public static function getAvailabilityLabelText()
    {
        return self::get('general', 'availability_label_text', 'Availability');
    }

    public static function getShowShortDescriptionSection()
    {
        return self::get('general', 'show_short_description_section', '1');
    }

    public static function getReadMoreButtonText()
    {
        return self::get('general', 'read_more_button_text', 'বিস্তারিত');
    }

    public static function getShowBottomActionButtons()
    {
        return self::get('general', 'show_bottom_action_buttons', '1');
    }

    public static function getShowWhatsappButton()
    {
        return self::get('general', 'show_whatsapp_button', '1');
    }

    public static function getShowPhoneButton()
    {
        return self::get('general', 'show_phone_button', '1');
    }

    public static function getShowReviewFormSection()
    {
        return self::get('general', 'show_review_form_section', '1');
    }

    public static function getReviewFormHeaderTitle()
    {
        return self::get('general', 'review_form_header_title', 'এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন');
    }

    public static function getReviewFormCommentPlaceholder()
    {
        return self::get('general', 'review_form_comment_placeholder', 'Write your comment...');
    }

    public static function getGuestNameLabel()
    {
        return self::get('general', 'guest_name_label', 'আপনার নাম');
    }

    public static function getGuestEmailLabel()
    {
        return self::get('general', 'guest_email_label', 'আপনার ইমেইল');
    }

    public static function getReviewFormSubmitButtonText()
    {
        return self::get('general', 'review_form_submit_button_text', 'আপনার মতামত সাবমিট করুন');
    }

    public static function getShowProductDescriptionSection()
    {
        return self::get('general', 'show_product_description_section', '1');
    }

    public static function getShowRatingsReviewsSection()
    {
        return self::get('general', 'show_ratings_reviews_section', '1');
    }

    public static function getDescriptionSectionTitle()
    {
        return self::get('general', 'description_section_title', 'Product Description');
    }

    public static function getRatingsSectionTitle()
    {
        return self::get('general', 'ratings_section_title', 'Customer Ratings & Reviews');
    }

    public static function getShowRelatedProductsSection()
    {
        return self::get('general', 'show_related_products_section', '1');
    }

    public static function getRelatedProductsSectionTitle()
    {
        return self::get('general', 'related_products_section_title', 'আরো দেখুন');
    }

    public static function getShowDeliveryInfo()
    {
        return self::get('general', 'show_delivery_info', '1');
    }

    public static function getDeliveryInfo()
    {
        return self::get('general', 'delivery_info', '');
    }

    public static function getShowOrderTimeline()
    {
        return self::get('general', 'show_order_timeline', '1');
    }

    public static function getGlobalVideoUrl()
    {
        return self::get('general', 'global_video_url');
    }

    public static function getShowProductTitle()
    {
        return self::get('general', 'show_product_title', '1');
    }

    public static function getShowProductRating()
    {
        return self::get('general', 'show_product_rating', '1');
    }

    public static function getShowProductWriter()
    {
        return self::get('general', 'show_product_writer', '1');
    }

    public static function getShowProductPrice()
    {
        return self::get('general', 'show_product_price', '1');
    }

    public static function getShowProductButton()
    {
        return self::get('general', 'show_product_button', '1');
    }

    public static function getShowProductBrandSingle()
    {
        return self::get('general', 'show_product_brand_single', '1');
    }

    public static function getProductButtonText()
    {
        return self::get('general', 'product_button_text', 'View Product');
    }

    public static function getProductBadgeType()
    {
        return self::get('general', 'product_badge_type', 'starburst');
    }

    public static function getDefaultBadgeText()
    {
        return self::get('general', 'default_badge_text', '১০% ছাড়');
    }

    public static function getShowFreeShippingProgress()
    {
        return self::get('general', 'show_free_shipping_progress', '1');
    }

    public static function getShowFreeShippingProgressDesktop()
    {
        return self::get('general', 'show_free_shipping_progress_desktop', '1');
    }

    public static function getShowFreeShippingProgressMobile()
    {
        return self::get('general', 'show_free_shipping_progress_mobile', '1');
    }

    public static function getFreeShippingProgressBottomDesktop()
    {
        return self::get('general', 'free_shipping_progress_bottom_desktop', '0');
    }

    public static function getFreeShippingProgressRightDesktop()
    {
        return self::get('general', 'free_shipping_progress_right_desktop', '69');
    }

    public static function getFreeShippingProgressBottomMobile()
    {
        return self::get('general', 'free_shipping_progress_bottom_mobile', '37');
    }

    public static function getFreeShippingProgressRightMobile()
    {
        return self::get('general', 'free_shipping_progress_right_mobile', '59');
    }

    public static function getContactHeading()
    {
        return self::get('general', 'contact_heading', 'Get In Touch');
    }

    public static function getContactLocation()
    {
        return self::get('general', 'contact_location', 'Dhaka, Bangladesh');
    }

    public static function getContactSupportEmail()
    {
        return self::get('general', 'contact_support_email', 'support@thikana.shop');
    }

    public static function getContactWhatsApp()
    {
        return self::get('general', 'contact_whatsapp', '+8801779542054');
    }

    public static function getContactMapUrl()
    {
        return self::get('general', 'contact_map_url', 'https://maps.google.com/maps?q=Dhaka,Bangladesh');
    }

    // Homepage settings
    public static function getEnableCategoryScrollbar()
    {
        return self::get('homepage', 'enable_category_scrollbar', '1');
    }

    public static function getEnableProductsByCategoryV2Location1()
    {
        return self::get('homepage', 'enable_products_by_category_v2_location1', '0');
    }

    public static function getEnableBestAuthorSection()
    {
        return self::get('homepage', 'enable_best_author_section', '1');
    }

    public static function getEnableProductsByCategoryV2Location2()
    {
        return self::get('homepage', 'enable_products_by_category_v2_location2', '0');
    }

    public static function getEnableBestPublisherSection()
    {
        return self::get('homepage', 'enable_best_publisher_section', '1');
    }

    public static function getEnableProductsByCategoryV2Location3()
    {
        return self::get('homepage', 'enable_products_by_category_v2_location3', '0');
    }

    public static function getGlobalCategoryBg()
    {
        return self::get('homepage', 'global_category_bg');
    }

    public static function getSliderHeightMobile()
    {
        return self::get('homepage', 'slider_height_mobile', '170');
    }

    public static function getEnableScrollToTop()
    {
        return self::get('homepage', 'enable_scroll_to_top', '1');
    }

    // Single product settings
    public static function getEnableRatingSummary()
    {
        return self::get('single_product', 'enable_rating_summary', '1');
    }

    public static function getEnableBookSample()
    {
        return self::get('single_product', 'enable_book_sample', '1');
    }

    public static function getEnableShortInfo()
    {
        return self::get('single_product', 'enable_short_info', '1');
    }

    public static function getEnableSocialShare()
    {
        return self::get('single_product', 'enable_social_share', '1');
    }

    public static function getEnableRelatedProducts()
    {
        return self::get('single_product', 'enable_related_products', '1');
    }

    public static function getEnableBottomCategorySlider()
    {
        return self::get('single_product', 'enable_bottom_category_slider', '0');
    }

    // SEO settings
    public static function getGoogleSearchConsole()
    {
        return self::get('seo', 'google_search_console');
    }

    public static function getBingWebmaster()
    {
        return self::get('seo', 'bing_webmaster');
    }

    public static function getYandexWebmaster()
    {
        return self::get('seo', 'yandex_webmaster');
    }

    /**
     * Convert YouTube URL to embed URL
     * 
     * @param string|null $url
     * @return string|null
     */
    public static function getYoutubeEmbedUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // Extract video ID from various YouTube URL formats
        $videoId = null;
        
        // Handle different YouTube URL formats
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/v\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        return null;
    }
} 