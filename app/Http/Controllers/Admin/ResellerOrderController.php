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
        $admin = Auth::user();

        $resellers = User::where('created_by', $admin->id)
            ->whereHas('roles', fn($q) => $q->where('name', 'reseller'))
            ->get(['id', 'name', 'email']);

        $totalCount     = $this->getResellerOrdersQuery()->count();
        $pendingCount   = $this->getResellerOrdersQuery()->where('status', 'pending')->count();
        $processingCount= $this->getResellerOrdersQuery()->where('status', 'processing')->count();
        $deliveredCount = $this->getResellerOrdersQuery()->where('status', 'delivered')->count();
        $cancelledCount = $this->getResellerOrdersQuery()->where('status', 'cancelled')->count();

        $statusCounts = [
            'all'        => $totalCount,
            'pending'    => $pendingCount,
            'processing' => $processingCount,
            'delivered'  => $deliveredCount,
            'cancelled'  => $cancelledCount,
        ];

        return view('admin.reseller-orders.index', compact('statusCounts', 'resellers', 'totalCount'));
    }

    public function data(Request $request)
    {
        $query = $this->getResellerOrdersQuery()
            ->with(['order_items.product', 'user', 'assignedStaff']);

        if (!$request->has('order')) {
            $query->orderBy('created_at', 'desc');
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($resellerId = $request->get('reseller_id')) {
            $query->whereIn('id', function($q) use ($resellerId) {
                $q->select('order_id')->from('order_items')
                  ->where(DB::raw("CAST(JSON_UNQUOTE(JSON_EXTRACT(others, '$.reseller_id')) AS UNSIGNED)"), $resellerId);
            });
        }

        if ($from = $request->get('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->get('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        return DataTables::eloquent($query)
            ->addColumn('reseller_info', function ($order) {
                $firstItem = $order->order_items->first();
                $others = is_string($firstItem?->others) ? json_decode($firstItem->others, true) : ($firstItem?->others ?? []);
                $resellerName = $others['reseller_name'] ?? 'Unknown Reseller';
                $resellerId = $others['reseller_id'] ?? null;
                $html = '<div class="fw-bold text-primary">' . e($resellerName) . '</div>';
                if ($resellerId) {
                    $html .= '<div class="text-muted small">ID: #' . $resellerId . '</div>';
                }
                return $html;
            })
            ->addColumn('customer_info', function ($order) {
                $editUrl = route('admin.orders.edit', $order->id);
                $html = '<div class="fw-semibold">' . e($order->name) . '</div>';
                $html .= '<div class="text-muted small">' . e($order->phone) . '</div>';
                if ($order->city) {
                    $html .= '<div class="text-muted small">' . e($order->city) . '</div>';
                }
                $html .= '<div class="mt-1"><a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:11px;"><i class="fas fa-eye me-1"></i>View</a></div>';
                return $html;
            })
            ->addColumn('items_info', function ($order) {
                $html = '';
                foreach ($order->order_items as $item) {
                    $title = $item->product->title ?? 'Product';
                    $qty   = $item->quantity;
                    $price = number_format($item->price, 2);
                    $others = is_string($item->others) ? json_decode($item->others, true) : ($item->others ?? []);
                    $html .= '<div class="small mb-1"><span class="fw-semibold">' . e($title) . '</span>';
                    $html .= ' <span class="badge bg-light text-dark border">x' . $qty . '</span>';
                    $html .= ' <span class="text-success fw-bold">৳' . $price . '</span>';
                    if (!empty($others['variation_display_name'])) {
                        $html .= '<br><small class="text-muted">' . e($others['variation_display_name']) . '</small>';
                    }
                    $html .= '</div>';
                }
                return $html ?: '-';
            })
            ->addColumn('total_info', function ($order) {
                $html = '<div class="fw-bold text-success fs-6">৳' . number_format($order->total, 2) . '</div>';
                $html .= '<div class="small text-muted">' . e(strtoupper($order->payment_method)) . '</div>';
                if ($order->discount > 0) {
                    $html .= '<div class="small text-danger">-৳' . number_format($order->discount, 2) . '</div>';
                }
                if ($order->shipping > 0) {
                    $html .= '<div class="small text-info">+৳' . number_format($order->shipping, 2) . ' ship</div>';
                }
                return $html;
            })
            ->addColumn('status_badge', function ($order) {
                $map = [
                    'pending' => 'warning', 'processing' => 'info',
                    'ready_for_delivery' => 'primary', 'shipped' => 'secondary',
                    'delivered' => 'success', 'cancelled' => 'danger', 'on_hold' => 'dark',
                ];
                $color = $map[$order->status] ?? 'secondary';
                $label = ucwords(str_replace('_', ' ', $order->status));
                $statusOptions = '';
                foreach ($map as $val => $c) {
                    $sel = $order->status === $val ? 'selected' : '';
                    $statusOptions .= '<option value="' . $val . '" ' . $sel . '>' . ucwords(str_replace('_', ' ', $val)) . '</option>';
                }
                return '<div class="d-flex flex-column gap-1">
                    <span class="badge bg-' . $color . '">' . $label . '</span>
                    <select class="form-select form-select-sm reseller-order-status-change" data-order-id="' . $order->id . '" style="font-size:11px;width:120px;">
                        ' . $statusOptions . '
                    </select>
                </div>';
            })
            ->addColumn('actions', function ($order) {
                $editUrl = route('admin.orders.edit', $order->id);
                $html = '<div class="d-flex gap-1">';
                $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>';
                $html .= '<a href="/admin/pos/print-invoice/' . $order->id . '" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i></a>';
                $html .= '</div>';
                return $html;
            })
            ->addColumn('created_date', function ($order) {
                return '<div class="small">' . $order->created_at->format('d M Y') . '<br><span class="text-muted">' . $order->created_at->format('h:i A') . '</span></div>';
            })
            ->rawColumns(['reseller_info', 'customer_info', 'items_info', 'total_info', 'status_badge', 'actions', 'created_date'])
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
