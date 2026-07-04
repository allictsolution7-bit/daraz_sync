<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorWithdrawal;
use App\Models\order_item;
use App\Services\VendorPayoutService;
use App\Services\VendorService;
use Illuminate\Http\Request;

class AdminVendorWithdrawalController extends Controller
{
    protected VendorPayoutService $payoutService;
    protected VendorService $vendorService;

    public function __construct(VendorPayoutService $payoutService, VendorService $vendorService)
    {
        $this->payoutService = $payoutService;
        $this->vendorService = $vendorService;
    }

    /**
     * Display all withdrawal requests
     */
    public function index(Request $request)
    {
        $query = VendorWithdrawal::with(['vendor.vendorSettings']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by vendor
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Order: pending first, then others
        $withdrawals = $query->orderByRaw("
            CASE status
                WHEN 'pending' THEN 1
                WHEN 'approved' THEN 2
                WHEN 'completed' THEN 3
                WHEN 'rejected' THEN 4
                WHEN 'cancelled' THEN 5
            END
        ")->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'pending_count' => VendorWithdrawal::where('status', 'pending')->count(),
            'pending_amount' => VendorWithdrawal::where('status', 'pending')->sum('amount'),
            'approved_count' => VendorWithdrawal::where('status', 'approved')->count(),
            'approved_amount' => VendorWithdrawal::where('status', 'approved')->sum('amount'),
            'processing_count' => VendorWithdrawal::where('status', 'approved')->count(), // Approved = Processing
            'processing_amount' => VendorWithdrawal::where('status', 'approved')->sum('amount'),
            'completed_count' => VendorWithdrawal::where('status', 'completed')->count(),
            'completed_amount' => VendorWithdrawal::where('status', 'completed')->sum('amount'),
            'rejected_count' => VendorWithdrawal::where('status', 'rejected')->count(),
            'rejected_amount' => VendorWithdrawal::where('status', 'rejected')->sum('amount'),
            'total_count' => VendorWithdrawal::count(),
            'total_amount' => VendorWithdrawal::sum('amount'),
        ];

        return view('admin.vendor-withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * Show withdrawal details
     */
    public function show(VendorWithdrawal $withdrawal)
    {
        $withdrawal->load([
            'vendor.vendorSettings',
            'processedBy',
        ]);

        // Get vendor's current balance
        $vendorBalance = $this->vendorService->calculateBalance($withdrawal->vendor_id);
        
        // Get vendor statistics
        $vendorStats = $this->vendorService->getVendorStats($withdrawal->vendor_id);
        
        // Add withdrawal-specific stats
        $vendorStats['total_withdrawn'] = VendorWithdrawal::where('vendor_id', $withdrawal->vendor_id)
            ->whereIn('status', ['completed', 'paid'])
            ->sum('amount');
        
        $vendorStats['pending_withdrawals'] = VendorWithdrawal::where('vendor_id', $withdrawal->vendor_id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        
        // Get order items associated with this withdrawal (if any)
        $orderItems = order_item::where('vendor_id', $withdrawal->vendor_id)
            ->where('paid_in_withdrawal_id', $withdrawal->id)
            ->with(['product', 'order', 'vendor'])
            ->get();

        return view('admin.vendor-withdrawals.show', compact('withdrawal', 'vendorBalance', 'vendorStats', 'orderItems'));
    }

    /**
     * Approve a withdrawal request
     */
    public function approve(Request $request, VendorWithdrawal $withdrawal)
    {
        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $result = $this->payoutService->approveWithdrawal(
            $withdrawal->id,
            auth()->id(),
            $validated['admin_note'] ?? null
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Reject a withdrawal request
     */
    public function reject(Request $request, VendorWithdrawal $withdrawal)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $result = $this->payoutService->rejectWithdrawal(
            $withdrawal->id,
            auth()->id(),
            $validated['rejection_reason']
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Complete a withdrawal (mark as paid)
     */
    public function complete(Request $request, VendorWithdrawal $withdrawal)
    {
        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $result = $this->payoutService->completeWithdrawal(
            $withdrawal->id,
            auth()->id(),
            $validated['transaction_id'] ?? null,
            $validated['admin_note'] ?? null
        );

        if ($result['success']) {
            return redirect()->route('admin.vendor-withdrawals.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Bulk approve withdrawals
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'withdrawal_ids' => 'required|array',
            'withdrawal_ids.*' => 'exists:vendor_withdrawals,id',
        ]);

        $count = 0;
        foreach ($validated['withdrawal_ids'] as $withdrawalId) {
            $withdrawal = VendorWithdrawal::find($withdrawalId);
            if ($withdrawal && $withdrawal->isPending()) {
                $result = $this->payoutService->approveWithdrawal(
                    $withdrawal->id,
                    auth()->id()
                );
                if ($result['success']) {
                    $count++;
                }
            }
        }

        return redirect()->back()
            ->with('success', "{$count} withdrawal requests approved successfully!");
    }

    /**
     * Show payout history/statistics
     */
    public function payoutHistory(Request $request)
    {
        $query = VendorWithdrawal::where('status', 'completed')
            ->with(['vendor.vendorSettings', 'processedBy']);

        // Date range filter
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('paid_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('paid_at', '<=', $request->end_date);
        }

        // Vendor filter
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $payouts = $query->latest('paid_at')->paginate(50);

        // Calculate statistics
        $totalAmount = $query->sum('amount');
        $totalPayouts = $query->count();

        $stats = [
            'total_amount' => $totalAmount,
            'total_payouts' => $totalPayouts,
            'average_payout' => $totalPayouts > 0 ? $totalAmount / $totalPayouts : 0,
        ];

        return view('admin.vendor-withdrawals.history', compact('payouts', 'stats'));
    }
}

