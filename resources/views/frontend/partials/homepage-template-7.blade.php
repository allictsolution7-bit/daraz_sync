{{-- 
    Template 7: Beauty, Skincare & Cosmetics (Glow & Wellness)
    Luxury Boutique Beauty Aesthetic (Sephora & Glossier inspired)
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Nunito+Sans:wght@300;400;600;700;800&display=swap');

/* ── SCOPED TEMPLATE 7: BEAUTY & COSMETICS GLOW ── */
.t7-page {
    --t7-bg: {{ $homepage['template_7_bg_color'] ?? '#fff9f9' }};
    --t7-accent: {{ $homepage['template_7_accent_color'] ?? '#db2777' }};
    --t7-text: {{ $homepage['template_7_text_color'] ?? '#27272a' }};
    background: var(--t7-bg);
    color: var(--t7-text);
    font-family: 'Nunito Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    padding-bottom: 70px;
    overflow-x: hidden;
}

.t7-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

/* SECTION 1: Animated Glow Ticker */
.t7-glow-strip {
    background: linear-gradient(90deg, #c2185b, #e91e63, #ec4899, #c2185b);
    background-size: 300% 300%;
    animation: t7GradientGlow 8s ease infinite;
    color: #ffffff;
    padding: 9px 16px;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    text-align: center;
    box-shadow: 0 4px 15px rgba(233, 30, 99, 0.2);
}

@keyframes t7GradientGlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* SECTION 2: Interactive Beauty Hero Slider */
.t7-hero-section {
    padding: 20px 0;
}

.t7-hero-container {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #fbcfe8;
    box-shadow: 0 14px 36px rgba(194, 24, 91, 0.08);
    background: #fff0f5;
    width: 100%;
}

.t7-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 1500 / 600;
    min-height: 180px;
    overflow: hidden;
}

.t7-slide {
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

.t7-slide.active {
    opacity: 1;
    visibility: visible;
}

.t7-slide-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
}

/* Slide Caption */
.t7-slide-caption {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(194, 24, 91, 0.88);
    border: 1px solid rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(8px);
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

@media (max-width: 640px) {
    .t7-slide-caption {
        bottom: 12px;
        left: 12px;
        right: 12px;
        padding: 6px 12px;
        gap: 8px;
    }
}

.t7-caption-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 16px;
    color: #ffffff;
    font-weight: 700;
}

.t7-caption-btn {
    background: #ffffff;
    color: #be185d;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    padding: 5px 12px;
    border-radius: 20px;
    text-transform: uppercase;
}

.t7-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.88);
    border: 1px solid #fbcfe8;
    color: #be185d;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(194, 24, 91, 0.18);
}

.t7-hero-prev {
    left: 20px;
}

.t7-hero-next {
    right: 20px;
}

.t7-hero-arrow:hover {
    background: #be185d;
    color: #ffffff;
    border-color: #be185d;
    transform: translateY(-50%) scale(1.1);
}

@media (max-width: 640px) {
    .t7-hero-arrow {
        width: 36px;
        height: 36px;
    }
    .t7-hero-prev {
        left: 10px;
    }
    .t7-hero-next {
        right: 10px;
    }
}

/* SECTION 3: Dedicated 6-Step Skincare Ritual Cards */
.t7-ritual-section {
    padding: 24px 0 16px;
}

.t7-ritual-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-top: 14px;
}

@media (max-width: 1100px) {
    .t7-ritual-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
}

