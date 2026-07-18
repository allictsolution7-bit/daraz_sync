@extends('layouts.master')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Outfit', sans-serif !important; }

    .hist-container { max-width: 1400px; margin: 0 auto; padding: 1.5rem 2rem; font-family: 'Outfit', sans-serif; }

    /* ── Page Header ── */
    .hist-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 24px; padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem;
        position: relative; overflow: hidden;
    }
    .hist-header::before {
        content: ''; position: absolute; top: -40%; right: -5%;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(99,102,241,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hist-header::after {
        content: ''; position: absolute; bottom: -50%; left: 5%;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(14,165,233,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hist-header-left { display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1; }
    .hist-icon-badge {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; box-shadow: 0 8px 20px rgba(99,102,241,0.4); flex-shrink: 0;
    }
    .hist-header h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.02em; }
    .breadcrumb-hist { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; margin-top: 4px; }
    .breadcrumb-hist a { color: rgba(255,255,255,0.4); text-decoration: none; }
    .breadcrumb-hist a:hover { color: rgba(255,255,255,0.7); }
    .breadcrumb-hist span { color: rgba(255,255,255,0.2); }
    .breadcrumb-hist .current { color: rgba(255,255,255,0.7); font-weight: 600; }
    .btn-back-inv {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.15); padding: 10px 20px; border-radius: 12px;
        font-weight: 700; font-size: 0.875rem; text-decoration: none;
        backdrop-filter: blur(8px); transition: all 0.25s; position: relative; z-index: 1;
    }
    .btn-back-inv:hover { background: rgba(255,255,255,0.18); color: #fff; transform: translateY(-1px); }

    /* ── Unified Filter + Legend Panel ── */
    .filter-panel {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    /* Top legend strip */
    .filter-panel-legend {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1.5px solid #e8edf3;
        padding: 0.9rem 1.5rem;
        display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
    }
    .legend-label {
        font-size: 0.7rem; font-weight: 900; text-transform: uppercase;
        letter-spacing: 0.1em; color: #94a3b8;
        margin-right: 6px; display: flex; align-items: center; gap: 5px;
    }
    .lchip {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.72rem; font-weight: 800; padding: 5px 13px;
        border-radius: 20px; cursor: default;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1.5px solid transparent;
    }
    .lchip:hover { transform: translateY(-1px); }
    .lchip .cdot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .lc-green  { background: rgba(16,185,129,.1);  color: #059669; border-color: rgba(16,185,129,.2); }  .lc-green .cdot  { background: #10b981; }
    .lc-red    { background: rgba(239,68,68,.1);   color: #dc2626; border-color: rgba(239,68,68,.2); }   .lc-red .cdot    { background: #ef4444; }
    .lc-sky    { background: rgba(14,165,233,.1);  color: #0284c7; border-color: rgba(14,165,233,.2); }  .lc-sky .cdot    { background: #0ea5e9; }
    .lc-indigo { background: rgba(99,102,241,.1);  color: #4f46e5; border-color: rgba(99,102,241,.2); }  .lc-indigo .cdot { background: #6366f1; }
    .lc-gray   { background: rgba(107,114,128,.1); color: #4b5563; border-color: rgba(107,114,128,.2); } .lc-gray .cdot   { background: #6b7280; }
    .lc-amber  { background: rgba(245,158,11,.1);  color: #d97706; border-color: rgba(245,158,11,.2); }  .lc-amber .cdot  { background: #f59e0b; }

    /* Filter form area */
    .filter-panel-body { padding: 1.25rem 1.5rem; }
    .filter-panel-title {
        font-size: 0.78rem; font-weight: 900; text-transform: uppercase;
        letter-spacing: 0.09em; color: #cbd5e1; margin-bottom: 1rem;
        display: flex; align-items: center; gap: 6px;
    }
    /* Search input */
    .search-wrap { position: relative; margin-bottom: 1rem; }
    .search-wrap .search-icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 0.9rem; pointer-events: none;
    }
    .search-wrap input {
        width: 100%; padding: 11px 16px 11px 44px;
        border: 1.5px solid #e2e8f0; border-radius: 14px;
        font-size: 0.9rem; font-family: 'Outfit', sans-serif;
        color: #0f172a; background: #f8fafc; transition: all 0.2s;
    }
    .search-wrap input::placeholder { color: #94a3b8; }
    .search-wrap input:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    /* Filter grid inputs */
    .filter-grid { display: grid; grid-template-columns: 2fr 1.5fr 1.2fr 1.2fr auto; gap: 12px; align-items: end; }
    @media(max-width:992px){ .filter-grid { grid-template-columns: 1fr 1fr; } }
    @media(max-width:576px){ .filter-grid { grid-template-columns: 1fr; } }
    .fg-group label {
        display: block; font-size: 0.72rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: #94a3b8; margin-bottom: 6px;
    }
    .fg-group .fg-input-wrap { position: relative; }
    .fg-group .fg-icon {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 0.8rem; pointer-events: none;
    }
    .fg-group select, .fg-group input[type=date] {
        width: 100%; padding: 10px 12px 10px 34px;
        border: 1.5px solid #e2e8f0; border-radius: 12px;
        font-size: 0.875rem; font-family: 'Outfit', sans-serif;
        color: #0f172a; background: #f8fafc; transition: all 0.2s;
        appearance: none;
    }
    .fg-group select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2394a3b8' d='M6 8L0 0h12z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
    .fg-group select:focus, .fg-group input[type=date]:focus { outline: none; border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .fg-actions { display: flex; gap: 8px; }
    .btn-apply {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg,#6366f1,#4f46e5); color: #fff;
        border: none; padding: 10px 22px; border-radius: 12px;
        font-weight: 800; font-size: 0.85rem; cursor: pointer; white-space: nowrap;
        box-shadow: 0 4px 14px rgba(99,102,241,.3); transition: all 0.25s;
    }
    .btn-apply:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
    .btn-clear {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: #f1f5f9; color: #64748b; border: 1.5px solid #e2e8f0;
        padding: 10px 16px; border-radius: 12px;
        font-weight: 700; font-size: 0.85rem; text-decoration: none; white-space: nowrap; transition: all 0.25s;
    }
    .btn-clear:hover { background: #e2e8f0; color: #1e293b; }

    /* ── Timeline Table ── */
    .hist-table-wrap {
        background: #fff; border-radius: 20px;
        border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .hist-table { width: 100%; border-collapse: collapse; }
    .hist-table thead tr {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid #e2e8f0;
    }
    .hist-table thead th {
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.08em; color: #64748b;
        padding: 1rem 1.25rem; white-space: nowrap;
    }
    .hist-table tbody tr {
        border-bottom: 1px solid #f8fafc; transition: background 0.15s;
    }
    .hist-table tbody tr:last-child { border-bottom: none; }
    .hist-table tbody tr:hover { background: #f8fafc; }
    .hist-table tbody td { padding: 0.875rem 1.25rem; vertical-align: middle; }

    /* Type badge */
    .type-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.72rem; font-weight: 800; padding: 4px 12px; border-radius: 20px;
        white-space: nowrap;
    }
    .type-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
    .tb-initial, .tb-restock { background: rgba(16,185,129,0.1); color: #059669; }
    .tb-initial .dot, .tb-restock .dot { background: #10b981; }
    .tb-sale { background: rgba(239,68,68,0.1); color: #dc2626; }
    .tb-sale .dot { background: #ef4444; }
    .tb-return { background: rgba(14,165,233,0.1); color: #0284c7; }
    .tb-return .dot { background: #0ea5e9; }
    .tb-adjustment { background: rgba(99,102,241,0.1); color: #4f46e5; }
    .tb-adjustment .dot { background: #6366f1; }
    .tb-damage { background: rgba(107,114,128,0.1); color: #4b5563; }
    .tb-damage .dot { background: #6b7280; }
    .tb-inventory_count { background: rgba(245,158,11,0.1); color: #d97706; }
    .tb-inventory_count .dot { background: #f59e0b; }

    /* Quantity change */
    .qty-positive { color: #059669; font-weight: 800; font-size: 1rem; }
    .qty-negative { color: #dc2626; font-weight: 800; font-size: 1rem; }
    .qty-neutral   { color: #64748b; font-weight: 800; font-size: 1rem; }

    /* Product cell */
    .product-cell { display: flex; align-items: center; gap: 10px; }
    .product-cell-thumb {
        width: 38px; height: 38px; border-radius: 10px; object-fit: cover;
        border: 1.5px solid #f0f4f8; flex-shrink: 0;
    }
    .product-cell-placeholder {
        width: 38px; height: 38px; border-radius: 10px;
        background: #f1f5f9; border: 1.5px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 0.9rem; flex-shrink: 0;
    }
    .product-cell-name { font-size: 0.875rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
    .product-cell-sub  { font-size: 0.72rem; color: #94a3b8; font-weight: 500; }

    /* Date cell */
    .date-main  { font-size: 0.825rem; font-weight: 700; color: #334155; }
    .date-sub   { font-size: 0.72rem; color: #94a3b8; }

    /* Variation tag */
    .variation-tag {
        display: inline-block; font-size: 0.7rem; font-weight: 700;
        background: rgba(99,102,241,0.08); color: #4f46e5;
        padding: 2px 8px; border-radius: 6px; margin-top: 3px;
    }

    /* Notes */
    .notes-text { font-size: 0.78rem; color: #64748b; max-width: 200px; }
    .ref-text { font-size: 0.75rem; color: #94a3b8; }

    /* Creator avatar */
    .creator-cell { display: flex; align-items: center; gap: 6px; }
    .creator-avatar {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; font-size: 0.7rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .creator-name { font-size: 0.8rem; font-weight: 600; color: #334155; }

    /* Empty State */
    .hist-empty {
        text-align: center; padding: 4rem 2rem;
        background: #fff; border-radius: 24px; border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .hist-empty .empty-icon {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(99,102,241,0.05));
        border-radius: 24px; margin: 0 auto 1.25rem;
        display: flex; align-items: center; justify-content: center; font-size: 2rem;
    }
    .hist-empty h3 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .hist-empty p { color: #64748b; font-size: 0.875rem; margin-bottom: 1.25rem; }
    .btn-back-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;
        border: none; padding: 12px 24px; border-radius: 14px; font-weight: 700;
        text-decoration: none; box-shadow: 0 4px 14px rgba(99,102,241,0.35); transition: all 0.25s;
    }
    .btn-back-primary:hover { color: #fff; transform: translateY(-1px); }

    @media(max-width: 768px) {
        .hist-table thead { display: none; }
        .hist-table tbody td { display: block; padding: 0.5rem 1rem; }
        .hist-table tbody td::before { content: attr(data-label); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; display: block; margin-bottom: 2px; }
        .hist-table tbody tr { border-bottom: 2px solid #f0f4f8; padding: 0.5rem 0; display: block; }
    }
</style>
@endsection

@section('content')
<div class="hist-container">

    <!-- Header -->
    <div class="hist-header">
        <div class="hist-header-left">
            <div class="hist-icon-badge">📊</div>
            <div>
                <h1>Stock Movement History</h1>
                <div class="breadcrumb-hist">
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('admin.inventory.index') }}">Inventory</a>
                    <span>/</span>
                    <span class="current">History</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="btn-back-inv">
            <i class="fa-solid fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <!-- Unified Filter + Legend Panel -->
    <div class="filter-panel">
        <!-- Legend strip -->
        <div class="filter-panel-legend">
            <span class="legend-label"><i class="fa-solid fa-tag"></i> Movement Types</span>
            <span class="lchip lc-green"><span class="cdot"></span> Initial</span>
            <span class="lchip lc-green"><span class="cdot"></span> Restock</span>
            <span class="lchip lc-red"><span class="cdot"></span> Sale</span>
            <span class="lchip lc-sky"><span class="cdot"></span> Return</span>
            <span class="lchip lc-indigo"><span class="cdot"></span> Adjustment</span>
            <span class="lchip lc-gray"><span class="cdot"></span> Damage</span>
            <span class="lchip lc-amber"><span class="cdot"></span> Inventory Count</span>
        </div>
        <!-- Filter body -->
        <div class="filter-panel-body">
            <div class="filter-panel-title"><i class="fa-solid fa-sliders"></i> Filter & Search</div>
            <form method="GET" action="{{ route('admin.inventory.history') }}" id="filter-form">
                <!-- Search -->
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" id="search"
                        placeholder="Search by product name or notes..."
                        value="{{ request('search') }}">
                </div>
                <!-- Filter grid -->
                <div class="filter-grid">
                    <div class="fg-group">
                        <label>Product</label>
                        <div class="fg-input-wrap">
                            <i class="fa-solid fa-box fg-icon"></i>
                            <select name="product_id" id="product_id">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fg-group">
                        <label>Movement Type</label>
                        <div class="fg-input-wrap">
                            <i class="fa-solid fa-shuffle fg-icon"></i>
                            <select name="type" id="type">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fg-group">
                        <label>From Date</label>
                        <div class="fg-input-wrap">
                            <i class="fa-solid fa-calendar fg-icon"></i>
                            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="fg-group">
                        <label>To Date</label>
                        <div class="fg-input-wrap">
                            <i class="fa-solid fa-calendar-check fg-icon"></i>
                            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="fg-actions">
                        <button type="submit" class="btn-apply"><i class="fa-solid fa-filter"></i> Apply</button>
                        <a href="{{ route('admin.inventory.history') }}" class="btn-clear"><i class="fa-solid fa-xmark"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($movements->count() > 0)


    <!-- Table -->
    <div class="hist-table-wrap">
        <table class="hist-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Qty Change</th>
                    <th>Variation</th>
                    <th>Notes</th>
                    <th>Reference</th>
                    <th>By</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $movement)
                @php
                    $typeClass = 'tb-' . $movement->type;
                    $isPositive = $movement->quantity > 0;
                    $isNegative = $movement->quantity < 0;
                    $qtyClass = $isPositive ? 'qty-positive' : ($isNegative ? 'qty-negative' : 'qty-neutral');
                    $qtyPrefix = $isPositive ? '+' : '';
                    $product = $movement->product;
                @endphp
                <tr>
                    <!-- Product -->
                    <td data-label="Product">
                        <div class="product-cell">
                            @if($product && $product->thumb_image)
                                <img src="{{ asset('storage/' . $product->thumb_image) }}" class="product-cell-thumb" alt="">
                            @else
                                <div class="product-cell-placeholder"><i class="fa-solid fa-box"></i></div>
                            @endif
                            <div>
                                <div class="product-cell-name">{{ $product->title ?? 'Unknown Product' }}</div>
                                <div class="product-cell-sub">#{{ $product->id ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <!-- Type -->
                    <td data-label="Type">
                        <span class="type-badge {{ $typeClass }}">
                            <span class="dot"></span>
                            {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                        </span>
                    </td>
                    <!-- Qty -->
                    <td data-label="Qty Change">
                        <span class="{{ $qtyClass }}">{{ $qtyPrefix }}{{ $movement->quantity }}</span>
                    </td>
                    <!-- Variation -->
                    <td data-label="Variation">
                        @if($movement->variationCombination)
                            <span class="variation-tag">{{ $movement->variationCombination->display_name }}</span>
                        @else
                            <span style="color:#cbd5e1;font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <!-- Notes -->
                    <td data-label="Notes">
                        @if($movement->notes)
                            <span class="notes-text">{{ Str::limit($movement->notes, 50) }}</span>
                        @else
                            <span style="color:#cbd5e1;font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <!-- Reference -->
                    <td data-label="Reference">
                        @if($movement->reference_type && $movement->reference_id)
                            <span class="ref-text">{{ $movement->reference_type }} #{{ $movement->reference_id }}</span>
                        @else
                            <span style="color:#cbd5e1;font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <!-- Creator -->
                    <td data-label="By">
                        @if($movement->creator)
                        <div class="creator-cell">
                            <div class="creator-avatar">{{ strtoupper(substr($movement->creator->name, 0, 2)) }}</div>
                            <span class="creator-name">{{ $movement->creator->name }}</span>
                        </div>
                        @else
                            <span style="color:#cbd5e1;font-size:0.8rem;">System</span>
                        @endif
                    </td>
                    <!-- Date -->
                    <td data-label="Date & Time">
                        <div class="date-main">{{ $movement->created_at->format('M d, Y') }}</div>
                        <div class="date-sub">{{ $movement->created_at->format('h:i A') }} &bull; {{ $movement->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-2">
        {{ $movements->appends(request()->query())->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="hist-empty">
        <div class="empty-icon">📋</div>
        <h3>No Stock Movements Found</h3>
        <p>
            @if(request()->hasAny(['product_id', 'type', 'date_from', 'date_to']))
                No movements match your current filters. Try adjusting your search criteria.
            @else
                No stock movements have been recorded yet.
            @endif
        </p>
        <a href="{{ route('admin.inventory.index') }}" class="btn-back-primary">
            <i class="fa-solid fa-arrow-left"></i> Back to Inventory
        </a>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const today = new Date().toISOString().split('T')[0];
        $('#date_from, #date_to').attr('max', today);
        $('#date_from, #date_to').on('change', function() {
            const from = $('#date_from').val(), to = $('#date_to').val();
            if (from && to && from > to) {
                alert('From date cannot be later than To date.');
                $(this).val('');
            }
        });
    });
</script>
@endsection
