@extends('vendor.layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-shopping-cart"></i> My Orders</h2>
        <p class="text-muted">View orders containing your products (read-only)</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-success">
            <i class="fas fa-money-bill-wave"></i> View Earnings Report
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('vendor.orders.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="Search by order number or customer..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times-circle"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-body">
        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fs-1 text-muted"></i>
                <h4 class="mt-3">No Orders Found</h4>
                <p class="text-muted">You haven't received any orders yet.</p>
            </div>
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Note:</strong> You can view orders but cannot manage them. All order management is handled by the platform admin.
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Your Items</th>
                            <th>Your Earning</th>
                            <th>Status</th>
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
                                <strong>#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                <strong>{{ $order->customer->name ?? 'Guest' }}</strong><br>
                                <small class="text-muted">{{ $order->customer->email ?? '-' }}</small>
                            </td>
                            <td>
                                {{ $order->created_at->format('d M Y') }}<br>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <strong>৳{{ number_format($order->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $vendorItems->count() }} items</span><br>
                                <small class="text-muted">৳{{ number_format($vendorItems->sum('sub_total'), 2) }}</small>
                            </td>
                            <td>
                                @if($vendorEarning > 0)
                                    <strong class="text-success">৳{{ number_format($vendorEarning, 2) }}</strong>
                                    @if($vendorItems->where('vendor_paid', true)->count() > 0)
                                        <br><span class="badge bg-success">Paid</span>
                                    @else
                                        <br><span class="badge bg-warning text-dark">Unpaid</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-info">Processing</span>
                                        @break
                                    @case('shipped')
                                        <span class="badge bg-primary">Shipped</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success">Delivered</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                            <td class="text-end">
                                <a href="{{ route('vendor.orders.show', $order) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

