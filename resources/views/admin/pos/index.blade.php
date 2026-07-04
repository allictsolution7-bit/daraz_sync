@extends('layouts.master')

@section('title', 'Point of Sale')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<style>
    .pos-container {
        min-height: calc(100vh - 120px);
        height: auto;
        overflow: visible;
        margin-top: -7px;
    }
    
    .pos-left-panel {
        height: auto;
        overflow: visible;
        padding: 10px;
    }
    
    .pos-right-panel {
        height: auto;
        overflow: visible;
        padding: 6px;
        background: #f8f9fa;
    }
    
    .product-search {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        padding-bottom: 4px;
        margin-bottom: 4px;
        border-bottom: 1px solid #eee;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px !important;
        padding: 0px !important;
        max-height: 476px;
    }
    
    .product-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 7px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
    }
    
    .product-card:hover {
        border-color: #197A94;
        box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        transform: translateY(-2px);
    }
    
    .product-card.out-of-stock {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .product-title {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 0px;
        color: #333;
        line-height: 1.2;
    }
    
    .product-price {
        font-size: 16px;
        font-weight: bold;
        color: #28a745;
    }
    
    .product-stock {
        font-size: 12px;
        line-height: 1.2;
        color: #666;
    }
    
    .cart-section {
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
        margin-bottom: 20px;
    }
    
    .cart-header {
        background: #197A94;
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
    }
    
    .cart-items {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .cart-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item-image {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }
    
    .cart-item-details {
        flex: 1;
    }
    
    .cart-item-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .cart-item-variation {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    
    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .quantity-btn {
        background: #f8f9fa;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 16px;
    }
    
    .quantity-btn:hover {
        background: #e9ecef;
    }
    
    .quantity-input {
        border: none;
        width: 50px;
        text-align: center;
        padding: 5px;
    }
    
    .cart-summary {
        padding: 20px;
        border-top: 2px solid #197A94;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .summary-total {
        font-size: 18px;
        font-weight: bold;
        color: #197A94;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
    
    .customer-section {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .customer-header {
        background: #28a745;
        color: white;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
    }
    
    .customer-form {
        padding: 20px;
    }
    
    .payment-section {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .payment-header {
        background: #ffc107;
        color: #212529;
        padding: 15px;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
    }
    
    .payment-form {
        padding: 20px;
    }
    
    .btn-checkout {
        background: #28a745;
        color: white;
        border: none;
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-checkout:hover {
        background: #218838;
        transform: translateY(-1px);
    }
    
    .btn-checkout:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }
    
    .variation-modal .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .variation-option {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .variation-option:hover {
        border-color: #197A94;
        background: #f8f9fa;
    }
    
    .variation-option.selected {
        border-color: #197A94;
        background: #e3f2fd;
    }
    
    .scanner-section {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        margin-top: 5px;
        text-align: center;
        display: none;
    }

    .scanner-section.active {
        display: block;
    }
    
    .scanner-btn {
        background: #6f42c1;
        color: white;
        border: none;
        padding: 7px 10px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .filters-panel.collapsed {
        display: none;
    }

    .scanner-btn-inline {
        display: none;
    }
    
    .scanner-btn:hover {
        background: #5a2d91;
    }
    
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 5px;
    }
    
    .stat-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 3px 6px;
    text-align: center;
}
    
    .stat-value {
        font-size: 20px;
        font-weight: bold;
        color: #197A94;
    }
    
    .stat-label {
    font-size: 12px;
    color: #666;
    margin-top: -6px;
}
    
    .recent-orders {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .recent-order-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }
    
    .recent-order-item:last-child {
        border-bottom: none;
    }
    
    /* QR Scanner Styles */
    #qr-reader {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .empty-cart {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    
    .empty-cart i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        .pos-container {
            height: auto;
        }
        .pos-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .pos-header > .d-flex {
            width: 100%;
            justify-content: space-between;
        }
        .stats-cards {
            width: 100%;
            margin-top: 4px;
        }
        .scanner-section {
            display: block;
        }
        .scanner-btn-header {
            display: none;
        }
        .scanner-btn-inline {
            display: inline-block;
        }
        
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }
        
        .stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    a.pos-menu-item {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center py-2 pos-header">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="mb-0 posmainheader">
                    <i class="fas fa-cash-register text-primary"></i>
                    Point of Sale
                </h4>
                <button class="scanner-btn scanner-toggle-btn scanner-btn-header" type="button" onclick="startScanner()">
                    <i class="fas fa-qrcode"></i> Start QR/Barcode Scanner
                </button>
            </div>
            <!-- <small class="text-muted">Manage in-store and phone sales</small> -->
        </div>
        <!-- Stats Cards -->
        <div class="stats-cards d-flex justify-content-between align-items-center" id="statsCards">
            <div class="stat-card">
                <div class="stat-value" id="todayOrders">0</div>
                <div class="stat-label">Today's Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="todayRevenue">৳0</div>
                <div class="stat-label">Today's Revenue</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="weekOrders">0</div>
                <div class="stat-label">This Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="monthRevenue">৳0</div>
                <div class="stat-label">Monthly Revenue</div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="loadStats()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-outline-secondary btn-sm" onclick="clearCart()">
                <i class="fas fa-trash"></i> Clear Cart
            </button>
        </div>
    </div>



    <div class="row pos-container">
        <!-- Left Panel - Products -->
        <div class="col-md-8 pos-left-panel">
            <!-- Search and Scanner -->
            <div class="product-search">
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-8 col-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="productSearch" 
                                   placeholder="Search products by name, ID, or scan barcode...">
                        </div>
                    </div>
                    <div class="col-md-4 col-12 text-end">
                        <button class="btn btn-outline-secondary btn-sm d-inline-block d-md-none me-1" id="filtersToggleBtn">
                            <i class="fas fa-filter"></i> Show Filters
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-1 mt-md-0" id="clearFiltersBtn">
                            <i class="fas fa-times"></i> Clear Filters
                        </button>
                    </div>
                </div>
                <div id="filtersPanel" class="filters-panel">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select id="primary-category-filter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="subcategory-filter" class="form-select" disabled>
                                <option value="">Select a primary category first</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="third-category-filter" class="form-select" disabled>
                                <option value="">Select a subcategory first</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 m-1">
                        <div class="col-md-4">
                            <select id="stock-status-filter" class="form-select">
                                <option value="">All Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="on_backorder">On Backorder</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="product-type-filter" class="form-select">
                                <option value="">All Types</option>
                                <option value="simple">Simple</option>
                                <option value="variable">Variable</option>
                                <option value="digital">Digital</option>
                                <option value="affiliate">Affiliate</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="low-stock-filter">
                                <label class="form-check-label" for="low-stock-filter">
                                    Show Low Stock Only
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QR/Barcode Scanner -->
                <div class="scanner-section">
                    <button class="scanner-btn scanner-toggle-btn scanner-btn-inline" type="button" onclick="startScanner()">
                        <i class="fas fa-qrcode"></i> Start QR/Barcode Scanner
                    </button>
                    <div id="qr-reader" style="display: none;"></div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="product-grid" id="productsGrid">
                <div class="text-center py-4">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Search for products to add to cart</p>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3" id="productsPagination" style="display: none;">
                <div class="text-muted small" id="paginationInfo"></div>
                <div class="btn-group">
                    <button class="btn btn-outline-secondary btn-sm" id="prevPageBtn">Previous</button>
                    <button class="btn btn-outline-secondary btn-sm" id="nextPageBtn">Next</button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div class="col-md-4 pos-right-panel">
            <!-- Cart Section -->
            <div class="cart-section">
                <div class="cart-header">
                    <i class="fas fa-shopping-cart"></i>
                    Cart (<span id="cartItemCount">0</span> items)
                </div>
                <div class="cart-items" id="cartItems">
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Your cart is empty</p>
                        <small>Add products from the left panel</small>
                    </div>
                </div>
                <div class="cart-summary" id="cartSummary" style="display: none;">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">৳0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>
                            <input type="number" id="discountAmount" class="form-control form-control-sm d-inline-block" 
                                   style="width: 80px;" min="0" step="0.01" value="0" onchange="updateTotals()">
                        </span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>
                            <input type="number" id="shippingAmount" class="form-control form-control-sm d-inline-block" 
                                   style="width: 80px;" min="0" step="0.01" value="0" onchange="updateTotals()">
                        </span>
                    </div>
                    <div class="summary-row" id="paymentChargeRow" style="display: none;">
                        <span id="paymentChargeLabel">Payment Charge:</span>
                        <span id="paymentChargeAmount">৳0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="total">৳0.00</span>
                    </div>
                </div>
            </div>

                        <!-- Payment Section -->
                        <div class="payment-section">
                            <div class="payment-header">
                                <i class="fas fa-credit-card"></i>
                                Payment & Order Details
                            </div>
                            <div class="payment-form">
                                                        <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Order Source *</label>
                                            <select class="form-select" id="orderSource" required>
                                                @foreach($orderSources as $value => $label)
                                                    <option value="{{ $value }}" {{ $value === 'Physical Store' ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Payment Method *</label>
                                            <select class="form-select" id="paymentMethod" required onchange="togglePaymentFields()">
                                                <option value="cod">Cash on Delivery (COD)</option>
                                                <option value="cash" selected>Cash</option>
                                                <option value="card">Card</option>
                                                <option value="bkash">bKash</option>
                                                <option value="nagad">Nagad</option>
                                                <option value="rocket">Rocket</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Payment Gateway Fields -->
                                        <!-- bKash Fields -->
                                        <div id="bkashFields" class="payment-gateway-fields" style="display: none;">
                                            <div class="col-12"><hr><h6 class="text-primary">bKash Payment Details</h6></div>
                                            <div class="col-md-4">
                                                <label class="form-label">bKash Number *</label>
                                                <input type="tel" class="form-control" id="bkashNumber" placeholder="01XXXXXXXXX">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Transaction ID (TrxID) *</label>
                                                <input type="text" class="form-control" id="bkashTrxId" placeholder="Enter TrxID">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">bKash Charge</label>
                                                <input type="number" class="form-control" id="bkashCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                                            </div>
                                        </div>
                                        
                                        <!-- Nagad Fields -->
                                        <div id="nagadFields" class="payment-gateway-fields" style="display: none;">
                                            <div class="col-12"><hr><h6 class="text-success">Nagad Payment Details</h6></div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nagad Number *</label>
                                                <input type="tel" class="form-control" id="nagadNumber" placeholder="01XXXXXXXXX">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Transaction ID (TrxID) *</label>
                                                <input type="text" class="form-control" id="nagadTrxId" placeholder="Enter TrxID">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nagad Charge</label>
                                                <input type="number" class="form-control" id="nagadCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                                            </div>
                                        </div>
                                        
                                        <!-- Rocket Fields -->
                                        <div id="rocketFields" class="payment-gateway-fields" style="display: none;">
                                            <div class="col-12"><hr><h6 class="text-warning">Rocket Payment Details</h6></div>
                                            <div class="col-md-4">
                                                <label class="form-label">Rocket Number *</label>
                                                <input type="tel" class="form-control" id="rocketNumber" placeholder="01XXXXXXXXX">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Transaction ID (TrxID) *</label>
                                                <input type="text" class="form-control" id="rocketTrxId" placeholder="Enter TrxID">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Rocket Charge</label>
                                                <input type="number" class="form-control" id="rocketCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <textarea class="form-control" id="orderNotes" rows="2" 
                                                      placeholder="Order notes (optional)"></textarea>
                                        </div>
                                    </div>
                            </div>
                        </div>

            <!-- Customer Section -->
            <div class="customer-section">
                <div class="customer-header">
                    <i class="fas fa-user"></i>
                    Customer Information
                </div>
                <div class="customer-form">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="customerSearch" 
                               placeholder="Search existing customer...">
                        <div id="customerSuggestions" class="mt-2"></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerName" 
                                   placeholder="Customer Name *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerPhone" 
                                   placeholder="Phone Number *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" id="customerEmail" 
                                   placeholder="Email (optional)">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerCity" 
                                   placeholder="City">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" id="customerAddress" rows="2" 
                                      placeholder="Address (optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Button -->
            <button class="btn-checkout" id="checkoutBtn" onclick="processOrder()" disabled>
                <i class="fas fa-eye"></i>
                Review Order
            </button>
        </div>
    </div>
</div>

<!-- Variation Selection Modal -->
<div class="modal fade" id="variationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Product Variation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="variationModalBody">
                <!-- Variation options will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedVariation()">Add to Cart</button>
            </div>
        </div>
    </div>
</div>

<!-- Order Success Modal -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle"></i>
                    Order Created Successfully!
                </h5>
            </div>
            <div class="modal-body" id="orderSuccessBody">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="startNewOrder()">
                    <i class="fas fa-check-circle"></i> Place Order
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// Global variables
let cart = [];
let selectedCustomer = null;
let currentProduct = null;
let html5QrCode = null;
let pendingOrder = null; // Store order data before final placement
const primaryCategoryFilter = $('#primary-category-filter');
const subcategoryFilter = $('#subcategory-filter');
const thirdCategoryFilter = $('#third-category-filter');
const stockStatusFilter = $('#stock-status-filter');
const productTypeFilter = $('#product-type-filter');
const lowStockFilter = $('#low-stock-filter');
let currentPage = 1;
let lastPage = 1;
let totalItems = 0;
let perPage = 20;
let filtersCollapsed = false;

// Initialize POS
$(document).ready(function() {
    // Set CSRF token for all AJAX requests FIRST
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    setSubcategoryOptions(null, 'Select a primary category first', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);
    loadStats();
    setupEventListeners();
    
    // Load initial products with a small delay
    setTimeout(function() {
        searchProducts();
    }, 100);
    
    // Handle modal cancellation
    $('#orderSuccessModal').on('hidden.bs.modal', function() {
        // If there's a pending order and modal is closed without placing, clear it
        if (pendingOrder) {
            pendingOrder = null;
            toastr.info('Order preview cancelled');
        }
    });
});

function setupEventListeners() {
    const debouncedSearch = debounce(() => searchProducts(1), 300);

    // Product search
    $('#productSearch').on('input', debouncedSearch);
    primaryCategoryFilter.on('change', function() {
        const categoryId = $(this).val();
        subcategoryFilter.val('');
        thirdCategoryFilter.val('');
        loadSubcategories(categoryId);
        searchProducts(1);
    });
    subcategoryFilter.on('change', function() {
        const subcategoryId = $(this).val();
        thirdCategoryFilter.val('');
        loadThirdCategories(subcategoryId);
        searchProducts(1);
    });
    thirdCategoryFilter.on('change', () => searchProducts(1));
    stockStatusFilter.on('change', () => searchProducts(1));
    productTypeFilter.on('change', () => searchProducts(1));
    lowStockFilter.on('change', () => searchProducts(1));
    $('#clearFiltersBtn').on('click', function() {
        clearFilters();
        searchProducts(1);
    });
    $('#filtersToggleBtn').on('click', function() {
        filtersCollapsed = !filtersCollapsed;
        updateFiltersVisibility();
    });
    $('#prevPageBtn').on('click', function() {
        if (currentPage > 1) {
            searchProducts(currentPage - 1);
        }
    });
    $('#nextPageBtn').on('click', function() {
        if (currentPage < lastPage) {
            searchProducts(currentPage + 1);
        }
    });
    
    // Customer search
    $('#customerSearch').on('input', debounce(searchCustomers, 300));
    
    // Customer form validation
    $('#customerName, #customerPhone').on('input', validateForm);
    
    // Enter key handling
    $('#productSearch').on('keypress', function(e) {
        if (e.which === 13) {
            searchProducts(1);
        }
    });

    if (window.innerWidth < 768) {
        filtersCollapsed = true;
        updateFiltersVisibility();
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function setSubcategoryOptions(options, placeholder, disabled) {
    subcategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
    if (Array.isArray(options)) {
        options.forEach(option => {
            subcategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
        });
    }
    subcategoryFilter.prop('disabled', disabled);
}

function setThirdCategoryOptions(options, placeholder, disabled) {
    thirdCategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
    if (Array.isArray(options)) {
        options.forEach(option => {
            thirdCategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
        });
    }
    thirdCategoryFilter.prop('disabled', disabled);
}

function loadSubcategories(categoryId) {
    if (!categoryId) {
        setSubcategoryOptions(null, 'Select a primary category first', true);
        setThirdCategoryOptions(null, 'Select a subcategory first', true);
        return;
    }

    setSubcategoryOptions(null, 'Loading...', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);

    const url = '{{ route("admin.get-product-subcategories", ':id') }}'.replace(':id', categoryId);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length) {
                setSubcategoryOptions(data, 'All Subcategories', false);
            } else {
                setSubcategoryOptions(null, 'No subcategories available', true);
            }
        })
        .catch(() => {
            setSubcategoryOptions(null, 'Failed to load subcategories', true);
        });
}

function loadThirdCategories(subcategoryId) {
    if (!subcategoryId) {
        setThirdCategoryOptions(null, 'Select a subcategory first', true);
        return;
    }

    setThirdCategoryOptions(null, 'Loading...', true);

    fetch('{{ route('admin.third-categories.by-subcategories') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ sub_category_ids: [subcategoryId] })
    })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length) {
                setThirdCategoryOptions(data, 'All Third Categories', false);
            } else {
                setThirdCategoryOptions(null, 'No third categories available', true);
            }
        })
        .catch(() => {
            setThirdCategoryOptions(null, 'Failed to load third categories', true);
        });
}

function clearFilters() {
    $('#productSearch').val('');
    primaryCategoryFilter.val('');
    setSubcategoryOptions(null, 'Select a primary category first', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);
    stockStatusFilter.val('');
    productTypeFilter.val('');
    lowStockFilter.prop('checked', false);
    currentPage = 1;
    lastPage = 1;
    totalItems = 0;
}

function updateFiltersVisibility() {
    const panel = $('#filtersPanel');
    const toggleBtn = $('#filtersToggleBtn');
    if (filtersCollapsed) {
        panel.addClass('collapsed');
        toggleBtn.html('<i class="fas fa-filter"></i> Show Filters');
    } else {
        panel.removeClass('collapsed');
        toggleBtn.html('<i class="fas fa-filter"></i> Hide Filters');
    }
}

function updatePagination(meta, count) {
    const container = $('#productsPagination');
    if (!meta || !meta.total) {
        container.hide();
        return;
    }

    currentPage = meta.current_page || 1;
    lastPage = meta.last_page || 1;
    perPage = meta.per_page || perPage;
    totalItems = meta.total || 0;

    const start = (currentPage - 1) * perPage + 1;
    const itemsOnPage = count || 0;
    const end = itemsOnPage > 0 ? Math.min(totalItems, start + itemsOnPage - 1) : Math.min(totalItems, start + perPage - 1);

    $('#paginationInfo').text(`Showing ${start}-${end} of ${totalItems}`);
    $('#prevPageBtn').prop('disabled', currentPage <= 1);
    $('#nextPageBtn').prop('disabled', currentPage >= lastPage);
    container.show();
}

// Product search and display
function searchProducts(page = 1) {
    currentPage = page;
    const search = $('#productSearch').val();
    const primaryCategoryId = primaryCategoryFilter.val();
    const subCategoryId = subcategoryFilter.val();
    const thirdCategoryId = thirdCategoryFilter.val();
    const stockStatus = stockStatusFilter.val();
    const productType = productTypeFilter.val();
    const lowStockOnly = lowStockFilter.is(':checked') ? '1' : '';
    
    $.get('{{ route("admin.pos.search-products") }}', {
        search: search,
        primary_category_id: primaryCategoryId,
        subcategory_id: subCategoryId,
        third_category_id: thirdCategoryId,
        stock_status: stockStatus,
        product_type: productType,
        low_stock_only: lowStockOnly,
        page: page
    })
    .done(function(response) {
        if (response.error) {
            toastr.error(response.message);
            displayProducts([]);
            updatePagination(null, 0);
        } else {
            displayProducts(response.products || []);
            updatePagination(response.meta, (response.products || []).length);
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response?.message || 'Failed to search products');
        displayProducts([]);
        updatePagination(null, 0);
    });
}

function displayProducts(products) {
    const grid = $('#productsGrid');
    
    if (!products || products.length === 0) {
        grid.html(`
            <div class="text-center py-4 col-12">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No products found</p>
                <small class="text-muted">Try searching for products or check filters</small>
            </div>
        `);
        return;
    }
    
    let html = '';
    products.forEach(product => {
        const status = product.computed_stock_status || (product.in_stock ? 'in_stock' : 'out_of_stock');
        const inStock = status !== 'out_of_stock';
        const stockClass = inStock ? '' : 'out-of-stock';
        
        let stockText = 'Stock: ∞';
        if (status === 'on_backorder') {
            stockText = 'On backorder';
        } else if (product.manage_stock) {
            if (product.product_type === 'variable') {
                const inStockVariations = (product.variations || []).filter(v => v.in_stock);
                stockText = `${inStockVariations.length} variations available`;
                if (product.stock_quantity !== undefined && product.stock_quantity !== null) {
                    stockText += ` • ${product.stock_quantity} in stock`;
                }
            } else {
                stockText = `Stock: ${product.stock_quantity || 0}`;
            }
        }
        if (status === 'low_stock') {
            stockText += ' (Low stock)';
        }
        
        const stockTextClass = status === 'low_stock' ? 'text-warning'
            : status === 'out_of_stock' ? 'text-danger'
            : status === 'on_backorder' ? 'text-info'
            : 'text-success';
        
        html += `
            <div class="product-card ${stockClass}" onclick="selectProduct(${product.id})" data-product='${JSON.stringify(product)}'>
                ${product.image ? `<img src="${product.image}" alt="${product.title}" class="product-image">` : '<div class="product-image bg-light d-flex align-items-center justify-content-center"><i class="fas fa-image text-muted"></i></div>'}
                <div class="product-title">${product.title}</div>
                <div class="product-price">৳${product.price || '0.00'}</div>
                <div class="product-stock ${stockTextClass}">${stockText}</div>
                ${product.product_type === 'variable' ? '<small class="text-info"><i class="fas fa-cogs"></i> Variable</small>' : ''}
            </div>
        `;
    });
    
    grid.html(html);
}

function selectProduct(productId) {
    const productCard = $(`.product-card[onclick="selectProduct(${productId})"]`);
    const product = JSON.parse(productCard.attr('data-product'));
    
    if (!product.in_stock) {
        toastr.warning('This product is out of stock');
        return;
    }
    
    if (product.product_type === 'variable') {
        showVariationModal(product);
    } else {
        addToCart(product);
    }
}

function showVariationModal(product) {
    currentProduct = product;
    const modal = $('#variationModal');
    const modalBody = $('#variationModalBody');
    
    let html = `
        <div class="mb-3">
            <h6>${product.title}</h6>
            <p class="text-muted mb-3">Select a variation to add to cart:</p>
        </div>
    `;
    
    if (product.variations && product.variations.length > 0) {
        product.variations.forEach(variation => {
            const inStock = variation.in_stock;
            const disabled = !inStock ? 'disabled' : '';
            const stockClass = inStock ? 'text-success' : 'text-danger';
            
            html += `
                <div class="variation-option ${disabled}" data-variation='${JSON.stringify(variation)}' onclick="selectVariation(this)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${variation.display_name}</strong>
                            <div class="small text-muted">Stock: <span class="${stockClass}">${variation.stock_quantity}</span></div>
                        </div>
                        <div class="text-end">
                            <div class="h6 mb-0 text-success">৳${variation.price}</div>
                            ${variation.offer_price ? `<small class="text-muted"><s>৳${variation.regular_price}</s></small>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p class="text-muted">No variations available</p>';
    }
    
    modalBody.html(html);
    modal.modal('show');
}

let selectedVariation = null;

function selectVariation(element) {
    if ($(element).hasClass('disabled')) return;
    
    $('.variation-option').removeClass('selected');
    $(element).addClass('selected');
    selectedVariation = JSON.parse($(element).attr('data-variation'));
}

function addSelectedVariation() {
    if (!selectedVariation) {
        toastr.warning('Please select a variation');
        return;
    }
    
    const productWithVariation = {
        ...currentProduct, // Keep the original product data
        price: selectedVariation.price, // Use variation price
        variation_id: selectedVariation.id, // Add variation ID
        display_name: selectedVariation.display_name, // Add variation display name
        stock_quantity: selectedVariation.stock_quantity // Use variation stock
    };
    
    addToCart(productWithVariation);
    $('#variationModal').modal('hide');
    selectedVariation = null;
}

function addToCart(product) {
    const existingItemIndex = cart.findIndex(item => 
        item.id === product.id && 
        (item.variation_id || null) === (product.variation_id || null)
    );
    
    if (existingItemIndex !== -1) {
        // Increase quantity
        cart[existingItemIndex].quantity += 1;
        cart[existingItemIndex].subtotal = cart[existingItemIndex].quantity * cart[existingItemIndex].price;
    } else {
        // Add new item
        cart.push({
            id: product.id, // This should always be the product ID
            product_id: product.id, // Explicitly store product ID
            title: product.title,
            price: parseFloat(product.price || 0),
            quantity: 1,
            subtotal: parseFloat(product.price || 0),
            image: product.image,
            variation_id: product.variation_id || null,
            display_name: product.display_name || null,
            stock_quantity: product.stock_quantity || 0,
            manage_stock: product.manage_stock
        });
    }
    
    updateCartDisplay();
    toastr.success(`${product.title} added to cart`);
}

function updateCartDisplay() {
    const cartItems = $('#cartItems');
    const cartItemCount = $('#cartItemCount');
    const cartSummary = $('#cartSummary');
    const checkoutBtn = $('#checkoutBtn');
    
    cartItemCount.text(cart.length);
    
    if (cart.length === 0) {
        cartItems.html(`
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty</p>
                <small>Add products from the left panel</small>
            </div>
        `);
        cartSummary.hide();
        checkoutBtn.prop('disabled', true);
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        html += `
            <div class="cart-item">
                ${item.image ? `<img src="${item.image}" alt="${item.title}" class="cart-item-image">` : '<div class="cart-item-image bg-light d-flex align-items-center justify-content-center"><i class="fas fa-image text-muted"></i></div>'}
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.title}</div>
                    ${item.display_name ? `<div class="cart-item-variation">${item.display_name}</div>` : ''}
                    <div class="text-success">৳${item.price.toFixed(2)} × ${item.quantity} = ৳${item.subtotal.toFixed(2)}</div>
                </div>
                <div class="cart-item-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <input type="number" class="quantity-input" value="${item.quantity}" min="1" 
                               onchange="setQuantity(${index}, this.value)">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    cartItems.html(html);
    cartSummary.show();
    updateTotals();
    validateForm();
}

function updateQuantity(index, change) {
    const item = cart[index];
    const newQuantity = item.quantity + change;
    
    if (newQuantity <= 0) {
        removeFromCart(index);
        return;
    }
    
    // Check stock availability
    if (item.manage_stock && newQuantity > item.stock_quantity) {
        toastr.warning(`Only ${item.stock_quantity} items available in stock`);
        return;
    }
    
    item.quantity = newQuantity;
    item.subtotal = item.quantity * item.price;
    updateCartDisplay();
}

function setQuantity(index, quantity) {
    const item = cart[index];
    const newQuantity = parseInt(quantity) || 1;
    
    if (newQuantity <= 0) {
        removeFromCart(index);
        return;
    }
    
    // Check stock availability
    if (item.manage_stock && newQuantity > item.stock_quantity) {
        toastr.warning(`Only ${item.stock_quantity} items available in stock`);
        $(`.quantity-input`).eq(index).val(item.quantity);
        return;
    }
    
    item.quantity = newQuantity;
    item.subtotal = item.quantity * item.price;
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
    toastr.info('Item removed from cart');
}

function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat($('#discountAmount').val()) || 0;
    const shipping = parseFloat($('#shippingAmount').val()) || 0;
    
    // Add payment gateway charges
    let paymentCharge = 0;
    const paymentMethod = $('#paymentMethod').val();
    if (paymentMethod === 'bkash') {
        paymentCharge = parseFloat($('#bkashCharge').val()) || 0;
    } else if (paymentMethod === 'nagad') {
        paymentCharge = parseFloat($('#nagadCharge').val()) || 0;
    } else if (paymentMethod === 'rocket') {
        paymentCharge = parseFloat($('#rocketCharge').val()) || 0;
    }
    
    const total = subtotal - discount + shipping + paymentCharge;
    
    $('#subtotal').text(`৳${subtotal.toFixed(2)}`);
    $('#total').text(`৳${total.toFixed(2)}`);
    
    // Show/hide payment charge row
    if (paymentCharge > 0) {
        let chargeLabel = 'Payment Charge:';
        if (paymentMethod === 'bkash') chargeLabel = 'bKash Charge:';
        else if (paymentMethod === 'nagad') chargeLabel = 'Nagad Charge:';
        else if (paymentMethod === 'rocket') chargeLabel = 'Rocket Charge:';
        
        $('#paymentChargeLabel').text(chargeLabel);
        $('#paymentChargeAmount').text(`৳${paymentCharge.toFixed(2)}`);
        $('#paymentChargeRow').show();
    } else {
        $('#paymentChargeRow').hide();
    }
}

function togglePaymentFields() {
    const paymentMethod = $('#paymentMethod').val();
    
    // Hide all payment gateway fields
    $('.payment-gateway-fields').hide();
    
    // Show relevant fields based on payment method
    if (paymentMethod === 'bkash') {
        $('#bkashFields').show();
    } else if (paymentMethod === 'nagad') {
        $('#nagadFields').show();
    } else if (paymentMethod === 'rocket') {
        $('#rocketFields').show();
    }
    
    // Update totals when payment method changes
    updateTotals();
}

function clearCart() {
    if (cart.length === 0) return;
    
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCartDisplay();
        toastr.info('Cart cleared');
    }
}

// Customer management
function searchCustomers() {
    const search = $('#customerSearch').val();
    if (search.length < 2) {
        $('#customerSuggestions').empty();
        return;
    }
    
    $.get('{{ route("admin.pos.search-customers") }}', { search: search })
    .done(function(response) {
        displayCustomerSuggestions(response.customers);
    });
}

function displayCustomerSuggestions(customers) {
    const suggestions = $('#customerSuggestions');
    
    if (customers.length === 0) {
        suggestions.empty();
        return;
    }
    
    let html = '<div class="list-group">';
    customers.forEach(customer => {
        html += `
            <button type="button" class="list-group-item list-group-item-action" 
                    onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.phone}', '${customer.email || ''}', '${customer.address || ''}', '${customer.city || ''}')">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${customer.name}</strong>
                        <br><small class="text-muted">${customer.phone}</small>
                    </div>
                    <small class="text-muted">${customer.email || ''}</small>
                </div>
            </button>
        `;
    });
    html += '</div>';
    
    suggestions.html(html);
}

function selectCustomer(id, name, phone, email, address, city) {
    selectedCustomer = { id, name, phone, email, address, city };
    
    $('#customerName').val(name);
    $('#customerPhone').val(phone);
    $('#customerEmail').val(email);
    $('#customerAddress').val(address);
    $('#customerCity').val(city);
    
    $('#customerSuggestions').empty();
    $('#customerSearch').val('');
    
    validateForm();
    toastr.success('Customer selected');
}

// Form validation
function validateForm() {
    const hasItems = cart.length > 0;
    const hasName = $('#customerName').val().trim() !== '';
    const hasPhone = $('#customerPhone').val().trim() !== '';
    
    $('#checkoutBtn').prop('disabled', !(hasItems && hasName && hasPhone));
}

// Order processing - Prepare order for review
function processOrder() {
    if (cart.length === 0) {
        toastr.error('Cart is empty');
        return;
    }
    
    const customerName = $('#customerName').val().trim();
    const customerPhone = $('#customerPhone').val().trim();
    
    if (!customerName || !customerPhone) {
        toastr.error('Customer name and phone are required');
        return;
    }
    
    // Validate payment gateway fields
    const paymentMethod = $('#paymentMethod').val();
    if (paymentMethod === 'bkash') {
        if (!$('#bkashNumber').val() || !$('#bkashTrxId').val()) {
            toastr.error('bKash number and transaction ID are required');
            return;
        }
    } else if (paymentMethod === 'nagad') {
        if (!$('#nagadNumber').val() || !$('#nagadTrxId').val()) {
            toastr.error('Nagad number and transaction ID are required');
            return;
        }
    } else if (paymentMethod === 'rocket') {
        if (!$('#rocketNumber').val() || !$('#rocketTrxId').val()) {
            toastr.error('Rocket number and transaction ID are required');
            return;
        }
    }
    
    // Prepare order data for review
    pendingOrder = {
        customer_id: selectedCustomer ? selectedCustomer.id : null,
        customer_name: customerName,
        customer_phone: customerPhone,
        customer_email: $('#customerEmail').val(),
        customer_address: $('#customerAddress').val(),
        customer_city: $('#customerCity').val(),
        order_source: $('#orderSource').val(),
        payment_method: paymentMethod,
        items: cart.map(item => ({
            product_id: item.product_id || item.id,
            combination_id: item.variation_id,
            quantity: item.quantity,
            price: item.price,
            name: item.title,
            display_name: item.display_name
        })),
        discount: parseFloat($('#discountAmount').val()) || 0,
        shipping: parseFloat($('#shippingAmount').val()) || 0,
        total: parseFloat($('#total').text().replace('৳', '')),
        notes: $('#orderNotes').val(),
        
        // Payment gateway fields
        bkash_number: paymentMethod === 'bkash' ? $('#bkashNumber').val() : null,
        bkash_transaction_id: paymentMethod === 'bkash' ? $('#bkashTrxId').val() : null,
        bkash_charge: paymentMethod === 'bkash' ? (parseFloat($('#bkashCharge').val()) || 0) : 0,
        
        nagad_number: paymentMethod === 'nagad' ? $('#nagadNumber').val() : null,
        nagad_transaction_id: paymentMethod === 'nagad' ? $('#nagadTrxId').val() : null,
        nagad_charge: paymentMethod === 'nagad' ? (parseFloat($('#nagadCharge').val()) || 0) : 0,
        
        rocket_number: paymentMethod === 'rocket' ? $('#rocketNumber').val() : null,
        rocket_transaction_id: paymentMethod === 'rocket' ? $('#rocketTrxId').val() : null,
        rocket_charge: paymentMethod === 'rocket' ? (parseFloat($('#rocketCharge').val()) || 0) : 0
    };
    
    // Show order preview modal
    showOrderPreview(pendingOrder);
}

function showOrderPreview(orderData) {
    const modal = $('#orderSuccessModal');
    const body = $('#orderSuccessBody');
    
    // Update modal header for preview
    modal.find('.modal-title').html('<i class="fas fa-eye"></i> Order Preview');
    modal.find('.modal-header').removeClass('bg-success').addClass('bg-primary');
    
    let itemsHtml = '';
    orderData.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td>${item.name}${item.display_name ? '<br><small class="text-muted">' + item.display_name + '</small>' : ''}</td>
                <td class="text-center">${item.quantity}</td>
                <td class="text-end">৳${item.price.toFixed(2)}</td>
                <td class="text-end">৳${(item.price * item.quantity).toFixed(2)}</td>
            </tr>
        `;
    });
    
    const subtotal = orderData.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    body.html(`
        <div class="mb-3">
            <h6>Customer Information</h6>
            <p class="mb-1"><strong>Name:</strong> ${orderData.customer_name}</p>
            <p class="mb-1"><strong>Phone:</strong> ${orderData.customer_phone}</p>
            ${orderData.customer_email ? `<p class="mb-1"><strong>Email:</strong> ${orderData.customer_email}</p>` : ''}
            ${orderData.customer_address ? `<p class="mb-1"><strong>Address:</strong> ${orderData.customer_address}</p>` : ''}
        </div>
        
        <div class="mb-3">
            <h6>Order Details</h6>
            <p class="mb-1"><strong>Payment Method:</strong> ${orderData.payment_method}</p>
            <p class="mb-1"><strong>Order Source:</strong> ${orderData.order_source}</p>
            ${orderData.notes ? `<p class="mb-1"><strong>Notes:</strong> ${orderData.notes}</p>` : ''}
            
            ${orderData.payment_method === 'bkash' && orderData.bkash_number ? `
                <hr>
                <h6 class="text-primary">bKash Payment Details</h6>
                <p class="mb-1"><strong>bKash Number:</strong> ${orderData.bkash_number}</p>
                <p class="mb-1"><strong>Transaction ID:</strong> ${orderData.bkash_transaction_id}</p>
                ${orderData.bkash_charge > 0 ? `<p class="mb-1"><strong>bKash Charge:</strong> ৳${orderData.bkash_charge.toFixed(2)}</p>` : ''}
            ` : ''}
            
            ${orderData.payment_method === 'nagad' && orderData.nagad_number ? `
                <hr>
                <h6 class="text-success">Nagad Payment Details</h6>
                <p class="mb-1"><strong>Nagad Number:</strong> ${orderData.nagad_number}</p>
                <p class="mb-1"><strong>Transaction ID:</strong> ${orderData.nagad_transaction_id}</p>
                ${orderData.nagad_charge > 0 ? `<p class="mb-1"><strong>Nagad Charge:</strong> ৳${orderData.nagad_charge.toFixed(2)}</p>` : ''}
            ` : ''}
            
            ${orderData.payment_method === 'rocket' && orderData.rocket_number ? `
                <hr>
                <h6 class="text-warning">Rocket Payment Details</h6>
                <p class="mb-1"><strong>Rocket Number:</strong> ${orderData.rocket_number}</p>
                <p class="mb-1"><strong>Transaction ID:</strong> ${orderData.rocket_transaction_id}</p>
                ${orderData.rocket_charge > 0 ? `<p class="mb-1"><strong>Rocket Charge:</strong> ৳${orderData.rocket_charge.toFixed(2)}</p>` : ''}
            ` : ''}
        </div>
        
        <div class="mb-3">
            <h6>Items</h6>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotal:</th>
                        <th class="text-end">৳${subtotal.toFixed(2)}</th>
                    </tr>
                    ${orderData.discount > 0 ? `
                    <tr>
                        <th colspan="3" class="text-end">Discount:</th>
                        <th class="text-end text-danger">-৳${orderData.discount.toFixed(2)}</th>
                    </tr>
                    ` : ''}
                    ${orderData.shipping > 0 ? `
                    <tr>
                        <th colspan="3" class="text-end">Shipping:</th>
                        <th class="text-end">৳${orderData.shipping.toFixed(2)}</th>
                    </tr>
                    ` : ''}
                    <tr class="table-primary">
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="text-end">৳${orderData.total.toFixed(2)}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    `);
    
    modal.modal('show');
}

function showOrderSuccess(order) {
    const modal = $('#orderSuccessModal');
    const body = $('#orderSuccessBody');
    
    // Update modal header for success
    modal.find('.modal-title').html('<i class="fas fa-check-circle"></i> Order Created Successfully!');
    modal.find('.modal-header').removeClass('bg-primary').addClass('bg-success');
    
    // Update footer buttons for success state
    modal.find('.modal-footer').html(`
        <div class="d-flex justify-content-between w-100">
            <div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="printReceipt(${order.id})">
                    <i class="fas fa-receipt"></i> Print Receipt
                </button>
                <button type="button" class="btn btn-info" onclick="printInvoice(${order.id})">
                    <i class="fas fa-file-invoice"></i> Print Invoice
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary" onclick="downloadReceipt(${order.id})">
                    <i class="fas fa-download"></i> Download Receipt
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="downloadInvoice(${order.id})">
                    <i class="fas fa-download"></i> Download Invoice
                </button>
                <button type="button" class="btn btn-success" onclick="clearOrderForNewOne(); $('#orderSuccessModal').modal('hide');">
                    <i class="fas fa-plus"></i> New Order
                </button>
            </div>
        </div>
    `);
    
    body.html(`
        <div class="text-center mb-3">
            <h5 class="text-success">Order #${order.id}</h5>
            <p class="mb-1"><strong>Customer:</strong> ${order.customer_name}</p>
            <p class="mb-1"><strong>Total:</strong> ৳${order.total}</p>
            <p class="mb-1"><strong>Payment:</strong> ${order.payment_method}</p>
            <p class="mb-1"><strong>Source:</strong> ${order.order_source}</p>
            <small class="text-muted">Created: ${order.created_at}</small>
        </div>
    `);
    
    modal.modal('show');
}

function startNewOrder() {
    // If there's a pending order, place it first
    if (pendingOrder) {
        placeOrder();
    } else {
        // Just clear and hide modal if no pending order
        clearOrderForNewOne();
        $('#orderSuccessModal').modal('hide');
    }
}

function placeOrder() {
    if (!pendingOrder) {
        toastr.error('No order to place');
        return;
    }
    
    // Show loading state
    const modal = $('#orderSuccessModal');
    const originalFooter = modal.find('.modal-footer').html();
    modal.find('.modal-footer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Placing order...</div>');
    
    $.post('{{ route("admin.pos.create-order") }}', pendingOrder)
    .done(function(response) {
        if (response.success) {
            toastr.success('Order #' + response.order.id + ' placed successfully!');
            clearOrderForNewOne();
            showOrderSuccess(response.order);
            loadStats();
            pendingOrder = null; // Clear pending order
        } else {
            toastr.error(response.message || 'Failed to place order');
            // Restore original footer on error
            modal.find('.modal-footer').html(originalFooter);
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response?.message || 'Failed to place order');
        // Restore original footer on error
        modal.find('.modal-footer').html(originalFooter);
    });
}

function clearOrderForNewOne() {
    // This function is called automatically after order creation
    // It clears the cart and forms but doesn't hide the success modal
    cart = [];
    selectedCustomer = null;
    
    // Clear forms
    $('#customerName, #customerPhone, #customerEmail, #customerAddress, #customerCity, #orderNotes').val('');
    $('#customerSearch').val('');
    $('#discountAmount, #shippingAmount').val('0');
    $('#orderSource').val('Physical Store');
    $('#paymentMethod').val('cash');
    
    // Clear payment gateway fields
    $('#bkashNumber, #bkashTrxId, #nagadNumber, #nagadTrxId, #rocketNumber, #rocketTrxId').val('');
    $('#bkashCharge, #nagadCharge, #rocketCharge').val('0');
    $('.payment-gateway-fields').hide();
    
    updateCartDisplay();
}



function printReceipt(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for printing');
        return;
    }
    
    // Open receipt PDF in new window for printing
    const printUrl = `{{ route('admin.pos.print-receipt', ':orderId') }}`.replace(':orderId', orderId);
    const printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
    
    if (printWindow) {
        printWindow.focus();
        toastr.success('Receipt PDF opened for printing');
    } else {
        toastr.error('Please allow pop-ups to print receipt');
    }
}

function printInvoice(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for printing');
        return;
    }
    
    // Open invoice PDF in new window for printing
    const printUrl = `{{ route('admin.pos.print-invoice', ':orderId') }}`.replace(':orderId', orderId);
    const printWindow = window.open(printUrl, '_blank', 'width=1200,height=800');
    
    if (printWindow) {
        printWindow.focus();
        toastr.success('Invoice PDF opened for printing');
    } else {
        toastr.error('Please allow pop-ups to print invoice');
    }
}

function downloadReceipt(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for download');
        return;
    }
    
    // Download receipt as PDF file
    const downloadUrl = `{{ route('admin.pos.download-receipt', ':orderId') }}`.replace(':orderId', orderId);
    window.open(downloadUrl, '_blank');
    
    toastr.success('Receipt PDF download started');
}

