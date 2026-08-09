@extends('frontend.app')

@section('content')
<style>
    /* Enhanced Vendor Banner Section */
    .vendor-hero-section {
        position: relative;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        padding: 80px 0 60px;
        margin-bottom: 50px;
        color: white;
        overflow: hidden;
    }

    .vendor-hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.1;
    }

    .vendor-hero-content {
        position: relative;
        z-index: 2;
    }

    .vendor-logo-container {
        position: relative;
        display: inline-block;
        margin-bottom: 30px;
    }

    .vendor-logo {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 6px solid rgba(255, 255, 255, 0.9);
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .vendor-logo:hover {
        transform: scale(1.05);
    }

    .vendor-verified-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        border: 3px solid white;
    }

    .vendor-info {
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }

    .vendor-name {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    .vendor-business-name {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        opacity: 0.95;
        color: rgba(255, 255, 255, 0.9);
    }

    .vendor-description {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 35px;
        color: rgba(255, 255, 255, 0.85);
    }

    .vendor-stats {
        display: flex;
        justify-content: center;
        gap: 60px;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
        padding: 15px 25px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        display: block;
        margin-bottom: 5px;
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    /* Enhanced Filters Section */
    .filters-section {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .filters-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f8f9fa;
    }

    .filters-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filters-title i {
        color: var(--primary-color);
    }

    .active-filters {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-tag {
        background: #f8f9fa;
        color: #495057;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #e9ecef;
    }

    .filter-tag i {
        color: #6c757d;
        cursor: pointer;
        transition: color 0.2s;
    }

    .filter-tag i:hover {
        color: #dc3545;
    }

    .filter-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb, 240, 38, 39), 0.1);
        background: white;
    }

    .filter-btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb, 240, 38, 39), 0.3);
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-color-rgb, 240, 38, 39), 0.4);
    }

    .clear-filters-btn {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .clear-filters-btn:hover {
        background: #5a6268;
    }

    /* Enhanced Products Section */
    .products-section {
        margin-bottom: 50px;
    }

    .section-header {
        margin-bottom: 30px;
        padding: 20px 0;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--secondary-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .results-count {
        background: #f8f9fa;
        color: #6c757d;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    /* Enhanced Empty State */
    .empty-state {
        text-align: center;
        padding: 100px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin: 40px 0;
    }

    .empty-state-icon {
        font-size: 5rem;
        color: #e9ecef;
        margin-bottom: 30px;
    }

    .empty-state h3 {
        color: #495057;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 30px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .clear-filters-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--primary-color);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(var(--primary-color-rgb, 240, 38, 39), 0.3);
    }

    .clear-filters-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(var(--primary-color-rgb, 240, 38, 39), 0.4);
        color: white;
        text-decoration: none;
    }

    /* Enhanced Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 50px;
    }

    /* Loading State */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .vendor-name {
            font-size: 2.5rem;
        }

        .filter-form {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-group {
            width: 100%;
        }

        .filter-btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .vendor-hero-section {
            padding: 60px 0 40px;
        }

        .vendor-logo {
            width: 120px;
            height: 120px;
            border-width: 5px;
        }

        .vendor-name {
            font-size: 2rem;
        }

        .vendor-business-name {
            font-size: 1.1rem;
        }

        .vendor-description {
            font-size: 1rem;
        }

        .vendor-stats {
            gap: 20px;
        }

        .stat-item {
            padding: 12px 20px;
            min-width: 120px;
        }

        .stat-number {
            font-size: 2rem;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .filters-section {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .vendor-hero-section {
            padding: 40px 0 30px;
        }

        .vendor-name {
            font-size: 1.8rem;
        }

        .vendor-stats {
            flex-direction: column;
            align-items: center;
        }

        .stat-item {
            width: 100%;
            max-width: 200px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }

        .empty-state {
            padding: 60px 15px;
        }

        .empty-state-icon {
            font-size: 4rem;
        }
    }

    /* Animation for product cards */
    .product-card {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Smooth scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

<!-- Enhanced Vendor Hero Section -->
<div class="vendor-hero-section">
    <div class="base-container">
        <div class="vendor-hero-content">
            <div class="vendor-info">
                <div class="vendor-logo-container">
                    @if($vendor->vendorSettings && $vendor->vendorSettings->business_logo)
                        <img src="{{ asset('storage/' . $vendor->vendorSettings->business_logo) }}"
                             alt="{{ $vendor->name }}"
                             class="vendor-logo">
                    @else
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=140&h=140&fit=crop&crop=face&auto=format"
                             alt="{{ $vendor->name }}"
                             class="vendor-logo">
                    @endif

                    @if($vendor->vendorSettings && $vendor->vendorSettings->is_verified)
                        <div class="vendor-verified-badge">
                            <i class="fas fa-check"></i>
                        </div>
                    @endif
                </div>

                <h1 class="vendor-name">{{ $vendor->name }}</h1>

                @if($vendor->vendorSettings && $vendor->vendorSettings->business_name)
                    <p class="vendor-business-name">{{ $vendor->vendorSettings->business_name }}</p>
                @endif

                @if($vendor->vendorSettings && $vendor->vendorSettings->business_description)
                    <p class="vendor-description">{{ $vendor->vendorSettings->business_description }}</p>
                @endif

                <div class="vendor-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_products'] }}</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">
                            @if($vendor->vendorSettings && $vendor->vendorSettings->is_verified)
                                <i class="fas fa-shield-alt"></i>
                            @else
                                <i class="fas fa-store"></i>
                            @endif
                        </span>
                        <span class="stat-label">
                            @if($vendor->vendorSettings && $vendor->vendorSettings->is_verified)
                                Verified
                            @else
                                Seller
                            @endif
                        </span>
                    </div>
                    @if(isset($stats['total_sales']) && $stats['total_sales'] > 0)
                        <div class="stat-item">
                            <span class="stat-number">{{ $stats['total_sales'] }}</span>
                            <span class="stat-label">Sales</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="base-container">
    <!-- Enhanced Filters Section -->
    <div class="filters-section">
        <div class="filters-header">
            <h2 class="filters-title">
                <i class="fas fa-sliders-h"></i>
                Filter Products
            </h2>
            <div class="active-filters">
                @if(request()->hasAny(['search', 'category', 'sort']))
                    <span class="filter-tag">
                        <i class="fas fa-filter"></i>
                        Active Filters
                    </span>
                    @if(request('search'))
                        <span class="filter-tag">
                            Search: {{ request('search') }}
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="filter-tag">
                            Category: {{ $vendorCategories->find(request('category'))->name ?? 'Selected' }}
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('sort'))
                        <span class="filter-tag">
                            Sort: {{ request('sort') == 'latest' ? 'Latest' : (request('sort') == 'price_low' ? 'Price Low' : (request('sort') == 'price_high' ? 'Price High' : 'Popular')) }}
                            <a href="{{ request()->fullUrlWithQuery(['sort' => null]) }}">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('vendor.store.show', $vendor->vendorSettings ? $vendor->vendorSettings->store_slug : $vendor->id) }}" class="clear-filters-btn">
                        Clear All
                    </a>
                @endif
            </div>
        </div>

        <form method="GET" class="filter-form">
            <div class="form-group">
                <label class="form-label">Search Products</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0"
                           placeholder="Search for products..."
                           value="{{ request('search') }}">
                </div>
            </div>

            @if($vendorCategories->count() > 0)
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($vendorCategories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Sort By</label>
                <select name="sort" class="form-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                        <i class="fas fa-clock"></i> Latest First
                    </option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                        <i class="fas fa-sort-amount-up"></i> Price: Low to High
                    </option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                        <i class="fas fa-sort-amount-down"></i> Price: High to Low
                    </option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                        <i class="fas fa-fire"></i> Most Popular
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-filter"></i>
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Enhanced Products Section -->
    <div class="products-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-shopping-bag"></i>
                Products
                <span class="results-count">
                    {{ $products->count() }} {{ $products->count() == 1 ? 'Product' : 'Products' }}
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        <span class="text-muted">(filtered)</span>
                    @endif
                </span>
            </h2>
        </div>

        @if($products->count() > 0)
            <div class="products-grid" id="productsContainer">
                @foreach($products as $index => $product)
                    @include('frontend.partials.product-item', [
                        'product' => $product,
                        'badge' => 'Shop!',
                    ])
                @endforeach
            </div>

            <!-- Enhanced Pagination -->
            <div class="pagination-container">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>No Products Found</h3>
                <p>
                    @if(request()->hasAny(['search', 'category', 'sort']))
                        No products match your current filter criteria. Try adjusting your filters or browse all products.
                    @else
                        This vendor hasn't added any products yet. Check back soon for new arrivals!
                    @endif
                </p>
                @if(request()->hasAny(['search', 'category', 'sort']))
                    <a href="{{ route('vendor.store.show', $vendor->vendorSettings ? $vendor->vendorSettings->store_slug : $vendor->id) }}" class="clear-filters-link">
                        <i class="fas fa-times-circle"></i>
                        Clear All Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay (Hidden by default) -->
<div class="loading-overlay" id="loadingOverlay" style="display: none;">
    <div class="loading-spinner"></div>
</div>

@endsection

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize filter interactions
    initializeFilters();

    // Initialize search autocomplete if needed
    initializeSearchAutocomplete();
});

// Filter interactions
function initializeFilters() {
    const filterForm = document.querySelector('.filter-form');
    const searchInput = document.querySelector('input[name="search"]');

    // Add debounced search
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    showLoadingState();
                    filterForm.submit();
                }
            }, 500);
        });
    }

    // Enhanced form submission with loading state
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            showLoadingState();
        });
    }
}

// Search autocomplete (placeholder for future enhancement)
function initializeSearchAutocomplete() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        // Future: Implement autocomplete functionality
        console.log('Search autocomplete ready for implementation');
    }
}

// Loading state management
function showLoadingState() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';

        // Auto-hide after 10 seconds (safety)
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 10000);
    }
}

// Hide loading state when page is fully loaded
window.addEventListener('load', function() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
});

// Enhanced scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe product cards for animations
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});

// Smooth scroll behavior for pagination links
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;

        showLoadingState();

        setTimeout(() => {
            window.location.href = url;
        }, 300);
    }
});

// Filter tag removal enhancement
document.addEventListener('click', function(e) {
    if (e.target.closest('.filter-tag a')) {
        e.preventDefault();
        const link = e.target.closest('a');
        showLoadingState();
        window.location.href = link.href;
    }
});
</script>

