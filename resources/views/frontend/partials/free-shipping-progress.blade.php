@php
    $currentVendorId = $vendorId ?? null;

    if (!$currentVendorId && isset($carts) && $carts->count() > 0) {
        $firstCart = $carts->first();
        if ($firstCart && $firstCart->product) {
            $currentVendorId = $firstCart->product->vendor_id ?: $firstCart->product->created_by ?: null;
        }
    }

    $shippingSetting = null;
    if ($currentVendorId) {
        $shippingSetting = \App\Models\BasicShippingSetting::where('user_id', $currentVendorId)->first();
    }
    if (!$shippingSetting) {
        $shippingSetting = \App\Models\BasicShippingSetting::first();
    }

    $freeShippingMinimum = $shippingSetting && $shippingSetting->free_shipping_threshold ? (float) $shippingSetting->free_shipping_threshold : 0;

    // Filter cart total for items belonging to the same shop/vendor if vendor context exists
    if (isset($carts) && $carts->count() > 0 && $currentVendorId) {
        $cartTotalVal = $carts->filter(function($c) use ($currentVendorId) {
            $itemOwner = optional($c->product)->vendor_id ?: optional($c->product)->created_by ?: null;
            return $itemOwner == $currentVendorId;
        })->sum(function($c) {
            return $c->calculated_subtotal ?? ($c->price * $c->qunt);
        });
    } else {
        $cartTotalVal = $cartTotal ?? 0;
    }

    $remaining = max(0, $freeShippingMinimum - $cartTotalVal);
    $progress = $freeShippingMinimum > 0 ? min(100, round(($cartTotalVal / $freeShippingMinimum) * 100)) : 0;
@endphp

@if ($freeShippingMinimum > 0)
<div class="cart-free-shipping-progress-banner">
    <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
    </div>
    <div class="progress-bar-text">
        @if ($remaining > 0)
            <span>আরো <b>৳{{ number_format($remaining, 0) }}</b> কিনলে <b>ফ্রি ডেলিভারি</b> পাবেন!</span>
        @else
            <span><b>🎉 ফ্রি ডেলিভারি unlocked!</b></span>
        @endif
    </div>
</div>

<style>
    .cart-free-shipping-progress-banner {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 12px;
        border: 1px solid #86efac;
        padding: 12px 16px;
        margin: 10px 0 16px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.12);
    }

    .cart-free-shipping-progress-banner .progress-bar-bg {
        width: 100%;
        height: 10px;
        background: #bbf7d0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .cart-free-shipping-progress-banner .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        border-radius: 8px;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);
    }

    .cart-free-shipping-progress-banner .progress-bar-text {
        font-size: 0.88rem;
        color: #065f46;
        font-weight: 600;
        text-align: center;
    }

    .cart-free-shipping-progress-banner .progress-bar-text b {
        color: #047857;
        font-weight: 700;
    }
</style>
@endif 