function downloadInvoice(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for download');
        return;
    }
    
    // Download invoice as PDF file
    const downloadUrl = `{{ route('admin.pos.download-invoice', ':orderId') }}`.replace(':orderId', orderId);
    window.open(downloadUrl, '_blank');
    
    toastr.success('Invoice PDF download started');
}



// QR/Barcode Scanner
function setScannerButtons(running) {
    const buttons = $('.scanner-toggle-btn');
    if (running) {
        buttons.html('<i class="fas fa-stop"></i> Stop QR/Barcode Scanner').addClass('btn-danger');
    } else {
        buttons.html('<i class="fas fa-qrcode"></i> Start QR/Barcode Scanner').removeClass('btn-danger');
    }
}

function startScanner() {
    const qrReader = $('#qr-reader');
    
    if (qrReader.is(':visible')) {
        stopScanner();
        return;
    }
    
    qrReader.closest('.scanner-section').addClass('active');
    qrReader.show();
    
    html5QrCode = new Html5Qrcode("qr-reader");
    setScannerButtons(true);
    
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        (decodedText, decodedResult) => {
            // Handle successful scan
            $('#productSearch').val(decodedText);
            searchProducts();
            stopScanner();
            toastr.success('Barcode scanned successfully');
        },
        (errorMessage) => {
            // Handle scan errors silently
        }
    ).catch(err => {
        toastr.error('Camera access denied or not available');
        qrReader.hide();
        setScannerButtons(false);
    });
    
}

function stopScanner() {
    const qrReader = $('#qr-reader');
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            qrReader.hide();
            qrReader.closest('.scanner-section').removeClass('active');
            setScannerButtons(false);
        }).catch(err => {
            // Scanner stop error handled silently
        });
    }
}

// Stats loading
function loadStats() {
    $.get('{{ route("admin.pos.stats") }}')
    .done(function(response) {
        $('#todayOrders').text(response.today.orders);
        $('#todayRevenue').text('৳' + response.today.revenue);
        $('#weekOrders').text(response.week.orders);
        $('#monthRevenue').text('৳' + response.month.revenue);
    })
    .fail(function() {
        // Stats loading failed silently
    });
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Ctrl/Cmd + F for search focus
    if ((e.ctrlKey || e.metaKey) && e.keyCode === 70) {
        e.preventDefault();
        $('#productSearch').focus();
    }
    
    // Ctrl/Cmd + Enter for checkout
    if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
        if (!$('#checkoutBtn').prop('disabled')) {
            processOrder();
        }
    }
    
    // Escape to clear search
    if (e.keyCode === 27) {
        $('#productSearch').val('').focus();
        searchProducts();
    }
});

// Configure toastr
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 3000
};
</script>
@endsection
