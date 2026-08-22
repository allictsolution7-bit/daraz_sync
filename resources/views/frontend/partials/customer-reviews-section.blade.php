@if (!empty($homepage['enable_customer_reviews_section']) && $homepage['enable_customer_reviews_section'] && isset($customerReviews) && $customerReviews->count() > 0)
    <!-- Customer Reviews Section -->
    <style>
        .cr-section {
            padding: 40px 0 45px;
            background: #ffffff;
            border-top: 1px solid #f1f5f9;
        }

        body[data-hp-template="3"] .cr-section {
            background: #0b0f19 !important;
            border-top-color: #1e293b !important;
        }

        .cr-wrapper {
            max-width: 1340px;
            margin: 0 auto;
            padding: 0 16px;
        }

        .cr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }

        body[data-hp-template="3"] .cr-header {
            border-bottom-color: #1e293b !important;
        }

        .cr-title-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cr-title-bar {
            width: 4px;
            height: 22px;
            background: linear-gradient(180deg, #2563eb, #3b82f6);
            border-radius: 4px;
        }

        .cr-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }

        body[data-hp-template="3"] .cr-title {
            color: #f8fafc !important;
        }

        .cr-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cr-nav-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            padding: 0;
        }

        .cr-nav-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        body[data-hp-template="3"] .cr-nav-btn {
            background: #1e293b;
            border-color: #334155;
            color: #cbd5e1;
        }

        body[data-hp-template="3"] .cr-nav-btn:hover {
            background: #38bdf8;
            color: #0f172a;
            border-color: #38bdf8;
        }

        .cr-carousel-track {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 4px 2px 14px;
            scroll-snap-type: x mandatory;
        }

        .cr-carousel-track::-webkit-scrollbar {
            display: none;
        }

        .cr-card {
            flex: 0 0 calc((100% - (3 * 16px)) / 4);
            max-width: calc((100% - (3 * 16px)) / 4);
            scroll-snap-align: start;
            background: #ffffff;
            border-radius: 16px;
            padding: 18px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.25s ease;
            box-sizing: border-box;
        }

        .cr-card:hover {
            transform: translateY(-4px);
            border-color: #bfdbfe;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.07);
        }

        body[data-hp-template="3"] .cr-card {
            background: #111827 !important;
            border-color: #1e293b !important;
        }

        body[data-hp-template="3"] .cr-card:hover {
            border-color: #38bdf8 !important;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5) !important;
        }

        .cr-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .cr-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .cr-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            background: #f1f5f9;
        }

        .cr-user-meta {
            min-width: 0;
        }

        .cr-user-name {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        body[data-hp-template="3"] .cr-user-name {
            color: #f1f5f9 !important;
        }

        .cr-review-date {
            font-size: 11px;
            color: #94a3b8;
            margin: 0;
        }

        .cr-stars {
            color: #f59e0b;
            font-size: 12px;
            display: flex;
            gap: 2px;
            flex-shrink: 0;
        }

        .cr-product-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 7px 10px;
            margin-bottom: 12px;
        }

        body[data-hp-template="3"] .cr-product-box {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        .cr-product-thumb {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .cr-product-name {
            font-size: 11.5px;
            font-weight: 600;
            color: #334155;
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        body[data-hp-template="3"] .cr-product-name {
            color: #e2e8f0 !important;
        }

        .cr-comment {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.5;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        body[data-hp-template="3"] .cr-comment {
            color: #94a3b8 !important;
        }

        /* Bottom Pagination / Dots */
        .cr-pagination-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 14px;
        }

        .cr-dots-wrap {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .cr-dot {
            width: 8px;
            height: 8px;
            border-radius: 4px;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .cr-dot.active {
            width: 22px;
            background: #2563eb;
        }

        body[data-hp-template="3"] .cr-dot {
            background: #334155;
        }

        body[data-hp-template="3"] .cr-dot.active {
            background: #38bdf8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1200px) {
            .cr-card {
                flex: 0 0 calc((100% - (2 * 16px)) / 3);
                max-width: calc((100% - (2 * 16px)) / 3);
            }
        }

        @media (max-width: 860px) {
            .cr-card {
                flex: 0 0 calc((100% - 14px) / 2);
                max-width: calc((100% - 14px) / 2);
            }
            .cr-carousel-track {
                gap: 14px;
            }
        }

        @media (max-width: 540px) {
            .cr-card {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>

    <section class="cr-section">
        <div class="cr-wrapper">
            <div class="cr-header">
                <div class="cr-title-box">
                    <div class="cr-title-bar"></div>
                    <h2 class="cr-title">Customer Reviews</h2>
                </div>
                <div class="cr-header-actions">
                    <button type="button" class="cr-nav-btn" id="crPrevBtn" aria-label="Previous Reviews">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </button>
                    <button type="button" class="cr-nav-btn" id="crNextBtn" aria-label="Next Reviews">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </button>
                </div>
            </div>

            <div class="cr-carousel-track" id="crCarouselTrack">
                @foreach($customerReviews as $idx => $review)
                    @php
                        // Resolve product image
                        $pImg = $review->product_image;
                        if (empty($pImg) && $review->product && !empty($review->product->thumb_image)) {
                            $pImg = $review->product->thumb_image;
                        }
                        if (!empty($pImg)) {
                            if (str_starts_with($pImg, 'http://') || str_starts_with($pImg, 'https://')) {
                                $pImgUrl = $pImg;
                            } elseif (str_starts_with($pImg, 'uploads/') || str_starts_with($pImg, 'storage/')) {
                                $pImgUrl = asset($pImg);
                            } elseif (str_starts_with($pImg, 'clientside/')) {
                                $pImgUrl = asset($pImg);
                            } else {
                                $pImgUrl = asset('uploads/custom-images/' . $pImg);
                            }
                        } else {
                            $pImgUrl = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=150&auto=format&fit=crop&q=80';
                        }

                        // Resolve reviewer avatar
                        $rImg = $review->reviewer_image;
                        $reviewerName = !empty($review->reviewer_name) ? $review->reviewer_name : 'Customer';
                        if (!empty($rImg)) {
                            if (str_starts_with($rImg, 'http://') || str_starts_with($rImg, 'https://')) {
                                $rImgUrl = $rImg;
                            } elseif (str_starts_with($rImg, 'uploads/') || str_starts_with($rImg, 'storage/')) {
                                $rImgUrl = asset($rImg);
                            } else {
                                $rImgUrl = asset('storage/' . $rImg);
                            }
                        } else {
                            $rImgUrl = 'https://ui-avatars.com/api/?name=' . urlencode($reviewerName) . '&background=0284c7&color=fff&size=100';
                        }
                    @endphp
                    <div class="cr-card">
                        <div>
                            <div class="cr-card-top">
                                <div class="cr-user-info">
                                    <img src="{{ $rImgUrl }}" 
                                         alt="{{ $reviewerName }}" 
                                         class="cr-avatar" 
                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Customer&background=0284c7&color=fff';">
                                    <div class="cr-user-meta">
                                        <h4 class="cr-user-name">{{ $reviewerName }}</h4>
                                        <p class="cr-review-date">{{ $review->review_date ? $review->review_date->format('M d, Y') : 'Recent' }}</p>
                                    </div>
                                </div>
                                <div class="cr-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @elseif($i <= $review->rating + 0.5)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="cr-product-box">
                                <img src="{{ $pImgUrl }}"
                                     alt="{{ $review->product_name ?? 'Product' }}" 
                                     class="cr-product-thumb"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=150&auto=format&fit=crop&q=80';">
                                <span class="cr-product-name">{{ $review->product_name ?? 'Featured Item' }}</span>
                            </div>
                        </div>
                        <p class="cr-comment">"{{ $review->review_text }}"</p>
                    </div>
                @endforeach
            </div>

            <div class="cr-pagination-row">
                <button type="button" class="cr-nav-btn" id="crBottomPrevBtn" aria-label="Previous">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <div class="cr-dots-wrap" id="crDotsWrap"></div>
                <button type="button" class="cr-nav-btn" id="crBottomNextBtn" aria-label="Next">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('crCarouselTrack');
        const prevBtn = document.getElementById('crPrevBtn');
        const nextBtn = document.getElementById('crNextBtn');
        const bottomPrevBtn = document.getElementById('crBottomPrevBtn');
        const bottomNextBtn = document.getElementById('crBottomNextBtn');
        const dotsWrap = document.getElementById('crDotsWrap');

        if (!track) return;

        const cards = track.querySelectorAll('.cr-card');
        if (!cards.length) return;

        function getVisibleCount() {
            if (window.innerWidth <= 540) return 1;
            if (window.innerWidth <= 860) return 2;
            if (window.innerWidth <= 1200) return 3;
            return 4;
        }

        function createDots() {
            if (!dotsWrap) return;
            dotsWrap.innerHTML = '';
            const visible = getVisibleCount();
            const totalPages = Math.ceil(cards.length / visible);

            if (totalPages <= 1) {
                if (bottomPrevBtn) bottomPrevBtn.style.display = 'none';
                if (bottomNextBtn) bottomNextBtn.style.display = 'none';
                dotsWrap.style.display = 'none';
                return;
            } else {
                if (bottomPrevBtn) bottomPrevBtn.style.display = 'inline-flex';
                if (bottomNextBtn) bottomNextBtn.style.display = 'inline-flex';
                dotsWrap.style.display = 'flex';
            }

            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('span');
                dot.className = 'cr-dot' + (i === 0 ? ' active' : '');
                dot.dataset.page = i;
                dot.addEventListener('click', function() {
                    const scrollTarget = i * track.clientWidth;
                    track.scrollTo({ left: scrollTarget, behavior: 'smooth' });
                });
                dotsWrap.appendChild(dot);
            }
        }

        function updateActiveDot() {
            if (!dotsWrap) return;
            const scrollLeft = track.scrollLeft;
            const pageWidth = track.clientWidth;
            const pageIndex = Math.round(scrollLeft / pageWidth);
            const dots = dotsWrap.querySelectorAll('.cr-dot');
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === pageIndex);
            });
        }

        function scrollReviews(dir) {
            const card = track.querySelector('.cr-card');
            const step = card ? (card.offsetWidth + 16) : 300;
            const scrollAmount = step * getVisibleCount();
            const maxScroll = track.scrollWidth - track.clientWidth;

            if (dir === 1) {
                // Next / Right: if at or near end, wrap circularly to start
                if (track.scrollLeft >= maxScroll - 15) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    const nextPos = Math.min(track.scrollLeft + scrollAmount, maxScroll);
                    track.scrollTo({ left: nextPos, behavior: 'smooth' });
                }
            } else {
                // Prev / Left: if at or near start, wrap circularly to end
                if (track.scrollLeft <= 15) {
                    track.scrollTo({ left: maxScroll, behavior: 'smooth' });
                } else {
                    const prevPos = Math.max(track.scrollLeft - scrollAmount, 0);
                    track.scrollTo({ left: prevPos, behavior: 'smooth' });
                }
            }
        }

        if (prevBtn) prevBtn.addEventListener('click', () => scrollReviews(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => scrollReviews(1));
        if (bottomPrevBtn) bottomPrevBtn.addEventListener('click', () => scrollReviews(-1));
        if (bottomNextBtn) bottomNextBtn.addEventListener('click', () => scrollReviews(1));

        track.addEventListener('scroll', updateActiveDot, { passive: true });
        window.addEventListener('resize', createDots);
        createDots();
    });
    </script>
@endif
