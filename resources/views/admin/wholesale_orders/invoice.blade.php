<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wholesale Delivery Invoice #{{ $order->order_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            padding: 30px 15px;
        }
        .invoice-wrapper {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .invoice-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            padding: 28px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .order-tag {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(255, 255, 255, 0.12);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .invoice-body {
            padding: 32px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
        }
        .info-card-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .info-line {
            color: #475569;
            font-size: 12.5px;
            margin-bottom: 3px;
        }
        .table-invoice {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .table-invoice th {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: left;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-invoice td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-verified {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-courier {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .summary-box {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .summary-table {
            width: 280px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }
        .summary-total {
            border-top: 2px solid #0f172a;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 800;
            color: #059669;
        }
        .invoice-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #64748b;
        }
        .print-toolbar {
            max-width: 820px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .btn-print:hover {
            background: #4338ca;
        }
        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 13px;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .invoice-wrapper {
                box-shadow: none;
                border: none;
                max-width: 100%;
            }
            .print-toolbar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-toolbar">
        <a href="javascript:history.back()" class="btn-back"><i class="fas fa-arrow-left me-1"></i> Back to Orders</a>
        <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Print Dispatch Invoice</button>
    </div>

    <div class="invoice-wrapper">
        <div class="invoice-header">
            <div>
                <div class="invoice-title">
                    <i class="fas fa-boxes-packing text-warning"></i>
                    B2B Wholesale Dispatch Slip
                </div>
                <div style="font-size: 12px; opacity: 0.85; margin-top: 4px;">
                    Order Date: {{ $order->created_at->format('d M, Y - h:i A') }}
                </div>
            </div>
            <div class="order-tag">
                #{{ $order->order_number }}
            </div>
        </div>

        <div class="invoice-body">
            <div class="info-grid">
                <!-- Supplier (Seller Store) -->
                <div class="info-card">
                    <div class="info-card-title">
                        <i class="fas fa-store text-primary"></i> Sender / Supplier Store
                    </div>
                    <div class="info-name">{{ '@' . $order->seller_subdomain }}</div>
                    <div class="info-line"><strong>Store Admin:</strong> {{ $order->seller_admin_name }}</div>
                    <div class="info-line"><strong>Fulfillment:</strong> {{ ucfirst($order->fulfillment_status) }}</div>
                    @if($order->courier_name)
                        <div class="info-line mt-2">
                            <span class="badge badge-courier">
                                <i class="fas fa-truck"></i> {{ $order->courier_name }}: {{ $order->tracking_number ?: 'Pending ID' }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Recipient (Buyer Store) -->
                <div class="info-card" style="border-color: #c7d2fe; background: #faf5ff;">
                    <div class="info-card-title" style="color: #6b21a8;">
                        <i class="fas fa-location-dot text-danger"></i> Ship To / Buyer Delivery Address
                    </div>
                    <div class="info-name" style="color: #581c87;">{{ $order->buyer_admin_name }} (@{{ $order->buyer_subdomain }})</div>
                    <div class="info-line"><i class="fas fa-phone-alt me-1 text-muted"></i> <strong>{{ $order->buyer_admin_phone }}</strong></div>
                    <div class="info-line" style="margin-top: 6px; line-height: 1.4;">
                        <i class="fas fa-map-pin me-1 text-danger"></i> {{ $order->buyer_shipping_address }}
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <table class="table-invoice">
                <thead>
                    <tr>
                        <th style="width: 45%;">Item Description</th>
                        <th style="text-align: center; width: 15%;">Quantity</th>
                        <th style="text-align: right; width: 20%;">Unit Earnings</th>
                        <th style="text-align: right; width: 20%;">Total Settlement</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="font-weight: 700; font-size: 13.5px; color: #0f172a; margin-bottom: 2px;">
                                {{ $order->product_title }}
                            </div>
                            <div style="font-size: 11px; color: #64748b;">
                                Product ID: #{{ $order->product_id }} &bull; Source: @{{ $order->seller_subdomain }}
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 700; font-size: 14px; color: #4338ca;">
                            {{ $order->quantity }} pcs
                        </td>
                        @php
                            $sellerUnitPrice = $order->quantity > 0 ? ($order->seller_earnings / $order->quantity) : $order->unit_price;
                        @endphp
                        <td style="text-align: right; font-weight: 600;">
                            ৳{{ number_format($sellerUnitPrice, 2) }}
                        </td>
                        <td style="text-align: right; font-weight: 800; font-size: 14px; color: #059669;">
                            ৳{{ number_format($order->seller_earnings, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Summary & Payment Status -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 10px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b; margin-bottom: 6px;">Payment Verification</div>
                    <span class="badge badge-verified">
                        <i class="fas fa-shield-check"></i> Super Admin Verified & Paid
                    </span>
                    <div style="font-size: 11px; color: #64748b; margin-top: 6px;">
                        Gateway: <strong>{{ $order->payment_gateway }}</strong> | TrxID: <code style="font-weight: 700;">{{ $order->trx_id }}</code>
                    </div>
                </div>

                <div class="summary-table">
                    <div class="summary-row">
                        <span style="color: #64748b;">Item Total ({{ $order->quantity }} pcs):</span>
                        <span>৳{{ number_format($order->seller_earnings, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span style="color: #64748b;">Delivery / Shipping:</span>
                        <span>৳0.00 (Prepaid)</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Net Seller Payout:</span>
                        <span>৳{{ number_format($order->seller_earnings, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <div>
                <i class="fas fa-barcode me-1"></i> B2B Wholesale Guaranteed Transaction Network
            </div>
            <div>
                Approved on: {{ $order->approved_at ? $order->approved_at->format('d M, Y') : 'Verified' }}
            </div>
        </div>
    </div>

</body>
</html>
