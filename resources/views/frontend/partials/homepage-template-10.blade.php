{{-- 
    Template 10: Home Living, Furniture & Interior Decor
    Designed specifically for Furniture, Home Appliances, Decor, Lighting & Kitchenware
--}}

<style>
.t10-page {
    background: #fdfcfb;
    color: #292524;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    padding-bottom: 60px;
}

.t10-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Warm Earthtone Terracotta Top Strip */
.t10-top-strip {
    background: #44403c;
    color: #ffffff;
    padding: 9px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.t10-top-strip a {
    color: #fdba74;
    font-weight: 800;
    text-decoration: underline;
}

/* Hero Section */
.t10-hero-section {
    padding: 20px 0 30px;
}

.t10-hero-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

@media (max-width: 992px) {
    .t10-hero-layout {
        grid-template-columns: 1fr;
    }
}

.t10-hero-slider {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 480px;
    background: #292524;
    box-shadow: 0 10px 30px rgba(68, 64, 60, 0.12);
}

@media (max-width: 768px) {
    .t10-hero-slider {
        height: 320px;
    }
}

.t10-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
    display: block;
    text-decoration: none;
}

.t10-slide.active {
    opacity: 1;
    visibility: visible;
}

.t10-slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.t10-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(28, 25, 23, 0.8) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px 40px;
    color: #ffffff;
}

.t10-slide-badge {
    background: #ea580c;
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

.t10-slide-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .t10-slide-title {
        font-size: 22px;
    }
    .t10-slide-overlay {
        padding: 20px;
    }
}

.t10-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    color: #ea580c;
    padding: 10px 22px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 800;
    width: fit-content;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    transition: all 0.2s;
}

.t10-slide-btn:hover {
    background: #ea580c;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Nav Arrows */
.t10-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    border: none;
    color: #44403c;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.t10-hero-arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.t10-hero-prev { left: 16px; }
.t10-hero-next { right: 16px; }

/* Side Banners */
.t10-side-deals {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 480px;
}

@media (max-width: 992px) {
    .t10-side-deals {
        height: auto;
        flex-direction: row;
    }
}

@media (max-width: 640px) {
    .t10-side-deals {
        flex-direction: column;
    }
}

.t10-side-card {
    flex: 1;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: #e7e5e4;
    display: block;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(68, 64, 60, 0.08);
}

.t10-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.t10-side-card:hover img {
    transform: scale(1.06);
}

.t10-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.05) 0%, rgba(28, 25, 23, 0.8) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    color: #fff;
}

.t10-side-badge {
    background: #ea580c;
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

.t10-side-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t10-side-link {
    font-size: 12px;
    color: #fed7aa;
    font-weight: 700;
}

/* Home Living Features Bar */
.t10-features-bar {
    background: #ffffff;
    border: 1px solid #fed7aa;
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    box-shadow: 0 4px 16px rgba(234, 88, 12, 0.04);
}

@media (max-width: 860px) {
    .t10-features-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t10-feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.t10-feature-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 50%;
    background: #ffedd5;
    color: #ea580c;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.t10-feature-label {
    font-size: 13.5px;
    font-weight: 800;
    color: #44403c;
    margin: 0;
}

.t10-feature-desc {
    font-size: 11px;
    color: #78716c;
    margin: 0;
}

/* Shop by Room Cards */
.t10-room-section {
    margin-bottom: 35px;
}

.t10-room-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 860px) {
    .t10-room-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t10-room-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    height: 160px;
    display: block;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}

.t10-room-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.t10-room-card:hover img {
    transform: scale(1.08);
}

.t10-room-content {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(28, 25, 23, 0.75) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 16px;
    color: #fff;
}

.t10-room-title {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 2px;
    color: #fff;
}

.t10-room-sub {
    font-size: 11px;
    color: #fed7aa;
    margin: 0;
}

/* Section Header */
.t10-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #fed7aa;
}

.t10-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.t10-sec-bar {
    width: 4px;
    height: 22px;
    background: #ea580c;
    border-radius: 2px;
}

.t10-sec-title {
    font-size: 19px;
    font-weight: 800;
    color: #44403c;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.t10-view-all {
    font-size: 13px;
    font-weight: 700;
    color: #ea580c;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.t10-view-all:hover {
    color: #c2410c;
}

