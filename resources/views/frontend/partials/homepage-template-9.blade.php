{{-- 
    Template 9: Books, Academy & Heritage Store
    Old-World Library, Academic Publishing & Islamic Bookstore Aesthetic (Rokomari & Oxford style)
--}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap');

/* ── SCOPED TEMPLATE 9: BOOKS & HERITAGE STORE ── */
.t9-page {
    --t9-bg: {{ $homepage['template_9_bg_color'] ?? '#fdf8ee' }};
    --t9-accent: {{ $homepage['template_9_accent_color'] ?? '#d97706' }};
    --t9-text: {{ $homepage['template_9_text_color'] ?? '#1c1600' }};
    background: var(--t9-bg);
    color: var(--t9-text);
    font-family: 'Lato', -apple-system, BlinkMacSystemFont, sans-serif;
    padding-bottom: 70px;
    overflow-x: hidden;
}

.t9-container {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
}

/* SECTION 1: Publisher's Notice Strip */
.t9-notice-strip {
    background: #0f172a;
    color: #fde68a;
    padding: 9px 20px;
    font-family: 'Libre Baskerville', serif;
    font-style: italic;
    font-size: 12.5px;
    text-align: center;
    border-bottom: 1px solid #bf8b2e;
    letter-spacing: 0.5px;
}

/* SECTION 2: Reading Room Hero Slider with Left/Right Controls */
.t9-hero-section {
    padding: 20px 0;
}

.t9-hero-container {
    position: relative;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #d1d5db;
    box-shadow: 0 14px 36px rgba(15, 23, 42, 0.15);
    background: #0f172a;
    width: 100%;
}

.t9-hero-slider {
    position: relative;
    width: 100%;
    aspect-ratio: 1500 / 600;
    min-height: 180px;
    overflow: hidden;
}

.t9-slide {
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

.t9-slide.active {
    opacity: 1;
    visibility: visible;
}

.t9-slide-img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    display: block;
}

/* Slide Caption */
.t9-slide-caption {
    position: absolute;
    bottom: 24px;
    left: 24px;
    background: rgba(15, 23, 42, 0.88);
    border: 1px solid #bf8b2e;
    backdrop-filter: blur(8px);
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
}

@media (max-width: 640px) {
    .t9-slide-caption {
        bottom: 12px;
        left: 12px;
        right: 12px;
        padding: 6px 12px;
        gap: 8px;
    }
}

.t9-caption-title {
    font-family: 'Libre Baskerville', serif;
    font-size: 16px;
    color: #ffffff;
    font-weight: 700;
}

.t9-caption-btn {
    background: #bf8b2e;
    color: #0f172a;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.8px;
    padding: 5px 12px;
    border-radius: 4px;
    text-transform: uppercase;
}

.t9-hero-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid #bf8b2e;
    color: #fbbf24;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.25s ease;
    z-index: 10;
    box-shadow: 0 4px 15px rgba(0,0,0,0.4);
}

.t9-hero-prev {
    left: 20px;
}

.t9-hero-next {
    right: 20px;
}

.t9-hero-arrow:hover {
    background: #bf8b2e;
    color: #0f172a;
    transform: translateY(-50%) scale(1.1);
}

@media (max-width: 640px) {
    .t9-hero-arrow {
        width: 36px;
        height: 36px;
    }
    .t9-hero-prev {
        left: 10px;
    }
    .t9-hero-next {
        right: 10px;
    }
}

/* SECTION 3: Browse by Genre Shelf Tabs */
.t9-section {
    padding: 30px 0 15px;
}

.t9-sec-head {
    text-align: center;
    margin-bottom: 24px;
}

.t9-sec-tag {
    color: #bf8b2e;
    font-size: 12px;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 900;
    margin-bottom: 4px;
}

.t9-sec-title {
    font-family: 'Libre Baskerville', serif;
    font-size: clamp(24px, 3.2vw, 36px);
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.t9-genre-pills {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.t9-genre-pill {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    color: #374151;
    font-family: 'Libre Baskerville', serif;
    font-size: 12.5px;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}

.t9-genre-pill:hover, .t9-genre-pill.active {
    background: #0f172a;
    color: #fbbf24;
    border-color: #0f172a;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.15);
}

/* SECTION 4: National Bestsellers (Portrait 3:4 Ratio Book Cards) */
.t9-bestsellers-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
}

@media (max-width: 1024px) {
    .t9-bestsellers-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 640px) {
    .t9-bestsellers-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t9-book-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    position: relative;
}

.t9-book-card:hover {
    transform: translateY(-6px);
    border-color: #bf8b2e;
    box-shadow: 0 12px 24px rgba(191, 139, 46, 0.15);
}

.t9-rank-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #bf8b2e;
    color: #0b1329;
    font-size: 10px;
    font-weight: 900;
    padding: 2px 8px;
    border-radius: 3px;
    z-index: 2;
}

