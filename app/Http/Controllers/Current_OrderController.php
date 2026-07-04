<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\User;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get("status");

        $orders = Order::with(["products", "fraudCheckResult"]);

        if ($status && $status !== '') {
            $orders = $orders->where("status", $status);
        }

        $orders = $orders->orderBy("created_at", "desc")->get();

        // Automatically check fraud for orders that need it
        $this->autoCheckFraudForOrders($orders);

        return view('admin.orders', compact("orders"));
    }

    /**
     * Data endpoint for server-side DataTables
     */
    public function data(Request $request)
    {
        $query = Order::with(['products', 'fraudCheckResult'])
            ->select('orders.*');

        // Optional: default ordering if none provided by DT
        if (!$request->has('order')) {
            $query->orderBy('created_at', 'desc');
        }

        // Custom filters from request (match the UI filters)
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // For courier status, mimic UI: steadfast sent = delivery_data->courier_provider == 'steadfast'
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

        if ($orderType = $request->get('order_type')) {
            if ($orderType === 'combo') {
                $query->where('is_combo_order', true);
            } elseif ($orderType === 'regular') {
                $query->where(function ($q) {
                    $q->whereNull('is_combo_order')->orWhere('is_combo_order', false);
                });
            }
        }

        if ($min = $request->get('amount_min')) {
            $query->where('total', '>=', (float) $min);
        }
        if ($max = $request->get('amount_max')) {
            $query->where('total', '<=', (float) $max);
        }

        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return DataTables::eloquent($query)
            ->addColumn('order_details', function ($order) {
                // Checkbox
                $isSteadfast = isset($order->delivery_data['courier_provider']) && $order->delivery_data['courier_provider'] === 'steadfast';
                $disabled = $isSteadfast ? 'disabled' : '';
                $checkbox = '<input type="checkbox" class="order-checkbox" value="'.$order->id.'" '.$disabled.' />';
                
                // Order ID and SL
                $html = '<div class="order-details-container">';
                $html .= '<div class="order-header">';
                $html .= '<div class="order-checkbox-wrapper">'.$checkbox.'</div>';
                $html .= '<div class="order-id">Order #'.$order->id.'</div>';
                $html .= '<div class="order-date">'.date('d:m:y h:i A', strtotime($order->created_at)).'</div>';
                $html .= '</div>';
                
                // Customer info
                $html .= '<div class="customer-info">';
                $html .= '<div class="customer-name">'.e($order->name).'</div>';
                $html .= '<div class="customer-phone">'.e($order->phone).'</div>';
                $html .= '</div>';
                
                // Product and price info
                $amountClass = $order->total > 1000 ? 'amount-high' : ($order->total > 500 ? 'amount-medium' : 'amount-low');
                $amount = number_format($order->total, 2);
                $html .= '<div class="product-section">';
                $html .= '<div class="'.$amountClass.' order-amount">৳'.$amount.'</div>';
                
                if ($order->is_combo_order && $order->combo_offer_id) {
                    $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
                    $comboSelections = $order->combo_selections;
                    if (is_string($comboSelections)) {
                        $comboSelections = json_decode($comboSelections, true);
                    }
                    if ($comboOffer) {
                        $html .= '<div class="combo-order-display"><strong>'.e($comboOffer->title).'</strong> <span class="badge bg-primary ms-1" style="font-size: 9px; padding: 1px 4px;">COMBO</span>';
                        if ($comboSelections && is_array($comboSelections)) {
                            $html .= '<div class="combo-selections-list">';
                            foreach ($comboSelections as $selection) {
                                $selectedProduct = \App\Models\Product::find($selection['product_id'] ?? null);
                                $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id'] ?? null);
                                if ($selectedProduct) {
                                    $html .= '<div>• '.e($selectedProduct->title);
                                    if ($selectedVariation) {
                                        $html .= ' ('.e($selectedVariation->display_name).')';
                                    }
                                    $html .= '</div>';
                                }
                            }
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }
                } else {
                    $titles = [];
                    foreach ($order->products as $product) {
                        $titles[] = e($product->title);
                    }
                    $html .= '<div class="product-titles">'.implode('<br>', $titles).'</div>';
                }
                $html .= '</div>'; // Close product-section
                $html .= '</div>'; // Close order-details-container
                
                return $html;
            })
            ->addColumn('status_and_note', function ($order) {
                $text = ucfirst(str_replace('_', ' ', $order->status));
                $statusHtml = '<span class="order-status-badge order-status-'.e($order->status).' change-status-btn" data-order-id="'.$order->id.'" data-current-status="'.e($order->status).'" style="cursor:pointer;">'.$text.' <i class="fas fa-chevron-down" style="margin-left: 6px; font-size: 12px; opacity: 0.7;"></i></span>';
                
                $note = $order->admin_note ?? '';
                $noteClass = $note ? '' : 'empty';
                $noteText = $note ?: 'Click to add note...';
                $noteHtml = '<div class="admin-note-cell"><div class="admin-note-text '.$noteClass.'" data-order-id="'.$order->id.'" title="'.e($noteText).'">'.e($noteText).'</div><button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="'.$order->id.'" title="Edit note"><i class="fas fa-edit"></i></button></div>';
                
                return '<div class="status-note-container">'.$statusHtml.'<div class="note-section">'.$noteHtml.'</div></div>';
            })
            ->addColumn('fraud_check', function ($order) {
                $fraudCheckResult = $order->fraudCheckResult;
                $canCheckFraud = !in_array($order->status, ['delivered', 'shipped', 'ready_for_delivery']);
                if ($order->hasFraudCheck() && $fraudCheckResult) {
                    $badge = $fraudCheckResult->risk_level_badge_class;
                    $display = $fraudCheckResult->risk_level_display;
                    $score = $fraudCheckResult->risk_score;
                    $rate = $fraudCheckResult->success_rate_display;
                    $stale = $fraudCheckResult->isStale();
                    $time = $fraudCheckResult->last_checked_at?->diffForHumans();
                    return '<div class="fraud-check-info"><span class="'.$badge.' fraud-risk-badge">'.$display.'</span><div class="fraud-success-rate">'.$rate.'</div>'.($stale ? '<small class="text-warning">(Stale - '.$time.')</small>' : '<small class="text-muted">('.$time.')</small>').'</div>';
                } elseif ($canCheckFraud) {
                    return '<div class="fraud-check-loading"><i class="fas fa-spinner fa-spin text-muted"></i> <small class="text-muted">Checking...</small></div>';
                }
                return '<div class="fraud-check-missing"><small class="text-muted">No data</small><button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'" title="Check from cache"><i class="fas fa-database"></i></button></div>';
            })
            ->addColumn('actions', function ($order) {
                $isSteadfastSent = isset($order->delivery_data['courier_provider']) && $order->delivery_data['courier_provider'] === 'steadfast';
                $sfBtnClass = 'steadfastbtn'.($isSteadfastSent ? ' sent-sf' : '');
                $sfDisabled = $isSteadfastSent ? 'disabled' : '';
                $sfIcon = $isSteadfastSent ? '<i class="fas fa-check"></i>' : '<i class="fas fa-paper-plane"></i>';
                $editUrl = route('admin.orders.edit', $order->id);
                $deleteForm = '<form action="'.route('admin.orders.destroy', $order->id).'" method="POST" class="d-inline">'.csrf_field().method_field('DELETE').'<button type="submit" onclick="return confirm(\'Are you sure?\')" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>';
                return '<button class="'.$sfBtnClass.'" data-order-id="'.$order->id.'" '.$sfDisabled.'>'.$sfIcon.' Steadfast</button><a href="'.$editUrl.'" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a> '.$deleteForm;
            })
            ->setRowClass(function ($order) {
                return 'order-status-' . $order->status;
            })
            ->rawColumns(['order_details','status_and_note','fraud_check','actions'])
            ->toJson();
    }

    /**
     * Load fraud check data from database for all orders
     */
    private function autoCheckFraudForOrders($orders)
    {
        foreach ($orders as $order) {
            // For all orders, try to load existing fraud check data from database
            if (!$order->hasFraudCheck()) {
                try {
                    // Check if we have fraud check data for this phone number in database
                    $existingResult = \App\Models\FraudCheckResult::where('phone', $order->phone)->first();
                    
                    if ($existingResult) {
                        // Link the existing database result to the order
                        $order->update([
                            'fraud_check_result_id' => $existingResult->id,
                            'fraud_check_completed' => true,
                            'fraud_check_at' => now()
                        ]);
                        
                        // Refresh the relationship
                        $order->load('fraudCheckResult');
                    } else {
                        // Only call API for incomplete orders if no data exists in database
                        if (!in_array($order->status, ['delivered', 'shipped', 'ready_for_delivery'])) {
                            $result = \App\Services\FraudChecker\FraudCheckerServiceManager::checkFraud($order->phone);
                            
                            if ($result) {
                                // Link the fraud check result to the order
                                $order->update([
                                    'fraud_check_result_id' => $result->id,
                                    'fraud_check_completed' => true,
                                    'fraud_check_at' => now()
                                ]);
                                
                                // Refresh the relationship
                                $order->load('fraudCheckResult');
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Fraud check failed silently
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(order $order)
    {
        // Users who can manage orders (assign/update), including super_admin
        $users = User::role('super_admin')->orWhereHas('permissions', function($q){
            $q->whereIn('name', ['orders.update','orders.update_status','orders.update_item']);
        })->get();
        return view("admin.orders.edit", compact("order", "users"));
    }


    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Get the old status to check if it changed
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Store old values before updating to calculate products subtotal
        $oldShipping = $order->shipping;
        $oldBkashCharge = $order->bkash_charge ?? 0;
        $oldNagadCharge = $order->nagad_charge ?? 0;
        $oldRocketCharge = $order->rocket_charge ?? 0;
        $oldTotal = $order->total;

        // Calculate products subtotal (remove old shipping and old charges from old total)
        $productsSubtotal = $oldTotal - $oldShipping - $oldBkashCharge - $oldNagadCharge - $oldRocketCharge;

        // Update all editable fields
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->upazila = $request->upazila;
        $order->city = $request->city;
        $order->address = $request->address;
        $order->message = $request->message;
        $order->shipping = $request->shipping;
        $order->payment_method = $request->payment_method;

        // Payment gateway fields
        $order->bkash_number = $request->bkash_number;
        $order->bkash_transaction_id = $request->bkash_transaction_id;
        $order->bkash_charge = $request->bkash_charge ?? 0;
        $order->nagad_number = $request->nagad_number;
        $order->nagad_transaction_id = $request->nagad_transaction_id;
        $order->nagad_charge = $request->nagad_charge ?? 0;
        $order->rocket_number = $request->rocket_number;
        $order->rocket_transaction_id = $request->rocket_transaction_id;
        $order->rocket_charge = $request->rocket_charge ?? 0;

        // Status, payment status, assignment, admin note, courier note
        $order->status = $newStatus;
        $order->payment_status = $request->payment_status;
        $order->assigned_to = $request->assign;
        $order->admin_note = $request->admin_note;
        $order->courier_note = $request->courier_note;

        // Recalculate total with new shipping and new payment charges
        $order->total = $productsSubtotal + $order->shipping + $order->bkash_charge + $order->nagad_charge + $order->rocket_charge;

        // Recalculate total_with_charge (same as total since charges are already included)
        $order->total_with_charge = $order->total;

        // If status has changed, record this in status_updates
        if ($oldStatus !== $newStatus) {
            $statusUpdate = [
                'status' => $newStatus,
                'date' => now(),
                'user_id' => Auth::id(),
                'user_name' => optional(Auth::user())->name,
                'note' => $request->status_note
            ];

            // Get existing updates or create empty array
            $statusUpdates = $order->status_updates ? json_decode($order->status_updates, true) : [];

            // Add new update to the array
            $statusUpdates[] = $statusUpdate;

            // Save back to the order
            $order->status_updates = json_encode($statusUpdates);
        }

        $order->save();

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    /**
     * Update order note via AJAX
     */
    public function updateNote(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'note' => 'nullable|string|max:1000'
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->admin_note = $request->note;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully'
        ]);
    }

    public function updateItem(Request $request, Order $order, $item)
    {
        $orderItem = $order->order_items()->findOrFail($item);
        $product = $orderItem->product;

        // Check if this is a variable product with combinations
        if ($product->product_type === 'variable' && $request->has('combination_id')) {
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'combination_id' => 'required|exists:variation_combinations,id'
            ]);

            // Update order item with variation combination
            $orderItem->update([
                'combination_id' => $request->combination_id,
                'option_id' => null, // Clear old option_id as we're using combinations now
                'quantity' => $request->quantity,
                'price' => $request->price,
                'sub_total' => $request->quantity * $request->price
            ]);
        } else {
            // For simple products
            $request->validate([
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0'
            ]);

            // Update order item for simple product
            $orderItem->update([
                'combination_id' => null,
                'option_id' => null,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'sub_total' => $request->quantity * $request->price
            ]);
        }

        // Recalculate order total with all charges
        $total = $order->order_items->sum('sub_total');
        $order->update([
            'total' => $total + $order->shipping,
            'total_with_charge' => $total + $order->shipping + 
                ($order->bkash_charge ?? 0) + 
                ($order->nagad_charge ?? 0) + 
                ($order->rocket_charge ?? 0)
        ]);

        return redirect()->back()->with('success', 'Order item updated successfully');
    }

    // public function updateItem(Request $request, Order $order, $item)
    // {
    //     $orderItem = $order->order_items()->findOrFail($item);

    //     $request->validate([
    //         'quantity' => 'required|integer|min:1',
    //         'price' => 'required|numeric|min:0',
    //         'option_id' => 'required|exists:variation_options,id'
    //     ]);

    //     // Update order item
    //     $orderItem->update([
    //         'option_id' => $request->option_id,
    //         'quantity' => $request->quantity,
    //         'price' => $request->price,
    //         'sub_total' => $request->quantity * $request->price
    //     ]);

    //     // Recalculate order total
    //     $total = $order->order_items->sum('sub_total');
    //     $order->update([
    //         'total' => $total + $order->shipping
    //     ]);

    //     return redirect()->back()->with('success', 'Order item updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(order $order)
    {
        // delete order
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully');
    }

}
