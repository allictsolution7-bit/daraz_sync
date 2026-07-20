<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\OthersController;
use App\Http\Controllers\Client\WriterController;
use App\Http\Controllers\Client\VendorStoreController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Client\BlogFrontController;
use App\Http\Controllers\Client\CommentController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\ShippingRuleController;
use App\Http\Controllers\Admin\ShippingRuleController as AdminShippingRuleController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\Courier\PathaoController;
use App\Http\Controllers\BasicShippingSettingController;
use App\Http\Controllers\Admin\PostSubCategoryController;
use App\Http\Controllers\Client\IncompleteOrderController;
use App\Http\Controllers\Admin\Courier\SteadFastController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Admin\DeliveryIntegrationController;
use App\Http\Controllers\Admin\FraudCheckerController;
use App\Http\Controllers\OrderController as BackOrderController;
use App\Http\Controllers\Admin\WriterController as AdminWriterController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\PublisherController as FrontendPublisherController;
use App\Models\order;
use App\Services\SettingsService;

use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorWithdrawalController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminVendorProductController;
use App\Http\Controllers\Admin\AdminVendorWithdrawalController;
use App\Http\Controllers\Admin\VendorGlobalSettingsController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BackupScheduleController;
use App\Http\Controllers\Admin\BackupHistoryController;
use App\Http\Controllers\Admin\UpdateController as AdminUpdateController;
use App\Http\Controllers\Admin\CustomerReportController;

// Authentication Routes
Auth::routes();


// OTP Verification Routes for Registration
Route::post('/verify-registration-otp', [App\Http\Controllers\Auth\RegisterController::class, 'verifyOtp'])->name('verify.registration.otp');
Route::post('/resend-registration-otp', [App\Http\Controllers\Auth\RegisterController::class, 'resendOtp'])->name('resend.registration.otp');

// Vendor Registration Routes (only if MultiVendor module not enabled - module handles its own routes)
if (!module_enabled('MultiVendor')) {
    Route::get('/vendor/register', [App\Http\Controllers\Auth\VendorRegisterController::class, 'showRegistrationForm'])->name('vendor.register');
    Route::post('/vendor/register', [App\Http\Controllers\Auth\VendorRegisterController::class, 'register'])->name('vendor.register.submit');
    Route::post('/vendor/register/verify-otp', [App\Http\Controllers\Auth\VendorRegisterController::class, 'verifyOtp'])->name('vendor.register.verify-otp');
    Route::post('/vendor/register/resend-otp', [App\Http\Controllers\Auth\VendorRegisterController::class, 'resendOtp'])->name('vendor.register.resend-otp');
    Route::post('/vendor/register/check-slug', [App\Http\Controllers\Auth\VendorRegisterController::class, 'checkSlug'])->name('vendor.register.check-slug');
}

