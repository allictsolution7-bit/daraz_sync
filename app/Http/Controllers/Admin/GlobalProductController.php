<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GlobalProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

        $superAdminBkash = DB::table('settings')->where('key', 'bkash_number')->value('value') ?? '01830501062';
        $superAdminNagad = DB::table('settings')->where('key', 'nagad_number')->value('value') ?? '01830501062';
        $superAdminRocket = DB::table('settings')->where('key', 'rocket_number')->value('value') ?? '01830501062';
        $superAdminBank = DB::table('settings')->where('key', 'bank_account_details')->value('value') ?? 'City Bank: AC 1234567890 (Branch: Dhaka)';

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
            'superAdminBank'
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
