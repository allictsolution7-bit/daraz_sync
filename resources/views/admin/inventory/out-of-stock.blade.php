@extends('layouts.master')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');
    body { font-family: 'Outfit', sans-serif !important; }

    .oos-container { max-width: 1400px; margin: 0 auto; padding: 1.5rem 2rem; font-family: 'Outfit', sans-serif; }

    /* ── Page Header ── */
    .oos-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 24px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem;
        position: relative; overflow: hidden;
    }
    .oos-header::before {
        content: ''; position: absolute; top: -40%; right: -5%;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(239,68,68,0.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .oos-header::after {
        content: ''; position: absolute; bottom: -50%; left: 5%;
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(239,68,68,0.06) 0%, transparent 70%);
        border-radius: 50%;
    }
    .oos-header-left { display: flex; align-items: center; gap: 1rem; position: relative; z-index: 1; }
    .oos-icon-badge {
        width: 52px; height: 52px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem; box-shadow: 0 8px 20px rgba(239,68,68,0.4); flex-shrink: 0;
    }
    .oos-header h1 { font-size: 1.5rem; font-weight: 800; color: #fff; margin: 0; letter-spacing: -0.02em; }
    .breadcrumb-oos { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; margin-top: 4px; }
    .breadcrumb-oos a { color: rgba(255,255,255,0.4); text-decoration: none; }
    .breadcrumb-oos a:hover { color: rgba(255,255,255,0.7); }
    .breadcrumb-oos span { color: rgba(255,255,255,0.2); }
    .breadcrumb-oos .current { color: rgba(255,255,255,0.7); font-weight: 600; }
    .btn-back-inv {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8);
        border: 1px solid rgba(255,255,255,0.15);
        padding: 10px 20px; border-radius: 12px;
        font-weight: 700; font-size: 0.875rem; text-decoration: none;
        backdrop-filter: blur(8px); transition: all 0.25s;
        position: relative; z-index: 1;
    }
    .btn-back-inv:hover { background: rgba(255,255,255,0.18); color: #fff; transform: translateY(-1px); }

    /* ── Alert Banner ── */
    .oos-alert-banner {
        background: linear-gradient(135deg, rgba(239,68,68,0.07) 0%, rgba(239,68,68,0.03) 100%);
        border: 1.5px solid rgba(239,68,68,0.2);
        border-radius: 16px; padding: 1rem 1.5rem;
        margin-bottom: 1.75rem; display: flex; align-items: center; gap: 12px;
    }
    .oos-alert-banner .alert-icon {
        width: 40px; height: 40px; border-radius: 12px;
        background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(239,68,68,0.08));
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #ef4444; flex-shrink: 0;
    }
    .oos-alert-banner .alert-text strong { color: #991b1b; font-size: 0.95rem; }
    .oos-alert-banner .alert-text span { color: #7f1d1d; font-size: 0.875rem; }

    /* ── Cards Grid ── */
    .oos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
    @media(max-width:768px) { .oos-grid { grid-template-columns: 1fr; } }

    .oos-card {
        background: #fff; border-radius: 20px;
        border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        overflow: hidden; transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative;
    }
    .oos-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #ef4444, #f87171);
    }
    .oos-card:hover { transform: translateY(-5px); box-shadow: 0 16px 40px rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.2); }
    .oos-card-body { padding: 1.25rem; }
    .oos-product-row { display: flex; gap: 1rem; align-items: flex-start; }

    .oos-thumb {
        width: 68px; height: 68px; border-radius: 14px;
        object-fit: cover; border: 2px solid #f0f4f8;
        flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .oos-thumb-placeholder {
        width: 68px; height: 68px; border-radius: 14px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 1.4rem;
    }
    .oos-product-info { flex: 1; min-width: 0; }
    .oos-product-name {
        font-size: 0.95rem; font-weight: 800; color: #0f172a;
        margin-bottom: 4px; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .oos-product-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 10px; }
    .oos-meta-badge { font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: 0.04em; }
    .oos-meta-id  { background: #f1f5f9; color: #64748b; }
    .oos-meta-cat { background: rgba(99,102,241,0.08); color: #6366f1; }
    .oos-meta-type { background: rgba(16,185,129,0.08); color: #059669; }

    /* Stock zero display */
    .oos-stock-display {
        background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.12);
        border-radius: 12px; padding: 10px 14px; margin: 10px 0;
        display: flex; align-items: center; justify-content: space-between;
    }
    .oos-stock-num { font-size: 2rem; font-weight: 800; letter-spacing: -0.04em; line-height: 1; color: #dc2626; }
    .oos-stock-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #94a3b8; }
    .oos-status-badge {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff; font-size: 0.72rem; font-weight: 800;
        padding: 5px 12px; border-radius: 20px; letter-spacing: 0.05em;
        box-shadow: 0 3px 10px rgba(239,68,68,0.3);
    }

    /* Last movement row */
    .oos-last-move {
        background: #f8fafc; border-radius: 10px; padding: 8px 12px;
        margin: 8px 0; font-size: 0.78rem; color: #64748b;
        display: flex; align-items: center; gap: 8px;
    }
    .oos-last-move i { color: #94a3b8; }

    /* Variation mini list */
    .oos-variations { border-top: 1.5px solid #f0f4f8; padding-top: 10px; margin-top: 4px; }
    .oos-var-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; margin-bottom: 8px; }
    .oos-var-row { display: flex; justify-content: space-between; align-items: center; padding: 4px 0; border-bottom: 1px solid #f8fafc; }
    .oos-var-row:last-child { border-bottom: none; }
    .oos-var-name { font-size: 0.8rem; color: #475569; font-weight: 500; }
    .oos-var-badge { font-size: 0.72rem; font-weight: 800; padding: 2px 10px; border-radius: 20px; background: rgba(239,68,68,0.1); color: #dc2626; }

    /* Actions */
    .oos-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn-restock-now {
        flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff; border: none; padding: 10px; border-radius: 12px;
        font-weight: 700; font-size: 0.85rem; cursor: pointer;
        box-shadow: 0 4px 12px rgba(239,68,68,0.3); transition: all 0.25s;
    }
    .btn-restock-now:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(239,68,68,0.4); }
    .btn-edit-product {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        background: #f8fafc; color: #475569; border: 1.5px solid #e2e8f0;
        padding: 10px 16px; border-radius: 12px;
        font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: all 0.25s;
    }
    .btn-edit-product:hover { background: rgba(99,102,241,0.06); border-color: #6366f1; color: #4f46e5; }

    /* Empty State */
    .oos-empty {
        text-align: center; padding: 5rem 2rem;
        background: #fff; border-radius: 24px; border: 1.5px solid #f0f4f8;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .oos-empty .empty-icon {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
        border-radius: 28px; margin: 0 auto 1.5rem;
        display: flex; align-items: center; justify-content: center; font-size: 2.5rem;
    }
    .oos-empty h3 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
    .oos-empty p { color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem; }
    .btn-back-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff; border: none; padding: 12px 24px; border-radius: 14px;
        font-weight: 700; text-decoration: none;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35); transition: all 0.25s;
    }
    .btn-back-primary:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }

    /* Modal */
    #adjustStockModal .modal-content { border-radius: 20px; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.15); }
    #adjustStockModal .modal-header { background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff; border-radius: 20px 20px 0 0; padding: 1.25rem 1.5rem; }
    #adjustStockModal .modal-header h5 { font-weight: 800; font-size: 1rem; color: #fff; }
    #adjustStockModal .btn-close { filter: invert(1); }
    #adjustStockModal .modal-body { padding: 1.5rem; }
</style>
@endsection

@section('content')
<div class="oos-container">

    <!-- Header -->
    <div class="oos-header">
        <div class="oos-header-left">
            <div class="oos-icon-badge">🚫</div>
            <div>
                <h1>Out of Stock Products</h1>
                <div class="breadcrumb-oos">
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('admin.inventory.index') }}">Inventory</a>
                    <span>/</span>
                    <span class="current">Out of Stock</span>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="btn-back-inv">
            <i class="fa-solid fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    @if($outOfStockProducts->count() > 0)

    <!-- Alert Banner -->
    <div class="oos-alert-banner">
        <div class="alert-icon">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div class="alert-text">
            <strong>{{ $outOfStockProducts->total() }} {{ Str::plural('product', $outOfStockProducts->total()) }}</strong>
            <span> are currently out of stock and need immediate restocking.</span>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="oos-grid">
        @foreach($outOfStockProducts as $product)
        @php
            $isVariable = $product->product_type === 'variable';
            $stock = $isVariable
                ? $product->variationCombinations->sum('stock_quantity')
                : ($product->quantity ?? 0);
            $outVars = $isVariable
                ? $product->variationCombinations->where('stock_quantity', '<=', 0)
                : collect();
            $totalVars = $isVariable ? $product->variationCombinations->count() : 0;
            $lastMovement = \App\Models\StockMovement::where('product_id', $product->id)->orderBy('created_at','desc')->first();
        @endphp
        <div class="oos-card">
            <div class="oos-card-body">
                <!-- Product row -->
                <div class="oos-product-row">
                    @if($product->thumb_image)
                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="oos-thumb">
                    @else
                        <div class="oos-thumb-placeholder"><i class="fa-solid fa-image"></i></div>
                    @endif
                    <div class="oos-product-info">
                        <div class="oos-product-name">{{ $product->title }}</div>
                        <div class="oos-product-meta">
                            <span class="oos-meta-badge oos-meta-id">#{{ $product->id }}</span>
                            <span class="oos-meta-badge oos-meta-cat">{{ $product->category->name ?? 'No Category' }}</span>
                            <span class="oos-meta-badge oos-meta-type">{{ ucfirst($product->product_type) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stock display -->
                <div class="oos-stock-display">
                    <div>
                        <div class="oos-stock-num">{{ $stock }}</div>
                        <div class="oos-stock-label">{{ $isVariable ? 'Total Units' : 'Units in Stock' }}</div>
                    </div>
                    <span class="oos-status-badge">OUT OF STOCK</span>
                </div>

                @if($isVariable && $outVars->count() > 0)
                <!-- Variation info -->
                <div style="font-size:0.8rem;color:#64748b;margin-bottom:8px;padding:0 2px;">
                    <i class="fa-solid fa-layer-group me-1" style="color:#94a3b8;"></i>
                    {{ $outVars->count() }} / {{ $totalVars }} variations out of stock
                </div>
                <div class="oos-variations">
                    <div class="oos-var-title">Out of Stock Variations</div>
                    @foreach($outVars->take(3) as $combination)
                    <div class="oos-var-row">
                        <span class="oos-var-name">{{ $combination->display_name }}</span>
                        <span class="oos-var-badge">{{ $combination->stock_quantity }} units</span>
                    </div>
                    @endforeach
                    @if($outVars->count() > 3)
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;">+{{ $outVars->count() - 3 }} more...</div>
                    @endif
                </div>
                @endif

                <!-- Last movement -->
                @if($lastMovement)
                <div class="oos-last-move">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Last: {{ ucfirst($lastMovement->type) }}
                    ({{ $lastMovement->quantity > 0 ? '+' : '' }}{{ $lastMovement->quantity }})
                    &bull; {{ $lastMovement->created_at->diffForHumans() }}
                </div>
                @else
                <div class="oos-last-move">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    Updated {{ $product->updated_at->diffForHumans() }}
                </div>
                @endif

                <!-- Actions -->
                <div class="oos-actions">
                    <button type="button" class="btn-restock-now adjust-stock-btn"
                            data-product-id="{{ $product->id }}"
                            data-product-title="{{ $product->title }}"
                            data-product-type="{{ $product->product_type }}">
                        <i class="fa-solid fa-plus"></i> Restock Now
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
        {{ $outOfStockProducts->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="oos-empty">
        <div class="empty-icon">✅</div>
        <h3>Excellent Stock Management! 🎉</h3>
        <p>No products are currently out of stock. Your inventory is in great shape!</p>
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
                <h5 class="modal-title">📦 Restock Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="adjust-stock-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-danger" role="status">
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
                    <div class="spinner-border text-danger" role="status">
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