/* Category Pills */
.t10-cat-scroll {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}

.t10-cat-scroll::-webkit-scrollbar {
    display: none;
}

.t10-cat-pill {
    flex: 0 0 130px;
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 10px;
    border: 1px solid #fed7aa;
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

.t10-cat-pill:hover {
    transform: translateY(-4px);
    border-color: #ea580c;
    box-shadow: 0 8px 20px rgba(234, 88, 12, 0.12);
    color: #ea580c;
}

.t10-cat-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #ffedd5;
    border: 2px solid #fed7aa;
}

.t10-cat-name {
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}

/* Product Grid */
.t10-product-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t10-product-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t10-page">

    {{-- Top Announcement Strip --}}
    <div class="t10-top-strip">
        <span>🛋️ PREMIUM SOLID WOOD FURNITURE • ARTISAN HOME DECOR & MODERN LIGHTING</span>
        <span>UP TO 10-YEAR WARRANTY &bull; <a href="{{ route('shop') }}">EXPLORE LIVING &rarr;</a></span>
    </div>

    {{-- Hero Section --}}
    <section class="t10-hero-section">
        <div class="t10-container">
            <div class="t10-hero-layout">
                
                {{-- Big Slider --}}
                <div class="t10-hero-slider" id="t10HeroSlider">
                    <button class="t10-hero-arrow t10-hero-prev" id="t10Prev" aria-label="Previous Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t10-hero-arrow t10-hero-next" id="t10Next" aria-label="Next Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>

                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t10-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t10-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Home & Living' }}">
                            <div class="t10-slide-overlay">
                                @if($slider->title)
                                    <span class="t10-slide-badge">INTERIOR HABITAT</span>
                                    <h1 class="t10-slide-title">{{ $slider->title }}</h1>
                                @endif
                                <span class="t10-slide-btn">
                                    {{ $slider->button_text ?: 'EXPLORE FURNITURE' }}
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t10-slide active">
                            <img class="t10-slide-img" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1400&q=80" alt="Furniture Living">
                            <div class="t10-slide-overlay">
                                <span class="t10-slide-badge">LIVING & HABITAT</span>
                                <h1 class="t10-slide-title">Handcrafted Solid Wood & Modern Living Room Collections</h1>
                                <span class="t10-slide-btn">SHOP COLLECTION &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Side Deals --}}
                <div class="t10-side-deals">
                    <a href="{{ $homepage['slider_side_image_one_link'] ?? route('shop') }}" class="t10-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_one']) ? asset($homepage['slider_side_image_one']) : 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&q=80' }}" alt="Living Room">
                        <div class="t10-side-overlay">
                            <span class="t10-side-badge">COMFORT</span>
                            <h3 class="t10-side-title">Luxury Sofas & Loungers</h3>
                            <span class="t10-side-link">View Range &rarr;</span>
                        </div>
                    </a>
                    <a href="{{ $homepage['slider_side_image_two_link'] ?? route('shop') }}" class="t10-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_two']) ? asset($homepage['slider_side_image_two']) : 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600&q=80' }}" alt="Dining Decor">
                        <div class="t10-side-overlay">
                            <span class="t10-side-badge">DECOR</span>
                            <h3 class="t10-side-title">Lamps & Dining Sets</h3>
                            <span class="t10-side-link">Shop Decor &rarr;</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Features Strip --}}
    <section class="t10-container">
        <div class="t10-features-bar">
            <div class="t10-feature-item">
                <div class="t10-feature-icon"><i class="fas fa-couch"></i></div>
                <div>
                    <h5 class="t10-feature-label">Solid Wood Guarantee</h5>
                    <p class="t10-feature-desc">100% seasoned teak & oak</p>
                </div>
            </div>
            <div class="t10-feature-item">
                <div class="t10-feature-icon"><i class="fas fa-tools"></i></div>
                <div>
                    <h5 class="t10-feature-label">Free Room Installation</h5>
                    <p class="t10-feature-desc">By expert craftsmen</p>
                </div>
            </div>
            <div class="t10-feature-item">
                <div class="t10-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h5 class="t10-feature-label">10-Year Warranty</h5>
                    <p class="t10-feature-desc">Hassle-free coverage</p>
                </div>
            </div>
            <div class="t10-feature-item">
                <div class="t10-feature-icon"><i class="fas fa-truck-moving"></i></div>
                <div>
                    <h5 class="t10-feature-label">Safe Freight Delivery</h5>
                    <p class="t10-feature-desc">Damage-proof transit</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Shop by Room Grid --}}
    <section class="t10-container t10-room-section">
        <div class="t10-sec-header">
            <div class="t10-sec-title-wrap">
                <div class="t10-sec-bar"></div>
                <h2 class="t10-sec-title">Shop by Room</h2>
            </div>
            <a href="{{ route('shop') }}" class="t10-view-all">All Rooms &rarr;</a>
        </div>
        <div class="t10-room-grid">
            <a href="{{ route('shop') }}" class="t10-room-card">
                <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&q=80" alt="Living Room">
                <div class="t10-room-content">
                    <h4 class="t10-room-title">Living Room</h4>
                    <p class="t10-room-sub">Sofas, Coffee Tables & TV Units</p>
                </div>
            </a>
            <a href="{{ route('shop') }}" class="t10-room-card">
                <img src="https://images.unsplash.com/photo-1540518614846-7ede433c4550?w=600&q=80" alt="Bedroom">
                <div class="t10-room-content">
                    <h4 class="t10-room-title">Bedroom</h4>
                    <p class="t10-room-sub">Beds, Wardrobes & Mattresses</p>
                </div>
            </a>
            <a href="{{ route('shop') }}" class="t10-room-card">
                <img src="https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600&q=80" alt="Kitchen & Dining">
                <div class="t10-room-content">
                    <h4 class="t10-room-title">Kitchen & Dining</h4>
                    <p class="t10-room-sub">Dining Sets, Cookware & Cabinets</p>
                </div>
            </a>
            <a href="{{ route('shop') }}" class="t10-room-card">
                <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=600&q=80" alt="Office & Workspace">
                <div class="t10-room-content">
                    <h4 class="t10-room-title">Home Office</h4>
                    <p class="t10-room-sub">Desks, Ergonomic Chairs & Bookshelves</p>
                </div>
            </a>
        </div>
    </section>

    {{-- Curated Categories --}}
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <section class="t10-container" style="margin-bottom: 35px;">
            <div class="t10-sec-header">
                <div class="t10-sec-title-wrap">
                    <div class="t10-sec-bar"></div>
                    <h2 class="t10-sec-title">Furniture Categories</h2>
                </div>
                <a href="{{ route('shop') }}" class="t10-view-all">All Categories &rarr;</a>
            </div>
            <div class="t10-cat-scroll">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t10-cat-pill">
                        <img class="t10-cat-img" src="{{ asset($cat->image ?? $cat->icon ?? 'clientside/images/product-placeholder.png') }}" alt="{{ $cat->name }}">
                        <span class="t10-cat-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Living Room & Deals --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t10-container">
            <div class="t10-sec-header">
                <div class="t10-sec-title-wrap">
                    <div class="t10-sec-bar"></div>
                    <h2 class="t10-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Signature Furniture Pieces' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t10-view-all">View All &rarr;</a>
            </div>
            <div class="t10-product-row">
                @foreach($featuredProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'SOLID WOOD'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Best Selling Decor --}}
    @if (!empty($homepage['enable_best_selling_section']) && $homepage['enable_best_selling_section'] && isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
        <section class="t10-container">
            <div class="t10-sec-header">
                <div class="t10-sec-title-wrap">
                    <div class="t10-sec-bar"></div>
                    <h2 class="t10-sec-title">{{ $homepage['best_selling_section_heading'] ?? 'Customer Favorites' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t10-view-all">View All &rarr;</a>
            </div>
            <div class="t10-product-row">
                @foreach($bestSellingProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'TOP PICK'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Latest Arrivals --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t10-container">
            <div class="t10-sec-header">
                <div class="t10-sec-title-wrap">
                    <div class="t10-sec-bar"></div>
                    <h2 class="t10-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'New Living Room Arrivals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t10-view-all">View All &rarr;</a>
            </div>
            <div class="t10-product-row">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t10-slide');
    const prevBtn = document.getElementById('t10Prev');
    const nextBtn = document.getElementById('t10Next');
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
