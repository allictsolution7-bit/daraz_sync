@extends('layouts.master')

@section('title', 'Wholesale Purchase Orders')

@section('styles')
<style>
    .stats-card-modern {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stats-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }
    .badge-status-pending {
        background-color: #fef3c7 !important;
        color: #92400e !important;
        border: 1px solid #fde68a !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-status-approved {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
        border: 1px solid #a7f3d0 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-status-rejected {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fecaca !important;
        font-weight: 700 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-processing {
        background-color: #e0e7ff !important;
        color: #3730a3 !important;
        border: 1px solid #c7d2fe !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-shipped {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #bae6fd !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-delivered {
        background-color: #ecfdf5 !important;
        color: #047857 !important;
        border: 1px solid #a7f3d0 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .badge-fulfillment-pending {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    .table-modern thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-modern tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .btn-soft {
        font-size: 11.5px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }
    .btn-soft-primary { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }
    .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }
    .btn-soft-success { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .btn-soft-success:hover { background: #a7f3d0; color: #065f46; }
    .btn-soft-info { background: #ecfeff; color: #0891b2; border-color: #a5f3fc; }
    .btn-soft-info:hover { background: #a5f3fc; color: #0369a1; }
    .btn-soft-warning { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .btn-soft-warning:hover { background: #fde68a; color: #92400e; }
    .btn-soft-dark { background: #f3f4f6; color: #1f2937; border-color: #e5e7eb; }
    .btn-soft-dark:hover { background: #e5e7eb; color: #111827; }

    /* Custom Navigation Tabs */
    .tab-pill-btn {
        font-weight: 600;
        font-size: 13px;
        padding: 8px 18px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        text-decoration: none !important;
        transition: all 0.2s ease-in-out;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #334155 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .tab-pill-btn:hover {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
    }
    .tab-pill-btn.active-all {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
        border-color: #0f172a !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25) !important;
    }
    .tab-pill-btn.active-purchases {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
        border-color: #4338ca !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    .tab-pill-btn.active-sales {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        border-color: #047857 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25) !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" style="max-width: 1650px;">
    <!-- Header Block -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill px-2.5 py-1" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-handshake me-1"></i> B2B WHOLESALE NETWORK
                </span>
                <span class="text-muted small">&bull;</span>
                <span class="text-muted small">Super Admin Payment Verification & Dispatch</span>
            </div>
            <h1 class="h3 mb-0 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">
                Wholesale Purchase Orders
            </h1>
            <p class="text-muted mb-0 small mt-1">
                Manage B2B stock purchase orders between store administrators and supplier stores.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.global-products.index') }}" class="btn btn-primary btn-sm rounded-3 px-3 py-2" style="font-weight: 600; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none;">
                <i class="fas fa-globe me-1.5"></i> Browse Global Wholesale Products
            </a>
        </div>
    </div>

    <!-- Navigation Tabs (My Purchases vs Orders to Deliver) -->
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        @if($isSuperAdmin)
            <a href="{{ route('admin.wholesale-orders.index', ['tab' => 'all']) }}" 
               class="tab-pill-btn {{ ($tab ?? 'all') === 'all' ? 'active-all' : '' }}">
                <i class="fas fa-shield-alt me-1.5 {{ ($tab ?? 'all') === 'all' ? 'text-warning' : 'text-slate-500' }}"></i> Super Admin All Orders
                <span class="badge rounded-pill ms-1.5 px-2 py-0.5 {{ ($tab ?? 'all') === 'all' ? 'bg-white text-dark' : 'bg-light text-dark border' }}" style="font-size: 10px; font-weight: 700;">{{ $orders->total() }}</span>
            </a>
        @endif
        <a href="{{ route('admin.wholesale-orders.index', ['tab' => 'purchases']) }}" 
           class="tab-pill-btn {{ ($tab ?? 'purchases') === 'purchases' ? 'active-purchases' : '' }}">
            <i class="fas fa-cart-shopping me-1.5 {{ ($tab ?? 'purchases') === 'purchases' ? 'text-white' : 'text-indigo-600' }}"></i> My Purchases (Bought)
            <span class="badge rounded-pill ms-1.5 px-2 py-0.5 {{ ($tab ?? 'purchases') === 'purchases' ? 'bg-white text-primary' : 'bg-light text-dark border' }}" style="font-size: 10px; font-weight: 700;">{{ $myPurchasesCount }}</span>
        </a>
        <a href="{{ route('admin.wholesale-orders.index', ['tab' => 'sales']) }}" 
           class="tab-pill-btn {{ ($tab ?? 'purchases') === 'sales' ? 'active-sales' : '' }}">
            <i class="fas fa-truck-ramp-box me-1.5 {{ ($tab ?? 'purchases') === 'sales' ? 'text-white' : 'text-amber-500' }}"></i> Orders to Ship & Earn (My Sales)
            <span class="badge rounded-pill ms-1.5 px-2 py-0.5 {{ ($tab ?? 'purchases') === 'sales' ? 'bg-white text-success' : 'bg-success text-white' }}" style="font-size: 10px; font-weight: 700;">{{ $mySalesCount }}</span>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">
                        {{ ($tab ?? 'purchases') === 'sales' ? 'Awaiting Packing' : 'Pending Verification' }}
                    </span>
                    <h3 class="mb-0 font-bold text-warning mt-1" style="font-weight: 700;">{{ $pendingCount }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">
                        {{ ($tab ?? 'purchases') === 'sales' ? 'Orders Ready to Ship' : 'Awaiting Super Admin' }}
                    </span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #fef3c7; color: #d97706;">
                    <i class="fas fa-clock fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">
                        {{ ($tab ?? 'purchases') === 'sales' ? 'Dispatched / Delivered' : 'Approved Orders' }}
                    </span>
                    <h3 class="mb-0 font-bold text-success mt-1" style="font-weight: 700;">{{ $approvedCount }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">
                        {{ ($tab ?? 'purchases') === 'sales' ? 'Completed Fulfillments' : 'Dispatched to Sellers' }}
                    </span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #d1fae5; color: #059669;">
                    <i class="fas fa-check-circle fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">
                        @if(($tab ?? 'purchases') === 'sales')
                            Sales Earnings
                        @elseif(($tab ?? 'purchases') === 'purchases')
                            Total Purchased Spend
                        @else
                            Wholesale Volume & Profit
                        @endif
                    </span>
                    <h3 class="mb-0 font-bold text-primary mt-1" style="font-weight: 700;">৳{{ number_format($totalVolume, 2) }}</h3>
                    @if($isSuperAdmin && ($tab ?? 'all') === 'all')
                        <span class="text-success small fw-bold d-block mt-0.5" style="font-size: 11.5px;">
                            <i class="fas fa-arrow-trend-up me-1"></i> Profit Comm: <strong>৳{{ number_format($totalProfit ?? 0, 2) }}</strong>
                        </span>
                    @elseif(($tab ?? 'purchases') === 'sales')
                        <span class="text-success small fw-semibold d-block mt-0.5" style="font-size: 11.5px;">
                            <i class="fas fa-wallet me-1"></i> Credited to Wallet
                        </span>
                    @else
                        <span class="text-muted small d-block mt-0.5" style="font-size: 11.5px;">
                            Settled Purchases
                        </span>
                    @endif
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #e0e7ff; color: #4338ca;">
                    <i class="fas fa-money-bill-wave fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="stats-card-modern d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small font-semibold text-uppercase d-block" style="font-size: 11px; letter-spacing: 0.5px;">Total Orders</span>
                    <h3 class="mb-0 font-bold text-slate-800 mt-1" style="font-weight: 700;">{{ $orders->total() }}</h3>
                    <span class="text-muted small" style="font-size: 11.5px;">
                        {{ ($tab ?? 'purchases') === 'sales' ? 'Seller Orders' : (($tab ?? 'purchases') === 'purchases' ? 'Your Purchases' : 'All Recorded Purchases') }}
                    </span>
                </div>
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f1f5f9; color: #64748b;">
                    <i class="fas fa-boxes-stacked fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.wholesale-orders.index') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-between gap-3 m-0">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search order #, product, TrxID..." value="{{ request('search') }}">
                    </div>

                    <select name="status" class="form-select form-select-sm rounded-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="">Payment: All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <select name="fulfillment" class="form-select form-select-sm rounded-3" style="width: 170px;" onchange="this.form.submit()">
                        <option value="">Fulfillment: All</option>
                        <option value="pending" {{ request('fulfillment') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('fulfillment') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('fulfillment') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('fulfillment') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3">Filter</button>
                    @if(request()->anyFilled(['search', 'status', 'fulfillment']))
                        <a href="{{ route('admin.wholesale-orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-3">Reset</a>
                    @endif
                </div>

                @if($isSuperAdmin)
                    <span class="badge bg-dark text-white px-2.5 py-1.5 rounded-3" style="font-size: 11px;">
                        <i class="fas fa-shield-alt me-1 text-warning"></i> Super Admin Mode
                    </span>
                @endif
            </form>
        </div>
    </div>

    <!-- Bulk Actions Dashboard -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4" style="background: #ffffff;">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div style="font-weight: 700; font-size: 13px; color: #1e293b; letter-spacing: -0.2px;">
                <i class="fas fa-tools text-primary me-1.5"></i> BULK ACTIONS DASHBOARD
            </div>
            <span class="text-muted small" style="font-size: 11.5px;" id="selected-orders-count-badge">
                <i class="fas fa-check-double text-muted me-1"></i> <span id="selected-count">0</span> orders selected
            </span>
        </div>
        
        <div class="row g-3">
            <!-- Courier & Shipping Section -->
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
                        <i class="fas fa-truck text-success"></i> LOGISTICS & DISPATCH
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" onclick="bulkDispatchCourier('Steadfast')" class="btn-soft btn-soft-success">
                            <i class="fas fa-paper-plane"></i> Steadfast
                        </button>
                        <button type="button" onclick="bulkDispatchCourier('Pathao')" class="btn-soft btn-soft-primary">
                            <i class="fas fa-shipping-fast"></i> Pathao
                        </button>
                        <button type="button" onclick="bulkSyncCourierStatus()" class="btn-soft btn-soft-info" title="Sync courier delivery status">
                            <i class="fas fa-sync-alt"></i> Sync Courier
                        </button>
                    </div>
                </div>
            </div>

            <!-- Documents & Export Section -->
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 6px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
                        <i class="fas fa-file-alt text-info"></i> DOCUMENTS & EXPORT
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" onclick="bulkPrintInvoices()" class="btn-soft btn-soft-info">
                            <i class="fas fa-file-invoice"></i> Invoices
                        </button>
                        <button type="button" onclick="bulkPrintSlips()" class="btn-soft btn-soft-warning">
                            <i class="fas fa-box"></i> Package Slips
                        </button>
                        <button type="button" onclick="exportWholesaleCsv()" class="btn-soft btn-soft-dark">
                            <i class="fas fa-download"></i> Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-modern" id="wholesale-orders-table">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 38px;">
                            <input type="checkbox" id="select-all-wholesale" class="form-check-input" title="Select All">
                        </th>
                        <th style="white-space: nowrap;">Order Info</th>
                        <th style="min-width: 170px;">Buyer Store & Address</th>
                        <th style="white-space: nowrap;">Seller Store</th>
                        <th style="min-width: 180px;">Product & Qty</th>
                        <th style="white-space: nowrap;">{{ ($tab ?? 'purchases') === 'sales' ? 'Your Earnings' : 'Total Amount' }}</th>
                        <th style="white-space: nowrap;">Payment</th>
                        <th style="white-space: nowrap;">Fulfillment</th>
                        <th class="pe-4 text-end" style="white-space: nowrap; min-width: 170px;">Actions & Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3">
                                <input type="checkbox" class="wholesale-item-check form-check-input" 
                                       value="{{ $order->id }}"
                                       data-ordernum="{{ $order->order_number }}"
                                       data-invoice="{{ route('admin.wholesale-orders.invoice', $order->id) }}"
                                       data-buyer="{{ $order->buyer_admin_name }}"
                                       data-subdomain="{{ $order->buyer_subdomain }}"
                                       data-phone="{{ $order->buyer_admin_phone }}"
                                       data-address="{{ $order->buyer_shipping_address }}"
                                       data-product="{{ $order->product_title }}"
                                       data-qty="{{ $order->quantity }}"
                                       data-price="{{ $order->seller_earnings }}"
                                       data-payment="{{ $order->payment_status }}"
                                       data-fulfillment="{{ $order->fulfillment_status }}">
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="font-bold text-dark font-monospace" style="font-weight: 700;">{{ $order->order_number }}</div>
                                <div class="text-muted small" style="font-size: 11px;">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td>
                                <div class="font-semibold text-slate-800" style="font-weight: 600;">
                                    {{ $order->buyer_admin_name }}
                                    @if($order->buyer_subdomain)
                                        <span class="badge rounded-pill ms-1" style="background-color: #e0e7ff; color: #3730a3; font-size: 10px;">{{ '@' . $order->buyer_subdomain }}</span>
                                    @endif
                                </div>
                                <div class="text-muted small" style="font-size: 11.5px;">
                                    <i class="fas fa-phone-alt me-1 text-primary"></i> {{ $order->buyer_admin_phone }}
                                </div>
                                <div class="text-muted small text-truncate" style="max-width: 200px; font-size: 11px;" title="{{ $order->buyer_shipping_address }}">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $order->buyer_shipping_address }}
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge rounded-pill px-2 py-1" style="background-color: #f1f5f9; color: #334155; font-size: 11.5px; font-weight: 600;">
                                    <i class="fas fa-store me-1 text-primary"></i> {{ '@' . $order->seller_subdomain }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($order->product_thumb_image)
                                        <img src="{{ filter_var($order->product_thumb_image, FILTER_VALIDATE_URL) ? $order->product_thumb_image : asset('storage/' . $order->product_thumb_image) }}" 
                                             class="rounded-3 border" style="width: 38px; height: 38px; object-fit: cover;">
                                    @endif
                                    <div>
                                        <div class="font-semibold text-slate-800 text-truncate" style="max-width: 160px; font-weight: 600;" title="{{ $order->product_title }}">
                                            {{ $order->product_title }}
                                        </div>
                                        @php
                                            $sellerUnitPrice = $order->quantity > 0 ? ($order->seller_earnings / $order->quantity) : $order->unit_price;
                                            $displayUnitPrice = (($tab ?? 'purchases') === 'sales') ? $sellerUnitPrice : $order->unit_price;
                                        @endphp
                                        <div class="small text-muted" style="font-size: 11px;">
                                            Qty: <strong class="text-primary">{{ $order->quantity }} pcs</strong> &bull; ৳{{ number_format($displayUnitPrice, 2) }}/unit
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                @if(($tab ?? 'purchases') === 'sales')
                                    <div class="font-bold text-success" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($order->seller_earnings, 2) }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 10.5px;">
                                        <i class="fas fa-coins text-warning me-1"></i> Seller Earning
                                    </div>
                                @elseif($isSuperAdmin && ($tab ?? 'all') === 'all')
                                    <div class="font-bold text-success" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 10px;">
                                        Seller: ৳{{ number_format($order->seller_earnings, 2) }} | Comm: ৳{{ number_format($order->platform_commission, 2) }}
                                    </div>
                                @else
                                    <div class="font-bold text-success" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="d-flex align-items-center gap-2">
                                    <div>
                                        @if($order->payment_status === 'pending')
                                            <span class="badge badge-status-pending px-2 py-1 rounded-pill" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 700; font-size: 10.5px;">
                                                <i class="fas fa-clock me-1"></i> Pending Verif.
                                            </span>
                                        @elseif($order->payment_status === 'approved')
                                            <span class="badge badge-status-approved px-2 py-1 rounded-pill" style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; font-weight: 700; font-size: 10.5px;">
                                                <i class="fas fa-check-circle me-1"></i> Paid & Verified
                                            </span>
                                        @else
                                            <span class="badge badge-status-rejected px-2 py-1 rounded-pill" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; font-weight: 700; font-size: 10.5px;">
                                                <i class="fas fa-times-circle me-1"></i> Rejected
                                            </span>
                                        @endif
                                        <div class="small text-slate-600 mt-1" style="font-size: 10.5px;">
                                            <span class="badge bg-light text-dark border px-1.5 py-0.5">{{ $order->payment_gateway }}</span>
                                            <code>{{ $order->trx_id ?: 'N/A' }}</code>
                                        </div>
                                    </div>
                                    @php
                                        $meta = is_array($order->metadata) ? $order->metadata : (is_string($order->metadata) ? json_decode($order->metadata, true) : []);
                                        $screenshot = $meta['payment_screenshot'] ?? null;
                                    @endphp
                                    @if($screenshot)
                                        <a href="{{ asset($screenshot) }}" target="_blank" title="View Payment Proof Slip" class="d-inline-block position-relative">
                                            <img src="{{ asset($screenshot) }}" class="rounded border shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                @if($order->fulfillment_status === 'delivered')
                                    <span class="badge badge-fulfillment-delivered px-2.5 py-1 rounded-pill" style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-box-open me-1"></i> Delivered
                                    </span>
                                @elseif($order->fulfillment_status === 'shipped')
                                    <span class="badge badge-fulfillment-shipped px-2.5 py-1 rounded-pill" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-truck-fast me-1"></i> Shipped
                                    </span>
                                    @if($order->tracking_number)
                                        <div class="text-muted small mt-1" style="font-size: 10.5px;">{{ $order->courier_name }}: <strong>{{ $order->tracking_number }}</strong></div>
                                    @endif
                                @elseif($order->fulfillment_status === 'processing')
                                    <span class="badge badge-fulfillment-processing px-2.5 py-1 rounded-pill" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-boxes-packing me-1"></i> Seller Packing
                                    </span>
                                @else
                                    <span class="badge badge-fulfillment-pending px-2.5 py-1 rounded-pill" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-hourglass-start me-1"></i> Pending Payment
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end" style="white-space: nowrap;">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    @if($order->payment_status === 'approved')
                                        <a href="{{ route('admin.wholesale-orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-3 px-2 py-1 font-semibold" style="font-size: 11px;" title="Print Dispatch Packing Slip">
                                            <i class="fas fa-file-invoice text-primary me-1"></i> Invoice
                                        </a>
                                    @endif

                                    @if($isSuperAdmin && $order->payment_status === 'pending')
                                        <button type="button" class="btn btn-sm btn-success rounded-3 px-2.5 py-1 font-semibold" style="font-size: 11.5px;" onclick="approveOrder('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->seller_subdomain }}', '{{ $order->quantity }}')">
                                            <i class="fas fa-check me-1"></i> Accept Payment
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2.5 py-1 font-semibold ms-1" style="font-size: 11.5px;" onclick="rejectOrder('{{ $order->id }}', '{{ $order->order_number }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($order->payment_status === 'approved')
                                        <button type="button" class="btn btn-sm btn-success rounded-3 px-2.5 py-1 font-semibold text-white shadow-sm" style="font-size: 11.5px; background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;" onclick="openFulfillmentModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $order->fulfillment_status }}', '{{ $order->courier_name }}', '{{ $order->tracking_number }}')">
                                            <i class="fas fa-truck-fast me-1"></i> Ship / Courier
                                        </button>
                                    @endif

                                    @php
                                        $canDelete = ($order->payment_status === 'rejected') && (
                                            (auth()->user() && ($order->buyer_admin_id == auth()->id() || $order->buyer_admin_email === auth()->user()->email))
                                            || ($currentSubdomain && $order->buyer_subdomain === $currentSubdomain)
                                            || $isSuperAdmin
                                        );
                                    @endphp
                                    @if($canDelete)
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2 py-1 font-semibold" style="font-size: 11px;" onclick="deleteRejectedOrder('{{ $order->id }}', '{{ $order->order_number }}')" title="Delete Rejected Order">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-receipt text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">No Wholesale Purchase Orders Found</h5>
                                    <p class="text-muted small">Orders placed across the B2B wholesale network will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Checkbox & Selection Management
const selectAllCheckbox = document.getElementById('select-all-wholesale');
const itemCheckboxes = document.querySelectorAll('.wholesale-item-check');
const selectedCountSpan = document.getElementById('selected-count');

function updateSelectionCount() {
    const checkedItems = document.querySelectorAll('.wholesale-item-check:checked');
    if (selectedCountSpan) {
        selectedCountSpan.textContent = checkedItems.length;
    }
}

if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function() {
        itemCheckboxes.forEach(cb => {
            cb.checked = selectAllCheckbox.checked;
        });
        updateSelectionCount();
    });
}

itemCheckboxes.forEach(cb => {
    cb.addEventListener('change', function() {
        if (!this.checked && selectAllCheckbox) {
            selectAllCheckbox.checked = false;
        } else if (selectAllCheckbox) {
            const allChecked = Array.from(itemCheckboxes).every(i => i.checked);
            selectAllCheckbox.checked = allChecked;
        }
        updateSelectionCount();
    });
});

function getSelectedOrders() {
    const selected = [];
    document.querySelectorAll('.wholesale-item-check:checked').forEach(cb => {
        selected.push({
            id: cb.value,
            order_number: cb.dataset.ordernum,
            invoice: cb.dataset.invoice,
            buyer: cb.dataset.buyer,
            subdomain: cb.dataset.subdomain,
            phone: cb.dataset.phone,
            address: cb.dataset.address,
            product: cb.dataset.product,
            qty: cb.dataset.qty,
            price: cb.dataset.price,
            payment: cb.dataset.payment,
            fulfillment: cb.dataset.fulfillment,
        });
    });
    return selected;
}

// Bulk Courier Dispatch (Steadfast / Pathao)
function bulkDispatchCourier(courierName) {
    const selected = getSelectedOrders();
    if (selected.length === 0) {
        Swal.fire({
            title: 'No Orders Selected',
            text: `Please check the box next to the orders you want to dispatch via ${courierName}.`,
            icon: 'info',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    const orderNumbers = selected.map(o => o.order_number).join(', ');

    Swal.fire({
        title: `Dispatch to ${courierName}`,
        html: `
            <div class="text-start">
                <p class="small text-muted mb-2">Selected <strong>${selected.length} order(s)</strong>: <code>${orderNumbers}</code></p>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Courier Provider</label>
                    <input type="text" id="bulk_courier_name" class="form-control form-control-sm" value="${courierName}">
                </div>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Consignment / Batch Tracking Prefix</label>
                    <input type="text" id="bulk_tracking_prefix" class="form-control form-control-sm" placeholder="e.g. ${courierName.substring(0, 2).toUpperCase()}-${Date.now().toString().slice(-6)}">
                </div>
                <div class="alert bg-light border text-start small p-2 rounded-3 mb-0">
                    <i class="fas fa-info-circle text-primary me-1"></i> These wholesale orders will transition from <strong>Seller Packing</strong> to <strong>Shipped</strong>.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: `<i class="fas fa-paper-plane me-1"></i> Dispatch ${selected.length} Orders`,
        preConfirm: () => {
            return {
                courier_name: document.getElementById('bulk_courier_name').value || courierName,
                tracking_prefix: document.getElementById('bulk_tracking_prefix').value || `${courierName.substring(0, 2).toUpperCase()}-${Date.now().toString().slice(-6)}`,
            };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            Swal.fire({
                title: 'Dispatching...',
                html: `Sending ${selected.length} orders to ${courierName}...`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const orderIds = selected.map(o => o.id);

            fetch("{{ route('admin.wholesale-orders.courier.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    provider: provider,
                    order_ids: orderIds
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Dispatched Successfully!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => location.reload());
                } else {
                    // Fallback to manual consignment entry if API not configured
                    const promises = selected.map((order, idx) => {
                        return fetch(`{{ url('admin/wholesale-orders') }}/${order.id}/fulfillment`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                fulfillment_status: 'shipped',
                                courier_name: res.value.courier_name,
                                tracking_number: `${res.value.tracking_prefix}-${idx + 1}`
                            })
                        });
                    });

                    Promise.all(promises).then(() => {
                        Swal.fire({
                            title: 'Shipment Created!',
                            text: `${selected.length} orders have been marked as Shipped (${res.value.courier_name}).`,
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        }).then(() => location.reload());
                    });
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Network error during dispatch. Please check connection.', 'error');
            });
        }
    });
}

// Bulk Sync Courier Status
function bulkSyncCourierStatus() {
    const selected = getSelectedOrders();
    const orderIds = selected.map(o => o.id);

    Swal.fire({
        title: 'Syncing Courier Status...',
        html: 'Checking live delivery status with courier networks (Steadfast, Pathao)...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch("{{ route('admin.wholesale-orders.courier.sync') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_ids: orderIds
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: data.updated_count > 0 ? 'Delivery Status Updated!' : 'Couriers Synchronized',
                html: `${data.message}${data.summary && data.summary.length ? '<br><br><span class="badge bg-success">' + data.summary.join('</span> <span class="badge bg-success">') + '</span>' : ''}`,
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            }).then(() => {
                if (data.updated_count > 0) location.reload();
            });
        } else {
            Swal.fire('Sync Error', data.message || 'Failed to synchronize couriers.', 'error');
        }
    })
    .catch(() => {
        Swal.fire('Error', 'Network error during courier synchronization.', 'error');
    });
}

// Bulk Print Invoices
function bulkPrintInvoices() {
    const selected = getSelectedOrders();
    if (selected.length === 0) {
        Swal.fire('No Orders Selected', 'Please check one or more orders to print invoices.', 'info');
        return;
    }

    selected.forEach(o => {
        window.open(o.invoice, '_blank');
    });
}

// Bulk Print Slips
function bulkPrintSlips() {
    bulkPrintInvoices();
}

// Export CSV
function exportWholesaleCsv() {
    const selected = getSelectedOrders();
    const rowsToExport = selected.length > 0 ? selected : Array.from(itemCheckboxes).map(cb => ({
        id: cb.value,
        order_number: cb.dataset.ordernum,
        buyer: cb.dataset.buyer,
        subdomain: cb.dataset.subdomain,
        phone: cb.dataset.phone,
        address: cb.dataset.address,
        product: cb.dataset.product,
        qty: cb.dataset.qty,
        price: cb.dataset.price,
        payment: cb.dataset.payment,
        fulfillment: cb.dataset.fulfillment,
    }));

    if (rowsToExport.length === 0) {
        Swal.fire('No Data', 'No orders available to export.', 'info');
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Order Number,Buyer Name,Buyer Store,Phone,Shipping Address,Product,Qty,Seller Payout,Payment Status,Fulfillment Status\n";

    rowsToExport.forEach(row => {
        const clean = (text) => `"${(text || '').replace(/"/g, '""')}"`;
        const line = [
            clean(row.order_number),
            clean(row.buyer),
            clean(row.subdomain ? '@' + row.subdomain : ''),
            clean(row.phone),
            clean(row.address),
            clean(row.product),
            clean(row.qty),
            clean(row.price),
            clean(row.payment),
            clean(row.fulfillment)
        ].join(",");
        csvContent += line + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `wholesale_orders_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function approveOrder(orderId, orderNum, sellerSubdomain, qty) {
    Swal.fire({
        title: 'Accept & Dispatch Order?',
        html: `Accept Super Admin payment for <strong>${orderNum}</strong>?<br><br>
               <div class="alert bg-light border text-start small p-2.5 rounded-3 mb-0">
                 &bull; Creates a delivery order in seller store (<strong>@${sellerSubdomain}</strong>).<br>
                 &bull; Allocates <strong>${qty} units</strong> into the buyer store catalog.
               </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Yes, Accept & Dispatch',
        cancelButtonText: 'Cancel'
    }).then((res) => {
        if (res.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                html: 'Verifying payment and dispatching order...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to approve order', 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Network error occurred', 'error'));
        }
    });
}

function rejectOrder(orderId, orderNum) {
    Swal.fire({
        title: 'Reject Payment?',
        html: `Enter rejection reason for order <strong>${orderNum}</strong>:`,
        input: 'text',
        inputPlaceholder: 'e.g. Invalid TrxID or payment not received',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Reject Payment',
        cancelButtonText: 'Cancel',
        inputValidator: (val) => {
            if (!val) return 'Please provide a reason';
        }
    }).then((res) => {
        if (res.isConfirmed) {
            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: res.value })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Rejected', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to reject', 'error');
                }
            });
        }
    });
}

function openFulfillmentModal(orderId, orderNum, currentStatus, courier, tracking) {
    Swal.fire({
        title: `Update Shipping for ${orderNum}`,
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label small font-semibold">Fulfillment Status</label>
                    <select id="swal_fulfillment_status" class="form-select form-select-sm">
                        <option value="processing" ${currentStatus === 'processing' ? 'selected' : ''}>Processing / Packing</option>
                        <option value="shipped" ${currentStatus === 'shipped' ? 'selected' : ''}>Shipped</option>
                        <option value="delivered" ${currentStatus === 'delivered' ? 'selected' : ''}>Delivered</option>
                        <option value="cancelled" ${currentStatus === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Courier Service</label>
                    <input type="text" id="swal_courier" class="form-control form-control-sm" placeholder="e.g. Steadfast / Pathao / RedX" value="${courier || ''}">
                </div>
                <div class="mb-3">
                    <label class="form-label small font-semibold">Tracking Number / Parcel ID</label>
                    <input type="text" id="swal_tracking" class="form-control form-control-sm" placeholder="e.g. ST12345678" value="${tracking || ''}">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Save Shipping Info',
        preConfirm: () => {
            return {
                fulfillment_status: document.getElementById('swal_fulfillment_status').value,
                courier_name: document.getElementById('swal_courier').value,
                tracking_number: document.getElementById('swal_tracking').value,
            };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}/fulfillment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(res.value)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to update', 'error');
                }
            });
        }
    });
}

function deleteRejectedOrder(orderId, orderNum) {
    Swal.fire({
        title: 'Delete Rejected Order?',
        html: `Are you sure you want to delete rejected purchase order <strong>${orderNum}</strong>?<br><br>
               <span class="text-muted small">This action cannot be undone and will permanently remove this record from your purchases.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then((res) => {
        if (res.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`{{ url('admin/wholesale-orders') }}/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete order', 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Network error occurred', 'error'));
        }
    });
}
</script>
@endsection
