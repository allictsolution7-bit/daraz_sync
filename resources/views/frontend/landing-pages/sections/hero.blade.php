<style>
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
        0%, 100% {
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

    /* Product Description & Benefits */
    .product-description {
        font-size: 24px;
        color: var(--secondary-color);
        line-height: 1.4;
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

    /* Product CTA */
    .product-cta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    /* Product Image */
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

    /* Customer Trust Indicators */
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
    
        /* Responsive Adjustments */
        @media (max-width: 768px) {

            .product-row {
                flex-direction: column;
                gap: 0px;
            }

            button.order-submit-btn {
                position: fixed;
                bottom: 5px;
                left: 5px;
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
</style>

@if ($section && $section->status)
<section class="natural-products-showcase">
    <div class="showcase-container">
        <div class="product-row">
            <div class="product-content">
                @if ($section->badge_image || $section->badge_text)
                    <div class="badge-container badge-width-vars">
                        @if ($section->badge_image)
                            <a href="{{ url('/') }}" class="badge-image-link" title="Go to Homepage">
                                <img src="{{ asset($section->badge_image) }}"
                                    alt="{{ $section->badge_text ?? 'Badge' }}" class="badge-image"
                                    style="max-height: 40px;">
                            </a>
                        @endif
                        @if ($section->badge_text)
                            <span class="organic-badge"
                                style="background-color: {{ $section->badge_color ?? '#ffd54f' }};">{{ $section->badge_text }}</span>
                        @endif
                    </div>
                @endif
                <h2 class="section-title">{{ $section->heading ?? ($landingPage->title ?? '') }}</h2>
                @if ($section->sub_heading)
                    <p class="product-description">{{ $section->sub_heading }}</p>
                @endif
                @if ($section->primary_text)
                    <div class="product-description">{!! $section->primary_text !!}</div>
                @endif

                @if ($section->show_trust_indicators && $section->trust_indicators && count($section->trust_indicators) > 0)
                    <div class="customer-trust-indicators">
                        @foreach ($section->trust_indicators as $indicator)
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
                @if ($section->hero_video_url)
                    @php
                        $videoUrl = $section->hero_video_url;
                        $embedUrl = '';
                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                            $videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                            $videoId = substr($videoUrl, strpos($videoUrl, 'youtu.be/') + 9);
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } elseif (strpos($videoUrl, 'youtube.com/shorts/') !== false) {
                            $videoId = substr($videoUrl, strpos($videoUrl, 'shorts/') + 7);
                            if (strpos($videoId, '?') !== false) {
                                $videoId = substr($videoId, 0, strpos($videoId, '?'));
                            }
                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
                            $embedUrl = $videoUrl;
                        } else {
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
                @elseif ($section->hero_image)
                    <img src="{{ asset($section->hero_image) }}"
                        alt="{{ $section->hero_image_alt ?? ($section->heading ?? ($landingPage->title ?? '')) }}"
                        loading="lazy">
                @elseif(isset($product))
                    <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title ?? 'Product' }}"
                        loading="lazy">
                @endif
                <div class="best-seller-badge">সেরা পণ্য!</div>
            </div>
        </div>
    </div>
</section>
@endif
