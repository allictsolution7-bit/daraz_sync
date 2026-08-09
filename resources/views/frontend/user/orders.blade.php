@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .orders-container {
            margin: 30px auto;
            padding: 0 15px;
            max-width: 1200px;
        }

        .orders-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .orders-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .orders-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
        }

        .orders-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .orders-body {
            padding: 30px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            vertical-align: middle;
        }

        .orders-table th,
        .orders-table td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            font-size: 14px;
        }

        .orders-table th {
            background-color: #f8fafc;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        .orders-table tbody tr {
            transition: all 0.2s ease;
        }

        .orders-table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.02);
        }

        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
        .badge-paid { background-color: #dcfce7; color: #15803d; }
        .badge-payment-pending { background-color: #fef3c7; color: #b45309; }
        .badge-failed { background-color: #fee2e2; color: #dc2626; }

        .view-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ff8c00 0%, #ff6a00 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 106, 0, 0.15);
        }

        .view-btn:hover {
            box-shadow: 0 6px 16px rgba(255, 106, 0, 0.25);
            transform: translateY(-1px);
            color: white !important;
        }

        .cancel-btn {
            display: inline-block;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white !important;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
        }

        .cancel-btn:hover {
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.25);
            transform: translateY(-1px);
            color: white !important;
        }

        @media (max-width: 768px) {
            .orders-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .orders-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('content')
<div class="base-container orders-container">
    <div class="orders-layout">
        <!-- Sidebar Menu -->
        @include('frontend.user.partials.sidebar')

        <!-- Main Content -->
        <div class="orders-card">
            <div class="orders-header d-flex justify-content-between align-items-center">
                <h4>My Orders History</h4>
            </div>
            <div class="orders-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Status / Payment</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge-status badge-{{ strtolower($order->status) == 'completed' || strtolower($order->status) == 'delivered' ? 'success' : (strtolower($order->status) == 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status ?? 'Pending')) }}
                                        </span>
                                        @php
                                            $ps = strtolower($order->payment_status ?? 'pending');
                                            $psBadge = $ps === 'paid' ? 'paid' : ($ps === 'failed' || $ps === 'refunded' ? 'failed' : 'payment-pending');
                                            $psLabel = $ps === 'paid' ? 'Paid' : ($ps === 'failed' ? 'Failed' : ($ps === 'refunded' ? 'Refunded' : 'Pending'));
                                        @endphp
                                        <span class="badge-status badge-{{ $psBadge }}" style="margin-top: 4px; display: inline-block;">
                                            {{ $psLabel }}
                                        </span>
                                    </td>
                                    <td><strong>৳{{ number_format($order->total ?? 0, 2) }}</strong></td>
                                    <td>
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            <a href="{{ route('account.order.detail', $order->id) }}" class="view-btn">View Details</a>
                                            @if(in_array(strtolower($order->status), ['pending', 'phone_not_rcv']))
                                                <form action="{{ route('account.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="cancel-btn">Cancel</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($orders, 'links') && $orders->hasPages())
                        <div class="pagination-container" style="margin-top: 20px;">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <div style="background-color: #e0f2fe; color: #0369a1; padding: 15px; border-radius: 6px;">
                        You haven't placed any orders yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection