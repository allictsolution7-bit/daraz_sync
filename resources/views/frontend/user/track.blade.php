@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .track-container {
            margin: 30px auto;
            padding: 0 15px;
            max-width: 1200px;
        }

        .track-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .track-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .track-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
        }

        .track-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .track-body {
            padding: 30px;
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
            font-weight: 600;
            color: #0f172a;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 10px;
            font-size: 15px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .track-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .track-btn:hover {
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            transform: translateY(-1px);
        }

        .order-info {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            background-color: #ffffff;
        }

        .order-info h5 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
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
            color: #1e293b;
        }

        .status-timeline {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 32px;
            bottom: -20px;
            width: 2px;
            background-color: #e2e8f0;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-icon {
            width: 32px;
            height: 32px;
            background-color: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            border: 2px solid #cbd5e1;
            z-index: 1;
            color: #64748b;
        }

        .timeline-icon.active {
            background-color: #ff6a00;
            color: white;
            border-color: #ff6a00;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-date {
            font-size: 12px;
            color: #64748b;
        }

        .timeline-status {
            font-weight: 600;
            color: #1e293b;
            margin: 4px 0;
        }

        .timeline-desc {
            font-size: 13px;
            color: #64748b;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        @media (max-width: 768px) {
            .track-layout {
                grid-template-columns: 1fr;
            }
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
        <div class="track-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content Card -->
            <div class="track-card">
                <div class="track-header">
                    <h4>Track Your Order Shipment</h4>
                </div>
                <div class="track-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('order.track.submit') }}" method="POST" class="track-form">
                        @csrf
                        <div class="form-group">
                            <label for="phone">Customer Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control"
                                placeholder="Enter your phone number (e.g. 017XXXXXXXX)" value="{{ old('phone', $prefillPhone) }}" required>
                            @error('phone')
                                <div class="text-danger" style="color: #dc3545; font-size: 13px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="track-btn">
                            <i class="fa-solid fa-magnifying-glass" style="margin-right: 6px;"></i> Track Order
                        </button>
                    </form>

                    @if (isset($order))
                        <div class="order-info">
                            <h5>Order Summary #{{ $order->id }}</h5>
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
                                        <strong>Customer Name:</strong> {{ $order->name }}
                                    </div>
                                    <div class="order-info-item">
                                        <strong>Phone Number:</strong> {{ $order->phone }}
                                    </div>
                                    <div class="order-info-item">
                                        <strong>Delivery Address:</strong> {{ $order->address }}
                                    </div>
                                    <div class="order-info-item">
                                        <strong>Total Amount:</strong> ৳{{ number_format($order->total, 2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="status-timeline">
                                <h5>Shipment Progress Timeline</h5>

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
                                                <div class="timeline-status">{{ ucwords(str_replace('_', ' ', $status)) }}</div>
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
