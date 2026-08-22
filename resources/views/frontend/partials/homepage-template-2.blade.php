{{-- ===================================================================
     TEMPLATE 2 — LUXURY BOUTIQUE
     Pearl White · Gold Accents · Glassmorphism Cards · Elegant Feel
     =================================================================== --}}

<style>
/* ── T2 RESET & BASE ── */
.t2-page { background: #faf9f7; min-height: 100vh; font-family: 'Outfit', 'Inter', sans-serif; }

/* ── T2 HERO SECTION ── */
.t2-hero {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #0a0a0a;
    min-height: 480px;
}
.t2-hero-slider {
    position: relative;
    width: 100%;
    height: 480px;
    overflow: hidden;
}
.t2-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.8s ease;
    display: flex;
    align-items: flex-end;
}
.t2-slide.t2-active { opacity: 1; z-index: 1; }
.t2-slide-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    transition: transform 6s ease;
}
.t2-slide.t2-active .t2-slide-bg { transform: scale(1); }
.t2-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.28) 60%, rgba(0,0,0,0.06) 100%);
}
.t2-slide-content {
    position: relative;
    z-index: 2;
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 30px 56px;
    width: 100%;
}
.t2-slide-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212,175,55,0.15);
    border: 1px solid rgba(212,175,55,0.5);
    color: #d4af37;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 30px;
    margin-bottom: 16px;
    backdrop-filter: blur(8px);
}
.t2-slide-title {
    font-size: clamp(32px, 5vw, 62px);
    font-weight: 800;
    color: #ffffff;
    line-height: 1.1;
    letter-spacing: -1px;
    margin: 0 0 12px;
    max-width: 640px;
}
.t2-slide-desc {
    font-size: 16px;
    color: rgba(255,255,255,0.75);
    margin: 0 0 28px;
    max-width: 480px;
    line-height: 1.6;
}
.t2-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #d4af37, #c8a420);
    color: #0a0a0a;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 14px 32px;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 8px 28px rgba(212,175,55,0.4);
}
.t2-slide-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 36px rgba(212,175,55,0.55);
    color: #0a0a0a;
    text-decoration: none;
}
.t2-slide-btn svg { transition: transform 0.2s; }
.t2-slide-btn:hover svg { transform: translateX(4px); }

/* Hero dots & arrows */
.t2-hero-nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(10, 10, 10, 0.7);
    border: 1.5px solid #d4af37;
    color: #d4af37;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
    transition: all 0.2s ease;
}
.t2-hero-nav-arrow:hover {
    background: #d4af37;
    color: #0a0a0a;
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.6);
    transform: translateY(-50%) scale(1.08);
}
.t2-hero-nav-prev { left: 20px; }
.t2-hero-nav-next { right: 20px; }

.t2-hero-dots {
    position: absolute;
    bottom: 20px;
    right: 30px;
    z-index: 10;
    display: flex;
    gap: 8px;
}
.t2-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.35);
    border: none;
    cursor: pointer;
    transition: all 0.25s;
    padding: 0;
}
.t2-dot.t2-active-dot {
    background: #d4af37;
    width: 24px;
    border-radius: 4px;
}

/* ── T2 FEATURED BANNERS ── */
.t2-banners {
    max-width: 1340px;
    margin: 28px auto;
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.t2-banner-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    height: 130px;
    display: block;
    text-decoration: none;
}
.t2-banner-card img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.t2-banner-card:hover img { transform: scale(1.05); }
.t2-banner-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 30%, rgba(0,0,0,0.5) 100%);
}

/* ── T2 SECTION HEADING ── */
.t2-section-head {
    text-align: center;
    margin-bottom: 32px;
}
.t2-section-eyebrow {
    display: inline-block;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #c8a420;
    margin-bottom: 8px;
}
.t2-section-title {
    font-size: 26px;
    font-weight: 800;
    color: #1a1206;
    letter-spacing: -0.4px;
    margin: 0 0 12px;
}
.t2-gold-line {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #c8a420);
    border-radius: 2px;
    margin: 0 auto;
}

