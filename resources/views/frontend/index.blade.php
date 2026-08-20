@extends('frontend.app')
@section('content')

    <!-- Main Slider -->
    @if (!empty($homepage['enable_main_slider_section']) && $homepage['enable_main_slider_section'])
        @php
            $sliderHeight = $homepage['slider_height'] ?? 300;
            $sliderHeightTablet = $homepage['slider_height_tablet'] ?? 200;
            $sliderHeightMobile = $homepage['slider_height_mobile'] ?? 170;
            $sliderLayout = $homepage['slider_layout'] ?? 'category_slider';
            $showCategoryMega = $sliderLayout === 'category_slider';
            $sliderSideImage = $homepage['slider_side_image'] ?? null;
            $sliderSideImageAlt = $homepage['slider_side_image_alt'] ?? '';
            $sliderSideImageLink = $homepage['slider_side_image_link'] ?? '#';
            $sliderSideImageOne = $homepage['slider_side_image_one'] ?? ($homepage['slider_side_image'] ?? null);
            $sliderSideImageOneAlt = $homepage['slider_side_image_one_alt'] ?? ($homepage['slider_side_image_alt'] ?? '');
            $sliderSideImageOneLink = $homepage['slider_side_image_one_link'] ?? ($homepage['slider_side_image_link'] ?? '#');
            $sliderSideImageTwo = $homepage['slider_side_image_two'] ?? null;
            $sliderSideImageTwoAlt = $homepage['slider_side_image_two_alt'] ?? '';
            $sliderSideImageTwoLink = $homepage['slider_side_image_two_link'] ?? '#';
            $sliderHalfHeight = max(50, (int) floor($sliderHeight / 2));
            $sliderHalfHeightTablet = max(40, (int) floor($sliderHeightTablet / 2));
            $sliderHalfHeightMobile = max(30, (int) floor($sliderHeightMobile / 2));

            // Load categories with sub and third levels for mega menu (only when needed)
            $sliderCategoriesList = isset($sliderMegaCategories) ? $sliderMegaCategories : collect();
            $sliderCategoryFlags = isset($sliderMegaCategoryFlags) ? $sliderMegaCategoryFlags : [];
            $sliderCategoryProducts = isset($sliderMegaCategoryProducts) ? $sliderMegaCategoryProducts : [];
        @endphp
        {{-- Start Slider --}}
        <style>
            .slider-layout {
                display: grid;
                grid-template-columns: 260px 1fr;
                gap: 12px;
                align-items: stretch;
                position: relative;
                overflow: visible;
            }

            .slider-one-image-layout {
                display: grid;
                grid-template-columns: 1.5fr 0.5fr;
                gap: 12px;
                align-items: stretch;
            }

            .slider-side-image {
                border-radius: 10px;
                overflow: hidden;
                position: relative;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                height: 100%;
            }

            .slider-side-image img {
                width: 100%;
                height: {{ $sliderHeight }}px;
                object-fit: cover;
                display: block;
            }

            .slider-two-image-layout {
                display: grid;
                grid-template-columns: 1.2fr 0.8fr;
                gap: 12px;
                align-items: stretch;
            }

            .slider-side-image-half {
                border-radius: 10px;
                overflow: hidden;
                position: relative;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                height: {{ $sliderHalfHeight }}px;
            }

            .slider-side-image-half + .slider-side-image-half {
                margin-top: 10px;
            }

            .slider-side-image-half img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .mega-products {
                margin-top: 14px;
                padding-top: 10px;
                border-top: 1px solid color-mix(in srgb, var(--secondary-color) 10%, transparent);
            }

            .mega-products-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
            }

            .mega-products-header h5 {
                margin: 0;
                font-size: 15px;
                font-weight: 700;
                color: var(--secondary-color);
            }

            .mega-products-header .view-all-link {
                font-size: 13px;
                font-weight: 600;
                color: var(--primary-color);
                text-decoration: none;
            }

            .mega-products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 12px;
            }

            .mega-product-card {
                display: flex;
                gap: 10px;
                text-decoration: none;
                color: inherit;
                background: #fff;
                border: 1px solid #eef2f5;
                border-radius: 10px;
                padding: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
                transition: transform 0.15s ease, box-shadow 0.15s ease;
            }

            .mega-product-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            }

            .mega-product-img {
                width: 70px;
                height: 90px;
                border-radius: 8px;
                overflow: hidden;
                flex-shrink: 0;
                background: #f7f8fb;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mega-product-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .mega-product-info {
                display: flex;
                flex-direction: column;
                gap: 6px;
                min-width: 0;
            }

            .mega-product-title {
                font-size: 14px;
                font-weight: 600;
                color: var(--secondary-color);
                line-height: 1.3;
                max-height: 36px;
                overflow: hidden;
            }

            .mega-product-price {
                font-size: 14px;
                font-weight: 700;
                color: var(--primary-color);
            }

            .mega-product-price-row {
                display: flex;
                align-items: center;
                gap: 6px;
                flex-wrap: wrap;
            }

            .mega-product-oldprice {
                font-size: 13px;
                color: #8b94a7;
                text-decoration: line-through;
            }
            .mega-loading {
                padding: 12px;
                text-align: center;
                color: var(--secondary-color);
                font-weight: 600;
            }
                text-align: center;
                color: var(--secondary-color);
                font-weight: 600;
            }

            @media (max-width: 992px) {
                .slider-one-image-layout {
                    grid-template-columns: 1fr;
                }

                .slider-side-image {
                    display: none;
                    height: {{ $sliderHeightTablet }}px;
                }

                .slider-two-image-layout {
                    grid-template-columns: 1fr;
                }

                .slider-side-image-half {
                    height: {{ $sliderHalfHeightTablet }}px;
                }
            }

            @media (max-width: 768px) {
                .slider-side-image {
                    height: {{ $sliderHeightMobile }}px;
                }

                .slider-side-image-half {
                    height: {{ $sliderHalfHeightMobile }}px;
                }
            }

            .slider-category-menu {
                background: #ffffff;
                /* background: var(--light-color); */
                /* border: 1px solid color-mix(in srgb, var(--secondary-color) 12%, transparent); */
                border: 1px solid var(--primary-color);
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                overflow: visible;
                min-height: {{ $sliderHeight }}px;
                max-height: {{ $sliderHeight }}px;
                display: flex;
                flex-direction: column;
                position: relative;
                z-index: 40;
            }

            .slider-category-heading {
                display: none;
                padding: 12px 14px;
                background: var(--secondary-color);
                color: var(--light-color);
                font-weight: 700;
                font-size: 14px;
                letter-spacing: 0.2px;
            }

            .slider-category-list {
                list-style: none;
                padding: 0;
                margin: 0;
                overflow-y: auto;
                flex: 1;
                position: relative;
                z-index: 41;
            }

            .slider-category-item>a {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 12px;
                text-decoration: none;
                color: var(--secondary-color);
                font-weight: 600;
                font-size: 14px;
                border-bottom: 1px solid #f1f5f9;
                border-bottom: 1px solid color-mix(in srgb, var(--secondary-color) 10%, transparent);
                transition: all 0.2s ease;
                gap: 10px;
            }

            .slider-category-item>a:hover {
                background: #f8fafc;
                background: color-mix(in srgb, var(--primary-color) 12%, var(--light-color));
                color: var(--primary-color);
            }

            .slider-category-item .chevron {
                color: var(--primary-color);
                margin-left: 8px;
                flex-shrink: 0;
            }

            .slider-category-thumb {
                width: 28px;
                height: 28px;
                border-radius: 6px;
                overflow: hidden;
                background: color-mix(in srgb, var(--secondary-color) 12%, transparent);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .slider-category-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .slider-category-label {
                flex: 1;
            }

            .slider-category-sub {
                list-style: none;
                margin: 0;
                padding: 8px 0;
                background: color-mix(in srgb, var(--secondary-color) 6%, var(--light-color));
                position: absolute;
                top: -1px;
                left: calc(100% - 2px);
                width: 260px;
                min-height: calc(100% + 2px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
                border: 1px solid color-mix(in srgb, var(--secondary-color) 10%, transparent);
                border-radius: 8px;
                display: none;
                z-index: 999;
            }

            .slider-category-sub.level-3 {
                background: color-mix(in srgb, var(--secondary-color) 8%, var(--light-color));
                left: 100%;
                top: -1px;
                width: 240px;
            }

            .slider-category-sub li>a {
                font-weight: 500;
                font-size: 13px;
                padding: 9px 12px;
                color: var(--secondary-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                text-decoration: none;
                border-bottom: 1px solid #eef2f5;
                border-bottom: 1px solid color-mix(in srgb, var(--secondary-color) 8%, transparent);
            }

            .slider-category-sub li>a:hover {
                background: #e2e8f0;
                background: color-mix(in srgb, var(--primary-color) 12%, var(--light-color));
                color: var(--primary-color);
            }

            .slider-category-item {
                position: relative;
            }

            .slider-category-item:hover>.slider-category-sub,
            .slider-category-sub li:hover>.slider-category-sub,
            .slider-category-item:focus-within>.slider-category-sub,
            .slider-category-sub li:focus-within>.slider-category-sub {
                display: block;
            }

            .slider-main {
                position: relative;
                z-index: 1;
            }

            .slider-mega-area {
                position: absolute;
                top: 0;
                left: calc(260px + 12px);
                right: 0;
                min-height: {{ $sliderHeight }}px;
                background: var(--light-color);
                /* border: 1px solid color-mix(in srgb, var(--secondary-color) 10%, transparent); */
                border: 1px solid var(--primary-color);
                border-radius: 7px;
                box-shadow: 0 16px 38px rgba(0, 0, 0, 0.08);
                padding: 16px 18px;
                display: none;
                z-index: 50;
            }

            .slider-mega-area.active {
                display: block;
            }

            .slider-mega-panel {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 16px 20px;
                align-items: start;
                pointer-events: auto;
            }

            .slider-mega-panel .mega-products {
                grid-column: 1 / -1;
            }

            .slider-mega-col h4 {
                margin: 0 0 8px;
                font-size: 16px;
                font-weight: 700;
                color: var(--secondary-color);
                border-bottom: 1px solid color-mix(in srgb, var(--secondary-color) 12%, transparent);
                padding-bottom: 6px;
            }

            .slider-mega-col ul {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 6px;
            }

            .slider-mega-col ul li a {
                text-decoration: none;
                color: var(--secondary-color);
                font-weight: 600;
                font-size: 14px;
                line-height: 1.35;
            }

            .slider-mega-col ul li a:hover {
                color: var(--primary-color);
            }

            .slider-category-item.active>a {
                background: color-mix(in srgb, var(--primary-color) 14%, var(--light-color));
                color: var(--primary-color);
                font-weight: 700;
            }

            @media (max-width: 992px) {
                .slider-mega-area {
                    display: none !important;
                }
            }

            /* Slider Section */
            .image-slider-section {
                width: 100%;
                padding: 0px;
                margin: 0px 0;
                overflow: visible;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            .slider-container {
                position: relative;
                overflow: hidden;
                border-radius: 8px;
            }

            .slider-wrapper {
                display: flex;
                transition: transform 0.5s ease;
                /* height: 500px; */
                height: {{ $sliderHeight }}px;
            }

            /* Slide Styles */
            .slide {
                min-width: 100%;
                position: relative;
                overflow: hidden;
                text-decoration: none;
                color: inherit;
                display: block;
            }

            .slide-image {
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                position: relative;
                overflow: hidden;
                will-change: transform;
                contain: layout paint;
            }

            .slide-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
            }

            .slide-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.4);
                z-index: 1;
            }

            .slide-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                color: var(--light-color);
                z-index: 2;
                width: 80%;
                max-width: 800px;
            }

            .slide-title {
                font-size: 42px;
                font-weight: 600;
                margin-bottom: 15px;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.5s, transform 0.5s;
                transition-delay: 0.2s;
            }

            .slide-description {
                font-size: 18px;
                margin-bottom: 25px;
                line-height: 1.6;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.5s, transform 0.5s;
                transition-delay: 0.4s;
            }

            .slide-button {
                display: inline-block;
                padding: 12px 30px;
                background-color: var(--primary-color);
                color: white;
                border-radius: 4px;
                font-weight: 500;
                transition: all 0.3s;
                opacity: 0;
                transform: translateY(20px);
                transition-delay: 0.6s;
            }

            .slide-button:hover {
                background-color: var(--secondary-color);
                transform: translateY(-3px);
                box-shadow: var(--shadow-md);
            }

            /* Active slide animations */
            .slide.active .slide-title,
            .slide.active .slide-description,
            .slide.active .slide-button {
                opacity: 1;
                transform: translateY(0);
            }

            /* Navigation Controls */
            .slider-nav button {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.7);
                color: var(--secondary-color);
                border: none;
                font-size: 18px;
                cursor: pointer;
                z-index: 10;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .slider-nav button:hover {
                background-color: var(--primary-color);
                color: white;
            }

            .slider-prev {
                left: 20px;
            }

            .slider-next {
                right: 20px;
            }

            /* Dots/Indicators */
            .slider-dots {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 10px;
                z-index: 10;
            }

            .dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.5);
                cursor: pointer;
                transition: all 0.3s;
            }

            .dot.active {
                background-color: var(--primary-color);
                transform: scale(1.2);
            }

            /* Mobile Responsiveness */
            @media (max-width: 992px) {
                .slider-layout {
                    grid-template-columns: 1fr;
                }

                .slider-category-menu {
                    display: none;
                }

                .slider-container {
                    margin: 0px auto;
                    margin-bottom: 5px;
                }

                .image-slider-section {
                    padding: 0px 0px;
                    margin-top: 5px;
                    margin-bottom: 5px;
                }

                .slider-wrapper {
                    height: {{ $sliderHeightTablet }}px;
                }

                .slide-title {
                    font-size: 32px;
                }

                .slide-description {
                    font-size: 16px;
                }
            }

            @media (max-width: 768px) {
                .slider-wrapper {
                    height: {{ $sliderHeightMobile }}px;
                }

                .slide-title {
                    font-size: 28px;
                    margin-bottom: 10px;
                }

                .slide-description {
                    font-size: 14px;
                    margin-bottom: 15px;
                }

                .slide-button {
                    padding: 10px 20px;
                    font-size: 14px;
                }

                .slider-nav button {
                    display: none;
                    width: 40px;
                    height: 40px;
                    font-size: 16px;
                }

                .slider-dots {
                    bottom: 10px;
                    gap: 7px;
                }
            }

            @media (max-width: 576px) {
                .slider-wrapper {
                    height: {{ $sliderHeightMobile }}px;
                }

                .slide-title {
                    font-size: 24px;
                }

                .slide-content {
                    width: 90%;
                }

                .slider-nav button {
                    width: 35px;
                    height: 35px;
                    font-size: 14px;
                }

                .dot {
                    width: 5px;
                    height: 5px;
                }
            }
        </style>
        <section class="image-slider-section">
            <div class="base-container {{ ($showCategoryMega && $sliderCategoriesList->count() > 0) ? 'slider-layout' : ($sliderLayout === 'slider_with_one_image' ? 'slider-one-image-layout' : '') }}">
                @if ($showCategoryMega && $sliderCategoriesList->count() > 0)
                    <aside class="slider-category-menu">
                        <div class="slider-category-heading">Categories</div>
                        <ul class="slider-category-list level-1">
                            @foreach ($sliderCategoriesList as $item)
                                <li class="slider-category-item" data-menu-id="{{ $item->id }}">
                                    <a href="{{ route('shop', $item->slug) }}">
                                        <span class="slider-category-thumb">
                                            @if ($item->image)
                                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" loading="lazy">
                                            @else
                                                <span style="font-size: 11px;">●</span>
                                            @endif
                                        </span>
                                        <span class="slider-category-label">{{ $item->name }}</span>
                                        @if ($item->subCategories && $item->subCategories->count())
                                            <span class="chevron">&rsaquo;</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                    <div class="slider-mega-area" id="slider-mega-area">
                        @foreach ($sliderCategoriesList as $item)
                            <div class="slider-mega-panel" data-menu-panel="{{ $item->id }}">
                                @if ($item->subCategories && $item->subCategories->count())
                                    @foreach ($item->subCategories as $child)
                                        <div class="slider-mega-col">
                                            <h4>{{ $child->name }}</h4>
                                            <ul>
                                                @if ($child->thirdCategories && $child->thirdCategories->count())
                                                    @foreach ($child->thirdCategories as $grandChild)
                                                        <li>
                                                            <a href="{{ route('shop', [$item->slug, $child->slug, $grandChild->slug]) }}">
                                                                {{ $grandChild->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @else
                                                    <li>
                                                        <a href="{{ route('shop', [$item->slug, $child->slug]) }}">
                                                            {{ $child->name }}
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="slider-mega-col">
                                        <h4>{{ $item->name }}</h4>
                                        <ul>
                                            <li><a href="{{ route('shop', $item->slug) }}">View</a></li>
                                        </ul>
                                    </div>
                                @endif

                                @if (!empty($sliderCategoryFlags[$item->id]) && $sliderCategoryFlags[$item->id])
                                    <div class="mega-products" data-mega-products="{{ $item->id }}">
                                        <div class="mega-products-header">
                                            <h5>Latest in {{ $item->name }}</h5>
                                            <a href="{{ route('shop', $item->slug) }}" class="view-all-link">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                                        </div>
                                        <div class="mega-products-grid" data-mega-products-grid="{{ $item->id }}"></div>
                                        <template data-mega-products-template="{{ $item->id }}">
                                            @include('frontend.partials.mega-products-grid', [
                                                'products' => $sliderCategoryProducts[$item->id] ?? collect(),
                                            ])
                                        </template>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="slider-main">
                    <div class="slider-container">
                        <div class="slider-wrapper">
                            @forelse($sliders as $slider)
                                <a href="{{ $slider->button_url }}" class="slide">
                                    <div class="slide-image">
                                        <img src="{{ asset($slider->image) }}"
                                            alt="{{ $slider->title ?? 'Slider Image' }}"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}" width="1340" height="550">
                                        <div class="slide-overlay"
                                            style="background-color: {{ $slider->overlay_color ?? 'rgba(0, 0, 0, 0.4)' }};">
                                        </div>
                                        <div class="slide-content">
                                            @if ($slider->title)
                                                <h2 class="slide-title">{{ $slider->title }}</h2>
                                            @endif
                                            @if ($slider->description)
                                                <p class="slide-description">{{ $slider->description }}</p>
                                            @endif
                                            @if ($slider->button_text)
                                                <span class="slide-button">{{ $slider->button_text }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                {{-- Fallback slide if no sliders are available --}}
                                <div class="slide">
                                    <div class="slide-image">
                                        <img src="{{ asset('sliders/vVV0cwK97XSfpTwKjDFLWK47JN1ug2JCzrVnnJeE.webp') }}" alt="Welcome to Our Store"
                                            loading="eager" width="1340" height="550">
                                        <div class="slide-overlay"></div>
                                        <div class="slide-content">
                                            <h2 class="slide-title">Welcome to Our Store</h2>
                                            <p class="slide-description">Discover our exclusive range of high-quality products
                                                designed
                                                for your comfort and style.</p>
                                            <span class="slide-button">Shop Now</span>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Navigation Arrows -->
                        <div class="slider-nav">
                            <button class="slider-prev">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                                </svg>
                            </button>
                            <button class="slider-next">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Dots/Indicators -->
                        <div class="slider-dots"></div>
                    </div>
                </div>

                @if ($sliderLayout === 'slider_with_one_image')
                    @if ($sliderSideImage)
                        <a class="slider-side-image" href="{{ $sliderSideImageLink }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset($sliderSideImage) }}" alt="{{ $sliderSideImageAlt ?: 'Slider Side Image' }}" loading="lazy">
                        </a>
                    @else
                        <div class="slider-side-image" style="background: color-mix(in srgb, var(--secondary-color) 8%, var(--light-color)); display:flex; align-items:center; justify-content:center; color: var(--secondary-color); font-weight:600;">
                            Add a side image in settings to show here.
                        </div>
                    @endif
                @endif
            </div>
        </section>
        <script defer>
            document.addEventListener('DOMContentLoaded', function() {
                // Mega menu interactions
                const catItems = document.querySelectorAll('.slider-category-item');
                const megaArea = document.getElementById('slider-mega-area');
                const panels = document.querySelectorAll('[data-menu-panel]');
                let hideTimer;

                if (catItems.length && megaArea) {
                    function showPanel(id) {
                        megaArea.classList.add('active');
                        panels.forEach(panel => {
                            if (panel.dataset.menuPanel === String(id)) {
                                panel.style.display = 'grid';
                            } else {
                                panel.style.display = 'none';
                            }
                        });
                        catItems.forEach(item => item.classList.toggle('active', item.dataset.menuId === String(id)));

                        // Inject pre-rendered products for this panel on first open
                        const productBlock = megaArea.querySelector(`[data-mega-products="${id}"]`);
                        const targetGrid = productBlock?.querySelector(`[data-mega-products-grid="${id}"]`);
                        const tpl = megaArea.querySelector(`[data-mega-products-template="${id}"]`);
                        if (productBlock && targetGrid && tpl && !productBlock.dataset.loaded) {
                            const content = tpl.content ? tpl.content.cloneNode(true) : null;
                            if (content) {
                                targetGrid.replaceWith(content);
                                productBlock.dataset.loaded = '1';
                            } else {
                                productBlock.dataset.loaded = 'error';
                            }
                        }
                    }

                    function hidePanel() {
                        megaArea.classList.remove('active');
                        panels.forEach(panel => (panel.style.display = 'none'));
                        catItems.forEach(item => item.classList.remove('active'));
                    }

                    catItems.forEach(item => {
                        const id = item.dataset.menuId;
                        item.addEventListener('mouseenter', () => {
                            clearTimeout(hideTimer);
                            showPanel(id);
                        });
                        item.addEventListener('focus', () => {
                            clearTimeout(hideTimer);
                            showPanel(id);
                        });
                        item.addEventListener('mouseleave', () => {
                            hideTimer = setTimeout(hidePanel, 120);
                        });
                    });

                    megaArea.addEventListener('mouseenter', () => clearTimeout(hideTimer));
                    megaArea.addEventListener('mouseleave', () => {
                        hideTimer = setTimeout(hidePanel, 120);
                    });
                }

                // Slider functionality
                const sliderWrapper = document.querySelector('.slider-wrapper');
                const slides = document.querySelectorAll('.slide');
                const prevBtn = document.querySelector('.slider-prev');
                const nextBtn = document.querySelector('.slider-next');
                const dotsContainer = document.querySelector('.slider-dots');

                let currentIndex = 0;
                let slideWidth = slides[0].clientWidth;
                let autoplayInterval;
                const autoplayDelay = 5000; // 5 seconds

                // Create dots based on number of slides
                slides.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.classList.add('dot');
                    if (index === 0) dot.classList.add('active');
                    dot.addEventListener('click', (e) => {
                        e.stopPropagation(); // Prevent click from bubbling to slide
                        goToSlide(index);
                    });
                    dotsContainer.appendChild(dot);
                });

                // Set first slide as active
                slides[0].classList.add('active');

                // Function to go to a specific slide
                function goToSlide(index) {
                    // Update current index
                    currentIndex = index;

                    // Move slider
                    sliderWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;

                    // Update active class on slides
                    slides.forEach(slide => slide.classList.remove('active'));
                    slides[currentIndex].classList.add('active');

                    // Update dots
                    const dots = document.querySelectorAll('.dot');
                    dots.forEach(dot => dot.classList.remove('active'));
                    dots[currentIndex].classList.add('active');

                    // Reset autoplay
                    resetAutoplay();
                }

                // Next slide function
                function nextSlide() {
                    currentIndex = (currentIndex + 1) % slides.length;
                    goToSlide(currentIndex);
                }

                // Previous slide function
                function prevSlide() {
                    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                    goToSlide(currentIndex);
                }

                // Set up autoplay
                function startAutoplay() {
                    autoplayInterval = setInterval(nextSlide, autoplayDelay);
                }

                // Reset autoplay
                function resetAutoplay() {
                    clearInterval(autoplayInterval);
                    startAutoplay();
                }

                // Event listeners
                prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent click from bubbling to slide
                    prevSlide();
                    resetAutoplay();
                });

                nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent click from bubbling to slide
                    nextSlide();
                    resetAutoplay();
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    slideWidth = slides[0].clientWidth;
                    goToSlide(currentIndex);
                });

                // Start autoplay
                startAutoplay();

                // Pause autoplay on hover
                sliderWrapper.addEventListener('mouseenter', () => {
                    clearInterval(autoplayInterval);
                });

                sliderWrapper.addEventListener('mouseleave', () => {
                    startAutoplay();
                });

                // Touch events for mobile swipe
                let touchStartX = 0;
                let touchEndX = 0;

                sliderWrapper.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                });

                sliderWrapper.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });

                function handleSwipe() {
                    const swipeThreshold = 50;
                    if (touchEndX < touchStartX - swipeThreshold) {
                        // Swipe left
                        nextSlide();
                    } else if (touchEndX > touchStartX + swipeThreshold) {
                        // Swipe right
                        prevSlide();
                    }
                }

                // Prevent navigation buttons from triggering slide links
                document.querySelectorAll('.slider-nav button, .slider-dots').forEach(el => {
                    el.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                });
            });
        </script>
        {{-- End Slider --}}
    @endif
    <!-- Main Slider End-->

    <!--- Start Featured Images section -->
    @if (!empty($homepage['enable_featured_images_section']) && $homepage['enable_featured_images_section'])
        <div class="featured-images-section">
            <div class="base-container">
                <div class="featured-images-grid"
                    data-image-count="{{ count(array_filter([$homepage['featured_image_1'] ?? null, $homepage['featured_image_2'] ?? null, $homepage['featured_image_3'] ?? null, $homepage['featured_image_4'] ?? null])) }}">
                    @if (!empty($homepage['featured_image_1']))
                        <div class="featured-image-item">
                            @if (!empty($homepage['featured_image_1_link']))
                                <a href="{{ $homepage['featured_image_1_link'] }}" target="_blank"
                                    rel="noopener noreferrer">
                            @endif
                            <img src="{{ asset($homepage['featured_image_1']) }}"
                                alt="{{ $homepage['featured_image_1_alt'] ?? 'Featured Image 1' }}" loading="lazy">
                            @if (!empty($homepage['featured_image_1_link']))
                                </a>
                            @endif
                        </div>
                    @endif

                    @if (!empty($homepage['featured_image_2']))
                        <div class="featured-image-item">
                            @if (!empty($homepage['featured_image_2_link']))
                                <a href="{{ $homepage['featured_image_2_link'] }}" target="_blank"
                                    rel="noopener noreferrer">
                            @endif
                            <img src="{{ asset($homepage['featured_image_2']) }}"
                                alt="{{ $homepage['featured_image_2_alt'] ?? 'Featured Image 2' }}" loading="lazy">
                            @if (!empty($homepage['featured_image_2_link']))
                                </a>
                            @endif
                        </div>
                    @endif

                    @if (!empty($homepage['featured_image_3']))
                        <div class="featured-image-item">
                            @if (!empty($homepage['featured_image_3_link']))
                                <a href="{{ $homepage['featured_image_3_link'] }}" target="_blank"
                                    rel="noopener noreferrer">
                            @endif
                            <img src="{{ asset($homepage['featured_image_3']) }}"
                                alt="{{ $homepage['featured_image_3_alt'] ?? 'Featured Image 3' }}" loading="lazy">
                            @if (!empty($homepage['featured_image_3_link']))
                                </a>
                            @endif
                        </div>
                    @endif

                    @if (!empty($homepage['featured_image_4']))
                        <div class="featured-image-item">
                            @if (!empty($homepage['featured_image_4_link']))
                                <a href="{{ $homepage['featured_image_4_link'] }}" target="_blank"
                                    rel="noopener noreferrer">
                            @endif
                            <img src="{{ asset($homepage['featured_image_4']) }}"
                                alt="{{ $homepage['featured_image_4_alt'] ?? 'Featured Image 4' }}" loading="lazy">
                            @if (!empty($homepage['featured_image_4_link']))
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <style>
            .featured-images-section {
                padding: 20px 0;
            }

            .featured-images-section {
                --featured-image-gap: {{ $homepage['featured_images_gap'] ?? 15 }}px;
            }

            .featured-images-grid {
                display: grid;
                gap: var(--featured-image-gap);
                width: 100%;
            }

            /* Single image - full width */
            .featured-images-grid[data-image-count="1"] {
                grid-template-columns: 1fr;
            }

            /* Two images - side by side */
            .featured-images-grid[data-image-count="2"] {
                grid-template-columns: 1fr 1fr;
            }

            /* Three images - side by side */
            .featured-images-grid[data-image-count="3"] {
                grid-template-columns: 1fr 1fr 1fr;
            }

            /* Four images - side by side in single row */
            .featured-images-grid[data-image-count="4"] {
                grid-template-columns: 1fr 1fr 1fr 1fr;
                grid-template-rows: auto;
            }

            .featured-image-item {
                position: relative;
                overflow: hidden;
            }

            .featured-image-item img {
                width: 100%;
                height: auto;
                position: relative;
                z-index: 2;
            }

            /* Link styling */
            .featured-image-item a {
                display: block;
                text-decoration: none;
                color: inherit;
            }

            .featured-image-item a:hover {
                cursor: pointer;
            }

            /* Lazy loading styles */
            .featured-image-item img[loading="lazy"] {
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .featured-image-item.loaded img[loading="lazy"] {
                opacity: 1;
            }

            /* Loading placeholder */
            .featured-image-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                background-size: 200% 100%;
                animation: loading 1.5s infinite;
                opacity: 1;
                transition: opacity 0.5s ease;
                z-index: 1;
            }

            /* Hide preloader when image is loaded */
            .featured-image-item.loaded::before {
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.5s ease;
            }

            @keyframes loading {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }

            /* Responsive design */
            @media (max-width: 1200px) {
                .featured-images-grid[data-image-count="4"] {
                    grid-template-columns: 1fr 1fr;
                    grid-template-rows: auto auto;
                }
            }

            @media (max-width: 768px) {

                .featured-images-grid[data-image-count="2"],
                .featured-images-grid[data-image-count="3"],
                .featured-images-grid[data-image-count="4"] {
                    grid-template-columns: 1fr 1fr;
                    grid-template-rows: auto;
                    gap: calc(var(--featured-image-gap) * 0.67);
                }

                .featured-images-section {
                    padding: 10px 0px;
                }
            }

            @media (max-width: 480px) {
                .featured-images-section {
                    padding: 7px 0px;
                }

                .featured-images-grid {
                    gap: calc(var(--featured-image-gap) * 0.53);
                }
            }
        </style>

        <script>
            // Handle lazy loading animation and preloader
            document.addEventListener('DOMContentLoaded', function() {
                const lazyImages = document.querySelectorAll('.featured-image-item img[loading="lazy"]');

                lazyImages.forEach(img => {
                    const container = img.closest('.featured-image-item');

                    // Check if image is already loaded (cached images)
                    if (img.complete) {
                        container.classList.add('loaded');
                    } else {
                        img.addEventListener('load', function() {
                            container.classList.add('loaded');
                        });
                    }

                    // Fallback for browsers that don't support lazy loading
                    if (!('loading' in HTMLImageElement.prototype)) {
                        img.src = img.dataset.src || img.src;
                    }
                });
            });
        </script>
    @endif
    <!---- End Featured Images section --->

    <!-- Start Product by Category Section v1-->
    @if (!empty($homepage['enable_product_category_section']) && $homepage['enable_product_category_section'])
        @php
            $categoryStyle = setting('homepage', 'category_style', '');
            $validCategoryStyles = ['1', '2', '3', '4', '5', '6'];
            if (!in_array((string) $categoryStyle, $validCategoryStyles, true)) {
                $categoryStyle = '';
            }

            if ($categoryStyle !== '') {
                $useCategoryStyleSix = $categoryStyle === '6';
                $useCategoryStyleFive = $categoryStyle === '5';
                $useCategoryStyleFour = $categoryStyle === '4';
                $useCategoryStyleThree = $categoryStyle === '3';
                $useCategoryStyleTwo = $categoryStyle === '2';
            } else {
                $useCategoryStyleSix = setting('homepage', 'category_style_6', '0') == '1';
                $useCategoryStyleFive = !$useCategoryStyleSix && setting('homepage', 'category_style_5', '0') == '1';
                $useCategoryStyleFour = !$useCategoryStyleSix && !$useCategoryStyleFive && setting('homepage', 'category_style_4', '1') == '1';
                $useCategoryStyleThree = !$useCategoryStyleSix && !$useCategoryStyleFive && !$useCategoryStyleFour && setting('homepage', 'category_style_3', '0') == '1';
                $useCategoryStyleTwo = !$useCategoryStyleSix && !$useCategoryStyleFive && !$useCategoryStyleFour && !$useCategoryStyleThree && setting('homepage', 'category_style_2', '0') == '1';
            }
        @endphp

        @if ($useCategoryStyleSix)
            <style>
                .categories-section {
                    margin: 0 auto 16px;
                    padding: 24px 5px 26px;
                    background: #0f172a;
                    border-radius: 16px;
                    border: 1px solid rgba(255, 255, 255, 0.06);
                    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
                    overflow: hidden;
                }

                .category-style-6 .section-heading h2 {
                    font-size: 20px;
                    font-weight: 800;
                    letter-spacing: 0.6px;
                    color: #ffffff;
                }

                .category-style-6 .heading-underline {
                    width: 110px;
                    height: 3px;
                    margin: 8px auto 0;
                    background: linear-gradient(90deg, #38bdf8 0%, transparent 100%);
                    border-radius: 6px;
                }

                .category-style-6 .categories-container {
                    gap: 16px;
                    padding: 6px 2px;
                }

                .category-style-6 .categories-carousel .categories-container {
                    gap: 16px !important;
                    flex-wrap: nowrap;
                }

                .category-style-6 .categories-carousel .category-card {
                    flex: 0 0 calc(33.33% - 12px);
                    min-width: 220px;
                }

                .category-style-6 .category-card {
                    position: relative;
                    height: 320px;
                    border-radius: 16px;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-end;
                    color: #ffffff;
                    text-decoration: none;
                    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
                    transition: transform 0.25s ease, box-shadow 0.25s ease;
                    background: #111827;
                }

                .category-style-6 .category-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 18px 46px rgba(0, 0, 0, 0.25);
                }

                .category-style-6 .category-image {
                    position: absolute;
                    inset: 0;
                    background-image: var(--cat-gradient), var(--cat-image);
                    background-size: cover;
                    background-position: center;
                    filter: brightness(0.95);
                    transition: transform 0.4s ease, filter 0.4s ease;
                }

                .category-style-6 .category-image img {
                    display: none;
                }

                .category-style-6 .category-card:hover .category-image {
                    transform: scale(1.04);
                    filter: brightness(1);
                }

                .category-style-6 .category-overlay {
                    position: absolute;
                    inset: 0;
                    background: linear-gradient(0deg, rgba(0, 0, 0, 0.78) 0%, rgba(0, 0, 0, 0.28) 50%, rgba(0, 0, 0, 0.05) 100%);
                    display: flex;
                    align-items: flex-end;
                    justify-content: flex-start;
                    padding: 22px;
                }

                .category-style-6 .category-tag {
                    display: none;
                }

                .category-style-6 .category-button {
                    position: relative;
                    display: block;
                    width: 100%;
                    padding: 12px 14px 10px;
                    background: rgba(255, 255, 255, 0.06);
                    border: 1px solid rgba(255, 255, 255, 0.14);
                    border-radius: 12px;
                    font-weight: 800;
                    font-size: 15px;
                    letter-spacing: 0.4px;
                    color: #ffffff;
                    backdrop-filter: blur(6px);
                    box-shadow: 0 10px 26px rgba(0, 0, 0, 0.2);
                }

                .category-style-6 .category-button::after {
                    content: attr(data-subtitle);
                    display: block;
                    margin-top: 6px;
                    font-size: 12px;
                    font-weight: 500;
                    opacity: 0.82;
                    letter-spacing: 0.2px;
                }

                .category-style-6 .category-card:hover .category-button {
                    background: rgba(56, 189, 248, 0.25);
                    border-color: rgba(56, 189, 248, 0.4);
                    box-shadow: 0 14px 32px rgba(56, 189, 248, 0.25);
                }

                @media(max-width:992px) {
                    .category-style-6 .categories-container {
                        gap: 12px;
                    }

                    .category-style-6 .categories-carousel .category-card {
                        flex: 0 0 calc(50% - 10px);
                        min-width: 180px;
                    }

                    .category-style-6 .category-card {
                        height: 280px;
                    }
                }

                @media(max-width:576px) {
                    .category-style-6 .categories-container {
                        gap: 10px;
                    }

                    .category-style-6 .categories-carousel .category-card {
                        flex: 0 0 calc(100% - 0px);
                        min-width: 0;
                    }

                    .category-style-6 .category-card {
                        height: 240px;
                    }

                    .category-style-6 .category-button {
                        font-size: 13px;
                    }
                }
            </style>
            @php
                $categoryGradients = [
                    'linear-gradient(45deg, #fa709a 0%, #fee140 100%)',
                    'linear-gradient(45deg, #30cfd0 0%, #330867 100%)',
                    'linear-gradient(45deg, #a8edea 0%, #fed6e3 100%)',
                    'linear-gradient(45deg, #ff9a56 0%, #ff6a88 100%)',
                    'linear-gradient(45deg, #667eea 0%, #764ba2 100%)',
                    'linear-gradient(45deg, #f6d365 0%, #fda085 100%)',
                ];
            @endphp
        @elseif ($useCategoryStyleFive)
            <style>
                .categories-section {
                    margin: 0 auto 16px;
                    padding: 22px 5px 24px;
                    background: linear-gradient(180deg, #ffffff 0%, #f6f7fb 100%);
                    border-radius: 14px;
                    border: 1px solid rgba(0, 0, 0, 0.04);
                    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
                }

                .section-heading {
                    text-align: center;
                    margin-bottom: 14px;
                }

                .section-heading h2 {
                    font-size: 19px;
                    font-weight: 800;
                    letter-spacing: 0.5px;
                    color: var(--secondary-color);
                }

                .heading-underline {
                    width: 110px;
                    height: 3px;
                    margin: 6px auto 0;
                    background: linear-gradient(90deg, transparent 0%, var(--primary-color) 50%, transparent 100%);
                    border-radius: 6px;
                }

                .categories-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
                    gap: 12px;
                    padding: 2px;
                }

                .category-card {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    padding: 12px;
                    background: #ffffff;
                    border-radius: 12px;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                    text-decoration: none;
                    color: inherit;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                }

                .category-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
                    border-color: rgba(0, 0, 0, 0.1);
                }

                .category-image {
                    position: relative;
                    height: 150px;
                    border-radius: 10px;
                    overflow: hidden;
                    background: linear-gradient(135deg, #f3f6fb 0%, #eef1f7 100%);
                    border: 1px solid rgba(0, 0, 0, 0.04);
                }

                .category-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.35s ease, filter 0.35s ease;
                }

                .category-card:hover .category-image img {
                    transform: scale(1.05);
                    filter: saturate(1.05);
                }

                .category-overlay {
                    position: absolute;
                    inset: 10px 10px auto auto;
                    width: fit-content;
                    background: transparent;
                    padding: 0;
                }

                .category-tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 10px;
                    background: rgba(255, 255, 255, 0.9);
                    color: var(--secondary-color);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    border-radius: 999px;
                    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
                }

                .category-button {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 12px 12px;
                    background: #f7f8fb;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    border-radius: 10px;
                    font-weight: 800;
                    color: var(--secondary-color);
                    letter-spacing: 0.2px;
                    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
                    position: relative;
                }

                .category-button:after {
                    content: '→';
                    font-size: 14px;
                    font-weight: 800;
                    color: inherit;
                }

                .category-card:hover .category-button {
                    background: var(--primary-color);
                    color: var(--light-color);
                    border-color: var(--primary-color);
                }

                @media(max-width:992px) {
                    .categories-container {
                        grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
                        gap: 10px;
                    }

                    .category-image {
                        height: 135px;
                    }
                }

                @media(max-width:576px) {
                    .categories-container {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 5px;
                    }

                    .category-card {
                        padding: 10px;
                        gap: 10px;
                    }

                    .category-image {
                        height: 120px;
                    }

                    .category-button {
                        padding: 10px 11px;
                        font-size: 12px;
                    }
                }
            </style>
        @elseif ($useCategoryStyleFour)
            <style>
                .categories-section {
                    margin: 0 auto 14px;
                    padding: 20px 0px 22px;
                    background: #f9f9fb;
                    border-radius: 14px;
                    border: 1px solid rgba(0, 0, 0, 0.03);
                    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.07);
                }

                .section-heading {
                    text-align: center;
                    margin-bottom: 16px;
                }

                .section-heading h2 {
                    font-size: 20px;
                    font-weight: 700;
                    color: var(--secondary-color);
                    letter-spacing: 0.5px;
                }

                .heading-underline {
                    width: 100px;
                    height: 3px;
                    margin: 6px auto 0;
                    background: linear-gradient(90deg, var(--primary-color) 0%, transparent 100%);
                    border-radius: 6px;
                }

                .categories-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                    gap: 10px;
                    padding: 4px;
                }

                .category-card {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                    padding: 8px 8px 10px;
                    background: #ffffff;
                    border-radius: 12px;
                    border: 1px solid rgba(0, 0, 0, 0.06);
                    text-decoration: none;
                    color: inherit;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                }

                .category-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
                    border-color: rgba(0, 0, 0, 0.1);
                }

                .category-image {
                    position: relative;
                    height: 180px;
                    border-radius: 10px;
                    overflow: hidden;
                    background: #f1f4f9;
                    border: 1px solid rgba(0, 0, 0, 0.04);
                }

                .category-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.35s ease;
                }

                .category-card:hover .category-image img {
                    transform: scale(1.05);
                }

                .category-overlay {
                    position: absolute;
                    inset: 10px 10px auto auto;
                    width: fit-content;
                    background: transparent;
                    padding: 0;
                }

                .category-tag {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 5px 9px;
                    background: rgba(255, 255, 255, 0.9);
                    color: var(--secondary-color);
                    font-size: 8px;
                    font-weight: 700;
                    letter-spacing: 1px;
                    border-radius: 999px;
                    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
                }

                .category-button {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 6px 10px;
                    background: #f7f8fa;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    border-radius: 10px;
                    font-weight: 600;
                    color: var(--secondary-color);
                    letter-spacing: 0.2px;
                    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
                    position: relative;
                    font-size: 14px;
                }

                .category-button:after {
                    content: '›';
                    font-size: 14px;
                    font-weight: 800;
                    color: inherit;
                }

                .category-card:hover .category-button {
                    background: var(--secondary-color);
                    color: var(--light-color);
                    border-color: var(--secondary-color);
                }

                @media(max-width:992px) {
                    .categories-container {
                        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                        gap: 12px;
                    }

                    .category-image {
                        height: 135px;
                    }
                }

                @media(max-width:576px) {
                    .categories-section {
                        padding: 10px 0px;
                    }
                    .categories-container {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 5px;
                    }

                    .category-card {
                        gap: 5px;
                        padding: 3px;
                    }

                    .category-overlay {
                    inset: 2px 9px auto auto;
                    background: transparent;
                    }

                    .category-tag {
                        gap: 6px;
                        padding: 4px 8px;
                        font-size: 5px;
                    }

                    .category-image {
                        height: 120px;
                    }

                    .category-button {
                        padding: 3px 9px;
                        font-size: 12px;
                    }
                }
            </style>
        @elseif ($useCategoryStyleThree)
            <style>
                .categories-section {
                    margin: 0 auto 14px;
                    padding: 18px 5px 22px;
                    background: var(--light-color);
                    border-radius: 16px;
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
                }

                .section-heading {
                    text-align: center;
                    margin-bottom: 16px;
                }

                .section-heading h2 {
                    font-size: 20px;
                    font-weight: 700;
                    color: var(--secondary-color);
                    letter-spacing: 0.5px;
                    margin-bottom: 6px;
                }

                .heading-underline {
                    width: 90px;
                    height: 3px;
                    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
                    border-radius: 3px;
                    margin: 0 auto;
                }

                .categories-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                    gap: 18px 16px;
                    padding: 6px;
                }

                .category-card {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 12px;
                    padding: 14px 10px;
                    background: #ffffff;
                    border-radius: 14px;
                    border: 1px solid rgba(0, 0, 0, 0.04);
                    text-decoration: none;
                    color: inherit;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                }

                .category-card:hover {
                    transform: translateY(-3px);
                    border-color: rgba(0, 0, 0, 0.08);
                    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
                }

                .category-image {
                    width: 120px;
                    height: 120px;
                    border-radius: 999px;
                    overflow: hidden;
                    position: relative;
                    background: radial-gradient(circle at 30% 30%, rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.02));
                    border: 1px solid rgba(0, 0, 0, 0.05);
                    display: grid;
                    place-items: center;
                }

                .category-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.35s ease;
                }

                .category-card:hover .category-image img {
                    transform: scale(1.06);
                }

                .category-overlay {
                    display: none;
                }

                .category-button {
                    display: inline-block;
                    padding: 8px 12px 6px;
                    background: none;
                    border: none;
                    color: var(--secondary-color);
                    font-weight: 700;
                    font-size: 13px;
                    letter-spacing: 0.3px;
                }

                .category-card:hover .category-button {
                    color: var(--primary-color);
                }

                @media(max-width:992px) {
                    .categories-container {
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 14px;
                    }

                    .category-image {
                        width: 110px;
                        height: 110px;
                    }
                }

                @media(max-width:576px) {
                    .categories-container {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 10px;
                    }

                    .category-card {
                        padding: 10px 6px;
                        gap: 10px;
                    }

                    .category-image {
                        width: 94px;
                        height: 94px;
                    }

                    .category-button {
                        font-size: 12px;
                    }
                }
            </style>
        @elseif ($useCategoryStyleTwo)
            <style>
                .categories-section {
                    margin: 0 auto 14px;
                    padding: 18px 5px 20px;
                    background: linear-gradient(135deg, #f7f9ff 0%, #ffffff 60%, #f8fbff 100%);
                    border-radius: 12px;
                    border: 1px solid rgba(0, 0, 0, 0.02);
                    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.05);
                }

                .section-heading {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 12px;
                }

                .section-heading h2 {
                    font-size: 18px;
                    font-weight: 800;
                    letter-spacing: 0.5px;
                    color: var(--secondary-color);
                    margin: 0;
                }

                .heading-underline {
                    width: 90px;
                    height: 3px;
                    background: linear-gradient(90deg, var(--primary-color), transparent);
                    border-radius: 6px;
                }

                .categories-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
                    gap: 12px;
                    padding: 6px 2px;
                }

                .category-card {
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    padding: 12px 12px 14px;
                    text-decoration: none;
                    color: inherit;
                    overflow: hidden;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
                    border:1px solid rgba(144, 144, 144, 0.53);
                    border-radius: 7px;
                }

                .category-image {
                    position: relative;
                    height: 150px;
                    overflow: hidden;
                    background: radial-gradient(circle at 20% 20%, rgba(0, 0, 0, 0.04), transparent 45%), #f3f5fa;
                    border: 1px solid rgba(0, 0, 0, 0.04);
                }

                .category-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease, filter 0.3s ease;
                }

                .category-card:hover .category-image img {
                    transform: scale(1.05);
                    filter: saturate(1.08);
                }

                .category-overlay {
                    position: absolute;
                    inset: 12px 12px auto auto;
                    width: fit-content;
                    background: transparent;
                    padding: 0;
                }

                .category-tag {
                    display: none;
                    align-items: center;
                    gap: 5px;
                    padding: 6px 10px;
                    background: rgba(255, 255, 255, 0.9);
                    color: var(--secondary-color);
                    font-size: 10px;
                    font-weight: 700;
                    letter-spacing: 0.7px;
                    border-radius: 999px;
                    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.1);
                }

                .category-button {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 8px;
                    padding: 0px 0px;
                    font-weight: 600;
                    color: var(--secondary-color);
                    letter-spacing: 0.35px;
                    text-decoration: none;
                    transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
                    text-align: center;
                }

                @media(max-width:992px) {
                    .categories-section {
                        padding: 16px 5px 18px;
                    }

                    .categories-container {
                        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                        gap: 10px;
                    }

                    .category-image {
                        height: 135px;
                    }
                }

                @media(max-width:576px) {
                    .categories-section {
                        padding: 14px 5px 18px;
                    }

                    .categories-container {
                        grid-template-columns: repeat(3, 1fr);
                        gap: 8px;
                    }

                    .category-image {
                        height: 120px;
                    }
                }
            </style>
        @else
            <style>
                .categories-section {
                    padding: 15px 15px 20px 15px;
                    background-color: var(--light-color);
                    margin-bottom: 10px;
                    border-radius: 8px;
                }

                .section-heading {
                    text-align: center;
                    margin-bottom: 20px;
                    position: relative;
                }

                .section-heading h2 {
                    font-size: 22px;
                    font-weight: 600;
                    color: var(--secondary-color);
                    letter-spacing: 2px;
                    display: inline-block;
                    position: relative;
                    margin-bottom: 6px;
                }


                .heading-underline {
                    width: 80px;
                    height: 3px;
                    background-color: var(--primary-color);
                    margin: 0 auto;
                    position: relative;
                }

                .heading-underline:before {
                    content: '';
                    position: absolute;
                    width: 50px;
                    height: 3px;
                    background-color: var(--secondary-color);
                    left: -60px;
                    top: 0;
                }

                .heading-underline:after {
                    content: '';
                    position: absolute;
                    width: 50px;
                    height: 3px;
                    background-color: var(--secondary-color);
                    right: -60px;
                    top: 0;
                }

                .categories-container {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 15px;
                    padding: 0 5px;
                    width: 100%;
                    max-width: var(--container-max-width, 1340px);
                    margin: 0 auto;
                    box-sizing: border-box;
                }

                .category-card {
                    flex: 0 0 calc(14.28% - 13px);
                    max-width: calc(14.28% - 13px);
                    box-sizing: border-box;
                }

                .category-card {
                    position: relative;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                    transition: transform 0.4s, box-shadow 0.4s;
                    cursor: pointer;
                    display: block;
                    text-decoration: none;
                    margin-bottom: 5px;
                }

                .category-card:hover {
                    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
                }

                .category-image {
                    position: relative;
                    height: 175px;
                    overflow: hidden;
                }

                .category-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.8s;
                }

                .category-card:hover .category-image img {
                    transform: scale(1.1);
                }

                .category-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.6));
                    display: flex;
                    align-items: flex-start;
                    justify-content: flex-start;
                    padding: 15px;
                    flex-direction: column;
                }

                .category-tag {
                    display: none;
                    background-color: var(--primary-color);
                    color: white;
                    padding: 5px 12px;
                    font-size: 10px;
                    font-weight: 600;
                    letter-spacing: 1px;
                    border-radius: 3px;
                    margin-bottom: 10px;
                }

                .category-button {
                    display: block;
                    text-align: center;
                    background-color: var(--light-color);
                    color: var(--secondary-color);
                    text-decoration: none;
                    padding: 9px 0;
                    font-weight: 700;
                    font-size: 12px;
                    transition: background-color 0.3s, color 0.3s;
                }

                .category-button:hover {
                    background-color: var(--primary-color);
                    color: var(--light-color);
                }

                /* Tablet */
                @media(max-width:992px) {
                    .categories-section {
                        padding: 20px 0;
                    }

                    .categories-section .section-heading h2 {
                        margin-bottom: 8px;
                    }

                    .category-card {
                        flex: 0 0 calc(25% - 6px);
                        max-width: calc(25% - 6px);
                    }
                    .categories-container {
                        padding: 0 5px;
                        gap: 8px;
                    }

                    .category-overlay {
                        padding: 10px;
                    }

                    .category-card:hover {
                        transform: translateY(0px);
                        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
                    }

                    .category-image {
                        height: 130px;
                    }

                    .section-heading {
                        margin-bottom: 20px;
                    }

                    .category-tag {
                        font-size: 7px;
                        font-weight: 500;
                    }

                    .category-button {
                        padding: 8px 0;
                        font-size: 13px;
                    }
                }

                /* Mobile */
                @media(max-width:576px) {
                    .category-card {
                        flex: 0 0 calc(33.33% - 3.33px);
                        max-width: calc(33.33% - 3.33px);
                    }
                    .categories-container {
                        gap: 5px;
                    }
                }
            </style>
        @endif
        <style>
            .categories-carousel {
                position: relative;
                box-sizing: border-box;
            }

            .categories-carousel.has-arrows {
                padding: 0 0px;
                margin: 0;
            }

            .categories-carousel .categories-container.categories-slider {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                cursor: grab;
            }

            .categories-carousel.has-arrows .categories-container.categories-slider {
                scroll-padding-left: 18px;
                scroll-padding-right: 18px;
            }

            .categories-carousel .categories-container.categories-slider::-webkit-scrollbar {
                display: none;
            }

            .categories-carousel .categories-container.categories-slider .category-card {
                flex: 0 0 var(--category-card-width, auto);
                scroll-snap-align: start;
            }

            .categories-carousel .categories-container.categories-slider.dragging {
                cursor: grabbing;
                user-select: none;
            }

            .categories-carousel .categories-container.categories-slider.dragging .category-card {
                pointer-events: none;
            }

            .categories-carousel .category-carousel-arrow {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 3;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(0, 0, 0, 0.1);
                color: var(--secondary-color);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                transition: background 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
                opacity: 0;
                pointer-events: none;
            }

            .categories-carousel .category-carousel-arrow svg {
                width: 18px;
                height: 18px;
            }

            .categories-carousel .category-carousel-arrow:hover {
                background: #ffffff;
                box-shadow: 0 8px 18px rgba(0, 0, 0, 0.16);
            }

            .categories-carousel .category-carousel-prev {
                left: 6px;
            }

            .categories-carousel .category-carousel-next {
                right: 6px;
            }

            .categories-carousel.has-arrows .category-carousel-arrow {
                opacity: 1;
                pointer-events: auto;
            }

            .categories-carousel .category-carousel-arrow:disabled {
                opacity: 0.35;
                cursor: not-allowed;
                box-shadow: none;
            }

            @media (max-width: 768px) {
                .categories-carousel.has-arrows {
                    padding: 0 0px;
                }

                .categories-carousel .category-carousel-arrow {
                    width: 28px;
                    height: 28px;
                }

                .categories-carousel .category-carousel-arrow svg {
                    width: 16px;
                    height: 16px;
                }
            }
        </style>
        <section class="base-container categories-section {{ $useCategoryStyleSix ? 'category-style-6' : '' }}">
            <div class="section-heading">
                <h2>EXPLORE OUR COLLECTIONS</h2>
                <div class="heading-underline"></div>
            </div>

            <div class="categories-carousel">
                <button class="category-carousel-arrow category-carousel-prev" type="button" aria-label="Previous">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="none"></circle>
                        <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
                <div class="categories-container">
                    @foreach ($featuredItems as $item)
                        @php
                            $catGradient = isset($categoryGradients) ? $categoryGradients[$loop->index % count($categoryGradients)] : 'linear-gradient(45deg, #0ea5e9, #1e3a8a)';
                            $catInlineStyle = $useCategoryStyleSix
                                ? "--cat-gradient: {$catGradient}; --cat-image: url('" . asset($item->image) . "');"
                                : '';
                            $subtitle =
                                $item->type === 'subcategory'
                                    ? ($item->product_category->name ?? '')
                                    : ($item->description ?? __('Discover more'));
                        @endphp
                        @if ($item->type === 'category')
                            <a href="{{ route('shop', $item->slug) }}" class="category-card">
                                <div class="category-image" style="{{ $catInlineStyle }}">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                                    <div class="category-overlay">
                                        <span class="category-tag">CATEGORY</span>
                                    </div>
                                </div>
                                <span class="category-button" data-subtitle="{{ $subtitle }}">{{ $item->name }}</span>
                            </a>
                        @elseif ($item->type === 'subcategory')
                            <a href="{{ route('shop', [$item->product_category->slug, $item->slug]) }}" class="category-card">
                                <div class="category-image" style="{{ $catInlineStyle }}">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                                    <div class="category-overlay">
                                        <span class="category-tag">SUBCATEGORY</span>
                                    </div>
                                </div>
                                <span class="category-button" data-subtitle="{{ $subtitle }}">{{ $item->name }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
                <button class="category-carousel-arrow category-carousel-next" type="button" aria-label="Next">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="none"></circle>
                        <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.categories-carousel').forEach(function(carousel) {
                    const container = carousel.querySelector('.categories-container');
                    if (!container) return;

                    const cards = container.querySelectorAll('.category-card');
                    if (!cards.length) return;

                    const prevBtn = carousel.querySelector('.category-carousel-prev');
                    const nextBtn = carousel.querySelector('.category-carousel-next');
                    let scrollStep = 0;

                    const applyCategorySlider = function() {
                        container.classList.remove('categories-slider');
                        container.style.removeProperty('--category-card-width');

                        const firstCard = container.querySelector('.category-card');
                        if (!firstCard) return;

                        const computedStyle = window.getComputedStyle(firstCard);
                        const flexBasis = computedStyle.flexBasis;
                        let cardWidth = '';

                        if (flexBasis && flexBasis !== 'auto' && flexBasis !== '0px') {
                            cardWidth = flexBasis;
                        } else {
                            const measuredWidth = Math.ceil(firstCard.getBoundingClientRect().width);
                            if (!measuredWidth) return;
                            cardWidth = `${measuredWidth}px`;
                        }

                        container.style.setProperty('--category-card-width', cardWidth);
                        container.classList.add('categories-slider');

                        window.requestAnimationFrame(function() {
                            updateScrollStep();
                            updateArrowState();
                        });
                    };

                    const updateScrollStep = function() {
                        const cardList = container.querySelectorAll('.category-card');
                        if (!cardList.length) return;

                        const firstCard = cardList[0];
                        const secondCard = cardList[1];
                        let step = Math.ceil(firstCard.getBoundingClientRect().width);

                        if (secondCard) {
                            const distance = Math.ceil(secondCard.getBoundingClientRect().left - firstCard.getBoundingClientRect().left);
                            if (distance > 0) {
                                step = distance;
                            }
                        }

                        scrollStep = step;
                    };

                    const updateArrowState = function() {
                        if (!prevBtn || !nextBtn) return;

                        const maxScroll = container.scrollWidth - container.clientWidth;
                        const hasOverflow = maxScroll > 2;
                        carousel.classList.toggle('has-arrows', hasOverflow);

                        if (!hasOverflow) {
                            prevBtn.disabled = true;
                            nextBtn.disabled = true;
                            return;
                        }

                        prevBtn.disabled = container.scrollLeft <= 0;
                        nextBtn.disabled = container.scrollLeft >= maxScroll - 1;
                    };

                    applyCategorySlider();

                    let resizeTimer;
                    window.addEventListener('resize', function() {
                        window.clearTimeout(resizeTimer);
                        resizeTimer = window.setTimeout(applyCategorySlider, 150);
                    });

                    if (prevBtn) {
                        prevBtn.addEventListener('click', function() {
                            const step = scrollStep || Math.round(container.clientWidth * 0.8);
                            container.scrollBy({
                                left: -step,
                                behavior: 'smooth'
                            });
                        });
                    }

                    if (nextBtn) {
                        nextBtn.addEventListener('click', function() {
                            const step = scrollStep || Math.round(container.clientWidth * 0.8);
                            container.scrollBy({
                                left: step,
                                behavior: 'smooth'
                            });
                        });
                    }

                    let isDown = false;
                    let startX = 0;
                    let scrollLeft = 0;
                    let hasDragged = false;
                    let lastDragTime = 0;

                    container.addEventListener('mousedown', function(event) {
                        isDown = true;
                        startX = event.pageX - container.offsetLeft;
                        scrollLeft = container.scrollLeft;
                        hasDragged = false;
                    });

                    container.addEventListener('mouseleave', function() {
                        isDown = false;
                        container.classList.remove('dragging');
                    });

                    container.addEventListener('mouseup', function() {
                        if (hasDragged) {
                            lastDragTime = Date.now();
                            hasDragged = false;
                        }
                        isDown = false;
                        container.classList.remove('dragging');
                    });

                    container.addEventListener('mousemove', function(event) {
                        if (!isDown) return;
                        event.preventDefault();
                        const x = event.pageX - container.offsetLeft;
                        const walk = x - startX;
                        if (Math.abs(walk) > 5) {
                            hasDragged = true;
                            container.classList.add('dragging');
                        }
                        container.scrollLeft = scrollLeft - walk;
                    });

                    container.addEventListener('scroll', updateArrowState);

                    container.addEventListener('click', function(event) {
                        if (Date.now() - lastDragTime < 250) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }, true);
                });
            });
        </script>
    @endif
    <!-- End Product by Category Section v1 -->

    <!-- Start 3 Best Selling Products -->
    <style>
        .best-selling-section {
            padding: 30px 50px;
            background-color: var(--body-bg);
        }

        .slider-navigation {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: {{ setting('general', 'section_header_padding', '2px 5px') }};

            @if (setting('general', 'section_header_custom_border'))
                {!! setting('general', 'section_header_custom_border') !!}
            @else
                border-bottom: 1px solid var(--border-color);
            @endif
            border-radius: {{ setting('general', 'section_header_border_radius', '8px') }};
        }

        .section-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            letter-spacing: 1.5px;
            position: relative;
            padding-left: 15px;
        }

        .section-header h2:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background-color: {{ setting('general', 'section_header_left_bar_color', 'var(--primary-color)') }};
            display: {{ setting('general', 'section_header_left_bar', '0') == '1' ? 'block' : 'none' }};
        }

        .view-all {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
            padding: 8px 15px;
            /* border: 1px solid var(--border-color); */
            border: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
            border-color: var(--primary-color);
        }

        .products-slider {
            position: relative;
            display: flex;
            align-items: center;
            padding: 0 5px;
        }

        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: background 0.2s;
        }

        .slider-arrow:hover,
        .slider-arrow:focus {
            background: #f0f4fa;
        }

        .slider-arrow.prev-arrow {
            left: 10px;
        }

        .slider-arrow.next-arrow {
            right: 10px;
        }

        .prev-arrow {
            margin-right: 15px;
        }

        .next-arrow {
            margin-left: 15px;
        }

        .products-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE and Edge */
            padding: 10px 0px 10px 0;
            /* Added bottom padding for arrows */
            margin-bottom: 0px;
        }

        products-container::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        .view-all-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--primary-color);
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.8s forwards 0.6s;
        }

        @media(max-width:992px) {
            .best-selling-section {
                padding: 5px 0px;
            }

            .section-header h2 {
                font-size: 15px;
            }

            .best-selling-section .section-header h2,
            .customer-reviews .section-header h2,
            .customer-reviews .section-header h2,
            .products-by-category .section-header h2 {
                font-size: 15px !important;
            }

            .section-header {
                padding-top: 5px;
                padding-bottom: 5px;
                margin-left: 5px;
                margin-right: 5px;
            }

            .products-slider {
                padding: 0px 0px;
            }

            .products-container {
                gap: 5px;
                padding: 10px 0 10px 0;
            }

            .slider-arrow {
                display: none;
            }
        }
    </style>
    @php
        $sections = [
            'best_selling' => 'Best Selling Products',
            'editors_pick' => "Editor's Picks",
            'trending' => 'Trending Now',
        ];
    @endphp
    @foreach ($sections as $key => $label)
        @if (!empty($homepage['enable_' . $key . '_section']) && $homepage['enable_' . $key . '_section'] && isset($featuredSections[$key]) && $featuredSections[$key]->count() > 0)
            <section class="{{ $key }}-section">
                <div class="base-container section-header">
                    <h2>{{ $homepage[$key . '_section_heading'] ?? $label }}</h2>
                    <a href="{{ route('shop', ['products' => implode(',', $featuredSections[$key]->pluck('id')->toArray())]) }}"
                        class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                </div>
                <div class="base-container products-slider products-slider-{{ $key }}">
                    <button class="slider-arrow prev-arrow prev-arrow-{{ $key }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="12" fill="none"></circle>
                            <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </button>
                    <div class="products-container products-container-{{ $key }}">
                        @foreach ($featuredSections[$key] as $product)
                            @include('frontend.partials.product-item', [
                                'product' => $product,
                                'badge' => 'Special!',
                            ])
                        @endforeach
                    </div>
                    <button class="slider-arrow next-arrow next-arrow-{{ $key }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="12" fill="none"></circle>
                            <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </section>
        @endif
    @endforeach
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // For each products-slider on the page
            document.querySelectorAll('.products-slider').forEach(function(slider) {
                const productsContainer = slider.querySelector('.products-container');
                const prevArrow = slider.querySelector('.prev-arrow');
                const nextArrow = slider.querySelector('.next-arrow');
                const productCards = productsContainer.querySelectorAll('.product-card');

                if (productsContainer && productCards.length > 0) {
                    // Only enable infinite scrolling if we have 4 or more products
                    const enableInfiniteScroll = productCards.length >= 4;

                    if (enableInfiniteScroll) {
                        // Clone the product cards for infinite scrolling
                        productCards.forEach(card => {
                            const clone = card.cloneNode(true);
                            productsContainer.appendChild(clone);
                        });

                        // Set the amount to scroll by (width of one product card + gap)
                        const scrollAmount = 270; // Adjust as needed

                        // Auto-sliding functionality
                        let autoSlideInterval;
                        let currentPosition = 0;
                        const totalWidth = productCards.length * scrollAmount;

                        function infiniteScroll() {
                            if (currentPosition >= totalWidth) {
                                productsContainer.scrollTo({
                                    left: 0,
                                    behavior: 'auto'
                                });
                                currentPosition = 0;
                            }
                            currentPosition += scrollAmount;
                            productsContainer.scrollTo({
                                left: currentPosition,
                                behavior: 'smooth'
                            });
                        }

                        function startAutoSlide() {
                            autoSlideInterval = setInterval(infiniteScroll, 3000);
                        }

                        function stopAutoSlide() {
                            clearInterval(autoSlideInterval);
                        }

                        startAutoSlide();

                        productsContainer.addEventListener('mouseenter', stopAutoSlide);
                        productsContainer.addEventListener('touchstart', stopAutoSlide);
                        productsContainer.addEventListener('mouseleave', startAutoSlide);
                        productsContainer.addEventListener('touchend', startAutoSlide);

                        if (nextArrow) {
                            nextArrow.addEventListener('click', () => {
                                infiniteScroll();
                                stopAutoSlide();
                                startAutoSlide();
                            });
                        }

                        if (prevArrow) {
                            prevArrow.addEventListener('click', () => {
                                currentPosition -= scrollAmount;
                                if (currentPosition < 0) {
                                    currentPosition = totalWidth - scrollAmount;
                                    productsContainer.scrollTo({
                                        left: currentPosition,
                                        behavior: 'auto'
                                    });
                                } else {
                                    productsContainer.scrollTo({
                                        left: currentPosition,
                                        behavior: 'smooth'
                                    });
                                }
                                stopAutoSlide();
                                startAutoSlide();
                            });
                        }
                    } else {
                        // Simple scrolling for fewer than 4 products
                        const scrollAmount = 270;

                        if (nextArrow) {
                            nextArrow.addEventListener('click', () => {
                                const maxScrollLeft = productsContainer.scrollWidth -
                                    productsContainer.clientWidth;
                                const newPosition = Math.min(productsContainer.scrollLeft +
                                    scrollAmount, maxScrollLeft);
                                productsContainer.scrollTo({
                                    left: newPosition,
                                    behavior: 'smooth'
                                });
                            });
                        }

                        if (prevArrow) {
                            prevArrow.addEventListener('click', () => {
                                const newPosition = Math.max(productsContainer.scrollLeft -
                                    scrollAmount, 0);
                                productsContainer.scrollTo({
                                    left: newPosition,
                                    behavior: 'smooth'
                                });
                            });
                        }
                    }
                }
            });
        });
    </script>
    <!-- End 3 Best Selling Products -->

    @if (setting('homepage', 'enable_category_scrollbar', '1') == '1')
        <!-- category-scrollbar-section -->
        <style>
            .category-scrollbar-section {
                margin: 10px auto;
                width: 100%;
                max-width: var(--container-max-width, 1340px);
            }

            .category-scrollbar-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                padding: 0px 5px;
                position: relative;
            }

            .category-scrollbar {
                display: flex;
                gap: 16px;
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
                scroll-behavior: smooth;
                padding: 8px 0;
                flex: 1 1 auto;
                -webkit-overflow-scrolling: touch;
                /* Smooth scrolling on iOS */
            }

            .category-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .cat-btn {
                background: #f5f6fa;
                color: #2d4379;
                border: none;
                border-radius: 30px;
                padding: 10px 28px;
                font-size: 16px;
                font-weight: 500;
                white-space: nowrap;
                cursor: pointer;
                transition: background 0.2s, color 0.2s;
                text-decoration: none;
                outline: none;
                box-shadow: none;
                user-select: none;
                -webkit-user-select: none;
                -webkit-tap-highlight-color: transparent;
            }

            .cat-btn:hover,
            .cat-btn:focus {
                background: #e0e6f7;
                color: #1a2a4f;
            }

            .cat-scroll-arrow {
                background: #fff;
                border: 1.5px solid #e0e6f7;
                color: #2d4379;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                min-width: 40px;
                min-height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.2s, color 0.2s, box-shadow 0.2s;
                box-shadow: 0 2px 8px rgba(44, 62, 80, 0.08);
                font-size: 20px;
                padding: 0;
                outline: none;
                border-width: 2px;
                z-index: 2;
            }

            .cat-scroll-arrow:hover,
            .cat-scroll-arrow:focus {
                background: #e0e6f7;
                color: #1a2a4f;
                box-shadow: 0 4px 16px rgba(44, 62, 80, 0.12);
            }

            @media (max-width: 768px) {
                .category-scrollbar-section {
                    margin: 5px auto;
                }

                .category-scrollbar-wrapper {
                    gap: 4px;
                    padding: 0 8px;
                }

                .category-scrollbar {
                    gap: 8px;
                    padding: 4px 0;
                }

                .cat-btn {
                    font-size: 14px;
                    padding: 8px 16px;
                    min-width: auto;
                }

                .cat-scroll-arrow {
                    width: 32px;
                    height: 32px;
                    min-width: 32px;
                    min-height: 32px;
                }

                .cat-scroll-arrow svg {
                    width: 16px;
                    height: 16px;
                }
            }

            @media (max-width: 480px) {
                .cat-btn {
                    font-size: 13px;
                    padding: 6px 14px;
                }
            }

            /* Add styles for dragging state */
            .category-scrollbar.dragging {
                cursor: grabbing;
                scroll-behavior: auto;
            }

            .category-scrollbar.dragging .cat-btn {
                pointer-events: none;
            }
        </style>
        <section class="category-scrollbar-section">
            <div class="category-scrollbar-wrapper">
                <button class="cat-scroll-arrow cat-scroll-left" aria-label="Previous">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="none" />
                        <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="category-scrollbar" id="category-scrollbar">
                    @foreach ($sliderCategories as $category)
                        <a href="{{ route('shop', $category->slug) }}" class="cat-btn">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                <button class="cat-scroll-arrow cat-scroll-right" aria-label="Next">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="none" />
                        <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scrollbar = document.getElementById('category-scrollbar');
                const leftBtn = document.querySelector('.cat-scroll-left');
                const rightBtn = document.querySelector('.cat-scroll-right');
                const scrollAmount = window.innerWidth < 768 ? 100 : 150; // Smaller scroll amount for mobile

                // Function to check scroll position and update arrow visibility
                function updateArrowVisibility() {
                    if (scrollbar.scrollLeft <= 0) {
                        leftBtn.style.opacity = '0.5';
                        leftBtn.style.pointerEvents = 'none';
                    } else {
                        leftBtn.style.opacity = '1';
                        leftBtn.style.pointerEvents = 'auto';
                    }

                    if (scrollbar.scrollLeft >= scrollbar.scrollWidth - scrollbar.clientWidth) {
                        rightBtn.style.opacity = '0.5';
                        rightBtn.style.pointerEvents = 'none';
                    } else {
                        rightBtn.style.opacity = '1';
                        rightBtn.style.pointerEvents = 'auto';
                    }
                }

                // Initial arrow visibility check
                updateArrowVisibility();

                // Arrow button click handlers
                leftBtn.addEventListener('click', () => {
                    scrollbar.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                rightBtn.addEventListener('click', () => {
                    scrollbar.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });

                // Mouse drag to scroll
                let isDown = false;
                let startX;
                let scrollLeft;
                let lastPageX;
                let wasClick = true;

                scrollbar.addEventListener('mousedown', (e) => {
                    isDown = true;
                    scrollbar.classList.add('dragging');
                    startX = e.pageX - scrollbar.offsetLeft;
                    scrollLeft = scrollbar.scrollLeft;
                    lastPageX = e.pageX;
                    wasClick = true;
                });

                scrollbar.addEventListener('mouseleave', () => {
                    isDown = false;
                    scrollbar.classList.remove('dragging');
                });

                scrollbar.addEventListener('mouseup', () => {
                    isDown = false;
                    scrollbar.classList.remove('dragging');
                    // If it was a click (no significant drag), allow the link to work
                    if (wasClick) {
                        scrollbar.querySelectorAll('.cat-btn').forEach(btn => {
                            btn.style.pointerEvents = 'auto';
                        });
                    }
                });

                scrollbar.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - scrollbar.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    scrollbar.scrollLeft = scrollLeft - walk;
                    // If there was significant movement, it wasn't a click
                    if (Math.abs(lastPageX - e.pageX) > 5) {
                        wasClick = false;
                    }
                    updateArrowVisibility();
                });

                // Touch events for mobile
                let touchStartX;
                let touchScrollLeft;
                let lastTouchX;
                let wasTap = true;

                scrollbar.addEventListener('touchstart', (e) => {
                    touchStartX = e.touches[0].pageX;
                    touchScrollLeft = scrollbar.scrollLeft;
                    lastTouchX = e.touches[0].pageX;
                    wasTap = true;
                    scrollbar.classList.add('dragging');
                }, {
                    passive: true
                });

                scrollbar.addEventListener('touchmove', (e) => {
                    const x = e.touches[0].pageX;
                    const walk = (touchStartX - x);
                    scrollbar.scrollLeft = touchScrollLeft + walk;

                    // If there was significant movement, it wasn't a tap
                    if (Math.abs(lastTouchX - x) > 5) {
                        wasTap = false;
                        e.preventDefault(); // Prevent page scroll only if dragging
                    }

                    updateArrowVisibility();
                }, {
                    passive: false
                });

                scrollbar.addEventListener('touchend', () => {
                    scrollbar.classList.remove('dragging');
                    // If it was a tap (no significant drag), allow the link to work
                    if (wasTap) {
                        scrollbar.querySelectorAll('.cat-btn').forEach(btn => {
                            btn.style.pointerEvents = 'auto';
                        });
                    }
                });

                // Update arrow visibility on scroll
                scrollbar.addEventListener('scroll', updateArrowVisibility);

                // Update arrow visibility on window resize
                window.addEventListener('resize', updateArrowVisibility);
            });
        </script>
        <!-- End category-scrollbar-section -->
    @endif

    <!-- Products By Category v1-->
    @if (!empty($homepage['enable_products_by_category_section']) && $homepage['enable_products_by_category_section'] && isset($productsByCategoryItems) && $productsByCategoryItems->count() > 0)
        <style>
            .products-by-category {
                padding-top: 15px;
                padding-bottom: 15px;
                background-color: #f9f9f9;
                position: relative;
            }

            .products-by-category::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('https://images.unsplash.com/photo-1579547945413-497e1b99dac0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
                background-size: cover;
                background-position: center;
                opacity: 0.03;
                z-index: 0;
            }

            .category-products-container {
                padding: 0 5px;
                position: relative;
                z-index: 1;
            }

            .category-showcase {
                display: flex;
                gap: 0px;
                margin-top: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                border-radius: 12px;
                overflow: hidden;
                background-color: white;
                margin-bottom: 10px;
            }

            .category-banner {
                flex: 0 0 15%;
                position: relative;
                overflow: hidden;
                max-height: 100%;
            }

            .category-banner img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.7s ease;
            }

            .category-showcase:hover .category-banner img {
                transform: scale(1.05);
            }

            .banner-content {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.7));
                color: white;
                text-align: center;
                padding: 20px;
            }

            .banner-content h3 {
                font-size: 28px;
                font-weight: 700;
                margin-bottom: 10px;
                letter-spacing: 2px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                transform: translateY(-20px);
                opacity: 0;
                animation: fadeInUp 0.8s forwards 0.2s;
            }

            .banner-content h4 {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 30px;
                letter-spacing: 1px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
                transform: translateY(-20px);
                opacity: 0;
                animation: fadeInUp 0.8s forwards 0.4s;
            }

            @keyframes fadeInUp {
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }


            .view-all-btn:hover {
                background-color: transparent;
                color: white;
                border-color: white;
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            }

            .category-products-grid {
                flex: 1;
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 10px;
                padding: 10px;
                background-color: white;
            }


            .category-product {
                background-color: white;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s, box-shadow 0.3s;
                position: relative;
            }

            /* Override any conflicting category-card styles for this section */
            .products-by-category .category-card {
                flex: none;
                max-width: none;
                width: auto;
            }


            @media (max-width: 1200px) {
                .category-showcase {
                    flex-direction: column;
                }

                .category-banner {
                    flex: 0 0 100%;
                    height: 240px;
                }

                .category-banner img {
                    width: 100%;
                    height: 240px;
                }

                .category-products-grid {
                    grid-template-columns: repeat(4, 1fr);
                    padding: 5px;
                }

                .banner-content h3 {
                    font-size: 28px;
                }

                .banner-content h4 {
                    font-size: 24px;
                }
            }

            @media (max-width: 992px) {

                .category-products-grid {
                    grid-template-columns: repeat(3, 1fr);
                }

                .category-products-container {
                    max-width: 100%;
                    margin: 0 auto;
                    padding: 0 5px;
                    position: relative;
                    z-index: 1;
                }

                .category-showcase {
                    margin-top: 0;
                }

                .category-banner img {
                    width: 100%;
                    height: 140px;
                }

                .category-banner {
                    min-height: 140px;
                }
            }

            @media (max-width: 768px) {
                .category-products-grid {
                    grid-template-columns: repeat(3, 1fr);
                }

                .banner-content h3 {
                    font-size: 22px;
                }

                .banner-content h3 {
                    margin-bottom: 4px;
                }

                .banner-content h4 {
                    font-size: 18px;
                    margin-bottom: 13px;
                }

                .view-all {
                    font-size: 13px;
                    font-weight: 600;
                    padding: 0px 7px;
                }

                .view-all-btn {
                    padding: 8px 30px;
                    font-size: 12px;
                }


                .category-banner {
                    flex: 0 0 100%;
                    height: 140px;
                }

                .products-by-category {
                    padding: 5px 0;
                }
            }

            @media (max-width: 768px) {

                .products-by-category .category-card {
                    height: auto;
                }

                .category-banner img {
                    width: 100%;
                    height: 140px;
                }

                .category-banner {
                    min-height: 140px;
                }

            }


            @media (max-width: 576px) {

                .category-products-grid {
                    grid-template-columns: repeat(2, 1fr);
                }


                /* Make category cards smaller for mobile */
                .products-by-category .category-card {
                    height: auto;
                }

                .section-heading h2,
                .section-header h2 {
                    font-size: 16px;
                }

                .banner-content h3 {
                    font-size: 22px;
                }

                .banner-content h4 {
                    font-size: 18px;
                }

                .category-showcase {
                    gap: 5px;
                }

                .category-banner {
                    height: 140px;
                }
            }
        </style>
        <section class="products-by-category">
            <div class="base-container section-header" style="padding: 5px 5px;margin-bottom: 5px;">
                <h2>Products By Category</h2>
                {{-- <a href="{{ route('shop') }}"
                    class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a> --}}
            </div>
            <div class="base-container category-products-container">
                @foreach ($productsByCategoryItems as $item)
                    <div class="category-showcase">
                        <div class="category-banner">
                            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                            <div class="banner-content">
                                <h4>{{ $item->type === 'category' ? $item->name : $item->product_category->name ?? '' }}
                                </h4>
                                <a href="{{ $item->type === 'category'
                                    ? route('shop', $item->slug)
                                    : route('shop', [$item->product_category->slug, $item->slug]) }}"
                                    class="view-all-btn">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                            </div>
                        </div>
                        <div class="category-products-grid">
                            @php
                                $products =
                                    $item->type === 'category'
                                        ? $item->products()->where('status', 1)->take(5)->get()
                                        : $item->products()->where('status', 1)->take(5)->get();
                            @endphp
                            @if ($products->count())
                                @foreach ($products as $product)
                                    @include('frontend.partials.product-item', [
                                        'product' => $product,
                                        'badge' => 'Shop!',
                                    ])
                                @endforeach
                            @else
                                <div class="no-products">
                                    <p>No products available at the moment.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
    <!-- End Products By Category v1-->

    {{-- Products By Category v2 Location 1 --}}
    @if (setting('homepage', 'enable_products_by_category_v2_location1', '0') == '1' && !empty($sliderCategories1))
        <!-- Product By Category v2 with slide -->
        @foreach ($sliderCategories1 as $i => $catData)
            @if (!empty($catData['products']) && count($catData['products']) > 0)
                <section class="category-slider-section" style="margin-top: 15px;">
                    <div class="base-container section-header">
                        <h2>{{ $catData['category']->name }}</h2>
                        <a href="{{ route('shop', $catData['category']->slug) }}"
                            class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                    </div>
                    <div class="base-container products-slider products-slider-category1-{{ $i }}">
                        <button class="slider-arrow prev-arrow prev-arrow-category1-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div class="products-container products-container-category1-{{ $i }}">
                            @foreach ($catData['products'] as $product)
                                @include('frontend.partials.product-item', [
                                    'product' => $product,
                                    'badge' => 'Shop!',
                                ])
                            @endforeach
                        </div>
                        <button class="slider-arrow next-arrow next-arrow-category1-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </section>
            @endif
        @endforeach
        <!-- End Product By Category v2 with slide -->
    @endif

    {{-- Best Author Section --}}
    @if (setting('homepage', 'enable_best_author_section', '1') == '1')
        <!-- Best Autor Section -->
        <style>
            .circle-slider-section {
                text-align: center;
                padding: 10 5px;
            }

            .circle-slider-header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                margin-bottom: 18px;
            }

            .circle-slider-header h2 {
                font-size: 2rem;
                font-weight: bold;
                color: #1a2a4f;
                margin: 0;
                letter-spacing: 1px;
            }

            .circle-slider-line {
                flex: 1 1 40px;
                height: 3px;
                background: #2d4379;
                border-radius: 2px;
                max-width: 60px;
            }

            .circle-slider-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 18px;
            }

            .circle-slider {
                display: flex;
                gap: 32px;
                overflow-x: auto;
                scroll-behavior: smooth;
                padding: 10px 0;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .circle-slider::-webkit-scrollbar {
                display: none;
            }

            .circle-slider-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 120px;
                max-width: 120px;
            }

            .circle-slider-img-wrap {
                width: 110px;
                height: 110px;
                border-radius: 50%;
                overflow: hidden;
                border: 4px solid #fff;
                box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
                margin-bottom: 10px;
                background: #f5f6fa;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .circle-slider-img-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 50%;
            }

            .circle-slider-name {
                font-size: 1rem;
                color: #222;
                margin-top: 2px;
                font-weight: 500;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                max-width: 110px;
            }

            .circle-slider-arrow {
                background: #fff;
                border: 2px solid #e0e6f7;
                color: #2d4379;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                min-width: 40px;
                min-height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.2s, color 0.2s, box-shadow 0.2s;
                box-shadow: 0 2px 8px rgba(44, 62, 80, 0.08);
                font-size: 20px;
                padding: 0;
                outline: none;
            }

            .circle-slider-arrow:hover,
            .circle-slider-arrow:focus {
                background: #e0e6f7;
                color: #1a2a4f;
                box-shadow: 0 4px 16px rgba(44, 62, 80, 0.12);
            }

            .circle-slider-cta {
                margin-top: 10px;
            }

            .circle-slider-btn {
                background: #e74c3c;
                color: #fff;
                border-radius: 20px;
                padding: 10px 28px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: background 0.2s;
                display: inline-block;
                border: none;
            }

            .circle-slider-btn:hover {
                background: #c0392b;
            }

            /* Tablet responsiveness */
            @media (max-width: 1024px) {
                .circle-slider-section {
                    max-width: 100%;
                    padding: 0 10px;
                }

                .circle-slider {
                    gap: 20px;
                }

                .circle-slider-card,
                .circle-slider-img-wrap {
                    min-width: 90px;
                    max-width: 100x;
                }

                .circle-slider-img-wrap {
                    width: 80px;
                    height: 80px;
                }

                .circle-slider-name {
                    font-size: 0.95rem;
                    max-width: 80px;
                }

                .circle-slider-header h2 {
                    font-size: 1.4rem;
                }
            }

            /* Mobile responsiveness */
            @media (max-width: 700px) {
                .circle-slider-section {
                    padding: 0 2vw;
                }

                .circle-slider-header h2 {
                    font-size: 1.1rem;
                }

                .circle-slider {
                    gap: 10px;
                    padding: 5px 0;
                }

                .circle-slider-card,
                .circle-slider-img-wrap {
                    min-width: 60px;
                    max-width: 70px;
                }

                .circle-slider-img-wrap {
                    width: 50px;
                    height: 60px;
                    border-width: 2px;
                }

                .circle-slider-name {
                    font-size: 0.8rem;
                    max-width: 50px;
                }

                .circle-slider-arrow {
                    width: 30px;
                    height: 30px;
                    min-width: 30px;
                    min-height: 30px;
                    font-size: 16px;
                }

                .circle-slider-line {
                    max-width: 30px;
                    height: 2px;
                }
            }
        </style>

        <section class="base-container circle-slider-section author-slider-section">
            <div class="circle-slider-header">
                <span class="circle-slider-line"></span>
                <h2>বেস্ট লেখকগণ</h2>
                <span class="circle-slider-line"></span>
            </div>
            <div class="circle-slider-wrapper">
                <button class="circle-slider-arrow circle-slider-arrow-left author-arrow-left" aria-label="Previous">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#f5f6fa" />
                        <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="circle-slider author-slider" id="author-slider">
                    @foreach ($writers as $writer)
                        <a href="{{ route('frontend.writers.show', $writer->id) }}">
                            <div class="circle-slider-card">
                                <div class="circle-slider-img-wrap">
                                    <img src="{{ $writer->photo }}" alt="{{ $writer->name }}">
                                </div>
                                <div class="circle-slider-name">{{ $writer->name }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button class="circle-slider-arrow circle-slider-arrow-right author-arrow-right" aria-label="Next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#f5f6fa" />
                        <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="circle-slider-cta">
                <a href="{{ route('frontend.writers.index') }}" class="circle-slider-btn">আরও লেখকগণ</a>
            </div>
        </section>
        <!-- End Best Autor Section -->
    @endif

    {{-- Products By Category v2 Location 2 --}}
    @if (setting('homepage', 'enable_products_by_category_v2_location2', '0') == '1' && !empty($sliderCategories2))
        <!-- Product By Category v2 with slide -->
        @foreach ($sliderCategories2 as $i => $catData)
            @if (!empty($catData['products']) && count($catData['products']) > 0)
                <section class="category-slider-section" style="margin-top: 15px;">
                    <div class="base-container section-header">
                        <h2>{{ $catData['category']->name }}</h2>
                        <a href="{{ route('shop', $catData['category']->slug) }}"
                            class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                    </div>
                    <div class="base-container products-slider products-slider-category2-{{ $i }}">
                        <button class="slider-arrow prev-arrow prev-arrow-category2-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div class="products-container products-container-category2-{{ $i }}">
                            @foreach ($catData['products'] as $product)
                                @include('frontend.partials.product-item', [
                                    'product' => $product,
                                    'badge' => 'Shop!',
                                ])
                            @endforeach
                        </div>
                        <button class="slider-arrow next-arrow next-arrow-category2-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </section>
            @endif
        @endforeach
        <!-- End Product By Category v2 with slide -->
    @endif

    @if (setting('homepage', 'enable_best_publisher_section', '1') == '1' && isset($publishers) && $publishers->count() > 0)
        <!-- Best Pubslisher Section -->
        <section class="base-container circle-slider-section author-slider-section">
            <div class="circle-slider-header">
                <span class="circle-slider-line"></span>
                <h2>বেস্ট প্রকাশক</h2>
                <span class="circle-slider-line"></span>
            </div>
            <div class="circle-slider-wrapper">
                <button class="circle-slider-arrow circle-slider-arrow-left author-arrow-left" aria-label="Previous">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#f5f6fa" />
                        <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="circle-slider author-slider" id="author-slider">
                    @foreach ($publishers as $item)
                        <a href="{{ route('frontend.publishers.show', $item->id) }}">
                            <div class="circle-slider-card">
                                <div class="circle-slider-img-wrap">
                                    <img src="{{ $item->logo }}" alt="{{ $item->name }}">
                                </div>
                                <div class="circle-slider-name">{{ $item->name }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button class="circle-slider-arrow circle-slider-arrow-right author-arrow-right" aria-label="Next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="12" fill="#f5f6fa" />
                        <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="circle-slider-cta">
                <a href="{{ route('frontend.publishers.index') }}" class="circle-slider-btn">আরও প্রকাশক</a>
            </div>
        </section>
        <!-- End Best Pubslisher Section -->
    @endif

    {{-- Products By Category v2 Location 3 --}}
    @if (setting('homepage', 'enable_products_by_category_v2_location3', '0') == '1' && !empty($sliderCategories3))
        <!-- Product By Category v2 with slide -->
        @foreach ($sliderCategories3 as $i => $catData)
            @if (!empty($catData['products']) && count($catData['products']) > 0)
                <section class="category-slider-section" style="margin-top: 15px;">
                    <div class="base-container section-header">
                        <h2>{{ $catData['category']->name }}</h2>
                        <a href="{{ route('shop', $catData['category']->slug) }}"
                            class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
                    </div>
                    <div class="base-container products-slider products-slider-category3-{{ $i }}">
                        <button class="slider-arrow prev-arrow prev-arrow-category3-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                        <div class="products-container products-container-category3-{{ $i }}">
                            @foreach ($catData['products'] as $product)
                                @include('frontend.partials.product-item', [
                                    'product' => $product,
                                    'badge' => 'Shop!',
                                ])
                            @endforeach
                        </div>
                        <button class="slider-arrow next-arrow next-arrow-category3-{{ $i }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="12" fill="none"></circle>
                                <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </section>
            @endif
        @endforeach
        <!-- End Product By Category v2 with slide -->
    @endif

    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <!-- Start Latest Products Section -->
        <section class="latest-products-section">
            <div class="base-container section-header" style="margin-bottom: 10px;">
                <h2>{{ $homepage['latest_products_section_heading'] ?? 'Latest Products' }}</h2>
                <a href="{{ route('shop') }}"
                    class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
            </div>
            <div class="base-container">
                <div class="latest-products-grid" id="latest-products-grid">
                    @foreach ($latestProducts as $product)
                        @include('frontend.partials.product-item', [
                            'product' => $product,
                            'badge' => 'NEW',
                        ])
                    @endforeach
                </div>
                @php
                    $initialCount = $homepage['latest_products_initial_count'] ?? 12;
                    $perPage = $homepage['latest_products_per_page'] ?? 12;
                    $loadType = $homepage['latest_products_load_type'] ?? 'button';
                    $hasMoreProducts = $totalProducts > $initialCount;
                @endphp
                @if ($hasMoreProducts)
                    @if ($loadType === 'button')
                        <div class="load-more-container text-center mt-4">
                            <button class="load-more-btn" id="load-more-latest" data-page="2"
                                data-per-page="{{ $perPage }}">
                                <span class="btn-text">Load More Products</span>
                                <span class="btn-loading" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="infinite-scroll-indicator text-center mt-4" id="infinite-scroll-indicator"
                            style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading more products...</p>
                        </div>
                    @endif
                @endif
            </div>
        </section>

        <style>
            .latest-products-section {
                padding: 10px 10px;
                background-color: var(--body-bg);
            }

            .latest-products-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(215px, 215px));
                gap: 25px 10px;
                margin-bottom: 20px;
            }

            /* Latest Products Image Heights - Using Admin Settings */
            .latest-products-section .product-image {
                height: {{ setting('general', 'product_image_height', '240px') }} !important;
                padding: {{ setting('general', 'product_image_padding', '5px') }} !important;
            }

            .load-more-container {
                text-align: center;
                margin-bottom: 10px;
            }

            .load-more-btn {
                background-color: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                min-width: 200px;
            }

            .load-more-btn:hover {
                background-color: var(--secondary-color);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .load-more-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            /* Infinite Scroll Indicator */
            .infinite-scroll-indicator {
                padding: 20px;
                background-color: rgba(255, 255, 255, 0.8);
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .infinite-scroll-indicator .spinner-border {
                width: 2rem;
                height: 2rem;
            }

            @media (max-width: 992px) {
                .latest-products-grid {
                    grid-template-columns: repeat(auto-fit, minmax(160px, 160px));
                    gap: 10px;
                    margin-bottom: 10px;
                }

            }

            @media (max-width: 768px) {
                .latest-products-section {
                    padding: 5px 0px;
                }

                .latest-products-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 5px;
                    margin-bottom: 13px;
                }

                /* Mobile Responsive Settings */
                .latest-products-section .product-image {
                    height: {{ setting('general', 'product_image_height_mobile', 'auto') }} !important;
                }

                .latest-products-section .product-title {
                    font-size: {{ setting('general', 'product_title_font_size_mobile', '14px') }} !important;
                }

                .latest-products-section .current-price {
                    font-size: {{ setting('general', 'product_price_font_size_mobile', '14px') }} !important;
                }
            }

            @media (min-width: 769px) and (max-width: 1024px) {
                .latest-products-grid {
                    grid-template-columns: repeat(4, 1fr);
                    gap: 18px;
                }

                /* Tablet Responsive Settings */
                .latest-products-section .product-image {
                    height: {{ setting('general', 'product_image_height_tablet', '200px') }} !important;
                }

                .latest-products-section .product-title {
                    font-size: {{ setting('general', 'product_title_font_size_tablet', '14px') }} !important;
                }

                .latest-products-section .current-price {
                    font-size: {{ setting('general', 'product_price_font_size_tablet', '14px') }} !important;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loadMoreBtn = document.getElementById('load-more-latest');
                const infiniteScrollIndicator = document.getElementById('infinite-scroll-indicator');
                const productsGrid = document.getElementById('latest-products-grid');
                const loadType = '{{ $loadType ?? 'button' }}';

                let currentPage = 2;
                let isLoading = false;
                let hasMore = {{ $hasMoreProducts ? 'true' : 'false' }};



                // Function to load more products
                function loadMoreProducts() {
                    if (isLoading || !hasMore) return;

                    isLoading = true;

                    // Show appropriate loading indicator
                    if (loadType === 'button' && loadMoreBtn) {
                        const btnText = loadMoreBtn.querySelector('.btn-text');
                        const btnLoading = loadMoreBtn.querySelector('.btn-loading');
                        btnText.style.display = 'none';
                        btnLoading.style.display = 'inline-block';
                        loadMoreBtn.disabled = true;
                    } else if (loadType === 'infinite' && infiniteScrollIndicator) {
                        infiniteScrollIndicator.style.display = 'block';
                    }

                    // Make AJAX request
                    fetch('{{ route('ajax.load-latest-products') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            page: currentPage,
                            per_page: parseInt('{{ $perPage ?? 12 }}')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add new products to grid
                            productsGrid.insertAdjacentHTML('beforeend', data.html);
                            currentPage++;

                            // Initialize image preloaders for newly loaded products
                            if (window.handleNewProductImages) {
                                window.handleNewProductImages();
                            }

                            // Update hasMore status
                            hasMore = data.hasMore;

                            // Hide load more button if no more products
                            if (!hasMore && loadMoreBtn) {
                                loadMoreBtn.style.display = 'none';
                            }
                            if (!hasMore && infiniteScrollIndicator) {
                                infiniteScrollIndicator.style.display = 'none';
                            }
                        }
                    })
                        .catch(error => {
                            // Handle error silently
                        })
                        .finally(() => {
                            // Reset loading state
                            isLoading = false;

                            if (loadType === 'button' && loadMoreBtn) {
                                const btnText = loadMoreBtn.querySelector('.btn-text');
                                const btnLoading = loadMoreBtn.querySelector('.btn-loading');
                                btnText.style.display = 'inline-block';
                                btnLoading.style.display = 'none';
                                loadMoreBtn.disabled = false;
                            } else if (loadType === 'infinite' && infiniteScrollIndicator) {
                                infiniteScrollIndicator.style.display = 'none';
                            }
                        });
                }

                // Button click handler
                if (loadMoreBtn && loadType === 'button') {
                    loadMoreBtn.addEventListener('click', loadMoreProducts);
                }

                // Infinite scroll handler
                if (loadType === 'infinite') {
                    let scrollTimeout;

                    function handleScroll() {
                        if (scrollTimeout) {
                            clearTimeout(scrollTimeout);
                        }

                        scrollTimeout = setTimeout(() => {
                            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                            const windowHeight = window.innerHeight;
                            const documentHeight = document.documentElement.scrollHeight;

                            // Load more when user is near the bottom (within 200px)
                            if (scrollTop + windowHeight >= documentHeight - 200 && !isLoading && hasMore) {
                                loadMoreProducts();
                            }
                        }, 100);
                    }

                    // Add scroll event listener
                    window.addEventListener('scroll', handleScroll);

                    // Also check on window resize
                    window.addEventListener('resize', handleScroll);
                }
            });
        </script>
        <!-- End Latest Products Section -->
    @endif

    @if (!empty($homepage['enable_customer_reviews_section']) && $homepage['enable_customer_reviews_section'])
        <!-- Start Customer Reviews -->
        <style>
            /* Customer Reviews Section */
            .customer-reviews {
                padding-top: 0;
                padding-bottom: 15px;
                background-color: #fff;
            }

            .customer-reviews .section-header {
                margin-bottom: 10px;
            }

            .reviews-container {
                display: flex;
                justify-content: center;
                gap: 0px;
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                padding: 0 var(--container-padding, 15px);
            }

            .review-card {
                background-color: #f9f9f9;
                border-radius: 10px;
                padding: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s, box-shadow 0.3s;
                width: 350px;
            }

            .review-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            }

            .review-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }

            .reviewer-info {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .reviewer-img {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid #fff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .reviewer-details {
                display: flex;
                flex-direction: column;
                max-width: 140px;
            }

            .reviewer-name {
                font-size: 15px;
                font-weight: 600;
                color: var(--secondary-color);
                margin-bottom: 3px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .review-date {
                font-size: 12px;
                color: #777;
            }

            .review-rating {
                color: var(--primary-color);
                font-size: 14px;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .review-product {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;
                background-color: #fff;
                border-radius: 6px;
                margin-bottom: 15px;
                border: 1px solid var(--border-color);
            }

            .review-product-img {
                width: 40px;
                height: 40px;
                object-fit: cover;
                border-radius: 4px;
            }

            .review-product-name {
                font-size: 13px;
                font-weight: 500;
                color: var(--text-color);
            }

            .review-text {
                font-size: 14px;
                line-height: 1.6;
                color: var(--text-color);
            }

            @media (max-width: 1340px) {
                .reviews-container {
                    max-width: 100%;
                    flex-wrap: wrap;
                }
            }

            @media (max-width: 992px) {
                .reviews-container {
                    flex-wrap: wrap;
                    justify-content: center;
                }

                .customer-reviews {
                    padding: 18px 0;
                }

                .review-card {
                    width: calc(50% - 15px);
                }
            }

            @media (max-width: 768px) {
                .review-card {
                    width: 100%;
                }
            }
        </style>
        <section class="customer-reviews">
            <div class="base-container section-header">
                <h2>CUSTOMER REVIEWS</h2>
                <a href="#" class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
            </div>

            <div class="reviews-container">
                @forelse($customerReviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                @if ($review->reviewer_image)
                                    <img src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}"
                                        class="reviewer-img">
                                @else
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg"
                                        alt="{{ $review->reviewer_name }}" class="reviewer-img">
                                @endif
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ $review->reviewer_name }}</h4>
                                    <div class="review-date">{{ $review->review_date->format('F d, Y') }}</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i <= $review->rating + 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="review-product">
                            @if ($review->product_image)
                                <img src="{{ asset($review->product_image) }}"
                                    alt="{{ $review->product_name }}" class="review-product-img">
                            @else
                                <img src="https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                    alt="{{ $review->product_name }}" class="review-product-img">
                            @endif
                            <span class="review-product-name">{{ $review->product_name }}</span>
                        </div>
                        <p class="review-text">{{ $review->review_text }}</p>
                    </div>
                @empty
                    <!-- Fallback content if no reviews are available -->
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer"
                                    class="reviewer-img">
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">Rafiqul Islam</h4>
                                    <div class="review-date">August 15, 2023</div>
                                </div>
                            </div>
                            <div class="review-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="review-product">
                            <img src="https://images.pexels.com/photos/2887766/pexels-photo-2887766.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2"
                                alt="Product" class="review-product-img">
                            <span class="review-product-name">Obsidian Black Panjabi</span>
                        </div>
                        <p class="review-text">The quality of the fabric is exceptional. The fit is perfect and the design
                            is
                            elegant. I've received many compliments wearing this panjabi. Highly recommended!</p>
                    </div>
                @endforelse
            </div>
        </section>
        <!-- Review slider -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize the review slider
                initReviewSlider();

                // Re-initialize on window resize
                window.addEventListener('resize', function() {
                    initReviewSlider();
                });

                function initReviewSlider() {
                    const reviewsContainer = document.querySelector('.reviews-container');
                    const reviewCards = document.querySelectorAll('.review-card');

                    // Don't proceed if elements don't exist
                    if (!reviewsContainer || reviewCards.length === 0) return;

                    // Add slider class if not already added
                    if (!reviewsContainer.classList.contains('review-slider')) {
                        reviewsContainer.classList.add('review-slider');

                        // Create navigation buttons if they don't exist
                        if (!document.querySelector('.review-slider-nav')) {
                            const navContainer = document.createElement('div');
                            navContainer.className = 'review-slider-nav';

                            const prevBtn = document.createElement('button');
                            prevBtn.className = 'review-prev';
                            prevBtn.innerHTML =
                                '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>';

                            const nextBtn = document.createElement('button');
                            nextBtn.className = 'review-next';
                            nextBtn.innerHTML =
                                '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"> <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" /> </svg>';

                            // Create dots container
                            const dotsContainer = document.createElement('div');
                            dotsContainer.className = 'review-dots';

                            // Create dots for each review group (mobile: 1 per view, desktop: 1 dot per 4 cards)
                            const totalGroups = window.innerWidth <= 768 ?
                                reviewCards.length :
                                Math.ceil(reviewCards.length / 4);

                            for (let i = 0; i < totalGroups; i++) {
                                const dot = document.createElement('span');
                                dot.className = 'review-dot';
                                if (i === 0) dot.classList.add('active');
                                dot.dataset.index = i;
                                dotsContainer.appendChild(dot);
                            }

                            navContainer.appendChild(prevBtn);
                            navContainer.appendChild(dotsContainer);
                            navContainer.appendChild(nextBtn);

                            // Insert after reviews container
                            reviewsContainer.parentNode.insertBefore(navContainer, reviewsContainer.nextSibling);

                            // Add event listeners
                            prevBtn.addEventListener('click', () => slideReview('prev'));
                            nextBtn.addEventListener('click', () => slideReview('next'));

                            // Add event listeners to dots
                            document.querySelectorAll('.review-dot').forEach(dot => {
                                dot.addEventListener('click', function() {
                                    const index = parseInt(this.dataset.index);
                                    goToReview(index);
                                });
                            });
                        }
                    }

                    // Apply different styles based on screen size
                    if (window.innerWidth <= 768) {
                        // Mobile view - one card at a time
                        setupMobileView(reviewCards);
                    } else {
                        // Desktop view - four cards at a time
                        setupDesktopView(reviewCards);
                    }
                }

                function setupMobileView(reviewCards) {
                    const reviewsContainer = document.querySelector('.reviews-container');

                    // Set container styles
                    reviewsContainer.style.display = 'block';

                    // Set initial state for mobile
                    reviewCards.forEach((card, index) => {
                        card.style.transform = index === 0 ? 'translateX(0)' : 'translateX(100%)';
                        card.style.position = 'absolute';
                        card.style.width = '100%';
                        card.style.left = 0;
                        card.style.opacity = index === 0 ? 1 : 0;
                        card.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                        card.dataset.index = index;
                    });

                    // Set container height to match the height of the first card
                    reviewsContainer.style.height = `${reviewCards[0].offsetHeight}px`;

                    // Update dots for mobile view
                    updateDots(reviewCards.length);
                }

                function setupDesktopView(reviewCards) {
                    const reviewsContainer = document.querySelector('.reviews-container');

                    // Set container styles for desktop
                    reviewsContainer.style.display = 'flex';
                    reviewsContainer.style.flexWrap = 'nowrap';
                    reviewsContainer.style.overflow = 'hidden';
                    reviewsContainer.style.height = 'auto';

                    // Calculate total groups (4 cards per group)
                    const cardsPerView = 4;
                    const totalGroups = Math.ceil(reviewCards.length / cardsPerView);

                    // Reset card styles for desktop view
                    reviewCards.forEach((card, index) => {
                        card.style.position = 'relative';
                        card.style.width = `calc(25% - 20px)`; // 4 cards with some margin
                        card.style.margin = '0 10px';
                        card.style.opacity = 1;
                        card.style.transform = `translateX(0)`;
                        card.style.transition = 'transform 0.5s ease';
                        card.dataset.index = index;
                    });

                    // Update dots for desktop view
                    updateDots(totalGroups);
                }

                function updateDots(totalGroups) {
                    const dotsContainer = document.querySelector('.review-dots');
                    if (!dotsContainer) return;

                    // Clear existing dots
                    dotsContainer.innerHTML = '';

                    // Create new dots based on total groups
                    for (let i = 0; i < totalGroups; i++) {
                        const dot = document.createElement('span');
                        dot.className = 'review-dot';
                        if (i === 0) dot.classList.add('active');
                        dot.dataset.index = i;
                        dot.addEventListener('click', function() {
                            goToReview(parseInt(this.dataset.index));
                        });
                        dotsContainer.appendChild(dot);
                    }
                }

                // Current slide index
                let currentIndex = 0;

                function slideReview(direction) {
                    const reviewCards = document.querySelectorAll('.review-card');
                    const isMobile = window.innerWidth <= 768;
                    const itemsPerView = isMobile ? 1 : 4;
                    const totalGroups = isMobile ?
                        reviewCards.length :
                        Math.ceil(reviewCards.length / itemsPerView);

                    // Calculate new index
                    let newIndex;
                    if (direction === 'next') {
                        newIndex = (currentIndex + 1) % totalGroups;
                    } else {
                        newIndex = (currentIndex - 1 + totalGroups) % totalGroups;
                    }

                    goToReview(newIndex);
                }

                function goToReview(index) {
                    const reviewCards = document.querySelectorAll('.review-card');
                    const reviewsContainer = document.querySelector('.reviews-container');
                    const dots = document.querySelectorAll('.review-dot');
                    const isMobile = window.innerWidth <= 768;
                    const itemsPerView = isMobile ? 1 : 4;
                    const totalGroups = isMobile ?
                        reviewCards.length :
                        Math.ceil(reviewCards.length / itemsPerView);

                    // Don't proceed if index is invalid
                    if (index < 0 || index >= totalGroups) return;

                    // Update current index
                    currentIndex = index;

                    // Update dots
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === index);
                    });

                    if (isMobile) {
                        // Mobile animation - one card at a time
                        reviewCards.forEach((card, i) => {
                            if (i === index) {
                                card.style.transform = 'translateX(0)';
                                card.style.opacity = 1;
                            } else {
                                card.style.transform = i < index ? 'translateX(-100%)' : 'translateX(100%)';
                                card.style.opacity = 0;
                            }
                        });

                        // Update container height to match the height of the current card
                        reviewsContainer.style.height = `${reviewCards[index].offsetHeight}px`;
                    } else {
                        // Desktop animation - slide groups of 4 cards
                        const translateValue = -100 * index;
                        reviewCards.forEach((card) => {
                            card.style.transform = `translateX(${translateValue}%)`;
                        });
                    }
                }
            });
        </script>

        <!-- Review Slider -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create a style element
                const style = document.createElement('style');

                // Add CSS for review slider
                style.textContent = `
        /* Common styles for both mobile and desktop */
        .reviews-container {
            position: relative;
            overflow: hidden;
            transition: height 0.3s ease;
        }

        .review-slider-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            gap: 15px;
        }

        .review-prev, .review-next {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-color);
            color: var(--secondary-color);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .review-prev:hover, .review-next:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .review-dots {
            display: flex;
            gap: 8px;
        }

        .review-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }

        .review-dot.active {
            background-color: var(--primary-color);
            transform: scale(1.2);
        }

        /* Desktop specific styles */
        @media (min-width: 769px) {
            .reviews-container {
                display: flex;
                flex-wrap: nowrap;
                transition: transform 0.5s ease;
            }

            .review-card {
                flex: 0 0 calc(25% - 20px);
                margin: 0 10px;
                transition: transform 0.5s ease;
            }
        }

        /* Mobile specific styles */
        @media (max-width: 768px) {
            .reviews-container {
                display: block;
            }

            .review-card {
                width: 100%;
                transition: transform 0.5s ease, opacity 0.5s ease;
            }
        }
    `;

                // Append the style to the head
                document.head.appendChild(style);
            });
        </script>
        <!-- End Customer Reviews -->
    @endif

    @if (!empty($homepage['enable_shop_features_section']) && $homepage['enable_shop_features_section'])
        <!-- Start Shop Features -->
        <style>
            .features {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
                padding: 50px 15px;
                background-color: var(--light-color);
            }

            .feature-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                max-width: 200px;
            }

            .feature-icon {
                font-size: 24px;
                color: var(--primary-color);
                margin-bottom: 5px;
            }


            .feature-title {
                font-weight: 600;
                margin-bottom: 10px;
                text-transform: uppercase;
                font-size: 14px;
                letter-spacing: 1px;
            }

            .feature-desc {
                color: #777;
                font-size: 13px;
                line-height: 1.5;
            }

            @media(max-width:992px) {
                .features {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 18px;
                }
            }
        </style>
        <section class="base-container features">
            <div class="feature-item">
                <!-- Truck -->
                <div class="feature-icon">
                    <svg width="35" height="35" viewBox="0 0 422.518 422.518" fill="currentColor">
                        <path
                            d="M422.512,215.424c0-0.079-0.004-0.158-0.005-0.237c-0.116-5.295-4.368-9.514-9.727-9.514h-2.554l-39.443-76.258
                                                                                                                                                                                                        c-1.664-3.22-4.983-5.225-8.647-5.226l-67.34-0.014l2.569-20.364c0.733-8.138-1.783-15.822-7.086-21.638
                                                                                                                                                                                                        c-5.293-5.804-12.683-9.001-20.81-9.001h-209c-5.255,0-9.719,4.066-10.22,9.308l-2.095,16.778h119.078
                                                                                                                                                                                                        c7.732,0,13.836,6.268,13.634,14c-0.203,7.732-6.635,14-14.367,14H126.78c0.007,0.02,0.014,0.04,0.021,0.059H10.163
                                                                                                                                                                                                        c-5.468,0-10.017,4.432-10.16,9.9c-0.143,5.468,4.173,9.9,9.641,9.9H164.06c7.168,1.104,12.523,7.303,12.326,14.808
                                                                                                                                                                                                        c-0.216,8.242-7.039,14.925-15.267,14.994H54.661c-5.523,0-10.117,4.477-10.262,10c-0.145,5.523,4.215,10,9.738,10h105.204
                                                                                                                                                                                                        c7.273,1.013,12.735,7.262,12.537,14.84c-0.217,8.284-7.109,15-15.393,15H35.792v0.011H25.651c-5.523,0-10.117,4.477-10.262,10
                                                                                                                                                                                                        c-0.145,5.523,4.214,10,9.738,10h8.752l-3.423,35.818c-0.734,8.137,1.782,15.821,7.086,21.637c5.292,5.805,12.683,9.001,20.81,9.001
                                                                                                                                                                                                        h7.55C69.5,333.8,87.3,349.345,109.073,349.345c21.773,0,40.387-15.545,45.06-36.118h94.219c7.618,0,14.83-2.913,20.486-7.682
                                                                                                                                                                                                        c5.172,4.964,12.028,7.682,19.514,7.682h1.55c3.597,20.573,21.397,36.118,43.171,36.118c21.773,0,40.387-15.545,45.06-36.118h6.219
                                                                                                                                                                                                        c16.201,0,30.569-13.171,32.029-29.36l6.094-67.506c0.008-0.091,0.004-0.181,0.01-0.273c0.01-0.139,0.029-0.275,0.033-0.415
                                                                                                                                                                                                        C422.52,215.589,422.512,215.508,422.512,215.424z M109.597,329.345c-13.785,0-24.707-11.214-24.346-24.999
                                                                                                                                                                                                        c0.361-13.786,11.87-25.001,25.655-25.001c13.785,0,24.706,11.215,24.345,25.001C134.89,318.131,123.382,329.345,109.597,329.345z
                                                                                                                                                                                                         M333.597,329.345c-13.785,0-24.706-11.214-24.346-24.999c0.361-13.786,11.87-25.001,25.655-25.001
                                                                                                                                                                                                        c13.785,0,24.707,11.215,24.345,25.001C358.89,318.131,347.382,329.345,333.597,329.345z M396.457,282.588
                                                                                                                                                                                                        c-0.52,5.767-5.823,10.639-11.58,10.639h-6.727c-4.454-19.453-21.744-33.882-42.721-33.882c-20.977,0-39.022,14.429-44.494,33.882
                                                                                                                                                                                                        h-2.059c-2.542,0-4.81-0.953-6.389-2.685c-1.589-1.742-2.337-4.113-2.106-6.676l12.609-139.691l28.959,0.006l-4.59,50.852
                                                                                                                                                                                                        c-0.735,8.137,1.78,15.821,7.083,21.637c5.292,5.806,12.685,9.004,20.813,9.004h56.338L396.457,282.588z" />
                    </svg>
                </div>
                <h4 class="feature-title">Fast Shipping</h4>
                <p class="feature-desc">
                    Free delivery on all orders above ৳
                    {{ number_format(setting('general', 'free_shipping_amount', 0)) }}
                    with 2-3 day shipping
                </p>
            </div>

            <div class="feature-item">
                <!-- Undo -->
                <div class="feature-icon">
                    <svg width="29" height="29" viewBox="0 0 512 512" fill="currentColor">
                        <path
                            d="m195.42 176.56 194.03-112.03 4.11-2.38 30.61 17.69-202.05 116.64c-2.64 1.5-4.11 4.28-4.11 7.11h-.06v66.19l-30.73-17.74v-70.75l7.77-4.48c.14-.08.29-.17.43-.25zm39.06 107.49c0 4.56-3.68 8.28-8.25 8.28-1.73 0-3.32-.54-4.62-1.42l-46.77-27.01c-2.64-1.5-4.11-4.28-4.11-7.11h-.03v-75.6l-71.23-41.16v143.83c9.92-.03 19.98 1.25 30.02 3.91 61.57 16.5 98.48 80.45 81.98 142.02-.28 1.05-.57 2.07-.88 3.09l86.88 50.17v-228.68l-62.99-36.37zm-47.74-121.5c.14-.08.31-.17.45-.26l189.93-109.64-67.04-38.69c-2.75-1.59-5.92-1.59-8.7 0l-193.69 111.83 71.37 41.21zm127.25 91.79v228.7l193.66-111.8c2.75-1.62 4.34-4.37 4.34-7.57v-223.65zm126.62-165.01-4.14 2.38-193.88 111.94 63.16 36.45 198.03-114.32zm-245.11 336.16c-14.2 53.06-68.74 84.53-121.78 70.33-53.04-14.23-84.53-68.74-70.3-121.78 14.2-53.06 68.71-84.53 121.78-70.33 53.04 14.24 84.53 68.75 70.3 121.78zm-37.92-14.65c0-11.17-4.56-21.29-11.91-28.63-7.34-7.34-17.46-11.91-28.6-11.91h-47.54l8.02-8.02c3.23-3.23 3.23-8.45 0-11.68-3.2-3.23-8.45-3.23-11.68 0l-22.11 22.14c-3.23 3.2-3.23 8.45 0 11.68l22.11 22.11c3.23 3.23 8.48 3.23 11.68 0 3.23-3.23 3.23-8.45 0-11.68l-8.02-8.02h47.54c6.58 0 12.59 2.69 16.92 7.06 4.37 4.34 7.06 10.35 7.06 16.95 0 6.58-2.69 12.59-7.06 16.92-4.34 4.37-10.35 7.06-16.92 7.06h-17.94c-4.56 0-8.25 3.71-8.25 8.28s3.69 8.25 8.25 8.25h17.94c11.14 0 21.26-4.56 28.6-11.91 7.34-7.34 11.91-17.46 11.91-28.6z" />
                    </svg>
                </div>
                <h4 class="feature-title">Easy Returns</h4>
                <p class="feature-desc">30-day hassle-free return policy for all products</p>
            </div>

            <div class="feature-item">
                <!-- Medal -->
                <div class="feature-icon">
                    <svg width="35" height="35" viewBox="0 0 64 64" fill="currentColor">
                        <path
                            d="m26.76288 20.75744h-6.07111c.04999-.13.12006-.24994.20007-.35992l2.33038-3.09943c.23004-.29993.59009-.48993.98016-.48993h3.55066l-.99017 3.94928zm-5.86102 1.99963c.02997.03992.06.09991.10999.13995l8.75153 9.72815-2.96051-9.8681h-5.901zm7.99139 0 3.11053 10.36798 3.11053-10.36798h-6.22107zm5.3009-5.94891h-4.38074l-.99017 3.94928h6.36108zm.04999 15.81702 8.75159-9.72815c.04999-.04004.08002-.10004.10999-.13995h-5.901l-2.96057 9.8681zm6.5412-15.32709c-.23004-.29993-.59015-.48993-.98022-.48993h-3.5506l.99017 3.94928h6.07104c.11334-.02905-2.45997-3.33461-2.5304-3.45935zm-8.39148 26.38495c-.9928-.07697-1.9422.16003-2.97052.38977-3.19055.9007-5.30383-.04199-7.2713-2.53937-.78564-.9137-1.5224-1.69507-2.63025-2.17969-5.89508-2.33899-5.05389-3.65375-6.03125-9.12817-.71014-1.96045-2.50775-3.67883-2.49042-6.0188-.01318-2.32404 1.78137-4.05377 2.49042-6.01886.60205-1.9953.17212-4.51776 1.52026-6.3288 1.31281-1.84692 3.84015-2.20703 5.55109-3.39917 3.18713-2.95007 3.99744-5.46375 9.11151-4.05963 3.03485 1.11786 5.80835-.99304 8.82147-.16974 2.20404.70575 3.30048 2.96576 4.95093 4.22925 1.71143 1.19043 4.23846 1.55304 5.55109 3.39917 1.73517 2.54645.5625 5.83423 2.52032 8.29852 2.94934 4.49512.90289 6.06488-1.00018 10.06805-.60101 1.99011-.1701 4.51337-1.52026 6.3288-1.34869 1.8645-3.7688 2.17731-5.55096 3.39941-1.69952 1.32123-2.71832 3.49341-4.95087 4.22913-2.01617.65735-4.12195-.34583-6.10107-.49988zm.35004-6.40881s11.74207-13.03748 11.74206-13.03748c1.26054-1.39697 1.3567-3.53042.2301-5.039l-2.33044-3.09949c-.6001-.7998-1.57031-1.28973-2.58051-1.28973h-15.60272c-1.01013 0-1.98035.48993-2.58044 1.28973l-2.33044 3.09949c-1.12665 1.50854-1.0304 3.64203.2301 5.039l11.74207 13.03748c.42365.43445 1.05774.42889 1.48022 0zm12.68079 3.83936c-.91278.39276-1.45312 1.03857-2.08038 1.74945-2.99445 3.67078-5.69452 4.12494-10.31177 2.92963 0 0 4.08069 13.49744 4.08069 13.49738.22101.80902 1.39044.95532 1.8103.23999l4.16077-6.71869 7.69135 1.79962c.61713.14111 1.20239-.35168 1.23022-.96979-.02887-.18457-.0639-.38348-.17004-.54993 0 0-6.41113-11.97772-6.41113-11.97766zm-24.7644 1.75964c-.62482-.72687-1.1748-1.39081-2.09033-1.74969 0 0-6.45111 12.04773-6.45111 12.04767-.4256.71674.31122 1.65991 1.11017 1.43976l7.69135-1.79962 4.16077 6.71869c.20203.32574.60046.51715.98016.4599.39008-.04993.71014-.31995.83014-.69983l4.10071-13.49744c-4.63318 1.185-7.31262.75348-10.33185-2.91943z" />
                    </svg>
                </div>
                <h4 class="feature-title">Premium Quality</h4>
                <p class="feature-desc">Ethically sourced materials and expert craftsmanship</p>
            </div>

            <div class="feature-item">
                <!-- Headset -->
                <div class="feature-icon">
                    <svg width="29" height="29" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 1C7.03 1 3 5.03 3 10V17C3 18.66 4.34 20 6 20H9V12H5V10C5 6.13 8.13 3 12 3C15.87 3 19 6.13 19 10V12H15V20H18C19.66 20 21 18.66 21 17V10C21 5.03 16.97 1 12 1Z" />
                    </svg>
                </div>
                <h4 class="feature-title">24/7 Support</h4>
                <p class="feature-desc">Our customer service team is always ready to assist you</p>
            </div>
        </section>
        <!-- End Shop Features -->
    @endif

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Author slider
            const authorSlider = document.getElementById('author-slider');
            const authorLeftBtn = document.querySelector('.author-arrow-left');
            const authorRightBtn = document.querySelector('.author-arrow-right');
            const scrollAmount = 160;

            authorLeftBtn.addEventListener('click', () => {
                authorSlider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
            authorRightBtn.addEventListener('click', () => {
                authorSlider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Mouse drag for author slider
            let isDownA = false,
                startXA, scrollLeftA;
            authorSlider.addEventListener('mousedown', (e) => {
                isDownA = true;
                authorSlider.classList.add('dragging');
                startXA = e.pageX - authorSlider.offsetLeft;
                scrollLeftA = authorSlider.scrollLeft;
            });
            authorSlider.addEventListener('mouseleave', () => {
                isDownA = false;
                authorSlider.classList.remove('dragging');
            });
            authorSlider.addEventListener('mouseup', () => {
                isDownA = false;
                authorSlider.classList.remove('dragging');
            });
            authorSlider.addEventListener('mousemove', (e) => {
                if (!isDownA) return;
                e.preventDefault();
                const x = e.pageX - authorSlider.offsetLeft;
                const walk = (x - startXA) * 1.5;
                authorSlider.scrollLeft = scrollLeftA - walk;
            });
            // Touch drag for author slider
            let touchStartXA = 0,
                touchScrollLeftA = 0;
            authorSlider.addEventListener('touchstart', (e) => {
                touchStartXA = e.touches[0].pageX;
                touchScrollLeftA = authorSlider.scrollLeft;
            });
            authorSlider.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX;
                const walk = (x - touchStartXA) * 1.5;
                authorSlider.scrollLeft = touchScrollLeftA - walk;
            });

            // Publisher slider
            const publisherSlider = document.getElementById('publisher-slider');
            const publisherLeftBtn = document.querySelector('.publisher-arrow-left');
            const publisherRightBtn = document.querySelector('.publisher-arrow-right');

            publisherLeftBtn.addEventListener('click', () => {
                publisherSlider.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });
            publisherRightBtn.addEventListener('click', () => {
                publisherSlider.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Mouse drag for publisher slider
            let isDownP = false,
                startXP, scrollLeftP;
            publisherSlider.addEventListener('mousedown', (e) => {
                isDownP = true;
                publisherSlider.classList.add('dragging');
                startXP = e.pageX - publisherSlider.offsetLeft;
                scrollLeftP = publisherSlider.scrollLeft;
            });
            publisherSlider.addEventListener('mouseleave', () => {
                isDownP = false;
                publisherSlider.classList.remove('dragging');
            });
            publisherSlider.addEventListener('mouseup', () => {
                isDownP = false;
                publisherSlider.classList.remove('dragging');
            });
            publisherSlider.addEventListener('mousemove', (e) => {
                if (!isDownP) return;
                e.preventDefault();
                const x = e.pageX - publisherSlider.offsetLeft;
                const walk = (x - startXP) * 1.5;
                publisherSlider.scrollLeft = scrollLeftP - walk;
            });
            // Touch drag for publisher slider
            let touchStartXP = 0,
                touchScrollLeftP = 0;
            publisherSlider.addEventListener('touchstart', (e) => {
                touchStartXP = e.touches[0].pageX;
                touchScrollLeftP = publisherSlider.scrollLeft;
            });
            publisherSlider.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX;
                const walk = (x - touchStartXP) * 1.5;
                publisherSlider.scrollLeft = touchScrollLeftP - walk;
            });
        });
    </script>
@endsection
