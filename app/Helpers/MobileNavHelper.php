<?php

namespace App\Helpers;

use App\Services\SettingsService;

class MobileNavHelper
{
    /**
     * Get optimized mobile navigation data
     */
    public static function getMobileNavData(): array
    {
        // Load all mobile nav settings in one call
        $mobileNavSettings = SettingsService::group('mobile_nav');
        
        // Define navigation items with optimized structure
        $navItems = [
            'home' => [
                'enabled' => $mobileNavSettings['home_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['home_order'] ?? 1),
                'url' => '/',
                'text' => $mobileNavSettings['home_label'] ?? 'হোম',
                'icon' => 'icon-home'
            ],
            'cart' => [
                'enabled' => $mobileNavSettings['cart_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['cart_order'] ?? 2),
                'url' => '/cart',
                'text' => $mobileNavSettings['cart_label'] ?? 'কার্ট',
                'icon' => 'icon-cart'
            ],
            'chat' => [
                'enabled' => $mobileNavSettings['chat_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['chat_order'] ?? 3),
                'url' => '',
                'text' => $mobileNavSettings['chat_label'] ?? 'চ্যাট',
                'icon' => 'icon-chat'
            ],
            'call' => [
                'enabled' => $mobileNavSettings['call_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['call_order'] ?? 4),
                'url' => '#',
                'text' => $mobileNavSettings['call_label'] ?? 'কল',
                'icon' => 'icon-call'
            ],
            'profile' => [
                'enabled' => $mobileNavSettings['profile_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['profile_order'] ?? 5),
                'url' => route('account.show'),
                'text' => $mobileNavSettings['profile_label'] ?? 'প্রোফাইল',
                'icon' => 'icon-profile'
            ],
            'category' => [
                'enabled' => $mobileNavSettings['category_enabled'] ?? '1',
                'order' => (int)($mobileNavSettings['category_order'] ?? 6),
                'url' => '#',
                'text' => $mobileNavSettings['category_label'] ?? 'ক্যাটাগরি',
                'icon' => 'icon-category',
                'popup' => true
            ]
        ];
        
        // Filter enabled items and sort by order
        $enabledItems = collect($navItems)
            ->filter(function ($item) {
                return $item['enabled'] == '1';
            })
            ->sortBy('order')
            ->toArray();
            
        return [
            'enabled' => $mobileNavSettings['enabled'] ?? '1',
            'items' => $enabledItems
        ];
    }
    
    /**
     * Get optimized fallback image URL
     */
    public static function getFallbackImageUrl(): string
    {
        return asset('assets/icons/mobile-nav-icons.svg#icon-grid');
    }
}
