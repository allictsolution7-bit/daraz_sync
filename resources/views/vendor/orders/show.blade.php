@extends('vendor.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-shopping-cart"></i> Order #{{ $order->order_number }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active">#{{ $order->order_number }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Read-Only:</strong> You can view this order but cannot modify it. All order management is handled by the platform.
</div>

<div class="row">
    <!-- Order Items -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-box"></i> Your Items in This Order</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                                <th>Commission</th>
                                <th>Your Earning</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendorItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->title }}</strong>
                                    @if($item->variationCombination)
                                        <br><small class="text-muted">{{ $item->variationCombination->name }}</small>
                                    @endif
                                </td>
                                <td>৳{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>৳{{ number_format($item->sub_total, 2) }}</td>
                                <td>
                                    @if($item->vendor_commission_rate)
                                        {{ $item->vendor_commission_rate }}%<br>
                                        <small class="text-muted">(৳{{ number_format($item->vendor_commission_amount, 2) }})</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">৳{{ number_format($item->vendor_earning, 2) }}</strong>
                                </td>
                                <td>
                                    @if($item->vendor_paid)
                                        <span class="badge bg-success">Paid</span>
                                        <br><small class="text-muted">{{ $item->vendor_paid_at->format('d M Y') }}</small>
                                    @else
                                        <span class="badge bg-warning text-dark">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end">Total Your Earnings:</th>
                                <th>৳{{ number_format($vendorItems->sum('vendor_earning'), 2) }}</th>
                                <th>
                                    @if($vendorItems->where('vendor_paid', true)->count() === $vendorItems->count())
                                        <span class="badge bg-success">All Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Info -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Order Number:</th>
                        <td>#{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
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
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td><strong>৳{{ number_format($order->total_amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Customer Information</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->customer->name ?? 'Guest' }}</strong></p>
                <p class="mb-1"><small class="text-muted">{{ $order->customer->email ?? '-' }}</small></p>
                <p class="mb-1"><small class="text-muted">{{ $order->customer->phone ?? '-' }}</small></p>
                
                <hr>
                
                <p class="mb-1"><strong>Shipping Address:</strong></p>
                <address class="mb-0">
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                    {{ $order->shipping_zip }}
                </address>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>
@endsection

