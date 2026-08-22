{{-- ===================================================================
     TEMPLATE 3 — ELECTRONIC & TECH HUB (FULL DARK CYBERPUNK/TECH)
     Midnight Dark #080c14 · Electric Cyan Glow · Modern Tech Layout
     =================================================================== --}}

<style>
/* ── T3 BASE ── */
.t3-page {
    background-color: #070a12;
    color: #e2e8f0;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    position: relative;
    overflow-x: hidden;
}

/* Background Cyber Grid effect */
.t3-page::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(to right, rgba(56, 189, 248, 0.03) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
    z-index: 0;
}

.t3-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
    z-index: 1;
}

/* ── T3 HERO SECTION ── */
.t3-hero-section {
    padding: 20px 0 25px;
}
.t3-hero-grid {
    display: grid;
    grid-template-columns: 2.5fr 1fr;
    gap: 14px;
    align-items: stretch;
}
@media (max-width: 960px) {
    .t3-hero-grid {
        grid-template-columns: 1fr;
    }
    .t3-hero-side-promos {
        display: none !important;
    }
}

/* Left Tech Nav */
.t3-tech-nav {
    background: #0d1322;
    border: 1px solid rgba(56, 189, 248, 0.15);
    border-radius: 14px;
    padding: 12px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}
.t3-tech-nav-head {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #38bdf8;
    padding: 8px 12px 12px;
    border-bottom: 1px solid rgba(56, 189, 248, 0.12);
    display: flex;
    align-items: center;
    gap: 8px;
}
.t3-tech-nav-list {
    list-style: none;
    padding: 8px 0 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.t3-tech-nav-item a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.2s;
}
.t3-tech-nav-item a:hover {
    background: rgba(56, 189, 248, 0.1);
    color: #38bdf8;
    transform: translateX(4px);
}
.t3-tech-nav-item a .t3-icon-box {
    width: 26px; height: 26px;
    border-radius: 6px;
    background: rgba(255,255,255,0.05);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
}

/* Center Slider */
.t3-slider-wrap {
    background: #0d1322;
    border: 1px solid rgba(56, 189, 248, 0.2);
    border-radius: 14px;
    overflow: hidden;
    position: relative;
    height: 270px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6), 0 0 20px rgba(56, 189, 248, 0.08);
}
.t3-hero-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(13, 19, 34, 0.85);
    border: 1.5px solid #38bdf8;
    color: #38bdf8;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 0 16px rgba(56, 189, 248, 0.4);
    transition: all 0.2s ease;
}
.t3-hero-nav-arrow:hover {
    background: #38bdf8;
    color: #080c14;
    box-shadow: 0 0 24px rgba(56, 189, 248, 0.9);
    transform: translateY(-50%) scale(1.08);
}
.t3-hero-nav-prev { left: 16px; }
.t3-hero-nav-next { right: 16px; }

