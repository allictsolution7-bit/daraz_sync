@extends('layouts.master')

@section('title', 'Establish Inventory Pair Bridge')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .daraz-create-dashboard {
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
        .premium-card .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem 1.5rem !important;
        }
        .premium-card .card-header h5, .premium-card .card-header h6 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }
        .premium-card .card-body {
            padding: 1.5rem !important;
        }
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 10px 14px !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .form-label {
            font-weight: 700;
            color: #475569;
            font-size: 0.8rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 0.88rem !important;
        }
        .setup-step-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .setup-step-num {
            width: 24px;
            height: 24px;
            background: #eff6ff;
            color: #3b82f6;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.72rem;
            margin-right: 12px;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }
        .setup-step-text {
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.5;
        }
        .search-result-item {
            padding: 12px 16px;
            border: 1px solid #f1f5f9;
            border-bottom: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .search-result-item:hover {
            background: #f8fafc;
        }
        .search-result-item:first-child {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .search-result-item:last-child {
            border-bottom: 1px solid #f1f5f9;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-create-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-plus-circle me-1 text-primary"></i> Establish Inventory Pair Bridge</h4>
            <p class="text-muted small mb-0">Bridge a catalog product to a marketplace listing</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-sm btn-outline-secondary" style="padding: 6px 14px !important;">
                <i class="fas fa-arrow-left me-1"></i> Back to Bridges
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card premium-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Bridge Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.mappings.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="daraz_store_id" class="form-label">Marketplace Outlet <span class="text-danger">*</span></label>
                            <select class="form-select @error('daraz_store_id') is-invalid @enderror"
                                    id="daraz_store_id"
                                    name="daraz_store_id"
                                    required>
                                <option value="">Select Outlet</option>
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

                        <hr class="my-4" style="border-color: #e2e8f0;">
                        <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">Catalog Product</h6>

                        <div class="mb-4">
                            <label for="product_search" class="form-label">Search Product <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="product_search"
                                   placeholder="Type to search products...">
                            <input type="hidden" name="product_id" id="product_id" value="{{ old('product_id') }}">
                            <div id="product_search_results" class="mt-2" style="display:none; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);"></div>
                            @error('product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="selected_product" class="alert alert-info d-flex justify-content-between align-items-center mb-4" style="display:none; border-radius: 8px; background: #eff6ff; border-color: #bfdbfe; color: #1e3a8a;">
                            <div><strong>Selected:</strong> <span id="selected_product_name"></span></div>
                            <button type="button" class="btn-close" onclick="clearProduct()" style="font-size: 0.75rem;"></button>
                        </div>

                        <div class="mb-4" id="variation_container" style="display:none;">
                            <label for="variation_combination_id" class="form-label">Product Variation</label>
                            <select class="form-select"
                                    id="variation_combination_id"
                                    name="variation_combination_id">
                                <option value="">Main Product (No Variation)</option>
                            </select>
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Select a specific variation if this is a variable product</div>
                        </div>

                        <hr class="my-4" style="border-color: #e2e8f0;">
                        <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">Marketplace Listing</h6>

                        <div class="mb-4">
                            <label for="daraz_item_id" class="form-label">Marketplace Item ID <span class="text-danger">*</span></label>
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
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">The numeric ID of the product on the marketplace</div>
                        </div>

                        <div class="mb-4">
                            <label for="daraz_sku" class="form-label">Marketplace SKU <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('daraz_sku') is-invalid @enderror"
                                   id="daraz_sku"
                                   name="daraz_sku"
                                   value="{{ old('daraz_sku') }}"
                                   placeholder="Marketplace ShopSKU"
                                   required>
                            @error('daraz_sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="seller_sku" class="form-label">Seller SKU (Reference)</label>
                            <input type="text"
                                   class="form-control @error('seller_sku') is-invalid @enderror"
                                   id="seller_sku"
                                   name="seller_sku"
                                   value="{{ old('seller_sku') }}"
                                   placeholder="Your internal SKU (optional)">
                            @error('seller_sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Used for auto-mapping. Leave blank to use Marketplace SKU.</div>
                        </div>

                        <hr class="my-4" style="border-color: #e2e8f0;">
                        <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">Sync Options</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="sync_enabled" name="sync_enabled" value="1"
                                               {{ old('sync_enabled', true) ? 'checked' : '' }}
                                               style="cursor: pointer;">
                                        <label class="form-check-label fw-bold text-dark" for="sync_enabled" style="font-size: 0.85rem; cursor: pointer;">Enable Background Sync</label>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">When enabled, stock changes will sync automatically</div>
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
                                    <div class="text-muted small mt-1" style="font-size: 0.75rem;">Reserve stock (effective stock = actual - buffer)</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4 pt-3 border-top" style="border-color: #e2e8f0 !important;">
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="background: #3b82f6; border-color: #3b82f6;">
                                <i class="fas fa-save"></i> Add Bridge Link
                            </button>
                            <a href="{{ route('admin.daraz.mappings.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card premium-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-1"></i> About Bridges</h5>
                </div>
                <div class="card-body">
                    <p class="text-secondary small mb-3">Product bridge links pair your local catalog inventory to marketplace listings. When stock level fluctuates on either platform, the updates sync automatically.</p>

                    <strong class="text-dark small d-block mb-2">Locating Marketplace Item ID & SKU:</strong>
                    <div class="setup-step-row">
                        <div class="setup-step-num">1</div>
                        <div class="setup-step-text">Log in to the seller account dashboard.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">2</div>
                        <div class="setup-step-text">Navigate to active listings.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">3</div>
                        <div class="setup-step-text">Find your item and copy the Item ID code.</div>
                    </div>
                    <div class="setup-step-row mb-0">
                        <div class="setup-step-num">4</div>
                        <div class="setup-step-text">Inspect options to find the shop-specific SKU.</div>
                    </div>
                </div>
            </div>

            <div class="card premium-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb text-primary me-1"></i> Best Practices</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small text-secondary">
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Maintain uniform SKUs across both platforms to speed up automatic pairing tools.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Establish unique linkages for each independent item variation.</span>
                        </li>
                        <li class="mb-0 d-flex align-items-start gap-2">
                            <i class="fas fa-check-circle text-success mt-1"></i>
                            <span>Test the sync cycle with a single inventory item before launching batch updates.</span>
                        </li>
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
                            <div class="search-result-item bg-white"
                                 onclick="selectProduct(${p.id}, '${p.name.replace(/'/g, "\\'")}', ${JSON.stringify(p.variations || []).replace(/"/g, '&quot;')})">
                                <div class="fw-bold text-dark" style="font-size: 0.85rem;">${p.name}</div>
                                ${p.sku ? '<div class="text-muted small mt-1" style="font-size: 0.72rem;">SKU: ' + p.sku + '</div>' : ''}
                                <span class="badge bg-secondary float-end" style="font-size: 0.7rem;">Stock: ${p.quantity}</span>
                                <div class="clearfix"></div>
                            </div>
                        `).join('');
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="search-result-item text-muted small bg-white">No products found</div>';
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
