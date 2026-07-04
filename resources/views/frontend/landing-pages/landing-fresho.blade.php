<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Add Google Font for Baloo Da 2 -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* মৌলিক রিসেট */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* সেকশন স্টাইলিং */
        .natural-products-showcase {
            padding: 50px 20px;
            background-color: #fcf8f0;
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

        /* কন্টেন্ট স্টাইলিং */
        .product-content {
            flex: 1;
            min-width: 300px;
        }

        .organic-badge {
            display: inline-block;
            background-color: #ffd54f;
            color: #5d4037;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 36px;
            color: #5d4037;
            margin-bottom: 20px;
            font-family: 'SolaimanLipi', Arial, sans-serif;
        }

        .product-description {
            font-size: 24px;
            color: #6d4c41;
            line-height: 1.8;
            margin-bottom: 25px;
            font-family: 'SolaimanLipi', Arial, sans-serif;
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
            font-family: 'SolaimanLipi', Arial, sans-serif;
        }

        .check-icon {
            color: #ffa000;
            margin-right: 10px;
            font-weight: bold;
        }

        /* বাটন স্টাইলিং */
        .product-cta {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 100px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            font-family: 'SolaimanLipi', Arial, sans-serif;
        }

        .primary-btn {
            background-color: #ffa000;
            color: white;
        }

        .primary-btn:hover {
            background-color: #ff8f00;
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
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
            font-family: 'SolaimanLipi', Arial, sans-serif;
        }

        /* রেসপন্সিভ অ্যাডজাস্টমেন্টস */
        @media (max-width: 768px) {
            .product-row {
                flex-direction: column;
                gap: 0px;
            }

            .product-content,
            .product-image {
                width: 100%;
            }

            .section-title {
                font-size: 28px;
            }
        }

        /* বাংলা ফন্ট লোড করার জন্য */
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('https://cdn.jsdelivr.net/gh/maateen/bangla-web-fonts/fonts/SolaimanLipi/SolaimanLipi.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
    </style>
    <style>
        /* Existing styles... */

        .section-title {
            font-size: 36px;
            color: #5d4037;
            margin-bottom: 20px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .product-description {
            color: #6d4c41;
            line-height: 1.8;
            margin-bottom: 25px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .product-benefits li {
            margin-bottom: 10px;
            color: #6d4c41;
            display: flex;
            align-items: center;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .btn {
            display: inline-block;
            padding: 12px 100px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            font-family: 'Baloo Da 2', sans-serif;
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
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 5px 15px rgba(255, 111, 0, 0.3);
            font-family: 'Baloo Da 2', sans-serif;
        }

        /* Enhanced responsive styles */
        @media (max-width: 768px) {
            .product-row {
                flex-direction: column;
            }

            .product-content,
            .product-image {
                width: 100%;
            }

            .section-title {
                font-size: 28px;
                text-align: center;
            }

            .organic-badge {
                display: table;
                margin: 0 auto 20px auto;
            }

            .product-description {
                text-align: center;
                font-size: 16px;
            }

            .product-benefits {
                max-width: 300px;
                margin: 0 auto 30px auto;
            }

            .product-cta {
                justify-content: center;
            }

            .best-seller-badge {
                width: 70px;
                height: 70px;
                font-size: 14px;
                right: 10px;
                bottom: 10px;
            }
        }

        /* Small mobile devices */
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
            }

            .section-title {
                font-size: 24px;
            }

            .organic-badge {
                font-size: 14px;
            }
        }
    </style>
    <style>
        /* মধুর বৈশিষ্ট্য বিভাগ স্টাইলিং */
        .honey-features {
            padding: 80px 20px;
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
            margin-bottom: 50px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
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
            margin-bottom: 20px;
        }

        .feature-name {
            font-size: 22px;
            color: #5d4037;
            margin-bottom: 15px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .feature-desc {
            color: #6d4c41;
            line-height: 1.6;
            font-family: 'Baloo Da 2', sans-serif;
        }

        /* গ্রাহক পর্যালোচনা বিভাগ স্টাইলিং */
        .customer-reviews {
            padding: 80px 20px;
            background-color: #f5f5f5;
        }

        .reviews-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .reviews-title {
            text-align: center;
            font-size: 36px;
            color: #5d4037;
            margin-bottom: 50px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .reviews-slider {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }

        .review-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            max-width: 350px;
            flex: 1 1 300px;
        }

        .review-rating {
            color: #ffc107;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .review-text {
            color: #6d4c41;
            line-height: 1.8;
            margin-bottom: 20px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .reviewer-info {
            display: flex;
            align-items: center;
        }

        .reviewer-avatar {
            font-size: 30px;
            margin-right: 15px;
        }

        .reviewer-name {
            font-weight: bold;
            color: #5d4037;
            font-family: 'Baloo Da 2', sans-serif;
        }

        /* রেসপন্সিভ অ্যাডজাস্টমেন্টস */
        @media (max-width: 768px) {

            .features-title,
            .reviews-title {
                font-size: 28px;
                margin-bottom: 30px;
            }

            .feature-card {
                padding: 20px;
            }

            .feature-name {
                font-size: 20px;
            }
        }

        @media (max-width: 480px) {

            .honey-features,
            .customer-reviews {
                padding: 20px 15px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .review-card {
                padding: 20px;
            }
        }
    </style>
    <style>
        /* অর্ডার ফর্ম বিভাগ স্টাইলিং */
        .order-form-section {
            padding: 80px 20px;
            background-color: #f0f7f0;
        }

        .order-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .order-title {
            text-align: center;
            font-size: 32px;
            color: #e53935;
            margin-bottom: 40px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .order-form-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background-color: #fff;
            border-radius: 15px;
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
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #5d4037;
            font-weight: 500;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f0f7f0;
            transition: border-color 0.3s;
            font-family: 'Baloo Da 2', sans-serif;
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
            font-family: 'Baloo Da 2', sans-serif;
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
            width: 100%;
            padding: 15px;
            background-color: #e53935;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Baloo Da 2', sans-serif;
            margin-top: 30px;
        }

        .order-submit-btn:hover {
            background-color: #d32f2f;
        }

        /* Product Summary Styling */
        .product-summary {
            background-color: #fff;
            border-radius: 8px;
        }

        .summary-title {
            font-size: 24px;
            color: #5d4037;
            margin-bottom: 20px;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .product-options {
            margin-bottom: 20px;
        }

        .option-group {
            display: flex;
            gap: 20px;
        }

        .quantity-selector {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
        }

        .qty-btn {
            width: 36px;
            height: 36px;
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
            width: 50px;
            height: 36px;
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

        .order-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
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
            font-family: 'Baloo Da 2', sans-serif;
        }

        .item-name {
            font-weight: 500;
        }

        .item-total {
            font-weight: bold;
        }

        .order-summary {
            padding: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-family: 'Baloo Da 2', sans-serif;
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
            font-family: 'Baloo Da 2', sans-serif;
        }

        .payment-option {
            display: flex;
            flex-direction: column;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            background-color: #f9f9f9;
            align-items: baseline;
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
            font-family: 'Baloo Da 2', sans-serif;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .order-form-wrapper {
                flex-direction: column-reverse;
            }

            .order-form-left,
            .order-form-right {
                width: 100%;
            }

            .order-title {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .order-form-section {
                padding: 20px 15px;
            }

            .order-form-left,
            .order-form-right {
                padding: 20px;
            }

            .item-details {
                flex-wrap: wrap;
            }

            .item-name {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
    <style>
        /* হেডার স্টাইলিং */
        .site-header {
            background-color: #f5f5f5;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            font-family: 'Baloo Da 2', sans-serif;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
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
            .header-container {
                flex-direction: column;
                gap: 10px;
                text-align: center;
                padding: 10px 20px;
            }

            .copyright-text {
                order: 2;
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
    </style>
</head>

<body>
    <!-- প্রাকৃতিক পণ্য প্রদর্শনী বিভাগ -->
    <section class="natural-products-showcase">
        <div class="showcase-container">
            <div class="product-row">
                <div class="product-content">
                    <span class="organic-badge">১০০% প্রাকৃতিক</span>
                    <h2 class="section-title">বিশুদ্ধ প্রাকৃতিক মধু</h2>
                    <p class="product-description">
                        প্রকৃতির কোলে অবিকৃত বন থেকে সংগ্রহ করা আমাদের প্রিমিয়াম মধুতে আছে সব প্রাকৃতিক এনজাইম ও
                        অ্যান্টিঅক্সিডেন্ট। একেবারে কাঁচা ও অপ্রক্রিয়াজাত – তাই এর স্বাদ যেমন খাঁটি, তেমনি উপকারিতাও
                        ভরপুর।
                    </p>


                    <div class="customer-trust-indicators">
                        <div class="trust-item">
                            <div class="trust-icon">⭐</div>
                            <div class="trust-info">
                                <div class="trust-value">4.9/5</div>
                                <div class="trust-label">গ্রাহক রেটিং</div>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon">👨‍👩‍👧‍👦</div>
                            <div class="trust-info">
                                <div class="trust-value">১০০০+</div>
                                <div class="trust-label">সন্তুষ্ট গ্রাহক</div>
                            </div>
                        </div>
                        <div class="trust-item">
                            <div class="trust-icon">🔄</div>
                            <div class="trust-info">
                                <div class="trust-value">৭ দিন</div>
                                <div class="trust-label">রিটার্ন গ্যারান্টি</div>
                            </div>
                        </div>
                    </div>

                    <style>
                        /* Customer Trust Indicators Styling */
                        .customer-trust-indicators {
                            display: flex;
                            justify-content: space-between;
                            gap: 15px;
                            margin-bottom: 20px;
                            padding: 18px 20px;
                            background-color: #fff8e1;
                            border-radius: 12px;
                            border-left: 4px solid #ffa000;
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
                            color: #ffa000;
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
                            color: #5d4037;
                            font-family: 'Baloo Da 2', sans-serif;
                        }

                        .trust-label {
                            font-size: 14px;
                            color: #6d4c41;
                            font-family: 'Baloo Da 2', sans-serif;
                        }

                        @media (max-width: 768px) {
                            .customer-trust-indicators {
                                padding: 10px;
                                flex-wrap: wrap;
                                justify-content: center;
                                gap: 0px;
                            }

                            .trust-item {
                                min-width: 100px;
                                justify-content: flex-start;
                                margin-bottom: 5px;
                                width: auto;
                                flex: 0 0 auto;
                            }
                        }

                        @media (max-width: 480px) {
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
                        }
                    </style>


                    <div class="product-cta">
                        <a href="#" class="btn primary-btn">এখনই কিনুন</a>
                    </div>
                </div>
                <div class="product-image">
                    <img src="honey landing.png" alt="প্রাকৃতিক মধু পণ্য">
                    <div class="best-seller-badge">সেরা মধু!</div>
                </div>
            </div>
        </div>
    </section>

    </section>

    <!-- Dynamic Feature List Sections -->
    @foreach ($landingPage->sections()->where('section_type', 'feature_list')->get() as $featureListSection)
        <section class="honey-features">
            <div class="features-container">
                <h2 class="features-title">{{ $featureListSection->title ?? 'সরিষা ফুলের মধুর বৈশিষ্ট্যঃ' }}</h2>
                <div class="features-grid">
                    @if ($featureListSection->feature_items && is_array($featureListSection->feature_items))
                        @foreach ($featureListSection->feature_items as $item)
                            <div class="feature-card">
                                <div class="feature-icon">{{ $item['emoji'] ?? '•' }}</div>
                                <h3 class="feature-name">{{ $item['text'] ?? '' }}</h3>
                            </div>
                        @endforeach
                    @endif
                </div>
                <style>
                    .product-cta2 {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-top: 30px;
                    }
                </style>
                <div class="product-cta2">
                    <a href="{{ $landingPage->order_button_url ?? '#order-section' }}" class="btn primary-btn">
                        {{ $landingPage->order_button_text ?? 'এখনই কিনুন' }}
                    </a>
                </div>
            </div>
        </section>
    @endforeach

    <!-- Dynamic Testimonials Section -->
    @if ($landingPage->sectionsByType('testimonials')->count() > 0)
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
                        {{ $testimonialsSection->title ?? 'আমাদের সন্তুষ্ট গ্রাহকদের মতামত' }}
                    </h2>
                    <div class="reviews-slider">
                        @foreach ($testimonialsSection->testimonials as $testimonial)
                            <div class="review-card">
                                @if (!empty($testimonial['rating']))
                                    <div class="review-rating">
                                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                                            ★
                                        @endfor
                                    </div>
                                @endif
                                @if (!empty($testimonial['text']))
                                    <p class="review-text">{{ $testimonial['text'] }}</p>
                                @endif
                                <div class="reviewer-info">
                                    @if (!empty($testimonial['image']))
                                        <div class="reviewer-avatar">
                                            <img src="{{ asset($testimonial['image']) }}" alt="{{ $testimonial['author'] ?? 'Customer' }}">
                                        </div>
                                    @else
                                        <div class="reviewer-avatar">👤</div>
                                    @endif
                                    @if (!empty($testimonial['author']))
                                        <div class="reviewer-name">{{ $testimonial['author'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="product-cta2">
                    <a href="{{ $landingPage->order_button_url ?? '#order-section' }}" class="btn primary-btn">
                        {{ $landingPage->order_button_text ?? 'এখনই কিনুন' }}
                    </a>
                </div>
            </section>
        @endif
    @endif

    </section>

    <!-- অর্ডার ফর্ম বিভাগ -->
    <section class="order-form-section">
        <div class="order-container">
            <h2 class="order-title">"{{ $landingPage->heading ?? 'Mustard Honey' }}" কেনার জন্য, নিচের ফর্মটি সম্পূর্ণ পূরণ করুন</h2>

            <div class="order-form-wrapper">
                <div class="order-form-left">
                    <form id="orderForm">
                        <div class="form-group">
                            <label for="name">নাম (Name)*</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="mobile">মোবাইল নাম্বার (Mobile No)*</label>
                            <input type="tel" id="mobile" class="form-control" required>
                            <small class="form-text">মোবাইল নম্বরটি অবশ্যই সঠিক হতে হবে</small>
                        </div>

                        <div class="form-group">
                            <label>শিপিং এরিয়া (Shipping Area)</label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" id="dhaka" name="shipping" value="dhaka" checked>
                                    <label for="dhaka">ঢাকা জেলা মধ্যে (In Dhaka District)</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="outside" name="shipping" value="outside">
                                    <label for="outside">ঢাকা জেলা বাহিরে (Outside of Dhaka District)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">ডেলিভারি ঠিকানা (Delivery Address)*</label>
                            <textarea id="address" class="form-control" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="order-submit-btn">Place Order ৳<span
                                id="totalPrice">1,150</span></button>
                    </form>
                </div>

                <div class="order-form-right">
                    <div class="product-summary">
                        <h3 class="summary-title">{{ $landingPage->heading ?? 'Mustard Honey' }}</h3>

                        <div class="product-options">
                            <div class="option-group">
                                <div class="radio-item">
                                    <input type="radio" id="2kg" name="weight" value="2kg" checked>
                                    <label for="2kg">2 KG</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="1kg" name="weight" value="1kg">
                                    <label for="1kg">1 KG</label>
                                </div>
                            </div>
                        </div>

                        <div class="quantity-selector">
                            <span>QTY(pcs)</span>
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn minus-btn">−</button>
                                <input type="number" id="quantity" value="1" min="1" max="10">
                                <button type="button" class="qty-btn plus-btn">+</button>
                            </div>
                            <span class="product-price">৳<span id="itemPrice">1,100</span></span>
                        </div>

                        <div class="order-item">
                            <div class="item-image">
                                <img src="https://naturobd.com/storage/IYrsnIeF5cnrRFrAw39BmKLxdY8qNTTbklI8cprK.webp"
                                    alt="{{ $landingPage->heading ?? 'Mustard Honey' }}">
                            </div>
                            <div class="item-details">
                                <span class="item-name">{{ $landingPage->heading ?? 'Mustard Honey' }} 2 KG</span>
                                <div class="item-quantity">
                                    <span>×</span>
                                    <span id="itemQuantity">1</span>
                                </div>
                                <span class="item-total">৳ <span id="itemTotal">1100</span></span>
                            </div>
                        </div>

                        <div class="order-summary">
                            <div class="summary-row">
                                <span>Shipping Cost</span>
                                <span>৳<span id="shippingCost">50</span></span>
                            </div>
                            <div class="summary-row subtotal">
                                <span>Subtotal</span>
                                <span>৳<span id="subtotal">1,150</span></span>
                            </div>
                        </div>

                        <div class="payment-method">
                            <h4>Payment Type</h4>
                            <div class="payment-option">
                                <input type="radio" id="cod" name="paymentMethod" value="cod" checked>
                                <label for="cod">Cash On Delivery</label>
                                <p class="payment-note">Pay with cash upon delivery.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <header class="site-header">
        <div class="header-container">
            <div class="copyright-text">© 2025 Honey Landing | সর্বস্বত্ব সংরক্ষিত</div>
            <nav class="main-nav">
                <ul class="nav-menu">
                    <li class="nav-item"><a href="#" class="nav-link">প্রাইভেসি পলিসি</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">শর্তাবলী</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">যোগাযোগ</a></li>
                </ul>
            </nav>
        </div>
    </header>


</body>

</html>
