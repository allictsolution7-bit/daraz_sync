@php
$shippingSetting = \App\Models\BasicShippingSetting::first();
$freeShippingMinimum = $shippingSetting && $shippingSetting->free_shipping_threshold ? (float) $shippingSetting->free_shipping_threshold : 0;
$remaining = max(0, $freeShippingMinimum - $cartTotal);
$progress = min(100, round(($cartTotal / $freeShippingMinimum) * 100));

// Check settings for showing progress bar
$showProgressBar = setting('general', 'show_free_shipping_progress', '1') == '1';
$showOnDesktop = setting('general', 'show_free_shipping_progress_desktop', '1') == '1';
$showOnMobile = setting('general', 'show_free_shipping_progress_mobile', '1') == '1';

// Use passed positioning variables or fallback to defaults
$bottomDesktop = $bottomDesktop ?? setting('general', 'free_shipping_progress_bottom_desktop', '0');
$rightDesktop = $rightDesktop ?? setting('general', 'free_shipping_progress_right_desktop', '69');
$bottomMobile = $bottomMobile ?? setting('general', 'free_shipping_progress_bottom_mobile', '37');
$rightMobile = $rightMobile ?? setting('general', 'free_shipping_progress_right_mobile', '59');
@endphp

@if ($freeShippingMinimum > 0 && $showProgressBar)
<div class="free-shipping-progress-bar @if(!$showOnDesktop) hide-desktop @endif @if(!$showOnMobile) hide-mobile @endif" style="bottom: {{ $bottomDesktop }}px; right: {{ $rightDesktop }}px;">
    <div class="progress-bar-bg">
        <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
    </div>
    <div class="progress-bar-text">
        @if ($remaining > 0)
            <span>আরো <b>৳{{ $remaining }}</b> কিনলে <b>ফ্রি ডেলিভারি</b> পাবেন!</span>
        @else
            <span><b>ফ্রি ডেলিভারি unlocked!</b></span>
        @endif
    </div>
</div>

<style>
    .free-shipping-progress-bar {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e2e2e2;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.06);
        padding: 7px 10px;
        margin: 18px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: inherit;
        position: fixed;
        z-index: 15;
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
            bottom: {{ $bottomMobile }}px !important;
            right: {{ $rightMobile }}px !important;
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
</style>
@endif 