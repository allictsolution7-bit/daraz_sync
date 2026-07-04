<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">

    <title>Invoice - Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            font-size: 24px;
            color: #0056b3;
        }
        .header p {
            font-size: 16px;
            color: #888;
        }
        .order-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .order-summary div {
            flex: 1;
            text-align: center;
        }
        .order-summary div p {
            margin: 4px 0;
        }
        .order-summary div p span {
            font-weight: bold;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .order-details th, .order-details td {
            padding: 8px 12px;
            text-align: left;
        }
        .order-details th {
            background-color: #f1f1f1;
        }
        .order-details td {
            border-top: 1px solid #ddd;
        }
        .order-details .total td {
            font-weight: bold;
        }
        .footer p {
            font-size: 12px;
            color: #555;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            color: #fff;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h2>Invoice - Order #{{ $order->id }}</h2>
        <p>Thank you, {{ $order->name }}! Your order has been received.</p>
    </div>

    <!-- Order Summary Section -->
    <div class="order-summary">
        <div>
            <p><span>Order number:</span> {{ $order->id }}</p>
        </div>
        <div>
            <p><span>Date:</span> {{ date('F j, Y', strtotime($order->created_at)) }}</p>
        </div>
        <div>
            <p><span>Total:</span> ${{ number_format($order->total, 2) }}</p>
        </div>
        <div>
            <p><span>Payment method:</span> {{ strtoupper($order->payment_method) }}</p>
        </div>
    </div>

    <!-- Payment Notes -->
    <p style="text-align: center; font-size: 14px; color: #888;">Pay with cash upon delivery.</p>

    <!-- Order Details Section -->
    <table class="order-details">
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $item)
            <tr>
                <td>{{ $item->product->title }} × {{ $item->quantity }}</td>
                <td style="text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td style="text-align: right;">
                    {{ $order->shipping_cost > 0 ? '$' . number_format($order->shipping_cost, 2) : 'Free shipping' }}
                </td>
            </tr>
            <tr>
                <td>Payment method:</td>
                <td style="text-align: right;">{{ strtoupper($order->payment_method) }}</td>
            </tr>
            <tr class="total">
                <td>Total:</td>
                <td style="text-align: right; font-weight: bold;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p>If you have any questions, please contact us at <a href="mailto:support@thikana.shop">support@thikana.shop</a>.</p>
    </div>

    <!-- Button Section (Optional) -->
    <div style="text-align: center;">
        <a href="javascript:window.print();" class="btn">Print Invoice</a>
    </div>
</div>

</body>
</html>
