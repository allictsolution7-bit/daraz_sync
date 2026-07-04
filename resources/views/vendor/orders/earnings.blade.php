@extends('vendor.layouts.app')

@section('title', 'Earnings Summary')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-chart-line"></i> Earnings Summary</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>

<!-- Earnings Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Sales</h6>
                        <h3 class="mb-0">৳{{ number_format($stats['total_sales'], 2) }}</h3>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Earnings</h6>
                        <h3 class="mb-0">৳{{ number_format($stats['total_earnings'], 2) }}</h3>
                        <small class="text-white-50">After commission</small>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Platform Commission</h6>
                        <h3 class="mb-0">৳{{ number_format($stats['total_commission'], 2) }}</h3>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="fas fa-percent"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paid vs Unpaid Earnings -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-body">
                <h5 class="card-title text-success">
                    <i class="fas fa-check-circle"></i> Paid Earnings
                </h5>
                <h2 class="mb-0 text-success">৳{{ number_format($stats['paid'], 2) }}</h2>
                <small class="text-muted">Already withdrawn</small>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-warning">
            <div class="card-body">
                <h5 class="card-title text-warning">
                    <i class="fas fa-clock"></i> Pending Earnings
                </h5>
                <h2 class="mb-0 text-warning">৳{{ number_format($stats['unpaid'], 2) }}</h2>
                <small class="text-muted">Available for withdrawal</small>
                <a href="{{ route('vendor.withdrawals.create') }}" class="btn btn-warning btn-sm mt-2">
                    <i class="fas fa-money-bill-wave"></i> Request Withdrawal
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('vendor.orders.earnings') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Earnings Details Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Earnings Details
        </h5>
    </div>
    <div class="card-body">
        @if($items->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-inbox fs-1 text-muted"></i>
                <h4 class="mt-3">No Earnings Yet</h4>
                <p class="text-muted">Your earnings will appear here once customers purchase your products.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order #</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Commission</th>
                            <th>Earning</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <small>{{ $item->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('vendor.orders.show', $item->order_id) }}">
                                    #{{ $item->order->order_number ?? $item->order_id }}
                                </a>
                            </td>
                            <td>
                                <strong>{{ $item->product->title ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>৳{{ number_format($item->sub_total, 2) }}</td>
                            <td>
                                <span class="text-danger">
                                    -৳{{ number_format($item->vendor_commission_amount, 2) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $item->vendor_commission_rate }}%</small>
                            </td>
                            <td>
                                <strong class="text-success">
                                    ৳{{ number_format($item->vendor_earning, 2) }}
                                </strong>
                            </td>
                            <td>
                                @if($item->vendor_paid)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Paid
                                    </span>
                                    @if($item->vendor_paid_at)
                                        <br><small class="text-muted">{{ $item->vendor_paid_at->format('d M Y') }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Totals:</strong></td>
                            <td><strong>৳{{ number_format($items->sum('sub_total'), 2) }}</strong></td>
                            <td><strong class="text-danger">-৳{{ number_format($items->sum('vendor_commission_amount'), 2) }}</strong></td>
                            <td><strong class="text-success">৳{{ number_format($items->sum('vendor_earning'), 2) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Info Alert -->
<div class="alert alert-info mt-4">
    <h5><i class="fas fa-info-circle"></i> About Earnings</h5>
    <ul class="mb-0">
        <li><strong>Total Sales:</strong> The total value of your products sold</li>
        <li><strong>Platform Commission:</strong> The fee charged by the platform ({{ $items->isNotEmpty() ? number_format($items->first()->vendor_commission_rate ?? 0, 1) : '0' }}% of sales)</li>
        <li><strong>Total Earnings:</strong> Your share after platform commission</li>
        <li><strong>Paid:</strong> Earnings already withdrawn to your account</li>
        <li><strong>Pending:</strong> Earnings available for withdrawal</li>
    </ul>
</div>
@endsection

