@extends('layouts.master')

@section('title', 'Edit Product Mapping')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-edit"></i> Edit Product Mapping</h4>
            <p class="text-muted mb-0">{{ $mapping->product_title }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Mappings
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mapping Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.mappings.update', $mapping) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Daraz Store</label>
                            <input type="text" class="form-control" value="{{ $mapping->store->name }} ({{ $mapping->store->country_code }})" disabled>
                            <input type="hidden" name="daraz_store_id" value="{{ $mapping->daraz_store_id }}">
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Thikana Product</h6>

                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" value="{{ $mapping->product_title }}" disabled>
                            <input type="hidden" name="product_id" value="{{ $mapping->product_id }}">
                            @if($mapping->variationCombination)
                                <input type="hidden" name="variation_combination_id" value="{{ $mapping->variation_combination_id }}">
                                <small class="text-muted">Variation: {{ $mapping->variationCombination->name ?? 'N/A' }}</small>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Daraz Product</h6>

                        <div class="mb-3">
                            <label for="daraz_item_id" class="form-label">Daraz Item ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('daraz_item_id') is-invalid @enderror"
                                   id="daraz_item_id"
                                   name="daraz_item_id"
                                   value="{{ old('daraz_item_id', $mapping->daraz_item_id) }}"
                                   required>
                            @error('daraz_item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="daraz_sku" class="form-label">Daraz SKU <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('daraz_sku') is-invalid @enderror"
                                   id="daraz_sku"
                                   name="daraz_sku"
                                   value="{{ old('daraz_sku', $mapping->daraz_sku) }}"
                                   required>
                            @error('daraz_sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="seller_sku" class="form-label">Seller SKU</label>
                            <input type="text"
                                   class="form-control @error('seller_sku') is-invalid @enderror"
                                   id="seller_sku"
                                   name="seller_sku"
                                   value="{{ old('seller_sku', $mapping->seller_sku) }}">
                            @error('seller_sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Sync Settings</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="sync_enabled" name="sync_enabled" value="1"
                                               {{ old('sync_enabled', $mapping->sync_enabled) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sync_enabled">Enable Sync</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_buffer" class="form-label">Stock Buffer</label>
                                    <input type="number"
                                           class="form-control"
                                           id="stock_buffer"
                                           name="stock_buffer"
                                           value="{{ old('stock_buffer', $mapping->stock_buffer) }}"
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Mapping
                            </button>
                            <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Current Stock -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-boxes"></i> Current Stock</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="mb-0">{{ $mapping->getCurrentStock() }}</h3>
                            <small class="text-muted">Thikana Stock</small>
                        </div>
                        <div class="col-6">
                            <h3 class="mb-0">{{ $mapping->getEffectiveStock() }}</h3>
                            <small class="text-muted">Effective (w/ buffer)</small>
                        </div>
                    </div>

                    @if($mapping->needsSync())
                        <div class="alert alert-warning mt-3 mb-0 small">
                            <i class="fas fa-exclamation-triangle"></i>
                            Stock has changed since last sync. Last synced: {{ $mapping->last_synced_quantity ?? 'N/A' }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sync Status -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-sync"></i> Sync Status</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Last Synced:</span>
                            <strong>{{ $mapping->last_synced_at?->diffForHumans() ?? 'Never' }}</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Last Status:</span>
                            @if($mapping->last_sync_status)
                                <span class="badge bg-{{ $mapping->last_sync_status === 'success' ? 'success' : ($mapping->last_sync_status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($mapping->last_sync_status) }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Last Synced Qty:</span>
                            <strong>{{ $mapping->last_synced_quantity ?? 'N/A' }}</strong>
                        </li>
                    </ul>

                    @if($mapping->last_error)
                        <div class="alert alert-danger mt-3 mb-0 small">
                            <strong>Last Error:</strong><br>
                            {{ $mapping->last_error }}
                        </div>
                    @endif

                    <div class="mt-3">
                        <button type="button"
                                class="btn btn-success btn-sm w-100 sync-now"
                                data-mapping-id="{{ $mapping->id }}"
                                {{ !$mapping->store->isConnected() ? 'disabled' : '' }}>
                            <i class="fas fa-sync"></i> Sync Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card mt-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.mappings.destroy', $mapping) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this mapping?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete Mapping
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.sync-now')?.addEventListener('click', function() {
        const mappingId = this.dataset.mappingId;
        const originalHtml = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
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
</script>
@endpush
