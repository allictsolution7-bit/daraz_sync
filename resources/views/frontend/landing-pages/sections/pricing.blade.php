@if ($section && $section->status)
@php
    $pricingVariants = $section->pricing_variants;
    $variantsCount = is_array($pricingVariants) ? count($pricingVariants) : 0;
@endphp
@if ($variantsCount > 0)
<style>
    .pricing-section {
        max-width: 1200px;
        margin: 0 auto;
        background: var(--secondary-color);
        display: grid;
        gap: 20px;
        padding: 30px 10px;
    }

    .pricing-card {
        background: linear-gradient(135deg, var(--primary-color) , var(--secondary-color) );
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

    .bangla-number {
        font-family: inherit;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .pricing-section {
            grid-template-columns: 1fr;
            padding: 20px 20px !important;
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
</style>

<section class="pricing-section">
    @foreach ($pricingVariants as $variant)
        <div class="pricing-card">
            <div class="original-price">
                {{ $variant['weight'] ?? '' }} রেগুলার মূল্য <span class="strike-through">
                    <span class="bangla-number">{{ number_format($variant['regular_price'] ?? 0) }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none">
                        <path d="M497.4,23.9C301.6,40,155.9,80.6,4,144.4"></path>
                        <path d="M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7"></path>
                    </svg>
                </span> টাকা
            </div>
            <div class="current-price">
                বর্তমান অফার মূল্য <span class="highlight highlight-underline">
                    <span class="bangla-number">{{ number_format($variant['offer_price'] ?? 0) }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 100" preserveAspectRatio="none">
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
@endif
@endif
