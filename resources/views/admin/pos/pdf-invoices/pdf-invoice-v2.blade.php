<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - Order #{{ $order->id }}</title>
    @php
        $primaryColor = $siteSettings['primary_color'] ?? '#e74c3c';
    @endphp
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #2c3e50;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        
        .invoice-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid {{ $primaryColor }};
        }
        
        /* Header Section */
        .invoice-header {
            background: {{ $primaryColor }};
            color: white;
            padding: 10px 15px;
        }
        
        .company-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoice-title {
            font-size: 13px;
            text-transform: capitalize;
        }
        
        /* Customer Info Section */
        .info-section {
            width: auto;
            padding: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .info-table {
            width: 99%;
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
            font-size: 10px;
            text-transform: uppercase;
            color: #95a5a6;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        
        .info-content {
            font-size: 13px;
            color: #2c3e50;
            font-weight: bold;
            margin: 0;
            padding: 2px 0 0 0;
        }
        
        /* Order Summary Cards */
        .summary-cards {
            width: auto;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-card {
            width: 25%;
            padding: 8px;
            text-align: center;
            vertical-align: top;
        }
        
        .card-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #7f8c8d;
            margin-bottom: 4px;
        }
        
        .card-value {
            font-size: 14px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        
        /* Payment Method Badge */
        .payment-badge {
            background: {{ $primaryColor }};
            color: white;
            padding: 4px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid {{ $primaryColor }};
        }
        
        /* Payment Details Box */
        .payment-details {
            background: #fff9e6;
            padding: 10px 15px;
            margin: 0;
            border-top: 1px solid #f39c12;
            border-bottom: 1px solid #f39c12;
        }
        
        .payment-details-text {
            color: #7f6514;
            font-size: 11px;
            line-height: 1.7;
        }
        
        .payment-details-text strong {
            color: #5c4a0f;
            font-weight: bold;
        }
        
        /* Products Section */
        .products-section {
            padding: 15px;
        }
        
        .section-header {
            font-size: 13px;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid {{ $primaryColor }};
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .products-table thead {
            background: #34495e;
            color: white;
        }
        
        .products-table th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            border: 1px solid #2c3e50;
        }
        
        .products-table .text-right {
            text-align: right;
        }
        
        .products-table td {
            padding: 10px 6px;
            border-bottom: 1px solid #ecf0f1;
            vertical-align: top;
        }
        
        .products-table .amount-cell {
            text-align: right;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        
        .product-title {
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        
        .product-meta {
            font-size: 10px;
            color: #7f8c8d;
        }
        
        /* Totals Section */
        .totals-wrapper {
            padding: 0 15px 15px 15px;
        }
        
        .totals-table {
            width: 100%;
            max-width: 350px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .total-row {
            border-bottom: 1px solid #ecf0f1;
        }
        
        .total-label {
            padding: 6px 8px;
            font-size: 12px;
            color: #7f8c8d;
            text-align: left;
        }
        
        .total-amount {
            padding: 6px 8px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .grand-total-row {
            border-top: 2px solid {{ $primaryColor }};
            border-bottom: 2px solid {{ $primaryColor }};
            background: #fff5f5;
        }
        
        .grand-total-label {
            padding: 10px 8px;
            font-size: 14px;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
        }
        
        .grand-total-amount {
            padding: 10px 8px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: {{ $primaryColor }};
        }
        
        /* Order Notes */
        .notes-section {
            padding: 15px;
            background: #f8f9fa;
            border-top: 2px solid #ecf0f1;
        }
        
        .notes-title {
            font-size: 11px;
            font-weight: bold;
            color: {{ $primaryColor }};
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        
        .notes-content {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.6;
        }
        
        /* Footer */
        .invoice-footer {
            background: #34495e;
            color: white;
            padding: 12px 15px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 11px;
            line-height: 1.6;
        }
        
        .footer-email {
            color: #74b9ff;
            text-decoration: none;
            font-weight: bold;
        }
        
        .divider {
            height: 1px;
            background: #ecf0f1;
            margin: 0;
            border: none;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-name">{{ $siteSettings['site_name'] }}</div>
            <div class="invoice-title">Thank you {{ $order->name }}, your order has been received!</div>
        </div>
        
        <!-- Customer & Order Info -->
        @php
            $consignmentId = $order->delivery_data['consignment_id'] ?? $order->delivery_data['tracking_code'] ?? null;
            $courierProvider = $order->delivery_data['courier_provider'] ?? null;
        @endphp
        <div class="info-section">
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
                        <div class="info-content">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <br>
                        <div class="info-title">Invoice Date</div>
                        <div class="info-content">{{ $order->created_at->format('d M, Y') }}</div>
                        <br>
                        <div class="info-title">Payment Status</div>
                        <div class="info-content">
                            <span class="payment-badge">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                        @if($consignmentId)
                        <br>
                        <div class="info-title">Consignment ID</div>
                        <div class="info-content">{{ $consignmentId }}</div>
                            @if($courierProvider)
                            <br>
                            <div class="info-title">Courier</div>
                            <div class="info-content">{{ ucfirst($courierProvider) }}</div>
                            @endif
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Summary Cards -->
        <div class="summary-cards">
            <table class="summary-table">
                <tr>
                    <td class="summary-card">
                        <div class="card-label">Order ID</div>
                        <div class="card-value">{{ $order->id }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="card-label">Items</div>
                        <div class="card-value">{{ $order->order_items->count() }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="card-label">Subtotal</div>
                        <div class="card-value">BDT {{ number_format($order->order_items->sum('sub_total'), 2) }}</div>
                    </td>
                    <td class="summary-card">
                        <div class="card-label">Total Amount</div>
                        <div class="card-value">BDT {{ number_format($order->total, 2) }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Payment Method Details -->
        @if($order->payment_method === 'cod')
        <div class="payment-details">
            <div class="payment-details-text">
                <strong>Payment Method:</strong> Cash on Delivery (COD)
            </div>
        </div>
        @elseif($order->payment_method === 'bkash' && $order->bkash_number)
        <div class="payment-details">
            <div class="payment-details-text">
                <strong>Payment Method:</strong> bKash<br>
                <strong>bKash Number:</strong> {{ $order->bkash_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->bkash_transaction_id }}
                @if($order->bkash_charge > 0)
                    <br><strong>Service Charge:</strong> BDT {{ number_format($order->bkash_charge, 2) }}
                @endif
            </div>
        </div>
        @elseif($order->payment_method === 'nagad' && $order->nagad_number)
        <div class="payment-details">
            <div class="payment-details-text">
                <strong>Payment Method:</strong> Nagad<br>
                <strong>Nagad Number:</strong> {{ $order->nagad_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->nagad_transaction_id }}
                @if($order->nagad_charge > 0)
                    <br><strong>Service Charge:</strong> BDT {{ number_format($order->nagad_charge, 2) }}
                @endif
            </div>
        </div>
        @elseif($order->payment_method === 'rocket' && $order->rocket_number)
        <div class="payment-details">
            <div class="payment-details-text">
                <strong>Payment Method:</strong> Rocket<br>
                <strong>Rocket Number:</strong> {{ $order->rocket_number }}<br>
                <strong>Transaction ID:</strong> {{ $order->rocket_transaction_id }}
                @if($order->rocket_charge > 0)
                    <br><strong>Service Charge:</strong> BDT {{ number_format($order->rocket_charge, 2) }}
                @endif
            </div>
        </div>
        @endif
        
        <hr class="divider">
        
        <!-- Products -->
        <div class="products-section">
            <div class="section-header">Order Items</div>
            
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Description</th>
                        <th style="width: 15%;">Quantity</th>
                        <th style="width: 17%;">Unit Price</th>
                        <th class="text-right" style="width: 18%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $item)
                    <tr>
                        <td>
                            <div class="product-title">{{ $item->product->title ?? 'Product' }}</div>
                            @if($item->variationCombination)
                                <div class="product-meta">{{ $item->variationCombination->display_name }}</div>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>BDT {{ number_format($item->price, 2) }}</td>
                        <td class="amount-cell">BDT {{ number_format($item->sub_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="totals-wrapper">
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
            
            <table class="totals-table">
                <tr class="total-row">
                    <td class="total-label">Subtotal</td>
                    <td class="total-amount">BDT {{ number_format($subtotal, 2) }}</td>
                </tr>
                
                @if($discount > 0)
                <tr class="total-row">
                    <td class="total-label">Discount</td>
                    <td class="total-amount" style="color: #27ae60;">-BDT {{ number_format($discount, 2) }}</td>
                </tr>
                @endif
                
                @if($shipping > 0)
                <tr class="total-row">
                    <td class="total-label">Shipping Charge</td>
                    <td class="total-amount">BDT {{ number_format($shipping, 2) }}</td>
                </tr>
                @endif
                
                @if($paymentCharge > 0)
                <tr class="total-row">
                    <td class="total-label">
                        @if($order->payment_method === 'bkash')
                            bKash Charge
                        @elseif($order->payment_method === 'nagad')
                            Nagad Charge
                        @elseif($order->payment_method === 'rocket')
                            Rocket Charge
                        @else
                            Payment Charge
                        @endif
                    </td>
                    <td class="total-amount">BDT {{ number_format($paymentCharge, 2) }}</td>
                </tr>
                @endif
                
                <tr class="grand-total-row">
                    <td class="grand-total-label">Total</td>
                    <td class="grand-total-amount">BDT {{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Order Notes -->
        @if($order->message)
        <div class="notes-section">
            <div class="notes-title">Order Notes</div>
            <div class="notes-content">{{ $order->message }}</div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-text">
                For any queries, contact us at <a href="mailto:{{ $siteSettings['contact_email'] }}" class="footer-email">{{ $siteSettings['contact_email'] }}</a>
            </div>
        </div>
    </div>
</body>
</html>
