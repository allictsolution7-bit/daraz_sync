<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - Order #{{ $order->id }}</title>
    @php
        $primaryColor = $siteSettings['primary_color'] ?? '#2c5aa0';
    @endphp
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
            color: #333;
            background: #fff;
        }
        
        .invoice {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid {{ $primaryColor }};
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 0px;
            padding-bottom: 15px;
        }
        
        .thank-you-message {
            font-size: 22px;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin-bottom: 0px;
        }
        
        .order-received {
            font-size: 16px;
            color: #666;
        }
        
        /* Customer Info Section */
        .customer-info-section {
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-cell {
            width: 50%;
            vertical-align: top;
            padding: 0 15px;
        }
        
        .info-cell-right {
            text-align: right;
        }
        
        .info-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #666;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        
        .info-content {
            font-size: 14px;
            color: {{ $primaryColor }};
            font-weight: bold;
            margin: 0;
            padding: 2px 0 0 0;
        }
        
        .invoice-number-badge {
            background: {{ $primaryColor }};
            color: white;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid {{ $primaryColor }};
        }
        
        .order-summary {
            background: #f8f9fa;
            padding: 5px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 7px 8px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .summary-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            min-width: 150px;
        }
        
        .summary-value {
            display: inline-block;
            text-align: left;
            margin-left: 10px;
        }
        
        .payment-method-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-left: 4px solid #fdcb6e;
            padding: 4px 7px;
            margin: 5px 0px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        
        .payment-method-text {
            color: #856404;
            font-weight: 700;
        }
        
        .order-details-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .products-table th {
            background: {{ $primaryColor }};
            color: white;
            padding: 10px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }
        
        .products-table th:last-child {
            text-align: right;
        }
        
        .products-table td {
            padding: 10px 0px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .products-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
        
        .product-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .product-details {
            font-size: 14px;
            color: #666;
        }
        
        .totals-section {
            margin-top: 10px;
            border: 1px solid {{ $primaryColor }};
            padding: 15px;
            background: #f8f9fa;
        }
        
        .totals-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        
        .totals-label {
            display: table-cell;
            text-align: left;
            font-size: 16px;
            color: #666;
            width: 70%;
        }
        
        .totals-amount {
            display: table-cell;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .total-final {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid {{ $primaryColor }};
        }
        
        .total-final .totals-label {
            font-size: 16px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        
        .total-final .totals-amount {
            font-size: 16px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        
        .footer {
            margin-top: 10px;
            text-align: center;
            padding: 12px;
            background: #2c3e50;
            color: white;
            border-radius: 5px;
        }
        
        .footer-text {
            font-size: 14px;
            margin: 0;
        }
        
        .contact-email {
            color: #74b9ff;
            text-decoration: none;
        }
        
        .bangla {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <!-- Header -->
        <div class="header">
            <div style="font-size: 24px; font-weight: bold; color: #2c5aa0; margin-bottom: 5px;">
                {{ $siteSettings['site_name'] }}
            </div>
            <div class="thank-you-message bangla">
                Thank You {{ $order->name }}. Your Order Has Been Received.
            </div>
            <div class="order-received">
                Your order has been received.
            </div>
        </div>

        <!-- Customer & Invoice Info -->
        <div class="customer-info-section">
            <table class="info-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="info-cell">
                        <div class="info-title">Bill To</div>
                        <div class="">{{ $order->name }}</div>
                        <br>
                        <div class="info-title">Phone</div>
                        <div class="">{{ $order->phone ?? 'N/A' }}</div>
                        @if($order->address)
                        <br>
                        <div class="info-title">Address</div>
                        <div class="">{{ $order->address }}</div>
                        @endif
                    </td>
                    <td class="info-cell info-cell-right">
                        <div class="info-title">Invoice Number</div>
                        <div class="info-content">
                            <span class="invoice-number-badge">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <br>
                        <div class="info-title">Invoice Date</div>
                        <div class="info-content">{{ $order->created_at->format('d M, Y') }}</div>
                        <br>
                        <div class="info-title">Order Status</div>
                        <div class="info-content">{{ ucfirst($order->status ?? 'Pending') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Order Summary -->
        @php
            $consignmentId = $order->delivery_data['consignment_id'] ?? $order->delivery_data['tracking_code'] ?? null;
            $courierProvider = $order->delivery_data['courier_provider'] ?? null;
        @endphp
        <div class="order-summary">
            <div class="summary-grid">
                <div class="summary-row">
                    <div class="summary-cell">
                        <span class="summary-label">Order ID:</span>
                        <span class="summary-value">{{ $order->id }}</span>
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-cell">
                        <span class="summary-label">Date:</span>
                        <span class="summary-value">{{ $order->created_at->format('F j, Y') }}</span>
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-cell">
                        <span class="summary-label">Total:</span>
                        <span class="summary-value">BDT {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                <div class="summary-row">
                    <div class="summary-cell">
                        <span class="summary-label">Payment method:</span>
                        <span class="summary-value">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                </div>
                @if($consignmentId)
                <div class="summary-row">
                    <div class="summary-cell">
                        <span class="summary-label">Consignment ID:</span>
                        <span class="summary-value">{{ $consignmentId }}</span>
                    </div>
                </div>
                    @if($courierProvider)
                    <div class="summary-row">
                        <div class="summary-cell">
                            <span class="summary-label">Courier:</span>
                            <span class="summary-value">{{ ucfirst($courierProvider) }}</span>
                        </div>
                    </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Payment Method Info -->
        @if($order->payment_method === 'cod')
        <div class="payment-method-box">
            <div class="payment-method-text">Pay with COD.</div>
        </div>
        @elseif($order->payment_method === 'bkash' && $order->bkash_number)
        <div class="payment-method-box">
            <div class="payment-method-text">
                Pay with bKash.<br>
                <strong>bKash Number:</strong> {{ $order->bkash_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->bkash_transaction_id }}
                @if($order->bkash_charge > 0)
                    <br><strong>bKash Charge:</strong> BDT {{ number_format($order->bkash_charge, 2) }}
                @endif
            </div>
        </div>
        @elseif($order->payment_method === 'nagad' && $order->nagad_number)
        <div class="payment-method-box">
            <div class="payment-method-text">
                Pay with Nagad.<br>
                <strong>Nagad Number:</strong> {{ $order->nagad_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->nagad_transaction_id }}
                @if($order->nagad_charge > 0)
                    <br><strong>Nagad Charge:</strong> BDT {{ number_format($order->nagad_charge, 2) }}
                @endif
            </div>
        </div>
        @elseif($order->payment_method === 'rocket' && $order->rocket_number)
        <div class="payment-method-box">
            <div class="payment-method-text">
                Pay with Rocket.<br>
                <strong>Rocket Number:</strong> {{ $order->rocket_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->rocket_transaction_id }}
                @if($order->rocket_charge > 0)
                    <br><strong>Rocket Charge:</strong> BDT {{ number_format($order->rocket_charge, 2) }}
                @endif
            </div>
        </div>
        @endif

        <!-- Order Details Section -->
        <div class="order-details-section">
            <div class="section-title">Order Details</div>
            
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product->title ?? 'Product' }}</div>
                            <div class="product-details" >
                                Quantity: {{ $item->quantity }} × BDT{{ number_format($item->price, 2) }}
                                @if($item->variationCombination)
                                    <br>{{ $item->variationCombination->display_name }}
                                @endif
                            </div>
                        </td>
                        <td>BDT {{ number_format($item->sub_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            @php
                $subtotal = $order->order_items->sum('sub_total');
                $discount = $order->discount ?? 0;
                $shipping = $order->shipping ?? 0;
                $paymentCharge = 0;
                
                if($order->payment_method === 'bkash') {
                    $paymentCharge = $order->bkash_charge ?? 0;
                } elseif($order->payment_method === 'nagad') {
                    $paymentCharge = $order->nagad_charge ?? 0;
                } elseif($order->payment_method === 'rocket') {
                    $paymentCharge = $order->rocket_charge ?? 0;
                }
            @endphp
            
            <div class="totals-row">
                <div class="totals-label">Subtotal:</div>
                <div class="totals-amount">BDT {{ number_format($subtotal, 2) }}</div>
            </div>
            
            @if($discount > 0)
            <div class="totals-row">
                <div class="totals-label">Discount:</div>
                <div class="totals-amount">-BDT {{ number_format($discount, 2) }}</div>
            </div>
            @endif
            
            @if($shipping > 0)
            <div class="totals-row">
                <div class="totals-label">Shipping:</div>
                <div class="totals-amount">BDT {{ number_format($shipping, 2) }}</div>
            </div>
            @endif
            
            @if($paymentCharge > 0)
            <div class="totals-row">
                <div class="totals-label">
                    @if($order->payment_method === 'bkash')
                        bKash Charge:
                    @elseif($order->payment_method === 'nagad')
                        Nagad Charge:
                    @elseif($order->payment_method === 'rocket')
                        Rocket Charge:
                    @else
                        Payment Charge:
                    @endif
                </div>
                <div class="totals-amount">BDT {{ number_format($paymentCharge, 2) }}</div>
            </div>
            @endif
            
            <div class="totals-row total-final">
                <div class="totals-label">TOTAL:</div>
                <div class="totals-amount">BDT {{ number_format($order->total, 2) }}</div>
            </div>
        </div>

        @if($order->message)
        <div class="order-summary" style="margin-top: 30px;">
            <div class="section-title">Order Notes</div>
            <p style="font-size: 16px; line-height: 1.6; color: #666;">{{ $order->message }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                If you have any questions, please contact us at 
                <a href="mailto:{{ $siteSettings['contact_email'] }}" class="contact-email">{{ $siteSettings['contact_email'] }}</a>
            </p>
        </div>
    </div>
</body>
</html>
