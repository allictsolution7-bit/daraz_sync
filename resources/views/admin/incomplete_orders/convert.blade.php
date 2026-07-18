@extends('layouts.master')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #197A94;
            --primary-hover: #136377;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-bg: #fdfdfd;
            --card-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: #f4f7f6;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #e3e8ec;
            border-radius: var(--border-radius);
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .form-section:hover {
            box-shadow: 0 6px 24px 0 rgba(0, 0, 0, 0.08);
        }

        .form-section h5 {
            color: var(--dark-color);
            margin-bottom: 24px;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 2px solid #e3e8ec;
            padding-bottom: 12px;
            position: relative;
        }

        .form-section h5::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background-color: var(--primary-color);
        }

        .product-row {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
            position: relative;
            transition: var(--transition);
        }

        .product-row:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }

        .product-row .product-info {
            background: #ffffff;
            padding: 14px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            margin-bottom: 14px;
        }

        .product-row .product-info h6 {
            margin: 0 0 6px 0;
            color: var(--dark-color);
            font-weight: 700;
            font-size: 0.95rem;
        }

        .product-row .product-info p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }

        .btn-group-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 32px;
            padding: 24px;
            background: #ffffff;
            border: 1px solid #e3e8ec;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .btn-group-actions .btn {
            min-width: 160px;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-group-actions .btn:hover {
            transform: translateY(-1px);
        }

        .variation-combination {
            font-size: 14px;
        }

        .stock-info {
            font-size: 13px;
            color: #64748b;
            background-color: #f1f5f9;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .form-control {
            height: 42px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
            outline: none;
        }

        .alert {
            border-radius: 8px;
            padding: 16px;
            font-weight: 500;
            margin-bottom: 24px;
            border: none;
        }

        .back-link {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            margin-bottom: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .back-link:hover {
            color: #ffffff;
            text-decoration: none;
            transform: translateX(-3px);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 32px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }

        .page-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            margin: 12px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            height: 42px;
            outline: none;
            transition: var(--transition);
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: var(--primary-color);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 14px;
            color: #334155;
            font-size: 0.95rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }

        .product-search-container {
            position: relative;
        }

        .product-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            max-height: 240px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .product-search-item {
            padding: 10px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
            transition: var(--transition);
        }

        .product-search-item:hover {
            background: #f8fafc;
            color: var(--primary-color);
        }

        .product-search-item:last-child {
            border-bottom: none;
        }

        .variation-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            margin: 2px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        .combo-badge {
            background: var(--success-color);
            color: white;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 6px;
            text-transform: uppercase;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <a href="{{ route('admin.incomplete-orders.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Incomplete Orders
            </a>
            <h2><i class="fas fa-exchange-alt"></i> Convert Incomplete Order to Real Order</h2>
            <p>Order ID: #{{ $incompleteOrder->id }} | Customer: {{ $incompleteOrder->name ?? 'N/A' }} | Phone: {{ $incompleteOrder->phone ?? 'N/A' }}</p>
        </div>

        <form id="convertOrderForm">
            @csrf
            <input type="hidden" name="incomplete_order_id" value="{{ $incompleteOrder->id }}">
            
            <!-- Customer Information -->
            <div class="form-section">
                <h5><i class="fas fa-user"></i> Customer Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_name">Customer Name *</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $incompleteOrder->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Phone Number *</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ $incompleteOrder->phone ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_email">Email Address</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" value="{{ $incompleteOrder->email ?? '' }}" placeholder="Optional">
                        </div>
                        <div class="form-group">
                            <label for="customer_address">Address *</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required>{{ $incompleteOrder->address ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_upazila">Upazila *</label>
                            <input type="text" class="form-control" id="customer_upazila" name="customer_upazila" value="{{ $incompleteOrder->upazila ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_city">City *</label>
                            <input type="text" class="form-control" id="customer_city" name="customer_city" value="{{ $incompleteOrder->city ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_message">Message</label>
                            <textarea class="form-control" id="customer_message" name="customer_message" rows="3">{{ $incompleteOrder->message ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="form-section">
                <h5><i class="fas fa-shopping-cart"></i> Order Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_source">Order Source</label>
                            <select class="form-control" id="order_source" name="order_source">
                                <option value="website" {{ ($incompleteOrder->source ?? '') == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="facebook" {{ ($incompleteOrder->source ?? '') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                <option value="instagram" {{ ($incompleteOrder->source ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                <option value="phone" {{ ($incompleteOrder->source ?? '') == 'phone' ? 'selected' : '' }}>Phone</option>
                                <option value="other" {{ ($incompleteOrder->source ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method *</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cod" {{ ($incompleteOrder->payment_method ?? '') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                <option value="bkash" {{ ($incompleteOrder->payment_method ?? '') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                <option value="nagad" {{ ($incompleteOrder->payment_method ?? '') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                <option value="rocket" {{ ($incompleteOrder->payment_method ?? '') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                <option value="bank_transfer" {{ ($incompleteOrder->payment_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shipping_method">Shipping Method</label>
                            <select class="form-control" id="shipping_method" name="shipping_method">
                                <option value="home_delivery" {{ ($incompleteOrder->shipping_method ?? '') == 'home_delivery' ? 'selected' : '' }}>Home Delivery</option>
                                <option value="office_pickup" {{ ($incompleteOrder->shipping_method ?? '') == 'office_pickup' ? 'selected' : '' }}>Office Pickup</option>
                                <option value="courier" {{ ($incompleteOrder->shipping_method ?? '') == 'courier' ? 'selected' : '' }}>Courier</option>
                            </select>
                        </div>
                                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subtotal">Subtotal</label>
                            <input type="number" class="form-control" id="subtotal" name="subtotal" step="0.01" readonly>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" class="form-control" id="discount" name="discount" step="0.01" value="0">
                        </div>
                        <div class="form-group">
                            <label for="shipping_cost">Shipping Cost</label>
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost" step="0.01" value="{{ $incompleteOrder->shipping_cost ?? 0 }}">
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Total Amount *</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" value="{{ $incompleteOrder->total ?? 0 }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="form-section">
                <h5><i class="fas fa-box"></i> Products</h5>
                <div id="products_container">
                    <!-- Products will be dynamically loaded here -->
                </div>
                <button type="button" class="btn btn-outline-primary" id="add_product_btn">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="btn-group-actions">
                <button type="button" class="btn btn-secondary" onclick="window.close()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                @can('incomplete_orders.convert_page')
                <button type="button" class="btn btn-info" id="save_as_customer_btn">
                    <i class="fas fa-user-plus"></i> Save as Potential Customer
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Create Order
                </button>
                @endcan
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load products from incomplete order
            loadProducts(@json($incompleteOrder->product_details));
            calculateTotals();

            // Add product row with enhanced functionality
            function addProductRow(product = {}, index = null) {
                const container = $('#products_container');
                const productIndex = index !== null ? index : container.children().length;
                
                const productHtml = `
                    <div class="product-row" data-index="${productIndex}">
                        <div class="product-info">
                            <h6>Product ${productIndex + 1}</h6>
                            <p>Select product and configure details</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search Product</label>
                                    <div class="product-search-container">
                                        <input type="text" class="form-control product-search" placeholder="Search products by name or ID...">
                                        <div class="product-search-results"></div>
                                    </div>
                                    <input type="hidden" class="product-id" name="products[${productIndex}][product_id]" value="${product.product_id || ''}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product Name *</label>
                                    <input type="text" class="form-control product-name" name="products[${productIndex}][name]" value="${product.name || ''}" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Variation Combination</label>
                                    <select class="form-control variation-combination" name="products[${productIndex}][combination_id]">
                                        <option value="">Select Variation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Price *</label>
                                    <input type="number" class="form-control product-price" name="products[${productIndex}][price]" value="${product.price || 0}" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Qty *</label>
                                    <input type="number" class="form-control product-quantity" name="products[${productIndex}][quantity]" value="${product.quantity || 1}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <input type="number" class="form-control product-subtotal" readonly value="${(product.price || 0) * (product.quantity || 1)}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-danger btn-sm remove-product" style="width: 100%;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input product-combo" name="products[${productIndex}][is_combo]" ${product.is_combo ? 'checked' : ''}>
                                    <label class="form-check-label">Is Combo Product</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Custom Variations</label>
                                    <textarea class="form-control product-variations" name="products[${productIndex}][variations]" rows="2" placeholder="Custom variations or notes">${product.variations || product.selections || ''}</textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Stock Info</label>
                                    <input type="text" class="form-control stock-info" readonly placeholder="Stock information will appear here">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Product Details</label>
                                    <div class="product-details-display" style="font-size: 11px; color: #6c757d; padding: 5px; background: #f8f9fa; border-radius: 3px;">
                                        ${product.product_id ? `ID: ${product.product_id}` : 'No product selected'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                container.append(productHtml);
                
                // Initialize product search for this row
                initializeProductSearch(productIndex);
                
                // Load variation combinations if product ID is provided
                if (product.product_id) {
                    loadVariationCombinations(productIndex, product.product_id);
                }
            }

            // Initialize product search functionality
            function initializeProductSearch(productIndex) {
                const row = $(`.product-row[data-index="${productIndex}"]`);
                const searchInput = row.find('.product-search');
                const resultsContainer = row.find('.product-search-results');
                const productIdInput = row.find('.product-id');
                const productNameInput = row.find('.product-name');
                const detailsDisplay = row.find('.product-details-display');

                let searchTimeout;

                searchInput.on('input', function() {
                    const query = $(this).val().trim();
                    
                    clearTimeout(searchTimeout);
                    
                    if (query.length < 2) {
                        resultsContainer.hide();
                        return;
                    }

                    searchTimeout = setTimeout(function() {
                        $.get('{{ route("admin.products.search") }}', { query: query }, function(response) {
                            resultsContainer.empty();
                            
                            if (response.products && response.products.length > 0) {
                                response.products.forEach(function(product) {
                                    const item = $(`
                                        <div class="product-search-item" data-product-id="${product.id}" data-product-name="${product.title}">
                                            <strong>${product.title}</strong><br>
                                            <small>ID: ${product.id} | Price: ৳${product.price || 'N/A'}</small>
                                        </div>
                                    `);
                                    
                                    item.on('click', function() {
                                        productIdInput.val(product.id);
                                        productNameInput.val(product.title);
                                        detailsDisplay.html(`ID: ${product.id} | ${product.title}`);
                                        searchInput.val(product.title);
                                        resultsContainer.hide();
                                        
                                        // Load variations for this product
                                        loadVariationCombinations(productIndex, product.id);
                                    });
                                    
                                    resultsContainer.append(item);
                                });
                                
                                resultsContainer.show();
                            } else {
                                resultsContainer.html('<div class="product-search-item">No products found</div>');
                                resultsContainer.show();
                            }
                        }).fail(function() {
                            resultsContainer.html('<div class="product-search-item">Error searching products</div>');
                            resultsContainer.show();
                        });
                    }, 300);
                });

                // Hide results when clicking outside
                $(document).on('click', function(e) {
                    if (!searchInput.is(e.target) && !resultsContainer.is(e.target) && resultsContainer.has(e.target).length === 0) {
                        resultsContainer.hide();
                    }
                });
            }

            // Load products into the form
            function loadProducts(productDetails) {
                const container = $('#products_container');
                container.empty();
                
                if (!productDetails) {
                    addProductRow({}, 0);
                    return;
                }
                
                let products = productDetails;
                if (typeof products === 'string') {
                    try {
                        products = JSON.parse(products);
                    } catch (e) {
                        products = [];
                    }
                }
                
                if (Array.isArray(products) && products.length > 0) {
                    products.forEach(function(product, index) {
                        addProductRow(product, index);
                    });
                } else {
                    addProductRow({}, 0);
                }
            }

            // Add product button
            $('#add_product_btn').on('click', function() {
                addProductRow();
            });

            // Remove product
            $(document).on('click', '.remove-product', function() {
                if ($('#products_container .product-row').length > 1) {
                    $(this).closest('.product-row').remove();
                    reindexProducts();
                    calculateTotals();
                }
            });

            // Reindex products after removal
            function reindexProducts() {
                $('#products_container .product-row').each(function(index) {
                    $(this).attr('data-index', index);
                    $(this).find('.product-info h6').text(`Product ${index + 1}`);
                    $(this).find('input, textarea, select').each(function() {
                        const name = $(this).attr('name');
                        if (name) {
                            $(this).attr('name', name.replace(/products\[\d+\]/, `products[${index}]`));
                        }
                    });
                });
            }

            // Handle variation combination selection
            $(document).on('change', '.variation-combination', function() {
                const row = $(this).closest('.product-row');
                const selectedOption = $(this).find('option:selected');
                const price = parseFloat(selectedOption.data('price')) || 0;
                const stock = selectedOption.data('stock') || 0;
                
                row.find('.product-price').val(price.toFixed(2));
                row.find('.stock-info').val(`Stock: ${stock}`);
                
                const quantity = parseInt(row.find('.product-quantity').val()) || 0;
                row.find('.product-subtotal').val((price * quantity).toFixed(2));
                calculateTotals();
            });

            // Calculate totals when product details change
            $(document).on('input', '.product-price, .product-quantity', function() {
                const row = $(this).closest('.product-row');
                const price = parseFloat(row.find('.product-price').val()) || 0;
                const quantity = parseInt(row.find('.product-quantity').val()) || 0;
                row.find('.product-subtotal').val((price * quantity).toFixed(2));
                calculateTotals();
            });

            // Calculate totals
            function calculateTotals() {
                let subtotal = 0;
                $('.product-subtotal').each(function() {
                    subtotal += parseFloat($(this).val()) || 0;
                });
                
                const discount = parseFloat($('#discount').val()) || 0;
                const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
                const total = subtotal - discount + shippingCost;
                
                $('#subtotal').val(subtotal.toFixed(2));
                $('#total_amount').val(total.toFixed(2));
            }

            // Update totals when discount or shipping changes
            $('#discount, #shipping_cost').on('input', calculateTotals);

            // Load variation combinations for a product
            function loadVariationCombinations(productIndex, productId) {
                const row = $(`.product-row[data-index="${productIndex}"]`);
                const select = row.find('.variation-combination');
                
                select.html('<option value="">Loading variations...</option>');
                
                $.get('{{ route("admin.incomplete-orders.get-variation-combinations") }}', {
                    product_id: productId
                }, function(response) {
                    select.html('<option value="">Select Variation</option>');
                    
                    if (response.combinations && response.combinations.length > 0) {
                        response.combinations.forEach(function(combination) {
                            select.append(`<option value="${combination.id}" data-price="${combination.price}" data-stock="${combination.stock}">${combination.name} - ৳${combination.price}</option>`);
                        });
                    } else {
                        select.html('<option value="">No variations found</option>');
                    }
                }).fail(function(xhr, status, error) {
                    select.html('<option value="">Error loading variations</option>');
                });
            }

            // Save as potential customer
            $('#save_as_customer_btn').on('click', function() {
                const formData = new FormData($('#convertOrderForm')[0]);
                formData.append('action', 'save_customer');
                
                $.ajax({
                    url: '{{ route("admin.incomplete-orders.convert") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Customer saved successfully!');
                            window.close();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Failed to save customer. Please try again.');
                    }
                });
            });

            // Convert to order form submission
            $('#convertOrderForm').on('submit', function(e) {
                e.preventDefault();
                
                // Basic form validation
                if (!validateForm()) {
                    return false;
                }
                
                const formData = new FormData(this);
                formData.append('action', 'create_order');
                
                // Manually add products data
                const products = [];
                $('#products_container .product-row').each(function(index) {
                    const row = $(this);
                    const product = {
                        name: row.find('.product-name').val().trim(),
                        price: parseFloat(row.find('.product-price').val()) || 0,
                        quantity: parseInt(row.find('.product-quantity').val()) || 0,
                        product_id: row.find('.product-id').val() || null,
                        combination_id: row.find('.variation-combination').val() || null,
                        is_combo: row.find('.product-combo').is(':checked'),
                        variations: row.find('.product-variations').val() || null
                    };
                    products.push(product);
                });
                
                // Add products to form data
                formData.append('products', JSON.stringify(products));
                

                
                // Show loading state
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating Order...');
                
                $.ajax({
                    url: '{{ route("admin.incomplete-orders.convert") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        submitBtn.prop('disabled', false).html(originalText);
                        
                        if (response.success) {
                            let message = 'Order created successfully!\n\n';
                            message += 'Order ID: ' + response.order_id + '\n';
                            message += 'User ID: ' + response.user_id + '\n';
                            if (response.is_new_user) {
                                message += '✅ New customer account created\n';
                                message += '📧 Email: ' + response.user_email;
                            } else {
                                message += '👤 Existing customer account used';
                            }
                            message += '\n\n🗑️ Incomplete order has been deleted';
                            
                            alert(message);
                            window.close();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        submitBtn.prop('disabled', false).html(originalText);
                        
                        let errorMessage = 'Failed to create order. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        alert(errorMessage);
                    }
                });
            });
            
            // Form validation function
            function validateForm() {
                let isValid = true;
                let errors = [];
                
                // Check required fields
                if (!$('#customer_name').val().trim()) {
                    errors.push('Customer name is required');
                    isValid = false;
                }
                
                if (!$('#customer_phone').val().trim()) {
                    errors.push('Phone number is required');
                    isValid = false;
                }
                
                // Validate email format if provided
                const email = $('#customer_email').val().trim();
                if (email && !isValidEmail(email)) {
                    errors.push('Please enter a valid email address');
                    isValid = false;
                }
                
                if (!$('#customer_address').val().trim()) {
                    errors.push('Address is required');
                    isValid = false;
                }
                
                if (!$('#customer_upazila').val().trim()) {
                    errors.push('Upazila is required');
                    isValid = false;
                }
                
                if (!$('#customer_city').val().trim()) {
                    errors.push('City is required');
                    isValid = false;
                }
                
                if (!$('#payment_method').val()) {
                    errors.push('Payment method is required');
                    isValid = false;
                }
                
                if (!$('#total_amount').val() || parseFloat($('#total_amount').val()) <= 0) {
                    errors.push('Total amount must be greater than 0');
                    isValid = false;
                }
                
                // Check if at least one product is added
                if ($('#products_container .product-row').length === 0) {
                    errors.push('At least one product is required');
                    isValid = false;
                }
                
                // Check each product row
                $('#products_container .product-row').each(function(index) {
                    const row = $(this);
                    const name = row.find('.product-name').val().trim();
                    const price = parseFloat(row.find('.product-price').val()) || 0;
                    const quantity = parseInt(row.find('.product-quantity').val()) || 0;
                    
                    if (!name) {
                        errors.push(`Product ${index + 1}: Name is required`);
                        isValid = false;
                    }
                    
                    if (price <= 0) {
                        errors.push(`Product ${index + 1}: Price must be greater than 0`);
                        isValid = false;
                    }
                    
                    if (quantity <= 0) {
                        errors.push(`Product ${index + 1}: Quantity must be greater than 0`);
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    alert('Please fix the following errors:\n' + errors.join('\n'));
                }
                
                return isValid;
            }
            
            // Email validation helper function
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
@endsection
