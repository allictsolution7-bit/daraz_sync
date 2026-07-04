<?php

namespace Modules\Daraz\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Models\DarazSyncLog;
use Modules\Daraz\Services\DarazApiService;
use Modules\Daraz\Services\DarazAuthService;

class DarazStoreController extends Controller
{
    protected DarazAuthService $authService;
    protected DarazApiService $apiService;

    public function __construct(DarazAuthService $authService, DarazApiService $apiService)
    {
        $this->authService = $authService;
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of stores.
     */
    public function index()
    {
        $stores = DarazStore::withCount('productMappings')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('daraz::stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        $countries = DarazStore::getCountries();
        return view('daraz::stores.create', compact('countries'));
    }

    /**
     * Store a newly created store.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|in:BD,PK,LK,NP,MM',
            'app_key' => 'required|string|max:255',
            'app_secret' => 'required|string|max:500',
            'auto_sync' => 'boolean',
            'sync_interval' => 'integer|min:5|max:1440',
        ]);

        $store = DarazStore::create([
            'name' => $validated['name'],
            'country_code' => $validated['country_code'],
            'app_key' => $validated['app_key'],
            'app_secret' => $validated['app_secret'],
            'auto_sync' => $validated['auto_sync'] ?? true,
            'sync_interval' => $validated['sync_interval'] ?? 30,
            'is_active' => true,
        ]);

        flash()->success('Store created successfully. Please authorize to connect.');

        return redirect()->route('admin.daraz.stores.authorize', $store);
    }

    /**
     * Show the form for editing a store.
     */
    public function edit(DarazStore $store)
    {
        $countries = DarazStore::getCountries();
        $recentLogs = $store->syncLogs()->latest('created_at')->limit(10)->get();

        return view('daraz::stores.edit', compact('store', 'countries', 'recentLogs'));
    }

    /**
     * Update the specified store.
     */
    public function update(Request $request, DarazStore $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|in:BD,PK,LK,NP,MM',
            'app_key' => 'required|string|max:255',
            'app_secret' => 'nullable|string|max:500',
            'auto_sync' => 'boolean',
            'sync_interval' => 'integer|min:5|max:1440',
            'is_active' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'country_code' => $validated['country_code'],
            'app_key' => $validated['app_key'],
            'auto_sync' => $validated['auto_sync'] ?? false,
            'sync_interval' => $validated['sync_interval'] ?? 30,
            'is_active' => $validated['is_active'] ?? true,
        ];

        // Only update app_secret if provided
        if (!empty($validated['app_secret'])) {
            $updateData['app_secret'] = $validated['app_secret'];
            // Clear tokens if credentials changed
            $updateData['access_token'] = null;
            $updateData['refresh_token'] = null;
            $updateData['token_expires_at'] = null;
        }

        $store->update($updateData);

        flash()->success('Store updated successfully.');

        return redirect()->route('admin.daraz.stores.edit', $store);
    }

    /**
     * Remove the specified store.
     */
    public function destroy(DarazStore $store)
    {
        $store->delete();

        flash()->success('Store deleted successfully.');

        return redirect()->route('admin.daraz.stores.index');
    }

    /**
     * Start OAuth authorization flow.
     */
    public function startAuthorization(DarazStore $store)
    {
        if (!$store->hasValidCredentials()) {
            flash()->error('Please configure app key and secret first.');
            return redirect()->route('admin.daraz.stores.edit', $store);
        }

        $redirectUri = route('admin.daraz.stores.callback');
        $authUrl = $this->authService->getAuthorizationUrl($store, $redirectUri);

        // Store the store ID in session for callback
        session(['daraz_auth_store_id' => $store->id]);

        return redirect()->away($authUrl);
    }

    /**
     * Handle OAuth callback.
     */
    public function callback(Request $request)
    {
        $storeId = session('daraz_auth_store_id');
        session()->forget('daraz_auth_store_id');

        if (!$storeId) {
            flash()->error('Authorization session expired. Please try again.');
            return redirect()->route('admin.daraz.stores.index');
        }

        $store = DarazStore::find($storeId);
        if (!$store) {
            flash()->error('Store not found.');
            return redirect()->route('admin.daraz.stores.index');
        }

        // Check for errors
        if ($request->has('error')) {
            flash()->error('Authorization failed: ' . $request->get('error_description', 'Unknown error'));
            return redirect()->route('admin.daraz.stores.edit', $store);
        }

        $code = $request->get('code');
        if (!$code) {
            flash()->error('No authorization code received.');
            return redirect()->route('admin.daraz.stores.edit', $store);
        }

        // Exchange code for token
        $result = $this->authService->exchangeCodeForToken($store, $code);

        if ($result['success']) {
            flash()->success('Store connected successfully!');
        } else {
            flash()->error('Failed to connect: ' . ($result['error'] ?? 'Unknown error'));
        }

        return redirect()->route('admin.daraz.stores.edit', $store);
    }

    /**
     * Refresh store token.
     */
    public function refreshToken(DarazStore $store)
    {
        if (!$store->refresh_token) {
            return response()->json([
                'success' => false,
                'message' => 'No refresh token available. Please re-authorize.',
            ]);
        }

        $result = $this->authService->refreshToken($store);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully.',
                'expires_at' => $store->fresh()->token_expires_at->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Failed to refresh token.',
        ]);
    }

    /**
     * Test store connection.
     */
    public function testConnection(DarazStore $store)
    {
        if (!$store->access_token) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected. Please authorize first.',
            ]);
        }

        // Ensure valid token
        if (!$this->authService->ensureValidToken($store)) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired and refresh failed. Please re-authorize.',
            ]);
        }

        $result = $this->apiService->testConnection($store);

        DarazSyncLog::logConnectionTest(
            $store,
            $result['success'] ? 'success' : 'failed',
            $result['data'] ?? null,
            $result['error'] ?? null
        );

        if ($result['success']) {
            $sellerData = $result['data'] ?? [];
            $sellerName = $sellerData['name'] ?? $sellerData['short_name'] ?? $sellerData['Name'] ?? null;

            return response()->json([
                'success' => true,
                'message' => 'Connection successful!',
                'seller_name' => $sellerName,
                'data' => $sellerData,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Connection test failed.',
        ]);
    }

    /**
     * Disconnect store (clear tokens).
     */
    public function disconnect(DarazStore $store)
    {
        $this->authService->disconnect($store);

        flash()->success('Store disconnected successfully.');

        return redirect()->route('admin.daraz.stores.edit', $store);
    }
}
