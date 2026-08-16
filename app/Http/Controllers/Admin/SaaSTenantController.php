<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaaSTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaaSTenantController extends Controller
{
    /**
     * Display a listing of tenants.
     */
    public function index()
    {
        $tenants = SaaSTenant::orderBy('id', 'desc')->paginate(15);

        foreach ($tenants as $tenant) {
            $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
            try {
                $this->connectToTenantDatabase($dbName);
                $productCount = DB::connection('tenant_temp')->table('products')->count();
                $tenant->db_status = 'connected';
                $tenant->product_count = $productCount;
            } catch (\Throwable $e) {
                $tenant->db_status = 'error';
                $tenant->db_error_message = $e->getMessage();
                $tenant->product_count = 0;
            }
        }

        return view('admin.saas_tenants.index', compact('tenants'));
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|unique:saas_tenants,subdomain|max:255|alpha_dash',
            'db_name' => 'nullable|string|max:255',
        ]);

        try {
            $provisioner = new \App\Services\TenantProvisioningService();
            $provisioner->provision(
                $request->name,
                $request->subdomain,
                $request->db_name,
                auth()->user()
            );

            return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant database and subdomain created & provisioned successfully!');
        } catch (\Throwable $e) {
            Log::error("Manual tenant provisioning failed: " . $e->getMessage());
            return redirect()->route('admin.saas-tenants.index')->with('error', 'Failed to provision tenant database: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, $id)
    {
        $tenant = SaaSTenant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|alpha_dash|unique:saas_tenants,subdomain,' . $tenant->id,
            'db_name' => 'nullable|string|max:255',
        ]);

        $tenant->update([
            'name' => $request->name,
            'subdomain' => strtolower($request->subdomain),
            'db_name' => $request->db_name ?: 'purnobd_' . strtolower($request->subdomain),
            'is_active' => $request->has('is_active'),
            'free_promotion' => $request->has('free_promotion'),
        ]);

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant updated successfully!');
    }

    /**
     * Toggle free promotion status for a tenant.
     */
    public function toggleFreePromotion(Request $request, $id)
    {
        $tenant = SaaSTenant::findOrFail($id);
        
        $newStatus = $request->has('free_promotion') 
            ? filter_var($request->input('free_promotion'), FILTER_VALIDATE_BOOLEAN)
            : !$tenant->free_promotion;

        $tenant->update([
            'free_promotion' => $newStatus,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Free promotion " . ($tenant->free_promotion ? 'enabled' : 'disabled') . " for tenant '{$tenant->name}'!",
                'free_promotion' => $tenant->free_promotion,
            ]);
        }

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Free promotion status updated successfully!');
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy($id)
    {
        $tenant = SaaSTenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant deleted successfully!');
    }

    /**
     * View all wholeselling products from all tenants.
     */
    public function wholesaleProducts(Request $request)
    {
        $tenants = SaaSTenant::where('is_active', true)->get();
        $selectedTenantId = $request->input('tenant_id');
        $currentTab = $request->input('tab', 'wholeseller'); // 'wholeseller' or 'admin'
        $commission = floatval($request->input('commission', 0));
        
        $allProducts = [];
        $errors = [];
        $totalWholesellerCount = 0;
        $totalAdminCount = 0;

        foreach ($tenants as $tenant) {
            // If a specific tenant filter is applied, skip other tenants
            if ($selectedTenantId && $tenant->id != $selectedTenantId) {
                continue;
            }

            try {
                // Setup dynamic connection for this tenant
                $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                $this->connectToTenantDatabase($dbName);

                // Fetch wholesellers role user IDs
                $wholesellerRoleIds = DB::connection('tenant_temp')
                    ->table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('roles.name', 'wholeseller')
                    ->pluck('model_has_roles.model_id')
                    ->toArray();

                // Also check vendor_settings additional_config for vendor_type = wholeseller
                $vendorSettingWholesellers = DB::connection('tenant_temp')
                    ->table('vendor_settings')
                    ->get()
                    ->filter(function($vs) {
                        if (!empty($vs->additional_config)) {
                            $cfg = is_string($vs->additional_config) ? json_decode($vs->additional_config, true) : $vs->additional_config;
                            return is_array($cfg) && ($cfg['vendor_type'] ?? '') === 'wholeseller';
                        }
                        return false;
                    })
                    ->pluck('vendor_id')
                    ->toArray();

                $wholesellerIds = array_unique(array_merge($wholesellerRoleIds, $vendorSettingWholesellers));

                // Count for both tabs
                $wholesellerCount = !empty($wholesellerIds)
                    ? DB::connection('tenant_temp')->table('products')->whereIn('vendor_id', $wholesellerIds)->count()
                    : 0;
                
                $adminCount = DB::connection('tenant_temp')
                    ->table('products')
                    ->where(function ($q) use ($wholesellerIds) {
                        $q->whereNull('vendor_id')
                          ->orWhere('vendor_id', 0);
                    })
                    ->count();

                $totalWholesellerCount += $wholesellerCount;
                $totalAdminCount += $adminCount;

                // Fetch products based on active tab
                if ($currentTab === 'wholeseller') {
                    $products = !empty($wholesellerIds)
                        ? DB::connection('tenant_temp')->table('products')->whereIn('vendor_id', $wholesellerIds)->get()
                        : collect();
                } else {
                    // Admin products
                    $products = DB::connection('tenant_temp')
                        ->table('products')
                        ->where(function ($q) {
                            $q->whereNull('vendor_id')
                              ->orWhere('vendor_id', 0);
                        })
                        ->get();
                }

                // Fetch vendor settings and details for the selected products
                $vendorIds = $products->pluck('vendor_id')->filter()->unique()->toArray();
                $vendors = collect();
                if (!empty($vendorIds)) {
                    $vendors = DB::connection('tenant_temp')
                        ->table('users')
                        ->leftJoin('vendor_settings', 'users.id', '=', 'vendor_settings.vendor_id')
                        ->whereIn('users.id', $vendorIds)
                        ->select('users.id', 'users.name', 'users.email', 'vendor_settings.business_name')
                        ->get()
                        ->keyBy('id');
                }

                foreach ($products as $prod) {
                    $vendor = isset($vendors[$prod->vendor_id]) ? $vendors[$prod->vendor_id] : null;
                    
                    $thumbImage = $prod->thumb_image;
                    $thumbImageUrl = null;
                    if (!empty($thumbImage)) {
                        if (filter_var($thumbImage, FILTER_VALIDATE_URL) || str_starts_with($thumbImage, 'http://') || str_starts_with($thumbImage, 'https://')) {
                            $thumbImageUrl = $thumbImage;
                        } elseif (str_starts_with($thumbImage, 'storage/')) {
                            $thumbImageUrl = asset($thumbImage);
                        } elseif (str_starts_with($thumbImage, '/')) {
                            $thumbImageUrl = asset(ltrim($thumbImage, '/'));
                        } else {
                            $thumbImageUrl = asset('storage/' . $thumbImage);
                        }
                    }

                    $baseWholesale = floatval($prod->wholesale_price ?: 0);
                    $commissionAmount = $commission > 0 ? ($baseWholesale * ($commission / 100)) : 0;
                    $finalWholesale = $baseWholesale + $commissionAmount;

                    $allProducts[] = [
                        'tenant_name' => $tenant->name,
                        'tenant_subdomain' => $tenant->subdomain,
                        'id' => $prod->id,
                        'title' => $prod->title,
                        'thumb_image' => $thumbImageUrl,
                        'wholesale_price' => $baseWholesale,
                        'commission_percent' => $commission,
                        'commission_amount' => $commissionAmount,
                        'final_wholesale_price' => $finalWholesale,
                        'price' => isset($prod->price) ? $prod->price : ($prod->old_price ?? 0),
                        'offer' => $prod->offer,
                        'quantity' => $prod->quantity,
                        'status' => $prod->status,
                        'vendor_name' => $vendor ? ($vendor->business_name ?: $vendor->name) : ($currentTab === 'admin' ? 'Tenant Admin' : 'N/A'),
                        'vendor_email' => $vendor ? $vendor->email : ($currentTab === 'admin' ? 'Store Owner' : 'N/A'),
                    ];
                }

            } catch (\Exception $e) {
                Log::error("Failed to fetch products for tenant {$tenant->name}: " . $e->getMessage());
                $errors[] = "Could not connect to database for tenant '{$tenant->name}' ({$tenant->subdomain}).";
            }
        }

        // Paginate manually since it's merged from multiple DB connections
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($allProducts, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            count($allProducts),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.saas_tenants.products', compact(
            'paginatedProducts',
            'tenants',
            'selectedTenantId',
            'currentTab',
            'commission',
            'totalWholesellerCount',
            'totalAdminCount',
            'errors'
        ));
    }

    /**
     * Dynamically connect to the tenant database using the tenant_temp connection.
     */
    private function connectToTenantDatabase($dbName)
    {
        $defaultConfig = config('database.connections.mysql');
        $defaultConfig['database'] = $dbName;

        config(['database.connections.tenant_temp' => $defaultConfig]);

        DB::purge('tenant_temp');
    }
}
