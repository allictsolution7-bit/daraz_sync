<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AdminVendorOrderController extends Controller
{
    /**
     * Get vendor orders base query scoped to current admin's vendors and copied products
     */
    public function getVendorOrdersQuery($user = null)
    {
        $user = $user ?: Auth::user();

        $isSuperAdmin = false;
        if ($user) {
            if (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('Super Admin'))) {
                $isSuperAdmin = true;
            } elseif ($user->is_super_admin ?? false) {
                $isSuperAdmin = true;
            }
        }

        if ($isSuperAdmin) {
            // Super Admin sees all vendor items
            return order::whereIn('id', function ($q) {
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

        // Regular Admin: Vendors created by this admin OR products copied from this admin
        $vendorIds = User::where('created_by', $user->id)->pluck('id')->toArray();
        $adminProductIds = Product::where('created_by', $user->id)->pluck('id')->toArray();

        if (empty($vendorIds) && empty($adminProductIds)) {
            return order::whereRaw('1 = 0');
        }

        return order::whereIn('id', function ($q) use ($vendorIds, $adminProductIds) {
            $q->select('order_id')
              ->from('order_items')
              ->where(function ($subQ) use ($vendorIds, $adminProductIds) {
                  $hasCondition = false;

                  if (!empty($vendorIds)) {
                      $subQ->whereIn('vendor_id', $vendorIds)
                           ->orWhereIn('product_id', function ($pq) use ($vendorIds) {
                               $pq->select('id')
                                  ->from('products')
                                  ->whereIn('vendor_id', $vendorIds);
                           });
                      $hasCondition = true;
                  }

                  if (!empty($adminProductIds)) {
                      if ($hasCondition) {
                          $subQ->orWhereIn('product_id', function ($pq) use ($adminProductIds) {
                              $pq->select('id')
                                 ->from('products')
                                 ->whereIn('parent_product_id', $adminProductIds);
                          });
                      } else {
                          $subQ->whereIn('product_id', function ($pq) use ($adminProductIds) {
                              $pq->select('id')
                                 ->from('products')
                                 ->whereIn('parent_product_id', $adminProductIds);
                          });
                      }
                  }
              });
        });
    }

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

        $isVendorOrdersPage = true;

        return view('admin.orders', compact('orders', 'statusCounts', 'assignableStaff', 'isVendorOrdersPage'));
    }

    public function data(Request $request)
    {
        $query = $this->getVendorOrdersQuery()
            ->with(['products', 'fraudCheckResult', 'assignedStaff', 'pendingPurchaseEvent', 'orderItems.vendor', 'orderItems.product']);

        if (!$request->has('order')) {
            $query->orderBy('created_at', 'desc');
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

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

                $cnId = $order->getCourierConsignmentId();
                $cnHtml = $cnId ? '<div class="courier-cn-id" style="font-size: 11px; margin-top: 2px;"><span style="background: #e3f2fd; color: #1565c0; padding: 1px 6px; border-radius: 3px; font-weight: 500;"><i class="fas fa-truck" style="font-size: 10px; margin-right: 3px;"></i>CN: '.e($cnId).'</span></div>' : '';

                $amountClass = 'amount-' . ($order->status ?? 'default');
                $amount = number_format($order->total, 2);
                $productInfo = '<div class="'.$amountClass.'">৳'.$amount.'</div>';

                $titles = [];
                foreach ($order->orderItems as $item) {
                    $vendorName = $item->vendor->name ?? ($item->product->vendor->name ?? 'Vendor');
                    $titles[] = e($item->product->title ?? 'Product') . ' <span class="badge bg-warning text-dark font-weight-bold ms-1" style="font-size: 10px;">Vendor: '.$vendorName.'</span>';
                }
                $productInfo .= implode('<br>', $titles);

                return '<div class="customer-info"><div class="customer-name">'.$name.'</div><div class="customer-phone">'.$phone.'</div>'.$ipHtml.$cnHtml.'<div class="product-info-mobile mt-2">'.$productInfo.'</div><div class="mt-2 d-flex align-items-center gap-2"><a href="'.$editUrl.'" title="View" class="text-primary"><i class="fas fa-eye"></i></a><a href="'.$editUrl.'" title="Edit" class="text-success"><i class="fas fa-edit"></i></a><a href="/admin/pos/print-invoice/'.$order->id.'" title="Print Invoice" class="text-info" target="_blank"><i class="fas fa-file-invoice"></i></a><a href="/admin/pos/print-package-slip/'.$order->id.'" title="Print Package Slip" class="text-warning" target="_blank"><i class="fas fa-box"></i></a><div class="custom-dropdown"><button class="btn btn-sm btn-outline-secondary custom-dropdown-toggle" type="button" title="More Options"><i class="fas fa-ellipsis-v"></i></button><ul class="custom-dropdown-menu"><li><h6 class="custom-dropdown-header"><i class="fas fa-print me-1"></i> Print Options</h6></li><li><a class="custom-dropdown-item print-receipt" href="/admin/pos/print-receipt/'.$order->id.'" target="_blank"><i class="fas fa-receipt"></i> Print Receipt</a></li><li><a class="custom-dropdown-item print-invoice" href="/admin/pos/print-invoice/'.$order->id.'" target="_blank"><i class="fas fa-file-invoice"></i> Print Invoice</a></li><li><a class="custom-dropdown-item print-slip" href="/admin/pos/print-package-slip/'.$order->id.'" target="_blank"><i class="fas fa-box"></i> Print Package Slip</a></li></ul></div></div></div>';
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('phone', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('product_price_and_name', function ($order) {
                $amount = number_format($order->total, 2);
                $html = '<div class="d-flex flex-column gap-1">';
                $html .= '<span class="font-title-sm text-primary fw-bold" style="color: #1f108e; font-size: 15px; letter-spacing: -0.01em;">৳ '.$amount.'</span>';

                $titles = [];
                foreach ($order->orderItems as $item) {
                    $vendorName = $item->vendor->name ?? ($item->product->vendor->name ?? 'Vendor');
                    $titles[] = '<span class="text-on-surface" style="font-size: 13px; color: #191c1e; line-height: 1.3;">- '.e($item->product->title ?? 'Product').' <span class="badge bg-warning-subtle text-warning border px-2 py-0.5" style="font-size: 10px;"><i class="fas fa-store me-1"></i>'.$vendorName.'</span></span>';
                }
                $html .= implode('<br>', $titles);
                $html .= '</div>';
                return $html;
            })
            ->addColumn('courier_column', function ($order) {
                $deliveryData = $order->delivery_data;
                if (!$deliveryData || empty($deliveryData['courier_provider'])) {
                    return '';
                }

                $courierProvider = ucfirst($deliveryData['courier_provider']);
                $cnId = $deliveryData['consignment_id'] ?? $deliveryData['tracking_code'] ?? '';
                $trackingCode = $deliveryData['tracking_code'] ?? $deliveryData['consignment_id'] ?? '';
                $trackUrl = $deliveryData['tracking_url'] ?? ($trackingCode ? 'https://steadfast.com.bd/tl/'.$trackingCode : '#');
                $courierStatus = $order->courier_status ?? null;
                $courierStatusUpdated = $order->courier_status_updated_at ?? null;

                $html = '<div class="d-flex flex-column gap-1">';
                $html .= '<div class="d-flex align-items-center gap-2">';
                $html .= '<span class="fw-bold text-dark" style="font-size: 13.5px;">'.e($courierProvider).'</span>';
                if ($cnId) {
                    $html .= '<a href="'.e($trackUrl).'" target="_blank" class="fw-bold text-decoration-none" style="color: #1f108e; font-size: 11.5px;">Track</a>';
                }
                $html .= '</div>';

                if ($cnId) {
                    $html .= '<span class="text-muted" style="font-size: 11.5px; font-family: monospace;">ID: '.e($cnId).'</span>';
                }

                if ($courierStatus) {
                    $html .= '<div class="courier-status-badge mt-1" data-order-id="'.$order->id.'" data-courier="'.e($deliveryData['courier_provider']).'" style="cursor:pointer; display:inline-block; padding: 2px 7px; border-radius: 4px; font-size: 11px; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 500;">';
                    $html .= '<i class="fas fa-truck me-1"></i><span class="courier-status-text">'.e($courierStatus).'</span></div>';
                }

                $html .= '</div>';
                return $html;
            })
            ->addColumn('status_badge', function ($order) {
                $text = ucfirst(str_replace('_', ' ', $order->status));
                if ($order->status === 'ready_for_delivery') {
                    $text = 'Ready Delivery';
                }

                $statusHtml = '<div class="order-status-container">
                    <span class="order-status-badge order-status-'.e($order->status).' change-status-btn shadow-sm" data-order-id="'.$order->id.'" data-current-status="'.e($order->status).'" data-payment-method="'.e($order->payment_method).'" data-order-source="'.e($order->order_source ?? '').'" style="cursor:pointer;">'.$text.' <i class="fas fa-chevron-down" style="font-size: 11px; opacity: 0.8;"></i></span>';

                $assignee = $order->assignedStaff;
                $assigneeName = $assignee?->name ?? 'Unassigned';
                $assignedLabel = $assignee ? 'Assigned To' : 'Unassigned';

                $statusHtml .= '<div class="assigned-user-profile d-flex align-items-center gap-2 mt-2">
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
                    return '<div class="fraud-check-info fraud-check-column"><span class="'.$badge.' fraud-risk-badge">'.$display.'</span><div class="fraud-success-rate" style="font-size: 12px; font-weight: 600; color: #191c1e;">'.$rate.'</div></div>';
                } elseif ($canCheckFraud) {
                    return '<div class="fraud-check-loading fraud-check-column" data-order-id="'.$order->id.'" data-phone="'.e($order->phone).'"><i class="fas fa-spinner fa-spin text-muted"></i> <small class="text-muted">Checking...</small></div>';
                }
                return '<div class="fraud-check-missing fraud-check-column"><small class="text-muted">No data</small></div>';
            })
            ->addColumn('order_at', function ($order) {
                $ts = $order->created_at->timestamp;
                $date = $order->created_at->format('m-d-y');
                $time = $order->created_at->format('h:i:s A');
                return '<span data-order="'.$ts.'"><div style="line-height: 1.3;"><div style="font-weight: 500; color: #191c1e; font-size: 13.5px;">'.$date.'</div><div style="font-weight: 400; font-size: 12px; color: #464553;">'.$time.'</div></div></span>';
            })
            ->setRowClass(function ($order) {
                return 'order-status-' . $order->status;
            })
            ->rawColumns(['select', 'customer_info', 'product_price_and_name', 'courier_column', 'status_badge', 'fraud_check', 'order_at'])
            ->make(true);
    }
}