@media (max-width: 640px) {
    .t7-ritual-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

.t7-ritual-card {
    background: #ffffff;
    border: 1.5px solid #fce7f3;
    border-radius: 18px;
    padding: 22px 14px 18px;
    text-decoration: none;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    min-height: 195px;
    box-shadow: 0 4px 18px rgba(244, 114, 182, 0.08);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}

.t7-ritual-card:hover {
    transform: translateY(-6px);
    border-color: #db2777;
    box-shadow: 0 12px 28px rgba(219, 39, 119, 0.2);
    background: linear-gradient(180deg, #ffffff 0%, #fdf2f8 100%);
}

.t7-ritual-step-tag {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
    color: #be185d;
    background: #fdf2f8;
    border: 1px solid #fbcfe8;
    padding: 3px 10px;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.25s ease;
}

.t7-ritual-card:hover .t7-ritual-step-tag {
    background: #be185d;
    color: #ffffff;
    border-color: #be185d;
}

.t7-ritual-icon {
    font-size: 34px;
    margin-bottom: 8px;
    transition: transform 0.3s ease;
    line-height: 1;
}

.t7-ritual-card:hover .t7-ritual-icon {
    transform: scale(1.18) rotate(6deg);
}

.t7-ritual-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px;
    font-weight: 700;
    color: #18181b;
    margin: 0 0 4px;
    line-height: 1.2;
}

.t7-ritual-desc {
    font-size: 11px;
    color: #71717a;
    margin: 0 0 10px;
    line-height: 1.35;
}

.t7-ritual-action {
    font-size: 11px;
    font-weight: 800;
    color: #be185d;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    opacity: 0.85;
    transition: all 0.2s ease;
}

.t7-ritual-card:hover .t7-ritual-action {
    opacity: 1;
    transform: translateX(3px);
}

/* SECTION 4: Best Sellers Category Tab Panel */
.t7-section {
    padding: 10px 0 20px;
}

.t7-sec-head {
    text-align: center;
    margin-bottom: 24px;
}

.t7-sec-tag {
    color: #c2185b;
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 800;
    margin-bottom: 2px;
}

.t7-sec-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(24px, 3.2vw, 36px);
    font-weight: 700;
    color: #18181b;
    margin: 0;
}