.t9-book-img {
    width: 100%;
    aspect-ratio: 3/4;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.t9-book-title {
    font-family: 'Libre Baskerville', serif;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
    margin: 0 0 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.t9-book-author {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 8px;
}

.t9-book-price {
    font-family: 'Lato', sans-serif;
    font-size: 15px;
    font-weight: 900;
    color: #92400e;
    margin-top: auto;
}

/* SECTION 5: Author Spotlight Editorial Profiles */
.t9-author-section {
    background: #0f172a;
    color: #ffffff;
    border-radius: 20px;
    padding: 45px 35px;
    margin: 40px 0;
}

.t9-author-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 24px;
}

@media (max-width: 850px) {
    .t9-author-grid {
        grid-template-columns: 1fr;
    }
}

.t9-author-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    padding: 22px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.t9-author-card:hover {
    border-color: #fbbf24;
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-4px);
}

.t9-author-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #bf8b2e;
    margin: 0 auto 12px;
}

.t9-author-name {
    font-family: 'Libre Baskerville', serif;
    font-size: 17px;
    font-weight: 700;
    color: #fbbf24;
    margin: 0 0 4px;
}

.t9-author-genre {
    font-size: 12px;
    color: #9ca3af;
    margin-bottom: 10px;
}

.t9-author-link {
    font-size: 12px;
    color: #fbbf24;
    font-weight: 700;
}

/* SECTION 6: Guarantee of Originality Trust Band */
.t9-trust-band {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px 20px;
    margin: 30px 0;
}

.t9-trust-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    text-align: center;
}

@media (max-width: 768px) {
    .t9-trust-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

.t9-trust-icon {
    font-size: 24px;
    margin-bottom: 6px;
}

.t9-trust-label {
    font-family: 'Libre Baskerville', serif;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2px;
}

.t9-trust-sub {
    font-size: 11px;
    color: #6b7280;
}

/* SECTION 7: Stationery & Islamic Supplies */
.t9-stationery-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 25px 0;
}

@media (max-width: 768px) {
    .t9-stationery-grid {
        grid-template-columns: 1fr;
    }
}

.t9-stationery-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    height: 220px;
    text-decoration: none;
    display: block;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

.t9-stationery-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.t9-stationery-card:hover img {
    transform: scale(1.05);
}

.t9-stationery-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 20%, rgba(15, 23, 42, 0.88) 100%);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.t9-stationery-title {
    font-family: 'Libre Baskerville', serif;
    font-size: 19px;
    color: #ffffff;
    font-weight: 700;
    margin: 0 0 4px;
}

.t9-stationery-sub {
    color: #fbbf24;
    font-size: 12px;
    font-weight: 700;
}

/* Standard Product Grid */
.t9-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
    gap: 18px;
    margin-bottom: 40px;
}

