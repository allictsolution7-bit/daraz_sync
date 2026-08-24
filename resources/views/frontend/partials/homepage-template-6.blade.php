{{-- 
    Template 6: Fashion & Apparel Studio
    High-End Fashion Magazine & Editorial Aesthetics
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&display=swap');

/* ── SCOPED TEMPLATE 6: LUXURY FASHION STUDIO ── */
.t6-page {
    --t6-bg: {{ $homepage['template_6_bg_color'] ?? '#0d0d0d' }};
    --t6-accent: {{ $homepage['template_6_accent_color'] ?? '#c9a84c' }};
    --t6-text: {{ $homepage['template_6_text_color'] ?? '#f3f4f6' }};
    background: var(--t6-bg);
    color: var(--t6-text);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    padding-bottom: 70px;
    overflow-x: hidden;
}

.t6-container {
    max-width: 1380px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 640px) {
    .t6-container {
        padding: 0 12px;
    }
}

@media (max-width: 360px) {
    .t6-container {
        padding: 0 8px;
    }
}

/* SECTION 1: Infinite Marquee Ticker Strip */
.t6-marquee-wrap {
    background: #151515;
    border-bottom: 1px solid rgba(201, 168, 76, 0.25);
    overflow: hidden;
    white-space: nowrap;
    padding: 10px 0;
    position: relative;
    width: 100%;
}

@media (max-width: 640px) {
    .t6-marquee-wrap {
        padding: 7px 0;
    }
    .t6-marquee-content {
        font-size: 11px;
        letter-spacing: 1.5px;
    }
}

.t6-marquee-content {
    display: inline-block;
    animation: t6Marquee 28s linear infinite;
    font-family: 'Playfair Display', serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #c9a84c;
}

.t6-marquee-content span {
    padding: 0 24px;
}

@keyframes t6Marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* SECTION 2: Clean Fashion Lookbook Hero Slider */
.t6-hero-section {
    padding: 20px 0;
}

.t6-hero-container {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid rgba(201, 168, 76, 0.35);
    box-shadow: 0 14px 36px rgba(0, 0, 0, 0.6);
    background: #0d0d0d;
    width: 100%;
}

.t6-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 1500 / 600;
    min-height: 180px;
    overflow: hidden;
}

.t6-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
    text-decoration: none;
}

.t6-slide.active {
    opacity: 1;
    visibility: visible;
}

.t6-slide-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
}

/* Clean minimal bottom caption bar ONLY when title exists */
.t6-slide-caption {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid rgba(201, 168, 76, 0.5);
    backdrop-filter: blur(10px);
    padding: 12px 24px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.5);
}

@media (max-width: 640px) {
    .t6-slide-caption {
        bottom: 12px;
        left: 12px;
        right: 12px;
        padding: 8px 14px;
        gap: 8px;
    }
}

.t6-caption-title {
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    color: #ffffff;
    font-weight: 700;
}

.t6-caption-btn {
    background: #c9a84c;
    color: #0d0d0d;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    padding: 6px 14px;
    border-radius: 4px;
    text-transform: uppercase;
}

.t6-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(13, 13, 13, 0.75);
    border: 1px solid rgba(201, 168, 76, 0.6);
    color: #c9a84c;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
}

.t6-hero-prev {
    left: 20px;
}

.t6-hero-next {
    right: 20px;
}

.t6-hero-arrow:hover {
    background: #c9a84c;
    color: #0d0d0d;
    transform: translateY(-50%) scale(1.1);
}

@media (max-width: 640px) {
    .t6-hero-arrow {
        width: 36px;
        height: 36px;
    }
    .t6-hero-prev {
        left: 10px;
    }
    .t6-hero-next {
        right: 10px;
    }
}

/* Editorial Highlights Strip */
.t6-editorial-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 18px;
}

@media (max-width: 850px) {
    .t6-editorial-strip {
        grid-template-columns: 1fr;
    }
}

.t6-editorial-card {
    background: #151515;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.t6-editorial-card:hover {
    border-color: #c9a84c;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(201, 168, 76, 0.15);
}

.t6-editorial-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(201, 168, 76, 0.1);
    border: 1px solid #c9a84c;
    color: #c9a84c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.t6-editorial-label {
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 2px;
}

.t6-editorial-sub {
    font-size: 12px;
    color: #9ca3af;
}

/* SECTION 3: Shop by Look (Portrait Cards with 3D tilt) */
.t6-section {
    padding: 50px 0 20px;
}

.t6-sec-head {
    text-align: center;
    margin-bottom: 34px;
    position: relative;
}