Route::get('/clear-cache', function () {
    try {
        // Clear all caches
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');
        \Artisan::call('optimize:clear');

        // Run database migrations (force for production)
        \Artisan::call('migrate', [
            '--force' => true,
        ]);

        // Clear application cache
        \Cache::flush();

        return response()->json([
            'success' => true,
            'message' => 'All caches cleared and migrations run successfully!',
            'cleared' => [
                'application_cache' => 'Cleared',
                'config_cache' => 'Cleared',
                'route_cache' => 'Cleared',
                'view_cache' => 'Cleared',
                'optimized_files' => 'Cleared',
                'cache_store' => 'Flushed',
                'migrations' => 'Migrated'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->name('clear.cache');


// Main Frontend Routes
Route::get('/', [OthersController::class, 'index'])->name("index");
Route::get('/shop/{category?}/{sub_category?}/{third_category?}', [OthersController::class, 'shop'])->name("shop");

// Vendor Store (Public) - only if MultiVendor module not enabled
if (!module_enabled('MultiVendor')) {
    Route::get('/store/{slug}', [VendorStoreController::class, 'show'])->name('vendor.store.show');
}

// Public PDF Routes (accessible without admin login)
// Uses POS module if enabled, otherwise falls back to default PDF service
Route::get('/order/{order}/print-receipt', [App\Http\Controllers\OrderPdfController::class, 'printReceipt'])->name('order.print-receipt');
Route::get('/order/{order}/print-invoice', [App\Http\Controllers\OrderPdfController::class, 'printInvoice'])->name('order.print-invoice');
Route::get('/order/{order}/print-package-slip', [App\Http\Controllers\OrderPdfController::class, 'printPackageSlip'])->name('order.print-package-slip');
Route::get('/order/{order}/download-receipt', [App\Http\Controllers\OrderPdfController::class, 'downloadReceipt'])->name('order.download-receipt');
Route::get('/order/{order}/download-invoice', [App\Http\Controllers\OrderPdfController::class, 'downloadInvoice'])->name('order.download-invoice');
Route::get('/order/{order}/download-package-slip', [App\Http\Controllers\OrderPdfController::class, 'downloadPackageSlip'])->name('order.download-package-slip');
Route::post('/shop/filter', [OthersController::class, 'shopFilter'])->name("shop.filter");
Route::get('/product/{id}/{slug}', [ProductController::class, 'index'])->name('product.single');
Route::get('/search/ajax', [SearchController::class, 'ajaxSearch'])->name('search.ajax');
Route::post('/ajax/load-latest-products', [OthersController::class, 'loadLatestProducts'])->name('ajax.load-latest-products');
Route::match(['get', 'post'], '/ajax/mega-category-products', [OthersController::class, 'loadMegaCategoryProducts'])->name('ajax.mega-category-products');
Route::post('/api/products/{product}/view', [App\Http\Controllers\Client\ProductController::class, 'trackView'])->name('products.view');

// Contact Routes
Route::get('/contact-us', [ClientController::class, 'contact'])->name('contact-us');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// Writers
Route::get('/writers', [WriterController::class, 'index'])->name('frontend.writers.index');
Route::get('/writers/{writer}', [WriterController::class, 'show'])->name('frontend.writers.show');

// Publishers
Route::get('/publishers', [FrontendPublisherController::class, 'index'])->name('frontend.publishers.index');
Route::get('/publishers/{publisher}', [FrontendPublisherController::class, 'show'])->name('frontend.publishers.show');

// Cart & Checkout Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
Route::post('/cart/combo/store', [CartController::class, 'storeCombo'])->name('cart.combo.store');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
Route::get('/cart/delete/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/cart/add-quick', [App\Http\Controllers\Client\OrderController::class, 'addQuick'])->name('cart.add.quick');
Route::get('/cart/count', [CartController::class, 'cartCount'])->name('cart.count');
Route::get('/cart/sidebar', [CartController::class, 'sidebar'])->name('cart.sidebar');

// Buy Now Routes
Route::get('/buy/store', [CartController::class, 'buystore'])->name('buy.store');
Route::post('/buy/store', [CartController::class, 'buystore'])->name('buy.store.post');
Route::post('/buy/combo/store', [CartController::class, 'buyComboStore'])->name('buy.combo.store');
Route::post('/buy/order', [CartController::class, 'buynoworder'])->name('buynow.order');
Route::post('/buy/verifyOtp', [CartController::class, 'verifyBuynowOtp'])->name('otp.verify.buynow');

// Landing Page Routes
Route::get('/landing/{slug}', [App\Http\Controllers\LandingPageController::class, 'show'])->name('landing.page');
Route::post('/landing/order', [App\Http\Controllers\LandingPageController::class, 'placeOrder'])->name('landing.order');
Route::post('/api/landing-pages/{landingPage}/view', [App\Http\Controllers\LandingPageController::class, 'trackView'])->name('landing-pages.view');

// Async Telegram Notification (called from thank you page)
Route::post('/api/send-order-notification', [App\Http\Controllers\LandingPageController::class, 'sendOrderNotification'])->name('send.order.notification');

// Order Routes
Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::post('/order/verifyOtp', [OrderController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/order/resendOtp', [OrderController::class, 'resendOtp'])->name('otp.resend');
Route::get('/thank-you/{order}', [OrderController::class, 'thankYou'])->name('order.thankYou');
Route::get('/newthank-you/{order}', [OrderController::class, 'newthankYou'])->name('neworder.thankYou');
Route::get('order/{orderId}/download-pdf', [OrderController::class, 'downloadOrderPDF'])->name('downloadOrderPDF');

// Payment Gateway Routes
Route::post('/payment/initiate', [\App\Http\Controllers\PaymentGatewayController::class, 'initiate'])->name('payment.initiate');
Route::get('/payment/callback/{provider}', [\App\Http\Controllers\PaymentGatewayController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook/{provider}', [\App\Http\Controllers\PaymentGatewayController::class, 'webhook'])->name('payment.webhook');

// Subscription Route
Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe');

// Combo Offer Routes
Route::prefix('combo')->name('combo.')->group(function () {
    Route::get('/offers/{productId}', [App\Http\Controllers\ComboOfferController::class, 'getComboOffers'])->name('offers');
    Route::get('/details/{comboOfferId}', [App\Http\Controllers\ComboOfferController::class, 'getComboOfferDetails'])->name('details');
    Route::post('/selection/add', [App\Http\Controllers\ComboOfferController::class, 'addSelection'])->name('selection.add');
    Route::put('/selection/{selectionId}', [App\Http\Controllers\ComboOfferController::class, 'updateSelection'])->name('selection.update');
    Route::delete('/selection/{selectionId}', [App\Http\Controllers\ComboOfferController::class, 'removeSelection'])->name('selection.remove');
    Route::delete('/selections/{comboOfferId}', [App\Http\Controllers\ComboOfferController::class, 'clearSelections'])->name('selections.clear');
    Route::get('/summary/{comboOfferId}', [App\Http\Controllers\ComboOfferController::class, 'getSummary'])->name('summary');
    Route::get('/products/{comboOfferId}', [App\Http\Controllers\ComboOfferController::class, 'getAvailableProducts'])->name('products');
});

// User Account Routes
Route::prefix('account')->name('account.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [AccountController::class, 'show'])->name('show');
        Route::get('/edit', [AccountController::class, 'edit'])->name('edit');
        Route::post('/update', [AccountController::class, 'update'])->name('update');
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AccountController::class, 'orderDetail'])->name('order.detail');
        Route::get('/download/digital-product/{order_id}/{product_id}', [AccountController::class, 'downloadDigitalProduct'])->name('download.digital.product');
        // Order Tracking RouteS
    });
});

Route::get('/track-order', [AccountController::class, 'trackOrder'])->name('order.track');
Route::post('/track-order', [AccountController::class, 'trackOrderSubmit'])->name('order.track.submit');

// Email Check Route
Route::post('/check-email', [AdminController::class, 'checkemail'])->name('check.email');
Route::get('/blog/search', [BlogFrontController::class, 'search'])->name('blog.search');

Route::get('sitemap.xml', [App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('sitemap');

Route::get('/blog', [BlogFrontController::class, 'blog'])->name('blog.index');
Route::get('/blog/load-more-featured', [BlogFrontController::class, 'loadMoreFeatured'])->name('blog.loadMoreFeatured');
Route::get('/blog/load-more', [BlogFrontController::class, 'loadMore'])->name('blog.loadMore');
Route::get('/blog/category/{slug}', [BlogFrontController::class, 'blogCategory'])->name('blog.category');
Route::get('/blog/tag/{tag}', [BlogFrontController::class, 'blogTag'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogFrontController::class, 'blogShow'])->name('blog.show');

// Comment routes
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::post('/comments/guest', [CommentController::class, 'storeGuest'])->name('comments.storeGuest');
Route::get('/comments/{postId}', [CommentController::class, 'getComments'])->name('comments.get');
Route::post('/comments/{commentId}/like', [CommentController::class, 'like'])->name('comments.like')->middleware('auth');
Route::post('/comments/{commentId}/report', [CommentController::class, 'report'])->name('comments.report');
Route::get('/get-subcategories/{id}', [PostCategoryController::class, 'getSubcategories']);

// Frontend route for all reviews
Route::get('/reviews', function () {
    $reviews = \App\Models\CustomerReview::where('is_active', true)->latest()->paginate(12);
    
    // Set SEO meta data for reviews page using the new SEO system
    $seoTitle = 'Customer Reviews - ' . \App\Models\SiteSetting::get('general', 'site_name', 'Thikana Shop');
    $seoDescription = 'Read what our customers say about us. Real reviews from satisfied customers about our products and service.';
    $seoKeywords = 'customer reviews, testimonials, feedback, ' . \App\Models\SiteSetting::getDefaultMetaKeywords();
    $seoData = [
        'metaTitle' => $seoTitle,
        'metaDescription' => $seoDescription,
        'metaKeywords' => $seoKeywords,
        'ogImage' => \App\Models\SiteSetting::getDefaultOgImage(),
        'ogType' => 'website',
        'metaRobots' => \App\Models\SiteSetting::getRobotsMeta(),
        'canonicalUrl' => url()->current(),
    ];
    
    return view('frontend.reviews', array_merge(compact('reviews'), $seoData));
})->name('reviews.all');

// Product Review Routes
Route::post('/product/review', [App\Http\Controllers\Client\ProductReviewController::class, 'store'])->name('product.review.store');
Route::get('/product/{product}/reviews', [App\Http\Controllers\Client\ProductReviewController::class, 'getProductReviews'])->name('product.reviews.get');
Route::get('/product/{product}/can-review', [App\Http\Controllers\Client\ProductReviewController::class, 'canReview'])->name('product.review.can');

// Admin Routes
Route::get('/test-auth-role', function() {
    $user = auth()->user();
    if (!$user) {
        return "Not logged in";
    }
    return [
        'id' => $user->id,
        'email' => $user->email,
        'roles' => $user->roles->pluck('name'),
        'permissions' => $user->getAllPermissions()->pluck('name'),
    ];
});

Route::get("/admin", function () {
    return redirect()->route('admin.dashboard');
})->name('admin');

Route::post('/incomplete-order', [IncompleteOrderController::class, 'store'])->name('incomplete-order.store');

Route::prefix('admin')->middleware(['auth', 'license', 'authorize.by_route', 'TrackInstallation'])->name('admin.')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [AdminController::class, 'admin'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');

    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'show'])->name('users.show');
    Route::get('/admin/users/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/usersedit', [AdminController::class, 'usersedit'])->name('users.edit');
    Route::put('/usersupdate/{id}', [AdminController::class, 'usersupdate'])->name('users.update');
    Route::delete('/usersdestroy/{id}', [AdminController::class, 'usersdestroy'])->name('users.destroy');
    Route::delete('/users/bulk-delete', [AdminController::class, 'bulkDelete'])->name('users.bulk-delete');

    Route::post('/orders/bulk-assign', [BackOrderController::class, 'bulkAssign'])->name('orders.bulk-assign');

    // Product Categories
    Route::prefix('categories')->name('product_categories.')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
        Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
        Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
        Route::get('/{product_category}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
        Route::put('/{product_category}', [ProductCategoryController::class, 'update'])->name('update');
        Route::delete('/{product_category}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        Route::match(['post', 'delete'], '/bulk-delete', [ProductCategoryController::class, 'bulkDestroy'])->name('bulk-delete');
    });
    Route::get('/check-category-slug-availability', [ProductCategoryController::class, 'checkSlugAvailability'])->name('check-category-slug-availability');
    Route::get('/get-product-subcategories/{id}', [ProductCategoryController::class, 'getSubcategories'])->name('get-product-subcategories');

    // Sub-categories
    Route::get('sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::get('sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('sub-categories/{sub_category}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('sub-categories/{sub_category}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('sub-categories/{sub_category}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');
    Route::match(['post', 'delete'], 'sub-categories/bulk-delete', [SubCategoryController::class, 'bulkDestroy'])->name('sub-categories.bulk-delete');
    Route::get('/check-subcategory-slug-availability', [SubCategoryController::class, 'checkSlugAvailability'])->name('check-subcategory-slug-availability');
    
    // Third Categories Routes
    Route::get('third-categories', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'index'])->name('third-categories.index');
    Route::get('third-categories/create', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'create'])->name('third-categories.create');
    Route::post('third-categories', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'store'])->name('third-categories.store');
    Route::get('third-categories/{thirdCategory}/edit', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'edit'])->name('third-categories.edit');
    Route::put('third-categories/{thirdCategory}', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'update'])->name('third-categories.update');
    Route::delete('third-categories/{thirdCategory}', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'destroy'])->name('third-categories.destroy');
    Route::match(['post', 'delete'], 'third-categories/bulk-delete', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'bulkDestroy'])->name('third-categories.bulk-delete');
    Route::post('third-categories/by-subcategories', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'getBySubCategory'])->name('third-categories.by-subcategories');
    Route::get('/check-thirdcategory-slug-availability', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'checkSlugAvailability'])->name('check-thirdcategory-slug-availability');

    // Products
    Route::get('/items', [AdminProductController::class, 'index'])->name('items.index');
    Route::get('/items/create', [AdminProductController::class, 'productCreate'])->name('items.create');
    Route::post('/items', [AdminProductController::class, 'store'])->name('items.store');
    Route::get('/items/{product}/edit', [AdminProductController::class, 'edit'])->name('items.edit');
    Route::put('/items/{product}', [AdminProductController::class, 'update'])->name('items.update');
    Route::delete('/items/{product}', [AdminProductController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('items.bulk-delete');
    Route::post('/items/bulk-status-toggle', [AdminProductController::class, 'bulkStatusToggle'])->name('items.bulk-status-toggle');
    Route::post('/items/export-selected', [AdminProductController::class, 'exportSelected'])->name('items.export-selected');
    Route::get('/items/data', [AdminProductController::class, 'data'])->name('items.data');
    Route::get('/items/search', [AdminProductController::class, 'search'])->name('items.search');
    Route::get('/item-slug-availability', [AdminProductController::class, 'checkSlugAvailability'])->name('item-slug-availability');

    // Inventory Management
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/data', [InventoryController::class, 'data'])->name('data');
        Route::get('/filtered-stats', [InventoryController::class, 'getFilteredStats'])->name('filtered-stats');
        Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('low-stock');
        Route::get('/out-of-stock', [InventoryController::class, 'outOfStock'])->name('out-of-stock');
        Route::get('/adjust/{product}', [InventoryController::class, 'adjustForm'])->name('adjust-form');
        Route::post('/adjust/{product}', [InventoryController::class, 'adjust'])->name('adjust');
        Route::get('/history', [InventoryController::class, 'history'])->name('history');
        Route::get('/history/{product}', [InventoryController::class, 'productHistory'])->name('product-history');
        Route::get('/combinations/{product}', [InventoryController::class, 'getVariationCombinations'])->name('combinations');
    });

    // POS System - Now handled by Modules/POS module
    // Routes are registered via Modules/POS/Providers/RouteServiceProvider
    // The module auto-registers routes when enabled via license
    // Legacy routes kept for reference:
    // Route::prefix('pos')->name('pos.')->middleware('license:pos')->group(...);

    // Payment Gateways Management
    Route::get('payment-gateways', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'index'])->name('payment-gateways.index');
    Route::get('payment-gateways/{gateway}/edit', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('payment-gateways/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'update'])->name('payment-gateways.update');
    Route::post('payment-gateways/{gateway}/toggle', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'toggle'])->name('payment-gateways.toggle');

    // Orders Management
    Route::prefix('sales')->name('orders.')->group(function () {
        Route::get('/', [BackOrderController::class, 'index'])->name('index');
        Route::get('/data', [BackOrderController::class, 'data'])->name('data');
        Route::get('/create', [BackOrderController::class, 'create'])->name('create');
        Route::post('/', [BackOrderController::class, 'store'])->name('store');
        Route::get('/product-options/{product}', [BackOrderController::class, 'productOptions'])->name('product-options');
        Route::post('/{order}/items', [BackOrderController::class, 'storeItem'])->name('store-item');
        Route::put('/{order}/items/{item}', [BackOrderController::class, 'updateItem'])->name('update-item');
        Route::delete('/{order}/items/{item}', [BackOrderController::class, 'destroyItem'])->name('destroy-item');
        Route::get('/{order}', [BackOrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [BackOrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [BackOrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [BackOrderController::class, 'destroy'])->name('destroy');
        Route::post('/update-status', [BackOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/bulk-update-status', [BackOrderController::class, 'bulkUpdateStatus'])->name('bulkUpdateStatus');
        Route::post('/bulk-refresh-courier-status', [BackOrderController::class, 'bulkRefreshCourierStatus'])->name('bulkRefreshCourierStatus');
        Route::post('/update-note', [BackOrderController::class, 'updateNote'])->name('updateNote');
        Route::post('/delete-multiple', [OrderController::class, 'deleteMultiple'])->name('deleteMultiple');
        Route::post('/export-selected', [OrderController::class, 'exportSelected'])->name('export-selected');
        Route::post('/fire-purchase-event', [BackOrderController::class, 'firePurchaseEvent'])->name('firePurchaseEvent');
        Route::post('/check-pending-purchase-event', [BackOrderController::class, 'checkPendingPurchaseEvent'])->name('checkPendingPurchaseEvent');
    });
    Route::get('/my-assigned-sales', [OrderController::class, 'asignedorders'])->name('asigned.orders');

    // Sales Reports
    Route::get('reports/sales', [SalesReportController::class, 'index'])->name('orders.reports');
    Route::get('reports/sales/data', [SalesReportController::class, 'data'])->name('orders.reports.data');
    // Customer Reports
    Route::get('reports/customers', [CustomerReportController::class, 'index'])->name('customers.reports');
    Route::get('reports/customers/data', [CustomerReportController::class, 'data'])->name('customers.reports.data');

    Route::get('/incomplete-orders', [IncompleteOrderController::class, 'index'])->name('incomplete-orders.index');
    Route::get('/incomplete-orders/my-assigned', [IncompleteOrderController::class, 'myAssigned'])->name('incomplete-orders.my-assigned');
    Route::get('/incomplete-orders/data', [IncompleteOrderController::class, 'data'])->name('incomplete-orders.data');
    Route::get('/incomplete-orders/{id}', [IncompleteOrderController::class, 'show'])->name('incomplete-orders.show');
    Route::delete('incomplete-orders/{id}', [IncompleteOrderController::class, 'destroy'])->name('incomplete-orders.destroy');
    Route::post('incomplete-orders/bulk-delete', [IncompleteOrderController::class, 'bulkDelete'])->name('incomplete-orders.bulk-delete');
    Route::post('incomplete-orders/export-selected', [IncompleteOrderController::class, 'exportSelected'])->name('incomplete-orders.export-selected');
    Route::post('incomplete-orders/{id}/update-status', [IncompleteOrderController::class, 'updateStatus'])->name('incomplete-orders.update-status');
    Route::post('incomplete-orders/{id}/update-note', [IncompleteOrderController::class, 'updateNote'])->name('incomplete-orders.update-note');
    Route::post('incomplete-orders/convert', [IncompleteOrderController::class, 'convert'])->name('incomplete-orders.convert');
    Route::get('incomplete-orders/get-variation-combinations', [IncompleteOrderController::class, 'getVariationCombinations'])->name('incomplete-orders.get-variation-combinations');
    Route::get('incomplete-orders/{id}/convert', [IncompleteOrderController::class, 'showConvertPage'])->name('incomplete-orders.convert-page');

    // Pages
    Route::get('pages', [\App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
    Route::get('pages/create', [\App\Http\Controllers\PageController::class, 'create'])->name('pages.create');
    Route::post('pages', [\App\Http\Controllers\PageController::class, 'store'])->name('pages.store');
    Route::get('pages/{page}/edit', [\App\Http\Controllers\PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{page}', [\App\Http\Controllers\PageController::class, 'update'])->name('pages.update');
    Route::delete('pages/{page}', [\App\Http\Controllers\PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('pages/check-slug', [\App\Http\Controllers\PageController::class, 'checkSlugAvailability'])->name('pages.check-slug');
    
    // Landing Pages Management
    Route::middleware(['license:landing_page'])->group(function () {
        Route::resource('landing-pages', \App\Http\Controllers\Admin\LandingPageController::class)->except(['store']);
        Route::post('landing-pages/{landingPage}/toggle-status', [\App\Http\Controllers\Admin\LandingPageController::class, 'toggleStatus'])->name('landing-pages.toggle-status');
        Route::post('landing-pages/update-positions', [\App\Http\Controllers\Admin\LandingPageController::class, 'updatePositions'])->name('landing-pages.update-positions');
        Route::post('landing-pages/{landingPage}/copy', [\App\Http\Controllers\Admin\LandingPageController::class, 'copy'])->name('landing-pages.copy');
    });
    
    // Landing Page creation with quota check
    Route::post('landing-pages', [\App\Http\Controllers\Admin\LandingPageController::class, 'store'])
        ->middleware(['license:landing_page', 'license.landing_page'])
        ->name('landing-pages.store');

    // Site Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/payment-gateway', [SettingController::class, 'paymentGateway'])->name('settings.payment-gateway');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/sitemap/generate', [SettingController::class, 'generateSitemap'])->name('sitemap.generate');

    // Modules & Tools Dashboard (with System Modules)
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('index');
        Route::post('/upload', [App\Http\Controllers\Admin\ModuleController::class, 'upload'])->name('upload');
        Route::post('/sync', [App\Http\Controllers\Admin\ModuleController::class, 'sync'])->name('sync');
        Route::get('/{name}', [App\Http\Controllers\Admin\ModuleController::class, 'show'])->name('show');
        Route::post('/{name}/enable', [App\Http\Controllers\Admin\ModuleController::class, 'enable'])->name('enable');
        Route::post('/{name}/disable', [App\Http\Controllers\Admin\ModuleController::class, 'disable'])->name('disable');
        Route::delete('/{name}', [App\Http\Controllers\Admin\ModuleController::class, 'delete'])->name('delete');
    });

    // WooCommerce Migration Routes
    Route::prefix('woocommerce-migration')->name('woocommerce-migration.')->group(function () {
        Route::post('/settings', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'saveSettings'])->name('settings.save');
        Route::get('/', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'index'])->name('index');
        Route::post('/test-connection', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'testConnection'])->name('test-connection');
        Route::post('/migrate/{entity}', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'migrateEntity'])->name('migrate.entity');
        Route::post('/migrate-all', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'migrateAll'])->name('migrate.all');
    });

    // Combo Offers
    Route::resource('combo_offers', App\Http\Controllers\Admin\ComboOfferController::class);
    Route::get('combo_offers/{comboOffer}/toggle-status', [App\Http\Controllers\Admin\ComboOfferController::class, 'toggleStatus'])->name('combo_offers.toggle_status');
    Route::post('combo_offers/get-variations', [App\Http\Controllers\Admin\ComboOfferController::class, 'getVariationCombinations'])->name('combo_offers.get_variations');
    
    // Frontend combo offers
    Route::get('combo/offers/{product}', [App\Http\Controllers\ComboOfferController::class, 'getProductComboOffers'])->name('combo.offers.product');

    // Socials
    Route::get('socials', [\App\Http\Controllers\SocialController::class, 'index'])->name('socials.index');
    Route::get('socials/create', [\App\Http\Controllers\SocialController::class, 'create'])->name('socials.create');
    Route::post('socials', [\App\Http\Controllers\SocialController::class, 'store'])->name('socials.store');
    Route::get('socials/{social}/edit', [\App\Http\Controllers\SocialController::class, 'edit'])->name('socials.edit');
    Route::put('socials/{social}', [\App\Http\Controllers\SocialController::class, 'update'])->name('socials.update');
    Route::delete('socials/{social}', [\App\Http\Controllers\SocialController::class, 'destroy'])->name('socials.destroy');

    Route::get('/basic-shipping-settings', [BasicShippingSettingController::class, 'edit'])->name('basic.shipping.settings.edit');
    Route::post('/basic-shipping-settings', [BasicShippingSettingController::class, 'update'])->name('basic.shipping.settings.update');

    // Shipping Management
    Route::prefix('shipping')->name('shipping.')->group(function () {
        // Zones routes
        Route::get('zones', [ShippingZoneController::class, 'index'])->name('zones.index');
        Route::get('zones/create', [ShippingZoneController::class, 'create'])->name('zones.create');
        Route::post('zones', [ShippingZoneController::class, 'store'])->name('zones.store');
        Route::get('zones/{zone}', [ShippingZoneController::class, 'show'])->name('zones.show');
        Route::get('zones/{zone}/edit', [ShippingZoneController::class, 'edit'])->name('zones.edit');
        Route::put('zones/{zone}', [ShippingZoneController::class, 'update'])->name('zones.update');
        Route::delete('zones/{zone}', [ShippingZoneController::class, 'destroy'])->name('zones.destroy');

        // Rules routes
        Route::get('rules', [AdminShippingRuleController::class, 'index'])->name('rules.index');
        Route::get('rules/create', [AdminShippingRuleController::class, 'create'])->name('rules.create');
        Route::post('rules', [AdminShippingRuleController::class, 'store'])->name('rules.store');
        Route::get('rules/{rule}', [AdminShippingRuleController::class, 'show'])->name('rules.show');
        Route::get('rules/{rule}/edit', [AdminShippingRuleController::class, 'edit'])->name('rules.edit');
        Route::put('rules/{rule}', [AdminShippingRuleController::class, 'update'])->name('rules.update');
        Route::delete('rules/{rule}', [AdminShippingRuleController::class, 'destroy'])->name('rules.destroy');
        Route::post('rules/toggle/{rule}', [AdminShippingRuleController::class, 'toggle'])->name('rules.toggle');
        Route::get('rules/get-rules', [AdminShippingRuleController::class, 'getRules'])->name('rules.get-rules');

        // Calculator routes
        Route::get('calculator', [ShippingController::class, 'calculator'])->name('calculator');
        Route::post('calculate', [ShippingController::class, 'calculate'])->name('calculate');
    });

    // Cities
    Route::get('cities', [CityController::class, 'index'])->name('cities.index');
    Route::get('cities/create', [CityController::class, 'create'])->name('cities.create');
    Route::post('cities', [CityController::class, 'store'])->name('cities.store');
    Route::get('cities/{city}', [CityController::class, 'show'])->name('cities.show');
    Route::get('cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
    Route::put('cities/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::delete('cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');

    // Menu Management
    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::get('menus/available', [MenuController::class, 'getAvailableMenus'])->name('menus.available');
    Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Menu Items
    Route::get('menus/{menu}/items/create', [MenuItemController::class, 'create'])->name('menus.items.create');
    Route::post('menus/{menu}/items', [MenuItemController::class, 'store'])->name('menus.items.store');
    Route::get('menus/{menu}/items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menus.items.edit');
    Route::put('menus/{menu}/items/{menuItem}', [MenuItemController::class, 'update'])->name('menus.items.update');
    Route::delete('menus/{menu}/items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menus.items.destroy');
    Route::post('menu-items/update-order', [MenuItemController::class, 'updateOrder'])->name('menu-items.update-order');
    Route::post('admin/menu-items/update-order', [MenuItemController::class, 'updateOrder'])->name('admin.menu-items.update-order');

    // Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::patch('/subscriptions/{subscription}/toggle-status', [SubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggle-status');
    Route::delete('/subscriptions/bulk-delete', [SubscriptionController::class, 'bulkDelete'])->name('subscriptions.bulk-delete');

    // Contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/unread', [ContactController::class, 'unread'])->name('contacts.unread');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // Sliders Management
    Route::resource('sliders', \App\Http\Controllers\SliderController::class);
    Route::post('sliders/update-positions', [\App\Http\Controllers\SliderController::class, 'updatePositions'])->name('sliders.update-positions');

    // Post Routes - restrict to users with manage posts permission
    Route::get('/post/add', [PostController::class, 'Add'])->name('post.add');
    Route::get('/post/validate-slug', [PostController::class, 'ValidatePostSlug'])->name('validate.post.slug');
    Route::post('/post/store', [PostController::class, 'Store'])->name('post.store');
    Route::get('/post/index', [PostController::class, 'Index'])->name('post.index');
    Route::get('/post/{id}/view', [PostController::class, 'View'])->name('post.view');
    Route::get('/post/{id}/edit', [PostController::class, 'Edit'])->name('post.edit');
    Route::put('/post/{id}', [PostController::class, 'Update'])->name('post.update');
    Route::delete('/post/{id}', [PostController::class, 'Destroy'])->name('post.destroy');

    // Category Routes
    Route::get('/category/add', [PostCategoryController::class, 'Add'])->name('category.add');
    Route::get('/postcategory/validate-slug', [PostController::class, 'PostCategorySlug'])->name('validate.category.slug');
    Route::post('/category/store', [PostCategoryController::class, 'Store'])->name('category.store');
    Route::get('/category/index', [PostCategoryController::class, 'Index'])->name('category.index');
    Route::get('/category/{id}/view', [PostCategoryController::class, 'View'])->name('category.view');
    Route::get('/category/{id}/edit', [PostCategoryController::class, 'Edit'])->name('category.edit');
    Route::put('/category/{id}', [PostCategoryController::class, 'Update'])->name('category.update');
    Route::delete('/category/{id}', [PostCategoryController::class, 'Destroy'])->name('category.destroy');

    // Sub Category Routes
    Route::get('/subcategory/add', [PostSubCategoryController::class, 'Add'])->name('postsubcategory.add');
    Route::get('/postsubcategory/validate-slug', [PostController::class, 'PostSubCategorySlug'])->name('validate.postsubcategory.slug');
    Route::post('/subcategory/store', [PostSubCategoryController::class, 'Store'])->name('postsubcategory.store');
    Route::get('/subcategory/index', [PostSubCategoryController::class, 'Index'])->name('postsubcategory.index');
    Route::get('/subcategory/{id}/view', [PostSubCategoryController::class, 'View'])->name('postsubcategory.view');
    Route::get('/subcategory/{id}/edit', [PostSubCategoryController::class, 'Edit'])->name('postsubcategory.edit');
    Route::put('/subcategory/{id}', [PostSubCategoryController::class, 'Update'])->name('postsubcategory.update');
    Route::delete('/subcategory/{id}', [PostSubCategoryController::class, 'Destroy'])->name('postsubcategory.destroy');

    Route::resource('writers', AdminWriterController::class);
    Route::resource('publishers', PublisherController::class);
    
    // Brands Management
    Route::match(['post', 'delete'], 'brands/bulk-delete', [\App\Http\Controllers\Admin\BrandController::class, 'bulkDestroy'])->name('brands.bulk-delete');
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::get('/check-brand-slug-availability', [\App\Http\Controllers\Admin\BrandController::class, 'checkSlugAvailability'])->name('check-brand-slug-availability');

    // Manual Customers Reviews

    Route::resource('reviews', \App\Http\Controllers\Admin\CustomerReviewController::class);
    Route::post('reviews/{review}/toggle-status', [\App\Http\Controllers\Admin\CustomerReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');

    // Comment management routes
    Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->except(['create', 'store']);
    Route::post('comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/spam', [\App\Http\Controllers\Admin\CommentController::class, 'markAsSpam'])->name('comments.spam');
    Route::post('comments/bulk-action', [\App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comments.bulk-action');

    Route::prefix('delivery')->group(function () {
        Route::get('/', [DeliveryIntegrationController::class, 'index'])->name('delivery.index');
        Route::match(['get', 'post'], '/integration/{id?}', [DeliveryIntegrationController::class, 'integrationForm'])->name('delivery.integration');
        Route::delete('/{id}', [DeliveryIntegrationController::class, 'destroy'])->name('delivery.destroy');
    });

    // Fraud Checker Routes
    Route::prefix('fraud-checker')->name('fraud-checker.')->group(function () {
        Route::get('/', [FraudCheckerController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/integration/{id?}', [FraudCheckerController::class, 'integrationForm'])->name('integration');
        Route::delete('/{id}', [FraudCheckerController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/test-connection', [FraudCheckerController::class, 'testConnection'])->name('test-connection');
        Route::post('/check-phone', [FraudCheckerController::class, 'checkPhone'])->name('check-phone');
        Route::post('/orders/{orderId}/check-fraud', [FraudCheckerController::class, 'checkOrderFraud'])->name('check-order-fraud');
        Route::get('/results', [FraudCheckerController::class, 'results'])->name('results');
        Route::get('/results/{id}', [FraudCheckerController::class, 'resultDetails'])->name('result-details');
        Route::get('/results/{id}/refresh', [FraudCheckerController::class, 'refreshResult'])->name('refresh-result');
    });

    // Fraud Protection Routes
    Route::prefix('fraud-protection')->name('fraud-protection.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'update'])->name('update');
        Route::get('/logs', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'logs'])->name('logs');
        Route::delete('/logs', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'clearLogs'])->name('clear-logs');
        Route::post('/blacklist', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'addToBlacklist'])->name('add-to-blacklist');
        Route::get('/export', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'exportLogs'])->name('export');
        Route::delete('/bulk-delete', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'bulkDelete'])->name('bulk-delete');
        Route::delete('/delete-single', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'deleteSingle'])->name('delete-single');
        Route::post('/unblock-phone', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'unblockPhone'])->name('unblock-phone');
        Route::post('/unblock-ip', [\App\Http\Controllers\Admin\FraudProtectionController::class, 'unblockIp'])->name('unblock-ip');
    });

    // Telegram Settings
    Route::prefix('telegram-settings')->name('telegram-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'update'])->name('update');
        Route::post('/test', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'test'])->name('test');
    });

    // Delayed Purchase Events Settings
    Route::prefix('delayed-events')->name('delayed-events.')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\DelayedEventSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [\App\Http\Controllers\DelayedEventSettingController::class, 'update'])->name('settings.update');
        Route::post('/test-connection', [\App\Http\Controllers\DelayedEventSettingController::class, 'testConnection'])->name('test-connection');
        Route::get('/pending', [\App\Http\Controllers\DelayedEventSettingController::class, 'pendingEvents'])->name('pending');
        Route::get('/history', [\App\Http\Controllers\DelayedEventSettingController::class, 'eventHistory'])->name('history');
    });
    Route::post('orders/send-to-courier', [PathaoController::class, 'sendToCourier'])->name('orders.sendToCourier');
    Route::post('orders/send-bulk-to-courier', [PathaoController::class, 'sendBulkToCourier'])->name('orders.sendBulkToCourier');
    Route::post('orders/{order}/save-send-courier', [PathaoController::class, 'saveSendCourier'])->name('orders.saveSendCourier');
    Route::get('courier-cities', [PathaoController::class, 'getCourierCities'])->name('courier-cities');
    Route::get('courier-zones', [PathaoController::class, 'getCourierZones'])->name('courier-zones');
    Route::get('courier-areas', [PathaoController::class, 'getCourierAreas'])->name('courier-areas');
    Route::get('orders/{order}/courier-status', [PathaoController::class, 'getCourierOrderStatus'])->name('orders.courierStatus');
    Route::get('courier-pathao-stores', [PathaoController::class, 'getPathaoStores']);
    Route::prefix('steadfast')->group(function () {
        Route::post('send', [SteadFastController::class, 'sendToCourier'])->name('steadfast.send');
        Route::post('send-bulk', [SteadFastController::class, 'sendBulkToCourier'])->name('steadfast.sendBulk');
        Route::get('order-status/{orderId}', [SteadFastController::class, 'getCourierOrderStatus'])->name('steadfast.orderStatus');
        Route::get('balance', [SteadFastController::class, 'getBalance'])->name('steadfast.balance');
    });

    Route::prefix('pathao')->group(function () {
        Route::post('send', [PathaoController::class, 'sendToCourier'])->name('pathao.send');
        Route::post('send-bulk', [PathaoController::class, 'sendBulkToCourier'])->name('pathao.sendBulk');
        Route::get('order-status/{orderId}', [PathaoController::class, 'getCourierOrderStatus'])->name('pathao.orderStatus');
        Route::get('balance', [PathaoController::class, 'getBalance'])->name('pathao.balance');
        Route::get('stores', [PathaoController::class, 'getPathaoStores'])->name('pathao.stores');
    });

    // Unified Roles & Permissions Management
    Route::get('/roles-permissions', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'index'])->name('roles_permissions.index');
    Route::post('/roles-permissions/permission', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'storePermission'])->name('roles_permissions.permission.store');
    Route::post('/roles-permissions/permission/{permission}/update', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updatePermission'])->name('roles_permissions.permission.update');
    Route::post('/roles-permissions/permission/{permission}/delete', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'deletePermission'])->name('roles_permissions.permission.delete');
    Route::post('/roles-permissions/role', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'storeRole'])->name('roles_permissions.role.store');
    Route::post('/roles-permissions/role/{role}/update', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updateRole'])->name('roles_permissions.role.update');
    Route::post('/roles-permissions/role/{role}/delete', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'deleteRole'])->name('roles_permissions.role.delete');
    Route::post('/roles-permissions/user/{user}/roles', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updateUserRoles'])->name('roles_permissions.user_roles.update');
    
    // License Management Routes
    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LicenseController::class, 'index'])->name('index');
        Route::post('/activate', [\App\Http\Controllers\Admin\LicenseController::class, 'activate'])->name('activate');
        Route::post('/revalidate', [\App\Http\Controllers\Admin\LicenseController::class, 'revalidate'])->name('revalidate');
        Route::get('/status', [\App\Http\Controllers\Admin\LicenseController::class, 'status'])->name('status');
        Route::post('/check-module', [\App\Http\Controllers\Admin\LicenseController::class, 'checkModule'])->name('check-module');
        Route::get('/landing-page-quota', [\App\Http\Controllers\Admin\LicenseController::class, 'checkLandingPageQuota'])->name('landing-page-quota');
        
        // License Security Routes
        Route::prefix('health-checks')->name('health-checks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LicenseSecurityController::class, 'index'])->name('index');
            Route::post('/clear-tamper', [\App\Http\Controllers\Admin\LicenseSecurityController::class, 'clearTamperAttempts'])->name('clear-tamper');
            Route::post('/integrity-check', [\App\Http\Controllers\Admin\LicenseSecurityController::class, 'forceIntegrityCheck'])->name('integrity-check');
            Route::get('/stats', [\App\Http\Controllers\Admin\LicenseSecurityController::class, 'getSecurityStats'])->name('stats');
            Route::get('/export', [\App\Http\Controllers\Admin\LicenseSecurityController::class, 'exportSecurityLogs'])->name('export');
        });
    });
    
    // Example routes that require support period (premium features)
    // Route::middleware(['license.support'])->group(function () {
    //     Route::get('/premium-reports', [ReportsController::class, 'premiumReports'])->name('premium-reports');
    //     Route::get('/advanced-analytics', [AnalyticsController::class, 'advanced'])->name('advanced-analytics');
    // });
    
    // System update routes
    Route::middleware(['license.updates'])->group(function () {
        Route::get('/updates', [AdminUpdateController::class, 'index'])->name('updates.index');
        Route::post('/updates/apply', [AdminUpdateController::class, 'apply'])->name('updates.apply');
    });
});


