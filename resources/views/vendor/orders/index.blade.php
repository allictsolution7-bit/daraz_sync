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
                <div class="col-lg-5 col-md-6">
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

                <div class="col-lg-3 col-md-3">
                    <label class="form-label font-weight-bold text-dark fs-8 text-uppercase mb-1">Filter Status</label>
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
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 font-weight-bold" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none; height: 42px;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary rounded-3 font-weight-bold d-inline-flex align-items-center justify-content-center" style="height: 42px; width: 42px;">
                        <i class="fas fa-undo"></i>
                    </a>
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
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="fs-6 font-weight-extrabold text-success px-2 py-0.5 rounded" style="background: #f0fdf4;">৳{{ number_format($vendorEarning, 2) }}</span>
                                            @if($vendorItems->where('vendor_paid', true)->count() > 0)
                                                <span class="badge-earning paid">Paid</span>
                                            @else
                                                <span class="badge-earning unpaid">Unpaid</span>
                                            @endif
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
@endsection
