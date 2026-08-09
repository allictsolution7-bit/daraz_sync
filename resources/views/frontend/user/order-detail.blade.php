@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        /* Order detail page specific styles */
        .order-container {
            margin: 30px auto;
            padding: 0 20px;
        }

        .order-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }

        .order-card {
            background-color: #fff;
            box-shadow: var(--shadow-md);
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .order-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8f9fa;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .order-body {
            padding: 25px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .order-info-section h5 {
            font-size: 16px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .order-info-section p {
            margin-bottom: 10px;
        }

        .order-info-section strong {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-table th,
        .order-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid var(--border-color);
        }

        .order-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .order-table tfoot td {
            font-weight: 500;
        }

        .order-table tfoot tr:last-child td {
            font-weight: 700;
        }

        .text-end {
            text-align: right;
        }

        .product-item {
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
            border: 1px solid var(--border-color);
        }

        .order-back-btn {
            display: inline-block;
            background-color:  var(--primary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
        }

        .order-back-btn:hover {
            background-color: #5a6268;
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        /* Download button styles */
        .download-btn {
            display: inline-flex;
            align-items: center;
            background-color: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
            margin-top: 8px;
        }

        .download-btn i {
            margin-right: 5px;
        }

        .download-btn:hover {
            background-color: #218838;
            color: white;
        }

        @media (max-width: 768px) {
            .order-layout {
                grid-template-columns: 1fr;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .order-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('content')
    <div class="base-container order-container">
        <div class="order-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content -->
            <div class="order-card">
                <div class="order-header">
                    <h4>Order #{{ $order->id }}</h4>
                    <a href="{{ route('account.orders') }}" class="order-back-btn">Back to Orders</a>
                </div>
                <div class="order-body">
                    <div class="order-info-grid">
                        <div class="order-info-section">
                            <h5>Order Information</h5>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            <p><strong>Order Status:</strong>
                                <span
                                    class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'processing' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'N/A') }}</p>
                        </div>
                        <div class="order-info-section">
                            <h5>Shipping Information</h5>
                            <p><strong>Name:</strong> {{ $order->name ?? $user->name }}</p>
                            <p><strong>Phone:</strong> {{ $order->phone ?? $user->phone }}</p>
                            <p><strong>Address:</strong> {{ $order->address ?? $user->address }}</p>
                            <p><strong>City:</strong> {{ $order->city ?? $user->city }}</p>
                        </div>
                    </div>

                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->order_items as $item)
                                    <tr>
                                        <td>
                                            <div class="product-item">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        alt="{{ $item->product->title }}" class="product-image">
                                                @endif
                                                <div>
                                                    {{ $item->product ? $item->product->title : 'Product not available' }}
                                                    
                                                    @if($order->status == 'ready_for_delivery' && $item->product && $item->product->product_type == 'digital' && $item->product->digital_file)
                                                        <div>
                                                            <a href="{{ route('account.download.digital.product', ['order_id' => $order->id, 'product_id' => $item->product->id]) }}" class="download-btn">
                                                                 <i class="fas fa-download"></i> Download File
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @php
                                                        $canReturn = false;
                                                        $returnDaysLeft = 0;
                                                        $policyText = '';
                                                        
                                                        if (strtolower($order->status) === 'delivered' && $item->product && ($item->product->return_period ?? 0) > 0) {
                                                            $deliveredDate = \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at);
                                                            $expiryDate = $deliveredDate->copy()->addDays($item->product->return_period);
                                                            
                                                            if (now()->lessThanOrEqualTo($expiryDate)) {
                                                                $canReturn = true;
                                                                $returnDaysLeft = ceil(now()->diffInDays($expiryDate, false));
                                                                if ($returnDaysLeft < 0) {
                                                                    $canReturn = false;
                                                                }
                                                            }
                                                            
                                                            if ($item->product->vendor_id) {
                                                                $vendorUser = \App\Models\User::find($item->product->vendor_id);
                                                                if ($vendorUser && $vendorUser->vendorSettings && !empty($vendorUser->vendorSettings->return_policy['policy_text'])) {
                                                                    $policyText = $vendorUser->vendorSettings->return_policy['policy_text'];
                                                                }
                                                            }
                                                            
                                                            if (empty($policyText)) {
                                                                $policyText = setting('general', 'default_return_policy', "If you are not satisfied with your purchase, you can return it within the specified return period. The product must be unused and in its original packaging. Once received, refund will be processed to your payment method.");
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($canReturn)
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-warning fw-bold text-white px-3 py-1.5 rounded" style="font-size: 12px; background-color: #f59e0b; border: none; border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#returnPolicyModal_{{ $item->id }}">
                                                                <i class="fas fa-undo me-1"></i> Easy Return ({{ $returnDaysLeft }} days left)
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Return Policy Modal -->
                                                        <div class="modal fade" id="returnPolicyModal_{{ $item->id }}" tabindex="-1" aria-hidden="true" style="text-transform: none;">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                                                    <div class="modal-header border-0 px-4 py-3 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <i class="fas fa-undo-alt fs-5"></i>
                                                                            <h5 class="modal-title fw-bold mb-0 text-white" style="font-size: 16px;">Product Return Policy</h5>
                                                                        </div>
                                                                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4 bg-light">
                                                                        <div class="p-3 bg-white border rounded-3 mb-3 text-start">
                                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                                <span class="text-secondary fw-semibold fs-7" style="font-size: 13px;">Easy Return Period:</span>
                                                                                <span class="badge bg-warning text-white fw-bold px-2.5 py-1 rounded-pill" style="font-size: 12px;">{{ $item->product->return_period }} Days</span>
                                                                            </div>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <span class="text-secondary fw-semibold fs-7" style="font-size: 13px;">Return Window Expiry:</span>
                                                                                <span class="text-dark fw-bold fs-7" style="font-size: 13px;">{{ \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at)->addDays($item->product->return_period)->format('M d, Y') }}</span>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="text-start">
                                                                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-circle-info text-primary me-1"></i> Return Instructions & Terms:</h6>
                                                                            <div class="p-3 bg-white border rounded-3 text-muted fs-7" style="line-height: 1.6; white-space: pre-line; font-size: 13px;">
                                                                                {{ $policyText }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer border-0 px-4 pb-4 pt-0 justify-content-end">
                                                                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 8px; font-size: 14px;">Got It</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>৳{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>৳{{ number_format($order->subtotal ?? $order->total, 2) }}</td>
                                </tr>
                                @if (isset($order->shipping_cost) && $order->shipping_cost > 0)
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td>৳{{ number_format($order->shipping_cost, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>৳{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection