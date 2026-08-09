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
                                                        $timeString = "";
                                                        $policyText = '';
                                                        
                                                        if (strtolower($order->status) === 'delivered' && $item->product && ($item->product->return_period ?? 0) > 0) {
                                                            $deliveredDate = \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at);
                                                            $expiryDate = $deliveredDate->copy()->addDays($item->product->return_period);
                                                            
                                                            if (now()->lessThanOrEqualTo($expiryDate)) {
                                                                $canReturn = true;
                                                                $diff = now()->diff($expiryDate);
                                                                if ($diff->days > 0) {
                                                                    $timeString .= $diff->days . 'd ';
                                                                }
                                                                $timeString .= $diff->h . 'h';
                                                            }
                                                            
                                                            if ($item->product->vendor_id) {
                                                                $vendorUser = \App\Models\User::find($item->product->vendor_id);
                                                                if ($vendorUser && $vendorUser->vendorSettings && !empty($vendorUser->vendorSettings->return_policy['policy_text'])) {
                                                                    $policyText = $vendorUser->vendorSettings->return_policy['policy_text'];
                                                                }
                                                            }
                                                            
                                                            if (empty($policyText)) {
                                                                $policyText = "If you are not satisfied with your purchase, you can return it within the specified return period under the following conditions:\n\n" .
                                                                    "1. Return Acceptance Rules:\n" .
                                                                    "- The product must be unused, unwashed, and in its original condition.\n" .
                                                                    "- All original tags, user manuals, warranty cards, and accessories must be intact.\n" .
                                                                    "- The product must be returned in its original manufacturer packaging/box.\n" .
                                                                    "- Returns are accepted for damaged, defective, or incorrect products delivered.\n\n" .
                                                                    "2. Refund & Payback Process:\n" .
                                                                    "- Once we receive the returned item, it will undergo a quality inspection.\n" .
                                                                    "- Upon approval, the refund will be processed to your original payment method within 3 to 7 business days.\n" .
                                                                    "- If the order was paid via Cash on Delivery, the refund will be sent via bKash/Nagad/Rocket.";
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                     @if($canReturn)
                                                         <div class="mt-2">
                                                             <button type="button" class="btn btn-sm btn-warning fw-bold text-white px-3 py-1.5 rounded shadow-sm" style="font-size: 12px; background-color: #f59e0b; border: none; border-radius: 6px; cursor: pointer;" onclick="document.getElementById('returnPolicyModal_{{ $item->id }}').style.display='flex';">
                                                                 <i class="fas fa-undo me-1"></i> Return ({{ $timeString }})
                                                             </button>
                                                         </div>
                                                         
                                                         <!-- Return Policy Modal -->
                                                         <div id="returnPolicyModal_{{ $item->id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 99999; align-items: center; justify-content: center; padding: 15px; text-transform: none; text-align: left; color: #334155;">
                                                             <div style="background: #fff; width: 100%; max-width: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; flex-direction: column; max-height: 90vh;">
                                                                 <!-- Header -->
                                                                 <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 16px 24px; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                                                                     <div style="display: flex; align-items: center; gap: 8px;">
                                                                         <i class="fas fa-undo-alt" style="font-size: 18px;"></i>
                                                                         <h5 style="margin: 0; font-weight: bold; font-size: 16px; color: #fff;">Product Return Policy</h5>
                                                                     </div>
                                                                     <button type="button" onclick="document.getElementById('returnPolicyModal_{{ $item->id }}').style.display='none';" style="background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
                                                                 </div>

                                                                 <!-- Step 1: Policy Instructions -->
                                                                 <div id="returnDetailStep1_{{ $item->id }}" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                                                                     <div style="padding: 24px; overflow-y: auto; flex: 1;">
                                                                         <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                                                                             <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                                                 <span style="color: #64748b; font-size: 13px;">Delivered On:</span>
                                                                                 <span style="color: #1e293b; font-weight: bold; font-size: 13px;">{{ \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at)->format('M d, Y') }}</span>
                                                                             </div>
                                                                             <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                                                 <span style="color: #64748b; font-size: 13px;">Easy Return Period:</span>
                                                                                 <span style="background: #f59e0b; color: #fff; font-size: 12px; font-weight: bold; padding: 4px 8px; border-radius: 99px; background-color: #f59e0b;">{{ $item->product->return_period }} Days</span>
                                                                             </div>
                                                                             <div style="display: flex; justify-content: space-between; align-items: center;">
                                                                                 <span style="color: #64748b; font-size: 13px;">Return Window Expiry:</span>
                                                                                 <span style="color: #1e293b; font-weight: bold; font-size: 13px;">{{ \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at)->addDays($item->product->return_period)->format('M d, Y') }}</span>
                                                                             </div>
                                                                         </div>
                                                                         
                                                                         <div>
                                                                             <h6 style="margin: 0 0 8px 0; font-weight: bold; color: #1e293b; font-size: 14px;"><i class="fas fa-circle-info text-primary" style="margin-right: 4px;"></i> Return Instructions & Terms:</h6>
                                                                             <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; color: #475569; font-size: 13px; line-height: 1.6; white-space: pre-line; max-height: 120px; overflow-y: auto; background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                                                                                 {{ $policyText }}
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                     <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                                                                         <button type="button" onclick="document.getElementById('returnPolicyModal_{{ $item->id }}').style.display='none';" style="background: #e2e8f0; border: none; color: #475569; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Cancel</button>
                                                                         <button type="button" onclick="document.getElementById('returnDetailStep1_{{ $item->id }}').style.display='none'; document.getElementById('returnDetailStep2_{{ $item->id }}').style.display='flex';" style="background: #f59e0b; border: none; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Proceed to Return</button>
                                                                     </div>
                                                                 </div>

                                                                 <!-- Step 2: Reason Submission Form -->
                                                                 <div id="returnDetailStep2_{{ $item->id }}" style="display: none; flex-direction: column; flex: 1; overflow: hidden;">
                                                                     <form action="{{ route('account.order.return', $order->id) }}" method="POST" style="margin: 0; display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                                                                         @csrf
                                                                         <div style="padding: 24px; flex: 1; overflow-y: auto;">
                                                                             <div style="display: flex; flex-direction: column; gap: 8px;">
                                                                                 <label style="font-weight: bold; color: #1e293b; font-size: 14px;">Reason for Return</label>
                                                                                 <textarea name="reason" rows="5" placeholder="Please describe the reason for returning this item..." required style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; font-size: 13.5px; width: 100%; box-sizing: border-box; resize: vertical;"></textarea>
                                                                             </div>
                                                                         </div>
                                                                         <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                                                                             <button type="button" onclick="document.getElementById('returnDetailStep2_{{ $item->id }}').style.display='none'; document.getElementById('returnDetailStep1_{{ $item->id }}').style.display='flex';" style="background: #e2e8f0; border: none; color: #475569; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Back</button>
                                                                             <button type="submit" style="background: #ef4444; border: none; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Submit Return</button>
                                                                         </div>
                                                                     </form>
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