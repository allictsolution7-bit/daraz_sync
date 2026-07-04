{{-- Order Form Component --}}
@php
    // This component will receive all necessary data as props
    // Ensure combinationMap is available
    if (!isset($combinationMap)) {
        $combinationMap = [];
    }
@endphp

<style>
    /* ========================================
       ORDER FORM SECTION
       ======================================== */
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

    /* Variation selector styles (matched to legacy landing page) */
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
        box-shadow: 0 2px 12px rgba(229, 57, 53, 0.1);
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

    .order-item {
        display: flex;
        align-items: center;
        padding: 0px 0;
    }

    .item-image {
        width: 60px;
        height: 60px;
        margin-right: 15px;
        margin-bottom: 5px;
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
        margin-left: 5px;
        font-size: 14px;
        color: #757575;
        font-family: inherit;
        margin-top: -8px;
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
        .order-form-section {
            padding: 16px 10px;
        }
        .order-form-wrapper {
            gap: 0;
        }

        button.order-submit-btn {
            position: fixed;
            bottom: 5px;
            left: 5px;
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
            margin-bottom: 10px;
        }
    }

    @media (max-width: 480px) {
        .order-form-section {
            padding: 13px 10px;
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
</style>

<!-- Order Form Section -->
<section id="order-section" class="order-form-section">
    <div class="order-container">
        <h2 class="order-title">{{ $landingPage->order_form_title ?? 'অর্ডার করুন' }}</h2>
        <form id="landing-order-form">
            @csrf
            <div class="order-form-wrapper">
                <div class="order-form-left">

                    <?php if($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->count() > 0): ?>
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

                </div>
                <div class="order-form-right">
                    <div class="product-summary">
                        <div class="summary-title-container">
                            <h3 class="summary-title">পণ্যের বিবরণ</h3>
                            <div class="alert blink-alert">
                                🚨 Stock শেষ হয়ে যাচ্ছে!
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
                                                ছাড</span>
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
                            <!-- @if ($nagadEnabled)
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
                            @endif -->

                            @php
                                $autoGateways = \App\Models\PaymentGateway::enabled()->orderBy('sort_order')->get();
                            @endphp
                            @if($autoGateways->count() > 0)
                                @foreach($autoGateways as $gw)
                                <div class="payment-option">
                                    <label>
                                        <input type="radio" name="payment_method" value="{{ $gw->provider }}">
                                        <i class="fas fa-credit-card" style="color: #2e7d32; margin-right: 4px;"></i>
                                        {{ $gw->name }}
                                    </label>
                                    <p class="payment-note">অনলাইন পেমেন্ট গেটওয়ে এর মাধ্যমে পেমেন্ট করুন।</p>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="landing_page_id" value="{{ $landingPage->id }}">
            <input type="hidden" name="price" value="{{ $price }}">
            <input type="hidden" name="combination_id" id="selected_combination_id" value="">
            <input type="hidden" name="price" id="selected_price" value="{{ $price }}">
            <input type="hidden" name="shipping"
                value="{{ $activeShippingOptions ? reset($activeShippingOptions)['cost'] : $shippingSetting->flat_rate }}">

            <!-- UTM & Click ID tracking (populated by JavaScript) -->
            <input type="hidden" name="utm_source" id="utm_source" value="">
            <input type="hidden" name="utm_medium" id="utm_medium" value="">
            <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
            <input type="hidden" name="utm_content" id="utm_content" value="">
            <input type="hidden" name="utm_term" id="utm_term" value="">
            <input type="hidden" name="fbclid" id="fbclid" value="">
            <input type="hidden" name="gclid" id="gclid" value="">
            <input type="hidden" name="ttclid" id="ttclid" value="">

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
        let freeShippingThreshold = window.shippingSettings.freeShippingThreshold;
        let fallbackCost = null;
        
        // Check for specific free shipping threshold rules
        if (window.shippingSettings.specificRules) {
            window.shippingSettings.specificRules.forEach(rule => {
                if (rule.rule_type === 'free_shipping' && rule.free_shipping_threshold) {
                    freeShippingThreshold = rule.free_shipping_threshold;
                    fallbackCost = rule.rule_value;
                }
            });
        }
        
        if (itemTotal >= freeShippingThreshold) {
            shippingCost = 0;
        } else if (fallbackCost !== null) {
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
                price = matchingCombination.offer_price || matchingCombination.regular_price || matchingCombination.price || 0;
                combinationId = matchingCombination.id;
                // Use combination's featured image if available
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

            // Update product image
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
                img.style.display = 'block';
            };
            testImg.onerror = function() {
                // Fallback to product thumb image
                img.src = '{{ asset('storage/' . $product->thumb_image) }}';
                img.style.display = 'block';
            };
            testImg.src = imagePath;
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

    // Capture UTM parameters and click IDs from URL
    function captureTrackingParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const trackingFields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'];

        trackingFields.forEach(function(field) {
            const value = urlParams.get(field);
            const input = document.getElementById(field);
            if (input && value) {
                input.value = value;
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Capture UTM params from URL first
        captureTrackingParams();

        var variationGroups = document.querySelectorAll('.variation-btn-group');
        if (variationGroups.length > 0) {
            var allRadios = document.querySelectorAll('.variation-radio');

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

            ensureInitialImage();
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

        // Shipping area change handler
        const shippingRadios = document.querySelectorAll('input[name="shipping_area"]');
        shippingRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                setTimeout(updatePrices, 100);
            });
        });

        // Make the whole shipping option clickable
        document.addEventListener('click', function(event) {
            const option = event.target.closest('.shipping-option');
            if (!option) return;

            const radio = option.querySelector('input[type="radio"]');
            if (radio && event.target !== radio) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
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
                        if (response.order_id) {
                            if (response.requires_redirect) {
                                // Automated payment gateway - redirect to payment page
                                var submitBtn = document.querySelector('.order-submit-btn');
                                if (submitBtn) {
                                    submitBtn.disabled = true;
                                    submitBtn.textContent = 'পেমেন্ট গেটওয়েতে যাচ্ছে...';
                                }
                                $.ajax({
                                    url: '/payment/initiate',
                                    type: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    contentType: 'application/json',
                                    data: JSON.stringify({ order_id: response.order_id, provider: response.provider }),
                                    success: function(result) {
                                        if (result.redirect_url) {
                                            window.location.href = result.redirect_url;
                                        } else {
                                            alert(result.message || 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।');
                                            window.location.href = "{{ url('thank-you') }}/" + response.order_id;
                                        }
                                    },
                                    error: function(xhr) {
                                        var errMsg = 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errMsg = xhr.responseJSON.message;
                                        }
                                        alert(errMsg);
                                        if (submitBtn) {
                                            submitBtn.disabled = false;
                                            submitBtn.textContent = '{{ $landingPage->order_place_button_text ?? "অর্ডার Confirm করুন" }}';
                                        }
                                    }
                                });
                            } else {
                                // Manual payment or COD - show success and redirect
                                alert('অর্ডার সফলভাবে সম্পন্ন হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');
                                window.location.href = "{{ url('thank-you') }}/" + response.order_id;
                            }
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

        // Order Submit Button Animation
        const orderSubmitBtn = document.querySelector('.order-submit-btn');

        function triggerShake() {
            if (orderSubmitBtn) {
                orderSubmitBtn.style.animation = 'shake 0.8s ease';
                setTimeout(() => {
                    orderSubmitBtn.style.animation = '';
                }, 3000);
            }
        }

        // Shake immediately when page loads
        triggerShake();
        setInterval(triggerShake, 4000); // every 4 seconds
    });
</script>
