<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f4f7;">
        <tr>
            <td align="center" style="padding: 30px 10px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #2e7d32; padding: 24px 30px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px;">{{ $siteName }}</h1>
                        </td>
                    </tr>

                    {{-- Success Badge --}}
                    <tr>
                        <td style="padding: 30px 30px 10px; text-align: center;">
                            <div style="display: inline-block; background-color: #e8f5e9; border-radius: 50%; width: 60px; height: 60px; line-height: 60px; font-size: 30px;">
                                &#10004;
                            </div>
                            <h2 style="color: #2e7d32; margin: 15px 0 5px; font-size: 20px;">Payment Confirmed!</h2>
                            <p style="color: #666; margin: 0; font-size: 14px;">Your online payment has been received successfully.</p>
                        </td>
                    </tr>

                    {{-- Order Details --}}
                    <tr>
                        <td style="padding: 20px 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #e0e0e0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 15px; border-bottom: 1px solid #e0e0e0; background-color: #fafafa;">
                                        <strong style="color: #333;">Order #{{ $order->id }}</strong>
                                        <span style="float: right; color: #666; font-size: 13px;">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                                    </td>
                                </tr>

                                {{-- Items --}}
                                @foreach($order->order_items as $item)
                                <tr>
                                    <td style="padding: 12px 15px; border-bottom: 1px solid #f0f0f0;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="color: #333; font-size: 14px;">
                                                    {{ $item->product->title ?? 'Product' }}
                                                    @if($item->variationCombination)
                                                        <br><span style="color: #888; font-size: 12px;">{{ $item->getVariationText() }}</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: right; white-space: nowrap; color: #333; font-size: 14px;">
                                                    {{ $item->quantity }} x &#2547;{{ number_format($item->price, 2) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach

                                {{-- Subtotal --}}
                                @php
                                    $itemsTotal = $order->order_items->sum('sub_total');
                                @endphp
                                <tr>
                                    <td style="padding: 10px 15px; border-bottom: 1px solid #f0f0f0;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="color: #666; font-size: 13px;">Subtotal</td>
                                                <td style="text-align: right; color: #333; font-size: 13px;">&#2547;{{ number_format($itemsTotal, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Shipping --}}
                                <tr>
                                    <td style="padding: 10px 15px; border-bottom: 1px solid #f0f0f0;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="color: #666; font-size: 13px;">Shipping</td>
                                                <td style="text-align: right; color: #333; font-size: 13px;">&#2547;{{ number_format($order->shipping, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Total Paid --}}
                                <tr>
                                    <td style="padding: 12px 15px; background-color: #f9fbe7;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="color: #333; font-size: 15px; font-weight: bold;">Total Paid</td>
                                                <td style="text-align: right; color: #2e7d32; font-size: 16px; font-weight: bold;">&#2547;{{ number_format($order->paid_amount ?? $order->total, 2) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Payment & Delivery Info --}}
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="50%" style="vertical-align: top; padding-right: 10px;">
                                        <h4 style="color: #333; margin: 0 0 8px; font-size: 14px;">Payment Info</h4>
                                        <p style="color: #666; font-size: 13px; margin: 0; line-height: 1.6;">
                                            Method: <strong>{{ ucfirst($order->payment_method) }}</strong><br>
                                            Status: <strong style="color: #2e7d32;">Paid</strong>
                                            @if($order->gateway_transaction_id)
                                                <br>Transaction ID: <code style="background: #f5f5f5; padding: 2px 5px; font-size: 12px;">{{ $order->gateway_transaction_id }}</code>
                                            @endif
                                        </p>
                                    </td>
                                    <td width="50%" style="vertical-align: top; padding-left: 10px;">
                                        <h4 style="color: #333; margin: 0 0 8px; font-size: 14px;">Delivery Address</h4>
                                        <p style="color: #666; font-size: 13px; margin: 0; line-height: 1.6;">
                                            {{ $order->name }}<br>
                                            {{ $order->phone }}<br>
                                            {{ $order->address }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 20px 30px; background-color: #fafafa; border-top: 1px solid #e0e0e0; text-align: center;">
                            <p style="color: #999; font-size: 12px; margin: 0; line-height: 1.6;">
                                This is an automated confirmation email from {{ $siteName }}.<br>
                                If you have any questions, please contact us.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
