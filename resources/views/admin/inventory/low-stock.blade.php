@extends('layouts.master')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --amber: #f59e0b;
        --amber-dark: #d97706;
        --amber-light: rgba(245,158,11,0.1);
        --red: #ef4444;
        --red-light: rgba(239,68,68,0.1);
        --dark: #0f172a;
        --slate: #1e293b;
        --border: #e2e8f0;
        --font: 'Outfit', sans-serif;
    }

    body { font-family: var(--font) !important; }
    .ls-container { max-width: 1400px; margin: 0 auto; padding: 1.5rem 2rem; font-family: var(--font); }

    /* ── Page Header ── */
    .ls-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 24px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }
    .ls-header::before {
        content: '';
        position: absolute;
        top: -40%; right: -5%;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(245,158,11,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .ls-header::after {
        content: '';
        position: absolute;
        bottom: -50%; left: 5%;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(239,68,68,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .ls-header-left { display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1; }
    .ls-icon-badge {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 8px 20px rgba(245,158,11,0.35);
        flex-shrink: 0;
    }
    .ls-header h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.02em; }
    .ls-header p { font-size: 0.875rem; color: rgba(255,255,255,0.5); margin: 3px 0 0 0; }
    .breadcrumb-ls { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; margin-top: 4px; }
    .breadcrumb-ls a { color: rgba(255,255,255,0.4); text-decoration: none; }
    .breadcrumb-ls a:hover { color: rgba(255,255,255,0.7); }
    .breadcrumb-ls span { color: rgba(255,255,255,0.2); }
    .breadcrumb-ls .current { color: rgba(255,255,255,0.7); font-weight: 600; }
    .btn-back-inv {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.15);
        padding: 10px 20px; border-radius: 12px;
        font-weight: 700; font-size: 0.875rem;
        text-decoration: none;
        backdrop-filter: blur(8px);
        transition: all 0.25s;
        position: relative; z-index: 1;
    }
    .btn-back-inv:hover { background: rgba(255,255,255,0.18); color: #fff; transform: translateY(-1px); }

    /* ── Alert Banner ── */
    .ls-alert-banner {
        background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(239,68,68,0.05) 100%);
        border: 1.5px solid rgba(245,158,11,0.25);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ls-alert-banner .alert-icon {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(245,158,11,0.1));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #f59e0b; flex-shrink: 0;
    }
    .ls-alert-banner .alert-text strong { color: #92400e; font-size: 0.95rem; }
    .ls-alert-banner .alert-text span { color: #78350f; font-size: 0.875rem; }

    /* ── Product Cards Grid ── */
    .ls-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    @media(max-width: 768px) { .ls-grid { grid-template-columns: 1fr; } }

    .ls-card {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative;
    }
    .ls-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
    }
    .ls-card.critical::before { background: linear-gradient(90deg, #ef4444, #f87171); }
    .ls-card.warning::before  { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .ls-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(0,0,0,0.08); border-color: rgba(245,158,11,0.2); }

    .ls-card-body { padding: 1.25rem; }
    .ls-product-row { display: flex; gap: 1rem; align-items: flex-start; }

    .ls-thumb {
        width: 68px; height: 68px; border-radius: 14px;
        object-fit: cover;
        border: 2px solid #f0f4f8;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .ls-thumb-placeholder {
        width: 68px; height: 68px; border-radius: 14px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 1.4rem;
    }
    .ls-product-info { flex: 1; min-width: 0; }
    .ls-product-name {
        font-size: 0.95rem; font-weight: 800; color: #0f172a;
        margin-bottom: 4px; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .ls-product-meta {
        display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .ls-meta-badge {
        font-size: 0.72rem; font-weight: 700; padding: 3px 10px;
        border-radius: 20px; letter-spacing: 0.04em;
    }
    .ls-meta-id { background: #f1f5f9; color: #64748b; }
    .ls-meta-cat { background: rgba(99,102,241,0.08); color: #6366f1; }
    .ls-meta-type { background: rgba(16,185,129,0.08); color: #059669; }

    /* Stock Level Display */
    .ls-stock-display {
        background: #f8fafc;
        border-radius: 12px;
        padding: 10px 14px;
        margin: 10px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ls-stock-num {
        font-size: 1.75rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1;
    }
    .ls-stock-num.critical { color: #ef4444; }
    .ls-stock-num.warning { color: #f59e0b; }
    .ls-stock-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #94a3b8; }
    .ls-threshold {
        display: flex; flex-direction: column; align-items: flex-end;
    }
    .ls-threshold .t-val { font-size: 0.9rem; font-weight: 700; color: #475569; }
    .ls-threshold .t-label { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }

    /* Mini progress bar */
    .ls-progress-wrap { margin: 6px 0 12px 0; }
    .ls-progress-bar-bg { background: #f0f4f8; border-radius: 20px; height: 6px; overflow: hidden; }
    .ls-progress-fill { height: 100%; border-radius: 20px; transition: width 0.6s ease; }
    .ls-progress-fill.critical { background: linear-gradient(90deg, #ef4444, #f87171); }
    .ls-progress-fill.warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    /* Variation mini list */
    .ls-variations {
        border-top: 1.5px solid #f0f4f8;
        padding-top: 10px;
        margin-top: 2px;
    }
    .ls-var-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; margin-bottom: 8px; }
    .ls-var-row { display: flex; justify-content: space-between; align-items: center; padding: 4px 0; border-bottom: 1px solid #f8fafc; }
    .ls-var-row:last-child { border-bottom: none; }
    .ls-var-name { font-size: 0.8rem; color: #475569; font-weight: 500; }
    .ls-var-badge { font-size: 0.72rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; }
    .ls-var-badge.critical { background: rgba(239,68,68,0.1); color: #dc2626; }
    .ls-var-badge.warning  { background: rgba(245,158,11,0.1); color: #d97706; }

    /* Action Buttons */
    .ls-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-restock {
        flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff; border: none; padding: 10px; border-radius: 12px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer;
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        transition: all 0.25s;
    }
    .btn-restock:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(245,158,11,0.4); }
    .btn-edit-product {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: #f8fafc; color: #475569; border: 1.5px solid #e2e8f0;
        padding: 10px 16px; border-radius: 12px;
        font-weight: 700; font-size: 0.85rem; text-decoration: none;
        transition: all 0.25s;
    }
    .btn-edit-product:hover { background: rgba(99,102,241,0.06); border-color: #6366f1; color: #4f46e5; }

    /* ── Empty State ── */
    .ls-empty {
        text-align: center; padding: 5rem 2rem;
        background: #fff; border-radius: 24px;
        border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .ls-empty .empty-icon {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
        border-radius: 28px; margin: 0 auto 1.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
    }
    .ls-empty h3 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .ls-empty p { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .btn-back-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; border: none; padding: 12px 24px; border-radius: 14px;
        font-weight: 700; text-decoration: none;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
        transition: all 0.25s;
    }
    .btn-back-primary:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }

    /* Modal styling */
    #adjustStockModal .modal-content {
        border-radius: 20px; border: none;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }
    #adjustStockModal .modal-header {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #fff; border-radius: 20px 20px 0 0; padding: 1.25rem 1.5rem;
    }
    #adjustStockModal .modal-header h5 { font-weight: 800; font-size: 1rem; color: #fff; }
    #adjustStockModal .btn-close { filter: invert(1); }
    #adjustStockModal .modal-body { padding: 1.5rem; }
</style>
@endsection

@section('content')
<div class="ls-container">

    <!-- Header -->
    <div class="ls-header">
        <div class="ls-header-left">
            <div class="ls-icon-badge">⚠️</div>
            <div>
                <h1>Low Stock Alerts</h1>
                <div class="breadcrumb-ls">
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('admin.inventory.index') }}">Inventory</a>
                    <span>/</span>
                    <span class="current">Low Stock Alert</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="btn-back-inv">
            <i class="fa-solid fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    @if($lowStockProducts->count() > 0)

    <!-- Alert Banner -->
    <div class="ls-alert-banner">
        <div class="alert-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="alert-text">
            <strong>{{ $lowStockProducts->total() }} {{ Str::plural('product', $lowStockProducts->total()) }}</strong>
            <span> are running low on stock and need immediate attention.</span>
        </div>
    </div>

    <!-- Product Cards -->
    <div class="ls-grid">
        @foreach($lowStockProducts as $product)
        @php
            $isVariable = $product->product_type === 'variable';
            $stock = $isVariable
                ? $product->variationCombinations->sum('stock_quantity')
                : ($product->quantity ?? 0);
            $threshold = $product->low_stock_threshold ?? 5;
            $isCritical = $stock <= 2;
            $cardClass = $isCritical ? 'critical' : 'warning';
            $progressPct = $threshold > 0 ? min(100, round(($stock / $threshold) * 100)) : 0;
            $lowVars = $isVariable
                ? $product->variationCombinations->where('stock_quantity', '<=', 5)->where('stock_quantity', '>', 0)
                : collect();
        @endphp
        <div class="ls-card {{ $cardClass }}">
            <div class="ls-card-body">
                <!-- Product row -->
                <div class="ls-product-row">
                    @if($product->thumb_image)
                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="ls-thumb">
                    @else
                        <div class="ls-thumb-placeholder"><i class="fa-solid fa-image"></i></div>
                    @endif

                    <div class="ls-product-info">
                        <div class="ls-product-name">{{ $product->title }}</div>
                        <div class="ls-product-meta">
                            <span class="ls-meta-badge ls-meta-id">#{{ $product->id }}</span>
                            <span class="ls-meta-badge ls-meta-cat">{{ $product->category->name ?? 'No Category' }}</span>
                            <span class="ls-meta-badge ls-meta-type">{{ ucfirst($product->product_type) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stock Display -->
                <div class="ls-stock-display">
                    <div>
                        <div class="ls-stock-num {{ $cardClass }}">{{ $stock }}</div>
                        <div class="ls-stock-label">{{ $isVariable ? 'Total Units' : 'Units Left' }}</div>
                    </div>
                    <div class="ls-threshold">
                        <div class="t-val">{{ $threshold }}</div>
                        <div class="t-label">Threshold</div>
                    </div>
                </div>

                <!-- Progress bar -->
                <div class="ls-progress-wrap">
                    <div class="ls-progress-bar-bg">
                        <div class="ls-progress-fill {{ $cardClass }}" style="width: {{ $progressPct }}%;"></div>
                    </div>
                </div>

                <!-- Variation mini list -->
                @if($isVariable && $lowVars->count() > 0)
                <div class="ls-variations">
                    <div class="ls-var-title">Low Stock Variations</div>
                    @foreach($lowVars->take(3) as $combination)
                    <div class="ls-var-row">
                        <span class="ls-var-name">{{ $combination->display_name }}</span>
                        <span class="ls-var-badge {{ $combination->stock_quantity <= 2 ? 'critical' : 'warning' }}">
                            {{ $combination->stock_quantity }} left
                        </span>
                    </div>
                    @endforeach
                    @if($lowVars->count() > 3)
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;">+{{ $lowVars->count() - 3 }} more variations...</div>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="ls-actions">
                    <button type="button" class="btn-restock adjust-stock-btn"
                            data-product-id="{{ $product->id }}"
                            data-product-title="{{ $product->title }}"
                            data-product-type="{{ $product->product_type }}">
                        <i class="fa-solid fa-plus"></i> Restock
                    </button>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-edit-product">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-2">
        {{ $lowStockProducts->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="ls-empty">
        <div class="empty-icon">✅</div>
        <h3>All Good! 🎉</h3>
        <p>No products are currently running low on stock. Your inventory looks healthy!</p>
        <a href="{{ route('admin.inventory.index') }}" class="btn-back-primary">
            <i class="fa-solid fa-arrow-left"></i> Back to Inventory
        </a>
    </div>
    @endif

</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📝 Restock Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="adjust-stock-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.adjust-stock-btn', function() {
            const productId    = $(this).data('product-id');
            const productTitle = $(this).data('product-title');
            const productType  = $(this).data('product-type');

            $('#adjustStockModal .modal-title').html('📦 Restock — ' + productTitle);
            $('#adjust-stock-content').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            $('#adjustStockModal').modal('show');

            $.get('{{ route("admin.inventory.adjust-form", ":id") }}'.replace(':id', productId))
                .done(function(response) {
                    $('#adjust-stock-content').html(response);
                    if (productType === 'simple') {
                        $('#adjustment-type').val('restock');
                    } else {
                        $('select[name*="[type]"]').val('restock');
                    }
                })
                .fail(function() {
                    $('#adjust-stock-content').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Failed to load restock form. Please try again.
                        </div>
                    `);
                });
        });
    });
</script>
@endsection
