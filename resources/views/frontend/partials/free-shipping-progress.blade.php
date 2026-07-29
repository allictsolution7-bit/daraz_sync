@php
$shippingSetting = \App\Models\BasicShippingSetting::first();
$freeShippingMinimum = $shippingSetting && $shippingSetting->free_shipping_threshold ? (float) $shippingSetting->free_shipping_threshold : 0;
$cartTotalVal = $cartTotal ?? 0;
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
        background: #f0fdf4;
        border-radius: 8px;
        border: 1px solid #bbf7d0;
        padding: 10px 14px;
        margin: 10px 0 15px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
    }

    .cart-free-shipping-progress-banner .progress-bar-bg {
        width: 100%;
        height: 10px;
        background: #dcfce7;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 6px;
    }

    .cart-free-shipping-progress-banner .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%);
        border-radius: 6px;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cart-free-shipping-progress-banner .progress-bar-text {
        font-size: 0.85rem;
        color: #15803d;
        font-weight: 600;
        text-align: center;
    }

    .cart-free-shipping-progress-banner .progress-bar-text b {
        color: #166534;
    }
</style>
@endif 