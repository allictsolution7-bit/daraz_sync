@extends('vendor.layouts.app')

@section('title', 'My POS Orders')

@section('styles')
<style>
    .reseller-orders-hero {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 60%, #a855f7 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 28px;
        box-shadow: 0 12px 40px rgba(99,102,241,0.35);
        position: relative;
        overflow: hidden;
    }
    .reseller-orders-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .reseller-orders-hero h2 { font-size: 1.6rem; font-weight: 800; margin: 0; }
    .reseller-orders-hero p { opacity: 0.85; margin: 5px 0 0; }

    .stat-pill {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 12px;
        padding: 12px 16px;
        text-align: center;
        min-width: 110px;
    }
    .stat-pill .val { font-size: 1.4rem; font-weight: 800; }
    .stat-pill .lbl { font-size: 0.72rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; }

    .earnings-card {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }
    .earnings-card .earn-num { font-size: 1.8rem; font-weight: 800; color: #15803d; }
    .earnings-card .earn-lbl { font-size: 0.78rem; color: #4ade80; text-transform: uppercase; }

    .filter-bar {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }

    .status-tab-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
    .status-tab {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all .2s;
    }
    .status-tab:hover { text-decoration: none; transform: translateY(-1px); }
    .status-tab.active { background: #6366f1; color: #fff; border-color: #6366f1; }
    .status-tab .cnt { background: rgba(0,0,0,0.08); border-radius: 9px; padding: 1px 7px; font-size: 0.7rem; }
    .status-tab.active .cnt { background: rgba(255,255,255,0.25); }

    .order-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        overflow: hidden;
        transition: all .2s;
    }
    .order-card:hover { box-shadow: 0 6px 24px rgba(99,102,241,0.12); transform: translateY(-1px); }
    .order-card-header {
        background: #f8fafc;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
    }
    .order-card-body { padding: 16px 20px; }
    .order-id { font-weight: 700; color: #1e293b; font-size: 0.9rem; }
    .order-date { font-size: 0.75rem; color: #94a3b8; }
    .customer-block .cname { font-weight: 600; color: #0f172a; }
    .customer-block .cphone { font-size: 0.8rem; color: #64748b; }
    .customer-block .ccity { font-size: 0.75rem; color: #94a3b8; }
    .item-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-top: 1px solid #f8fafc; }
    .item-title { font-weight: 500; font-size: 0.83rem; color: #1e293b; flex: 1; }
    .item-qty { background: #f1f5f9; border-radius: 6px; padding: 2px 8px; font-size: 0.75rem; font-weight: 600; }
    .item-price { font-size: 0.85rem; font-weight: 700; color: #6366f1; }
    .item-earn { font-size: 0.75rem; color: #16a34a; }
    .total-row { padding-top: 10px; font-weight: 700; font-size: 1rem; color: #0f172a; border-top: 2px solid #f1f5f9; }

    .badge-status-pending { background: #fef3c7; color: #92400e; }
    .badge-status-processing { background: #dbeafe; color: #1e40af; }
    .badge-status-delivered { background: #dcfce7; color: #15803d; }
    .badge-status-cancelled { background: #fee2e2; color: #991b1b; }
    .badge-status-on_hold { background: #f3f4f6; color: #374151; }
    .badge-status-shipped { background: #ede9fe; color: #6d28d9; }
    .badge-status-ready_for_delivery { background: #e0f2fe; color: #0369a1; }
    .order-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .empty-state i { font-size: 3rem; color: #c7d2fe; margin-bottom: 16px; }
    .empty-state h5 { color: #4b5563; font-weight: 700; }
    .empty-state p { color: #9ca3af; max-width: 320px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Hero Header --}}
    <div class="reseller-orders-hero">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h2><i class="fas fa-receipt me-2"></i>My POS Orders</h2>
                <p>Orders you've placed through the Reseller POS terminal</p>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <div class="stat-pill">
                    <div class="val">{{ $statusCounts['all'] }}</div>
                    <div class="lbl">Total</div>
                </div>
                <div class="stat-pill">
                    <div class="val">{{ $statusCounts['pending'] }}</div>
                    <div class="lbl">Pending</div>
                </div>
                <div class="stat-pill">
                    <div class="val">{{ $statusCounts['delivered'] }}</div>
                    <div class="lbl">Delivered</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Earnings Row --}}
    @if($totalEarnings > 0)
    <div class="row g-3 mb-3">
        <div class="col-md-6 col-lg-4">
            <div class="earnings-card">
                <div class="earn-lbl">Total Earnings</div>
                <div class="earn-num">৳{{ number_format($totalEarnings, 2) }}</div>
                <div class="mt-2 text-muted small">
                    <span class="text-success fw-bold">৳{{ number_format($paidEarnings, 2) }}</span> paid
                    &nbsp;&bull;&nbsp;
                    <span class="text-warning fw-bold">৳{{ number_format($totalEarnings - $paidEarnings, 2) }}</span> pending
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Status Tabs --}}
    <div class="status-tab-row">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="status-tab {{ !request('status') || request('status') === 'all' ? 'active' : '' }}">
            <i class="fas fa-list-ul"></i> All <span class="cnt">{{ $statusCounts['all'] }}</span>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="status-tab {{ request('status') === 'pending' ? 'active' : '' }}" style="{{ request('status') === 'pending' ? '' : 'border-color:#fcd34d;color:#92400e;' }}">
            <i class="fas fa-clock"></i> Pending <span class="cnt">{{ $statusCounts['pending'] }}</span>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}" class="status-tab {{ request('status') === 'processing' ? 'active' : '' }}" style="{{ request('status') === 'processing' ? '' : 'border-color:#93c5fd;color:#1e40af;' }}">
            <i class="fas fa-spinner"></i> Processing <span class="cnt">{{ $statusCounts['processing'] }}</span>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'delivered']) }}" class="status-tab {{ request('status') === 'delivered' ? 'active' : '' }}" style="{{ request('status') === 'delivered' ? '' : 'border-color:#86efac;color:#15803d;' }}">
            <i class="fas fa-check-circle"></i> Delivered <span class="cnt">{{ $statusCounts['delivered'] }}</span>
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}" class="status-tab {{ request('status') === 'cancelled' ? 'active' : '' }}" style="{{ request('status') === 'cancelled' ? '' : 'border-color:#fca5a5;color:#991b1b;' }}">
            <i class="fas fa-times-circle"></i> Cancelled <span class="cnt">{{ $statusCounts['cancelled'] }}</span>
        </a>
    </div>

    {{-- Filters --}}
    <div class="filter-bar mb-4">
        <form method="GET" action="" class="row g-2 align-items-end">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="col-md-4">
                <label class="form-label fw-semibold small text-muted mb-1">Search Customer</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name or phone...">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-muted mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-muted mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('vendor.orders.reseller') }}" class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-times me-1"></i>Reset</a>
            </div>
        </form>
    </div>

    {{-- Orders List --}}
    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fas fa-receipt d-block"></i>
            <h5>No Orders Yet</h5>
            <p>Orders you create through your Reseller POS will appear here. Start selling!</p>
            <a href="{{ route('vendor.pos.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-cash-register me-2"></i>Go to POS
            </a>
        </div>
    @else
        @foreach($orders as $order)
        @php
            $statusClass = 'badge-status-' . $order->status;
            $statusLabel = ucwords(str_replace('_', ' ', $order->status));
            $statusIconMap = [
                'pending' => 'fa-clock', 'processing' => 'fa-spinner',
                'delivered' => 'fa-check-circle', 'cancelled' => 'fa-times-circle',
                'shipped' => 'fa-truck', 'on_hold' => 'fa-pause-circle',
                'ready_for_delivery' => 'fa-box-open',
            ];
            $statusIcon = $statusIconMap[$order->status] ?? 'fa-circle';
        @endphp
        <div class="order-card">
            <div class="order-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <div class="order-id">#{{ $order->id }}</div>
                        <div class="order-date">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <span class="order-status-badge {{ $statusClass }}">
                        <i class="fas {{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold text-success fs-6">৳{{ number_format($order->total, 2) }}</span>
                    <a href="/admin/pos/print-invoice/{{ $order->id }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
            <div class="order-card-body">
                <div class="row g-3">
                    {{-- Customer --}}
                    <div class="col-md-3 customer-block">
                        <div class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing:.5px;">Customer</div>
                        <div class="cname">{{ $order->name }}</div>
                        <div class="cphone"><i class="fas fa-phone fa-xs me-1"></i>{{ $order->phone }}</div>
                        @if($order->city)
                            <div class="ccity"><i class="fas fa-map-marker-alt fa-xs me-1"></i>{{ $order->city }}</div>
                        @endif
                        @if($order->address)
                            <div class="ccity text-truncate" style="max-width:200px;">{{ $order->address }}</div>
                        @endif
                    </div>

                    {{-- Items --}}
                    <div class="col-md-6">
                        <div class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing:.5px;">Order Items</div>
                        @foreach($order->order_items as $item)
                        @php
                            $others = is_string($item->others) ? json_decode($item->others, true) : ($item->others ?? []);
                            $earn = $item->vendor_earning ?? 0;
                        @endphp
                        <div class="item-row">
                            <div class="item-title">
                                {{ $item->product->title ?? 'Product' }}
                                @if(!empty($others['variation_display_name']))
                                    <span class="badge bg-light text-muted border ms-1" style="font-size:0.7rem;">{{ $others['variation_display_name'] }}</span>
                                @endif
                            </div>
                            <span class="item-qty">x{{ $item->quantity }}</span>
                            <div class="text-end">
                                <div class="item-price">৳{{ number_format($item->price, 2) }}</div>
                                @if($earn > 0)
                                    <div class="item-earn">+৳{{ number_format($earn, 2) }} earn</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <div class="total-row d-flex justify-content-between mt-2">
                            <span>Total</span>
                            <span>৳{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div class="col-md-3 text-md-end">
                        <div class="text-muted small fw-semibold mb-1 text-uppercase" style="letter-spacing:.5px;">Payment</div>
                        <div class="fw-bold text-uppercase" style="font-size:0.85rem;">{{ $order->payment_method }}</div>
                        <div class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} mt-1">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </div>
                        @if($order->shipping > 0)
                            <div class="text-muted small mt-1">Shipping: ৳{{ number_format($order->shipping, 2) }}</div>
                        @endif
                        @if($order->discount > 0)
                            <div class="text-success small">Discount: -৳{{ number_format($order->discount, 2) }}</div>
                        @endif
                        @if($order->message)
                            <div class="text-muted small mt-2" style="font-style:italic;">"{{ $order->message }}"</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
