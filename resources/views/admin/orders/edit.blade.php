@extends('layouts.master')

@section('content')
    <style>
        table.table {
            white-space: nowrap;
            border: 0.1px solid #C6C7C8;
        }

        .order-status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .order-card {
            transition: all 0.3s ease;
            border-left: 4px solid #4e73df;
        }

        .order-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .product-image {
            border: 1px solid #eee;
            padding: 4px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .product-search-wrapper {
            position: relative;
        }

        .product-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            background: #fff;
            border: 1px solid #dee2e6;
            border-top: none;
            max-height: 260px;
            overflow-y: auto;
            display: none;
        }

        .product-search-results button {
            width: 100%;
            text-align: left;
            background: transparent;
            border: none;
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .product-search-results .list-group-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-search-thumb {
            width: 45px;
            height: 45px;
            border-radius: 6px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .product-search-details {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .product-search-title {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .product-search-meta {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .product-search-results button:hover,
        .product-search-results button:focus {
            background-color: #f8f9fc;
        }

        .selected-product-hint {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .summary-card {
            background-color: #f8f9fc;
            border-radius: 8px;
        }

        .customer-info-table th {
            width: 35%;
            background-color: #f8f9fc;
        }

        .section-heading {
            border-bottom: 2px solid #4e73df;
            padding-bottom: 8px;
            margin-bottom: 20px;
            color: #2e59d9;
        }

        .order-meta {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .update-order {
            display: fixed;
            position: fixed;
            right: 30px;
            bottom: 30px;
            z-index: 1000 !important;
        }

        .modal-header {
            justify-content: space-between;
        }

        .modal-header button.close {
            font-size: 37px;
            color: red;
        }

        div#courier-status-result {
            font-size: 16px;
            font-weight: bold;
        }
        button#delivernow {
            margin-top: 7px;
        }
    </style>
    <div class="container-fluid mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-3 shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
            </ol>
        </nav>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i> Order #{{ $order->id }}
                </h5>
                <div>
                    <span
                        class="order-status-badge bg-{{ $order->status === 'pending'
                            ? 'warning'
                            : ($order->status === 'processing'
                                ? 'info'
                                : ($order->status === 'delivered'
                                    ? 'success'
                                    : ($order->status === 'canceled'
                                        ? 'danger'
                                        : 'secondary'))) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <!-- Order Meta Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="order-meta">
                            <i class="far fa-calendar-alt me-1"></i> Order Date:
                            <strong>{{ $order->created_at->format('d M, Y h:i A') }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="order-meta">
                            <i class="fas fa-money-bill-wave me-1"></i> Payment Method:
                            <strong>{{ $order->payment_method == 'cod' ? 'Cash On Delivery' : ucfirst($order->payment_method) }}</strong>
                        </div>
                        <div class="mt-3">
                            <input type="hidden" id="order_id" value="{{ $order->id }}">

                            <!-- Courier Actions -->
                            <div class="d-flex flex-wrap gap-2 mb-3 justify-content-md-end">

                                
                                @if (!isset($order->delivery_data['courier_provider']) || $order->delivery_data['courier_provider'] !== 'steadfast')
                                    <button class="btn btn-success" id="send-to-steadfast" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-paper-plane"></i> Send to Steadfast
                                    </button>
                                @else
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check"></i> Sent to Steadfast
                                    </button>
                                @endif
                                
                                @if (!isset($order->delivery_data['courier_provider']) || $order->delivery_data['courier_provider'] !== 'pathao')
                                    <button class="btn btn-primary" id="send-to-pathao" data-order-id="{{ $order->id }}">
                                        <i class="fas fa-shipping-fast"></i> Send to Pathao
                                    </button>
                                @else
                                    <button class="btn btn-primary" disabled>
                                        <i class="fas fa-check"></i> Sent to Pathao
                                    </button>
                                @endif

                                @if (isset($order->delivery_data['consignment_id']))
                                    <button id="check-courier-status" class="btn btn-secondary text-light">
                                        <i class="fas fa-info-circle"></i> Check Courier Status
                                    </button>
                                @endif
                            </div>

                            <div id="courier-status-result"></div>
                            <span id="courier-action-status" class="ms-2"></span>
                        </div>
                    </div>
                </div>



                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Orders
                    </a>
                    <!-- Print & Download Buttons -->
                    <div class="btn-group ms-2" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-print me-1"></i> Print & Download
                        </button>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Print Options</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.print-receipt', $order->id) }}" target="_blank">
                                    <i class="fas fa-receipt me-2"></i> Print Receipt
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.print-invoice', $order->id) }}" target="_blank">
                                    <i class="fas fa-file-invoice me-2"></i> Print Invoice
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.print-package-slip', $order->id) }}" target="_blank">
                                    <i class="fas fa-box me-2"></i> Print Package Slip
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Download Options</h6></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.download-receipt', $order->id) }}">
                                    <i class="fas fa-download me-2"></i> Download Receipt
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.download-invoice', $order->id) }}">
                                    <i class="fas fa-download me-2"></i> Download Invoice
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.pos.download-package-slip', $order->id) }}">
                                    <i class="fas fa-download me-2"></i> Download Package Slip
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <h4 class="section-heading mb-3 mb-md-0">
                            <i class="fas fa-box me-2"></i> Product Details
                        </h4>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addOrderProductModal">
                            <i class="fas fa-plus me-1"></i> Add Product
                        </button>
                    </div>
                    
                    {{-- Display Combo Order Details --}}
                    @if ($order->is_combo_order && $order->combo_offer_id)
                        @php
                            $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
                            $comboSelections = $order->combo_selections;
                            if (is_string($comboSelections)) {
                                $comboSelections = json_decode($comboSelections, true);
                            }
                        @endphp
                        @if ($comboOffer)
                            <div class="card mb-3 border-0 shadow-sm order-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge bg-primary me-2">COMBO OFFER</span>
                                        <h5 class="mb-0 fw-bold">{{ $comboOffer->title }}</h5>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="combo-selections-section">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-list me-1"></i> Selected Items:
                                                </h6>
                                                @if ($comboSelections && is_array($comboSelections))
                                                    @foreach ($comboSelections as $index => $selection)
                                                        @php
                                                            $selectedProduct = \App\Models\Product::find($selection['product_id']);
                                                            $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                        @endphp
                                                        @if ($selectedProduct)
                                                            <div class="combo-selection-item mb-2 p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <strong>{{ $index + 1 }}. {{ $selectedProduct->title }}</strong>
                                                                        @if ($selectedVariation)
                                                                            <br><small class="text-muted">Variation: {{ $selectedVariation->display_name }}</small>
                                                                        @endif
                                                                    </div>
                                                                    @if ($selectedProduct->thumb_image)
                                                                        <img src="{{ asset('storage/' . $selectedProduct->thumb_image) }}" 
                                                                             width="60" alt="{{ $selectedProduct->title }}" 
                                                                             class="rounded product-image">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Combo selections not available
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="combo-summary">
                                                <h6 class="text-muted mb-2">
                                                    <i class="fas fa-calculator me-1"></i> Combo Summary:
                                                </h6>
                                                <div class="bg-light p-3 rounded">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Combo Price:</span>
                                                        <strong>৳{{ number_format($comboOffer->combo_price, 2) }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Original Price:</span>
                                                        <span class="text-muted"><del>৳{{ number_format($comboOffer->original_price, 2) }}</del></span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Discount:</span>
                                                        <span class="text-success">-৳{{ number_format($comboOffer->discount_amount, 2) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Quantity:</span>
                                                        <strong>{{ $order->order_items->first()->quantity ?? 1 }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- Display Regular Order Products --}}
                    @if (!empty($order->order_items))
                            @foreach ($order->order_items as $orderItem)
                            <div class="card mb-3 border-0 shadow-sm order-card">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    @php
                                        $product = $orderItem->product;
                                    @endphp
                                    <div>
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-2">Item #{{ $loop->index + 1 }}</span>
                                            <h6 class="mb-0 fw-bold">{{ $product ? $product->title : 'Product not found' }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editOrderItem{{ $orderItem->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.orders.destroy-item', ['order' => $order->id, 'item' => $orderItem->id]) }}"
                                                method="POST" class="d-inline ms-2"
                                                onsubmit="return confirm('Are you sure you want to remove this product from the order?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </form>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-7">
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-tag me-1"></i> SKU:
                                                    <strong>{{ $product ? $product->sku : 'N/A' }}</strong>
                                                </p>
                                                @if($product && $product->product_type === 'variable' && $orderItem->variationCombination)
                                                    <p class="text-muted mb-1">
                                                        <i class="fas fa-palette me-1"></i> Variation:
                                                        <strong>{{ $orderItem->variationCombination->display_name }}</strong>
                                                    </p>
                                                    @if($orderItem->variationCombination->short_description)
                                                        <p class="text-muted mb-1">
                                                            <i class="fas fa-info-circle me-1"></i> Description:
                                                            <strong>{{ $orderItem->variationCombination->short_description }}</strong>
                                                        </p>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-5">
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-cubes me-1"></i> Quantity:
                                                    <strong>{{ $orderItem->quantity }}</strong>
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-money-bill-alt me-1"></i> Price:
                                                    @if($product && $product->product_type === 'variable' && $orderItem->variationCombination)
                                                        @if($orderItem->variationCombination->has_offer)
                                                            <strong class="text-success">{{ $orderItem->variationCombination->formatted_offer_price }}</strong>
                                                            <del class="text-muted">{{ $orderItem->variationCombination->formatted_regular_price }}</del>
                                                        @else
                                                            <strong>{{ $orderItem->variationCombination->formatted_regular_price }}</strong>
                                                        @endif
                                                    @else
                                                        <strong>{{ number_format($orderItem->price, 2) }} TK</strong>
                                                    @endif
                                                </p>
                                                <p class="text-muted mb-1">
                                                    <i class="fas fa-calculator me-1"></i> Subtotal:
                                                    <strong>{{ number_format($orderItem->sub_total, 2) }} TK</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($product && $product->product_type === 'variable' && $orderItem->variationCombination && $orderItem->variationCombination->featured_image)
                                        <img src="{{ asset('storage/' . $orderItem->variationCombination->featured_image) }}" width="120"
                                            alt="{{ $product ? $product->title : 'Product image' }}" class="rounded product-image">
                                    @elseif($product)
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" width="120"
                                            alt="{{ $product->title }}" class="rounded product-image">
                                    @else
                                        <div class="rounded product-image bg-light d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                            <small class="text-muted">No Image</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Edit Modal for this specific order item -->
                            <div class="modal fade order-item-modal" id="editOrderItem{{ $orderItem->id }}" tabindex="-1"
                                data-order-item-id="{{ $orderItem->id }}" data-initial-product-id="{{ $product ? $product->id : '' }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Order Item - {{ $product ? $product->title : 'Unknown Product' }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.orders.update-item', ['order' => $order->id, 'item' => $orderItem->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Product</label>
                                                    <div class="product-search-wrapper">
                                                        <input type="hidden" name="product_id" value="{{ $product ? $product->id : '' }}">
                                                        <input type="text" class="form-control product-search-input"
                                                            placeholder="Search product by name or ID"
                                                            value="{{ $product ? $product->title : '' }}" autocomplete="off">
                                                        <div class="product-search-results list-group"></div>
                                                    </div>
                                                    <div class="form-text selected-product-hint">
                                                        {{ $product ? 'Selected product #' . $product->id : 'Select a product to continue' }}
                                                    </div>
                                                </div>
                                                <div class="mb-3 variation-group {{ $product && $product->product_type === 'variable' ? '' : 'd-none' }}">
                                                    <label class="form-label">Variation</label>
                                                    <select name="combination_id" class="form-select product-variation-select"
                                                        data-selected="{{ $orderItem->combination_id }}">
                                                        @if($product && $product->product_type === 'variable')
                                                            <option value="">Select Variation</option>
                                                            @foreach($product->variationCombinations as $combination)
                                                                <option value="{{ $combination->id }}"
                                                                    {{ $orderItem->combination_id == $combination->id ? 'selected' : '' }}
                                                                    data-regular-price="{{ $combination->regular_price }}"
                                                                    data-offer-price="{{ $combination->offer_price }}"
                                                                    data-effective-price="{{ $combination->effective_price }}"
                                                                    data-has-offer="{{ $combination->has_offer ? 1 : 0 }}">
                                                                    {{ $combination->display_name }}
                                                                    ({{ $combination->formatted_price }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="form-text combination-price-info mt-2">
                                                        @if($orderItem->variationCombination)
                                                            @if($orderItem->variationCombination->has_offer)
                                                                <span class="text-success">Offer Price:
                                                                    ৳{{ $orderItem->variationCombination->formatted_offer_price }}</span>
                                                                <span class="text-muted ms-2"><del>Regular:
                                                                        ৳{{ $orderItem->variationCombination->formatted_regular_price }}</del></span>
                                                            @else
                                                                <span>Regular Price:
                                                                    ৳{{ $orderItem->variationCombination->formatted_regular_price }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="quantity" class="form-control quantity-input"
                                                        value="{{ $orderItem->quantity }}" min="1">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Price per unit</label>
                                                    <input type="number" name="price" class="form-control price-input"
                                                        value="{{ $orderItem->price }}" step="0.01">
                                                    <div class="form-text price-warning text-danger d-none">
                                                        The entered price differs from the selected product price.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Add Product Modal -->
                <div class="modal fade order-item-modal" id="addOrderProductModal" tabindex="-1" data-mode="create">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Product to Order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.orders.store-item', $order->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Product</label>
                                        <div class="product-search-wrapper">
                                            <input type="hidden" name="product_id" value="">
                                            <input type="text" class="form-control product-search-input"
                                                placeholder="Search product by name or ID" autocomplete="off">
                                            <div class="product-search-results list-group"></div>
                                        </div>
                                        <div class="form-text selected-product-hint">
                                            No product selected yet
                                        </div>
                                    </div>
                                    <div class="mb-3 variation-group d-none">
                                        <label class="form-label">Variation</label>
                                        <select name="combination_id" class="form-select product-variation-select"
                                            data-selected="">
                                        </select>
                                        <div class="form-text combination-price-info mt-2"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quantity</label>
                                        <input type="number" name="quantity" class="form-control quantity-input" value="1"
                                            min="1">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price per unit</label>
                                        <input type="number" name="price" class="form-control price-input" value=""
                                            step="0.01">
                                        <div class="form-text price-warning text-danger d-none">
                                            The entered price differs from the selected product price.
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Add Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @if ($order->admin_note)
                    <div class="alert alert-warning mb-4">
                        <h6 class="mb-2"><i class="fas fa-sticky-note me-2"></i> Admin Note:</h6>
                        <p class="mb-0">{{ $order->admin_note }}</p>
                    </div>
                @endif

                <!-- Order Details -->
                <div class="mb-4">
                    <h4 class="section-heading">
                        <i class="fas fa-info-circle me-2"></i> Order Information
                    </h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i> Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered customer-info-table">
                                        <tbody>
                                            <tr>
                                                <th><i class="fas fa-user me-1"></i> Name</th>
                                                <td>{{ $order->name }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-phone me-1"></i> Phone</th>
                                                <td>
                                                    <a href="tel:{{ $order->phone }}" class="text-decoration-none">
                                                        {{ $order->phone }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-map-marker-alt me-1"></i> Upazila</th>
                                                <td>{{ $order->upazila }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-city me-1"></i> City</th>
                                                <td>{{ $order->city }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-map me-1"></i> Address</th>
                                                <td style="word-break: break-all; white-space: normal;">
                                                    {{ $order->address }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-calculator me-2"></i> Order Summary</h6>
                                </div>
                                <div class="card-body summary-card">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><i class="fas fa-shopping-basket me-1"></i> Subtotal</span>
                                        <span class="fw-bold">{{ number_format($order->total - $order->shipping, 2) }}
                                            TK</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><i class="fas fa-truck me-1"></i> Shipping Cost</span>
                                        <span class="fw-bold">{{ number_format($order->shipping, 2) }} TK</span>
                                    </div>

                                    @if ($order->payment_method == 'bkash' && $order->bkash_charge > 0)
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-percentage me-1"></i> bKash Charge</span>
                                            <span class="fw-bold">{{ number_format($order->bkash_charge, 2) }} TK</span>
                                        </div>
                                        <div class="payment-details bg-light rounded mb-3">
                                            <p><i class="fas fa-info-circle me-1"></i> bKash Details:</p>
                                            <p>Number: <strong>{{ $order->bkash_number }}</strong></p>
                                            <p>TrxID: <strong>{{ $order->bkash_transaction_id }}</strong></p>
                                        </div>
                                    @endif

                                    @if ($order->payment_method == 'nagad' && $order->nagad_charge > 0)
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-percentage me-1"></i> Nagad Charge</span>
                                            <span class="fw-bold">{{ number_format($order->nagad_charge, 2) }} TK</span>
                                        </div>
                                        <div class="payment-details bg-light rounded mb-3 ">
                                            <p><i class="fas fa-info-circle me-1"></i> Nagad Details:</p>
                                            <p>Number: <strong>{{ $order->nagad_number }}</strong></p>
                                            <p>TrxID: <strong>{{ $order->nagad_transaction_id }}</strong></p>
                                        </div>
                                    @endif

                                    @if ($order->payment_method == 'rocket' && $order->rocket_charge > 0)
                                        <div class="d-flex justify-content-between mb-3">
                                            <span><i class="fas fa-percentage me-1"></i> Rocket Charge</span>
                                            <span class="fw-bold">{{ number_format($order->rocket_charge, 2) }} TK</span>
                                        </div>
                                        <div class="payment-details bg-light rounded mb-3 ">
                                            <p><i class="fas fa-info-circle me-1"></i> Rocket Details:</p>
                                            <p>Number: <strong>{{ $order->rocket_number }}</strong></p>
                                            <p>TrxID: <strong>{{ $order->rocket_transaction_id }}</strong></p>
                                        </div>
                                    @endif

                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fw-bold"><i class="fas fa-money-bill-wave me-1"></i> Total
                                            Amount</span>
                                        <span
                                            class="fw-bold fs-5 text-primary">{{ number_format($order->total_with_charge, 2) }}
                                            TK</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-credit-card me-1"></i> Payment Status</span>
                                        <span
                                            class="badge px-3 py-2 @switch($order->payment_status)
                                            @case('paid') bg-success @break
                                            @case('pending') bg-warning @break
                                            @case('failed') bg-danger @break
                                            @case('transaction_not_matched') bg-secondary @break
                                            @default bg-light @endswitch">
                                            @switch($order->payment_status)
                                                @case('paid')
                                                    Paid
                                                @break

                                                @case('pending')
                                                    Pending
                                                @break

                                                @case('failed')
                                                    Failed
                                                @break

                                                @case('transaction_not_matched')
                                                    Transaction Not Matched
                                                @break

                                                @default
                                                    Unknown
                                            @endswitch
                                        </span>
                                    </div>
                                    @if($order->payment_type)
                                    <div class="d-flex justify-content-between mt-2">
                                        <span><i class="fas fa-wallet me-1"></i> Payment Type</span>
                                        <span
                                            class="badge px-3 py-2 @switch($order->payment_type)
                                            @case('full_paid') bg-success @break
                                            @case('partial') bg-warning @break
                                            @case('due') bg-danger @break
                                            @default bg-light @endswitch">
                                            @switch($order->payment_type)
                                                @case('full_paid') Full Paid @break
                                                @case('partial') Partial Pay @break
                                                @case('due') Due @break
                                                @default {{ ucfirst($order->payment_type ?? 'N/A') }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @if($order->payment_type === 'partial' || $order->payment_type === 'due')
                                    <div class="d-flex justify-content-between mt-2">
                                        <span><i class="fas fa-hand-holding-usd me-1"></i> Paid Amount</span>
                                        <span class="text-success fw-bold">৳{{ number_format($order->paid_amount ?? 0, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span><i class="fas fa-exclamation-circle me-1"></i> Due Amount</span>
                                        <span class="text-danger fw-bold">৳{{ number_format($order->due_amount ?? 0, 2) }}</span>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Update Order Status & Others info</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <div class="row">
                                    <!-- Existing fields for status, payment_status, assign, status_note, admin_note -->
                                    <div class="col-md-6 mb-4">
                                        <label for="status" class="form-label fw-bold">
                                            <i class="fas fa-tasks me-1"></i> Order Status
                                        </label>
                                        <select name="status" id="status" class="form-select"
                                            {{ $order->status === 'delivered' ? 'disabled' : '' }}>
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="processing"
                                                {{ $order->status === 'processing' ? 'selected' : '' }}>
                                                Processing
                                            </option>
                                            <option value="phone_not_rcv"
                                                {{ $order->status === 'phone_not_rcv' ? 'selected' : '' }}>
                                                Call Not Received
                                            </option>
                                            <option value="follow_up"
                                                {{ $order->status === 'follow_up' ? 'selected' : '' }}>
                                                Follow up
                                            </option>
                                            <option value="on_hold" {{ $order->status === 'on_hold' ? 'selected' : '' }}>
                                                On Hold
                                            </option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                                                Shipped
                                            </option>
                                            <option value="ready_for_delivery"
                                                {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>
                                                Ready For Delivery
                                            </option>
                                            <option value="delivered"
                                                {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>
                                        </select>
                                        @if ($order->status === 'delivered')
                                            <div class="form-text text-muted mt-1">
                                                <i class="fas fa-info-circle me-1"></i> This order is delivered and cannot
                                                be updated.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="payment_status" class="form-label fw-bold">
                                            <i class="fas fa-money-bill me-1"></i> Payment Status
                                        </label>
                                        <select name="payment_status" id="payment_status" class="form-select">
                                            <option value="pending"
                                                {{ $order->payment_status === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="paid"
                                                {{ $order->payment_status === 'paid' ? 'selected' : '' }}>
                                                Paid
                                            </option>
                                            <option value="failed"
                                                {{ $order->payment_status === 'failed' ? 'selected' : '' }}>
                                                Failed
                                            </option>
                                            <option value="transaction_not_matched"
                                                {{ $order->payment_status === 'transaction_not_matched' ? 'selected' : '' }}>
                                                Transaction Not Matched
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Payment Type Fields -->
                                    <div class="col-md-4 mb-4">
                                        <label for="payment_type" class="form-label fw-bold">
                                            <i class="fas fa-wallet me-1"></i> Payment Type
                                        </label>
                                        <select name="payment_type" id="payment_type" class="form-select" onchange="togglePaymentAmountFields()">
                                            <option value="full_paid" {{ ($order->payment_type ?? 'full_paid') === 'full_paid' ? 'selected' : '' }}>
                                                Full Paid
                                            </option>
                                            <option value="partial" {{ ($order->payment_type ?? '') === 'partial' ? 'selected' : '' }}>
                                                Partial Pay
                                            </option>
                                            <option value="due" {{ ($order->payment_type ?? '') === 'due' ? 'selected' : '' }}>
                                                Due
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="paid_amount" class="form-label fw-bold">
                                            <i class="fas fa-hand-holding-usd me-1"></i> Paid Amount
                                        </label>
                                        <input type="number" name="paid_amount" id="paid_amount" class="form-control"
                                            value="{{ $order->paid_amount ?? 0 }}" min="0" step="0.01"
                                            onchange="calculateDueFromPaid()">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="due_amount" class="form-label fw-bold">
                                            <i class="fas fa-exclamation-circle me-1"></i> Due Amount
                                        </label>
                                        <input type="number" name="due_amount" id="due_amount" class="form-control"
                                            value="{{ $order->due_amount ?? 0 }}" min="0" step="0.01" readonly
                                            style="background-color: #f8f9fa;">
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label for="assign" class="form-label fw-bold">
                                            <i class="fas fa-user-tag me-1"></i> Assign Order to
                                        </label>
                                        <select name="assign" id="assign" class="form-select">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $order->assigned_to == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="status_note" class="form-label fw-bold">
                                            <i class="fas fa-comment-alt me-1"></i> Status Update Note
                                        </label>
                                        <textarea name="status_note" id="status_note" class="form-control" rows="2"
                                            placeholder="Add a note about this status update (will be visible in timeline)"></textarea>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> This note will be recorded with the
                                            status change in the timeline.
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="admin_note" class="form-label fw-bold">
                                            <i class="fas fa-sticky-note me-1"></i> Admin Note
                                        </label>
                                        <textarea name="admin_note" id="admin_note" class="form-control" rows="3"
                                            placeholder="Add notes about this order (only visible to admin)">{{ $order->admin_note }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="courier_note" class="form-label fw-bold">
                                            <i class="fas fa-shipping-fast me-1"></i> Courier Message/Note
                                        </label>
                                        <textarea name="courier_note" id="courier_note" class="form-control" rows="3"
                                            placeholder="Add message/instructions for courier delivery (will be sent to courier service)">{{ $order->courier_note }}</textarea>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-info-circle me-1"></i> This note will be sent to the courier service (Steadfast, Pathao, etc.) for delivery instructions.
                                        </div>
                                    </div>
                                    <!-- Editable Customer Info -->
                                    <div class="col-md-6 mb-4">
                                        <label for="name" class="form-label fw-bold">
                                            <i class="fas fa-user me-1"></i> Customer Name
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ $order->name }}">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="phone" class="form-label fw-bold">
                                            <i class="fas fa-phone me-1"></i> Phone
                                        </label>
                                        <input type="text" name="phone" id="phone" class="form-control"
                                            value="{{ $order->phone }}">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="upazila" class="form-label fw-bold">
                                            <i class="fas fa-map-marker-alt me-1"></i> Upazila
                                        </label>
                                        <input type="text" name="upazila" id="upazila" class="form-control"
                                            value="{{ $order->upazila }}">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="city" class="form-label fw-bold">
                                            <i class="fas fa-city me-1"></i> City
                                        </label>
                                        <input type="text" name="city" id="city" class="form-control"
                                            value="{{ $order->city }}">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label for="address" class="form-label fw-bold">
                                            <i class="fas fa-map me-1"></i> Address
                                        </label>
                                        <textarea name="address" id="address" class="form-control" rows="2">{{ $order->address }}</textarea>
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label for="message" class="form-label fw-bold">
                                            <i class="fas fa-comment me-1"></i> Message
                                        </label>
                                        <textarea name="message" id="message" class="form-control" rows="2">{{ $order->message }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="shipping" class="form-label fw-bold">
                                            <i class="fas fa-truck me-1"></i> Shipping Cost
                                        </label>
                                        <input type="number" name="shipping" id="shipping" class="form-control"
                                            value="{{ $order->shipping }}" step="0.01">
                                    </div>
                                    <!-- Payment Method -->
                                    <div class="col-md-6 mb-4">
                                        <label for="payment_method" class="form-label fw-bold">
                                            <i class="fas fa-money-bill-wave me-1"></i> Payment Method
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-select">
                                            <option value="cod"
                                                {{ $order->payment_method == 'cod' ? 'selected' : '' }}>Cash On Delivery
                                            </option>
                                            <option value="bkash"
                                                {{ $order->payment_method == 'bkash' ? 'selected' : '' }}>bKash</option>
                                            <option value="nagad"
                                                {{ $order->payment_method == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                            <option value="rocket"
                                                {{ $order->payment_method == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                        </select>
                                    </div>
                                    <!-- Payment Gateway Details -->
                                    <div class="col-md-4 mb-4">
                                        <label for="bkash_number" class="form-label fw-bold">bKash Number</label>
                                        <input type="text" name="bkash_number" id="bkash_number" class="form-control"
                                            value="{{ $order->bkash_number }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="bkash_transaction_id" class="form-label fw-bold">bKash TrxID</label>
                                        <input type="text" name="bkash_transaction_id" id="bkash_transaction_id"
                                            class="form-control" value="{{ $order->bkash_transaction_id }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="bkash_charge" class="form-label fw-bold">bKash Charge</label>
                                        <input type="number" name="bkash_charge" id="bkash_charge" class="form-control"
                                            value="{{ $order->bkash_charge }}" step="0.01">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="nagad_number" class="form-label fw-bold">Nagad Number</label>
                                        <input type="text" name="nagad_number" id="nagad_number" class="form-control"
                                            value="{{ $order->nagad_number }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="nagad_transaction_id" class="form-label fw-bold">Nagad TrxID</label>
                                        <input type="text" name="nagad_transaction_id" id="nagad_transaction_id"
                                            class="form-control" value="{{ $order->nagad_transaction_id }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="nagad_charge" class="form-label fw-bold">Nagad Charge</label>
                                        <input type="number" name="nagad_charge" id="nagad_charge" class="form-control"
                                            value="{{ $order->nagad_charge }}" step="0.01">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="rocket_number" class="form-label fw-bold">Rocket Number</label>
                                        <input type="text" name="rocket_number" id="rocket_number"
                                            class="form-control" value="{{ $order->rocket_number }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="rocket_transaction_id" class="form-label fw-bold">Rocket TrxID</label>
                                        <input type="text" name="rocket_transaction_id" id="rocket_transaction_id"
                                            class="form-control" value="{{ $order->rocket_transaction_id }}">
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label for="rocket_charge" class="form-label fw-bold">Rocket Charge</label>
                                        <input type="number" name="rocket_charge" id="rocket_charge"
                                            class="form-control" value="{{ $order->rocket_charge }}" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary update-order"
                                    {{ $order->status === 'delivered' ? 'disabled' : '' }}>
                                    <i class="fas fa-save me-1"></i> Update Order
                                </button>
                                @if (!in_array($order->status, ['delivered']))
                                    <a href="" class="btn btn-outline-danger ms-2"
                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                        <i class="fas fa-times-circle me-1"></i> Cancel Order
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i> Order Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-0">Order Placed</h6>
                                    <small class="text-muted">{{ $order->created_at->format('d M, Y h:i A') }}</small>
                                    <p class="mt-2">Order #{{ $order->id }} was placed by {{ $order->name }}</p>
                                </div>
                            </div>

                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'phone_not_rcv' => 'secondary',
                                    'follow_up' => 'primary',
                                    'on_hold' => 'dark',
                                    'shipped' => 'info',
                                    'ready_for_delivery' => 'primary',
                                    'delivered' => 'success',
                                    'canceled' => 'danger',
                                ];

                                $statusIcons = [
                                    'pending' => 'clock',
                                    'processing' => 'cog',
                                    'phone_not_rcv' => 'phone-slash',
                                    'follow_up' => 'phone',
                                    'on_hold' => 'pause-circle',
                                    'shipped' => 'shipping-fast',
                                    'ready_for_delivery' => 'box',
                                    'delivered' => 'check-circle',
                                    'canceled' => 'times-circle',
                                ];

                                // If status_updates exists, use it, otherwise create a timeline based on current status
                                $timelineItems = [];

                                if ($order->status_updates) {
                                    $timelineItems = json_decode($order->status_updates);
                                } else {
                                    // Create a simulated timeline based on current status
                                    $allStatuses = [
                                        'pending',
                                        'processing',
                                        'phone_not_rcv',
                                        'follow_up',
                                        'on_hold',
                                        'shipped',
                                        'ready_for_delivery',
                                        'delivered',
                                        'canceled',
                                    ];

                                    $currentStatusIndex = array_search($order->status, $allStatuses);

                                    if ($currentStatusIndex !== false) {
                                        // If order is canceled, only show that
                                        if ($order->status === 'canceled') {
                                            $timelineItems[] = (object) [
                                                'status' => 'canceled',
                                                'date' => $order->updated_at,
                                                'note' => 'Order was canceled',
                                            ];
                                        } else {
                                            // For other statuses, show progression up to current status
                                            for ($i = 0; $i <= $currentStatusIndex; $i++) {
                                                if ($allStatuses[$i] !== 'canceled') {
                                                    // Skip canceled if we're on a different path
                    $timelineItems[] = (object) [
                        'status' => $allStatuses[$i],
                        'date' =>
                            $i === $currentStatusIndex ? $order->updated_at : null,
                        'note' =>
                            'Status updated to ' .
                            ucfirst(str_replace('_', ' ', $allStatuses[$i])),
                                                    ];
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp

                            @foreach ($timelineItems as $update)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-{{ $statusColors[$update->status] ?? 'secondary' }}">
                                        <i class="fas fa-{{ $statusIcons[$update->status] ?? 'circle' }} text-white position-absolute"
                                            style="font-size: 8px; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">
                                            <span
                                                class="badge bg-{{ $statusColors[$update->status] ?? 'secondary' }} me-2">
                                                <i
                                                    class="fas fa-{{ $statusIcons[$update->status] ?? 'circle' }} me-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $update->status)) }}
                                            </span>
                                            @if (isset($update->user_name))
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-user me-1"></i> Updated by {{ $update->user_name }}
                                                </small>
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            {{ isset($update->date) ? \Carbon\Carbon::parse($update->date)->format('d M, Y h:i A') : 'Date not recorded' }}
                                        </small>

                                        @if (isset($update->note) && !empty($update->note))
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <i class="fas fa-quote-left text-muted me-1"></i>
                                                <span>{{ $update->note }}</span>
                                                <i class="fas fa-quote-right text-muted ms-1"></i>
                                            </div>
                                        @endif

                                        @if ($update->status === 'shipped')
                                            <p class="mt-2 text-muted">
                                                <i class="fas fa-truck me-1"></i>
                                                Package has been shipped and is on the way.
                                            </p>
                                        @elseif($update->status === 'delivered')
                                            <p class="mt-2 text-success">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Order successfully delivered to {{ $order->name }}.
                                            </p>
                                        @elseif($update->status === 'canceled')
                                            <p class="mt-2 text-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Order has been canceled.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if (count($timelineItems) === 0)
                                <div class="text-center text-muted my-4">
                                    <i class="fas fa-info-circle me-1"></i> No status updates available for this order.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            left: 15px;
            height: 100%;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            top: 5px;
        }

        .timeline-content {
            padding-bottom: 10px;
            border-bottom: 1px dashed #e9ecef;
        }
    </style>


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Send to Pathao - Simplified
            $('#send-to-pathao').on('click', function() {
                const btn = $(this);
                const orderId = btn.data('order-id');
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
                
                $.ajax({
                    url: "{{ route('admin.pathao.send') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message || 'Order sent to Pathao successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                            btn.prop('disabled', false).html('<i class="fas fa-shipping-fast"></i> Send to Pathao');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while sending to Pathao. Please try again.');
                        btn.prop('disabled', false).html('<i class="fas fa-shipping-fast"></i> Send to Pathao');
                    }
                });
            });

            // Send to Steadfast - Simplified
            $('#send-to-steadfast').on('click', function() {
                const btn = $(this);
                const orderId = btn.data('order-id');
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
                
                $.ajax({
                    url: "{{ route('admin.steadfast.send') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message || 'Order sent to Steadfast successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                            btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send to Steadfast');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while sending to Steadfast. Please try again.');
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send to Steadfast');
                    }
                });
            });

            $('#check-courier-status').on('click', function() {
                $.get("{{ route('admin.orders.courierStatus', $order->id) }}", function(response) {
                    if (response.success) {
                        $('#courier-status-result').text('Status: ' + response.status
                            .order_status_slug);
                    } else {
                        $('#courier-status-result').text('Error: ' + response.message);
                    }
                });
            });

            // Initialize payment type fields visibility
            togglePaymentAmountFields();
        });

        // Payment Type Functions
        function togglePaymentAmountFields() {
            const paymentType = $('#payment_type').val();
            const paidInput = $('#paid_amount');
            const dueInput = $('#due_amount');

            if (paymentType === 'full_paid') {
                // For full paid, set paid_amount = total and due = 0
                const total = {{ $order->total ?? 0 }};
                paidInput.val(total.toFixed(2));
                dueInput.val('0.00');
            } else if (paymentType === 'due') {
                // For due, set paid_amount = 0 and due = total
                const total = {{ $order->total ?? 0 }};
                paidInput.val('0.00');
                dueInput.val(total.toFixed(2));
            }
            // For partial, let user enter values manually
        }

        function calculateDueFromPaid() {
            const total = {{ $order->total ?? 0 }};
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            const dueAmount = Math.max(0, total - paidAmount);
            $('#due_amount').val(dueAmount.toFixed(2));

            // Auto-update payment type based on amounts
            if (paidAmount >= total) {
                $('#payment_type').val('full_paid');
            } else if (paidAmount <= 0) {
                $('#payment_type').val('due');
            } else {
                $('#payment_type').val('partial');
            }
        }
    </script>
    <input type="hidden" id="laravel-csrf-token" value="{{ csrf_token() }}">
    
    <script>
        (function($) {
            'use strict';

            const productSearchUrl = "{{ route('admin.products.search') }}";
            const productOptionsTemplate = "{{ route('admin.orders.product-options', ['product' => '__ID__']) }}";
            const initializedModals = new WeakSet();

            function escapeHtml(text) {
                if (text === null || text === undefined) {
                    return '';
                }
                return $('<div>').text(text).html();
            }

            function formatCurrency(value) {
                const numeric = parseFloat(value);
                if (Number.isNaN(numeric)) {
                    return '0.00';
                }
                return numeric.toFixed(2);
            }

            function initProductModal(modal) {
                if (initializedModals.has(modal)) {
                    return;
                }
                initializedModals.add(modal);

                const $modal = $(modal);
                const $searchInput = $modal.find('.product-search-input');
                if (!$searchInput.length) {
                    return;
                }

                const $hiddenProductId = $modal.find('input[name="product_id"]');
                const $results = $modal.find('.product-search-results');
                const $selectedHint = $modal.find('.selected-product-hint');
                const $variationGroup = $modal.find('.variation-group');
                const $variationSelect = $modal.find('.product-variation-select');
                const $combinationInfo = $modal.find('.combination-price-info');
                const $priceInput = $modal.find('.price-input');
                const $priceWarning = $modal.find('.price-warning');
                const $quantityInput = $modal.find('.quantity-input');

                let searchTimeout = null;
                let suppressPriceFlag = false;

                function setSelectedProductHint(product) {
                    if (product && product.id) {
                        $selectedHint.text('Selected product #' + product.id);
                    } else {
                        $selectedHint.text('No product selected yet');
                    }
                }

                function setExpectedPrice(value) {
                    if (value === null || value === undefined || value === '') {
                        $modal.data('expectedPrice', null);
                    } else {
                        $modal.data('expectedPrice', parseFloat(value));
                    }
                }

                function getExpectedPrice() {
                    const selectedOption = $variationSelect.length ? $variationSelect.find('option:selected') : null;
                    if (selectedOption && selectedOption.val()) {
                        const effective = parseFloat(selectedOption.data('effective-price'));
                        if (!Number.isNaN(effective)) {
                            return effective;
                        }
                    }
                    const expected = $modal.data('expectedPrice');
                    if (!Number.isNaN(expected)) {
                        return expected;
                    }
                    return null;
                }

                function setPrice(value, markManual = false) {
                    suppressPriceFlag = true;
                    if (value === null || value === undefined || value === '') {
                        $priceInput.val('');
                    } else {
                        const numeric = parseFloat(value);
                        if (!Number.isNaN(numeric)) {
                            $priceInput.val(numeric.toFixed(2));
                        } else {
                            $priceInput.val(value);
                        }
                    }
                    suppressPriceFlag = false;
                    if (!markManual) {
                        $priceInput.data('manual', false);
                    }
                }

                function updatePriceWarning() {
                    const expectedPrice = getExpectedPrice();
                    const currentValue = parseFloat($priceInput.val());

                    if (Number.isNaN(expectedPrice) || expectedPrice === null || Number.isNaN(currentValue)) {
                        $priceWarning.addClass('d-none');
                        return;
                    }

                    if (Math.abs(currentValue - expectedPrice) > 0.01) {
                        $priceWarning.removeClass('d-none');
                    } else {
                        $priceWarning.addClass('d-none');
                    }
                }

                function renderCombinationInfo(option) {
                    if (!option || !option.val()) {
                        const productData = $modal.data('productData');
                        if (productData && productData.default_price) {
                            $combinationInfo.html(
                                '<span>Regular Price: ৳' + formatCurrency(productData.default_price) + '</span>'
                            );
                            setExpectedPrice(productData.default_price);
                            if (!$priceInput.data('manual')) {
                                setPrice(productData.default_price);
                            }
                            updatePriceWarning();
                        } else {
                            $combinationInfo.empty();
                            setExpectedPrice(null);
                            updatePriceWarning();
                        }
                        return;
                    }

                    const regularPrice = parseFloat(option.data('regular-price'));
                    const offerPrice = parseFloat(option.data('offer-price'));
                    const effectivePrice = parseFloat(option.data('effective-price'));
                    const hasOffer = option.data('has-offer') === 1 || option.data('has-offer') === '1';

                    let html = '';
                    if (hasOffer && !Number.isNaN(offerPrice)) {
                        html = '<span class="text-success">Offer Price: ৳' + formatCurrency(offerPrice) + '</span>';
                        if (!Number.isNaN(regularPrice)) {
                            html += '<span class="text-muted ms-2"><del>Regular: ৳' + formatCurrency(regularPrice) + '</del></span>';
                        }
                    } else if (!Number.isNaN(effectivePrice)) {
                        html = '<span>Regular Price: ৳' + formatCurrency(effectivePrice) + '</span>';
                    }

                    $combinationInfo.html(html);

                    if (!Number.isNaN(effectivePrice)) {
                        setExpectedPrice(effectivePrice);
                        if (!$priceInput.data('manual')) {
                            setPrice(effectivePrice);
                        }
                    } else {
                        setExpectedPrice(null);
                    }
                    updatePriceWarning();
                }

                function buildResultItem(product) {
                    const hasImage = Boolean(product.thumb_image_url);
                    const price = product.offer_price ?? product.regular_price ?? product.default_price ?? null;
                    const priceHtml = price !== null && price !== undefined
                        ? '৳' + formatCurrency(price)
                        : 'Price unavailable';
                    const statusLabel = product.offer_price ? 'Offer' : 'Regular';
                    const productType = product.product_type ? product.product_type.toUpperCase() : 'PRODUCT';

                    const $item = $('<button type="button" class="list-group-item list-group-item-action d-flex align-items-center gap-3"></button>');
                    const $media = hasImage
                        ? $('<img>', { src: product.thumb_image_url, alt: product.title, class: 'product-search-thumb' })
                        : $('<div class="product-search-thumb bg-light d-flex align-items-center justify-content-center text-muted"><i class="fas fa-image"></i></div>');

                    const $details = $('<div class="product-search-details"></div>');
                    $details.append('<span class="product-search-title">#' + product.id + ' • ' + escapeHtml(product.title) + '</span>');
                    $details.append('<span class="product-search-meta">' + priceHtml + ' • ' + statusLabel + ' • ' + productType + '</span>');

                    $item.append($media).append($details);
                    $item.data('product', product);

                    return $item;
                }

                function renderResults(products) {
                    $results.empty();

                    if (!products.length) {
                        $results
                            .append('<div class="list-group-item text-muted py-3 text-center">No products found</div>')
                            .data('hasResults', false)
                            .show();
                        return;
                    }

                    products.forEach(function(product) {
                        const $item = buildResultItem(product);
                        $results.append($item);
                    });

                    $results.data('hasResults', true).show();
                }

                function fetchProducts(query, initial = false) {
                    const params = { limit: 10 };
                    if (initial) {
                        params.initial = 1;
                    }
                    if (query && query.length) {
                        params.query = query;
                    } else if (!initial) {
                        return;
                    }

                    $results
                        .html('<div class="list-group-item py-3 text-center"><i class="fas fa-spinner fa-spin me-2"></i>Searching products...</div>')
                        .show();

                    $.get(productSearchUrl, params)
                        .done(function(response) {
                            const products = Array.isArray(response.products) ? response.products : [];
                            renderResults(products);
                        })
                        .fail(function() {
                            $results
                                .html('<div class="list-group-item text-danger py-3 text-center">Failed to load products</div>')
                                .data('hasResults', false)
                                .show();
                        });
                }

                function loadProductOptions(productId, options = {}) {
                    const settings = $.extend({
                        preservePrice: false
                    }, options || {});

                    if (!productId) {
                        $variationGroup.addClass('d-none');
                        $variationSelect.empty();
                        $modal.removeData('productData');
                        setExpectedPrice(null);
                        updatePriceWarning();
                        return;
                    }

                    const url = productOptionsTemplate.replace('__ID__', productId);
                    $variationGroup.removeClass('d-none');
                    $variationSelect.html('<option value="">Loading variations...</option>');

                    $.get(url)
                        .done(function(response) {
                            if (!response.success || !response.product) {
                                throw new Error('Invalid response');
                            }

                            const productData = response.product;
                            $modal.data('productData', productData);

                            const variations = Array.isArray(productData.variations) ? productData.variations : [];

                            if (!variations.length || productData.product_type !== 'variable') {
                                $variationGroup.addClass('d-none');
                                $variationSelect.empty();
                                const numericDefault = parseFloat(productData.default_price);
                                setExpectedPrice(!Number.isNaN(numericDefault) ? numericDefault : null);
                                if (!settings.preservePrice && !Number.isNaN(numericDefault)) {
                                    setPrice(numericDefault);
                                }
                                if (!Number.isNaN(numericDefault)) {
                                    $combinationInfo.html('<span>Regular Price: ৳' + formatCurrency(numericDefault) + '</span>');
                                } else {
                                    $combinationInfo.empty();
                                }
                                updatePriceWarning();
                                return;
                            }

                            $variationGroup.removeClass('d-none');
                            $variationSelect.empty().append('<option value="">Select Variation</option>');

                            variations.forEach(function(variation) {
                                const $option = $('<option></option>')
                                    .val(variation.id)
                                    .text(variation.display_name + ' (৳' + formatCurrency(variation.effective_price ?? variation.offer_price ?? variation.regular_price ?? 0) + ')')
                                    .attr('data-regular-price', variation.regular_price ?? '')
                                    .attr('data-offer-price', variation.offer_price ?? '')
                                    .attr('data-effective-price', variation.effective_price ?? variation.offer_price ?? variation.regular_price ?? '')
                                    .attr('data-has-offer', variation.has_offer ? 1 : 0);

                                $variationSelect.append($option);
                            });

                            const selectedValue = $variationSelect.data('selected');
                            if (selectedValue) {
                                $variationSelect.val(selectedValue);
                            }

                            if (!$variationSelect.val()) {
                                setExpectedPrice(productData.default_price);
                                if (!settings.preservePrice && productData.default_price) {
                                    setPrice(productData.default_price);
                                }
                            }

                            renderCombinationInfo($variationSelect.find('option:selected'));
                        })
                        .fail(function() {
                            $variationGroup.addClass('d-none');
                            $variationSelect.empty();
                            $combinationInfo.html('<span class="text-danger">Failed to load variation data</span>');
                            setExpectedPrice(null);
                            updatePriceWarning();
                        });
                }

                function selectProduct(product) {
                    if (!product || !product.id) {
                        return;
                    }
                    $hiddenProductId.val(product.id);
                    $searchInput.val('#' + product.id + ' • ' + product.title);
                    $modal.data('selectedProduct', product);
                    $priceInput.data('manual', false);
                    setSelectedProductHint(product);
                    if (product.default_price !== undefined && product.default_price !== null) {
                        setExpectedPrice(product.default_price);
                        if (!Number.isNaN(parseFloat(product.default_price)) && !$priceInput.data('manual')) {
                            setPrice(product.default_price);
                        }
                        updatePriceWarning();
                    }
                    loadProductOptions(product.id, { preservePrice: false });
                    $results.hide();
                }

                $searchInput.on('focus', function() {
                    const query = $(this).val().trim();
                    if (query.length >= 2) {
                        if ($results.children().length === 0) {
                            fetchProducts(query, false);
                        } else {
                            $results.show();
                        }
                    } else {
                        fetchProducts('', true);
                    }
                });

                $searchInput.on('input', function() {
                    const query = $(this).val().trim();
                    clearTimeout(searchTimeout);

                    if (query.length < 2) {
                        $results.hide().empty().data('hasResults', false);
                        return;
                    }

                    searchTimeout = setTimeout(function() {
                        fetchProducts(query, false);
                    }, 250);
                });

                $results.on('click', '.list-group-item', function() {
                    const product = $(this).data('product');
                    selectProduct(product);
                });

                $(document).on('click', function(event) {
                    if (!$modal.is(event.target) && $modal.has(event.target).length === 0) {
                        $results.hide();
                    }
                });

                $variationSelect.on('change', function() {
                    const $selected = $(this).find('option:selected');
                    renderCombinationInfo($selected);
                });

                $priceInput.on('input', function() {
                    if (!suppressPriceFlag) {
                        $(this).data('manual', true);
                    }
                    updatePriceWarning();
                });

                if ($quantityInput.length) {
                    $quantityInput.on('input', function() {
                        if (parseInt($(this).val(), 10) < 1 || Number.isNaN(parseInt($(this).val(), 10))) {
                            $(this).val(1);
                        }
                    });
                }

                $modal.on('hidden.bs.modal', function() {
                    $results.hide();
                });

                const initialProductId = $hiddenProductId.val();
                if (initialProductId) {
                    setSelectedProductHint({ id: initialProductId });
                    loadProductOptions(initialProductId, { preservePrice: true });
                }
            }

            function initAllModals() {
                $('.order-item-modal').each(function() {
                    initProductModal(this);
                });
            }

            $(document).ready(function() {
                initAllModals();

                $(document).on('shown.bs.modal', '.order-item-modal', function() {
                    initProductModal(this);
                });
            });
        })(jQuery);
    </script>
@endsection
