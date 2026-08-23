{{-- 
    Template 7: Beauty, Skincare & Cosmetics (Glow & Wellness)
    Designed specifically for Skincare, Makeup, Perfumes, Haircare & Organic Beauty
--}}

<style>
.t7-page {
    background: #fdfaf7;
    color: #27272a;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    padding-bottom: 60px;
}

.t7-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Beauty Announcement Bar */
.t7-top-strip {
    background: linear-gradient(135deg, #f43f5e, #ec4899);
    color: #ffffff;
    padding: 9px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.t7-top-strip a {
    color: #fff;
    text-decoration: underline;
    font-weight: 800;
}

/* Hero Section */
.t7-hero-section {
    padding: 20px 0 30px;
}

.t7-hero-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

@media (max-width: 992px) {
    .t7-hero-layout {
        grid-template-columns: 1fr;
    }
}

.t7-hero-slider {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 480px;
    background: #fce7f3;
    box-shadow: 0 10px 30px rgba(244, 63, 94, 0.08);
}

@media (max-width: 768px) {
    .t7-hero-slider {
        height: 320px;
    }
}

.t7-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
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
    object-fit: cover;
}

.t7-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(15, 23, 42, 0.65) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px 40px;
    color: #ffffff;
}

.t7-slide-badge {
    background: #f43f5e;
    color: #fff;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    width: fit-content;
}

.t7-slide-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .t7-slide-title {
        font-size: 22px;
    }
    .t7-slide-overlay {
        padding: 20px;
    }
}

.t7-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #e11d48;
    padding: 10px 22px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 800;
    width: fit-content;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: all 0.2s;
}

.t7-slide-btn:hover {
    background: #f43f5e;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Nav Arrows */
.t7-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    border: none;
    color: #be123c;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.t7-hero-arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.t7-hero-prev { left: 16px; }
.t7-hero-next { right: 16px; }

/* Side Banners */
.t7-side-deals {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 480px;
}

@media (max-width: 992px) {
    .t7-side-deals {
        height: auto;
        flex-direction: row;
    }
}

@media (max-width: 640px) {
    .t7-side-deals {
        flex-direction: column;
    }
}

.t7-side-card {
    flex: 1;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: #fce7f3;
    display: block;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(244, 63, 94, 0.06);
}

.t7-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.t7-side-card:hover img {
    transform: scale(1.06);
}

.t7-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(15, 23, 42, 0.7) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    color: #fff;
}

.t7-side-badge {
    background: #f43f5e;
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 12px;
    margin-bottom: 6px;
    width: fit-content;
}

.t7-side-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t7-side-link {
    font-size: 12px;
    color: #fbcfe8;
    font-weight: 700;
}

/* Trust Badges Bar (100% Vegan, Cruelty Free, Dermatologist Tested) */
.t7-trust-bar {
    background: #ffffff;
    border: 1px solid #fecdd3;
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    box-shadow: 0 4px 16px rgba(244, 63, 94, 0.04);
}

@media (max-width: 860px) {
    .t7-trust-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t7-trust-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.t7-trust-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    background: #fff1f2;
    color: #e11d48;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.t7-trust-label {
    font-size: 13px;
    font-weight: 800;
    color: #1e293b;
    margin: 0;
}

.t7-trust-desc {
    font-size: 11px;
    color: #64748b;
    margin: 0;
}

/* Section Header */
.t7-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #fecdd3;
}

.t7-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.t7-sec-bar {
    width: 4px;
    height: 22px;
    background: #f43f5e;
    border-radius: 2px;
}

.t7-sec-title {
    font-size: 19px;
    font-weight: 800;
    color: #881337;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.t7-view-all {
    font-size: 13px;
    font-weight: 700;
    color: #e11d48;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.t7-view-all:hover {
    color: #be123c;
}

/* Categories Carousel */
.t7-cat-scroll {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}

.t7-cat-scroll::-webkit-scrollbar {
    display: none;
}

.t7-cat-pill {
    flex: 0 0 130px;
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 10px;
    border: 1px solid #fecdd3;
    text-align: center;
    text-decoration: none;
    color: #1e293b;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.t7-cat-pill:hover {
    transform: translateY(-4px);
    border-color: #f43f5e;
    box-shadow: 0 8px 20px rgba(244, 63, 94, 0.12);
    color: #e11d48;
}

.t7-cat-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #fff1f2;
    border: 2px solid #fce7f3;
}

.t7-cat-name {
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}

