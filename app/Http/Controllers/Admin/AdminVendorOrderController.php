<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminVendorOrderController extends Controller
{
    /**
     * Get vendor orders base query scoped to current admin's vendors and copied products
     */
    protected function getVendorOrdersQuery($user = null)
    {
        $user = $user ?: Auth::user();

        return order::whereHas('orderItems', function ($q) use ($user) {
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
                $q->where(function ($subQ) {
                    $subQ->whereNotNull('vendor_id')
                         ->orWhereHas('product', function ($pq) {
                             $pq->whereNotNull('vendor_id')
                                ->orWhereNotNull('parent_product_id');
                         });
                });
            } else {
                // Regular Admin: Vendors created by this admin OR copied products from this admin
                $vendorIds = User::where('created_by', $user->id)->pluck('id')->toArray();
                $adminProductIds = Product::where('created_by', $user->id)->pluck('id')->toArray();

                $q->where(function ($subQ) use ($vendorIds, $adminProductIds) {
                    if (!empty($vendorIds)) {
                        $subQ->whereIn('vendor_id', $vendorIds);
                    }
                    
                    $subQ->orWhereHas('product', function ($pq) use ($vendorIds, $adminProductIds) {
                        $conditionSet = false;
                        if (!empty($vendorIds)) {
                            $pq->whereIn('vendor_id', $vendorIds);
                            $conditionSet = true;
                        }
                        if (!empty($adminProductIds)) {
                            if ($conditionSet) {
                                $pq->orWhereIn('parent_product_id', $adminProductIds);
                            } else {
                                $pq->whereIn('parent_product_id', $adminProductIds);
                            }
                        }
                    });
                });
            }
        });
    }

    public function index(Request $request)
    {
        $baseQuery = $this->getVendorOrdersQuery();

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

                return '<div class="customer-info"><div class="customer-name">'.$name.'</div><div class="customer-phone">'.$phone.'</div>'.$ipHtml.$cnHtml.'<div class="product-info-mobile mt-2">'.$productInfo.'</div><div class="mt-2 d-flex align-items-center gap-2"><a href="'.$editUrl.'" title="View" class="text-primary"><i class="fas fa-eye"></i></a><a href="'.$editUrl.'" title="Edit" class="text-success"><i class="fas fa-edit"></i></a><a href="/admin/pos/print-invoice/'.$order->id.'" title="Print Invoice" class="text-info" target="_blank"><i class="fas fa-file-invoice"></i></a><a href="/admin/pos/print-package-slip/'.$order->id.'" title="Print Package Slip" class="text-warning" target="_blank"><i class="fas fa-box"></i></a></div></div>';
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

                $titles = [];
                foreach ($order->orderItems as $item) {
                    $vendorName = $item->vendor->name ?? ($item->product->vendor->name ?? 'Vendor');
                    $titles[] = e($item->product->title ?? 'Product') . ' <span class="badge bg-warning text-dark font-weight-bold ms-1" style="font-size: 11px; padding: 2px 6px;"><i class="fas fa-store me-1"></i>'.$vendorName.'</span>';
                }
                $html .= implode('<br>', $titles);
                return $html;
            })
            ->rawColumns(['select', 'customer_info', 'product_price_and_name'])
            ->make(true);
    }
}
