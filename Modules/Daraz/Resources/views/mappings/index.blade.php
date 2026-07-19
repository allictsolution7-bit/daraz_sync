@extends('layouts.master')

@section('title', 'Product Mappings')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .daraz-mappings-dashboard {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 18px;
            padding: 6px;
        }
        .premium-card {
            border: 1px solid #f1f5f9 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
            background: #fff;
            overflow: hidden;
        }
        .premium-card .card-body {
            padding: 1.5rem !important;
        }
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 8px 12px !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
            height: 38px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 0.88rem !important;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .table-premium {
            margin-bottom: 0;
        }
        .table-premium th {
            font-size: 0.72rem !important;
            text-transform: uppercase;
            font-weight: 700;
            color: #475569;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px !important;
        }
        .table-premium td {
            font-size: 0.85rem;
            padding: 14px 16px !important;
            vertical-align: middle;
            color: #334155;
        }
        .table-premium tr {
            transition: all 0.2s ease;
        }
        .table-premium tbody tr:hover {
            background-color: #f8fafc;
        }
        .mapping-checkbox {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-mappings-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-link me-1 text-primary"></i> Inventory Pair Bridges</h4>
            <p class="text-muted small mb-0">Map catalog products to seller listing SKUs</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.daraz.mappings.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Link Pair
            </a>
            <button type="button" class="btn btn-sm btn-outline-success" id="autoMapBtn" {{ $stores->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-magic me-1"></i> Auto-Link
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius: 12px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card premium-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.daraz.mappings.index') }}" method="GET" class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <select name="store_id" class="form-select">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search product or SKU..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2 col-sm-6">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="enabled" {{ request('status') === 'enabled' ? 'selected' : '' }}>Enabled</option>
                        <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        <option value="needs_sync" {{ request('status') === 'needs_sync' ? 'selected' : '' }}>Needs Sync</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2 col-sm-3">
                    <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Mappings Table -->
    <div class="card premium-card mb-4">
        <div class="card-body p-0">
            @if($mappings->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-link-slash fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Inventory Pairs Established</h5>
                    <p class="text-muted small">Establish relations between catalog items and seller listing SKUs to activate inventory syncing.</p>
                    <a href="{{ route('admin.daraz.mappings.create') }}" class="btn btn-primary btn-sm mt-2" style="border-radius: 8px;">
                        <i class="fas fa-plus-circle me-1"></i> Add Pair Bridge
                    </a>
                </div>
            @else
                <form id="bulkForm" action="{{ route('admin.daraz.mappings.bulk-toggle') }}" method="POST">
                    @csrf
                    <div class="p-3 border-bottom d-flex align-items-center bg-light" style="border-color: #f1f5f9 !important;">
                        <div class="text-muted small fw-bold text-uppercase me-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">Bulk Actions:</div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkAction('enable')" style="height: 32px;">
                                <i class="fas fa-toggle-on me-1"></i> Enable
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark" onclick="bulkAction('disable')" style="height: 32px;">
                                <i class="fas fa-toggle-off me-1"></i> Disable
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkAction('delete')" style="height: 32px;">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-premium">
                            <thead>
                                <tr>
                                    <th width="40"><input type="checkbox" id="selectAll" class="mapping-checkbox"></th>
                                    <th>Product</th>
                                    <th>Store</th>
                                    <th>Daraz SKU</th>
                                    <th>Stock</th>
                                    <th>Last Synced</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mappings as $mapping)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="mapping_ids[]" value="{{ $mapping->id }}" class="mapping-checkbox mapping-ids">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $mapping->product_title }}</div>
                                        @if($mapping->variationCombination)
                                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">{{ $mapping->variationCombination->name ?? 'Variation' }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border" style="font-size: 0.75rem; font-weight: 500;">{{ $mapping->store->name }}</span>
                                    </td>
                                    <td>
                                        <code class="text-dark bg-light px-2 py-1 rounded" style="font-size: 0.8rem; font-family: monospace;">{{ $mapping->daraz_sku }}</code>
                                        @if($mapping->seller_sku && $mapping->seller_sku !== $mapping->daraz_sku)
                                            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Seller: {{ $mapping->seller_sku }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mapping->getCurrentStock() > 0 ? 'success' : 'danger' }}-subtle text-{{ $mapping->getCurrentStock() > 0 ? 'success' : 'danger' }} border border-{{ $mapping->getCurrentStock() > 0 ? 'success' : 'danger' }}-subtle" style="font-size: 0.75rem; font-weight: 600;">
                                            {{ $mapping->getCurrentStock() }}
                                        </span>
                                        @if($mapping->stock_buffer > 0)
                                            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Buffer: -{{ $mapping->stock_buffer }}</div>
                                        @endif
                                        @if($mapping->needsSync())
                                            <div class="mt-1"><span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.7rem; font-weight: 500;">Needs Sync</span></div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->last_synced_at)
                                            <div class="text-dark">{{ $mapping->last_synced_at->diffForHumans() }}</div>
                                            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Qty: {{ $mapping->last_synced_quantity ?? 'N/A' }}</div>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->sync_enabled)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem; font-weight: 500;">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.75rem; font-weight: 500;">Disabled</span>
                                        @endif
                                        @if($mapping->last_sync_status)
                                            <div class="mt-1">
                                                <span class="badge bg-{{ $mapping->last_sync_status === 'success' ? 'success' : ($mapping->last_sync_status === 'failed' ? 'danger' : 'warning') }}-subtle text-{{ $mapping->last_sync_status === 'success' ? 'success' : ($mapping->last_sync_status === 'failed' ? 'danger' : 'warning') }} border border-{{ $mapping->last_sync_status === 'success' ? 'success' : ($mapping->last_sync_status === 'failed' ? 'danger' : 'warning') }}-subtle" style="font-size: 0.7rem; font-weight: 500;">
                                                    {{ ucfirst($mapping->last_sync_status) }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success sync-single"
                                                    style="width: 32px; height: 32px; border-radius: 6px !important; padding: 0 !important;"
                                                    data-mapping-id="{{ $mapping->id }}"
                                                    title="Sync Now"
                                                    {{ !$mapping->store->isConnected() ? 'disabled' : '' }}>
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <a href="{{ route('admin.daraz.mappings.edit', $mapping) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               style="width: 32px; height: 32px; border-radius: 6px !important; padding: 0 !important; display: inline-flex; align-items: center; justify-content: center;"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.daraz.mappings.destroy', $mapping) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Delete this mapping?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        style="width: 32px; height: 32px; border-radius: 6px !important; padding: 0 !important;"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
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

                <!-- Pagination -->
                <div class="p-3 border-top" style="border-color: #f1f5f9 !important;">
                    {{ $mappings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Auto-Map Modal -->
<div class="modal fade" id="autoMapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 1rem;"><i class="fas fa-magic text-primary me-1"></i> Auto-Link Inventory Pairs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.daraz.mappings.auto-map') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small">This will fetch seller listings from the marketplace and automatically establish bridge links by matching SKUs.</p>

                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.75rem;">Select Outlet</label>
                        <select name="store_id" class="form-select" required>
                            <option value="">Choose an outlet...</option>
                            @foreach($stores->filter(fn($s) => $s->isConnected()) as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info small d-flex align-items-start gap-2" style="border-radius: 8px;">
                        <i class="fas fa-info-circle mt-1"></i>
                        <span>Products will be matched by comparing Daraz SellerSKU with Thikana product/variation SKU.</span>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-magic me-1"></i> Start Auto-Linking
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
        document.querySelectorAll('.mapping-checkbox').forEach(cb => cb.checked = this.checked);
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
