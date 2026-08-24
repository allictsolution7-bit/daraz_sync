{{-- ===================================================================
     TEMPLATE 4 — LUXURY & MINIMALIST EDITORIAL (REFINED BOUTIQUE)
     Dark Charcoal & Warm Champagne Gold · Refined Typography · Premium Feel
     =================================================================== --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

/* ── T4 LUXURY EDITORIAL BASE ── */
.t4-page {
    background-color: #0f1115;
    color: #e2e8f0;
    min-height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.t4-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ── T4 EDITORIAL TOP RIBBON ── */
.t4-top-bar {
    background: #171a21;
    border-bottom: 1px solid rgba(212, 175, 55, 0.25);
    color: #d4af37;
    padding: 11px 24px;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
}
.t4-top-bar span {
    font-family: 'Cinzel', serif;
    font-weight: 700;
}
.t4-timer-wrap {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #94a3b8;
    font-size: 12px;
}
.t4-timer-box {
    background: #090a0d;
    color: #fef08a;
    border: 1px solid rgba(212, 175, 55, 0.4);
    padding: 3px 8px;
    border-radius: 4px;
    font-weight: 800;
    font-size: 12px;
    min-width: 26px;
    text-align: center;
}

/* ── T4 HERO GRID ── */
.t4-hero-section {
    padding: 16px 0 20px;
}

@media (max-width: 640px) {
    .t4-hero-section {
        padding: 10px 0 14px;
    }
}

.t4-hero-layout {
    display: grid;
    grid-template-columns: 2.2fr 1fr;
    gap: 14px;
    align-items: stretch;
}

@media (max-width: 900px) {
    .t4-hero-layout {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}

/* Luxury Hero Slider */
.t4-hero-slider {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    aspect-ratio: 16 / 7.5;
    min-height: 180px;
    background: #171a21;
    border: 1px solid rgba(212, 175, 55, 0.3);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6);
}

@media (max-width: 900px) {
    .t4-hero-slider {
        aspect-ratio: 16 / 9;
        min-height: 140px;
        border-radius: 10px;
    }
}

.t4-hero-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(15, 17, 21, 0.85);
    border: 1px solid #d4af37;
    color: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
    transition: all 0.25s ease;
    backdrop-filter: blur(8px);
}

.t4-hero-nav-arrow:hover {
    background: #d4af37;
    color: #0f1115;
    transform: translateY(-50%) scale(1.06);
}

.t4-hero-nav-prev { left: 12px; }
.t4-hero-nav-next { right: 12px; }

@media (max-width: 640px) {
    .t4-hero-nav-arrow {
        width: 26px;
        height: 26px;
    }
    .t4-hero-nav-arrow svg {
        width: 14px;
        height: 14px;
    }
    .t4-hero-nav-prev { left: 6px; }
    .t4-hero-nav-next { right: 6px; }
}

.t4-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.6s ease;
    display: flex;
    align-items: flex-end;
    background: #0f1115;
    text-decoration: none;
    color: inherit;
}

.t4-slide.t4-active {
    opacity: 1;
    z-index: 1;
}

.t4-slide-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: fill;
    object-position: center;
    display: block;
}

.t4-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15,17,21,0) 0%, rgba(15,17,21,0.1) 60%, rgba(15,17,21,0.4) 100%);
    pointer-events: none;
}

.t4-slide-content {
    position: relative;
    z-index: 2;
    padding: 20px 24px;
    width: 100%;
}

@media (max-width: 640px) {
    .t4-slide-content {
        padding: 10px 12px;
    }
}

.t4-slide-cta-row {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.t4-badge-flash {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(212, 175, 55, 0.15);
    color: #d4af37;
    border: 1px solid #d4af37;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    padding: 5px 12px;
    border-radius: 4px;
}

.t4-deal-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #d4af37, #aa8214);
    color: #0f1115;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 7px 18px;
    border-radius: 4px;
    text-decoration: none;
    box-shadow: 0 4px 18px rgba(212, 175, 55, 0.35);
    transition: all 0.25s ease;
}

.t4-deal-btn:hover {
    background: #fef08a;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(212, 175, 55, 0.5);
    color: #0f1115;
    text-decoration: none;
}

