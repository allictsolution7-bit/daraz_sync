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

        SaaSTenant::create([
            'name' => $request->name,
            'subdomain' => strtolower($request->subdomain),
            'db_name' => $request->db_name ?: 'purnobd_' . strtolower($request->subdomain),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant subdomain registered successfully!');
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
        ]);

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant updated successfully!');
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
        $allProducts = [];
        $errors = [];

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
                $wholesellerIds = DB::connection('tenant_temp')
                    ->table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('roles.name', 'wholeseller')
                    ->pluck('model_has_roles.model_id')
                    ->toArray();

                // Fetch wholesale products
                $products = DB::connection('tenant_temp')
                    ->table('products')
                    ->where(function ($q) use ($wholesellerIds) {
                        $q->where('wholesale_price', '>', 0)
                          ->orWhereIn('vendor_id', $wholesellerIds);
                    })
                    ->get();

                // Fetch vendor settings and details
                $vendorIds = $products->pluck('vendor_id')->filter()->unique()->toArray();
                $vendors = DB::connection('tenant_temp')
                    ->table('users')
                    ->leftJoin('vendor_settings', 'users.id', '=', 'vendor_settings.vendor_id')
                    ->whereIn('users.id', $vendorIds)
                    ->select('users.id', 'users.name', 'users.email', 'vendor_settings.business_name')
                    ->get()
                    ->keyBy('id');

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

                    $allProducts[] = [
                        'tenant_name' => $tenant->name,
                        'tenant_subdomain' => $tenant->subdomain,
                        'id' => $prod->id,
                        'title' => $prod->title,
                        'thumb_image' => $thumbImageUrl,
                        'wholesale_price' => $prod->wholesale_price,
                        'price' => isset($prod->price) ? $prod->price : ($prod->old_price ?? 0),
                        'offer' => $prod->offer,
                        'quantity' => $prod->quantity,
                        'status' => $prod->status,
                        'vendor_name' => $vendor ? ($vendor->business_name ?: $vendor->name) : 'N/A',
                        'vendor_email' => $vendor ? $vendor->email : 'N/A',
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

        return view('admin.saas_tenants.products', compact('paginatedProducts', 'tenants', 'selectedTenantId', 'errors'));
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
