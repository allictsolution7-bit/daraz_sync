<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Receipt #{{ $trx->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header-table, .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { padding: 5px; }
        .title { font-size: 24px; font-weight: bold; color: #4f46e5; text-transform: uppercase; }
        .status-badge { display: inline-block; padding: 4px 10px; font-size: 11px; font-weight: bold; border-radius: 12px; color: #fff; text-transform: uppercase; }
        .status-approved { background-color: #10b981; }
        .status-pending { background-color: #f59e0b; }
        .status-rejected { background-color: #ef4444; }
        .details-table th, .details-table td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        .details-table th { background-color: #f9fafb; font-weight: bold; font-size: 11px; text-transform: uppercase; color: #6b7280; }
        .total-row { font-size: 16px; font-weight: bold; color: #10b981; }
        .footer { margin-top: 30px; text-align: center; font-size: 11px; color: #9ca3af; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td>
                    <div class="title">{{ setting('general', 'site_name', 'Bazarei') }}</div>
                    <div>Vendor Payment Receipt</div>
                </td>
                <td style="text-align: right;">
                    <div><strong>Receipt #:</strong> VTRX-{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div><strong>Date:</strong> {{ $trx->created_at->format('d M, Y h:i A') }}</div>
                    <div style="margin-top: 5px;">
                        <span class="status-badge status-{{ $trx->status }}">{{ strtoupper($trx->status) }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <table class="details-table" style="margin-top: 15px;">
            <tr>
                <th width="50%">Vendor Information</th>
                <th width="50%">Payment Details</th>
            </tr>
            <tr>
                <td>
                    <strong>Name:</strong> {{ $trx->vendor->name ?? 'N/A' }}<br>
                    <strong>Email:</strong> {{ $trx->vendor->email ?? 'N/A' }}<br>
                    <strong>Current Wallet:</strong> ৳{{ number_format($trx->vendor->wallet_balance ?? 0, 2) }}
                </td>
                <td>
                    <strong>Transaction Type:</strong> {{ strtoupper(str_replace('_', ' ', $trx->type)) }}<br>
                    <strong>Payment Method:</strong> {{ $trx->payment_method ?? 'N/A' }}<br>
                    <strong>TRX Reference:</strong> {{ $trx->transaction_id ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $trx->type)) }}
                        @if($trx->admin_note)
                            <br><small style="color: #6b7280;">Note: {{ $trx->admin_note }}</small>
                        @endif
                    </td>
                    <td style="text-align: right;" class="total-row">৳{{ number_format($trx->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Generated automatically by {{ setting('general', 'site_name', 'Bazarei') }} System on {{ date('d M, Y h:i A') }}
        </div>
    </div>
</body>
</html>
