@extends('vendor.layouts.app')

@section('title', 'Order #' . $order->order_number)

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
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-800 mb-0 text-dark">Order #{{ $order->order_number }}</h4>
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
                    @endswitch
                </div>
                <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3 fw-bold btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" style="background: rgba(99, 102, 241, 0.08); color: #4338ca;">
        <i class="fas fa-info-circle fs-4"></i>
        <div>
            <strong class="fw-bold">Read-Only View:</strong> All fulfillment processes and payment disbursements are managed directly by platform administrators.
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Items Column -->
        <div class="col-lg-8">
            <div class="v-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i> Your Items in This Order
                    </h5>
                    <span class="badge bg-secondary rounded-pill px-3 py-1 font-semibold fs-8">{{ $vendorItems->count() }} {{ Str::plural('item', $vendorItems->count()) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Product Details</th>
                                    <th>Price</th>
                                    <th class="text-center">Qty</th>
                                    <th>Subtotal</th>
                                    <th>Commission</th>
                                    <th>Your Earning</th>
                                    <th class="text-center">Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorItems as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-dark d-block">{{ $item->product->title ?? 'N/A' }}</span>
                                        @if($item->variationCombination)
                                            <small class="text-muted"><i class="fas fa-tag me-1"></i>{{ $item->variationCombination->name }}</small>
                                        @endif
                                    </td>
                                    <td class="fw-bold">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="fw-bold">৳{{ number_format($item->sub_total, 2) }}</td>
                                    <td>
                                        @if($item->vendor_commission_rate)
                                            <span class="text-danger fw-bold">-৳{{ number_format($item->vendor_commission_amount, 2) }}</span>
                                            <small class="d-block text-muted">({{ number_format($item->vendor_commission_rate, 1) }}%)</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-800 text-success fs-6">৳{{ number_format($item->vendor_earning, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->vendor_paid)
                                            <span class="badge-earning paid">Paid Out</span>
                                            @if($item->vendor_paid_at)
                                                <small class="d-block text-muted mt-1">{{ $item->vendor_paid_at->format('d M Y') }}</small>
                                            @endif
                                        @else
                                            <span class="badge-earning unpaid">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td colspan="5" class="text-end text-dark">Total Net Earnings:</td>
                                    <td class="text-success fs-6 fw-800">৳{{ number_format($vendorItems->sum('vendor_earning'), 2) }}</td>
                                    <td class="text-center">
                                        @if($vendorItems->where('vendor_paid', true)->count() === $vendorItems->count())
                                            <span class="badge bg-success rounded-pill px-2.5 py-1">Fully Disbursed</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">Pending Clearance</span>
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="v-card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-receipt text-primary"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body p-4 fs-8">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Order Number:</span>
                        <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date & Time:</span>
                        <span class="fw-bold text-dark">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Method:</span>
                        <span class="fw-bold text-dark text-uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold text-dark">Grand Total Amount:</span>
                        <span class="fw-800 text-primary fs-6">৳{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="v-card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-primary"></i> Customer Details
                    </h5>
                </div>
                <div class="card-body p-4 fs-8">
                    <div class="mb-3">
                        <span class="fw-bold text-dark d-block fs-6 mb-1">{{ $order->customer->name ?? 'Guest Customer' }}</span>
                        <span class="text-muted d-block"><i class="fas fa-envelope me-1"></i>{{ $order->customer->email ?? 'N/A' }}</span>
                        <span class="text-muted d-block"><i class="fas fa-phone me-1"></i>{{ $order->customer->phone ?? 'N/A' }}</span>
                    </div>
                    
                    <hr>
                    
                    <div>
                        <strong class="text-dark d-block mb-1"><i class="fas fa-truck-ramp-box me-1"></i> Shipping Address:</strong>
                        <div class="p-3 bg-light rounded-3 text-secondary">
                            {{ $order->shipping_address }}<br>
                            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
