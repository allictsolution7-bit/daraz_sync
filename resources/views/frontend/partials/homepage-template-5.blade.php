{{-- ===================================================================
     TEMPLATE 5 — GROCERY & FRESH EXPRESS (ORGANIC, CLEAN & MODERN)
     Fresh Forest Green · Soft Ivory · Nature-inspired Curved Cards
     =================================================================== --}}

<style>
/* ── T5 BASE ── */
.t5-page {
    background-color: #f7fdf9;
    color: #14532d;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.t5-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 20px;
}

/* ── T5 FULL-WIDTH HERO ── */
.t5-hero-section {
    padding: 20px 0 24px;
}
.t5-hero-full-wrap {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.t5-slider-box {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    aspect-ratio: 16 / 7.5;
    min-height: 180px;
    box-shadow: 0 10px 30px rgba(22, 101, 52, 0.08);
    border: 2px solid #dcfce7;
}

@media (max-width: 640px) {
    .t5-slider-box {
        aspect-ratio: 16 / 9;
        min-height: 140px;
        border-radius: 12px;
    }
}

.t5-hero-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid #86efac;
    color: #15803d;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 14px rgba(22, 101, 52, 0.2);
    transition: all 0.2s ease;
}

.t5-hero-nav-arrow:hover {
    background: #16a34a;
    color: #ffffff;
    border-color: #16a34a;
    transform: translateY(-50%) scale(1.08);
}

.t5-hero-nav-prev { left: 12px; }
.t5-hero-nav-next { right: 12px; }

@media (max-width: 640px) {
    .t5-hero-nav-arrow {
        width: 26px;
        height: 26px;
    }
    .t5-hero-nav-arrow svg {
        width: 14px;
        height: 14px;
    }
    .t5-hero-nav-prev { left: 6px; }
    .t5-hero-nav-next { right: 6px; }
}

.t5-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.6s ease;
    display: flex;
    align-items: flex-end;
    text-decoration: none;
    color: inherit;
}

.t5-slide.t5-active {
    opacity: 1;
    z-index: 1;
}

.t5-slide-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: fill;
    object-position: center;
    display: block;
}

.t5-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.15) 60%, rgba(0,0,0,0.4) 100%);
    pointer-events: none;
}

.t5-slide-content {
    position: relative;
    z-index: 2;
    padding: 20px 24px;
    width: 100%;
}

@media (max-width: 640px) {
    .t5-slide-content {
        padding: 10px 12px;
    }
}

.t5-eco-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #bbf7d0;
    color: #14532d;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 30px;
}

.t5-fresh-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #22c55e;
    color: #ffffff;
    font-weight: 700;
    font-size: 12px;
    padding: 7px 18px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
    transition: all 0.25s ease;
}

.t5-fresh-btn:hover {
    background: #16a34a;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
    color: #ffffff;
    text-decoration: none;
}

