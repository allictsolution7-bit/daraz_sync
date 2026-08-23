{{-- 
    Template 10: Home Living, Furniture & Interior Decor
    Luxury Interior Studio & Architectural Living Aesthetic (IKEA & Pottery Barn inspired)
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;700;800&family=DM+Serif+Display:ital@0;1&display=swap');

/* ── SCOPED TEMPLATE 10: LUXURY HOME & LIVING ── */
.t10-page {
    background: #f7f3ee;
    color: #201f1a;
    font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    padding-bottom: 70px;
    overflow-x: hidden;
}

.t10-container {
    max-width: 1380px;
    margin: 0 auto;
    padding: 0 20px;
}

/* SECTION 1: Interior Design Inspiration Strip */
.t10-linen-strip {
    background: #e8ddd0;
    color: #2d3a2e;
    padding: 10px 20px;
    font-family: 'DM Serif Display', serif;
    font-size: 13.5px;
    text-align: center;
    letter-spacing: 0.8px;
    border-bottom: 1px solid #d6c7b2;
}

/* SECTION 2: Clean Architectural Hero Slider */
.t10-hero-section {
    padding: 20px 0;
}

.t10-hero-container {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #d6c7b2;
    background: #201f1a;
    box-shadow: 0 14px 36px rgba(45, 58, 46, 0.12);
    width: 100%;
}

.t10-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 1500 / 600;
    min-height: 180px;
    overflow: hidden;
}

.t10-slide {
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

.t10-slide.active {
    opacity: 1;
    visibility: visible;
}

.t10-slide-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
}

.t10-slide-caption {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(32, 31, 26, 0.85);
    border: 1px solid #c96a1e;
    backdrop-filter: blur(10px);
    padding: 12px 24px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.4);
}

@media (max-width: 640px) {
    .t10-slide-caption {
        bottom: 12px;
        left: 12px;
        right: 12px;
        padding: 8px 14px;
        gap: 8px;
    }
}

.t10-caption-title {
    font-family: 'DM Serif Display', serif;
    font-size: 17px;
    color: #ffffff;
}

.t10-caption-btn {
    background: #c96a1e;
    color: #ffffff;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    padding: 6px 14px;
    border-radius: 4px;
    text-transform: uppercase;
}

.t10-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(32, 31, 26, 0.82);
    border: 1px solid #d6c7b2;
    color: #f7eedd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.4);
}

.t10-hero-prev {
    left: 20px;
}

.t10-hero-next {
    right: 20px;
}

.t10-hero-arrow:hover {
    background: #c96a1e;
    color: #ffffff;
    border-color: #c96a1e;
    transform: translateY(-50%) scale(1.1);
}

@media (max-width: 640px) {
    .t10-hero-arrow {
        width: 36px;
        height: 36px;
    }
    .t10-hero-prev {
        left: 10px;
    }
    .t10-hero-next {
        right: 10px;
    }
}

/* SECTION 3: Dedicated Interactive Room Hotspot Studio */
.t10-hotspot-studio {
    margin: 40px 0;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #d6c7b2;
    box-shadow: 0 16px 40px rgba(0,0,0,0.08);
}

.t10-studio-bg {
    width: 100%;
    height: 520px;
    object-fit: cover;
    display: block;
    filter: brightness(0.88);
}

@media (max-width: 768px) {
    .t10-studio-bg {
        height: 380px;
    }
}

.t10-studio-overlay-head {
    position: absolute;
    top: 30px;
    left: 30px;
    background: rgba(32, 31, 26, 0.85);
    backdrop-filter: blur(8px);
    padding: 16px 24px;
    border-radius: 12px;
    border: 1px solid rgba(253, 186, 116, 0.4);
    max-width: 440px;
}

@media (max-width: 600px) {
    .t10-studio-overlay-head {
        top: 15px;
        left: 15px;
        right: 15px;
        padding: 12px 16px;
    }
}

