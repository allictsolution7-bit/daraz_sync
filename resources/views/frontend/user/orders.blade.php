@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        /* Orders page specific styles */
        .orders-container {
            margin: 20px auto;
            padding: 0 20px;
        }

        .orders-layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }

        .orders-card {
            background-color: #fff;
            box-shadow: var(--shadow-md);
            border-radius: 8px;
            overflow: hidden;
        }

        .orders-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8f9fa;
        }

        .orders-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
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
            border: 1px solid var(--border-color);
        }

        .orders-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .view-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
        }

        .view-btn:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin-top: 20px;
            justify-content: center;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: var(--text-color);
            background-color: #f8f9fa;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .pagination a:hover {
            background-color: var(--light-color);
        }

        .pagination .active span {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
            <div class="orders-header">
                <h4>My Orders</h4>
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
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'processing' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>৳{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('account.order.detail', $order->id) }}" class="view-btn">View Details</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-container">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="alert-info">
                        You haven't placed any orders yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection