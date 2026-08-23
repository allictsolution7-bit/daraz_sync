{{-- 
    Template 8: Mega Supermarket & Daily Essentials
    Designed specifically for Groceries, Fresh Produce, Meat, Dairy & Supermarkets
--}}

<style>
.t8-page {
    background: #f8fafc;
    color: #1e293b;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    padding-bottom: 60px;
}

.t8-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Supermarket Green Announcement Bar */
.t8-top-strip {
    background: #15803d;
    color: #ffffff;
    padding: 9px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.t8-top-strip a {
    color: #fef08a;
    font-weight: 800;
    text-decoration: underline;
}

/* Hero Section */
.t8-hero-section {
    padding: 20px 0 30px;
}

.t8-hero-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

@media (max-width: 992px) {
    .t8-hero-layout {
        grid-template-columns: 1fr;
    }
}

.t8-hero-slider {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    height: 480px;
    background: #14532d;
    box-shadow: 0 10px 30px rgba(22, 163, 74, 0.1);
}

@media (max-width: 768px) {
    .t8-hero-slider {
        height: 320px;
    }
}

.t8-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
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
}

.t8-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(20, 83, 45, 0.8) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px 40px;
    color: #ffffff;
}

.t8-slide-badge {
    background: #22c55e;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    width: fit-content;
}

.t8-slide-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .t8-slide-title {
        font-size: 22px;
    }
    .t8-slide-overlay {
        padding: 20px;
    }
}

.t8-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #16a34a;
    padding: 10px 22px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 800;
    width: fit-content;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: all 0.2s;
}

.t8-slide-btn:hover {
    background: #16a34a;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Nav Arrows */
.t8-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    border: none;
    color: #15803d;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.t8-hero-arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.t8-hero-prev { left: 16px; }
.t8-hero-next { right: 16px; }

/* Side Banners */
.t8-side-deals {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 480px;
}

@media (max-width: 992px) {
    .t8-side-deals {
        height: auto;
        flex-direction: row;
    }
}

@media (max-width: 640px) {
    .t8-side-deals {
        flex-direction: column;
    }
}

.t8-side-card {
    flex: 1;
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    background: #dcfce7;
    display: block;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(22, 163, 74, 0.06);
}

.t8-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.t8-side-card:hover img {
    transform: scale(1.06);
}

.t8-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(20, 83, 45, 0.75) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    color: #fff;
}

.t8-side-badge {
    background: #22c55e;
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

.t8-side-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t8-side-link {
    font-size: 12px;
    color: #bbf7d0;
    font-weight: 700;
}

/* Express Grocery Features Strip */
.t8-features-bar {
    background: #ffffff;
    border: 1px solid #bbf7d0;
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    box-shadow: 0 4px 16px rgba(22, 163, 74, 0.04);
}

@media (max-width: 860px) {
    .t8-features-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t8-feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.t8-feature-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 50%;
    background: #dcfce7;
    color: #16a34a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.t8-feature-label {
    font-size: 13.5px;
    font-weight: 800;
    color: #14532d;
    margin: 0;
}

.t8-feature-desc {
    font-size: 11px;
    color: #64748b;
    margin: 0;
}

/* Section Header */
.t8-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #bbf7d0;
}

.t8-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.t8-sec-bar {
    width: 4px;
    height: 22px;
    background: #16a34a;
    border-radius: 2px;
}

.t8-sec-title {
    font-size: 19px;
    font-weight: 800;
    color: #14532d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.t8-view-all {
    font-size: 13px;
    font-weight: 700;
    color: #16a34a;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.t8-view-all:hover {
    color: #15803d;
}

/* Category Aisles Pills */
.t8-cat-scroll {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}

.t8-cat-scroll::-webkit-scrollbar {
    display: none;
}

.t8-cat-pill {
    flex: 0 0 130px;
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 10px;
    border: 1px solid #bbf7d0;
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

.t8-cat-pill:hover {
    transform: translateY(-4px);
    border-color: #16a34a;
    box-shadow: 0 8px 20px rgba(22, 163, 74, 0.12);
    color: #16a34a;
}

.t8-cat-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #dcfce7;
    border: 2px solid #bbf7d0;
}

.t8-cat-name {
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}