.t6-sec-subtitle {
    color: #c9a84c;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 6px;
}

.t6-sec-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(26px, 3.5vw, 38px);
    color: #ffffff;
    font-weight: 700;
    margin: 0;
}

.t6-looks-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    overflow-x: auto;
    padding-bottom: 10px;
}

@media (max-width: 1024px) {
    .t6-looks-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    .t6-looks-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

.t6-look-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 2/3;
    background: #1a1a1a;
    display: block;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.4s ease, border-color 0.4s ease;
}

.t6-look-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: #c9a84c;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6), 0 0 20px rgba(201, 168, 76, 0.2);
}

.t6-look-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.t6-look-card:hover .t6-look-img {
    transform: scale(1.08);
}

.t6-look-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(13,13,13,0.92) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 16px;
}

.t6-look-tag {
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #c9a84c;
    font-weight: 700;
}

.t6-look-name {
    font-family: 'Playfair Display', serif;
    color: #ffffff;
    font-size: 17px;
    font-weight: 700;
    margin: 4px 0 2px;
}

/* SECTION 4: Asymmetric Featured Collection Grid */
.t6-asym-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr;
    grid-template-rows: 240px 240px;
    gap: 18px;
    margin-top: 20px;
}

@media (max-width: 900px) {
    .t6-asym-grid {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: auto;
    }
}

@media (max-width: 600px) {
    .t6-asym-grid {
        grid-template-columns: 1fr;
    }
}

.t6-asym-main {
    grid-row: span 2;
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: #1a1a1a;
    border: 1px solid rgba(201, 168, 76, 0.3);
    text-decoration: none;
    display: block;
}

@media (max-width: 900px) {
    .t6-asym-main {
        grid-row: span 1;
        aspect-ratio: 16/9;
    }
}

.t6-asym-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #1a1a1a;
    border: 1px solid rgba(255, 255, 255, 0.08);
    text-decoration: none;
    display: block;
    transition: all 0.3s ease;
}

.t6-asym-item:hover, .t6-asym-main:hover {
    border-color: #c9a84c;
    box-shadow: 0 10px 30px rgba(201, 168, 76, 0.2);
}

.t6-asym-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.t6-asym-item:hover .t6-asym-img, .t6-asym-main:hover .t6-asym-img {
    transform: scale(1.06);
}

.t6-asym-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 30%, rgba(13,13,13,0.85) 100%);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.t6-asym-badge {
    background: #c9a84c;
    color: #0d0d0d;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 3px;
    align-self: flex-start;
    margin-bottom: 8px;
}

.t6-asym-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #ffffff;
    font-weight: 700;
    margin: 0;
}

/* SECTION 5: Product Row (Dark Grid) */
.t6-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t6-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

/* SECTION 6: Compact Bespoke Spotlight Banner with Multi-Product Cards */
/* SECTION 6: Bespoke Spotlight Carousel Banner */
.t6-size-banner {
    margin: 36px 0;
    border-radius: 18px;
    background: linear-gradient(135deg, #181818 0%, #201c15 50%, #2e2615 100%);
    border: 1.5px solid rgba(201, 168, 76, 0.35);
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    align-items: center;
    padding: 34px 36px;
    gap: 32px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.5);
    position: relative;
    overflow: hidden;
}

@media (max-width: 960px) {
    .t6-size-banner {
        grid-template-columns: 1fr;
        padding: 24px;
        gap: 20px;
    }
}

@media (max-width: 480px) {
    .t6-size-banner {
        padding: 16px 14px;
        gap: 14px;
        margin: 20px 0;
        border-radius: 12px;
    }
}

.t6-size-content {
    padding: 8px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.t6-size-tag {
    color: #c9a84c;
    font-size: 11px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    font-weight: 800;
    margin-bottom: 8px;
}

.t6-size-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(20px, 2.8vw, 32px);
    color: #ffffff;
    font-weight: 700;
    margin: 0 0 10px;
    line-height: 1.25;
}

.t6-size-desc {
    color: #9ca3af;
    font-size: 13.5px;
    line-height: 1.6;
    margin-bottom: 20px;
}

@media (max-width: 480px) {
    .t6-size-desc {
        font-size: 12px;
        margin-bottom: 14px;
        line-height: 1.45;
    }
}