@media (max-width: 768px) {
    .t9-product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<div class="t9-page">

    {{-- SECTION 1: Publisher's Notice Strip --}}
    <div class="t9-notice-strip">
        {{ $homepage['template_9_notice_text'] ?? '📖 Guaranteed 100% Genuine Publisher Prints • Islamic Scholarly Library & Academic Textbooks Direct to Your Door' }}
    </div>

    {{-- SECTION 2: Reading Room Hero Slider with Left/Right Controls --}}
    <section class="t9-hero-section">
        <div class="t9-container">
            <div class="t9-hero-container">
                <div class="t9-hero-slider" id="t9HeroSlider">
                    @forelse($sliders as $idx => $slider)
                        <a href="{{ $slider->button_url ?? route('shop') }}" class="t9-slide {{ $idx === 0 ? 'active' : '' }}">
                            <img class="t9-slide-img" src="{{ asset($slider->image) }}" alt="{{ $slider->title ?? 'Book Collection' }}">
                            @if(!empty($slider->title))
                                <div class="t9-slide-caption">
                                    <span class="t9-caption-title">{{ $slider->title }}</span>
                                    <span class="t9-caption-btn">{{ $slider->button_text ?: 'EXPLORE' }} &rarr;</span>
                                </div>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('shop') }}" class="t9-slide active">
                            <img class="t9-slide-img" src="https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=1600&q=80" alt="Books Collection">
                            <div class="t9-slide-caption">
                                <span class="t9-caption-title">Classical Islamic & Academic Library</span>
                                <span class="t9-caption-btn">BROWSE BOOKS &rarr;</span>
                            </div>
                        </a>
                    @endforelse
                </div>
                {{-- Left & Right Buttons Centered on Both Sides --}}
                <button class="t9-hero-arrow t9-hero-prev" id="t9Prev" aria-label="Previous Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="t9-hero-arrow t9-hero-next" id="t9Next" aria-label="Next Slide">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- SECTION 3: Browse by Genre Shelf Tabs --}}
    <section class="t9-section">
        <div class="t9-container">
            <div class="t9-sec-head">
                <div class="t9-sec-tag">LITERARY SHELVES</div>
                <h2 class="t9-sec-title">Explore by Subject & Discipline</h2>
            </div>
            <div class="t9-genre-pills">
                <a href="{{ route('shop') }}" class="t9-genre-pill active">📖 Islamic Studies & Tafseer</a>
                <a href="{{ route('shop') }}" class="t9-genre-pill">🕌 Hadith & Seerah</a>
                <a href="{{ route('shop') }}" class="t9-genre-pill">📜 Bengali Classical Literature</a>
                <a href="{{ route('shop') }}" class="t9-genre-pill">🎓 BCS & Academic Guides</a>
                <a href="{{ route('shop') }}" class="t9-genre-pill">🧠 Self-Help & Psychology</a>
                <a href="{{ route('shop') }}" class="t9-genre-pill">🌍 World History & Politics</a>
            </div>
        </div>
    </section>

    {{-- SECTION 4: National Bestsellers (Portrait 3:4 Cards) --}}
    <section class="t9-section">
        <div class="t9-container">
            <div class="t9-sec-head">
                <div class="t9-sec-tag">TOP CIRCULATION</div>
                <h2 class="t9-sec-title">{{ $homepage['template_9_bestsellers_title'] ?? 'National Bestselling Titles' }}</h2>
            </div>
            <div class="t9-bestsellers-grid">
                <a href="{{ route('shop') }}" class="t9-book-card">
                    <span class="t9-rank-badge">#1 BESTSELLER</span>
                    <img class="t9-book-img" src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&q=80" alt="Book 1">
                    <h4 class="t9-book-title">Seerat-un-Nabi (Complete Edition)</h4>
                    <span class="t9-book-author">Allama Shibli Nomani</span>
                    <span class="t9-book-price">৳650</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-book-card">
                    <span class="t9-rank-badge">#2 BESTSELLER</span>
                    <img class="t9-book-img" src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80" alt="Book 2">
                    <h4 class="t9-book-title">Tafseer Ibn Kathir (English/Bangla)</h4>
                    <span class="t9-book-author">Imam Ibn Kathir</span>
                    <span class="t9-book-price">৳1,400</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-book-card">
                    <span class="t9-rank-badge">#3 BESTSELLER</span>
                    <img class="t9-book-img" src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400&q=80" alt="Book 3">
                    <h4 class="t9-book-title">Atomic Habits (Translated)</h4>
                    <span class="t9-book-author">James Clear</span>
                    <span class="t9-book-price">৳320</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-book-card">
                    <span class="t9-rank-badge">#4 BESTSELLER</span>
                    <img class="t9-book-img" src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=400&q=80" alt="Book 4">
                    <h4 class="t9-book-title">Paradoxical Sajid</h4>
                    <span class="t9-book-author">Arif Azad</span>
                    <span class="t9-book-price">৳280</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-book-card">
                    <span class="t9-rank-badge">#5 BESTSELLER</span>
                    <img class="t9-book-img" src="https://images.unsplash.com/photo-1495640388908-05fa85288e61?w=400&q=80" alt="Book 5">
                    <h4 class="t9-book-title">Ar-Raheequl Makhtum</h4>
                    <span class="t9-book-author">Safiur Rahman Mubarakpuri</span>
                    <span class="t9-book-price">৳480</span>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 5: Author Spotlight Editorial Profiles --}}
    <section class="t9-container">
        <div class="t9-author-section">
            <div class="t9-sec-head" style="margin-bottom:0;">
                <div class="t9-sec-tag" style="color:#fbbf24;">DISTINGUISHED SCHOLARS</div>
                <h2 class="t9-sec-title" style="color:#ffffff;">{{ $homepage['template_9_author_title'] ?? 'Author Spotlight' }}</h2>
            </div>
            <div class="t9-author-grid">
                <a href="{{ route('shop') }}" class="t9-author-card">
                    <img class="t9-author-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80" alt="Author 1">
                    <h4 class="t9-author-name">Dr. Khondokar Abdullah Jahangir</h4>
                    <div class="t9-author-genre">Islamic Hadith & Comparative Theology</div>
                    <span class="t9-author-link">18 Titles Available &rarr;</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-author-card">
                    <img class="t9-author-avatar" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80" alt="Author 2">
                    <h4 class="t9-author-name">Arif Azad</h4>
                    <div class="t9-author-genre">Youth Awakening & Apologetics</div>
                    <span class="t9-author-link">12 Titles Available &rarr;</span>
                </a>

                <a href="{{ route('shop') }}" class="t9-author-card">
                    <img class="t9-author-avatar" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80" alt="Author 3">
                    <h4 class="t9-author-name">Humayun Ahmed</h4>
                    <div class="t9-author-genre">Contemporary Bengali Fiction</div>
                    <span class="t9-author-link">45 Titles Available &rarr;</span>
                </a>
            </div>
        </div>
    </section>

    {{-- SECTION 6: Guarantee of Originality Trust Band --}}
    <section class="t9-container">
        <div class="t9-trust-band">
            <div class="t9-trust-grid">
                <div>
                    <div class="t9-trust-icon">{{ $homepage['template_9_trust_1_icon'] ?? '📜' }}</div>
                    <div class="t9-trust-label">{{ $homepage['template_9_trust_1_title'] ?? '100% Genuine Prints' }}</div>
                    <div class="t9-trust-sub">{{ $homepage['template_9_trust_1_sub'] ?? 'Direct publisher authorization' }}</div>
                </div>
                <div>
                    <div class="t9-trust-icon">{{ $homepage['template_9_trust_2_icon'] ?? '📦' }}</div>
                    <div class="t9-trust-label">{{ $homepage['template_9_trust_2_title'] ?? 'Publisher Sealed Pack' }}</div>
                    <div class="t9-trust-sub">{{ $homepage['template_9_trust_2_sub'] ?? 'Unopened pristine condition' }}</div>
                </div>
                <div>
                    <div class="t9-trust-icon">{{ $homepage['template_9_trust_3_icon'] ?? '🚚' }}</div>
                    <div class="t9-trust-label">{{ $homepage['template_9_trust_3_title'] ?? 'Safe Book Delivery' }}</div>
                    <div class="t9-trust-sub">{{ $homepage['template_9_trust_3_sub'] ?? 'Bubble-wrapped damage proof' }}</div>
                </div>
                <div>
                    <div class="t9-trust-icon">{{ $homepage['template_9_trust_4_icon'] ?? '↩️' }}</div>
                    <div class="t9-trust-label">{{ $homepage['template_9_trust_4_title'] ?? 'Hassle-Free Replacement' }}</div>
                    <div class="t9-trust-sub">{{ $homepage['template_9_trust_4_sub'] ?? 'If print defects are found' }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 7: Stationery & Islamic Supplies --}}
    <section class="t9-container">
        <div class="t9-stationery-grid">
            <a href="{{ route('shop') }}" class="t9-stationery-card">
                <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=600&q=80" alt="Academic Stationery">
                <div class="t9-stationery-overlay">
                    <h4 class="t9-stationery-title">Academic & Student Stationery</h4>
                    <span class="t9-stationery-sub">Leather Diaries, Fountain Pens & Mathematical Sets &rarr;</span>
                </div>
            </a>

            <a href="{{ route('shop') }}" class="t9-stationery-card">
                <img src="https://images.unsplash.com/photo-1584281722573-95669b35b2e5?w=600&q=80" alt="Islamic Goods">
                <div class="t9-stationery-overlay">
                    <h4 class="t9-stationery-title">Islamic Heritage Collection</h4>
                    <span class="t9-stationery-sub">Carved Rehal (Quran Stand), Prayer Mats & Attar &rarr;</span>
                </div>
            </a>
        </div>
    </section>

    {{-- SECTION 8: Featured Catalog Products --}}
    @if (!empty($homepage['enable_featured_product_section']) && $homepage['enable_featured_product_section'] && isset($featuredProducts) && $featuredProducts->count() > 0)
        <section class="t9-section">
            <div class="t9-container">
                <div class="t9-sec-head">
                    <div class="t9-sec-tag">CURATED RELEASES</div>
                    <h2 class="t9-sec-title">{{ $homepage['featured_product_section_heading'] ?? 'Fresh Off The Press' }}</h2>
                </div>
                <div class="t9-product-grid">
                    @foreach($featuredProducts as $product)
                        @include('frontend.partials.product-item', ['product' => $product, 'badge' => 'GENUINE'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.t9-slide');
    const prev = document.getElementById('t9Prev');
    const next = document.getElementById('t9Next');
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
