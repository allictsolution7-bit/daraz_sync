<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create main menus
        $headerMenu = Menu::create([
            'name' => 'Header Menu',
            'location' => 'header',
            'status' => true,
            'description' => 'Main navigation menu for the website header'
        ]);

        $footerMenu = Menu::create([
            'name' => 'Footer Menu',
            'location' => 'footer',
            'status' => true,
            'description' => 'Footer navigation menu'
        ]);

        $sidebarMenu = Menu::create([
            'name' => 'Sidebar Menu',
            'location' => 'sidebar',
            'status' => true,
            'description' => 'Sidebar navigation menu'
        ]);

        // Create menu items for Header Menu
        $home = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Home',
            'url' => '/',
            'order' => 1,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-home'
        ]);

        $shop = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Shop',
            'url' => '/shop',
            'order' => 2,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-shopping-bag'
        ]);

        $about = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'About Us',
            'url' => '/p/about-us',
            'order' => 3,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-info-circle'
        ]);

        $contact = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Contact',
            'url' => '/p/contact',
            'order' => 4,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-envelope'
        ]);

        // Create submenu items for Shop
        $menCategory = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'parent_id' => $shop->id,
            'title' => 'Men',
            'url' => '/shop/men',
            'order' => 1,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-male'
        ]);

        $womenCategory = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'parent_id' => $shop->id,
            'title' => 'Women',
            'url' => '/shop/women',
            'order' => 2,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-female'
        ]);

        $kidsCategory = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'parent_id' => $shop->id,
            'title' => 'Kids',
            'url' => '/shop/kids',
            'order' => 3,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-child'
        ]);

        // Create Footer Menu items
        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Terms & Conditions',
            'url' => '/p/terms',
            'order' => 1,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-file-contract'
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Privacy Policy',
            'url' => '/p/privacy',
            'order' => 2,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-shield-alt'
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'FAQ',
            'url' => '/p/faq',
            'order' => 3,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-question-circle'
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'About Us',
            'url' => '/p/about-us',
            'order' => 4,
            'status' => true,
            'target' => '_self',
            'icon_class' => 'fas fa-info-circle'
        ]);
    }
}