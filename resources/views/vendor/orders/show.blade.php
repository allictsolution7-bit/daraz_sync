@extends('vendor.layouts.app')

@section('title', 'Order details #' . ($order->invoice_no ?? $order->order_number ?? $order->id))

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
        overflow: hidden;
    }

    .v-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    /* Premium Table Styling */
    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        border-bottom: 1px solid #cbd5e1;
        padding: 0.85rem 1.25rem;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 1.15rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges */
    .badge-status {
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status.pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-status.processing { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-status.shipped { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
    .badge-status.delivered { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-status.completed { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-status.cancelled { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

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
@php
    $tracingId = $order->order_number;
@endphp
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 order-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0 text-dark">Order #{{ $tracingId }}</h4>
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
                    @endswitch
                </div>
                <p class="text-muted small mb-0 mt-1"><i class="fas fa-calendar-alt me-1"></i> Placed on {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3.5 py-2 font-weight-bold btn-sm shadow-sm d-inline-flex align-items-center gap-1.5">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <!-- Info Notice -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3" style="background: #eef2ff; color: #3730a3; border: 1px solid #c7d2fe !important;">
        <i class="fas fa-info-circle fs-4"></i>
        <div>
            <strong class="font-weight-bold">Read-Only View:</strong> All fulfillment processes, shipping tracking, and payout disbursements are centrally managed by store administrators.
        </div>
    </div>

    <div class="row g-4">
        <!-- Order Items Column -->
        <div class="col-lg-8">
            <div class="v-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i> Your Items in This Order
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 font-weight-bold" style="font-size: 0.75rem;">
                        {{ $vendorItems->count() }} {{ Str::plural('item', $vendorItems->count()) }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive w-100" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                        <table class="table table-modern align-middle" style="min-width: 700px; margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th style="min-width: 180px; width: 30%;">Product Info</th>
                                    <th style="width: 12%;">Unit Price</th>
                                    <th class="text-center" style="width: 8%;">Qty</th>
                                    <th style="width: 12%;">Subtotal</th>
                                    <th style="width: 16%;">Commission</th>
                                    <th style="width: 14%;">Your Earning</th>
                                    <th class="text-center" style="width: 8%;">Payout</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorItems as $item)
                                @php
                                    $prd = $item->product;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($prd && $prd->thumb_image)
                                                <img src="{{ asset('storage/' . $prd->thumb_image) }}" alt="{{ $prd->title }}" class="rounded-3 border flex-shrink-0" style="width: 52px; height: 52px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border flex-shrink-0" style="width: 52px; height: 52px;">
                                                    <i class="fas fa-image text-muted fs-5"></i>
                                                </div>
                                            @endif
                                            <div style="max-width: 220px;">
                                                <span class="font-weight-bold text-dark d-block lh-sm mb-1" style="word-break: break-word; white-space: normal;">{{ $prd->title ?? $item->product_name ?? 'Product Item' }}</span>
                                                @if($item->variationCombination)
                                                    <span class="badge" style="color: #4f46e5; background: #eef2ff; font-weight: 700; font-size: 0.72rem; white-space: normal; text-align: left; display: inline-block; word-break: break-word;"><i class="fas fa-tag me-1"></i>{{ $item->variationCombination->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold text-dark">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border font-weight-bold px-2.5 py-1" style="font-size: 0.75rem;">x{{ $item->quantity }}</span>
                                    </td>
                                    <td class="font-weight-bold text-dark">৳{{ number_format($item->sub_total, 2) }}</td>
                                    <td>
                                        @if($item->vendor_commission_rate)
                                            <div class="d-inline-flex flex-column">
                                                <span class="text-danger font-weight-bold">-৳{{ number_format($item->vendor_commission_amount, 2) }}</span>
                                                <span class="small text-muted" style="font-size: 0.72rem;">({{ number_format($item->vendor_commission_rate, 1) }}%)</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fs-6 font-weight-extrabold text-success px-2 py-1 rounded" style="background: #f0fdf4;">৳{{ number_format($item->vendor_earning, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->vendor_paid)
                                            <span class="badge-earning paid"><i class="fas fa-check-circle me-1"></i>Paid Out</span>
                                            @if($item->vendor_paid_at)
                                                <small class="d-block text-muted mt-1" style="font-size: 0.68rem;">{{ $item->vendor_paid_at->format('d M Y') }}</small>
                                            @endif
                                        @else
                                            <span class="badge-earning unpaid"><i class="fas fa-clock me-1"></i>Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                                <tr class="font-weight-bold">
                                    <td colspan="5" class="text-end text-dark py-3" style="font-size: 0.85rem;">Total Net Earnings for Vendor:</td>
                                    <td class="text-success fs-5 font-weight-extrabold py-3">৳{{ number_format($vendorItems->sum('vendor_earning'), 2) }}</td>
                                    <td class="text-center py-3">
                                        @if($vendorItems->where('vendor_paid', true)->count() === $vendorItems->count())
                                            <span class="badge bg-success rounded-pill px-3 py-1 font-weight-bold">Fully Disbursed</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-weight-bold">Pending Clearance</span>
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
            <!-- Order Summary Card -->
            <div class="v-card mb-4">
                <div class="card-header">
                    <h5 class="font-weight-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-receipt text-primary"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body p-4" style="font-size: 0.85rem;">
                    <div class="d-flex justify-content-between align-items-center mb-2.5">
                        <span class="text-muted font-weight-bold">Order Number:</span>
                        <span class="font-weight-bold text-dark">#{{ $order->invoice_no ?? $order->order_number ?? $order->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2.5">
                        <span class="text-muted font-weight-bold">Date & Time:</span>
                        <span class="font-weight-bold text-dark">{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2.5">
                        <span class="text-muted font-weight-bold">Payment Method:</span>
                        <span class="font-weight-bold text-dark text-uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold text-dark">Grand Total Amount:</span>
                        <span class="fs-5 font-weight-extrabold text-primary">৳{{ number_format($order->total_amount ?? $order->payable_amount ?? $vendorItems->sum('sub_total'), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Details Card -->
            <div class="v-card">
                <div class="card-header">
                    <h5 class="font-weight-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-primary"></i> Customer Details
                    </h5>
                </div>
                <div class="card-body p-4" style="font-size: 0.85rem;">
                    <div class="mb-3">
                        <span class="font-weight-bold text-dark d-block fs-6 mb-1">{{ $order->customer->name ?? 'Customer' }}</span>
                        @if($order->customer && $order->customer->email)
                            <span class="text-muted d-block mb-1"><i class="fas fa-envelope me-1.5 text-primary"></i>{{ $order->customer->email }}</span>
                        @endif
                        @if($order->customer && $order->customer->phone)
                            <span class="text-muted d-block"><i class="fas fa-phone me-1.5 text-primary"></i>{{ $order->customer->phone }}</span>
                        @endif
                    </div>
                    
                    <hr class="my-3">
                    
                    <div>
                        <strong class="text-dark d-block mb-2"><i class="fas fa-truck-ramp-box me-1.5 text-primary"></i> Shipping Address:</strong>
                        <div class="p-3 bg-light rounded-3 text-dark font-weight-bold border" style="line-height: 1.5;">
                            {{ $order->shipping_address ?? $order->address ?? 'Standard Delivery Address' }}<br>
                            @if($order->shipping_city || $order->shipping_state)
                                <span class="text-muted small font-weight-normal">{{ implode(', ', array_filter([$order->shipping_city, $order->shipping_state, $order->shipping_zip])) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