/* Product Grid */
.t7-product-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t7-product-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t7-page">

    {{-- Top Announcement Strip --}}
    <div class="t7-top-strip">
        <span>🌸 100% AUTHENTIC COSMETICS & DERMATOLOGIST TESTED BEAUTY ESSENTIALS</span>
        <span>FREE SHIPPING ON ORDERS OVER ৳999 &bull; <a href="{{ route('shop') }}">EXPLORE BEAUTY &rarr;</a></span>
    </div>

    {{-- Hero Section --}}
    <section class="t7-hero-section">
        <div class="t7-container">
            <div class="t7-hero-layout">
                
                {{-- Big Slider --}}
                <div class="t7-hero-slider" id="t7HeroSlider">
                    <button class="t7-hero-arrow t7-hero-prev" id="t7Prev" aria-label="Previous Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t7-hero-arrow t7-hero-next" id="t7Next" aria-label="Next Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>

                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t7-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t7-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Cosmetics Glow' }}">
                            <div class="t7-slide-overlay">
                                @if($slider->title)
                                    <span class="t7-slide-badge">GLOW & BEAUTY</span>
                                    <h1 class="t7-slide-title">{{ $slider->title }}</h1>
                                @endif
                                <span class="t7-slide-btn">
                                    {{ $slider->button_text ?: 'SHOP SKINCARE' }}
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t7-slide active">
                            <img class="t7-slide-img" src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=1400&q=80" alt="Cosmetics Glow">
                            <div class="t7-slide-overlay">
                                <span class="t7-slide-badge">BEAUTY ESSENTIALS</span>
                                <h1 class="t7-slide-title">Hydrating Skincare & Luxury Fragrances</h1>
                                <span class="t7-slide-btn">SHOP COLLECTION &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Side Deals --}}
                <div class="t7-side-deals">
                    <a href="{{ $homepage['slider_side_image_one_link'] ?? route('shop') }}" class="t7-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_one']) ? asset($homepage['slider_side_image_one']) : 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80' }}" alt="Skincare Glow">
                        <div class="t7-side-overlay">
                            <span class="t7-side-badge">ORGANIC GLOW</span>
                            <h3 class="t7-side-title">Serums & Moisturizers</h3>
                            <span class="t7-side-link">View Glow Essentials &rarr;</span>
                        </div>
                    </a>
                    <a href="{{ $homepage['slider_side_image_two_link'] ?? route('shop') }}" class="t7-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_two']) ? asset($homepage['slider_side_image_two']) : 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=600&q=80' }}" alt="Luxury Perfumes">
                        <div class="t7-side-overlay">
                            <span class="t7-side-badge">FRAGRANCE</span>
                            <h3 class="t7-side-title">Designer Perfumes & Scents</h3>
                            <span class="t7-side-link">Shop Scents &rarr;</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Trust Badges --}}
    <section class="t7-container">
        <div class="t7-trust-bar">
            <div class="t7-trust-item">
                <div class="t7-trust-icon"><i class="fas fa-leaf"></i></div>
                <div>
                    <h5 class="t7-trust-label">100% Organic & Vegan</h5>
                    <p class="t7-trust-desc">Pure botanical extracts</p>
                </div>
            </div>
            <div class="t7-trust-item">
                <div class="t7-trust-icon"><i class="fas fa-heart"></i></div>
                <div>
                    <h5 class="t7-trust-label">Cruelty-Free</h5>
                    <p class="t7-trust-desc">Never tested on animals</p>
                </div>
            </div>
            <div class="t7-trust-item">
                <div class="t7-trust-icon"><i class="fas fa-user-md"></i></div>
                <div>
                    <h5 class="t7-trust-label">Dermatologist Tested</h5>
                    <p class="t7-trust-desc">Safe for all skin types</p>
                </div>
            </div>
            <div class="t7-trust-item">
                <div class="t7-trust-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h5 class="t7-trust-label">100% Authentic</h5>
                    <p class="t7-trust-desc">Direct from official brands</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Curated Categories --}}
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <section class="t7-container" style="margin-bottom: 35px;">
            <div class="t7-sec-header">
                <div class="t7-sec-title-wrap">
                    <div class="t7-sec-bar"></div>
                    <h2 class="t7-sec-title">Beauty Routine Categories</h2>
                </div>
                <a href="{{ route('shop') }}" class="t7-view-all">All Categories &rarr;</a>
            </div>
            <div class="t7-cat-scroll">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t7-cat-pill">
                        <img class="t7-cat-img" src="{{ asset($cat->image ?? $cat->icon ?? 'clientside/images/product-placeholder.png') }}" alt="{{ $cat->name }}">
                        <span class="t7-cat-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Skincare & Deals --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t7-container">
            <div class="t7-sec-header">
                <div class="t7-sec-title-wrap">
                    <div class="t7-sec-bar"></div>
                    <h2 class="t7-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Trending Skincare & Cosmetics' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t7-view-all">View All &rarr;</a>
            </div>
            <div class="t7-product-row">
                @foreach($featuredProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'GLOW'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Best Selling Cosmetics --}}
    @if (!empty($homepage['enable_best_selling_section']) && $homepage['enable_best_selling_section'] && isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
        <section class="t7-container">
            <div class="t7-sec-header">
                <div class="t7-sec-title-wrap">
                    <div class="t7-sec-bar"></div>
                    <h2 class="t7-sec-title">{{ $homepage['best_selling_section_heading'] ?? 'Customer Holy Grails' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t7-view-all">View All &rarr;</a>
            </div>
            <div class="t7-product-row">
                @foreach($bestSellingProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'TOP'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Latest Arrivals --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t7-container">
            <div class="t7-sec-header">
                <div class="t7-sec-title-wrap">
                    <div class="t7-sec-bar"></div>
                    <h2 class="t7-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'New Beauty Arrivals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t7-view-all">View All &rarr;</a>
            </div>
            <div class="t7-product-row">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t7-slide');
    const prevBtn = document.getElementById('t7Prev');
    const nextBtn = document.getElementById('t7Next');
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
