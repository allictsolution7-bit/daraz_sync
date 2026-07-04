@extends('layouts.master')

@section('title', 'Create Combo Offer')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Combo Offer</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.combo_offers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h5>Please fix the following errors:</h5>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h5>Error:</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.combo_offers.store') }}" method="POST" id="combo-offer-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Base Product *</label>
                                    <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Combo Title *</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="items_count">Number of Items *</label>
                                    <input type="number" name="items_count" id="items_count" class="form-control @error('items_count') is-invalid @enderror" value="{{ old('items_count', 3) }}" min="1" max="10" required>
                                    @error('items_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="combo_price">Combo Price (৳) *</label>
                                    <input type="number" name="combo_price" id="combo_price" class="form-control @error('combo_price') is-invalid @enderror" value="{{ old('combo_price') }}" step="0.01" min="0" required>
                                    @error('combo_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="original_price">Original Price (৳) *</label>
                                    <input type="number" name="original_price" id="original_price" class="form-control @error('original_price') is-invalid @enderror" value="{{ old('original_price') }}" step="0.01" min="0" required>
                                    @error('original_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h4>Combo Items</h4>
                        <div id="combo-items">
                            <div class="combo-item" data-index="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product *</label>
                                            <select name="items[0][product_id]" class="form-control product-select @error('items.0.product_id') is-invalid @enderror" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('items.0.product_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Variation (Optional)</label>
                                            <select name="items[0][variation_combination_id]" class="form-control variation-select @error('items.0.variation_combination_id') is-invalid @enderror">
                                                <option value="">Select Variation</option>
                                            </select>
                                            @error('items.0.variation_combination_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sort Order</label>
                                            <input type="number" name="items[0][sort_order]" class="form-control @error('items.0.sort_order') is-invalid @enderror" value="{{ old('items.0.sort_order', 0) }}" min="0">
                                            @error('items.0.sort_order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="custom-control custom-switch">
                                                <input type="hidden" name="items[0][is_active]" value="0">
                                                <input type="checkbox" class="custom-control-input" id="item_active_0" name="items[0][is_active]" value="1" {{ old('items.0.is_active', true) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="item_active_0"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" id="add-item" class="btn btn-info">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                                <button type="button" id="remove-item" class="btn btn-danger" style="display: none;">
                                    <i class="fas fa-minus"></i> Remove Last Item
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="fas fa-save"></i> Create Combo Offer
                                </button>
                                <a href="{{ route('admin.combo_offers.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    let itemIndex = 1;

    // Simple function to add new item
    function addNewItem() {
        
        // Get the first combo item as template
        const template = document.querySelector('.combo-item');
        if (!template) {
            console.error('Template not found!');
            return;
        }
        
        // Clone the template
        const newItem = template.cloneNode(true);
        
        // Update the data-index
        newItem.setAttribute('data-index', itemIndex);
        
        // Update all input names
        const inputs = newItem.querySelectorAll('input, select');
        inputs.forEach(function(input) {
            if (input.name) {
                input.name = input.name.replace('[0]', '[' + itemIndex + ']');
            }
            // Clear values
            if (input.type !== 'checkbox') {
                input.value = '';
            }
        });
        
        // Handle checkboxes
        const checkboxes = newItem.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
            // Update ID and label
            if (checkbox.id) {
                const newId = checkbox.id.replace('_0', '_' + itemIndex);
                checkbox.id = newId;
                const label = newItem.querySelector('label[for="' + checkbox.id.replace('_0', '_' + itemIndex) + '"]');
                if (label) {
                    label.setAttribute('for', newId);
                }
            }
        });
        
        // Clear variation select
        const variationSelect = newItem.querySelector('.variation-select');
        if (variationSelect) {
            variationSelect.innerHTML = '<option value="">Select Variation</option>';
        }
        
        // Add to container
        const container = document.getElementById('combo-items');
        container.appendChild(newItem);
        
        itemIndex++;
        
        // Show remove button
        const removeBtn = document.getElementById('remove-item');
        if (removeBtn) {
            removeBtn.style.display = 'inline-block';
        }
    }
    
    // Simple function to remove last item
    function removeLastItem() {
        const items = document.querySelectorAll('.combo-item');
        if (items.length > 1) {
            const lastItem = items[items.length - 1];
            lastItem.parentNode.removeChild(lastItem);
            itemIndex--;
            
            // Hide remove button if only one item left
            if (items.length === 2) { // After removing, only 1 will remain
                const removeBtn = document.getElementById('remove-item');
                if (removeBtn) {
                    removeBtn.style.display = 'none';
                }
            }
        }
    }
    
    // Bind events when DOM is ready
    setTimeout(function() {
        
        // Add item button
        const addBtn = document.getElementById('add-item');
        if (addBtn) {
            addBtn.addEventListener('click', function(e) {
                e.preventDefault();
                addNewItem();
            });
        }
        
        // Remove item button
        const removeBtn = document.getElementById('remove-item');
        if (removeBtn) {
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                removeLastItem();
            });
        }
        
        // Product selection change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                
                const variationSelect = e.target.closest('.combo-item').querySelector('.variation-select');
                if (variationSelect) {
                    variationSelect.innerHTML = '<option value="">Select Variation</option>';
                    
                    if (e.target.value) {
                        // Load variations via AJAX
                        fetch('{{ route("admin.combo_offers.get_variations") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: e.target.value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                data.forEach(function(variation) {
                                    const option = document.createElement('option');
                                    option.value = variation.id;
                                    option.textContent = variation.display_name || variation.name || 'Variation ' + variation.id;
                                    variationSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error loading variations:', error);
                        });
                    }
                }
            }
        });
        
    }, 100);
    
});
</script>
@endsection 