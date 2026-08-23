{{-- 
    Template 9: Books, Academy & Heritage Store
    Designed specifically for Books, Islamic Collections, Academic Libraries & Stationery
--}}

<style>
.t9-page {
    background: #fdfbf7;
    color: #1e293b;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    padding-bottom: 60px;
}

.t9-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 16px;
}

/* Classic Heritage Navy/Gold Top Strip */
.t9-top-strip {
    background: #0f172a;
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

.t9-top-strip a {
    color: #fbbf24;
    font-weight: 800;
    text-decoration: underline;
}

/* Hero Section */
.t9-hero-section {
    padding: 20px 0 30px;
}

.t9-hero-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

@media (max-width: 992px) {
    .t9-hero-layout {
        grid-template-columns: 1fr;
    }
}

.t9-hero-slider {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    height: 480px;
    background: #1e1b18;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .t9-hero-slider {
        height: 320px;
    }
}

.t9-slide {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.6s ease, visibility 0.6s ease;
    display: block;
    text-decoration: none;
}

.t9-slide.active {
    opacity: 1;
    visibility: visible;
}

.t9-slide-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.t9-slide-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(15, 23, 42, 0.85) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 36px 40px;
    color: #ffffff;
}

.t9-slide-badge {
    background: #d97706;
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

.t9-slide-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .t9-slide-title {
        font-size: 22px;
    }
    .t9-slide-overlay {
        padding: 20px;
    }
}

.t9-slide-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fbbf24;
    color: #0f172a;
    padding: 10px 22px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 800;
    width: fit-content;
    box-shadow: 0 4px 14px rgba(251, 191, 36, 0.3);
    transition: all 0.2s;
}

.t9-slide-btn:hover {
    background: #f59e0b;
    transform: translateY(-2px);
}

/* Nav Arrows */
.t9-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    border: none;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.t9-hero-arrow:hover {
    background: #ffffff;
    transform: translateY(-50%) scale(1.08);
}

.t9-hero-prev { left: 16px; }
.t9-hero-next { right: 16px; }

/* Side Banners */
.t9-side-deals {
    display: flex;
    flex-direction: column;
    gap: 16px;
    height: 480px;
}

@media (max-width: 992px) {
    .t9-side-deals {
        height: auto;
        flex-direction: row;
    }
}

@media (max-width: 640px) {
    .t9-side-deals {
        flex-direction: column;
    }
}

.t9-side-card {
    flex: 1;
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    background: #1e293b;
    display: block;
    text-decoration: none;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.t9-side-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.t9-side-card:hover img {
    transform: scale(1.06);
}

.t9-side-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(15, 23, 42, 0.8) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 20px;
    color: #fff;
}

.t9-side-badge {
    background: #d97706;
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

.t9-side-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 4px;
}

.t9-side-link {
    font-size: 12px;
    color: #fde68a;
    font-weight: 700;
}

/* Heritage Book Badges Strip */
.t9-features-bar {
    background: #ffffff;
    border: 1px solid #fde68a;
    border-radius: 16px;
    padding: 16px 24px;
    margin-bottom: 35px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    box-shadow: 0 4px 16px rgba(217, 119, 6, 0.04);
}

@media (max-width: 860px) {
    .t9-features-bar {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t9-feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.t9-feature-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 50%;
    background: #fef3c7;
    color: #d97706;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.t9-feature-label {
    font-size: 13.5px;
    font-weight: 800;
    color: #78350f;
    margin: 0;
}

.t9-feature-desc {
    font-size: 11px;
    color: #64748b;
    margin: 0;
}

/* Section Header */
.t9-sec-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #fde68a;
}

.t9-sec-title-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}

.t9-sec-bar {
    width: 4px;
    height: 22px;
    background: #d97706;
    border-radius: 2px;
}

.t9-sec-title {
    font-size: 19px;
    font-weight: 800;
    color: #78350f;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.t9-view-all {
    font-size: 13px;
    font-weight: 700;
    color: #d97706;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
}

.t9-view-all:hover {
    color: #b45309;
}

/* Genre Shelves Pills */
.t9-cat-scroll {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 6px 2px 14px;
}

.t9-cat-scroll::-webkit-scrollbar {
    display: none;
}