.t3-slides-container {
    position: relative;
    width: 100%;
    height: 100%;
}
.t3-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.6s ease;
    display: flex;
    align-items: flex-end;
    background: #080c14;
    text-decoration: none;
    color: inherit;
}
.t3-slide.t3-active {
    opacity: 1;
    z-index: 1;
}
.t3-slide img.t3-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.t3-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(8,12,20,0) 0%, rgba(8,12,20,0.15) 60%, rgba(8,12,20,0.45) 100%);
    pointer-events: none;
}
.t3-slide-content {
    position: relative;
    z-index: 2;
    padding: 24px 30px;
    width: 100%;
}
.t3-badge {
    display: inline-block;
    background: rgba(56, 189, 248, 0.15);
    border: 1px solid #38bdf8;
    color: #38bdf8;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
}
.t3-slide-title {
    font-size: clamp(22px, 3vw, 34px);
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px;
    line-height: 1.2;
}
.t3-slide-desc {
    font-size: 13.5px;
    color: #cbd5e1;
    margin: 0 0 16px;
    max-width: 500px;
    line-height: 1.4;
}
.t3-neon-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #0284c7, #2563eb);
    border: 1px solid #38bdf8;
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    padding: 9px 22px;
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);
    transition: all 0.25s ease;
}
.t3-neon-btn:hover {
    background: linear-gradient(135deg, #38bdf8, #0284c7);
    color: #080c14;
    box-shadow: 0 0 25px rgba(56, 189, 248, 0.8);
    text-decoration: none;
    transform: translateY(-2px);
}

/* Side Rotating Promos (2-Row Groups with 1 set of Left/Right Buttons) */
.t3-hero-side-promos {
    position: relative;
    height: 270px;
    background: #0d1322;
    border: 1px solid rgba(56, 189, 248, 0.2);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}
.t3-side-group {
    position: absolute;
    inset: 0;
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.45s ease, transform 0.45s ease;
    transform: scale(0.98);
}
.t3-side-group.active {
    opacity: 1;
    pointer-events: auto;
    z-index: 1;
    transform: scale(1);
}
.t3-side-card {
    flex: 1;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: #080c14;
    border: 1px solid rgba(56, 189, 248, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.t3-side-card:hover {
    transform: translateY(-2px);
    border-color: rgba(56, 189, 248, 0.5);
}
.t3-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}
.t3-side-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(13, 19, 34, 0.9);
    border: 1.5px solid #38bdf8;
    color: #38bdf8;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
}
.t3-side-nav-btn:hover {
    background: #38bdf8;
    color: #080c14;
    transform: translateY(-50%) scale(1.08);
}
.t3-side-prev { left: 10px; }
.t3-side-next { right: 10px; }
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.3);
    transition: all 0.2s ease;
}
.t3-side-nav-btn:hover {
    background: #38bdf8;
    color: #080c14;
}
.t3-side-prev { left: 8px; }
.t3-side-next { right: 8px; }

/* ── T3 TECH TICKER ── */
.t3-ticker-bar {
    background: #0d1322;
    border-top: 1px solid rgba(56, 189, 248, 0.12);
    border-bottom: 1px solid rgba(56, 189, 248, 0.12);
    padding: 12px 0;
    margin-bottom: 30px;
}
.t3-ticker-flex {
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.t3-ticker-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #cbd5e1;
}
.t3-ticker-item svg {
    color: #38bdf8;
}

/* ── T3 CATEGORY CHIPS ── */
.t3-section-title-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    border-bottom: 1px solid rgba(56, 189, 248, 0.15);
    padding-bottom: 12px;
}
.t3-sec-title {
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #f8fafc;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.t3-sec-title::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 20px;
    background: #38bdf8;
    border-radius: 2px;
    box-shadow: 0 0 10px #38bdf8;
}
.t3-view-all-link {
    color: #38bdf8;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
}
.t3-view-all-link:hover {
    color: #7dd3fc;
    text-shadow: 0 0 8px rgba(56, 189, 248, 0.6);
}

.t3-cat-chips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 40px;
}
.t3-cat-chip {
    background: #0d1322;
    border: 1px solid rgba(56, 189, 248, 0.15);
    border-radius: 12px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: all 0.25s ease;
}
.t3-cat-chip:hover {
    background: #111a30;
    border-color: #38bdf8;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(56, 189, 248, 0.15);
}
.t3-cat-chip-img {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: #1e293b;
    overflow: hidden;
    flex-shrink: 0;
}
.t3-cat-chip-img img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.t3-cat-chip-name {
    color: #f1f5f9;
    font-size: 13.5px;
    font-weight: 700;
}

