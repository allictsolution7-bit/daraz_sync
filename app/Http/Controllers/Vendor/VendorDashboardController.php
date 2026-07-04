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
        $effectiveSettings = $vendorSettings->getEffectiveSettings();

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
        $vendorSettings = $vendor->vendorSettings;

        return view('vendor.profile.index', compact('vendor', 'vendorSettings'));
    }

    /**
     * Update vendor profile
     */
    public function updateProfile(Request $request)
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

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
        ]);

        // Only update fields that were actually submitted
        $dataToUpdate = array_filter($validated, function($value) {
            return !is_null($value);
        });

        if (!empty($dataToUpdate)) {
            $vendorSettings->update($dataToUpdate);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}

