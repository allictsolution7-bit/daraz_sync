<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\VendorService;
use Illuminate\Http\Request;

class AdminVendorProductController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Display all vendor products for approval
     */
    public function index(Request $request)
    {
        $query = Product::whereNotNull('vendor_id')
            ->with(['vendor.vendorSettings', 'category', 'subCategory', 'variationCombinations']);

        // Filter by approval status
        if ($request->has('status') && !empty($request->status) && $request->status !== 'all') {
            $query->where('approval_status', $request->status);
        }

        // Filter by vendor
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(20)->withQueryString();

        return view('admin.vendor-products.index', compact('products'));
    }

    /**
     * Show product details for approval
     */
    public function show(Product $product)
    {
        if (!$product->vendor_id) {
            abort(404, 'This is not a vendor product.');
        }

        $product->load(['vendor.vendorSettings', 'category', 'subCategory', 'brand', 'variationCombinations']);

        // Calculate commission
        $commission = $this->vendorService->calculateProductCommission($product);
        
        $vendorSettings = $product->vendor?->vendorSettings;
        $commissionLimits = [
            'min' => $vendorSettings && method_exists($vendorSettings, 'getMinCommissionRate') ? $vendorSettings->getMinCommissionRate() : 5.0,
            'max' => $vendorSettings && method_exists($vendorSettings, 'getMaxCommissionRate') ? $vendorSettings->getMaxCommissionRate() : 30.0,
            'default' => $vendorSettings && method_exists($vendorSettings, 'getDefaultCommissionRate') ? $vendorSettings->getDefaultCommissionRate() : 15.0,
        ];

        $stockPurchaseTrx = \App\Models\VendorWalletTransaction::where('vendor_id', $product->vendor_id)
            ->where('type', 'stock_purchase')
            ->where('admin_note', 'like', '%' . $product->title . '%')
            ->latest()
            ->first();

        return view('admin.vendor-products.show', compact(
            'product',
            'commission',
            'commissionLimits',
            'stockPurchaseTrx'
        ));
    }

    /**
     * Approve a product
     */
    public function approve(Request $request, Product $product)
    {
        if (!$product->vendor_id) {
            return redirect()->back()->with('error', 'This is not a vendor product.');
        }

        $validated = $request->validate([
            'commission_note' => 'nullable|string|max:500',
            'vendor_commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        // If admin sets a custom commission rate
        if ($request->has('vendor_commission_rate')) {
            $product->vendor_commission_rate = $validated['vendor_commission_rate'];
            $product->save();
        }

        $this->vendorService->approveProduct(
            $product->id,
            auth()->id(),
            $validated['commission_note'] ?? null
        );

        return redirect()->route('admin.vendor-products.index')
            ->with('success', 'Product approved successfully!');
    }

    /**
     * Reject a product
     */
    public function reject(Request $request, Product $product)
    {
        if (!$product->vendor_id) {
            return redirect()->back()->with('error', 'This is not a vendor product.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $this->vendorService->rejectProduct(
            $product->id,
            auth()->id(),
            $validated['rejection_reason']
        );

        return redirect()->route('admin.vendor-products.index')
            ->with('success', 'Product rejected.');
    }

    /**
     * Bulk approve products
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $count = 0;
        foreach ($validated['product_ids'] as $productId) {
            $product = Product::find($productId);
            if ($product && $product->vendor_id && $product->isPending()) {
                $this->vendorService->approveProduct($product->id, auth()->id());
                $count++;
            }
        }

        return redirect()->back()->with('success', "{$count} products approved successfully!");
    }

    /**
     * Update commission rate for a product
     */
    public function updateCommission(Request $request, Product $product)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.vendor-products.show', $product);
        }

        if (!$product->vendor_id) {
            return redirect()->back()->with('error', 'This is not a vendor product.');
        }

        $validated = $request->validate([
            'vendor_commission_rate' => 'required|numeric|min:0|max:100',
            'commission_note' => 'nullable|string|max:500',
        ]);

        $product->update([
            'vendor_commission_rate' => $validated['vendor_commission_rate'],
            'commission_note' => $validated['commission_note'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Commission rate updated successfully!');
    }
}

