@extends('layouts.master')

@section('title', 'Product Mappings')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #daraz-mappings-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── ALERTS ──────────────────────────────────── */
    .alert-premium {
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        position: relative;
    }
    .alert-ok-p  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #86efac; color: #15803d; }
    .alert-err-p { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fca5a5; color: #b91c1c; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ── HEADER BANNER ── */
    .hdr-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .hdr-title h4 { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .hdr-title p { font-size: 0.85rem; color: #64748b; margin: 0; }

    .header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-hdr.primary { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .btn-hdr.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); color: #fff; }
    .btn-hdr.ghost { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-hdr.ghost:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── FILTER CONTAINER ── */
    .filter-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }
    .field-input, .field-select {
        width: 100%; padding: 8px 12px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        font-size: 0.85rem; color: #0f172a;
        background: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease; outline: none;
        height: 38px;
    }
    .field-input:focus, .field-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    /* ── TABLE CARD ── */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .table-card-header h2 {
        font-size: 1rem; font-weight: 800; color: #fff; margin: 0;
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-header .hdr-icon {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.15); border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff; backdrop-filter: blur(4px);
    }

    /* ── BULK ACTION ROW ── */
    .bulk-action-bar {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .bulk-btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        color: #475569;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all 0.15s ease;
    }
    .bulk-btn.bb-green:hover { border-color: #86efac; background: #f0fdf4; color: #15803d; }
    .bulk-btn.bb-orange:hover { border-color: #fbd38d; background: #fffaf0; color: #dd6b20; }
    .bulk-btn.bb-red:hover { border-color: #fca5a5; background: #fff5f5; color: #e53e3e; }

    /* ── DATA TABLE ── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .data-table thead th {
        padding: 12px 16px;
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: left;
        white-space: nowrap;
    }
    .data-table td {
        padding: 12px 16px;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    /* ── FIXED WIDTH & COLOR CODED ROWS ── */
    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    /* Alternating list row colors */
    .data-table tbody tr.row-item-odd { background: #ffffff; }
    .data-table tbody tr.row-item-even { background: #fafbff; }
    .data-table tbody tr:hover { background: #f1f5f9 !important; }
    .data-table tbody tr:last-child { border-bottom: none; }

    /* LESS WIDTH & FIXED WIDTH for Product column */
    .col-product {
        width: 250px !important;
        max-width: 250px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .text-title { font-weight: 700; color: #0f172a; }
    .text-subtitle { font-size: 0.76rem; color: #64748b; margin-top: 2px; display: block; }

    /* ── BADGES ── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .bp-active   { background: #dcfce7; color: #15803d; }
    .bp-inactive { background: #fee2e2; color: #b91c1c; }
    .bp-warning  { background: #fef3c7; color: #b45309; }
    .bp-country  { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }

    /* ── ACTION BUTTONS ── */
    .row-actions { display: flex; gap: 5px; align-items: center; }
    .icon-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .icon-btn.ib-sync:hover { border-color: #86efac; background: #f0fdf4; color: #15803d; }
    .icon-btn.ib-edit:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }
    .icon-btn.ib-delete:hover { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }

    .mapping-checkbox {
        width: 16px; height: 16px;
        border: 1.5px solid #cbd5e1; border-radius: 4px;
        cursor: pointer; display: block; margin: 0;
    }

    /* ── EMPTY STATE ── */
    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon {
        width: 70px; height: 70px;
        border-radius: 50%;
        background: #ede9fe;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: #6366f1;
    }
    .empty-state h4 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .empty-state p  { font-size: 0.875rem; color: #94a3b8; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div id="daraz-mappings-page" class="container-fluid px-4 py-4">

    @if(session('success'))
        <div class="alert-premium alert-ok-p">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Inventory Pair Bridges</h4>
            <p>Map catalog products to seller listing SKUs</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-hdr ghost" id="autoMapBtn" {{ $stores->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-magic"></i> Auto-Link
            </button>
            <a href="{{ route('admin.daraz.mappings.create') }}" class="btn-hdr primary">
                <i class="fas fa-plus"></i> Link Pair
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-card">
        <form action="{{ route('admin.daraz.mappings.index') }}" method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="store_id" class="field-select">
                    <option value="">All Stores</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="field-input" placeholder="Search product or SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="field-select">
                    <option value="">All Status</option>
                    <option value="enabled" {{ request('status') === 'enabled' ? 'selected' : '' }}>Enabled</option>
                    <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                    <option value="needs_sync" {{ request('status') === 'needs_sync' ? 'selected' : '' }}>Needs Sync</option>
                </select>
            </div>
            <div class="col-md-2 col-6">
                <button type="submit" class="btn-hdr primary w-100" style="height:38px; justify-content:center;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
            <div class="col-md-2 col-6">
                <a href="{{ route('admin.daraz.mappings.index') }}" class="btn-hdr ghost w-100" style="height:38px; justify-content:center;">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-link"></i></span>
                Active Pairing Links
            </h2>
        </div>

        @if($mappings->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-link-slash"></i></div>
                <h4>No Inventory Pairs Established</h4>
                <p>Establish relations between catalog items and seller listing SKUs to activate inventory syncing.</p>
                <a href="{{ route('admin.daraz.mappings.create') }}" class="btn-hdr primary" style="margin: 0 auto; text-decoration: none;">
                    <i class="fas fa-plus-circle"></i> Add Pair Bridge
                </a>
            </div>
        @else
            <form id="bulkForm" action="{{ route('admin.daraz.mappings.bulk-toggle') }}" method="POST">
                @csrf
                <div class="bulk-action-bar">
                    <span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.04em;">Bulk actions:</span>
                    <button type="button" class="bulk-btn bb-green" onclick="bulkAction('enable')">
                        <i class="fas fa-toggle-on"></i> Enable
                    </button>
                    <button type="button" class="bulk-btn bb-orange" onclick="bulkAction('disable')">
                        <i class="fas fa-toggle-off"></i> Disable
                    </button>
                    <button type="button" class="bulk-btn bb-red" onclick="bulkAction('delete')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </div>

                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px; padding-left:18px;"><input type="checkbox" id="selectAll" class="mapping-checkbox"></th>
                                <th class="col-product">Product</th>
                                <th>Store</th>
                                <th>Daraz SKU</th>
                                <th>Stock</th>
                                <th>Last Synced</th>
                                <th>Status</th>
                                <th style="width: 140px; text-align: right; padding-right:18px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mappings as $index => $mapping)
                                <tr class="{{ $index % 2 === 0 ? 'row-item-odd' : 'row-item-even' }}">
                                    <td style="padding-left:18px;">
                                        <input type="checkbox" name="mapping_ids[]" value="{{ $mapping->id }}" class="mapping-checkbox mapping-ids">
                                    </td>
                                    <td class="col-product" title="{{ $mapping->product_title }}">
                                        <span class="text-title">{{ $mapping->product_title }}</span>
                                        @if($mapping->variationCombination)
                                            <span class="text-subtitle">{{ $mapping->variationCombination->name ?? 'Variation' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-pill bp-country">{{ $mapping->store->name }}</span>
                                    </td>
                                    <td>
                                        <code style="font-size:0.78rem; font-family:monospace; color:#0f172a; background:#f1f5f9; padding:4px 8px; border-radius:6px; border:1px solid #e2e8f0;">{{ $mapping->daraz_sku }}</code>
                                        @if($mapping->seller_sku && $mapping->seller_sku !== $mapping->daraz_sku)
                                            <span class="text-subtitle" style="font-size:0.7rem;">Seller: {{ $mapping->seller_sku }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-pill {{ $mapping->getCurrentStock() > 0 ? 'bp-active' : 'bp-inactive' }}">
                                            {{ $mapping->getCurrentStock() }}
                                        </span>
                                        @if($mapping->stock_buffer > 0)
                                            <span class="text-subtitle" style="font-size:0.7rem;">Buffer: -{{ $mapping->stock_buffer }}</span>
                                        @endif
                                        @if($mapping->needsSync())
                                            <span class="badge-pill bp-warning" style="margin-top:4px; font-size:0.65rem; padding: 2px 8px;">Needs Sync</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->last_synced_at)
                                            <span class="text-title">{{ $mapping->last_synced_at->diffForHumans() }}</span>
                                            <span class="text-subtitle">Qty: {{ $mapping->last_synced_quantity ?? 'N/A' }}</span>
                                        @else
                                            <span style="color:#cbd5e1;">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->sync_enabled)
                                            <span class="badge-pill bp-active">Enabled</span>
                                        @else
                                            <span class="badge-pill bp-country">Disabled</span>
                                        @endif
                                        @if($mapping->last_sync_status)
                                            <div style="margin-top:4px;">
                                                <span class="badge-pill {{ $mapping->last_sync_status === 'success' ? 'bp-active' : ($mapping->last_sync_status === 'failed' ? 'bp-inactive' : 'bp-warning') }}" style="font-size:0.65rem; padding: 2px 8px;">
                                                    {{ ucfirst($mapping->last_sync_status) }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding-right:18px;">
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            <button type="button" class="icon-btn ib-sync sync-single" data-mapping-id="{{ $mapping->id }}" title="Sync Now" {{ !$mapping->store->isConnected() ? 'disabled' : '' }}>
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <a href="{{ route('admin.daraz.mappings.edit', $mapping) }}" class="icon-btn ib-edit" title="Edit Link Settings">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('admin.daraz.mappings.destroy', $mapping) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this mapping?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="icon-btn ib-delete" title="Delete Pair">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- Pagination --}}
            <div class="p-3 border-top" style="border-color: #f1f5f9 !important;">
                {{ $mappings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Auto-Map Modal -->
<div class="modal fade" id="autoMapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border:none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding:18px 24px;">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 1rem;"><i class="fas fa-magic text-primary me-1"></i> Auto-Link Inventory Pairs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size:0.8rem;"></button>
            </div>
            <form action="{{ route('admin.daraz.mappings.auto-map') }}" method="POST" style="margin:0;">
                @csrf
                <div class="modal-body p-4">
                    <p style="font-size: 0.825rem; color:#64748b; line-height:1.5; margin-bottom:16px;">This will fetch seller listings from the marketplace and automatically establish bridge links by matching SKUs.</p>

                    <div style="margin-bottom:16px;">
                        <label class="field-label" style="font-size: 0.75rem;">Select Outlet</label>
                        <select name="store_id" class="field-select" required>
                            <option value="">Choose an outlet...</option>
                            @foreach($stores->filter(fn($s) => $s->isConnected()) as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px; display:flex; gap:10px; align-items:flex-start;">
                        <i class="fas fa-info-circle" style="color:#2563eb; margin-top:2px; font-size:0.9rem;"></i>
                        <span style="font-size:0.78rem; color:#1e40af; line-height:1.4;">Products will be matched by comparing Daraz SellerSKU with local product/variation SKU.</span>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding:14px 24px; display:flex; gap:8px;">
                    <button type="button" class="btn-hdr ghost" data-bs-dismiss="modal" style="height:34px; padding: 0 16px;">Cancel</button>
                    <button type="submit" class="btn-hdr primary" style="height:34px; padding: 0 16px;">
                        <i class="fas fa-magic"></i> Start Auto-Linking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.mapping-ids').forEach(cb => cb.checked = this.checked);
    });

    // Auto-Map Modal
    document.getElementById('autoMapBtn')?.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('autoMapModal')).show();
    });

    // Sync Single
    document.querySelectorAll('.sync-single').forEach(btn => {
        btn.addEventListener('click', function() {
            const mappingId = this.dataset.mappingId;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`{{ url('admin/daraz/sync/single') }}/${mappingId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Synced successfully!');
                    location.reload();
                } else {
                    alert('Sync failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });
});

function bulkAction(action) {
    const checked = document.querySelectorAll('.mapping-ids:checked');
    if (checked.length === 0) {
        alert('Please select at least one mapping.');
        return;
    }

    const form = document.getElementById('bulkForm');

    if (action === 'delete') {
        if (!confirm('Delete ' + checked.length + ' mapping(s)?')) return;
        form.action = '{{ route("admin.daraz.mappings.bulk-delete") }}';
        form.method = 'POST';
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
    } else {
        form.action = '{{ route("admin.daraz.mappings.bulk-toggle") }}';
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
    }

    form.submit();
}
</script>
@endpush
