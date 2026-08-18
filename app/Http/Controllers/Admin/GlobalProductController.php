<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GlobalProductController extends Controller
{
    protected GlobalProductService $globalProductService;

    public function __construct(GlobalProductService $globalProductService)
    {
        $this->globalProductService = $globalProductService;
    }

    /**
     * Display Global SaaS Products Catalog.
     */
    public function index(Request $request)
    {
        $currentTab = $request->get('tab', 'admin'); // 'admin' or 'wholeseller'
        $selectedTenantId = $request->get('tenant_id');
        $search = $request->get('search');

        $data = $this->globalProductService->getGlobalProducts([
            'tab' => $currentTab,
            'tenant_id' => $selectedTenantId,
            'search' => $search,
        ]);

        $allProducts = $data['products'];
        $tenants = $data['tenants'];
        $totalWholesellerCount = $data['totalWholesellerCount'];
        $totalAdminCount = $data['totalAdminCount'];
        $globalCommission = $data['globalCommission'];
        $errors = $data['errors'];

        // Paginate manually since it aggregates from multiple tenant connections
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($allProducts, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new LengthAwarePaginator(
            $currentItems,
            count($allProducts),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $superAdminBkash = '';
        $superAdminNagad = '';
        $superAdminRocket = '';
        $superAdminBank = '';
        $superAdminBkashEnabled = true;
        $superAdminNagadEnabled = true;
        $superAdminRocketEnabled = true;
        $superAdminBankEnabled = true;

        try {
            // Find Super Admin User in central DB (admin@purnobd.com / sabbir@purnobd.com)
            $superAdminUser = DB::connection('mysql')->table('users')
                ->where('email', 'admin@purnobd.com')
                ->orWhere('email', 'sabbir@purnobd.com')
                ->first();

            $superAdminId = $superAdminUser ? $superAdminUser->id : 129;

            // Retrieve configured manual payment gateways from central site_settings table with priority
            $settingsQuery = DB::connection('mysql')->table('site_settings')
                ->whereIn('group', [
                    "vendor_{$superAdminId}_ecommerce",
                    "vendor_129_ecommerce",
                    "vendor_130_ecommerce",
                    "ecommerce"
                ])
                ->orderByRaw("CASE 
                    WHEN `group` = 'vendor_{$superAdminId}_ecommerce' THEN 1 
                    WHEN `group` = 'vendor_129_ecommerce' THEN 2 
                    WHEN `group` = 'ecommerce' THEN 3 
                    ELSE 4 END")
                ->get();

            foreach ($settingsQuery as $st) {
                if ($st->key === 'bkash_number' && empty($superAdminBkash) && !empty($st->value)) $superAdminBkash = $st->value;
                if ($st->key === 'nagad_number' && empty($superAdminNagad) && !empty($st->value)) $superAdminNagad = $st->value;
                if ($st->key === 'rocket_number' && empty($superAdminRocket) && !empty($st->value)) $superAdminRocket = $st->value;
                if ($st->key === 'bank_account_info' && empty($superAdminBank) && !empty($st->value)) $superAdminBank = $st->value;

                if ($st->key === 'bkash') $superAdminBkashEnabled = ($st->value == '1' || $st->value === 1);
                if ($st->key === 'nagad') $superAdminNagadEnabled = ($st->value == '1' || $st->value === 1);
                if ($st->key === 'rocket') $superAdminRocketEnabled = ($st->value == '1' || $st->value === 1);
                if ($st->key === 'bank') $superAdminBankEnabled = ($st->value == '1' || $st->value === 1);
            }
        } catch (\Throwable $e) {
            Log::error("Super admin gateway query error: " . $e->getMessage());
        }

        // Fallbacks if empty
        if (empty($superAdminBkash)) $superAdminBkash = '01779542054';
        if (empty($superAdminNagad)) $superAdminNagad = $superAdminBkash;
        if (empty($superAdminRocket)) $superAdminRocket = $superAdminBkash;
        if (empty($superAdminBank)) $superAdminBank = 'City Bank: AC 1234567890 (Branch: Dhaka)';

        return view('admin.global_products.index', compact(
            'paginatedProducts',
            'tenants',
            'selectedTenantId',
            'currentTab',
            'globalCommission',
            'totalWholesellerCount',
            'totalAdminCount',
            'errors',
            'superAdminBkash',
            'superAdminNagad',
            'superAdminRocket',
            'superAdminBank',
            'superAdminBkashEnabled',
            'superAdminNagadEnabled',
            'superAdminRocketEnabled',
            'superAdminBankEnabled'
        ));
    }

    /**
     * Copy / Purchase a single SaaS product into the store catalog.
     */
    public function copy(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|string',
            'product_id' => 'required|integer',
            'copy_mode' => 'nullable|string|in:copy,purchase',
            'quantity' => 'nullable|integer|min:0',
        ]);

        $copyMode = $request->input('copy_mode', 'copy');
        $quantity = (int)$request->input('quantity', 0);

        $res = $this->globalProductService->copyProductToStore(
            $request->subdomain,
            (int)$request->product_id,
            $copyMode,
            $quantity
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($res, $res['success'] ? 200 : 422);
        }

        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        } else {
            return redirect()->back()->with('error', $res['message']);
        }
    }

    /**
     * Bulk copy multiple products into the store catalog.
     */
    public function bulkCopy(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.subdomain' => 'required|string',
            'items.*.product_id' => 'required|integer',
        ]);

        $res = $this->globalProductService->bulkCopyProducts($request->items);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($res, $res['success'] ? 200 : 422);
        }

        if ($res['success']) {
            return redirect()->back()->with('success', "{$res['success_count']} products copied successfully into your store catalog!");
        } else {
            return redirect()->back()->with('error', "Failed to copy selected products.");
        }
    }
}