.t9-cat-pill {
    flex: 0 0 130px;
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 10px;
    border: 1px solid #fde68a;
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

.t9-cat-pill:hover {
    transform: translateY(-4px);
    border-color: #d97706;
    box-shadow: 0 8px 20px rgba(217, 119, 6, 0.12);
    color: #d97706;
}

.t9-cat-img {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    background: #fef3c7;
    border: 2px solid #fde68a;
}

.t9-cat-name {
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 100%;
}

/* Product Grid */
.t9-product-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t9-product-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t9-page">

    {{-- Top Announcement Strip --}}
    <div class="t9-top-strip">
        <span>📖 AUTHENTIC ORIGINAL EDITIONS • BESTSELLING AUTHORS & ISLAMIC LITERATURE</span>
        <span>DELIVERED NATIONWIDE &bull; <a href="{{ route('shop') }}">BROWSE BOOKSTORE &rarr;</a></span>
    </div>

    {{-- Hero Section --}}
    <section class="t9-hero-section">
        <div class="t9-container">
            <div class="t9-hero-layout">
                
                {{-- Big Slider --}}
                <div class="t9-hero-slider" id="t9HeroSlider">
                    <button class="t9-hero-arrow t9-hero-prev" id="t9Prev" aria-label="Previous Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button class="t9-hero-arrow t9-hero-next" id="t9Next" aria-label="Next Slide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                    </button>

                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t9-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t9-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Bookstore & Academy' }}">
                            <div class="t9-slide-overlay">
                                @if($slider->title)
                                    <span class="t9-slide-badge">KNOWLEDGE & HERITAGE</span>
                                    <h1 class="t9-slide-title">{{ $slider->title }}</h1>
                                @endif
                                <span class="t9-slide-btn">
                                    {{ $slider->button_text ?: 'EXPLORE BOOKS' }}
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t9-slide active">
                            <img class="t9-slide-img" src="https://images.unsplash.com/photo-1507842229450-78212e6900f9?w=1400&q=80" alt="Bookstore Library">
                            <div class="t9-slide-overlay">
                                <span class="t9-slide-badge">ORIGINAL EDITIONS</span>
                                <h1 class="t9-slide-title">Bestselling Titles, Novels & Academic Guides</h1>
                                <span class="t9-slide-btn">READ & EXPLORE &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>

                {{-- Side Deals --}}
                <div class="t9-side-deals">
                    <a href="{{ $homepage['slider_side_image_one_link'] ?? route('shop') }}" class="t9-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_one']) ? asset($homepage['slider_side_image_one']) : 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&q=80' }}" alt="Islamic Books">
                        <div class="t9-side-overlay">
                            <span class="t9-side-badge">ISLAMIC</span>
                            <h3 class="t9-side-title">Quran, Hadith & Tafseer</h3>
                            <span class="t9-side-link">View Collection &rarr;</span>
                        </div>
                    </a>
                    <a href="{{ $homepage['slider_side_image_two_link'] ?? route('shop') }}" class="t9-side-card">
                        <img src="{{ !empty($homepage['slider_side_image_two']) ? asset($homepage['slider_side_image_two']) : 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&q=80' }}" alt="Stationery">
                        <div class="t9-side-overlay">
                            <span class="t9-side-badge">ACADEMY</span>
                            <h3 class="t9-side-title">Academic & Self Growth</h3>
                            <span class="t9-side-link">Shop Titles &rarr;</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Bookstore Features --}}
    <section class="t9-container">
        <div class="t9-features-bar">
            <div class="t9-feature-item">
                <div class="t9-feature-icon"><i class="fas fa-book"></i></div>
                <div>
                    <h5 class="t9-feature-label">100% Genuine Prints</h5>
                    <p class="t9-feature-desc">Official publisher editions</p>
                </div>
            </div>
            <div class="t9-feature-item">
                <div class="t9-feature-icon"><i class="fas fa-feather-alt"></i></div>
                <div>
                    <h5 class="t9-feature-label">Author Signatures</h5>
                    <p class="t9-feature-desc">Exclusive collectible copies</p>
                </div>
            </div>
            <div class="t9-feature-item">
                <div class="t9-feature-icon"><i class="fas fa-box"></i></div>
                <div>
                    <h5 class="t9-feature-label">Protective Box Packaging</h5>
                    <p class="t9-feature-desc">Delivered in mint condition</p>
                </div>
            </div>
            <div class="t9-feature-item">
                <div class="t9-feature-icon"><i class="fas fa-bookmark"></i></div>
                <div>
                    <h5 class="t9-feature-label">Free Bookmark Gift</h5>
                    <p class="t9-feature-desc">With every book parcel</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Curated Categories / Genres --}}
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <section class="t9-container" style="margin-bottom: 35px;">
            <div class="t9-sec-header">
                <div class="t9-sec-title-wrap">
                    <div class="t9-sec-bar"></div>
                    <h2 class="t9-sec-title">Explore Book Genres & Shelves</h2>
                </div>
                <a href="{{ route('shop') }}" class="t9-view-all">All Genres &rarr;</a>
            </div>
            <div class="t9-cat-scroll">
                @foreach($featuredCategories as $cat)
                    <a href="{{ route('shop', $cat->slug) }}" class="t9-cat-pill">
                        <img class="t9-cat-img" src="{{ asset($cat->image ?? $cat->icon ?? 'clientside/images/product-placeholder.png') }}" alt="{{ $cat->name }}">
                        <span class="t9-cat-name">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured Books --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t9-container">
            <div class="t9-sec-header">
                <div class="t9-sec-title-wrap">
                    <div class="t9-sec-bar"></div>
                    <h2 class="t9-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Featured Book Picks' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t9-view-all">View All &rarr;</a>
            </div>
            <div class="t9-product-row">
                @foreach($featuredProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'FEATURED'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Bestsellers --}}
    @if (!empty($homepage['enable_best_selling_section']) && $homepage['enable_best_selling_section'] && isset($bestSellingProducts) && $bestSellingProducts->count() > 0)
        <section class="t9-container">
            <div class="t9-sec-header">
                <div class="t9-sec-title-wrap">
                    <div class="t9-sec-bar"></div>
                    <h2 class="t9-sec-title">{{ $homepage['best_selling_section_heading'] ?? 'National Bestsellers' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t9-view-all">View All &rarr;</a>
            </div>
            <div class="t9-product-row">
                @foreach($bestSellingProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'BESTSELLER'])
                @endforeach
            </div>
        </section>
    @endif

    {{-- Latest Arrivals --}}
    @if (!empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] && isset($latestProducts) && $latestProducts->count() > 0)
        <section class="t9-container">
            <div class="t9-sec-header">
                <div class="t9-sec-title-wrap">
                    <div class="t9-sec-bar"></div>
                    <h2 class="t9-sec-title">{{ $homepage['latest_products_section_heading'] ?? 'New Release Publications' }}</h2>
                </div>
                <a href="{{ route('shop') }}" class="t9-view-all">View All &rarr;</a>
            </div>
            <div class="t9-product-row">
                @foreach($latestProducts as $product)
                    @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'NEW'])
                @endforeach
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t9-slide');
    const prevBtn = document.getElementById('t9Prev');
    const nextBtn = document.getElementById('t9Next');
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
