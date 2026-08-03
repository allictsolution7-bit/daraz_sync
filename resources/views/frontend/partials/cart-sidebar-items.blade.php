<div class="cart-sidebar-items">
    @include('frontend.partials.free-shipping-progress', [
        'carts' => $carts,
        'cartTotal' => $carts->sum(function($c) { return $c->calculated_subtotal ?? ($c->price * $c->qunt); })
    ])
    @forelse ($carts as $cart)
        @php
            $product = $cart->product;
            $isCombo = (bool) $cart->combo_offer_id;
            $thumbImage = $isCombo
                ? optional(optional($cart->comboOffer)->product)->thumb_image
                : optional($product)->thumb_image;
            $title = $isCombo
                ? ($cart->comboOffer->title ?? 'Combo offer')
                : ($product->title ?? 'Product unavailable');
            $variationLabel = !$isCombo && $product && $product->product_type === 'variable' && $cart->variationCombination
                ? $cart->variationCombination->display_name
                : null;
            $subTotal = $cart->calculated_subtotal ?? ($cart->price * $cart->qunt);
        @endphp
        <div class="cart-sidebar-item" data-cart-id="{{ $cart->id }}">
            <div class="cart-sidebar-thumb">
                @if ($thumbImage)
                    <img src="{{ asset('storage/' . $thumbImage) }}" alt="{{ $title }}">
                @else
                    <div class="cart-thumb-placeholder">No image</div>
                @endif
            </div>
            <div class="cart-sidebar-info">
                <div class="cart-sidebar-title">{{ $title }}</div>
                @if ($variationLabel)
                    <div class="cart-sidebar-variant">{{ $variationLabel }}</div>
                @endif
                @if ($isCombo)
                    <div class="cart-sidebar-chip">Combo</div>
                @endif
                <div class="cart-sidebar-meta">
                    <span>{{ $cart->qunt }} × ৳{{ number_format($cart->price, 0) }}</span>
                    <span class="cart-sidebar-line-total">= ৳{{ number_format($subTotal, 0) }}</span>
                </div>
                <div class="cart-sidebar-quantity" data-cart-id="{{ $cart->id }}">
                    <button type="button" class="cart-qty-btn" data-action="decrease">-</button>
                    <input type="number" min="1" class="cart-qty-input" value="{{ $cart->qunt }}">
                    <button type="button" class="cart-qty-btn" data-action="increase">+</button>
                </div>
            </div>
            <button type="button" class="cart-sidebar-remove"
                data-cart-remove="{{ $isCombo ? $cart->id : $cart->product_id }}"
                data-remove-all="{{ $isCombo ? '0' : '1' }}" aria-label="Remove item from cart">
                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 7H18" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M10 11V17" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M14 11V17" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
                    <path
                        d="M7 7L7.631 18.142C7.7 19.329 8.695 20.25 9.884 20.25H14.116C15.305 20.25 16.3 19.329 16.369 18.142L17 7"
                        stroke="#DC2626" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M9.5 4.5C9.5 3.67157 10.1716 3 11 3H13C13.8284 3 14.5 3.67157 14.5 4.5V7H9.5V4.5Z"
                        stroke="#DC2626" stroke-width="1.5" />
                </svg>
            </button>
        </div>
    @empty
        <div class="cart-sidebar-empty">
            <p>আপনার কার্ট খালি</p>
            <a href="{{ route('shop') }}" class="cart-sidebar-shop">শপিং চালিয়ে যান</a>
        </div>
    @endforelse
</div>