// Catch-all route for pages - must be at the end
Route::get('/{slug}', [OthersController::class, 'page'])->name("page");

// Utility Routes
// Route::get("/migrate", function () {
//     Artisan::call("migrate");
//     return "migrate";
// });


// Route::get('/run-artisan-commands', function () {
//     // Run migrations with force flag (for production)
//     Artisan::call('migrate', ['--force' => true]);

//     // Clear optimization caches
//     Artisan::call('optimize:clear');

//     return Response::json([
//         'message' => 'Commands executed successfully!',
//         'output' => Artisan::output()
//     ]);
// });

// Route::get('/run-artisan-commands', function () {
//     Artisan::call('optimize:clear');

//     // Get migration status
//     Artisan::call('migrate:status');
//     $migrationStatus = Artisan::output();

//     // Determine if any are pending
//     $hasPending = str_contains($migrationStatus, 'Pending');

//     return Response::json([
//         'message' => $hasPending 
//             ? 'There are pending migrations.' 
//             : 'All migrations are up to date!',
//         'pending' => $hasPending,
//         'migration_status' => $migrationStatus,
//     ]);
// });

// Mobile Category AJAX Routes
Route::get('/api/mobile-categories', [CategoryController::class, 'getMobileCategories'])->name('api.mobile.categories');
Route::get('/api/mobile-subcategories/{categoryId}', [CategoryController::class, 'getMobileSubcategories'])->name('api.mobile.subcategories');

