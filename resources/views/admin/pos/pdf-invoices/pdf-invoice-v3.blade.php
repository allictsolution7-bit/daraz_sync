<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Order #{{ $order->id }}</title>
    @php
        $primaryColor = $siteSettings['primary_color'] ?? '#1d72b8';
    @endphp
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            color: #2d3436;
            background: #f6f8fa;
            margin: 0;
            padding: 20px;
        }

        .invoice {
            max-width: 900px;
            background: #fff;
            margin: 0 auto;
            border: 1px solid {{ $primaryColor }};
            overflow: hidden;
        }
        .header {
            background: {{ $primaryColor }};
            color: white;
            text-align: center;
            padding: 10px 10px 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 0.5px;
        }

        .thank-you {
            font-size: 18px;
            margin-top: 10px;
            color: #f1f1f1;
        }

        .section {
            padding: 25px 30px;
            border-bottom: 1px solid #eaeaea;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: {{ $primaryColor }};
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 0;
            vertical-align: top;
        }

        .summary-label {
            color: #555;
            font-weight: 600;
            width: 30%;
        }

        .summary-value {
            color: #333;
        }

        .payment-box {
            background: #fffbe6;
            border: 1px solid #ffe58f;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 10px;
            color: #856404;
            font-weight: 600;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .products-table th {
            background: {{ $primaryColor }};
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .products-table td {
            padding: 10px 0;
            border-bottom: 1px solid #eaeaea;
        }

        .products-table td:last-child {
            text-align: right;
        }

        .product-name {
            font-weight: 600;
            color: #333;
        }

        .product-details {
            font-size: 13px;
            color: #777;
        }

        .totals {
            margin-top: 20px;
        }

        .totals-table {
            width: 100%;
            max-width: 350px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-row {
            border-bottom: 1px solid #f1f1f1;
        }

        .totals-label {
            padding: 6px 8px;
            color: #555;
            text-align: left;
            font-size: 14px;
        }

        .totals-amount {
            padding: 6px 8px;
            font-weight: 600;
            text-align: right;
            font-size: 14px;
        }

        .total-final-row {
            border-top: 2px solid {{ $primaryColor }};
            border-bottom: 2px solid {{ $primaryColor }};
            background: #f8f9fa;
        }

        .total-final-label {
            padding: 10px 8px;
            color: {{ $primaryColor }};
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }

        .total-final-amount {
            padding: 10px 8px;
            color: {{ $primaryColor }};
            font-weight: bold;
            font-size: 16px;
            text-align: right;
        }

        .footer {
            background: {{ $primaryColor }};
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
        }

        .footer a {
            color: #ffeaa7;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .note-box {
            background: #f8f9fa;
            border-left: 4px solid {{ $primaryColor }};
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 15px;
            color: #555;
        }

        /* Customer & Invoice Info */
        .customer-info-section {
            padding: 10px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
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
            color: #777;
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
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>{{ $siteSettings['site_name'] }}</h1>
            <div class="thank-you">Thank you {{ $order->name }}, your order has been received!</div>
        </div>

        <!-- Customer & Invoice Info -->
        <div class="customer-info-section">
            <table class="info-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="info-cell">
                        <div class="info-title">Bill To</div>
                        <div class="">{{ $order->name }}</div>
                        <br>
                        <div class="">Phone</div>
                        <div class="">{{ $order->phone ?? 'N/A' }}</div>
                        @if($order->address)
                        <br>
                        <div class="">Address</div>
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

        <div class="section">
            <div class="section-title">Order Summary</div>
            <table class="summary-table">
                <tr><td class="summary-label">Order ID:</td><td class="summary-value">#{{ $order->id }}</td></tr>
                <tr><td class="summary-label">Date:</td><td class="summary-value">{{ $order->created_at->format('F j, Y') }}</td></tr>
                <tr><td class="summary-label">Total:</td><td class="summary-value">BDT {{ number_format($order->total, 2) }}</td></tr>
                <tr><td class="summary-label">Payment Method:</td><td class="summary-value">{{ strtoupper($order->payment_method) }}</td></tr>
                @php
                    $consignmentId = $order->delivery_data['consignment_id'] ?? $order->delivery_data['tracking_code'] ?? null;
                    $courierProvider = $order->delivery_data['courier_provider'] ?? null;
                @endphp
                @if($consignmentId)
                    <tr><td class="summary-label">Consignment ID:</td><td class="summary-value">{{ $consignmentId }}</td></tr>
                    @if($courierProvider)
                        <tr><td class="summary-label">Courier:</td><td class="summary-value">{{ ucfirst($courierProvider) }}</td></tr>
                    @endif
                @endif
            </table>

            @if($order->payment_method)
            <div class="payment-box">
                @if($order->payment_method === 'cod')
                    Pay with Cash on Delivery.
                @elseif($order->payment_method === 'bkash')
                    bKash Payment<br>
                    <strong>Number:</strong> {{ $order->bkash_number }}<br>
                    <strong>Txn ID:</strong> {{ $order->bkash_transaction_id }}
                @elseif($order->payment_method === 'nagad')
                    Nagad Payment<br>
                    <strong>Number:</strong> {{ $order->nagad_number }}<br>
                    <strong>Txn ID:</strong> {{ $order->nagad_transaction_id }}
                @elseif($order->payment_method === 'rocket')
                    Rocket Payment<br>
                    <strong>Number:</strong> {{ $order->rocket_number }}<br>
                    <strong>Txn ID:</strong> {{ $order->rocket_transaction_id }}
                @endif
            </div>
            @endif
        </div>

        <div class="section">
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
                            <div class="product-details">
                                Qty: {{ $item->quantity }} × BDT{{ number_format($item->price, 2) }}
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

            <div class="totals">
                <table class="totals-table" cellpadding="0" cellspacing="0">
                    <tr class="totals-row">
                        <td class="totals-label">Subtotal:</td>
                        <td class="totals-amount">BDT {{ number_format($order->order_items->sum('sub_total'), 2) }}</td>
                    </tr>
                    @if($order->discount > 0)
                    <tr class="totals-row">
                        <td class="totals-label">Discount:</td>
                        <td class="totals-amount" style="color: #27ae60;">-BDT {{ number_format($order->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping > 0)
                    <tr class="totals-row">
                        <td class="totals-label">Shipping:</td>
                        <td class="totals-amount">BDT {{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-final-row">
                        <td class="total-final-label">Total:</td>
                        <td class="total-final-amount" style="text-align: right;">BDT {{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            @if($order->message)
            <div class="note-box">
                <strong>Order Note:</strong><br>
                {{ $order->message }}
            </div>
            @endif
        </div>

        <div class="footer">
            If you have any questions, contact us at 
            <a href="mailto:{{ $siteSettings['contact_email'] }}">{{ $siteSettings['contact_email'] }}</a>
        </div>
    </div>
</body>
</html>
