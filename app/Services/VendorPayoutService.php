<?php

namespace App\Services;

use App\Models\User;
use App\Models\VendorSetting;
use App\Models\VendorBalanceLedger;
use App\Models\VendorWithdrawal;
use App\Models\order_item;
use Illuminate\Support\Facades\DB;
use Exception;

class VendorPayoutService
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Create a withdrawal request
     */
    public function createWithdrawalRequest(
        int $vendorId,
        float $amount,
        string $method,
        array $accountDetails = [],
        ?string $note = null
    ): array {
        try {
            $vendorSettings = VendorSetting::where('vendor_id', $vendorId)->firstOrFail();
            
            // Validate vendor can withdraw
            if (!$vendorSettings->canWithdraw($amount)) {
                return [
                    'success' => false,
                    'message' => 'Cannot process withdrawal. Check your account status and minimum withdrawal amount.',
                ];
            }

            // Check balance
            $currentBalance = $this->vendorService->calculateBalance($vendorId);
            
            if ($amount > $currentBalance) {
                return [
                    'success' => false,
                    'message' => "Insufficient balance. Available: ৳" . number_format($currentBalance, 2),
                ];
            }

            // Check for pending withdrawals
            $pendingWithdrawals = VendorWithdrawal::forVendor($vendorId)
                ->pending()
                ->sum('amount');

            $availableBalance = $currentBalance - $pendingWithdrawals;

            if ($amount > $availableBalance) {
                return [
                    'success' => false,
                    'message' => "You have pending withdrawals. Available after pending: ৳" . number_format($availableBalance, 2),
                ];
            }

            // Create withdrawal request
            $withdrawal = VendorWithdrawal::create([
                'vendor_id' => $vendorId,
                'amount' => $amount,
                'method' => $method,
                'account_details' => $accountDetails,
                'note' => $note,
                'status' => 'pending',
            ]);

            // TODO: Send notification to admin

            return [
                'success' => true,
                'message' => 'Withdrawal request created successfully.',
                'withdrawal' => $withdrawal,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating withdrawal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Approve a withdrawal request
     */
    public function approveWithdrawal(int $withdrawalId, int $adminId, ?string $note = null): array
    {
        try {
            $withdrawal = VendorWithdrawal::findOrFail($withdrawalId);

            if (!$withdrawal->isPending()) {
                return [
                    'success' => false,
                    'message' => 'Only pending withdrawals can be approved.',
                ];
            }

            // Check vendor balance again
            $currentBalance = $this->vendorService->calculateBalance($withdrawal->vendor_id);
            
            if ($withdrawal->amount > $currentBalance) {
                return [
                    'success' => false,
                    'message' => 'Insufficient vendor balance.',
                ];
            }

            $withdrawal->update([
                'status' => 'approved',
                'approved_at' => now(),
                'admin_note' => $note,
                'processed_by' => $adminId,
            ]);

            // TODO: Send notification to vendor

            return [
                'success' => true,
                'message' => 'Withdrawal approved. Ready for payment processing.',
                'withdrawal' => $withdrawal,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error approving withdrawal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reject a withdrawal request
     */
    public function rejectWithdrawal(int $withdrawalId, int $adminId, string $reason): array
    {
        try {
            $withdrawal = VendorWithdrawal::findOrFail($withdrawalId);

            if (!$withdrawal->isPending()) {
                return [
                    'success' => false,
                    'message' => 'Only pending withdrawals can be rejected.',
                ];
            }

            $withdrawal->update([
                'status' => 'rejected',
                'admin_note' => $reason,
                'processed_by' => $adminId,
            ]);

            // TODO: Send notification to vendor

            return [
                'success' => true,
                'message' => 'Withdrawal rejected.',
                'withdrawal' => $withdrawal,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error rejecting withdrawal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Complete a withdrawal (mark as paid)
     * This deducts from vendor balance and marks order items as paid
     */
    public function completeWithdrawal(
        int $withdrawalId, 
        int $adminId, 
        ?string $transactionId = null, 
        ?string $adminNote = null
    ): array {
        try {
            $withdrawal = VendorWithdrawal::findOrFail($withdrawalId);

            if (!$withdrawal->isApproved()) {
                return [
                    'success' => false,
                    'message' => 'Only approved withdrawals can be completed.',
                ];
            }

            DB::transaction(function () use ($withdrawal, $adminId, $transactionId, $adminNote) {
                // Get current balance
                $currentBalance = $this->vendorService->calculateBalance($withdrawal->vendor_id);
                
                if ($withdrawal->amount > $currentBalance) {
                    throw new Exception('Insufficient vendor balance.');
                }

                $newBalance = $currentBalance - $withdrawal->amount;

                // Create debit ledger entry
                VendorBalanceLedger::create([
                    'vendor_id' => $withdrawal->vendor_id,
                    'transaction_type' => 'withdrawal',
                    'amount' => -$withdrawal->amount, // Negative for debit
                    'balance_after' => $newBalance,
                    'withdrawal_id' => $withdrawal->id,
                    'description' => "Withdrawal via {$withdrawal->method}",
                    'created_by' => $adminId,
                ]);

                // Mark unpaid order items as paid (up to withdrawal amount)
                $this->markOrderItemsAsPaid($withdrawal);

                // Update withdrawal status
                $withdrawal->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'processed_by' => $adminId,
                    'transaction_id' => $transactionId,
                    'admin_note' => $adminNote ? ($withdrawal->admin_note ? $withdrawal->admin_note . "\n\nCompletion Note: " . $adminNote : $adminNote) : $withdrawal->admin_note,
                ]);
            });

            // TODO: Send notification to vendor

            return [
                'success' => true,
                'message' => 'Withdrawal completed successfully.',
                'withdrawal' => $withdrawal->fresh(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error completing withdrawal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mark order items as paid for this withdrawal
     * This tracks which earnings were included in this withdrawal
     */
    protected function markOrderItemsAsPaid(VendorWithdrawal $withdrawal): void
    {
        // Get oldest unpaid items first (FIFO)
        $unpaidItems = order_item::forVendor($withdrawal->vendor_id)
            ->unpaid()
            ->whereNotNull('vendor_earning')
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingAmount = $withdrawal->amount;

        foreach ($unpaidItems as $item) {
            if ($remainingAmount <= 0) {
                break;
            }

            if ($item->vendor_earning <= $remainingAmount) {
                // Full item can be marked as paid
                $item->markAsPaid($withdrawal->id);
                $remainingAmount -= $item->vendor_earning;
            } else {
                // Partial payment scenario - in this simple version we skip
                // In a more complex system, you'd split the item
                break;
            }
        }
    }

    /**
     * Cancel a pending withdrawal
     */
    public function cancelWithdrawal(int $withdrawalId, int $userId, ?string $reason = null): array
    {
        try {
            $withdrawal = VendorWithdrawal::findOrFail($withdrawalId);

            if (!$withdrawal->isPending()) {
                return [
                    'success' => false,
                    'message' => 'Only pending withdrawals can be cancelled.',
                ];
            }

            $withdrawal->update([
                'status' => 'cancelled',
                'admin_note' => $reason ?? 'Cancelled by user',
            ]);

            return [
                'success' => true,
                'message' => 'Withdrawal cancelled successfully.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error cancelling withdrawal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get withdrawal statistics for vendor
     */
    public function getWithdrawalStats(int $vendorId): array
    {
        $totalWithdrawn = VendorWithdrawal::forVendor($vendorId)
            ->where('status', 'completed')
            ->sum('amount');

        $pendingWithdrawals = VendorWithdrawal::forVendor($vendorId)
            ->where('status', 'pending')
            ->sum('amount');

        $approvedWithdrawals = VendorWithdrawal::forVendor($vendorId)
            ->where('status', 'approved')
            ->sum('amount');

        $totalRequests = VendorWithdrawal::forVendor($vendorId)->count();

        return [
            'total_withdrawn' => $totalWithdrawn,
            'pending_amount' => $pendingWithdrawals,
            'approved_amount' => $approvedWithdrawals,
            'total_requests' => $totalRequests,
        ];
    }
}

