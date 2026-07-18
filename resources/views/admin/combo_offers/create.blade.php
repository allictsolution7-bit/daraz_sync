@extends('layouts.master')

@section('title', 'Create Combo Offer')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        /* Main Container Styling */
        .categories-container {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 24px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
            margin-top: 1rem;
        }

        /* Sleek Glassmorphic Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 1.5rem 1.75rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
        }

        /* Custom Breadcrumb Styles */
        .custom-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0.75rem;
        }
        .custom-breadcrumb .breadcrumb-item {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .custom-breadcrumb .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .custom-breadcrumb .breadcrumb-item a:hover {
            color: #4f46e5;
        }
        .custom-breadcrumb .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }

        /* Typography */
        .page-header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.15rem;
        }
        .page-header-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        /* Form Card Layouts */
        .form-section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }
        .form-section-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-section-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            margin-bottom: 1.25rem;
        }

        /* Modern Form Controls */
        .form-label {
            font-weight: 500;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            font-size: 0.925rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #1e293b;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background-color: #ffffff;
        }

        /* Custom Toggle Switch */
        .switch-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 0.5rem;
        }

        /* Feedback Alerts */
        .text-error-small {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.25rem;
            color: #ef4444;
        }

        /* Action Buttons */
        .btn-submit-premium {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 0.7rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: #ffffff;
        }

        .btn-back-premium {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-back-premium:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .btn-action-outline {
            background: #ffffff;
            color: #4f46e5;
            border: 1px dashed #4f46e5;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-action-outline:hover {
            background: rgba(79, 70, 229, 0.05);
            color: #4338ca;
            transform: translateY(-1px);
        }

        .btn-action-danger-outline {
            background: #ffffff;
            color: #ef4444;
            border: 1px dashed #ef4444;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-action-danger-outline:hover {
            background: rgba(239, 68, 68, 0.05);
            color: #dc2626;
            transform: translateY(-1px);
        }

        /* Combo Item Container card block */
        .combo-item {
            border: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s ease;
        }
        .combo-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.combo_offers.index') }}">Combo Offers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Combo Offer</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="page-header-title">Create Combo Offer</h1>
                <p class="page-header-subtitle">Bundle multiple store items together into high-converting special offers.</p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <div class="fw-semibold mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please resolve the following errors:</div>
                    <ul class="mb-0" style="padding-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.combo_offers.store') }}" method="POST" id="combo-offer-form">
                @csrf
                <div class="row">
                    <!-- Left Column: Combo Details -->
                    <div class="col-lg-7">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-gift text-indigo-500"></i> Offer Configurations
                            </h3>
                            <p class="form-section-subtitle">Specify base listing product, title tag, description, and pricing details.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="product_id" class="form-label">Base Product <span class="text-danger">*</span></label>
                                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Combo Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title') }}" placeholder="e.g. Premium Solid Cotton T-Shirt Combo 3pcs" required>
                                    @error('title')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                    placeholder="Write details or descriptions about this combo package..."></textarea>
                                @error('description')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="items_count" class="form-label">Number of Items <span class="text-danger">*</span></label>
                                    <input type="number" name="items_count" id="items_count" class="form-control @error('items_count') is-invalid @enderror" 
                                        value="{{ old('items_count', 3) }}" min="1" max="10" required>
                                    @error('items_count')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="combo_price" class="form-label">Combo Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" name="combo_price" id="combo_price" class="form-control @error('combo_price') is-invalid @enderror" 
                                        value="{{ old('combo_price') }}" step="0.01" min="0" placeholder="e.g. 750.00" required>
                                    @error('combo_price')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="original_price" class="form-label">Original Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" name="original_price" id="original_price" class="form-control @error('original_price') is-invalid @enderror" 
                                        value="{{ old('original_price') }}" step="0.01" min="0" placeholder="e.g. 1000.00" required>
                                    @error('original_price')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                                        value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-block">Status</label>
                                    <div class="switch-wrapper form-check form-switch pt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted" for="is_active" style="font-size: 0.9rem;">Mark combo as Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Combo Items -->
                    <div class="col-lg-5">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-list-check text-indigo-500"></i> Combo Items
                            </h3>
                            <p class="form-section-subtitle">Add products and variations included within this combo package.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div id="combo-items">
                                <div class="combo-item" data-index="0">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <label class="form-label">Product <span class="text-danger">*</span></label>
                                            <select name="items[0][product_id]" class="form-select product-select @error('items.0.product_id') is-invalid @enderror" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ old('items.0.product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('items.0.product_id')
                                                <div class="text-error-small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 mb-2">
                                            <label class="form-label">Variation (Optional)</label>
                                            <select name="items[0][variation_combination_id]" class="form-select variation-select @error('items.0.variation_combination_id') is-invalid @enderror">
                                                <option value="">Select Variation</option>
                                            </select>
                                            @error('items.0.variation_combination_id')
                                                <div class="text-error-small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-7">
                                            <label class="form-label">Sort Order</label>
                                            <input type="number" name="items[0][sort_order]" class="form-control @error('items.0.sort_order') is-invalid @enderror" 
                                                value="{{ old('items.0.sort_order', 0) }}" min="0">
                                            @error('items.0.sort_order')
                                                <div class="text-error-small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-5 pt-4 text-end">
                                            <div class="form-check form-switch d-inline-block text-start">
                                                <input type="hidden" name="items[0][is_active]" value="0">
                                                <input type="checkbox" class="form-check-input" id="item_active_0" name="items[0][is_active]" value="1" {{ old('items.0.is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label text-muted" for="item_active_0" style="font-size: 0.8rem;">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Row Operations -->
                            <div class="d-flex gap-2 mt-3">
                                <button type="button" id="add-item" class="btn-action-outline">
                                    <i class="fa-solid fa-plus"></i> Add Item
                                </button>
                                <button type="button" id="remove-item" class="btn-action-danger-outline" style="display: none;">
                                    <i class="fa-solid fa-minus"></i> Remove Last Item
                                </button>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                            <a href="{{ route('admin.combo_offers.index') }}" class="btn-back-premium">
                                <i class="fa-solid fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-premium" id="submit-btn">
                                <i class="fa-solid fa-floppy-disk"></i> Create Combo
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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