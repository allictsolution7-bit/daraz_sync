<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    /**
     * Display the modules dashboard
     */
    public function index()
    {
        $modules = [
            // Priority modules in specified order
            [
                'key' => 'landing-pages',
                'name' => 'Landing Page Builder',
                'icon' => 'fas fa-palette',
                'description' => 'Create and manage custom landing pages with drag-and-drop builder.',
                'route' => route('admin.landing-pages.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ],
            [
                'key' => 'incomplete-orders',
                'name' => 'Incomplete Orders',
                'icon' => 'fas fa-shopping-basket',
                'description' => 'Track and manage incomplete orders, abandoned carts, and conversions.',
                'route' => route('admin.incomplete-orders.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #ffd89b 0%, #19547b 100%)',
            ],
            [
                'key' => 'telegram-notification',
                'name' => 'Telegram Notification',
                'icon' => 'fab fa-telegram',
                'description' => 'Configure Telegram bot settings for order notifications and updates.',
                'route' => route('admin.telegram-settings.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #0088cc 0%, #00a8e8 100%)',
            ],
            [
                'key' => 'roles-permissions',
                'name' => 'Roles & Permissions',
                'icon' => 'fas fa-user-lock',
                'description' => 'Manage user roles, permissions, and access control for your system.',
                'route' => route('admin.roles_permissions.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #434343 0%, #000000 100%)',
            ],
            [
                'key' => 'pos',
                'name' => 'Point of Sale (POS)',
                'icon' => 'fas fa-cash-register',
                'description' => 'Point of sale system for in-store sales and quick order processing.',
                'route' => route('admin.pos.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
            ],
            [
                'key' => 'multi-seller',
                'name' => 'Multi Seller',
                'icon' => 'fas fa-store',
                'description' => 'Manage vendors, verify sellers, and handle vendor products and commissions.',
                'route' => route('admin.vendors.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
            ],
            [
                'key' => 'fraud-protection',
                'name' => 'Fraud Protection',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Advanced fraud detection and prevention system for your orders.',
                'route' => route('admin.fraud-protection.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            ],
            [
                'key' => 'inventory',
                'name' => 'Inventory Management',
                'icon' => 'fas fa-boxes',
                'description' => 'Track stock levels, manage inventory, and get low stock alerts.',
                'route' => route('admin.inventory.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #28a745 0%, #20c997 100%)',
            ],
            [
                'key' => 'woocommerce-migration',
                'name' => 'WooCommerce Migration',
                'icon' => 'fas fa-exchange-alt',
                'description' => 'Migrate orders, products, users, and categories from WooCommerce to your website.',
                'route' => route('admin.woocommerce-migration.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ],
            // Other modules
            [
                'key' => 'delivery',
                'name' => 'Courier Integration',
                'icon' => 'fas fa-shipping-fast',
                'description' => 'Integrate with Pathao, Steadfast, and other courier services for automated shipping.',
                'route' => route('admin.delivery.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%)',
            ],
            [
                'key' => 'fraud-checker',
                'name' => 'Fraud Checker',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Check customer phone numbers and prevent fraudulent orders.',
                'route' => route('admin.fraud-checker.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            ],
            [
                'key' => 'backup',
                'name' => 'Backup System',
                'icon' => 'fas fa-database',
                'description' => 'Automated database backups and restore functionality.',
                'route' => route('admin.backup.settings'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            ],
            [
                'key' => 'shipping-rules',
                'name' => 'Shipping Rules',
                'icon' => 'fas fa-truck',
                'description' => 'Configure shipping rules and rates for different zones and conditions.',
                'route' => route('admin.shipping.rules.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            ],
            [
                'key' => 'blog',
                'name' => 'Blog Management',
                'icon' => 'fas fa-blog',
                'description' => 'Manage blog posts, categories, and content for your website.',
                'route' => route('admin.post.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            ],
            [
                'key' => 'sliders',
                'name' => 'Sliders',
                'icon' => 'fas fa-images',
                'description' => 'Manage homepage sliders and banners to showcase your products.',
                'route' => route('admin.sliders.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            ],
            [
                'key' => 'subscriptions',
                'name' => 'Subscriptions',
                'icon' => 'fas fa-envelope',
                'description' => 'View and manage email newsletter subscriptions from customers.',
                'route' => route('admin.subscriptions.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #ffc3a0 0%, #ffafbd 100%)',
            ],
            [
                'key' => 'contact-messages',
                'name' => 'Contact Messages',
                'icon' => 'fas fa-comments',
                'description' => 'View and manage contact form messages and inquiries.',
                'route' => route('admin.contacts.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
            ],
            [
                'key' => 'combo-offers',
                'name' => 'Combo Offers',
                'icon' => 'fas fa-gift',
                'description' => 'Create and manage combo offers, bundle deals, and special product packages.',
                'route' => route('admin.combo_offers.index'),
                'active' => true,
                'color' => 'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
            ],
        ];

        return view('admin.modules.index', compact('modules'));
    }
}