.t6-btn-gold {
    background: linear-gradient(135deg, #c9a84c, #e0c27b);
    color: #0d0d0d;
    font-weight: 800;
    font-size: 11.5px;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 10px 22px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(201, 168, 76, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    align-self: flex-start;
}

@media (max-width: 480px) {
    .t6-btn-gold {
        padding: 8px 16px;
        font-size: 10.5px;
    }
}

.t6-btn-gold:hover {
    background: #ffffff;
    color: #0d0d0d;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

.t6-spotlight-wrapper {
    position: relative;
    overflow: visible;
}

.t6-spotlight-products {
    display: flex;
    gap: 14px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    padding: 6px 2px;
}

.t6-spotlight-products::-webkit-scrollbar {
    display: none;
}

@media (max-width: 480px) {
    .t6-spotlight-products {
        gap: 10px;
        padding: 4px 1px;
    }
}

.t6-spotlight-card {
    flex: 0 0 190px;
    background: rgba(15, 15, 15, 0.85);
    border: 1.5px solid rgba(201, 168, 76, 0.25);
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
}

@media (max-width: 600px) {
    .t6-spotlight-card {
        flex: 0 0 145px;
        border-radius: 9px;
    }
}

@media (max-width: 360px) {
    .t6-spotlight-card {
        flex: 0 0 130px;
    }
}

.t6-spotlight-card:hover {
    transform: translateY(-4px);
    border-color: #c9a84c;
    box-shadow: 0 10px 24px rgba(201, 168, 76, 0.3);
}

.t6-spotlight-card img {
    width: 100%;
    height: 165px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

@media (max-width: 600px) {
    .t6-spotlight-card img {
        height: 130px;
    }
}

.t6-spotlight-card:hover img {
    transform: scale(1.06);
}

.t6-spotlight-info {
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: #111111;
}

@media (max-width: 600px) {
    .t6-spotlight-info {
        padding: 8px 10px;
    }
}

.t6-spotlight-name {
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #f3f4f6;
}

@media (max-width: 600px) {
    .t6-spotlight-name {
        font-size: 12px;
    }
}

.t6-spotlight-price {
    font-size: 14px;
    font-weight: 800;
    color: #c9a84c;
}

@media (max-width: 600px) {
    .t6-spotlight-price {
        font-size: 12.5px;
    }
}

.t6-spotlight-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(18, 18, 18, 0.92);
    border: 1.5px solid rgba(201, 168, 76, 0.7);
    color: #c9a84c;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    z-index: 10;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(6px);
}

@media (max-width: 600px) {
    .t6-spotlight-arrow {
        width: 28px;
        height: 28px;
        font-size: 15px;
    }
}

.t6-spotlight-arrow:hover {
    background: #c9a84c;
    color: #0d0d0d;
    transform: translateY(-50%) scale(1.12);
    box-shadow: 0 8px 24px rgba(201, 168, 76, 0.45);
}

.t6-spotlight-arrow.prev {
    left: -14px;
}

.t6-spotlight-arrow.next {
    right: -14px;
}

@media (max-width: 600px) {
    .t6-spotlight-arrow.prev {
        left: -8px;
    }
    .t6-spotlight-arrow.next {
        right: -8px;
    }
}

/* SECTION 7: Style the Look (Shoppable Outfit Tiles) */
.t6-outfits-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 850px) {
    .t6-outfits-grid {
        grid-template-columns: 1fr;
    }
}

.t6-outfit-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: #151515;
    border: 1px solid rgba(255, 255, 255, 0.08);
    aspect-ratio: 4/5;
    text-decoration: none;
    display: block;
}

.t6-outfit-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.t6-outfit-card:hover .t6-outfit-img {
    transform: scale(1.05);
}

.t6-outfit-tag-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid #c9a84c;
    color: #c9a84c;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 30px;
    backdrop-filter: blur(6px);
}

.t6-outfit-info {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(13,13,13,0.92) 100%);
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.t6-outfit-title {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: #ffffff;
    margin: 0 0 6px;
}

.t6-outfit-items {
    font-size: 12px;
    color: #c9a84c;
    font-weight: 500;
}

