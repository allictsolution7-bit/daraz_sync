<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Slip - Order #{{ $order->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .bangla {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        
        .package-slip {
            width: 100%;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }
        
        .store-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .store-details {
            font-size: 10px;
            line-height: 1.3;
            margin-bottom: 5px;
        }
        
        .slip-title {
            font-size: 12px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .order-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        
        .order-info div {
            margin-bottom: 3px;
        }
        
        .customer-info {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 8px;
            background-color: #f9f9f9;
        }
        
        .customer-info h3 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .customer-info div {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .items-section {
            margin-bottom: 15px;
        }
        
        .items-section h3 {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
        
        .item {
            border-bottom: 1px dotted #ccc;
            padding: 5px 0;
            font-size: 10px;
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .item-details {
            color: #666;
            font-size: 9px;
        }
        
        .item-quantity {
            float: right;
            font-weight: bold;
        }
        
        .summary {
            border-top: 2px solid #333;
            padding-top: 8px;
            margin-top: 15px;
            font-size: 10px;
        }
        
        .summary div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .total-row {
            font-weight: bold;
            font-size: 11px;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .notes {
            margin-top: 15px;
            border-top: 1px dotted #ccc;
            padding-top: 8px;
            font-size: 9px;
        }
        
        .notes h4 {
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px dotted #ccc;
            padding-top: 8px;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="package-slip">
        <!-- Header -->
        <div class="header">
            <div class="store-name">{{ $siteSettings['site_name'] }}</div>
            <div class="store-details bangla">
                {{ $siteSettings['address'] }}<br>
                Phone : {{ $siteSettings['phone_number'] }}
            </div>
            <div class="slip-title">PACKAGE SLIP</div>
        </div>

        <!-- Order Info -->
        @php
            $consignmentId = $order->delivery_data['consignment_id'] ?? $order->delivery_data['tracking_code'] ?? null;
            $courierProvider = $order->delivery_data['courier_provider'] ?? null;
        @endphp
        <div class="order-info">
            <div><strong>Order #{{ $order->id }}</strong></div>
            <div>Date: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div>Source: {{ ucfirst(str_replace('_', ' ', $order->order_source)) }}</div>
            <div>Payment: {{ strtoupper($order->payment_method) }}</div>
            @if($consignmentId)
                <div>Consignment: {{ $consignmentId }}</div>
                @if($courierProvider)
                    <div>Courier: {{ ucfirst($courierProvider) }}</div>
                @endif
            @endif
        </div>

        <!-- Customer Info -->
        <div class="customer-info">
            <h3>SHIP TO:</h3>
            <div>{{ $order->name }}</div>
            <div>{{ $order->phone }}</div>
            <div>{{ $order->address }}</div>
            @if($order->upazila)
                <div>{{ $order->upazila }}, {{ $order->city }}</div>
            @else
                <div>{{ $order->city }}</div>
            @endif
        </div>

        <!-- Items Section -->
        <div class="items-section">
            <h3>ITEMS TO PACK:</h3>
            @foreach($order->order_items as $item)
                <div class="item clearfix">
                    <div>
                       <span>{{ $item->product->title }}</span>
                        <span>× {{ $item->quantity }}</span>
                    </div>
                    @if($item->product->product_type === 'variable' && $item->variationCombination)
                        <div class="item-details">
                            Variation: {{ $item->variationCombination->display_name }}
                            @if($item->variationCombination->sku)
                                | SKU: {{ $item->variationCombination->sku }}
                            @endif
                        </div>
                    @else
                        @if($item->product->sku)
                            <div class="item-details">SKU: {{ $item->product->sku }}</div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="summary">
            <div>
                <span>Total Items:</span>
                <span>{{ $order->order_items->sum('quantity') }}</span>
            </div>
            <div>
                <span>Shipping:</span>
                <span>BDT {{ number_format($order->shipping, 2) }}</span>
            </div>
            @if($order->bkash_charge > 0)
                <div>
                    <span>bKash Charge:</span>
                    <span>BDT {{ number_format($order->bkash_charge, 2) }}</span>
                </div>
            @endif
            @if($order->nagad_charge > 0)
                <div>
                    <span>Nagad Charge:</span>
                    <span>BDT {{ number_format($order->nagad_charge, 2) }}</span>
                </div>
            @endif
            @if($order->rocket_charge > 0)
                <div>
                    <span>Rocket Charge:</span>
                    <span>BDT {{ number_format($order->rocket_charge, 2) }}</span>
                </div>
            @endif
            <div class="total-row">
                <span>TOTAL AMOUNT:</span>
                <span>BDT {{ number_format($order->total_with_charge, 2) }}</span>
            </div>
        </div>

        @if($order->message)
            <div class="notes">
                <h4>Order Notes:</h4>
                <p>{{ $order->message }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Handle with care • Check items before delivery</p>
            <p>{{ $siteSettings['website'] }}</p>
        </div>
    </div>
</body>
</html>
