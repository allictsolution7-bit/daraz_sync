@extends('frontend.app')
@section('styles')
    <style>
        /* Modern Premium Shop Page Styles */
        .shop-container {
            padding: 24px 0 40px;
            display: grid;
            grid-template-columns: 290px 1fr;
            gap: 24px;
            max-width: 1340px;
            margin: 0 auto;
        }

        /* Modern Filter Sidebar */
        .filter-sidebar {
            background: #ffffff;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
            height: fit-content;
            position: sticky;
            top: 24px;
        }

        .filter-section {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .filter-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .filter-title {
            font-size: 13px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
            padding-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 3px;
            max-height: 280px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 10px;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            color: #334155;
            user-select: none;
        }

        .filter-option:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .filter-option.active {
            background: #2563eb !important;
            color: #ffffff !important;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
        }

        .filter-checkbox {
            width: 17px;
            height: 17px;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .filter-option.active .filter-checkbox {
            background: #ffffff;
            border-color: #ffffff;
        }
        
        .brand-logo-filter {
            width: 18px;
            height: 18px;
            object-fit: contain;
            border-radius: 4px;
            margin-right: 4px;
            vertical-align: middle;
            background: #fff;
        }

        .filter-option.active .filter-checkbox::after {
            content: '✓';
            color: #2563eb;
            font-weight: 900;
            font-size: 11px;
        }

        .filter-label {
            flex: 1;
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .filter-option.active .filter-label {
            font-weight: 700;
        }

        .filter-count {
            background: #f1f5f9;
            color: #64748b;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            min-width: 26px;
            text-align: center;
            flex-shrink: 0;
        }

        .filter-option.active .filter-count {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Price Range Slider */
        .price-range-container {
            padding: 6px 0;
        }

        .price-range-slider {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #e2e8f0;
            outline: none;
            accent-color: #2563eb;
            margin: 10px 0;
        }

        .price-range-values {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .price-input {
            width: 85px;
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .price-input:focus {
            outline: none;
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        /* Product Results Right Container */
        .product-result-right {
            background: #ffffff;
            padding: 22px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 12px;
        }

        .product-count {
            font-size: 15px;
            color: #0f172a;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .product-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .clear-filters-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .clear-filters-btn:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
        }

        .clear-filters-btn svg {
            width: 13px;
            height: 13px;
        }

        /* Selected Filters Display */
        .selected-filters {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding: 10px 14px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .selected-filters-label {
            font-size: 12.5px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
        }

        .selected-filters-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-tag:hover {
            background: #dbeafe;
            color: #1e40af;
            border-color: #93c5fd;
        }

        .filter-tag .remove-icon {
            width: 11px;
            height: 11px;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .filter-tag:hover .remove-icon {
            opacity: 1;
        }

        .sort-select {
            padding: 7px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            color: #1e293b;
            outline: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .sort-select:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        /* Products Grid */
        .category-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
            gap: 18px;
        }

        .category-products-grid .product-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }

        .category-products-grid .product-card:hover {
            transform: translateY(-5px);
            border-color: #93c5fd;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .category-products-grid .product-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            left: auto !important;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 6px;
            z-index: 5;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
            letter-spacing: 0.3px;
        }

        .category-products-grid .product-info {
            padding: 12px 14px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
        }

        .category-products-grid .product-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.35;
            margin-bottom: 6px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .category-products-grid .current-price {
            font-size: 15.5px;
            font-weight: 800;
            color: #0f172a;
        }

        .category-products-grid .original-price {
            font-size: 12.5px;
            color: #94a3b8;
            text-decoration: line-through;
            margin-left: 6px;
        }

        .category-products-grid .add-to-cart-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            color: #ffffff !important;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12.5px;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .category-products-grid .add-to-cart-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
            font-size: 16px;
            font-weight: 600;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
        }

        /* Loading State */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 3px solid #e2e8f0;
            border-top: 3px solid #2563eb;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 12px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mobile Filter Button */
        .mobile-filter-btn {
            display: none;
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #2563eb;
            color: #ffffff;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            justify-content: center;
            align-items: center;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            z-index: 1000;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-filter-btn:hover {
            transform: scale(1.08);
        }

        .mobile-filter-btn svg {
            width: 22px;
            height: 22px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .shop-container {
                grid-template-columns: 260px 1fr;
                gap: 18px;
                padding: 16px;
            }
        }

        @media (max-width: 992px) {
            .shop-container {
                grid-template-columns: 1fr;
                padding: 14px;
            }

            .mobile-filter-btn {
                display: flex;
                bottom: 168px !important;
                right: 14px !important;
                width: 40px !important;
                height: 40px !important;
                z-index: 998 !important;
            }

            .mobile-filter-btn svg {
                width: 18px !important;
                height: 18px !important;
            }

            .filter-sidebar {
                display: none;
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                max-height: 85vh;
                overflow-y: auto;
                z-index: 999;
                border-radius: 20px 20px 0 0;
                padding: 24px 20px 36px;
                background: #ffffff;
                box-shadow: 0 -4px 25px rgba(0, 0, 0, 0.15);
            }

            .filter-sidebar.show {
                display: block;
                animation: slideUp 0.3s ease-out forwards;
            }

            @keyframes slideUp {
                from { transform: translateY(100%); }
                to { transform: translateY(0); }
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(3px);
                z-index: 998;
            }

            .overlay.show {
                display: block;
            }

            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 14px;
            }
        }

        @media (max-width: 768px) {
            .category-products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .product-header {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .product-controls {
                flex-direction: column;
                gap: 8px;
            }

            .clear-filters-btn {
                width: 100%;
                justify-content: center;
            }

            .sort-select {
                width: 100%;
            }
        }

        /* Modern Compact Category Hero Section */
        .category-hero {
            position: relative;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            background-size: cover;
            background-position: center;
            margin-bottom: 24px;
            overflow: hidden;
            border-bottom: 1px solid #e2e8f0;
        }

        .category-hero-overlay {
            width: 100%;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.94) 0%, rgba(15, 23, 42, 0.78) 60%, rgba(15, 23, 42, 0.9) 100%);
            backdrop-filter: blur(2px);
            padding: 24px 0 20px;
        }

        .category-hero-content {
            color: #fff;
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .category-breadcrumbs {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .category-breadcrumbs a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .category-breadcrumbs a:hover {
            color: #38bdf8;
            text-decoration: underline;
        }

        .category-breadcrumbs .sep {
            color: #64748b;
            font-size: 11px;
        }

        .category-breadcrumbs .current {
            color: #f8fafc;
            font-weight: 600;
        }

        .category-hero-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .category-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .category-hero-content h1 {
            font-size: 26px;
            font-weight: 800;
            margin: 0;
            color: #ffffff;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .category-count-badge {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.35);
            border-radius: 20px;
            padding: 3px 10px;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .category-hero-content p {
            font-size: 12.5px;
            line-height: 1.5;
            margin: 4px 0 0;
            color: #94a3b8;
            max-width: 650px;
        }

        .category-subnav-chips {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-top: 12px;
            margin-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            scrollbar-width: thin;
        }

        .category-chip-btn {
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .category-chip-btn:hover, .category-chip-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        @media only screen and (max-width: 768px) {
            .category-hero-overlay {
                padding: 16px 0;
            }
            .category-hero-content h1 {
                font-size: 20px;
            }
            .category-hero-content p {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="overlay"></div>
    
    @php
    // Find the first available background image (third_category, sub_category, category, global)
    $bgImage = null;
    if (isset($third_category_obj) && $third_category_obj && $third_category_obj->background_image) {
        $bgImage = asset('storage/' . $third_category_obj->background_image);
        $title = $third_category_obj->name;
        $rawDesc = $third_category_obj->description ?? '';
    } elseif (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->background_image) {
        $bgImage = asset('category_background_images/' . $sub_category_obj->background_image);
        $title = $sub_category_obj->name;
        $rawDesc = $sub_category_obj->description ?? '';
    } elseif (isset($category_obj) && $category_obj && $category_obj->background_image) {
        $bgImage = asset($category_obj->background_image);
        $title = $category_obj->name;
        $rawDesc = $category_obj->description ?? '';
    } elseif (isset($globalBgImage) && !empty($globalBgImage)) {
        $bgImage = asset($globalBgImage);
        $title = isset($third_category_obj) && $third_category_obj
            ? $third_category_obj->name
            : (isset($category_obj) && $category_obj
                ? $category_obj->name
                : (isset($sub_category_obj) && $sub_category_obj
                    ? $sub_category_obj->name
                    : 'Shop'));
        $rawDesc = isset($third_category_obj) && $third_category_obj && $third_category_obj->description
            ? $third_category_obj->description
            : (isset($category_obj) && $category_obj && $category_obj->description
                ? $category_obj->description
                : (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->description
                    ? $sub_category_obj->description
                    : ''));
    } else {
        $title = isset($third_category_obj) && $third_category_obj
            ? $third_category_obj->name
            : (isset($category_obj) && $category_obj
                ? $category_obj->name
                : (isset($sub_category_obj) && $sub_category_obj
                    ? $sub_category_obj->name
                    : 'Shop'));
        $rawDesc = isset($third_category_obj) && $third_category_obj && $third_category_obj->description
            ? $third_category_obj->description
            : (isset($category_obj) && $category_obj && $category_obj->description
                ? $category_obj->description
                : (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->description
                    ? $sub_category_obj->description
                    : ''));
    }

    // Suppress description if it is identical to title or just whitespace
    $desc = (trim(strtolower($rawDesc)) === trim(strtolower($title)) || empty(trim($rawDesc))) ? '' : $rawDesc;
    @endphp

    <section class="category-hero" style="{{ $bgImage ? "background-image: url('$bgImage')" : '' }}">
        <div class="category-hero-overlay">
            <div class="category-hero-content">
                <!-- Breadcrumbs -->
                <nav class="category-breadcrumbs" aria-label="breadcrumb">
                    <a href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Home</a>
                    <span class="sep"><i class="fas fa-chevron-right"></i></span>
                    <a href="{{ route('shop') }}">Shop</a>
                    @if(isset($category_obj) && $category_obj)
                        <span class="sep"><i class="fas fa-chevron-right"></i></span>
                        @if(isset($sub_category_obj) && $sub_category_obj)
                            <a href="{{ route('shop', $category_obj->slug) }}">{{ $category_obj->name }}</a>
                            <span class="sep"><i class="fas fa-chevron-right"></i></span>
                            <span class="current">{{ $sub_category_obj->name }}</span>
                        @else
                            <span class="current">{{ $category_obj->name }}</span>
                        @endif
                    @elseif(isset($title) && $title !== 'Shop')
                        <span class="sep"><i class="fas fa-chevron-right"></i></span>
                        <span class="current">{{ $title }}</span>
                    @endif
                </nav>

                <div class="category-hero-main">
                    <div>
                        <div class="category-title-wrap">
                            <h1>{{ $title }}</h1>
                            @if(isset($filter_counts['total_products']) && $filter_counts['total_products'] > 0)
                                <span class="category-count-badge">{{ $filter_counts['total_products'] }} Products</span>
                            @elseif(isset($products) && method_exists($products, 'total') && $products->total() > 0)
                                <span class="category-count-badge">{{ $products->total() }} Products</span>
                            @endif
                        </div>
                        @if ($desc)
                            <p>{{ $desc }}</p>
                        @endif
                    </div>
                </div>

                @if(isset($category_obj) && $category_obj && $category_obj->subCategories && $category_obj->subCategories->count() > 0)
                    <div class="category-subnav-chips">
                        <a href="{{ route('shop', $category_obj->slug) }}" class="category-chip-btn {{ !isset($sub_category_obj) ? 'active' : '' }}">
                            All in {{ $category_obj->name }}
                        </a>
                        @foreach($category_obj->subCategories as $sub)
                            <a href="{{ route('shop', [$category_obj->slug, $sub->slug]) }}" class="category-chip-btn {{ (isset($sub_category_obj) && $sub_category_obj->id == $sub->id) ? 'active' : '' }}">
                                {{ $sub->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="base-container shop-container">
        <button class="mobile-filter-btn" id="mobileFilterBtn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22,3 2,3 10,12.46 10,19 14,21 14,12.46 22,3"></polygon>
            </svg>
        </button>

        <div class="filter-sidebar" id="filterSidebar">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 14.5px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-filter" style="color: #2563eb;"></i> Filters
                </h5>
                <span class="text-muted" style="font-size: 11.5px; font-weight: 600; cursor: pointer;" onclick="document.getElementById('clearFilters').click()">Reset All</span>
            </div>

            <!-- Price Range Filter -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span><i class="fas fa-sliders-h me-1.5" style="color: #2563eb;"></i> Price Range</span>
                </h3>
                <div class="price-range-container">
                    <input type="range" class="price-range-slider" id="priceRange" 
                           min="0" max="1000" value="{{ $price_max }}" step="10">
                    <div class="price-range-values">
                        <input type="number" class="price-input" id="priceMin" value="{{ $price_min }}" placeholder="Min">
                        <span style="color: #94a3b8; font-weight: 700;">-</span>
                        <input type="number" class="price-input" id="priceMax" value="{{ $price_max }}" placeholder="Max">
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="filter-section">
                <h3 class="filter-title">
                    <span><i class="fas fa-layer-group me-1.5" style="color: #2563eb;"></i> Categories</span>
                </h3>
                <div class="filter-options">
                    <div class="filter-option @if (!$selected_category) active @endif" 
                         data-filter="category" data-value="">
                        <div class="filter-checkbox"></div>
                        <span class="filter-label">All Categories</span>
                        <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                    </div>
                    @foreach($categories as $category)
                        <div class="filter-option @if ($category->slug === $selected_category) active @endif" 
                             data-filter="category" data-value="{{ $category->slug }}">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">{{ $category->name }}</span>
                            <span class="filter-count">{{ $filter_counts['categories'][$category->id] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sub Category Filter -->
            @if (isset($sub_categories) && $sub_categories && $sub_categories->count() > 0)
                <div class="filter-section">
                    <h3 class="filter-title">
                        <span><i class="fas fa-tags me-1.5" style="color: #2563eb;"></i> Sub Categories</span>
                    </h3>
                    <div class="filter-options">
                        <div class="filter-option @if (!$selected_sub_category) active @endif" 
                             data-filter="sub_category" data-value="">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">All Sub Categories</span>
                            <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                        </div>
                        @foreach ($sub_categories as $sub_category)
                            <div class="filter-option @if ($sub_category->slug === $selected_sub_category) active @endif" 
                                 data-filter="sub_category" data-value="{{ $sub_category->slug }}">
                                <div class="filter-checkbox"></div>
                                <span class="filter-label">{{ $sub_category->name }}</span>
                                <span class="filter-count">{{ $filter_counts['sub_categories'][$sub_category->slug] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Third Category Filter -->
            @if (isset($third_categories) && $third_categories && $third_categories->count() > 0)
                <div class="filter-section">
                    <h3 class="filter-title">
                        <span><i class="fas fa-sitemap me-1.5" style="color: #2563eb;"></i> Sub-Level Categories</span>
                    </h3>
                    <div class="filter-options">
                        @php
                            $thirdCategoryParam = request('third_category');
                            $selectedThirdCategories = [];
                            if ($thirdCategoryParam) {
                                $selectedThirdCategories = is_array($thirdCategoryParam) ? $thirdCategoryParam : [$thirdCategoryParam];
                            }
                        @endphp
                        <div class="filter-option @if (empty($selectedThirdCategories)) active @endif" 
                             data-filter="third_category" data-value="">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">All Sub-Levels</span>
                            <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                        </div>
                        @foreach ($third_categories as $third_category)
                            <div class="filter-option @if (in_array($third_category->slug, $selectedThirdCategories)) active @endif" 
                                 data-filter="third_category" data-value="{{ $third_category->slug }}">
                                <div class="filter-checkbox"></div>
                                <span class="filter-label">{{ $third_category->name }}</span>
                                <span class="filter-count">{{ $filter_counts['third_categories'][$third_category->slug] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Brand Filter -->
            @if (isset($brands) && $brands && $brands->count() > 0)
                <div class="filter-section">
                    <h3 class="filter-title">
                        <span><i class="fas fa-award me-1.5" style="color: #2563eb;"></i> Brands</span>
                    </h3>
                    <div class="filter-options">
                        <div class="filter-option @if (!request('brand')) active @endif" 
                             data-filter="brand" data-value="">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">All Brands</span>
                            <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                        </div>
                        @foreach ($brands as $brand)
                            @php
                                $brandParam = request('brand');
                                $isBrandSelected = false;
                                if ($brandParam) {
                                    if (is_array($brandParam)) {
                                        $isBrandSelected = in_array($brand->id, $brandParam);
                                    } else {
                                        $isBrandSelected = $brand->id == $brandParam;
                                    }
                                }
                            @endphp
                            <div class="filter-option @if ($isBrandSelected) active @endif" 
                                 data-filter="brand" data-value="{{ $brand->id }}">
                                <div class="filter-checkbox"></div>
                                <span class="filter-label">
                                    @if($brand->logo)
                                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo-filter">
                                    @endif
                                    {{ $brand->name }}
                                </span>
                                <span class="filter-count">{{ $filter_counts['brands'][$brand->id] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="product-result-right">
            <div class="product-header">
                <div class="product-count">
                    @if(isset($search_query) && $search_query)
                        @if(isset($category_obj) && $category_obj)
                            <span id="productCount">{{ $products->count() }}</span> Products Found for "<strong>{{ $search_query }}</strong>" in <strong>{{ $category_obj->name }}</strong>
                        @else
                            <span id="productCount">{{ $products->count() }}</span> Products Found for "<strong>{{ $search_query }}</strong>"
                        @endif
                    @else
                        <span id="productCount">{{ $products->count() }}</span> Products Found
                    @endif
                </div>
                <div class="product-controls">
                    <button id="clearFilters" class="clear-filters-btn" style="display: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Clear All Filters
                    </button>
                    <select name="sort" id="sort" class="sort-select">
                        <option value="latest" @if ($sort_value === 'latest') selected @endif>Latest</option>
                        <option value="price_desc" @if ($sort_value === 'price_desc') selected @endif>Price High to Low</option>
                        <option value="price_asc" @if ($sort_value === 'price_asc') selected @endif>Price Low to High</option>
                    </select>
                </div>
            </div>

            <!-- Selected Filters Display -->
            <div class="selected-filters" id="selectedFilters" style="display: none;">
                <div class="selected-filters-label">Active Filters:</div>
                <div class="selected-filters-tags" id="selectedFiltersTags"></div>
            </div>

            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Loading products...</p>
            </div>

            <div class="category-products-grid" id="productsGrid">
                @foreach ($products as $product)
                    @include('frontend.partials.product-item', [
                        'product' => $product,
                        'badge' => 'SHOP',
                        'buttonText' => 'View Product'
                    ])
                @endforeach
                @if (count($products) === 0)
                    <h2 class="no-products">No products found</h2>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        class ShopFilter {
            constructor() {
                this.filters = {
                    category: [],
                    sub_category: [],
                    third_category: [],
                    writer: [],
                    publisher: [],
                    brand: [],
                    rating: [],
                    price_min: {{ $price_min }},
                    price_max: {{ $price_max }},
                    sort: '{{ $sort_value }}'
                };
                
                // Initialize arrays from request parameters
                if ('{{ $selected_category }}') {
                    this.filters.category = ['{{ $selected_category }}'];
                }
                if ('{{ $selected_sub_category }}') {
                    this.filters.sub_category = ['{{ $selected_sub_category }}'];
                }
                @if(request("third_category"))
                    this.filters.third_category = {!! is_array(request("third_category")) ? json_encode(request("third_category")) : json_encode([request("third_category")]) !!};
                @endif
                @if(request("writer"))
                    this.filters.writer = {!! is_array(request("writer")) ? json_encode(request("writer")) : json_encode([request("writer")]) !!};
                @endif
                @if(request("publisher"))
                    this.filters.publisher = {!! is_array(request("publisher")) ? json_encode(request("publisher")) : json_encode([request("publisher")]) !!};
                @endif
                @if(request("brand"))
                    this.filters.brand = {!! is_array(request("brand")) ? json_encode(request("brand")) : json_encode([request("brand")]) !!};
                @endif
                @if(request("rating"))
                    this.filters.rating = {!! is_array(request("rating")) ? json_encode(request("rating")) : json_encode([request("rating")]) !!};
                @endif
                
                // Add debounce timer for price inputs
                this.priceDebounceTimer = null;
                
                this.init();
            }

            init() {
                this.bindEvents();
                this.initMobileFilter();
                this.updateClearFiltersButton(); // Initial call to set button visibility
                this.updateSelectedFiltersDisplay(); // Initial call to display filters
            }

            bindEvents() {
                // Filter option clicks
                document.querySelectorAll('.filter-option').forEach(option => {
                    option.addEventListener('click', (e) => {
                        this.handleFilterClick(e.currentTarget);
                    });
                });

                // Clear filters button
                document.getElementById('clearFilters').addEventListener('click', () => {
                    this.clearAllFilters();
                });

                // Sort select change
                document.getElementById('sort').addEventListener('change', (e) => {
                    this.filters.sort = e.target.value;
                    this.applyFilters();
                });

                // Price range inputs with debouncing
                document.getElementById('priceMin').addEventListener('input', (e) => {
                    this.filters.price_min = parseInt(e.target.value) || 0;
                    this.debouncePriceFilter();
                });

                document.getElementById('priceMax').addEventListener('input', (e) => {
                    this.filters.price_max = parseInt(e.target.value) || 1000;
                    this.debouncePriceFilter();
                });

                // Price range slider with debouncing
                document.getElementById('priceRange').addEventListener('input', (e) => {
                    this.filters.price_max = parseInt(e.target.value);
                    document.getElementById('priceMax').value = e.target.value;
                    this.debouncePriceFilter();
                });
            }

            handleFilterClick(option) {
                const filterType = option.dataset.filter;
                const filterValue = option.dataset.value;

                // Handle multiple selection for all filter types
                if (filterType === 'category' || filterType === 'sub_category' || filterType === 'third_category' || filterType === 'writer' || filterType === 'publisher' || filterType === 'brand' || filterType === 'rating') {
                    if (option.classList.contains('active')) {
                        // Remove from selection
                        option.classList.remove('active');
                        const index = this.filters[filterType].indexOf(filterValue);
                        if (index > -1) {
                            this.filters[filterType].splice(index, 1);
                        }
                    } else {
                        // Add to selection
                        option.classList.add('active');
                        if (!this.filters[filterType].includes(filterValue)) {
                            this.filters[filterType].push(filterValue);
                        }
                    }
                } else {
                    // Single selection for other filters (price range)
                    if (option.classList.contains('active')) {
                        option.classList.remove('active');
                        this.filters[filterType] = '';
                    } else {
                        // Remove active class from all options in the same section
                        const section = option.closest('.filter-section');
                        section.querySelectorAll('.filter-option').forEach(opt => {
                            opt.classList.remove('active');
                        });

                        // Add active class to clicked option
                        option.classList.add('active');
                        this.filters[filterType] = filterValue;
                    }
                }

                // Show/hide clear filters button based on active filters
                this.updateClearFiltersButton();

                // Update selected filters display
                this.updateSelectedFiltersDisplay();

                // Apply filters
                this.applyFilters();
            }

            updateClearFiltersButton() {
                const clearBtn = document.getElementById('clearFilters');
                const hasActiveFilters = 
                    this.filters.category.length > 0 ||
                    this.filters.sub_category.length > 0 ||
                    this.filters.third_category.length > 0 ||
                    this.filters.writer.length > 0 ||
                    this.filters.publisher.length > 0 ||
                    this.filters.rating.length > 0 ||
                    this.filters.price_min > 0 ||
                    this.filters.price_max < 1000;
                
                if (hasActiveFilters) {
                    clearBtn.style.display = 'flex';
                } else {
                    clearBtn.style.display = 'none';
                }
            }

            updateSelectedFiltersDisplay() {
                const selectedFiltersContainer = document.getElementById('selectedFilters');
                const selectedFiltersTags = document.getElementById('selectedFiltersTags');
                
                // Clear existing tags
                selectedFiltersTags.innerHTML = '';
                
                const activeFilters = [];
                
                // Check each filter type and create tags for active ones
                if (this.filters.category && this.filters.category.length > 0) {
                    this.filters.category.forEach(categorySlug => {
                        const categoryOption = document.querySelector(`[data-filter="category"][data-value="${categorySlug}"]`);
                        if (categoryOption) {
                            const label = categoryOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'category',
                                value: categorySlug,
                                label: `Category: ${label}`
                            });
                        }
                    });
                }

                if (this.filters.sub_category && this.filters.sub_category.length > 0) {
                    this.filters.sub_category.forEach(subCategorySlug => {
                        const subCategoryOption = document.querySelector(`[data-filter="sub_category"][data-value="${subCategorySlug}"]`);
                        if (subCategoryOption) {
                            const label = subCategoryOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'sub_category',
                                value: subCategorySlug,
                                label: `Sub Category: ${label}`
                            });
                        }
                    });
                }

                if (this.filters.third_category && this.filters.third_category.length > 0) {
                    this.filters.third_category.forEach(thirdCategorySlug => {
                        const thirdCategoryOption = document.querySelector(`[data-filter="third_category"][data-value="${thirdCategorySlug}"]`);
                        if (thirdCategoryOption) {
                            const label = thirdCategoryOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'third_category',
                                value: thirdCategorySlug,
                                label: `Third Category: ${label}`
                            });
                        }
                    });
                }
                
                if (this.filters.writer && this.filters.writer.length > 0) {
                    this.filters.writer.forEach(writerId => {
                        const writerOption = document.querySelector(`[data-filter="writer"][data-value="${writerId}"]`);
                        if (writerOption) {
                            const label = writerOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'writer',
                                value: writerId,
                                label: `Writer: ${label}`
                            });
                        }
                    });
                }
                
                if (this.filters.publisher && this.filters.publisher.length > 0) {
                    this.filters.publisher.forEach(publisherId => {
                        const publisherOption = document.querySelector(`[data-filter="publisher"][data-value="${publisherId}"]`);
                        if (publisherOption) {
                            const label = publisherOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'publisher',
                                value: publisherId,
                                label: `Publisher: ${label}`
                            });
                        }
                    });
                }
                
                if (this.filters.brand && this.filters.brand.length > 0) {
                    this.filters.brand.forEach(brandId => {
                        const brandOption = document.querySelector(`[data-filter="brand"][data-value="${brandId}"]`);
                        if (brandOption) {
                            const label = brandOption.querySelector('.filter-label').textContent.trim();
                            activeFilters.push({
                                type: 'brand',
                                value: brandId,
                                label: `Brand: ${label}`
                            });
                        }
                    });
                }
                
                if (this.filters.rating && this.filters.rating.length > 0) {
                    this.filters.rating.forEach(ratingValue => {
                        const ratingOption = document.querySelector(`[data-filter="rating"][data-value="${ratingValue}"]`);
                        if (ratingOption) {
                            // Create star display for the rating
                            const stars = '★'.repeat(parseInt(ratingValue));
                            const emptyStars = '☆'.repeat(5 - parseInt(ratingValue));
                            const ratingLabel = `Rating: ${stars}${emptyStars} (${ratingValue}+ stars)`;
                            
                            activeFilters.push({
                                type: 'rating',
                                value: ratingValue,
                                label: ratingLabel
                            });
                        }
                    });
                }
                
                if (this.filters.price_min > 0 || this.filters.price_max < 1000) {
                    const priceLabel = `Price: ৳${this.filters.price_min} - ৳${this.filters.price_max}`;
                    activeFilters.push({
                        type: 'price',
                        value: `${this.filters.price_min}-${this.filters.price_max}`,
                        label: priceLabel
                    });
                }
                
                // Create and append filter tags
                activeFilters.forEach(filter => {
                    const tag = document.createElement('button');
                    tag.className = 'filter-tag';
                    tag.dataset.filterType = filter.type;
                    tag.dataset.filterValue = filter.value;
                    
                    tag.innerHTML = `
                        <span>${filter.label}</span>
                        <svg class="remove-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    `;
                    
                    tag.addEventListener('click', () => {
                        this.removeFilter(filter.type, filter.value);
                    });
                    
                    selectedFiltersTags.appendChild(tag);
                });
                
                // Show/hide the selected filters container
                if (activeFilters.length > 0) {
                    selectedFiltersContainer.style.display = 'flex';
                } else {
                    selectedFiltersContainer.style.display = 'none';
                }
            }

            removeFilter(filterType, filterValue) {
                if (filterType === 'price') {
                    // Reset price range
                    this.filters.price_min = 0;
                    this.filters.price_max = 1000;
                    document.getElementById('priceMin').value = 0;
                    document.getElementById('priceMax').value = 1000;
                    document.getElementById('priceRange').value = 1000;
                } else if (filterType === 'category' || filterType === 'sub_category' || filterType === 'third_category' || filterType === 'writer' || filterType === 'publisher' || filterType === 'brand' || filterType === 'rating') {
                    // Remove specific item from multiple selection
                    const index = this.filters[filterType].indexOf(filterValue);
                    if (index > -1) {
                        this.filters[filterType].splice(index, 1);
                    }
                    
                    // Remove active class from the corresponding filter option
                    const filterOption = document.querySelector(`[data-filter="${filterType}"][data-value="${filterValue}"]`);
                    if (filterOption) {
                        filterOption.classList.remove('active');
                    }
                }
                
                // Update displays and apply filters
                this.updateClearFiltersButton();
                this.updateSelectedFiltersDisplay();
                this.applyFilters();
            }

            async applyFilters() {
                this.showLoading();

                try {
                    const formData = new FormData();
                    
                    // Add single value filters
                    if (this.filters.sort) formData.append('sort', this.filters.sort);
                    if (this.filters.price_min !== undefined) formData.append('price_min', this.filters.price_min);
                    if (this.filters.price_max !== undefined) formData.append('price_max', this.filters.price_max);
                    
                    // Add array filters
                    if (this.filters.category && this.filters.category.length > 0) {
                        this.filters.category.forEach(categorySlug => {
                            formData.append('category[]', categorySlug);
                        });
                    }
                    
                    if (this.filters.sub_category && this.filters.sub_category.length > 0) {
                        this.filters.sub_category.forEach(subCategorySlug => {
                            formData.append('sub_category[]', subCategorySlug);
                        });
                    }
                    
                    if (this.filters.third_category && this.filters.third_category.length > 0) {
                        this.filters.third_category.forEach(thirdCategorySlug => {
                            formData.append('third_category[]', thirdCategorySlug);
                        });
                    }
                    
                    if (this.filters.writer && this.filters.writer.length > 0) {
                        this.filters.writer.forEach(writerId => {
                            formData.append('writer[]', writerId);
                        });
                    }
                    
                    if (this.filters.publisher && this.filters.publisher.length > 0) {
                        this.filters.publisher.forEach(publisherId => {
                            formData.append('publisher[]', publisherId);
                        });
                    }
                    
                    if (this.filters.rating && this.filters.rating.length > 0) {
                        this.filters.rating.forEach(ratingValue => {
                            formData.append('rating[]', ratingValue);
                        });
                    }
                    
                    if (this.filters.brand && this.filters.brand.length > 0) {
                        this.filters.brand.forEach(brandId => {
                            formData.append('brand[]', brandId);
                        });
                    }

                    const response = await fetch('{{ route("shop.filter") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.updateProducts(data.html, data.count);
                    } else {
                        console.error('Filter request failed');
                    }
                } catch (error) {
                    console.error('Error applying filters:', error);
                } finally {
                    this.hideLoading();
                }
            }

            updateProducts(html, count) {
                const productsGrid = document.getElementById('productsGrid');
                const productCount = document.getElementById('productCount');
                
                productsGrid.innerHTML = html;
                productCount.textContent = count;
                
                // Initialize image preloader for new images
                if (window.handleNewProductImages) {
                    window.handleNewProductImages();
                }
            }

            showLoading() {
                document.getElementById('loading').classList.add('show');
                document.getElementById('productsGrid').style.opacity = '0.5';
            }

            hideLoading() {
                document.getElementById('loading').classList.remove('show');
                document.getElementById('productsGrid').style.opacity = '1';
            }

            debouncePriceFilter() {
                // Clear existing timer
                if (this.priceDebounceTimer) {
                    clearTimeout(this.priceDebounceTimer);
                }
                
                // Set new timer - wait 500ms after user stops typing
                this.priceDebounceTimer = setTimeout(() => {
                    this.updateClearFiltersButton();
                    this.updateSelectedFiltersDisplay();
                    this.applyFilters();
                }, 500);
            }

            clearAllFilters() {
                this.filters = {
                    category: [],
                    sub_category: [],
                    third_category: [],
                    writer: [],
                    publisher: [],
                    rating: [],
                    price_min: 0,
                    price_max: 1000,
                    sort: 'latest'
                };
                document.getElementById('priceMin').value = 0;
                document.getElementById('priceMax').value = 1000;
                document.getElementById('priceRange').value = 1000;
                this.updateClearFiltersButton();
                this.updateSelectedFiltersDisplay();
                this.applyFilters();
            }

            initMobileFilter() {
                const mobileFilterBtn = document.getElementById('mobileFilterBtn');
                const filterSidebar = document.getElementById('filterSidebar');
                const overlay = document.querySelector('.overlay');

                if (mobileFilterBtn && filterSidebar && overlay) {
                    mobileFilterBtn.addEventListener('click', () => {
                        filterSidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                    });

                    overlay.addEventListener('click', () => {
                        filterSidebar.classList.remove('show');
                        overlay.classList.remove('show');
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new ShopFilter();
        });
    </script>
@endsection