/* Side Rotating Deals */
.t4-side-deals {
    position: relative;
    background: #171a21;
    border: 1px solid rgba(212, 175, 55, 0.25);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    height: auto;
    aspect-ratio: 16 / 7.5;
}

@media (max-width: 900px) {
    .t4-side-deals {
        display: block !important;
        aspect-ratio: 16 / 7;
        min-height: 110px;
        border-radius: 10px;
    }
}

.t4-side-group {
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

@media (max-width: 900px) {
    .t4-side-group {
        flex-direction: row;
        gap: 6px;
        padding: 6px;
    }
}

.t4-side-group.active {
    opacity: 1;
    pointer-events: auto;
    z-index: 1;
    transform: scale(1);
}

.t4-side-card {
    flex: 1;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #0f1115;
    border: 1px solid rgba(212, 175, 55, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: transform 0.2s ease, border-color 0.2s ease;
}

.t4-side-card:hover {
    transform: translateY(-2px);
    border-color: rgba(212, 175, 55, 0.5);
}

.t4-side-card img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    object-position: center;
    display: block;
}

.t4-side-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(15, 17, 21, 0.9);
    border: 1.5px solid #d4af37;
    color: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.6);
    transition: all 0.2s ease;
    backdrop-filter: blur(8px);
}

.t4-side-nav-btn:hover {
    background: #d4af37;
    color: #0f1115;
    transform: translateY(-50%) scale(1.08);
}

.t4-side-prev { left: 8px; }
.t4-side-next { right: 8px; }

@media (max-width: 640px) {
    .t4-side-nav-btn {
        width: 24px;
        height: 24px;
    }
    .t4-side-nav-btn svg {
        width: 12px;
        height: 12px;
    }
    .t4-side-prev { left: 4px; }
    .t4-side-next { right: 4px; }
}

