<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\VendorSetting;
use App\Models\VendorBalanceLedger;
use App\Models\VendorWalletTransaction;
use App\Models\order_item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorService
{
    /**
     * Calculate vendor's current balance
     */
    public function calculateBalance(int $vendorId): float
    {
        $ledger = VendorBalanceLedger::forVendor($vendorId)
            ->latest()
            ->first();

        return $ledger ? (float) $ledger->balance_after : 0.00;
    }

    /**
     * Alias for calculateBalance
     */
    public function getCurrentBalance(int $vendorId): float
    {
        return $this->calculateBalance($vendorId);
    }

    /**
     * Get vendor's pending earnings (not yet credited to balance)
     * These are earnings from completed orders that haven't been processed yet
     */
    public function getPendingEarnings(int $vendorId): float
    {
        // Get unpaid order items for this vendor
        return (float) order_item::forVendor($vendorId)
            ->unpaid()
            ->whereHas('order', function ($query) {
                $query->where('status', 'delivered'); // Only delivered orders
            })
            ->sum('vendor_earning');
    }

    /**
     * Get vendor's total earnings (all time)
     */
    public function getTotalEarnings(int $vendorId): float
    {
        return (float) order_item::forVendor($vendorId)
            ->whereNotNull('vendor_earning')
            ->sum('vendor_earning');
    }

    /**
     * Get vendor's statistics
     */
    public function getVendorStats(int $vendorId): array
    {
        $totalProducts = Product::forVendor($vendorId)->count();
        $approvedProducts = Product::forVendor($vendorId)->approved()->count();
        $pendingProducts = Product::forVendor($vendorId)->pending()->count();
        
        $totalOrders = order_item::forVendor($vendorId)
            ->distinct('order_id')
            ->count('order_id');
        
        $totalEarnings = $this->getTotalEarnings($vendorId);
        $pendingEarnings = $this->getPendingEarnings($vendorId);
        $currentBalance = $this->calculateBalance($vendorId);

        return [
            // Flat keys for view compatibility
            'total_products' => $totalProducts,
            'approved_products' => $approvedProducts,
            'pending_products' => $pendingProducts,
            'total_orders' => $totalOrders,
            'total_earnings' => $totalEarnings,
            'pending_earnings' => $pendingEarnings,
            'current_balance' => $currentBalance,
            'withdrawn' => $totalEarnings - $currentBalance - $pendingEarnings,
            
            // Nested structure for backward compatibility
            'products' => [
                'total' => $totalProducts,
                'approved' => $approvedProducts,
                'pending' => $pendingProducts,
            ],
            'orders' => [
                'total' => $totalOrders,
            ],
            'earnings' => [
                'total' => $totalEarnings,
                'pending' => $pendingEarnings,
                'balance' => $currentBalance,
                'withdrawn' => $totalEarnings - $currentBalance - $pendingEarnings,
            ],
        ];
    }

    /**
     * Process completed order items and credit vendor balance
     * Call this when an order is marked as delivered/completed
     */
    public function processCompletedOrderItems(int $orderId): void
    {
        $orderItems = order_item::where('order_id', $orderId)
            ->whereNotNull('vendor_id')
            ->unpaid()
            ->get();

        foreach ($orderItems as $item) {
            $this->creditVendorEarning($item);
        }
    }

    /**
     * Credit vendor's earning to their balance
     */
    public function creditVendorEarning(order_item $orderItem): void
    {
        if (!$orderItem->vendor_id || !$orderItem->vendor_earning) {
            return;
        }

        DB::transaction(function () use ($orderItem) {
            // Get current balance
            $currentBalance = $this->calculateBalance($orderItem->vendor_id);
            $newBalance = $currentBalance + $orderItem->vendor_earning;

            // Create ledger entry
            VendorBalanceLedger::create([
                'vendor_id' => $orderItem->vendor_id,
                'transaction_type' => 'sale',
                'amount' => $orderItem->vendor_earning,
                'balance_after' => $newBalance,
                'order_id' => $orderItem->order_id,
                'order_item_id' => $orderItem->id,
                'description' => "Earning from Order #{$orderItem->order_id} - {$orderItem->product->title}",
            ]);

            // Note: We don't mark as paid yet - that happens when withdrawal is completed
        });
    }

    /**
     * Approve a product
     */
    public function approveProduct(int $productId, int $adminId, ?string $note = null): bool
    {
        $product = Product::findOrFail($productId);

        if (!$product->vendor_id) {
            return false; // Not a vendor product
        }

        DB::transaction(function () use ($product, $adminId, $note) {
            $product->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $adminId,
                'commission_note' => $note,
                'status' => 1, // Also activate the product
            ]);

            // Deduct stock from Parent Admin product if this was copied
            $adminProduct = Product::whereNull('vendor_id')->where('title', $product->title)->first();
            if ($adminProduct) {
                if ($product->product_type === 'variable' && $product->variationCombinations()->exists()) {
                    foreach ($product->variationCombinations as $vComb) {
                        if ($vComb->stock_quantity > 0) {
                            $opts = $vComb->variation_options;
                            // Find matching admin variation combination
                            $adminComb = $adminProduct->variationCombinations->first(function($item) use ($opts) {
                                return json_encode($item->variation_options) === json_encode($opts);
                            });

                            if ($adminComb && $adminComb->stock_quantity !== null) {
                                $adminComb->decrement('stock_quantity', min($adminComb->stock_quantity, $vComb->stock_quantity));
                            }
                        }
                    }
                } elseif ($adminProduct->quantity !== null && $product->quantity > 0) {
                    $adminProduct->decrement('quantity', min($adminProduct->quantity, $product->quantity));
                }
            }

            // Update VendorWalletTransaction to approved
            VendorWalletTransaction::where('vendor_id', $product->vendor_id)
                ->where('type', 'stock_purchase')
                ->where('admin_note', 'like', '%' . $product->title . '%')
                ->update(['status' => 'approved']);
        });

        return true;
    }

    /**
     * Reject a product
     */
    public function rejectProduct(int $productId, int $adminId, string $reason): bool
    {
        $product = Product::findOrFail($productId);

        if (!$product->vendor_id) {
            return false;
        }

        DB::transaction(function () use ($product, $adminId, $reason) {
            $product->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $reason,
                'approved_by' => $adminId,
                'status' => 0, // Deactivate
            ]);

            // Find stock purchase transaction for this product
            $trx = VendorWalletTransaction::where('vendor_id', $product->vendor_id)
                ->where('type', 'stock_purchase')
                ->where('admin_note', 'like', '%' . $product->title . '%')
                ->latest()
                ->first();

            if ($trx && $trx->amount > 0) {
                // Refund vendor wallet
                $vendor = User::find($product->vendor_id);
                if ($vendor) {
                    $vendor->increment('wallet_balance', $trx->amount);

                    VendorWalletTransaction::create([
                        'vendor_id' => $vendor->id,
                        'admin_id' => $adminId,
                        'type' => 'stock_purchase_refund',
                        'amount' => $trx->amount,
                        'payment_method' => 'Wallet',
                        'transaction_id' => 'REF-' . strtoupper(Str::random(8)),
                        'status' => 'approved',
                        'admin_note' => 'Refund for rejected stock purchase: "' . $product->title . '" (' . $reason . ')',
                        'is_seen' => true,
                    ]);
                }

                $trx->update(['status' => 'rejected']);
            }
        });

        return true;
    }

    /**
     * Approve a vendor product allocation request (without creating duplicate product)
     */
    public function approveAllocation(int $allocationId, int $adminId): bool
    {
        $allocation = \App\Models\VendorProductAllocation::findOrFail($allocationId);

        DB::transaction(function () use ($allocation, $adminId) {
            $allocation->update([
                'status' => 'approved',
                'allocated_quantity' => $allocation->requested_quantity,
            ]);

            // Update VendorWalletTransaction to approved
            VendorWalletTransaction::where('vendor_id', $allocation->vendor_id)
                ->where('type', 'stock_purchase')
                ->where('product_id', $allocation->product_id)
                ->where('status', 'pending')
                ->update(['status' => 'approved']);
        });

        return true;
    }

    /**
     * Reject a vendor product allocation request and refund wallet
     */
    public function rejectAllocation(int $allocationId, int $adminId, ?string $reason = null): bool
    {
        $allocation = \App\Models\VendorProductAllocation::findOrFail($allocationId);

        DB::transaction(function () use ($allocation, $adminId, $reason) {
            $allocation->update([
                'status' => 'rejected',
            ]);

            $trx = VendorWalletTransaction::where('vendor_id', $allocation->vendor_id)
                ->where('type', 'stock_purchase')
                ->where('product_id', $allocation->product_id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($trx && $trx->amount > 0) {
                $vendor = User::find($allocation->vendor_id);
                if ($vendor) {
                    $vendor->increment('wallet_balance', $trx->amount);

                    VendorWalletTransaction::create([
                        'vendor_id' => $vendor->id,
                        'product_id' => $allocation->product_id,
                        'admin_id' => $adminId,
                        'type' => 'stock_purchase_refund',
                        'amount' => $trx->amount,
                        'payment_method' => 'Wallet',
                        'transaction_id' => 'REF-' . strtoupper(Str::random(8)),
                        'status' => 'approved',
                        'admin_note' => 'Refund for rejected stock allocation request on "' . ($allocation->product->title ?? 'Product') . '": ' . ($reason ?? 'Rejected by admin'),
                        'is_seen' => true,
                    ]);
                }
                $trx->update(['status' => 'rejected']);
            }
        });

        return true;
    }

    /**
     * Calculate commission for a product
     * Returns the commission rate to be used
     */
    public function calculateProductCommission(Product $product): float
    {
        // If vendor proposed commission and it's set, use that
        if ($product->vendor_commission_rate !== null) {
            return $product->vendor_commission_rate;
        }

        // Otherwise, use vendor's default commission
        $vendorSettings = VendorSetting::where('vendor_id', $product->vendor_id)->first();
        
        return $vendorSettings ? $vendorSettings->getDefaultCommissionRate() : 15.00;
    }

    /**
     * Validate vendor's proposed commission
     */
    public function validateProposedCommission(int $vendorId, float $proposedRate): array
    {
        $vendorSettings = VendorSetting::where('vendor_id', $vendorId)->first();

        if (!$vendorSettings) {
            return [
                'valid' => false,
                'message' => 'Vendor settings not found.',
            ];
        }

        $minRate = $vendorSettings->getMinCommissionRate();
        $maxRate = $vendorSettings->getMaxCommissionRate();

        if ($proposedRate < $minRate) {
            return [
                'valid' => false,
                'message' => "Commission rate must be at least {$minRate}%.",
                'min' => $minRate,
                'max' => $maxRate,
            ];
        }

        if ($proposedRate > $maxRate) {
            return [
                'valid' => false,
                'message' => "Commission rate cannot exceed {$maxRate}%.",
                'min' => $minRate,
                'max' => $maxRate,
            ];
        }

        return [
            'valid' => true,
            'message' => 'Commission rate is valid.',
            'min' => $minRate,
            'max' => $maxRate,
        ];
    }

    /**
     * Check if vendor can add more products
     */
    public function canAddProduct(int $vendorId): array
    {
        $vendorSettings = VendorSetting::where('vendor_id', $vendorId)->first();

        if (!$vendorSettings) {
            return [
                'can_add' => false,
                'message' => 'Vendor settings not found.',
            ];
        }

        if (!$vendorSettings->is_active) {
            return [
                'can_add' => false,
                'message' => 'Your vendor account is inactive.',
            ];
        }

        if ($vendorSettings->hasReachedProductLimit()) {
            return [
                'can_add' => false,
                'message' => 'You have reached your product limit.',
            ];
        }

        return [
            'can_add' => true,
            'message' => 'You can add products.',
        ];
    }
}

