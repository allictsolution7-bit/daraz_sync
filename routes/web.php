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
use App\Http\Controllers\Admin\AdminVendorOrderController;
use App\Http\Controllers\Admin\ResellerOrderController;
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
}

// Always allow checking slug availability, verifying OTP, and resending OTP (used by partner registration)
Route::post('/vendor/register/verify-otp', [App\Http\Controllers\Auth\VendorRegisterController::class, 'verifyOtp'])->name('vendor.register.verify-otp');
Route::post('/vendor/register/resend-otp', [App\Http\Controllers\Auth\VendorRegisterController::class, 'resendOtp'])->name('vendor.register.resend-otp');
Route::post('/vendor/register/check-slug', [App\Http\Controllers\Auth\VendorRegisterController::class, 'checkSlug'])->name('vendor.register.check-slug');



// Partner / Multi-role registration routes
Route::get('/join-as-seller', [App\Http\Controllers\Auth\VendorRegisterController::class, 'showPartnerRegistrationForm'])->name('partner.register');
Route::post('/join-as-seller', [App\Http\Controllers\Auth\VendorRegisterController::class, 'registerPartner'])->name('partner.register.submit');


// CSRF Token Refresh Route – used by admin auto-refresh to prevent 419 errors
Route::get('/csrf-token-refresh', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware('web')->name('csrf.refresh');

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

// Vendor Store (Public)
Route::get('/store/{slug}', [VendorStoreController::class, 'show'])->name('vendor.store.show');

// Public PDF Routes (accessible without admin login)
// Uses POS module if enabled, otherwise falls back to default PDF service
Route::get('/order/{order}/print-receipt', [App\Http\Controllers\OrderPdfController::class, 'printReceipt'])->name('order.print-receipt');
Route::get('/order/{order}/print-invoice', [App\Http\Controllers\OrderPdfController::class, 'printInvoice'])->name('order.print-invoice');
Route::get('/order/{order}/print-package-slip', [App\Http\Controllers\OrderPdfController::class, 'printPackageSlip'])->name('order.print-package-slip');
Route::get('/order/{order}/download-receipt', [App\Http\Controllers\OrderPdfController::class, 'downloadReceipt'])->name('order.download-receipt');
Route::get('/order/{order}/download-invoice', [App\Http\Controllers\OrderPdfController::class, 'downloadInvoice'])->name('order.download-invoice');
Route::get('/order/{order}/download-package-slip', [App\Http\Controllers\OrderPdfController::class, 'downloadPackageSlip'])->name('order.download-package-slip');
Route::get('/order/{order}/print-steadfast-invoice', [App\Http\Controllers\OrderPdfController::class, 'printSteadfastInvoice'])->name('order.print-steadfast-invoice');
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
Route::get('/wishlist', function() { return view('frontend.user.wishlist'); })->name('wishlist.index');

// Buy Now Routes
Route::match(['get', 'post'], '/buy/store', [CartController::class, 'buystore'])->name('buy.store.post');
Route::get('/buy/store', [CartController::class, 'buystore'])->name('buy.store');
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
        Route::post('/orders/{id}/cancel', [AccountController::class, 'cancelOrder'])->name('order.cancel');
        Route::post('/orders/{id}/return', [AccountController::class, 'returnOrder'])->name('order.return');
        Route::get('/download/digital-product/{order_id}/{product_id}', [AccountController::class, 'downloadDigitalProduct'])->name('download.digital.product');
        // Delivery Locations Routes
        Route::get('/locations/store', fn() => redirect()->route('account.show'));
        Route::post('/locations/store', [AccountController::class, 'storeLocation'])->name('locations.store');
        Route::delete('/locations/{id}', [AccountController::class, 'deleteLocation'])->name('locations.delete');
        Route::post('/locations/{id}/default', [AccountController::class, 'setDefaultLocation'])->name('locations.default');
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

Route::get("/admin", function () {
    return redirect()->route('admin.dashboard');
})->name('admin');

Route::post('/incomplete-order', [IncompleteOrderController::class, 'store'])->name('incomplete-order.store');

Route::prefix('admin')->middleware(['auth', 'license', 'authorize.by_route', 'TrackInstallation'])->name('admin.')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [AdminController::class, 'admin'])->name('dashboard');
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');

    // Users Management
    Route::get('/team-members', [AdminController::class, 'users'])->name('users');
    Route::get('/team-members/{user}', [AdminController::class, 'show'])->name('users.show');
    Route::get('/admin/team-members/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/team-members', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/team-members-edit', [AdminController::class, 'usersedit'])->name('users.edit');
    Route::put('/team-members-update/{id}', [AdminController::class, 'usersupdate'])->name('users.update');
    Route::delete('/team-members-destroy/{id}', [AdminController::class, 'usersdestroy'])->name('users.destroy');
    Route::delete('/team-members/bulk-delete', [AdminController::class, 'bulkDelete'])->name('users.bulk-delete');

    Route::post('/transactions/bulk-assign', [BackOrderController::class, 'bulkAssign'])->name('orders.bulk-assign');

    // Product Categories
    Route::prefix('catalog-groups')->name('product_categories.')->group(function () {
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
    Route::get('catalog-tiers', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::get('catalog-tiers/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('catalog-tiers', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('catalog-tiers/{sub_category}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('catalog-tiers/{sub_category}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('catalog-tiers/{sub_category}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');
    Route::match(['post', 'delete'], 'catalog-tiers/bulk-delete', [SubCategoryController::class, 'bulkDestroy'])->name('sub-categories.bulk-delete');
    Route::get('/check-subcategory-slug-availability', [SubCategoryController::class, 'checkSlugAvailability'])->name('check-subcategory-slug-availability');
    
    // Third Categories Routes
    Route::get('catalog-levels', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'index'])->name('third-categories.index');
    Route::get('catalog-levels/create', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'create'])->name('third-categories.create');
    Route::post('catalog-levels', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'store'])->name('third-categories.store');
    Route::get('catalog-levels/{thirdCategory}/edit', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'edit'])->name('third-categories.edit');
    Route::put('catalog-levels/{thirdCategory}', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'update'])->name('third-categories.update');
    Route::delete('catalog-levels/{thirdCategory}', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'destroy'])->name('third-categories.destroy');
    Route::match(['post', 'delete'], 'catalog-levels/bulk-delete', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'bulkDestroy'])->name('third-categories.bulk-delete');
    Route::post('catalog-levels/by-subcategories', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'getBySubCategory'])->name('third-categories.by-subcategories');
    Route::get('/check-thirdcategory-slug-availability', [\App\Http\Controllers\Admin\ThirdCategoryController::class, 'checkSlugAvailability'])->name('check-thirdcategory-slug-availability');

    // Products
    Route::get('/catalog', [AdminProductController::class, 'index'])->name('items.index');
    Route::get('/catalog/create', [AdminProductController::class, 'productCreate'])->name('items.create');
    Route::post('/catalog', [AdminProductController::class, 'store'])->name('items.store');
    Route::get('/catalog/{product}/edit', [AdminProductController::class, 'edit'])->name('items.edit');
    Route::put('/catalog/{product}', [AdminProductController::class, 'update'])->name('items.update');
    Route::delete('/catalog/{product}', [AdminProductController::class, 'destroy'])->name('items.destroy');
    Route::post('/catalog/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('items.bulk-delete');
    Route::post('/catalog/bulk-status-toggle', [AdminProductController::class, 'bulkStatusToggle'])->name('items.bulk-status-toggle');
    Route::post('/catalog/export-selected', [AdminProductController::class, 'exportSelected'])->name('items.export-selected');
    Route::get('/catalog/data', [AdminProductController::class, 'data'])->name('items.data');
    Route::get('/catalog/search', [AdminProductController::class, 'search'])->name('items.search');
    Route::get('/catalog/search-products', [AdminProductController::class, 'search'])->name('products.search');
    Route::get('/item-slug-availability', [AdminProductController::class, 'checkSlugAvailability'])->name('item-slug-availability');
    Route::post('/catalog/combination/{combination}/save-wholesale-tiers', [AdminProductController::class, 'saveCombinationWholesaleTiers'])->name('items.combination.save-wholesale-tiers');

    // Inventory Management
    Route::prefix('stock-control')->name('inventory.')->group(function () {
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
    Route::get('payment-options', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'index'])->name('payment-gateways.index');
    Route::get('payment-options/{gateway}/edit', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'edit'])->name('payment-gateways.edit');
    Route::put('payment-options/{gateway}', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'update'])->name('payment-gateways.update');
    Route::post('payment-options/{gateway}/toggle', [\App\Http\Controllers\Admin\PaymentGatewaySettingController::class, 'toggle'])->name('payment-gateways.toggle');

    // Orders Management
    Route::prefix('transactions')->name('orders.')->group(function () {
        Route::get('/', [BackOrderController::class, 'index'])->name('index');
        Route::get('/data', [BackOrderController::class, 'data'])->name('data');
        Route::get('/status-counts', [BackOrderController::class, 'statusCounts'])->name('status-counts');
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
        Route::post('/update-payment-status', [BackOrderController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::post('/bulk-update-status', [BackOrderController::class, 'bulkUpdateStatus'])->name('bulkUpdateStatus');
        Route::post('/bulk-refresh-courier-status', [BackOrderController::class, 'bulkRefreshCourierStatus'])->name('bulkRefreshCourierStatus');
        Route::post('/update-note', [BackOrderController::class, 'updateNote'])->name('updateNote');
        Route::post('/delete-multiple', [OrderController::class, 'deleteMultiple'])->name('deleteMultiple');
        Route::post('/export-selected', [OrderController::class, 'exportSelected'])->name('export-selected');
        Route::post('/fire-purchase-event', [BackOrderController::class, 'firePurchaseEvent'])->name('firePurchaseEvent');
        Route::post('/check-pending-purchase-event', [BackOrderController::class, 'checkPendingPurchaseEvent'])->name('checkPendingPurchaseEvent');
    });
    Route::get('/my-assignments', [OrderController::class, 'asignedorders'])->name('asigned.orders');
    Route::get('/vendor-orders', [AdminVendorOrderController::class, 'index'])->name('vendor-orders.index');
    Route::get('/vendor-orders/data', [AdminVendorOrderController::class, 'data'])->name('vendor-orders.data');

    // Reseller Orders
    Route::get('/reseller-orders', [ResellerOrderController::class, 'index'])->name('reseller-orders.index');
    Route::get('/reseller-orders/data', [ResellerOrderController::class, 'data'])->name('reseller-orders.data');
    Route::post('/reseller-orders/{id}/status', [ResellerOrderController::class, 'updateStatus'])->name('reseller-orders.update-status');

    // Sales Reports
    Route::get('analytics/revenue', [SalesReportController::class, 'index'])->name('orders.reports');
    Route::get('analytics/revenue/data', [SalesReportController::class, 'data'])->name('orders.reports.data');
    // Customer Reports
    Route::get('analytics/clients', [CustomerReportController::class, 'index'])->name('customers.reports');
    Route::get('analytics/clients/data', [CustomerReportController::class, 'data'])->name('customers.reports.data');

    Route::get('/pending-queue', [IncompleteOrderController::class, 'index'])->name('incomplete-orders.index');
    Route::get('/pending-queue/my-assigned', [IncompleteOrderController::class, 'myAssigned'])->name('incomplete-orders.my-assigned');
    Route::get('/pending-queue/data', [IncompleteOrderController::class, 'data'])->name('incomplete-orders.data');
    Route::get('/pending-queue/{id}', [IncompleteOrderController::class, 'show'])->name('incomplete-orders.show');
    Route::delete('pending-queue/{id}', [IncompleteOrderController::class, 'destroy'])->name('incomplete-orders.destroy');
    Route::post('pending-queue/bulk-delete', [IncompleteOrderController::class, 'bulkDelete'])->name('incomplete-orders.bulk-delete');
    Route::post('pending-queue/export-selected', [IncompleteOrderController::class, 'exportSelected'])->name('incomplete-orders.export-selected');
    Route::post('pending-queue/{id}/update-status', [IncompleteOrderController::class, 'updateStatus'])->name('incomplete-orders.update-status');
    Route::post('pending-queue/{id}/update-note', [IncompleteOrderController::class, 'updateNote'])->name('incomplete-orders.update-note');
    Route::post('pending-queue/convert', [IncompleteOrderController::class, 'convert'])->name('incomplete-orders.convert');
    Route::get('pending-queue/get-variation-combinations', [IncompleteOrderController::class, 'getVariationCombinations'])->name('incomplete-orders.get-variation-combinations');
    Route::get('pending-queue/{id}/convert', [IncompleteOrderController::class, 'showConvertPage'])->name('incomplete-orders.convert-page');

    // Pages
    Route::get('site-pages', [\App\Http\Controllers\PageController::class, 'index'])->name('pages.index');
    Route::get('site-pages/create', [\App\Http\Controllers\PageController::class, 'create'])->name('pages.create');
    Route::post('site-pages', [\App\Http\Controllers\PageController::class, 'store'])->name('pages.store');
    Route::get('site-pages/{page}/edit', [\App\Http\Controllers\PageController::class, 'edit'])->name('pages.edit');
    Route::put('site-pages/{page}', [\App\Http\Controllers\PageController::class, 'update'])->name('pages.update');
    Route::delete('site-pages/{page}', [\App\Http\Controllers\PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('site-pages/check-slug', [\App\Http\Controllers\PageController::class, 'checkSlugAvailability'])->name('pages.check-slug');
    
    // Landing Pages Management
    Route::middleware(['license:landing_page'])->group(function () {
        Route::get('promo-pages', [\App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('landing-pages.index');
        Route::get('promo-pages/create', [\App\Http\Controllers\Admin\LandingPageController::class, 'create'])->name('landing-pages.create');
        Route::get('promo-pages/{landingPage}', [\App\Http\Controllers\Admin\LandingPageController::class, 'show'])->name('landing-pages.show');
        Route::get('promo-pages/{landingPage}/edit', [\App\Http\Controllers\Admin\LandingPageController::class, 'edit'])->name('landing-pages.edit');
        Route::put('promo-pages/{landingPage}', [\App\Http\Controllers\Admin\LandingPageController::class, 'update'])->name('landing-pages.update');
        Route::delete('promo-pages/{landingPage}', [\App\Http\Controllers\Admin\LandingPageController::class, 'destroy'])->name('landing-pages.destroy');
        Route::post('promo-pages/{landingPage}/toggle-status', [\App\Http\Controllers\Admin\LandingPageController::class, 'toggleStatus'])->name('landing-pages.toggle-status');
        Route::post('promo-pages/update-positions', [\App\Http\Controllers\Admin\LandingPageController::class, 'updatePositions'])->name('landing-pages.update-positions');
        Route::post('promo-pages/{landingPage}/copy', [\App\Http\Controllers\Admin\LandingPageController::class, 'copy'])->name('landing-pages.copy');
    });
    
    // Landing Page creation with quota check
    Route::post('promo-pages', [\App\Http\Controllers\Admin\LandingPageController::class, 'store'])
        ->middleware(['license:landing_page', 'license.landing_page'])
        ->name('landing-pages.store');

    // Site Settings
    Route::get('/config', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/config/payment-gateway', [SettingController::class, 'paymentGateway'])->name('settings.payment-gateway');
    Route::post('/config/update', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/config/sitemap/generate', [SettingController::class, 'generateSitemap'])->name('sitemap.generate');

    // Admin Subscription Payments (dedicated table)
    Route::get('/subscription-payments', [\App\Http\Controllers\Admin\AdminSubscriptionPaymentController::class, 'index'])->name('subscription-payments.index');
    Route::post('/subscription-payments', [\App\Http\Controllers\Admin\AdminSubscriptionPaymentController::class, 'store'])->name('subscription-payments.store');
    Route::patch('/subscription-payments/{subId}', [\App\Http\Controllers\Admin\AdminSubscriptionPaymentController::class, 'update'])->name('subscription-payments.update');
    Route::get('/subscription-payments/{subId}/print-invoice', [\App\Http\Controllers\Admin\AdminSubscriptionPaymentController::class, 'printInvoice'])->name('subscription-payments.print-invoice');
    Route::get('/subscription-payments/{subId}/download-invoice', [\App\Http\Controllers\Admin\AdminSubscriptionPaymentController::class, 'downloadInvoice'])->name('subscription-payments.download-invoice');

    // Modules & Tools Dashboard (with System Modules)
    Route::prefix('extensions')->name('modules.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('index');
        Route::post('/upload', [App\Http\Controllers\Admin\ModuleController::class, 'upload'])->name('upload');
        Route::post('/sync', [App\Http\Controllers\Admin\ModuleController::class, 'sync'])->name('sync');
        Route::get('/{name}', [App\Http\Controllers\Admin\ModuleController::class, 'show'])->name('show');
        Route::post('/{name}/enable', [App\Http\Controllers\Admin\ModuleController::class, 'enable'])->name('enable');
        Route::post('/{name}/disable', [App\Http\Controllers\Admin\ModuleController::class, 'disable'])->name('disable');
        Route::delete('/{name}', [App\Http\Controllers\Admin\ModuleController::class, 'delete'])->name('delete');
    });

    // WooCommerce Migration Routes
    Route::prefix('import-woo')->name('woocommerce-migration.')->group(function () {
        Route::post('/settings', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'saveSettings'])->name('settings.save');
        Route::get('/', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'index'])->name('index');
        Route::post('/test-connection', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'testConnection'])->name('test-connection');
        Route::post('/migrate/{entity}', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'migrateEntity'])->name('migrate.entity');
        Route::post('/migrate-all', [App\Http\Controllers\Admin\WooCommerceMigrationController::class, 'migrateAll'])->name('migrate.all');
    });

    // Combo Offers
    Route::resource('bundle-deals', App\Http\Controllers\Admin\ComboOfferController::class)->parameters(['bundle-deals' => 'combo_offer'])->names(['index' => 'combo_offers.index', 'create' => 'combo_offers.create', 'store' => 'combo_offers.store', 'show' => 'combo_offers.show', 'edit' => 'combo_offers.edit', 'update' => 'combo_offers.update', 'destroy' => 'combo_offers.destroy']);
    Route::get('bundle-deals/{comboOffer}/toggle-status', [App\Http\Controllers\Admin\ComboOfferController::class, 'toggleStatus'])->name('combo_offers.toggle_status');
    Route::post('bundle-deals/get-variations', [App\Http\Controllers\Admin\ComboOfferController::class, 'getVariationCombinations'])->name('combo_offers.get_variations');
    
    // Frontend combo offers
    Route::get('combo/offers/{product}', [App\Http\Controllers\ComboOfferController::class, 'getProductComboOffers'])->name('combo.offers.product');

    // Socials
    Route::get('social-links', [\App\Http\Controllers\SocialController::class, 'index'])->name('socials.index');
    Route::get('social-links/create', [\App\Http\Controllers\SocialController::class, 'create'])->name('socials.create');
    Route::post('social-links', [\App\Http\Controllers\SocialController::class, 'store'])->name('socials.store');
    Route::get('social-links/{social}/edit', [\App\Http\Controllers\SocialController::class, 'edit'])->name('socials.edit');
    Route::put('social-links/{social}', [\App\Http\Controllers\SocialController::class, 'update'])->name('socials.update');
    Route::delete('social-links/{social}', [\App\Http\Controllers\SocialController::class, 'destroy'])->name('socials.destroy');

    Route::get('/shipping-basics', [BasicShippingSettingController::class, 'edit'])->name('basic.shipping.settings.edit');
    Route::post('/shipping-basics', [BasicShippingSettingController::class, 'update'])->name('basic.shipping.settings.update');

    // Shipping Management
    Route::prefix('delivery-zones')->name('shipping.')->group(function () {
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
    Route::get('regions', [CityController::class, 'index'])->name('cities.index');
    Route::get('regions/create', [CityController::class, 'create'])->name('cities.create');
    Route::post('regions', [CityController::class, 'store'])->name('cities.store');
    Route::get('regions/{city}', [CityController::class, 'show'])->name('cities.show');
    Route::get('regions/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
    Route::put('regions/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::delete('regions/{city}', [CityController::class, 'destroy'])->name('cities.destroy');

    // Menu Management
    Route::get('nav-builder', [MenuController::class, 'index'])->name('menus.index');
    Route::get('nav-builder/create', [MenuController::class, 'create'])->name('menus.create');
    Route::get('nav-builder/available', [MenuController::class, 'getAvailableMenus'])->name('menus.available');
    Route::post('nav-builder', [MenuController::class, 'store'])->name('menus.store');
    Route::get('nav-builder/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('nav-builder/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('nav-builder/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('nav-builder/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Menu Items
    Route::get('nav-builder/{menu}/items/create', [MenuItemController::class, 'create'])->name('menus.items.create');
    Route::post('nav-builder/{menu}/items', [MenuItemController::class, 'store'])->name('menus.items.store');
    Route::get('nav-builder/{menu}/items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menus.items.edit');
    Route::put('nav-builder/{menu}/items/{menuItem}', [MenuItemController::class, 'update'])->name('menus.items.update');
    Route::delete('nav-builder/{menu}/items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menus.items.destroy');
    Route::post('nav-items/update-order', [MenuItemController::class, 'updateOrder'])->name('menu-items.update-order');
    Route::post('admin/nav-items/update-order', [MenuItemController::class, 'updateOrder'])->name('admin.menu-items.update-order');

    // Subscriptions
    Route::get('/newsletter-list', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/newsletter-list/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::delete('/newsletter-list/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::patch('/newsletter-list/{subscription}/toggle-status', [SubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggle-status');
    Route::delete('/newsletter-list/bulk-delete', [SubscriptionController::class, 'bulkDelete'])->name('subscriptions.bulk-delete');

    // Contacts
    Route::get('/inquiries', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/inquiries/unread', [ContactController::class, 'unread'])->name('contacts.unread');
    Route::get('/inquiries/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('/inquiries/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // Sliders Management
    Route::resource('hero-banners', \App\Http\Controllers\SliderController::class)->parameters(['hero-banners' => 'slider'])->names(['index' => 'sliders.index', 'create' => 'sliders.create', 'store' => 'sliders.store', 'show' => 'sliders.show', 'edit' => 'sliders.edit', 'update' => 'sliders.update', 'destroy' => 'sliders.destroy']);
    Route::post('hero-banners/update-positions', [\App\Http\Controllers\SliderController::class, 'updatePositions'])->name('sliders.update-positions');

    // Post Routes - restrict to users with manage posts permission
    Route::get('/articles/add', [PostController::class, 'Add'])->name('post.add');
    Route::get('/articles/validate-slug', [PostController::class, 'ValidatePostSlug'])->name('validate.post.slug');
    Route::post('/articles/store', [PostController::class, 'Store'])->name('post.store');
    Route::get('/articles/index', [PostController::class, 'Index'])->name('post.index');
    Route::get('/articles/{id}/view', [PostController::class, 'View'])->name('post.view');
    Route::get('/articles/{id}/edit', [PostController::class, 'Edit'])->name('post.edit');
    Route::put('/articles/{id}', [PostController::class, 'Update'])->name('post.update');
    Route::delete('/articles/{id}', [PostController::class, 'Destroy'])->name('post.destroy');

    // Category Routes
    Route::get('/article-topics/add', [PostCategoryController::class, 'Add'])->name('category.add');
    Route::get('/article-topics/validate-slug', [PostController::class, 'PostCategorySlug'])->name('validate.category.slug');
    Route::post('/article-topics/store', [PostCategoryController::class, 'Store'])->name('category.store');
    Route::get('/article-topics/index', [PostCategoryController::class, 'Index'])->name('category.index');
    Route::get('/article-topics/{id}/view', [PostCategoryController::class, 'View'])->name('category.view');
    Route::get('/article-topics/{id}/edit', [PostCategoryController::class, 'Edit'])->name('category.edit');
    Route::put('/article-topics/{id}', [PostCategoryController::class, 'Update'])->name('category.update');
    Route::delete('/article-topics/{id}', [PostCategoryController::class, 'Destroy'])->name('category.destroy');

    // Sub Category Routes
    Route::get('/article-subtopics/add', [PostSubCategoryController::class, 'Add'])->name('postsubcategory.add');
    Route::get('/article-subtopics/validate-slug', [PostController::class, 'PostSubCategorySlug'])->name('validate.postsubcategory.slug');
    Route::post('/article-subtopics/store', [PostSubCategoryController::class, 'Store'])->name('postsubcategory.store');
    Route::get('/article-subtopics/index', [PostSubCategoryController::class, 'Index'])->name('postsubcategory.index');
    Route::get('/article-subtopics/{id}/view', [PostSubCategoryController::class, 'View'])->name('postsubcategory.view');
    Route::get('/article-subtopics/{id}/edit', [PostSubCategoryController::class, 'Edit'])->name('postsubcategory.edit');
    Route::put('/article-subtopics/{id}', [PostSubCategoryController::class, 'Update'])->name('postsubcategory.update');
    Route::delete('/article-subtopics/{id}', [PostSubCategoryController::class, 'Destroy'])->name('postsubcategory.destroy');

    Route::resource('content-authors', AdminWriterController::class)->parameters(['content-authors' => 'writer'])->names(['index' => 'writers.index', 'create' => 'writers.create', 'store' => 'writers.store', 'show' => 'writers.show', 'edit' => 'writers.edit', 'update' => 'writers.update', 'destroy' => 'writers.destroy']);
    Route::resource('content-publishers', PublisherController::class)->parameters(['content-publishers' => 'publisher'])->names(['index' => 'publishers.index', 'create' => 'publishers.create', 'store' => 'publishers.store', 'show' => 'publishers.show', 'edit' => 'publishers.edit', 'update' => 'publishers.update', 'destroy' => 'publishers.destroy']);
    
    // Brands Management
    Route::match(['post', 'delete'], 'publishers-mark/bulk-delete', [\App\Http\Controllers\Admin\BrandController::class, 'bulkDestroy'])->name('brands.bulk-delete');
    Route::resource('publishers-mark', \App\Http\Controllers\Admin\BrandController::class)->parameters(['publishers-mark' => 'brand'])->names(['index' => 'brands.index', 'create' => 'brands.create', 'store' => 'brands.store', 'show' => 'brands.show', 'edit' => 'brands.edit', 'update' => 'brands.update', 'destroy' => 'brands.destroy']);
    Route::get('/check-brand-slug-availability', [\App\Http\Controllers\Admin\BrandController::class, 'checkSlugAvailability'])->name('check-brand-slug-availability');

    // Manual Customers Reviews

    Route::resource('feedback', \App\Http\Controllers\Admin\CustomerReviewController::class)->parameters(['feedback' => 'review'])->names(['index' => 'reviews.index', 'create' => 'reviews.create', 'store' => 'reviews.store', 'show' => 'reviews.show', 'edit' => 'reviews.edit', 'update' => 'reviews.update', 'destroy' => 'reviews.destroy']);
    Route::post('feedback/{review}/toggle-status', [\App\Http\Controllers\Admin\CustomerReviewController::class, 'toggleStatus'])->name('reviews.toggle-status');

    // Comment management routes
    Route::resource('comments', \App\Http\Controllers\Admin\CommentController::class)->except(['create', 'store']);
    Route::post('comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
    Route::post('comments/{comment}/spam', [\App\Http\Controllers\Admin\CommentController::class, 'markAsSpam'])->name('comments.spam');
    Route::post('comments/bulk-action', [\App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comments.bulk-action');

    Route::prefix('courier-connect')->group(function () {
        Route::get('/', [DeliveryIntegrationController::class, 'index'])->name('delivery.index');
        Route::match(['get', 'post'], '/integration/{id?}', [DeliveryIntegrationController::class, 'integrationForm'])->name('delivery.integration');
        Route::delete('/{id}', [DeliveryIntegrationController::class, 'destroy'])->name('delivery.destroy');
    });

    // Fraud Checker Routes
    Route::prefix('trust-scanner')->name('fraud-checker.')->group(function () {
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
    Route::prefix('trust-shield')->name('fraud-protection.')->group(function () {
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
    Route::prefix('notify-settings')->name('telegram-settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'update'])->name('update');
        Route::post('/test', [\App\Http\Controllers\Admin\TelegramSettingController::class, 'test'])->name('test');
    });

    // Delayed Purchase Events Settings
    Route::prefix('event-queue')->name('delayed-events.')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\DelayedEventSettingController::class, 'index'])->name('settings');
        Route::put('/settings', [\App\Http\Controllers\DelayedEventSettingController::class, 'update'])->name('settings.update');
        Route::post('/test-connection', [\App\Http\Controllers\DelayedEventSettingController::class, 'testConnection'])->name('test-connection');
        Route::get('/pending', [\App\Http\Controllers\DelayedEventSettingController::class, 'pendingEvents'])->name('pending');
        Route::get('/history', [\App\Http\Controllers\DelayedEventSettingController::class, 'eventHistory'])->name('history');
    });
    Route::post('transactions/send-to-courier', [PathaoController::class, 'sendToCourier'])->name('orders.sendToCourier');
    Route::post('transactions/send-bulk-to-courier', [PathaoController::class, 'sendBulkToCourier'])->name('orders.sendBulkToCourier');
    Route::post('transactions/{order}/save-send-courier', [PathaoController::class, 'saveSendCourier'])->name('orders.saveSendCourier');
    Route::get('courier-cities', [PathaoController::class, 'getCourierCities'])->name('courier-cities');
    Route::get('courier-zones', [PathaoController::class, 'getCourierZones'])->name('courier-zones');
    Route::get('courier-areas', [PathaoController::class, 'getCourierAreas'])->name('courier-areas');
    Route::get('transactions/{order}/courier-status', [PathaoController::class, 'getCourierOrderStatus'])->name('orders.courierStatus');
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
    Route::get('/access-control', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'index'])->name('roles_permissions.index');
    Route::post('/access-control/permission', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'storePermission'])->name('roles_permissions.permission.store');
    Route::post('/access-control/permission/{permission}/update', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updatePermission'])->name('roles_permissions.permission.update');
    Route::post('/access-control/permission/{permission}/delete', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'deletePermission'])->name('roles_permissions.permission.delete');
    Route::post('/access-control/role', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'storeRole'])->name('roles_permissions.role.store');
    Route::post('/access-control/role/{role}/update', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updateRole'])->name('roles_permissions.role.update');
    Route::post('/access-control/role/{role}/delete', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'deleteRole'])->name('roles_permissions.role.delete');
    Route::post('/access-control/user/{user}/roles', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updateUserRoles'])->name('roles_permissions.user_roles.update');
    Route::post('/access-control/user/{user}/permissions', [\App\Http\Controllers\Admin\RolesPermissionsController::class, 'updateUserPermissions'])->name('roles_permissions.user_permissions.update');
    
    // POS (Point of Sale) Routes
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\POSController::class, 'index'])->name('index');
        Route::get('/search-products', [\App\Http\Controllers\Admin\POSController::class, 'searchProducts'])->name('search-products');
        Route::get('/search-customers', [\App\Http\Controllers\Admin\POSController::class, 'searchCustomers'])->name('search-customers');
        Route::post('/create-customer', [\App\Http\Controllers\Admin\POSController::class, 'createCustomer'])->name('create-customer');
        Route::post('/create-order', [\App\Http\Controllers\Admin\POSController::class, 'createOrder'])->name('create-order');
        Route::get('/print-receipt/{order}', [\App\Http\Controllers\Admin\POSController::class, 'printReceipt'])->name('print-receipt');
        Route::get('/print-invoice/{order}', [\App\Http\Controllers\Admin\POSController::class, 'printInvoice'])->name('print-invoice');
        Route::get('/download-receipt/{order}', [\App\Http\Controllers\Admin\POSController::class, 'downloadReceipt'])->name('download-receipt');
        Route::get('/download-invoice/{order}', [\App\Http\Controllers\Admin\POSController::class, 'downloadInvoice'])->name('download-invoice');
        Route::get('/stats', [\App\Http\Controllers\Admin\POSController::class, 'stats'])->name('stats');
    });
    
    // License Management Routes
    Route::prefix('licensing')->name('verification.')->group(function () {
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
        Route::get('/system-updates', [AdminUpdateController::class, 'index'])->name('updates.index');
        Route::post('/system-updates/apply', [AdminUpdateController::class, 'apply'])->name('updates.apply');
    });
});


// Chat/Messaging System Routes
Route::middleware('auth')->group(function () {
    Route::get('/chats', [App\Http\Controllers\Client\ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/start/{seller}', [App\Http\Controllers\Client\ChatController::class, 'startChat'])->name('chats.start');
    Route::get('/chats/{chatRoom}/messages', [App\Http\Controllers\Client\ChatController::class, 'getMessages'])->name('chats.messages');
    Route::post('/chats/{chatRoom}/send', [App\Http\Controllers\Client\ChatController::class, 'sendMessage'])->name('chats.send');
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
// VENDOR PANEL ROUTES
// ==========================================
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'vendor'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [VendorDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [VendorDashboardController::class, 'updateProfile'])->name('profile.update');

    // POS Routes (Reseller portal)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Vendor\VendorPOSController::class, 'index'])->name('index');
        Route::get('/search-products', [\App\Http\Controllers\Vendor\VendorPOSController::class, 'searchProducts'])->name('search-products');
        Route::post('/create-order', [\App\Http\Controllers\Vendor\VendorPOSController::class, 'createOrder'])->name('create-order');
    });

    // Products
    Route::get('/products/subcategories/{categoryId}', [VendorProductController::class, 'getSubcategories'])->name('products.subcategories');
    Route::get('/products/thirdcategories/{subCategoryId}', [VendorProductController::class, 'getThirdcategories'])->name('products.thirdcategories');
    Route::post('/products/bulk-copy', [VendorProductController::class, 'bulkCopy'])->name('products.bulk-copy');
    Route::post('/products/{product}/copy', [VendorProductController::class, 'copy'])->name('products.copy');
    Route::post('/products/{product}/return-allocation', [VendorProductController::class, 'returnAllocation'])->name('products.return-allocation');
    Route::resource('products', VendorProductController::class);

    // Orders (view only)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [VendorOrderController::class, 'index'])->name('index');
        Route::get('/my-pos-orders', [VendorOrderController::class, 'resellerOrders'])->name('reseller');
        Route::get('/my-pos-orders/{id}/edit', [VendorOrderController::class, 'editResellerOrder'])->name('reseller.edit');
        Route::post('/my-pos-orders/{id}/update', [VendorOrderController::class, 'updateResellerOrder'])->name('reseller.update');
        Route::delete('/my-pos-orders/{id}/delete', [VendorOrderController::class, 'deleteResellerOrder'])->name('reseller.delete');
        Route::get('/{order}', [VendorOrderController::class, 'show'])->name('show');
        Route::post('/{order}/toggle-payment-status', [VendorOrderController::class, 'togglePaymentStatus'])->name('toggle-payment-status');
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

    // Wallet & Payments
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Vendor\VendorWalletController::class, 'index'])->name('index');
        Route::post('/recharge', [\App\Http\Controllers\Vendor\VendorWalletController::class, 'recharge'])->name('recharge');
        Route::post('/transfer', [\App\Http\Controllers\Vendor\VendorWalletController::class, 'transfer'])->name('transfer');
    });
});

// ==========================================
// ADMIN VENDOR MANAGEMENT ROUTES
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'license', 'role:admin|super_admin|super admin'])->group(function () {

    // Vendor Payments & Wallet Management
    Route::prefix('vendor-payments')->name('vendor-payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'reject'])->name('reject');
        Route::post('/grant-fund', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'grantFund'])->name('grant-fund');
        Route::post('/bulk-delete', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export-csv', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'exportCsv'])->name('export-csv');
        Route::get('/{id}/pdf', [\App\Http\Controllers\Admin\AdminVendorPaymentController::class, 'downloadPdf'])->name('download-pdf');
    });

    // Vendor Management
    Route::resource('partners', AdminVendorController::class)->parameters(['partners' => 'vendor'])->names(['index' => 'vendors.index', 'create' => 'vendors.create', 'store' => 'vendors.store', 'show' => 'vendors.show', 'edit' => 'vendors.edit', 'update' => 'vendors.update', 'destroy' => 'vendors.destroy']);
    Route::post('/partners/{vendor}/verify', [AdminVendorController::class, 'verify'])->name('vendors.verify');
    Route::post('/partners/{vendor}/toggle-status', [AdminVendorController::class, 'toggleStatus'])->name('vendors.toggle-status');

    // Vendor Product Approval
    Route::prefix('partner-items')->name('vendor-products.')->group(function () {
        Route::get('/', [AdminVendorProductController::class, 'index'])->name('index');
        Route::get('/{product}', [AdminVendorProductController::class, 'show'])->name('show');
        Route::post('/{product}/approve', [AdminVendorProductController::class, 'approve'])->name('approve');
        Route::post('/{product}/reject', [AdminVendorProductController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [AdminVendorProductController::class, 'bulkApprove'])->name('bulk-approve');
        Route::match(['get', 'post', 'put'], '/{product}/commission', [AdminVendorProductController::class, 'updateCommission'])->name('update-commission');
    });

    // Vendor Withdrawal Management
    Route::prefix('partner-payouts')->name('vendor-withdrawals.')->group(function () {
        Route::get('/', [AdminVendorWithdrawalController::class, 'index'])->name('index');
        Route::get('/history', [AdminVendorWithdrawalController::class, 'payoutHistory'])->name('history');
        Route::get('/{withdrawal}', [AdminVendorWithdrawalController::class, 'show'])->name('show');
        Route::post('/{withdrawal}/approve', [AdminVendorWithdrawalController::class, 'approve'])->name('approve');
        Route::post('/{withdrawal}/reject', [AdminVendorWithdrawalController::class, 'reject'])->name('reject');
        Route::post('/{withdrawal}/complete', [AdminVendorWithdrawalController::class, 'complete'])->name('complete');
        Route::post('/bulk-approve', [AdminVendorWithdrawalController::class, 'bulkApprove'])->name('bulk-approve');
    });

    // Vendor Global Settings
    Route::prefix('partner-config')->name('vendor-settings.')->group(function () {
        Route::get('/global', [VendorGlobalSettingsController::class, 'index'])->name('global');
        Route::post('/global', [VendorGlobalSettingsController::class, 'update'])->name('global.update');
        Route::post('/global/toggle-system', [VendorGlobalSettingsController::class, 'toggleSystem'])->name('toggle-system');
        Route::put('/global/{key}', [VendorGlobalSettingsController::class, 'updateSingle'])->name('global.update-single');
        Route::post('/global/reset', [VendorGlobalSettingsController::class, 'reset'])->name('global.reset');
        Route::get('/global/export', [VendorGlobalSettingsController::class, 'export'])->name('global.export');
    });
});

// ==========================================
// ADMIN ROUTES (Non-Vendor)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'license', 'role:admin|super_admin|super admin'])->group(function () {
    // Backup System
    Route::prefix('snapshots')->name('backup.')->group(function () {
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