// ==========================================
// MULTI-SELLER / VENDOR ROUTES
// ==========================================


// ==========================================
// VENDOR PANEL ROUTES (only if MultiVendor module not enabled)
// ==========================================
if (!module_enabled('MultiVendor')) {
    Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [VendorDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [VendorDashboardController::class, 'updateProfile'])->name('profile.update');

        // Products
        Route::get('/products/subcategories/{categoryId}', [VendorProductController::class, 'getSubcategories'])->name('products.subcategories');
        Route::resource('products', VendorProductController::class);

        // Orders (view only)
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [VendorOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [VendorOrderController::class, 'show'])->name('show');
            Route::get('/earnings/summary', [VendorOrderController::class, 'earnings'])->name('earnings');
        });

        // Withdrawals (requires verified vendor)
        Route::prefix('withdrawals')->name('withdrawals.')->middleware('vendor.verified')->group(function () {
            Route::get('/', [VendorWithdrawalController::class, 'index'])->name('index');
            Route::get('/create', [VendorWithdrawalController::class, 'create'])->name('create');
            Route::post('/', [VendorWithdrawalController::class, 'store'])->name('store');
            Route::get('/{withdrawal}', [VendorWithdrawalController::class, 'show'])->name('show');
            Route::put('/{withdrawal}/cancel', [VendorWithdrawalController::class, 'cancel'])->name('cancel');
        });
    });

    // ==========================================
    // ADMIN VENDOR MANAGEMENT ROUTES
    // ==========================================
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'license', 'role:admin|super_admin|super admin'])->group(function () {

        // Vendor Management
        Route::resource('vendors', AdminVendorController::class);
        Route::post('/vendors/{vendor}/verify', [AdminVendorController::class, 'verify'])->name('vendors.verify');
        Route::post('/vendors/{vendor}/toggle-status', [AdminVendorController::class, 'toggleStatus'])->name('vendors.toggle-status');

        // Vendor Product Approval
        Route::prefix('vendor-products')->name('vendor-products.')->group(function () {
            Route::get('/', [AdminVendorProductController::class, 'index'])->name('index');
            Route::get('/{product}', [AdminVendorProductController::class, 'show'])->name('show');
            Route::post('/{product}/approve', [AdminVendorProductController::class, 'approve'])->name('approve');
            Route::post('/{product}/reject', [AdminVendorProductController::class, 'reject'])->name('reject');
            Route::post('/bulk-approve', [AdminVendorProductController::class, 'bulkApprove'])->name('bulk-approve');
            Route::put('/{product}/commission', [AdminVendorProductController::class, 'updateCommission'])->name('update-commission');
        });

        // Vendor Withdrawal Management
        Route::prefix('vendor-withdrawals')->name('vendor-withdrawals.')->group(function () {
            Route::get('/', [AdminVendorWithdrawalController::class, 'index'])->name('index');
            Route::get('/history', [AdminVendorWithdrawalController::class, 'payoutHistory'])->name('history');
            Route::get('/{withdrawal}', [AdminVendorWithdrawalController::class, 'show'])->name('show');
            Route::post('/{withdrawal}/approve', [AdminVendorWithdrawalController::class, 'approve'])->name('approve');
            Route::post('/{withdrawal}/reject', [AdminVendorWithdrawalController::class, 'reject'])->name('reject');
            Route::post('/{withdrawal}/complete', [AdminVendorWithdrawalController::class, 'complete'])->name('complete');
            Route::post('/bulk-approve', [AdminVendorWithdrawalController::class, 'bulkApprove'])->name('bulk-approve');
        });

        // Vendor Global Settings
        Route::prefix('vendor-settings')->name('vendor-settings.')->group(function () {
            Route::get('/global', [VendorGlobalSettingsController::class, 'index'])->name('global');
            Route::post('/global', [VendorGlobalSettingsController::class, 'update'])->name('global.update');
            Route::post('/global/toggle-system', [VendorGlobalSettingsController::class, 'toggleSystem'])->name('toggle-system');
            Route::put('/global/{key}', [VendorGlobalSettingsController::class, 'updateSingle'])->name('global.update-single');
            Route::post('/global/reset', [VendorGlobalSettingsController::class, 'reset'])->name('global.reset');
            Route::get('/global/export', [VendorGlobalSettingsController::class, 'export'])->name('global.export');
        });
    });
} // End MultiVendor module check