.t10-studio-tag {
    color: #fdba74;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.t10-studio-title {
    font-family: 'DM Serif Display', serif;
    font-size: 20px;
    color: #ffffff;
    margin: 4px 0 2px;
}

.t10-studio-sub {
    font-size: 12px;
    color: #d1d5db;
}

/* Hotspot Pins */
.t10-hotspot-item {
    position: absolute;
    z-index: 10;
}

.t10-hotspot-pin {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #ffffff;
    border: 2px solid #c96a1e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c96a1e;
    font-weight: 900;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    animation: t10Pulse 2.5s infinite;
    transition: all 0.3s ease;
}

.t10-hotspot-pin:hover {
    transform: scale(1.15);
    background: #c96a1e;
    color: #ffffff;
}

.t10-hotspot-card {
    position: absolute;
    bottom: 45px;
    left: 50%;
    transform: translateX(-50%) translateY(10px);
    background: #ffffff;
    border: 1px solid #d6c7b2;
    padding: 10px 16px;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s ease;
    pointer-events: none;
}

.t10-hotspot-item:hover .t10-hotspot-card {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(0);
}

.t10-card-name {
    font-family: 'DM Serif Display', serif;
    font-size: 14px;
    color: #201f1a;
    margin-bottom: 2px;
}

.t10-card-price {
    font-size: 12px;
    font-weight: 800;
    color: #c96a1e;
}

@keyframes t10Pulse {
    0% { box-shadow: 0 0 0 0 rgba(201, 106, 30, 0.6); }
    70% { box-shadow: 0 0 0 14px rgba(201, 106, 30, 0); }
    100% { box-shadow: 0 0 0 0 rgba(201, 106, 30, 0); }
}

/* SECTION 4: Shop by Room Interactive Large Cards */
.t10-section {
    padding: 40px 0 20px;
}

.t10-sec-head {
    text-align: center;
    margin-bottom: 35px;
}

.t10-sec-tag {
    color: #c96a1e;
    font-size: 12px;
    letter-spacing: 3px;
    text-transform: uppercase;
    font-weight: 800;
    margin-bottom: 4px;
}

.t10-sec-title {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(28px, 3.8vw, 42px);
    color: #201f1a;
    margin: 0;
}

.t10-rooms-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
}

@media (max-width: 1024px) {
    .t10-rooms-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 600px) {
    .t10-rooms-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t10-room-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    aspect-ratio: 1/1;
    background: #e8ddd0;
    text-decoration: none;
    display: block;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    transition: all 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.t10-room-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(45, 58, 46, 0.18);
}

.t10-room-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.t10-room-card:hover .t10-room-img {
    transform: scale(1.08);
}

.t10-room-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(32, 31, 26, 0.85) 100%);
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.t10-room-name {
    font-family: 'DM Serif Display', serif;
    font-size: 19px;
    color: #ffffff;
    margin: 0 0 2px;
}

.t10-room-cta {
    font-size: 11px;
    font-weight: 700;
    color: #fdba74;
}

/* SECTION 5: Material & Craftsmanship Trust Section */
.t10-craft-section {
    background: #2d3a2e;
    color: #ffffff;
    border-radius: 20px;
    padding: 55px 40px;
    margin: 50px 0;
}

.t10-craft-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-top: 30px;
}

@media (max-width: 850px) {
    .t10-craft-grid {
        grid-template-columns: 1fr;
    }
}

.t10-craft-card {
    text-align: center;
    padding: 20px;
}

.t10-craft-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.t10-craft-title {
    font-family: 'DM Serif Display', serif;
    font-size: 20px;
    color: #fdba74;
    margin: 0 0 8px;
}

.t10-craft-desc {
    font-size: 13.5px;
    color: #d1d5db;
    line-height: 1.6;
}

/* SECTION 6: Interior Styling Inspiration Blog Tiles */
.t10-blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

@media (max-width: 850px) {
    .t10-blog-grid {
        grid-template-columns: 1fr;
    }
}

