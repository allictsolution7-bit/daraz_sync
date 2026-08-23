{{-- 
    Template 6: Fashion, Clothing & Luxury Apparel Studio
    Designed specifically for Fashion, Panjabi, Western Wear, Couture, and Lifestyle Clothing
--}}

<style>
/* ── TEMPLATE 6: FASHION & APPAREL STUDIO ── */
.t6-page {
    background: #fafafa;
    color: #1a1a1a;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    padding-bottom: 60px;
}

.t6-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Editorial Top Banner */
.t6-top-strip {
    background: #111827;
    color: #ffffff;
    padding: 10px 16px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.t6-top-strip a {
    color: #f59e0b;
    text-decoration: underline;
    transition: color 0.2s;
}

.t6-top-strip a:hover {
    color: #fbbf24;
}

/* Hero Section */
.t6-hero-section {
    padding: 20px 0 30px;
}

.t6-hero-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

@media (max-width: 992px) {
    .t6-hero-layout {
        grid-template-columns: 1fr;
    }
}

/* Big Hero Slider */
.t6-hero-slider {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    height: 480px;
    background: #1e293b;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

@media (max-width: 768px) {
    .t6-hero-slider {
        height: 320px;
    }
}

.t6-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
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
    object-fit: cover;
}

.t6-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.65) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px 40px;
    color: #ffffff;
}

.t6-slide-tag {
    display: inline-block;
    background: #ffffff;
    color: #0f172a;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
    width: fit-content;
}

.t6-slide-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .t6-slide-title {
        font-size: 22px;
    }
    .t6-slide-overlay {
        padding: 20px;
    }
}

.t6-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #2563eb;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    width: fit-content;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: all 0.2s;
}

.t6-slide-btn:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

/* Slider Nav Arrows */
.t6-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(4px);
    border: none;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.t6-hero-arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.t6-hero-prev { left: 16px; }
.t6-hero-next { right: 16px; }

/* Side Editorial Banners */
.t6-side-deals {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 480px;
}

@media (max-width: 992px) {
    .t6-side-deals {
        height: auto;
        flex-direction: row;
    }
}

@media (max-width: 640px) {
    .t6-side-deals {
        flex-direction: column;
    }
}

.t6-side-card {
    flex: 1;
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #0f172a;
    display: block;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}

.t6-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.t6-side-card:hover img {
    transform: scale(1.06);
}

.t6-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    color: #fff;
}

.t6-side-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.4);
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

.t6-side-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t6-side-link {
    font-size: 12px;
    color: #93c5fd;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Department Split Cards (Men / Women / Essentials) */
.t6-dept-section {
    padding: 30px 0;
}

.t6-dept-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 768px) {
    .t6-dept-grid {
        grid-template-columns: 1fr;
    }
}

.t6-dept-card {
    position: relative;
    height: 240px;
    border-radius: 16px;
    overflow: hidden;
    display: block;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
}

.t6-dept-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.t6-dept-card:hover img {
    transform: scale(1.05);
}

.t6-dept-content {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.75) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 24px;
    color: #fff;
}

.t6-dept-title {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t6-dept-sub {
    font-size: 12px;
    color: #e2e8f0;
    margin-bottom: 8px;
}

.t6-dept-cta {
    font-size: 12.5px;
    font-weight: 700;
    color: #60a5fa;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Section Header */
.t6-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e2e8f0;
}

.t6-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.t6-sec-bar {
    width: 4px;
    height: 22px;
    background: #2563eb;
    border-radius: 2px;
}

.t6-sec-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.t6-view-all {
    font-size: 13px;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
}

.t6-view-all:hover {
    color: #1d4ed8;
}

/* Categories Carousel */
.t6-cat-scroll {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}

.t6-cat-scroll::-webkit-scrollbar {
    display: none;
}

.t6-cat-pill {
    flex: 0 0 130px;
    background: #ffffff;
    border-radius: 14px;
    padding: 14px 10px;
    border: 1px solid #e2e8f0;
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

.t6-cat-pill:hover {
    transform: translateY(-4px);
    border-color: #93c5fd;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.1);
    color: #2563eb;
}

.t6-cat-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #f8fafc;
    border: 2px solid #f1f5f9;
}

.t6-cat-name {
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}

/* Product Rows & Grids */
.t6-product-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t6-product-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

/* Lookbook Shoppable Instagram Row */
.t6-lookbook-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 30px;
    margin: 30px 0 45px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}