// ==========================================
// ADMIN ROUTES (Non-Vendor)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'license', 'role:admin|super_admin|super admin'])->group(function () {
    // Backup System
    Route::prefix('backup')->name('backup.')->group(function () {
        // Settings
        Route::get('/settings', [BackupController::class, 'settings'])->name('settings');
        Route::post('/settings', [BackupController::class, 'updateSettings'])->name('settings.update');

        // Google Drive
        Route::get('/google-drive/connect', [BackupController::class, 'googleDriveConnect'])->name('google-drive.connect');
        Route::get('/google-drive/callback', [BackupController::class, 'googleDriveCallback'])->name('google-drive.callback');
        Route::post('/google-drive/disconnect', [BackupController::class, 'googleDriveDisconnect'])->name('google-drive.disconnect');
        Route::get('/google-drive/test', [BackupController::class, 'googleDriveTest'])->name('google-drive.test');

        // Manual Backup
        Route::post('/run', [BackupController::class, 'runManualBackup'])->name('run');

        // Schedules
        Route::get('/schedules', [BackupScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [BackupScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [BackupScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{id}/edit', [BackupScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{id}', [BackupScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{id}', [BackupScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::post('/schedules/{id}/toggle', [BackupScheduleController::class, 'toggle'])->name('schedules.toggle');
        Route::post('/schedules/{id}/run-now', [BackupScheduleController::class, 'runNow'])->name('schedules.run-now');

        // History
        Route::get('/history', [BackupHistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{id}/download', [BackupHistoryController::class, 'download'])->name('history.download');
        Route::post('/history/{id}/restore', [BackupHistoryController::class, 'restore'])->name('history.restore');
        Route::delete('/history/{id}', [BackupHistoryController::class, 'destroy'])->name('history.destroy');
    });
});