/* ── T2 CATEGORIES ── */
.t2-categories {
    background: #ffffff;
    padding: 48px 0;
}
.t2-cat-grid {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding-bottom: 8px;
}
.t2-cat-grid::-webkit-scrollbar { display: none; }
.t2-cat-card {
    flex: 0 0 160px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    gap: 12px;
    transition: transform 0.25s;
}
.t2-cat-card:hover { transform: translateY(-5px); }
.t2-cat-img-wrap {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid transparent;
    background: linear-gradient(#faf9f7, #faf9f7) padding-box,
                linear-gradient(135deg, #d4af37, #c8a420) border-box;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transition: box-shadow 0.25s;
}
.t2-cat-card:hover .t2-cat-img-wrap {
    box-shadow: 0 14px 32px rgba(212,175,55,0.25);
}
.t2-cat-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
}
.t2-cat-name {
    font-size: 13px;
    font-weight: 700;
    color: #1a1206;
    letter-spacing: 0.3px;
    text-align: center;
}
.t2-cat-count {
    font-size: 11px;
    color: #9a8040;
    margin-top: -8px;
}

/* ── T2 PRODUCTS SECTION ── */
.t2-products-section {
    padding: 52px 0;
    background: #faf9f7;
}
.t2-products-wrap {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 20px;
}
.t2-section-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
}
.t2-view-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 700;
    color: #c8a420;
    text-decoration: none;
    border: 1.5px solid #e8d88a;
    padding: 8px 20px;
    border-radius: 30px;
    transition: all 0.2s;
}
.t2-view-all:hover {
    background: #c8a420;
    color: #fff;
    border-color: #c8a420;
    text-decoration: none;
}
.t2-products-slider-wrap {
    position: relative;
}
.t2-products-row {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 4px 2px 12px;
    scroll-snap-type: x mandatory;
}
.t2-products-row::-webkit-scrollbar { display: none; }
.t2-prod-card-wrap {
    flex: 0 0 calc(20% - 13px);
    min-width: 220px;
    scroll-snap-align: start;
}
.t2-slide-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px; height: 40px;
    background: #ffffff;
    border: 1.5px solid #e8d88a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    transition: all 0.2s;
    color: #c8a420;
}
.t2-slide-arrow:hover {
    background: #c8a420;
    color: #fff;
    border-color: #c8a420;
}
.t2-slide-prev { left: -20px; }
.t2-slide-next { right: -20px; }

