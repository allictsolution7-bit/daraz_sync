@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .track-container {
            margin: 40px auto;
            padding: 0 20px;
        }

        .track-card {
            background-color: #fff;
            box-shadow: var(--shadow-md);
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
        }

        .track-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8f9fa;
        }

        .track-header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .track-body {
            padding: 25px;
        }

        .track-form {
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.2);
        }

        .track-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .track-btn:hover {
            background-color: var(--secondary-color);
        }

        .order-info {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .order-info h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .order-info-item {
            margin-bottom: 10px;
        }

        .order-info-item strong {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .status-timeline {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .timeline-item {
            display: flex;
            margin-bottom: 15px;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 30px;
            bottom: -15px;
            width: 2px;
            background-color: var(--border-color);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            border: 2px solid var(--border-color);
            z-index: 1;
        }

        .timeline-icon.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-date {
            font-size: 12px;
            color: #6c757d;
        }

        .timeline-status {
            font-weight: 600;
            color: var(--secondary-color);
            margin: 5px 0;
        }

        .timeline-desc {
            font-size: 14px;
            color: #6c757d;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        @media (max-width: 768px) {
            .order-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $prefillPhone = request()->isMethod('get') ? request('phone') : '';
        $autoSubmit = request()->isMethod('get') && request('auto') == '1' && !empty($prefillPhone);
    @endphp
    <div class="base-container track-container">
        <div class="track-card">
            <div class="track-header">
                <h4>Track Your Order</h4>
            </div>
            <div class="track-body">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('order.track.submit') }}" method="POST" class="track-form">
                    @csrf
                    {{-- <div class="form-group">
                        <label for="order_id">Order ID</label>
                        <input type="text" id="order_id" name="order_id" class="form-control" placeholder="Enter your order ID" value="{{ old('order_id') }}" required>
                        @error('order_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                            placeholder="Enter your phone number" value="{{ old('phone', $prefillPhone) }}" required>
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="track-btn">Track Order</button>
                </form>

                @if (isset($order))
                    <div class="order-info">
                        <h5>Order #{{ $order->id }}</h5>
                        <div class="order-info-grid">
                            <div>
                                <div class="order-info-item">
                                    <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Status:</strong> {{ ucfirst($order->status) }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Payment Method:</strong> {{ ucfirst($order->payment_method ?? 'N/A') }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'N/A') }}
                                </div>
                            </div>
                            <div>
                                <div class="order-info-item">
                                    <strong>Name:</strong> {{ $order->name }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Phone:</strong> {{ $order->phone }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Address:</strong> {{ $order->address }}
                                </div>
                                <div class="order-info-item">
                                    <strong>Total:</strong> ৳{{ number_format($order->total, 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="status-timeline">
                            <h5>Order Status Timeline</h5>

                            @php
                                $statuses = [
                                    'pending' => [
                                        'icon' => 'fa-clock',
                                        'desc' => 'Your order has been received and is awaiting processing.',
                                    ],
                                    'processing' => [
                                        'icon' => 'fa-spinner',
                                        'desc' => 'Your order is being processed.',
                                    ],
                                    'ready_for_delivery' => [
                                        'icon' => 'fa-box',
                                        'desc' => 'Your order is packed and ready for delivery.',
                                    ],
                                    'shipped' => [
                                        'icon' => 'fa-truck',
                                        'desc' => 'Your order has been shipped and is on the way.',
                                    ],
                                    'completed' => [
                                        'icon' => 'fa-check-circle',
                                        'desc' => 'Your order has been delivered successfully.',
                                    ],
                                    'cancelled' => [
                                        'icon' => 'fa-times-circle',
                                        'desc' => 'Your order has been cancelled.',
                                    ],
                                ];

                                $currentStatus = $order->status;
                                $statusReached = false;
                            @endphp

                            @foreach ($statuses as $status => $details)
                                @if ($status != 'cancelled' || $currentStatus == 'cancelled')
                                    @php
                                        if ($status == $currentStatus) {
                                            $statusReached = true;
                                        }

                                        $isActive = $statusReached ? '' : 'active';

                                        // Skip remaining statuses if cancelled
                                        if ($currentStatus == 'cancelled' && $status != 'cancelled') {
                                            continue;
                                        }
                                    @endphp

                                    <div class="timeline-item">
                                        <div class="timeline-icon {{ $status == $currentStatus ? 'active' : '' }}">
                                            <i class="fas {{ $details['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                @if ($status == $currentStatus)
                                                    {{ $order->updated_at->format('M d, Y h:i A') }}
                                                @endif
                                            </div>
                                            <div class="timeline-status">{{ ucwords(str_replace('_', ' ', $status)) }}
                                            </div>
                                            <div class="timeline-desc">{{ $details['desc'] }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($autoSubmit)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.querySelector('.track-form');
                var phoneInput = document.getElementById('phone');
                if (form && phoneInput && phoneInput.value.trim() !== '') {
                    form.submit();
                }
            });
        </script>
    @endif
@endsection
