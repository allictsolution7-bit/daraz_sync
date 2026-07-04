@extends('frontend.app')
@section('styles')
    <style>
        /* Modern Shop Page Styles */
        .shop-container {
            padding: 20px;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }

        #shop-heading {
            grid-column: 1 / -1;
            font-size: 32px;
            color: var(--secondary-color);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            text-align: center;
            font-weight: 600;
        }

        /* Modern Filter Sidebar */
        .filter-sidebar {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .filter-section {
            margin-bottom: 10px;
        }

        .filter-section:last-child {
            margin-bottom: 0;
        }

        .filter-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 0px;
        }

        .filter-option {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 2px 5px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            margin: 3px;
        }

        .filter-options .filter-option:first-child {
                margin-bottom: 5px;
                padding: 5px 5px;
            }

        .filter-option:hover {
            background: #FFF5F2;
            border-color: var(--primary-color);
        }

        .filter-option.active {
            background: var(--primary-color);
            color: white;
        }

        .filter-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            position: relative;
        }

        .filter-option.active .filter-checkbox {
            background: white;
            border-color: white;
        }
        
        /* Brand Filter Styles */
        .brand-logo-filter {
            width: 16px;
            height: 16px;
            object-fit: contain;
            border-radius: 3px;
            margin-right: 4px;
            vertical-align: middle;
        }

        .filter-option.active .filter-checkbox::after {
            content: '✓';
            color: var(--primary-color);
            font-weight: bold;
            font-size: 12px;
        }

        .filter-label {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .filter-count {
            background: #f0f0f0;
            color: #666;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }

        .filter-option.active .filter-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Rating Filter Styles */
        .rating-stars-display {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .rating-stars-display .star-icon {
            flex-shrink: 0;
        }

        .rating-stars-display .star-icon.filled {
            color: #f59e0b;
        }

        .rating-stars-display .star-icon.empty {
            color: #e5e7eb;
        }

        .rating-text {
            margin-left: 4px;
            font-size: 12px;
            color: inherit;
        }

        .filter-option.active .rating-text {
            color: white;
        }

        /* Price Range Slider */
        .price-range-container {
            padding: 10px 0;
        }

        .price-range-slider {
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: #e0e0e0;
            outline: none;
            -webkit-appearance: none;
            margin: 10px 0;
        }

        .price-range-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .price-range-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .price-range-values {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .price-input {
            width: 80px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
        }

        .price-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        /* Product Grid */
        .product-result-right {
            background: white;
            padding: 7px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .product-count {
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }

        .product-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .clear-filters-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clear-filters-btn:hover {
            background: #e9ecef;
            color: #333;
            border-color: #adb5bd;
        }

        .clear-filters-btn svg {
            width: 14px;
            height: 14px;
        }

        /* Selected Filters Display */
        .selected-filters {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .selected-filters-label {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            white-space: nowrap;
        }

        .selected-filters-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-tag {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--primary-color);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .filter-tag:hover {
            background: #E55A2B;
            transform: translateY(-1px);
        }

        .filter-tag .remove-icon {
            width: 12px;
            height: 12px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .filter-tag:hover .remove-icon {
            opacity: 1;
        }

        .sort-select {
            padding: 8px 11px;
            border: 2px solid #eee;
            border-radius: 8px;
            color: #333;
            outline: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            background: white;
            transition: all 0.3s ease;
        }

        .sort-select:focus {
            border-color: var(--primary-color);
        }

        .category-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .category-product {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .category-product:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .category-product .product-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-product:hover .product-image {
            transform: scale(1.05);
        }

        .category-product .product-info {
            padding: 20px;
        }

        .category-product .product-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-align: center;
        }

        .category-product .product-price {
            display: flex;
            gap: 12px;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .category-product .current-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .category-product .original-price {
            font-size: 16px;
            color: #999;
            text-decoration: line-through;
        }

        .category-product .add-to-cart {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color), #FF8C42);
            color: white;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .category-product .add-to-cart:hover {
            background: linear-gradient(135deg, #E55A2B, var(--primary-color));
            transform: translateY(-2px);
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #666;
            font-size: 18px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        /* Loading State */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }



        /* Mobile Filter Button */
        .mobile-filter-btn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.3);
            z-index: 1000;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mobile-filter-btn:hover {
            transform: scale(1.1);
        }

        .mobile-filter-btn svg {
            width: 24px;
            height: 24px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .shop-container {
                grid-template-columns: 280px 1fr;
                gap: 20px;
                padding: 15px;
            }
        }

        @media (max-width: 992px) {
            .shop-container {
                grid-template-columns: 1fr;
            }

            .mobile-filter-btn {
                display: flex;
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
                padding-bottom: 30px;
                background: white;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
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
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .overlay.show {
                display: block;
            }

            .category-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .category-products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .product-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .product-controls {
                flex-direction: column;
                gap: 10px;
            }

            .clear-filters-btn {
                width: 100%;
                justify-content: center;
            }

            .sort-select {
                width: 100%;
            }

            .selected-filters {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .selected-filters-label {
                font-size: 13px;
            }

            .selected-filters-tags {
                width: 100%;
            }

            .filter-tag {
                font-size: 11px;
                padding: 5px 10px;
            }
        }

        @media (max-width: 576px) {
            .shop-container {
                padding: 0px;
            }

            #shop-heading {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .category-products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .category-product .product-image {
                height: 200px;
            }

            .category-product .product-info {
                padding: 15px;
            }

            .category-product .product-title {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .category-product .current-price {
                font-size: 16px;
            }

            .category-product .original-price {
                font-size: 14px;
            }

            .category-product .add-to-cart {
                padding: 12px;
                font-size: 14px;
            }
        }

        /* Category Hero Section */
        .category-hero {
            position: relative;
            max-width: 1920px;
            margin: 0 auto;
            height: 320px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .category-hero-overlay {
            width: 100%;
            height: 100%;
            background: rgba(30, 30, 30, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-hero-content {
            color: #fff;
            max-width: 1340px;
            padding: 1rem 0;
            text-align: center;
        }

        .category-hero-content h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .category-hero-content p {
            font-size: 1.2rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }



        @media only screen and (max-width: 800px) {
            .category-hero-content {
                padding: 1rem;
            }

            .category-hero-content h1 {
                font-size: 1.8rem;
                margin-bottom: 0.5rem;
            }

            .category-hero-content p {
                font-size: 1rem;
                margin-bottom: 1rem;
            }

            .category-hero {
                height: auto;
                min-height: 200px;
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
        $desc = $third_category_obj->description ?? '';
    } elseif (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->background_image) {
        $bgImage = asset('category_background_images/' . $sub_category_obj->background_image);
        $title = $sub_category_obj->name;
        $desc = $sub_category_obj->description ?? '';
    } elseif (isset($category_obj) && $category_obj && $category_obj->background_image) {
        $bgImage = asset($category_obj->background_image);
        $title = $category_obj->name;
        $desc = $category_obj->description ?? '';
    } elseif (isset($globalBgImage) && !empty($globalBgImage)) {
        $bgImage = asset($globalBgImage);
        $title = isset($third_category_obj) && $third_category_obj
            ? $third_category_obj->name
            : (isset($category_obj) && $category_obj
                ? $category_obj->name
                : (isset($sub_category_obj) && $sub_category_obj
                    ? $sub_category_obj->name
                    : 'Shop'));
        $desc = isset($third_category_obj) && $third_category_obj && $third_category_obj->description
            ? $third_category_obj->description
            : (isset($category_obj) && $category_obj && $category_obj->description
                ? $category_obj->description
                : (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->description
                    ? $sub_category_obj->description
                    : 'Shop the best deals on quality products with fast delivery, secure checkout, and unbeatable customer support.'));
    } else {
        $title = isset($third_category_obj) && $third_category_obj
            ? $third_category_obj->name
            : (isset($category_obj) && $category_obj
                ? $category_obj->name
                : (isset($sub_category_obj) && $sub_category_obj
                    ? $sub_category_obj->name
                    : 'Shop'));
        $desc = isset($third_category_obj) && $third_category_obj && $third_category_obj->description
            ? $third_category_obj->description
            : (isset($category_obj) && $category_obj && $category_obj->description
                ? $category_obj->description
                : (isset($sub_category_obj) && $sub_category_obj && $sub_category_obj->description
                    ? $sub_category_obj->description
                    : 'Shop the best deals on quality products with fast delivery, secure checkout, and unbeatable customer support.'));
    }
    @endphp

    <section class="category-hero" style="{{ $bgImage ? "background-image: url('$bgImage')" : 'background: linear-gradient(135deg, ' . \App\Services\SettingsService::getPrimaryColor() . ', #FF8C42);' }}">
        <div class="category-hero-overlay">
            <div class="category-hero-content">
                <h1>{{ $title }}</h1>
                @if ($desc)
                    <p>{{ $desc }}</p>
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
            <!-- Price Range Filter -->
            <div class="filter-section">
                <h3 class="filter-title">Price Range</h3>
                <div class="price-range-container">
                    <input type="range" class="price-range-slider" id="priceRange" 
                           min="0" max="1000" value="{{ $price_max }}" step="10">
                    <div class="price-range-values">
                        <input type="number" class="price-input" id="priceMin" value="{{ $price_min }}" placeholder="Min">
                        <span>-</span>
                        <input type="number" class="price-input" id="priceMax" value="{{ $price_max }}" placeholder="Max">
                    </div>
                </div>
            </div>
            <!-- Rating Filter -->
            {{-- <div class="filter-section">
                <h3 class="filter-title">Rating</h3>
                <div class="filter-options">
                    <div class="filter-option @if (!request('rating')) active @endif" 
                         data-filter="rating" data-value="">
                        <div class="filter-checkbox"></div>
                        <span class="filter-label">All Ratings</span>
                        <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                    </div>
                    @for ($rating = 5; $rating >= 1; $rating--)
                        <div class="filter-option @if (request('rating') == $rating) active @endif" 
                             data-filter="rating" data-value="{{ $rating }}">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">
                                <div class="rating-stars-display">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $rating)
                                            <svg class="star-icon filled" viewBox="0 0 24 24" width="14" height="14">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#f59e0b"/>
                                            </svg>
                                        @else
                                            <svg class="star-icon empty" viewBox="0 0 24 24" width="14" height="14">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#e5e7eb"/>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="rating-text">& Up</span>
                                </div>
                            </span>
                            <span class="filter-count">{{ $filter_counts['ratings'][$rating] ?? 0 }}</span>
                        </div>
                    @endfor
                </div>
            </div> --}}
            <!-- Category Filter -->
            <div class="filter-section">
                <h3 class="filter-title">Categories</h3>
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
                    <h3 class="filter-title">Sub Categories</h3>
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
                    <h3 class="filter-title">Third Level Categories</h3>
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
                            <span class="filter-label">All Third Categories</span>
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
                    <h3 class="filter-title">Brands</h3>
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

            <!-- Writer Filter -->
            {{-- <div class="filter-section">
                <h3 class="filter-title">Writers</h3>
                <div class="filter-options">
                    <div class="filter-option @if (!request('writer')) active @endif" 
                         data-filter="writer" data-value="">
                        <div class="filter-checkbox"></div>
                        <span class="filter-label">All Writers</span>
                        <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                    </div>
                    @foreach($writers as $writer)
                        <div class="filter-option @if (request('writer') == $writer->id) active @endif" 
                             data-filter="writer" data-value="{{ $writer->id }}">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">{{ $writer->name }}</span>
                            <span class="filter-count">{{ $filter_counts['writers'][$writer->id] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div> --}}

            <!-- Publisher Filter -->
            {{-- <div class="filter-section">
                <h3 class="filter-title">Publishers</h3>
                <div class="filter-options">
                    <div class="filter-option @if (!request('publisher')) active @endif" 
                         data-filter="publisher" data-value="">
                        <div class="filter-checkbox"></div>
                        <span class="filter-label">All Publishers</span>
                        <span class="filter-count">{{ $filter_counts['total_products'] }}</span>
                    </div>
                    @foreach($publishers as $publisher)
                        <div class="filter-option @if (request('publisher') == $publisher->id) active @endif" 
                             data-filter="publisher" data-value="{{ $publisher->id }}">
                            <div class="filter-checkbox"></div>
                            <span class="filter-label">{{ $publisher->name }}</span>
                            <span class="filter-count">{{ $filter_counts['publishers'][$publisher->id] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div> --}}
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