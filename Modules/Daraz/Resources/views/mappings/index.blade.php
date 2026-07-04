@extends('layouts.master')

@section('title', 'Product Mappings')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-link"></i> Product Mappings</h4>
            <p class="text-muted mb-0">Map Thikana products to Daraz SKUs</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.mappings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add Mapping
            </a>
            <button type="button" class="btn btn-outline-success" id="autoMapBtn" {{ $stores->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-magic"></i> Auto-Map by SKU
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.daraz.mappings.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="store_id" class="form-select">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search product or SKU..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="enabled" {{ request('status') === 'enabled' ? 'selected' : '' }}>Enabled</option>
                        <option value="disabled" {{ request('status') === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        <option value="needs_sync" {{ request('status') === 'needs_sync' ? 'selected' : '' }}>Needs Sync</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Mappings Table -->
    <div class="card">
        <div class="card-body">
            @if($mappings->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-link-slash fs-1 text-muted"></i>
                    <h4 class="mt-3">No Product Mappings</h4>
                    <p class="text-muted">Map your Thikana products to Daraz SKUs to start syncing inventory.</p>
                    <a href="{{ route('admin.daraz.mappings.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus-circle"></i> Create Mapping
                    </a>
                </div>
            @else
                <form id="bulkForm" action="{{ route('admin.daraz.mappings.bulk-toggle') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-success" onclick="bulkAction('enable')">
                                <i class="fas fa-toggle-on"></i> Enable Selected
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="bulkAction('disable')">
                                <i class="fas fa-toggle-off"></i> Disable Selected
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="bulkAction('delete')">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
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
                                        <input type="checkbox" name="mapping_ids[]" value="{{ $mapping->id }}" class="mapping-checkbox">
                                    </td>
                                    <td>
                                        <strong>{{ $mapping->product_title }}</strong>
                                        @if($mapping->variationCombination)
                                            <br><small class="text-muted">{{ $mapping->variationCombination->name ?? 'Variation' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $mapping->store->name }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $mapping->daraz_sku }}</code>
                                        @if($mapping->seller_sku && $mapping->seller_sku !== $mapping->daraz_sku)
                                            <br><small class="text-muted">Seller: {{ $mapping->seller_sku }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $mapping->getCurrentStock() > 0 ? 'success' : 'danger' }}">
                                            {{ $mapping->getCurrentStock() }}
                                        </span>
                                        @if($mapping->stock_buffer > 0)
                                            <br><small class="text-muted">Buffer: -{{ $mapping->stock_buffer }}</small>
                                        @endif
                                        @if($mapping->needsSync())
                                            <br><span class="badge bg-warning text-dark">Needs Sync</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->last_synced_at)
                                            {{ $mapping->last_synced_at->diffForHumans() }}
                                            <br><small class="text-muted">Qty: {{ $mapping->last_synced_quantity ?? 'N/A' }}</small>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($mapping->sync_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                        @if($mapping->last_sync_status)
                                            <br><span class="badge bg-{{ $mapping->last_sync_status === 'success' ? 'success' : ($mapping->last_sync_status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($mapping->last_sync_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success sync-single"
                                                    data-mapping-id="{{ $mapping->id }}"
                                                    title="Sync Now"
                                                    {{ !$mapping->store->isConnected() ? 'disabled' : '' }}>
                                                <i class="fas fa-sync"></i>
                                            </button>
                                            <a href="{{ route('admin.daraz.mappings.edit', $mapping) }}"
                                               class="btn btn-sm btn-outline-primary"
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
                <div class="mt-3">
                    {{ $mappings->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Auto-Map Modal -->
<div class="modal fade" id="autoMapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-magic"></i> Auto-Map Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.daraz.mappings.auto-map') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">This will fetch products from Daraz and automatically create mappings by matching SKUs.</p>

                    <div class="mb-3">
                        <label class="form-label">Select Store</label>
                        <select name="store_id" class="form-select" required>
                            <option value="">Choose a store...</option>
                            @foreach($stores->filter(fn($s) => $s->isConnected()) as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i>
                        Products will be matched by comparing Daraz SellerSKU with Thikana product/variation SKU.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-magic"></i> Start Auto-Map
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
    const checked = document.querySelectorAll('.mapping-checkbox:checked');
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
