<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\order_item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ResellerOrderController extends Controller
{
    protected function getResellerOrdersQuery()
    {
        $admin = Auth::user();
        $isSuperAdmin = $admin && method_exists($admin, 'hasRole') &&
            ($admin->hasRole('super_admin') || $admin->hasRole('super admin') || $admin->hasRole('Super Admin'));

        if ($isSuperAdmin) {
            return order::where('order_source', 'Reseller POS');
        }

        $resellerIds = User::where('created_by', $admin->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'reseller'))
            ->pluck('id')
            ->toArray();

        if (empty($resellerIds)) {
            return order::whereRaw('1 = 0');
        }

        return order::where('order_source', 'Reseller POS')
            ->whereIn('id', function($q) use ($resellerIds) {
                $q->select('order_id')
                  ->from('order_items')
                  ->whereIn(DB::raw("CAST(JSON_UNQUOTE(JSON_EXTRACT(others, '$.reseller_id')) AS UNSIGNED)"), $resellerIds);
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

        $isResellerOrdersPage = true;

        return view('admin.orders', compact('orders', 'statusCounts', 'assignableStaff', 'isResellerOrdersPage'));
    }

    public function data(Request $request)
    {
        $query = $this->getResellerOrdersQuery()
            ->with(['order_items.product', 'order_items.vendor', 'assignedStaff', 'fraudCheckResult']);

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
                // Return data structure for JS customization in Datatables
                return '';
            })
            ->addColumn('product_price_and_name', function ($order) {
                $amount = number_format($order->total, 2);
                $html = '<div class="d-flex flex-column gap-1">';
                $html .= '<span class="font-title-sm text-primary fw-bold" style="color: #1f108e; font-size: 15px; letter-spacing: -0.01em;">৳ '.$amount.'</span>';

                $titles = [];
                foreach ($order->order_items as $item) {
                    $resellerName = $item->vendor->name ?? 'Reseller';
                    $titles[] = '<span class="text-on-surface" style="font-size: 13px; color: #191c1e; line-height: 1.3;">- '.e($item->product->title ?? 'Product').' <span class="badge bg-warning-subtle text-warning border px-2 py-0.5" style="font-size: 10px;"><i class="fas fa-store me-1"></i>'.$resellerName.'</span></span>';
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
            ->toJson();
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate(['status' => 'required|string']);
        $order = order::where('order_source', 'Reseller POS')->findOrFail($orderId);
        $order->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Order status updated.']);
    }
}
