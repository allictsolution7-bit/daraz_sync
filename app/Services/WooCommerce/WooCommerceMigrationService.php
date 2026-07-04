<?php

namespace App\Services\WooCommerce;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\order;
use App\Models\order_item;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class WooCommerceMigrationService
{
    protected $connectionService;
    protected $config;
    protected $mapping = [];

    public function __construct(WooCommerceConnectionService $connectionService)
    {
        $this->connectionService = $connectionService;
        $this->config = config('woocommerce');
        
        // Initialize ID mapping tables
        $this->initializeMapping();
    }

    /**
     * Initialize ID mapping (to track migrated records)
     */
    protected function initializeMapping()
    {
        // Create mapping table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('woocommerce_migration_mapping')) {
            try {
                DB::statement('CREATE TABLE woocommerce_migration_mapping (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    entity_type VARCHAR(50) NOT NULL,
                    woocommerce_id BIGINT UNSIGNED NOT NULL,
                    local_id BIGINT UNSIGNED NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY unique_mapping (entity_type, woocommerce_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
            } catch (Exception $e) {
                // Table might already exist
            }
        }
    }

    /**
     * Store ID mapping
     */
    protected function storeMapping($entityType, $woocommerceId, $localId)
    {
        DB::table('woocommerce_migration_mapping')->updateOrInsert(
            [
                'entity_type' => $entityType,
                'woocommerce_id' => $woocommerceId,
            ],
            [
                'local_id' => $localId,
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Get local ID from WooCommerce ID
     */
    protected function getLocalId($entityType, $woocommerceId)
    {
        $mapping = DB::table('woocommerce_migration_mapping')
            ->where('entity_type', $entityType)
            ->where('woocommerce_id', $woocommerceId)
            ->first();

        return $mapping ? $mapping->local_id : null;
    }

    /**
     * Migrate categories
     */
    public function migrateCategories($dryRun = false): array
    {
        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $page = 1;
        $hasMore = true;
        $categoryMap = [];

        while ($hasMore) {
            $result = $this->connectionService->getCategories($page, $this->config['migration']['chunk_size']);

            if (!$result['success']) {
                $stats['errors'][] = "Failed to fetch categories: " . ($result['message'] ?? 'Unknown error');
                break;
            }

            $categories = $result['data'];
            
            if (empty($categories)) {
                $hasMore = false;
                break;
            }

            foreach ($categories as $wcCategory) {
                $stats['total']++;

                try {
                    // Check if already migrated
                    $existingId = $this->getLocalId('category', $wcCategory['id']);
                    
                    if ($existingId && !$this->config['migration']['update_existing']) {
                        $stats['skipped']++;
                        $categoryMap[$wcCategory['id']] = $existingId;
                        continue;
                    }

                    if ($dryRun) {
                        $stats['created']++;
                        $categoryMap[$wcCategory['id']] = 'new';
                        continue;
                    }

                    // Handle parent category
                    $parentId = null;
                    if (isset($wcCategory['parent']) && $wcCategory['parent'] > 0) {
                        $parentId = $categoryMap[$wcCategory['parent']] ?? $this->getLocalId('category', $wcCategory['parent']);
                        
                        // If parent is a subcategory, get the parent category
                        if ($parentId) {
                            $parentCategory = ProductCategory::find($parentId);
                            if (!$parentCategory) {
                                // Check if it's a subcategory
                                $parentSubCategory = SubCategory::find($parentId);
                                if ($parentSubCategory) {
                                    $parentId = $parentSubCategory->product_category_id;
                                }
                            }
                        }
                    }

                    // For now, we'll create as ProductCategory (top level)
                    // You may want to adjust logic for subcategories
                    $categoryData = [
                        'name' => $wcCategory['name'],
                        'slug' => $this->generateUniqueSlug($wcCategory['slug'], 'product_categories', $existingId),
                        'description' => $wcCategory['description'] ?? null,
                        'status' => 'active',
                    ];

                    // Download image if configured
                    if ($this->config['migration']['download_images'] && isset($wcCategory['image']['src'])) {
                        // Note: Adjust directory based on your category image storage location
                        $categoryData['image'] = $this->downloadImage($wcCategory['image']['src'], 'category_images');
                    }

                    if ($existingId) {
                        ProductCategory::where('id', $existingId)->update($categoryData);
                        $categoryId = $existingId;
                        $stats['updated']++;
                    } else {
                        $category = ProductCategory::create($categoryData);
                        $categoryId = $category->id;
                        $stats['created']++;
                    }

                    $this->storeMapping('category', $wcCategory['id'], $categoryId);
                    $categoryMap[$wcCategory['id']] = $categoryId;

                } catch (Exception $e) {
                    $stats['errors'][] = "Category {$wcCategory['id']}: " . $e->getMessage();
                    Log::error("WooCommerce category migration error", [
                        'category_id' => $wcCategory['id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Check if there are more pages
            $headers = $result['headers'] ?? [];
            $totalPages = isset($headers['X-WP-TotalPages'][0]) ? (int)$headers['X-WP-TotalPages'][0] : 1;
            
            if ($page >= $totalPages) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        return $stats;
    }

    /**
     * Migrate products
     */
    public function migrateProducts($dryRun = false): array
    {
        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $page = 1;
        $hasMore = true;

        while ($hasMore) {
            $result = $this->connectionService->getProducts($page, $this->config['migration']['chunk_size']);

            if (!$result['success']) {
                $stats['errors'][] = "Failed to fetch products: " . ($result['message'] ?? 'Unknown error');
                break;
            }

            $products = $result['data'];
            
            if (empty($products)) {
                $hasMore = false;
                break;
            }

            foreach ($products as $wcProduct) {
                $stats['total']++;

                try {
                    $existingId = $this->getLocalId('product', $wcProduct['id']);
                    
                    if ($existingId && !$this->config['migration']['update_existing']) {
                        $stats['skipped']++;
                        continue;
                    }

                    if ($dryRun) {
                        $stats['created']++;
                        continue;
                    }

                    // Map category and additional categories
                    $categoryId = $this->config['migration']['default_category_id'];
                    $additionalCategoryIds = [];
                    
                    if (!empty($wcProduct['categories'])) {
                        $firstCategory = $wcProduct['categories'][0];
                        $categoryId = $this->getLocalId('category', $firstCategory['id']) ?? $categoryId;
                        
                        // Collect additional categories
                        foreach (array_slice($wcProduct['categories'], 1) as $category) {
                            $mappedCategoryId = $this->getLocalId('category', $category['id']);
                            if ($mappedCategoryId) {
                                $additionalCategoryIds[] = $mappedCategoryId;
                            }
                        }
                    }

                    // Calculate prices
                    $oldPrice = (float)($wcProduct['regular_price'] ?? 0);
                    $offerPrice = (float)($wcProduct['sale_price'] ?? 0);
                    if ($offerPrice == 0) {
                        $offerPrice = $oldPrice;
                    }

                    // Map stock status
                    $stockStatus = $this->config['mapping']['stock_status'][$wcProduct['stock_status'] ?? 'instock'] ?? 'in_stock';

                    // Extract meta data for additional fields
                    $metaData = $this->extractMetaData($wcProduct['meta_data'] ?? []);

                    // Extract weight
                    $weight = null;
                    if (isset($wcProduct['weight'])) {
                        $weight = (float)$wcProduct['weight'];
                    } elseif (isset($metaData['_weight'])) {
                        $weight = (float)$metaData['_weight'];
                    }

                    // Extract product cost and wholesale price from meta_data
                    $productCost = $metaData['_product_cost'] ?? $metaData['_purchase_price'] ?? null;
                    $wholesalePrice = $metaData['_wholesale_price'] ?? null;

                    // Extract low stock threshold
                    $lowStockThreshold = isset($metaData['_low_stock_amount']) ? (int)$metaData['_low_stock_amount'] : 5;

                    // Extract featured status
                    $isFeatured = isset($wcProduct['featured']) && $wcProduct['featured'] ? 1 : 0;
                    if (!$isFeatured && isset($metaData['_featured'])) {
                        $isFeatured = $metaData['_featured'] === 'yes' ? 1 : 0;
                    }

                    // Extract video URL
                    $videoUrl = $metaData['_product_video'] ?? $metaData['video_url'] ?? null;
                    if (!$videoUrl && isset($wcProduct['video_url'])) {
                        $videoUrl = $wcProduct['video_url'];
                    }

                    // Map sub-category (if available in categories array)
                    $subCategoryId = null;
                    if (!empty($wcProduct['categories']) && count($wcProduct['categories']) > 1) {
                        // Try to find subcategory (you may need to adjust this logic based on your category structure)
                        // For now, we'll use the second category if it exists
                    }

                    // Prepare product data with all available fields
                    $productData = [
                        'title' => $wcProduct['name'],
                        'slug' => $this->generateUniqueSlug($wcProduct['slug'], 'products', $existingId),
                        'description' => $this->cleanHtml($wcProduct['description'] ?? null),
                        'short_description' => $this->cleanHtml($wcProduct['short_description'] ?? null),
                        'old_price' => $oldPrice > 0 ? $oldPrice : null,
                        'offer' => $offerPrice > 0 ? $offerPrice : null,
                        'product_cost' => $productCost ? (float)$productCost : null,
                        'wholesale_price' => $wholesalePrice ? (float)$wholesalePrice : null,
                        'quantity' => $wcProduct['stock_quantity'] ?? null,
                        'weight' => $weight,
                        'stock_status' => $stockStatus,
                        'manage_stock' => $wcProduct['manage_stock'] ?? false,
                        'low_stock_threshold' => $lowStockThreshold,
                        'status' => $this->mapProductStatus($wcProduct['status'] ?? 'publish'),
                        'category_id' => $categoryId,
                        'sub_category_id' => $subCategoryId,
                        'product_type' => $this->mapProductType($wcProduct['type'] ?? 'simple'),
                        'tags' => $this->extractTags($wcProduct),
                        'is_featured' => $isFeatured,
                        'video_url' => $videoUrl,
                    ];

                    // Extract and set SEO data if available
                    $seoData = $this->extractSeoData($wcProduct, $metaData);
                    if (!empty($seoData)) {
                        $productData['seo'] = $seoData;
                    }

                    // Download images
                    $thumbImage = null;
                    $images = [];
                    
                    if ($this->config['migration']['download_images'] && !empty($wcProduct['images'])) {
                        foreach ($wcProduct['images'] as $index => $image) {
                            // Use 'product' (singular) to match system convention
                            $imagePath = $this->downloadImage($image['src'] ?? '', 'product');
                            if ($imagePath) {
                                if ($index === 0) {
                                    $thumbImage = $imagePath;
                                } else {
                                    $images[] = $imagePath;
                                }
                            }
                        }
                    }
                    
                    // Set thumb_image - required field, use placeholder if no image available
                    if ($thumbImage) {
                        $productData['thumb_image'] = $thumbImage;
                    } else {
                        // Use a placeholder or default image path
                        // You may want to adjust this to your actual placeholder image
                        $productData['thumb_image'] = 'product/placeholder.jpg';
                    }
                    
                    // Store images array as JSON (matching ProductController behavior)
                    if (!empty($images)) {
                        $productData['images'] = json_encode($images, JSON_THROW_ON_ERROR);
                    }

                    if ($existingId) {
                        $product = Product::find($existingId);
                        $product->update($productData);
                        $productId = $existingId;
                        $stats['updated']++;
                    } else {
                        $product = Product::create($productData);
                        $productId = $product->id;
                        $stats['created']++;
                    }

                    // Sync additional categories if any
                    if (!empty($additionalCategoryIds)) {
                        $product->additionalCategories()->sync($additionalCategoryIds);
                    }

                    $this->storeMapping('product', $wcProduct['id'], $productId);

                } catch (Exception $e) {
                    $stats['errors'][] = "Product {$wcProduct['id']}: " . $e->getMessage();
                    Log::error("WooCommerce product migration error", [
                        'product_id' => $wcProduct['id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $headers = $result['headers'] ?? [];
            $totalPages = isset($headers['X-WP-TotalPages'][0]) ? (int)$headers['X-WP-TotalPages'][0] : 1;
            
            if ($page >= $totalPages) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        return $stats;
    }

    /**
     * Migrate users/customers
     */
    public function migrateUsers($dryRun = false): array
    {
        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $page = 1;
        $hasMore = true;

        while ($hasMore) {
            $result = $this->connectionService->getCustomers($page, $this->config['migration']['chunk_size']);

            if (!$result['success']) {
                $stats['errors'][] = "Failed to fetch customers: " . ($result['message'] ?? 'Unknown error');
                break;
            }

            $customers = $result['data'];
            
            if (empty($customers)) {
                $hasMore = false;
                break;
            }

            foreach ($customers as $wcCustomer) {
                $stats['total']++;

                try {
                    // Check by email first
                    $existingUser = User::where('email', $wcCustomer['email'])->first();
                    
                    if ($existingUser) {
                        $existingId = $this->getLocalId('user', $wcCustomer['id']);
                        if (!$existingId) {
                            $this->storeMapping('user', $wcCustomer['id'], $existingUser->id);
                        }
                        
                        if (!$this->config['migration']['update_existing']) {
                            $stats['skipped']++;
                            continue;
                        }
                    }

                    if ($dryRun) {
                        $stats['created']++;
                        continue;
                    }

                    $firstName = $wcCustomer['first_name'] ?? '';
                    $lastName = $wcCustomer['last_name'] ?? '';
                    $name = trim("{$firstName} {$lastName}");
                    if (empty($name)) {
                        $name = $wcCustomer['username'] ?? $wcCustomer['email'];
                    }

                    $billing = $wcCustomer['billing'] ?? [];
                    
                    $userData = [
                        'name' => $name,
                        'email' => $wcCustomer['email'],
                        'phone' => $billing['phone'] ?? null,
                        'address' => $billing['address_1'] ?? null,
                        'city' => $billing['city'] ?? null,
                        'password' => bcrypt(Str::random(16)), // Random password, user can reset
                    ];

                    if ($existingUser) {
                        $existingUser->update($userData);
                        $userId = $existingUser->id;
                        $stats['updated']++;
                    } else {
                        $user = User::create($userData);
                        $userId = $user->id;
                        $stats['created']++;
                    }

                    $this->storeMapping('user', $wcCustomer['id'], $userId);

                } catch (Exception $e) {
                    $stats['errors'][] = "User {$wcCustomer['id']}: " . $e->getMessage();
                    Log::error("WooCommerce user migration error", [
                        'user_id' => $wcCustomer['id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $headers = $result['headers'] ?? [];
            $totalPages = isset($headers['X-WP-TotalPages'][0]) ? (int)$headers['X-WP-TotalPages'][0] : 1;
            
            if ($page >= $totalPages) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        return $stats;
    }

    /**
     * Migrate orders
     */
    public function migrateOrders($dryRun = false): array
    {
        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $page = 1;
        $hasMore = true;

        while ($hasMore) {
            $result = $this->connectionService->getOrders($page, $this->config['migration']['chunk_size']);

            if (!$result['success']) {
                $stats['errors'][] = "Failed to fetch orders: " . ($result['message'] ?? 'Unknown error');
                break;
            }

            $orders = $result['data'];
            
            if (empty($orders)) {
                $hasMore = false;
                break;
            }

            foreach ($orders as $wcOrder) {
                $stats['total']++;

                try {
                    $existingId = $this->getLocalId('order', $wcOrder['id']);
                    
                    if ($existingId && !$this->config['migration']['update_existing']) {
                        $stats['skipped']++;
                        continue;
                    }

                    if ($dryRun) {
                        $stats['created']++;
                        continue;
                    }

                    // Get or create user
                    $userId = null;
                    if (isset($wcOrder['customer_id']) && $wcOrder['customer_id']) {
                        $userId = $this->getLocalId('user', $wcOrder['customer_id']);
                    }

                    // If no user ID, try to find by email
                    if (!$userId && isset($wcOrder['billing']['email'])) {
                        $user = User::where('email', $wcOrder['billing']['email'])->first();
                        $userId = $user ? $user->id : null;
                    }

                    $billing = $wcOrder['billing'] ?? [];
                    $shipping = $wcOrder['shipping'] ?? [];

                    // Extract more order details from meta_data
                    $metaData = $this->extractMetaData($wcOrder['meta_data'] ?? []);
                    
                    // Build customer name (prefer shipping name if available, otherwise billing)
                    $firstName = $shipping['first_name'] ?? $billing['first_name'] ?? '';
                    $lastName = $shipping['last_name'] ?? $billing['last_name'] ?? '';
                    $customerName = trim("{$firstName} {$lastName}");
                    
                    // Build full address
                    $addressLine1 = $shipping['address_1'] ?? $billing['address_1'] ?? '';
                    $addressLine2 = $shipping['address_2'] ?? $billing['address_2'] ?? '';
                    $fullAddress = trim($addressLine1 . (!empty($addressLine2) ? ', ' . $addressLine2 : ''));

                    // Extract phone number from multiple sources (WooCommerce stores it in different places)
                    $phoneNumber = '';
                    
                    // Try shipping phone first
                    if (!empty($shipping['phone'])) {
                        $phoneNumber = trim($shipping['phone']);
                    }
                    // Try billing phone
                    elseif (!empty($billing['phone'])) {
                        $phoneNumber = trim($billing['phone']);
                    }
                    // Try meta_data billing phone
                    elseif (!empty($metaData['_billing_phone'])) {
                        $phoneNumber = trim($metaData['_billing_phone']);
                    }
                    // Try meta_data shipping phone
                    elseif (!empty($metaData['_shipping_phone'])) {
                        $phoneNumber = trim($metaData['_shipping_phone']);
                    }
                    // Try from user if linked
                    elseif ($userId) {
                        $user = User::find($userId);
                        if ($user && !empty($user->phone)) {
                            $phoneNumber = trim($user->phone);
                        }
                    }
                    // Try other possible meta_data keys
                    elseif (!empty($metaData['billing_phone'])) {
                        $phoneNumber = trim($metaData['billing_phone']);
                    }
                    elseif (!empty($metaData['phone'])) {
                        $phoneNumber = trim($metaData['phone']);
                    }
                    
                    // Log if phone is still empty (for debugging)
                    if (empty($phoneNumber) && $this->config['migration']['log_progress']) {
                        Log::warning("Order missing phone number", [
                            'order_id' => $wcOrder['id'],
                            'billing' => $billing,
                            'shipping' => $shipping,
                            'has_meta_data' => !empty($metaData),
                        ]);
                    }

                    // Calculate totals - ensure proper decimal conversion
                    $total = (float)($wcOrder['total'] ?? 0);
                    $shippingCost = (float)($wcOrder['shipping_total'] ?? 0);
                    $discountAmount = (float)($wcOrder['discount_total'] ?? $wcOrder['total_discount'] ?? 0);
                    
                    // Calculate payment gateway charge if available
                    $paymentCharge = 0;
                    $paymentMethod = strtolower($wcOrder['payment_method'] ?? '');
                    
                    // Map payment status
                    $paymentStatus = $this->mapPaymentStatus($wcOrder);
                    
                    // Prepare order data - mapping all fields
                    $orderData = [
                        'user_id' => $userId,
                        'name' => $customerName ?: ($billing['company'] ?? 'Guest'),
                        'phone' => $phoneNumber ?: 'N/A', // Ensure phone is never empty (required field)
                        'address' => $fullAddress ?: $addressLine1,
                        'city' => $shipping['city'] ?? $billing['city'] ?? '',
                        'upazila' => $shipping['state'] ?? $billing['state'] ?? null,
                        'status' => $this->mapOrderStatus($wcOrder['status'] ?? 'pending'),
                        'total' => number_format($total, 2, '.', ''),
                        'shipping' => number_format($shippingCost, 2, '.', ''),
                        'discount' => number_format($discountAmount, 2, '.', ''),
                        'payment_method' => $wcOrder['payment_method_title'] ?? $wcOrder['payment_method'] ?? 'N/A',
                        'payment_status' => $paymentStatus,
                        'order_source' => 'woocommerce', // Mark as migrated from WooCommerce
                        'message' => $wcOrder['customer_note'] ?? $metaData['_order_comments'] ?? null,
                        'admin_note' => $metaData['_admin_order_note'] ?? $wcOrder['order_note'] ?? null,
                        'created_at' => $wcOrder['date_created'] ?? now(),
                        'updated_at' => $wcOrder['date_modified'] ?? now(),
                    ];

                    // Extract payment gateway specific fields (bKash, Nagad, Rocket)
                    if (str_contains($paymentMethod, 'bkap') || str_contains($paymentMethod, 'bkash')) {
                        $orderData['bkash_number'] = $metaData['_billing_bkash'] 
                            ?? $metaData['_bkash_number'] 
                            ?? $billing['bkash'] 
                            ?? null;
                        $orderData['bkash_transaction_id'] = $metaData['_bkash_transaction_id'] 
                            ?? $metaData['_transaction_id'] 
                            ?? null;
                        $orderData['bkash_charge'] = $metaData['_bkash_charge'] ?? $paymentCharge;
                    } elseif (str_contains($paymentMethod, 'nagad')) {
                        $orderData['nagad_number'] = $metaData['_billing_nagad'] 
                            ?? $metaData['_nagad_number'] 
                            ?? $billing['nagad'] 
                            ?? null;
                        $orderData['nagad_transaction_id'] = $metaData['_nagad_transaction_id'] 
                            ?? $metaData['_transaction_id'] 
                            ?? null;
                        $orderData['nagad_charge'] = $metaData['_nagad_charge'] ?? $paymentCharge;
                    } elseif (str_contains($paymentMethod, 'rocket')) {
                        $orderData['rocket_number'] = $metaData['_billing_rocket'] 
                            ?? $metaData['_rocket_number'] 
                            ?? $billing['rocket'] 
                            ?? null;
                        $orderData['rocket_transaction_id'] = $metaData['_rocket_transaction_id'] 
                            ?? $metaData['_transaction_id'] 
                            ?? null;
                        $orderData['rocket_charge'] = $metaData['_rocket_charge'] ?? $paymentCharge;
                    }

                    // Calculate total_with_charge (total + payment gateway charge)
                    $gatewayCharge = $orderData['bkash_charge'] 
                        ?? $orderData['nagad_charge'] 
                        ?? $orderData['rocket_charge'] 
                        ?? 0;
                    $orderData['total_with_charge'] = number_format($total + $gatewayCharge, 2, '.', '');

                    // Store delivery data as JSON (for future use or reference)
                    $deliveryData = [];
                    if (!empty($shipping)) {
                        $deliveryData['shipping'] = $shipping;
                    }
                    if (!empty($billing)) {
                        $deliveryData['billing'] = $billing;
                    }
                    if (!empty($wcOrder['shipping_lines'])) {
                        $deliveryData['shipping_method'] = $wcOrder['shipping_lines'][0]['method_title'] ?? null;
                        $deliveryData['shipping_method_id'] = $wcOrder['shipping_lines'][0]['method_id'] ?? null;
                    }
                    if (!empty($deliveryData)) {
                        $orderData['delivery_data'] = json_encode($deliveryData, JSON_THROW_ON_ERROR);
                    }

                    if ($existingId) {
                        order::where('id', $existingId)->update($orderData);
                        $orderId = $existingId;
                        $stats['updated']++;
                    } else {
                        $order = order::create($orderData);
                        $orderId = $order->id;
                        $stats['created']++;
                    }

                    // Migrate order items
                    if (!empty($wcOrder['line_items'])) {
                        $this->migrateOrderItems($orderId, $wcOrder['line_items'], $existingId !== null);
                    }

                    $this->storeMapping('order', $wcOrder['id'], $orderId);

                } catch (Exception $e) {
                    $stats['errors'][] = "Order {$wcOrder['id']}: " . $e->getMessage();
                    Log::error("WooCommerce order migration error", [
                        'order_id' => $wcOrder['id'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $headers = $result['headers'] ?? [];
            $totalPages = isset($headers['X-WP-TotalPages'][0]) ? (int)$headers['X-WP-TotalPages'][0] : 1;
            
            if ($page >= $totalPages) {
                $hasMore = false;
            } else {
                $page++;
            }
        }

        return $stats;
    }

    /**
     * Migrate order items
     */
    protected function migrateOrderItems($orderId, $items, $isUpdate = false)
    {
        if ($isUpdate) {
            // Delete existing items
            order_item::where('order_id', $orderId)->delete();
        }

        foreach ($items as $wcItem) {
            $productId = $this->getLocalId('product', $wcItem['product_id'] ?? 0);
            
            if (!$productId) {
                Log::warning("Order item skipped - product not found", [
                    'order_id' => $orderId,
                    'woocommerce_product_id' => $wcItem['product_id'] ?? null,
                    'item_name' => $wcItem['name'] ?? 'Unknown',
                ]);
                continue; // Skip if product not migrated
            }

            // Extract item details
            $quantity = (int)($wcItem['quantity'] ?? 1);
            
            // Calculate price - use line total if available, otherwise calculate from subtotal
            $lineTotal = (float)($wcItem['total'] ?? 0);
            $lineSubtotal = (float)($wcItem['subtotal'] ?? 0);
            
            // Price per unit (line total divided by quantity)
            $price = $quantity > 0 ? ($lineTotal / $quantity) : $lineSubtotal;
            
            // Sub total should match line total
            $subTotal = $lineTotal > 0 ? $lineTotal : ($lineSubtotal * $quantity);

            // Extract variation/combination info if available
            $variationId = $wcItem['variation_id'] ?? null;
            $combinationId = null;
            $optionId = null;
            
            // Extract meta data from item
            $itemMeta = $this->extractMetaData($wcItem['meta_data'] ?? []);
            
            // Try to find variation combination if product has variations
            if ($variationId) {
                // Note: You may need to map WooCommerce variation IDs to your system's variation combinations
                // For now, we'll leave this null and you can enhance it later if needed
            }

            order_item::create([
                'order_id' => $orderId,
                'product_id' => $productId,
                'option_id' => $optionId,
                'combination_id' => $combinationId,
                'quantity' => $quantity,
                'price' => number_format($price, 2, '.', ''),
                'sub_total' => number_format($subTotal, 2, '.', ''),
                'others' => !empty($itemMeta) ? json_encode($itemMeta, JSON_THROW_ON_ERROR) : null,
            ]);
        }
    }

    /**
     * Helper methods
     */
    protected function generateUniqueSlug($slug, $table, $excludeId = null)
    {
        $baseSlug = Str::slug($slug);
        $newSlug = $baseSlug;
        $counter = 1;

        while (DB::table($table)->where('slug', $newSlug)->when($excludeId, function ($q) use ($excludeId) {
            $q->where('id', '!=', $excludeId);
        })->exists()) {
            $newSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $newSlug;
    }

    protected function downloadImage($url, $directory = 'product')
    {
        if (empty($url)) {
            return null;
        }

        try {
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                Log::warning("Failed to download image - HTTP error", [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $imageContent = $response->body();
            
            if (empty($imageContent)) {
                Log::warning("Failed to download image - empty content", ['url' => $url]);
                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            
            if (empty($extension)) {
                // Try to detect from Content-Type header
                $contentType = $response->header('Content-Type');
                if (str_contains($contentType, 'jpeg') || str_contains($contentType, 'jpg')) {
                    $extension = 'jpg';
                } elseif (str_contains($contentType, 'png')) {
                    $extension = 'png';
                } elseif (str_contains($contentType, 'webp')) {
                    $extension = 'webp';
                } else {
                    $extension = 'jpg';
                }
            }

            // Use 'product' (singular) to match the system's convention
            // ProductController uses: $thumbImage->storeAs('public', $thumbImagePath)
            // which stores to storage/app/public/product/filename.jpg
            $filename = time() . '-' . Str::random(20) . '.' . $extension;
            $path = "{$directory}/{$filename}";

            // Use 'storage' disk which points to storage_path('app/public')
            // This matches where ProductController stores files
            Storage::disk('storage')->put($path, $imageContent);

            // Log successful download for debugging
            if ($this->config['migration']['log_progress']) {
                Log::info("Image downloaded successfully", [
                    'url' => $url,
                    'path' => $path,
                    'size' => strlen($imageContent),
                ]);
            }

            return $path;

        } catch (Exception $e) {
            Log::warning("Failed to download image: {$url}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function mapProductStatus($status)
    {
        // Status is a boolean field (0 or 1) in the database
        $mapping = [
            'publish' => 1,  // active
            'draft' => 0,    // inactive
            'private' => 0,  // inactive
            'pending' => 0,  // inactive
        ];
        return $mapping[$status] ?? 0; // Default to inactive (0)
    }

    protected function mapOrderStatus($status)
    {
        $mapping = $this->config['mapping']['order_status'];
        return $mapping[$status] ?? 'pending';
    }

    protected function mapPaymentStatus($order)
    {
        // Check if order is paid
        if (isset($order['date_paid']) && !empty($order['date_paid'])) {
            return 'paid';
        }
        
        // Check payment status from meta_data
        $metaData = $this->extractMetaData($order['meta_data'] ?? []);
        $paymentStatus = $metaData['_payment_status'] ?? $order['payment_status'] ?? null;
        
        if ($paymentStatus) {
            $statusMapping = [
                'paid' => 'paid',
                'completed' => 'paid',
                'processing' => 'paid',
                'pending' => 'pending',
                'failed' => 'failed',
                'cancelled' => 'failed',
                'refunded' => 'failed',
                'transaction_not_matched' => 'transaction_not_matched',
            ];
            return $statusMapping[strtolower($paymentStatus)] ?? 'pending';
        }
        
        // Default based on order status
        $orderStatus = strtolower($order['status'] ?? 'pending');
        if (in_array($orderStatus, ['completed', 'processing'])) {
            return 'paid';
        }
        
        return 'pending';
    }

    protected function mapProductType($type)
    {
        $mapping = [
            'simple' => 'simple',
            'variable' => 'variable',
            'grouped' => 'simple',
            'external' => 'external',
        ];
        return $mapping[$type] ?? 'simple';
    }

    protected function extractTags($product)
    {
        if (isset($product['tags']) && is_array($product['tags'])) {
            return implode(',', array_column($product['tags'], 'name'));
        }
        return '';
    }

    /**
     * Extract meta data from WooCommerce product
     */
    protected function extractMetaData($metaDataArray)
    {
        $meta = [];
        if (is_array($metaDataArray)) {
            foreach ($metaDataArray as $item) {
                if (isset($item['key']) && isset($item['value'])) {
                    $meta[$item['key']] = $item['value'];
                }
            }
        }
        return $meta;
    }

    /**
     * Extract SEO data from WooCommerce product
     */
    protected function extractSeoData($wcProduct, $metaData)
    {
        $seo = [];

        // Meta title
        if (isset($metaData['_yoast_wpseo_title'])) {
            $seo['meta_title'] = $metaData['_yoast_wpseo_title'];
        } elseif (isset($metaData['_seopress_titles_title'])) {
            $seo['meta_title'] = $metaData['_seopress_titles_title'];
        }

        // Meta description
        if (isset($metaData['_yoast_wpseo_metadesc'])) {
            $seo['meta_description'] = $metaData['_yoast_wpseo_metadesc'];
        } elseif (isset($metaData['_seopress_titles_desc'])) {
            $seo['meta_description'] = $metaData['_seopress_titles_desc'];
        }

        // Meta keywords
        if (isset($metaData['_yoast_wpseo_focuskw'])) {
            $seo['meta_keywords'] = $metaData['_yoast_wpseo_focuskw'];
        } elseif (isset($metaData['_seopress_titles_keywords'])) {
            $seo['meta_keywords'] = $metaData['_seopress_titles_keywords'];
        }

        // Canonical URL
        if (isset($metaData['_yoast_wpseo_canonical'])) {
            $seo['canonical_url'] = $metaData['_yoast_wpseo_canonical'];
        }

        // OG Image
        if (isset($metaData['_yoast_wpseo_opengraph-image'])) {
            $seo['og_image'] = $metaData['_yoast_wpseo_opengraph-image'];
        }

        return $seo;
    }

    /**
     * Clean HTML content (remove unwanted tags/styling)
     */
    protected function cleanHtml($html)
    {
        if (empty($html)) {
            return null;
        }

        // Remove data attributes that WooCommerce sometimes adds
        $html = preg_replace('/\s+data-[^=]*="[^"]*"/', '', $html);
        
        return $html;
    }
}

