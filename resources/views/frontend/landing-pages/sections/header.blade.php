<style>
    /* Header Section Styles */
    #mainheader {
        background: #fff;
        padding: 4px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .header-logo img {
        max-height: 60px;
        width: auto;
        object-fit: contain;
        transition: all 0.2s ease;
    }

    .header-logo-link {
        display: inline-block;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .header-logo-link:hover img {
        transform: scale(1.02);
        filter: brightness(1.1);
    }

    .header-logo-link:hover {
        opacity: 0.9;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .header-btn {
        padding: 10px 20px;
        border-radius: 25px;
        text-decoration: none;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 190px;
    }

    .header-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    .header-btn.btn1 {
        background-color: #007bff;
    }

    .header-btn.btn2 {
        background-color: #dc3545;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .header-container {
            justify-content: space-between !important;
            padding: 0 5px;
        }

        .header-logo img {
            max-height: 50px;
        }

        .header-logo-link:hover img {
            transform: none;
            filter: none;
        }

        .header-actions {
            gap: 10px;
        }

        .header-btn {
            padding: 5px;
            min-width: 110px;
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .header-container {
            flex-direction: row;
            gap: 10px;
            text-align: center;
            padding: 0px 5px;
        }

        .footer-landing .header-container {
            flex-direction: row;
            gap: 5px;
            text-align: center;
        }

        .header-actions {
            flex-direction: row;
            gap: 8px;
        }

        .header-btn {
            width: 100%;
            min-width: 110px;
            max-width: 200px;
        }
    }
</style>

@if ($section && $section->status)
<section id="mainheader">
    <div class="header-container" style="justify-content: space-between;">
        @if ($section->header_logo)
            <div class="header-logo">
                <a href="{{ url('/') }}" class="header-logo-link" title="Go to Homepage">
                    <img src="{{ asset($section->header_logo) }}"
                        alt="{{ $section->header_logo_alt ?? ($landingPage->title ?? 'Logo') }}"
                        style="width: {{ $section->header_desktop_logo_width ?? 200 }}px; max-width: 100%;"
                        loading="lazy">
                </a>
            </div>
        @endif

        <div class="header-actions">
            @if ($section->header_button1_active)
                <a href="{{ $section->header_button1_url ?? 'tel:01717171717' }}" class="header-btn btn1"
                    style="background-color: {{ $section->header_button1_color ?? '#007bff' }}; color: {{ $section->header_button1_text_color ?? '#ffffff' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="margin-right: 10px;">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    {{ $section->header_button1_text ?? 'Call Now' }}
                </a>
            @endif

            @if ($section->header_button2_active)
                <a href="{{ $section->header_button2_url ?? '#order-section' }}"
                    class="header-btn btn2 primary-btn"
                    style="background-color: {{ $section->header_button2_color ?? '#dc3545' }}; color: {{ $section->header_button2_text_color ?? '#ffffff' }};">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                        <path
                            d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    {{ $section->header_button2_text ?? 'এখনই কিনুন' }}
                </a>
            @endif
        </div>
    </div>
</section>
@endif
