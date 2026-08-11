@extends('vendor.layouts.app')

@section('title', 'My Vendor Orders')

@push('styles')
<style>
    :root {
        --v-primary: #4f46e5;
        --v-primary-hover: #4338ca;
        --v-success: #10b981;
        --v-warning: #f59e0b;
        --v-info: #06b6d4;
        --v-danger: #ef4444;
        --v-dark: #0f172a;
    }

    .order-header-card {
        background: #ffffff;
        padding: 1.25rem 1.75rem;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        background: #eef2ff;
        color: #4f46e5;
    }

    .v-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        overflow: visible;
    }

    .v-card .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 0 0 16px 16px;
    }

    .v-card .table-responsive table {
        min-width: 900px;
    }

    .v-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.6rem 0.9rem;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
    }

    /* Premium Table Styling */
    /* Modern Table Redesign - OrderFlow Style */
    .table-modern-card {
        border: 1px solid rgba(200, 196, 213, 0.4) !important;
        border-radius: 2rem !important;
        overflow: hidden !important;
        background: #ffffff !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table-modern thead th {
        background: #f2f4f6 !important;
        color: #464553 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        font-size: 0.72rem !important;
        letter-spacing: 0.08em !important;
        border-bottom: 1px solid rgba(200, 196, 213, 0.3) !important;
        padding: 1rem 1.25rem !important;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 1.1rem 1.25rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(200, 196, 213, 0.15) !important;
        font-size: 0.875rem !important;
        color: #191c1e !important;
    }

    .table-modern tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-modern tbody tr:hover {
        background-color: #f2f4f6 !important;
    }

    /* Badges - OrderFlow theme */
    .badge-status {
        padding: 6px 14px;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
    }

    .badge-status.pending { background: #ffdbcc; color: #511c00; }
    .badge-status.processing { background: #e1e0ff; color: #07006c; }
    .badge-status.shipped { background: #cffafe; color: #155e75; }
    .badge-status.delivered { background: #d1fae5; color: #065f46; }
    .badge-status.completed { background: #d1fae5; color: #065f46; }
    .badge-status.cancelled { background: #ffdad6; color: #93000a; }

    .badge-earning {
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.72rem;
    }
    .badge-earning.paid { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-earning.unpaid { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 order-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <h4 class="font-weight-bold mb-0 text-dark">Order Management</h4>
                <p class="text-muted small mb-0">Overview of customer orders containing your listed products.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-primary rounded-3 px-3.5 py-2 font-weight-bold btn-sm shadow-sm d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
                <i class="fas fa-coins"></i> Earnings Report
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="v-card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('vendor.orders.index') }}" method="GET" class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Search Orders</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0" 
                               placeholder="Order # or Customer name..."
                               value="{{ request('search') }}"
                               style="border-radius: 0 10px 10px 0;">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Order Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-lg-5 col-md-12 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 font-weight-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none; height: 42px;">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    <button type="button" id="btn-toggle-advanced" class="btn btn-outline-secondary rounded-3 font-weight-bold d-inline-flex align-items-center gap-1.5" style="height: 42px; white-space: nowrap;">
                        <i class="fas fa-sliders-h"></i> Advanced Filters
                    </button>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-danger rounded-3 font-weight-bold d-inline-flex align-items-center justify-content-center" style="height: 42px; width: 42px;" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>

                <!-- Advanced Filters Collapsible Section -->
                @php
                    $hasAdvancedFilters = request('courier_status') || request('order_type') || request('min_amount') || request('max_amount') || request('date_from') || request('date_to');
                @endphp
                <div class="col-12 {{ $hasAdvancedFilters ? '' : 'd-none' }}" id="advanced-filters-section">
                    <div class="p-3 bg-light rounded-3 border mt-2 row g-3">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Courier Status</label>
                            <select name="courier_status" class="form-select bg-white">
                                <option value="">All Orders</option>
                                <option value="steadfast_sent" {{ request('courier_status') === 'steadfast_sent' ? 'selected' : '' }}>Steadfast Sent</option>
                                <option value="steadfast_not_sent" {{ request('courier_status') === 'steadfast_not_sent' ? 'selected' : '' }}>Steadfast Not Sent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Order Type</label>
                            <select name="order_type" class="form-select bg-white">
                                <option value="">All Types</option>
                                <option value="combo" {{ request('order_type') === 'combo' ? 'selected' : '' }}>Combo Orders</option>
                                <option value="regular" {{ request('order_type') === 'regular' ? 'selected' : '' }}>Regular Orders</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Min Amount</label>
                            <input type="number" name="min_amount" class="form-control bg-white" placeholder="Min Amount" value="{{ request('min_amount') }}" min="0">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Max Amount</label>
                            <input type="number" name="max_amount" class="form-control bg-white" placeholder="Max Amount" value="{{ request('max_amount') }}" min="0">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Date From</label>
                            <input type="date" name="date_from" class="form-control bg-white" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Date To</label>
                            <input type="date" name="date_to" class="form-control bg-white" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" style="background: #eef2ff; color: #3730a3; border: 1px solid #c7d2fe !important;">
        <i class="fas fa-circle-info fs-4"></i>
        <div>
            <strong class="font-weight-bold">Platform Information:</strong> Order status update and fulfillment are centrally managed by store administrators.
        </div>
    </div>

    <!-- Orders Table Section -->
    <div class="v-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="font-weight-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-primary"></i> Customer Orders
            </h5>
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 font-weight-bold" style="font-size: 0.75rem;">
                {{ $orders->total() }} total orders
            </span>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <div class="metric-icon-box mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                        <i class="fas fa-box-open text-muted"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">No Orders Found</h5>
                    <p class="text-muted small max-w-sm mx-auto">There are no orders matching your current search parameters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-modern align-middle">
                        <thead>
                            <tr>
                                <th>Order Ref</th>
                                <th>Customer Details</th>
                                <th>Order Date</th>
                                <th>Order Total</th>
                                <th>Your Items</th>
                                <th>Your Earnings</th>
                                <th class="text-center">Fulfillment Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php
                                $vendorItems = $order->orderItems->where('vendor_id', auth()->id());
                                $vendorEarning = $vendorItems->sum('vendor_earning');
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="font-weight-bold text-primary text-decoration-none fs-6">
                                        #{{ $order->invoice_no ?? $order->order_number ?? $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark d-block mb-0.5">{{ $order->customer->name ?? 'Customer' }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $order->customer->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark d-block">{{ $order->created_at ? $order->created_at->format('d M, Y') : 'N/A' }}</span>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $order->created_at ? $order->created_at->format('h:i A') : '' }}</small>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark">৳{{ number_format($order->total_amount ?? $order->payable_amount ?? $vendorItems->sum('sub_total'), 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-weight-bold border px-2.5 py-1 mb-1" style="font-size: 0.75rem;">
                                        {{ $vendorItems->count() }} {{ Str::plural('item', $vendorItems->count()) }}
                                    </span>
                                    <small class="d-block text-muted" style="font-size: 0.72rem;">Value: ৳{{ number_format($vendorItems->sum('sub_total'), 2) }}</small>
                                </td>
                                <td>
                                    @if($vendorEarning > 0)
                                        @php
                                            $allPaid = $vendorItems->where('vendor_paid', false)->count() === 0;
                                            $badgeClass = $allPaid ? 'paid' : 'unpaid';
                                            $badgeText = $allPaid ? 'Paid' : 'Unpaid';
                                        @endphp
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="fs-6 font-weight-extrabold text-success px-2 py-0.5 rounded" style="background: #f0fdf4;">৳{{ number_format($vendorEarning, 2) }}</span>
                                            <button type="button" 
                                                    class="btn-badge-payment badge-earning {{ $badgeClass }} border-0" 
                                                    style="cursor: pointer; transition: transform 0.15s ease;"
                                                    data-order-id="{{ $order->id }}"
                                                    data-current-status="{{ $allPaid ? 'paid' : 'unpaid' }}"
                                                    data-order-number="#{{ $order->invoice_no ?? $order->order_number ?? $order->id }}"
                                                    title="Click to change payment status">
                                                {{ $badgeText }}
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge-status pending"><i class="fas fa-clock"></i> Pending</span>
                                            @break
                                        @case('processing')
                                            <span class="badge-status processing"><i class="fas fa-cog fa-spin"></i> Processing</span>
                                            @break
                                        @case('shipped')
                                            <span class="badge-status shipped"><i class="fas fa-truck"></i> Shipped</span>
                                            @break
                                        @case('delivered')
                                        @case('completed')
                                            <span class="badge-status delivered"><i class="fas fa-circle-check"></i> {{ ucfirst($order->status) }}</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge-status cancelled"><i class="fas fa-times-circle"></i> Cancelled</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-outline-primary btn-sm rounded-3 font-weight-bold px-3 py-1.5">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                <div class="p-4 border-top">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Payment Status Update Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-labelledby="paymentStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark" id="paymentStatusModalLabel">Update Payment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="text-muted">You are updating the payment status for order <strong id="modal-order-number" class="text-dark"></strong>.</p>
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <span class="small text-muted d-block mb-2 text-uppercase font-weight-bold" style="letter-spacing: 0.5px;">Select Payment Status</span>
                    <div class="d-flex gap-3">
                        <div class="form-check flex-grow-1 p-0">
                            <input class="btn-check" type="radio" name="modal_payment_status" id="status_paid" value="paid">
                            <label class="btn btn-outline-success w-100 py-2.5 font-weight-bold rounded-3" for="status_paid">
                                <i class="fas fa-check-circle me-1.5"></i> Mark Paid
                            </label>
                        </div>
                        <div class="form-check flex-grow-1 p-0">
                            <input class="btn-check" type="radio" name="modal_payment_status" id="status_unpaid" value="unpaid">
                            <label class="btn btn-outline-warning w-100 py-2.5 font-weight-bold rounded-3" for="status_unpaid">
                                <i class="fas fa-clock me-1.5"></i> Mark Unpaid
                            </label>
                        </div>
                    </div>
                </div>
                <p class="small text-warning mb-0"><i class="fas fa-triangle-exclamation me-1.5"></i> Note: This action will automatically credit or reverse earnings in your wallet balance.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-3 px-3 py-2 font-weight-bold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn-save-payment-status" class="btn btn-primary rounded-3 px-4 py-2 font-weight-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('btn-toggle-advanced');
        const advancedSection = document.getElementById('advanced-filters-section');
        
        if (toggleBtn && advancedSection) {
            toggleBtn.addEventListener('click', function() {
                advancedSection.classList.toggle('d-none');
            });
        }

        // Payment Status Update functionality
        const paymentButtons = document.querySelectorAll('.btn-badge-payment');
        const paymentModalElement = document.getElementById('paymentStatusModal');
        let paymentModalInstance = null;
        if (paymentModalElement) {
            paymentModalInstance = new bootstrap.Modal(paymentModalElement);
        }

        let selectedOrderId = null;

        paymentButtons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });

            btn.addEventListener('click', function() {
                selectedOrderId = this.getAttribute('data-order-id');
                const currentStatus = this.getAttribute('data-current-status');
                const orderNumber = this.getAttribute('data-order-number');

                document.getElementById('modal-order-number').textContent = orderNumber;

                if (currentStatus === 'paid') {
                    document.getElementById('status_paid').checked = true;
                } else {
                    document.getElementById('status_unpaid').checked = true;
                }

                if (paymentModalInstance) {
                    paymentModalInstance.show();
                }
            });
        });

        const saveBtn = document.getElementById('btn-save-payment-status');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const statusInput = document.querySelector('input[name="modal_payment_status"]:checked');
                if (!statusInput || !selectedOrderId) return;

                const selectedStatus = statusInput.value;
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

                fetch(`/vendor/orders/${selectedOrderId}/toggle-payment-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_status: selectedStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (paymentModalInstance) paymentModalInstance.hide();
                        // Show native alert or reload
                        window.location.reload();
                    } else {
                        alert(data.message || 'Something went wrong.');
                        this.disabled = false;
                        this.innerHTML = 'Save Changes';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred. Please try again.');
                    this.disabled = false;
                    this.innerHTML = 'Save Changes';
                });
            });
        }
    });
</script>
@endpush
