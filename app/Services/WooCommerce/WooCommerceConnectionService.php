<?php

namespace App\Services\WooCommerce;

use App\Models\WooCommerceSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WooCommerceConnectionService
{
    protected $connectionMethod;
    protected $config;
    protected $settings;

    public function __construct()
    {
        $this->settings = WooCommerceSetting::getSettings();
        $this->connectionMethod = $this->settings->connection_method ?? config('woocommerce.connection_method', 'api');
        $this->loadConfig();
    }

    /**
     * Load configuration from database settings or fallback to config file
     */
    protected function loadConfig()
    {
        $this->config = config('woocommerce');
        
        // Override with database settings if available
        if ($this->settings && $this->settings->id) {
            $this->config['connection_method'] = $this->settings->connection_method;
            
            // API settings
            if ($this->settings->api_url) {
                $this->config['api']['url'] = $this->settings->api_url;
            }
            if ($this->settings->consumer_key) {
                $this->config['api']['consumer_key'] = $this->settings->consumer_key;
            }
            if ($this->settings->consumer_secret) {
                $this->config['api']['consumer_secret'] = $this->settings->consumer_secret;
            }
            if ($this->settings->api_version) {
                $this->config['api']['version'] = $this->settings->api_version;
            }
            $this->config['api']['verify_ssl'] = $this->settings->verify_ssl ?? true;
            
            // Database settings
            if ($this->settings->db_host) {
                $this->config['database']['host'] = $this->settings->db_host;
            }
            if ($this->settings->db_port) {
                $this->config['database']['port'] = $this->settings->db_port;
            }
            if ($this->settings->db_database) {
                $this->config['database']['database'] = $this->settings->db_database;
            }
            if ($this->settings->db_username) {
                $this->config['database']['username'] = $this->settings->db_username;
            }
            if ($this->settings->db_password) {
                $this->config['database']['password'] = $this->settings->db_password;
            }
            if ($this->settings->db_table_prefix) {
                $this->config['database']['table_prefix'] = $this->settings->db_table_prefix;
            }
        }
    }

    /**
     * Test the connection to WooCommerce
     */
    public function testConnection(): array
    {
        try {
            if ($this->connectionMethod === 'api') {
                return $this->testApiConnection();
            } else {
                return $this->testDatabaseConnection();
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test REST API connection
     */
    protected function testApiConnection(): array
    {
        try {
            $url = rtrim($this->config['api']['url'], '/');
            $apiUrl = "{$url}/wp-json/{$this->config['api']['version']}/system_status";
            
            $response = Http::timeout(30)
                ->withBasicAuth(
                    $this->config['api']['consumer_key'],
                    $this->config['api']['consumer_secret']
                )
                ->get($apiUrl);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Successfully connected to WooCommerce API',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to connect: ' . $response->body(),
                'status' => $response->status(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test database connection
     */
    protected function testDatabaseConnection(): array
    {
        try {
            $prefix = $this->config['database']['table_prefix'];
            
            // Test connection by querying posts table
            $products = DB::connection('woocommerce')->table($prefix . 'posts')
                ->where('post_type', 'product')
                ->limit(1)
                ->count();

            return [
                'success' => true,
                'message' => 'Successfully connected to WooCommerce database',
                'products_count' => $products,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Database connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get products from WooCommerce
     */
    public function getProducts($page = 1, $perPage = 100): array
    {
        if ($this->connectionMethod === 'api') {
            return $this->getProductsFromApi($page, $perPage);
        } else {
            return $this->getProductsFromDatabase($page, $perPage);
        }
    }

    /**
     * Get products via API
     */
    protected function getProductsFromApi($page = 1, $perPage = 100): array
    {
        $url = rtrim($this->config['api']['url'], '/');
        $apiUrl = "{$url}/wp-json/{$this->config['api']['version']}/products";
        
        $response = Http::timeout(60)
            ->withBasicAuth(
                $this->config['api']['consumer_key'],
                $this->config['api']['consumer_secret']
            )
            ->get($apiUrl, [
                'page' => $page,
                'per_page' => $perPage,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'headers' => $response->headers(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to fetch products: ' . $response->body(),
        ];
    }

    /**
     * Get products from database
     */
    protected function getProductsFromDatabase($page = 1, $perPage = 100): array
    {
        $prefix = $this->config['database']['table_prefix'];
        $offset = ($page - 1) * $perPage;

        $products = DB::connection('woocommerce')
            ->table($prefix . 'posts as p')
            ->leftJoin($prefix . 'postmeta as pm', 'p.ID', '=', 'pm.post_id')
            ->where('p.post_type', 'product')
            ->where('p.post_status', 'publish')
            ->select('p.*')
            ->groupBy('p.ID')
            ->skip($offset)
            ->take($perPage)
            ->get();

        // Get product meta
        $productIds = $products->pluck('ID')->toArray();
        $meta = DB::connection('woocommerce')
            ->table($prefix . 'postmeta')
            ->whereIn('post_id', $productIds)
            ->get()
            ->groupBy('post_id');

        // Transform to WooCommerce API format
        $transformed = $products->map(function ($product) use ($meta, $prefix) {
            $productMeta = $meta->get($product->ID, collect())->pluck('meta_value', 'meta_key');
            
            return $this->transformDatabaseProduct($product, $productMeta, $prefix);
        });

        return [
            'success' => true,
            'data' => $transformed->toArray(),
        ];
    }

    /**
     * Transform database product to API format
     */
    protected function transformDatabaseProduct($product, $meta, $prefix)
    {
        // This is a simplified transformation
        // You may need to adjust based on your WooCommerce structure
        return [
            'id' => $product->ID,
            'name' => $product->post_title,
            'slug' => $product->post_name,
            'description' => $product->post_content,
            'short_description' => $product->post_excerpt,
            'status' => $product->post_status,
            'regular_price' => $meta->get('_regular_price', ''),
            'sale_price' => $meta->get('_sale_price', ''),
            'stock_status' => $meta->get('_stock_status', 'instock'),
            'manage_stock' => $meta->get('_manage_stock', 'no') === 'yes',
            'stock_quantity' => $meta->get('_stock', 0),
            'categories' => $this->getProductCategories($product->ID, $prefix),
            'images' => $this->getProductImages($product->ID, $prefix),
            'meta_data' => $meta->toArray(),
        ];
    }

    /**
     * Get product categories
     */
    protected function getProductCategories($productId, $prefix)
    {
        $terms = DB::connection('woocommerce')
            ->table($prefix . 'term_relationships as tr')
            ->join($prefix . 'term_taxonomy as tt', 'tr.term_taxonomy_id', '=', 'tt.term_taxonomy_id')
            ->join($prefix . 'terms as t', 'tt.term_id', '=', 't.term_id')
            ->where('tr.object_id', $productId)
            ->where('tt.taxonomy', 'product_cat')
            ->select('t.term_id as id', 't.name', 't.slug')
            ->get();

        return $terms->toArray();
    }

    /**
     * Get product images
     */
    protected function getProductImages($productId, $prefix)
    {
        $imageId = DB::connection('woocommerce')
            ->table($prefix . 'postmeta')
            ->where('post_id', $productId)
            ->where('meta_key', '_thumbnail_id')
            ->value('meta_value');

        if (!$imageId) {
            return [];
        }

        $image = DB::connection('woocommerce')
            ->table($prefix . 'posts')
            ->where('ID', $imageId)
            ->first();

        if (!$image) {
            return [];
        }

        $imageUrl = DB::connection('woocommerce')
            ->table($prefix . 'postmeta')
            ->where('post_id', $imageId)
            ->where('meta_key', '_wp_attached_file')
            ->value('meta_value');

        return [[
            'id' => $imageId,
            'src' => $imageUrl ? $this->config['api']['url'] . '/wp-content/uploads/' . $imageUrl : '',
            'alt' => $image->post_title ?? '',
        ]];
    }

    /**
     * Get orders from WooCommerce
     */
    public function getOrders($page = 1, $perPage = 100): array
    {
        if ($this->connectionMethod === 'api') {
            return $this->getOrdersFromApi($page, $perPage);
        } else {
            return $this->getOrdersFromDatabase($page, $perPage);
        }
    }

    /**
     * Get orders via API
     */
    protected function getOrdersFromApi($page = 1, $perPage = 100): array
    {
        $url = rtrim($this->config['api']['url'], '/');
        $apiUrl = "{$url}/wp-json/{$this->config['api']['version']}/orders";
        
        $response = Http::timeout(60)
            ->withBasicAuth(
                $this->config['api']['consumer_key'],
                $this->config['api']['consumer_secret']
            )
            ->get($apiUrl, [
                'page' => $page,
                'per_page' => $perPage,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'headers' => $response->headers(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to fetch orders: ' . $response->body(),
        ];
    }

    /**
     * Get orders from database
     */
    protected function getOrdersFromDatabase($page = 1, $perPage = 100): array
    {
        $prefix = $this->config['database']['table_prefix'];
        $offset = ($page - 1) * $perPage;

        $orders = DB::connection('woocommerce')
            ->table($prefix . 'posts as p')
            ->where('p.post_type', 'shop_order')
            ->whereIn('p.post_status', ['wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed'])
            ->select('p.*')
            ->skip($offset)
            ->take($perPage)
            ->get();

        // Get order meta and items
        $orderIds = $orders->pluck('ID')->toArray();
        $ordersMeta = $this->getOrdersMeta($orderIds, $prefix);
        $orderItems = $this->getOrderItems($orderIds, $prefix);

        // Transform orders
        $transformed = $orders->map(function ($order) use ($ordersMeta, $orderItems, $prefix) {
            return $this->transformDatabaseOrder($order, $ordersMeta->get($order->ID, collect()), $orderItems->get($order->ID, collect()), $prefix);
        });

        return [
            'success' => true,
            'data' => $transformed->toArray(),
        ];
    }

    /**
     * Get orders meta
     */
    protected function getOrdersMeta($orderIds, $prefix)
    {
        return DB::connection('woocommerce')
            ->table($prefix . 'postmeta')
            ->whereIn('post_id', $orderIds)
            ->get()
            ->groupBy('post_id')
            ->map(function ($meta) {
                return $meta->pluck('meta_value', 'meta_key');
            });
    }

    /**
     * Get order items
     */
    protected function getOrderItems($orderIds, $prefix)
    {
        $items = DB::connection('woocommerce')
            ->table($prefix . 'woocommerce_order_items as oi')
            ->leftJoin($prefix . 'woocommerce_order_itemmeta as oim', 'oi.order_item_id', '=', 'oim.order_item_id')
            ->whereIn('oi.order_id', $orderIds)
            ->where('oi.order_item_type', 'line_item')
            ->select('oi.*', 'oim.meta_key', 'oim.meta_value')
            ->get()
            ->groupBy('order_id');

        return $items->map(function ($orderItems) {
            return $orderItems->groupBy('order_item_id')->map(function ($item) {
                $meta = $item->pluck('meta_value', 'meta_key');
                return [
                    'id' => $item->first()->order_item_id,
                    'name' => $item->first()->order_item_name,
                    'product_id' => $meta->get('_product_id'),
                    'quantity' => $meta->get('_qty', 1),
                    'subtotal' => $meta->get('_line_subtotal', 0),
                    'total' => $meta->get('_line_total', 0),
                    'meta_data' => $meta->toArray(),
                ];
            })->values();
        });
    }

    /**
     * Transform database order to API format
     */
    protected function transformDatabaseOrder($order, $meta, $items, $prefix)
    {
        return [
            'id' => $order->ID,
            'status' => str_replace('wc-', '', $order->post_status),
            'currency' => $meta->get('_order_currency', 'BDT'),
            'date_created' => $order->post_date,
            'total' => $meta->get('_order_total', 0),
            'subtotal' => $meta->get('_order_subtotal', 0),
            'shipping_total' => $meta->get('_order_shipping', 0),
            'payment_method' => $meta->get('_payment_method', ''),
            'payment_method_title' => $meta->get('_payment_method_title', ''),
            'billing' => [
                'first_name' => $meta->get('_billing_first_name', ''),
                'last_name' => $meta->get('_billing_last_name', ''),
                'email' => $meta->get('_billing_email', ''),
                'phone' => $meta->get('_billing_phone', ''),
                'address_1' => $meta->get('_billing_address_1', ''),
                'address_2' => $meta->get('_billing_address_2', ''),
                'city' => $meta->get('_billing_city', ''),
                'postcode' => $meta->get('_billing_postcode', ''),
            ],
            'shipping' => [
                'first_name' => $meta->get('_shipping_first_name', ''),
                'last_name' => $meta->get('_shipping_last_name', ''),
                'address_1' => $meta->get('_shipping_address_1', ''),
                'address_2' => $meta->get('_shipping_address_2', ''),
                'city' => $meta->get('_shipping_city', ''),
                'postcode' => $meta->get('_shipping_postcode', ''),
            ],
            'line_items' => $items->toArray(),
        ];
    }

    /**
     * Get categories from WooCommerce
     */
    public function getCategories($page = 1, $perPage = 100): array
    {
        if ($this->connectionMethod === 'api') {
            return $this->getCategoriesFromApi($page, $perPage);
        } else {
            return $this->getCategoriesFromDatabase($page, $perPage);
        }
    }

    /**
     * Get categories via API
     */
    protected function getCategoriesFromApi($page = 1, $perPage = 100): array
    {
        $url = rtrim($this->config['api']['url'], '/');
        $apiUrl = "{$url}/wp-json/{$this->config['api']['version']}/products/categories";
        
        $response = Http::timeout(60)
            ->withBasicAuth(
                $this->config['api']['consumer_key'],
                $this->config['api']['consumer_secret']
            )
            ->get($apiUrl, [
                'page' => $page,
                'per_page' => $perPage,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'headers' => $response->headers(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to fetch categories: ' . $response->body(),
        ];
    }

    /**
     * Get categories from database
     */
    protected function getCategoriesFromDatabase($page = 1, $perPage = 100): array
    {
        $prefix = $this->config['database']['table_prefix'];
        $offset = ($page - 1) * $perPage;

        $categories = DB::connection('woocommerce')
            ->table($prefix . 'terms as t')
            ->join($prefix . 'term_taxonomy as tt', 't.term_id', '=', 'tt.term_id')
            ->where('tt.taxonomy', 'product_cat')
            ->select('t.*', 'tt.description', 'tt.parent')
            ->skip($offset)
            ->take($perPage)
            ->get();

        $transformed = $categories->map(function ($cat) use ($prefix) {
            $imageId = DB::connection('woocommerce')
                ->table($prefix . 'termmeta')
                ->where('term_id', $cat->term_id)
                ->where('meta_key', 'thumbnail_id')
                ->value('meta_value');

            $imageUrl = '';
            if ($imageId) {
                $imageUrl = DB::connection('woocommerce')
                    ->table($prefix . 'postmeta')
                    ->where('post_id', $imageId)
                    ->where('meta_key', '_wp_attached_file')
                    ->value('meta_value');
            }

            return [
                'id' => $cat->term_id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'description' => $cat->description,
                'parent' => $cat->parent,
                'image' => $imageUrl ? ['src' => $this->config['api']['url'] . '/wp-content/uploads/' . $imageUrl] : null,
            ];
        });

        return [
            'success' => true,
            'data' => $transformed->toArray(),
        ];
    }

    /**
     * Get users/customers from WooCommerce
     */
    public function getCustomers($page = 1, $perPage = 100): array
    {
        if ($this->connectionMethod === 'api') {
            return $this->getCustomersFromApi($page, $perPage);
        } else {
            return $this->getCustomersFromDatabase($page, $perPage);
        }
    }

    /**
     * Get customers via API
     */
    protected function getCustomersFromApi($page = 1, $perPage = 100): array
    {
        $url = rtrim($this->config['api']['url'], '/');
        $apiUrl = "{$url}/wp-json/{$this->config['api']['version']}/customers";
        
        $response = Http::timeout(60)
            ->withBasicAuth(
                $this->config['api']['consumer_key'],
                $this->config['api']['consumer_secret']
            )
            ->get($apiUrl, [
                'page' => $page,
                'per_page' => $perPage,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
                'headers' => $response->headers(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to fetch customers: ' . $response->body(),
        ];
    }

    /**
     * Get customers from database
     */
    protected function getCustomersFromDatabase($page = 1, $perPage = 100): array
    {
        $prefix = $this->config['database']['table_prefix'];
        $offset = ($page - 1) * $perPage;

        $users = DB::connection('woocommerce')
            ->table($prefix . 'users as u')
            ->leftJoin($prefix . 'usermeta as um', function ($join) {
                $join->on('u.ID', '=', 'um.user_id')
                    ->whereIn('um.meta_key', ['first_name', 'last_name', 'billing_phone', 'billing_address_1', 'billing_city']);
            })
            ->skip($offset)
            ->take($perPage)
            ->get();

        $userMeta = DB::connection('woocommerce')
            ->table($prefix . 'usermeta')
            ->whereIn('user_id', $users->pluck('ID'))
            ->get()
            ->groupBy('user_id')
            ->map(function ($meta) {
                return $meta->pluck('meta_value', 'meta_key');
            });

        $transformed = $users->map(function ($user) use ($userMeta) {
            $meta = $userMeta->get($user->ID, collect());
            return [
                'id' => $user->ID,
                'email' => $user->user_email,
                'username' => $user->user_login,
                'first_name' => $meta->get('first_name', ''),
                'last_name' => $meta->get('last_name', ''),
                'billing' => [
                    'phone' => $meta->get('billing_phone', ''),
                    'address_1' => $meta->get('billing_address_1', ''),
                    'city' => $meta->get('billing_city', ''),
                ],
            ];
        });

        return [
            'success' => true,
            'data' => $transformed->toArray(),
        ];
    }
}

