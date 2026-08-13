@extends('layouts.master')

@section('title', 'Partner Earnings Overview')

@section('styles')
<style>
    :root {
        --vp-primary: #4f46e5;
        --vp-primary-hover: #4338ca;
        --vp-secondary: #06b6d4;
        --vp-green: #10b981;
        --vp-amber: #f59e0b;
        --vp-danger: #ef4444;
        --vp-surface: #ffffff;
        --vp-border: #e2e8f0;
        --vp-radius: 14px;
    }

    .vp-page-wrapper {
        background: #f1f5f9;
        margin: -15px -15px 0 -15px;
        padding: 24px;
        min-height: calc(100vh - 60px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Hero Card */
    .vp-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        color: white;
        border-radius: var(--vp-radius);
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
        position: relative;
        overflow: hidden;
    }

    .vp-hero-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .vp-title-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #818cf8;
    }

    /* Stat Cards */
    .stat-box {
        background: var(--vp-surface);
        border: 1px solid var(--vp-border);
        border-radius: var(--vp-radius);
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
    }

    .stat-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .stat-blue .stat-icon { background: #e0e7ff; color: #4f46e5; }
    .stat-green .stat-icon { background: #d1fae5; color: #10b981; }
    .stat-amber .stat-icon { background: #fef3c7; color: #d97706; }
    .stat-cyan .stat-icon { background: #ecfeff; color: #0891b2; }

    /* Filter Card */
    .filter-card {
        background: var(--vp-surface);
        border: 1px solid var(--vp-border);
        border-radius: var(--vp-radius);
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Table styles */
    .table-container {
        background: var(--vp-surface);
        border: 1px solid var(--vp-border);
        border-radius: var(--vp-radius);
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .custom-table {
        margin: 0;
        width: 100%;
    }

    .custom-table th {
        background: #f8fafc;
        padding: 14px 18px;
        font-weight: 800;
        font-size: 13px;
        color: #475569;
        border-bottom: 1px solid var(--vp-border);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-table td {
        padding: 16px 18px;
        vertical-align: middle;
        border-bottom: 1px solid var(--vp-border);
        font-size: 14px;
        color: #334155;
    }

    .custom-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .role-badge {
        font-weight: 700;
        font-size: 11px;
        padding: 5px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-transform: uppercase;
    }
    .badge-reseller {
        background: #e0f2fe;
        color: #0369a1;
    }
    .badge-vendor {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .btn-apply {
        background: var(--vp-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        transition: background 0.2s;
    }
    .btn-apply:hover {
        background: var(--vp-primary-hover);
        color: white;
    }
    .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        transition: background 0.2s;
    }
    .btn-reset:hover {
        background: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Hero Card -->
    <div class="vp-hero-card d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
                <h1 class="h3 fw-bold mb-0 text-white">Partner Earnings</h1>
                <p class="text-white-50 mb-0 small">Monitor reseller profits, vendor sale commissions, and platform earnings.</p>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Sales Vol -->
        <div class="col-md-3">
            <div class="stat-box stat-blue">
                <div>
                    <div class="stat-icon"><i class="fas fa-cart-shopping"></i></div>
                    <div class="stat-label">Total Sales Volume</div>
                </div>
                <div class="stat-value">৳{{ number_format($totalSalesAmount, 2) }}</div>
            </div>
        </div>

        <!-- Reseller Profits -->
        <div class="col-md-3">
            <div class="stat-box stat-cyan">
                <div>
                    <div class="stat-icon"><i class="fas fa-user-tag"></i></div>
                    <div class="stat-label">Resellers Earned</div>
                </div>
                <div class="stat-value">৳{{ number_format($totalResellerEarnings, 2) }}</div>
            </div>
        </div>

        <!-- Vendor Earnings -->
        <div class="col-md-3">
            <div class="stat-box stat-green">
                <div>
                    <div class="stat-icon"><i class="fas fa-store"></i></div>
                    <div class="stat-label">Vendors Earned</div>
                </div>
                <div class="stat-value">৳{{ number_format($totalVendorEarnings, 2) }}</div>
            </div>
        </div>

        <!-- Platform commission -->
        <div class="col-md-3">
            <div class="stat-box stat-amber">
                <div>
                    <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                    <div class="stat-label">Platform Commissions</div>
                </div>
                <div class="stat-value">৳{{ number_format($totalPlatformCommission, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.vendor-earnings.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Partner Type</label>
                    <select name="partner_type" id="partner_type" class="form-select rounded-3">
                        <option value="">All Partner Types</option>
                        <option value="reseller" {{ $partnerType === 'reseller' ? 'selected' : '' }}>Resellers Only</option>
                        <option value="vendor" {{ $partnerType === 'vendor' ? 'selected' : '' }}>Vendors Only</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Specific Partner</label>
                    <select name="partner_id" id="partner_id" class="form-select rounded-3">
                        <option value="">All Partners</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ $partnerId == $partner->id ? 'selected' : '' }} data-role="{{ $partner->hasRole('reseller') ? 'reseller' : 'vendor' }}">
                                {{ $partner->name }} ({{ $partner->hasRole('reseller') ? 'Reseller' : 'Vendor' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted text-uppercase">Start Date</label>
                    <input type="date" name="start_date" class="form-control rounded-3" value="{{ $startDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted text-uppercase">End Date</label>
                    <input type="date" name="end_date" class="form-control rounded-3" value="{{ $endDate }}">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-apply w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('admin.vendor-earnings.index') }}" class="btn btn-reset"><i class="fas fa-undo"></i></a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-3" placeholder="Search by Order #, Partner Name, Business Name, or Product Title..." value="{{ $search }}">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Order & Date</th>
                        <th>Partner Info</th>
                        <th>Product & Qty</th>
                        <th class="text-end">Sale Total</th>
                        <th class="text-end">Partner Profit</th>
                        <th class="text-end">Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings as $item)
                        @php
                            $isReseller = $item->vendor && $item->vendor->hasRole('reseller');
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-primary">#{{ $item->order->order_number ?? $item->order->id ?? $item->order_id }}</div>
                                <div class="small text-dark fw-bold">{{ $item->order->name ?? 'N/A' }}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;">{{ $item->order ? $item->order->created_at->format('d M, Y h:i A') : '' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->vendor->name ?? 'Deleted Partner' }}</div>
                                <div>
                                    <span class="role-badge {{ $isReseller ? 'badge-reseller' : 'badge-vendor' }}">
                                        <i class="fas {{ $isReseller ? 'fa-user-tag' : 'fa-store' }}"></i>
                                        {{ $isReseller ? 'Reseller' : 'Vendor' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="{{ $item->product->title ?? 'N/A' }}">
                                    {{ $item->product->title ?? 'N/A' }}
                                </div>
                                <div class="small text-muted">Qty: {{ $item->quantity }} × ৳{{ number_format($item->price, 2) }}</div>
                            </td>
                            <td class="text-end fw-bold text-dark">
                                ৳{{ number_format($item->sub_total, 2) }}
                            </td>
                            <td class="text-end fw-bold text-success">
                                ৳{{ number_format($item->vendor_earning, 2) }}
                            </td>
                            <td class="text-end fw-bold text-warning">
                                ৳{{ number_format($item->vendor_commission_amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-receipt fa-3x mb-3 text-light"></i>
                                <p class="mb-0 fw-bold">No earning transactions found</p>
                                <small>Adjust filters or date range to find transactions.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($earnings->hasPages())
            <div class="px-4 py-3 border-top bg-light">
                {{ $earnings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const partnerType = document.getElementById('partner_type');
        const partnerSelect = document.getElementById('partner_id');
        const originalOptions = Array.from(partnerSelect.options);

        function filterPartners() {
            const selectedType = partnerType.value;
            partnerSelect.innerHTML = '';
            
            // Re-add "All Partners" option
            partnerSelect.appendChild(originalOptions[0]);

            originalOptions.forEach(function(option, index) {
                if (index === 0) return;
                const role = option.getAttribute('data-role');
                if (!selectedType || role === selectedType) {
                    partnerSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        partnerType.addEventListener('change', filterPartners);
        
        // Initial run in case page loaded with filter pre-selected
        if (partnerType.value) {
            filterPartners();
        }
    });
</script>
@endsection
