@if ($section && $section->status)
<section class="cta-section"
    style="background: {{ $section->cta_background_color ?? '#1D8758' }}; padding: 19px 20px; text-align: center; color: white;padding-top: 11px;">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title" style="font-size: 28px; margin-bottom: 10px; font-weight: 600;">
                {{ $section->cta_title ?? 'প্রয়োজনে কল করুন' }}</h2>
            @if ($section->cta_subtitle)
                <p class="cta-subtitle" style="font-size: 18px; margin-bottom: 25px; opacity: 0.9;">
                    {{ $section->cta_subtitle }}</p>
            @endif
            <a href="tel:{{ $section->cta_phone_number ?? '01611-109447' }}" class="cta-button"
                style="display: inline-flex; align-items: center; background: {{ $section->cta_button_color ?? '#dc3545' }}; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-size: 18px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                {{ $section->cta_button_text ?? 'কল করুন' }}
            </a>
        </div>
    </div>
</section>
@endif
