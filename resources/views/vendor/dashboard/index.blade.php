@extends('vendor.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </h2>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Products -->
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Products</h6>
                        <h3 class="mb-0">{{ $stats['products']['total'] }}</h3>
                        <small class="text-success">{{ $stats['products']['approved'] }} Approved</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="fas fa-box fs-2 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <small class="text-warning">{{ $stats['products']['pending'] }} Pending Approval</small>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h3 class="mb-0">{{ $stats['orders']['total'] }}</h3>
                        <small class="text-muted">All Time</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded">
                        <i class="fas fa-shopping-cart fs-2 text-info"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('vendor.orders.index') }}" class="text-decoration-none">
                    View Orders <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Balance -->
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Current Balance</h6>
                        <h3 class="mb-0">৳{{ number_format($stats['earnings']['balance'], 2) }}</h3>
                        <small class="text-muted">Available</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="fas fa-wallet fs-2 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('vendor.withdrawals.create') }}" class="text-decoration-none">
                    Request Withdrawal <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Earnings -->
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Earnings</h6>
                        <h3 class="mb-0">৳{{ number_format($stats['earnings']['total'], 2) }}</h3>
                        <small class="text-success">Pending: ৳{{ number_format($stats['earnings']['pending'], 2) }}</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="fas fa-money-bill-wave fs-2 text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <small class="text-muted">Withdrawn: ৳{{ number_format($stats['earnings']['withdrawn'], 2) }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Commission Settings Info -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Your Commission Settings</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="mb-1"><strong>Default Commission:</strong></p>
                        <h4 class="text-primary">{{ $effectiveSettings['default_commission'] }}%</h4>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1"><strong>Commission Range:</strong></p>
                        <h4 class="text-muted">{{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%</h4>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1"><strong>Min Withdrawal:</strong></p>
                        <h4 class="text-success">৳{{ number_format($effectiveSettings['min_withdrawal'], 2) }}</h4>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1"><strong>Product Limit:</strong></p>
                        <h4 class="text-info">
                            @if($effectiveSettings['product_limit'] == 0)
                                Unlimited
                            @else
                                {{ $effectiveSettings['product_count'] }} / {{ $effectiveSettings['product_limit'] }}
                            @endif
                        </h4>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-lightbulb"></i>
                    <strong>Tip:</strong> When creating products, you can propose custom commission rates within your allowed range ({{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%). Admin will review and approve.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Products -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-box"></i> Recent Products</h5>
                <a href="{{ route('vendor.products.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentProducts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No products yet</p>
                        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Create Your First Product
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($recentProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->thumb_image)
                                                <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                                     alt="{{ $product->title }}" 
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($product->title, 30) }}</strong><br>
                                                <small class="text-muted">৳{{ number_format($product->offer, 2) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        @if($product->approval_status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($product->approval_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
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
                <div class="card-footer bg-transparent">
                    <a href="{{ route('vendor.products.index') }}" class="text-decoration-none">
                        View All Products <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Recent Orders</h5>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No orders yet</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong><br>
                                        <small class="text-muted">{{ $order->customer->name ?? 'N/A' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <strong>৳{{ number_format($order->total_amount, 2) }}</strong><br>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @if($recentOrders->isNotEmpty())
                <div class="card-footer bg-transparent">
                    <a href="{{ route('vendor.orders.index') }}" class="text-decoration-none">
                        View All Orders <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Withdrawal Stats -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Withdrawal Statistics</h5>
                <a href="{{ route('vendor.withdrawals.index') }}" class="btn btn-sm btn-outline-primary">
                    View All
                </a>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="text-success">৳{{ number_format($withdrawalStats['total_withdrawn'], 2) }}</h4>
                        <p class="text-muted mb-0">Total Withdrawn</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning">৳{{ number_format($withdrawalStats['pending_amount'], 2) }}</h4>
                        <p class="text-muted mb-0">Pending Requests</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-info">৳{{ number_format($withdrawalStats['approved_amount'], 2) }}</h4>
                        <p class="text-muted mb-0">Approved (Processing)</p>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-primary">{{ $withdrawalStats['total_requests'] }}</h4>
                        <p class="text-muted mb-0">Total Requests</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

