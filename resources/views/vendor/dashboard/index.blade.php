@extends('vendor.layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<!-- Hero Welcome Banner -->
<div class="hero-banner mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative style-z-1">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge text-white rounded-pill px-3 py-1 fw-bold fs-7" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); backdrop-filter: blur(5px);">
                    <i class="fas fa-store me-1 text-warning"></i> Merchant Control Center
                </span>
                <span class="badge text-white rounded-pill px-3 py-1 fw-semibold fs-7" style="background: #10b981;">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Live Active
                </span>
            </div>
            <h2 class="fw-extrabold text-white mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-white-50 mb-0">Here is what's happening with your store products, orders, and revenue today.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('vendor.products.create') }}" class="btn btn-warning fw-bold rounded-pill px-4 py-2 text-dark shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i> Add New Product
            </a>
            <a href="{{ route('vendor.withdrawals.create') }}" class="btn text-white rounded-pill px-4 py-2 d-flex align-items-center gap-2 fw-semibold" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); backdrop-filter: blur(5px);">
                <i class="fas fa-wallet text-warning"></i> Request Payout
            </a>
        </div>
    </div>
</div>

<!-- Key Performance Metric Cards -->
<div class="row g-3 g-lg-4 mb-4">
    <!-- Products Stat Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="v-card p-4 h-100 position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted fw-semibold fs-7 text-uppercase tracking-wider">Total Products</span>
                    <h2 class="fw-extrabold text-dark mt-1 mb-0">{{ $stats['products']['total'] }}</h2>
                </div>
                <div class="stat-icon primary">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <span class="badge badge-approved">
                    <i class="fas fa-check-circle me-1"></i> {{ $stats['products']['approved'] }} Approved
                </span>
                @if($stats['products']['pending'] > 0)
                    <span class="badge badge-pending">
                        <i class="fas fa-clock me-1"></i> {{ $stats['products']['pending'] }} Pending
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Orders Stat Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="v-card p-4 h-100 position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted fw-semibold fs-7 text-uppercase tracking-wider">Total Orders</span>
                    <h2 class="fw-extrabold text-dark mt-1 mb-0">{{ $stats['orders']['total'] }}</h2>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <span class="text-muted fs-7">All Time Activity</span>
                <a href="{{ route('vendor.orders.index') }}" class="text-primary fw-bold text-decoration-none fs-7">
                    View Orders <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Available Balance Stat Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="v-card p-4 h-100 position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted fw-semibold fs-7 text-uppercase tracking-wider">Available Balance</span>
                    <h2 class="fw-extrabold text-success mt-1 mb-0">৳{{ number_format($stats['earnings']['balance'], 2) }}</h2>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <span class="text-muted fs-7">Ready for Payout</span>
                <a href="{{ route('vendor.withdrawals.create') }}" class="text-success fw-bold text-decoration-none fs-7">
                    Withdraw <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Revenue Stat Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="v-card p-4 h-100 position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="text-muted fw-semibold fs-7 text-uppercase tracking-wider">Total Revenue</span>
                    <h2 class="fw-extrabold text-dark mt-1 mb-0">৳{{ number_format($stats['earnings']['total'], 2) }}</h2>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <span class="text-muted fs-7">Pending: <strong>৳{{ number_format($stats['earnings']['pending'], 2) }}</strong></span>
                <span class="text-muted fs-7">Withdrawn: ৳{{ number_format($stats['earnings']['withdrawn'], 0) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Commission Settings & Store Policy Section -->
<div class="v-card p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-indigo p-2 rounded-3 text-primary bg-primary bg-opacity-10">
                <i class="fas fa-sliders fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Commission Rates & Quota Settings</h5>
                <small class="text-muted">Effective rate parameters governed by admin policy</small>
            </div>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
            <i class="fas fa-shield-check me-1"></i> Policy Active
        </span>
    </div>

    <div class="row g-3">
        <div class="col-6 col-md-3">
            <div class="p-3 bg-light rounded-3 border">
                <small class="text-muted font-monospace text-uppercase fw-semibold d-block mb-1">Default Commission</small>
                <h3 class="fw-extrabold text-primary mb-0">{{ $effectiveSettings['default_commission'] }}%</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-light rounded-3 border">
                <small class="text-muted font-monospace text-uppercase fw-semibold d-block mb-1">Commission Range</small>
                <h3 class="fw-extrabold text-dark mb-0">{{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-light rounded-3 border">
                <small class="text-muted font-monospace text-uppercase fw-semibold d-block mb-1">Min Withdrawal Limit</small>
                <h3 class="fw-extrabold text-success mb-0">৳{{ number_format($effectiveSettings['min_withdrawal'], 2) }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-light rounded-3 border">
                <small class="text-muted font-monospace text-uppercase fw-semibold d-block mb-1">Product Limit</small>
                <h3 class="fw-extrabold text-info mb-0">
                    @if($effectiveSettings['product_limit'] == 0)
                        Unlimited
                    @else
                        {{ $effectiveSettings['product_count'] }} / {{ $effectiveSettings['product_limit'] }}
                    @endif
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Recent Products & Recent Orders -->
<div class="row g-4 mb-4">
    <!-- Recent Products Table Card -->
    <div class="col-12 col-lg-6">
        <div class="v-card h-100 d-flex flex-column">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-boxes-stacked text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0">Recent Products</h5>
                </div>
                <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                    <i class="fas fa-plus me-1"></i> Add
                </a>
            </div>
            
            <div class="p-0 flex-grow-1">
                @if($recentProducts->isEmpty())
                    <div class="text-center py-5">
                        <div class="bg-light d-inline-flex p-3 rounded-circle mb-3">
                            <i class="fas fa-box-open fs-1 text-muted"></i>
                        </div>
                        <h6 class="fw-bold text-dark">No products uploaded yet</h6>
                        <p class="text-muted fs-7 mb-3">Start adding products to showcase in the merchant store.</p>
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold">
                            Create First Product
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0 text-muted fs-7 text-uppercase">Product</th>
                                    <th class="border-0 text-muted fs-7 text-uppercase text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProducts as $product)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($product->thumb_image)
                                                <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                                     alt="{{ $product->title }}" 
                                                     class="rounded-3"
                                                     style="width: 44px; height: 44px; object-fit: cover; border: 1px solid #e2e8f0;">
                                            @else
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0 fs-7">{{ Str::limit($product->title, 28) }}</h6>
                                                <small class="text-primary fw-semibold">৳{{ number_format($product->offer, 2) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($product->approval_status === 'approved')
                                            <span class="badge badge-approved">Approved</span>
                                        @elseif($product->approval_status === 'pending')
                                            <span class="badge badge-pending">Pending</span>
                                        @else
                                            <span class="badge badge-rejected">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($recentProducts->isNotEmpty())
                <div class="p-3 border-top text-center bg-light rounded-bottom-4">
                    <a href="{{ route('vendor.products.index') }}" class="text-primary fw-bold text-decoration-none fs-7">
                        View All Products <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Orders Table Card -->
    <div class="col-12 col-lg-6">
        <div class="v-card h-100 d-flex flex-column">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-receipt text-info fs-5"></i>
                    <h5 class="fw-bold mb-0">Recent Store Orders</h5>
                </div>
                <a href="{{ route('vendor.orders.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">
                    View All
                </a>
            </div>

            <div class="p-0 flex-grow-1">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-5">
                        <div class="bg-light d-inline-flex p-3 rounded-circle mb-3">
                            <i class="fas fa-shopping-bag fs-1 text-muted"></i>
                        </div>
                        <h6 class="fw-bold text-dark">No orders received yet</h6>
                        <p class="text-muted fs-7 mb-0">New customer orders for your products will appear here.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0 text-muted fs-7 text-uppercase">Order ID</th>
                                    <th class="border-0 text-muted fs-7 text-uppercase text-end pe-4">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-circle text-info">
                                                <i class="fas fa-bag-shopping"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0 fs-7">#{{ $order->order_number }}</h6>
                                                <small class="text-muted">{{ $order->customer->name ?? 'Customer' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="fw-extrabold text-dark fs-7">৳{{ number_format($order->total_amount, 2) }}</div>
                                        <small class="text-muted fs-8">{{ $order->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($recentOrders->isNotEmpty())
                <div class="p-3 border-top text-center bg-light rounded-bottom-4">
                    <a href="{{ route('vendor.orders.index') }}" class="text-info fw-bold text-decoration-none fs-7">
                        View All Orders <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Withdrawal Statistics Breakdown -->
<div class="v-card p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-money-bill-transfer text-success fs-5"></i>
            <h5 class="fw-bold mb-0">Withdrawal & Payout Summary</h5>
        </div>
        <a href="{{ route('vendor.withdrawals.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
            History
        </a>
    </div>

    <div class="row text-center g-3">
        <div class="col-6 col-md-3">
            <div class="p-3 bg-success bg-opacity-10 rounded-3">
                <h4 class="fw-extrabold text-success mb-1">৳{{ number_format($withdrawalStats['total_withdrawn'], 2) }}</h4>
                <small class="text-muted fw-semibold">Total Payouts Paid</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                <h4 class="fw-extrabold text-warning mb-1">৳{{ number_format($withdrawalStats['pending_amount'], 2) }}</h4>
                <small class="text-muted fw-semibold">Pending Requests</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-info bg-opacity-10 rounded-3">
                <h4 class="fw-extrabold text-info mb-1">৳{{ number_format($withdrawalStats['approved_amount'], 2) }}</h4>
                <small class="text-muted fw-semibold">Approved (Processing)</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                <h4 class="fw-extrabold text-primary mb-1">{{ $withdrawalStats['total_requests'] }}</h4>
                <small class="text-muted fw-semibold">Total Requests</small>
            </div>
        </div>
    </div>
</div>
@endsection