/* Bottom Promo Showcase */
.t5-promo-strip-4col {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

@media (max-width: 900px) {
    .t5-promo-strip-4col {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .t5-promo-strip-4col {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
}

.t5-promo-strip-card {
    background: #ffffff;
    border: 1.5px solid #dcfce7;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(22, 101, 52, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    position: relative;
    height: 140px;
    padding: 6px;
    transition: all 0.25s ease;
}

@media (max-width: 480px) {
    .t5-promo-strip-card {
        height: 105px;
        border-radius: 10px;
        padding: 4px;
    }
}
.t5-promo-strip-card:hover {
    transform: translateY(-4px);
    border-color: #22c55e;
    box-shadow: 0 10px 24px rgba(34, 197, 94, 0.16);
}
.t5-promo-strip-card img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    display: block;
    border-radius: 12px;
}

/* ── T5 TRUST PILLARS ── */
.t5-pillars {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #dcfce7;
    padding: 18px 24px;
    margin-bottom: 34px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}
.t5-pillars-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media (max-width: 860px) {
    .t5-pillars-grid { grid-template-columns: repeat(2, 1fr); }
}
.t5-pillar-item {
    display: flex;
    align-items: center;
    gap: 14px;
}
.t5-pillar-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.t5-pillar-text h4 {
    margin: 0 0 2px;
    font-size: 14px;
    font-weight: 800;
    color: #14532d;
}
.t5-pillar-text p {
    margin: 0;
    font-size: 12px;
    color: #15803d;
}

/* ── T5 CATEGORIES ── */
.t5-sec-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.t5-sec-title {
    font-size: 22px;
    font-weight: 800;
    color: #14532d;
    margin: 0;
}
.t5-cat-slider-strip {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    margin-bottom: 38px;
    padding-bottom: 6px;
}
.t5-cat-slider-strip::-webkit-scrollbar { display: none; }
.t5-cat-item-card {
    flex: 0 0 140px;
    background: #ffffff;
    border: 1.5px solid #dcfce7;
    border-radius: 18px;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(22, 101, 52, 0.04);
    transition: all 0.25s ease;
}
.t5-cat-item-card:hover {
    border-color: #22c55e;
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(34, 197, 94, 0.15);
}
.t5-cat-item-img {
    width: 75px; height: 75px;
    border-radius: 50%;
    background: #f0fdf4;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.t5-cat-item-img img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.t5-cat-item-name {
    font-size: 13px;
    font-weight: 700;
    color: #14532d;
    text-align: center;
}

/* ── T5 PRODUCTS CAROUSEL ── */
.t5-prod-block {
    margin-bottom: 40px;
}
.t5-prod-slider-wrap {
    position: relative;
}
.t5-prod-row {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}
.t5-prod-row::-webkit-scrollbar { display: none; }
.t5-prod-col {
    flex: 0 0 calc(20% - 13px);
    min-width: 220px;
}
@media (max-width: 1100px) { .t5-prod-col { flex: 0 0 calc(25% - 12px); } }
@media (max-width: 860px) { .t5-prod-col { flex: 0 0 calc(33.33% - 11px); min-width: 180px; } }
@media (max-width: 576px) { .t5-prod-col { flex: 0 0 calc(50% - 8px); min-width: 155px; } }

.t5-arrow {
    position: absolute;
    top: 48%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #22c55e;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 15;
    box-shadow: 0 6px 18px rgba(22, 101, 52, 0.22);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.t5-arrow:hover {
    background: #16a34a;
    color: #ffffff;
    border-color: #15803d;
    box-shadow: 0 8px 24px rgba(22, 101, 52, 0.4);
    transform: translateY(-50%) scale(1.1);
}
.t5-arrow-prev { left: -18px; }
.t5-arrow-next { right: -18px; }

/* ── OVERRIDE PRODUCT CARDS FOR T5 ── */
.t5-page .product-card {
    background: #ffffff !important;
    border: 1.5px solid #dcfce7 !important;
    border-radius: 18px !important;
    box-shadow: 0 6px 16px rgba(22, 101, 52, 0.05) !important;
    transition: all 0.25s ease !important;
}
.t5-page .product-card:hover {
    border-color: #22c55e !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 12px 28px rgba(34, 197, 94, 0.15) !important;
}
.t5-page .product-card .current-price {
    color: #15803d !important;
    font-weight: 800 !important;
}
.t5-page .product-card .add-to-cart-btn,
.t5-page .product-card .buy-now-btn {
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
    border: none !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 25px !important;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3) !important;
}
.t5-page .product-card .add-to-cart-btn:hover,
.t5-page .product-card .buy-now-btn:hover {
    background: linear-gradient(135deg, #16a34a, #15803d) !important;
}
</style>

<div class="t5-page">

    {{-- ── HERO SECTION (ORGANIC FULL-WIDTH SHOWCASE) ── --}}
    <section class="t5-hero-section">
        <div class="t5-container">
            <div class="t5-hero-full-wrap">
                
                {{-- BIG FULL-WIDTH FRESH SLIDER --}}
                <div class="t5-slider-box" id="t5-slider">
                    <button class="t5-hero-nav-arrow t5-hero-nav-prev" id="t5-hero-prev" aria-label="Previous Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t5-hero-nav-arrow t5-hero-nav-next" id="t5-hero-next" aria-label="Next Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t5-slide {{ $idx === 0 ? 't5-active' : '' }}">
                            <img class="t5-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Fresh Groceries' }}">
                            @if($slider->title || $slider->button_text)
                                <div class="t5-slide-overlay"></div>
                                <div class="t5-slide-content">
                                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                                        @if($slider->title)
                                            <div class="t5-eco-badge" style="margin-bottom: 0;">{{ $slider->title }}</div>
                                        @endif
                                        @if($slider->button_text)
                                            <span class="t5-fresh-btn">
                                                {{ $slider->button_text }}
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t5-slide t5-active">
                            <img class="t5-slide-img" src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1600&q=80" alt="Fresh Produce">
                        </a>
                    @endforelse
                </div>

                {{-- 4-CARD PROMO STRIP DIRECTLY BELOW SLIDER --}}
                @php
                    $t5Banners = array_filter([
                        $homepage['featured_image_1'] ?? ($homepage['slider_side_image_one'] ?? ($homepage['slider_side_image'] ?? null)),
                        $homepage['featured_image_2'] ?? ($homepage['slider_side_image_two'] ?? null),
                        $homepage['featured_image_3'] ?? null,
                        $homepage['featured_image_4'] ?? null,
                    ]);
                @endphp
                @if(count($t5Banners) > 0)
                    <div class="t5-promo-strip-4col">
                        @foreach(array_slice($t5Banners, 0, 4) as $sIdx => $sImg)
                            @php
                                $sLink = $homepage['featured_image_' . ($sIdx + 1) . '_link'] ?? ($homepage['slider_side_image_' . ($sIdx == 0 ? 'one_' : 'two_') . 'link'] ?? route('shop'));
                                $sAlt  = $homepage['featured_image_' . ($sIdx + 1) . '_alt'] ?? ($homepage['slider_side_image_' . ($sIdx == 0 ? 'one_' : 'two_') . 'alt'] ?? ('Fresh Deal ' . ($sIdx + 1)));
                            @endphp
                            <a href="{{ $sLink }}" class="t5-promo-strip-card">
                                <img src="{{ asset($sImg) }}" alt="{{ $sAlt }}" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </section>

    {{-- ── TRUST PILLARS ── --}}
    <div class="t5-container">
        <div class="t5-pillars">
            <div class="t5-pillars-grid">
                <div class="t5-pillar-item">
                    <div class="t5-pillar-icon">🌱</div>
                    <div class="t5-pillar-text">
                        <h4>100% Organic</h4>
                        <p>Certified healthy products</p>
                    </div>
                </div>
                <div class="t5-pillar-item">
                    <div class="t5-pillar-icon">⚡</div>
                    <div class="t5-pillar-text">
                        <h4>Same Day Delivery</h4>
                        <p>Delivered fresh in hours</p>
                    </div>
                </div>
                <div class="t5-pillar-item">
                    <div class="t5-pillar-icon">🛡️</div>
                    <div class="t5-pillar-text">
                        <h4>Quality Assured</h4>
                        <p>Hand-inspected freshness</p>
                    </div>
                </div>
                <div class="t5-pillar-item">
                    <div class="t5-pillar-icon">💬</div>
                    <div class="t5-pillar-text">
                        <h4>Live Support</h4>
                        <p>Quick assistance anytime</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CATEGORIES ── --}}
    @if(isset($featuredItems) && $featuredItems->count() > 0)
    <section class="t5-container">
        <div class="t5-sec-head">
            <h3 class="t5-sec-title">Fresh Categories</h3>
            <a href="{{ route('shop') }}" style="color:#16a34a; font-weight:700; text-decoration:none;">View All &rarr;</a>
        </div>
        <div class="t5-cat-slider-strip">
            @foreach($featuredItems as $cat)
                <a href="{{ route('shop', $cat->slug) }}" class="t5-cat-item-card">
                    <div class="t5-cat-item-img">
                        <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}">
                    </div>
                    <span class="t5-cat-item-name">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── BEST SELLING / FRESH PICKS ── --}}
    @foreach(['best_selling' => ($homepage['best_selling_section_heading'] ?? '🌿 Most Popular Picks'), 'trending' => ($homepage['trending_section_heading'] ?? '⚡ Daily Essentials'), 'editors_pick' => ($homepage['editors_pick_section_heading'] ?? "⭐ Farm Selected Deals")] as $secKey => $secTitle)
        @if (!empty($homepage['enable_' . $secKey . '_section']) && $homepage['enable_' . $secKey . '_section'] && isset($featuredSections[$secKey]) && $featuredSections[$secKey]->count() > 0)
            <section class="t5-container t5-prod-block">
                <div class="t5-sec-head">
                    <h3 class="t5-sec-title">{{ $secTitle }}</h3>
                    <a href="{{ route('shop') }}" style="color:#16a34a; font-weight:700; text-decoration:none;">Explore All &rarr;</a>
                </div>
                <div class="t5-prod-slider-wrap">
                    <button class="t5-arrow t5-arrow-prev" data-target="t5-row-{{ $secKey }}" aria-label="Scroll Left">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <div class="t5-prod-row" id="t5-row-{{ $secKey }}">
                        @foreach($featuredSections[$secKey] as $product)
                            <div class="t5-prod-col">
                                @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'FRESH'])
                            </div>
                        @endforeach
                    </div>
                    <button class="t5-arrow t5-arrow-next" data-target="t5-row-{{ $secKey }}" aria-label="Scroll Right">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </section>
        @endif
    @endforeach

    {{-- ── LATEST PRODUCTS ── --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t5-container" style="margin-bottom: 40px;">
            <div class="t5-sec-head">
                <h3 class="t5-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'Fresh Farm Arrivals' }}</h3>
                <a href="{{ route('shop') }}" style="color:#16a34a; font-weight:700; text-decoration:none;">View All &rarr;</a>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px;">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW FRESH'])
                @endforeach
            </div>
        </section>
    @endif

</div>{{-- .t5-page --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // T5 Hero Slider
    const t5Slides = document.querySelectorAll('.t5-slide');
    const t5PrevBtn = document.getElementById('t5-hero-prev');
    const t5NextBtn = document.getElementById('t5-hero-next');
    let t5Idx = 0;
    let t5Timer;

    function t5ShowSlide(n) {
        if (!t5Slides.length) return;
        t5Slides[t5Idx].classList.remove('t5-active');
        t5Idx = (n + t5Slides.length) % t5Slides.length;
        t5Slides[t5Idx].classList.add('t5-active');
    }

    function t5StartTimer() {
        if (t5Slides.length > 1) {
            clearInterval(t5Timer);
            t5Timer = setInterval(() => t5ShowSlide(t5Idx + 1), 5000);
        }
    }

    if (t5PrevBtn) {
        t5PrevBtn.addEventListener('click', () => {
            t5ShowSlide(t5Idx - 1);
            t5StartTimer();
        });
    }
    if (t5NextBtn) {
        t5NextBtn.addEventListener('click', () => {
            t5ShowSlide(t5Idx + 1);
            t5StartTimer();
        });
    }
    t5StartTimer();

    // T5 Product Row Arrow Scrolling
    document.querySelectorAll('.t5-arrow').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            const row = document.getElementById(targetId);
            if (!row) return;
            const isPrev = this.classList.contains('t5-arrow-prev');
            const scrollAmount = row.clientWidth * 0.75 || 320;
            row.scrollBy({
                left: isPrev ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        });
    });
});
</script>
