{{-- 
    Template 8: Mega Supermarket & Grocery
    Modern Hypermarket, Fresh Produce & Daily Express Delivery
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Source+Sans+3:wght@300;400;600;700&display=swap');

/* ── SCOPED TEMPLATE 8: MEGA SUPERMARKET ── */
.t8-page {
    background: #f1f8f2;
    color: #1b2a1d;
    font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, sans-serif;
    padding-bottom: 70px;
    overflow-x: hidden;
}

.t8-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

/* SECTION 1: Express Delivery Countdown Strip */
.t8-express-bar {
    background: #15803d;
    color: #ffffff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    font-weight: 700;
}

.t8-express-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.t8-countdown-box {
    background: #0f5126;
    padding: 4px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: 'Poppins', sans-serif;
    color: #fde047;
    font-size: 12px;
    font-weight: 800;
}

/* SECTION 2: Full-Width Clean Hero Banner Slider */
.t8-hero-section {
    padding: 16px 0 24px;
}

.t8-hero-container {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    background: #14532d;
    box-shadow: 0 10px 30px rgba(20, 83, 45, 0.12);
}

.t8-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 1500 / 520;
    max-height: 480px;
    min-height: 220px;
}

@media (max-width: 768px) {
    .t8-hero-slider {
        aspect-ratio: 1500 / 650;
    }
}

.t8-slide {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.5s ease;
    display: block;
    text-decoration: none;
}

.t8-slide.active {
    opacity: 1;
    visibility: visible;
}

.t8-slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

.t8-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(20, 83, 45, 0.88);
    border: 1.5px solid #86efac;
    color: #fde047;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
    z-index: 10;
    box-shadow: 0 4px 14px rgba(0,0,0,0.25);
}

.t8-hero-arrow:hover {
    background: #fde047;
    color: #14532d;
    transform: translateY(-50%) scale(1.08);
}

.t8-hero-prev {
    left: 16px;
}

.t8-hero-next {
    right: 16px;
}

@media (max-width: 640px) {
    .t8-hero-arrow {
        width: 36px;
        height: 36px;
    }
    .t8-hero-prev {
        left: 10px;
    }
    .t8-hero-next {
        right: 10px;
    }
}

.t8-slide-caption {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(20, 83, 45, 0.88);
    border: 1.5px solid #86efac;
    backdrop-filter: blur(8px);
    padding: 12px 22px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

.t8-caption-title {
    font-family: 'Poppins', sans-serif;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
}

.t8-caption-btn {
    background: #fde047;
    color: #14532d;
    font-family: 'Poppins', sans-serif;
    font-size: 11.5px;
    font-weight: 800;
    padding: 6px 14px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

/* SECTION 3: 2-Column Promo Banners */
.t8-promos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 16px;
}

@media (max-width: 768px) {
    .t8-promos-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

.t8-side-promo {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    height: 180px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    border: 1.5px solid #dcfce7;
}

.t8-side-promo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease;
}

.t8-side-promo:hover img {
    transform: scale(1.05);
}

.t8-side-promo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 20%, rgba(15, 23, 42, 0.8) 100%);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.t8-promo-tag {
    background: #16a34a;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 4px;
    align-self: flex-start;
    margin-bottom: 6px;
}

.t8-promo-title {
    font-family: 'Poppins', sans-serif;
    color: #ffffff;
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 2px;
}

.t8-promo-sub {
    color: #fde047;
    font-size: 12px;
    font-weight: 700;
}

/* SECTION 4: Weekly Freshness Deals */
.t8-section {
    padding: 30px 0;
}

.t8-sec-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}

.t8-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.t8-sec-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #16a34a;
}

.t8-sec-title {
    font-family: 'Poppins', sans-serif;
    font-size: clamp(20px, 2.5vw, 26px);
    font-weight: 800;
    color: #14532d;
    margin: 0;
}

.t8-view-all {
    color: #15803d;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.t8-deals-scroll {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 992px) {
    .t8-deals-scroll {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 560px) {
    .t8-deals-scroll {
        grid-template-columns: 1fr;
    }
}

.t8-deal-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #bbf7d0;
    overflow: hidden;
    padding: 16px;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.06);
    position: relative;
    display: flex;
    flex-direction: column;
}

.t8-deal-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #ea580c;
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 4px;
    z-index: 2;
}

.t8-deal-img {
    height: 160px;
    width: 100%;
    object-fit: contain;
    margin-bottom: 12px;
}

.t8-deal-name {
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px;
}

.t8-deal-pricing {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 10px;
}

.t8-deal-price {
    font-family: 'Poppins', sans-serif;
    font-size: 18px;
    font-weight: 800;
    color: #15803d;
}