/* ── T3 PRODUCT SECTIONS ── */
.t3-products-block {
    margin-bottom: 44px;
}
.t3-product-slider-row {
    position: relative;
}
.t3-products-carousel {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 16px;
}
.t3-products-carousel::-webkit-scrollbar { display: none; }
.t3-prod-card-col {
    flex: 0 0 calc(20% - 13px);
    min-width: 220px;
}
@media (max-width: 1100px) {
    .t3-prod-card-col { flex: 0 0 calc(25% - 12px); }
}
@media (max-width: 860px) {
    .t3-prod-card-col { flex: 0 0 calc(33.33% - 11px); min-width: 190px; }
}
@media (max-width: 576px) {
    .t3-prod-card-col { flex: 0 0 calc(50% - 8px); min-width: 155px; }
}

.t3-slider-btn {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    width: 38px; height: 38px;
    border-radius: 8px;
    background: #0d1322;
    border: 1px solid #38bdf8;
    color: #38bdf8;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.3);
    transition: all 0.2s;
}
.t3-slider-btn:hover {
    background: #38bdf8;
    color: #080c14;
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.8);
}
.t3-slider-btn-prev { left: -14px; }
.t3-slider-btn-next { right: -14px; }

/* ── OVERRIDE PRODUCT-ITEM STYLES FOR T3 ── */
.t3-page .product-card {
    background: #0d1322 !important;
    border: 1px solid rgba(56, 189, 248, 0.15) !important;
    border-radius: 12px !important;
    box-shadow: 0 6px 16px rgba(0,0,0,0.5) !important;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
.t3-page .product-card:hover {
    border-color: #38bdf8 !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 28px rgba(56, 189, 248, 0.2), 0 0 12px rgba(56, 189, 248, 0.15) !important;
}
.t3-page .product-card .product-title {
    color: #f1f5f9 !important;
}
.t3-page .product-card .current-price {
    color: #38bdf8 !important;
    font-weight: 800 !important;
}
.t3-page .product-card .original-price {
    color: #64748b !important;
}
.t3-page .product-card .add-to-cart-btn,
.t3-page .product-card .buy-now-btn {
    background: linear-gradient(135deg, #0284c7, #2563eb) !important;
    border: 1px solid #38bdf8 !important;
    color: #fff !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4) !important;
}
.t3-page .product-card .add-to-cart-btn:hover,
.t3-page .product-card .buy-now-btn:hover {
    background: #38bdf8 !important;
    color: #080c14 !important;
    box-shadow: 0 0 18px rgba(56, 189, 248, 0.7) !important;
}
.t3-page .product-card .item-cart-icon {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #38bdf8 !important;
}
</style>

<div class="t3-page">

    {{-- ── HERO SECTION ── --}}
    <section class="t3-hero-section">
        <div class="t3-container">
            <div class="t3-hero-grid">
                
                {{-- CENTER: CYBER SLIDER --}}
                <div class="t3-slider-wrap">
                    <button class="t3-hero-nav-arrow t3-hero-nav-prev" id="t3-hero-prev" aria-label="Previous Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t3-hero-nav-arrow t3-hero-nav-next" id="t3-hero-next" aria-label="Next Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    <div class="t3-slides-container" id="t3-slides">
                        @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t3-slide {{ $idx === 0 ? 't3-active' : '' }}">
                            <img class="t3-bg" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Tech Hub' }}">
                            @if($slider->title || $slider->button_text || $slider->description)
                                <div class="t3-slide-overlay"></div>
                                <div class="t3-slide-content">
                                    @if($slider->title)
                                        <h2 class="t3-slide-title">{{ $slider->title }}</h2>
                                    @endif
                                    @if($slider->description)
                                        <p class="t3-slide-desc">{{ $slider->description }}</p>
                                    @endif
                                    @if($slider->button_text)
                                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                            <span class="t3-neon-btn">
                                                {{ $slider->button_text }}
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </a>
                        @empty
                        <a href="{{ route('shop') }}" class="t3-slide t3-active">
                            <img class="t3-bg" src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1600&q=80" alt="Cyber Tech">
                        </a>
                        @endforelse
                    </div>
                </div>

                {{-- RIGHT: UNIFIED 2-ROW SIDE PROMO GROUP WITH 1 PAIR OF NEXT/PREV BUTTONS --}}
                <div class="t3-hero-side-promos">
                    @php
                        $t3AllImages = [];
                        for ($i = 1; $i <= 6; $i++) {
                            $img = $homepage['featured_image_' . $i] ?? ($i === 1 ? ($homepage['slider_side_image_one'] ?? ($homepage['slider_side_image'] ?? null)) : ($i === 2 ? ($homepage['slider_side_image_two'] ?? null) : null));
                            if (!empty($img)) {
                                $t3AllImages[] = [
                                    'image' => $img,
                                    'link'  => $homepage['featured_image_' . $i . '_link'] ?? ($i === 1 ? ($homepage['slider_side_image_one_link'] ?? route('shop')) : ($i === 2 ? ($homepage['slider_side_image_two_link'] ?? route('shop')) : route('shop'))),
                                    'alt'   => $homepage['featured_image_' . $i . '_alt'] ?? ('Tech Promo ' . $i),
                                ];
                            }
                        }
                        if (empty($t3AllImages)) {
                            $t3AllImages = [
                                ['image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&q=80', 'link' => route('shop'), 'alt' => 'Tech Deal 1'],
                                ['image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80', 'link' => route('shop'), 'alt' => 'Tech Deal 2'],
                            ];
                        }
                        $t3Groups = array_chunk($t3AllImages, 2);
                    @endphp

                    @if(count($t3Groups) > 1)
                        <button class="t3-side-nav-btn t3-side-prev" data-dir="-1" aria-label="Previous Promo Pair">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button class="t3-side-nav-btn t3-side-next" data-dir="1" aria-label="Next Promo Pair">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    @endif

                    @foreach($t3Groups as $gIdx => $group)
                        <div class="t3-side-group {{ $gIdx === 0 ? 'active' : '' }}">
                            @foreach($group as $item)
                                <a href="{{ $item['link'] }}" class="t3-side-card">
                                    <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']) }}" alt="{{ $item['alt'] }}" loading="lazy">
                                </a>
                            @endforeach
                            @if(count($group) === 1 && count($t3AllImages) > 1)
                                {{-- Fill 2nd slot with first image if odd number --}}
                                <a href="{{ $t3AllImages[0]['link'] }}" class="t3-side-card">
                                    <img src="{{ str_starts_with($t3AllImages[0]['image'], 'http') ? $t3AllImages[0]['image'] : asset($t3AllImages[0]['image']) }}" alt="{{ $t3AllImages[0]['alt'] }}" loading="lazy">
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- ── TICKER STRIP ── --}}
    <div class="t3-ticker-bar">
        <div class="t3-container">
            <div class="t3-ticker-flex">
                <div class="t3-ticker-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    Express Superfast Delivery
                </div>
                <div class="t3-ticker-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    100% Genuine Official Warranty
                </div>
                <div class="t3-ticker-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    Exclusive Tech Discounts Daily
                </div>
                <div class="t3-ticker-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 14 14"></polyline></svg>
                    24/7 Priority Tech Support
                </div>
            </div>
        </div>
    </div>

    {{-- ── CATEGORIES MATRIX ── --}}
    @if(isset($featuredItems) && $featuredItems->count() > 0)
    <section class="t3-container" style="margin-bottom: 30px;">
        <div class="t3-section-title-wrap">
            <h3 class="t3-sec-title">Tech Categories</h3>
            <a href="{{ route('shop') }}" class="t3-view-all-link">Browse All &rarr;</a>
        </div>
        <div class="t3-cat-chips-grid">
            @foreach($featuredItems as $cat)
                <a href="{{ route('shop', $cat->slug) }}" class="t3-cat-chip">
                    <div class="t3-cat-chip-img">
                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}">
                    </div>
                    <span class="t3-cat-chip-name">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── PRODUCT SECTIONS (BEST SELLING, TRENDING, EDITORS PICK) ── --}}
    @foreach(['best_selling' => ($homepage['best_selling_section_heading'] ?? 'Trending Gear'), 'trending' => ($homepage['trending_section_heading'] ?? 'Hot Tech Deals'), 'editors_pick' => ($homepage['editors_pick_section_heading'] ?? "Top Rated Devices")] as $secKey => $secTitle)
        @if (!empty($homepage['enable_' . $secKey . '_section']) && $homepage['enable_' . $secKey . '_section'] && isset($featuredSections[$secKey]) && $featuredSections[$secKey]->count() > 0)
            <section class="t3-container t3-products-block">
                <div class="t3-section-title-wrap">
                    <h3 class="t3-sec-title">{{ $secTitle }}</h3>
                    <a href="{{ route('shop') }}" class="t3-view-all-link">View All &rarr;</a>
                </div>
                <div class="t3-product-slider-row">
                    <button class="t3-slider-btn t3-slider-btn-prev" data-target="t3-row-{{ $secKey }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <div class="t3-products-carousel" id="t3-row-{{ $secKey }}">
                        @foreach($featuredSections[$secKey] as $product)
                            <div class="t3-prod-card-col">
                                @include('frontend.partials.product-item', ['product' => $product, 'badge' => '⚡ SPEC'])
                            </div>
                        @endforeach
                    </div>
                    <button class="t3-slider-btn t3-slider-btn-next" data-target="t3-row-{{ $secKey }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </section>
        @endif
    @endforeach

    {{-- ── LATEST PRODUCTS ── --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t3-container t3-products-block">
            <div class="t3-section-title-wrap">
                <h3 class="t3-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'Latest Tech Arrivals' }}</h3>
                <a href="{{ route('shop') }}" class="t3-view-all-link">View All &rarr;</a>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px;">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW TECH'])
                @endforeach
            </div>
        </section>
    @endif

</div>{{-- .t3-page --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // T3 Slider auto rotate & manual buttons
    const t3Slides = document.querySelectorAll('.t3-slide');
    const t3PrevBtn = document.getElementById('t3-hero-prev');
    const t3NextBtn = document.getElementById('t3-hero-next');
    let t3Cur = 0;
    let t3Timer;

    function t3ShowSlide(n) {
        if (!t3Slides.length) return;
        t3Slides[t3Cur].classList.remove('t3-active');
        t3Cur = (n + t3Slides.length) % t3Slides.length;
        t3Slides[t3Cur].classList.add('t3-active');
    }

    function t3StartTimer() {
        if (t3Slides.length > 1) {
            clearInterval(t3Timer);
            t3Timer = setInterval(() => t3ShowSlide(t3Cur + 1), 5000);
        }
    }

    if (t3PrevBtn) {
        t3PrevBtn.addEventListener('click', () => {
            t3ShowSlide(t3Cur - 1);
            t3StartTimer();
        });
    }
    if (t3NextBtn) {
        t3NextBtn.addEventListener('click', () => {
            t3ShowSlide(t3Cur + 1);
            t3StartTimer();
        });
    }
    t3StartTimer();

    // T3 Side Groups (2-row pair rotation with 1 Left/Right button)
    const sidePromosContainer = document.querySelector('.t3-hero-side-promos');
    if (sidePromosContainer) {
        const groups = sidePromosContainer.querySelectorAll('.t3-side-group');
        if (groups.length > 1) {
            let gIdx = 0;
            let gTimer;

            function showSideGroup(n) {
                groups[gIdx].classList.remove('active');
                gIdx = (n + groups.length) % groups.length;
                groups[gIdx].classList.add('active');
            }

            function startSideGroupTimer() {
                clearInterval(gTimer);
                gTimer = setInterval(() => showSideGroup(gIdx + 1), 4500);
            }

            sidePromosContainer.querySelectorAll('.t3-side-nav-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const dir = parseInt(btn.dataset.dir || '1');
                    showSideGroup(gIdx + dir);
                    startSideGroupTimer();
                });
            });

            startSideGroupTimer();
        }
    }
});
</script>
