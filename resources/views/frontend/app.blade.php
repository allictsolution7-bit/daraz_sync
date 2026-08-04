<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preload" href="{{ asset($sliders[0]->image ?? 'banner/Thikana Benapole.jpg') }}" as="image">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/analytics.js') }}?v={{ filemtime(public_path('js/analytics.js')) }}" defer></script>

    <!-- Google Tag Manager -->
    @php
        $gtmId = setting('general', 'gtm_id', 'GTM-MMMMMMM');
    @endphp
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
    </script>
    <!-- End Google Tag Manager -->

    <style>
        /* Font family is now handled globally via font-loader.blade.php */

        .web-primary-color {
            fill: var(--primary-color);
        }

        /* Header icons color override - applies to cart, user/profile, hamburger menu icons */
        .nav-icons .web-primary-color,
        .header-icons .web-primary-color {
            fill: var(--header-icons-color, var(--primary-color));
        }

        /* Mobile menu toggle icon uses stroke instead of fill */
        .mobile-menu-toggle svg {
            color: var(--header-icons-color, var(--primary-color));
        }
        .mobile-menu-toggle svg path {
            stroke: var(--header-icons-color, var(--primary-color));
        }

        .product-image {
            position: relative;
        }

        .image-preloader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.7);
            z-index: 2;
        }

        .image-preloader .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #ed1a25;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .product-card {
            flex: 0 0 auto;
            width: 100%;
            background-color: var(--body-bg);
            border-radius: {{ setting('general', 'product_card_border_radius', '10px') }};
            overflow: hidden;
            box-shadow: {{ setting('general', 'product_card_box_shadow', '0 5px 15px rgba(0, 0, 0, 0.05)') }};
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            display: block;
            text-decoration: none;
            border: {{ setting('general', 'product_card_border', '1px solid #efefef') }};
        }

        .category-products-container .product-card {
            width: 100%;
        }

        .products-slider .product-card {
            width: 240px;
            /* width: 300px; */
        }

        .product-card:hover {
            /* transform: translateY(-10px); */
            /* box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); */
        }

        /* .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--primary-color);
            color: var(--light-color);
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 3px;
            z-index: 5;
            letter-spacing: 1px;
        } */

        .starburst-badge {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 48px;
            height: 48px;
            background: url(/star.svg) no-repeat center center;
            background-size: contain;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.1;
            z-index: 5;
            letter-spacing: 0;
            padding: 0;
            border: none;
            box-shadow: none;
        }

        .simple-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background-color: var(--primary-color);
            color: var(--light-color);
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 3px;
            z-index: 5;
            letter-spacing: 1px;
        }

        .product-image {
            position: relative;
            height: {{ setting('general', 'product_image_height', '240px') }};
            overflow: hidden;
            padding: {{ setting('general', 'product_image_padding', '5px') }};
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
            display: block;
            border-radius: 7px;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 7px;
        }

        .product-card .product-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 2px;
            margin-top: 7px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-meta {
            display: block;
        }

        .item-action {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }

        .quickitemcart {
            flex-shrink: 0;
        }
        .item-cart-icon {
            border: 1px solid var(--primary-color);
            padding: 4px;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .item-cart-icon:hover {
            background: var(--primary-color);
            color: #fff;
        }

        .product-price {
            display: block;
        }

        .current-price {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .product-card .original-price {
            font-size: 15px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .add-to-cart-btn,
        .buy-now-btn {
            flex: 1;
            width: 100%;    
            background-color: transparent;
            /* background-color: var(--primary-color); */
            border: 1px solid var(--border-color);
            color: var(--secondary-color);
            /* color: #fff; */
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add-to-cart-btn:hover,
        .buy-now-btn:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
            border-color: var(--primary-color);
        }



        @media(max-width:992px) {
            .add-to-cart-btn,
            .buy-now-btn {
                padding: 3px 6px;
                height: 33px;
                font-size: 12px;
            }

            .item-cart-icon {
                padding: 4px;
                width: 33px;
                height: 33px;
            }

            .product-info {
                padding: 5px;
            }

            .product-card {
                width: 100%;
            }

            .category-products-container .product-card {
                width: 100%;
            }

            .products-slider .products-container {
                gap: 5px;
            }

            .products-slider .product-card {
                /* width: 260px; */
                width: 160px;
            }

            .product-image {
                height: {{ setting('general', 'product_image_height_tablet', '200px') }};
            }

            .products-slider .product-image {
                height: {{ setting('general', 'product_image_height_mobile', 'auto') }};
            }

            .product-card .product-title {
                font-size: 14px;
            }

            .current-price {
                font-size: 14px;
            }
        }

        /* @media(max-width:576px) {
            .product-card {
                width: 100%;
            }

            .products-slider .product-card {
                width:230px;
            }

            .product-image {
                height: {{ setting('general', 'product_image_height_mobile', 'auto') }};
            }

            .product-title {
                font-size: 12px;
            }

            .current-price {
                font-size: 12px;
            }
        } */
    </style>

    <style>
        span.loop-writers {
            font-size: 15px;
            margin-top: -5px;
        }

        /* Product Card Rating Styles */
        .product-card-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 8px 0;
            font-size: 12px;
        }

        .product-card-rating .rating-stars {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .product-card-rating .star-icon {
            flex-shrink: 0;
        }

        .product-card-rating .rating-text {
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Responsive adjustments for product card ratings */
        @media (max-width: 576px) {
            span.loop-writers {
                font-size: 11px;
            }

            .product-card-rating {
                font-size: 11px;
                gap: 4px;
            }

            .product-card-rating .rating-stars {
                gap: 0;
            }
        }

        /* Image Preloader Styles */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .image-preloader.hidden {
            opacity: 0 !important;
            pointer-events: none;
        }
        .product-image-loaded.loaded {
            opacity: 1 !important;
        }

        /* Error state for image preloader */
        .image-preloader.error .spinner {
            display: none;
        }

        .image-preloader.error .image-preloader-text {
            color: #dc3545;
            background: rgba(255, 255, 255, 0.9);
        }
    </style>

    {{-- Font preloading is now handled dynamically in partials/font-loader.blade.php --}}
    @include('partials.font-loader')


    <!-- Dynamic Title -->
    <title>{{ $metaTitle ?? \App\Services\SettingsService::getDefaultMetaTitle() }}</title>
    <!-- Favicon - Use settings if available, fallback to default -->
    @php
        $favicon = \App\Services\SettingsService::getFavicon();
        $faviconPath = $favicon ?: asset('new/logo.png');
        // Determine MIME type based on file extension
        $faviconUrl = parse_url($faviconPath, PHP_URL_PATH);
        $faviconType = strtolower(pathinfo($faviconUrl, PATHINFO_EXTENSION));
        $faviconMime = ($faviconType === 'ico') ? 'image/x-icon' : (($faviconType === 'svg') ? 'image/svg+xml' : 'image/png');
    @endphp
    <link rel="icon" type="{{ $faviconMime }}" href="{{ $faviconPath }}">
    
    <!-- Fallback for browsers that automatically request /favicon.ico -->
    @if (file_exists(public_path('favicon.ico')))
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif

    <!-- Primary Meta Tags -->
    <meta name="description"
        content="{{ $metaDescription ?? \App\Services\SettingsService::getDefaultMetaDescription() }}">
    <meta name="keywords" content="{{ $metaKeywords ?? \App\Services\SettingsService::getDefaultMetaKeywords() }}">
    <meta name="author" content="{{ \App\Services\SettingsService::getSiteAuthor() }}">

    <!-- Robots Meta Tags -->
    <meta name="robots" content="{{ $metaRobots ?? \App\Services\SettingsService::getRobotsMeta() }}">
    <meta name="googlebot" content="{{ $metaRobots ?? \App\Services\SettingsService::getRobotsMeta() }}">

    <!-- Canonical URL -->
    <meta name="canonical" content="{{ $canonicalUrl ?? url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:title" content="{{ $metaTitle ?? \App\Services\SettingsService::getDefaultMetaTitle() }}">
    <meta property="og:description"
        content="{{ $metaDescription ?? \App\Services\SettingsService::getDefaultMetaDescription() }}">
    <meta property="og:image" content="{{ $ogImage ?? \App\Services\SettingsService::getDefaultOgImage() }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $metaTitle ?? \App\Services\SettingsService::getDefaultMetaTitle() }}">
    <meta property="og:site_name" content="{{ setting('general', 'site_name', 'Thikana Shop') }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ \App\Services\SettingsService::getTwitterUsername() }}">
    <meta name="twitter:title" content="{{ $metaTitle ?? \App\Services\SettingsService::getDefaultMetaTitle() }}">
    <meta name="twitter:description"
        content="{{ $metaDescription ?? \App\Services\SettingsService::getDefaultMetaDescription() }}">
    <meta name="twitter:image" content="{{ $ogImage ?? \App\Services\SettingsService::getDefaultOgImage() }}">

    <!-- Search Engine Verification -->
    @if (setting('seo', 'google_search_console'))
        <meta name="google-site-verification" content="{{ setting('seo', 'google_search_console') }}">
    @endif
    @if (setting('seo', 'bing_webmaster'))
        <meta name="msvalidate.01" content="{{ setting('seo', 'bing_webmaster') }}">
    @endif
    @if (setting('seo', 'yandex_webmaster'))
        <meta name="yandex-verification" content="{{ setting('seo', 'yandex_webmaster') }}">
    @endif



    <!-- Schema Markup -->
    @if (\App\Services\SettingsService::isSchemaEnabled())
        @php
            $organizationSchema = \App\Services\SettingsService::getOrganizationSchema();

            if (!$organizationSchema) {
                $organizationSchema = json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => setting('general', 'site_name', 'Thikana Shop'),
                    'url' => url('/'),
                    'logo' => \App\Services\SettingsService::getLogo(),
                    'description' => \App\Services\SettingsService::getDefaultMetaDescription(),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressCountry' => 'BD',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => setting('general', 'phone_number', ''),
                        'contactType' => 'customer service',
                    ],
                ]);
            }
        @endphp

        <script type="application/ld+json">
            {!! $organizationSchema !!}
        </script>

        <!-- Product Schema Markup -->
        @if (isset($schemaMarkup) && $schemaMarkup)
            <script type="application/ld+json">
                {!! $schemaMarkup !!}
            </script>
        @endif
    @endif

    <!-- Mobile Specific -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ setting('general', 'site_name', 'Best Shop') }}">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    {{-- <link rel="stylesheet" href="{{ asset('new/style.css') }}"> --}}
    {{-- Main Root css --}}
    <style>

        :root {
            --primary-color: #F02627;
            --secondary-color: #113056;
            --dark-color: #222;
            --light-color: #f5f5f5;
            --body-bg: #fff;
            --accent-color: #F02627;
            --text-color: #555;
            --text-light: #aaa;
            --border-color: #eee;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 5px 15px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 15px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html {
            max-width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--body-bg);
            max-width: 100% !important;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
        }

        .base-container{
            width: 100%;
            max-width: var(--container-max-width, 1340px);
            margin: 0 auto;
            padding: 0;
        }

        /* Add padding only when screen is smaller than container max-width */
        @media (max-width: 1340px) {
            .base-container {
                padding: 0 var(--container-padding, 15px);
            }
        }

        /* Mobile breakpoints */
        @media (max-width: 768px) {
            .base-container {
                padding: 0 10px;
            }

            body, html {
                overflow-x: hidden;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .base-container {
                padding: 0 5px;
            }
        }

        a {
            text-decoration: none;
        }

        a.view-all {
            z-index: 10;
        }

        .search-result-old-price{
            color: #F02627;
            text-decoration: line-through;
            font-size: 0.9em;
            margin-top: 2px;
        }
    </style>

    <!-- Then inject the dynamic override -->
    <style>
        :root {
            --primary-color: {{ \App\Services\SettingsService::getPrimaryColor() }};
            --secondary-color: {{ \App\Services\SettingsService::getSecondaryColor() }};
            --accent-color: {{ \App\Services\SettingsService::getAccentColor() }};
            --container-max-width: {{ \App\Services\SettingsService::getContainerMaxWidth() }}px;
            --container-padding: {{ \App\Services\SettingsService::getContainerPadding() }}px;

            /* Dropdown/Submenu CSS Variables */
            --dropdown-bg-color: {{ setting('general', 'dropdown_bg_color', '#ffffff') }};
            --dropdown-text-color: {{ setting('general', 'dropdown_text_color', '#333333') }};
            --dropdown-hover-bg-color: {{ setting('general', 'dropdown_hover_bg_color', '#f8f9fa') }};
            --dropdown-hover-text-color: {{ setting('general', 'dropdown_hover_text_color', 'var(--primary-color)') }};
            --dropdown-font-size: {{ setting('general', 'dropdown_font_size', '14px') }};
            --dropdown-padding: {{ setting('general', 'dropdown_padding', '10px 15px') }};

            /* Header Icons Color */
            --header-icons-color: {{ setting('general', 'header_icons_color', '#ffffff') }};
        }
    </style>

    {{-- Mega Menu CSS - Only loads when globally enabled --}}
    @if(\App\Services\SettingsService::isMegaMenuEnabled())
        <link rel="stylesheet" href="{{ asset('css/mega-menu.css') }}">
        <style>
            :root {
                --mega-menu-bg: {{ \App\Services\SettingsService::getMegaMenuBgColor() }};
                --mega-menu-header-color: {{ \App\Services\SettingsService::getMegaMenuHeaderColor() }};
                --mega-menu-link-color: {{ \App\Services\SettingsService::get('navigation', 'mega_menu_link_color', '#555555') }};
                --mega-menu-link-hover-color: {{ \App\Services\SettingsService::get('navigation', 'mega_menu_link_hover_color', '#000000') }};
            }
        </style>
    @endif

    <link rel="apple-touch-icon" href="{{ asset('new/logo.png') }}">
    @yield('styles')
    
    <!-- Custom Header Code -->
    @if(setting('general', 'custom_header_code', ''))
        {!! setting('general', 'custom_header_code', '') !!}
    @endif
</head>

<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Start Preloader -->
    @php
        $showPreloader = setting('general', 'preloader_show', '1');
        $preloaderText = setting('general', 'preloader_text', 'Thikana Shop');
    @endphp

    @if ($showPreloader == '1')
        <style>
            .preloader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: var(--body-bg);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                transition: opacity 0.3s ease-out;
            }

            .preloader.fade-out {
                opacity: 0;
            }

            .clothing-animation {
                position: relative;
                width: 200px;
                height: 200px;
            }

            .clothing-item {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0;
                filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
            }

            .clothing-item.active {
                animation: popAndSpin 0.8s forwards;
            }

            .clothing-item i {
                font-size: 80px;
                color: var(--primary-color);
                filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.1));
            }

            .loading-text {
                position: absolute;
                bottom: -40px;
                left: 0;
                width: 100%;
                text-align: center;
                font-size: 16px;
                color: var(--secondary-color);
                font-weight: 600;
                letter-spacing: 4px;
                animation: pulse 1s infinite;
            }

            .loading-bar {
                position: absolute;
                bottom: -60px;
                left: 0;
                width: 100%;
                height: 4px;
                background-color: #eee;
                border-radius: 2px;
                overflow: hidden;
            }

            .loading-progress {
                height: 100%;
                width: 0;
                background-color: var(--primary-color);
                animation: loading 1s ease-in-out infinite;
                border-radius: 2px;
            }

            @keyframes popAndSpin {
                0% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.5) rotate(-45deg);
                }

                50% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1.2) rotate(0deg);
                }

                80% {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1) rotate(0deg);
                }

                100% {
                    opacity: 0;
                    transform: translate(-50%, -50%) scale(0.8) rotate(45deg);
                }
            }

            @keyframes loading {
                0% {
                    width: 0;
                }

                100% {
                    width: 100%;
                }
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 0.7;
                }

                50% {
                    opacity: 1;
                }
            }
        </style>
        <div class="preloader">
            <div class="clothing-animation">
                <div class="clothing-item">
                    <i class="fas fa-tshirt"></i>
                </div>
                <div class="clothing-item">
                    <i class="fas fa-vest"></i>
                </div>
                <div class="clothing-item">
                    <i class="fas fa-hat-cowboy"></i>
                </div>
                <div class="clothing-item">
                    <i class="fas fa-socks"></i>
                </div>
                <div class="clothing-item">
                    <i class="fas fa-mitten"></i>
                </div>
                <div class="loading-text">Thikana Shop</div>
                <div class="loading-bar">
                    <div class="loading-progress"></div>
                </div>
            </div>
        </div>
        <!-- Preloader js -->
        <script src="{{ asset('new/preloader.js') }}"></script>
    @endif
    {{-- End Preloader --}}

    @php
        $showTopHeader = setting('general', 'top_header_bar_show', '1');
        $topHeaderVersion = setting('general', 'top_header_bar_version', 'v1');
        $showTopHeaderMobile = setting('general', 'top_header_bar_show_mobile', '1');
        $topHeaderLeft = setting('general', 'top_header_bar_left', 'অনলাইন বই দোকানে আপনাকে স্বাগতম!');
        $topHeaderEmail = setting('general', 'top_header_bar_email', 'example@gmail.com');
        $topHeaderPhone = setting('general', 'top_header_bar_phone', '+8801723-000000');
        
        // New styling options
        $topHeaderBgColor = setting('general', 'top_header_bar_bg_color', '#2c3e50');
        $topHeaderTextColor = setting('general', 'top_header_bar_text_color', '#ffffff');
        $topHeaderFontSize = setting('general', 'top_header_bar_font_size', '15px');
        
        // New layout options
        $showEmail = setting('general', 'top_header_bar_show_email', '1');
        $showPhone = setting('general', 'top_header_bar_show_phone', '1');
        $showTrackOrder = setting('general', 'top_header_bar_show_track_order', '1');
        $trackOrderText = setting('general', 'top_header_bar_track_order_text', 'Track Your Order');
        $topHeaderMarqueeText = setting('general', 'top_header_bar_marquee_text', 'অনলাইন শপে আপনাকে স্বাগতম। অনলাইনে অর্ডারে সারা বাংলাদেশে হোম ডেলিভারি।');
        $topHeaderMarqueeSpeed = max(4, (float) setting('general', 'top_header_bar_marquee_speed', '24'));
        
        // Top Header v2 menu options
        $topHeaderV2LeftMenuId = setting('general', 'top_header_v2_left_menu', '');
        $topHeaderV2RightMenuId = setting('general', 'top_header_v2_right_menu', '');
        
        // Get menus for v2
        $topHeaderV2LeftMenu = null;
        $topHeaderV2RightMenu = null;
        
        if ($topHeaderV2LeftMenuId) {
            $topHeaderV2LeftMenu = \App\Models\Menu::where('id', $topHeaderV2LeftMenuId)->where('status', true)->first();
        }
        
        if ($topHeaderV2RightMenuId) {
            $topHeaderV2RightMenu = \App\Models\Menu::where('id', $topHeaderV2RightMenuId)->where('status', true)->first();
        }
    @endphp

    @if ($showTopHeader == '1' && $topHeaderVersion == 'v1')
        <!-- Top Header Bar v1 -->
        <style>
            .top-header-section {
                background: #0f172a;
                color: #e2e8f0;
                font-size: 13px;
                font-family: 'Inter', sans-serif;
                font-weight: 500;
                width: 100%;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                position: relative;
                z-index: 1000;
            }
            .top-header-section.show-topbar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1001;
            }

            .top-header-bar {
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                padding: 10px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .top-header-bar .left,
            .top-header-bar .right {
                display: flex;
                align-items: center;
                gap: 24px;
            }

            .top-header-bar a {
                color: #e2e8f0;
                text-decoration: none;
                transition: color 0.2s;
            }

            .top-header-bar a:hover {
                color: var(--primary-color);
            }

            .top-header-bar span {
                display: flex;
                align-items: center;
            }

            .top-header-bar svg {
                margin-right: 8px;
                width: 16px;
                height: 16px;
                fill: currentColor;
            }

            @media (max-width: 768px) {
                @if ($showTopHeaderMobile == '0')
                     .top-header-section {
                         display: none !important;
                     }
                @else
                    .top-header-bar {
                        flex-direction: column;
                        font-size: 11px;
                        padding: 8px 10px;
                        text-align: center;
                        gap: 8px;
                    }

                    .top-header-bar .left,
                    .top-header-bar .right {
                        margin: 0;
                        gap: 12px;
                    }

                    .top-header-bar .left {
                        display: none;
                    }
                @endif
            }
        </style>
        <section id="topHeaderSection" class="top-header-section">
            <div class="base-container">
                <div class="top-header-bar">
                <div class="left">
                    {{ $topHeaderLeft }}
                    @if ($showEmail == '1')
                        <span>
                            <!-- Envelope SVG -->
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 20V8.236l7.293 6.364a1 1 0 0 0 1.414 0L20 8.236V20H4z" />
                            </svg>
                            <a href="mailto:{{ $topHeaderEmail }}" style="color:{{ $topHeaderTextColor }};">{{ $topHeaderEmail }}</a>
                        </span>
                    @endif
                </div>
                <div class="right">
                    @if ($showPhone == '1')
                        <span>
                            <!-- Phone SVG -->
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1c-9.39 0-17-7.61-17-17a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z" />
                            </svg>
                            <a href="tel:{{ $topHeaderPhone }}" style="color:{{ $topHeaderTextColor }};">{{ $topHeaderPhone }}</a>
                        </span>
                    @endif
                    @if ($showTrackOrder == '1')
                        <span>
                            <!-- Truck SVG -->
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M20 8V6a2 2 0 0 0-2-2H3a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h1a3 3 0 0 0 6 0h4a3 3 0 0 0 6 0h1a1 1 0 0 0 1-1v-5a1 1 0 0 0-.293-.707l-3-3A1 1 0 0 0 20 8zm-1.414 0L21 10.414V15h-1a3 3 0 0 0-6 0h-4a3 3 0 0 0-6 0H3V6h15v2zM7 19a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm10 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                            </svg>
                            <a href="{{ route('order.track') }}" style="color:{{ $topHeaderTextColor }};">{{ $trackOrderText }}</a>
                        </span>
                    @endif
                    @auth
                        @php
                            $u = auth()->user();
                            $isVendorUser = ($u->role === 'vendor' || (method_exists($u, 'isVendor') && $u->isVendor()) || (method_exists($u, 'hasRole') && $u->hasRole('vendor')) || (isset($u->user_type) && $u->user_type === 'vendor') || (isset($u->type) && $u->type === 'vendor'));
                            $isAdminUser = !$isVendorUser && ($u->isAdmin() || $u->hasRole('admin') || $u->hasRole('super_admin') || $u->hasRole('super admin') || $u->hasRole('manager') || $u->can('access admin') || $u->id == 1 || (isset($u->role) && in_array($u->role, ['admin', 'super_admin', 'manager'])));
                        @endphp

                        @if($isVendorUser)
                            <span>
                                <a href="{{ Route::has('vendor.dashboard') ? route('vendor.dashboard') : url('/vendor/dashboard') }}" style="color:#ffffff; background: #4f46e5; padding: 3px 10px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-store" style="font-size: 11px;"></i> Vendor Dashboard
                                </a>
                            </span>
                        @elseif($isAdminUser)
                            <span>
                                <a href="{{ url('/admin') }}" style="color:#ffffff; background: #2563eb; padding: 3px 10px; border-radius: 6px; font-weight: 700; text-decoration: none; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fas fa-gauge-high" style="font-size: 11px;"></i> Admin Dashboard
                                </a>
                            </span>
                        @endif
                    @endauth
                    {{-- <span>
                        <!-- Truck SVG -->
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M9 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                            <circle cx="17" cy="8" r="3"></circle>
                            <path d="M17 13c-1.11 0-2.41.35-3.83.86 1.92.71 3.22 1.9 3.83 3.14h6v-2c0-2.66-3.33-2-6-2z">
                            </path>
                        </svg>
                        <a href="{{ route('account.show') }}" style="color:#fff;">Become an Uddokta</a>
                    </span> --}}
                </div>
                </div>
            </div>
        </section>
        <!-- End Top Header Bar -->
    @endif
    @if ($showTopHeader == '1' && $topHeaderVersion == 'v2')
        <!-- Top Header Bar v2 -->
        <style>
            .top-header-v2-section {
                background: {{ $topHeaderBgColor }};
                color: {{ $topHeaderTextColor }};
                font-size: {{ $topHeaderFontSize }};
                font-weight: 400;
                width: 100%;
            }

            .top-header-v2-bar {
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                padding: 8px 0 6px 0;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .top-header-v2-bar .left,
            .top-header-v2-bar .right {
                display: flex;
                align-items: center;
                gap: 20px;
            }

            .top-header-v2-menu {
                display: flex;
                align-items: center;
                gap: 20px;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .top-header-v2-menu li {
                margin: 0;
            }

            .top-header-v2-menu a {
                color: {{ $topHeaderTextColor }};
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 5px 10px;
                border-radius: 3px;
                transition: all 0.3s ease;
            }

            .top-header-v2-menu a:hover {
                background-color: rgba(255, 255, 255, 0.1);
                color: {{ $topHeaderTextColor }};
            }

            .top-header-v2-menu i {
                font-size: 14px;
            }

            @media (max-width: 768px) {
                @if ($showTopHeaderMobile == '0')
                    .top-header-v2-section {
                        display: none !important;
                    }
                @else
                    .top-header-v2-bar {
                        flex-direction: column;
                        font-size: 12px;
                        padding: 8px 0;
                        text-align: center;
                    }

                    .top-header-v2-bar .left,
                    .top-header-v2-bar .right {
                        margin: 0;
                        gap: 10px;
                    }

                    .top-header-v2-menu {
                        gap: 10px;
                        flex-wrap: wrap;
                        justify-content: center;
                    }

                    .top-header-v2-menu a {
                        padding: 3px 8px;
                        font-size: 12px;
                    }
                @endif
            }
        </style>
        <section id="topHeaderV2Section" class="top-header-v2-section">
            <div class="base-container">
                <div class="top-header-v2-bar">
                    <div class="left">
                        @if ($topHeaderV2LeftMenu && $topHeaderV2LeftMenu->menuItems->count() > 0)
                            <ul class="top-header-v2-menu">
                                @foreach ($topHeaderV2LeftMenu->menuItems->where('parent_id', null)->where('status', true)->sortBy('order') as $menuItem)
                                    <li>
                                        <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}">
                                            @if ($menuItem->icon_class)
                                                <i class="{{ $menuItem->icon_class }}"></i>
                                            @endif
                                            {{ $menuItem->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="right">
                        @if ($topHeaderV2RightMenu && $topHeaderV2RightMenu->menuItems->count() > 0)
                            <ul class="top-header-v2-menu">
                                @foreach ($topHeaderV2RightMenu->menuItems->where('parent_id', null)->where('status', true)->sortBy('order') as $menuItem)
                                    <li>
                                        <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}">
                                            @if ($menuItem->icon_class)
                                                <i class="{{ $menuItem->icon_class }}"></i>
                                            @endif
                                            {{ $menuItem->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- End Top Header Bar v2 -->
    @endif

    @if ($showTopHeader == '1' && $topHeaderVersion == 'v3')
        <!-- Top Header Bar v3 -->
        <style>
            .top-header-v3-section {
                background: {{ $topHeaderBgColor }};
                color: {{ $topHeaderTextColor }};
                font-size: {{ $topHeaderFontSize }};
                width: 100%;
            }
            .top-header-v3-section .base-container{
                padding: 0;
            }

            .top-header-v3-bar {
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                display: flex;
                align-items: stretch;
            }

            .top-header-v3-left {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #f8f9fa;
                color: #111;
                padding: 10px 18px;
                font-weight: 700;
                min-width: 260px;
                border-right: 1px solid rgba(0, 0, 0, 0.08);
            }

            .top-header-v3-left svg {
                width: 22px;
                height: 22px;
                fill: currentColor;
            }

            .top-header-v3-left a {
                color: inherit;
                text-decoration: none;
            }

            .top-header-v3-right {
                flex: 1;
                overflow: hidden;
                background: {{ $topHeaderBgColor }};
                display: flex;
                align-items: center;
                padding: 8px 0;
                color: {{ $topHeaderTextColor }};
            }

            .top-header-v3-marquee {
                width: 100%;
                overflow: hidden;
            }

            .top-header-v3-marquee .marquee-track {
                display: inline-flex;
                align-items: center;
                white-space: nowrap;
                width: max-content;
                animation: topHeaderV3Marquee {{ $topHeaderMarqueeSpeed }}s linear infinite;
            }

            .top-header-v3-marquee span {
                color: {{ $topHeaderTextColor }};
                font-weight: 500;
                padding-right: 40px;
            }

            @keyframes topHeaderV3Marquee {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(-50%);
                }
            }

            @media (max-width: 992px) {
                .top-header-v3-left {
                    min-width: 200px;
                    font-size: 14px;
                    padding: 8px 14px;
                }

                .top-header-v3-marquee .marquee-track {
                    animation-duration: {{ max(4, $topHeaderMarqueeSpeed * 0.85) }}s;
                }
            }

            @media (max-width: 768px) {
                @if ($showTopHeaderMobile == '0')
                    .top-header-v3-section {
                        display: none !important;
                    }
                @else
                    .top-header-v3-bar {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .top-header-v3-left {
                        width: 100%;
                        justify-content: center;
                    }

                    .top-header-v3-right {
                        width: 100%;
                        padding: 6px 0;
                    }

                    .top-header-v3-marquee .marquee-track {
                        animation-duration: {{ max(4, $topHeaderMarqueeSpeed * 0.67) }}s;
                    }
                @endif
            }
        </style>
        <section class="top-header-v3-section">
            <div class="base-container">
                <div class="top-header-v3-bar">
                    <div class="top-header-v3-left">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1c-9.39 0-17-7.61-17-17a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z" />
                        </svg>
                        <a href="tel:{{ $topHeaderPhone }}">{{ $topHeaderPhone }}</a>
                    </div>
                    <div class="top-header-v3-right">
                        <div class="top-header-v3-marquee">
                            <div class="marquee-track">
                                <span>{{ $topHeaderMarqueeText }}</span>
                                <span aria-hidden="true">{{ $topHeaderMarqueeText }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Top Header Bar v3 -->
    @endif

    @php
        $headerLayout = setting('general', 'header_layout', 'v4');
    @endphp

    @if ($headerLayout == 'v1')
        <!-- Start Desktop Header v1-->
        @php
            $total = 0;
            $carts = collect();
            $cartCount = 0;

            if (auth()->check()) {
                $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
            } else {
                $guestId = request()->cookie('guest_id');
                if ($guestId) {
                    $carts = \App\Models\Cart::where('guest_id', $guestId)->get();
                }
            }

            if (!$carts->isEmpty()) {
                $cartCount = $carts->count();
                foreach ($carts as $cart) {
                    // Use pre-calculated subtotal if available, otherwise calculate
                    $total += $cart->calculated_subtotal ?? ($cart->price * $cart->qunt);
                }
            }
        @endphp
        <style>
            .main-header-section {
                background-color: var(--light-color);
                border-bottom: 3px solid var(--border-color);
            }

            .header {
                display: grid;
                grid-template-columns: 1fr auto 1fr;
                align-items: center;
                padding: 5px 5px;
            }

            .logo {
                text-align: left;
            }

            .logo img {
                width: {{ setting('general', 'logo_width', '140px') }}px;
                height: auto;
                transition: transform 0.3s ease;
            }

            .logo img:hover {
                transform: scale(1.05);
            }

            .search-bar {
                display: flex;
                align-items: center;
                border: 1px solid var(--border-color);
                background-color: var(--body-bg);
                padding: 8px 15px;
                border-radius: 25px;
                width: 250px;
                box-shadow: var(--shadow-sm);
                position: relative;
            }

            .search-bar input {
                border: none;
                outline: none;
                width: 100%;
                margin-right: 10px;
                font-size: 14px;
                padding-left: 25px;
                padding-right: 10px;
            }

            .search-bar .search-icon {
                position: absolute;
                left: 13px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
            }

            .search-bar .clear-search-icon {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                cursor: pointer;
                display: none;
                font-size: 14px;
            }

            .search-bar .clear-search-icon.visible {
                display: block;
            }

            .search-results-container {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--body-bg);
                border-radius: 0 0 8px 8px;
                box-shadow: var(--shadow-md);
                z-index: 3000;
                max-height: 400px;
                overflow-y: auto;
                margin-top: 7px;
                display: none;
            }

            .search-result-item {
                display: flex;
                padding: 10px 15px;
                border-bottom: 1px solid var(--border-color);
                transition: background-color 0.2s;
            }

            .search-result-item:hover {
                background-color: var(--light-color);
            }

            .search-result-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 15px;
            }

            .search-result-info {
                flex: 1;
            }

            .search-result-title {
                font-weight: 500;
                margin-bottom: 5px;
            }

            .search-result-price {
                color: var(--primary-color);
                font-weight: 500;
            }

            .search-result-category {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .search-no-results {
                padding: 15px;
                text-align: center;
                color: var(--text-light);
            }

            .search-all-results {
                display: block;
                text-align: center;
                padding: 10px;
                background-color: #f7fafc;
                color: #4a5568;
                font-weight: 500;
                border-top: 1px solid #e2e8f0;
            }

            .search-all-results:hover {
                background-color: #edf2f7;
            }

            h3.search-section-title {
                font-size: 16px;
                padding: 15px;
            }

            .nav-icons {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 18px;
            }

            .nav-icons a {
                color: var(--text-color);
                text-decoration: none;
                position: relative;
                transition: color 0.3s;
            }

            .wishlist-icon {
                margin-bottom: -3px;
            }

            .nav-icons a:not(:last-of-type) {
                margin-bottom: -6px;
            }

            .profile-icon {
                margin-bottom: -6px;
            }

            .nav-icons a:hover {
                color: var(--primary-color);
            }

            .nav-icons .cart-count {
                position: absolute;
                top: -2px;
                right: 5px;
                background-color: var(--accent-color, var(--primary-color));
                color: white;
                border-radius: 50%;
                width: 12px;
                height: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .nav-icons i {
                font-size: 20px;
            }
            @media(max-width:768px) {

                .search-bar {
                    display: none;
                }

                .header {
                    grid-template-columns: 1fr auto;
                    justify-content: space-between;
                }

                .logo img {
                    width: {{ setting('general', 'mobile_logo_width', '140px') }}px;
                    margin-bottom: -5px;
                }
            }

            @media(max-width:576) {
                .nav-icons a {
                    margin-left: 15px;
                }
            }
        </style>
        <section id="mainHeaderSection" class="main-header-section">
            <header class="base-container header">

                <div class="search-bar">
                    <input type="text" id="header-v1-search-input" placeholder="What are you looking for?">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="search-icon">
                        <path
                            d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                    </svg>

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                        class="clear-search-icon" id="desktop-clear-search">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                    <div id="header-v1-search-results" class="search-results-container"></div>
                </div>

                <a href="{{ route('index') }}" class="logo">
                    @if (setting('general', 'logo'))
                        <img src="{{ \App\Services\SettingsService::getLogo() }}"
                            alt="{{ setting('general', 'site_name', 'Shop') }}">
                    @else
                        <img src="{{ asset('new/thikana.png') }}"
                            alt="{{ setting('general', 'site_name', 'Shop') }}" style="width: 140px">
                    @endif
                </a>

                <div class="nav-icons">
                    <a href="#" title="Wishlist">
                        <div class="wishlist-icon">
                            <svg class="web-primary-color" width="22" height="18" viewBox="0 0 22 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.6875 0C13.7516 0 12.0566 0.8325 11 2.23969C9.94344 0.8325 8.24844 0 6.3125 0C3.10384 0.00361655 0.503617 2.60384 0.5 5.8125C0.5 12.375 10.2303 17.6869 10.6447 17.9062C10.8665 18.0256 11.1335 18.0256 11.3553 17.9062C11.7697 17.6869 21.5 12.375 21.5 5.8125C21.4964 2.60384 18.8962 0.00361655 15.6875 0V0ZM11 16.3875C9.28813 15.39 2 10.8459 2 5.8125C2.0031 3.43206 3.93206 1.5031 6.3125 1.5C8.13594 1.5 9.66687 2.47125 10.3062 4.03125C10.4218 4.31259 10.6959 4.49627 11 4.49627C11.3041 4.49627 11.5782 4.31259 11.6938 4.03125C12.3331 2.46844 13.8641 1.5 15.6875 1.5C18.0679 1.5031 19.9969 3.43206 20 5.8125C20 10.8384 12.71 15.3891 11 16.3875V16.3875Z" />
                            </svg>

                        </div>
                    </a>

                    <a href="{{ route('cart.index') }}" title="Shopping Cart" class="cart-drawer-trigger">


                        <div class="cart-icon">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" />
                            </svg>
                        </div>
                        <span class="cart-count">{{ $cartCount }}</span>
                    </a>
                    <a href="{{ route('account.show') }}" title="My Account">
                        <div class="profile-icon">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.2313 18.375C18.8319 15.9269 16.6716 14.1685 14.1455 13.3374C16.7168 11.8055 17.9799 8.74371 17.1836 5.86726C16.3874 2.99081 13.7903 0.997722 10.5 0.997722C7.20975 0.997722 4.61261 2.99081 3.81637 5.86726C3.02013 8.74371 4.28324 11.8055 6.85453 13.3374C4.32844 14.1675 2.16812 15.9259 0.76875 18.375C0.619540 18.6129 0.614176 18.9107 0.751669 19.1533C0.889162 19.3959 1.14893 19.5453 1.42689 19.5429C1.70486 19.5404 1.96256 19.3861 2.09718 19.1406C3.88774 16.0513 7.06471 14.2031 10.5 14.2031C13.9353 14.2031 17.1123 16.0513 18.9028 19.1406C19.0374 19.3861 19.2951 19.5404 19.5731 19.5429C19.8511 19.5453 20.1108 19.3959 20.2483 19.1533C20.3858 18.9107 20.3805 18.6129 20.2313 18.375V18.375ZM4.9875 7.4531C4.9875 4.60539 7.35229 2.2406 10.5 2.2406C13.6477 2.2406 16.0125 4.60539 16.0125 7.4531C16.0125 10.3008 13.6477 12.6656 10.5 12.6656C7.35356 12.6625 4.99044 10.2994 4.9875 7.4531V7.4531Z" />
                            </svg>
                        </div>
                    </a>
                    <div class="mobile-menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
                            <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

            </header>
        </section>
        {{-- End Desktop Header v1 --}}
    @elseif ($headerLayout == 'v2')
        <!-- Start Desktop Header v2-->
        @php
            $total = 0;
            $carts = collect();
            $cartCount = 0;

            if (auth()->check()) {
                $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
            } else {
                $guestId = request()->cookie('guest_id');
                if ($guestId) {
                    $carts = \App\Models\Cart::where('guest_id', $guestId)->get();
                }
            }

            if (!$carts->isEmpty()) {
                $cartCount = $carts->count();
                foreach ($carts as $cart) {
                    // Use pre-calculated subtotal if available, otherwise calculate
                    $total += $cart->calculated_subtotal ?? ($cart->price * $cart->qunt);
                }
            }
        @endphp
        <style>
            .main-header-section {
                background-color: var(--light-color);
                border-bottom: 3px solid var(--border-color);
            }

            .header {
                width: 100%;
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 15px;
                padding: 8px 15px;
                flex-wrap: nowrap;
            }

            .logo {
                text-align: left;
                flex-shrink: 0;
            }

            .logo img {
                width: {{ setting('general', 'logo_width', '140px') }}px;
                height: auto;
                transition: transform 0.3s ease;
            }

            .logo img:hover {
                transform: scale(1.05);
            }

            .search-bar {
                display: flex;
                align-items: center;
                border: 1.5px solid var(--primary-color);
                background: #fff;
                border-radius: 30px;
                padding: 0;
                flex: 1 1 180px;
                min-width: 120px;
                max-width: 700px;
                box-shadow: none;
                position: relative;
                margin: 0;
            }

            .search-bar input {
                border: none;
                outline: none;
                width: 100%;
                font-size: 16px;
                padding: 16px 24px;
                border-radius: 30px 0 0 30px;
                background: transparent;
                color: #444;
            }

            .search-bar .search-btn {
                background: none;
                border: none;
                outline: none;
                margin-right: 0px;
                margin-left: -46px;
                border-radius: 50%;
                width: 43px;
                height: 43px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--primary-color);
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(229, 57, 53, 0.08);
                transition: background 0.2s;
            }

            .search-bar .search-btn svg {
                display: block;
            }
            .search-bar .clear-search-icon {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                cursor: pointer;
                display: none;
                font-size: 14px;
            }

            .search-bar .clear-search-icon.visible {
                display: block;
            }

            .search-results-container {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--body-bg);
                border-radius: 0 0 8px 8px;
                box-shadow: var(--shadow-md);
                z-index: 3000;
                max-height: 400px;
                overflow-y: auto;
                margin-top: 7px;
                display: none;
            }

            .search-result-item {
                display: flex;
                padding: 10px 15px;
                border-bottom: 1px solid var(--border-color);
                transition: background-color 0.2s;
            }

            .search-result-item:hover {
                background-color: var(--light-color);
            }

            .search-result-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 15px;
            }

            .search-result-info {
                flex: 1;
            }

            .search-result-title {
                font-weight: 500;
                margin-bottom: 5px;
            }

            .search-result-price {
                color: var(--primary-color);
                font-weight: 500;
            }

            .search-result-category {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .search-no-results {
                padding: 15px;
                text-align: center;
                color: var(--text-light);
            }

            .search-all-results {
                display: block;
                text-align: center;
                padding: 10px;
                background-color: #f7fafc;
                color: #4a5568;
                font-weight: 500;
                border-top: 1px solid #e2e8f0;
            }

            .search-all-results:hover {
                background-color: #edf2f7;
            }

            h3.search-section-title {
                font-size: 16px;
                padding: 15px;
            }

            .nav-icons {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 18px;
            }

            .nav-icons a {
                color: var(--text-color);
                text-decoration: none;
                position: relative;
                transition: color 0.3s;
            }

            .wishlist-icon {
                margin-bottom: -6px;
            }

            .nav-icons a:not(:last-of-type) {
                margin-bottom: -6px;
            }


            .nav-icons a:hover {
                color: var(--primary-color);
            }

            .nav-icons .cart-count {
                position: absolute;
                top: -2px;
                right: 5px;
                background-color: var(--accent-color, var(--primary-color));
                color: white;
                border-radius: 50%;
                width: 12px;
                height: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .nav-icons i {
                font-size: 20px;
            }

            .profile-icon-area {
                display: none;
            }

            .register-btn {
                background: var(--accent-color);
                color: #fff !important;
                padding: 10px 22px;
                border-radius: 30px;
                display: inline-flex;
                align-items: center;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
            }

            @media(max-width:768px) {

                .logo img {
                    width: {{ setting('general', 'mobile_logo_width', '140px') }}px;
                    margin-bottom: -5px;
                }

                .profile-icon-area {
                    display: block;
                }

                .search-bar {
                    display: none;
                }

                .header {
                    grid-template-columns: 1fr auto;
                    justify-content: space-between;
                }

                .mainmenulogin {
                    display: none;
                }

                .register-btn {
                    display: none;
                }
            }

            @media(max-width:576) {
                .nav-icons a {
                    margin-left: 15px;
                }
            }
        </style>
        <section id="mainHeaderSection" class="main-header-section">
            <header class="base-container header">

                <a href="{{ route('index') }}" class="logo">
                    @if (setting('general', 'logo'))
                        <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="Thikana Logo">
                    @else
                        <img src="{{ asset('new/thikana.png') }}" alt="Thikana Logo" style="140px">
                    @endif
                </a>

                <div class="search-bar">
                    <input type="text" id="search-input" placeholder="Search Your Product Here...">
                    <button type="button" class="search-btn">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10"
                                fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
                            <path
                                d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                                fill="#fff" />
                        </svg>
                    </button>
                    <div id="search-results" class="search-results-container"></div>
                </div>

                <div class="nav-icons">
                    <a href="#" title="Wishlist">
                        <div class="wishlist-icon">
                            <svg class="web-primary-color" width="22" height="18" viewBox="0 0 22 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.6875 0C13.7516 0 12.0566 0.8325 11 2.23969C9.94344 0.8325 8.24844 0 6.3125 0C3.10384 0.00361655 0.503617 2.60384 0.5 5.8125C0.5 12.375 10.2303 17.6869 10.6447 17.9062C10.8665 18.0256 11.1335 18.0256 11.3553 17.9062C11.7697 17.6869 21.5 12.375 21.5 5.8125C21.4964 2.60384 18.8962 0.00361655 15.6875 0V0ZM11 16.3875C9.28813 15.39 2 10.8459 2 5.8125C2.0031 3.43206 3.93206 1.5031 6.3125 1.5C8.13594 1.5 9.66687 2.47125 10.3062 4.03125C10.4218 4.31259 10.6959 4.49627 11 4.49627C11.3041 4.49627 11.5782 4.31259 11.6938 4.03125C12.3331 2.46844 13.8641 1.5 15.6875 1.5C18.0679 1.5031 19.9969 3.43206 20 5.8125C20 10.8384 12.71 15.3891 11 16.3875V16.3875Z" />
                            </svg>

                        </div>
                    </a>

                    <a href="{{ route('cart.index') }}" title="Shopping Cart" class="cart-drawer-trigger">


                        <div class="cart-icon">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" />
                            </svg>
                        </div>
                        <span class="cart-count">{{ $cartCount }}</span>
                    </a>
                    <a href="{{ route('account.show') }}" title="My Account" class="profile-icon-area">
                        <div class="profile-icon">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.2313 18.375C18.8319 15.9269 16.6716 14.1685 14.1455 13.3374C16.7168 11.8055 17.9799 8.74371 17.1836 5.86726C16.3874 2.99081 13.7903 0.997722 10.5 0.997722C7.20975 0.997722 4.61261 2.99081 3.81637 5.86726C3.02013 8.74371 4.28324 11.8055 6.85453 13.3374C4.32844 14.1675 2.16812 15.9259 0.76875 18.375C0.619540 18.6129 0.614176 18.9107 0.751669 19.1533C0.889162 19.3959 1.14893 19.5453 1.42689 19.5429C1.70486 19.5404 1.96256 19.3861 2.09718 19.1406C3.88774 16.0513 7.06471 14.2031 10.5 14.2031C13.9353 14.2031 17.1123 16.0513 18.9028 19.1406C19.0374 19.3861 19.2951 19.5404 19.5731 19.5429C19.8511 19.5453 20.1108 19.3959 20.2483 19.1533C20.3858 18.9107 20.3805 18.6129 20.2313 18.375V18.375ZM4.9875 7.4531C4.9875 4.60539 7.35229 2.2406 10.5 2.2406C13.6477 2.2406 16.0125 4.60539 16.0125 7.4531C16.0125 10.3008 13.6477 12.6656 10.5 12.6656C7.35356 12.6625 4.99044 10.2994 4.9875 7.4531V7.4531Z" />
                            </svg>
                        </div>
                    </a>
                    @if (auth()->check())
                        <a href="{{ route('account.show') }}" class="register-btn">
                            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2"
                                style="margin-right:8px;">
                                <circle cx="10" cy="7" r="4" />
                                <path d="M2 19c0-4 8-4 8-4s8 0 8 4" />
                            </svg>
                            My Account
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mainmenulogin"
                            style="margin: 0 10px; font-size: 18px;">Log In</a>
                        <a href="{{ route('register') }}" class="register-btn">
                            <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2"
                                style="margin-right:8px;">
                                <circle cx="10" cy="7" r="4" />
                                <path d="M2 19c0-4 8-4 8-4s8 0 8 4" />
                            </svg>
                            Register
                        </a>
                    @endif
                    <div class="mobile-menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
                            <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

            </header>
        </section>
        <style>
            /* Hide mobile search bar on desktop */
            .search-bar-mobile {
                display: none;
            }

            @media (max-width: 768px) {
                .search-bar-mobile {
                    display: flex;
                    align-items: center;
                    position: relative;
                    padding: 5px;
                    background: #fff;
                    z-index: 100;
                    transition: all 0.3s ease;
                }

                .search-bar-mobile.fixed-top {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                .search-bar-mobile input[type="text"] {
                    flex: 1;
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                    /* border-radius: 5px; */
                    outline: none;
                    line-height: 18px;
                }

                .search-bar-mobile .search-btn {
                    background: var(--primary-color);
                    border: none;
                    padding: 9px 10px;
                    /* border-radius: 5px; */
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                }

                .search-results-container {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: #fff;
                    border: 1px solid #eee;
                    border-top: none;
                    max-height: 300px;
                    overflow-y: auto;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                    z-index: 101;
                }
            }
        </style>
        <div class="search-bar-mobile">
            <input type="text" id="mobile-search-input-1" placeholder="Search Your Product Here...">
            <button type="button" class="search-btn">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10"
                        fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
                    <path
                        d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                        fill="#fff" />
                </svg>
            </button>
            <div id="mobile-search-results-1" class="search-results-container"></div>
        </div>
        {{-- End Desktop Header v2 --}}
    @elseif ($headerLayout == 'v3')
        <!-- Start Desktop Header v3 -->
        @php
            $total = 0;
            $carts = collect();
            $cartCount = 0;

            if (auth()->check()) {
                $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
            } else {
                $guestId = request()->cookie('guest_id');
                if ($guestId) {
                    $carts = \App\Models\Cart::where('guest_id', $guestId)->get();
                }
            }

            if (!$carts->isEmpty()) {
                $cartCount = $carts->count();
                foreach ($carts as $cart) {
                    // Use pre-calculated subtotal if available, otherwise calculate
                    $total += $cart->calculated_subtotal ?? ($cart->price * $cart->qunt);
                }
            }
        @endphp
        <style>
            .main-header-section {
                background-color: var(--light-color);
                border-bottom: 3px solid var(--border-color);
            }

            .header {
                width: 100%;
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 8px 15px;
            }

            .logo {
                text-align: left;
            }

            .logo img {
                width: {{ setting('general', 'logo_width', '140px') }}px;
                height: auto;
                transition: transform 0.3s ease;
            }

            .logo img:hover {
                transform: scale(1.05);
            }

            .search-bar {
                display: flex;
                align-items: center;
                border: 1.5px solid var(--primary-color);
                background: #fff;
                border-radius: 30px;
                padding: 0;
                width: 100%;
                max-width: 700px;
                box-shadow: none;
                position: relative;
                margin: 0 30px;
            }

            .search-bar input {
                border: none;
                outline: none;
                width: 100%;
                font-size: 16px;
                padding: 16px 24px;
                border-radius: 30px 0 0 30px;
                background: transparent;
                color: #444;
            }

            .search-bar .search-btn {
                background: none;
                border: none;
                outline: none;
                margin-right: 0px;
                margin-left: -46px;
                border-radius: 50%;
                width: 43px;
                height: 43px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--primary-color);
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(229, 57, 53, 0.08);
                transition: background 0.2s;
            }

            .search-bar .search-btn svg {
                display: block;
            }

            .search-bar .clear-search-icon {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                cursor: pointer;
                display: none;
                font-size: 14px;
            }

            .search-bar .clear-search-icon.visible {
                display: block;
            }

            .search-results-container {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--body-bg);
                border-radius: 0 0 8px 8px;
                box-shadow: var(--shadow-md);
                z-index: 3000;
                max-height: 400px;
                overflow-y: auto;
                margin-top: 7px;
                display: none;
            }

            .search-result-item {
                display: flex;
                padding: 10px 15px;
                border-bottom: 1px solid var(--border-color);
                transition: background-color 0.2s;
            }

            .search-result-item:hover {
                background-color: var(--light-color);
            }

            .search-result-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 15px;
            }

            .search-result-info {
                flex: 1;
            }

            .search-result-title {
                font-weight: 500;
                margin-bottom: 5px;
            }

            .search-result-price {
                color: var(--primary-color);
                font-weight: 500;
            }

            .search-result-category {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .search-no-results {
                padding: 15px;
                text-align: center;
                color: var(--text-light);
            }

            .search-all-results {
                display: block;
                text-align: center;
                padding: 10px;
                background-color: #f7fafc;
                color: #4a5568;
                font-weight: 500;
                border-top: 1px solid #e2e8f0;
            }

            .search-all-results:hover {
                background-color: #edf2f7;
            }

            h3.search-section-title {
                font-size: 16px;
                padding: 15px;
            }

            .nav-icons {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 18px;
            }
            .nav-icons a {
                color: var(--text-color);
                text-decoration: none;
                position: relative;
                transition: color 0.3s;
            }

            .wishlist-icon {
                margin-bottom: -6px;
            }

            .nav-icons a:not(:last-of-type) {
                margin-bottom: -6px;
            }


            .nav-icons a:hover {
                color: var(--primary-color);
            }

            a.shpooing-cart {
                display: flex;
            }

            .cart-icon {
                position: relative;
            }

            .cart-total {
                color: rgb(0, 0, 0);
                font-size: 15px;
                font-weight: 500;
                white-space: nowrap;
                min-width: 60px;
                text-align: center;
                margin-left: 10px;
                margin-top: 3px;
            }

            .nav-icons .cart-count {
                position: absolute;
                top: -2px;
                right: 5px;
                background-color: var(--accent-color, var(--primary-color));
                color: white;
                border-radius: 50%;
                width: 12px;
                height: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .v3-wishlist-icon {
                margin-bottom: 0px !important;
            }

            .nav-icons i {
                font-size: 20px;
            }

            .profile-icon-area {
                display: none;
            }

            .register-btn {
                background: var(--accent-color);
                color: #fff !important;
                padding: 10px 22px;
                border-radius: 30px;
                display: inline-flex;
                align-items: center;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
            }

            @media(max-width:768px) {

                .logo img {
                    width: {{ setting('general', 'mobile_logo_width', '140px') }}px;
                    margin-bottom: -5px;
                }

                .profile-icon-area {
                    display: block;
                }

                .search-bar {
                    display: none;
                }

                .header {
                    grid-template-columns: 1fr auto;
                    justify-content: space-between;
                }

                .mainmenulogin {
                    display: none;
                }

                .register-btn {
                    display: none;
                }
            }

            @media(max-width:576) {
                .nav-icons a {
                    margin-left: 15px;
                }
            }
        </style>
        <section id="mainHeaderSection" class="main-header-section">
            <header class="base-container header">

                <a href="{{ route('index') }}" class="logo">
                    @if (setting('general', 'logo'))
                        <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="Thikana Logo">
                    @else
                        <img src="{{ asset('new/thikana.png') }}" alt="Thikana Logo" style="140px">
                    @endif
                </a>

                <span class="dekstop-menu-icon" id="sidebarMenuToggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
                        <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>

                <div class="search-bar">
                    <select id="category-select" class="category-select"
                        style="border: none; outline: none; background: transparent; padding: 13px 15px; font-size: 14px; color: #666; border-right: 1px solid #eee; cursor: pointer; min-width: 120px; border-radius: 0; border-top-left-radius: 30px; border-bottom-left-radius: 30px;">
                        <option value="">All Categories</option>
                        @foreach (\App\Models\ProductCategory::where('status', 1)->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="main-search-input" placeholder="Search for Products"
                        style="border: none; outline: none; background: transparent; padding: 13px 15px; font-size: 14px; color: #666; width: 100%; border-radius: 0;">
                    <button type="button" class="search-btn"
                        style="border: none; outline: none; background: var(--primary-color); border-radius: 50%; width: 56px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; margin-right: 2px; margin-left: 0;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                                fill="#fff" />
                        </svg>
                    </button>
                    <div id="search-results" class="search-results-container"></div>
                </div>

                <div class="nav-icons">
                    <a href="#" title="Wishlist" class="v3-wishlist-icon">
                        <div class="wishlist-icon">
                            <svg class="web-primary-color" width="22" height="18" viewBox="0 0 22 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.6875 0C13.7516 0 12.0566 0.8325 11 2.23969C9.94344 0.8325 8.24844 0 6.3125 0C3.10384 0.00361655 0.503617 2.60384 0.5 5.8125C0.5 12.375 10.2303 17.6869 10.6447 17.9062C10.8665 18.0256 11.1335 18.0256 11.3553 17.9062C11.7697 17.6869 21.5 12.375 21.5 5.8125C21.4964 2.60384 18.8962 0.00361655 15.6875 0V0ZM11 16.3875C9.28813 15.39 2 10.8459 2 5.8125C2.0031 3.43206 3.93206 1.5031 6.3125 1.5C8.13594 1.5 9.66687 2.47125 10.3062 4.03125C10.4218 4.31259 10.6959 4.49627 11 4.49627C11.3041 4.49627 11.5782 4.31259 11.6938 4.03125C12.3331 2.46844 13.8641 1.5 15.6875 1.5C18.0679 1.5031 19.9969 3.43206 20 5.8125C20 10.8384 12.71 15.3891 11 16.3875V16.3875Z" />
                            </svg>

                        </div>
                    </a>

                    <a href="{{ route('cart.index') }}" class="shpooing-cart cart-drawer-trigger" title="Shopping Cart">


                        <div class="cart-icon">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" />
                            </svg>
                            <span class="cart-count">{{ $cartCount }}</span>
                        </div>
                        <div class="cart-total">BDT {{ number_format($total, 2) }}</div>
                    </a>

                    <div class="mobile-menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
                            <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>

            </header>
        </section>
        <style>
            /* Hide mobile search bar on desktop */
            .search-bar-mobile {
                display: none;
            }

            @media (max-width: 768px) {
                .search-bar-mobile {
                    display: flex;
                    align-items: center;
                    position: relative;
                    padding: 5px;
                    background: #fff;
                    z-index: 100;
                    transition: all 0.3s ease;
                }

                .search-bar-mobile.fixed-top {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                .search-bar-mobile input[type="text"] {
                    flex: 1;
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                    /* border-radius: 5px; */
                    outline: none;
                    line-height: 18px;
                }

                .search-bar-mobile .search-btn {
                    background: var(--primary-color);
                    border: none;
                    padding: 9px 10px;
                    /* border-radius: 5px; */
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                }

                .search-results-container {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: #fff;
                    border: 1px solid #eee;
                    border-top: none;
                    max-height: 300px;
                    overflow-y: auto;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                    z-index: 101;
                }
            }
        </style>
        <div class="search-bar-mobile">
            <input type="text" id="mobile-search-input-1" placeholder="Search Your Product Here...">
            <button type="button" class="search-btn">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10"
                        fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
                    <path
                        d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                        fill="#fff" />
                </svg>
            </button>
            <div id="mobile-search-results-1" class="search-results-container"></div>
        </div>

        <style>
            /* Desktop Sidebar Menu Styles */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9998;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
    
            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
    
            .sidebar-menu {
                position: fixed;
                top: 0;
                left: -400px;
                width: 400px;
                height: 100%;
                background: #fff;
                z-index: 9999;
                transition: left 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
            }
    
            .sidebar-menu.active {
                left: 0;
            }
    
            .sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px;
                border-bottom: 1px solid #e5e5e5;
                background: #f8f9fa;
            }
    
            .sidebar-header h3 {
                margin: 0;
                font-size: 18px;
                font-weight: 600;
                color: #333;
            }
    
            .sidebar-close {
                background: none;
                border: none;
                padding: 8px;
                cursor: pointer;
                border-radius: 4px;
                transition: background 0.2s ease;
            }
    
            .sidebar-close:hover {
                background: #e9ecef;
            }
    
            .sidebar-close svg {
                width: 20px;
                height: 20px;
                stroke: #666;
            }
    
            .sidebar-content {
                flex: 1;
                overflow-y: auto;
                padding: 0;
            }
    
            .sidebar-menu-list {
                list-style: none;
                margin: 0;
                padding: 0;
            }
    
            .sidebar-menu-item {
                border-bottom: 1px solid #f0f0f0;
            }
    
            .sidebar-menu-item:last-child {
                border-bottom: none;
            }
    
            .sidebar-menu-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px 20px;
                text-decoration: none;
                color: #333;
                transition: all 0.2s ease;
                position: relative;
            }
    
            /* Level-based padding for nested items */
            .sidebar-menu-item.level-2 .sidebar-menu-link {
                padding-left: 40px;
                background: #f9f9f9;
            }
    
            .sidebar-menu-item.level-3 .sidebar-menu-link {
                padding-left: 60px;
                background: #f5f5f5;
            }
    
            .sidebar-menu-item.level-4 .sidebar-menu-link {
                padding-left: 80px;
                background: #f1f1f1;
            }
    
            .sidebar-menu-link:hover {
                background: #e3f2fd !important;
                color: var(--primary-color);
            }
            .sidebar-menu-link.active {
                background: var(--primary-color) !important;
                color: #fff;
            }
    
            .sidebar-menu-link i {
                width: 20px;
                font-size: 16px;
                text-align: center;
            }
    
            .menu-text {
                flex: 1;
                font-size: 14px;
                font-weight: 500;
            }
    
            .menu-arrow {
                width: 16px;
                height: 16px;
                stroke: currentColor;
                transition: transform 0.3s ease;
            }
    
            .sidebar-menu-link.has-children.expanded .menu-arrow {
                transform: rotate(180deg);
            }
    
            /* Submenu styles */
            .sidebar-submenu {
                list-style: none;
                margin: 0;
                padding: 0;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }
    
            .sidebar-submenu.expanded {
                max-height: 1000px;
            }
    
            .no-menu-items {
                padding: 40px 20px;
                text-align: center;
                color: #666;
                font-size: 14px;
            }
    
            /* Desktop Menu Toggle */
            .dekstop-menu-icon {
                cursor: pointer;
                padding: 8px;
                border-radius: 4px;
                transition: background 0.2s ease;
            }
    
            .dekstop-menu-icon:hover {
                background: rgba(0, 0, 0, 0.05);
            }
    
            .dekstop-menu-icon svg {
                stroke: #333;
            }
    
            /* Hide on mobile */
            @media (max-width: 992px) {
                .dekstop-menu-icon {
                    display: none;
                }
    
                .sidebar-menu {
                    display: none;
                }
    
                .sidebar-overlay {
                    display: none;
                }
            }
    
            /* Responsive adjustments */
            @media (max-width: 480px) {
                .sidebar-menu {
                    width: 320px;
                    left: -320px;
                }
            }
        </style>
        <!-- Desktop Sidebar Menu -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>
        <div id="sidebarMenu" class="sidebar-menu">
            <div class="sidebar-header">
                <h3>Menu</h3>
                <button id="sidebarClose" class="sidebar-close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
    
            <div class="sidebar-content">
                @php
                    // Get the sidebar menu from the database
                    $sidebarMenu = \App\Models\Menu::where('location', 'sidebar-menu-desktop')
                        ->where('status', true)
                        ->first();
    
                    // Get menu items with eager loading of all nested children
                    $sidebarMenuItems = $sidebarMenu
                        ? $sidebarMenu
                            ->allMenuItems()
                            ->whereNull('parent_id')
                            ->where('status', true)
                            ->with([
                                'children' => function ($query) {
                                    $query
                                        ->where('status', true)
                                        ->orderBy('order')
                                        ->with([
                                            'children' => function ($subQuery) {
                                                $subQuery
                                                    ->where('status', true)
                                                    ->orderBy('order')
                                                    ->with([
                                                        'children' => function ($subSubQuery) {
                                                            $subSubQuery->where('status', true)->orderBy('order');
                                                        },
                                                    ]);
                                            },
                                        ]);
                                },
                            ])
                            ->orderBy('order')
                            ->get()
                        : collect();
                @endphp
    
                @if ($sidebarMenuItems->count() > 0)
                    <ul class="sidebar-menu-list">
                        @foreach ($sidebarMenuItems as $item)
                            @include('frontend.partials.sidebar-menu-item', ['item' => $item, 'level' => 1])
                        @endforeach
                    </ul>
                @else
                    <p class="no-menu-items">No menu items available</p>
                @endif
            </div>
        </div>
        <!-- Desktop Sidebar Menu JavaScript (Vanilla JS) -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebarMenu');
                const overlay = document.getElementById('sidebarOverlay');
                const toggle = document.getElementById('sidebarMenuToggle');
                const closeBtn = document.getElementById('sidebarClose');
    
                // Open sidebar
                if (toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        openSidebar();
                    });
                }
    
                // Close sidebar
                if (closeBtn) {
                    closeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        closeSidebar();
                    });
                }
    
                // Close sidebar when clicking overlay
                if (overlay) {
                    overlay.addEventListener('click', function() {
                        closeSidebar();
                    });
                }
    
                // Close sidebar with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                        closeSidebar();
                    }
                });
    
                function openSidebar() {
                    if (sidebar) sidebar.classList.add('active');
                    if (overlay) overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
    
                function closeSidebar() {
                    if (sidebar) sidebar.classList.remove('active');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = '';
    
                    // Collapse all expanded submenus
                    setTimeout(function() {
                        const submenus = document.querySelectorAll('.sidebar-submenu');
                        const expandedLinks = document.querySelectorAll('.sidebar-menu-link.has-children');
    
                        submenus.forEach(submenu => submenu.classList.remove('expanded'));
                        expandedLinks.forEach(link => link.classList.remove('expanded'));
                    }, 300);
                }
    
                // Handle menu item clicks with children (expand/collapse)
                document.addEventListener('click', function(e) {
                    const link = e.target.closest('.sidebar-menu-link.has-children');
                    if (link) {
                        e.preventDefault();
    
                        const itemId = link.getAttribute('data-item-id');
                        const submenu = link.parentNode.querySelector('.sidebar-submenu[data-parent="' +
                            itemId + '"]');
    
                        if (submenu) {
                            // Toggle the submenu
                            if (submenu.classList.contains('expanded')) {
                                // Collapse this submenu and all its nested submenus
                                submenu.classList.remove('expanded');
                                link.classList.remove('expanded');
    
                                // Collapse all nested submenus
                                const nestedSubmenus = submenu.querySelectorAll('.sidebar-submenu');
                                const nestedLinks = submenu.querySelectorAll('.sidebar-menu-link.has-children');
    
                                nestedSubmenus.forEach(nested => nested.classList.remove('expanded'));
                                nestedLinks.forEach(nested => nested.classList.remove('expanded'));
                            } else {
                                // Expand this submenu
                                submenu.classList.add('expanded');
                                link.classList.add('expanded');
                            }
                        }
                    }
                });
    
                // Handle regular menu item clicks (without children)
                document.addEventListener('click', function(e) {
                    const link = e.target.closest('.sidebar-menu-link:not(.has-children)');
                    if (link) {
                        const href = link.getAttribute('href');
                        const target = link.getAttribute('target') || '_self';
    
                        // Close sidebar before navigation
                        closeSidebar();
    
                        // Navigate after a short delay to allow sidebar to close
                        if (href && href !== '#' && href !== 'javascript:void(0)') {
                            setTimeout(function() {
                                if (target === '_blank') {
                                    window.open(href, '_blank');
                                } else {
                                    window.location.href = href;
                                }
                            }, 100);
                        }
                    }
                });
    
                // Prevent body scroll when sidebar is open
                if (sidebar) {
                    sidebar.addEventListener('touchmove', function(e) {
                        e.stopPropagation();
                    });
                }
    
                if (overlay) {
                    overlay.addEventListener('touchmove', function(e) {
                        e.preventDefault();
                    });
                }
            });
        </script>
        <!-- End Desktop Header v3 -->
    @elseif ($headerLayout == 'v4')
        <!-- Start Desktop Header v4-->
        @php
            $total = 0;
            $carts = collect();
            $cartCount = 0;

            if (auth()->check()) {
                $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
            } else {
                $guestId = request()->cookie('guest_id');
                if ($guestId) {
                    $carts = \App\Models\Cart::where('guest_id', $guestId)->get();
                }
            }

            if (!$carts->isEmpty()) {
                $cartCount = $carts->count();
                foreach ($carts as $cart) {
                    $total += $cart->price * $cart->qunt;
                }
            }
        @endphp
        <style>
            .main-header-section {
                background-color: #ffffff !important;
                border-bottom: 1px solid #f1f5f9;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
                position: sticky;
                top: 0;
                z-index: 100;
            }
            .header {
                width: 100%;
                max-width: var(--container-max-width, 1340px);
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 15px;
            }

            .logo {
                text-align: left;
            }

            .logo img {
                width: {{ setting('general', 'logo_width', '140px') }}px;
                height: auto;
                transition: transform 0.25s ease;
            }

            .logo img:hover {
                transform: scale(1.02);
            }

            .search-bar {
                display: flex;
                align-items: center;
                border: 2px solid #e2e8f0;
                background: #f8fafc;
                border-radius: 50px;
                padding: 0;
                width: 100%;
                max-width: 380px;
                box-shadow: none;
                position: relative;
                margin: 0 30px;
                transition: all 0.2s ease;
            }
            
            .search-bar:focus-within {
                border-color: var(--primary-color);
                background: #fff;
                box-shadow: 0 0 0 4px rgba(255, 105, 37, 0.15);
            }

            .search-bar input {
                border: none;
                outline: none;
                width: 100%;
                font-size: 14px;
                padding: 12px 20px;
                border-radius: 50px 0 0 50px;
                background: transparent;
                color: #334155;
            }

            .search-bar .search-btn {
                background: none;
                border: none;
                outline: none;
                margin-right: 2px;
                border-radius: 50%;
                width: 38px;
                height: 38px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--primary-color);
                cursor: pointer;
                transition: background 0.2s;
            }

            .search-bar .search-btn svg {
                display: block;
            }

            .search-bar .clear-search-icon {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                cursor: pointer;
                display: none;
                font-size: 14px;
            }

            .search-bar .clear-search-icon.visible {
                display: block;
            }

            .search-results-container {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--body-bg);
                border-radius: 0 0 8px 8px;
                box-shadow: var(--shadow-md);
                z-index: 3000;
                max-height: 400px;
                overflow-y: auto;
                margin-top: 7px;
                display: none;
            }

            .search-result-item {
                display: flex;
                padding: 10px 15px;
                border-bottom: 1px solid var(--border-color);
                transition: background-color 0.2s;
            }

            .search-result-item:hover {
                background-color: var(--light-color);
            }

            .search-result-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 15px;
            }

            .search-result-info {
                flex: 1;
            }

            .search-result-title {
                font-weight: 500;
                margin-bottom: 5px;
            }

            .search-result-price {
                color: var(--primary-color);
                font-weight: 500;
            }

            .search-result-category {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .search-no-results {
                padding: 15px;
                text-align: center;
                color: var(--text-light);
            }

            .search-all-results {
                display: block;
                text-align: center;
                padding: 10px;
                background-color: #f7fafc;
                color: #4a5568;
                font-weight: 500;
                border-top: 1px solid #e2e8f0;
            }

            .search-all-results:hover {
                background-color: #edf2f7;
            }

            h3.search-section-title {
                font-size: 16px;
                padding: 15px;
            }

            .nav-icons {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                gap: 18px;
            }

            .nav-icons a {
                color: var(--text-color);
                text-decoration: none;
                position: relative;
                transition: color 0.3s;
            }

            .wishlist-icon {
                display: flex;
                align-items: center;
            }


            .nav-icons a:hover {
                color: var(--primary-color);
            }
            .nav-icons .cart-count {
                position: absolute;
                top: -2px;
                right: 5px;
                background-color: var(--accent-color, var(--primary-color));
                color: white;
                border-radius: 50%;
                width: 12px;
                height: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: bold;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .nav-icons i {
                font-size: 20px;
            }

            .profile-icon-area {
                display: none;
            }

            .register-btn {
                background: var(--accent-color);
                color: #fff !important;
                padding: 10px 22px;
                border-radius: 30px;
                display: inline-flex;
                align-items: center;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
            }

            @media(max-width:768px) {

                .logo img {
                    width: {{ setting('general', 'mobile_logo_width', '140px') }}px;
                    margin-bottom: -5px;
                }

                .profile-icon-area {
                    display: block;
                }

                .search-bar {
                    display: none;
                }

                .header {
                    grid-template-columns: 1fr auto;
                    justify-content: space-between;
                }

                .mainmenulogin {
                    display: none;
                }

                .register-btn {
                    display: none;
                }
            }

            @media(max-width:1200px) {
                .track-text, .call-text {
                    display: none !important;
                }
                .track-box, .call-box {
                    padding: 8px;
                    border-radius: 50%;
                    background: rgba(30, 41, 59, 0.04);
                }
            }

            @media(max-width:576) {
                .nav-icons a {
                    margin-left: 15px;
                }
            }
        </style>
        <section id="mainHeaderSection" class="main-header-section">
            <header class="base-container header">

                <a href="{{ route('index') }}" class="logo">
                    @if (setting('general', 'logo'))
                        <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="Thikana Logo">
                    @else
                        <img src="{{ asset('new/thikana.png') }}" alt="Thikana Logo" style="140px">
                    @endif
                </a>

                <div class="category-trigger-wrapper">
                    <a href="#" class="category-trigger-btn" id="categoryTriggerBtn">
                        <i class="fas fa-list"></i>
                        <span>Category</span>
                        <svg class="toggle-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 5px; transition: transform 0.2s ease;">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                </div>
    
                <div class="search-bar">
                    <input type="text" id="header-search-input"
                        placeholder="Search Your Product Here...">
                    <button type="button" class="search-btn" style="background: #1e293b;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10"
                                fill="#1e293b" />
                            <path
                                d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                                fill="#ffffff" />
                        </svg>
                    </button>
                    <div id="search-results" class="search-results-container"></div>
                </div>
    
                <div class="nav-icons">
                    <style>
                        .track-box {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            font-family: 'Inter', sans-serif;
                            text-decoration: none;
                            transition: all 0.2s ease;
                        }
                        .track-box:hover {
                            opacity: 0.85;
                            transform: translateY(-1px);
                        }
                        
                        .track-icon {
                            width: 22px;
                            height: 22px;
                            flex-shrink: 0;
                        }
                        
                        .track-text span {
                            display: block;
                            font-size: 11px;
                            color: #64748b;
                            font-weight: 500;
                        }
                        
                        .track-text strong {
                            display: block;
                            font-size: 13px;
                            font-weight: 700;
                            color: #1e293b;
                        }
                        
                        .web-primary-color {
                            fill: #1e293b;
                        }
                        .web-primary-stroke {
                            stroke: #1e293b;
                        }
                        </style>
                        <a href="{{ route('order.track') }}" class="track-box">
                            <svg class="track-icon web-primary-color" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5 0.67 1.5 1.5-.67 1.5-1.5 1.5zm12 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5 0.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H17V9h2.5l2 3.5H19.5z"/>
                            </svg>
                            <div class="track-text">
                                <span>Shipping</span>
                                <strong>Track Order</strong>
                            </div>
                        </a>

                    <style>
                    .call-box {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        font-family: 'Inter', sans-serif;
                        text-decoration: none;
                        transition: all 0.2s ease;
                    }
                    .call-box:hover {
                        opacity: 0.85;
                        transform: translateY(-1px);
                    }
                    
                    .call-icon {
                        width: 20px;
                        height: 20px;
                        flex-shrink: 0;
                    }
                    
                    .call-text span {
                        display: block;
                        font-size: 11px;
                        color: #64748b;
                        font-weight: 500;
                    }
                    
                    .call-text strong {
                        display: block;
                        font-size: 13px;
                        font-weight: 700;
                        color: #1e293b;
                    }
                    @media (max-width: 992px) {
                      .track-box,
                      .call-box {
                        display: none !important;
                      }
                    }
                    </style>
                    <a href="tel:{{ setting('general', 'phone_number', '01821772211') }}" class="call-box">
                        <svg class="call-icon web-primary-color" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1c-9.39 0-17-7.61-17-17a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <div class="call-text">
                            <span>Call Us Now</span>
                            <strong>{{ setting('general', 'phone_number', '01821772211') }}</strong>
                        </div>
                    </a>
                    
                    <a href="#" title="Wishlist" style="transition: all 0.2s ease;">
                        <div class="wishlist-icon">
                            <svg class="web-primary-stroke" width="22" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                    </a>
    
                    <a href="{{ route('cart.index') }}" title="Shopping Cart" class="cart-drawer-trigger" style="transition: all 0.2s ease;">
                        <div class="cart-icon" style="position: relative;">
                            <svg class="web-primary-stroke" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            <span class="cart-count" style="position: absolute; top: -6px; right: -8px; background-color: #1e293b; color: white; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; border: 2px solid #fff;">{{ $cartCount }}</span>
                        </div>
                    </a>
                    
                    <a href="{{ route('account.show') }}" title="My Account" class="profile-icon-area" style="transition: all 0.2s ease;">
                        <div class="profile-icon">
                            <svg class="web-primary-stroke" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </div>
                    </a>
                    @if (auth()->check())
                        <a href="{{ route('account.show') }}" class="register-btn" style="background: #1e293b; border-radius: 50px; font-size: 13px; font-weight: 600; padding: 10px 20px; transition: all 0.2s ease;">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" style="margin-right:6px;" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            Account
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mainmenulogin" style="margin: 0 10px; font-size: 14px; font-weight: 600; color: #475569; transition: all 0.2s;">Log In</a>
                        <a href="{{ route('register') }}" class="register-btn" style="background: #1e293b; border-radius: 50px; font-size: 13px; font-weight: 600; padding: 10px 20px; transition: all 0.2s ease;">
                            <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" style="margin-right:6px;" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            Register
                        </a>
                    @endif
                    <div class="mobile-menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
                            <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
    
            </header>
        </section>
        <style>
            /* Hide mobile search bar on desktop */
            .search-bar-mobile {
                display: none;
            }

            @media (max-width: 768px) {
                .search-bar-mobile {
                    display: flex;
                    align-items: center;
                    position: relative;
                    padding: 5px;
                    background: #fff;
                    z-index: 100;
                    transition: all 0.3s ease;
                }

                .search-bar-mobile.fixed-top {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }

                .search-bar-mobile input[type="text"] {
                    flex: 1;
                    padding: 10px 12px;
                    border: 1px solid #ddd;
                    /* border-radius: 5px; */
                    outline: none;
                    line-height: 18px;
                }

                .search-bar-mobile .search-btn {
                    background: var(--primary-color);
                    border: none;
                    padding: 9px 10px;
                    /* border-radius: 5px; */
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                }

                .search-results-container {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: #fff;
                    border: 1px solid #eee;
                    border-top: none;
                    max-height: 300px;
                    overflow-y: auto;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                    z-index: 101;
                }
            }
	        </style>
	        <div class="search-bar-mobile">
	            <input type="text" id="mobile-search-input-1"
	                placeholder="Search Your Product Here...">
            <button type="button" class="search-btn">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10"
                        fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
                    <path
                        d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
                        fill="#fff" />
                </svg>
            </button>
	            <div id="mobile-search-results-1" class="search-results-container"></div>
	        </div>
	        {{-- End Desktop Header v4 --}}
	    @elseif ($headerLayout == 'v5')
	        <!-- Start Desktop Header v5 -->
	        @php
	            $total = 0;
	            $carts = collect();
	            $cartCount = 0;

	            if (auth()->check()) {
	                $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
	            } else {
	                $guestId = request()->cookie('guest_id');
	                if ($guestId) {
	                    $carts = \App\Models\Cart::where('guest_id', $guestId)->get();
	                }
	            }

	            if (!$carts->isEmpty()) {
	                $cartCount = $carts->count();
	                foreach ($carts as $cart) {
	                    $total += $cart->calculated_subtotal ?? ($cart->price * $cart->qunt);
	                }
	            }

	            $showMainNavigationV5 = setting('general', 'main_navigation_show', '1');

	            $headerMenuV5 = \App\Models\Menu::where('location', 'header')->where('status', true)->first();
	            if (!$headerMenuV5) {
	                $headerMenuV5 = \App\Models\Menu::where('status', true)->first();
	            }

	            $menuItemsV5 = $headerMenuV5
	                ? $headerMenuV5
	                    ->allMenuItems()
	                    ->whereNull('parent_id')
	                    ->where('status', true)
	                    ->with([
	                        'children' => function ($query) {
	                            $query
	                                ->where('status', true)
	                                ->orderBy('order')
	                                ->with([
	                                    'children' => function ($subQuery) {
	                                        $subQuery
	                                            ->where('status', true)
	                                            ->orderBy('order')
	                                            ->with([
	                                                'children' => function ($subSubQuery) {
	                                                    $subSubQuery->where('status', true)->orderBy('order');
	                                                },
	                                            ]);
	                                    },
	                                ]);
	                        },
	                    ])
	                    ->orderBy('order')
	                    ->get()
	                : collect();
	        @endphp
	        <style>
	            .main-header-section.header-v5-section {
	                background: {{ setting('general', 'main_nav_background_color', '#ff6925') }};
	                color: {{ setting('general', 'main_nav_text_color', '#fff') }};
	                border-bottom: 3px solid var(--border-color);
	            }

	            .header-v5 {
	                width: 100%;
	                max-width: var(--container-max-width, 1340px);
	                margin: 0 auto;
	                display: flex;
	                align-items: center;
	                justify-content: space-between;
	                padding: 8px 15px;
	                gap: 16px;
	            }

	            .header-v5 .logo {
	                flex-shrink: 0;
	                text-align: left;
	            }

	            .header-v5 .logo img {
	                width: {{ setting('general', 'logo_width', '140px') }}px;
	                height: auto;
	                transition: transform 0.3s ease;
	            }

	            .header-v5 .logo img:hover {
	                transform: scale(1.05);
	            }

	            .header-v5-right {
	                display: flex;
	                align-items: center;
	                justify-content: space-between;
	                gap: 14px;
	                flex: 1;
	                min-width: 0;
	            }

	            .navigation.header-v5-navigation {
	                justify-content: flex-end;
	                flex-wrap: nowrap;
	                background: transparent;
	            }

	            .navigation.header-v5-navigation a {
	                color: {{ setting('general', 'main_nav_text_color', '#fff') }};
	                padding: {{ setting('general', 'main_nav_padding', '14px 18px') }};
	                font-size: {{ setting('general', 'main_nav_font_size', '15px') }};
	                border-right: 1px solid {{ setting('general', 'main_nav_border_right_color', '#ffffffb5') }};
	            }

	            .navigation.header-v5-navigation a:hover {
	                color: {{ setting('general', 'main_nav_hover_text_color', 'var(--primary-color)') }};
	                background: {{ setting('general', 'main_nav_hover_bg_color', 'rgba(255,255,255,0.08)') }};
	                transform: none;
	            }

	            .navigation.header-v5-navigation a.active {
	                color: {{ setting('general', 'main_nav_hover_text_color', 'var(--primary-color)') }};
	                background: rgba(255, 255, 255, 0.08);
	            }

	            /* Override white color for dropdown links inside header-v5 navigation */
	            .navigation.header-v5-navigation .dropdown-menu a {
	                color: var(--dropdown-text-color, #333333) !important;
	                background: transparent !important;
	                border-right: none !important;
	                padding: var(--dropdown-padding, 10px 15px) !important;
	                font-size: var(--dropdown-font-size, 14px) !important;
	            }

	            .navigation.header-v5-navigation .dropdown-menu a:hover {
	                color: var(--dropdown-hover-text-color, var(--primary-color)) !important;
	                background: var(--dropdown-hover-bg-color, #f8f9fa) !important;
	            }

	            .header-v5-section .search-bar {
	                display: flex;
	                align-items: center;
	                border: 1.5px solid rgba(255, 255, 255, 0.6);
	                background: #fff;
	                border-radius: 22px;
	                padding: 0;
	                width: 320px;
	                min-width: 280px;
	                box-shadow: none;
	                position: relative;
	                margin: 0;
	                flex-shrink: 1;
	            }

	            .header-v5-section .search-bar input {
	                border: none;
	                outline: none;
	                width: 100%;
	                font-size: 14px;
	                padding: 10px 14px;
                    border: 1px solid #e3e3e3ff;
	                border-radius: 22px 22px 22px 22px;
	                background: transparent;
	                color: #444;
	            }

	            .header-v5-section .search-bar .search-btn {
	                background: none;
	                border: none;
	                outline: none;
	                margin-right: 0;
	                margin-left: -36px;
	                border-radius: 50%;
	                width: 32px;
	                height: 32px;
	                display: flex;
	                align-items: center;
	                justify-content: center;
	                background: var(--primary-color);
	                cursor: pointer;
	                transition: background 0.2s;
	            }

	            .header-v5-section .search-results-container {
	                position: absolute;
	                top: 100%;
	                left: 0;
	                width: 100%;
	                background-color: var(--body-bg);
	                border-radius: 0 0 8px 8px;
	                box-shadow: var(--shadow-md);
	                z-index: 3000;
	                max-height: 400px;
	                overflow-y: auto;
	                margin-top: 7px;
	                display: none;
	            }

	            .header-v5-section .search-result-item {
	                display: flex;
	                padding: 10px 15px;
	                border-bottom: 1px solid var(--border-color);
	                transition: background-color 0.2s;
	            }

	            .header-v5-section .search-result-item:hover {
	                background-color: var(--light-color);
	            }

	            .header-v5-section .search-result-image {
	                width: 50px;
	                height: 50px;
	                object-fit: cover;
	                margin-right: 15px;
	            }

	            .header-v5-section .search-result-info {
	                flex: 1;
	            }

	            .header-v5-section .search-result-title {
	                font-weight: 500;
	                margin-bottom: 5px;
	            }

	            .header-v5-section .search-result-price {
	                color: var(--primary-color);
	                font-weight: 500;
	            }

	            .header-v5-section .search-result-category {
	                font-size: 0.8rem;
	                color: var(--text-light);
	            }

	            .header-v5-section .search-no-results {
	                padding: 15px;
	                text-align: center;
	                color: var(--text-light);
	            }

	            .header-v5-section h3.search-section-title {
	                font-size: 16px;
	                padding: 15px;
	            }

	            .header-v5-section .nav-icons {
	                display: flex;
	                justify-content: flex-end;
	                align-items: center;
	                gap: 14px;
	            }

	            .header-v5-section .nav-icons a {
	                color: {{ setting('general', 'main_nav_text_color', '#fff') }};
	                text-decoration: none;
	                position: relative;
	                transition: color 0.3s;
	            }

	            .header-v5-section .nav-icons a:hover {
	                color: {{ setting('general', 'main_nav_hover_text_color', '#fff') }};
	            }

	            /* Header icons color for header-v5 */
	            .header-v5-section .nav-icons .web-primary-color {
	                fill: var(--header-icons-color, #fff);
	            }

	            .header-v5-section .nav-icons .cart-count {
	                position: absolute;
	                top: -2px;
	                right: 5px;
	                background-color: var(--accent-color, var(--primary-color));
	                color: white;
	                border-radius: 50%;
	                width: 12px;
	                height: 12px;
	                display: flex;
	                align-items: center;
	                justify-content: center;
	                font-size: 10px;
	                font-weight: bold;
	                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	            }

	            @media (max-width: 992px) {
	                .header-v5 {
	                    width: 100%;
	                    padding: 8px 12px;
	                }

	                .navigation.header-v5-navigation {
	                    display: none;
	                }

	                .header-v5-section .search-bar {
	                    display: none;
	                }

	                .header-v5-section .mobile-menu-toggle {
	                    display: block;
	                    font-size: 24px;
	                    color: var(--header-icons-color, #fff);
	                    cursor: pointer;
	                    z-index: 1000;
	                    padding: 0 4px;
	                    margin-left: 5px;
	                }
	                .header-v5-section .mobile-menu-toggle svg path {
	                    stroke: var(--header-icons-color, #fff);
	                }
	            }

	            @media (max-width: 768px) {
	                .header-v5 .logo img {
	                    width: {{ setting('general', 'mobile_logo_width', '140px') }}px;
	                }
	            }
	        </style>
	        <section id="mainHeaderSection" class="main-header-section header-v5-section">
	            <header class="base-container header-v5">
	                <a href="{{ route('index') }}" class="logo">
	                    @if (setting('general', 'logo'))
	                        <img src="{{ \App\Services\SettingsService::getLogo() }}"
	                            alt="{{ setting('general', 'site_name', 'Shop') }}">
	                    @else
	                        <img src="{{ asset('new/thikana.png') }}"
	                            alt="{{ setting('general', 'site_name', 'Shop') }}" style="width: 140px">
	                    @endif
	                </a>

	                <div class="header-v5-right">
	                    <div class="category-trigger-wrapper">
	                        <button id="categoryTriggerBtn" class="category-trigger-btn" type="button">
	                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
	                                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
	                            </svg>
	                            Category
	                            <svg class="toggle-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
	                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
	                            </svg>
	                        </button>
	                    </div>

	                    @if ($showMainNavigationV5 == '1')

	                        <nav class="navigation header-v5-navigation" role="navigation" aria-label="Header navigation">
	                            @foreach ($menuItemsV5 as $item)
	                                @include('frontend.partials.menu-item', ['item' => $item, 'level' => 1])
	                            @endforeach
	                        </nav>
	                    @endif

	                    <div class="nav-icons">
	                        <div class="search-bar">
	                            <input type="text" id="header-search-input"
	                                placeholder="Search Your Product Here...">
	                            <button type="button" class="search-btn" aria-label="Search">
	                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
	                                    <circle cx="12" cy="12" r="10"
	                                        fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
	                                    <path
	                                        d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
	                                        fill="#ffffff" />
	                                </svg>
	                            </button>
	                            <div id="search-results" class="search-results-container"></div>
	                        </div>

	                        <a href="#" title="Wishlist">
	                            <div class="wishlist-icon">
	                                <svg class="web-primary-color" width="22" height="18" viewBox="0 0 22 18"
	                                    fill="none" xmlns="http://www.w3.org/2000/svg">
	                                    <path fill-rule="evenodd" clip-rule="evenodd"
	                                        d="M15.6875 0C13.7516 0 12.0566 0.8325 11 2.23969C9.94344 0.8325 8.24844 0 6.3125 0C3.10384 0.00361655 0.503617 2.60384 0.5 5.8125C0.5 12.375 10.2303 17.6869 10.6447 17.9062C10.8665 18.0256 11.1335 18.0256 11.3553 17.9062C11.7697 17.6869 21.5 12.375 21.5 5.8125C21.4964 2.60384 18.8962 0.00361655 15.6875 0V0ZM11 16.3875C9.28813 15.39 2 10.8459 2 5.8125C2.0031 3.43206 3.93206 1.5031 6.3125 1.5C8.13594 1.5 9.66687 2.47125 10.3062 4.03125C10.4218 4.31259 10.6959 4.49627 11 4.49627C11.3041 4.49627 11.5782 4.31259 11.6938 4.03125C12.3331 2.46844 13.8641 1.5 15.6875 1.5C18.0679 1.5031 19.9969 3.43206 20 5.8125C20 10.8384 12.71 15.3891 11 16.3875V16.3875Z" />
	                                </svg>
	                            </div>
	                        </a>

	                        <a href="{{ route('cart.index') }}" title="Shopping Cart" class="cart-drawer-trigger">
	                            <div class="cart-icon">
	                                <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21"
	                                    fill="none" xmlns="http://www.w3.org/2000/svg">
	                                    <path fill-rule="evenodd" clip-rule="evenodd"
	                                        d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" />
	                                </svg>
	                            </div>
	                            <span class="cart-count">{{ $cartCount }}</span>
	                        </a>

	                        <a href="{{ route('account.show') }}" title="My Account">
	                            <div class="profile-icon">
	                                <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21"
	                                    fill="none" xmlns="http://www.w3.org/2000/svg">
	                                    <path fill-rule="evenodd" clip-rule="evenodd"
	                                        d="M20.2313 18.375C18.8319 15.9269 16.6716 14.1685 14.1455 13.3374C16.7168 11.8055 17.9799 8.74371 17.1836 5.86726C16.3874 2.99081 13.7903 0.997722 10.5 0.997722C7.20975 0.997722 4.61261 2.99081 3.81637 5.86726C3.02013 8.74371 4.28324 11.8055 6.85453 13.3374C4.32844 14.1675 2.16812 15.9259 0.76875 18.375C0.619540 18.6129 0.614176 18.9107 0.751669 19.1533C0.889162 19.3959 1.14893 19.5453 1.42689 19.5429C1.70486 19.5404 1.96256 19.3861 2.09718 19.1406C3.88774 16.0513 7.06471 14.2031 10.5 14.2031C13.9353 14.2031 17.1123 16.0513 18.9028 19.1406C19.0374 19.3861 19.2951 19.5404 19.5731 19.5429C19.8511 19.5453 20.1108 19.3959 20.2483 19.1533C20.3858 18.9107 20.3805 18.6129 20.2313 18.375V18.375ZM4.9875 7.4531C4.9875 4.60539 7.35229 2.2406 10.5 2.2406C13.6477 2.2406 16.0125 4.60539 16.0125 7.4531C16.0125 10.3008 13.6477 12.6656 10.5 12.6656C7.35356 12.6625 4.99044 10.2994 4.9875 7.4531V7.4531Z" />
	                                </svg>
	                            </div>
	                        </a>

	                        <div class="mobile-menu-toggle">
	                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
	                                xmlns="http://www.w3.org/2000/svg" aria-label="Toggle menu">
	                                <path d="M3 6H21M3 12H21M3 18H21" stroke="currentColor"
	                                    stroke-width="2" stroke-linecap="round"
	                                    stroke-linejoin="round" />
	                            </svg>
	                        </div>
	                    </div>
	                </div>
	            </header>
	        </section>

	        <style>
	            /* Hide mobile search bar on desktop */
	            .search-bar-mobile {
	                display: none;
	            }

	            @media (max-width: 768px) {
	                .search-bar-mobile {
	                    display: flex;
	                    align-items: center;
	                    position: relative;
	                    padding: 5px;
	                    background: #fff;
	                    z-index: 100;
	                    transition: all 0.3s ease;
	                }

	                .search-bar-mobile.fixed-top {
	                    position: fixed;
	                    top: 0;
	                    left: 0;
	                    right: 0;
	                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
	                }

	                .search-bar-mobile input[type="text"] {
	                    flex: 1;
	                    padding: 10px 12px;
	                    border: 1px solid #ddd;
	                    outline: none;
	                    line-height: 18px;
	                }

	                .search-bar-mobile .search-btn {
	                    background: var(--primary-color);
	                    border: none;
	                    padding: 9px 10px;
	                    cursor: pointer;
	                    display: flex;
	                    align-items: center;
	                }

	                .search-results-container {
	                    position: absolute;
	                    top: 100%;
	                    left: 0;
	                    right: 0;
	                    background: #fff;
	                    border: 1px solid #eee;
	                    border-top: none;
	                    max-height: 300px;
	                    overflow-y: auto;
	                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
	                    z-index: 101;
	                }
	            }
	        </style>
	        <div class="search-bar-mobile">
	            <input type="text" id="mobile-search-input-1"
	                placeholder="Search Your Product Here...">
	            <button type="button" class="search-btn">
	                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
	                    <circle cx="12" cy="12" r="10"
	                        fill="{{ \App\Services\SettingsService::getPrimaryColor() }}" />
	                    <path
	                        d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
	                        fill="#fff" />
	                </svg>
	            </button>
	            <div id="mobile-search-results-1" class="search-results-container"></div>
	        </div>
	        {{-- End Desktop Header v5 --}}
	    @endif

    {{-- Start Enhanced Header Main Navigation  --}}
	    @php
	        $showMainNavigation = setting('general', 'main_navigation_show', '1');
	    @endphp
	    @if ($showMainNavigation == '1' || $headerLayout == 'v5')
	    <style>
	        :root {
	            --nav-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	            --nav-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --nav-shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.12);
            --nav-border-radius: 8px;
            --nav-dropdown-width: 220px;
        }

        /* Main Navigation Container */
        .navigation {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            background: transparent;
            position: relative;
            z-index: 50;
        }
        .mainnav-section {
            background: #1e293b;
            color: #ffffff;
            margin-top: -3px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 1010;
        }

        @media (min-width: 993px) {
            .mainnav-section {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.28s ease, opacity 0.28s ease;
                opacity: 0;
                border-bottom: 3px solid var(--primary-color, #ff6925);
            }
            .mainnav-section.active {
                max-height: 200px;
                opacity: 1;
            }
        }

        .category-trigger-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .category-trigger-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(30, 41, 59, 0.08);
            border: 1.5px solid rgba(30, 41, 59, 0.15);
            padding: 7.5px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b !important;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        /* If header background color is dark, make button contrast in white */
        .header-v5-section .category-trigger-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            color: #ffffff !important;
        }

        .category-trigger-btn:hover,
        .category-trigger-btn.active {
            background: rgba(30, 41, 59, 0.15);
            border-color: rgba(30, 41, 59, 0.3);
            color: #1e293b !important;
        }

        .header-v5-section .category-trigger-btn:hover,
        .header-v5-section .category-trigger-btn.active {
            background: rgba(255, 255, 255, 0.28);
            border-color: rgba(255, 255, 255, 0.9);
            color: #ffffff !important;
        }

        .category-trigger-btn svg {
            flex-shrink: 0;
        }

        .category-trigger-btn .toggle-arrow {
            transition: transform 0.2s ease;
        }

        .category-trigger-btn.active .toggle-arrow {
            transform: rotate(180deg);
        }

        @media (max-width: 992px) {
            .category-trigger-wrapper {
                display: none !important;
            }
        }

        /* Navigation Links */
        .navigation a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 20px;
            text-decoration: none;
            color: {{ setting('general', 'main_nav_text_color', '#fff') }};
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }

        .mainnav-section .navigation a {
            color: #ffffff !important;
        }

        /* Active state */
        .navigation a.active {
            color: #fff !important;
            background: rgba(0, 0, 0, 0.15);
        }

        /* Hover effects */
        .navigation a:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
        }

        /* Bottom border animation */
        .navigation a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: #fff;
            border-radius: 3px 3px 0 0;
            transform: translateX(-50%);
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navigation a:hover::after,
        .navigation a.active::after {
            width: 70%;
        }

        /* Dropdown Container */
        .dropdown-parent {
            position: relative;
            display: inline-block;
        }

        /* Base dropdown menu styles */
        .dropdown-menu {
            position: absolute;
            background: var(--dropdown-bg-color, #ffffff);
            border: 1px solid var(--border-color);
            border-radius: var(--nav-border-radius);
            box-shadow: var(--nav-shadow);
            opacity: 0;
            visibility: hidden;
            transition: var(--nav-transition);
            pointer-events: none;
            min-width: var(--nav-dropdown-width);
            white-space: nowrap;
            overflow: visible;
        }

        /* Level 1 dropdown (main navigation) */
        .navigation>.dropdown-parent>.dropdown-menu {
            top: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }

        /* Level 1 dropdown arrow */
        .navigation>.dropdown-parent>.dropdown-menu::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 12px;
            height: 12px;
            background: var(--body-bg);
            border: 1px solid var(--border-color);
            border-bottom: none;
            border-right: none;
        }

        /* Level 2+ dropdowns (all nested levels) */
        .dropdown-menu .dropdown-parent {
            position: relative;
            display: block;
            width: 100%;
        }

        /* All nested dropdowns always show to the right */
        .dropdown-menu .dropdown-parent>.dropdown-menu {
            position: absolute;
            top: 0;
            /* Align with parent item top */
            left: 100% !important;
            /* Always position to the right */
            right: auto !important;
            /* Override any right positioning */
            transform: none;
            z-index: 1100;
            /* Higher than parent */
            min-width: var(--nav-dropdown-width);
            margin-left: 0;
            margin-right: 0;
        }

        /* Force all nested levels to show right regardless of depth */
        .dropdown-menu .dropdown-menu {
            position: absolute;
            top: 0 !important;
            left: 100% !important;
            right: auto !important;
            transform: none !important;
            margin-left: 0;
            margin-right: 0;
            z-index: 1200;
        }

        .dropdown-menu .dropdown-menu .dropdown-menu {
            z-index: 1300;
            left: 100% !important;
            right: auto !important;
        }

        .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-menu {
            z-index: 1400;
            left: 100% !important;
            right: auto !important;
        }

        .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-menu {
            z-index: 1500;
            left: 100% !important;
            right: auto !important;
        }

        /* Nested dropdown arrow - always points from left */
        .dropdown-menu .dropdown-parent>.dropdown-menu::before {
            content: '';
            position: absolute;
            top: 15px;
            left: -6px !important;
            right: auto !important;
            transform: rotate(45deg);
            width: 12px;
            height: 12px;
            background: var(--body-bg);
            border: 1px solid var(--border-color);
            border-top: none;
            border-right: none;
        }
        /* Ensure arrows for all nested levels point from left */
        .dropdown-menu .dropdown-menu::before,
        .dropdown-menu .dropdown-menu .dropdown-menu::before,
        .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-menu::before {
            left: -6px !important;
            right: auto !important;
            border-top: none !important;
            border-right: none !important;
            border-bottom: 1px solid var(--border-color) !important;
            border-left: 1px solid var(--border-color) !important;
        }

        /* Show dropdown on hover - all levels with better timing */
        .dropdown-parent:hover>.dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transition-delay: 0ms;
        }

        /* Keep dropdown open when hovering over submenu */
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Extended hover area to prevent disappearing */
        .navigation>.dropdown-parent::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 15px;
            background: transparent;
            z-index: 999;
            pointer-events: auto;
        }

        /* For nested dropdowns, create horizontal bridge area */
        .dropdown-menu .dropdown-parent::after {
            content: '';
            position: absolute;
            top: 0;
            left: 100%;
            width: 5px;
            height: 100%;
            background: transparent;
            z-index: 1050;
            pointer-events: auto;
        }

        /* Enhanced hover states for all levels */
        .dropdown-parent:hover>.dropdown-menu,
        .dropdown-menu:hover {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Specific hover states for nested levels */
        .dropdown-menu .dropdown-parent:hover>.dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }

        /* Dropdown links */
        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: var(--dropdown-padding, 14px 18px);
            margin: 0;
            color: var(--dropdown-text-color, #333333) !important;
            font-size: var(--dropdown-font-size, 14px) !important;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0.5px;
            border-radius: 0;
            transition: var(--nav-transition);
            border-bottom: 1px solid var(--border-color);
            position: relative;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
            text-decoration: none !important;
            background: transparent !important;
        }

        /* Ensure dropdown links in nested menus have proper styling */
        .dropdown-menu .dropdown-menu a {
            padding: 12px 16px;
            font-size: 13px;
        }

        .dropdown-menu .dropdown-menu .dropdown-menu a {
            padding: 10px 14px;
            font-size: 12px;
        }

        /* Level-specific styling for better visual hierarchy */
        .menu-level-1>.dropdown-menu {
            border: 2px solid var(--border-color);
        }

        .menu-level-2>.dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: var(--nav-shadow);
        }

        .menu-level-3>.dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Ensure proper display for all dropdown levels */
        .dropdown-parent {
            position: relative;
            display: block;
        }

        .navigation>.dropdown-parent {
            display: inline-block;
        }

        /* Fix z-index stacking for visual perfection */
        .submenu-level-1 {
            z-index: 1000;
        }

        .submenu-level-2 {
            z-index: 1100;
        }

        .submenu-level-3 {
            z-index: 1200;
        }

        .submenu-level-4 {
            z-index: 1300;
        }

        .submenu-level-5 {
            z-index: 1400;
        }

        /* Ensure submenu arrows are properly positioned */
        .submenu-arrow {
            margin-left: auto;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .dropdown-parent:hover .submenu-arrow {
            opacity: 1;
            transform: translateX(2px);
        }

        .dropdown-menu a:last-child {
            border-bottom: none;
        }

        /* Remove the bottom border animation for dropdown links */
        .dropdown-menu a::after {
            display: none;
        }

        .dropdown-menu a:hover {
            background: var(--dropdown-hover-bg-color, #f8f9fa) !important;
            color: var(--dropdown-hover-text-color, var(--primary-color)) !important;
            transform: none;
            padding-left: 24px;
        }

        .dropdown-menu a.active {
            background: var(--dropdown-hover-bg-color, rgba(var(--primary-color-rgb), 0.1)) !important;
            color: var(--dropdown-hover-text-color, var(--primary-color)) !important;
        }



        /* Dropdown icon styling for main navigation */
        .dropdown-icon {
            width: 12px;
            height: 12px;
            margin-left: 8px;
            opacity: 0.7;
            transition: transform 0.3s ease, opacity 0.3s ease;
            flex-shrink: 0;
        }

        .navigation>.dropdown-parent:hover .dropdown-icon {
            transform: rotate(180deg);
            opacity: 1;
        }
        /* Submenu arrow for nested dropdowns */
        .submenu-arrow {
            width: 10px;
            height: 10px;
            margin-left: auto;
            opacity: 0.6;
            transition: transform 0.3s ease, opacity 0.3s ease;
            flex-shrink: 0;
        }

        /* Icons */
        .navigation i:not(.dropdown-icon) {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .dropdown-menu i:not(.submenu-arrow) {
            font-size: 14px;
            width: 18px;
            text-align: center;
            opacity: 0.7;
            flex-shrink: 0;
        }

        /* Mobile Navigation */
        @media (max-width: 992px) {
            .navigation {
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
                padding: 12px 16px;
                gap: 8px;
            }

            .navigation::-webkit-scrollbar {
                display: none;
            }

            .navigation a {
                padding: 12px 20px;
                font-size: 16px;
                min-width: max-content;
            }

            /* Mobile dropdown positioning */
            .navigation>.dropdown-parent>.dropdown-menu {
                position: fixed;
                top: auto;
                bottom: 100%;
                left: 16px;
                right: 16px;
                transform: none;
                min-width: auto;
                max-height: 60vh;
                overflow-y: auto;
            }

            .navigation>.dropdown-parent>.dropdown-menu::before {
                display: none;
            }

            /* Nested dropdowns on mobile still appear to the right */
            .dropdown-menu .dropdown-parent>.dropdown-menu {
                position: absolute;
                top: 0;
                left: 100%;
                right: auto;
                bottom: auto;
                max-height: none;
                overflow-y: visible;
            }
        }

        @media (max-width: 768px) {
            .navigation {
                padding: 8px 12px;
            }

            .navigation a {
                padding: 10px 16px;
                font-size: 11px;
            }

            .navigation>.dropdown-parent>.dropdown-menu {
                left: 12px;
                right: 12px;
            }
        }

        /* Ensure body can scroll horizontally if needed for wide menus */
        body {
            overflow-x: hidden;
        }

        /* Allow dropdowns to extend beyond viewport if needed */
        .dropdown-menu {
            white-space: nowrap;
            overflow: visible;
        }

        /* For very wide nested menus, allow horizontal scrolling */
        @media (max-width: 1200px) {
            .dropdown-menu .dropdown-menu .dropdown-menu {
                max-width: calc(100vw - 40px);
                overflow-x: auto;
                white-space: normal;
            }

            .dropdown-menu .dropdown-menu .dropdown-menu a {
                white-space: normal;
                word-wrap: break-word;
            }
        }

        /* Ensure all nested menus always go right, never left */
        .dropdown-menu .dropdown-parent>.dropdown-menu,
        .dropdown-menu .dropdown-menu .dropdown-parent>.dropdown-menu,
        .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-parent>.dropdown-menu {
            left: 100% !important;
            right: auto !important;
            transform: translateX(0) !important;
        }

        /* Focus states for accessibility */
        .navigation a:focus,
        .dropdown-menu a:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {

            .navigation a,
            .dropdown-menu,
            .dropdown-icon,
            .submenu-arrow {
                transition: none;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .navigation {
                border-bottom-width: 2px;
            }

            .dropdown-menu {
                border-width: 2px;
            }
        }

        /* Progressive z-index for nested levels */
        .navigation>.dropdown-parent>.dropdown-menu {
            z-index: 1000;
        }

        .dropdown-menu .dropdown-parent>.dropdown-menu {
            z-index: 1100;
        }

        .dropdown-menu .dropdown-menu .dropdown-parent>.dropdown-menu {
            z-index: 1200;
        }

        .dropdown-menu .dropdown-menu .dropdown-menu .dropdown-parent>.dropdown-menu {
            z-index: 1300;
        }

        /* Smooth transitions without flickering */
        .dropdown-parent {
            transition: none;
        }

        .dropdown-menu {
            transition: opacity 0.15s ease, visibility 0.15s ease;
            transition-delay: 200ms;
        }

        .dropdown-parent:hover>.dropdown-menu {
            transition-delay: 0ms;
        }

        /* Slower hide transition to prevent accidental closing */
        .dropdown-menu:not(:hover) {
            transition-delay: 300ms;
        }
	    </style>
	    @endif
	    @if ($showMainNavigation == '1' && $headerLayout != 'v5')
	    <div class="mainnav-section">
	        <nav class="base-container navigation" role="navigation" aria-label="Main navigation">
	        @php
	            // Get the header menu from the database - try different approaches
	            $headerMenu = \App\Models\Menu::where('location', 'header')->where('status', true)->first();

	            // If no header menu found, try getting the first active menu
	            if (!$headerMenu) {
	                $headerMenu = \App\Models\Menu::where('status', true)->first();
	            }

	            // Get menu items with eager loading of all nested children
	            $menuItems = $headerMenu
	                ? $headerMenu
	                    ->allMenuItems()
	                    ->whereNull('parent_id')
	                    ->where('status', true)
	                    ->with([
	                        'children' => function ($query) {
	                            $query
	                                ->where('status', true)
	                                ->orderBy('order')
	                                ->with([
	                                    'children' => function ($subQuery) {
	                                        $subQuery
	                                            ->where('status', true)
	                                            ->orderBy('order')
	                                            ->with([
	                                                'children' => function ($subSubQuery) {
	                                                    $subSubQuery->where('status', true)->orderBy('order');
	                                                },
	                                            ]);
	                                    },
	                                ]);
	                        },
	                    ])
	                    ->orderBy('order')
	                    ->get()
	                : collect();
	        @endphp

	        @if ($menuItems->count() > 0 || (isset($topCategories) && $topCategories->count() > 0))
	            @foreach ($menuItems as $item)
	                @if (strtolower($item->title) === 'home' || $item->url === '/' || $item->url === url('/'))
	                    @continue
	                @endif
	                @include('frontend.partials.menu-item', ['item' => $item, 'level' => 1])
	            @endforeach

	            @php
	                $topCategories = \App\Models\ProductCategory::where(function($q) {
	                    $q->where('status', '1')->orWhere('status', 'active');
	                })
	                ->withCount('products')
	                ->orderByDesc('products_count')
	                ->take(5)
	                ->get();
	                $menuItemTitles = $menuItems->map(fn($item) => strtolower($item->title))->toArray();
	            @endphp
	            @foreach ($topCategories as $cat)
	                @if (!in_array(strtolower($cat->name), $menuItemTitles))
	                    <a href="{{ url('/shop?category=' . $cat->slug) }}" class="menu-item-level-1">
	                        <span>{{ $cat->name }}</span>
	                    </a>
	                @endif
	            @endforeach
	        @else
	            {{-- Debug: Show if no menu items found --}}
	            <div style="color: red; padding: 10px;">
	                No menu items found. Header Menu: {{ $headerMenu ? 'Found' : 'Not Found' }}
	            </div>
	        @endif
	    </nav>
	    </div>
	    @endif

    <!-- Start Mobile Menu -->
    <style>
        /* Mobile Menu Styles */
        .mobile-menu-toggle {
            display: none;
            font-size: 24px;
            color: var(--secondary-color);
            cursor: pointer;
            z-index: 1000;
            /* border: 1px solid red; */
            padding: 0px 4px;
            margin-left: 5px;
        }

        .mobile-menu-toggle svg {
            width: 27px !important;
            height: 27px !important;
            margin-bottom: -6px;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            height: 100vh;
            background-color: white;
            z-index: 999;
            transition: right 0.3s ease;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .mobile-menu.active {
            right: 0;
            z-index: 2000;
        }

        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .mobile-menu-close {
            font-size: 24px;
            color: var(--secondary-color);
            cursor: pointer;
        }

        /* Mobile Search */
        .mobile-search {
            position: relative;
            width: 100%;
            padding: 5px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 15px;
        }

        .mobile-search input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
            padding-left: 35px;
            padding-right: 35px;
        }

        .mobile-search .search-icon {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
        }

        .mobile-search .clear-search-icon {
            position: absolute;
            right: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
            cursor: pointer;
            display: none;
            /* Hidden by default */
            font-size: 14px;
        }

        .clear-search-icon.visible {
            display: block;
        }

        /* Mobile Search Results */
        #mobile-search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: white;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 2001;
            /* Higher than mobile menu z-index */
            max-height: 400px;
            overflow-y: auto;
        }

        .mobile-menu .search-results-container {
            display: none;
            position: absolute;
            width: 100%;
            background: white;
            z-index: 2001;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 8px 8px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .mobile-menu .search-result-item {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }

        .mobile-menu .search-result-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-right: 10px;
        }
        .mobile-menu h3.search-section-title {
            padding: 10px 15px;
            margin: 0;
            background-color: #f7fafc;
            font-size: 14px;
            font-weight: 600;
        }

        /* Mobile Navigation */
        .mobile-nav {
            padding: 15px 0;
        }

        .mobile-nav a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }

        .mobile-nav a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .mobile-nav a:hover {
            background-color: var(--light-color);
        }

        .mobile-nav a.active {
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        /* Mobile Social Links */
        .mobile-social {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 20px;
            margin-top: auto;
            border-top: 1px solid var(--border-color);
        }

        .mobile-social a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            transition: all 0.3s;
            text-decoration: none;
        }

        .mobile-social a:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
        }

        .overlay.active {
            display: block;
        }

        /* Submenu Styles */
        .has-submenu {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .submenu-arrow {
            margin-left: auto;
            margin-right: 0;
            transition: transform 0.3s;
        }

        .submenu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            height: 100vh;
            background-color: white;
            z-index: 2001;
            transition: right 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .submenu.active {
            right: 0;
        }

        .submenu-header {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--light-color);
        }

        .submenu-header span {
            margin-left: 15px;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .back-btn {
            cursor: pointer;
            color: var(--secondary-color);
            transition: color 0.3s;
        }

        .back-btn:hover {
            color: var(--primary-color);
        }

        .submenu a {
            padding-left: 30px;
        }

        /* Media Queries */
        @media (max-width: 992px) {
            .mobile-menu-toggle {
                display: block;
            }

            .navigation {
                display: none;
            }

            .nav-icons i {
                font-size: 17px;
            }

            .header {
                grid-template-columns: auto 1fr auto;
                padding: 2px 10px;
            }
        }
    </style>
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <div class="logo">
                @if (setting('general', 'logo'))
                    <img src="{{ \App\Services\SettingsService::getLogo() }}"
                        style="width: {{ setting('general', 'mobile_logo_width', '140') }}px">
                @else
                    <img src="{{ asset('new/thikana.png') }}" alt="Thikana Logo"
                        style="width: {{ setting('general', 'mobile_logo_width', '140') }}px">
                @endif
            </div>
            <div class="mobile-menu-close">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </div>
        </div>

        <div class="mobile-search">
            <input type="text" placeholder="Search here..." id="mobile-search-input">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" class="search-icon">
                <path
                    d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
            </svg>

            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="clear-search-icon"
                id="mobile-clear-search">
                <path
                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
            </svg>
            <div id="mobile-search-results" class="search-results-container"></div>
        </div>

        <div class="mobile-nav">
            @php
                $mobileMenu = App\Models\Menu::where('name', 'Mobile Menu')->first();
                $menuItems = $mobileMenu
                    ? $mobileMenu->menuItems()->where('parent_id', null)->where('status', true)->orderBy('order')->get()
                    : collect();
            @endphp

            @foreach ($menuItems as $item)
                @php
                    $hasChildren = $item->children()->where('status', true)->count() > 0;
                @endphp

                <a href="{{ $item->url ?? '#' }}"
                    class="{{ request()->url() == $item->url ? 'active' : '' }} {{ $hasChildren ? 'has-submenu' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                    {{ $item->title }}
                    @if ($hasChildren)
                        <svg class="submenu-arrow" width="16" height="16" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                        </svg>
                    @endif
                </a>

                @if ($hasChildren)
                    <div class="submenu">
                        <div class="submenu-header">
                            <svg class="back-btn" width="16" height="16" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path d="M14 6l-1.41 1.41L7.17 12l5.42 5.59L14 18l-6-6z" />
                            </svg>
                            <span>{{ $item->title }}</span>
                        </div>
                        @foreach ($item->children()->where('status', true)->orderBy('order')->get() as $child)
                            @php
                                $hasGrandchildren = $child->children()->where('status', true)->count() > 0;
                            @endphp

                            <a href="{{ $child->url ?? '#' }}" class="{{ $hasGrandchildren ? 'has-submenu' : '' }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <circle cx="12" cy="12" r="2" />
                                </svg>
                                {{ $child->title }}
                                @if ($hasGrandchildren)
                                    <svg class="submenu-arrow" width="16" height="16" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                                    </svg>
                                @endif
                            </a>

                            @if ($hasGrandchildren)
                                <div class="submenu third-level">
                                    <div class="submenu-header">
                                        <svg class="back-btn" width="16" height="16" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path d="M14 6l-1.41 1.41L7.17 12l5.42 5.59L14 18l-6-6z" />
                                        </svg>
                                        <span>{{ $child->title }}</span>
                                    </div>
                                    @foreach ($child->children()->where('status', true)->orderBy('order')->get() as $grandchild)
                                        <a href="{{ $grandchild->url ?? '#' }}">
                                            <svg width="16" height="16" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <circle cx="12" cy="12" r="2" />
                                            </svg>
                                            {{ $grandchild->title }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>

        <div class="mobile-social">
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
            </a>
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                </svg>
            </a>
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                </svg>
            </a>
            <a href="#">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                </svg>
            </a>
        </div>
    </div>
    <div class="overlay"></div>
    {{-- End Mobile Menu --}}

    @yield('content')

    <!-- Start Footer -->
    <style>
        .footer {
            background-color: #0f172a;
            color: #ffffff;
            padding: 60px 0 30px;
        }

        .footer-container {
            padding: 0 15px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .footer-column {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-logo img {
            filter: brightness(0) invert(1);
        }

        .footer-about {
            font-size: 14px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
            gap: 15px;
        }

        .footer-social a {
            text-decoration: none;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.3s;
        }
        .social-icon:hover {
            background-color: var(--primary-color, #f97316);
            transform: translateY(-3px);
        }

        .footer-heading {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary-color, #f97316);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-link {
            color: #94a3b8;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .footer-link i {
            margin-right: 8px;
            font-size: 10px;
        }

        .footer-links a {
            text-decoration: none;
        }

        .footer-link:hover {
            color: var(--primary-color, #f97316);
            transform: translateX(5px);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #94a3b8;
            font-size: 14px;
        }

        .contact-icon {
            color: #60a5fa;
            font-size: 16px;
            margin-top: 3px;
        }

        .footer-bottom {
            padding: 20px 15px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            /* margin-top: 40px; */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #94a3b8;
            font-size: 14px;
        }

        .payment-methods {
            display: flex;
            gap: 10px;
        }

        .payment-icon {
            width: 40px;
            height: 25px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
        }

        .newsletter-form {
            position: relative;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .newsletter-input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
            font-size: 14px;
        }

        .newsletter-input::placeholder {
            color: #94a3b8;
        }

        .newsletter-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            background-color: #1e3a8a;
            color: #fff;
            border: none;
            padding: 7px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .newsletter-btn:hover {
            background-color: #1d4ed8;
        }

        @media (max-width: 992px) {
            .footer-heading {
                font-size: 14px;
            }

            .footer-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }

            .footer {
                padding: 20px 0 30px;
            }
        }

        @media (max-width: 576px) {
            .footer-container {
                grid-template-columns: 1fr;
                display: block;
            }

            /* 1st column (Logo/About) - full width */
            .footer-container .footer-column:nth-child(1) {
                width: 100%;
                margin-bottom: 25px;
            }

            /* 2nd, 3rd and 4th columns - side by side */
            .footer-container .footer-column:nth-child(2),
            .footer-container .footer-column:nth-child(3),
            .footer-container .footer-column:nth-child(4) {
                display: inline-block;
                width: 31%;
                vertical-align: top;
                margin-right: 2%;
                margin-bottom: 25px;
            }

            .footer-container .footer-column:nth-child(4) {
                margin-right: 0;
            }

            /* 5th column (Contact) - full width */
            .footer-container .footer-column:nth-child(5) {
                width: 100%;
                clear: both;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

        }

        .footer-dev-break {
            display: inline;
        }
        @media (max-width: 767.98px) {
            .footer-dev-break {
                display: block;
                margin-top: 4px;
            }
        }
    </style>
    <footer class="footer">
        <div class="base-container footer-container">
            <!-- Column 1: Logo & About -->
            <div class="footer-column">
                <div class="footer-logo">
                    @if (setting('general', 'logo'))
                        <img src="{{ \App\Services\SettingsService::getLogo() }}"
                            style="width: {{ setting('general', 'logo_width', '140') }}px">
                    @else
                        <img src="{{ asset('new/thikana.png') }}"
                            style="width: {{ setting('general', 'logo_width', '140') }}px">
                    @endif
                </div>
                <p class="footer-about">
                    {{ setting('general', 'about_website', 'Welcome to our online store! Discover premium quality products crafted for everyday lifestyle and modern trends.') }}
                </p>
                <div class="footer-social">
                    @if (setting('general', 'facebook_url'))
                        <a href="{{ setting('general', 'facebook_url') }}" class="social-icon" target="_blank"
                            rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    @endif

                    @if (setting('general', 'instagram_url'))
                        <a href="{{ setting('general', 'instagram_url') }}" class="social-icon" target="_blank"
                            rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    @endif

                    @if (setting('general', 'twitter_url'))
                        <a href="{{ setting('general', 'twitter_url') }}" class="social-icon" target="_blank"
                            rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    @endif

                    @if (setting('general', 'youtube_url'))
                        <a href="{{ setting('general', 'youtube_url') }}" class="social-icon" target="_blank"
                            rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    @endif

                    @if (setting('general', 'tiktok_url'))
                        <a href="{{ setting('general', 'tiktok_url') }}" class="social-icon" target="_blank"
                            rel="noopener">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Column 2: Information -->
            <div class="footer-column">
                <h4 class="footer-heading">Information</h4>
                <div class="footer-links">
                    <a href="{{ url('/p/terms-conditions') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> TERMS & CONDITIONS
                    </a>
                    <a href="{{ url('/p/privacy-policy') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> PRIVACY POLICY
                    </a>
                    <a href="{{ url('/faq') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> FAQ
                    </a>
                    <a href="{{ url('/p/about-us') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> ABOUT US
                    </a>
                </div>
            </div>

            <!-- Column 3: Customer Care -->
            <div class="footer-column">
                <h4 class="footer-heading">Customer Care</h4>
                <div class="footer-links">
                    <a href="{{ url('/user/dashboard') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> MY ACCOUNT
                    </a>
                    <a href="{{ url('/order-tracking') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> ORDER TRACKING
                    </a>
                    <a href="{{ url('/p/shipping-policy') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> SHIPPING POLICY
                    </a>
                    <a href="{{ url('/p/returns-exchanges') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> RETURNS & EXCHANGES
                    </a>
                    <a href="{{ url('/faq') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> FAQS
                    </a>
                </div>
            </div>

            <!-- Column 4: Categories -->
            <div class="footer-column">
                <h4 class="footer-heading">Categories</h4>
                <div class="footer-links">
                    <a href="{{ url('/shop/watches') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> WATCHES
                    </a>
                    <a href="{{ url('/') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> HOME
                    </a>
                    <a href="{{ url('/shop') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> SHOP
                    </a>
                    <a href="{{ url('/combo') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> COMBO
                    </a>
                    <a href="{{ url('/shop/man') }}" class="footer-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9.707 18.707l6-6a.999.999 0 0 0 0-1.414l-6-6a.999.999 0 1 0-1.414 1.414L13.586 12l-5.293 5.293a.999.999 0 1 0 1.414 1.414z" />
                        </svg> MAN
                    </a>
                </div>
            </div>

            <!-- Column 5: Contact Us -->
            <div class="footer-column">
                <h4 class="footer-heading">Contact Us</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </div>
                        <div>
                            {{ setting('general', 'address', '123 Fashion Street, Dhaka 1230, Bangladesh') }}
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                            </svg>
                        </div>
                        <div>{{ setting('general', 'phone_number', '+880 1304224233') }}</div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                        </div>
                        <div>{{ setting('general', 'contact_email', 'info@thikana.com') }}</div>
                    </div>
                </div>
                @php
                    $showNewsletter = setting('general', 'newsletter_show', '1');
                @endphp

                @if ($showNewsletter == '1')
                    <h4 class="footer-heading" style="margin-top: 20px;">Newsletter</h4>
                    <p style="color: var(--text-light); font-size: 14px; margin-bottom: 10px;">Subscribe to receive
                        updates
                        on new
                        arrivals and special promotions</p>
                    <form class="newsletter-form" id="newsletter-form">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address"
                            class="newsletter-input" required>
                        <button type="submit" class="newsletter-btn">Subscribe</button>
                        <div class="newsletter-message" style="display: none; margin-top: 10px;"></div>
                    </form>
                @endif
            </div>
        </div>

        <div class="base-container footer-bottom">
            <div class="copyright">
                {!! setting('general', 'footer_copyright', '© 2025 Shop . All Rights Reserved. Developed By') !!}
                <span class="footer-dev-break">
                    Developed By <a href="https://uddoktaecommerce.com" target="_blank"
                        style="color:rgb(218, 82, 82);">Uddokta
                        Ecommerce</a>
                </span>
            </div>
            {{-- <div class="payment-methods">
                <div class="payment-icon"><i class="fab fa-cc-visa"></i></div>
                <div class="payment-icon"><i class="fab fa-cc-mastercard"></i></div>
                <div class="payment-icon"><i class="fab fa-cc-paypal"></i></div>
                <div class="payment-icon"><i class="fab fa-cc-amex"></i></div>
            </div> --}}
        </div>
    </footer>
    {{-- End Footer --}}
    <!-- Floating Cart -->
    <style>
        .floating-cart {
            position: fixed;
            bottom: {{ setting('general', 'floating_cart_bottom_desktop', '21') }}px;
            right: {{ setting('general', 'floating_cart_right_desktop', '30') }}px;
            width: 50px;
            height: 50px;
            background-color: #1e3a8a !important;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%) !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
            cursor: pointer;
            z-index: 20;
        }

        @media (max-width:768px) {
            .floating-cart {
                width: 50px;
                height: 50px;
                bottom: {{ setting('general', 'floating_cart_bottom_mobile', '55') }}px;
                right: {{ setting('general', 'floating_cart_right_mobile', '20') }}px;
            }
        }

        .cart-items {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #1d4ed8 !important;
            color: white;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .cc4 svg {
            color: #fff;
            width: 40px;
            height: 40px;
            background-color: transparent;
        }
    </style>
    <style>
        .free-shipping-progress-bar {
            display: none !important;
        }



        .progress-bar-bg {
            width: 100%;
            height: 12px;
            background: #e5f7e0;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 3px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%);
            border-radius: 6px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .progress-bar-text {
            font-size: 0.9rem;
            color: #16a34a;
            font-weight: 600;
            text-align: center;
        }

        .progress-bar-text b {
            color: #0d7a2b;
        }

        @media (max-width: 768px) {
            .free-shipping-progress-bar {
                bottom: {{ setting('general', 'free_shipping_progress_bottom_mobile', '37') }}px;
                right: {{ setting('general', 'free_shipping_progress_right_mobile', '59') }}px;
            }
        }

        /* Hide progress bar on desktop */
        .free-shipping-progress-bar.hide-desktop {
            display: none !important;
        }

        /* Hide progress bar on mobile */
        @media (max-width: 768px) {
            .free-shipping-progress-bar.hide-mobile {
                display: none !important;
            }
        }

        /* Hide floating cart on desktop */
        .floating-cart.hide-desktop {
            display: none !important;
        }

        /* Hide floating cart on mobile */
        @media (max-width: 768px) {
            .floating-cart.hide-mobile {
                display: none !important;
            }
        }
    </style>
    <style>
        .cart-drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease;
            z-index: 2000;
        }

        .cart-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: min(420px, 92vw);
            height: 100vh;
            height: 100dvh;
            background: #f7f8fa;
            box-shadow: -6px 0 20px rgba(0, 0, 0, 0.12);
            transition: right 0.3s ease;
            z-index: 2000;
            display: flex;
            flex-direction: column;
        }

        .cart-drawer.active {
            right: 0;
        }

        body.cart-drawer-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
        }

        .cart-drawer-header {
            display: grid;
            grid-template-columns: 40px 1fr 40px;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cart-drawer-title {
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 0.5px;
            color: #f8fafc;
        }

        .cart-drawer-close {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .cart-drawer-close:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .cart-drawer-body {
            padding: 12px;
            overflow-y: auto;
            flex: 1;
            background: #f8fafc;
        }

        .cart-sidebar-loading {
            text-align: center;
            padding: 40px 0;
            color: #64748b;
            font-weight: 600;
        }

        .cart-sidebar-items {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .cart-sidebar-item {
            display: grid;
            grid-template-columns: 78px 1fr auto;
            gap: 12px;
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 10px;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .cart-sidebar-item:hover {
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .cart-sidebar-thumb img,
        .cart-thumb-placeholder {
            width: 100%;
            height: 88px;
            border-radius: 10px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .cart-thumb-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 12px;
        }

        .cart-sidebar-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
            justify-content: center;
        }

        .cart-sidebar-title {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.35;
        }

        .cart-sidebar-variant {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .cart-sidebar-chip {
            align-self: flex-start;
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        .cart-sidebar-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #334155;
            flex-wrap: wrap;
            font-size: 13px;
        }

        .cart-sidebar-line-total {
            color: #2563eb;
            font-weight: 700;
        }

        .cart-sidebar-remove {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 6px;
            border-radius: 8px;
            cursor: pointer;
            align-self: flex-start;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-sidebar-remove:hover {
            background: #fee2e2;
            transform: scale(1.08);
        }

        .cart-drawer-footer {
            padding: 16px 18px 20px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -10px 25px rgba(0, 0, 0, 0.06);
        }

        .cart-drawer-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            font-weight: 800;
            font-size: 17px;
            color: #0f172a;
        }

        .cart-drawer-total-value {
            color: #2563eb;
            font-size: 20px;
        }

        .cart-drawer-actions {
            display: flex;
            gap: 12px;
        }

        .cart-drawer-actions .drawer-btn {
            flex: 1;
            text-align: center;
            padding: 13px 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .cart-drawer-actions .drawer-btn.primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.35);
        }

        .cart-drawer-actions .drawer-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(37, 99, 235, 0.45);
        }

        .cart-drawer-actions .drawer-btn.secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .cart-drawer-actions .drawer-btn.secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .cart-drawer-actions .drawer-btn:active {
            transform: translateY(1px);
        }

        .cart-sidebar-empty {
            text-align: center;
            padding: 40px 0;
            color: #6b7280;
            font-weight: 600;
        }

        .cart-sidebar-shop {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 14px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
        }

        .cart-sidebar-quantity {
            width: 125px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 0px;
            background: #f3f4f6;
            padding: 3px 4px;
            border-radius: 10px;
        }

        .cart-qty-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            color: #111827;
        }

        .cart-qty-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .cart-qty-btn:hover:not(:disabled) {
            background: #fef2f2;
            border-color: #fecdd3;
        }

        .cart-qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 6px;
            font-weight: 700;
            background: #fff;
        }

        @media (max-width: 768px) {
            .cart-drawer {
                width: 100%;
                right: -100%;
                border-radius: 0;
            }
        }
    </style>
    {{-- End Floating Cart --}}

    <!-- Menu js -->
    <script src="{{ asset('new/menu.js') }}"></script>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-to-cart-quick').forEach(function(icon) {
                icon.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const quickCart = icon.closest('.quickitemcart');
                    const productId = quickCart.getAttribute('data-product-id');
                    const productType = quickCart.getAttribute('data-product-type');

                    let payload = {
                        product_id: productId
                    };

                    if (productType === 'variable') {
                        const combinationInput = quickCart.querySelector(
                            'input[name="combination_id"]');
                        if (combinationInput) {
                            payload.combination_id = combinationInput.value;
                        }
                    }
                    fetch('{{ route('cart.add.quick') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show simple checkmark
                                if (window.showCheck) {
                                    window.showCheck(icon);
                                }
                                // Update cart count and free shipping progress
                                fetch('{{ route('cart.count') }}')
                                    .then(res => res.json())
                                    .then(countData => {
                                        // Update cart count
                                        document.querySelectorAll(
                                            '.cart-count, .cart-items').forEach(
                                            function(el) {
                                                el.textContent = countData.count;
                                            });

                                        // Update cart total
                                        document.querySelectorAll('.cart-total').forEach(
                                            function(el) {
                                                el.textContent = 'BDT ' + parseFloat(
                                                    countData.total).toFixed(2);
                                            });

                                        // Update free shipping progress bar
                                        const progressBarContainer = document.querySelector(
                                            '.free-shipping-progress-bar');
                                        if (progressBarContainer && countData.html) {
                                            progressBarContainer.outerHTML = countData.html;
                                        }
                                    });
                            }
                        })
                        .catch(() => alert('Error adding to cart.'));
                });
            });
        });
    </script>

    <script src="{{ asset('new/search.js') }}"></script>
    @php
        $showNewsletter = setting('general', 'newsletter_show', '1');
    @endphp

    @if ($showNewsletter == '1')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const newsletterForm = document.getElementById('newsletter-form');

                if (newsletterForm) {
                    newsletterForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        const messageDiv = this.querySelector('.newsletter-message');
                        const submitBtn = this.querySelector('.newsletter-btn');
                        const emailInput = this.querySelector('input[name="email"]');

                        // Disable the button and input during submission
                        submitBtn.disabled = true;
                        emailInput.disabled = true;
                        submitBtn.innerHTML = 'Subscribing...';

                        fetch('{{ route('subscribe') }}', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                messageDiv.style.display = 'block';

                                if (data.success) {
                                    messageDiv.innerHTML =
                                        `<div style="color: green;">${data.message}</div>`;
                                    this.reset();
                                } else {
                                    messageDiv.innerHTML = `<div style="color: red;">${data.message}</div>`;
                                }

                                // Re-enable the button and input
                                submitBtn.disabled = false;
                                emailInput.disabled = false;
                                submitBtn.innerHTML = 'Subscribe';

                                // Hide the message after 5 seconds
                                setTimeout(() => {
                                    messageDiv.style.display = 'none';
                                }, 5000);
                            })
                            .catch(error => {
                                messageDiv.style.display = 'block';
                                messageDiv.innerHTML =
                                    '<div style="color: red;">An error occurred. Please try again later.</div>';

                                // Re-enable the button and input
                                submitBtn.disabled = false;
                                emailInput.disabled = false;
                                submitBtn.innerHTML = 'Subscribe';
                            });
                    });
                }
            });
        </script>
    @endif

    {{-- Scroll to Top Button will be added after PHP variables are defined --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality is handled by search.js
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.product-image img[loading="lazy"]').forEach(function(img) {
                function hidePreloader() {
                    const preloader = img.parentElement.querySelector('.image-preloader');
                    if (preloader) preloader.style.display = 'none';
                }
                img.addEventListener('load', hidePreloader);
                img.addEventListener('error', hidePreloader);

                // If image is already loaded (from cache), hide preloader immediately
                if (img.complete && img.naturalHeight !== 0) {
                    hidePreloader();
                }
            });
        });
    </script>

    @php
        $shippingSetting = \App\Models\BasicShippingSetting::first();
        $freeShippingMinimum =
            $shippingSetting && $shippingSetting->free_shipping_threshold
                ? (float) $shippingSetting->free_shipping_threshold
                : 0;
        $cartTotal = $total ?? 0;

        // Check settings for showing progress bar
        $showProgressBar = setting('general', 'show_free_shipping_progress', '1') == '1';
        $showOnDesktop = setting('general', 'show_free_shipping_progress_desktop', '1') == '1';
        $showOnMobile = setting('general', 'show_free_shipping_progress_mobile', '1') == '1';

        // Check settings for showing floating cart
        $showFloatingCart = setting('general', 'show_floating_cart', '1') == '1';
        $showFloatingCartDesktop = setting('general', 'show_floating_cart_desktop', '1') == '1';
        $showFloatingCartMobile = setting('general', 'show_floating_cart_mobile', '1') == '1';

        // Check settings for showing scroll to top button
        $showScrollToTopDesktop = setting('general', 'show_scroll_to_top_desktop', '1') == '1';
        $showScrollToTopMobile = setting('general', 'show_scroll_to_top_mobile', '1') == '1';

        // Get scroll to top positioning settings
        $scrollToTopBottomDesktop = setting('general', 'scroll_to_top_bottom_desktop', '20');
        $scrollToTopRightDesktop = setting('general', 'scroll_to_top_right_desktop', '20');
        $scrollToTopBottomMobile = setting('general', 'scroll_to_top_bottom_mobile', '20');
        $scrollToTopRightMobile = setting('general', 'scroll_to_top_right_mobile', '20');

        // Get shop page specific positioning
        $scrollToTopShopBottomDesktop = setting('general', 'scroll_to_top_shop_bottom_desktop', '20');
        $scrollToTopShopRightDesktop = setting('general', 'scroll_to_top_shop_right_desktop', '20');
        $scrollToTopShopBottomMobile = setting('general', 'scroll_to_top_shop_bottom_mobile', '20');
        $scrollToTopShopRightMobile = setting('general', 'scroll_to_top_shop_right_mobile', '20');

        // Check if current page is shop page
        $isShopPage = request()->is('shop*') || request()->is('category*') || request()->is('product*');
    @endphp

    @if ($freeShippingMinimum > 0 && $showProgressBar)
        @php
            $remaining = max(0, $freeShippingMinimum - $cartTotal);
            $progress = min(100, round(($cartTotal / $freeShippingMinimum) * 100));
        @endphp
        <div
            class="free-shipping-progress-bar @if (!$showOnDesktop) hide-desktop @endif @if (!$showOnMobile) hide-mobile @endif">
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
            </div>
            <div class="progress-bar-text">
                @if ($remaining > 0)
                    <span><b>৳{{ $remaining }}</b> কিনলে <b>ফ্রি ডেলিভারি</b> পাবেন!</span>
                @else
                    <span><b>ফ্রি ডেলিভারি unlocked!</b></span>
                @endif
            </div>
        </div>
    @endif

     <!-- Floating Cart -->
     @if ($showFloatingCart)
     <a href="{{ route('cart.index') }}"
         class="floating-cart cart-drawer-trigger @if (!$showFloatingCartDesktop) hide-desktop @endif @if (!$showFloatingCartMobile) hide-mobile @endif">
         <div class="cc4">

             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" xml:space="preserve">
                 <path fill="#fff"
                     d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z" />
                 <path fill="#fff"
                     d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z" />
             </svg>

         </div>
         <span class="cart-items">{{ $cartCount }}</span>
     </a>
    @endif
    {{-- End Floating Cart --}}

    <!-- Cart Drawer -->
    @php
        $drawerCarts = collect();
        if (Auth::check()) {
            $drawerCarts = \App\Models\Cart::with(['product', 'variationCombination', 'comboOffer'])->where('user_id', Auth::id())->get();
        } elseif (request()->cookie('guest_id')) {
            $drawerCarts = \App\Models\Cart::with(['product', 'variationCombination', 'comboOffer'])->where('guest_id', request()->cookie('guest_id'))->get();
        }
        foreach ($drawerCarts as $item) {
            $item->calculated_subtotal = $item->price * $item->qunt;
        }
        $drawerCartTotal = $drawerCarts->sum(function ($item) {
            return $item->calculated_subtotal ?? ($item->price * $item->qunt);
        });
    @endphp
    <div id="cartDrawerOverlay" class="cart-drawer-overlay"></div>
    <div id="cartDrawer" class="cart-drawer">
        <div class="cart-drawer-header">
            <button type="button" id="cartDrawerClose" class="cart-drawer-close" aria-label="Close cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" />
                    <path d="M18 6L6 18" stroke="white" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
            <div class="cart-drawer-title">কার্ট</div>
            <div></div>
        </div>
        <div class="cart-drawer-body" id="cartDrawerBody">
            @include('frontend.partials.cart-sidebar-items', [
                'carts' => $drawerCarts,
                'subTotal' => $drawerCartTotal
            ])
        </div>
        <div class="cart-drawer-footer">
            <div class="cart-drawer-total">
                <span>কার্ট টোটাল</span>
                <span class="cart-drawer-total-value">৳{{ number_format($drawerCartTotal ?? 0, 0) }}</span>
            </div>
            <div class="cart-drawer-actions">
                <a href="{{ route('cart.index') }}" class="drawer-btn secondary" style="display: none;">কার্ট দেখুন</a>
                <a href="{{ route('checkout') }}" class="drawer-btn primary">কিনে ফেলুন</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const drawer = document.getElementById('cartDrawer');
            const overlay = document.getElementById('cartDrawerOverlay');
            const closeBtn = document.getElementById('cartDrawerClose');
            const cartBody = document.getElementById('cartDrawerBody');
            const totalEl = document.querySelector('.cart-drawer-total-value');
            const triggers = document.querySelectorAll('.cart-drawer-trigger');
            const sidebarUrl = "{{ route('cart.sidebar') }}";
            const destroyUrlTemplate = "{{ route('cart.destroy', ['id' => '__ID__']) }}";
            const updateQuantityUrl = "{{ route('cart.update.quantity') }}";
            const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
            let isLoading = false;
            let scrollPosition = 0;

            function openDrawer() {
                if (!drawer || !overlay) return;
                overlay.classList.add('active');
                drawer.classList.add('active');
                scrollPosition = window.scrollY || document.documentElement.scrollTop || 0;
                document.body.style.top = `-${scrollPosition}px`;
                document.body.classList.add('cart-drawer-open');
                if (cartBody) {
                    cartBody.scrollTop = 0;
                }
                loadCart(false);
            }

            function closeDrawer() {
                overlay?.classList.remove('active');
                drawer?.classList.remove('active');
                document.body.classList.remove('cart-drawer-open');
                document.body.style.top = '';
                if (scrollPosition) {
                    window.scrollTo(0, scrollPosition);
                }
            }

            function renderCart(data) {
                if (!cartBody) return;
                if (!data || data.success === false) {
                    cartBody.innerHTML = '<div class="cart-sidebar-empty"><p>কার্ট খালি</p></div>';
                    return;
                }
                cartBody.innerHTML = data.html || '';
                updateCounters(data);
            }

            function updateCounters(data) {
                if (data && typeof data.total !== 'undefined' && totalEl) {
                    totalEl.textContent = '৳' + Number(data.total).toFixed(0);
                }

                if (data && typeof data.count !== 'undefined') {
                    document.querySelectorAll('.cart-count, .cart-items').forEach(function(el) {
                        el.textContent = data.count;
                    });
                }
            }

            function loadCart(showLoading = false) {
                if (!cartBody || !sidebarUrl) return;
                if (showLoading) {
                    cartBody.innerHTML = '<div class="cart-sidebar-loading">লোড হচ্ছে...</div>';
                }
                isLoading = true;

                fetch(sidebarUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => renderCart(data))
                    .catch(() => {
                        if (showLoading || !cartBody.innerHTML.trim() || cartBody.querySelector('.cart-sidebar-loading')) {
                            cartBody.innerHTML =
                                '<div class="cart-sidebar-empty"><p>কার্টের তথ্য আনতে সমস্যা হয়েছে</p></div>';
                        }
                    })
                    .finally(() => {
                        isLoading = false;
                    });
            }

            function handleRemove(event) {
                const removeBtn = event.target.closest('[data-cart-remove]');
                if (!removeBtn || isLoading) return;

                event.preventDefault();
                const id = removeBtn.getAttribute('data-cart-remove');
                const removeAll = removeBtn.getAttribute('data-remove-all') === '1';
                if (!id) return;

                const url = removeAll
                    ? `${destroyUrlTemplate.replace('__ID__', id)}?remove_all=1`
                    : destroyUrlTemplate.replace('__ID__', id);

                isLoading = true;
                removeBtn.disabled = true;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => renderCart(data))
                    .catch(() => {
                        cartBody.innerHTML =
                            '<div class="cart-sidebar-empty"><p>আইটেম মুছতে সমস্যা হয়েছে</p></div>';
                    })
                    .finally(() => {
                        isLoading = false;
                        removeBtn.disabled = false;
                    });
            }

            function handleQuantityClick(event) {
                const btn = event.target.closest('.cart-qty-btn');
                if (!btn || isLoading) return;

                const wrapper = btn.closest('.cart-sidebar-quantity');
                const input = wrapper?.querySelector('.cart-qty-input');
                const cartId = wrapper?.getAttribute('data-cart-id');
                const action = btn.getAttribute('data-action');

                if (!cartId || !action || !input) return;

                if (action === 'decrease' && Number(input.value) <= 1) return;
                submitQuantity(cartId, action, input);
            }

            function handleQuantityChange(event) {
                const input = event.target.closest('.cart-qty-input');
                if (!input || isLoading) return;
                const wrapper = input.closest('.cart-sidebar-quantity');
                const cartId = wrapper?.getAttribute('data-cart-id');
                if (!cartId) return;

                const value = Math.max(1, Number(input.value) || 1);
                input.value = value;
                submitQuantity(cartId, 'set', input, value);
            }

            function submitQuantity(cartId, action, inputEl, value = null) {
                if (!updateQuantityUrl) return;
                isLoading = true;
                disableQuantityControls(true);

                fetch(updateQuantityUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            cart_id: cartId,
                            action: action,
                            ...(action === 'set' ? {
                                quantity: value
                            } : {})
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            loadCart();
                        } else if (data.message) {
                            alert(data.message);
                        }
                    })
                    .catch(() => {
                        alert('পরিমাণ আপডেট করতে সমস্যা হয়েছে');
                    })
                    .finally(() => {
                        isLoading = false;
                        disableQuantityControls(false);
                    });
            }

            function disableQuantityControls(state) {
                cartBody?.querySelectorAll('.cart-qty-btn, .cart-qty-input').forEach(function(el) {
                    el.disabled = state;
                });
            }

            triggers.forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    openDrawer();
                });
            });

            overlay?.addEventListener('click', closeDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            document.addEventListener('keyup', function(e) {
                if (e.key === 'Escape') {
                    closeDrawer();
                }
            });

            cartBody?.addEventListener('click', handleRemove);
            cartBody?.addEventListener('click', handleQuantityClick);
            cartBody?.addEventListener('change', handleQuantityChange);
        });
    </script>

    <!-- Scroll to Top Button -->
    @if (setting('homepage', 'enable_scroll_to_top', '1') == '1')
        <style>
            /* Scroll to top button styles (Left Side, Deep Navy) */
            #scrollToTopBtn {
                position: fixed !important;
                bottom: 25px !important;
                left: 20px !important;
                right: auto !important;
                width: 44px;
                height: 44px;
                background-color: #1e3a8a !important;
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%) !important;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                display: none;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
                transition: all 0.3s ease;
                z-index: 9999 !important;
            }

            #scrollToTopBtn:hover {
                background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%) !important;
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(30, 58, 138, 0.6);
            }

            #scrollToTopBtn svg {
                width: 22px;
                height: 22px;
                fill: white;
            }

            #scrollToTopBtn.show {
                display: flex !important;
            }

            html {
                scroll-behavior: smooth;
            }

            @media (max-width: 768px) {
                #scrollToTopBtn {
                    bottom: 25px !important;
                    left: 20px !important;
                    right: auto !important;
                }
            }

            /* Hide scroll to top button on desktop */
            #scrollToTopBtn.hide-desktop {
                display: none !important;
            }

            /* Hide scroll to top button on mobile */
            @media (max-width: 768px) {
                #scrollToTopBtn.hide-mobile {
                    display: none !important;
                }
            }
        </style>
        <button id="scrollToTopBtn"
            class="@if (!$showScrollToTopDesktop) hide-desktop @endif @if (!$showScrollToTopMobile) hide-mobile @endif"
            title="Go to top">
            <!-- Up arrow SVG -->
            <svg viewBox="0 0 24 24">
                <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.59 5.58L20 12l-8-8z" />
            </svg>
        </button>
        <script>
            const scrollBtn = document.getElementById('scrollToTopBtn');

            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    scrollBtn.classList.add('show');
                } else {
                    scrollBtn.classList.remove('show');
                }
            });

            scrollBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>
    @endif
    {{-- End Scroll to Top Button --}}

    @if(setting('mobile_nav', 'enabled', '1') == '1')
    <style>

        .mobileflotnav-section {
            display: none;
        }

        @media (max-width: 768px) {
            .footer-bottom {
                margin-bottom: 50px !important;
            }
            .mobileflotnav-section {
                display: block;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1000;
                background: white;
                padding: 15px 20px;
                border-radius: 20px 20px 0 0;
                box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.15);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }

            .mobileflotnav-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                max-width: 500px;
                margin: 0 auto;
            }

            .mobileflotnav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: #333;
                transition: all 0.3s ease;
                padding: 5px 4px;
                border-radius: 12px;
                flex: 1;
                cursor: pointer;
            }

            .mobileflotnav-item:hover {
                background-color: #f8f9fa;
                transform: translateY(-3px);
            }

            .mobileflotnav-item:active {
                transform: translateY(0);
            }

            .mobileflotnav-icon {
                width: 32px;
                height: 32px;
                margin-bottom: 0px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .mobileflotnav-item:first-child .mobileflotnav-icon svg {
                fill: #e74c3c;
            }

            .mobileflotnav-item:nth-child(2) .mobileflotnav-icon svg path {
                fill: #2c3e50;
            }

            .mobileflotnav-item:nth-child(3) .mobileflotnav-icon svg {
                fill: #3498db;
            }

            .mobileflotnav-item:nth-child(4) .mobileflotnav-icon svg {
                fill: #27ae60;
            }

            .mobileflotnav-item:nth-child(5) .mobileflotnav-icon svg path {
                fill: #8e44ad;
            }

            .mobileflotnav-text {
                font-size: 12px;
                font-weight: 500;
                color: #2c3e50;
                text-align: center;
                line-height: 1.2;
            }
            .mobileflotnav-item:hover .mobileflotnav-text {
                color: #3498db;
            }
        }

        @media (max-width: 480px) {
            .mobileflotnav-section {
                padding: 0px 5px;
            }

            .mobileflotnav-container {
                gap: 5px;
            }

            .mobileflotnav-item {
                padding: 5px 4px;
            }

            .mobileflotnav-icon {
                width: 28px;
                height: 28px;
                margin-bottom: 0px;
            }

            .mobileflotnav-text {
                font-size: 11px;
            }
        }

        @media (max-width: 320px) {
            .mobileflotnav-text {
                font-size: 10px;
            }

            .mobileflotnav-icon {
                width: 24px;
                height: 24px;
            }
        }

        </style>
    <section class="mobileflotnav-section">
        <nav class="mobileflotnav-container">
                @php
                    // Define navigation items with their settings
                    $navItems = [
                        'home' => [
                            'enabled' => setting('mobile_nav', 'home_enabled', '1'),
                            'order' => setting('mobile_nav', 'home_order', '1'),
                            'url' => '/',
                            'text' => setting('mobile_nav', 'home_label', 'হোম'),
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z" /></svg>'
                        ],
                        'cart' => [
                            'enabled' => setting('mobile_nav', 'cart_enabled', '1'),
                            'order' => setting('mobile_nav', 'cart_order', '2'),
                            'url' => '/cart',
                            'text' => setting('mobile_nav', 'cart_label', 'কার্ট'),
                            'icon' => '<svg class="cart-drawer-trigger" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" /></svg>'
                        ],
                        'chat' => [
                            'enabled' => setting('mobile_nav', 'chat_enabled', '1'),
                            'order' => setting('mobile_nav', 'chat_order', '3'),
                            'url' => '',
                            'text' => setting('mobile_nav', 'chat_label', 'চ্যাট'),
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4C2.9 2 2 2.9 2 4V16C2 17.1 2.9 18 4 18H6L10 22L14 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H13.17L10 19.17L6.83 16H4V4H20V16Z" /><circle cx="8" cy="10" r="1" /><circle cx="12" cy="10" r="1" /><circle cx="16" cy="10" r="1" /></svg>'
                        ],
                        'call' => [
                            'enabled' => setting('mobile_nav', 'call_enabled', '1'),
                            'order' => setting('mobile_nav', 'call_order', '4'),
                            'url' => '#',
                            'text' => setting('mobile_nav', 'call_label', 'কল'),
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"></path></svg>'
                        ],
                        'profile' => [
                            'enabled' => setting('mobile_nav', 'profile_enabled', '1'),
                            'order' => setting('mobile_nav', 'profile_order', '5'),
                            'url' => route('account.show'),
                            'text' => setting('mobile_nav', 'profile_label', 'প্রোফাইল'),
                            'icon' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.2313 18.375C18.8319 15.9269 16.6716 14.1685 14.1455 13.3374C16.7168 11.8055 17.9799 8.74371 17.1836 5.86726C16.3874 2.99081 13.7903 0.997722 10.5 0.997722C7.20975 0.997722 4.61261 2.99081 3.81637 5.86726C3.02013 8.74371 4.28324 11.8055 6.85453 13.3374C4.32844 14.1675 2.16812 15.9259 0.76875 18.375C0.619540 18.6129 0.614176 18.9107 0.751669 19.1533C0.889162 19.3959 1.14893 19.5453 1.42689 19.5429C1.70486 19.5404 1.96256 19.3861 2.09718 19.1406C3.88774 16.0513 7.06471 14.2031 10.5 14.2031C13.9353 14.2031 17.1123 16.0513 18.9028 19.1406C19.0374 19.3861 19.2951 19.5404 19.5731 19.5429C19.8511 19.5453 20.1108 19.3959 20.2483 19.1533C20.3858 18.9107 20.3805 18.6129 20.2313 18.375V18.375ZM4.9875 7.4531C4.9875 4.60539 7.35229 2.2406 10.5 2.2406C13.6477 2.2406 16.0125 4.60539 16.0125 7.4531C16.0125 10.3008 13.6477 12.6656 10.5 12.6656C7.35356 12.6625 4.99044 10.2994 4.9875 7.4531V7.4531Z" /></svg>'
                        ],
                        'category' => [
                            'enabled' => setting('mobile_nav', 'category_enabled', '1'),
                            'order' => setting('mobile_nav', 'category_order', '6'),
                            'url' => '#',
                            'text' => setting('mobile_nav', 'category_label', 'ক্যাটাগরি'),
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" style="fill: #f39c12;" /></svg>',
                            'popup' => true
                        ]
                    ];
                    
                    // Filter enabled items and sort by order
                    $enabledItems = collect($navItems)
                        ->filter(function($item) {
                            return $item['enabled'] == '1';
                        })
                        ->sortBy(function($item) {
                            return (int) $item['order'];
                        });
                @endphp
                
                @foreach($enabledItems as $key => $item)
                    @if(isset($item['popup']) && $item['popup'])
                        <div class="mobileflotnav-item category-trigger" data-popup="category-popup">
                <div class="mobileflotnav-icon">
                                {!! $item['icon'] !!}
                </div>
                            <span class="mobileflotnav-text">{{ $item['text'] }}</span>
                        </div>
                    @else
                        <a href="{{ $item['url'] }}" class="mobileflotnav-item">
                <div class="mobileflotnav-icon">
                                {!! $item['icon'] !!}
                </div>
                            <span class="mobileflotnav-text">{{ $item['text'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </section>

        @if(setting('mobile_nav', 'category_enabled', '1') == '1')
            <style>
                /* Category Popup Styles */
                .category-popup {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    z-index: 9999;
                    display: none;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .category-popup.show {
                    display: flex;
                    opacity: 1;
                }

                .category-popup-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(4px);
                }

                .category-popup-content {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    background: white;
                    display: flex;
                    flex-direction: column;
                    transform: translateY(100%);
                    transition: transform 0.3s ease;
                }

                .category-popup.show .category-popup-content {
                    transform: translateY(0);
                }

                .category-popup-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 16px 20px;
                    border-bottom: 1px solid #e5e5e5;
                    background: white;
                    position: sticky;
                    top: 0;
                    z-index: 10;
                }

                .category-popup-close,
                .category-popup-search {
                    background: none;
                    border: none;
                    padding: 8px;
                    cursor: pointer;
                    color: #666;
                    border-radius: 8px;
                    transition: background-color 0.2s ease;
                }

                .category-popup-close:hover,
                .category-popup-search:hover {
                    background-color: #f5f5f5;
                }

                .category-popup-title {
                    font-size: 18px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                }
                .category-popup-body {
                    flex: 1;
                    overflow: hidden;
                }

                .category-layout {
                    display: flex;
                    height: 100%;
                }

                .category-sidebar {
                    width: 40%;
                    background: #f8f9fa;
                    border-right: 1px solid #e5e5e5;
                    overflow-y: auto;
                }

                .category-content {
                    flex: 1;
                    overflow-y: auto;
                    background: white;
                }

                .category-list {
                    padding: 0;
                }

                .category-item {
                    display: flex;
                    align-items: center;
                    padding: 8px 18px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    border-bottom: 1px solid #e5e5e5;
                }

                .category-item:hover {
                    background-color: #f0f0f0;
                }

                .category-item.active {
                    background-color: #fff3e0;
                    border-right: 3px solid #ff9800;
                }

                .category-item-icon {
                    width: 34px;
                    height: 34px;
                    margin-right: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .category-item-icon img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 4px;
                }

                .category-item-text {
                    font-size: 14px;
                    font-weight: 500;
                    color: #333;
                }

                .category-item.active .category-item-text {
                    color: #ff9800;
                    font-weight: 600;
                }

                .subcategory-container {
                    padding: 10px;
                }

                .subcategory-placeholder {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 200px;
                    color: #999;
                    text-align: center;
                }

                .view-category-btn {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    background: #ff9800;
                    color: white;
                    border: none;
                    padding: 9px 20px;
                    border-radius: 8px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    margin-top: 16px;
                }

                .view-category-btn:hover {
                    background: #f57c00;
                    transform: translateY(-1px);
                }

                .view-category-btn:active {
                    transform: translateY(0);
                }

                .view-category-btn svg {
                    width: 16px;
                    height: 16px;
                }

                .placeholder-icon {
                    /* margin-bottom: 16px; */
                    color: #ddd;
                }

                .subcategory-section {
                    margin-bottom: 24px;
                }

                .subcategory-section-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 12px 0;
                    cursor: pointer;
                    border-bottom: 1px solid #e5e5e5;
                }

                .subcategory-section-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #333;
                    margin: 0;
                }

                .subcategory-section-toggle {
                    background: none;
                    border: none;
                    color: #666;
                    cursor: pointer;
                    transition: transform 0.2s ease;
                }

                .subcategory-section.expanded .subcategory-section-toggle {
                    transform: rotate(180deg);
                }

                .subcategory-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 16px;
                    padding: 16px 0;
                }

                .subcategory-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    text-decoration: none;
                    color: #333;
                    transition: transform 0.2s ease;
                }

                .subcategory-item:hover {
                    transform: translateY(-2px);
                }

                .subcategory-item-image {
                    width: 80px;
                    height: 80px;
                    border-radius: 8px;
                    margin-bottom: 8px;
                    background: #f5f5f5;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }

                .subcategory-item-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .subcategory-item-text {
                    font-size: 12px;
                    text-align: center;
                    line-height: 1.3;
                    font-weight: 500;
                }
                .category-loading {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 40px 20px;
                    color: #666;
                }

                .spinner {
                    width: 24px;
                    height: 24px;
                    border: 2px solid #e5e5e5;
                    border-top: 2px solid #ff9800;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-bottom: 12px;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                /* Responsive adjustments */
                @media (max-width: 480px) {
                    .category-sidebar {
                        width: 45%;
                    }
                    
                    .subcategory-grid {
                        grid-template-columns: repeat(2, 1fr);
                        gap: 2px;
                    }
                    
                    .subcategory-item-image {
                        width: 75px;
                        height: 75px;
                    }
                }
            </style>

            <!-- Category Popup -->
            <div id="category-popup" class="category-popup">
                <div class="category-popup-overlay"></div>
                <div class="category-popup-content">
                    <!-- Header -->
                    <div class="category-popup-header">
                        <button class="category-popup-close">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                        </button>
                        <h2 class="category-popup-title">Categories</h2>
                        {{-- <button class="category-popup-search">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button> --}}
                </div>
                    
                    <!-- Content -->
                    <div class="category-popup-body">
                        <div class="category-layout">
                            <!-- Left Sidebar - Main Categories -->
                            <div class="category-sidebar">
                                <div class="category-list" id="main-categories">
                                    <!-- Main categories will be loaded here via AJAX -->
                                    <div class="category-loading">
                                        <div class="spinner"></div>
                                        <span>Loading categories...</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Content - Sub Categories -->
                            <div class="category-content">
                                <div class="subcategory-container" id="subcategories">
                                    <!-- Subcategories will be loaded here via AJAX -->
                                    <div class="subcategory-placeholder">
                                        <div class="placeholder-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </div>
                                        <p>Select a category to view subcategories</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Category Popup Functionality
                function initializeCategoryPopup() {
                    const categoryTrigger = document.querySelector('.category-trigger');
                    const categoryPopup = document.getElementById('category-popup');
                    const closeBtn = document.querySelector('.category-popup-close');
                    const overlay = document.querySelector('.category-popup-overlay');
                    
                    if (!categoryTrigger || !categoryPopup) return;
                    
                    // Open popup
                    categoryTrigger.addEventListener('click', function(e) {
                        e.preventDefault();
                        showCategoryPopup();
                    });
                    
                    // Close popup
                    function closeCategoryPopup() {
                        categoryPopup.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                    
                    closeBtn.addEventListener('click', closeCategoryPopup);
                    overlay.addEventListener('click', closeCategoryPopup);
                    
                    // Load main categories when popup opens
                    function showCategoryPopup() {
                        categoryPopup.classList.add('show');
                        document.body.style.overflow = 'hidden';
                        loadMainCategories();
                    }
                    
                    // Load main categories
                    function loadMainCategories() {
                        const categoriesContainer = document.getElementById('main-categories');
                        
                        fetch('/api/mobile-categories')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    renderMainCategories(data.data);
                                } else {
                                    showError('Failed to load categories');
                                }
                            })
                            .catch(error => {
                                console.error('Error loading categories:', error);
                                showError('Failed to load categories');
                            });
                    }
                    
                    // Render main categories
                    function renderMainCategories(categories) {
                        const categoriesContainer = document.getElementById('main-categories');
                        
                        if (categories.length === 0) {
                            categoriesContainer.innerHTML = '<div class="category-loading"><span>No categories found</span></div>';
                            return;
                        }
                        
                        // Store category data globally for access in viewMainCategory function
                        window.categoryData = categories;
                        
                        const categoriesHTML = categories.map(category => `
                            <div class="category-item" data-category-id="${category.id}" data-category-slug="${category.slug}">
                                <div class="category-item-icon">
                                    <img src="${category.image_url}" alt="${category.name}" onerror="this.src='{{ asset('assets/icons/mobile-nav-icons.svg') }}#icon-grid'">
                                </div>
                                <div class="category-item-text">${category.name}</div>
                            </div>
                        `).join('');
                        
                        categoriesContainer.innerHTML = categoriesHTML;
                        
                        // Add click handlers
                        categoriesContainer.querySelectorAll('.category-item').forEach(item => {
                            item.addEventListener('click', function() {
                                const categoryId = this.dataset.categoryId;
                                selectCategory(categoryId, this);
                                loadSubcategories(categoryId);
                            });
                        });
                        
                        // Select first category by default
                        if (categories.length > 0) {
                            const firstCategory = categoriesContainer.querySelector('.category-item');
                            if (firstCategory) {
                                const categoryId = firstCategory.dataset.categoryId;
                                selectCategory(categoryId, firstCategory);
                                loadSubcategories(categoryId);
                            }
                        }
                    }
                    // Select category
                    function selectCategory(categoryId, element) {
                        // Remove active class from all categories
                        document.querySelectorAll('.category-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        
                        // Add active class to selected category
                        element.classList.add('active');
                    }
                    
                    // Load subcategories
                    function loadSubcategories(categoryId) {
                        const subcategoriesContainer = document.getElementById('subcategories');
                        subcategoriesContainer.innerHTML = '<div class="category-loading"><div class="spinner"></div><span>Loading subcategories...</span></div>';
                        
                        fetch(`/api/mobile-subcategories/${categoryId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    renderSubcategories(data.data);
                                } else {
                                    showError('Failed to load subcategories');
                                }
                            })
                            .catch(error => {
                                console.error('Error loading subcategories:', error);
                                showError('Failed to load subcategories');
                            });
                    }
                    
                    // Render subcategories
                    function renderSubcategories(subcategoryGroups) {
                        const subcategoriesContainer = document.getElementById('subcategories');
                        
                        if (subcategoryGroups.length === 0) {
                            // Get the currently selected category
                            const activeCategory = document.querySelector('.category-item.active');
                            const categoryId = activeCategory ? activeCategory.dataset.categoryId : null;
                            const categoryName = activeCategory ? activeCategory.querySelector('.category-item-text').textContent : 'Category';
                            
                            // Get category image from stored data
                            let categoryImage = null;
                            if (window.categoryData && categoryId) {
                                const category = window.categoryData.find(cat => cat.id == categoryId);
                                categoryImage = category ? category.image_url : null;
                            }
                            
                            subcategoriesContainer.innerHTML = `
                                <div class="subcategory-placeholder">
                                    <div class="placeholder-icon">
                                        ${categoryImage ? 
                                            `<img src="${categoryImage}" alt="${categoryName}" style="width: 77px; height: 77px; object-fit: cover; border-radius: 8px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                             <svg width="77" height="77" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                                <use href="{{ asset('assets/icons/mobile-nav-icons.svg') }}#icon-grid"></use>
                                             </svg>` :
                                            `<svg width="77" height="77" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <use href="{{ asset('assets/icons/mobile-nav-icons.svg') }}#icon-grid"></use>
                                             </svg>`
                                        }
                                    </div>
                                    <button class="view-category-btn" onclick="viewMainCategory('${categoryId}', '${categoryName}')">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 3H6C4.89543 3 4 3.89543 4 5V19C4 20.1046 4.89543 21 6 21H18C19.1046 21 20 20.1046 20 19V8L15 3Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M15 3V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 13H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 17H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        View ${categoryName}
                                    </button>
                                </div>
                            `;
                            return;
                        }
                        
                        const groupsHTML = subcategoryGroups.map(group => `
                            <div class="subcategory-section expanded">
                                <div class="subcategory-section-header">
                                    <h3 class="subcategory-section-title">${group.name}</h3>
                                    <button class="subcategory-section-toggle">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                                    </button>
                </div>
                                <div class="subcategory-grid">
                                    ${group.items.map(item => `
                                        <a href="${item.url}" class="subcategory-item">
                                            <div class="subcategory-item-image">
                                                <img src="${item.image_url}" alt="${item.name}" onerror="this.src='{{ asset('assets/icons/mobile-nav-icons.svg') }}#icon-grid'">
                                            </div>
                                            <div class="subcategory-item-text">${item.name}</div>
                                        </a>
                                    `).join('')}
                                </div>
                            </div>
                        `).join('');
                        
                        subcategoriesContainer.innerHTML = groupsHTML;
                        
                        // Add toggle functionality
                        subcategoriesContainer.querySelectorAll('.subcategory-section-header').forEach(header => {
                            header.addEventListener('click', function() {
                                const section = this.parentElement;
                                section.classList.toggle('expanded');
                            });
                        });
                    }
                    
                    // Show error message
                    function showError(message) {
                        const container = document.getElementById('subcategories');
                        container.innerHTML = `<div class="subcategory-placeholder"><p style="color: #e74c3c;">${message}</p></div>`;
                    }
                }

                // View main category function (global scope for onclick)
                window.viewMainCategory = function(categoryId, categoryName) {
                    if (categoryId) {
                        // Close the popup
                        const categoryPopup = document.getElementById('category-popup');
                        if (categoryPopup) {
                            categoryPopup.classList.remove('show');
                            document.body.style.overflow = '';
                        }
                        
                        // Find the category data to get the URL
                        const activeCategory = document.querySelector('.category-item.active');
                        if (activeCategory && window.categoryData) {
                            const category = window.categoryData.find(cat => cat.id == categoryId);
                            if (category && category.url) {
                                window.location.href = category.url;
                                return;
                            }
                        }
                        
                        // Fallback: construct URL using category slug
                        const categorySlug = activeCategory ? activeCategory.dataset.categorySlug : categoryId;
                        window.location.href = `/shop/${categorySlug}`;
                    }
                };

                // Initialize category popup functionality when DOM is ready
                document.addEventListener('DOMContentLoaded', function() {
                    initializeCategoryPopup();
                });
            </script>
        @endif
    @endif

    <style>
        @media (max-width: 768px) {
            .search-bar-mobile {
                transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
                will-change: transform;
            }

            .search-bar-mobile.fixed-top {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                transform: translateY(-110%);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .search-bar-mobile.fixed-top.is-visible {
                transform: translateY(0);
            }

            .search-bar-mobile-spacer {
                display: block;
                width: 100%;
                pointer-events: none;
            }
        }
    </style>

    <script>
        // Mobile search bar scroll behavior
        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.querySelector('.search-bar-mobile');

            if (!(searchBar && window.innerWidth <= 768)) {
                return;
            }

            const spacer = document.createElement('div');
            spacer.className = 'search-bar-mobile-spacer';
            spacer.style.height = `${searchBar.offsetHeight}px`;
            spacer.style.display = 'none';
            searchBar.insertAdjacentElement('afterend', spacer);

            let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const triggerPoint = (searchBar.getBoundingClientRect().top + window.pageYOffset) + searchBar.offsetHeight;

            const updateSpacerHeight = () => {
                spacer.style.height = `${searchBar.offsetHeight}px`;
            };

            const stickBar = () => {
                if (!searchBar.classList.contains('fixed-top')) {
                    searchBar.classList.add('fixed-top');
                    spacer.style.display = 'block';
                    updateSpacerHeight();
                }
            };

            const resetBar = () => {
                searchBar.classList.remove('fixed-top', 'is-visible');
                spacer.style.display = 'none';
            };

            window.addEventListener('resize', updateSpacerHeight);

            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop <= 0) {
                    resetBar();
                    lastScrollTop = 0;
                    return;
                }

                if (scrollTop < triggerPoint) {
                    resetBar();
                    lastScrollTop = scrollTop;
                    return;
                }

                stickBar();

                if (scrollTop < lastScrollTop) {
                    searchBar.classList.add('is-visible');
                } else if (scrollTop > lastScrollTop) {
                    searchBar.classList.remove('is-visible');
                }

                lastScrollTop = scrollTop;
            }, { passive: true });
        });
    </script>
    <!-- jQuery and Combo Offer System Scripts -->
    @if (isset($hasComboOffers) && $hasComboOffers)
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/combo-offer.js') }}"></script>
    @endif

    <!-- Cart Animation Styles and Scripts -->
    <style>
        .item-cart-icon { 
            position:relative; 
            padding:8px;
            border-radius:50%;
            transition:all 0.3s ease;
        }
        .item-cart-icon:hover {
            background:var(--primary-color);
            transform:scale(1.1);
            border: 1px solid var(--primary-color);
        }
        .item-cart-icon:hover svg path {
            fill: #fff;
        }
        .item-cart-icon.active {
            background: var(--primary-color);
        }
        .item-cart-icon.active svg path {
            fill: #fff;
        }
        .item-cart-icon.active .simple-check {
            color: var(--secondary-color);
        }
        .item-cart-icon.active .item-cart-icon {
            background: var(--primary-color);
        }
        .simple-check { 
            display:none;
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            color:var(--primary-color);
            font-size:24px;
            font-weight:bold;
            animation:pop 0.5s ease;
        }
        @keyframes pop { from{transform:translate(-50%,-50%) scale(0);} to{transform:translate(-50%,-50%) scale(1);} }
    </style>

    <script>
        // Simple checkmark animation
        function showCheck(cartIcon) {
            const check = cartIcon.querySelector('.simple-check');
            if (check) {
                // Add active class for green background
                cartIcon.classList.add('active');
                
                // Show checkmark
                check.style.display = 'flex';
                setTimeout(() => {
                    check.style.display = 'none';
                    cartIcon.classList.remove('active');
                }, 1500);
            }
        }
        window.showCheck = showCheck;
        
    </script>

    <!-- Whatsapp fixed button for chat -->
    @php
        $showWhatsAppButton = setting('general', 'show_whatsapp_button', '1') == '1';
        $rawWhatsAppNumber = setting('general', 'whatsapp_number', '');
        $formattedWhatsAppNumber = preg_replace('/\D+/', '', $rawWhatsAppNumber);
        $whatsAppMessage = urlencode(setting('general', 'whatsapp_message', "Hello, I'm interested in your services"));
    @endphp

    @if ($showWhatsAppButton && !empty($formattedWhatsAppNumber))
        <!-- Whatsapp fixed button for chat (Right Side, above cart) -->
        <div class="whatsapp-button-container">
            <a href="https://wa.me/{{ $formattedWhatsAppNumber }}?text={{ $whatsAppMessage }}"
                class="whatsapp-button" id="whatsappButton">
                <svg class="whatsapp-icon" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.569-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488" />
                </svg>
            </a>
        </div>
    @endif

    <style>
        /* Scroll To Top Left Button */
        .scroll-to-top-container {
            position: fixed !important;
            bottom: 30px !important;
            left: 20px !important;
            right: auto !important;
            z-index: 9999 !important;
        }

        .scroll-to-top-btn {
            width: 44px;
            height: 44px;
            background: #1e3a8a;
            color: #ffffff;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
            transition: all 0.3s ease;
        }

        .scroll-to-top-btn:hover {
            background: #1d4ed8;
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.6);
        }

        /* WhatsApp Button Position (Right Side directly ABOVE Cart) */
        .whatsapp-button-container {
            position: fixed;
            bottom: 85px;
            right: 20px;
            z-index: 1000;
        }

        .whatsapp-button {
            position: relative;
            width: 44px;
            height: 44px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .whatsapp-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
        }

        .whatsapp-button::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(37, 211, 102, 0.3);
            animation: whatsappPulse 2s infinite;
        }

        .whatsapp-button::after {
            content: '';
            position: absolute;
            width: 140%;
            height: 140%;
            border-radius: 50%;
            background: rgba(37, 211, 102, 0.15);
            animation: whatsappPulse 2s infinite 0.5s;
        }

        .whatsapp-icon {
            width: 22px;
            height: 22px;
            fill: white;
            z-index: 10;
            position: relative;
        }

        @keyframes whatsappPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.4;
            }

            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .scroll-to-top-container {
                bottom: 25px;
                left: 15px;
            }

            .whatsapp-button-container {
                bottom: 25px;
                right: 75px;
            }

            .whatsapp-button, .scroll-to-top-btn {
                width: 40px;
                height: 40px;
            }

            .whatsapp-icon {
                width: 20px;
                height: 20px;
            }
        }

        .whatsapp-button.clicked::before {
            animation: whatsappRipple 0.6s ease-out;
        }

        @keyframes whatsappRipple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .whatsapp-button {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }
    </style>
    <script>
        const whatsappButton = document.getElementById('whatsappButton');

        if (whatsappButton) {
            whatsappButton.addEventListener('click', function() {
                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 600);
            });

            whatsappButton.addEventListener('click', function() {
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
            });
        }

        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        if (scrollToTopBtn) {
            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Scroll topbar detection script
        (function() {
            const topbar = document.getElementById('topHeaderSection');
            if (topbar) {
                let lastScrollTop = 0;
                
                function checkPosition() {
                    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > 250) {
                        if (scrollTop < lastScrollTop) {
                            // Scrolling Up
                            topbar.classList.add('show-topbar');
                        } else {
                            // Scrolling Down
                            topbar.classList.remove('show-topbar');
                        }
                    } else {
                        // Near the top of the page (0 - 250px)
                        topbar.classList.remove('show-topbar');
                    }
                    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                }

                // Run only on scroll
                window.addEventListener('scroll', checkPosition, { passive: true });
            }
        })();

        // Category hover toggle script
        (function() {
            const trigger = document.getElementById('categoryTriggerBtn');
            const mainnav = document.querySelector('.mainnav-section');
            if (trigger && mainnav) {
                let hoverTimeout;
                
                function showNav() {
                    clearTimeout(hoverTimeout);
                    mainnav.classList.add('active');
                    trigger.classList.add('active');
                }
                
                function hideNav() {
                    hoverTimeout = setTimeout(function() {
                        mainnav.classList.remove('active');
                        trigger.classList.remove('active');
                    }, 250);
                }

                trigger.addEventListener('mouseenter', showNav);
                trigger.addEventListener('mouseleave', hideNav);
                mainnav.addEventListener('mouseenter', showNav);
                mainnav.addEventListener('mouseleave', hideNav);
            }
        })();
    </script>
    
    <!-- Custom Footer Code -->
    @if(setting('general', 'custom_footer_code', ''))
        {!! setting('general', 'custom_footer_code', '') !!}
    @endif

    {{-- Mega Menu Hover Delay Script - Only loads when globally enabled --}}
    @if(\App\Services\SettingsService::isMegaMenuEnabled())
    <script>
        (function() {
            const hoverDelay = {{ \App\Services\SettingsService::getMegaMenuHoverDelay() }};
            if (hoverDelay > 0) {
                const megaMenuTriggers = document.querySelectorAll('.has-mega-menu');
                megaMenuTriggers.forEach(function(trigger) {
                    let timeout;
                    trigger.addEventListener('mouseenter', function() {
                        const dropdown = this.querySelector('.mega-menu-dropdown');
                        if (dropdown) {
                            timeout = setTimeout(function() {
                                dropdown.classList.add('show');
                            }, hoverDelay);
                        }
                    });
                    trigger.addEventListener('mouseleave', function() {
                        clearTimeout(timeout);
                        const dropdown = this.querySelector('.mega-menu-dropdown');
                        if (dropdown) {
                            dropdown.classList.remove('show');
                        }
                    });
                });
            }
        })();
    </script>
    @endif

</body>

</html>
