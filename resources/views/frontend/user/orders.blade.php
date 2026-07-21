@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .orders-container {
            margin: 20px auto;
            padding: 0 5px;
        }

        .orders-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }

        .orders-card {
            background-color: #fff;
            box-shadow: var(--shadow-md, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
            border-radius: 8px;
            overflow: hidden;
        }

        .orders-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .orders-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .orders-body {
            padding: 25px;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .orders-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #1e293b;
        }

        .badge-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }

        .view-btn {
            display: inline-block;
            background-color: #ff6a00;
            color: white !important;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .view-btn:hover {
            background-color: #e05d00;
            color: white !important;
        }

        @media (max-width: 768px) {
            .orders-layout {
                grid-template-columns: 1fr;
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
                                    <th>Status</th>
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
                                            {{ ucfirst($order->status ?? 'Pending') }}
                                        </span>
                                    </td>
                                    <td><strong>৳{{ number_format($order->total ?? 0, 2) }}</strong></td>
                                    <td>
                                        <a href="{{ route('account.order.detail', $order->id) }}" class="view-btn">View Details</a>
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