.t7-tabs-bar {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.t7-tab-btn {
    background: #ffffff;
    border: 1px solid #fce7f3;
    color: #71717a;
    font-weight: 700;
    font-size: 12.5px;
    padding: 8px 18px;
    border-radius: 24px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.t7-tab-btn.active, .t7-tab-btn:hover {
    background: #c2185b;
    color: #ffffff;
    border-color: #c2185b;
    box-shadow: 0 4px 12px rgba(194, 24, 91, 0.2);
}

.t7-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .t7-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

/* SECTION 5: Compact Inline Ingredient Trust Ribbon */
.t7-trust-ribbon {
    background: #fff0f5;
    border: 1.5px solid #fbcfe8;
    border-radius: 14px;
    padding: 15px 22px;
    margin: 20px 0 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    text-align: center;
}

@media (max-width: 768px) {
    .t7-trust-ribbon {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

.t7-ribbon-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
}

.t7-ribbon-item span.icon {
    font-size: 16px;
}

.t7-ribbon-item strong {
    color: #be185d;
}

/* SECTION 6: Luxury Gift Sets Highlighted Row */
.t7-gift-section {
    background: linear-gradient(180deg, #ffffff 0%, #fff1f2 100%);
    border: 1px solid #fce7f3;
    border-radius: 20px;
    padding: 30px 24px;
    margin: 25px 0 35px;
}

.t7-gift-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

@media (max-width: 900px) {
    .t7-gift-grid {
        grid-template-columns: 1fr;
    }
}

.t7-gift-card {
    background: #ffffff;
    border: 1px solid #fbcfe8;
    border-radius: 14px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
}

.t7-gift-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(194, 24, 91, 0.12);
}

.t7-gift-img-wrap {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.t7-gift-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.t7-gift-card:hover .t7-gift-img-wrap img {
    transform: scale(1.05);
}

.t7-gift-ribbon {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #c2185b;
    color: #ffffff;
    font-size: 9.5px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 3px;
}

.t7-gift-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.t7-gift-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 4px;
}

.t7-gift-items {
    font-size: 11.5px;
    color: #71717a;
    margin-bottom: 10px;
}

.t7-gift-foot {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.t7-gift-price {
    font-size: 15px;
    font-weight: 800;
    color: #c2185b;
}

/* SECTION 7: Beauty Blog / Glow Guides */
.t7-blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
}

@media (max-width: 850px) {
    .t7-blog-grid {
        grid-template-columns: 1fr;
    }
}

.t7-blog-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #fce7f3;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.t7-blog-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 24px rgba(194, 24, 91, 0.08);
}

.t7-blog-img {
    height: 160px;
    width: 100%;
    object-fit: cover;
}

.t7-blog-body {
    padding: 16px;
}

.t7-blog-tag {
    font-size: 10.5px;
    font-weight: 800;
    color: #c2185b;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.t7-blog-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 18px;
    font-weight: 700;
    margin: 4px 0 6px;
    line-height: 1.25;
}

.t7-blog-desc {
    font-size: 12px;
    color: #71717a;
    line-height: 1.45;
}
</style>

<div class="t7-page">

    {{-- SECTION 1: Animated Glow Ticker --}}
    <div class="t7-glow-strip">
        {{ $homepage['template_7_ticker_text'] ?? '💋 FREE LUXURY BEAUTY GIFT ON ORDERS OVER ৳1,500 • 100% DERMATOLOGIST TESTED & HALAL CERTIFIED • EXPRESS DOORSTEP DELIVERY' }}
    </div>

    {{-- SECTION 2: Interactive Beauty Hero Slider with Left/Right Controls --}}
    <section class="t7-hero-section">
        <div class="t7-container">
            <div class="t7-hero-container">
                <div class="t7-hero-slider" id="t7HeroSlider">
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t7-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t7-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Glow Beauty' }}">
                            @if(!empty($slider->title))
                                <div class="t7-slide-caption">
                                    <span class="t7-caption-title">{{ $slider->title }}</span>
                                    <span class="t7-caption-btn">{{ $slider->button_text ?: 'EXPLORE' }} &rarr;</span>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t7-slide active">
                            <img class="t7-slide-img" src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1600&q=80" alt="Glow Beauty">
                            <div class="t7-slide-caption">
                                <span class="t7-caption-title">Botanical Skincare & Clean Glow</span>
                                <span class="t7-caption-btn">SHOP SKINCARE &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>
                {{-- Left & Right Buttons Centered on Both Sides --}}
                <button class="t7-hero-arrow t7-hero-prev" id="t7Prev" aria-label="Previous Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="t7-hero-arrow t7-hero-next" id="t7Next" aria-label="Next Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Dedicated 6-Step Skincare Ritual Cards --}}
    <section class="t7-section t7-ritual-section">
        <div class="t7-container">
            <div class="t7-sec-head">
                <div class="t7-sec-tag">{{ $homepage['template_7_ritual_section_subtitle'] ?? 'DAILY RADIANCE REGIMEN' }}</div>
                <h2 class="t7-sec-title">{{ $homepage['template_7_ritual_section_title'] ?? 'The 6-Step Clean Beauty Ritual' }}</h2>
            </div>
            <div class="t7-ritual-grid">
                <a href="{{ $homepage['template_7_ritual_1_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 01</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_1_icon'] ?? '🫧' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_1_name'] ?? 'Cleansers' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_1_desc'] ?? 'Purify & Refresh Skin' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
                <a href="{{ $homepage['template_7_ritual_2_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 02</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_2_icon'] ?? '💧' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_2_name'] ?? 'Toners & Mists' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_2_desc'] ?? 'Hydrate & Balance pH' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
                <a href="{{ $homepage['template_7_ritual_3_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 03</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_3_icon'] ?? '🧪' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_3_name'] ?? 'Serums & Actives' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_3_desc'] ?? 'Intensive Repair & Glow' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
                <a href="{{ $homepage['template_7_ritual_4_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 04</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_4_icon'] ?? '🧴' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_4_name'] ?? 'Moisturizers' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_4_desc'] ?? 'Lock In Deep Moisture' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
                <a href="{{ $homepage['template_7_ritual_5_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 05</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_5_icon'] ?? '☀️' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_5_name'] ?? 'SPF 50+ Sunscreen' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_5_desc'] ?? 'Broad UV Shield' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
                <a href="{{ $homepage['template_7_ritual_6_url'] ?? route('shop') }}" class="t7-ritual-card">
                    <span class="t7-ritual-step-tag">STEP 06</span>
                    <div class="t7-ritual-icon">{{ $homepage['template_7_ritual_6_icon'] ?? '👁️' }}</div>
                    <h4 class="t7-ritual-title">{{ $homepage['template_7_ritual_6_name'] ?? 'Lip & Eye Care' }}</h4>
                    <p class="t7-ritual-desc">{{ $homepage['template_7_ritual_6_desc'] ?? 'Nourish Delicate Areas' }}</p>
                    <span class="t7-ritual-action">{{ $homepage['template_7_ritual_cta'] ?? 'Explore' }} &rarr;</span>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Best Sellers by Category Tab Panel & Products --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t7-section">
            <div class="t7-container">
                <div class="t7-sec-head">
                    <div class="t7-sec-tag">CURATED ESSENTIALS</div>
                    <h2 class="t7-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Holy Grail Beauty Picks' }}</h2>
                </div>
                
                <div class="t7-tabs-bar">
                    <button class="t7-tab-btn active" onclick="t7Filter('all', this)">All Formulas</button>
                    <button class="t7-tab-btn" onclick="t7Filter('skincare', this)">Skincare</button>
                    <button class="t7-tab-btn" onclick="t7Filter('makeup', this)">Makeup & Lip</button>
                    <button class="t7-tab-btn" onclick="t7Filter('organic', this)">Organic Botanical</button>
                </div>

                <div class="t7-product-grid" id="t7ProductGrid">
                    @foreach($featuredProducts as $product)
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'GLOW'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- SECTION 5: Compact Inline Ingredient Trust Ribbon --}}
    <section class="t7-container">
        <div class="t7-trust-ribbon">
            <div class="t7-ribbon-item">
                <span class="icon">🌿</span>
                <span><strong>{{ $homepage['template_7_trust_1'] ?? '100% Non-Toxic • Zero Parabens' }}</strong></span>
            </div>
            <div class="t7-ribbon-item">
                <span class="icon">🐰</span>
                <span><strong>{{ $homepage['template_7_trust_2'] ?? 'PETA Cruelty-Free • Not Tested on Animals' }}</strong></span>
            </div>
            <div class="t7-ribbon-item">
                <span class="icon">🌸</span>
                <span><strong>{{ $homepage['template_7_trust_3'] ?? 'Botanical Actives • Pure Plant Extracts' }}</strong></span>
            </div>
            <div class="t7-ribbon-item">
                <span class="icon">💧</span>
                <span><strong>{{ $homepage['template_7_trust_4'] ?? 'Clinically Tested • Dermatologist Safe' }}</strong></span>
            </div>
        </div>
    </section>

    {{-- SECTION 6: Luxury Gift Sets Highlighted Row --}}
    <section class="t7-section">
        <div class="t7-container">
            <div class="t7-gift-section">
                <div class="t7-sec-head">
                    <div class="t7-sec-tag">SIGNATURE BUNDLES</div>
                    <h2 class="t7-sec-title">{{ $homepage['template_7_gifts_title'] ?? 'Luxury Beauty Gift Sets' }}</h2>
                </div>
                <div class="t7-gift-grid">
                    <a href="{{ route('shop') }}" class="t7-gift-card">
                        <div class="t7-gift-img-wrap">
                            <span class="t7-gift-ribbon">BESTSELLER SET</span>
                            <img src="https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=600&q=80" alt="Rose Water Set">
                        </div>
                        <div class="t7-gift-body">
                            <h4 class="t7-gift-title">Hydration Glow Trio</h4>
                            <p class="t7-gift-items">Foam Cleanser + Rose Mist + Hyaluronic Serum</p>
                            <div class="t7-gift-foot">
                                <span class="t7-gift-price">Special Bundle ৳1,850</span>
                                <span style="font-size:11.5px; font-weight:800; color:#c2185b;">SHOP SET &rarr;</span>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shop') }}" class="t7-gift-card">
                        <div class="t7-gift-img-wrap">
                            <span class="t7-gift-ribbon">NEW EDITION</span>
                            <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80" alt="Lip Trio">
                        </div>
                        <div class="t7-gift-body">
                            <h4 class="t7-gift-title">Velvet Matte Lip Vault</h4>
                            <p class="t7-gift-items">3 Long-Wear Nude & Berry Halal Lipsticks</p>
                            <div class="t7-gift-foot">
                                <span class="t7-gift-price">Special Bundle ৳1,450</span>
                                <span style="font-size:11.5px; font-weight:800; color:#c2185b;">SHOP SET &rarr;</span>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('shop') }}" class="t7-gift-card">
                        <div class="t7-gift-img-wrap">
                            <span class="t7-gift-ribbon">ORGANIC</span>
                            <img src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&q=80" alt="Spa Night Set">
                        </div>
                        <div class="t7-gift-body">
                            <h4 class="t7-gift-title">Botanical Night Spa Kit</h4>
                            <p class="t7-gift-items">Clay Mask + Bakuchiol Night Oil + Jade Roller</p>
                            <div class="t7-gift-foot">
                                <span class="t7-gift-price">Special Bundle ৳2,200</span>
                                <span style="font-size:11.5px; font-weight:800; color:#c2185b;">SHOP SET &rarr;</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 7: Beauty Blog / Glow Guides --}}
    <section class="t7-section">
        <div class="t7-container">
            <div class="t7-sec-head">
                <div class="t7-sec-tag">BEAUTY INSIGHTS</div>
                <h2 class="t7-sec-title">{{ $homepage['template_7_journal_title'] ?? 'The Glow Journal' }}</h2>
            </div>
            <div class="t7-blog-grid">
                <a href="{{ route('shop') }}" class="t7-blog-card">
                    <img class="t7-blog-img" src="https://images.unsplash.com/photo-1512290900672-1f413d71239c?w=500&q=80" alt="Glass Skin">
                    <div class="t7-blog-body">
                        <span class="t7-blog-tag">SKINCARE SECRETS</span>
                        <h4 class="t7-blog-title">5 Steps to Achieving True Glass Skin at Home</h4>
                        <p class="t7-blog-desc">How consistent micro-hydration and barrier care outperform aggressive chemical peels.</p>
                    </div>
                </a>
                <a href="{{ route('shop') }}" class="t7-blog-card">
                    <img class="t7-blog-img" src="https://images.unsplash.com/photo-1586495777744-4e6b0f4a0a34?w=500&q=80" alt="Lip Shades">
                    <div class="t7-blog-body">
                        <span class="t7-blog-tag">TRENDING SHADES</span>
                        <h4 class="t7-blog-title">Top 10 Halal Nude Lipsticks for South Asian Skin</h4>
                        <p class="t7-blog-desc">Finding the right undertone balance: warm terracotta vs. cool plum tones.</p>
                    </div>
                </a>
                <a href="{{ route('shop') }}" class="t7-blog-card">
                    <img class="t7-blog-img" src="https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?w=500&q=80" alt="Serums">
                    <div class="t7-blog-body">
                        <span class="t7-blog-tag">INGREDIENT 101</span>
                        <h4 class="t7-blog-title">How to Layer Vitamin C & Niacinamide Correctly</h4>
                        <p class="t7-blog-desc">Demystifying the common active ingredient myths for clear and blemish-free skin.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t7-slide');
    const prev = document.getElementById('t7Prev');
    const next = document.getElementById('t7Next');
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
});

function t7Filter(cat, btn) {
    document.querySelectorAll('.t7-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const grid = document.getElementById('t7ProductGrid');
    if (grid) {
        grid.style.opacity = '0.5';
        setTimeout(() => { grid.style.opacity = '1'; }, 180);
    }
}
</script>
