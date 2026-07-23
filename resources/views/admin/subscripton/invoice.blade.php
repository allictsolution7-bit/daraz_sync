<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subscription Invoice - {{ $payment->sub_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #ffffff;
        }
        .header-table {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .site-title {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            text-align: right;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            vertical-align: top;
        }
        .info-card h4 {
            margin: 0 0 10px 0;
            color: #4f46e5;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 10px;
            text-align: left;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .totals-table {
            width: 320px;
            float: right;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .totals-table td {
            padding: 6px 10px;
        }
        .totals-table .grand-total {
            font-size: 15px;
            font-weight: bold;
            color: #4f46e5;
            border-top: 2px solid #4f46e5;
            border-bottom: 2px solid #4f46e5;
            background: #f1f5f9;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .status-pending {
            background: #fef9c3;
            color: #854d0e;
            border: 1px solid #fef08a;
        }
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
        .print-btn-bar {
            text-align: right;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
        }
        @media print {
            .print-btn-bar {
                display: none !important;
            }
            body {
                background: #ffffff;
            }
            .invoice-box {
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="invoice-box">
    @if(!request()->has('pdf'))
    <div class="print-btn-bar">
        <a href="javascript:window.print();" class="btn-print">Print Invoice</a>
    </div>
    @endif

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: top;">
                <h1 class="site-title">{{ $issuerInfo['site_name'] }}</h1>
                <div style="font-size: 11px; color: #64748b;">
                    <strong>Platform / Software:</strong> {{ $issuerInfo['site_name'] }}<br>
                    <strong>Website:</strong> {{ $issuerInfo['website'] }}
                </div>
            </td>
            <td style="vertical-align: top; text-align: right;">
                <h2 class="invoice-title">INVOICE</h2>
                <div style="margin-top: 5px; font-size: 12px; color: #475569;">
                    <strong>Invoice #:</strong> {{ $payment->sub_id }}<br>
                    <strong>Invoice Date:</strong> {{ $payment->created_at ? $payment->created_at->format('M d, Y') : date('M d, Y') }}<br>
                    <strong>Payment Status:</strong> 
                    <span class="badge-status {{ $payment->status === 'Approved' ? 'status-approved' : ($payment->status === 'Pending' ? 'status-pending' : 'status-rejected') }}">
                        {{ $payment->status }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Billing Info Section -->
    <table class="info-table">
        <tr>
            <!-- ISSUER SECTION -->
            <td class="info-card" width="48%">
                <h4>Issuer (Platform / Provider)</h4>
                <strong>{{ $issuerInfo['site_name'] }}</strong><br>
                <strong>Provider:</strong> {{ $issuerInfo['name'] }}<br>
                <strong>Email:</strong> {{ $issuerInfo['email'] }}<br>
                <strong>Phone:</strong> {{ $issuerInfo['phone'] }}<br>
                <strong>Address:</strong> {{ $issuerInfo['address'] }}
            </td>

            <td width="4%"></td>

            <!-- ISSUED TO SECTION -->
            <td class="info-card" width="48%">
                <h4>Issued To (Subscriber Admin)</h4>
                <strong>Admin Name:</strong> {{ $issuedToInfo['name'] }}<br>
                <strong>Email:</strong> {{ $issuedToInfo['email'] }}<br>
                <strong>Phone / Contact:</strong> {{ $issuedToInfo['phone'] }}<br>
                <strong>Store / Company:</strong> {{ $issuedToInfo['company'] }}<br>
                <strong>Address:</strong> {{ $issuedToInfo['address'] }}
            </td>
        </tr>
    </table>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Subscription Package</th>
                <th style="text-align: center;">Billing Cycle</th>
                <th style="text-align: center;">Payment Method</th>
                <th style="text-align: center;">Transaction ID</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong style="font-size: 13px; color: #1e293b;">{{ $payment->plan }}</strong>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                        Access Period: {{ $payment->expiry_date ? \Carbon\Carbon::parse($payment->expiry_date)->format('M d, Y') : 'Pending Expiration Date' }}
                    </div>
                </td>
                <td style="text-align: center;">{{ $payment->cycle ?? 'Monthly' }}</td>
                <td style="text-align: center;"><strong>{{ strtoupper($payment->gateway ?? 'N/A') }}</strong></td>
                <td style="text-align: center;"><code style="background: #f1f5f9; padding: 3px 6px; border-radius: 4px; border: 1px solid #cbd5e1;">{{ $payment->trx_id ?? 'N/A' }}</code></td>
                <td style="text-align: right; font-weight: bold;">TK {{ number_format((float)str_replace(',', '', $payment->price ?? 0), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td style="text-align: right;">TK {{ number_format((float)str_replace(',', '', $payment->price ?? 0), 2) }}</td>
        </tr>
        <tr>
            <td>Tax / VAT (0%):</td>
            <td style="text-align: right;">TK 0.00</td>
        </tr>
        <tr class="grand-total">
            <td>Grand Total:</td>
            <td style="text-align: right;">TK {{ number_format((float)str_replace(',', '', $payment->price ?? 0), 2) }}</td>
        </tr>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <p style="margin: 0 0 5px 0; font-weight: bold;">Thank you for your subscription payment!</p>
        <p style="margin: 0;">This is an official subscription payment invoice from {{ $issuerInfo['site_name'] }}.</p>
    </div>
</div>

</body>
</html>
