<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ProductSettingsComposer
{
    /**
     * Bind data to the view.
     * This composer caches all product-related settings to avoid repeated database calls
     */
    public function compose(View $view)
    {
        // Cache product settings for 1 hour to avoid repeated queries
        $productSettings = Cache::remember('product_card_settings', 3600, function () {
            return [
                'badge_type' => setting('general', 'product_badge_type', 'starburst'),
                'default_badge_text' => setting('general', 'default_badge_text', '১০% ছাড়'),
                'show_product_title' => setting('general', 'show_product_title', '1'),
                'show_product_rating' => setting('general', 'show_product_rating', '1'),
                'show_product_writer' => setting('general', 'show_product_writer', '1'),
                'show_product_price' => setting('general', 'show_product_price', '1'),
                'show_product_button' => setting('general', 'show_product_button', '1'),
                'show_view_product_button' => setting('general', 'show_view_product_button', '1'),
                'show_buy_now_button' => setting('general', 'show_buy_now_button', '0'),
                'show_quick_cart_icon' => setting('general', 'show_quick_cart_icon', '1'),
                'product_button_text' => setting('general', 'product_button_text', 'View Product'),
                'buy_now_button_text' => setting('general', 'buy_now_button_text', 'Buy Now'),
                'buy_now_button_type' => setting('general', 'buy_now_button_type', 'quick'),
                'view_product_button_bg_color' => setting('general', 'view_product_button_bg_color', 'transparent'),
                'view_product_button_text_color' => setting('general', 'view_product_button_text_color', 'var(--secondary-color)'),
                'buy_now_button_bg_color' => setting('general', 'buy_now_button_bg_color', '#2ecc71'),
                'buy_now_button_text_color' => setting('general', 'buy_now_button_text_color', '#ffffff'),
            ];
        });

        $view->with('productSettings', $productSettings);
    }
}