/* ── T2 MARQUEE TRUST BAR ── */
.t2-trust-bar {
    background: linear-gradient(135deg, #1a1206, #2a1f0a);
    padding: 14px 0;
    overflow: hidden;
}
.t2-marquee {
    display: flex;
    gap: 48px;
    animation: t2-marquee 20s linear infinite;
    white-space: nowrap;
}
.t2-marquee-item {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: rgba(212,175,55,0.85);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    flex-shrink: 0;
}
.t2-marquee-dot {
    width: 5px; height: 5px;
    background: rgba(212,175,55,0.4);
    border-radius: 50%;
    flex-shrink: 0;
}
@keyframes t2-marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ── T2 RESPONSIVE ── */
@media (max-width: 992px) {
    .t2-banners { grid-template-columns: repeat(2, 1fr); }
    .t2-prod-card-wrap { flex: 0 0 calc(33% - 11px); min-width: 180px; }
    .t2-hero-slider { height: 360px; }
}
@media (max-width: 576px) {
    .t2-banners { grid-template-columns: 1fr 1fr; gap: 8px; padding: 0 12px; }
    .t2-prod-card-wrap { flex: 0 0 calc(50% - 8px); min-width: 160px; }
    .t2-hero-slider { height: 280px; }
    .t2-slide-title { font-size: 26px; }
    .t2-slide-content { padding: 0 16px 36px; }
    .t2-categories { padding: 32px 0; }
}
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="t2-page">

{{-- ─── TRUST BAR ─── --}}
<div class="t2-trust-bar">
    <div class="t2-marquee" id="t2-marquee">
        @foreach(['Free Delivery On Orders Over ৳999', 'Premium Quality Guaranteed', 'Easy 7-Day Returns', 'Secure Payments', 'Authentic Products Only', 'Exclusive Member Benefits', 'Free Delivery On Orders Over ৳999', 'Premium Quality Guaranteed', 'Easy 7-Day Returns', 'Secure Payments', 'Authentic Products Only', 'Exclusive Member Benefits'] as $item)
            <span class="t2-marquee-item">
                <span class="t2-marquee-dot"></span>
                {{ $item }}
            </span>
        @endforeach
    </div>
</div>

{{-- ─── HERO SLIDER ─── --}}
<section class="t2-hero">
    <div class="t2-hero-slider" id="t2-slider">
        <button class="t2-hero-nav-arrow t2-hero-nav-prev" id="t2-hero-prev" aria-label="Previous Slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="t2-hero-nav-arrow t2-hero-nav-next" id="t2-hero-next" aria-label="Next Slide">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        @forelse($sliders as $idx => $slider)
            <div class="t2-slide {{ $idx === 0 ? 't2-active' : '' }}" id="t2-slide-{{ $idx }}">
                <div class="t2-slide-bg" style="background-image: url('{{ asset($slider->image) }}');"></div>
                <div class="t2-slide-overlay"></div>
                <div class="t2-slide-content">
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <div class="t2-slide-label" style="margin-bottom: 0;">
                            ✦ Curated Collection
                        </div>
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t2-slide-btn">
                            {{ $slider->button_text ?? 'Explore Collection' }}
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="t2-slide t2-active">
                <div class="t2-slide-bg" style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&q=80'); background-position: center;"></div>
                <div class="t2-slide-overlay"></div>
                <div class="t2-slide-content">
                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                        <div class="t2-slide-label" style="margin-bottom: 0;">✦ Curated Collection</div>
                        <a href="{{ route('shop') }}" class="t2-slide-btn">
                            Shop Now
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    @if(count($sliders) > 1)
    <div class="t2-hero-dots" id="t2-dots">
        @foreach($sliders as $idx => $slider)
            <button class="t2-dot {{ $idx === 0 ? 't2-active-dot' : '' }}" data-slide="{{ $idx }}"></button>
        @endforeach
    </div>
    @endif
</section>

{{-- ─── FEATURED BANNERS ─── --}}
@php
    $t2Banners = array_filter([
        $homepage['featured_image_1'] ?? null,
        $homepage['featured_image_2'] ?? null,
        $homepage['featured_image_3'] ?? null,
        $homepage['featured_image_4'] ?? null,
    ]);
@endphp
@if(count($t2Banners) > 0)
<div class="t2-banners">
    @foreach($t2Banners as $bIdx => $bImg)
        @php
            $bLinkKey = 'featured_image_' . ($bIdx + 1) . '_link';
            $bAltKey  = 'featured_image_' . ($bIdx + 1) . '_alt';
            $bLink = $homepage[$bLinkKey] ?? '#';
            $bAlt  = $homepage[$bAltKey] ?? 'Promo Banner';
        @endphp
        <a href="{{ $bLink }}" class="t2-banner-card">
            <img src="{{ asset($bImg) }}" alt="{{ $bAlt }}" loading="lazy">
        </a>
    @endforeach
</div>
@endif

{{-- ─── CATEGORIES ─── --}}
@if(isset($featuredItems) && $featuredItems->count() > 0)
<section class="t2-categories">
    <div class="t2-products-wrap">
        <div class="t2-section-head">
            <span class="t2-section-eyebrow">Browse</span>
            <h2 class="t2-section-title">Explore Collections</h2>
            <div class="t2-gold-line"></div>
        </div>
    </div>
    <div class="t2-cat-grid">
        @foreach($featuredItems as $cat)
            <a href="{{ route('shop', $cat->slug) }}" class="t2-cat-card">
                <div class="t2-cat-img-wrap">
                    <img src="{{ asset($cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
                </div>
                <span class="t2-cat-name">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ─── BEST SELLING PRODUCTS ─── --}}
@foreach(['best_selling' => ($homepage['best_selling_section_heading'] ?? 'Best Sellers'), 'editors_pick' => ($homepage['editors_pick_section_heading'] ?? "Editor's Picks"), 'trending' => ($homepage['trending_section_heading'] ?? 'Trending Now')] as $sectionKey => $sectionLabel)
@if (!empty($homepage['enable_' . $sectionKey . '_section']) && $homepage['enable_' . $sectionKey . '_section'] && isset($featuredSections[$sectionKey]) && $featuredSections[$sectionKey]->count() > 0)
<section class="t2-products-section" style="{{ $loop->even ? 'background:#ffffff;' : '' }}">
    <div class="t2-products-wrap">
        <div class="t2-section-actions">
            <div>
                <span class="t2-section-eyebrow">{{ $loop->iteration == 1 ? 'Top Picks' : ($loop->iteration == 2 ? 'Curated For You' : 'What\'s Hot') }}</span>
                <h2 class="t2-section-title" style="margin-bottom:0; text-align:left;">{{ $sectionLabel }}</h2>
            </div>
            <a href="{{ route('shop') }}" class="t2-view-all">
                View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="t2-products-slider-wrap">
            <button class="t2-slide-arrow t2-slide-prev" data-target="t2-prod-row-{{ $sectionKey }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <div class="t2-products-row" id="t2-prod-row-{{ $sectionKey }}">
                @foreach($featuredSections[$sectionKey] as $product)
                    <div class="t2-prod-card-wrap">
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'Special!'])
                    </div>
                @endforeach
            </div>
            <button class="t2-slide-arrow t2-slide-next" data-target="t2-prod-row-{{ $sectionKey }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</section>