.t10-blog-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e8ddd0;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.t10-blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(45, 58, 46, 0.1);
}

.t10-blog-img {
    height: 200px;
    width: 100%;
    object-fit: cover;
}

.t10-blog-body {
    padding: 22px;
}

.t10-blog-tag {
    font-size: 11px;
    font-weight: 800;
    color: #c96a1e;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.t10-blog-title {
    font-family: 'DM Serif Display', serif;
    font-size: 21px;
    font-weight: 700;
    color: #201f1a;
    margin: 6px 0 8px;
}

.t10-blog-desc {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.5;
}

/* SECTION 7: Room Makeover Offer CTA Banner */
.t10-makeover-banner {
    margin: 50px 0;
    border-radius: 20px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    background: #2d3a2e;
    box-shadow: 0 16px 40px rgba(0,0,0,0.1);
}

@media (max-width: 850px) {
    .t10-makeover-banner {
        grid-template-columns: 1fr;
    }
}

.t10-makeover-content {
    padding: 55px 45px;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.t10-makeover-tag {
    color: #fdba74;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.t10-makeover-title {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(26px, 3.5vw, 38px);
    line-height: 1.15;
    margin: 0 0 12px;
}

.t10-makeover-desc {
    color: #d1d5db;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 24px;
}

.t10-btn-terracotta {
    background: #c96a1e;
    color: #ffffff;
    font-family: 'DM Sans', sans-serif;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 14px 32px;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 6px 20px rgba(201, 106, 30, 0.35);
    display: inline-block;
}

.t10-btn-terracotta:hover {
    background: #df7c2e;
    transform: translateY(-2px);
}

.t10-makeover-img {
    min-height: 280px;
}

.t10-makeover-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Grid */
.t10-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t10-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t10-page">

    {{-- SECTION 1: Interior Design Inspiration Strip --}}
    <div class="t10-linen-strip">
        {{ $homepage['template_10_linen_text'] ?? '🏡 Free Professional Assembly • 100% Solid Seasoned Teak Guarantee • 10-Year Structural Frame Warranty' }}
    </div>

    {{-- SECTION 2: Clean Architectural Hero Slider --}}
    <section class="t10-hero-section">
        <div class="t10-container">
            <div class="t10-hero-container">
                <div class="t10-hero-slider" id="t10HeroSlider">
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t10-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t10-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Home & Furniture' }}">
                            @if(!empty($slider->title))
                                <div class="t10-slide-caption">
                                    <span class="t10-caption-title">{{ $slider->title }}</span>
                                    <span class="t10-caption-btn">{{ $slider->button_text ?: 'EXPLORE' }} &rarr;</span>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t10-slide active">
                            <img class="t10-slide-img" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&q=80" alt="Luxury Living Room">
                            <div class="t10-slide-caption">
                                <span class="t10-caption-title">Nordic Living Room Collection</span>
                                <span class="t10-caption-btn">SHOP COLLECTION &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>
                {{-- Left & Right Buttons Centered on Both Sides --}}
                <button class="t10-hero-arrow t10-hero-prev" id="t10Prev" aria-label="Previous Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="t10-hero-arrow t10-hero-next" id="t10Next" aria-label="Next Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Dedicated Interactive Room Hotspot Studio --}}
    <section class="t10-container">
        <div class="t10-hotspot-studio">
            <img class="t10-studio-bg" src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&q=80" alt="Curated Scandinavian Studio">
            
            <div class="t10-studio-overlay-head">
                <span class="t10-studio-tag">✦ INTERACTIVE ROOM STUDIO</span>
                <h3 class="t10-studio-title">{{ $homepage['template_10_hotspot_title'] ?? 'Interactive Room Hotspot Studio' }}</h3>
                <span class="t10-studio-sub">Hover on the (+) pins below to inspect and order featured furnishings</span>
            </div>

            {{-- Hotspot 1: Sofa --}}
            <div class="t10-hotspot-item" style="top: 58%; left: 32%;">
                <div class="t10-hotspot-pin">+</div>
                <div class="t10-hotspot-card">
                    <div class="t10-card-name">Nordic 3-Seater Fabric Sofa</div>
                    <div class="t10-card-price">৳28,500 &bull; In Stock</div>
                </div>
            </div>

            {{-- Hotspot 2: Coffee Table --}}
            <div class="t10-hotspot-item" style="top: 75%; left: 55%;">
                <div class="t10-hotspot-pin">+</div>
                <div class="t10-hotspot-card">
                    <div class="t10-card-name">Solid Teak Minimalist Coffee Table</div>
                    <div class="t10-card-price">৳8,200 &bull; In Stock</div>
                </div>
            </div>

            {{-- Hotspot 3: Floor Lamp --}}
            <div class="t10-hotspot-item" style="top: 38%; left: 82%;">
                <div class="t10-hotspot-pin">+</div>
                <div class="t10-hotspot-card">
                    <div class="t10-card-name">Arc Brass Floor Reading Lamp</div>
                    <div class="t10-card-price">৳4,500 &bull; In Stock</div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Shop by Room Interactive Large Cards --}}
    <section class="t10-section">
        <div class="t10-container">
            <div class="t10-sec-head">
                <div class="t10-sec-tag">SPATIAL HARMONY</div>
                <h2 class="t10-sec-title">{{ $homepage['template_10_rooms_title'] ?? 'Shop Curated Living Environments' }}</h2>
            </div>
            <div class="t10-rooms-grid">
                <a href="{{ route('shop') }}" class="t10-room-card">
                    <img class="t10-room-img" src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&q=80" alt="Living Room">
                    <div class="t10-room-overlay">
                        <h4 class="t10-room-name">Living Room</h4>
                        <span class="t10-room-cta">Sofas & Media Consoles &rarr;</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-room-card">
                    <img class="t10-room-img" src="https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=500&q=80" alt="Bedroom">
                    <div class="t10-room-overlay">
                        <h4 class="t10-room-name">Bedroom</h4>
                        <span class="t10-room-cta">King Beds & Wardrobes &rarr;</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-room-card">
                    <img class="t10-room-img" src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbe?w=500&q=80" alt="Dining Room">
                    <div class="t10-room-overlay">
                        <h4 class="t10-room-name">Dining Room</h4>
                        <span class="t10-room-cta">Solid Wood Dining Sets &rarr;</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-room-card">
                    <img class="t10-room-img" src="https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=500&q=80" alt="Home Office">
                    <div class="t10-room-overlay">
                        <h4 class="t10-room-name">Home Office</h4>
                        <span class="t10-room-cta">Ergonomic Desks & Bookshelves &rarr;</span>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-room-card">
                    <img class="t10-room-img" src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500&q=80" alt="Kitchen Decor">
                    <div class="t10-room-overlay">
                        <h4 class="t10-room-name">Kitchen & Bath</h4>
                        <span class="t10-room-cta">Shelving & Modern Fixtures &rarr;</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 5: Teak Wood & Craftsmanship Story --}}
    <section class="t10-container">
        <div class="t10-craft-section">
            <div class="t10-sec-head" style="margin-bottom:0;">
                <div class="t10-sec-tag" style="color:#fdba74;">SUSTAINABLE ARCHITECTURE</div>
                <h2 class="t10-sec-title" style="color:#ffffff;">{{ $homepage['template_10_warranty_title'] ?? 'Built to Last Generations' }}</h2>
            </div>
            <div class="t10-craft-grid">
                <div class="t10-craft-card">
                    <div class="t10-craft-icon">🌲</div>
                    <h4 class="t10-craft-title">Solid Seasoned Teak</h4>
                    <p class="t10-craft-desc">Ethically harvested from government-certified forestry reserves and kiln-dried to eliminate moisture warping.</p>
                </div>
                <div class="t10-craft-card">
                    <div class="t10-craft-icon">🔨</div>
                    <h4 class="t10-craft-title">Master Wood Joinery</h4>
                    <p class="t10-craft-desc">Traditional mortise and tenon joints built by master carpenters with 35+ years of bench experience.</p>
                </div>
                <div class="t10-craft-card">
                    <div class="t10-craft-icon">🛡️</div>
                    <h4 class="t10-craft-title">10-Year Frame Warranty</h4>
                    <p class="t10-craft-desc">Full structural replacement guarantee covering termite resistance, joint integrity, and lacquer durability.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 6: Interior Styling Inspiration Blog Tiles --}}
    <section class="t10-section">
        <div class="t10-container">
            <div class="t10-sec-head">
                <div class="t10-sec-tag">DESIGN JOURNAL</div>
                <h2 class="t10-sec-title">Interior Styling Inspiration</h2>
            </div>
            <div class="t10-blog-grid">
                <a href="{{ route('shop') }}" class="t10-blog-card">
                    <img class="t10-blog-img" src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=600&q=80" alt="Minimal Living">
                    <div class="t10-blog-body">
                        <span class="t10-blog-tag">LIVING SPACES</span>
                        <h4 class="t10-blog-title">How to Style a Warm Minimalist Apartment</h4>
                        <p class="t10-blog-desc">Balancing neutral earth tones, textured bouclé fabrics, and walnut wood accents.</p>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-blog-card">
                    <img class="t10-blog-img" src="https://images.unsplash.com/photo-1540518614846-7eded433c457?w=600&q=80" alt="Bedroom Guide">
                    <div class="t10-blog-body">
                        <span class="t10-blog-tag">BEDROOM COMFORTS</span>
                        <h4 class="t10-blog-title">Choosing the Right Bed Frame: Wood vs. Upholstered</h4>
                        <p class="t10-blog-desc">A complete structural analysis on longevity, storage hydraulics, and room acoustics.</p>
                    </div>
                </a>

                <a href="{{ route('shop') }}" class="t10-blog-card">
                    <img class="t10-blog-img" src="https://images.unsplash.com/photo-1449247709967-d4461a6a6103?w=600&q=80" alt="Dining Decor">
                    <div class="t10-blog-body">
                        <span class="t10-blog-tag">ENTERTAINING</span>
                        <h4 class="t10-blog-title">Dining Table Dimensions Guide for Urban Homes</h4>
                        <p class="t10-blog-desc">Maximize your dining footprint with expandable butterfly leaves and bench seating.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 7: Room Makeover Offer CTA Banner --}}
    <section class="t10-container">
        <div class="t10-makeover-banner">
            <div class="t10-makeover-content">
                <span class="t10-makeover-tag">COMPLETE INTERIOR PACKAGE</span>
                <h3 class="t10-makeover-title">Full Living Room Makeover Suite</h3>
                <p class="t10-makeover-desc">
                    Includes our 3-Seater Nordic Sofa, Solid Walnut Coffee Table, and Floating Media Unit with complimentary installation. Price: {{ $homepage['template_10_package_price'] ?? '৳48,500' }}.
                </p>
                <div>
                    <a href="{{ route('shop') }}" class="t10-btn-terracotta">EXPLORE ROOM PACKAGES &rarr;</a>
                </div>
            </div>
            <div class="t10-makeover-img">
                <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=700&q=80" alt="Room Suite">
            </div>
        </div>
    </section>

    {{-- SECTION 8: Featured Furniture Products --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t10-section">
            <div class="t10-container">
                <div class="t10-sec-head">
                    <div class="t10-sec-tag">ICONIC PIECES</div>
                    <h2 class="t10-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Signature Furniture Creations' }}</h2>
                </div>
                <div class="t10-product-grid">
                    @foreach($featuredProducts as $product)
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'SOLID WOOD'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t10-slide');
    const prev = document.getElementById('t10Prev');
    const next = document.getElementById('t10Next');
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
</script>
