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
        return $query->where('order_source', '!=', 'Reseller POS')
            ->whereNotIn('id', function($q) {
                $q->select('order_id')
                  ->from('order_items')
                  ->where(function($sub) {
                      $sub->whereNotNull('vendor_id')
                          ->orWhereIn('product_id', function($pq) {
                              $pq->select('id')
                                 ->from('products')
                                 ->whereNotNull('vendor_id')
                                 ->orWhereNotNull('parent_product_id');
                          });
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

        return $query->whereIn('id', function($q) use ($adminProductIds) {
            $q->select('order_id')
              ->from('order_items')
              ->whereIn('product_id', $adminProductIds);
        });
    }

    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statusCounts = [
            'all' => '...', 'pending' => '...', 'phone_not_rcv' => '...', 'follow_up' => '...',
            'processing' => '...', 'ready_for_delivery' => '...', 'shipped' => '...',
            'delivered' => '...', 'on_hold' => '...', 'cancelled' => '...'
        ];
        
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
        $statusCounts = [
            'all' => '...', 'pending' => '...', 'phone_not_rcv' => '...', 'follow_up' => '...',
            'processing' => '...', 'ready_for_delivery' => '...', 'shipped' => '...',
            'delivered' => '...', 'on_hold' => '...', 'cancelled' => '...'
        ];
        
        $orders = collect([]);

        $assignableStaff = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $isAssignedOrdersPage = true;

        return view('admin.orders', compact("orders", "statusCounts", "assignableStaff", "isAssignedOrdersPage"));
    }

    public function statusCounts(Request $request)
    {
        $query = order::query();
        if ($request->get('assigned_to_me')) {
            $query->where('assigned_to', Auth::id());
        } elseif ($request->get('vendor_orders')) {
            // Vendor orders counts handled separately if needed
        }

        if ($request->get('vendor_orders')) {
            $baseQuery = app(\App\Http\Controllers\Admin\AdminVendorOrderController::class)->getVendorOrdersQuery();
        } elseif ($request->get('reseller_orders')) {
            $baseQuery = app(\App\Http\Controllers\Admin\ResellerOrderController::class)->getResellerOrdersQuery();
        } else {
            $baseQuery = $this->withoutVendorOrders($this->scopeForAdminUser($query));
        }

        $rawCounts = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return response()->json([
            'all' => array_sum($rawCounts),
            'pending' => $rawCounts['pending'] ?? 0,
            'phone_not_rcv' => $rawCounts['phone_not_rcv'] ?? 0,
            'follow_up' => $rawCounts['follow_up'] ?? 0,
            'processing' => $rawCounts['processing'] ?? 0,
            'ready_for_delivery' => $rawCounts['ready_for_delivery'] ?? 0,
            'shipped' => $rawCounts['shipped'] ?? 0,
            'delivered' => $rawCounts['delivered'] ?? 0,
            'on_hold' => $rawCounts['on_hold'] ?? 0,
            'cancelled' => $rawCounts['cancelled'] ?? 0,
        ]);
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
        $query = order::with([
            'order_items.product',
            'order_items.variationCombination',
            'comboOffer', // needed for combo order title (no extra query per row)
            'fraudCheckResult',
            'assignedStaff',
            'pendingPurchaseEvent',
        ])
        ->select('orders.*'); // ensure only orders columns are selected for DataTables
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
                $hasConsignment = !empty($order->delivery_data['consignment_id']) || !empty($order->delivery_data['tracking_code']);
                
                $hasCourier = !empty($courierProvider) && $hasConsignment;
                $isSteadfast = ($courierProvider === 'steadfast') && $hasConsignment;
                $isPathao = ($courierProvider === 'pathao') && $hasConsignment;
                
                $steadfastSent = $isSteadfast ? 'true' : 'false';
                $pathaoSent = $isPathao ? 'true' : 'false';
                $courierSent = $hasCourier ? 'true' : 'false';
                $ipAddress = $order->ip_address ?? $order->ip ?? ($order->delivery_data['ip'] ?? null);
                return '<input type="checkbox" class="order-checkbox" value="'.$order->id.'" data-steadfast-sent="'.$steadfastSent.'" data-pathao-sent="'.$pathaoSent.'" data-courier-sent="'.$courierSent.'" data-courier-provider="'.e($hasCourier ? $courierProvider : '').'" data-phone="'.e($order->phone).'" data-ip="'.e($ipAddress ?? '').'" />';
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
                    $comboOffer = $order->comboOffer;
                    if ($comboOffer) {
                        $productInfo .= '<div class="combo-order-display"><strong>'.e($comboOffer->title).'</strong> <span class="badge bg-primary ms-1">COMBO</span></div>';
                    }
                } else {
                    $titles = [];
                    foreach ($order->order_items->take(2) as $item) {
                        if ($item->product) {
                            $titles[] = e($item->product->title);
                        }
                    }
                    if ($order->order_items->count() > 2) {
                        $titles[] = '+'.($order->order_items->count() - 2).' more';
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
                $amount = number_format($order->total, 2);
                $html = '<div class="prod-col">';
                $html .= '<span class="prod-price">৳ '.$amount.'</span>';

                if ($order->is_combo_order && $order->combo_offer_id) {
                    // Combo: use eager loaded comboOffer relation (no N+1)
                    $comboTitle = e($order->comboOffer?->title ?? 'Combo Order');
                    $html .= '<div class="prod-name">'.$comboTitle.' <span class="prod-badge-combo">COMBO</span></div>';
                } else {
                    $items = $order->order_items;
                    $shown = $items->take(2);
                    foreach ($shown as $item) {
                        $productTitle = $item->product ? e($item->product->title) : '–';
                        $html .= '<div class="prod-item">';
                        $html .= '<div class="prod-name" title="'.$productTitle.'">'.$productTitle.'</div>';
                        // Variation + Qty pills on one line
                        $pills = [];
                        if ($item->variationCombination && $item->variationCombination->display_name) {
                            foreach (explode(',', $item->variationCombination->display_name) as $attr) {
                                $attr = trim($attr);
                                if ($attr) $pills[] = '<span class="prod-pill prod-pill-var">'.$attr.'</span>';
                            }
                        }
                        if ($item->qty > 0) {
                            $pills[] = '<span class="prod-pill prod-pill-qty">×'.$item->qty.'</span>';
                        }
                        if ($pills) {
                            $html .= '<div class="prod-pills">'.implode('', $pills).'</div>';
                        }
                        $html .= '</div>';
                    }
                    $extra = $items->count() - 2;
                    if ($extra > 0) {
                        $html .= '<div class="prod-more">+'.$extra.' more item'.($extra > 1 ? 's' : '').'</div>';
                    }
                }
                $html .= '</div>';
                return $html;
            })
            ->filterColumn('total', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereIn('id', function($subQ) use ($keyword) {
                        $subQ->select('order_id')
                             ->from('order_items')
                             ->join('products', 'products.id', '=', 'order_items.product_id')
                             ->where('products.title', 'like', "%{$keyword}%");
                    })
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

                // Only the order status pill + assignee — courier info moved to courier_column
                $statusHtml = '<div class="order-status-container">
                    <span class="order-status-badge order-status-'.e($order->status).' change-status-btn shadow-sm" data-order-id="'.$order->id.'" data-current-status="'.e($order->status).'" data-payment-method="'.e($order->payment_method).'" data-order-source="'.e($order->order_source ?? '').'" style="cursor:pointer;">'.$text.' <i class="fas fa-chevron-down" style="font-size: 11px; opacity: 0.8;"></i></span>';

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
                    <img src="'.e($avatarSrc).'" alt="'.e($assigneeName).'" class="user-img" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                    <div class="assigned-user-details">
                        <div class="assigned-user-name" style="margin-bottom:-4px; font-size: 11.5px; font-weight: 600; color: #191c1e;">'.e($assigneeName).'</div>
                        <small class="text-muted" style="font-size: 10px;">'.$assignedLabel.'</small>
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
                    return '<div class="fraud-check-info fraud-check-column"><span class="'.$badge.' fraud-risk-badge">'.$display.'</span><div class="fraud-success-rate" style="font-size: 12px; font-weight: 600; color: #191c1e;">'.$rate.'</div>'.($stale ? '<small class="text-warning" style="font-size: 10.5px;">(Stale - '.$time.')</small>' : '<small class="text-muted" style="font-size: 10.5px;">('.$time.')</small>').'</div>';
                } elseif ($canCheckFraud) {
                    return '<div class="fraud-check-loading fraud-check-column" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'"><i class="fas fa-spinner fa-spin text-muted"></i> <small class="text-muted">Checking...</small></div>';
                }
                return '<div class="fraud-check-missing fraud-check-column"><small class="text-muted">No data</small><button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'" title="Check from cache"><i class="fas fa-database"></i></button></div>';
            })
            ->addColumn('courier_column', function ($order) {
                $deliveryData = $order->delivery_data;
                if (!$deliveryData || empty($deliveryData['courier_provider'])) {
                    return '';
                }

                $courierProvider = ucfirst($deliveryData['courier_provider']);
                $cnId = $deliveryData['consignment_id'] ?? $deliveryData['tracking_code'] ?? '';
                $trackingCode = $deliveryData['tracking_code'] ?? $deliveryData['consignment_id'] ?? '';
                $providerKey = strtolower($deliveryData['courier_provider'] ?? '');
                $trackUrl = $deliveryData['tracking_url'] ?? match($providerKey) {
                    'pathao' => $cnId ? "https://merchant.pathao.com/tracking?consignment_id={$cnId}" : '#',
                    default => $trackingCode ? "https://steadfast.com.bd/tl/{$trackingCode}" : '#',
                };
                $courierStatus = $order->courier_status ?? null;
                $courierStatusUpdated = $order->courier_status_updated_at ?? null;

                $html = '<div class="d-flex flex-column gap-1">';

                // Courier name + Track link
                $html .= '<div class="d-flex align-items-center gap-2">';
                $html .= '<span class="fw-bold text-dark" style="font-size: 13.5px;">'.e($courierProvider).'</span>';
                if ($cnId) {
                    $html .= '<a href="'.e($trackUrl).'" target="_blank" class="fw-bold text-decoration-none" style="color: #1f108e; font-size: 11.5px;">Track</a>';
                }
                $html .= '</div>';

                // CN ID
                if ($cnId) {
                    $html .= '<span class="text-muted" style="font-size: 11.5px; font-family: monospace;">ID: '.e($cnId).'</span>';
                }

                // Courier delivery status badge (Order Created / In Transit / etc.)
                if ($courierStatus) {
                    $statusColors = $this->getCourierStatusColors(strtolower($courierStatus));
                    $html .= '<div class="courier-status-badge mt-1" data-order-id="'.$order->id.'" data-courier="'.e($deliveryData['courier_provider']).'" style="cursor:pointer; display:inline-block; padding: 2px 7px; border-radius: 4px; font-size: 11px; background: '.$statusColors['bg'].'; color: '.$statusColors['text'].'; border: 1px solid '.$statusColors['border'].'; font-weight: 500;">';
                    $html .= '<i class="fas fa-truck me-1"></i><span class="courier-status-text">'.e($courierStatus).'</span>';
                    $html .= '<i class="fas fa-sync-alt ms-1" style="font-size: 10px;"></i></div>';
                    if ($courierStatusUpdated) {
                        $updatedTime = is_string($courierStatusUpdated) ? \Carbon\Carbon::parse($courierStatusUpdated) : $courierStatusUpdated;
                        $html .= '<small class="text-muted d-block" style="font-size: 10px;">Updated '.$updatedTime->diffForHumans().'</small>';
                    }
                }

                $html .= '</div>';
                return $html;
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
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event sent at '.$event->fired_at->format('m-d-y h:i A').'"><i class="fas fa-chart-line" style="font-size: 11px; color: #10b981;"></i> <span style="font-size: 10.5px; color: #10b981; font-weight: 600;">Event Sent</span></div>';
                    } elseif ($event->fire_failed) {
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event failed: '.e($event->fire_error).'"><i class="fas fa-chart-line" style="font-size: 11px; color: #ba1a1a;"></i> <span style="font-size: 10.5px; color: #ba1a1a; font-weight: 600;">Event Failed</span></div>';
                    } else {
                        $eventIcon = '<div style="margin-top: 3px;" title="Purchase event pending"><i class="fas fa-chart-line" style="font-size: 11px; color: #f97316;"></i> <span style="font-size: 10.5px; color: #f97316; font-weight: 600;">Event Pending</span></div>';
                    }
                }

                return '<span data-order="'.$ts.'"><div style="line-height: 1.3;"><div style="font-weight: 500; color: #191c1e; font-size: 13.5px;">'.$date.'</div><div style="font-weight: 400; font-size: 12px; color: #464553;">'.$time.'</div>'.$eventIcon.'</div></span>';
            })
            ->setRowClass(function ($order) {
                return 'order-status-' . $order->status;
            })
            ->rawColumns(['select','customer_info','product_price_and_name','status_badge','fraud_check','courier_column','order_at'])
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
     * Update payment status (paid/pending) via AJAX
     */
    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_status' => 'required|string|in:paid,pending'
        ]);

        $order = order::findOrFail($request->order_id);
        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated to ' . ($request->payment_status === 'paid' ? 'Full Paid' : 'Pending')
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
                    $rawStatus = is_string($response['delivery_status']) ? $response['delivery_status'] : ($response['delivery_status']['status'] ?? 'unknown');
                    
                    if (in_array(strtolower($rawStatus), ['unknown', 'not_found', 'invalid', '404'])) {
                        // Order deleted/invalid on Steadfast portal - clear delivery data to allow re-sending
                        $order->courier_status = null;
                        $order->courier_status_slug = null;
                        $order->courier_status_updated_at = now();
                        $order->courier_status_details = null;
                        $order->delivery_data = null;
                        $order->save();
                        $updated++;
                    } else {
                        $statusText = ucfirst(str_replace('_', ' ', $rawStatus));
                        $order->courier_status = $statusText;
                        $order->courier_status_slug = strtolower(str_replace(' ', '_', $rawStatus));
                        $order->courier_status_updated_at = now();
                        $order->courier_status_details = $response;
                        $order->save();
                        $updated++;
                    }
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
