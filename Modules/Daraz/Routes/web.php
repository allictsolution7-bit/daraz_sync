<?php

use Illuminate\Support\Facades\Route;
use Modules\Daraz\Http\Controllers\DarazStoreController;
use Modules\Daraz\Http\Controllers\DarazProductMappingController;
use Modules\Daraz\Http\Controllers\DarazSyncController;

/*
|--------------------------------------------------------------------------
| Daraz Module Web Routes
|--------------------------------------------------------------------------
|
| All routes are prefixed with /admin/daraz and use admin.daraz. name prefix.
| NO license middleware - this is a FREE module.
|
*/

// Dashboard
Route::get('/', [DarazSyncController::class, 'index'])->name('index');

// Store Management
Route::prefix('stores')->name('stores.')->group(function () {
    Route::get('/', [DarazStoreController::class, 'index'])->name('index');
    Route::get('/create', [DarazStoreController::class, 'create'])->name('create');
    Route::post('/', [DarazStoreController::class, 'store'])->name('store');
    Route::get('/{store}/edit', [DarazStoreController::class, 'edit'])->name('edit');
    Route::put('/{store}', [DarazStoreController::class, 'update'])->name('update');
    Route::delete('/{store}', [DarazStoreController::class, 'destroy'])->name('destroy');

    // OAuth Authorization
    Route::get('/{store}/authorize', [DarazStoreController::class, 'startAuthorization'])->name('authorize');
    Route::get('/callback', [DarazStoreController::class, 'callback'])->name('callback');

    // Connection Testing
    Route::post('/{store}/test', [DarazStoreController::class, 'testConnection'])->name('test');
    Route::post('/{store}/refresh-token', [DarazStoreController::class, 'refreshToken'])->name('refresh-token');

    // Toggle Status
    Route::post('/{store}/toggle', [DarazStoreController::class, 'toggle'])->name('toggle');
});

// Product Mappings
Route::prefix('mappings')->name('mappings.')->group(function () {
    Route::get('/', [DarazProductMappingController::class, 'index'])->name('index');
    Route::get('/create', [DarazProductMappingController::class, 'create'])->name('create');
    Route::post('/', [DarazProductMappingController::class, 'store'])->name('store');
    Route::get('/{mapping}/edit', [DarazProductMappingController::class, 'edit'])->name('edit');
    Route::put('/{mapping}', [DarazProductMappingController::class, 'update'])->name('update');
    Route::delete('/{mapping}', [DarazProductMappingController::class, 'destroy'])->name('destroy');

    // Bulk Operations
    Route::post('/auto-map', [DarazProductMappingController::class, 'autoMap'])->name('auto-map');
    Route::post('/bulk-toggle', [DarazProductMappingController::class, 'bulkToggle'])->name('bulk-toggle');
    Route::delete('/bulk-delete', [DarazProductMappingController::class, 'bulkDelete'])->name('bulk-delete');

    // Single Operations / AJAX
    Route::post('/{mapping}/toggle-sync', [DarazProductMappingController::class, 'toggleSync'])->name('toggle-sync');
    Route::get('/fetch-daraz-products', [DarazProductMappingController::class, 'fetchDarazProducts'])->name('fetch-daraz-products');
    Route::get('/search-products', [DarazProductMappingController::class, 'searchProducts'])->name('search-products');
});

// Sync Operations
Route::prefix('sync')->name('sync.')->group(function () {
    Route::get('/', [DarazSyncController::class, 'index'])->name('index');

    // Single mapping sync
    Route::post('/single/{mapping}', [DarazSyncController::class, 'syncSingle'])->name('single');

    // Store sync
    Route::post('/store/{store}', [DarazSyncController::class, 'syncStore'])->name('store');

    // Sync all stores
    Route::post('/all', [DarazSyncController::class, 'syncAll'])->name('all');
    Route::post('/pull-all', [DarazSyncController::class, 'pullAll'])->name('pull-all');

    // Store status (AJAX)
    Route::get('/store-status/{store}', [DarazSyncController::class, 'storeStatus'])->name('store-status');

    // Logs
    Route::get('/logs', [DarazSyncController::class, 'logs'])->name('logs');
    Route::delete('/logs/clear', [DarazSyncController::class, 'clearLogs'])->name('logs.clear');
});

// Orders Management
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [\Modules\Daraz\Http\Controllers\DarazOrderController::class, 'index'])->name('index');
    Route::get('/fetch', [\Modules\Daraz\Http\Controllers\DarazOrderController::class, 'fetchOrders'])->name('fetch');
    Route::get('/{orderId}/items', [\Modules\Daraz\Http\Controllers\DarazOrderController::class, 'fetchOrderItems'])->name('items');
    Route::get('/{orderId}/logistic', [\Modules\Daraz\Http\Controllers\DarazOrderController::class, 'fetchOrderLogistic'])->name('logistic');
    Route::get('/document', [\Modules\Daraz\Http\Controllers\DarazOrderController::class, 'downloadDocument'])->name('document');
});

