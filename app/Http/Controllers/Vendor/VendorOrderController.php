<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorOrderController extends Controller
{
    /**
     * Display vendor's orders (read-only)
     */
    public function index(Request $request)
    {
        $vendor = auth()->user();

        // Get order IDs that contain vendor's products (including copied reseller products)
        $orderIds = order_item::where(function($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id)
              ->orWhereIn('product_id', function($pq) use ($vendor) {
                  $pq->select('id')
                     ->from('products')
                     ->whereIn('parent_product_id', function($ppq) use ($vendor) {
                         $ppq->select('id')
                             ->from('products')
                             ->where('vendor_id', $vendor->id);
                     });
              });
        })
        ->distinct()
        ->pluck('order_id');

        $query = order::whereIn('id', $orderIds)
            ->with(['customer', 'orderItems' => function ($query) use ($vendor) {
                $query->where(function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id)
                      ->orWhereIn('product_id', function($pq) use ($vendor) {
                          $pq->select('id')
                             ->from('products')
                             ->whereIn('parent_product_id', function($ppq) use ($vendor) {
                                 $ppq->select('id')
                                     ->from('products')
                                     ->where('vendor_id', $vendor->id);
                             });
                      });
                });
            }]);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Courier Status
        if ($courierStatus = $request->get('courier_status')) {
            if ($courierStatus === 'steadfast_sent') {
                $query->where('delivery_data->courier_provider', 'steadfast');
            } elseif ($courierStatus === 'steadfast_not_sent') {
                $query->where(function ($q) {
                    $q->whereNull('delivery_data->courier_provider')
                      ->orWhere('delivery_data->courier_provider', '!=', 'steadfast');
                });
            }
        }

        // Filter by Order Type
        if ($orderType = $request->get('order_type')) {
            if ($orderType === 'combo') {
                $query->where('is_combo_order', true);
            } elseif ($orderType === 'regular') {
                $query->where(function ($q) {
                    $q->whereNull('is_combo_order')->orWhere('is_combo_order', false);
                });
            }
        }

        // Filter by Amount range
        if ($minAmount = $request->get('min_amount')) {
            $query->where('total', '>=', (float) $minAmount);
        }
        if ($maxAmount = $request->get('max_amount')) {
            $query->where('total', '<=', (float) $maxAmount);
        }

        // Filter by Date range
        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Search by order ID or customer
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($customerQuery) use ($request) {
                      $customerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(order $order)
    {
        $vendor = auth()->user();

        // Check if vendor has items in this order
        $vendorItems = $order->orderItems()
            ->where(function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id)
                  ->orWhereIn('product_id', function($pq) use ($vendor) {
                      $pq->select('id')
                         ->from('products')
                         ->whereIn('parent_product_id', function($ppq) use ($vendor) {
                             $ppq->select('id')
                                 ->from('products')
                                 ->where('vendor_id', $vendor->id);
                         });
                  });
            })
            ->get();

        if ($vendorItems->isEmpty()) {
            abort(403, 'You do not have access to this order.');
        }

        $order->load(['customer', 'orderItems.product', 'orderItems.vendor']);

        return view('vendor.orders.show', compact('order', 'vendorItems'));
    }

    /**
     * Get earnings summary
     */
    public function earnings(Request $request)
    {
        $vendor = auth()->user();

        // Get all vendor's order items
        $query = order_item::forVendor($vendor->id)
            ->whereNotNull('vendor_earning');

        // Filter by date range if provided
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $items = $query->with(['order', 'product'])->latest()->paginate(50);

        // Calculate totals
        $totalEarnings = $query->sum('vendor_earning');
        $totalCommission = $query->sum('vendor_commission_amount');
        $totalSales = $query->sum('sub_total');
        $paidEarnings = order_item::forVendor($vendor->id)
            ->where(function($q) {
                $q->where('vendor_paid', true)
                  ->orWhereHas('order', function($q2) {
                      $q2->where('payment_status', 'paid');
                  });
            })
            ->sum('vendor_earning');

        $stats = [
            'total_sales' => $totalSales,
            'total_earnings' => $totalEarnings,
            'total_commission' => $totalCommission,
            'paid' => $paidEarnings,
            'unpaid' => $totalEarnings - $paidEarnings,
        ];

        return view('vendor.orders.earnings', compact('items', 'stats'));
    }

    /**
     * Show reseller's own POS orders (Reseller POS Orders page)
     */
    public function resellerOrders(Request $request)
    {
        $reseller = auth()->user();

        // Orders placed via this reseller's POS — identified by vendor_id in order_items
        $orderIds = order_item::where('vendor_id', $reseller->id)
            ->whereNotNull('others')
            ->whereRaw("JSON_EXTRACT(others, '$.is_pos_order') = true")
            ->distinct()
            ->pluck('order_id');

        $query = order::whereIn('id', $orderIds)
            ->where('order_source', 'Reseller POS')
            ->with(['order_items.product']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        // Apply Delivery Filter
        $deliveryBy = $request->get('delivery_by', 'reseller');
        if ($deliveryBy === 'reseller') {
            $query->where('delivery_data->delivery_by', 'reseller');
        } else {
            $query->where(function($q) {
                $q->where('delivery_data->delivery_by', 'admin')
                  ->orWhereNull('delivery_data->delivery_by');
            });
        }

        $allOrders = order::whereIn('id', $orderIds)->where('order_source', 'Reseller POS');
        
        // Filter status counts by delivery type
        $statusOrders = (clone $allOrders)->where(function($q) use ($deliveryBy) {
            if ($deliveryBy === 'reseller') {
                $q->where('delivery_data->delivery_by', 'reseller');
            } else {
                $q->where('delivery_data->delivery_by', 'admin')
                  ->orWhereNull('delivery_data->delivery_by');
            }
        });

        $statusCounts = [
            'all'        => (clone $statusOrders)->count(),
            'pending'    => (clone $statusOrders)->where('status', 'pending')->count(),
            'processing' => (clone $statusOrders)->where('status', 'processing')->count(),
            'delivered'  => (clone $statusOrders)->where('status', 'delivered')->count(),
            'cancelled'  => (clone $statusOrders)->where('status', 'cancelled')->count(),
        ];

        // Overall tab counts for navigation
        $selfDeliveryCount = (clone $allOrders)->where('delivery_data->delivery_by', 'reseller')->count();
        $adminDeliveryCount = (clone $allOrders)->where(function($q) {
            $q->where('delivery_data->delivery_by', 'admin')
              ->orWhereNull('delivery_data->delivery_by');
        })->count();

        // Earnings summary
        $totalEarnings = order_item::where('vendor_id', $reseller->id)
            ->whereIn('order_id', $orderIds)
            ->sum('vendor_earning');
        $paidEarnings = order_item::where('vendor_id', $reseller->id)
            ->whereIn('order_id', $orderIds)
            ->where(function($q) {
                $q->where('vendor_paid', true)
                  ->orWhereHas('order', function($q2) {
                      $q2->where('payment_status', 'paid');
                  });
            })
            ->sum('vendor_earning');

        $hasCourierIntegration = \App\Services\Delivery\DeliveryServiceManager::forProvider('steadfast', $reseller->id) !== null 
            || \App\Services\Delivery\DeliveryServiceManager::forProvider('pathao', $reseller->id) !== null;

        $orders = $query->latest()->paginate(20);

        return view('vendor.orders.reseller', compact(
            'orders', 'statusCounts', 'totalEarnings', 'paidEarnings', 'deliveryBy', 'selfDeliveryCount', 'adminDeliveryCount', 'hasCourierIntegration'
        ));
    }

    /**
     * Show edit form for reseller POS orders (Pending status only)
     */
    public function editResellerOrder($id)
    {
        $reseller = auth()->user();
        $order = order::where('order_source', 'Reseller POS')
            ->whereIn('id', function($q) use ($reseller) {
                $q->select('order_id')->from('order_items')->where('vendor_id', $reseller->id);
            })
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending POS orders can be edited.');
        }

        return view('vendor.orders.reseller_edit', compact('order'));
    }

    /**
     * Update reseller POS order info (Customer details & remarks)
     */
    public function updateResellerOrder(Request $request, $id)
    {
        $reseller = auth()->user();
        $order = order::where('order_source', 'Reseller POS')
            ->whereIn('id', function($q) use ($reseller) {
                $q->select('order_id')->from('order_items')->where('vendor_id', $reseller->id);
            })
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be updated.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        $deliveryData = $order->delivery_data ?? [];
        $deliveryData['amount_paid'] = $request->amount_paid ?? 0;

        $order->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city ?? '',
            'message' => $request->notes,
            'delivery_data' => $deliveryData,
        ]);

        return redirect()->route('vendor.orders.reseller')->with('success', 'Order updated successfully.');
    }

    /**
     * Delete/Cancel reseller POS order (Pending status only)
     */
    public function deleteResellerOrder($id)
    {
        $reseller = auth()->user();
        $order = order::where('order_source', 'Reseller POS')
            ->whereIn('id', function($q) use ($reseller) {
                $q->select('order_id')->from('order_items')->where('vendor_id', $reseller->id);
            })
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending POS orders can be deleted.');
        }

        DB::transaction(function () use ($order) {
            // Restore inventory stock
            $stockService = app(\App\Services\StockManagementService::class);
            foreach ($order->order_items as $item) {
                if ($item->combination_id) {
                    $comb = \App\Models\VariationCombination::find($item->combination_id);
                    if ($comb) {
                        $stockService->updateVariationCombinationStock($comb, $item->quantity, 'restock', $order->id, "Reseller POS Cancelled - Restock");
                    }
                } else {
                    if ($item->product) {
                        $stockService->updateSimpleProductStock($item->product, $item->quantity, 'restock', $order->id, "Reseller POS Cancelled - Restock");
                    }
                }
            }

            // Delete order items and order
            $order->order_items()->delete();
            $order->delete();
        });

        return redirect()->route('vendor.orders.reseller')->with('success', 'Order deleted successfully and stock restored.');
    }

    /**
     * Toggle payment status for the items belonging to the vendor in this order
     */
    public function togglePaymentStatus(Request $request, order $order)
    {
        $vendor = auth()->user();
        $targetStatus = $request->input('payment_status'); // 'paid' or 'unpaid'

        if (!in_array($targetStatus, ['paid', 'unpaid'])) {
            return response()->json(['success' => false, 'message' => 'Invalid payment status option.'], 400);
        }

        // Get the order items belonging to this vendor
        $vendorItems = $order->orderItems()
            ->where(function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id)
                  ->orWhereIn('product_id', function($pq) use ($vendor) {
                      $pq->select('id')
                         ->from('products')
                         ->whereIn('parent_product_id', function($ppq) use ($vendor) {
                             $ppq->select('id')
                                 ->from('products')
                                 ->where('vendor_id', $vendor->id);
                         });
                  });
            })
            ->get();

        if ($vendorItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No matching items found for this vendor in the order.'], 403);
        }

        try {
            DB::transaction(function () use ($vendorItems, $targetStatus, $order, $vendor) {
                $vendorService = app(\App\Services\VendorService::class);

                foreach ($vendorItems as $item) {
                    if ($targetStatus === 'paid') {
                        // Only credit and mark as vendor_paid if the order is delivered
                        if ($order->status === 'delivered') {
                            if (!$item->vendor_paid) {
                                $item->update([
                                    'vendor_paid' => true,
                                    'vendor_paid_at' => now(),
                                ]);

                                if ($item->vendor_earning > 0) {
                                    $vendorService->creditVendorEarning($item);
                                }
                            }
                        }
                    } else { // unpaid
                        if ($item->vendor_paid) {
                            $item->update([
                                'vendor_paid' => false,
                                'vendor_paid_at' => null,
                            ]);

                            // Deduct from vendor wallet/ledger if it was previously credited
                            if ($item->vendor_earning > 0) {
                                // Decrement user wallet balance
                                $vendorUser = \App\Models\User::find($item->vendor_id);
                                if ($vendorUser) {
                                    $vendorUser->decrement('wallet_balance', $item->vendor_earning);
                                }

                                // Create a ledger entry for adjustment/reversal
                                $currentBalance = $vendorService->calculateBalance($item->vendor_id);
                                $newBalance = $currentBalance - $item->vendor_earning;

                                \App\Models\VendorBalanceLedger::create([
                                    'vendor_id' => $item->vendor_id,
                                    'transaction_type' => 'withdrawal', // acts as a debit
                                    'amount' => $item->vendor_earning,
                                    'balance_after' => $newBalance,
                                    'order_id' => $item->order_id,
                                    'order_item_id' => $item->id,
                                    'description' => "Earning reversal from Order #{$item->order_id} - {$item->product->title}",
                                ]);
                            }
                        }
                    }
                }

                // Update the order's payment status
                $order->update([
                    'payment_status' => $targetStatus === 'paid' ? 'paid' : 'pending'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

