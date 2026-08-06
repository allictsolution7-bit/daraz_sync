<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\VendorService;
use App\Services\VendorPayoutService;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    protected VendorService $vendorService;
    protected VendorPayoutService $payoutService;

    public function __construct(
        VendorService $vendorService,
        VendorPayoutService $payoutService
    ) {
        $this->vendorService = $vendorService;
        $this->payoutService = $payoutService;
    }

    /**
     * Show vendor dashboard
     */
    public function index()
    {
        $vendor = auth()->user();
        
        if ($vendor->hasRole('reseller')) {
            $adminId = $vendor->created_by;
            $totalAdminProducts = \App\Models\Product::where(function ($q) use ($adminId) {
                if ($adminId) {
                    $q->where('created_by', $adminId)
                      ->orWhere('vendor_id', $adminId)
                      ->orWhereNull('vendor_id');
                } else {
                    $q->whereNull('vendor_id');
                }
            })->count();

            return view('vendor.dashboard.reseller', compact('vendor', 'totalAdminProducts'));
        }

        $vendorSettings = $vendor->vendorSettings;

        // Get statistics
        $stats = $this->vendorService->getVendorStats($vendor->id);
        $withdrawalStats = $this->payoutService->getWithdrawalStats($vendor->id);

        // Get recent products
        $recentProducts = $vendor->products()
            ->latest()
            ->take(5)
            ->get();

        // Get recent orders
        $recentOrders = $vendor->vendorOrders()
            ->with(['customer', 'orderItems.product'])
            ->latest()
            ->take(10)
            ->get();

        // Get effective settings (commission limits, etc.)
        $effectiveSettings = $vendorSettings ? $vendorSettings->getEffectiveSettings() : null;

        return view('vendor.dashboard.index', compact(
            'vendor',
            'vendorSettings',
            'stats',
            'withdrawalStats',
            'recentProducts',
            'recentOrders',
            'effectiveSettings'
        ));
    }

    /**
     * Show vendor profile/settings
     */
    public function profile()
    {
        $vendor = auth()->user();
        if (!$vendor->can('vendor.profile.edit') && !$vendor->hasRole('reseller')) {
            abort(403, 'Unauthorized action.');
        }

        $vendorSettings = \App\Models\VendorSetting::firstOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'business_name' => $vendor->name ?? 'Store',
                'store_slug' => \Illuminate\Support\Str::slug($vendor->name ?? 'store'),
                'business_email' => $vendor->email,
                'is_active' => true,
                'is_verified' => true
            ]
        );

        return view('vendor.profile.index', compact('vendor', 'vendorSettings'));
    }

    /**
     * Update vendor profile
     */
    public function updateProfile(Request $request)
    {
        $vendor = auth()->user();
        if (!$vendor->can('vendor.profile.edit') && !$vendor->hasRole('reseller')) {
            abort(403, 'Unauthorized action.');
        }

        $vendorSettings = \App\Models\VendorSetting::firstOrCreate(
            ['vendor_id' => $vendor->id],
            [
                'business_name' => $vendor->name ?? 'Store',
                'store_slug' => \Illuminate\Support\Str::slug($vendor->name ?? 'store'),
                'business_email' => $vendor->email,
                'is_active' => true,
                'is_verified' => true
            ]
        );

        // Make validation flexible - only validate fields that are present
        $validated = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'payout_method' => 'nullable|in:bank,bkash,nagad,rocket',
            'payout_account_number' => 'nullable|string|max:50',
            'payout_account_name' => 'nullable|string|max:255',
            'payout_bank_name' => 'nullable|string|max:255',
            'payout_branch_name' => 'nullable|string|max:255',
            'payout_routing_number' => 'nullable|string|max:50',
            'sale_price_markup_pct' => 'nullable|numeric|min:0',
            'old_price_markup_pct' => 'nullable|numeric|min:0',
            'wholesale_price_markup_pct' => 'nullable|numeric|min:0',
            'reseller_markup_pct' => 'nullable|numeric|min:0',
        ]);

        // Only update fields that were actually submitted
        $dataToUpdate = array_filter($validated, function($value) {
            return !is_null($value) && $value !== '';
        });

        if (!empty($dataToUpdate)) {
            $vendorSettings->update($dataToUpdate);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}