/* ── T4 CATEGORY CURATION STRIP ── */
.t4-deals-cat-strip {
    background: #171a21;
    border-radius: 16px;
    border: 1px solid rgba(212, 175, 55, 0.2);
    padding: 16px 22px;
    margin-bottom: 30px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.t4-strip-head {
    font-family: 'Cinzel', serif;
    font-size: 13.5px;
    font-weight: 700;
    color: #d4af37;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}
.t4-cat-pills {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scrollbar-width: none;
}
.t4-cat-pills::-webkit-scrollbar { display: none; }
.t4-pill {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #0f1115;
    border: 1px solid rgba(212, 175, 55, 0.25);
    padding: 9px 20px;
    border-radius: 6px;
    color: #cbd5e1;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-decoration: none;
    transition: all 0.25s;
}
.t4-pill:hover {
    background: rgba(212, 175, 55, 0.12);
    border-color: #d4af37;
    color: #fef08a;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(212, 175, 55, 0.15);
    text-decoration: none;
}
.t4-pill img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
}

/* ── T4 PRODUCT SECTIONS ── */
.t4-prod-slider-wrap {
    margin-bottom: 40px;
}
.t4-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(212, 175, 55, 0.2);
}
.t4-sec-title {
    font-family: 'Cinzel', serif;
    font-size: 21px;
    font-weight: 700;
    color: #f1f5f9;
    letter-spacing: 1px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.t4-view-all {
    font-size: 12.5px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #d4af37;
    text-decoration: none;
    transition: all 0.2s;
}
.t4-view-all:hover {
    color: #fef08a;
    transform: translateX(4px);
    text-decoration: none;
}

.t4-prod-row {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding-bottom: 12px;
    scrollbar-width: none;
}
.t4-prod-row::-webkit-scrollbar { display: none; }
.t4-col-item {
    flex: 0 0 calc(20% - 13px);
    min-width: 220px;
}
@media (max-width: 1100px) { .t4-col-item { flex: 0 0 calc(25% - 12px); } }
@media (max-width: 860px) { .t4-col-item { flex: 0 0 calc(33.33% - 11px); min-width: 180px; } }
@media (max-width: 576px) { .t4-col-item { flex: 0 0 calc(50% - 8px); min-width: 155px; } }

.t4-arrow {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #171a21;
    border: 1px solid rgba(212, 175, 55, 0.4);
    color: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 4px 14px rgba(0,0,0,0.5);
    transition: all 0.2s;
}
.t4-arrow:hover {
    background: #d4af37;
    color: #0f1115;
    border-color: #d4af37;
}
.t4-arrow-prev { left: -18px; }
.t4-arrow-next { right: -18px; }

/* ── LUXURY PRODUCT CARD OVERRIDES ── */
.t4-page .product-card {
    background: #171a21 !important;
    border: 1px solid rgba(212, 175, 55, 0.2) !important;
    border-radius: 12px !important;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3) !important;
    transition: all 0.3s ease !important;
}
.t4-page .product-card:hover {
    border-color: #d4af37 !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 14px 30px rgba(212, 175, 55, 0.15) !important;
}
.t4-page .product-card .product-title {
    color: #f1f5f9 !important;
    font-weight: 600 !important;
}
.t4-page .product-card .current-price {
    color: #d4af37 !important;
    font-size: 17px !important;
    font-weight: 800 !important;
}
.t4-page .product-card .add-to-cart-btn,
.t4-page .product-card .buy-now-btn {
    background: linear-gradient(135deg, #d4af37, #aa8214) !important;
    border: none !important;
    color: #0f1115 !important;
    font-weight: 700 !important;
    border-radius: 4px !important;
    box-shadow: 0 4px 14px rgba(212, 175, 55, 0.25) !important;
    transition: all 0.2s ease !important;
}
.t4-page .product-card .add-to-cart-btn:hover,
.t4-page .product-card .buy-now-btn:hover {
    background: #fef08a !important;
    color: #0f1115 !important;
}
</style>

@php
    $t4OfferEndTime = $homepage['template_4_offer_end_time'] ?? null;
    $t4OfferHeadline = $homepage['template_4_offer_heading'] ?? '✨ EXCLUSIVE CURATED COLLECTION • LIMITED BOUTIQUE EDITIONS';
    $t4OfferLabel = $homepage['template_4_offer_label'] ?? 'OFFER CLOSES IN:';
    $t4OfferBtnText = $homepage['template_4_offer_btn_text'] ?? 'EXPLORE CATALOG →';
    $t4OfferBtnUrl = $homepage['template_4_offer_btn_url'] ?? route('shop');
@endphp

<div class="t4-page">

    {{-- ── EDITORIAL TOP RIBBON ── --}}
    <div class="t4-top-bar">
        <span>{{ $t4OfferHeadline }}</span>
        <div class="t4-timer-wrap">
            <span>{{ $t4OfferLabel }}</span>
            <span id="t4-days-container" style="display:none;"><span class="t4-timer-box" id="t4-days">00</span> :</span>
            <span class="t4-timer-box" id="t4-hours">08</span> :
            <span class="t4-timer-box" id="t4-mins">45</span> :
            <span class="t4-timer-box" id="t4-secs">20</span>
        </div>
        <a href="{{ $t4OfferBtnUrl }}" style="color:#d4af37; text-decoration:underline; font-weight:700; font-size:12px;">{{ $t4OfferBtnText }}</a>
    </div>

    {{-- ── HERO SECTION ── --}}
    <section class="t4-hero-section">
        <div class="t4-container">
            <div class="t4-hero-layout">
                
                {{-- BIG HERO SLIDER --}}
                <div class="t4-hero-slider" id="t4-slider">
                    <button class="t4-hero-nav-arrow t4-hero-nav-prev" id="t4-hero-prev" aria-label="Previous Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t4-hero-nav-arrow t4-hero-nav-next" id="t4-hero-next" aria-label="Next Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t4-slide {{ $idx === 0 ? 't4-active' : '' }}">
                            <img class="t4-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Luxury Boutique' }}">
                            @if($slider->title || $slider->button_text)
                                <div class="t4-slide-overlay"></div>
                                <div class="t4-slide-content">
                                    <div class="t4-slide-cta-row">
                                        @if($slider->title)
                                            <div class="t4-badge-flash">{{ $slider->title }}</div>
                                        @endif
                                        @if($slider->button_text)
                                            <span class="t4-deal-btn">
                                                {{ $slider->button_text }}
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t4-slide t4-active">
                            <img class="t4-slide-img" src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1600&q=80" alt="Luxury Boutique">
                        </a>
                    @endforelse
                </div>

                {{-- RIGHT PROMO TILES --}}
                <div class="t4-side-deals">
                    @php
                        $t4AllImages = [];
                        for ($i = 1; $i <= 6; $i++) {
                            $img = $homepage['featured_image_' . $i] ?? ($i === 1 ? ($homepage['slider_side_image_one'] ?? ($homepage['slider_side_image'] ?? null)) : ($i === 2 ? ($homepage['slider_side_image_two'] ?? null) : null));
                            if (!empty($img)) {
                                $t4AllImages[] = [
                                    'image' => $img,
                                    'link'  => $homepage['featured_image_' . $i . '_link'] ?? ($i === 1 ? ($homepage['slider_side_image_one_link'] ?? route('shop')) : ($i === 2 ? ($homepage['slider_side_image_two_link'] ?? route('shop')) : route('shop'))),
                                    'alt'   => $homepage['featured_image_' . $i . '_alt'] ?? ('Promo Banner ' . $i),
                                ];
                            }
                        }
                        if (empty($t4AllImages)) {
                            $t4AllImages = [
                                ['image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&q=80', 'link' => route('shop'), 'alt' => 'Promo 1'],
                                ['image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80', 'link' => route('shop'), 'alt' => 'Promo 2'],
                            ];
                        }
                        $t4Groups = array_chunk($t4AllImages, 2);
                    @endphp

                    @if(count($t4Groups) > 1)
                        <button class="t4-side-nav-btn t4-side-prev" data-dir="-1" aria-label="Previous Promo Pair">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button class="t4-side-nav-btn t4-side-next" data-dir="1" aria-label="Next Promo Pair">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    @endif

                    @foreach($t4Groups as $gIdx => $group)
                        <div class="t4-side-group {{ $gIdx === 0 ? 'active' : '' }}">
                            @foreach($group as $item)
                                <a href="{{ $item['link'] }}" class="t4-side-card">
                                    <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']) }}" alt="{{ $item['alt'] }}" loading="lazy">
                                </a>
                            @endforeach
                            @if(count($group) === 1 && count($t4AllImages) > 1)
                                {{-- Fill 2nd slot with first image if odd number --}}
                                <a href="{{ $t4AllImages[0]['link'] }}" class="t4-side-card">
                                    <img src="{{ str_starts_with($t4AllImages[0]['image'], 'http') ? $t4AllImages[0]['image'] : asset($t4AllImages[0]['image']) }}" alt="{{ $t4AllImages[0]['alt'] }}" loading="lazy">
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- ── CURATED CATEGORIES STRIP ── --}}
    @if(isset($featuredItems) && $featuredItems->count() > 0)
    <div class="t4-container">
        <div class="t4-deals-cat-strip">
            <div class="t4-strip-head">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                Curated Collections
            </div>
            <div class="t4-cat-pills">
                @foreach($featuredItems as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t4-pill">
                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ── LUXURY PRODUCT SECTIONS ── --}}
    @foreach(['best_selling' => ($homepage['best_selling_section_heading'] ?? '✨ Connoisseur’s Choice'), 'trending' => ($homepage['trending_section_heading'] ?? '⭐ Haute Trending'), 'editors_pick' => ($homepage['editors_pick_section_heading'] ?? "⚜️ Private Reserve Selection")] as $secKey => $secTitle)
        @if (!empty($homepage['enable_' . $secKey . '_section']) && $homepage['enable_' . $secKey . '_section'] && isset($featuredSections[$secKey]) && $featuredSections[$secKey]->count() > 0)
            <section class="t4-container t4-prod-slider-wrap">
                <div class="t4-sec-header">
                    <h2 class="t4-sec-title">
                        {{ $secTitle }}
                    </h2>
                    <a href="{{ route('shop') }}" class="t4-view-all">Explore Collection &rarr;</a>
                </div>
                <div style="position: relative;">
                    <button class="t4-arrow t4-arrow-prev" data-target="t4-row-{{ $secKey }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <div class="t4-prod-row" id="t4-row-{{ $secKey }}">
                        @foreach($featuredSections[$secKey] as $product)
                            <div class="t4-col-item">
                                @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'EXCLUSIVE'])
                            </div>
                        @endforeach
                    </div>
                    <button class="t4-arrow t4-arrow-next" data-target="t4-row-{{ $secKey }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </section>
        @endif
    @endforeach

    {{-- ── LATEST ARRIVALS ── --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t4-container" style="margin-bottom: 50px;">
            <div class="t4-sec-header">
                <h2 class="t4-sec-title">{{ $homepage['latest_products_section_heading'] ?? '💎 New Boutique Arrivals' }}</h2>
                <a href="{{ route('shop') }}" class="t4-view-all">View All &rarr;</a>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px;">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'DEAL'])
                @endforeach
            </div>
        </section>
    @endif

</div>{{-- .t4-page --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // T4 Live Countdown calculation
    const endTimestampStr = @json($t4OfferEndTime ?? '');
    const dContainer = document.getElementById('t4-days-container');
    const dEl = document.getElementById('t4-days');
    const hEl = document.getElementById('t4-hours');
    const mEl = document.getElementById('t4-mins');
    const sEl = document.getElementById('t4-secs');

    let targetDate = endTimestampStr ? new Date(endTimestampStr).getTime() : null;

    // Fallback: If not configured or passed, countdown to end of current day
    if (!targetDate || isNaN(targetDate) || targetDate <= Date.now()) {
        const now = new Date();
        targetDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59).getTime();
    }

    function updateT4Countdown() {
        const now = Date.now();
        let diff = Math.max(0, targetDate - now);

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);

        if (days > 0 && dContainer && dEl) {
            dContainer.style.display = 'inline';
            dEl.textContent = String(days).padStart(2, '0');
        } else if (dContainer) {
            dContainer.style.display = 'none';
        }

        if (hEl) hEl.textContent = String(hours).padStart(2, '0');
        if (mEl) mEl.textContent = String(mins).padStart(2, '0');
        if (sEl) sEl.textContent = String(secs).padStart(2, '0');
    }

    updateT4Countdown();
    setInterval(updateT4Countdown, 1000);

    // T4 Hero Slider
    const t4Slides = document.querySelectorAll('.t4-slide');
    const t4PrevBtn = document.getElementById('t4-hero-prev');
    const t4NextBtn = document.getElementById('t4-hero-next');
    let t4Idx = 0;
    let t4Timer;

    function t4ShowSlide(n) {
        if (!t4Slides.length) return;
        t4Slides[t4Idx].classList.remove('t4-active');
        t4Idx = (n + t4Slides.length) % t4Slides.length;
        t4Slides[t4Idx].classList.add('t4-active');
    }

    function t4StartTimer() {
        if (t4Slides.length > 1) {
            clearInterval(t4Timer);
            t4Timer = setInterval(() => t4ShowSlide(t4Idx + 1), 4500);
        }
    }

    if (t4PrevBtn) {
        t4PrevBtn.addEventListener('click', () => {
            t4ShowSlide(t4Idx - 1);
            t4StartTimer();
        });
    }
    if (t4NextBtn) {
        t4NextBtn.addEventListener('click', () => {
            t4ShowSlide(t4Idx + 1);
            t4StartTimer();
        });
    }
    t4StartTimer();

    // T4 Side Groups (2-row pair rotation with 1 Left/Right button)
    const sideDealsContainer = document.querySelector('.t4-side-deals');
    if (sideDealsContainer) {
        const groups = sideDealsContainer.querySelectorAll('.t4-side-group');
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

            sideDealsContainer.querySelectorAll('.t4-side-nav-btn').forEach(btn => {
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

    // T4 Product Row Arrow Scrolling
    document.querySelectorAll('.t4-arrow').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const row = document.getElementById(targetId);
            if (!row) return;
            const isPrev = this.classList.contains('t4-arrow-prev');
            const scrollAmount = row.clientWidth * 0.75 || 320;
            row.scrollBy({
                left: isPrev ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        });
    });
});
</script>