@endif
@endforeach

{{-- ─── LATEST PRODUCTS ─── --}}
@if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
<section class="t2-products-section" style="background:#faf9f7;">
    <div class="t2-products-wrap">
        <div class="t2-section-actions">
            <div>
                <span class="t2-section-eyebrow">Fresh Arrivals</span>
                <h2 class="t2-section-title" style="margin-bottom:0; text-align:left;">{{ $homepage['latest_products_section_heading'] ?? 'Latest Products' }}</h2>
            </div>
            <a href="{{ route('shop') }}" class="t2-view-all">View All
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(215px, 1fr)); gap:16px;">
            @foreach($latestProducts as $product)
                @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── SHOP FEATURES ─── --}}
@if (!empty($homepage['enable_shop_features_section']) && $homepage['enable_shop_features_section'])
<section style="background: linear-gradient(135deg, #1a1206, #2a1f0a); padding: 48px 20px;">
    <div style="max-width:1340px; margin:0 auto; display:grid; grid-template-columns: repeat(4,1fr); gap:28px; text-align:center;">
        @foreach([['🚚','Free Delivery','On all orders over ৳999'],['🔒','Secure Payment','100% safe & encrypted'],['↩️','Easy Returns','7-day hassle-free returns'],['⭐','Premium Quality','Curated authentic products']] as $feat)
        <div>
            <div style="font-size:32px; margin-bottom:10px;">{{ $feat[0] }}</div>
            <div style="font-size:15px; font-weight:800; color:#d4af37; margin-bottom:6px; letter-spacing:0.3px;">{{ $feat[1] }}</div>
            <div style="font-size:13px; color:rgba(255,255,255,0.55);">{{ $feat[2] }}</div>
        </div>
        @endforeach
    </div>
</section>
@endif

</div>{{-- .t2-page --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    // T2 Hero Slider
    const slides = document.querySelectorAll('.t2-slide');
    const dots   = document.querySelectorAll('.t2-dot');
    const t2PrevBtn = document.getElementById('t2-hero-prev');
    const t2NextBtn = document.getElementById('t2-hero-next');
    let t2Current = 0, t2Timer;

    function t2GoTo(n) {
        if (!slides.length) return;
        slides[t2Current].classList.remove('t2-active');
        if (dots[t2Current]) dots[t2Current].classList.remove('t2-active-dot');
        t2Current = (n + slides.length) % slides.length;
        slides[t2Current].classList.add('t2-active');
        if (dots[t2Current]) dots[t2Current].classList.add('t2-active-dot');
    }

    function t2StartTimer() {
        if (slides.length > 1) {
            clearInterval(t2Timer);
            t2Timer = setInterval(() => t2GoTo(t2Current + 1), 5000);
        }
    }

    if (t2PrevBtn) {
        t2PrevBtn.addEventListener('click', () => {
            t2GoTo(t2Current - 1);
            t2StartTimer();
        });
    }
    if (t2NextBtn) {
        t2NextBtn.addEventListener('click', () => {
            t2GoTo(t2Current + 1);
            t2StartTimer();
        });
    }

    dots.forEach((d, i) => d.addEventListener('click', () => {
        t2GoTo(i);
        t2StartTimer();
    }));

    t2StartTimer();

    // T2 Product row arrow scrolling
    document.querySelectorAll('.t2-slide-arrow').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = document.getElementById(this.dataset.target);
            if (!row) return;
            const dir = this.classList.contains('t2-slide-prev') ? -1 : 1;
            row.scrollBy({ left: dir * 260, behavior: 'smooth' });
        });
    });
});
</script>
