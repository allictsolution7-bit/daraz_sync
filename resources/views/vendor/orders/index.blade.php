@extends('vendor.layouts.app')

@section('title', 'My Vendor Orders')

@push('styles')
<style>
    :root {
        --v-primary: #6366f1;
        --v-primary-hover: #4f46e5;
        --v-success: #10b981;
        --v-warning: #f59e0b;
        --v-info: #06b6d4;
        --v-danger: #ef4444;
        --v-dark: #0f172a;
    }

    /* Glassmorphic Container */
    .order-header-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        padding: 1.25rem 1.75rem;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    /* Metric Cards */
    .metric-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        background: rgba(99, 102, 241, 0.12);
        color: #6366f1;
    }

    .v-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
    }

    .v-card .card-header {
        background: rgba(248, 250, 252, 0.7);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.65rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    /* Custom Table Styling */
    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem 1.25rem;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
    }

    /* Status Badges */
    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status.pending { background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-status.processing { background: rgba(6, 182, 212, 0.1); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.2); }
    .badge-status.shipped { background: rgba(99, 102, 241, 0.1); color: #4f46e5; border: 1px solid rgba(99, 102, 241, 0.2); }
    .badge-status.delivered { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-status.cancelled { background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); }

    .badge-earning {
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.7rem;
    }
    .badge-earning.paid { background: #dcfce7; color: #15803d; }
    .badge-earning.unpaid { background: #fef3c7; color: #b45309; }
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
                <h4 class="fw-800 mb-0 text-dark">Order Management</h4>
                <p class="text-muted small mb-0">Overview of customer orders containing your listed products.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-primary rounded-3 px-3 fw-bold btn-sm shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                <i class="fas fa-coins me-1"></i> Earnings Report
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="v-card mb-4">
        <div class="card-body p-4">
            <form action="{{ route('vendor.orders.index') }}" method="GET" class="row g-3">
                <div class="col-lg-5 col-md-6">
                    <label class="form-label fw-bold text-dark fs-8 uppercase mb-1">Search Orders</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid #e2e8f0;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0" 
                               placeholder="Order # or Customer name..."
                               value="{{ request('search') }}"
                               style="border-radius: 0 12px 12px 0;">
                    </div>
                </div>

                <div class="col-lg-3 col-md-3">
                    <label class="form-label fw-bold text-dark fs-8 uppercase mb-1">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="all">All Order Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-lg-4 col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 fw-bold" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; height: 42px;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary rounded-3 fw-bold" style="height: 42px;">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" style="background: rgba(99, 102, 241, 0.08); color: #4338ca;">
        <i class="fas fa-circle-info fs-4"></i>
        <div>
            <strong class="fw-bold">Platform Information:</strong> Order status update and fulfillment are centrally managed by store administrators.
        </div>
    </div>

    <!-- Orders Table Section -->
    <div class="v-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-primary"></i> Customer Orders
            </h5>
            <span class="badge bg-secondary rounded-pill px-3 py-1 font-semibold fs-8">{{ $orders->total() }} total orders</span>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <div class="metric-icon-box mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                        <i class="fas fa-box-open text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No Orders Found</h5>
                    <p class="text-muted small max-w-sm mx-auto">There are no orders matching your current search parameters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom">
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
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="fw-bold text-primary text-decoration-none fs-6">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block">{{ $order->customer->name ?? 'Guest User' }}</span>
                                    <small class="text-muted">{{ $order->customer->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $order->created_at->format('d M, Y') }}</span>
                                    <small class="d-block text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">৳{{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark font-semibold border px-2 py-1 mb-1">
                                        {{ $vendorItems->count() }} {{ Str::plural('item', $vendorItems->count()) }}
                                    </span>
                                    <small class="d-block text-muted">Value: ৳{{ number_format($vendorItems->sum('sub_total'), 2) }}</small>
                                </td>
                                <td>
                                    @if($vendorEarning > 0)
                                        <span class="fw-800 text-success fs-6">৳{{ number_format($vendorEarning, 2) }}</span>
                                        @if($vendorItems->where('vendor_paid', true)->count() > 0)
                                            <span class="badge-earning paid ms-1">Paid</span>
                                        @else
                                            <span class="badge-earning unpaid ms-1">Unpaid</span>
                                        @endif
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
                                            <span class="badge-status delivered"><i class="fas fa-circle-check"></i> Delivered</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge-status cancelled"><i class="fas fa-times-circle"></i> Cancelled</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-outline-primary btn-sm rounded-2 fw-bold px-3">
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
@endsection