.t6-lookbook-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

@media (max-width: 992px) {
    .t6-lookbook-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t6-lookbook-tile {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 1/1;
    display: block;
}

.t6-lookbook-tile img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.t6-lookbook-tile:hover img {
    transform: scale(1.08);
}

.t6-lookbook-tag {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
}
</style>

<div class="t6-page">

    {{-- Top Announcement Strip --}}
    <div class="t6-top-strip">
        <span>✨ NEW SEASON COLLECTION &bull; 100% ETHICAL FABRICS & PREMIER TAILORING</span>
        <span>EXPRESS DELIVERY AVAILABLE &bull; <a href="{{ route('shop') }}">SHOP NEW ARRIVALS &rarr;</a></span>
    </div>

    {{-- Hero Section --}}
    <section class="t6-hero-section">
        <div class="t6-container">
            <div class="t6-hero-layout">
                
                {{-- Big Slider --}}
                <div class="t6-hero-slider" id="t6HeroSlider">
                    <button class="t6-hero-arrow t6-hero-prev" id="t6Prev" aria-label="Previous Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t6-hero-arrow t6-hero-next" id="t6Next" aria-label="Next Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>

                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t6-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t6-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Fashion Lookbook' }}">
                            <div class="t6-slide-overlay">
                                @if($slider->title)
                                    <span class="t6-slide-tag">SEASON LOOKBOOK</span>
                                    <h1 class="t6-slide-title">{{ $slider->title }}</h1>
                                @endif
                                <span class="t6-slide-btn">
                                    {{ $slider->button_text ?: 'EXPLORE COLLECTION' }}
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t6-slide active">
                            <img class="t6-slide-img" src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1400&q=80" alt="Fashion Couture">
                            <div class="t6-slide-overlay">
                                <span class="t6-slide-tag">PREMIUM APPAREL</span>
                                <h1 class="t6-slide-title">Autumn & Winter Lookbook Collection</h1>
                                <span class="t6-slide-btn">SHOP COLLECTION &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Side Deals --}}
                <div class="t6-side-deals">
                    <a href="{{ $homepage['slider_side_image_one_link'] ?? route('shop') }}" class="t6-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_one']) ? asset($homepage['slider_side_image_one']) : 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&q=80' }}" alt="Men's Collection">
                        <div class="t6-side-overlay">
                            <span class="t6-side-badge">LUXURY WEAR</span>
                            <h3 class="t6-side-title">Panjabi & Festive Wear</h3>
                            <span class="t6-side-link">View Range &rarr;</span>
                        </div>
                    </a>
                    <a href="{{ $homepage['slider_side_image_two_link'] ?? route('shop') }}" class="t6-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_two']) ? asset($homepage['slider_side_image_two']) : 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80' }}" alt="Women's Collection">
                        <div class="t6-side-overlay">
                            <span class="t6-side-badge">COUTURE</span>
                            <h3 class="t6-side-title">Designer Western & Ethnic</h3>
                            <span class="t6-side-link">Shop Now &rarr;</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Shop by Department (Split Cards) --}}
    <section class="t6-dept-section">
        <div class="t6-container">
            <div class="t6-dept-grid">
                <a href="{{ route('shop') }}" class="t6-dept-card">
                    <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?w=600&q=80" alt="Men's Wardrobe">
                    <div class="t6-dept-content">
                        <h3 class="t6-dept-title">Men's Apparel</h3>
                        <p class="t6-dept-sub">Suits, Panjabi, Casual Shirts & Footwear</p>
                        <span class="t6-dept-cta">Shop Men &rarr;</span>
                    </div>
                </a>
                <a href="{{ route('shop') }}" class="t6-dept-card">
                    <img src="https://images.unsplash.com/photo-1485968579580-b6d095142e6e?w=600&q=80" alt="Women's Wardrobe">
                    <div class="t6-dept-content">
                        <h3 class="t6-dept-title">Women's Boutique</h3>
                        <p class="t6-dept-sub">Dresses, Saree, Kurtis & Handbags</p>
                        <span class="t6-dept-cta">Shop Women &rarr;</span>
                    </div>
                </a>
                <a href="{{ route('shop') }}" class="t6-dept-card">
                    <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600&q=80" alt="Accessories & Fragrances">
                    <div class="t6-dept-content">
                        <h3 class="t6-dept-title">Accessories & Shoes</h3>
                        <p class="t6-dept-sub">Watches, Belts, Wallets & Footwear</p>
                        <span class="t6-dept-cta">Shop Accessories &rarr;</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Curated Categories Pills --}}
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <section class="t6-container" style="margin-bottom: 35px;">
            <div class="t6-sec-header">
                <div class="t6-sec-title-wrap">
                    <div class="t6-sec-bar"></div>
                    <h2 class="t6-sec-title">Explore Categories</h2>
                </div>
                <a href="{{ route('shop') }}" class="t6-view-all">All Categories &rarr;</a>
            </div>
            <div class="t6-cat-scroll">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t6-cat-pill">
                        <img class="t6-cat-img" src="{{ asset($cat->image ?? $cat->icon ?? 'clientside/images/product-placeholder.png') }}" alt="{{ $cat->name }}">
                        <span class="t6-cat-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured & Trending Collection --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t6-container">
            <div class="t6-sec-header">
                <div class="t6-sec-title-wrap">
                    <div class="t6-sec-bar"></div>
                    <h2 class="t6-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Trending Fashion Picks' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t6-view-all">View All &rarr;</a>
            </div>
            <div class="t6-product-row">
                @foreach($featuredProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'TRENDING'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Best Selling Outfits --}}
    @if (!empty($homepage['enable_best_selling_section']) && $homepage['enable_best_selling_section'] && isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
        <section class="t6-container">
            <div class="t6-sec-header">
                <div class="t6-sec-title-wrap">
                    <div class="t6-sec-bar"></div>
                    <h2 class="t6-sec-title">{{ $homepage['best_selling_section_heading'] ?? 'Most Loved Outfits' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t6-view-all">View All &rarr;</a>
            </div>
            <div class="t6-product-row">
                @foreach($bestSellingProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'POPULAR'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Lookbook / Instagram Shoppable Gallery --}}
    <section class="t6-container">
        <div class="t6-lookbook-section">
            <div class="text-center mb-4">
                <span class="text-primary font-weight-bold text-uppercase" style="letter-spacing: 1px; font-size: 11px;">#StyleInspiration</span>
                <h3 class="font-weight-bold text-dark mt-1" style="font-size: 22px;">Shop The Look Gallery</h3>
                <p class="text-muted small">Curated seasonal outfits styled by top creators & our boutique atelier.</p>
            </div>
            <div class="t6-lookbook-grid">
                <a href="{{ route('shop') }}" class="t6-lookbook-tile">
                    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600&q=80" alt="Spring Look">
                    <span class="t6-lookbook-tag"><i class="fas fa-shopping-bag"></i> 2 Items Tagged</span>
                </a>
                <a href="{{ route('shop') }}" class="t6-lookbook-tile">
                    <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?w=600&q=80" alt="Urban Streetwear">
                    <span class="t6-lookbook-tag"><i class="fas fa-shopping-bag"></i> 3 Items Tagged</span>
                </a>
                <a href="{{ route('shop') }}" class="t6-lookbook-tile">
                    <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=600&q=80" alt="Luxury Formal">
                    <span class="t6-lookbook-tag"><i class="fas fa-shopping-bag"></i> 1 Item Tagged</span>
                </a>
                <a href="{{ route('shop') }}" class="t6-lookbook-tile">
                    <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?w=600&q=80" alt="Classic Festive">
                    <span class="t6-lookbook-tag"><i class="fas fa-shopping-bag"></i> 2 Items Tagged</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Latest Arrivals --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t6-container">
            <div class="t6-sec-header">
                <div class="t6-sec-title-wrap">
                    <div class="t6-sec-bar"></div>
                    <h2 class="t6-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'New Atelier Arrivals' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t6-view-all">View All &rarr;</a>
            </div>
            <div class="t6-product-row">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t6-slide');
    const prevBtn = document.getElementById('t6Prev');
    const nextBtn = document.getElementById('t6Next');
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