.t8-deal-old {
    font-size: 13px;
    color: #94a3b8;
    text-decoration: line-through;
}

.t8-stock-bar-wrap {
    margin-top: auto;
}

.t8-stock-text {
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    margin-bottom: 4px;
}

.t8-stock-bar {
    height: 6px;
    background: #fee2e2;
    border-radius: 10px;
    overflow: hidden;
}

.t8-stock-fill {
    height: 100%;
    background: #dc2626;
    width: 65%;
    border-radius: 10px;
}

/* SECTION 5: Farm-to-Table Trust Section */
.t8-trust-wrap {
    background: #ffffff;
    border: 1px solid #dcfce7;
    border-radius: 20px;
    padding: 35px;
    margin: 40px 0;
}

.t8-trust-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

@media (max-width: 768px) {
    .t8-trust-grid {
        grid-template-columns: 1fr;
    }
}

.t8-trust-box {
    display: flex;
    gap: 18px;
    align-items: flex-start;
}

.t8-trust-img {
    width: 100px;
    height: 100px;
    border-radius: 14px;
    object-fit: cover;
    flex-shrink: 0;
}

.t8-trust-title {
    font-family: 'Poppins', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #14532d;
    margin: 0 0 6px;
}

.t8-trust-desc {
    font-size: 13px;
    color: #475569;
    line-height: 1.5;
    margin: 0;
}

/* SECTION 6: Bundle & Save Combo Packs */
.t8-bundle-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 992px) {
    .t8-bundle-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 560px) {
    .t8-bundle-grid {
        grid-template-columns: 1fr;
    }
}

