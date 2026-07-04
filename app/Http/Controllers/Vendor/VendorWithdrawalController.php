<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorWithdrawal;
use App\Services\VendorService;
use App\Services\VendorPayoutService;
use Illuminate\Http\Request;

class VendorWithdrawalController extends Controller
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
     * Display vendor's withdrawal requests
     */
    public function index()
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;
        $vendorService = $this->vendorService;

        $withdrawals = VendorWithdrawal::forVendor($vendor->id)
            ->latest()
            ->paginate(20);

        return view('vendor.withdrawals.index', compact(
            'withdrawals',
            'vendorService',
            'vendorSettings'
        ));
    }

    /**
     * Show create withdrawal form
     */
    public function create()
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        $currentBalance = $this->vendorService->calculateBalance($vendor->id);
        $pendingWithdrawals = VendorWithdrawal::forVendor($vendor->id)
            ->pending()
            ->sum('amount');
        
        $availableBalance = $currentBalance - $pendingWithdrawals;
        $minWithdrawal = $vendorSettings->getMinWithdrawalAmount();

        return view('vendor.withdrawals.create', compact(
            'currentBalance',
            'pendingWithdrawals',
            'availableBalance',
            'minWithdrawal',
            'vendorSettings'
        ));
    }

    /**
     * Store withdrawal request
     */
    public function store(Request $request)
    {
        $vendor = auth()->user();
        $vendorSettings = $vendor->vendorSettings;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payout_method' => 'required|in:bank,bkash,nagad,rocket',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'payout_bank_name' => 'nullable|required_if:payout_method,bank|string|max:255',
            'payout_branch_name' => 'nullable|string|max:255',
            'payout_routing_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        // Prepare account details
        $accountDetails = [
            'account_number' => $validated['account_number'],
            'account_name' => $validated['account_name'],
        ];

        if ($validated['payout_method'] === 'bank') {
            $accountDetails['bank_name'] = $validated['payout_bank_name'] ?? null;
            $accountDetails['branch_name'] = $validated['payout_branch_name'] ?? null;
            $accountDetails['routing_number'] = $validated['payout_routing_number'] ?? null;
        }

        // Create withdrawal request
        $result = $this->payoutService->createWithdrawalRequest(
            $vendor->id,
            $validated['amount'],
            $validated['payout_method'],
            $accountDetails,
            $validated['notes'] ?? null
        );

        if ($result['success']) {
            return redirect()->route('vendor.withdrawals.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Show withdrawal details
     */
    public function show(Request $request, VendorWithdrawal $withdrawal)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's withdrawal
        if ($withdrawal->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        $withdrawal->load(['processedBy', 'orderItems.product']);

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'fee' => $withdrawal->fee,
                'net_amount' => $withdrawal->net_amount,
                'status' => $withdrawal->status,
                'payment_method' => $withdrawal->payment_method,
                'account_number' => $withdrawal->account_number,
                'account_name' => $withdrawal->account_name,
                'bank_name' => $withdrawal->bank_name,
                'branch_name' => $withdrawal->branch_name,
                'routing_number' => $withdrawal->routing_number,
                'vendor_note' => $withdrawal->vendor_note,
                'admin_note' => $withdrawal->admin_note,
                'transaction_id' => $withdrawal->transaction_id,
                'created_at' => $withdrawal->created_at,
                'processed_at' => $withdrawal->processed_at,
            ]);
        }

        return view('vendor.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Cancel a pending withdrawal
     */
    public function cancel(VendorWithdrawal $withdrawal)
    {
        $vendor = auth()->user();

        // Ensure this is vendor's withdrawal
        if ($withdrawal->vendor_id !== $vendor->id) {
            abort(403, 'Unauthorized');
        }

        $result = $this->payoutService->cancelWithdrawal(
            $withdrawal->id,
            $vendor->id,
            'Cancelled by vendor'
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}

