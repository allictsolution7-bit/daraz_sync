<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landingPage->title }}</title>
    <meta name="description" content="{{ $landingPage->sub_heading }}">
    <link rel="icon" type="image/x-icon" href="/favicons/tQJvnjUyDsNhvrg0JcxpQykNeyd4sYuxdIz7f2UL.webp">

    <script src="{{ asset('js/landing_analytics.js') }}?v={{ filemtime(public_path('js/landing_analytics.js')) }}" defer></script>
    
    <!-- Swiper.js CSS and JS (Local - Bundle Version) -->
    <link rel="stylesheet" href="{{ asset('css/vendor/swiper-bundle.min.css') }}" />
    <script src="{{ asset('js/vendor/swiper-bundle.min.js') }}"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    {{-- Landing Page Color Variables --}}
    @php
        $primaryColor = $landingPage->primary_color ?? '#007bff';
        $secondaryColor = $landingPage->secondary_color ?? '#6c757d';
        $accentColor = $landingPage->accent_color ?? '#28a745';
        $orderButtonColor = $landingPage->order_button_color ?? '#dc3545';

        // Helper function to convert English numbers to Bangla numerals
        function englishToBangla($number)
        {
            $banglaNumbers = [
                '0' => '০',
                '1' => '১',
                '2' => '২',
                '3' => '৩',
                '4' => '৪',
                '5' => '৫',
                '6' => '৬',
                '7' => '৭',
                '8' => '৮',
                '9' => '৯',
            ];
            return str_replace(array_keys($banglaNumbers), array_values($banglaNumbers), $number);
        }
    @endphp

    <style>
        .landing-page-theme {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --order-button-color: {{ $orderButtonColor }};
        }

        /* Dynamic Badge Width Variables */
        .badge-width-vars {
            --badge-desktop-width: {{ $heroSection->badge_desktop_width ?? 100 }}px;
            --badge-mobile-width: {{ $heroSection->badge_mobile_width ?? 80 }}px;
        }

        /* Discount Information Styles */
        .discount-info {
            display: flex;
            align-items: center;
            gap: 2px;
            margin-bottom: 0px;
        }

        .regular-price {
            color: #000000;
            font-size: 15px;
        }

        .strikethrough {
            text-decoration: line-through;
            font-family: auto !important;
        }

        .discount-badge {
            background-color: #e74c3c;
            color: white;
            padding: 1px 5px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        /* Alert Styles */
        .alert {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            margin: 5px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .blink-alert {
            background: #dc3545;
            color: white;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.7; }
        }

        .alert:hover {
            transform: scale(1.05);
        }
    </style>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: inherit;
        }

        /*section style */
        .natural-products-showcase {
            padding: 50px 20px;
            background-color: #ffffff;
        }

        .showcase-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 40px;
        }

        /* content styling */
        .product-content {
            flex: 1;
            min-width: 300px;
        }

        .organic-badge {
            display: inline-block;
            background-color: {{ $landingPage->badge_color ?? 'var(--primary-color)' }};
            color: #fff !important;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            line-height: 1.3;
            font-size: 36px;
            color: #00000;
        }


        .cta-global {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            font-family: inherit;
        }

        /* Hero Video Container Styles */
        .hero-video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
            /* 16:9 aspect ratio */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 4px solid var(--primary-color)
        }

        .hero-video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Responsive Hero Video */
        @media (max-width: 1024px) and (min-width: 769px) {
            .hero-video-container {
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
        }

        @media (max-width: 768px) {
            .hero-video-container {
                border-radius: 15px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 480px) {
            .hero-video-container {
                border-radius: 15px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
        }

        @media (max-width: 360px) {
            .hero-video-container {
                border-radius: 15px;
                box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            }
        }

        /* Unmute Overlay Styles */
        .unmute-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: opacity 0.3s ease;
        }

        .unmute-overlay:hover {
            background: rgba(0, 0, 0, 0.4);
        }

        .unmute-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 4px 15px;
            border-radius: 60px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-family: inherit;
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .unmute-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .unmute-button:hover::before {
            left: 100%;
        }

        .unmute-button:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .unmute-icon {
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .unmute-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .unmute-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .unmute-subtitle {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Hide overlay when video is unmuted */
        .unmute-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .product-description {
            font-size: 24px;
            color: var(--secondary-color);
            line-height: 1.8;
            margin-bottom: 25px;
            font-family: inherit;
            font-weight: 700;
        }

        .product-benefits {
            list-style: none;
            margin-bottom: 30px;
        }

        .product-benefits li {
            margin-bottom: 10px;
            color: #6d4c41;
            display: flex;
            align-items: center;
            font-family: inherit;
        }

        .check-icon {
            color: var(--primary-color);
            margin-right: 10px;
            font-weight: bold;
        }

        /* বাটন স্টাইলিং */
        .product-cta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 12px 100px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .primary-btn {
            background-color: var(--accent-color);
            color: white;
        }

        .primary-btn:hover {
            background-color: #641e1d;
        }

        .outline-btn {
            border: 2px solid #6d4c41;
            color: #6d4c41;
        }

        .outline-btn:hover {
            background-color: #6d4c41;
            color: white;
        }

        /* ইমেজ স্টাইলিং */
        .product-image {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .product-image img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .best-seller-badge {
            position: absolute;
            bottom: 20px;
            right: -10px;
            background-color: #ff6f00;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            font-weight: bold;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
            font-family: inherit;
            z-index: 10;
        }

        /* Customer Trust Indicators Styling */
        .customer-trust-indicators {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
            padding: 18px 20px;
            background-color: #f5883414;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 4px 12px rgba(255, 160, 0, 0.1);
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .trust-icon {
            font-size: 22px;
            color: var(--primary-color);
            background-color: #fff;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .trust-item:hover .trust-icon {
            transform: scale(1.1);
        }

        .trust-info {
            display: flex;
            flex-direction: column;
        }

        .trust-value {
            font-weight: bold;
            font-size: 18px;
            color: var(--secondary-color);
            font-family: inherit;
        }

        .trust-label {
            font-size: 14px;
            color: var(--secondary-color);
            font-family: inherit;
        }

        /* Features Section */
        .honey-features {
            padding: 30px 20px;
            background-color: #fff8e1;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-title {
            text-align: center;
            font-size: 36px;
            color: #5d4037;
            margin-bottom: 20px;
            font-family: inherit;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .feature-name {
            font-size: 22px;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-family: inherit;
        }

        .feature-desc {
            color: var(--secondary-color);
            line-height: 1.6;
            font-family: inherit;
        }

        .product-cta2 {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
        }



        /* Order Form Section */
        .order-form-section {
            padding: 30px 20px;
            background-color: #ffffff;
        }

        .order-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .order-title {
            text-align: center;
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 40px;
            font-family: inherit;
        }

        .order-form-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background-color: #fff;
            border-radius: 0px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .order-form-left,
        .order-form-right {
            flex: 1;
            min-width: 300px;
            padding: 30px;
        }

        .order-form-left {
            background-color: #f9f9f9;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #5d4037;
            font-weight: 500;
            font-family: inherit;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f0f7f0;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #4caf50;
            outline: none;
        }

        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            color: #757575;
            font-family: inherit;
        }

        .radio-group {
            margin-top: 10px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .radio-item input[type="radio"] {
            margin-right: 10px;
            accent-color: #4caf50;
        }

        .radio-item label {
            margin-bottom: 0;
            font-weight: normal;
        }

        .order-submit-btn {
            display: block;
            width: 98%;
            padding: 15px;
            background-color: {{ $orderButtonColor ?? 'var(--primary-color)' }};
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: inherit;
            margin-top: 30px;
        }

        .order-submit-btn:hover {
            background-color: var(--secondary-color);
        }

        /* Product Summary Styling */
        .product-summary {
            background-color: #fff;
            border-radius: 8px;
        }

        .summary-title {
            font-size: 24px;
            color: var(--secondary-color);
            font-family: inherit;
        }
        .summary-title-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .quantity-selector {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: inherit;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .qty-btn:hover {
            background-color: #e0e0e0;
        }

        .minus-btn {
            border-radius: 4px 0 0 4px;
        }

        .plus-btn {
            border-radius: 0 4px 4px 0;
        }

        #quantity {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
            text-align: center;
            font-size: 16px;
        }

        #quantity::-webkit-inner-spin-button,
        #quantity::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .product-price {
            font-weight: bold;
            color: #e53935;
            font-size: 18px;
        }

        .enitre-item {
            border: 1px solid #0a5b38;
            padding: 7px;
            border-radius: 10px;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 0px 0;
        }

        .item-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .item-details {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: inherit;
        }

        .item-name {
            font-weight: 500;
        }

        .item-total {
            font-weight: 700 !important;
            font-size: 17px !important;
        }

        .order-summary {
            padding: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-family: auto;
        }

        .subtotal {
            font-weight: bold;
            font-size: 18px;
            padding-top: 10px;
            margin-top: 10px;
            border-top: 1px dashed #eee;
        }

        .payment-method {
            margin-top: 20px;
        }

        .payment-method h4 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #5d4037;
            font-family: inherit;
        }

        .payment-option {
            display: flex;
            flex-direction: column;
            padding: 4px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #f9f9f9;
            align-items: baseline;
            margin-bottom: 15px;
        }

        .payment-option label {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .payment-option input {
            margin-right: 10px;
            accent-color: #4caf50;
        }

        .payment-note {
            margin-left: 25px;
            font-size: 14px;
            color: #757575;
            font-family: inherit;
            margin-top: -10px;
        }

        /* Shipping Area Styles */
        .shipping-area {
            background-color: #fff;
            border-radius: 8px;
            padding: 5px 0px;
            margin-bottom: 10px;
        }

        .shipping-area-title {
            font-size: 16px;
            font-weight: 600;
            color: #5d4037;
            margin-bottom: 15px;
        }

        .shipping-option {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #e0e0e0;
            padding: 10px 10px;
            border-radius: 9px;
            cursor: pointer;
        }

        .shipping-option-left {
            display: flex;
            align-items: center;
        }

        .shipping-option input[type="radio"] {
            margin-right: 10px;
        }

        .shipping-option label {
            font-size: 15px;
            color: #6d4c41;
            margin-bottom: 0;
            cursor: pointer;
        }

        .shipping-price {
            font-weight: 500;
            font-family: auto !important;
        }

        /* Free Shipping Style */
        .free-shipping {
            color: #10b981;
            font-weight: 600;
        }

        /* Payment Gateway Charge Rows */
        #bkashChargeRow,
        #nagadChargeRow,
        #rocketChargeRow {
            display: none;
        }

        tr#bkashChargeRow td:last-child,
        tr#nagadChargeRow td:last-child,
        tr#rocketChargeRow td:last-child {
            text-align: right;
        }

        button.order-submit-btn {
            z-index: 10;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .order-form-wrapper {
                gap: 0;
            }

            .honey-features {
                padding: 20px 10px;
            }

            .product-row {
                flex-direction: column;
                gap: 0px;
            }

            button.order-submit-btn {
                position: fixed;
                bottom: 5px;
                left: 5px;
            }

            .product-content,
            .product-image {
                width: 100%;
                text-align: left;
            }

            .section-title,
            .features-title {
                font-size: 28px;
            }

            .features-title {
                margin-bottom: 15px;
            }

            .order-title {
                margin-bottom: 10px;
            }

            .customer-trust-indicators {
                padding: 10px;
                flex-wrap: wrap;
                justify-content: center;
                gap: 0px;
            }

            .organic-badge,
            .section-title {
                margin-bottom: 10px;
            }

            .trust-item {
                min-width: 100px;
                justify-content: flex-start;
                margin-bottom: 5px;
                width: auto;
                flex: 0 0 auto;
            }

            .order-form-wrapper {
                /* flex-direction: column-reverse; */
            }

            .order-form-left,
            .order-form-right {
                width: 100%;
            }

            .order-form-right {
                border: 2px solid #ff6925;
                border-radius: 10px;
            }

            .order-title {
                font-size: 24px;
            }


        }

        @media (max-width: 480px) {
            .natural-products-showcase {
                padding: 15px 15px;
            }

            .product-content {
                min-width: 100%;
            }

            .product-image {
                min-width: 100%;
                margin-top: 20px;
            }

            .btn {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }

            .product-cta {
                flex-direction: column;
                gap: 10px;
                margin-bottom: -10px;
            }

            .section-title {
                font-size: 24px;
            }

            .organic-badge {
                font-size: 14px;
            }

            .customer-trust-indicators {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                gap: 0px;
                justify-content: space-around;
            }

            .trust-item {
                width: auto;
                flex: 0 0 auto;
                margin-bottom: 5px;
            }

            .trust-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .trust-info {
                min-width: 0;
            }

            .trust-value {
                font-size: 16px;
            }

            .trust-label {
                font-size: 12px;
            }

            .order-form-section {
                padding: 20px 10px;
            }

            .order-form-left,
            .order-form-right {
                padding: 10px;
            }

            .item-details {
                flex-wrap: wrap;
            }

            .item-name {
                width: 100%;
                margin-bottom: 5px;
                font-style: 20px !important;
            }
        }

        /* .shipping-price {
            display: none;
        } */
    </style>

    {{-- Font preloading is now handled dynamically in partials/font-loader.blade.php --}}
    @include('partials.font-loader')


    {{-- Time box --}}
    <style>
        .countdown-section {
            background: var(--primary-color);
            padding: 40px 20px;
            border-radius: 0px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            margin: 0 auto;
        }

        .countdown-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .time-box {
            background: rgba(255, 255, 255, 0.1);
            border: 3px solid rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 5px;
            min-width: 120px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .time-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .time-number {
            color: white;
            font-size: 48px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .time-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .countdown-section {
                width: 100%;
                padding: 25px 12px;
                border-radius: 0px;
            }

            .countdown-title {
                font-size: 24px;
                margin-bottom: 6px;
            }

            .time-box {
                min-width: 100px;
                padding: 15px;
            }

            .time-number {
                font-size: 36px;
                margin-bottom: 0px;
            }

            .time-label {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .countdown-container {
                gap: 15px;
            }

            .time-box {
                min-width: 80px;
                padding: 0px;
            }

            .time-number {
                font-size: 30px;
            }

            .time-label {
                font-size: 12px;
            }
        }
    </style>
</head>

<body class="landing-page-theme">

    {{-- Header Section --}}
    @php
        $headerSection = $landingPage->sections->where('section_type', 'header')->where('status', 1)->first();
    @endphp

    @if ($headerSection)
        <section id="mainheader">
            <div class="header-container" style="justify-content: {{ $headerSection->header_alignment ?? 'center' }};">
                @if ($headerSection->header_logo)
                    <div class="header-logo">
                        <a href="{{ url('/') }}" class="header-logo-link" title="Go to Homepage">
                            <img src="{{ asset($headerSection->header_logo) }}"
                                alt="{{ $headerSection->header_logo_alt ?? $landingPage->title }}"
                                style="width: {{ $headerSection->header_desktop_logo_width ?? 200 }}px; max-width: 100%;"
                                loading="lazy">
                        </a>
                    </div>
                @endif

                <div class="header-actions">
                    @if ($headerSection->header_button1_active)
                        <a href="{{ $headerSection->header_button1_url ?? 'tel:01717171717' }}" class="header-btn btn1"
                            style="background-color: {{ $headerSection->header_button1_color ?? '#007bff' }}; color: {{ $headerSection->header_button1_text_color ?? '#ffffff' }};">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                            {{ $headerSection->header_button1_text ?? 'Call Now' }}
                        </a>
                    @endif

                    @if ($headerSection->header_button2_active)
                        <a href="{{ $headerSection->header_button2_url ?? '#order-section' }}"
                            class="header-btn btn2 primary-btn"
                            style="background-color: {{ $headerSection->header_button2_color ?? '#dc3545' }}; color: {{ $headerSection->header_button2_text_color ?? '#ffffff' }};">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                                <path
                                    d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path
                                    d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                            {{ $headerSection->header_button2_text ?? 'এখনই কিনুন' }}
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Hero Section -->
    @php
        $heroSections = $landingPage->sections()->where('section_type', 'hero')->get();
    @endphp
    @foreach ($heroSections as $heroSection)
        <section class="natural-products-showcase">
            <div class="showcase-container">
                <div class="product-row">
                    <div class="product-content">
                        @if ($heroSection->badge_image || $heroSection->badge_text)
                            <div class="badge-container badge-width-vars">
                                @if ($heroSection->badge_image)
                                    <a href="{{ url('/') }}" class="badge-image-link" title="Go to Homepage">
                                        <img src="{{ asset($heroSection->badge_image) }}"
                                            alt="{{ $heroSection->badge_text ?? 'Badge' }}" class="badge-image"
                                            style="max-height: 40px;">
                                    </a>
                                @endif
                                @if ($heroSection->badge_text)
                                    <span class="organic-badge"
                                        style="background-color: {{ $heroSection->badge_color ?? '#ffd54f' }};">{{ $heroSection->badge_text }}</span>
                                @endif
                            </div>
                        @endif
                        <h2 class="section-title">{{ $heroSection->heading ?? $landingPage->title }}</h2>
                        @if ($heroSection->sub_heading)
                            <p class="product-description">{{ $heroSection->sub_heading }}</p>
                        @endif
                        @if ($heroSection->primary_text)
                            <div class="product-description">{!! $heroSection->primary_text !!}</div>
                        @endif

                        <!-- Customer Trust Indicators -->
                        @if ($heroSection->show_trust_indicators && $heroSection->trust_indicators && count($heroSection->trust_indicators) > 0)
                            <div class="customer-trust-indicators">
                                @foreach ($heroSection->trust_indicators as $indicator)
                                    <div class="trust-item">
                                        <div class="trust-icon">{{ $indicator['icon'] ?? '⭐' }}</div>
                                        <div class="trust-info">
                                            <div class="trust-value">{{ $indicator['text1'] ?? '' }}</div>
                                            <div class="trust-label">{{ $indicator['text2'] ?? '' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="product-cta">
                            <a href="{{ $landingPage->order_button_url ?? '#order-section' }}" class="btn primary-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="margin-right: 8px; vertical-align: middle;">
                                    <path
                                        d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ $landingPage->order_button_text ?? 'এখনই কিনুন' }}
                            </a>
                        </div>
                    </div>
                    <div class="product-image">
                        @if ($heroSection->hero_video_url)
                            @php
                                $videoUrl = $heroSection->hero_video_url;
                                $embedUrl = '';

                                // Convert various YouTube URL formats to embed URL
                                if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                    $videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                    $videoId = substr($videoUrl, strpos($videoUrl, 'youtu.be/') + 9);
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                } elseif (strpos($videoUrl, 'youtube.com/shorts/') !== false) {
                                    $videoId = substr($videoUrl, strpos($videoUrl, 'shorts/') + 7);
                                    // Remove any query parameters from video ID
                                    if (strpos($videoId, '?') !== false) {
                                        $videoId = substr($videoId, 0, strpos($videoId, '?'));
                                    }
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                                    $embedUrl = $videoUrl;
                                } else {
                                    // If it's already an embed URL or unknown format, use as is
                                    $embedUrl = $videoUrl;
                                }
                            @endphp
                            <div class="hero-video-container">
                                <iframe
                                    data-src="{{ $embedUrl }}?autoplay=1&mute=1&controls=0&rel=0&modestbranding=1&showinfo=0&loop=1&playlist={{ substr($embedUrl, strpos($embedUrl, 'embed/') + 6) }}&enablejsapi=1&origin={{ url('/') }}&disablekb=1&fs=0&iv_load_policy=3"
                                    allowfullscreen allow="autoplay; encrypted-media" style="pointer-events: auto;"
                                    id="hero-video-iframe" class="lazy-video">
                                </iframe>
                                <div class="unmute-overlay" onclick="unmuteVideo('hero-video-iframe')">
                                    <div class="unmute-button">
                                        <div class="unmute-icon">🔇</div>
                                        <div class="unmute-text">
                                            <span class="unmute-title">Click to Unmute</span>
                                            <span class="unmute-subtitle">Enable Sound 🔊</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($heroSection->hero_image)
                            <img src="{{ asset($heroSection->hero_image) }}"
                                alt="{{ $heroSection->hero_image_alt ?? ($heroSection->heading ?? $landingPage->title) }}"
                                loading="lazy">
                        @else
                            <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}"
                                loading="lazy">
                        @endif
                        <div class="best-seller-badge">সেরা পণ্য!</div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    {{-- Image Carousel Sections --}}
    @php
        $imageCarouselSections = $landingPage->sections->where('section_type', 'image_carousel');
    @endphp
    @if ($imageCarouselSections->count() > 0)
        <style>
            .image-carousel-section {
                padding: 40px 12px;
                background: #f8fafc;
            }

            .image-carousel-container {
                max-width: 1180px;
                margin: 0 auto;
            }

            .image-carousel-header {
                text-align: center;
                margin-bottom: 18px;
            }

            .image-carousel-title {
                font-size: 30px;
                font-weight: 800;
                color: var(--primary-color);
                letter-spacing: -0.5px;
            }

            .image-carousel-description {
                margin-top: 6px;
                color: #475467;
                font-size: 16px;
                line-height: 1.6;
            }

            .image-carousel {
                position: relative;
            }

            .image-carousel .swiper-slide {
                display: flex;
                justify-content: center;
                flex: 0 0 auto;
            }

            .carousel-card {
                background: #ffffff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                border: 1px solid rgba(0, 123, 255, 0.06);
                transition: transform 0.25s ease, box-shadow 0.25s ease;
                position: relative;
            }

            .carousel-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12);
            }

            .carousel-card img {
                width: 100%;
                height: auto;
                display: block;
            }

            .carousel-caption {
                padding: 10px 12px;
                font-weight: 700;
                color: #1f2937;
                background: linear-gradient(135deg, rgba(0, 123, 255, 0.08), rgba(220, 53, 69, 0.08));
                border-top: 1px solid rgba(0, 0, 0, 0.05);
            }

            .image-carousel .swiper-button-next,
            .image-carousel .swiper-button-prev {
                color: var(--primary-color);
                background: #ffffff;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(0, 0, 0, 0.06);
            }

            .image-carousel .swiper-button-next::after,
            .image-carousel .swiper-button-prev::after {
                font-size: 16px;
                font-weight: 700;
            }

            .image-carousel .swiper-pagination-bullet {
                background: rgba(0, 123, 255, 0.4);
            }

            .image-carousel .swiper-pagination-bullet-active {
                background: var(--primary-color);
            }

            /* Fallback layout if Swiper fails */
            .image-carousel:not(.swiper-initialized) .swiper-wrapper {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 14px;
            }

            @media (min-width: 1200px) {
                .image-carousel:not(.swiper-initialized) .swiper-wrapper {
                grid-template-columns: repeat(5, 1fr);
            }
        }

            @media (max-width: 768px) {
                .image-carousel-title {
                    font-size: 24px;
                }

                .carousel-card {
                    border-radius: 10px;
                }
            }

            /* Thumbnails */
            .image-carousel-thumbs {
                margin-top: 12px;
                padding: 6px 4px;
                gap: 3px;
            }

            .image-carousel-thumbs .swiper-slide {
                width: auto;
                flex: 0 0 auto;
                opacity: 0.6;
                transition: opacity 0.2s ease, transform 0.2s ease;
            }

            .image-carousel-thumbs .swiper-slide-thumb-active {
                opacity: 1;
                transform: translateY(-2px);
            }

            .thumb-card {
                width: 80px;
                height: 60px;
                border-radius: 8px;
                overflow: hidden;
                border: 2px solid rgba(0, 123, 255, 0.12);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            }

            .thumb-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* Zoom button */
            .carousel-zoom-btn {
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(0, 0, 0, 0.55);
                color: #fff;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                text-decoration: none;
                transition: background 0.2s ease, transform 0.2s ease;
                border: 1px solid rgba(255, 255, 255, 0.25);
                cursor: pointer;
            }

            .carousel-zoom-btn:hover {
                background: rgba(0, 0, 0, 0.7);
                transform: translateY(-1px);
            }

            /* Lightbox */
            .lightbox-open {
                overflow: hidden;
            }

            .lightbox-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.75);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                padding: 20px;
            }

            .lightbox-backdrop.active {
                display: flex;
            }

            .lightbox-content {
                position: relative;
                max-width: 90vw;
                max-height: 90vh;
                background: #0b0b0b;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
            }

            .lightbox-content img {
                max-width: 100%;
                max-height: 90vh;
                display: block;
                object-fit: contain;
            }

            .lightbox-close {
                position: absolute;
                top: 8px;
                right: 8px;
                background: rgba(0, 0, 0, 0.7);
                color: #fff;
                border: none;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 16px;
            }

            .lightbox-close:hover {
                background: rgba(0, 0, 0, 0.85);
            }

            @media (min-width: 1024px) {
                .image-carousel-thumbs {
                    display: none !important;
                }
            }
        </style>

        @foreach ($imageCarouselSections as $carouselSection)
            @php
                $carouselSlides = collect($carouselSection->carousel_images ?? [])->filter(fn($slide) => !empty($slide['image']))->values();
            @endphp
            @if ($carouselSlides->count() > 0)
                <section class="image-carousel-section">
                    <div class="image-carousel-container">
                        <div class="image-carousel-header">
                            @if ($carouselSection->title)
                                <h2 class="image-carousel-title">{{ $carouselSection->title }}</h2>
                            @endif
                            @if ($carouselSection->description)
                                <p class="image-carousel-description">{{ $carouselSection->description }}</p>
                            @endif
                        </div>
                        <div class="swiper image-carousel" id="image-carousel-{{ $carouselSection->id }}" data-carousel-id="{{ $carouselSection->id }}">
                            <div class="swiper-wrapper">
                                @foreach ($carouselSlides as $slide)
                                    <div class="swiper-slide">
                                        <div class="carousel-card">
                                            <img src="{{ asset($slide['image']) }}"
                                                alt="{{ $slide['caption'] ?? $carouselSection->title ?? 'Gallery image' }}"
                                                loading="lazy">
                                            <button type="button" class="carousel-zoom-btn" data-image="{{ asset($slide['image']) }}" aria-label="View full size">
                                                ⤢
                                            </button>
                                            @if (!empty($slide['caption']))
                                                <div class="carousel-caption">{{ $slide['caption'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>

                        <div class="swiper image-carousel-thumbs" data-carousel-id="{{ $carouselSection->id }}">
                            <div class="swiper-wrapper">
                                @foreach ($carouselSlides as $slide)
                                    <div class="swiper-slide">
                                        <div class="thumb-card">
                                            <img src="{{ asset($slide['image']) }}" alt="thumb" loading="lazy">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif

    @foreach ($landingPage->sections as $section)
        @if ($section->section_type === 'countdown' && $section->status)
            <section class="countdown-section">
                <h2 class="countdown-title">{{ $section->countdown_title ?? 'Offer ends soon!' }}</h2>
                <div class="countdown-container">
                    <div class="time-box">
                        <span class="time-number" id="hours-{{ $section->id }}">০০</span>
                        <span class="time-label">ঘন্টা</span>
                    </div>
                    <div class="time-box">
                        <span class="time-number" id="minutes-{{ $section->id }}">০০</span>
                        <span class="time-label">মিনিট</span>
                    </div>
                    <div class="time-box">
                        <span class="time-number" id="seconds-{{ $section->id }}">০০</span>
                        <span class="time-label">সেকেন্ড</span>
                    </div>
                </div>
            </section>
            <script>
                (function() {
                    // Function to convert English numbers to Bangla numbers
                    function toBanglaNumber(num) {
                        const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                        return num.toString().split('').map(digit => {
                            return banglaNumbers[parseInt(digit)] || digit;
                        }).join('');
                    }

                    let countdownHours = {{ $section->countdown_hours ?? 4 }};
                    let repeat = {{ $section->countdown_repeat ? 'true' : 'false' }};

                    // Use localStorage to persist countdown end time and settings
                    let storageKey = 'countdown_end_{{ $section->id }}';
                    let settingsKey = 'countdown_settings_{{ $section->id }}';
                    let targetDate;

                    // Check if admin settings have changed
                    let currentSettings = JSON.stringify({
                        hours: countdownHours,
                        repeat: repeat
                    });
                    let storedSettings = localStorage.getItem(settingsKey);
                    let settingsChanged = storedSettings !== currentSettings;

                    // Check if we have a stored end time
                    let storedEndTime = localStorage.getItem(storageKey);

                    if (storedEndTime && !settingsChanged) {
                        // Use existing countdown if settings haven't changed
                        targetDate = parseInt(storedEndTime);
                        // If the stored time has passed and repeat is enabled, create a new countdown
                        if (targetDate < new Date().getTime() && repeat) {
                            targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
                            localStorage.setItem(storageKey, targetDate.toString());
                        }
                    } else {
                        // Settings changed or first time: create new countdown
                        targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
                        localStorage.setItem(storageKey, targetDate.toString());
                        localStorage.setItem(settingsKey, currentSettings);
                    }

                    function updateCountdown_{{ $section->id }}() {
                        const now = new Date().getTime();
                        let distance = targetDate - now;

                        if (distance > 0) {
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            // Convert to Bangla numbers and pad with zeros
                            const banglaHours = toBanglaNumber(hours.toString().padStart(2, '0'));
                            const banglaMinutes = toBanglaNumber(minutes.toString().padStart(2, '0'));
                            const banglaSeconds = toBanglaNumber(seconds.toString().padStart(2, '0'));

                            document.getElementById('hours-{{ $section->id }}').textContent = banglaHours;
                            document.getElementById('minutes-{{ $section->id }}').textContent = banglaMinutes;
                            document.getElementById('seconds-{{ $section->id }}').textContent = banglaSeconds;
                        } else {
                            // Show zeros in Bangla when countdown ends
                            document.getElementById('hours-{{ $section->id }}').textContent = '০০';
                            document.getElementById('minutes-{{ $section->id }}').textContent = '০০';
                            document.getElementById('seconds-{{ $section->id }}').textContent = '০০';

                            if (repeat) {
                                // Create new countdown and store it
                                targetDate = new Date().getTime() + countdownHours * 60 * 60 * 1000;
                                localStorage.setItem(storageKey, targetDate.toString());
                            } else {
                                // Remove from localStorage if not repeating
                                localStorage.removeItem(storageKey);
                            }
                        }
                    }
                    setInterval(updateCountdown_{{ $section->id }}, 1000);
                    updateCountdown_{{ $section->id }}();
                })
                ();
            </script>
        @endif
    @endforeach

    {{-- Offer highlight --}}
    <style>
        .pricing-section {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            /* grid-template-columns: 1fr 1fr; */
            gap: 20px;
            padding: 30px 10px;
            ;
        }

        .pricing-card {
            background: linear-gradient(135deg, #1D8758, #F58834);
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .pricing-card:hover::before {
            opacity: 1;
            animation: shine 1s ease-in-out;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .original-price {
            color: #ffffff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
            background: rgb(243 98 98);
            border-radius: 8px;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }

        .strike-through {
            color: #f7ff0e;
            position: relative;
            display: inline-block;
            font-weight: bold;
        }

        .strike-through svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .strike-through svg path {
            stroke: #ad000f;
            stroke-width: 15.5;
            fill: none;
            stroke-linecap: round;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: drawCross 2s ease-in-out infinite;
        }

        .strike-through svg path:nth-child(2) {
            animation-delay: 0.3s;
        }

        @keyframes drawCross {
            0% {
                stroke-dashoffset: 1000;
            }

            50% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: -1000;
            }
        }

        /* Hover effect for cross-out */
        /* .pricing-card:hover .strike-through svg path {
            stroke: #ff0000;
            stroke-width: 25;
            animation-duration: 0.8s;
        } */

        .current-price {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 0px;
            text-align: center;
            padding: 15px;
            background: rgba(255, 215, 0, 0.15);
            border-radius: 10px;
            border: 2px solid rgba(255, 215, 0, 0.4);
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2);
        }

        .current-price .highlight {
            color: #f7ff0e;
            font-size: 36px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            font-weight: 900;
        }

        .highlight-underline {
            position: relative;
            display: inline-block;
            font-weight: bold;
        }

        .highlight-underline svg {
            position: absolute;
            bottom: 2px;
            left: 0;
            width: 100%;
            height: 17px;
            z-index: 1;
            pointer-events: none;
        }

        .highlight-underline svg path {
            stroke: #ffd700;
            stroke-width: 30;
            fill: none;
            stroke-linecap: round;
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: drawUnderline 3s ease-in-out infinite;
        }

        .highlight-underline svg path:nth-child(2) {
            animation-delay: 0.5s;
        }

        /* Summernote content styling */
        .product-description {
            line-height: 1.4;
        }

        .product-description h1,
        .product-description h2,
        .product-description h3,
        .product-description h4,
        .product-description h5,
        .product-description h6 {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-description p {
            margin-bottom: 0rem;
        }

        .product-description ul,
        .product-description ol {
            margin-bottom: 0.5rem;
            padding-left: 1rem;
        }

        .product-description li {
            margin-bottom: 0.25rem;
        }

        .product-description strong,
        .product-description b {
            font-weight: 600;
        }

        .product-description em,
        .product-description i {
            font-style: italic;
        }

        .product-description a {
            color: #007bff;
            text-decoration: underline;
        }

        .product-description a:hover {
            color: #0056b3;
        }

        /* Header Section Styles */
        #mainheader {
            background: #fff;
            padding: 4px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .header-logo img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
            transition: all 0.2s ease;
        }

        .header-logo-link {
            display: inline-block;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .header-logo-link:hover img {
            transform: scale(1.02);
            filter: brightness(1.1);
        }

        .header-logo-link:hover {
            opacity: 0.9;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .header-btn {
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 190px;
        }

        .header-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .header-btn.btn1 {
            background-color: #007bff;
        }

        .header-btn.btn2 {
            background-color: #dc3545;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .header-container {
                justify-content: space-between !important;
                padding: 0 5px;
            }

            .header-logo img {
                max-height: 50px;
                width: {{ $headerSection->header_mobile_logo_width ?? 150 }}px !important;
            }

            .header-logo-link:hover img {
                transform: none;
                filter: none;
            }

            .header-actions {
                gap: 10px;
            }

            .header-btn {
                padding: 5px;
                min-width: 110px;
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .header-container {
                flex-direction: row;
                gap: 10px;
                text-align: center;
                padding: 0px 5px;
            }

            .footer-landing .header-container {
                flex-direction: row;
                gap: 5px;
                text-align: center;
            }

            .header-actions {
                flex-direction: row;
                gap: 8px;
            }

            .header-btn {
                width: 100%;
                min-width: 110px;
                max-width: 200px;
            }
        }

        @keyframes drawUnderline {
            0% {
                stroke-dashoffset: 1000;
            }

            50% {
                stroke-dashoffset: 0;
            }

            100% {
                stroke-dashoffset: -1000;
            }
        }

        /* Badge Image Styling */
        .badge-image {
            display: inline-block;
            vertical-align: middle;
            border-radius: 4px;
            transition: transform 0.2s ease;
            max-height: 40px;
            width: var(--badge-desktop-width, 100px);
        }

        .badge-image-link {
            display: inline-block;
            margin-right: 15px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .badge-image-link:hover {
            transform: scale(1.05);
        }

        /* Badge Container Styling */
        .badge-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 0px;
        }

        .organic-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: white;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Mobile Responsive for Badges */
        @media (max-width: 768px) {
            .badge-container {
                flex-direction: column;
                align-items: center;
                gap: 10px;
                justify-content: center;
            }

            .badge-image-link {
                margin-right: 0;
                margin-bottom: 5px;
            }

            .badge-image {
                width: var(--badge-mobile-width, 80px) !important;
            }
        }

        /* Hover effect for underline */
        /* .pricing-card:hover .highlight-underline svg path {
            stroke: #ffff00;
            stroke-width: 2.5;
            animation-duration: 1.2s;
        } */

        /* Call to Action Section Styles */
        .cta-section {
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4) !important;
        }

        .cta-button:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .cta-title {
                font-size: 22px !important;
            }

            .cta-subtitle {
                font-size: 16px !important;
            }

            .cta-button {
                font-size: 16px !important;
                padding: 8px 24px !important;
            }
        }

        .delivery-note {
            color: #ffd700;
            font-size: 21px;
            font-weight: 600;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 8px;
            border: 2px solid rgba(255, 215, 0, 0.3);
        }

        /* Mobile responsive */
        @media (max-width: 768px) {

            .pricing-section {
                grid-template-columns: 1fr;
                padding: 30px 20px !important;
                gap: 10px;
                max-width: 100%;
            }

            .pricing-card {
                padding: 30px 20px;
                border-radius: 0px
            }

            .original-price {
                font-size: 24px;
            }

            .current-price {
                font-size: 28px;
            }

            .current-price .highlight {
                font-size: 32px;
            }

            .delivery-note {
                font-size: 18px;
                padding: 8px;
                font-weight: 700;
            }
        }

        @media (max-width: 480px) {
            .pricing-card {
                padding: 25px 15px;
            }

            .original-price {
                font-size: 20px;
            }

            .current-price {
                font-size: 21px;
            }

            .current-price .highlight {
                font-size: 28px;
            }

            .delivery-note {
                font-size: 18px;
                padding: 8px;
                font-weight: 700;
            }
        }

        /* Bangla Number Conversion Styles */
        .bangla-number {
            font-family: inherit;
        }
    </style>
    @foreach ($landingPage->sections()->where('section_type', 'pricing')->get() as $pricingSection)
        @if (
            $pricingSection->pricing_variants &&
                is_array($pricingSection->pricing_variants) &&
                count($pricingSection->pricing_variants) > 0)
            <section class="pricing-section">
                {{-- <div class="pricing-container">
                    <h2 class="pricing-title">{{ $pricingSection->pricing_title ?? 'পণ্যের মূল্য' }}</h2>
                    @if ($pricingSection->description)
                        <p class="pricing-description">{{ $pricingSection->description }}</p>
                    @endif
                </div> --}}
                <!-- First Pricing Card -->
                @foreach ($pricingSection->pricing_variants as $variant)
                    <div class="pricing-card">
                        <div class="original-price">
                            {{ $variant['weight'] ?? '' }} রেগুলার মূল্য <span class="strike-through">
                                <span class="bangla-number">{{ number_format($variant['regular_price'] ?? 0) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150"
                                    preserveAspectRatio="none">
                                    <path d="M497.4,23.9C301.6,40,155.9,80.6,4,144.4"></path>
                                    <path d="M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7">
                                </svg>
                                </svg>
                            </span> টাকা
                        </div>
                        <div class="current-price">
                            বর্তমান অফার মূল্য <span class="highlight highlight-underline">
                                <span class="bangla-number">{{ number_format($variant['offer_price'] ?? 0) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 100"
                                    preserveAspectRatio="none">
                                    <path d="M10,80 Q125,60 250,80 T490,80"></path>
                                    <path d="M10,90 Q125,70 250,90 T490,90"></path>
                                </svg>
                            </span>
                            টাকা
                        </div>
                        @if (!empty($variant['delivery_text']))
                            <div class="delivery-note">
                                {{ $variant['delivery_icon'] ?? '🚚' }} {{ $variant['delivery_text'] }}
                            </div>
                        @endif
                    </div>
                @endforeach

            </section>
        @endif
    @endforeach

    <script>
        // Function to convert English numbers to Bangla numbers
        function toBanglaNumber(num) {
            const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return num.toString().split('').map(digit => {
                return banglaNumbers[parseInt(digit)] || digit;
            }).join('');
        }

        // Convert all pricing numbers to Bangla
        function convertPricingNumbersToBangla() {
            const banglaNumberElements = document.querySelectorAll('.bangla-number');
            banglaNumberElements.forEach(element => {
                const originalText = element.textContent;
                const convertedText = originalText.replace(/\d/g, digit => {
                    const banglaNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                    return banglaNumbers[parseInt(digit)] || digit;
                });
                element.textContent = convertedText;
            });
        }

        // Run the conversion when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            convertPricingNumbersToBangla();
        });

        // Also run after any dynamic content is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', convertPricingNumbersToBangla);
        } else {
            convertPricingNumbersToBangla();
        }
    </script>

    {{-- Single Image Section --}}
    @foreach ($landingPage->sections->where('section_type', 'single_image') as $singleImageSection)
        @if ($singleImageSection->status)
            <section class="single-image-section">
                <div class="single-image-wrapper">
                    <div class="single-image-content">

                        @if ($singleImageSection->sub_heading)
                            <p class="single-image-description">
                                {{ $singleImageSection->sub_heading }}
                            </p>
                        @endif

                        @if ($singleImageSection->single_image)
                            <div class="single-image-container">
                                <img src="{{ asset($singleImageSection->single_image) }}"
                                    alt="{{ $singleImageSection->single_image_alt ?? 'Section Image' }}"
                                    class="single-image" loading="lazy">
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endforeach


    {{-- Video Sections --}}
    <style>
        /* Video Section Styles */
        .video-section {
            padding: 30px 10px;
        }

        .video-section .container {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 10px;
        }

        .video-section .section-title {
            text-align: center;
            font-size: 36px;
            color: var(--secondary-color);
            font-family: inherit;
            margin-bottom: 20px;
        }

        .video-section .video-title {
            text-align: center;
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-family: inherit;
        }

        .video-section .video-description {
            text-align: center;
            font-size: 18px;
            color: #6d4c41;
            margin-bottom: 20px;
            font-family: inherit;
            line-height: 1.6;
        }

        .video-container {
            position: relative;
            width: 100%;
            max-width: 1150px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Tablet Responsive (768px - 1024px) */
        @media (max-width: 1024px) and (min-width: 769px) {
            .video-section {
                padding: 25px 8px;
            }

            .video-section .container {
                max-width: 900px;
                padding: 0 20px;
            }

            .video-section .section-title {
                font-size: 32px;
                margin-bottom: 18px;
            }

            .video-section .video-title {
                font-size: 24px;
                margin-bottom: 8px;
            }

            .video-section .video-description {
                font-size: 16px;
                margin-bottom: 18px;
            }

            .video-container {
                max-width: 700px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
        }

        /* Mobile Responsive (up to 768px) */
        @media (max-width: 768px) {
            .video-section {
                padding: 20px 5px;
            }

            .video-section .container {
                max-width: 100%;
                padding: 0 10px;
            }

            .video-section .section-title {
                font-size: 28px;
                margin-bottom: 15px;
                line-height: 1.5rem;
            }

            .video-section .video-title {
                font-size: 22px;
                margin-bottom: 8px;
                line-height: 1.3;
            }

            .video-section .video-description {
                font-size: 16px;
                margin-bottom: 15px;
                line-height: 1.5;
                padding: 0 5px;
            }

            .video-container {
                max-width: 100%;
                border-radius: 10px;
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            }

            .video-wrapper {
                padding-bottom: 56.25%;
                /* Maintain 16:9 aspect ratio */
            }
        }

        /* Small Mobile Responsive (up to 480px) */
        @media (max-width: 480px) {
            .video-section {
                padding: 15px 3px;
            }

            .video-section .container {
                padding: 0 8px;
            }

            .video-section .section-title {
                font-size: 24px;
                margin-bottom: 12px;
                line-height: 1.5rem;
            }

            .video-section .video-title {
                font-size: 20px;
                margin-bottom: 6px;
            }

            .video-section .video-description {
                font-size: 14px;
                margin-bottom: 12px;
                padding: 0 3px;
            }

            .video-container {
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
        }

        /* Extra Small Mobile Responsive (up to 360px) */
        @media (max-width: 360px) {
            .video-section {
                padding: 12px 2px;
            }

            .video-section .container {
                padding: 0 5px;
            }

            .video-section .section-title {
                font-size: 22px;
                margin-bottom: 10px;
            }

            .video-section .video-title {
                font-size: 18px;
                margin-bottom: 5px;
            }

            .video-section .video-description {
                font-size: 13px;
                margin-bottom: 10px;
                padding: 0 2px;
            }

            .video-container {
                border-radius: 6px;
                box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            }
        }
    </style>
    @php
        $videoSections = $landingPage->sections()->where('section_type', 'video')->get();
    @endphp
    @foreach ($videoSections as $videoSection)
        @if ($videoSection->status)
            <section class="video-section">
                <div class="container">
                    @if (!empty($videoSection->video_title))
                        <h2 class="section-title">
                            {{ $videoSection->video_title }}
                        </h2>
                    @endif

                    @if (!empty($videoSection->video_description))
                        <p class="video-description">
                            {{ $videoSection->video_description }}
                        </p>
                    @endif

                    @if (!empty($videoSection->video_url))
                        @php
                            $videoUrl = $videoSection->video_url;
                            $embedUrl = '';

                            // Convert various YouTube URL formats to embed URL
                            if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                $videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
                                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                            } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                                $videoId = substr($videoUrl, strpos($videoUrl, 'youtu.be/') + 9);
                                $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                            } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                                $embedUrl = $videoUrl;
                            } else {
                                // If it's already an embed URL or unknown format, use as is
                                $embedUrl = $videoUrl;
                            }
                        @endphp
                        <div class="video-container">
                            <div class="video-wrapper">
                                <iframe
                                    data-src="{{ $embedUrl }}?autoplay=1&mute=1&controls=0&rel=0&modestbranding=1&showinfo=0&loop=1&playlist={{ substr($embedUrl, strpos($embedUrl, 'embed/') + 6) }}&enablejsapi=1&origin={{ url('/') }}&disablekb=1&fs=0&iv_load_policy=3"
                                    allowfullscreen allow="autoplay; encrypted-media" style="pointer-events: auto;"
                                    id="video-section-iframe" class="lazy-video">
                                </iframe>
                                <div class="unmute-overlay" onclick="unmuteVideo('video-section-iframe')">
                                    <div class="unmute-button">
                                        <div class="unmute-icon">🔇</div>
                                        <div class="unmute-text">
                                            <span class="unmute-title">Click to Unmute</span>
                                            <span class="unmute-subtitle">Enable Sound 🔊</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <div class="cta-global">
                <div class="product-cta">
                    <a href="#order-section" class="btn primary-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                            <path
                                d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        {{ $landingPage->order_button_text ?? 'এখনই কিনুন' }}
                    </a>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Benefit Section -->
    @php
        $allBenefitSections = $landingPage->sections()->where('section_type', 'benefit')->get();
    @endphp
    @if ($allBenefitSections->count() > 0)
        @foreach ($allBenefitSections as $benefitSection)
            @php
                $benefits = $benefitSection->benefits;
                $benefitsCount = is_array($benefits) ? count($benefits) : 0;
            @endphp

            @if ($benefitsCount > 0)
                <section class="honey-features">
                    <div class="features-container">
                        <h2 class="features-title">{{ $benefitSection->benefit_title ?? 'পণ্যের উপকারিতা' }}</h2>
                        <div class="features-grid">
                            @foreach ($benefits as $benefit)
                                <div class="feature-card">
                                    @if (!empty($benefit['icon']))
                                        <div class="feature-icon">{{ $benefit['icon'] }}</div>
                                    @endif
                                    @if (!empty($benefit['title']))
                                        <h3 class="feature-name">{{ $benefit['title'] }}</h3>
                                    @endif
                                    @if (!empty($benefit['description']))
                                        <p class="feature-desc">{{ $benefit['description'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif

    <!-- Feature List Sections -->
    <style>
        .feature-list-section {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .feature-list-header {
            background: var(--secondary-color);
            padding: 25px 30px;
            text-align: center;
        }

        .feature-list-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Pricing Section Styles */
        .pricing-section {
            padding: 50px 20px;
            background: var(--secondary-color);
            color: white;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .pricing-container {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .pricing-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .pricing-description {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .pricing-header {
            margin-bottom: 25px;
        }

        .variant-weight {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #ffd54f;
        }

        .pricing-content {
            text-align: left;
        }

        .price-info {
            margin-bottom: 20px;
        }

        .regular-price,
        .offer-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0px;
            padding: 10px 0;
        }

        .price-label {
            font-size: 16px;
            opacity: 0.9;
        }

        .price-amount {
            font-size: 18px;
            font-weight: bold;
            color: #ff6b6b;
        }

        .price-amount.highlight {
            color: #ffd54f;
            text-decoration: underline;
            font-size: 20px;
        }

        .delivery-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .delivery-icon {
            font-size: 20px;
        }

        .delivery-text {
            font-size: 14px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .pricing-card {
                padding: 20px;
            }

            .pricing-title {
                font-size: 28px;
            }

            .variant-weight {
                font-size: 20px;
            }
        }

        .feature-list-content {
            padding: 20px 25px;
        }

        .feature-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0px;
            padding: 2px 0;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .feature-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .feature-item:hover {
            background-color: #f8f9fa;
            padding-left: 10px;
            border-radius: 8px;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            margin-right: 15px;
            margin-top: 0px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .feature-text {
            flex: 1;
            color: #333;
            font-size: 18px;
            font-weight: 500;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .feature-list-section {
                max-width: 100%;
                margin: 0 10px;
            }

            .feature-list-title {
                font-size: 20px;
            }

            .feature-list-content {
                padding: 30px 20px;
            }

            .feature-text {
                font-size: 17px;
                margin-top: -5px;
            }

            .feature-item {
                margin-bottom: 0px;
                padding: 2px 0;
            }
        }

        @media (max-width: 480px) {
            .feature-list-header {
                padding: 20px 15px;
            }

            .feature-list-title {
                font-size: 18px;
            }

            .feature-list-content {
                padding: 15px 10px;
            }

            .feature-text {
                font-size: 16px;
                margin-top: -5px;
            }

            .feature-icon {
                width: 20px;
                height: 20px;
                margin-right: 12px;
                font-size: 20px;
            }
        }
    </style>
    @php
        $featureListSections = $landingPage->sections()->where('section_type', 'feature_list')->get();
        $featureListCount = $featureListSections->count();
        $allSections = $landingPage->sections;
        $allSectionsCount = $allSections->count();
    @endphp
    @foreach ($featureListSections as $featureListSection)
        @php
            $featureItems = $featureListSection->feature_items;
            $featureItemsCount = is_array($featureItems) ? count($featureItems) : 0;
        @endphp

        <section class="feature-list-section">
            <div class="feature-list-header">
                <h2 class="feature-list-title">
                    {{ $featureListSection->feature_list_title ?? 'Feature List' }}</h2>
            </div>

            <div class="feature-list-content">
                @if ($featureItems && is_array($featureItems) && $featureItemsCount > 0)
                    <ul class="feature-list">
                        @foreach ($featureItems as $item)
                            <li class="feature-item">
                                <div class="feature-icon">{{ $item['emoji'] ?? '•' }}</div>
                                <span class="feature-text">{{ $item['text'] ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                @endif
            </div>
        </section>
    @endforeach

    <!-- Customer Reviews Section -->
    @if ($landingPage->sectionsByType('testimonials')->count() > 0)
        <style>
            /* Customer Reviews Section */
            .customer-reviews {
                padding: 20px 0px;
                background-color: #ffffff;
            }

            .reviews-container {
                max-width: 1200px;
                margin: 0 auto;
            }

            .reviews-title {
                text-align: center;
                font-size: 36px;
                color: var(--secondary-color);
                margin-bottom: 20px;
                font-family: inherit;
            }

            /* Swiper Custom Styles */
            .testimonials-swiper {
                position: relative;
                padding: 15px 30px;
            }

            .testimonials-swiper .swiper-slide {
                height: auto;
            }

            .review-card {
                background-color: #fff;
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
                text-align: center;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .review-image {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                margin: 0 auto 20px;
                object-fit: cover;
            }

            .review-text {
                color: #6d4c41;
                line-height: 1.6;
                margin-bottom: 15px;
                font-family: inherit;
                flex-grow: 1;
            }

            .review-author {
                font-weight: bold;
                color: var(--secondary-color);
                font-family: inherit;
            }

            /* Custom Navigation Arrows */
            .testimonials-swiper {
                position: relative;
            }

            .testimonials-swiper .swiper-button-next,
            .testimonials-swiper .swiper-button-prev {
                background: rgba(255, 255, 255, 0.9);
                border-radius: 50%;
                width: 34px;
                height: 34px;
                color: var(--secondary-color);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .testimonials-swiper .swiper-button-next:hover,
            .testimonials-swiper .swiper-button-prev:hover {
                background: rgba(255, 255, 255, 1);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .testimonials-swiper .swiper-button-next::after,
            .testimonials-swiper .swiper-button-prev::after {
                font-size: 16px;
                font-weight: 700;
            }

            /* Tablet responsive adjustments */
            @media (max-width: 768px) {
                .testimonials-swiper {
                    padding: 10px 20px;
                }

                .review-card {
                    padding: 20px;
                }

                .reviews-title {
                    font-size: 28px;
                    margin-bottom: 10px;
                }
            }

            /* Mobile responsive adjustments */
            @media (max-width: 480px) {
                .testimonials-swiper {
                    padding: 10px 20px;
                }

                .review-card {
                    padding: 15px;
                }

                .reviews-title {
                    font-size: 19px;
                    margin-bottom: 0px;
                    background: #123257;
                    color: #ffffff;
                    padding: 10px;
                }

                .testimonials-swiper .swiper-button-next,
                .testimonials-swiper .swiper-button-prev {
                    width: 35px;
                    height: 35px;
                }

                .testimonials-swiper .swiper-button-next::after,
                .testimonials-swiper .swiper-button-prev::after {
                    font-size: 16px;
                }
            }
        </style>
        @php
            $testimonialsSection = $landingPage->sectionsByType('testimonials')->first();
        @endphp
        @if (
            $testimonialsSection &&
                is_array($testimonialsSection->testimonials) &&
                count($testimonialsSection->testimonials) > 0)
            <section class="customer-reviews">
                <div class="reviews-container">
                    <h2 class="reviews-title">
                        {{ $testimonialsSection->testimonials_title ?? 'গ্রাহক টেস্টিমোনিয়াল' }}
                    </h2>
                    <div class="swiper testimonials-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($testimonialsSection->testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="review-card">
                                        @if (!empty($testimonial['image']))
                                            <img src="{{ asset($testimonial['image']) }}"
                                                alt="{{ $testimonial['author'] ?? 'Customer' }}" class="review-image"
                                                loading="lazy">
                                        @endif
                                        @if (!empty($testimonial['text']))
                                            <p class="review-text">{{ $testimonial['text'] }}</p>
                                        @endif
                                        @if (!empty($testimonial['author']))
                                            <div class="review-author">{{ $testimonial['author'] }}</div>
                                        @endif
                                        @if (!empty($testimonial['rating']))
                                            <div>
                                                @for ($i = 0; $i < $testimonial['rating']; $i++)
                                                    ⭐
                                                @endfor
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Navigation arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    @php
        $hasImageCarousels = $landingPage->sections->where('section_type', 'image_carousel')->count() > 0;
    @endphp

    @if ($hasImageCarousels)
        {{-- Lightbox for carousel images --}}
        <div id="carousel-lightbox" class="lightbox-backdrop" aria-modal="true" role="dialog">
            <div class="lightbox-content">
                <button type="button" class="lightbox-close" aria-label="Close">✕</button>
                <img id="lightbox-image" src="" alt="Full size image">
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Image Carousel Slider(s)
                document.querySelectorAll('.image-carousel').forEach(function(carouselEl) {
                    const slideCount = carouselEl.querySelectorAll('.swiper-slide').length;
                    const perView = slideCount >= 5 ? 5 : Math.max(slideCount, 1);
                    const carouselId = carouselEl.getAttribute('data-carousel-id');
                    const thumbsEl = document.querySelector(`.image-carousel-thumbs[data-carousel-id="${carouselId}"]`);
                    let thumbsSwiper = null;

                    if (thumbsEl) {
                        thumbsSwiper = new Swiper(thumbsEl, {
                            spaceBetween: 10,
                            slidesPerView: Math.min(slideCount, 6) || 1,
                            freeMode: true,
                            watchSlidesProgress: true,
                            watchSlidesVisibility: true,
                            slideToClickedSlide: true,
                            allowTouchMove: true,
                            simulateTouch: true,
                            grabCursor: true,
                        });
                    }

                    new Swiper(carouselEl, {
                        slidesPerView: perView,
                        spaceBetween: 14,
                        loop: slideCount > 1,
                        loopAdditionalSlides: Math.max(0, 5 - slideCount),
                        allowTouchMove: true,
                        simulateTouch: true,
                        touchRatio: 1,
                        touchAngle: 45,
                        grabCursor: true,
                        autoplay: slideCount > 1 ? {
                            delay: 2500,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        } : false,
                        navigation: {
                            nextEl: carouselEl.querySelector('.swiper-button-next'),
                            prevEl: carouselEl.querySelector('.swiper-button-prev'),
                        },
                        pagination: {
                            el: carouselEl.querySelector('.swiper-pagination'),
                            clickable: true,
                        },
                        breakpoints: {
                            0: {
                                slidesPerView: Math.min(1.2, Math.max(slideCount, 1)),
                                spaceBetween: 12,
                            },
                            480: {
                                slidesPerView: Math.min(2, Math.max(slideCount, 1)),
                                spaceBetween: 14,
                            },
                            768: {
                                slidesPerView: Math.min(3, Math.max(slideCount, 1)),
                                spaceBetween: 14,
                            },
                            1024: {
                                slidesPerView: Math.min(4, Math.max(slideCount, 1)),
                                spaceBetween: 16,
                            },
                            1280: {
                                slidesPerView: Math.min(5, Math.max(slideCount, 1)),
                                spaceBetween: 18,
                            },
                        },
                        thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
                    });
                });

                // Lightbox handling
                const lightbox = document.getElementById('carousel-lightbox');
                const lightboxImg = document.getElementById('lightbox-image');
                const lightboxClose = document.querySelector('.lightbox-close');

                document.querySelectorAll('.carousel-zoom-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const src = btn.getAttribute('data-image');
                        if (!src || !lightbox || !lightboxImg) return;
                        lightboxImg.src = src;
                        lightbox.classList.add('active');
                        document.body.classList.add('lightbox-open');
                    });
                });

                const hideLightbox = () => {
                    if (lightbox) {
                        lightbox.classList.remove('active');
                        document.body.classList.remove('lightbox-open');
                    }
                };

                if (lightbox) {
                    lightbox.addEventListener('click', (e) => {
                        if (e.target === lightbox) {
                            hideLightbox();
                        }
                    });
                }

                if (lightboxClose) {
                    lightboxClose.addEventListener('click', hideLightbox);
                }

                document.addEventListener('keyup', (e) => {
                    if (e.key === 'Escape') {
                        hideLightbox();
                    }
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Testimonials slider (if present)
            const testimonialEl = document.querySelector('.testimonials-swiper');
            if (testimonialEl) {
                new Swiper(testimonialEl, {
                    breakpoints: {
                        320: { slidesPerView: 1, spaceBetween: 20 },
                        481: { slidesPerView: 2, spaceBetween: 30 },
                        769: { slidesPerView: 3, spaceBetween: 30 }
                    },
                    navigation: {
                        nextEl: testimonialEl.querySelector('.swiper-button-next'),
                        prevEl: testimonialEl.querySelector('.swiper-button-prev'),
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    loop: true,
                    allowTouchMove: true,
                    simulateTouch: true,
                    touchRatio: 1,
                    touchAngle: 45,
                    speed: 600,
                    pauseOnMouseEnter: true,
                    autoHeight: true,
                    grabCursor: true,
                    effect: 'slide',
                });
            }
        });
    </script>

    {{-- Call to action for phone number --}}
    @foreach ($landingPage->sections->where('section_type', 'call_to_action') as $ctaSection)
        <section class="cta-section"
            style="background: {{ $ctaSection->cta_background_color ?? '#1D8758' }}; padding: 19px 20px; text-align: center; color: white;padding-top: 11px;">
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title" style="font-size: 28px; margin-bottom: 10px; font-weight: 600;">
                        {{ $ctaSection->cta_title ?? 'প্রয়োজনে কল করুন' }}</h2>
                    @if ($ctaSection->cta_subtitle)
                        <p class="cta-subtitle" style="font-size: 18px; margin-bottom: 25px; opacity: 0.9;">
                            {{ $ctaSection->cta_subtitle }}</p>
                    @endif
                    <a href="tel:{{ $ctaSection->cta_phone_number ?? '01611-109447' }}" class="cta-button"
                        style="display: inline-flex; align-items: center; background: {{ $ctaSection->cta_button_color ?? '#dc3545' }}; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-size: 18px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        {{ $ctaSection->cta_button_text ?? 'কল করুন' }}
                    </a>
                </div>
            </div>
        </section>
    @endforeach

    <!-- Order Form Section -->
    <style>
        .variation-btn-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 10px;
        }

        .variation-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 24px;
            padding: 10px 28px 10px 16px;
            cursor: pointer;
            background: #fff;
            font-size: 18px;
            font-weight: 600;
            color: #5d4037;
            transition: border-color 0.2s, background 0.2s, color 0.2s, box-shadow 0.2s;
            min-width: 180px;
            min-height: 48px;
            position: relative;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .variation-btn:hover,
        .variation-radio:focus+.variation-btn {
            border-color: var(--primary-color);
            background: #fffbe7;
            color: #e53935;
        }

        .variation-radio:checked+.variation-btn {
            border-color: #006E3F;
            background: #fff3f2;
            color: #006E3F;
            box-shadow: 0 2px 12px rgba(229, 57, 53, 0.10);
        }

        .custom-radio-indicator {
            display: inline-block;
            width: 22px;
            height: 22px;
            border: 2.5px solid #bdbdbd;
            border-radius: 50%;
            background: #fff;
            margin-right: 6px;
            position: relative;
            transition: border-color 0.2s, box-shadow 0.2s;
            flex-shrink: 0;
        }

        .variation-radio:checked+.variation-btn .custom-radio-indicator {
            border-color: #006E3F;
            box-shadow: 0 0 0 3px #ffeaea;
        }

        .variation-radio:checked+.variation-btn .custom-radio-indicator::after {
            content: '';
            display: block;
            position: absolute;
            top: 4px;
            left: 4px;
            width: 10px;
            height: 10px;
            background: #006E3F;
            border-radius: 50%;
        }

        .variation-name {
            font-weight: 600;
            font-size: 18px;
            margin-right: 8px;
        }

        .variation-price {
            color: #e53935;
            font-weight: 500;
            font-size: 16px;
        }

        .visually-hidden {
            position: absolute !important;
            height: 1px;
            width: 1px;
            overflow: hidden;
            clip: rect(1px, 1px, 1px, 1px);
            white-space: nowrap;
        }
    </style>
    <section id="order-section" class="order-form-section">
        <div class="order-container">
            <h2 class="order-title">{{ $landingPage->order_form_title ?? 'অর্ডার করুন' }}</h2>
            <form id="landing-order-form">
                @csrf
                <div class="order-form-wrapper">
                    <div class="order-form-left">

                        @if (
                            $product->product_type === 'variable' &&
                                $product->variationCombinations &&
                                $product->variationCombinations->count() > 0)
                            @php
                                // Extract unique variations and their options from combinations
                                $variations = [];
                                $combinationMap = []; // Map to store combination data for each option

                                // First, get all unique variations and their options
                                $allVariations = [];
                                foreach ($product->variationCombinations as $combination) {
                                    $combinationOptions = is_array($combination->variation_options)
                                        ? $combination->variation_options
                                        : json_decode($combination->variation_options, true);

                                    foreach ($combinationOptions as $optionId) {
                                        $option = \App\Models\VariationOption::find($optionId);
                                        if ($option && $option->variation) {
                                            $variationId = $option->variation->id;
                                            $variationName = $option->variation->name;

                                            if (!isset($allVariations[$variationId])) {
                                                $allVariations[$variationId] = [
                                                    'id' => $variationId,
                                                    'name' => $variationName,
                                                    'options' => [],
                                                ];
                                            }

                                            if (!isset($allVariations[$variationId]['options'][$optionId])) {
                                                $allVariations[$variationId]['options'][$optionId] = [
                                                    'id' => $optionId,
                                                    'name' => $option->name,
                                                    'featured_image' => $option->featured_image ?? '',
                                                ];
                                            }
                                        }
                                    }
                                }

                                // Now create the display variations with combination data
                                foreach ($allVariations as $variationId => $variation) {
                                    $variations[$variationId] = [
                                        'id' => $variationId,
                                        'name' => $variation['name'],
                                        'options' => [],
                                    ];

                                    foreach ($variation['options'] as $optionId => $option) {
                                        // Find the combination that uses this option
                                        $combinationForOption = null;
                                        foreach ($product->variationCombinations as $combination) {
                                            $combinationOptions = is_array($combination->variation_options)
                                                ? $combination->variation_options
                                                : json_decode($combination->variation_options, true);

                                            if (in_array($optionId, $combinationOptions)) {
                                                $combinationForOption = $combination;
                                                break;
                                            }
                                        }

                                        if ($combinationForOption) {
                                            $effectivePrice =
                                                $combinationForOption->offer_price ??
                                                ($combinationForOption->regular_price ??
                                                    ($combinationForOption->price ?? 0));

                                            $variations[$variationId]['options'][$optionId] = [
                                                'id' => $optionId,
                                                'name' => $option['name'],
                                                'price' => $effectivePrice,
                                                'combination_id' => $combinationForOption->id,
                                                'featured_image' => $option['featured_image'],
                                            ];

                                            // Store combination data for this option
                                            $combinationMap[$optionId] = [
                                                'combination_id' => $combinationForOption->id,
                                                'price' => $effectivePrice,
                                                'regular_price' => $combinationForOption->regular_price ?? 0,
                                                'offer_price' => $combinationForOption->offer_price ?? 0,
                                            ];
                                        }
                                    }
                                }
                            @endphp

                            @foreach ($variations as $variation)
                                @if (count($variation['options']) > 0)
                                    <div class="form-group">
                                        <label class="variation-label"
                                            style="font-weight:600;font-size:16px;margin-bottom:5px;display:block;">
                                            {{ $variation['name'] ?? 'ভ্যারিয়েশন নির্বাচন করুন' }} *
                                        </label>
                                        <div id="variation_options_{{ $variation['id'] }}"
                                            class="variation-btn-group" data-variation-id="{{ $variation['id'] }}">
                                            @foreach ($variation['options'] as $option)
                                                <input class="variation-radio visually-hidden" type="radio"
                                                    name="variation_option_id_{{ $variation['id'] }}"
                                                    id="variation_option_{{ $option['id'] }}"
                                                    value="{{ $option['id'] }}"
                                                    data-price="{{ $option['price'] }}"
                                                    data-combination-id="{{ $option['combination_id'] }}"
                                                    data-image="{{ asset('storage/' . ($option['featured_image'] ?? $product->thumb_image)) }}">
                                                <label class="variation-btn"
                                                    for="variation_option_{{ $option['id'] }}">
                                                    <span class="custom-radio-indicator"></span>
                                                    <span class="variation-name">{{ $option['name'] }}</span>

                                                    <span class="variation-price">
                                                        @if (isset($combinationMap[$option['id']]) &&
                                                                $combinationMap[$option['id']]['offer_price'] &&
                                                                $combinationMap[$option['id']]['regular_price'] &&
                                                                $combinationMap[$option['id']]['offer_price'] < $combinationMap[$option['id']]['regular_price']
                                                        )
                                                            <span
                                                                style="text-decoration: line-through; color: #888;">৳{{ number_format($combinationMap[$option['id']]['regular_price'], 2) }}</span>
                                                            <span
                                                                style="color: red;">৳{{ number_format($combinationMap[$option['id']]['offer_price'], 2) }}</span>
                                                        @else
                                                            <span
                                                                style="color: red;">৳{{ number_format($option['price'], 2) }}</span>
                                                        @endif
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        <div class="form-group">
                            <label for="name">নাম *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="আপনার নাম লিখুন" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">মোবাইল নাম্বার *</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                placeholder="আপনার মোবাইল নাম্বার লিখুন" required>
                        </div>
                        <div class="form-group">
                            <label for="address">ঠিকানা *</label>
                            <textarea class="form-control" id="address" name="address" rows="3"
                                placeholder="যেখানে ডেলিভারি নিবেন তা লিখুন যেমনঃ হোল্ডিং/গ্রাম/বাজার" required></textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="message">অর্ডার নোট</label>
                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="স্পেশাল কিছু বলতে চাইলে লেখুন (অপশনাল)"></textarea>
                        </div> --}}


                    </div>
                    <div class="order-form-right">
                        <div class="product-summary">
                            <div class="summary-title-container">
                                <h3 class="summary-title">পণ্যের বিবরণ</h3>
                                <div class="alert blink-alert">
                                    🚨 Stock শেষ হয়ে যাচ্ছে!
                                </div>
                            </div>
                            <div class="enitre-item">
                                <div class="order-item" data-product-id="{{ $product->id }}"
                                    data-category="{{ $product->category->name ?? '' }}">
                                    <div class="item-image">
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}"
                                            alt="{{ $product->title }}" loading="lazy">
                                    </div>
                                    <div class="item-details">
                                        <span class="item-name">{{ $product->title }}</span>
                                    </div>
                                </div>
                                <div class="quantity-selector">
                                    <div class="quantity-controls">
                                        <button type="button" class="qty-btn minus-btn"
                                            onclick="decreaseQuantity()">-</button>
                                        <input type="number" id="quantity" name="quantity" value="1"
                                            min="1" onchange="updatePrices()">
                                        <button type="button" class="qty-btn plus-btn"
                                            onclick="increaseQuantity()">+</button>
                                    </div>
                                    @if ($product->offer && $product->old_price && $product->offer < $product->old_price)
                                            <div class="discount-info">
                                                <span class="regular-price">৳<span
                                                        class="strikethrough">{{ englishToBangla(number_format($product->old_price, 2)) }}</span></span>
                                                @php
                                                    $discountPercentage = round(
                                                        (($product->old_price - $product->offer) /
                                                            $product->old_price) *
                                                            100,
                                                    );
                                                @endphp
                                                <span
                                                    class="discount-badge">{{ englishToBangla($discountPercentage) }}%
                                                    ছাড়</span>
                                            </div>
                                        @endif
                                        <span class="item-total">৳<span
                                                id="itemTotal" style="font-weight: 700 !important;">{{ englishToBangla(number_format($price, 2)) }}</span></span>
                                </div>
                                <span class="quantity-label">পরিমাণ</span>
                            </div>

                            <div class="order-summary">
                                <div class="summary-row">
                                    <span>ডেলিভারি খরচ</span>
                                    <span>৳<span id="shippingCost" style="font-family: sans-serif !important;">{{ englishToBangla(number_format($activeShippingOptions ? reset($activeShippingOptions)['cost'] : $shippingSetting->flat_rate, 2)) }}</span></span>
                                </div>
                                <div class="summary-row subtotal">
                                    <span>মোট টাকা</span>
                                    <span>৳<span
                                            id="subtotal">{{ englishToBangla(number_format($price + ($activeShippingOptions ? reset($activeShippingOptions)['cost'] : $shippingSetting->flat_rate), 2)) }}</span></span>
                                </div>
                            </div>

                            <!-- Shipping Area Selection -->
                            <div class="shipping-area">
                                <h5 class="shipping-area-title">আপনার ডেলিভারি এরিয়া নির্বাচন করুন</h5>
                                <div>
                                    @if (count($activeShippingOptions) > 0)
                                        @foreach ($activeShippingOptions as $key => $option)
                                            <div class="shipping-option">
                                                <div class="shipping-option-left">
                                                    <input type="radio" name="shipping_area"
                                                        id="{{ $key }}" value="{{ $key }}"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    <label for="{{ $key }}">{{ $option['name'] }}</label>
                                                </div>
                                                <div class="shipping-price">৳{{ englishToBangla(number_format($option['cost'], 2)) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="shipping-option">
                                            <div class="shipping-option-left">
                                                <input type="radio" name="shipping_area" id="flat_rate"
                                                    value="flat_rate" checked>
                                                <label for="flat_rate">Standard Shipping</label>
                                            </div>
                                            <div class="shipping-price">
                                                ৳{{ englishToBangla(number_format($shippingSetting->flat_rate, 2)) }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="payment-method">
                                <h4>পেমেন্ট পদ্ধতি</h4>
                                @if ($codEnabled)
                                    <div class="payment-option">
                                        <label>
                                            <input type="radio" name="payment_method" value="cod"
                                                {{ $defaultMethod == 'cod' ? 'checked' : '' }}>
                                            Cash On Delivery
                                        </label>
                                        <p class="payment-note">ডেলিভারির সময় নগদ অর্থ প্রদান করুন।</p>
                                    </div>
                                @endif
                                {{-- @if ($bkashEnabled)
                                    <div class="payment-option">
                                        <label>
                                            <input type="radio" name="payment_method" value="bkash"
                                                {{ $defaultMethod == 'bkash' ? 'checked' : '' }}>
                                            bKash
                                        </label>
                                        <p class="payment-note">মোবাইল ব্যাংকিং এর মাধ্যমে পেমেন্ট করুন।</p>
                                    </div>
                                @endif
                                @if ($nagadEnabled)
                                    <div class="payment-option">
                                        <label>
                                            <input type="radio" name="payment_method" value="nagad"
                                                {{ $defaultMethod == 'nagad' ? 'checked' : '' }}>
                                            Nagad
                                        </label>
                                        <p class="payment-note">মোবাইল ব্যাংকিং এর মাধ্যমে পেমেন্ট করুন।</p>
                                    </div>
                                @endif
                                @if ($rocketEnabled)
                                    <div class="payment-option">
                                        <label>
                                            <input type="radio" name="payment_method" value="rocket"
                                                {{ $defaultMethod == 'rocket' ? 'checked' : '' }}>
                                            Rocket
                                        </label>
                                        <p class="payment-note">মোবাইল ব্যাংকিং এর মাধ্যমে পেমেন্ট করুন।</p>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="price" value="{{ $price }}">
                <input type="hidden" name="combination_id" id="selected_combination_id" value="">
                <input type="hidden" name="price" id="selected_price" value="{{ $price }}">
                <input type="hidden" name="shipping"
                    value="{{ $activeShippingOptions ? reset($activeShippingOptions)['cost'] : $shippingSetting->flat_rate }}">

                <button type="submit" class="order-submit-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                        <path
                            d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ $landingPage->order_place_button_text ?? 'অর্ডার Confirm করুন' }}
                </button>
            </form>
        </div>
    </section>

    {{-- Footer css --}}
    <style>
        /* হেডার স্টাইলিং */
        .site-header {
            background-color: #f5f5f5;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        .footer-landing a{
            text-decoration: none;
            color: #2e7d32;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between !important;
            align-items: center;
            padding: 0 10px;
        }

        .copyright-text {
            font-size: 14px;
            color: #666;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav-link {
            color: #2e7d32;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #1b5e20;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .site-header {
                padding-bottom: 72px;
            }
            .header-container {
                flex-direction: row;
                gap: 10px;
                text-align: center;
                padding: 0px 5px;
            }
            .footer-landing .header-container {
                    flex-direction: column;
                    gap: 0px;
                    text-align: center;
                    padding: 0px 5px;
                }

            .copyright-text {
                /*order: 2;*/
            }
            
            section.footer-landing {
                margin-top: -16px;
            }

            .main-nav {
                order: 1;
                width: 100%;
            }

            .nav-menu {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .nav-menu {
                gap: 15px;
            }

            .nav-link {
                font-size: 13px;
            }
        }

        /* Single Image Section Styles */
        .single-image-section {
            padding: 30px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin-bottom: 10px;
        }

        .single-image-wrapper {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .single-image-content {
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .single-image-title {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-family: 'Hind Siliguri', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            line-height: 1.2;
        }

        .single-image-description {
            color: var(--secondary-color);
            margin-bottom: 30px;
            font-size: 18px;
            line-height: 1.6;
            font-family: 'Hind Siliguri', sans-serif;
        }

        .single-image-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .single-image-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .single-image {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .single-image-container:hover .single-image {
            transform: scale(1.02);
        }

        /* Lazy Loading Styles */
        img[loading="lazy"] {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        img[loading="lazy"].loaded {
            opacity: 1;
        }

        /* Fallback for lazy loading - show images after 2 seconds if JS fails */
        img[loading="lazy"] {
            animation: lazyLoadFallback 2s forwards;
        }

        @keyframes lazyLoadFallback {
            to {
                opacity: 1;
            }
        }

        /* Lazy Video Loading Styles */
        .lazy-video {
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .lazy-video.loaded {
            opacity: 1;
        }

        /* Responsive Design for Single Image Section */
        @media (max-width: 768px) {
            .single-image-section {
                padding: 40px 0;
            }

            .single-image-wrapper {
                padding: 0 15px;
            }

            .single-image-title {
                font-size: 2rem;
                margin-bottom: 15px;
            }

            .single-image-description {
                font-size: 16px;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 480px) {
            .single-image-section {
                padding: 30px 0;
            }

            .single-image-title {
                font-size: 1.75rem;
            }

            .single-image-description {
                font-size: 15px;
            }
        }
    </style>
    <!-- Footer -->
    <section class="footer-landing">
        <header class="site-header">
            <div class="header-container">
                
                <nav class="main-nav">
                    <ul class="nav-menu">
                        <li class="nav-item"><a href="#" class="nav-link">প্রাইভেসি পলিসি</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">শর্তাবলী</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">যোগাযোগ</a></li>
                    </ul>
                </nav>
                
                
            <div class="copyright-text">© {{ date('Y') }} {{ \App\Models\SiteSetting::get('general', 'site_name', 'Thikana Shop') }} | সর্বস্বত্ব সংরক্ষিত</div> Developed by <a href="https://uddoktaecommerce.com" target="_blank">Uddokta Ecommerce</a>
            </div>
            </div>
        </header>
    </section>

    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    <script>
        // Combination data for JavaScript
        window.combinationData = @json($product->variationCombinations ?? []);
        window.combinationMap = @json($combinationMap ?? []);
        // Shipping settings
        window.shippingSettings = {
            flatRate: {{ $shippingSetting->flat_rate }},
            shippingOptions: @json($activeShippingOptions),
            freeShippingThreshold: {{ $shippingSetting->free_shipping_threshold }},
            specificRules: @json($specificShippingRules)
        };
        

        // Lazy loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');

            // Fallback: show all images after 3 seconds if IntersectionObserver fails
            setTimeout(function() {
                lazyImages.forEach(img => {
                    if (!img.classList.contains('loaded')) {
                        img.classList.add('loaded');
                    }
                });
            }, 3000);

            // Check if IntersectionObserver is supported
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.addEventListener('load', function() {
                                img.classList.add('loaded');
                            });
                            // Also handle error case
                            img.addEventListener('error', function() {
                                img.classList.add('loaded'); // Show anyway
                            });
                            observer.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(img => {
                    img.addEventListener('load', function() {
                        img.classList.add('loaded');
                    });
                    img.addEventListener('error', function() {
                        img.classList.add('loaded');
                    });
                });
            }
        });

        // Lazy video loading
        document.addEventListener('DOMContentLoaded', function() {
            const lazyVideos = document.querySelectorAll('.lazy-video');

            // Load videos after page load with a small delay
            setTimeout(function() {
                lazyVideos.forEach(video => {
                    if (video.dataset.src && !video.src) {
                        video.src = video.dataset.src;
                        video.classList.add('loaded');
                    }
                });
            }, 1000); // 1 second delay after page load

            // Also load videos when they come into view (for better performance)
            if ('IntersectionObserver' in window) {
                const videoObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const video = entry.target;
                            if (video.dataset.src && !video.src) {
                                video.src = video.dataset.src;
                                video.classList.add('loaded');
                            }
                            observer.unobserve(video);
                        }
                    });
                }, {
                    rootMargin: '50px' // Start loading 50px before video comes into view
                });

                lazyVideos.forEach(video => videoObserver.observe(video));
            }
        });

        // Helper function to convert English numbers to Bangla numerals
        function englishToBanglaNumber(number) {
            const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return number.toString().replace(/[0-9]/g, function(digit) {
                return banglaDigits[parseInt(digit)];
            });
        }



        // Quantity controls
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
            updatePrices();
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
                updatePrices();
            }
        }

        function updatePrices() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const price = parseFloat(document.querySelector('input[name="price"]').value) || 0;
            const itemTotal = price * quantity;

            const itemTotalElement = document.getElementById('itemTotal');
            const shippingCostElement = document.getElementById('shippingCost');
            const subtotalElement = document.getElementById('subtotal');

            if (itemTotalElement) {
                itemTotalElement.textContent = englishToBanglaNumber(itemTotal.toFixed(2));
            }

            // Get shipping cost from selected shipping area
            const selectedShipping = document.querySelector('input[name="shipping_area"]:checked');
            let shippingCost = 0;

            if (selectedShipping) {
                const settings = window.shippingSettings;
                if (selectedShipping.value === 'flat_rate') {
                    shippingCost = settings.flatRate;
                } else {
                    // Try both string and numeric keys
                    let selectedOption = settings.shippingOptions[selectedShipping.value];
                    if (!selectedOption) {
                        selectedOption = settings.shippingOptions[parseInt(selectedShipping.value)];
                    }
                    if (selectedOption && (selectedOption.active || selectedOption.rule_type === 'delivery_area')) {
                        shippingCost = parseFloat(selectedOption.cost);
                    }
                }
            } else {
                // If no shipping area selected, use flat rate
                shippingCost = window.shippingSettings.flatRate;
            }

            // Apply free shipping if threshold met
            // First check specific shipping rules
            let freeShippingThreshold = window.shippingSettings.freeShippingThreshold;
            let fallbackCost = null;
            
            // Check for specific free shipping threshold rules
            if (window.shippingSettings.specificRules) {
                window.shippingSettings.specificRules.forEach(rule => {
                    if (rule.rule_type === 'free_shipping' && rule.free_shipping_threshold) {
                        freeShippingThreshold = rule.free_shipping_threshold;
                        fallbackCost = rule.rule_value; // Fallback cost when threshold not met
                    }
                });
            }
            
            if (itemTotal >= freeShippingThreshold) {
                shippingCost = 0;
            } else if (fallbackCost !== null) {
                // Use the fallback cost from the specific rule
                shippingCost = parseFloat(fallbackCost);
            }

            if (shippingCostElement) {
                shippingCostElement.textContent = englishToBanglaNumber(shippingCost.toFixed(2));
            }

            if (subtotalElement) {
                subtotalElement.textContent = englishToBanglaNumber((itemTotal + shippingCost).toFixed(2));
            }

            // Update hidden shipping input
            document.querySelector('input[name="shipping"]').value = shippingCost;
        }

        function updateVariationPrice() {
            var variationGroups = document.querySelectorAll('.variation-btn-group');
            var selectedOptions = {};
            var allSelected = true;

            // Get all selected options from each variation group
            variationGroups.forEach(function(group) {
                var checkedRadio = group.querySelector('.variation-radio:checked');
                if (checkedRadio) {
                    var variationId = group.getAttribute('data-variation-id');
                    selectedOptions[variationId] = {
                        radio: checkedRadio,
                        price: checkedRadio.getAttribute('data-price'),
                        image: checkedRadio.getAttribute('data-image'),
                        combinationId: checkedRadio.getAttribute('data-combination-id')
                    };
                } else {
                    allSelected = false;
                }
            });

            // Only update if we have a complete selection
            if (allSelected && Object.keys(selectedOptions).length > 0) {
                // Find the matching combination to get the correct price
                var selectedOptionIds = Object.values(selectedOptions).map(function(option) {
                    return parseInt(option.radio.value);
                }).sort(function(a, b) {
                    return a - b;
                });

                var matchingCombination = null;
                if (window.combinationData) {
                    for (var i = 0; i < window.combinationData.length; i++) {
                        var combination = window.combinationData[i];
                        var combinationOptions = Array.isArray(combination.variation_options) ?
                            combination.variation_options :
                            JSON.parse(combination.variation_options);

                        combinationOptions.sort(function(a, b) {
                            return a - b;
                        });

                        if (combinationOptions.length === selectedOptionIds.length) {
                            var match = true;
                            for (var j = 0; j < combinationOptions.length; j++) {
                                if (combinationOptions[j] !== selectedOptionIds[j]) {
                                    match = false;
                                    break;
                                }
                            }
                            if (match) {
                                matchingCombination = combination;
                                break;
                            }
                        }
                    }
                }

                var price, image, combinationId;
                if (matchingCombination) {
                    // Use the combination price
                    price = matchingCombination.offer_price || matchingCombination.regular_price || matchingCombination
                        .price || 0;
                    combinationId = matchingCombination.id;
                    // Use combination's featured image if available, otherwise use first selected option's image
                    if (matchingCombination.featured_image) {
                        image = '{{ asset('storage/') }}/' + matchingCombination.featured_image;
                    } else {
                        var firstSelected = Object.values(selectedOptions)[0];
                        image = firstSelected.image;
                    }
                } else {
                    // Fallback to first selected option
                    var firstSelected = Object.values(selectedOptions)[0];
                    price = firstSelected.price;
                    image = firstSelected.image;
                    combinationId = firstSelected.combinationId;
                }

                // Update price fields
                var priceInput = document.querySelector('input[name="price"]');
                if (priceInput) priceInput.value = price;

                var selectedPriceInput = document.getElementById('selected_price');
                if (selectedPriceInput) selectedPriceInput.value = price;

                var priceDisplay = document.querySelector('.product-price');
                if (priceDisplay) priceDisplay.textContent = '৳' + englishToBanglaNumber(parseFloat(price).toFixed(2));

                // Update combination_id
                if (combinationId) {
                    var combinationInput = document.getElementById('selected_combination_id');
                    if (combinationInput) combinationInput.value = combinationId;
                }

                // Update item total and subtotal
                updatePrices();

                // Optionally update product image
                if (image) {
                    updateProductImage(image);
                }
            }
        }

        // Function to handle combination selection
        function handleCombinationSelection() {
            var variationGroups = document.querySelectorAll('.variation-btn-group');
            var selectedOptions = {};
            var allSelected = true;

            // Get all selected options from each variation group
            variationGroups.forEach(function(group) {
                var variationId = group.getAttribute('data-variation-id');
                var checkedRadio = group.querySelector('.variation-radio:checked');

                if (checkedRadio) {
                    selectedOptions[variationId] = checkedRadio.value;
                } else {
                    allSelected = false;
                }
            });



            if (allSelected && Object.keys(selectedOptions).length > 0) {
                // Find the matching combination
                var combinationId = findMatchingCombination(selectedOptions);

                if (combinationId) {
                    document.getElementById('selected_combination_id').value = combinationId;
                }
            }
        }

        // Function to update product image
        function updateProductImage(imagePath) {
            var img = document.querySelector('.item-image img');
            if (img && imagePath) {


                // Create a new image to test loading
                var testImg = new Image();
                testImg.onload = function() {

                    img.src = imagePath;
                    img.style.display = 'block'; // Ensure image is visible
                };
                testImg.onerror = function() {

                    // Fallback to product thumb image
                    img.src = '{{ asset('storage/' . $product->thumb_image) }}';
                    img.style.display = 'block'; // Ensure image is visible
                };
                testImg.src = imagePath;
            } else {

            }
        }

        // Function to ensure initial image is visible
        function ensureInitialImage() {
            var img = document.querySelector('.item-image img');
            if (img) {

                img.style.display = 'block';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
            }
        }

        // Function to find matching combination
        function findMatchingCombination(selectedOptions) {
            if (!window.combinationData) return null;

            // Convert selected options to array of option IDs
            var selectedOptionIds = Object.values(selectedOptions).map(function(id) {
                return parseInt(id);
            }).sort(function(a, b) {
                return a - b;
            });



            // Find matching combination
            for (var i = 0; i < window.combinationData.length; i++) {
                var combination = window.combinationData[i];
                var combinationOptions = Array.isArray(combination.variation_options) ?
                    combination.variation_options :
                    JSON.parse(combination.variation_options);

                // Sort combination options for comparison
                combinationOptions.sort(function(a, b) {
                    return a - b;
                });



                // Check if this combination matches our selected options
                if (combinationOptions.length === selectedOptionIds.length) {
                    var match = true;
                    for (var j = 0; j < combinationOptions.length; j++) {
                        if (combinationOptions[j] !== selectedOptionIds[j]) {
                            match = false;
                            break;
                        }
                    }
                    if (match) {

                        return combination.id;
                    }
                }
            }

            return null;
        }

        // Call on page load to set initial price
        document.addEventListener('DOMContentLoaded', function() {
            var variationGroups = document.querySelectorAll('.variation-btn-group');
            if (variationGroups.length > 0) {

                var allRadios = document.querySelectorAll('.variation-radio');

                allRadios.forEach(function(radio, index) {

                });

                // Set the first radio in each variation group as checked if none is selected
                variationGroups.forEach(function(group) {
                    var radios = group.querySelectorAll('.variation-radio');
                    var hasChecked = false;

                    radios.forEach(function(radio) {
                        if (radio.checked) hasChecked = true;
                    });

                    if (!hasChecked && radios.length > 0) {
                        radios[0].checked = true;
                    }
                });

                ensureInitialImage(); // Ensure initial image is visible
                updateVariationPrice();
                handleCombinationSelection();

                // Add event listeners to all radio buttons
                allRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {

                        updateVariationPrice();
                        handleCombinationSelection();
                    });
                });
            }
        });

        // Shipping area change handler
        document.addEventListener('DOMContentLoaded', function() {
            const shippingRadios = document.querySelectorAll('input[name="shipping_area"]');
            shippingRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    setTimeout(updatePrices, 100); // Small delay to ensure DOM is ready
                });
            });
            
            // Update prices on page load
            setTimeout(updatePrices, 100);

            // Form submission
            const orderForm = document.getElementById('landing-order-form');
            orderForm.addEventListener('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('landing.order') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            alert(
                                'অর্ডার সফলভাবে সম্পন্ন হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।'
                            );
                            if (response.order_id) {
                                window.location.href = "{{ url('thank-you') }}/" + response
                                    .order_id;
                            }
                        } else {
                            const msg = response.message || 'অর্ডার সম্পন্ন করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
                            if (window.pushFraudNotification) {
                                window.pushFraudNotification(msg, 'error');
                            } else {
                                alert(msg);
                            }
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const resp = xhr.responseJSON || {};
                            const topMessage = resp.message;
                            const payloadErrors = resp.errors;
                            let message = topMessage || 'অর্ডার সম্পন্ন করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
                            if (Array.isArray(payloadErrors) && payloadErrors.length) {
                                message = payloadErrors[0];
                            } else if (payloadErrors && typeof payloadErrors === 'object') {
                                const firstKey = Object.keys(payloadErrors)[0];
                                if (firstKey) {
                                    const arr = payloadErrors[firstKey];
                                    if (Array.isArray(arr) && arr.length) message = arr[0];
                                }
                            }
                            if (window.pushFraudNotification) {
                                window.pushFraudNotification(message, 'error');
                            } else {
                                alert(message);
                            }
                            return;
                        }
                        const fallback = 'অর্ডার সম্পন্ন করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
                        if (window.pushFraudNotification) {
                            window.pushFraudNotification(fallback, 'error');
                        } else {
                            alert(fallback);
                        }
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('js/incomplete-landing.js') }}"></script>

    <script>
        

        // Function to unmute YouTube videos
        function unmuteVideo(iframeId) {
            const iframe = document.getElementById(iframeId);
            if (iframe) {
                // Get current src
                let currentSrc = iframe.src;

                // Remove mute parameter if it exists
                currentSrc = currentSrc.replace('&mute=1', '');

                // Update iframe src to unmute
                iframe.src = currentSrc;

                // Hide the unmute overlay
                const overlay = iframe.parentElement.querySelector('.unmute-overlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                }
            }
        }

        // Order Submit Button Animation
        const orderSubmitBtn = document.querySelector('.order-submit-btn');

        function triggerShake() {
            if (orderSubmitBtn) {
                orderSubmitBtn.style.animation = 'shake 0.8s ease';

                // Remove animation after it finishes so we can re-apply it again later
                setTimeout(() => {
                    orderSubmitBtn.style.animation = '';
                }, 3000); // match the animation duration
            }
        }

        // Shake immediately when page loads
        window.addEventListener('load', () => {
            triggerShake();
            setInterval(triggerShake, 4000); // every 4 seconds
        });
    </script>

    {{-- Order Submit Button Animation CSS --}}
    <style>
        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            11.11% {
                transform: translateX(-5px);
            }

            22.22% {
                transform: translateX(5px);
            }

            33.33% {
                transform: translateX(-5px);
            }

            44.44% {
                transform: translateX(5px);
            }

            55.55% {
                transform: translateX(-5px);
            }

            66.66% {
                transform: translateX(5px);
            }

            77.77% {
                transform: translateX(-5px);
            }

            88.88% {
                transform: translateX(5px);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>


    @php
        $landingCallEnabled = setting('ecommerce', 'landing_call_button_show', '1') == '1';
        $landingCallNumber = setting('ecommerce', 'landing_call_button_number', '');
        if (blank($landingCallNumber)) {
            $landingCallNumber = setting('general', 'phone_number', '');
        }
        $landingCallHref = $landingCallNumber ? preg_replace('/[^0-9\+]/', '', $landingCallNumber) : '';
    @endphp

    @if ($landingCallEnabled && $landingCallNumber)
        <!-- Call fixed button for chat -->
        <div class="call-button-container">
            <a href="tel:{{ $landingCallHref }}" class="call-button" id="callButton">
                <svg class="phone-icon" viewBox="0 0 24 24">
                    <path
                        d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                </svg>
            </a>
        </div>
        <style>
            .call-button-container {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 1000;
            }
    
            .call-button {
                position: relative;
                width: 60px;
                height: 60px;
                background: #027BFF;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(2, 123, 255, 0.4);
                transition: all 0.3s ease;
                text-decoration: none;
            }
    
            .call-button:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(2, 123, 255, 0.6);
            }
    
            .call-button::before {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: rgba(2, 123, 255, 0.3);
                animation: pulse 2s infinite;
            }
    
            .call-button::after {
                content: '';
                position: absolute;
                width: 140%;
                height: 140%;
                border-radius: 50%;
                background: rgba(2, 123, 255, 0.15);
                animation: pulse 2s infinite 0.5s;
            }
    
            .phone-icon {
                width: 24px;
                height: 24px;
                fill: white;
                z-index: 10;
                position: relative;
            }
    
            @keyframes pulse {
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
                .call-button-container {
                    bottom: 147px;
                    right: 20px;
                }
    
                .call-button {
                    width: 50px;
                    height: 50px;
                }
    
                .phone-icon {
                    width: 22px;
                    height: 22px;
                }
            }
    
            .call-button.clicked::before {
                animation: clickRipple 0.6s ease-out;
            }
    
            @keyframes clickRipple {
                0% {
                    transform: scale(1);
                    opacity: 0.8;
                }
    
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        </style>
    @endif
   
    @if ($landingCallEnabled && $landingCallNumber)
        <script>
            const callButton = document.getElementById('callButton');

            if (callButton) {
                callButton.addEventListener('click', function() {
                    this.classList.add('clicked');
                    setTimeout(() => {
                        this.classList.remove('clicked');
                    }, 600);
                });

                callButton.addEventListener('click', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(100);
                    }
                });
            }
        </script>
    @endif

    @php
        $showLandingWhatsApp = setting('general', 'show_whatsapp_button_landing', '1') == '1';
        $landingNumberRaw = setting('general', 'landing_whatsapp_number', '') ?: setting('general', 'whatsapp_number', '');
        $landingNumberFormatted = preg_replace('/\D+/', '', $landingNumberRaw);
        $landingMessage = urlencode(setting('general', 'landing_whatsapp_message', setting('general', 'whatsapp_message', "Hello, I'm interested in your services")));
    @endphp

    @if ($showLandingWhatsApp && !empty($landingNumberFormatted))
        <!-- Whatsapp fixed button for chat -->
        <div class="whatsapp-button-container">
            <a href="https://wa.me/{{ $landingNumberFormatted }}?text={{ $landingMessage }}"
                class="whatsapp-button" id="whatsappButton">
                <svg class="whatsapp-icon" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.569-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488" />
                </svg>
            </a>
        </div>
        <style>
            .whatsapp-button-container {
                position: fixed;
                bottom: 100px;
                right: 30px;
                z-index: 1000;
            }

            .whatsapp-button {
                position: relative;
                width: 60px;
                height: 60px;
                background: #25d366;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .whatsapp-button:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
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
                width: 30px;
                height: 30px;
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
                .whatsapp-button-container {
                    bottom: 80px;
                    right: 20px;
                }

                .whatsapp-button {
                    width: 55px;
                    height: 55px;
                }

                .whatsapp-icon {
                    width: 26px;
                    height: 26px;
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

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-5px);
                }
            }
        </style>
        <script>
            const whatsappButton = document.getElementById('whatsappButton');

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
        </script>
    @endif

    <!-- Messenger fixed button for chat -->
    {{-- <div class="messenger-button-container">
        <a href="https://m.me/yourpagename" class="messenger-button" id="messengerButton">
            <svg class="messenger-icon" viewBox="0 0 24 24">
                <path
                    d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M8.5,14.5L5.19,10.42L11.19,7.5L15.5,9.5L18.81,13.58L12.81,16.5L8.5,14.5Z" />
            </svg>
        </a>
    </div>
    <style>
        .messenger-button-container {
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 1000;
        }

        .messenger-button {
            position: relative;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #0084ff, #00a3ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 132, 255, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .messenger-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 132, 255, 0.6);
        }

        .messenger-button::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 132, 255, 0.3);
            animation: messengerPulse 2s infinite;
        }

        .messenger-button::after {
            content: '';
            position: absolute;
            width: 140%;
            height: 140%;
            border-radius: 50%;
            background: rgba(0, 132, 255, 0.15);
            animation: messengerPulse 2s infinite 0.5s;
        }

        .messenger-icon {
            width: 28px;
            height: 28px;
            fill: white;
            z-index: 10;
            position: relative;
        }

        @keyframes messengerPulse {
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
            .messenger-button-container {
                bottom: 80px;
                right: 20px;
            }

            .messenger-button {
                width: 55px;
                height: 55px;
            }

            .messenger-icon {
                width: 25px;
                height: 25px;
            }
        }

        .messenger-button.clicked::before {
            animation: messengerRipple 0.6s ease-out;
        }

        @keyframes messengerRipple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .messenger-button {
            animation: messengerFloat 3s ease-in-out infinite;
        }

        @keyframes messengerFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .messenger-button {
            animation: messengerFloat 3s ease-in-out infinite, shadowPulse 2s ease-in-out infinite;
        }

        @keyframes shadowPulse {

            0%,
            100% {
                box-shadow: 0 4px 20px rgba(0, 132, 255, 0.4);
            }

            50% {
                box-shadow: 0 8px 30px rgba(0, 132, 255, 0.6);
            }
        }
    </style>
    <script>
        const messengerButton = document.getElementById('messengerButton');

        messengerButton.addEventListener('click', function() {
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 600);
        });

        messengerButton.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
        });

        messengerButton.addEventListener('click', function() {
        });
    </script> --}}

    {{-- Fraud Protection System --}}
    <script src="{{ asset('js/fraud-protection.js') }}"></script>
    <script>
        // Initialize Fraud Protection with settings from backend
        const fraudProtectionSettings = @json(app(\App\Services\FraudProtectionService::class)->getFrontendSettings());
        const fraudProtection = new FraudProtection(fraudProtectionSettings);
        window.fraudProtectionInstance = fraudProtection;
    </script>

    <style>
        .strike-through {
            font-family: initial;
        }

        .trust-value {
            font-family: initial;
        }

        .bangla-number {
                    font-family: initial;
                }

        .product-description {
            font-family: initial;
        }

        span.organic-badge {
            font-family: initial;
        }

        .countdown-container {
            font-family: initial;
        }
        span.item-total {
            font-family: initial !important;
        }
        .variation-name {
            font-family: auto;
        }
        span#itemTotal {
    font-family: auto;
}

    </style>

</body>

</html>
