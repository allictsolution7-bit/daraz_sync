@extends('layouts.master')

@section('title', 'Create Product Mapping')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-plus-circle"></i> Create Product Mapping</h4>
            <p class="text-muted mb-0">Map a Thikana product to a Daraz SKU</p>
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
                    <form action="{{ route('admin.daraz.mappings.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="daraz_store_id" class="form-label">Daraz Store <span class="text-danger">*</span></label>
                            <select class="form-select @error('daraz_store_id') is-invalid @enderror"
                                    id="daraz_store_id"
                                    name="daraz_store_id"
                                    required>
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}"
                                            {{ old('daraz_store_id') == $store->id ? 'selected' : '' }}
                                            {{ !$store->isConnected() ? 'disabled' : '' }}>
                                        {{ $store->name }} ({{ $store->country_code }})
                                        {{ !$store->isConnected() ? '- Not Connected' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('daraz_store_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Thikana Product</h6>

                        <div class="mb-3">
                            <label for="product_search" class="form-label">Search Product <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="product_search"
                                   placeholder="Type to search products...">
                            <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">
                            <div id="product_search_results" class="list-group mt-2" style="display:none;"></div>
                            @error('product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="selected_product" class="alert alert-info" style="display:none;">
                            <strong>Selected:</strong> <span id="selected_product_name"></span>
                            <button type="button" class="btn-close float-end" onclick="clearProduct()"></button>
                        </div>

                        <div class="mb-3" id="variation_container" style="display:none;">
                            <label for="variation_combination_id" class="form-label">Product Variation</label>
                            <select class="form-select"
                                    id="variation_combination_id"
                                    name="variation_combination_id">
                                <option value="">Main Product (No Variation)</option>
                            </select>
                            <small class="text-muted">Select a specific variation if this is a variable product</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Daraz Product</h6>

                        <div class="mb-3">
                            <label for="daraz_item_id" class="form-label">Daraz Item ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('daraz_item_id') is-invalid @enderror"
                                   id="daraz_item_id"
                                   name="daraz_item_id"
                                   value="{{ old('daraz_item_id') }}"
                                   placeholder="e.g., 123456789"
                                   required>
                            @error('daraz_item_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">The numeric ID of the product on Daraz</small>
                        </div>

                        <div class="mb-3">
                            <label for="daraz_sku" class="form-label">Daraz SKU <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('daraz_sku') is-invalid @enderror"
                                   id="daraz_sku"
                                   name="daraz_sku"
                                   value="{{ old('daraz_sku') }}"
                                   placeholder="Daraz ShopSKU"
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
                                   value="{{ old('seller_sku') }}"
                                   placeholder="Your internal SKU (optional)">
                            @error('seller_sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Used for auto-mapping. Leave blank to use Daraz SKU.</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Sync Settings</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="sync_enabled" name="sync_enabled" value="1"
                                               {{ old('sync_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sync_enabled">Enable Sync</label>
                                    </div>
                                    <small class="text-muted">When enabled, stock changes will sync automatically</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_buffer" class="form-label">Stock Buffer</label>
                                    <input type="number"
                                           class="form-control"
                                           id="stock_buffer"
                                           name="stock_buffer"
                                           value="{{ old('stock_buffer', 0) }}"
                                           min="0">
                                    <small class="text-muted">Reserve stock (effective stock = actual - buffer)</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Mapping
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
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> About Mappings</h6>
                </div>
                <div class="card-body small">
                    <p>Product mappings link your Thikana inventory to Daraz products. When stock changes on either side, it will be synchronized.</p>

                    <strong>Finding Daraz Item ID & SKU:</strong>
                    <ol class="mb-3">
                        <li>Log in to Daraz Seller Center</li>
                        <li>Go to Products > Manage Products</li>
                        <li>Find your product and note the Item ID</li>
                        <li>Click edit to find the SKU details</li>
                    </ol>

                    <strong>Stock Buffer:</strong>
                    <p class="mb-0">Use this to reserve stock for other channels. If you have 10 items and set buffer to 2, Daraz will show 8 available.</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h6>
                </div>
                <div class="card-body small">
                    <ul class="mb-0">
                        <li class="mb-2">Use matching SKUs in Thikana and Daraz for easy auto-mapping</li>
                        <li class="mb-2">Create separate mappings for each product variation</li>
                        <li>Test sync with one product before mapping all products</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let searchTimeout;

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product_search');
    const resultsContainer = document.getElementById('product_search_results');

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.daraz.mappings.search-products') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.products && data.products.length > 0) {
                        resultsContainer.innerHTML = data.products.map(p => `
                            <a href="#" class="list-group-item list-group-item-action"
                               onclick="selectProduct(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${JSON.stringify(p.variations || []).replace(/"/g, '&quot;')})">
                                <strong>${p.name}</strong>
                                ${p.sku ? '<br><small class="text-muted">SKU: ' + p.sku + '</small>' : ''}
                                <span class="badge bg-secondary float-end">Stock: ${p.quantity}</span>
                            </a>
                        `).join('');
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="list-group-item text-muted">No products found</div>';
                        resultsContainer.style.display = 'block';
                    }
                });
        }, 300);
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
});

function selectProduct(id, name, variations) {
    document.getElementById('product_id').value = id;
    document.getElementById('product_search').value = '';
    document.getElementById('product_search_results').style.display = 'none';
    document.getElementById('selected_product_name').textContent = name;
    document.getElementById('selected_product').style.display = 'block';

    // Handle variations
    const variationContainer = document.getElementById('variation_container');
    const variationSelect = document.getElementById('variation_combination_id');

    if (variations && variations.length > 0) {
        variationSelect.innerHTML = '<option value="">Main Product (No Variation)</option>' +
            variations.map(v => `<option value="${v.id}">${v.name} (Stock: ${v.stock_quantity})</option>`).join('');
        variationContainer.style.display = 'block';
    } else {
        variationSelect.innerHTML = '<option value="">Main Product (No Variation)</option>';
        variationContainer.style.display = 'none';
    }
}

function clearProduct() {
    document.getElementById('product_id').value = '';
    document.getElementById('selected_product').style.display = 'none';
    document.getElementById('variation_container').style.display = 'none';
}
</script>
@endpush
