@extends('layouts.master')

@section('title', 'Wholesale Purchase Orders')

@section('styles')
<style>
    .stats-card-modern {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stats-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }
    .badge-status-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fde68a !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-status-approved {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
        border: 1px solid #a7f3d0 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-status-rejected {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fecaca !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-processing {
        background-color: #e0e7ff !important;
        color: #3730a3 !important;
        border: 1px solid #c7d2fe !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-shipped {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-delivered {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-pending {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .table-modern thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-modern tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="max-width: 1650px;">
    <!-- Header Block -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill px-2.5 py-1" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-handshake me-1"></i> B2B WHOLESALE NETWORK
                </span>
                <span class="text-muted small">&bull;</span>
                <span class="text-muted small">Super Admin Payment Verification & Dispatch</span>
            </div>
            <h1 class="h3 mb-0 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">
                Wholesale Purchase Orders
            </h1>
            <p class="text-muted mb-0 small mt-1">
                Manage B2B stock purchase orders between store administrators and supplier stores.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.global-products.index') }}" class="btn btn-primary btn-sm rounded-3 px-3 py-2" style="font-weight: 600; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none;">
                <i class="fas fa-globe me-1.5"></i> Browse Global Wholesale Products
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">Pending Verification</span>
                    <h3 class="mb-0 font-bold text-warning mt-1" style="font-weight: 700;">{{ $pendingCount }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">Awaiting Super Admin</span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #fef3c7; color: #d97706;">
                    <i class="fas fa-clock fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">Approved Orders</span>
                    <h3 class="mb-0 font-bold text-success mt-1" style="font-weight: 700;">{{ $approvedCount }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">Dispatched to Sellers</span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #d1fae5; color: #059669;">
                    <i class="fas fa-check-circle fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">Total Wholesale Volume</span>
                    <h3 class="mb-0 font-bold text-primary mt-1" style="font-weight: 700;">৳{{ number_format($totalVolume, 2) }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">Settled Volume</span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #e0e7ff; color: #4338ca;">
                    <i class="fas fa-money-bill-wave fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">Total Orders</span>
                    <h3 class="mb-0 font-bold text-slate-800 mt-1" style="font-weight: 700;">{{ $orders->total() }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">All Recorded Purchases</span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f1f5f9; color: #64748b;">
                    <i class="fas fa-boxes-stacked fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.wholesale-orders.index') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-between gap-3 m-0">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search order #, product, TrxID..." value="{{ request('search') }}">
                    </div>

                    <select name="status" class="form-select form-select-sm rounded-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="">Payment: All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <select name="fulfillment" class="form-select form-select-sm rounded-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="">Fulfillment: All</option>
                        <option value="pending" {{ request('fulfillment') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('fulfillment') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('fulfillment') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('fulfillment') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3">Filter</button>
                    @if(request()->anyFilled(['search', 'status', 'fulfillment']))
                        <a href="{{ route('admin.wholesale-orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">Reset</a>
                    @endif
                </div>

                @if($isSuperAdmin)
                    <span class="badge bg-dark text-white px-2.5 py-1.5 rounded-3" style="font-size: 11px;">
                        <i class="fas fa-shield-alt me-1 text-warning"></i> Super Admin Mode
                    </span>
                @endif
            </form>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-modern">
                <thead>
                    <tr>
                        <th class="ps-4" style="white-space: nowrap;">Order Info</th>
                        <th style="min-width: 180px;">Buyer Store & Address</th>
                        <th style="white-space: nowrap;">Seller / Supplier</th>
                        <th style="min-width: 200px;">Product & Qty</th>
                        <th style="white-space: nowrap;">Total Amount</th>
                        <th style="white-space: nowrap;">Payment Proof (Gateway)</th>
                        <th style="white-space: nowrap;">Payment Status</th>
                        <th style="white-space: nowrap;">Fulfillment</th>
                        <th class="pe-4 text-end" style="white-space: nowrap;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4" style="white-space: nowrap;">
                                <div class="font-bold text-dark font-monospace" style="font-weight: 700;">{{ $order->order_number }}</div>
                                <div class="text-muted small" style="font-size: 11px;">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-slate-800" style="font-weight: 600;">
                                    {{ $order->buyer_admin_name }}
                                    @if($order->buyer_subdomain)
                                        <span class="badge rounded-pill ms-1" style="background-color: #e0e7ff; color: #3730a3; font-size: 10px;">{{ '@' . $order->buyer_subdomain }}</span>
                                    @endif
                                </div>
                                <div class="text-muted small" style="font-size: 11.5px;">
                                    <i class="fas fa-phone-alt me-1"></i> {{ $order->buyer_admin_phone }}
                                </div>
                                <div class="text-muted small text-truncate" style="max-width: 220px; font-size: 11px;" title="{{ $order->buyer_shipping_address }}">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $order->buyer_shipping_address }}
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge rounded-pill px-2 py-1" style="background-color: #f1f5f9; color: #334155; font-size: 11.5px; font-weight: 600;">
                                    <i class="fas fa-store me-1 text-primary"></i> {{ '@' . $order->seller_subdomain }}
                                </span>
                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                    {{ $order->seller_admin_name }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($order->product_thumb_image)
                                        <img src="{{ filter_var($order->product_thumb_image, FILTER_VALIDATE_URL) ? $order->product_thumb_image : asset('storage/' . $order->product_thumb_image) }}" 
                                             class="rounded-3 border" style="width: 40px; height: 40px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <div class="font-semibold text-slate-800 text-truncate" style="max-width: 180px; font-weight: 600;" title="{{ $order->product_title }}">
                                            {{ $order->product_title }}
                                        </div>
                                        <div class="small text-muted" style="font-size: 11px;">
                                            Qty: <strong class="text-primary">{{ $order->quantity }} pcs</strong> &bull; ৳{{ number_format($order->unit_price, 2) }}/unit
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="font-bold text-success" style="font-weight: 700; font-size: 14px;">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </div>
                                @if($isSuperAdmin)
                                    <div class="text-muted small" style="font-size: 10px;">
                                        Seller: ৳{{ number_format($order->seller_earnings, 2) }} | Comm: ৳{{ number_format($order->platform_commission, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        <span class="badge px-2 py-1 rounded-pill" style="background-color: #fce7f3; color: #9d174d; font-weight: 700; font-size: 11px;">
                                            {{ $order->payment_gateway }}
                                        </span>
                                        <div class="small font-monospace text-slate-700 mt-1" style="font-size: 11px;">
                                            Trx: <strong>{{ $order->trx_id ?: 'N/A' }}</strong>
                                        </div>
                                        @if($order->sender_phone)
                                            <div class="text-muted small" style="font-size: 10.5px;">
                                                From: {{ $order->sender_phone }}
                                            </div>
                                        @endif
                                    </div>
                                    @php
                                        $meta = is_array($order->metadata) ? $order->metadata : (is_string($order->metadata) ? json_decode($order->metadata, true) : []);
                                        $screenshot = $meta['payment_screenshot'] ?? null;
                                    @endphp
                                    @if($screenshot)
                                        <a href="{{ asset($screenshot) }}" target="_blank" title="View Payment Proof Slip" class="d-inline-block position-relative">
                                            <img src="{{ asset($screenshot) }}" class="rounded border shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 14px; height: 14px; font-size: 8px;">
                                                <i class="fas fa-search-plus"></i>
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                @if($order->payment_status === 'pending')
                                    <span class="badge badge-status-pending px-2.5 py-1.5 rounded-pill" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 700; font-size: 11px;">
                                        <i class="fas fa-clock me-1"></i> Pending Verification
                                    </span>
                                @elseif($order->payment_status === 'approved')
                                    <span class="badge badge-status-approved px-2.5 py-1.5 rounded-pill" style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-weight: 700; font-size: 11px;">
                                        <i class="fas fa-check-circle me-1"></i> Paid & Verified
                                    </span>
                                @else
                                    <span class="badge badge-status-rejected px-2.5 py-1.5 rounded-pill" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-weight: 700; font-size: 11px;">
                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if($order->fulfillment_status === 'delivered')
                                    <span class="badge badge-fulfillment-delivered px-2.5 py-1 rounded-pill" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-box-open me-1"></i> Delivered
                                    </span>
                                @elseif($order->fulfillment_status === 'shipped')
                                    <span class="badge badge-fulfillment-shipped px-2.5 py-1 rounded-pill" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-truck-fast me-1"></i> Shipped
                                    </span>
                                    @if($order->tracking_number)
                                        <div class="text-muted small mt-1" style="font-size: 10.5px;">{{ $order->courier_name }}: {{ $order->tracking_number }}</div>
                                    @endif
                                @elseif($order->fulfillment_status === 'processing')
                                    <span class="badge badge-fulfillment-processing px-2.5 py-1 rounded-pill" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-boxes-packing me-1"></i> Seller Packing
                                    </span>
                                @else
                                    <span class="badge badge-fulfillment-pending px-2.5 py-1 rounded-pill" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-hourglass-start me-1"></i> Pending Payment
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end" style="white-space: nowrap;">
                                @if($isSuperAdmin && $order->payment_status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success rounded-3 px-2.5 py-1 font-semibold" style="font-size: 11.5px;" onclick="approveOrder('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->seller_subdomain }}', '{{ $order->quantity }}')">
                                        <i class="fas fa-check me-1"></i> Accept Payment
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1 font-semibold ms-1" style="font-size: 11.5px;" onclick="rejectOrder('{{ $order->id }}', '{{ $order->order_number }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @elseif($order->payment_status === 'approved')
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-3 px-2.5 py-1 font-semibold" style="font-size: 11.5px;" onclick="openFulfillmentModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->fulfillment_status }}', '{{ $order->courier_name }}', '{{ $order->tracking_number }}')">
                                        <i class="fas fa-truck me-1"></i> Shipping
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-receipt text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">No Wholesale Purchase Orders Found</h5>
                                    <p class="text-muted small">Orders placed across the B2B wholesale network will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveOrder(orderId, orderNum, sellerSubdomain, qty) {
    Swal.fire({
        title: 'Accept & Dispatch Order?',
        html: `Accept Super Admin payment for <strong>${orderNum}</strong>?<br><br>
               <div class="alert bg-light border text-start small p-2.5 rounded-3 mb-0">
                 &bull; Creates a delivery order in seller store (<strong>@${sellerSubdomain}</strong>).<br>
                 &bull; Allocates <strong>${qty} units</strong> into the buyer store catalog.
               </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Yes, Accept & Dispatch',
        cancelButtonText: 'Cancel'
    }).then((res) => {
        if (res.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                html: 'Verifying payment and dispatching order...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to approve order', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Network error occurred', 'error'));
        }
    });
}

function rejectOrder(orderId, orderNum) {
    Swal.fire({
        title: 'Reject Payment?',
        html: `Enter rejection reason for order <strong>${orderNum}</strong>:`,
        input: 'text',
        inputPlaceholder: 'e.g. Invalid TrxID or payment not received',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Reject Payment',
        cancelButtonText: 'Cancel',
        inputValidator: (val) => {
            if (!val) return 'Please provide a reason';
        }
    }).then((res) => {
        if (res.isConfirmed) {
            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: res.value })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Rejected', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to reject', 'error');
                }
            });
        }
    });
}

function openFulfillmentModal(orderId, orderNum, currentStatus, courier, tracking) {
    Swal.fire({
        title: `Update Shipping for ${orderNum}`,
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label small font-semibold">Fulfillment Status</label>
                    <select id="swal_fulfillment_status" class="form-select form-select-sm">
                        <option value="processing" ${currentStatus === 'processing' ? 'selected' : ''}>Processing / Packing</option>
                        <option value="shipped" ${currentStatus === 'shipped' ? 'selected' : ''}>Shipped</option>
                        <option value="delivered" ${currentStatus === 'delivered' ? 'selected' : ''}>Delivered</option>
                        <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Courier Service</label>
                    <input type="text" id="swal_courier" class="form-control form-control-sm" placeholder="e.g. Steadfast / Pathao / RedX" value="${courier || ''}">
                </div>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Tracking Number / Parcel ID</label>
                    <input type="text" id="swal_tracking" class="form-control form-control-sm" placeholder="e.g. ST12345678" value="${tracking || ''}">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Save Shipping Info',
        preConfirm: () => {
            return {
                fulfillment_status: document.getElementById('swal_fulfillment_status').value,
                courier_name: document.getElementById('swal_courier').value,
                tracking_number: document.getElementById('swal_tracking').value,
            };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/fulfillment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(res.value)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to update', 'error');
                }
            });
        }
    });
}
</script>
@endsection