.t8-bundle-card {
    background: #ffffff;
    border: 2px dashed #86efac;
    border-radius: 18px;
    padding: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.t8-bundle-card:hover {
    border-color: #16a34a;
    border-style: solid;
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(22, 163, 74, 0.12);
}

.t8-bundle-tag {
    background: #fef08a;
    color: #854d0e;
    font-size: 10px;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 4px;
    align-self: flex-start;
    margin-bottom: 12px;
}

.t8-bundle-title {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #14532d;
    margin: 0 0 4px;
}

.t8-bundle-items {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 14px;
    line-height: 1.4;
}

.t8-bundle-foot {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.t8-bundle-price {
    font-family: 'Poppins', sans-serif;
    font-size: 17px;
    font-weight: 800;
    color: #15803d;
}

.t8-bundle-btn {
    background: #15803d;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    padding: 6px 12px;
    border-radius: 20px;
}

/* SECTION 7: Product Grid */
.t8-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t8-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t8-page">

    {{-- SECTION 1: Express Delivery Countdown Strip --}}
    <div class="t8-express-bar">
        <div class="t8-express-left">
            <span>{{ $homepage['template_8_express_text'] ?? '🚴 EXPRESS 45-MIN HOME DELIVERY • ORDER BEFORE CUTOFF' }}</span>
        </div>
        <div class="t8-countdown-box">
            <span>⏳ Next Dispatch Window Closes In:</span>
            <span id="t8Timer">04:22:15</span>
        </div>
    </div>

    {{-- SECTION 2: Full-Width Clean Hero Banner Slider & Promo Grid --}}
    <section class="t8-hero-section">
        <div class="t8-container">
            <div class="t8-hero-container">
                <div class="t8-hero-slider" id="t8MainSlider">
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t8-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t8-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Supermarket Banner' }}">
                            @if(!empty($slider->title))
                                <div class="t8-slide-caption">
                                    <span class="t8-caption-title">{{ $slider->title }}</span>
                                    <span class="t8-caption-btn">{{ $slider->button_text ?: 'SHOP NOW' }} &rarr;</span>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t8-slide active">
                            <img class="t8-slide-img" src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1600&q=80" alt="Supermarket">
                            <div class="t8-slide-caption">
                                <span class="t8-caption-title">Crisp Farm Harvest & Daily Kitchen Staples</span>
                                <span class="t8-caption-btn">ORDER TODAY &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Left & Right Centered Navigation Arrows --}}
                <button class="t8-hero-arrow t8-hero-prev" id="t8Prev" aria-label="Previous Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="t8-hero-arrow t8-hero-next" id="t8Next" aria-label="Next Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>

            {{-- 2-Column Side Promos Below Slider --}}
            <div class="t8-promos-grid">
                <a href="{{ $homepage['template_8_promo_1_url'] ?? route('shop') }}" class="t8-side-promo">
                    <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=800&q=80" alt="Fresh Fruits">
                    <div class="t8-side-promo-overlay">
                        <span class="t8-promo-tag">{{ $homepage['template_8_promo_1_tag'] ?? 'FLASH 30% OFF' }}</span>
                        <h4 class="t8-promo-title">{{ $homepage['template_8_promo_1_title'] ?? 'Organic Fruits & Greens' }}</h4>
                        <span class="t8-promo-sub">{{ $homepage['template_8_promo_1_sub'] ?? 'Direct From Bogura Farms →' }}</span>
                    </div>
                </a>

                <a href="{{ $homepage['template_8_promo_2_url'] ?? route('shop') }}" class="t8-side-promo">
                    <img src="https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=800&q=80" alt="Pantry Essentials">
                    <div class="t8-side-promo-overlay">
                        <span class="t8-promo-tag" style="background:#ea580c;">{{ $homepage['template_8_promo_2_tag'] ?? 'SAVINGS PACK' }}</span>
                        <h4 class="t8-promo-title">{{ $homepage['template_8_promo_2_title'] ?? 'Pantry Starter Bundles' }}</h4>
                        <span class="t8-promo-sub">{{ $homepage['template_8_promo_2_sub'] ?? 'Rice, Mustard Oil & Spices from ৳299 →' }}</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Weekly Freshness Deals --}}
    <section class="t8-section">
        <div class="t8-container">
            <div class="t8-sec-head">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-dot"></div>
                    <h2 class="t8-sec-title">{{ $homepage['template_8_deals_title'] ?? 'Today\'s Fresh Clearance Deals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">View All Deals &rarr;</a>
            </div>

            <div class="t8-deals-scroll">
                <div class="t8-deal-card">
                    <span class="t8-deal-badge">SAVE 25%</span>
                    <img class="t8-deal-img" src="https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=400&q=80" alt="Fresh Carrots">
                    <h4 class="t8-deal-name">Organic Baby Carrots (1 KG)</h4>
                    <div class="t8-deal-pricing">
                        <span class="t8-deal-price">৳75</span>
                        <span class="t8-deal-old">৳100</span>
                    </div>
                    <div class="t8-stock-bar-wrap">
                        <div class="t8-stock-text">🔥 Only 8 packs left</div>
                        <div class="t8-stock-bar"><div class="t8-stock-fill" style="width: 80%;"></div></div>
                    </div>
                </div>

                <div class="t8-deal-card">
                    <span class="t8-deal-badge">SAVE 15%</span>
                    <img class="t8-deal-img" src="https://images.unsplash.com/photo-1550989460-0adf9ea622e2?w=400&q=80" alt="Farm Eggs">
                    <h4 class="t8-deal-name">Brown Country Eggs (1 Dozen)</h4>
                    <div class="t8-deal-pricing">
                        <span class="t8-deal-price">৳145</span>
                        <span class="t8-deal-old">৳170</span>
                    </div>
                    <div class="t8-stock-bar-wrap">
                        <div class="t8-stock-text">🔥 Only 14 dozen left</div>
                        <div class="t8-stock-bar"><div class="t8-stock-fill" style="width: 60%;"></div></div>
                    </div>
                </div>

                <div class="t8-deal-card">
                    <span class="t8-deal-badge">SAVE 30%</span>
                    <img class="t8-deal-img" src="https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&q=80" alt="Raw Honey">
                    <h4 class="t8-deal-name">Sundarban Pure Raw Honey (500g)</h4>
                    <div class="t8-deal-pricing">
                        <span class="t8-deal-price">৳490</span>
                        <span class="t8-deal-old">৳700</span>
                    </div>
                    <div class="t8-stock-bar-wrap">
                        <div class="t8-stock-text">🔥 Only 5 jars left</div>
                        <div class="t8-stock-bar"><div class="t8-stock-fill" style="width: 90%;"></div></div>
                    </div>
                </div>

                <div class="t8-deal-card">
                    <span class="t8-deal-badge">SAVE 20%</span>
                    <img class="t8-deal-img" src="https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=400&q=80" alt="Cow Milk">
                    <h4 class="t8-deal-name">Fresh Pasteurised Milk (1 Litre)</h4>
                    <div class="t8-deal-pricing">
                        <span class="t8-deal-price">৳80</span>
                        <span class="t8-deal-old">৳100</span>
                    </div>
                    <div class="t8-stock-bar-wrap">
                        <div class="t8-stock-text">🔥 Only 19 bottles left</div>
                        <div class="t8-stock-bar"><div class="t8-stock-fill" style="width: 45%;"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5: Farm-to-Table Trust Section --}}
    <section class="t8-container">
        <div class="t8-trust-wrap">
            <div class="t8-trust-grid">
                <div class="t8-trust-box">
                    <img class="t8-trust-img" src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=400&q=80" alt="Farmer">
                    <div>
                        <h4 class="t8-trust-title">Direct From Verified Farmers</h4>
                        <p class="t8-trust-desc">We bypass middlemen to source harvest directly from local agro-clusters, guaranteeing peak freshness and fair compensation to farmers.</p>
                    </div>
                </div>
                <div class="t8-trust-box">
                    <img class="t8-trust-img" src="https://images.unsplash.com/photo-1607582278038-a2cc0c7ed24f?w=400&q=80" alt="Hygiene">
                    <div>
                        <h4 class="t8-trust-title">Multi-Stage Hygiene Protocol</h4>
                        <p class="t8-trust-desc">Every piece of produce is ozonated, sorted, and packed in temperature-controlled facilities ensuring zero pesticide residue.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 6: Bundle & Save Combo Packs --}}
    <section class="t8-section">
        <div class="t8-container">
            <div class="t8-sec-head">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-dot" style="background:#ea580c;"></div>
                    <h2 class="t8-sec-title">Family Kitchen Bundle Packs</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">All Combos &rarr;</a>
            </div>

            <div class="t8-bundle-grid">
                <a href="{{ route('shop') }}" class="t8-bundle-card">
                    <span class="t8-bundle-tag">BREAKFAST SAVER</span>
                    <h4 class="t8-bundle-title">Morning Breakfast Box</h4>
                    <p class="t8-bundle-items">1 Dozen Eggs + 1L Milk + Bread + Butter</p>
                    <div class="t8-bundle-foot">
                        <span class="t8-bundle-price">৳380</span>
                        <span class="t8-bundle-btn">+ Add Pack</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t8-bundle-card">
                    <span class="t8-bundle-tag">WEEKLY ESSENTIAL</span>
                    <h4 class="t8-bundle-title">Pantry Staple Combo</h4>
                    <p class="t8-bundle-items">Miniket Rice 5KG + Masoor Dal 1KG + Mustard Oil 1L</p>
                    <div class="t8-bundle-foot">
                        <span class="t8-bundle-price">৳740</span>
                        <span class="t8-bundle-btn">+ Add Pack</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t8-bundle-card">
                    <span class="t8-bundle-tag">HEALTHY GREENS</span>
                    <h4 class="t8-bundle-title">Fresh Salad & Veggie Pack</h4>
                    <p class="t8-bundle-items">Cucumber + Tomato + Lettuce + Coriander + Lemon</p>
                    <div class="t8-bundle-foot">
                        <span class="t8-bundle-price">৳195</span>
                        <span class="t8-bundle-btn">+ Add Pack</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t8-bundle-card">
                    <span class="t8-bundle-tag">EVENING SNACK</span>
                    <h4 class="t8-bundle-title">Tea Time Combo</h4>
                    <p class="t8-bundle-items">Premium CTC Tea 400g + Toast Biscuit + Sugar 1KG</p>
                    <div class="t8-bundle-foot">
                        <span class="t8-bundle-price">৳310</span>
                        <span class="t8-bundle-btn">+ Add Pack</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 7: Featured Grocery Products --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t8-section">
            <div class="t8-container">
                <div class="t8-sec-head">
                    <div class="t8-sec-title-wrap">
                        <div class="t8-sec-dot"></div>
                        <h2 class="t8-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Today\'s Fresh Produce' }}</h2>
                    </div>
                    <a href="{{ route('shop') }}" class="t8-view-all">View All &rarr;</a>
                </div>
                <div class="t8-product-grid">
                    @foreach($featuredProducts as $product)
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'FRESH'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown Timer logic
    function updateTimer() {
        const now = new Date();
        const midnight = new Date();
        midnight.setHours(24, 0, 0, 0);
        let diff = Math.floor((midnight - now) / 1000);
        if (diff < 0) diff = 86400 + diff;

        const h = String(Math.floor(diff / 3600)).padStart(2, '0');
        const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
        const s = String(diff % 60).padStart(2, '0');

        const el = document.getElementById('t8Timer');
        if (el) el.textContent = `${h}:${m}:${s}`;
    }
    setInterval(updateTimer, 1000);
    updateTimer();

    // Slider logic
    const slides = document.querySelectorAll('.t8-slide');
    const prevBtn = document.getElementById('t8Prev');
    const nextBtn = document.getElementById('t8Next');
    let idx = 0;
    let timer;

    function showSlide(n) {
        if (!slides.length) return;
        slides[idx].classList.remove('active');
        idx = (n + slides.length) % slides.length;
        slides[idx].classList.add('active');
    }

    function startTimer() {
        if (slides.length > 1) {
            clearInterval(timer);
            timer = setInterval(() => showSlide(idx + 1), 5000);
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            showSlide(idx - 1);
            startTimer();
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            showSlide(idx + 1);
            startTimer();
        });
    }

    startTimer();
});
</script>
