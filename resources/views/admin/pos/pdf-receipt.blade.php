<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .receipt {
            width: 100%;
            max-width: 80mm;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .store-details {
            font-size: 10px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 10px;
        }
        
        .order-info div {
            width: 50%;
        }
        
        .order-info .right {
            text-align: right;
        }
        
        .order-number {
            font-weight: bold;
            font-size: 14px;
        }
        
        .customer-info {
            margin-bottom: 12px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 3px;
        }
        
        .customer-info h4 {
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .customer-info p {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        
        .items-table th {
            background: #333;
            color: white;
            padding: 5px 3px;
            font-size: 9px;
            text-align: left;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 4px 3px;
            font-size: 9px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .variation {
            font-size: 8px;
            color: #666;
            font-style: italic;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .totals {
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-bottom: 12px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .total-row.final {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #666;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .payment-info {
            background: #f0f0f0;
            padding: 8px;
            border-radius: 3px;
            margin-bottom: 12px;
        }
        
        .payment-info h4 {
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .payment-method {
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .gateway-details {
            margin-top: 5px;
            font-size: 9px;
        }
        
        .gateway-details p {
            margin-bottom: 2px;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #999;
        }
        
        .thank-you {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .footer-note {
            font-size: 8px;
            color: #666;
            line-height: 1.3;
        }
        
        .bangla {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        
        /* Table styling for better PDF rendering */
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">{{ $siteSettings['site_name'] }}</div>
            <div class="store-details bangla">
                {{ $siteSettings['site_name'] }}<br>
                {{ $siteSettings['address'] }}<br>
                Phone : {{ $siteSettings['phone_number'] }}
            </div>
            <div class="receipt-title">RECEIPT</div>
        </div>

        <!-- Order Info -->
        @php
            $consignmentId = $order->delivery_data['consignment_id'] ?? $order->delivery_data['tracking_code'] ?? null;
            $courierProvider = $order->delivery_data['courier_provider'] ?? null;
        @endphp
        <div class="order-info">
            <div>
                <strong>Order #<span class="order-number">{{ $order->id }}</span></strong><br>
                <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
            </div>
            <div class="right">
                <strong>{{ $order->order_source }}</strong><br>
                <small>{{ ucfirst($order->status) }}</small>
                @if($consignmentId)
                    <br><small>Consignment: {{ $consignmentId }}</small>
                    @if($courierProvider)
                        <br><small>Courier: {{ ucfirst($courierProvider) }}</small>
                    @endif
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <h4>Customer Details:</h4>
            <p>{{ $order->name }}</p>
            <p>Phone: {{ $order->phone }}</p>
            @if($order->email)
                <p>Email: {{ $order->email }}</p>
            @endif
            @if($order->address)
                <p>Address: {{ $order->address }}</p>
            @endif
            @if($order->city)
                <p>City: {{ $order->city }}</p>
            @endif
        </div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Item</th>
                    <th style="width: 15%;" class="text-center">Qty</th>
                    <th style="width: 17%;" class="text-right">Price</th>
                    <th style="width: 18%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->order_items as $item)
                <tr>
                    <td>
                        <div class="">{{ $item->product->title ?? 'Product' }}</div>
                        @if($item->variationCombination)
                            <div class="">{{ $item->variationCombination->display_name }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">৳{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">৳{{ number_format($item->sub_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
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
            
            <div class="total-row">
                <span>Subtotal:</span>
                <span>৳{{ number_format($subtotal, 2) }}</span>
            </div>
            
            @if($discount > 0)
            <div class="total-row">
                <span>Discount:</span>
                <span>-৳{{ number_format($discount, 2) }}</span>
            </div>
            @endif
            
            @if($shipping > 0)
            <div class="total-row">
                <span>Shipping:</span>
                <span>৳{{ number_format($shipping, 2) }}</span>
            </div>
            @endif
            
            @if($paymentCharge > 0)
            <div class="total-row">
                <span>
                    @if($order->payment_method === 'bkash')
                        bKash Charge:
                    @elseif($order->payment_method === 'nagad')
                        Nagad Charge:
                    @elseif($order->payment_method === 'rocket')
                        Rocket Charge:
                    @else
                        Payment Charge:
                    @endif
                </span>
                <span>৳{{ number_format($paymentCharge, 2) }}</span>
            </div>
            @endif
            
            <div class="total-row final">
                <span>TOTAL:</span>
                <span>৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <h4>Payment Information:</h4>
            <p>Method: <span class="payment-method">{{ $order->payment_method }}</span></p>
            
            @if($order->payment_method === 'bkash' && $order->bkash_number)
                <div class="gateway-details">
                    <p><strong>bKash Details:</strong></p>
                    <p>Number: {{ $order->bkash_number }}</p>
                    <p>Transaction ID: {{ $order->bkash_transaction_id }}</p>
                </div>
            @elseif($order->payment_method === 'nagad' && $order->nagad_number)
                <div class="gateway-details">
                    <p><strong>Nagad Details:</strong></p>
                    <p>Number: {{ $order->nagad_number }}</p>
                    <p>Transaction ID: {{ $order->nagad_transaction_id }}</p>
                </div>
            @elseif($order->payment_method === 'rocket' && $order->rocket_number)
                <div class="gateway-details">
                    <p><strong>Rocket Details:</strong></p>
                    <p>Number: {{ $order->rocket_number }}</p>
                    <p>Transaction ID: {{ $order->rocket_transaction_id }}</p>
                </div>
            @endif
        </div>

        @if($order->message)
        <div class="customer-info">
            <h4>Order Notes:</h4>
            <p>{{ $order->message }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you bangla">Thank You!</div>
            <div class="footer-note">
                This is a computer generated receipt.<br>
                For any queries, please contact us.<br>
                <strong>Visit again!</strong>
            </div>
        </div>
    </div>
</body>
</html>