/* SECTION 8: Style Community Newsletter */
.t6-newsletter-wrap {
    margin-top: 60px;
    background: #151515;
    border: 1px solid rgba(201, 168, 76, 0.25);
    border-radius: 16px;
    padding: 60px 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.t6-newsletter-wrap::before {
    content: '';
    position: absolute;
    top: -50%;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(201, 168, 76, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.t6-news-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(26px, 3.5vw, 40px);
    color: #ffffff;
    font-weight: 700;
    margin: 0 0 10px;
}

.t6-news-desc {
    color: #9ca3af;
    font-size: 14px;
    max-width: 500px;
    margin: 0 auto 28px;
}

.t6-news-form {
    display: flex;
    max-width: 480px;
    margin: 0 auto;
    gap: 8px;
}

@media (max-width: 540px) {
    .t6-news-form {
        flex-direction: column;
    }
}

.t6-news-input {
    flex: 1;
    background: #0d0d0d;
    border: 1px solid rgba(201, 168, 76, 0.4);
    border-radius: 4px;
    padding: 14px 18px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
}

.t6-news-input:focus {
    border-color: #c9a84c;
    box-shadow: 0 0 12px rgba(201, 168, 76, 0.3);
}
</style>

<div class="t6-page">

    {{-- SECTION 1: Infinite Marquee Ticker Strip --}}
    <div class="t6-marquee-wrap">
        <div class="t6-marquee-content">
            @php
                $t6Ticker = $homepage['template_6_ticker_text'] ?? '✦ NEW SEASON EDITORIAL ✦ PREMIER PANJABI & COUTURE COLLECTION ✦ 100% ETHICAL FABRICS & TAILORING ✦ EXPRESS NATIONWIDE SHIPPING ✦';
            @endphp
            <span>{{ $t6Ticker }}</span>
            <span>{{ $t6Ticker }}</span>
        </div>
    </div>

    {{-- SECTION 2: Clean Fashion Lookbook Hero Slider --}}
    <section class="t6-hero-section">
        <div class="t6-container">
            <div class="t6-hero-container">
                <div class="t6-hero-slider" id="t6HeroSlider">
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t6-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t6-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Fashion Lookbook' }}">
                            @if(!empty($slider->title))
                                <div class="t6-slide-caption">
                                    <span class="t6-caption-title">{{ $slider->title }}</span>
                                    <span class="t6-caption-btn">{{ $slider->button_text ?: 'EXPLORE' }} &rarr;</span>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t6-slide active">
                            <img class="t6-slide-img" src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1600&q=80" alt="Fashion Couture">
                            <div class="t6-slide-caption">
                                <span class="t6-caption-title">Autumn & Winter Lookbook Collection</span>
                                <span class="t6-caption-btn">SHOP COLLECTION &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>
                {{-- Left & Right Buttons Centered on Both Sides --}}
                <button class="t6-hero-arrow t6-hero-prev" id="t6Prev" aria-label="Previous Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="t6-hero-arrow t6-hero-next" id="t6Next" aria-label="Next Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>

            {{-- Editorial Highlights Strip --}}
            <div class="t6-editorial-strip">
                <a href="{{ $homepage['template_6_strip1_url'] ?? route('shop') }}" class="t6-editorial-card">
                    <div class="t6-editorial-icon">{{ $homepage['template_6_strip1_icon'] ?? '👑' }}</div>
                    <div>
                        <div class="t6-editorial-label">{{ $homepage['template_6_strip1_label'] ?? 'Festive Panjabi & Sherwani' }}</div>
                        <div class="t6-editorial-sub">{{ $homepage['template_6_strip1_sub'] ?? 'Pure silk with hand zardozi collar embroidery' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_strip2_url'] ?? route('shop') }}" class="t6-editorial-card">
                    <div class="t6-editorial-icon">{{ $homepage['template_6_strip2_icon'] ?? '✨' }}</div>
                    <div>
                        <div class="t6-editorial-label">{{ $homepage['template_6_strip2_label'] ?? 'Designer Western & Gowns' }}</div>
                        <div class="t6-editorial-sub">{{ $homepage['template_6_strip2_sub'] ?? 'Modern tailoring and contemporary silhouettes' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_strip3_url'] ?? route('shop') }}" class="t6-editorial-card">
                    <div class="t6-editorial-icon">{{ $homepage['template_6_strip3_icon'] ?? '💎' }}</div>
                    <div>
                        <div class="t6-editorial-label">{{ $homepage['template_6_strip3_label'] ?? 'Handcrafted Leather Footwear' }}</div>
                        <div class="t6-editorial-sub">{{ $homepage['template_6_strip3_sub'] ?? 'Artisanal Nagra, loafers & festive accessories' }}</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Shop by Look (Portrait Cards with 3D tilt) --}}
    <section class="t6-section">
        <div class="t6-container">
            <div class="t6-sec-head">
                <div class="t6-sec-subtitle">{{ $homepage['template_6_looks_subtitle'] ?? 'Curated Styles' }}</div>
                <h2 class="t6-sec-title">{{ $homepage['template_6_looks_title'] ?? 'Shop by Distinct Look' }}</h2>
            </div>
            <div class="t6-looks-grid">
                <a href="{{ $homepage['template_6_look1_url'] ?? route('shop') }}" class="t6-look-card">
                    <img class="t6-look-img" src="{{ $homepage['template_6_look1_image'] ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80' }}" alt="{{ $homepage['template_6_look1_name'] ?? 'Panjabi Edition' }}">
                    <div class="t6-look-overlay">
                        <span class="t6-look-tag">{{ $homepage['template_6_look1_tag'] ?? 'SIGNATURE' }}</span>
                        <div class="t6-look-name">{{ $homepage['template_6_look1_name'] ?? 'Panjabi Luxe' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_look2_url'] ?? route('shop') }}" class="t6-look-card">
                    <img class="t6-look-img" src="{{ $homepage['template_6_look2_image'] ?? 'https://images.unsplash.com/photo-1594938298603-c8148c4b2f7a?w=400&q=80' }}" alt="{{ $homepage['template_6_look2_name'] ?? 'Sherwani' }}">
                    <div class="t6-look-overlay">
                        <span class="t6-look-tag">{{ $homepage['template_6_look2_tag'] ?? 'ROYAL' }}</span>
                        <div class="t6-look-name">{{ $homepage['template_6_look2_name'] ?? 'Festive Kurta' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_look3_url'] ?? route('shop') }}" class="t6-look-card">
                    <img class="t6-look-img" src="{{ $homepage['template_6_look3_image'] ?? 'https://images.unsplash.com/photo-1520367445093-50dc08a59d9d?w=400&q=80' }}" alt="{{ $homepage['template_6_look3_name'] ?? 'Women Couture' }}">
                    <div class="t6-look-overlay">
                        <span class="t6-look-tag">{{ $homepage['template_6_look3_tag'] ?? 'ETHNIC' }}</span>
                        <div class="t6-look-name">{{ $homepage['template_6_look3_name'] ?? 'Silk Saree & Kurtis' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_look4_url'] ?? route('shop') }}" class="t6-look-card">
                    <img class="t6-look-img" src="{{ $homepage['template_6_look4_image'] ?? 'https://images.unsplash.com/photo-1617137968427-85924c800a22?w=400&q=80' }}" alt="{{ $homepage['template_6_look4_name'] ?? 'Western Men' }}">
                    <div class="t6-look-overlay">
                        <span class="t6-look-tag">{{ $homepage['template_6_look4_tag'] ?? 'URBAN' }}</span>
                        <div class="t6-look-name">{{ $homepage['template_6_look4_name'] ?? 'Suits & Blazers' }}</div>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_look5_url'] ?? route('shop') }}" class="t6-look-card">
                    <img class="t6-look-img" src="{{ $homepage['template_6_look5_image'] ?? 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=400&q=80' }}" alt="{{ $homepage['template_6_look5_name'] ?? 'Accessories' }}">
                    <div class="t6-look-overlay">
                        <span class="t6-look-tag">{{ $homepage['template_6_look5_tag'] ?? 'LIFESTYLE' }}</span>
                        <div class="t6-look-name">{{ $homepage['template_6_look5_name'] ?? 'Luxury Footwear' }}</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Asymmetric Featured Editorial Grid --}}
    <section class="t6-section">
        <div class="t6-container">
            <div class="t6-sec-head">
                <div class="t6-sec-subtitle">{{ $homepage['template_6_grid_subtitle'] ?? 'Editorial Picks' }}</div>
                <h2 class="t6-sec-title">{{ $homepage['template_6_grid_title'] ?? 'Handcrafted Department Highlights' }}</h2>
            </div>
            <div class="t6-asym-grid">
                <a href="{{ $homepage['template_6_grid_main_url'] ?? route('shop') }}" class="t6-asym-main">
                    <img class="t6-asym-img" src="{{ $homepage['template_6_grid_main_image'] ?? 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?w=900&q=80' }}" alt="{{ $homepage['template_6_grid_main_title'] ?? 'Royal Panjabi' }}">
                    <div class="t6-asym-overlay">
                        <span class="t6-asym-badge">{{ $homepage['template_6_grid_main_badge'] ?? 'MASTER CRAFT' }}</span>
                        <h3 class="t6-asym-title">{{ $homepage['template_6_grid_main_title'] ?? 'Royal Embroidered Panjabi & Kurtas' }}</h3>
                        <p style="color:#d1d5db; font-size:13px; margin: 4px 0 0;">{{ $homepage['template_6_grid_main_desc'] ?? 'Finest raw silk with handcrafted zardozi collar embroidery.' }}</p>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_grid_item1_url'] ?? route('shop') }}" class="t6-asym-item">
                    <img class="t6-asym-img" src="{{ $homepage['template_6_grid_item1_image'] ?? 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80' }}" alt="{{ $homepage['template_6_grid_item1_title'] ?? 'Western Couture' }}">
                    <div class="t6-asym-overlay">
                        <span class="t6-asym-badge">{{ $homepage['template_6_grid_item1_badge'] ?? 'NEW IN' }}</span>
                        <h4 class="t6-asym-title">{{ $homepage['template_6_grid_item1_title'] ?? 'Designer Dresses' }}</h4>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_grid_item2_url'] ?? route('shop') }}" class="t6-asym-item">
                    <img class="t6-asym-img" src="{{ $homepage['template_6_grid_item2_image'] ?? 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=600&q=80' }}" alt="{{ $homepage['template_6_grid_item2_title'] ?? 'Blazers' }}">
                    <div class="t6-asym-overlay">
                        <span class="t6-asym-badge">{{ $homepage['template_6_grid_item2_badge'] ?? 'BESPOKE' }}</span>
                        <h4 class="t6-asym-title">{{ $homepage['template_6_grid_item2_title'] ?? 'Slim-fit Blazers' }}</h4>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_grid_item3_url'] ?? route('shop') }}" class="t6-asym-item">
                    <img class="t6-asym-img" src="{{ $homepage['template_6_grid_item3_image'] ?? 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&q=80' }}" alt="{{ $homepage['template_6_grid_item3_title'] ?? 'Footwear' }}">
                    <div class="t6-asym-overlay">
                        <span class="t6-asym-badge">{{ $homepage['template_6_grid_item3_badge'] ?? 'HANDMADE' }}</span>
                        <h4 class="t6-asym-title">{{ $homepage['template_6_grid_item3_title'] ?? 'Leather Loafers & Nagra' }}</h4>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_grid_item4_url'] ?? route('shop') }}" class="t6-asym-item">
                    <img class="t6-asym-img" src="{{ $homepage['template_6_grid_item4_image'] ?? 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600&q=80' }}" alt="{{ $homepage['template_6_grid_item4_title'] ?? 'Accessories' }}">
                    <div class="t6-asym-overlay">
                        <span class="t6-asym-badge">{{ $homepage['template_6_grid_item4_badge'] ?? 'PREMIUM' }}</span>
                        <h4 class="t6-asym-title">{{ $homepage['template_6_grid_item4_title'] ?? 'Timepieces & Brooches' }}</h4>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 5: Trending Now (Featured Products) --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t6-section">
            <div class="t6-container">
                <div class="t6-sec-head">
                    <div class="t6-sec-subtitle">Now Trending</div>
                    <h2 class="t6-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Signature Garments' }}</h2>
                </div>
                <div class="t6-product-grid">
                    @foreach($featuredProducts as $product)
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW ARRIVAL'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- SECTION 6: Bespoke Spotlight & Shoppable Luxury Collection --}}
    @php
        $spotlightList = collect();
        if (isset($latestProducts) && $latestProducts->count() > 0) {
            $spotlightList = $latestProducts->take(8);
        } elseif (isset($products) && $products->count() > 0) {
            $spotlightList = $products->take(8);
        } elseif (isset($features_products) && $features_products->count() > 0) {
            $spotlightList = $features_products->take(8);
        }
    @endphp

    <section class="t6-container">
        <div class="t6-size-banner">
            <div class="t6-size-content">
                <div class="t6-size-tag">{{ $homepage['template_6_spotlight_tag'] ?? '✦ ARTISANAL LUXURY & FIT' }}</div>
                <h3 class="t6-size-title">{{ $homepage['template_6_spotlight_title'] ?? 'Tailored Silhouette & Master Craft' }}</h3>
                <p class="t6-size-desc">
                    {{ $homepage['template_6_spotlight_desc'] ?? 'From hand-woven leather accessories to custom-fitted silk ensembles, explore our handcrafted artisanal pieces.' }}
                </p>
                <div>
                    <a href="{{ $homepage['template_6_spotlight_btn_url'] ?? route('shop') }}" class="t6-btn-gold">
                        {{ $homepage['template_6_spotlight_btn_text'] ?? 'VIEW COLLECTION →' }}
                    </a>
                </div>
            </div>
            <div class="t6-spotlight-wrapper">
                <div class="t6-spotlight-products" id="t6SpotlightTrack">
                    @if($spotlightList->count() > 0)
                        @foreach($spotlightList as $spotItem)
                            @php
                                $spotSlug = $spotItem->slug ?: $spotItem->id;
                                $spotUrl = route('product.single', ['slug' => $spotSlug, 'id' => $spotItem->id]);
                                $spotImg = !empty($spotItem->thumb_image) 
                                    ? (str_starts_with($spotItem->thumb_image, 'http') ? $spotItem->thumb_image : asset('storage/' . $spotItem->thumb_image)) 
                                    : 'https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?w=500&q=80';
                                $spotPrice = $spotItem->offer ? $spotItem->offer : ($spotItem->old_price ?? 0);
                            @endphp
                            <a href="{{ $spotUrl }}" class="t6-spotlight-card">
                                <img src="{{ $spotImg }}" alt="{{ $spotItem->title }}" onerror="this.src='https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?w=500&q=80'">
                                <div class="t6-spotlight-info">
                                    <span class="t6-spotlight-name" title="{{ $spotItem->title }}">{{ Str::limit($spotItem->title, 24) }}</span>
                                    <span class="t6-spotlight-price">৳{{ number_format((float)$spotPrice) }}</span>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('shop') }}" class="t6-spotlight-card">
                            <img src="https://images.unsplash.com/photo-1598532163257-ae3c6b2524b6?w=500&q=80" alt="Woven Leather Tote">
                            <div class="t6-spotlight-info">
                                <span class="t6-spotlight-name">Woven Leather Bag</span>
                                <span class="t6-spotlight-price">৳2,450</span>
                            </div>
                        </a>
                        <a href="{{ route('shop') }}" class="t6-spotlight-card">
                            <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&q=80" alt="Handmade Leather Loafers">
                            <div class="t6-spotlight-info">
                                <span class="t6-spotlight-name">Handmade Loafers</span>
                                <span class="t6-spotlight-price">৳1,850</span>
                            </div>
                        </a>
                        <a href="{{ route('shop') }}" class="t6-spotlight-card">
                            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=500&q=80" alt="Luxury Chronograph Watch">
                            <div class="t6-spotlight-info">
                                <span class="t6-spotlight-name">Gold Classic Watch</span>
                                <span class="t6-spotlight-price">৳3,200</span>
                            </div>
                        </a>
                        <a href="{{ route('shop') }}" class="t6-spotlight-card">
                            <img src="https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?w=500&q=80" alt="Zardozi Silk Stole">
                            <div class="t6-spotlight-info">
                                <span class="t6-spotlight-name">Zardozi Silk Stole</span>
                                <span class="t6-spotlight-price">৳1,650</span>
                            </div>
                        </a>
                    @endif
                </div>
                {{-- Carousel Left & Right Side Arrows --}}
                <button class="t6-spotlight-arrow prev" id="t6SpotPrev" aria-label="Previous Products">‹</button>
                <button class="t6-spotlight-arrow next" id="t6SpotNext" aria-label="Next Products">›</button>
            </div>
        </div>
    </section>

    {{-- SECTION 7: Style the Look (Shoppable Outfit Tiles) --}}
    <section class="t6-section">
        <div class="t6-container">
            <div class="t6-sec-head">
                <div class="t6-sec-subtitle">{{ $homepage['template_6_style_look_subtitle'] ?? 'Complete Ensembles' }}</div>
                <h2 class="t6-sec-title">{{ $homepage['template_6_style_look_title'] ?? 'Style the Full Look' }}</h2>
            </div>
            <div class="t6-outfits-grid">
                <a href="{{ $homepage['template_6_outfit1_url'] ?? route('shop') }}" class="t6-outfit-card">
                    <img class="t6-outfit-img" src="{{ $homepage['template_6_outfit1_image'] ?? 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80' }}" alt="{{ $homepage['template_6_outfit1_title'] ?? 'Evening Panjabi Set' }}">
                    <span class="t6-outfit-tag-btn">{{ $homepage['template_6_outfit1_tag'] ?? '✦ SHOP SET' }}</span>
                    <div class="t6-outfit-info">
                        <h4 class="t6-outfit-title">{{ $homepage['template_6_outfit1_title'] ?? 'Festive Eid Ensemble' }}</h4>
                        <span class="t6-outfit-items">{{ $homepage['template_6_outfit1_items'] ?? 'Panjabi + Pajama + Shawl + Nagra' }}</span>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_outfit2_url'] ?? route('shop') }}" class="t6-outfit-card">
                    <img class="t6-outfit-img" src="{{ $homepage['template_6_outfit2_image'] ?? 'https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?w=600&q=80' }}" alt="{{ $homepage['template_6_outfit2_title'] ?? 'Wedding Kurta Ensemble' }}">
                    <span class="t6-outfit-tag-btn">{{ $homepage['template_6_outfit2_tag'] ?? '✦ SHOP SET' }}</span>
                    <div class="t6-outfit-info">
                        <h4 class="t6-outfit-title">{{ $homepage['template_6_outfit2_title'] ?? 'Groom & Wedding Aura' }}</h4>
                        <span class="t6-outfit-items">{{ $homepage['template_6_outfit2_items'] ?? 'Sherwani + Embroidered Turban + Mojari' }}</span>
                    </div>
                </a>
                <a href="{{ $homepage['template_6_outfit3_url'] ?? route('shop') }}" class="t6-outfit-card">
                    <img class="t6-outfit-img" src="{{ $homepage['template_6_outfit3_image'] ?? 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80' }}" alt="{{ $homepage['template_6_outfit3_title'] ?? 'High Fashion Women' }}">
                    <span class="t6-outfit-tag-btn">{{ $homepage['template_6_outfit3_tag'] ?? '✦ SHOP SET' }}</span>
                    <div class="t6-outfit-info">
                        <h4 class="t6-outfit-title">{{ $homepage['template_6_outfit3_title'] ?? 'Evening Gala Gown' }}</h4>
                        <span class="t6-outfit-items">{{ $homepage['template_6_outfit3_items'] ?? 'Couture Dress + Clutch + Heels' }}</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 8: Curated Product Showcases (Best Selling, Editor's Picks, Trending Now) --}}
    @foreach(['best_selling' => ($homepage['best_selling_section_heading'] ?? 'Best Selling Collections'), 'editors_pick' => ($homepage['editors_pick_section_heading'] ?? "Editor's Curation"), 'trending' => ($homepage['trending_section_heading'] ?? 'Trending Now')] as $secKey => $secTitle)
        @if (!empty($homepage['enable_' . $secKey . '_section']) && $homepage['enable_' . $secKey . '_section'] && isset($featuredSections[$secKey]) && $featuredSections[$secKey]->count() > 0)
            <section class="t6-section">
                <div class="t6-container">
                    <div class="t6-sec-head">
                        <div class="t6-sec-subtitle">{{ $secKey === 'best_selling' ? 'Hot Deals' : ($secKey === 'editors_pick' ? 'Curated' : 'Popular') }}</div>
                        <h2 class="t6-sec-title">{{ $secTitle }}</h2>
                    </div>
                    <div class="t6-product-grid">
                        @foreach($featuredSections[$secKey] as $product)
                            @include('frontend.partials.product-item', ['product' => $product, 'badge' => strtoupper(str_replace('_', ' ', $secKey))])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    {{-- SECTION 9: Style Community Newsletter --}}
    <section class="t6-container">
        <div class="t6-newsletter-wrap">
            <div class="t6-sec-subtitle">{{ $homepage['template_6_vip_eyebrow'] ?? 'EXCLUSIVE PRIVILEGES' }}</div>
            <h3 class="t6-news-title">{{ $homepage['template_6_vip_title'] ?? 'Join The Couture Circle' }}</h3>
            <p class="t6-news-desc">{{ $homepage['template_6_vip_desc'] ?? 'Receive first-access to seasonal lookbooks, bespoke private sales, and fashion masterclasses directly to your inbox.' }}</p>
            <form class="t6-news-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to our Couture Circle!');">
                <input type="email" class="t6-news-input" placeholder="Enter your email address..." required>
                <button type="submit" class="t6-btn-gold">SUBSCRIBE</button>
            </form>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    const slides = document.querySelectorAll('.t6-slide');
    const prev = document.getElementById('t6Prev');
    const next = document.getElementById('t6Next');
    let cur = 0;
    let timer;

    function show(n) {
        if (!slides.length) return;
        slides[cur].classList.remove('active');
        cur = (n + slides.length) % slides.length;
        slides[cur].classList.add('active');
    }

    function resetTimer() {
        if (slides.length > 1) {
            clearInterval(timer);
            timer = setInterval(() => show(cur + 1), 6000);
        }
    }

    if (prev) prev.addEventListener('click', () => { show(cur - 1); resetTimer(); });
    if (next) next.addEventListener('click', () => { show(cur + 1); resetTimer(); });
    resetTimer();

    // Section 6 Spotlight Carousel Controls
    const spotTrack = document.getElementById('t6SpotlightTrack');
    const spotPrev = document.getElementById('t6SpotPrev');
    const spotNext = document.getElementById('t6SpotNext');

    if (spotTrack && spotPrev && spotNext) {
        spotPrev.addEventListener('click', () => {
            spotTrack.scrollBy({ left: -210, behavior: 'smooth' });
        });
        spotNext.addEventListener('click', () => {
            spotTrack.scrollBy({ left: 210, behavior: 'smooth' });
        });
    }
});
</script>
