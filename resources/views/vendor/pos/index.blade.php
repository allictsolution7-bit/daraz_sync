@extends('vendor.layouts.app')

@section('title', 'Reseller POS System')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<style>
    /* POS Design override */
    :root {
        --pos-primary: #10b981; /* Success/Green theme for reseller */
        --pos-primary-hover: #059669;
        --pos-secondary: #06b6d4;
        --pos-accent: #6366f1;
        --pos-dark: #0f172a;
        --pos-light-bg: #f8fafc;
        --pos-card-bg: #ffffff;
        --pos-border: #e2e8f0;
        --pos-radius: 16px;
    }

    .pos-page-wrapper {
        background: #f1f5f9;
        margin: -20px;
        padding: 24px;
        min-height: calc(100vh - 60px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    .pos-header-card {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #10b981 100%);
        color: white;
        border-radius: var(--pos-radius);
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.15);
        position: relative;
        overflow: hidden;
    }

    .pos-title-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #ffffff;
    }

    .pos-title-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #a7f3d0;
    }

    .product-search-box {
        background: #ffffff;
        border-radius: var(--pos-radius);
        padding: 16px;
        border: 1px solid var(--pos-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        margin-bottom: 16px;
    }

    .pos-category-pills-wrap {
        margin-bottom: 20px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 8px;
    }

    .pos-category-pills {
        display: flex;
        gap: 8px;
    }

    .pos-pill-btn {
        background: #ffffff;
        border: 1px solid var(--pos-border);
        color: #64748b;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pos-pill-btn.active, .pos-pill-btn:hover {
        background: var(--pos-primary);
        border-color: var(--pos-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        max-height: calc((230px * 4) + (16px * 3)); /* Height of roughly 4 rows */
        overflow-y: auto;
        padding-right: 4px;
    }

    @media (max-width: 1200px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            max-height: calc((230px * 5) + (16px * 4)); /* Adjusted for 3 columns */
        }
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }

    .pos-product-card {
        background: #ffffff;
        border: 1px solid var(--pos-border);
        border-radius: var(--pos-radius);
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .pos-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .pos-product-card.out-of-stock {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .pos-card-img-wrap {
        width: 100%;
        padding-top: 100%;
        position: relative;
        background: #f8fafc;
        border-bottom: 1px solid var(--pos-border);
    }

    .pos-card-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pos-card-img-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 32px;
        color: #cbd5e1;
    }

    .pos-card-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .pos-card-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--pos-dark);
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 38px;
        line-height: 1.4;
    }

    .pos-card-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pos-card-price {
        font-size: 15px;
        font-weight: 800;
        color: var(--pos-primary);
    }

    .pos-right-card {
        background: #ffffff;
        border-radius: var(--pos-radius);
        border: 1px solid var(--pos-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .pos-section-header {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
        color: white;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cart-items {
        max-height: 480px;
        overflow-y: auto;
        padding: 12px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--pos-border);
    }

    .cart-item-info {
        flex-grow: 1;
        min-width: 0;
    }

    .cart-item-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--pos-dark);
        white-space: nowrap;
        overflow: text-overflow;
        text-overflow: ellipsis;
    }

    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
    }

    .quantity-btn {
        background: #f1f5f9;
        border: none;
        padding: 3px 8px;
        cursor: pointer;
        font-weight: 800;
    }

    .quantity-input {
        border: none;
        width: 32px;
        text-align: center;
        font-weight: 700;
        font-size: 12px;
    }

    .cart-summary {
        background: #f8fafc;
        border-top: 1px solid var(--pos-border);
        padding: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .summary-total {
        font-size: 16px;
        font-weight: 800;
        color: var(--pos-primary);
        border-top: 1px dashed #cbd5e1;
        padding-top: 8px;
        margin-top: 8px;
        margin-bottom: 0;
    }

    .payment-form, .customer-form {
        padding: 20px;
    }

    .payment-form label, .customer-form label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 4px;
        display: block;
    }

    .payment-form input, .payment-form select, .customer-form input, .customer-form textarea {
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
    }

    .btn-checkout {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 14px 20px;
        font-size: 15px;
        font-weight: 800;
        border-radius: var(--pos-radius);
        width: 100%;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        margin-top: 16px;
    }

    .variation-badge {
        font-size: 11px;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')
<div class="pos-page-wrapper">
    <div class="pos-header-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="pos-title-icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <div>
                <h3 class="pos-title-badge mb-0">Reseller POS</h3>
                <p class="mb-0 text-white-50 small">Quickly place catalog orders directly to your admin</p>
            </div>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5" onclick="clearCart()">
                <i class="fas fa-trash-can me-1"></i> Clear Cart
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Products Grid catalog -->
        <div class="col-lg-8 col-md-7">
            <div class="product-search-box">
                <div class="row g-2">
                    <div class="col-md-9 col-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control" id="productSearch" placeholder="Search admin catalog products...">
                        </div>
                    </div>
                    <div class="col-md-3 col-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-list-numeric text-muted" style="font-size: 11px;"></i></span>
                            <select class="form-select" id="pageSizeSelect" style="font-size: 13px; font-weight: 600;">
                                <option value="25" selected>25 items</option>
                                <option value="50">50 items</option>
                                <option value="100">100 items</option>
                                <option value="200">200 items</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories pills -->
            <div class="pos-category-pills-wrap">
                <div class="pos-category-pills" id="categoryPills">
                    <button type="button" class="pos-pill-btn active" data-cat-id="">All Products</button>
                    @foreach($categories as $category)
                        <button type="button" class="pos-pill-btn" data-cat-id="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>

            <div class="product-grid" id="productsGrid">
                <div class="text-center py-5 w-100">
                    <i class="fas fa-spinner fa-spin fa-2x text-success mb-2"></i>
                    <p class="text-muted small">Loading catalog products...</p>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-3 bg-white p-3 border rounded-3" id="productsPagination" style="display: none;">
                <div class="text-muted small fw-semibold" id="paginationInfo"></div>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary btn-sm" id="prevPageBtn">Previous</button>
                    <button class="btn btn-outline-secondary btn-sm" id="nextPageBtn">Next</button>
                </div>
            </div>
        </div>

        <!-- Checkout and Cart -->
        <div class="col-lg-4 col-md-5">
            <!-- Cart card -->
            <div class="pos-right-card">
                <div class="pos-section-header">
                    <span><i class="fas fa-shopping-cart me-2"></i> Sales Cart</span>
                    <span class="badge bg-white text-dark px-2 py-0.5"><span id="cartItemCount">0</span> items</span>
                </div>
                <div class="cart-items" id="cartItems">
                    <div class="text-center py-5" id="emptyCartState">
                        <i class="fas fa-shopping-basket fa-2x text-muted opacity-50 mb-2"></i>
                        <p class="text-muted small mb-0">No items added to cart yet</p>
                    </div>
                </div>
                <div class="cart-summary" id="cartSummary" style="display: none;">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">৳0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <input type="number" id="shippingAmount" class="form-control form-control-sm text-end" style="width: 80px;" min="0" value="0" onchange="updateTotals()">
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <input type="number" id="discountAmount" class="form-control form-control-sm text-end" style="width: 80px;" min="0" value="0" onchange="updateTotals()">
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="total">৳0.00</span>
                    </div>
                    <div class="summary-row mt-2 pt-2 border-top">
                        <span>Amount Paid (Optional):</span>
                        <input type="number" id="amountPaid" class="form-control form-control-sm text-end" style="width: 100px; font-weight: bold; color: var(--pos-primary);" min="0" value="0" oninput="$(this).data('autofilled', false); updateTotals();" onchange="$(this).data('autofilled', false); updateTotals();">
                    </div>
                    <div class="summary-row">
                        <span class="fw-bold text-danger">Due Amount:</span>
                        <span id="dueAmount" class="fw-bold text-danger">৳0.00</span>
                    </div>
                </div>
            </div>

            <!-- Customer and Payment Form -->
            <div class="pos-right-card mt-3">
                <div class="pos-section-header">
                    <span><i class="fas fa-user-tag me-2"></i> Shipping & Payment Info</span>
                </div>
                <div class="customer-form pb-0">
                    <div class="row g-2">
                        <div class="col-12 mb-1">
                            <label class="fw-semibold small text-muted">Select Customer (Optional)</label>
                            <select class="form-select form-select-sm" id="posCustomerSelect">
                                <option value="">-- New / Walk-in Customer --</option>
                                @foreach($customers as $cust)
                                    @php
                                        $masked = strlen($cust->phone) > 8 ? substr($cust->phone, 0, 4) . str_repeat('*', strlen($cust->phone) - 6) . substr($cust->phone, -2) : (strlen($cust->phone) > 4 ? substr($cust->phone, 0, 2) . str_repeat('*', strlen($cust->phone) - 4) . substr($cust->phone, -2) : str_repeat('*', strlen($cust->phone)));
                                    @endphp
                                    <option value="{{ $cust->id }}" 
                                            data-name="{{ $cust->name }}" 
                                            data-phone="{{ $masked }}" 
                                            data-address="{{ $cust->address }}" 
                                            data-city="{{ $cust->city }}">
                                        {{ $cust->name }} ({{ $masked }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Customer Name *</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Name" required>
                        </div>
                        <div class="col-md-6">
                            <label>Customer Phone *</label>
                            <input type="text" class="form-control" id="customerPhone" placeholder="Phone" required>
                        </div>
                        <div class="col-12 mt-2">
                            <label>Address *</label>
                            <textarea class="form-control" id="customerAddress" rows="2" placeholder="Delivery Address" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="payment-form pt-2">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label>City</label>
                            <input type="text" class="form-control" id="customerCity" placeholder="City">
                        </div>
                        <div class="col-md-6">
                            <label>Payment Method *</label>
                            <select class="form-select" id="paymentMethod">
                                @foreach($paymentMethods as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Self-Delivery Wallet System -->
                        <div class="col-12 mt-3 p-3 bg-light rounded-3 border" id="selfDeliveryWrapper">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark fs-7"><i class="fas fa-truck-ramp-box me-1.5 text-primary"></i> Self-Delivery Option</span>
                                <div class="form-check form-switch p-0 m-0">
                                    <input class="form-check-input ms-0" type="checkbox" id="selfDeliveryToggle" onchange="toggleSelfDelivery()">
                                </div>
                            </div>
                            <p class="text-muted small mb-2" style="font-size: 0.72rem; line-height: 1.4;">
                                Enable this option to dispatch the order yourself using Steadfast/Pathao. This will deduct the reseller product cost from your wallet and directly approve the order.
                            </p>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>Reseller Cost of Items:</span>
                                <span class="fw-bold text-dark" id="resellerCostTotal">৳0.00</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small">
                                <span>Your Wallet Balance:</span>
                                <span class="fw-bold text-primary" id="walletBalanceVal">৳{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                            </div>
                            <div class="alert alert-danger p-2 mt-2 mb-0 d-none" id="insufficientBalanceAlert" style="font-size: 0.7rem; border-radius: 6px;">
                                <i class="fas fa-triangle-exclamation me-1"></i> Insufficient wallet balance for Self-Delivery.
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <label>Remarks</label>
                            <textarea class="form-control" id="orderNotes" rows="2" placeholder="Optional notes for admin"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn-checkout" id="checkoutBtn" onclick="processOrder()" disabled>
                <i class="fas fa-check-circle me-1"></i> Submit Reseller Order
            </button>
        </div>
    </div>
</div>

<!-- Variation Selector Modal -->
<div class="modal fade" id="variationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-tags me-2 text-warning"></i> Select Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="variationModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success font-weight-bold" onclick="addSelectedVariation()">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script>
let cart = [];
let currentPage = 1;
let lastPage = 1;
let perPage = 25;
let currentProduct = null;

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    searchProducts();

    $('#paymentMethod').on('change', function() {
        $('#amountPaid').val('');
        $('#amountPaid').data('autofilled', true);
        updateTotals();
    });

    $('#productSearch').on('input', function() {
        searchProducts(1);
    });

    $('#pageSizeSelect').on('change', function() {
        perPage = parseInt($(this).val()) || 25;
        searchProducts(1);
    });

    $(document).on('click', '.pos-pill-btn', function() {
        $('.pos-pill-btn').removeClass('active');
        $(this).addClass('active');
        searchProducts(1);
    });

    $('#prevPageBtn').click(function() {
        if (currentPage > 1) searchProducts(currentPage - 1);
    });
    $('#nextPageBtn').click(function() {
        if (currentPage < lastPage) searchProducts(currentPage + 1);
    });

    $('#customerName, #customerPhone, #customerAddress').on('input', validateCheckout);

    $('#posCustomerSelect').on('change', function() {
        const option = $(this).find('option:selected');
        if (option.val() === '') {
            $('#customerName').val('');
            $('#customerPhone').val('').prop('readonly', false);
            $('#customerAddress').val('');
            $('#customerCity').val('');
        } else {
            $('#customerName').val(option.data('name'));
            $('#customerPhone').val(option.data('phone')).prop('readonly', true);
            $('#customerAddress').val(option.data('address'));
            $('#customerCity').val(option.data('city'));
        }
        validateCheckout();
    });
});

function searchProducts(page = 1) {
    currentPage = page;
    const search = $('#productSearch').val();
    const catId = $('.pos-pill-btn.active').data('cat-id');

    $('#productsGrid').html('<div class="text-center py-5 w-100"><i class="fas fa-spinner fa-spin fa-2x text-success"></i><p class="text-muted mt-2">Loading Products...</p></div>');

    $.get('{{ route("vendor.pos.search-products") }}', {
        search: search,
        category_id: catId,
        page: page,
        per_page: perPage
    })
    .done(function(response) {
        renderProducts(response.products);
        renderPagination(response.meta);
    })
    .fail(function() {
        toastr.error('Failed to load products');
    });
}

function renderProducts(products) {
    const grid = $('#productsGrid');
    if (!products || products.length === 0) {
        grid.html('<div class="text-center py-5 w-100 bg-white rounded-4 border"><i class="fas fa-box-open fa-3x text-muted mb-2"></i><p class="text-muted fw-bold">No Products Available</p></div>');
        return;
    }

    let html = '';
    products.forEach(p => {
        const isOutOfStock = p.computed_stock_status === 'out_of_stock';
        const imgHtml = p.image 
            ? `<img src="${p.image}" class="pos-card-img">`
            : `<div class="pos-card-img-placeholder"><i class="fas fa-image"></i></div>`;

        html += `
            <div class="pos-product-card ${isOutOfStock ? 'out-of-stock' : ''}" onclick="onProductClick(${JSON.stringify(p).replace(/"/g, '&quot;')})">
                <div class="pos-card-img-wrap">${imgHtml}</div>
                <div class="pos-card-body">
                    <div class="pos-card-title">${p.title}</div>
                    <div class="pos-card-footer d-flex flex-column gap-2 mt-auto">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span class="pos-card-price text-success fw-bold" style="font-size: 1.05rem;" title="Selling Price">৳${parseFloat(p.price).toFixed(0)}</span>
                            <span class="badge ${isOutOfStock ? 'bg-danger' : 'bg-success bg-opacity-10 text-success'} font-weight-bold" style="font-size:0.65rem; padding: 4px 8px; border-radius: 6px;">
                                ${isOutOfStock ? 'Out of Stock' : 'In Stock'}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center w-100 border-top pt-2" style="font-size: 0.75rem;">
                            <span class="text-muted">Cost: ৳${parseFloat(p.reseller_price).toFixed(0)}</span>
                            <span class="badge bg-warning bg-opacity-10 text-dark fw-bold border-0 px-2 py-1" style="font-size: 0.68rem; border-radius: 4px;" title="Profit margin">+৳${parseFloat(p.price - p.reseller_price).toFixed(0)} profit</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    grid.html(html);
}

function renderPagination(meta) {
    const pag = $('#productsPagination');
    if (!meta || meta.total <= perPage) {
        pag.hide();
        return;
    }
    currentPage = meta.current_page;
    lastPage = meta.last_page;
    $('#paginationInfo').text(`Showing ${(currentPage-1)*perPage+1}-${Math.min(meta.total, currentPage*perPage)} of ${meta.total}`);
    $('#prevPageBtn').prop('disabled', currentPage <= 1);
    $('#nextPageBtn').prop('disabled', currentPage >= lastPage);
    pag.show();
}

function onProductClick(product) {
    if (product.computed_stock_status === 'out_of_stock') {
        toastr.warning('This product is out of stock');
        return;
    }

    if (product.product_type === 'variable') {
        currentProduct = product;
        let html = '<div class="list-group">';
        product.variations.forEach(v => {
            const varDisabled = !v.in_stock ? 'disabled opacity-50' : '';
            const profit = parseFloat(v.price - v.reseller_price);
            html += `
                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3" ${varDisabled} onclick="addVariationToCart(${v.id}, '${v.display_name.replace(/'/g, "\\'")}', ${v.price}, ${v.reseller_price})">
                    <span class="font-weight-bold text-dark">${v.display_name}</span>
                    <div class="text-end">
                        <span class="text-success font-weight-bold d-block">৳${parseFloat(v.price).toFixed(0)}</span>
                        <small class="text-muted" style="font-size: 0.68rem;">Cost: ৳${parseFloat(v.reseller_price).toFixed(0)} | +৳${profit.toFixed(0)} profit</small>
                    </div>
                </button>
            `;
        });
        html += '</div>';
        $('#variationModalBody').html(html);
        $('#variationModal').modal('show');
    } else {
        addToCart(product.id, null, product.title, product.price, product.reseller_price);
    }
}

function addVariationToCart(comboId, displayName, price, resellerPrice) {
    addToCart(currentProduct.id, comboId, `${currentProduct.title} (${displayName})`, price, resellerPrice);
    $('#variationModal').modal('hide');
}

function addToCart(productId, combinationId, name, price, resellerPrice) {
    const existing = cart.find(item => item.product_id === productId && item.combination_id === combinationId);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({
            product_id: productId,
            combination_id: combinationId,
            name: name,
            price: parseFloat(price),
            reseller_price: parseFloat(resellerPrice) || 0,
            quantity: 1
        });
    }
    updateCartDisplay();
    toastr.success('Item added to cart');
}

function updateCartDisplay() {
    const container = $('#cartItems');
    if (cart.length === 0) {
        container.html('<div class="text-center py-5" id="emptyCartState"><i class="fas fa-shopping-basket fa-2x text-muted opacity-50 mb-2"></i><p class="text-muted small mb-0">No items added to cart yet</p></div>');
        $('#cartSummary').hide();
        validateCheckout();
        return;
    }

    let html = '';
    cart.forEach((item, index) => {
        console.log("Cart item object:", item);
        const profit = Math.max(0, item.price - item.reseller_price) * item.quantity;
        html += `
            <div class="cart-item-card border p-3 rounded-3 mb-2 bg-light">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div class="fw-bold text-dark" style="font-size: 13px; line-height: 1.4; max-width: 80%;">${item.name}</div>
                    <button class="btn btn-sm btn-link text-danger p-0" onclick="removeItem(${index})" title="Remove item">
                        <i class="fas fa-trash-can"></i>
                    </button>
                </div>
                
                <div class="row g-2 align-items-center">
                    <div class="col-6">
                        <div class="d-flex flex-column">
                            <span class="text-muted small" style="font-size: 11px;">Reseller Price</span>
                            <span class="fw-bold text-secondary" style="font-size: 13px;">৳${item.reseller_price.toFixed(0)}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column align-items-end">
                            <span class="text-muted small" style="font-size: 11px;">Selling Price</span>
                            <input type="number" class="form-control form-control-sm text-success fw-bold p-1 text-center" style="width: 85px; height: 28px; font-size: 12px; border: 1px solid var(--pos-border); border-radius: 6px;" value="${Math.ceil(item.price)}" onchange="updateItemPrice(${index}, this.value)" min="0" step="any">
                        </div>
                    </div>
                    
                    <div class="col-6 mt-2">
                        <div class="quantity-control d-flex align-items-center border rounded-3 p-0" style="background:#fff; width: fit-content; height: 28px;">
                            <button class="btn btn-sm px-2 py-0 border-0" onclick="updateQty(${index}, -1)" style="font-size:12px;">-</button>
                            <span class="px-2 fw-semibold" style="font-size:12px; min-width:20px; text-align:center;">${item.quantity}</span>
                            <button class="btn btn-sm px-2 py-0 border-0" onclick="updateQty(${index}, 1)" style="font-size:12px;">+</button>
                        </div>
                    </div>
                    <div class="col-6 mt-2 text-end">
                        <span class="badge bg-warning text-dark font-weight-bold p-2" style="font-size: 11px; border-radius: 6px;">
                            +৳${profit.toFixed(0)} profit
                        </span>
                    </div>
                </div>
            </div>
        `;
    });
    container.html(html);
    $('#cartSummary').show();
    updateTotals();
    validateCheckout();
}

function updateQty(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    updateCartDisplay();
}

function updateItemPrice(index, price) {
    cart[index].price = parseFloat(price) || 0;
    updateCartDisplay();
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function clearCart() {
    cart = [];
    updateCartDisplay();
}

function updateTotals() {
    let sub = 0;
    let resellerCostSum = 0;
    cart.forEach(i => {
        sub += (i.price * i.quantity);
        resellerCostSum += (i.reseller_price * i.quantity);
    });
    
    const shipping = parseFloat($('#shippingAmount').val()) || 0;
    const discount = parseFloat($('#discountAmount').val()) || 0;
    const grand = Math.max(0, sub + shipping - discount);

    // If the user hasn't typed anything yet or if it is marked as autofilled (including initial page load), auto-fill it with the grand total
    let amountPaidVal = $('#amountPaid').val();
    if (amountPaidVal === '' || $('#amountPaid').data('autofilled') === true || $('#amountPaid').data('autofilled') === undefined) {
        $('#amountPaid').val(grand.toFixed(0));
        $('#amountPaid').data('autofilled', true);
    }

    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const due = Math.max(0, grand - amountPaid);

    $('#subtotal').text('৳' + sub.toFixed(0));
    $('#total').text('৳' + grand.toFixed(0));
    $('#dueAmount').text('৳' + due.toFixed(0));
    $('#cartItemCount').text(cart.reduce((a, b) => a + b.quantity, 0));

    // Update reseller cost total UI
    $('#resellerCostTotal').text('৳' + resellerCostSum.toFixed(0));

    // Check if wallet balance supports self delivery
    const walletBalance = parseFloat('{{ auth()->user()->wallet_balance ?? 0 }}');
    if (resellerCostSum > walletBalance) {
        $('#selfDeliveryToggle').prop('checked', false).prop('disabled', true);
        $('#insufficientBalanceAlert').removeClass('d-none');
    } else {
        $('#selfDeliveryToggle').prop('disabled', false);
        $('#insufficientBalanceAlert').addClass('d-none');
    }
}

function toggleSelfDelivery() {
    if ($('#selfDeliveryToggle').is(':checked')) {
        $('#shippingAmount').val('0').prop('disabled', true);
    } else {
        $('#shippingAmount').prop('disabled', false);
    }
    updateTotals();
}

function validateCheckout() {
    const name = $('#customerName').val().trim();
    const phone = $('#customerPhone').val().trim();
    const address = $('#customerAddress').val().trim();
    const valid = cart.length > 0 && name.length > 0 && phone.length > 0 && address.length > 0;
    $('#checkoutBtn').prop('disabled', !valid);
}

function processOrder() {
    if (cart.length === 0) return;
    
    const btn = $('#checkoutBtn');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');

    const total = parseFloat($('#total').text().replace('৳', ''));
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const paymentMethod = $('#paymentMethod').val();
    const isSelfDelivery = $('#selfDeliveryToggle').is(':checked');
    const paymentStatus = (paymentMethod === 'cod' && !isSelfDelivery) ? 'pending' : (amountPaid >= total ? 'paid' : 'pending');

    const orderData = {
        customer_id: $('#posCustomerSelect').val() || null,
        customer_name: $('#customerName').val(),
        customer_phone: $('#customerPhone').val(),
        customer_address: $('#customerAddress').val(),
        customer_city: $('#customerCity').val(),
        payment_method: $('#paymentMethod').val(),
        discount: parseFloat($('#discountAmount').val()) || 0,
        shipping: parseFloat($('#shippingAmount').val()) || 0,
        notes: $('#orderNotes').val(),
        items: cart,
        total: total,
        amount_paid: amountPaid,
        payment_status: paymentStatus,
        delivery_type: $('#selfDeliveryToggle').is(':checked') ? 'self' : 'admin'
    };

    $.post('{{ route("vendor.pos.create-order") }}', orderData)
    .done(function(response) {
        if (response.success) {
            toastr.success(response.message);
            
            // Deduct wallet balance from UI dynamically if self delivery was checked
            if ($('#selfDeliveryToggle').is(':checked')) {
                let cost = 0;
                cart.forEach(i => cost += (i.reseller_price * i.quantity));
                let oldBal = parseFloat('{{ auth()->user()->wallet_balance ?? 0 }}');
                let newBal = Math.max(0, oldBal - cost);
                $('#walletBalanceVal, .wallet-balance-amount').text('৳ ' + newBal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            }

            clearCart();
            $('#posCustomerSelect').val('');
            $('#customerName, #customerPhone, #customerAddress, #customerCity, #orderNotes, #amountPaid').val('');
            $('#customerPhone').prop('readonly', false);
            $('#shippingAmount, #discountAmount').val('0');
            $('#selfDeliveryToggle').prop('checked', false);
            $('#shippingAmount').prop('disabled', false);
        } else {
            toastr.error(response.message);
        }
    })
    .fail(function(xhr) {
        toastr.error(xhr.responseJSON?.message || 'Failed to place POS order');
    })
    .always(function() {
        btn.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> Submit Reseller Order');
    });
}
</script>
@endpush
