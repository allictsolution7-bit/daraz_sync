<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // products
            'products.view','products.create','products.update','products.delete',
            'products.bulk_delete','products.bulk_status_toggle','products.export_selected','products.search','products.data',
            // product categories & sub-categories (catalog taxonomy)
            'product_categories.view','product_categories.create','product_categories.update','product_categories.delete',
            'sub_categories.view','sub_categories.create','sub_categories.update','sub_categories.delete',
            // orders
            'orders.view','orders.create','orders.update','orders.delete','orders.asigned',
            'orders.update_item','orders.update_status','orders.update_note','orders.delete_multiple','orders.export_selected',
            // reports
            'reports.sales.view','reports.customers.view',
            // incomplete orders
            'incomplete_orders.view','incomplete_orders.delete','incomplete_orders.bulk_delete','incomplete_orders.export_selected',
            'incomplete_orders.update_status','incomplete_orders.update_note','incomplete_orders.convert','incomplete_orders.get_variation_combinations','incomplete_orders.convert_page',
            // pos
            'pos.view','pos.access','pos.create_order','pos.manage_settings','admin.pos.access','admin.pos.create-order','admin.pos.manage-settings',
            // pages
            'pages.view','pages.create','pages.update','pages.delete',
            // landing pages
            'landing_pages.view','landing_pages.create','landing_pages.update','landing_pages.delete','landing_pages.toggle_status','landing_pages.update_positions',
            // settings
            'settings.view','settings.update','sitemap.generate',
            // combo offers
            'combo_offers.view','combo_offers.create','combo_offers.update','combo_offers.delete','combo_offers.toggle_status','combo_offers.get_variations',
            // socials
            'socials.view','socials.create','socials.update','socials.delete',
            // shipping
            'shipping.basic.view','shipping.basic.update',
            'shipping.zones.view','shipping.zones.create','shipping.zones.update','shipping.zones.delete',
            'shipping.rules.view','shipping.rules.create','shipping.rules.update','shipping.rules.delete','shipping.rules.toggle',
            'shipping.calculate',
            // cities
            'cities.view','cities.create','cities.update','cities.delete',
            // menus
            'menus.view','menus.create','menus.update','menus.delete',
            'menu_items.create','menu_items.update','menu_items.delete','menu_items.update_order',
            // subscriptions
            'subscriptions.view','subscriptions.delete','subscriptions.toggle_status','subscriptions.bulk_delete','subscriptions.show',
            // contacts
            'contacts.view','contacts.delete','contacts.unread','contacts.show',
            // sliders
            'sliders.view','sliders.create','sliders.update','sliders.delete','sliders.update_positions',
            // blog/posts/categories/subcategories
            'posts.view','posts.create','posts.update','posts.delete',
            'categories.view','categories.create','categories.update','categories.delete',
            'post_subcategories.view','post_subcategories.create','post_subcategories.update','post_subcategories.delete',
            // writers/publishers
            'writers.view','writers.create','writers.update','writers.delete',
            'publishers.view','publishers.create','publishers.update','publishers.delete',
            // reviews (manual customer reviews)
            'reviews.view','reviews.create','reviews.update','reviews.delete',
            // delivery / courier
            'delivery.view','delivery.integrate','delivery.delete',
            'courier.pathao.send','courier.pathao.send_bulk','courier.pathao.save_send','courier.pathao.cities','courier.pathao.zones','courier.pathao.areas','courier.pathao.status',
            'courier.steadfast.send','courier.steadfast.send_bulk','courier.steadfast.status','courier.steadfast.balance',
            // fraud checker
            'fraud_checker.view','fraud_checker.integrate','fraud_checker.delete','fraud_checker.test_connection','fraud_checker.check_phone','fraud_checker.check_order','fraud_checker.results','fraud_checker.result_details','fraud_checker.refresh_result',
            // roles & permissions
            'roles_permissions.view','roles.manage','permissions.manage','user_roles.assign',
            // dashboard
            'dashboard.view',
            // missing sidebar & utility permissions
            'users.view', 'users.create', 'users.update', 'users.delete',
            'blog.view',
            'fraud_protection.view',
            'telegram_settings.view',
            'delayed_events.view',
            'woocommerce_migration.view',
            'basic_shipping.view',
            'daraz.view', 'admin.daraz.view', 'daraz_sync.view', 'daraz.index',
        ];

        foreach ($permissions as $p) {
            Permission::findOrCreate($p, 'web');
        }

        $super = Role::findOrCreate('super_admin', 'web');
        $super->givePermissionTo(Permission::all());

        // Attach to existing admin users if present
        foreach ([
            'mdmahedihasan792@gmail.com',
        ] as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles(['super_admin']);
            }
        }
        // Attach by ID fallback (e.g., user id 39)
        $userById = \App\Models\User::find(39);
        if ($userById) {
            $userById->syncRoles(['super_admin']);
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

