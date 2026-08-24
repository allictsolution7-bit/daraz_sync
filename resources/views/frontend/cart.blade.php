@extends('frontend.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
<style>
    /* Cart Page Styles */
    .cart-container {
        margin: 15px auto;
        padding: 0 15px;
    }

    /* Combo Item Styles */
    .combo-item {
        background-color: #f8f9fa;
    }

    .combo-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .combo-badge {
        background-color: #059669;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .combo-selections {
        margin-top: 8px;
    }

    .combo-selection-item {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
        padding-left: 15px;
        position: relative;
    }

    .combo-selection-item:before {
        content: "•";
        position: absolute;
        left: 0;
        color: #059669;
        font-weight: bold;
    }

    .product-name {
        font-weight: 500;
    }

    .variation-name {
        color: #888;
    }

    .cart-title {
        font-size: 21px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
        position: relative;
        padding-left: 15px;
    }

    .cart-title:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background-color: var(--primary-color);
    }

    .cart-divider {
        border: none;
        height: 1px;
        background-color: var(--border-color);
        margin-bottom: 30px;
    }

    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .cart-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-weight: 500;
        border-left: 4px solid #2e7d32;
    }

    .cart-area {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        padding: 16px;
        overflow: visible;
    }

    .cart-table {
        display: flex;
        flex-direction: column;
        gap: 14px;
        width: 100%;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 12px;
        align-items: flex-start;
        padding: 14px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: #fff;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }

    .cart-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .cart-top {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        justify-content: space-between;
        padding-right: 36px;
        width: 100%;
        box-sizing: border-box;
    }

    .cart-remove {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
    }

    .remove-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background-color: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        font-size: 18px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .remove-btn:hover {
        background-color: #ef4444;
        color: #ffffff;
        transform: scale(1.08);
    }

    .cart-image {
        width: 90px;
        height: 85px;
        flex-shrink: 0;
    }

    .cart-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s ease;
        display: block;
    }

    .cart-image img:hover {
        transform: scale(1.04);
    }

    .cart-product-title {
        width: 100%;
        line-height: 1.4;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .product-link {
        color: var(--secondary-color);
        font-weight: 600;
        text-decoration: none;
        font-size: 14.5px;
        line-height: 1.4;
        display: block;
        word-break: break-word;
        overflow-wrap: break-word;
        transition: color 0.2s;
    }

    .product-link:hover {
        color: var(--primary-color);
    }

    .variation-details {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .variation-item {
        font-size: 13px;
        color: var(--text-color);
        background-color: var(--light-color);
        padding: 5px 10px;
        border-radius: 4px;
        margin-top: 5px;
        margin-right: 5px;
        display: inline-block;
        border-left: 2px solid var(--primary-color);
    }

    .variation-name {
        font-weight: 600;
        color: var(--secondary-color);
    }

    .variation-qty {
        color: var(--text-light);
        margin: 0 5px;
    }

    .variation-price {
        font-weight: 600;
        color: var(--primary-color);
    }

    /* Quantity Controls Styles */
    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 28px;
        height: 28px;
        border: 1px solid var(--border-color);
        background-color: var(--light-color);
        color: var(--secondary-color);
        border-radius: 4px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover:not(:disabled) {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-display {
        min-width: 30px;
        text-align: center;
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 14px;
    }

    .quantity-input {
        min-width: 40px;
        text-align: center;
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 14px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 4px 8px;
        background-color: white;
        outline: none;
        transition: border-color 0.3s;
    }

    .quantity-input:focus {
        border-color: var(--primary-color);
    }

    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-input[type=number] {
        -moz-appearance: textfield;
    }

    /* Loading state styles */
    .quantity-btn.loading {
        position: relative;
        color: transparent !important;
    }

    .quantity-btn.loading::after {
        content: '';
        position: absolute;
        width: 12px;
        height: 12px;
        border: 2px solid transparent;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
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

    .cart-price,
    .cart-quantity,
    .cart-total {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 14px;
        color: var(--text-color);
        background: #f8fafc;
        padding: 8px 10px;
        border-radius: 8px;
        width: 100%;
    }

    .price-text,
    .total-price {
        font-weight: 600;
        color: var(--primary-color);
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        align-items: stretch;
    }

    .meta-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--secondary-color);
        letter-spacing: 0.3px;
    }

    .coupon-section {
        display: flex;
        padding: 20px;
        gap: 10px;
        border-top: 1px solid var(--border-color);
        align-items: center;
        flex-wrap: wrap;
    }


    .coupon-input {
        flex: 1;
        min-width: 200px;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s;
    }

    .coupon-input:focus {
        border-color: var(--primary-color);
    }

    .coupon-btn,
    .continue-shopping {
        padding: 12px 20px;
        background-color: var(--light-color);
        color: var(--secondary-color);
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        text-align: center;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .coupon-btn:hover,
    .continue-shopping:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    .continue-shopping {
        background-color: var(--primary-color);
        color: white;
    }

    .continue-shopping:hover {
        background-color: var(--secondary-color);
        opacity: 0.9;
    }

    .cart-sidebar {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .sidebar-title {
        font-size: 17px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .cart-total-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 20px;
    }

    .total-label {
        font-size: 16px;
        color: var(--text-color);
    }

    .total-value {
        font-size: 18px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .checkout-btn {
        display: block;
        width: 100%;
        padding: 15px;
        background-color: var(--primary-color);
        color: white;
        text-align: center;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .checkout-btn:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.7s;
    }

    .checkout-btn:hover:before {
        left: 100%;
    }

    .checkout-btn:hover {
        background-color: var(--secondary-color);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .promo-video {
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .promo-video iframe {
        width: 100%;
        border-radius: 8px;
    }

    .empty-cart-message {
        text-align: center;
        padding: 40px 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
    }

    .empty-cart-message h3 {
        font-size: 24px;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .empty-cart-message p {
        color: var(--text-color);
        margin-bottom: 20px;
    }

    /* Responsive Cart Styles */
    @media (max-width: 992px) {
        .cart-container {
            max-width: 100%;
            margin: 10px auto;
            padding: 0 5px;
        }

        .cart-title {
            font-size: 20px;
        }

        .cart-layout {
            grid-template-columns: 1fr;
        }

        .cart-sidebar {
            order: -1;
            position: static;
        }


        .cart-table {
            min-width: 100%;
        }

        .cart-area {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .cart-area::-webkit-scrollbar {
            height: 6px;
        }

        .cart-area::-webkit-scrollbar-track {
            background: var(--light-color);
            border-radius: 10px;
        }

        .cart-area::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .variation-details {
            padding: 0 0px;
        }

        .meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .coupon-section {
            flex-wrap: wrap;
        }

        .coupon-input {
            width: 100%;
            flex: 1 0 100%;
            margin-bottom: 10px;
        }

        .coupon-btn,
        .continue-shopping {
            flex: 1;
        }

        .cart-item {
            grid-template-columns: 80px 1fr;
            align-items: flex-start;
        }

        .cart-image img {
            width: 70px;
            height: 70px;
        }

        .meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .cart-price,
        .cart-quantity,
        .cart-total {
            padding: 8px;
        }
    }

    @media (max-width: 576px) {
        .cart-container {
            margin: 10px auto;
            padding: 0 8px;
        }

        .cart-title {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .cart-area {
            padding: 12px;
        }

        .cart-table {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .product-link {
            font-size: 14px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .variation-item {
            font-size: 11px;
            padding: 4px 8px;
            margin-top: 4px;
        }

        .combo-selection-item {
            font-size: 11px;
        }

        .sidebar-title {
            font-size: 16px;
        }

        .checkout-btn {
            padding: 14px;
            font-size: 15px;
        }

        .quantity-controls {
            gap: 6px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .quantity-input {
            min-width: 45px;
            font-size: 14px;
        }

        .cart-item {
            grid-template-columns: 80px 1fr;
            padding: 10px;
            gap: 10px;
        }

        .cart-image {
            width: 80px;
            height: 80px;
        }

        .cart-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .cart-body {
            gap: 6px;
        }

        .meta-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .cart-price,
        .cart-quantity,
        .cart-total {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 8px;
        }

        .meta-label {
            font-size: 11px;
        }

        .price-text,
        .total-price {
            font-size: 14px;
        }

        .remove-btn {
            width: 28px;
            height: 28px;
            line-height: 28px;
            font-size: 16px;
        }

        .cart-remove {
            top: 8px;
            right: 8px;
        }

        .coupon-section {
            padding: 15px;
            gap: 8px;
        }

        .coupon-input {
            font-size: 13px;
            padding: 10px 12px;
        }

        .coupon-btn,
        .continue-shopping {
            font-size: 13px;
            padding: 10px 16px;
            min-width: 100px;
        }

        .cart-sidebar {
            padding: 20px;
        }

        .total-label {
            font-size: 14px;
        }

        .total-value {
            font-size: 16px;
        }
    }
</style>
@endsection

@section('content')
<div class="base-container profile-container">
    <div class="profile-layout">
        <!-- Sidebar Menu -->
        @include('frontend.user.partials.sidebar')

        <!-- Main Cart Content -->
        <div class="cart-container" style="margin: 0; padding: 0; width: 100%;">
            <h1 class="cart-title">Shopping Cart</h1>
            <hr class="cart-divider">

            <div class="cart-layout">

        @if (session('success'))
        <div class="cart-success">{{ session('success') }}</div>
        @endif

        <div class="cart-main">
            @if (count($carts) > 0)
            <div id="cart-table" class="cart-area">
                <div class="cart-table">
                    @php
                    // Group cart items by product_id, but exclude combo items
                    $regularCarts = $carts->where('combo_offer_id', null);
                    $groupedCarts = $regularCarts->groupBy('product_id');
                    @endphp
                    @foreach ($groupedCarts as $productId => $cartItems)
                    @php
                    $product = $cartItems->first()->product;
                    $totalQuantity = $cartItems->sum('qunt');
                    $totalPrice = $cartItems->sum(function ($item) {
                    return $item->calculated_subtotal ?? ($item->qunt * $item->price);
                    });
                    @endphp
                    <div class="cart-item" data-cart-id="{{ $cartItems->first()->id }}">
                        <div class="cart-remove">
                            <a class="remove-btn"
                                href="{{ route('cart.destroy', ['id' => $productId, 'remove_all' => true]) }}">&times;</a>
                        </div>
                        <div id="p-img" class="cart-image">
                            <a
                                href="{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}">
                                <img src="{{ asset('storage/' . $product->thumb_image) }}"
                                    alt="{{ $product->title }}">
                            </a>
                        </div>
                        <div class="cart-body">
                            <div class="cart-top">
                                <div id="p-title" class="cart-product-title">
                                    <a class="product-link"
                                        href="{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}">{{ $product->title }}</a>
                                    @if ($cartItems->count() >= 1)
                                    <div class="variation-details">
                                        @foreach ($cartItems as $item)
                                        @if ($item->product->product_type === 'variable')
                                        @php
                                        $combinationDetails = null;
                                        if ($item->combination_id && $item->variationCombination) {
                                        $combination = $item->variationCombination;
                                        $combinationDetails = [
                                        'display_name' => $combination->display_name,
                                        'short_description' => $combination->short_description,
                                        'qty' => $item->qunt,
                                        'effective_price' => $combination->offer_price ?? $combination->regular_price ?? $combination->price,
                                        'has_offer' => $combination->hasOffer(),
                                        'regular_price' => $combination->regular_price ?? $combination->price,
                                        'offer_price' => $combination->offer_price,
                                        ];
                                        }
                                        @endphp

                                        @if ($combinationDetails)
                                        <div class="variation-item">
                                            <span class="variation-combination">{{ $combinationDetails['display_name'] }}</span>
                                            <span class="variation-qty">(x{{ $combinationDetails['qty'] }})</span>
                                            <div class="combination-price">
                                                @if ($combinationDetails['has_offer'])
                                                <span class="original-price" style="text-decoration: line-through; color: #999;">{{ $combinationDetails['regular_price'] }}৳</span>
                                                <span class="offer-price" style="color: #e74c3c; font-weight: bold;">{{ $combinationDetails['offer_price'] }}৳</span>
                                                @else
                                                <span class="regular-price">{{ $combinationDetails['effective_price'] }}৳</span>
                                                @endif
                                            </div>
                                            @if ($combinationDetails['short_description'])
                                            <div class="combination-description" style="font-size: 12px; color: #666; margin-top: 5px;">
                                                {{ $combinationDetails['short_description'] }}
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                        @else
                                        <!-- Simple product details -->
                                        <div class="simple-item" style="font-size: 14px; color: #666; margin-top: 5px;">
                                            <span class="simple-qty">Quantity: {{ $item->qunt }}</span>
                                            <span class="simple-price"> • Unit Price: {{ $item->price }}৳ each</span>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="meta-grid">
                                <div id="p-price" class="cart-price" data-label="Price:">
                                    <span class="meta-label">Price</span>
                                    @if ($cartItems->count() > 1)
                                    <span class="price-text">Various</span>
                                    @else
                                    <span class="price-text">{{ $cartItems->first()->price }}৳</span>
                                    @endif
                                </div>
                                <div class="cart-quantity" data-label="Quantity:">
                                    <span class="meta-label">Quantity</span>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn decrease-btn"
                                            onclick="updateCartQuantity({{ $cartItems->first()->id }}, 'decrease')"
                                            {{ $totalQuantity <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="number" class="quantity-input" value="{{ $totalQuantity }}" min="1"
                                            onchange="updateCartQuantity({{ $cartItems->first()->id }}, 'set', this.value)"
                                            onblur="validateQuantityInput(this, {{ $totalQuantity }})">
                                        <button type="button" class="quantity-btn increase-btn"
                                            onclick="updateCartQuantity({{ $cartItems->first()->id }}, 'increase')">+</button>
                                    </div>
                                </div>
                                <div id="p-price-total" class="cart-total" data-label="Total:">
                                    <span class="meta-label">Total</span>
                                    <span class="total-price">{{ $totalPrice }}৳</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    {{-- Display Combo Items --}}
                    @foreach ($carts->where('combo_offer_id', '!=', null) as $comboItem)
                    <div class="cart-item combo-item" data-cart-id="{{ $comboItem->id }}">
                        <div class="cart-remove">
                            <a class="remove-btn"
                                href="{{ route('cart.destroy', ['id' => $comboItem->id]) }}">&times;</a>
                        </div>
                        <div class="cart-image">
                            <img src="{{ asset('storage/' . $comboItem->comboOffer->product->thumb_image) }}"
                                alt="{{ $comboItem->comboOffer->title }}">
                        </div>
                        <div class="cart-body">
                            <div class="cart-top">
                                <div class="cart-product-title">
                                    <div class="combo-title">
                                        <strong>{{ $comboItem->comboOffer->title }}</strong>
                                        <span class="combo-badge">Combo Offer</span>
                                    </div>
                                    @php
                                    $comboSelections = $comboItem->combo_selections;
                                    if (is_string($comboSelections)) {
                                    $comboSelections = json_decode($comboSelections, true);
                                    }

                                    @endphp
                                    @if ($comboSelections && is_array($comboSelections))
                                    <div class="combo-selections">
                                        @foreach ($comboSelections as $selection)
                                        @php
                                        $product = \App\Models\Product::find($selection['product_id']);
                                        $variation = null;
                                        if ($selection['variation_id']) {
                                        $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                                        }
                                        @endphp
                                        @if ($product)
                                        <div class="combo-selection-item">
                                            <span class="product-name">{{ $product->title }}</span>
                                            @if ($variation)
                                            <span class="variation-name">({{ $variation->display_name }})</span>
                                            @endif
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    @else
                                    <div class="combo-selections">
                                        <div class="combo-selection-item">
                                            <span class="product-name">Combo selections not available</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="meta-grid">
                                <div class="cart-price" data-label="Price:">
                                    <span class="meta-label">Price</span>
                                    <span class="price-text">{{ $comboItem->price }}৳</span>
                                </div>
                                <div class="cart-quantity" data-label="Quantity:">
                                    <span class="meta-label">Quantity</span>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn decrease-btn"
                                            onclick="updateCartQuantity({{ $comboItem->id }}, 'decrease')"
                                            {{ $comboItem->qunt <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="number" class="quantity-input" value="{{ $comboItem->qunt }}" min="1"
                                            onchange="updateCartQuantity({{ $comboItem->id }}, 'set', this.value)"
                                            onblur="validateQuantityInput(this, {{ $comboItem->qunt }})">
                                        <button type="button" class="quantity-btn increase-btn"
                                            onclick="updateCartQuantity({{ $comboItem->id }}, 'increase')">+</button>
                                    </div>
                                </div>
                                <div class="cart-total" data-label="Total:">
                                    <span class="meta-label">Total</span>
                                    <span class="total-price">{{ $comboItem->calculated_subtotal ?? ($comboItem->price * $comboItem->qunt) }}৳</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="coupon-section">
                    <input type="text" name="text" placeholder="Coupon Code" class="coupon-input">
                    <a href="" class="coupon-btn">Apply Coupon</a>
                    <a href="{{ route('shop') }}" class="continue-shopping">Continue Shopping</a>
                </div>
            </div>
            @else
            <div class="empty-cart-message">
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added any products to your cart yet.</p>
                <a href="{{ route('shop') }}" class="continue-shopping">Start Shopping</a>
            </div>
            @endif
        </div>

        <div class="cart-sidebar">
            <h2 class="sidebar-title">Cart totals</h2>
            <div class="cart-total-row">
                <p class="total-label">Subtotal</p>
                <p class="total-value">{{ $sub_total }}৳</p>
            </div>
            <a href="{{ route('checkout') }}" class="checkout-btn">Proceed to checkout</a>
            <!--
                <div class="promo-video">
                    <iframe src="https://www.youtube.com/embed/MvOTNP_xQ7A?si=4eR_nfb_qj42-bns"
                        height="160" width="100%" frameborder="0" allowfullscreen></iframe>
                </div>
                -->
        </div>
        </div>
    </div>
</div>
</div>

<script>
    // Get CSRF token from meta tag
    function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    /**
     * Update cart item quantity via AJAX
     */
    function updateCartQuantity(cartId, action, quantity = null) {
        let button = event.target;
        let isInput = button.tagName === 'INPUT';

        // If it's an input field, get the button from the parent
        if (isInput) {
            button = button.closest('.quantity-controls').querySelector('.quantity-btn');
        }

        // Disable button and show loading state
        button.disabled = true;
        button.classList.add('loading');

        // Prepare data
        let data = {
            cart_id: cartId,
            action: action
        };

        if (action === 'set' && quantity !== null) {
            data.quantity = parseInt(quantity);
        }

        // Use fetch API instead of jQuery
        // Create FormData for proper CSRF handling
        const formData = new FormData();
        formData.append('cart_id', data.cart_id);
        formData.append('action', data.action);
        if (data.quantity) {
            formData.append('quantity', data.quantity);
        }

        fetch('{{ route("cart.update.quantity") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('📥 Response received:', response);
                console.log('📊 Response status:', response.status);
                console.log('📋 Response headers:', response.headers);

                if (!response.ok) {
                    console.log('❌ Response not OK, status:', response.status);
                    if (response.status === 422) {
                        console.log('⚠️ Validation error (422)');
                        // Validation error - try to parse JSON response
                        return response.json().then(errorData => {
                            console.log('📄 Error data:', errorData);
                            throw new Error(JSON.stringify({
                                status: 422,
                                data: errorData
                            }));
                        });
                    }
                    console.log('❌ HTTP error, throwing error');
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                console.log('✅ Response OK, parsing JSON');
                return response.json();
            })
            .then(response => {
                if (response.success) {
                    // Update quantity input
                    const quantityInput = button.closest('.quantity-controls').querySelector('.quantity-input');
                    if (quantityInput) {
                        quantityInput.value = response.new_quantity;
                    }

                    // Update total price for this item
                    const totalCell = button.closest('.cart-item').querySelector('.cart-total .total-price');
                    if (totalCell) {
                        totalCell.textContent = response.new_total + '৳';
                    }

                    // Update subtotal in sidebar
                    const subtotalElement = document.querySelector('.cart-sidebar .total-value');
                    if (subtotalElement) {
                        subtotalElement.textContent = response.sub_total + '৳';
                    }

                    // Update cart count in header if exists
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = response.cart_count;
                    }

                    // Update button states
                    updateQuantityButtonStates(cartId, response.new_quantity);

                    // Check if cart is empty and redirect if needed
                    if (response.cart_count === 0) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }

                    // Show success message
                    showNotification('Quantity updated successfully', 'success');
                }
            })
            .catch(error => {
                // Handle validation errors (422 status)
                if (error.message.includes('"status":422')) {
                    try {
                        const errorData = JSON.parse(error.message);
                        const response = errorData.data;

                        showNotification(response.message, 'error');

                        // If stock limit reached, update quantity to max available
                        if (response.max_quantity) {
                            const quantityInput = button.closest('.quantity-controls').querySelector('.quantity-input');
                            if (quantityInput) {
                                quantityInput.value = response.max_quantity;
                            }

                            // Update button states
                            updateQuantityButtonStates(cartId, response.max_quantity);
                        }
                    } catch (e) {
                        showNotification('Error updating quantity. Please try again.', 'error');
                    }
                } else {
                    showNotification('Error updating quantity. Please try again.', 'error');
                }
            })
            .finally(() => {
                // Re-enable button and remove loading state
                button.disabled = false;
                button.classList.remove('loading');
            });
    }

    /**
     * Update quantity button states (enable/disable)
     */
    function updateQuantityButtonStates(cartId, quantity) {
        const row = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
        if (row) {
            const decreaseBtn = row.querySelector('.decrease-btn');
            const increaseBtn = row.querySelector('.increase-btn');

            if (decreaseBtn) {
                decreaseBtn.disabled = quantity <= 1;
            }

            if (increaseBtn) {
                // Note: We'll keep increase button enabled as stock validation happens server-side
                // But you could add visual feedback here if needed
            }
        }
    }

    /**
     * Validate quantity input on blur
     */
    function validateQuantityInput(input, originalValue) {
        const value = parseInt(input.value);
        if (isNaN(value) || value < 1) {
            input.value = originalValue;
            showNotification('Please enter a valid quantity (minimum 1)', 'warning');
            return false;
        }
        return true;
    }

    /**
     * Prevent negative numbers in quantity input
     */
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === '-' || e.key === 'e' || e.key === 'E') {
                    e.preventDefault();
                }
            });
        });
    });

    /**
     * Show notification message
     */
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;

        // Add styles
        notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: 500;
                z-index: 9999;
                max-width: 300px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;

        // Set background color based on type
        switch (type) {
            case 'success':
                notification.style.backgroundColor = '#10b981';
                break;
            case 'error':
                notification.style.backgroundColor = '#ef4444';
                break;
            case 'warning':
                notification.style.backgroundColor = '#f59e0b';
                break;
            default:
                notification.style.backgroundColor = '#3b82f6';
        }

        // Add to page
        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Initialize quantity button states on page load
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        quantityInputs.forEach(input => {
            const quantity = parseInt(input.value);
            const cartRow = input.closest('.cart-item');
            const cartId = cartRow ? cartRow.getAttribute('data-cart-id') : null;
            if (cartId) {
                updateQuantityButtonStates(cartId, quantity);
            }
        });
    });
</script>
@endsection