/* Product Grid */
.t8-product-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t8-product-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t8-page">

    {{-- Top Announcement Strip --}}
    <div class="t8-top-strip">
        <span>🥬 FRESH MARKET DIRECT • CRISP VEGETABLES, ORGANIC FRUITS & DAILY PANTRY ESSENTIALS</span>
        <span>EXPRESS 45-MIN HOME DELIVERY &bull; <a href="{{ route('shop') }}">ORDER GROCERY &rarr;</a></span>
    </div>

    {{-- Hero Section --}}
    <section class="t8-hero-section">
        <div class="t8-container">
            <div class="t8-hero-layout">
                
                {{-- Big Slider --}}
                <div class="t8-hero-slider" id="t8HeroSlider">
                    <button class="t8-hero-arrow t8-hero-prev" id="t8Prev" aria-label="Previous Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t8-hero-arrow t8-hero-next" id="t8Next" aria-label="Next Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>

                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t8-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t8-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Fresh Grocery' }}">
                            <div class="t8-slide-overlay">
                                @if($slider->title)
                                    <span class="t8-slide-badge">ORGANIC MARKET</span>
                                    <h1 class="t8-slide-title">{{ $slider->title }}</h1>
                                @endif
                                <span class="t8-slide-btn">
                                    {{ $slider->button_text ?: 'SHOP GROCERY' }}
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t8-slide active">
                            <img class="t8-slide-img" src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1400&q=80" alt="Fresh Supermarket">
                            <div class="t8-slide-overlay">
                                <span class="t8-slide-badge">FARM FRESH</span>
                                <h1 class="t8-slide-title">Daily Fresh Harvest & Pantry Essentials</h1>
                                <span class="t8-slide-btn">ORDER TODAY &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Side Deals --}}
                <div class="t8-side-deals">
                    <a href="{{ $homepage['slider_side_image_one_link'] ?? route('shop') }}" class="t8-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_one']) ? asset($homepage['slider_side_image_one']) : 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=600&q=80' }}" alt="Fresh Fruits">
                        <div class="t8-side-overlay">
                            <span class="t8-side-badge">ORGANIC</span>
                            <h3 class="t8-side-title">Crisp Fruits & Greens</h3>
                            <span class="t8-side-link">Shop Fresh Produce &rarr;</span>
                        </div>
                    </a>
                    <a href="{{ $homepage['slider_side_image_two_link'] ?? route('shop') }}" class="t8-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_two']) ? asset($homepage['slider_side_image_two']) : 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=600&q=80' }}" alt="Pantry & Cooking">
                        <div class="t8-side-overlay">
                            <span class="t8-side-badge">PANTRY</span>
                            <h3 class="t8-side-title">Cooking Oil, Rice & Spices</h3>
                            <span class="t8-side-link">Order Pantry &rarr;</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Supermarket Features --}}
    <section class="t8-container">
        <div class="t8-features-bar">
            <div class="t8-feature-item">
                <div class="t8-feature-icon"><i class="fas fa-shipping-fast"></i></div>
                <div>
                    <h5 class="t8-feature-label">Fast 45-Min Delivery</h5>
                    <p class="t8-feature-desc">To your doorstep</p>
                </div>
            </div>
            <div class="t8-feature-item">
                <div class="t8-feature-icon"><i class="fas fa-carrot"></i></div>
                <div>
                    <h5 class="t8-feature-label">Direct From Farmers</h5>
                    <p class="t8-feature-desc">100% natural & fresh</p>
                </div>
            </div>
            <div class="t8-feature-item">
                <div class="t8-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h5 class="t8-feature-label">Hygiene Assured</h5>
                    <p class="t8-feature-desc">Safe & contactless packing</p>
                </div>
            </div>
            <div class="t8-feature-item">
                <div class="t8-feature-icon"><i class="fas fa-tags"></i></div>
                <div>
                    <h5 class="t8-feature-label">Best Market Prices</h5>
                    <p class="t8-feature-desc">Guaranteed wholesale savings</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Category Aisles --}}
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <section class="t8-container" style="margin-bottom: 35px;">
            <div class="t8-sec-header">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-bar"></div>
                    <h2 class="t8-sec-title">Shop by Supermarket Aisle</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">All Aisles &rarr;</a>
            </div>
            <div class="t8-cat-scroll">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t8-cat-pill">
                        <img class="t8-cat-img" src="{{ asset($cat->image ?? $cat->icon ?? 'clientside/images/product-placeholder.png') }}" alt="{{ $cat->name }}">
                        <span class="t8-cat-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Fresh Produce & Deals --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t8-container">
            <div class="t8-sec-header">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-bar"></div>
                    <h2 class="t8-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Today\'s Fresh Grocery Deals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">View All &rarr;</a>
            </div>
            <div class="t8-product-row">
                @foreach($featuredProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'FRESH'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Best Selling Daily Staples --}}
    @if (!empty($homepage['enable_best_selling_section']) && $homepage['enable_best_selling_section'] && isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
        <section class="t8-container">
            <div class="t8-sec-header">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-bar"></div>
                    <h2 class="t8-sec-title">{{ $homepage['best_selling_section_heading'] ?? 'Daily Kitchen Staples' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">View All &rarr;</a>
            </div>
            <div class="t8-product-row">
                @foreach($bestSellingProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'POPULAR'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Latest Grocery Arrivals --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t8-container">
            <div class="t8-sec-header">
                <div class="t8-sec-title-wrap">
                    <div class="t8-sec-bar"></div>
                    <h2 class="t8-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'New Produce Arrivals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t8-view-all">View All &rarr;</a>
            </div>
            <div class="t8-product-row">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
