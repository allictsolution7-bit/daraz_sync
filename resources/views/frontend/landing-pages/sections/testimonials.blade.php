@if ($section && $section->status && is_array($section->testimonials) && count($section->testimonials) > 0)
<style>
    .customer-reviews { padding: 20px 0px; background-color: #ffffff; }
    .reviews-container { max-width: 1200px; margin: 0 auto; }
    .reviews-title { text-align: center; font-size: 36px; color: var(--secondary-color); margin-bottom: 20px; }
    .testimonials-swiper { position: relative; padding: 15px 30px; }
    .testimonials-swiper .swiper-slide { height: auto; }
    .review-card { background-color: #fff; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05); text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center; }
    .review-image { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; object-fit: cover; }
    .review-text { color: #6d4c41; line-height: 1.6; margin-bottom: 15px; flex-grow: 1; }
    .review-author { font-weight: bold; color: var(--secondary-color); }
    .testimonials-swiper .swiper-button-next, .testimonials-swiper .swiper-button-prev { background: rgba(255, 255, 255, 0.9); border-radius: 50%; width: 34px; height: 34px; color: var(--secondary-color); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border: 1px solid rgba(0, 0, 0, 0.05); }
    .testimonials-swiper .swiper-button-next:hover, .testimonials-swiper .swiper-button-prev:hover { background: rgba(255, 255, 255, 1); box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); }
    .testimonials-swiper .swiper-button-next::after, .testimonials-swiper .swiper-button-prev::after { font-size: 16px; font-weight: 700; }
    @media (max-width: 768px) { .testimonials-swiper { padding: 10px 20px; } .review-card { padding: 20px; } .reviews-title { font-size: 28px; margin-bottom: 10px; } }
    @media (max-width: 480px) { .testimonials-swiper { padding: 10px 20px; } .review-card { padding: 15px; } .reviews-title { font-size: 19px; margin-bottom: 0px; background: #123257; color: #ffffff; padding: 10px; } .testimonials-swiper .swiper-button-next, .testimonials-swiper .swiper-button-prev { width: 35px; height: 35px; } .testimonials-swiper .swiper-button-next::after, .testimonials-swiper .swiper-button-prev::after { font-size: 16px; } }
</style>

<section class="customer-reviews">
    <div class="reviews-container">
        <h2 class="reviews-title">{{ $section->testimonials_title ?? 'গ্রাহক টেস্টিমোনিয়াল' }}</h2>
        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper">
                @foreach ($section->testimonials as $testimonial)
                    <div class="swiper-slide">
                        <div class="review-card">
                            @if (!empty($testimonial['image']))
                                <img src="{{ asset($testimonial['image']) }}"
                                    alt="{{ $testimonial['author'] ?? 'Customer' }}" class="review-image" loading="lazy">
                            @endif
                            @if (!empty($testimonial['text']))
                                <p class="review-text">{{ $testimonial['text'] }}</p>
                            @endif
                            @if (!empty($testimonial['author']))
                                <div class="review-author">{{ $testimonial['author'] }}</div>
                            @endif
                            @if (!empty($testimonial['rating']))
                                <div>
                                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                                        ⭐
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
@endif
