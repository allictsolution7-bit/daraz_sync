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
                                            @php
                                                $hasReturnableItems = false;
                                                $returnableItems = [];
                                                $minExpiryDate = null;
                                                if (strtolower($order->status) === 'delivered') {
                                                    foreach ($order->order_items as $item) {
                                                        if ($item->product && ($item->product->return_period ?? 0) > 0) {
                                                            $deliveredDate = \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at);
                                                            $expiryDate = $deliveredDate->copy()->addDays($item->product->return_period);
                                                            if (now()->lessThanOrEqualTo($expiryDate)) {
                                                                $hasReturnableItems = true;
                                                                if (!$minExpiryDate || $expiryDate->lessThan($minExpiryDate)) {
                                                                    $minExpiryDate = $expiryDate;
                                                                }
                                                                
                                                                // Get policy text
                                                                $policyText = '';
                                                                if ($item->product->vendor_id) {
                                                                    $vendorUser = \App\Models\User::find($item->product->vendor_id);
                                                                    if ($vendorUser && $vendorUser->vendorSettings && !empty($vendorUser->vendorSettings->return_policy['policy_text'])) {
                                                                        $policyText = $vendorUser->vendorSettings->return_policy['policy_text'];
                                                                    }
                                                                }
                                                                if (empty($policyText)) {
                                                                    $policyText = setting('general', 'default_return_policy', "If you are not satisfied with your purchase, you can return it within the specified return period. The product must be unused and in its original packaging. Once received, refund will be processed to your payment method.");
                                                                }
                                                                
                                                                $returnableItems[] = [
                                                                    'item' => $item,
                                                                    'expiry_date' => $expiryDate->format('M d, Y'),
                                                                    'policy_text' => $policyText
                                                                ];
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                $timeString = "";
                                                if ($minExpiryDate) {
                                                    $diff = now()->diff($minExpiryDate);
                                                    if ($diff->days > 0) {
                                                        $timeString .= $diff->days . 'd ';
                                                    }
                                                    $timeString .= $diff->h . 'h';
                                                }
                                            @endphp
                                            
                                            @if($hasReturnableItems)
                                                <button type="button" class="btn btn-sm btn-warning text-white fw-bold px-3 py-1.5" style="background-color: #f59e0b; border: none; font-size: 13px; border-radius: 4px; padding: 6px 12px; line-height: 1.2; cursor: pointer;" onclick="document.getElementById('orderReturnModal_{{ $order->id }}').style.display='flex';">
                                                    Return ({{ $timeString }})
                                                </button>
                                            @endif

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

@foreach($orders as $order)
    @php
        $hasReturnableItems = false;
        $returnableItems = [];
        if (strtolower($order->status) === 'delivered') {
            foreach ($order->order_items as $item) {
                if ($item->product && ($item->product->return_period ?? 0) > 0) {
                    $deliveredDate = \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at);
                    $expiryDate = $deliveredDate->copy()->addDays($item->product->return_period);
                    if (now()->lessThanOrEqualTo($expiryDate)) {
                        $hasReturnableItems = true;
                        
                        // Get policy text
                        $policyText = '';
                        if ($item->product->vendor_id) {
                            $vendorUser = \App\Models\User::find($item->product->vendor_id);
                            if ($vendorUser && $vendorUser->vendorSettings && !empty($vendorUser->vendorSettings->return_policy['policy_text'])) {
                                $policyText = $vendorUser->vendorSettings->return_policy['policy_text'];
                            }
                        }
                        if (empty($policyText)) {
                            $policyText = "If you are not satisfied with your purchase, you can return it within the specified return period under the following conditions:\n\n" .
                                "1. Return Acceptance Rules:\n" .
                                "- The product must be unused, unwashed, and in its original condition.\n" .
                                "- All original tags, user manuals, warranty cards, and accessories must be intact.\n" .
                                "- The product must be returned in its original manufacturer packaging/box.\n" .
                                "- Returns are accepted for damaged, defective, or incorrect products delivered.\n\n" .
                                "2. Refund & Payback Process:\n" .
                                "- Once we receive the returned item, it will undergo a quality inspection.\n" .
                                "- Upon approval, the refund will be processed to your original payment method within 3 to 7 business days.\n" .
                                "- If the order was paid via Cash on Delivery, the refund will be sent via bKash/Nagad/Rocket.";
                        }
                        
                        $returnableItems[] = [
                            'item' => $item,
                            'delivered_date' => $deliveredDate->format('M d, Y'),
                            'expiry_date' => $expiryDate->format('M d, Y'),
                            'policy_text' => $policyText
                        ];
                    }
                }
            }
        }
    @endphp

    @if($hasReturnableItems)
        <!-- Return Order Modal -->
        <div id="orderReturnModal_{{ $order->id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 99999; align-items: center; justify-content: center; padding: 15px; text-transform: none; text-align: left; color: #334155;">
            <div style="background: #fff; width: 100%; max-width: 600px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; flex-direction: column; max-height: 90vh;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 16px 24px; color: #fff; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-undo-alt" style="font-size: 18px;"></i>
                        <h5 style="margin: 0; font-weight: bold; font-size: 16px; color: #fff;">Return Items - Order #{{ $order->id }}</h5>
                    </div>
                    <button type="button" onclick="document.getElementById('orderReturnModal_{{ $order->id }}').style.display='none';" style="background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; line-height: 1; padding: 0;">&times;</button>
                </div>

                <!-- Step 1: Policy Instructions -->
                <div id="returnModalStep1_{{ $order->id }}" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                    <div style="padding: 24px; overflow-y: auto; flex: 1;">
                        <p style="margin-top: 0; margin-bottom: 16px; color: #64748b; font-size: 13px;">The following items in this order are eligible for return. Please review the return policies below.</p>
                        
                        @foreach($returnableItems as $rData)
                            @php 
                                $rItem = $rData['item'];
                            @endphp
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 16px; text-align: left;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
                                    <div>
                                        <h6 style="margin: 0 0 4px 0; font-weight: bold; color: #1e293b; font-size: 14px;">{{ $rItem->product->title }}</h6>
                                        <div style="color: #64748b; font-size: 12px; display: flex; flex-wrap: wrap; gap: 12px;">
                                            <span>Delivered On: <strong>{{ $rData['delivered_date'] }}</strong></span>
                                            <span>Return Period: <strong>{{ $rItem->product->return_period }} Days</strong></span>
                                            <span>Expiry: <strong>{{ $rData['expiry_date'] }}</strong></span>
                                        </div>
                                    </div>
                                    <span style="background: #f59e0b; color: #fff; font-size: 11px; font-weight: bold; padding: 4px 8px; border-radius: 4px;">Return Window Active</span>
                                </div>
                                
                                <div style="margin-top: 12px; border-top: 1px solid #e2e8f0; padding-top: 8px;">
                                    <strong style="color: #1e293b; font-size: 12px; display: block; margin-bottom: 4px;"><i class="fas fa-file-shield text-primary"></i> Return Instructions & Policy:</strong>
                                    <div style="color: #475569; font-size: 12.5px; line-height: 1.5; white-space: pre-line; max-height: 120px; overflow-y: auto; background: #fff; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                                        {{ $rData['policy_text'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                        <button type="button" onclick="document.getElementById('orderReturnModal_{{ $order->id }}').style.display='none';" style="background: #e2e8f0; border: none; color: #475569; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Cancel</button>
                        <button type="button" onclick="document.getElementById('returnModalStep1_{{ $order->id }}').style.display='none'; document.getElementById('returnModalStep2_{{ $order->id }}').style.display='flex';" style="background: #f59e0b; border: none; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Proceed to Return</button>
                    </div>
                </div>

                <!-- Step 2: Return Form (Reason input) -->
                <div id="returnModalStep2_{{ $order->id }}" style="display: none; flex-direction: column; flex: 1; overflow: hidden;">
                    <form action="{{ route('account.order.return', $order->id) }}" method="POST" style="margin: 0; display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                        @csrf
                        <div style="padding: 24px; flex: 1; overflow-y: auto;">
                            <div style="text-align: left; display: flex; flex-direction: column; gap: 8px;">
                                <label style="font-weight: bold; color: #1e293b; font-size: 14px;">Reason for Return</label>
                                <textarea name="reason" rows="5" placeholder="Please describe the reason for returning this item..." required style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; font-size: 13.5px; width: 100%; box-sizing: border-box; resize: vertical;"></textarea>
                            </div>
                        </div>
                        <div style="padding: 16px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                            <button type="button" onclick="document.getElementById('returnModalStep2_{{ $order->id }}').style.display='none'; document.getElementById('returnModalStep1_{{ $order->id }}').style.display='flex';" style="background: #e2e8f0; border: none; color: #475569; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Back</button>
                            <button type="submit" style="background: #ef4444; border: none; color: #fff; padding: 8px 16px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px;">Submit Return</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    @endif
@endforeach

@endsection