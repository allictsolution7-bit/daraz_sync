<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\Product;
use App\Models\User;
use App\Models\VariationCombination;
use App\Services\Delivery\DeliveryServiceManager;
use App\Services\PendingPurchaseEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Exclude vendor product orders from main admin orders lists
     */
    protected function withoutVendorOrders($query)
    {
        return $query->whereDoesntHave('orderItems', function ($q) {
            $q->whereNotNull('vendor_id')
              ->orWhereHas('product', function ($pq) {
                  $pq->whereNotNull('vendor_id')
                    ->orWhereNotNull('parent_product_id');
              });
        });
    }

    /**
     * Scope order queries by admin product ownership unless user is Super Admin
     */
    protected function scopeForAdminUser($query, $user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return $query;
        }

        $isSuperAdmin = false;
        if (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('Super Admin'))) {
            $isSuperAdmin = true;
        } elseif ($user->is_super_admin ?? false) {
            $isSuperAdmin = true;
        }

        if ($isSuperAdmin) {
            return $query;
        }

        // Regular admin: only orders containing products created by this admin
        $adminProductIds = Product::where('created_by', $user->id)->pluck('id')->toArray();

        if (empty($adminProductIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('orderItems', function ($q) use ($adminProductIds) {
            $q->whereIn('product_id', $adminProductIds);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = $this->withoutVendorOrders($this->scopeForAdminUser(order::query()));

        // Get counts per status
        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'phone_not_rcv' => (clone $baseQuery)->where('status', 'phone_not_rcv')->count(),
            'follow_up' => (clone $baseQuery)->where('status', 'follow_up')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'ready_for_delivery' => (clone $baseQuery)->where('status', 'ready_for_delivery')->count(),
            'shipped' => (clone $baseQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $baseQuery)->where('status', 'delivered')->count(),
            'on_hold' => (clone $baseQuery)->where('status', 'on_hold')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
        
        // Create empty collection for view compatibility (DataTables will load via AJAX)
        $orders = collect([]);

        $assignableStaff = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.orders', compact("orders", "statusCounts", "assignableStaff"));
    }

    public function asignedorders(Request $request)
    {
        // Base query for counts - only my assigned orders
        $baseQuery = $this->withoutVendorOrders($this->scopeForAdminUser(order::where('assigned_to', Auth::id())));

        // Get counts per status
        $statusCounts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'phone_not_rcv' => (clone $baseQuery)->where('status', 'phone_not_rcv')->count(),
            'follow_up' => (clone $baseQuery)->where('status', 'follow_up')->count(),
            'processing' => (clone $baseQuery)->where('status', 'processing')->count(),
            'ready_for_delivery' => (clone $baseQuery)->where('status', 'ready_for_delivery')->count(),
            'shipped' => (clone $baseQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $baseQuery)->where('status', 'delivered')->count(),
            'on_hold' => (clone $baseQuery)->where('status', 'on_hold')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
        
        // Create empty collection for view compatibility (DataTables will load via AJAX)
        $orders = collect([]);

        $assignableStaff = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $isAssignedOrdersPage = true;

        return view('admin.orders', compact("orders", "statusCounts", "assignableStaff", "isAssignedOrdersPage"));
    }

    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $staffMember = User::where('id', $validated['user_id'])
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })
            ->first();

        if (!$staffMember) {
            return response()->json([
                'success' => false,
                'message' => 'Selected user cannot be assigned orders.',
            ], 422);
        }

        $affected = order::whereIn('id', $validated['order_ids'])
            ->update(['assigned_to' => $staffMember->id]);

        return response()->json([
            'success' => true,
            'message' => "Assigned {$affected} order(s) to {$staffMember->name}.",
        ]);
    }

    /**
     * Data endpoint for server-side DataTables
     */
    public function data(Request $request)
    {
        $query = order::with(['products', 'fraudCheckResult', 'assignedStaff', 'pendingPurchaseEvent']);
        $query = $this->scopeForAdminUser($query);
        $query = $this->withoutVendorOrders($query);

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

        if ($request->get('assigned_to_me')) {
            $query->where('assigned_to', Auth::id());
        }

        return DataTables::eloquent($query)
            ->addColumn('select', function ($order) {
                $courierProvider = $order->delivery_data['courier_provider'] ?? null;
                $isSteadfast = $courierProvider === 'steadfast';
                $isPathao = $courierProvider === 'pathao';
                $hasCourier = !empty($courierProvider);
                $steadfastSent = $isSteadfast ? 'true' : 'false';
                $pathaoSent = $isPathao ? 'true' : 'false';
                $courierSent = $hasCourier ? 'true' : 'false';
                $ipAddress = $order->ip_address ?? $order->ip ?? ($order->delivery_data['ip'] ?? null);
                return '<input type="checkbox" class="order-checkbox" value="'.$order->id.'" data-steadfast-sent="'.$steadfastSent.'" data-pathao-sent="'.$pathaoSent.'" data-courier-sent="'.$courierSent.'" data-courier-provider="'.e($courierProvider).'" data-phone="'.e($order->phone).'" data-ip="'.e($ipAddress ?? '').'" />';
            })
            ->addColumn('customer_info', function ($order) {
                $name = e($order->name);
                $phone = e($order->phone);
                $editUrl = route('admin.orders.edit', $order->id);
                $ipAddress = $order->ip_address ?? ($order->delivery_data['ip'] ?? null);
                $ipHtml = $ipAddress ? '<div class="customer-ip text-muted" style="font-size: 12px;">IP: '.e($ipAddress).'</div>' : '';

                // Courier CN (Consignment) ID
                $cnId = $order->getCourierConsignmentId();
                $cnHtml = $cnId ? '<div class="courier-cn-id" style="font-size: 11px; margin-top: 2px;"><span style="background: #e3f2fd; color: #1565c0; padding: 1px 6px; border-radius: 3px; font-weight: 500;"><i class="fas fa-truck" style="font-size: 10px; margin-right: 3px;"></i>CN: '.e($cnId).'</span></div>' : '';

                // Add product info for mobile responsive (full data, no truncation)
                // Use status-based tint rather than amount tiers
                $amountClass = 'amount-' . ($order->status ?? 'default');
                $amount = number_format($order->total, 2);
                $productInfo = '<div class="'.$amountClass.'">৳'.$amount.'</div>';
                
                if ($order->is_combo_order && $order->combo_offer_id) {
                    $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
                    $comboSelections = $order->combo_selections;
                    if (is_string($comboSelections)) {
                        $comboSelections = json_decode($comboSelections, true);
                    }
                    if ($comboOffer) {
                        $productInfo .= '<div class="combo-order-display"><strong>'.e($comboOffer->title).'</strong> <span class="badge bg-primary ms-1">COMBO</span>';
                        if ($comboSelections && is_array($comboSelections)) {
                            $productInfo .= '<div class="combo-selections-list" style="font-size: 12px; color: #666; margin-top: 2px;">';
                            foreach ($comboSelections as $selection) {
                                $selectedProduct = \App\Models\Product::find($selection['product_id'] ?? null);
                                $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id'] ?? null);
                                if ($selectedProduct) {
                                    $productInfo .= '<div>• '.e($selectedProduct->title);
                                    if ($selectedVariation) {
                                        $productInfo .= ' ('.e($selectedVariation->display_name).')';
                                    }
                                    $productInfo .= '</div>';
                                }
                            }
                            $productInfo .= '</div>';
                        }
                        $productInfo .= '</div>';
                    }
                } else {
                    $titles = [];
                    foreach ($order->products as $product) {
                        $titles[] = e($product->title);
                    }
                    $productInfo .= implode('<br>', $titles);
                }
                
                return '<div class="customer-info"><div class="customer-name">'.$name.'</div><div class="customer-phone">'.$phone.'</div>'.$ipHtml.$cnHtml.'<div class="product-info-mobile mt-2">'.$productInfo.'</div><div class="mt-2 d-flex align-items-center gap-2"><a href="'.$editUrl.'" title="View" class="text-primary"><i class="fas fa-eye"></i></a><a href="'.$editUrl.'" title="Edit" class="text-success"><i class="fas fa-edit"></i></a><a href="/admin/pos/print-invoice/'.$order->id.'" title="Print Invoice" class="text-info" target="_blank"><i class="fas fa-file-invoice"></i></a><a href="/admin/pos/print-package-slip/'.$order->id.'" title="Print Package Slip" class="text-warning" target="_blank"><i class="fas fa-box"></i></a><div class="custom-dropdown"><button class="btn btn-sm btn-outline-secondary custom-dropdown-toggle" type="button" title="More Options"><i class="fas fa-ellipsis-v"></i></button><ul class="custom-dropdown-menu"><li><h6 class="custom-dropdown-header"><i class="fas fa-print me-1"></i> Print Options</h6></li><li><a class="custom-dropdown-item print-receipt" href="/admin/pos/print-receipt/'.$order->id.'" target="_blank"><i class="fas fa-receipt"></i> Print Receipt</a></li><li><a class="custom-dropdown-item print-invoice" href="/admin/pos/print-invoice/'.$order->id.'" target="_blank"><i class="fas fa-file-invoice"></i> Print Invoice</a></li><li><a class="custom-dropdown-item print-slip" href="/admin/pos/print-package-slip/'.$order->id.'" target="_blank"><i class="fas fa-box"></i> Print Package Slip</a></li><li><hr class="custom-dropdown-divider"></li><li><h6 class="custom-dropdown-header"><i class="fas fa-download me-1"></i> Download Options</h6></li><li><a class="custom-dropdown-item download-receipt" href="/admin/pos/download-receipt/'.$order->id.'"><i class="fas fa-download"></i> Download Receipt</a></li><li><a class="custom-dropdown-item download-invoice" href="/admin/pos/download-invoice/'.$order->id.'"><i class="fas fa-download"></i> Download Invoice</a></li><li><a class="custom-dropdown-item download-slip" href="/admin/pos/download-package-slip/'.$order->id.'"><i class="fas fa-download"></i> Download Package Slip</a></li></ul></div></div></div>';
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('product_price_and_name', function ($order) {
                $amountClass = 'amount-' . ($order->status ?? 'default');
                $amount = number_format($order->total, 2);
                $html = '<div class="'.$amountClass.'">৳'.$amount.'</div> - ';

                if ($order->is_combo_order && $order->combo_offer_id) {
                    $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
                    $comboSelections = $order->combo_selections;
                    if (is_string($comboSelections)) {
                        $comboSelections = json_decode($comboSelections, true);
                    }
                    if ($comboOffer) {
                        $html .= '<div class="combo-order-display"><strong>'.e($comboOffer->title).'</strong> <span class="badge bg-primary ms-1">COMBO</span>';
                        if ($comboSelections && is_array($comboSelections)) {
                            $html .= '<div class="combo-selections-list" style="font-size: 12px; color: #666; margin-top: 2px;">';
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
                    $html .= implode('<br>', $titles);
                }

                return $html;
            })
            ->filterColumn('total', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    // Search in related products
                    $q->whereHas('products', function($subQ) use ($keyword) {
                        $subQ->where('title', 'like', "%{$keyword}%");
                    })
                    // Search in combo offers
                    ->orWhereHas('comboOffer', function($subQ) use ($keyword) {
                        $subQ->where('title', 'like', "%{$keyword}%");
                    });
                });
            })
            ->addColumn('status_badge', function ($order) {
                $text = ucfirst(str_replace('_', ' ', $order->status));
                // Custom display text for specific statuses
                if ($order->status === 'ready_for_delivery') {
                    $text = 'Ready Delivery';
                }

                $statusHtml = '<div class="order-status-container">
                    <span class="order-status-badge order-status-'.e($order->status).' change-status-btn" data-order-id="'.$order->id.'" data-current-status="'.e($order->status).'" data-payment-method="'.e($order->payment_method).'" data-order-source="'.e($order->order_source ?? '').'" style="cursor:pointer;">'.$text.' <i class="fas fa-chevron-down" style="font-size: 12px; opacity: 0.7;"></i></span>';
                
                // Add courier status if order is sent to courier
                if (isset($order->delivery_data['courier_provider'])) {
                    $courierProvider = $order->delivery_data['courier_provider'];
                    $courierStatus = $order->courier_status;
                    $courierStatusUpdated = $order->courier_status_updated_at;
                    
                    $statusHtml .= '<div class="courier-status-container mt-1">
                        <div class="courier-status-display">';
                    
                    if ($courierStatus) {
                        // Get colors based on status
                        $statusColors = $this->getCourierStatusColors(strtolower($courierStatus));
                        
                        $statusHtml .= '<div class="courier-status-badge" data-order-id="'.$order->id.'" data-courier="'.$courierProvider.'" style="cursor: pointer; display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; background: '.$statusColors['bg'].'; color: '.$statusColors['text'].'; border: 1px solid '.$statusColors['border'].'; font-weight: 500;">
                            <i class="fas fa-truck me-1"></i>
                            <span class="courier-status-text">'.e($courierStatus).'</span>
                            <i class="fas fa-sync-alt ms-1" style="font-size: 10px;"></i>
                        </div>';
                        
                        if ($courierStatusUpdated) {
                            $updatedTime = is_string($courierStatusUpdated) ? \Carbon\Carbon::parse($courierStatusUpdated) : $courierStatusUpdated;
                            $statusHtml .= '<small class="text-muted d-block" style="font-size: 10px;margin-top:-3px;">Updated '.$updatedTime->diffForHumans().'</small>';
                        }
                    } else {
                        $statusHtml .= '<div class="courier-status-badge" data-order-id="'.$order->id.'" data-courier="'.$courierProvider.'" style="cursor: pointer; display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; background: #fff3e0; color: #f57c00; border: 1px solid #ffb74d; font-weight: 500;">
                            <i class="fas fa-truck me-1"></i>
                            <span class="courier-status-text">Check Status</span>
                            <i class="fas fa-sync-alt ms-1" style="font-size: 10px;"></i>
                        </div>';
                    }
                    
                    $statusHtml .= '<div class="courier-provider-name" style="font-size: 10px;color: #666; margin-top:-7px;">
                        <i class="fas fa-shipping-fast me-1"></i>
                        '.ucfirst($courierProvider).'
                    </div>
                </div>
            </div>';
                }
                
                $assignee = $order->assignedStaff;
                $assigneeName = $assignee?->name ?? 'Unassigned';
                $profilePhoto = $assignee?->profile_photo_path ?? $assignee?->image ?? null;
                if ($profilePhoto) {
                    if (Str::startsWith($profilePhoto, ['http://', 'https://', '/'])) {
                        $avatarSrc = $profilePhoto;
                    } else {
                        $avatarSrc = asset($profilePhoto);
                    }
                } else {
                    $avatarSrc = asset('assets/img/man.png');
                }
                $assignedLabel = $assignee ? 'Assigned To' : 'Unassigned';

                $statusHtml .= '<div class="assigned-user-profile d-flex align-items-center gap-2 mt-2">
                    <img src="'.e($avatarSrc).'" alt="'.e($assigneeName).'" class="user-img">
                    <div class="assigned-user-details">
                        <div class="assigned-user-name" style="margin-bottom:-4px;">'.e($assigneeName).'</div>
                        <small class="text-muted">'.$assignedLabel.'</small>
                    </div>
                </div>';

                $statusHtml .= '</div>';
                return $statusHtml;
            })
            ->addColumn('fraud_check', function ($order) {
                $fraudCheckResult = $order->fraudCheckResult;
                $canCheckFraud = !in_array($order->status, ['delivered', 'shipped', 'ready_for_delivery']);
                if ($order->hasFraudCheck() && $fraudCheckResult) {
                    $badge = $fraudCheckResult->risk_level_badge_class;
                    $display = $fraudCheckResult->risk_level_display;
                    $rate = $fraudCheckResult->success_rate_display;
                    $stale = $fraudCheckResult->isStale();
                    $time = $fraudCheckResult->last_checked_at?->diffForHumans();
                    return '<div class="fraud-check-info fraud-check-column"><span class="'.$badge.' fraud-risk-badge">'.$display.'</span><div class="fraud-success-rate">'.$rate.'</div>'.($stale ? '<small class="text-warning">(Stale - '.$time.')</small>' : '<small class="text-muted">('.$time.')</small>').'</div>';
                } elseif ($canCheckFraud) {
                    return '<div class="fraud-check-loading fraud-check-column" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'"><i class="fas fa-spinner fa-spin text-muted"></i> <small class="text-muted">Checking...</small></div>';
                }
                return '<div class="fraud-check-missing fraud-check-column"><small class="text-muted">No data</small><button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'" title="Check from cache"><i class="fas fa-database"></i></button></div>';
            })
            ->addColumn('order_at', function ($order) {
                $ts = $order->created_at->timestamp;
                $date = $order->created_at->format('m-d-y');
                $time = $order->created_at->format('h:i:s A');

                // Purchase event status icon
                $eventIcon = '';
                $event = $order->pendingPurchaseEvent;
                if ($event) {
                    if ($event->isFired()) {
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event sent at '.$event->fired_at->format('m-d-y h:i A').'"><i class="fas fa-chart-line" style="font-size: 11px; color: #2e7d32;"></i> <span style="font-size: 10px; color: #2e7d32; font-weight: 500;">Event Sent</span></div>';
                    } elseif ($event->fire_failed) {
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event failed: '.e($event->fire_error).'"><i class="fas fa-chart-line" style="font-size: 11px; color: #c62828;"></i> <span style="font-size: 10px; color: #c62828; font-weight: 500;">Event Failed</span></div>';
                    } else {
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event pending"><i class="fas fa-chart-line" style="font-size: 11px; color: #f57c00;"></i> <span style="font-size: 10px; color: #f57c00; font-weight: 500;">Event Pending</span></div>';
                    }
                }

                return '<span data-order="'.$ts.'"><div style="line-height: 1.2;"><div style="font-weight: 500; color: #333;">'.$date.'</div><div style="font-weight: 500;font-size: 12px; color: #666;">'.$time.'</div>'.$eventIcon.'</div></span>';
            })
            ->addColumn('note', function ($order) {
                $note = $order->admin_note ?? 'Add Note';
                return '<span class="editable-note" data-order-id="'.$order->id.'">'.e($note).'</span>';
            })
            ->setRowClass(function ($order) {
                return 'order-status-' . $order->status;
            })
            ->rawColumns(['select','customer_info','product_price_and_name','status_badge','fraud_check','order_at','note'])
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
        $order->load(['order_items.product', 'order_items.variationCombination']);

        // Users who can manage orders (assign/update), including admin and super_admin
        try {
            $users = User::role(['admin', 'super_admin', 'super admin'])->orWhereHas('permissions', function($q){
                $q->whereIn('name', ['orders.update','orders.update_status','orders.update_item']);
            })->get();
        } catch (\Throwable $e) {
            $users = User::all();
        }

        return view("admin.orders.edit", compact("order", "users"));
    }


    public function update(Request $request, $id)
    {
        $order = order::with('order_items')->findOrFail($id);

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

        // Payment type fields (Full Paid, Partial, Due)
        $order->payment_type = $request->payment_type ?? 'full_paid';
        $order->paid_amount = $request->paid_amount ?? 0;
        $order->due_amount = $request->due_amount ?? 0;

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

        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            $this->restoreStockForOrder($order);
        }

        $order->save();

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    /**
     * Return allocated stock when an order is cancelled.
     */
    protected function restoreStockForOrder(order $order): void
    {
        $order->loadMissing('order_items');

        foreach ($order->order_items as $orderItem) {
            if ($orderItem->combination_id) {
                VariationCombination::where('id', $orderItem->combination_id)
                    ->increment('stock_quantity', $orderItem->quantity);
            } else {
                Product::where('id', $orderItem->product_id)
                    ->increment('quantity', $orderItem->quantity);
            }
        }
    }

    public function updateItem(Request $request, order $order, $item)
    {
        $orderItem = $order->order_items()->findOrFail($item);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'combination_id' => ['nullable', 'integer'],
        ]);

        $product = Product::with('variationCombinations')->findOrFail($validated['product_id']);

        if ($product->product_type === 'variable') {
            $request->validate([
                'combination_id' => [
                    'required',
                    Rule::exists('variation_combinations', 'id')->where('product_id', $product->id),
                ],
            ]);
            $combinationId = (int) $request->combination_id;
        } else {
            $combinationId = null;
        }

        $orderItem->update([
            'product_id' => $product->id,
            'combination_id' => $combinationId,
            'option_id' => null,
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'sub_total' => $validated['quantity'] * $validated['price'],
        ]);

        $this->recalculateOrderTotals($order);

        return redirect()->back()->with('success', 'Order item updated successfully');
    }

    public function storeItem(Request $request, order $order)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'combination_id' => ['nullable', 'integer'],
        ]);

        $product = Product::with('variationCombinations')->findOrFail($validated['product_id']);

        if ($product->product_type === 'variable') {
            $request->validate([
                'combination_id' => [
                    'required',
                    Rule::exists('variation_combinations', 'id')->where('product_id', $product->id),
                ],
            ]);
            $combinationId = (int) $request->combination_id;
        } else {
            $combinationId = null;
        }

        $order->order_items()->create([
            'product_id' => $product->id,
            'combination_id' => $combinationId,
            'option_id' => null,
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'sub_total' => $validated['quantity'] * $validated['price'],
        ]);

        $this->recalculateOrderTotals($order);

        return redirect()->back()->with('success', 'Product added to order successfully');
    }

    public function destroyItem(order $order, $item)
    {
        $orderItem = $order->order_items()->findOrFail($item);
        $orderItem->delete();

        $this->recalculateOrderTotals($order);

        return redirect()->back()->with('success', 'Order item removed successfully');
    }

    public function productOptions(Product $product)
    {
        $product->load(['variationCombinations' => function ($query) {
            $query->orderBy('sort_order')->orderBy('id');
        }]);

        $variationData = [];
        if ($product->product_type === 'variable') {
            $variationData = $product->variationCombinations->map(function ($combination) {
                return [
                    'id' => $combination->id,
                    'display_name' => $combination->display_name,
                    'regular_price' => $combination->regular_price,
                    'offer_price' => $combination->offer_price,
                    'effective_price' => $combination->effective_price,
                    'has_offer' => $combination->hasOffer(),
                ];
            })->values();
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'product_type' => $product->product_type,
                'regular_price' => $product->old_price,
                'offer_price' => $product->offer,
                'default_price' => $product->offer ?? $product->old_price ?? 0,
                'variations' => $variationData,
                'thumb_image_url' => $product->thumb_image ? asset('storage/' . $product->thumb_image) : null,
            ],
        ]);
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

        $order = order::findOrFail($request->order_id);
        $order->admin_note = $request->note;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully'
        ]);
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|in:pending,phone_not_rcv,follow_up,processing,ready_for_delivery,delivered,on_hold,shipped,cancelled'
        ]);

        $order = order::findOrFail($request->order_id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update status
        $order->status = $newStatus;

        // Record status change
        if ($oldStatus !== $newStatus) {
            $statusUpdate = [
                'status' => $newStatus,
                'date' => now(),
                'user_id' => Auth::id(),
                'user_name' => optional(Auth::user())->name,
                'note' => $request->get('status_note', '')
            ];

            $statusUpdates = $order->status_updates ? json_decode($order->status_updates, true) : [];
            $statusUpdates[] = $statusUpdate;
            $order->status_updates = json_encode($statusUpdates);
        }

        // If cancelling from the list view, restore allocated stock just like the edit page flow
        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            $this->restoreStockForOrder($order);
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }

    /**
     * Fire pending purchase event for a COD or offline order.
     * This sends the purchase event to PixelFly after order confirmation.
     */
    public function firePurchaseEvent(Request $request, PendingPurchaseEventService $pendingPurchaseEventService)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = order::findOrFail($request->order_id);

        // Check if this is a COD order or an offline source order
        $isCod = $order->payment_method === 'cod';
        $isOfflineSource = $pendingPurchaseEventService->isOfflineSource($order->order_source ?? '');

        if (!$isCod && !$isOfflineSource) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not eligible for delayed purchase events. Purchase event was already fired via browser.'
            ], 400);
        }

        // Check if there's a pending event
        if (!$pendingPurchaseEventService->hasPendingEvent($order)) {
            $pendingEvent = $pendingPurchaseEventService->getPendingEvent($order);
            if ($pendingEvent && $pendingEvent->isFired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Purchase event was already fired for this order.'
                ], 400);
            }

            // Create a new pending event if none exists
            // Use appropriate method based on order type
            // Force=true to allow manual creation from admin even if source not enabled in settings
            if ($isOfflineSource) {
                $pendingPurchaseEventService->storeOfflineEvent($order, true);
            } else {
                $pendingPurchaseEventService->storePendingEvent($order);
            }
        }

        // Fire the event
        $success = $pendingPurchaseEventService->fireEventForOrder($order);

        if ($success) {
            $methodLabel = \App\Models\DelayedEventSetting::getFiringMethodLabel();
            return response()->json([
                'success' => true,
                'message' => "Purchase event fired successfully to {$methodLabel}!"
            ]);
        }

        $pendingEvent = $pendingPurchaseEventService->getPendingEvent($order);
        $errorMessage = $pendingEvent?->fire_error ?? 'Unknown error';

        return response()->json([
            'success' => false,
            'message' => 'Failed to fire purchase event: ' . $errorMessage
        ], 500);
    }

    /**
     * Check if an order has a pending purchase event.
     */
    public function checkPendingPurchaseEvent(Request $request, PendingPurchaseEventService $pendingPurchaseEventService)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = order::findOrFail($request->order_id);
        $pendingEvent = $pendingPurchaseEventService->getPendingEvent($order);

        return response()->json([
            'has_pending_event' => $pendingEvent && $pendingEvent->isPending(),
            'was_fired' => $pendingEvent && $pendingEvent->isFired(),
            'fire_failed' => $pendingEvent?->fire_failed ?? false,
            'fire_error' => $pendingEvent?->fire_error,
            'fired_at' => $pendingEvent?->fired_at?->toISOString(),
        ]);
    }

    /**
     * Bulk update order statuses
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|string|in:pending,phone_not_rcv,follow_up,processing,ready_for_delivery,delivered,on_hold,shipped,cancelled',
        ]);

        $newStatus = $request->status;
        $updated = 0;
        $stockRestored = 0;

        // If cancelling, we need to restore stock for each order individually
        if ($newStatus === 'cancelled') {
            $orders = order::whereIn('id', $request->order_ids)
                ->where('status', '!=', 'cancelled') // Only process non-cancelled orders
                ->get();

            foreach ($orders as $order) {
                // Restore stock before cancelling
                $this->restoreStockForOrder($order);
                $stockRestored++;

                // Update status
                $order->status = $newStatus;
                $order->updated_at = now();
                $order->save();
                $updated++;
            }

            // Also update any already-cancelled orders (just for status consistency)
            $alreadyCancelled = order::whereIn('id', $request->order_ids)
                ->where('status', 'cancelled')
                ->update(['updated_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => "Status updated for {$updated} order(s). Stock restored for {$stockRestored} order(s).",
                'updated' => $updated,
                'stock_restored' => $stockRestored,
            ]);
        }

        // For non-cancel status changes, use bulk update
        $updated = order::whereIn('id', $request->order_ids)->update([
            'status' => $newStatus,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status updated for {$updated} order(s).",
            'updated' => $updated,
        ]);
    }

    /**
     * Bulk refresh courier status for orders in transit.
     * Updates status for all orders that have been sent to courier but not yet delivered/cancelled.
     */
    public function bulkRefreshCourierStatus()
    {
        // Get all orders that have been sent to courier but not yet delivered/cancelled
        $finalStatuses = ['delivered', 'cancelled', 'returned', 'partial_delivered', 'return'];

        $orders = order::whereNotNull('delivery_data->courier_provider')
            ->where(function ($query) use ($finalStatuses) {
                $query->whereNull('courier_status_slug')
                    ->orWhereNotIn('courier_status_slug', $finalStatuses);
            })
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No orders found that need status refresh.',
                'updated' => 0,
                'failed' => 0,
            ]);
        }

        $updated = 0;
        $failed = 0;
        $errors = [];

        foreach ($orders as $order) {
            try {
                $courierProvider = $order->delivery_data['courier_provider'] ?? null;

                if (!$courierProvider) {
                    continue;
                }

                $trackingId = $order->delivery_data['consignment_id']
                    ?? $order->delivery_data['tracking_code']
                    ?? $order->id;

                if (!$trackingId) {
                    $failed++;
                    continue;
                }

                $delivery = \App\Services\Delivery\DeliveryServiceManager::forProvider($courierProvider);
                $response = $delivery->trackOrder($trackingId);

                // Handle Steadfast response
                if ($courierProvider === 'steadfast' && isset($response['delivery_status'])) {
                    $statusText = is_string($response['delivery_status'])
                        ? ucfirst(str_replace('_', ' ', $response['delivery_status']))
                        : ($response['delivery_status']['status'] ?? 'Unknown');

                    $order->courier_status = $statusText;
                    $order->courier_status_slug = is_string($response['delivery_status'])
                        ? $response['delivery_status']
                        : strtolower(str_replace(' ', '_', $statusText));
                    $order->courier_status_updated_at = now();
                    $order->courier_status_details = $response;
                    $order->save();
                    $updated++;
                }
                // Handle Pathao response
                elseif ($courierProvider === 'pathao' && isset($response['data']['order_status'])) {
                    $statusText = $response['data']['order_status'];
                    $statusSlug = $response['data']['order_status_slug'] ?? strtolower(str_replace(' ', '_', $statusText));

                    $order->courier_status = $statusText;
                    $order->courier_status_slug = $statusSlug;
                    $order->courier_status_updated_at = now();
                    $order->courier_status_details = $response;
                    $order->save();
                    $updated++;
                } else {
                    $failed++;
                }

                // Small delay to avoid rate limiting
                usleep(100000); // 100ms delay between API calls

            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Order #{$order->id}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Courier status refreshed. Updated: {$updated}, Failed: {$failed}",
            'updated' => $updated,
            'failed' => $failed,
            'total' => $orders->count(),
            'errors' => array_slice($errors, 0, 5), // Return first 5 errors only
        ]);
    }

    /**
     * Delete multiple orders
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|json'
        ]);

        $orderIds = json_decode($request->order_ids, true);
        
        if (!is_array($orderIds) || empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid order IDs provided'
            ]);
        }

        $deletedCount = order::whereIn('id', $orderIds)->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully deleted {$deletedCount} orders"
        ]);
    }

    /**
     * Export selected orders
     */
    public function exportSelected(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|json'
        ]);

        $orderIds = json_decode($request->order_ids, true);
        
        if (!is_array($orderIds) || empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid order IDs provided'
            ]);
        }

        $orders = order::whereIn('id', $orderIds)->with(['products'])->get();

        // Generate CSV content
        $csvData = [];
        $csvData[] = ['Order ID', 'Customer Name', 'Phone', 'Total', 'Status', 'Created At', 'Products'];

        foreach ($orders as $order) {
            $productNames = $order->products->pluck('title')->implode(', ');
            $csvData[] = [
                $order->id,
                $order->name,
                $order->phone,
                $order->total,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s'),
                $productNames
            ];
        }

        $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function recalculateOrderTotals(order $order): void
    {
        $order->loadMissing('order_items');

        $lineTotal = $order->order_items->sum('sub_total');
        $shipping = (float) ($order->shipping ?? 0);
        $bkashCharge = (float) ($order->bkash_charge ?? 0);
        $nagadCharge = (float) ($order->nagad_charge ?? 0);
        $rocketCharge = (float) ($order->rocket_charge ?? 0);

        $newTotal = $lineTotal + $shipping + $bkashCharge + $nagadCharge + $rocketCharge;

        $order->update([
            'total' => $newTotal,
            'total_with_charge' => $newTotal,
        ]);
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

    /**
     * Get courier status colors based on status
     */
    private function getCourierStatusColors($status)
    {
        $statusColors = [
            // Delivered - Green (Success)
            'delivered' => ['bg' => '#e8f5e9', 'text' => '#2e7d32', 'border' => '#81c784'],
            'delivered approval pending' => ['bg' => '#e8f5e9', 'text' => '#2e7d32', 'border' => '#81c784'],
            'partial delivered' => ['bg' => '#e8f5e9', 'text' => '#388e3c', 'border' => '#81c784'],
            
            // In Transit - Cyan/Teal (Moving)
            'in transit' => ['bg' => '#e0f7fa', 'text' => '#00838f', 'border' => '#4dd0e1'],
            'shipped' => ['bg' => '#e0f7fa', 'text' => '#00838f', 'border' => '#4dd0e1'],
            'out for delivery' => ['bg' => '#e0f7fa', 'text' => '#00695c', 'border' => '#4dd0e1'],
            
            // Processing - Blue (In Progress)
            'processing' => ['bg' => '#e3f2fd', 'text' => '#1565c0', 'border' => '#90caf9'],
            'picked up' => ['bg' => '#e3f2fd', 'text' => '#1565c0', 'border' => '#90caf9'],
            'order created' => ['bg' => '#e3f2fd', 'text' => '#1976d2', 'border' => '#90caf9'],
            
            // Pending/Waiting - Orange/Amber (Waiting)
            'pending' => ['bg' => '#fff3e0', 'text' => '#e65100', 'border' => '#ffb74d'],
            'in review' => ['bg' => '#fff3e0', 'text' => '#ef6c00', 'border' => '#ffb74d'],
            'ready to ship' => ['bg' => '#fff3e0', 'text' => '#e65100', 'border' => '#ffb74d'],
            
            // Hold/Unknown - Purple (Issue)
            'hold' => ['bg' => '#f3e5f5', 'text' => '#6a1b9a', 'border' => '#ba68c8'],
            'unknown' => ['bg' => '#f3e5f5', 'text' => '#6a1b9a', 'border' => '#ba68c8'],
            'unknown approval pending' => ['bg' => '#f3e5f5', 'text' => '#6a1b9a', 'border' => '#ba68c8'],
            
            // Cancelled/Failed - Red (Error)
            'cancelled' => ['bg' => '#ffebee', 'text' => '#c62828', 'border' => '#ef5350'],
            'cancelled approval pending' => ['bg' => '#ffebee', 'text' => '#c62828', 'border' => '#ef5350'],
            'pickup cancel' => ['bg' => '#ffebee', 'text' => '#c62828', 'border' => '#ef5350'],
            'pickup cancelled' => ['bg' => '#ffebee', 'text' => '#c62828', 'border' => '#ef5350'],
            'failed' => ['bg' => '#ffebee', 'text' => '#c62828', 'border' => '#ef5350'],
            'return' => ['bg' => '#ffebee', 'text' => '#d32f2f', 'border' => '#ef5350'],
        ];
        
        return $statusColors[$status] ?? ['bg' => '#e3f2fd', 'text' => '#1976d2', 'border' => '#90caf9'];
    }

}
