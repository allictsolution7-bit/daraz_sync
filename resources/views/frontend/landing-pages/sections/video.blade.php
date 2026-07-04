@if ($section && $section->status && $section->video_url)
<style>
    /* Video Section Styles */
    .video-section {
        padding: 30px 10px;
    }

    .video-section .container {
        max-width: 1340px;
        margin: 0 auto;
        padding: 0 10px;
    }

    .video-section .section-title {
        text-align: center;
        font-size: 36px;
        color: var(--secondary-color);
        font-family: inherit;
        margin-bottom: 20px;
    }

    .video-section .video-title {
        text-align: center;
        font-size: 28px;
        color: var(--primary-color);
        margin-bottom: 10px;
        font-family: inherit;
    }

    .video-section .video-description {
        text-align: center;
        font-size: 18px;
        color: #6d4c41;
        margin-bottom: 20px;
        font-family: inherit;
        line-height: 1.6;
    }

    .video-container {
        position: relative;
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .video-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Tablet Responsive (768px - 1024px) */
    @media (max-width: 1024px) and (min-width: 769px) {
        .video-section {
            padding: 25px 8px;
        }

        .video-section .container {
            max-width: 900px;
            padding: 0 20px;
        }

        .video-section .section-title {
            font-size: 32px;
            margin-bottom: 18px;
        }

        .video-section .video-title {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .video-section .video-description {
            font-size: 16px;
            margin-bottom: 18px;
        }

        .video-container {
            max-width: 700px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
    }

    /* Mobile Responsive (up to 768px) */
    @media (max-width: 768px) {
        .video-section {
            padding: 20px 5px;
        }

        .video-section .container {
            max-width: 100%;
            padding: 0 10px;
        }

        .video-section .section-title {
            font-size: 28px;
            margin-bottom: 15px;
            line-height: 1.5rem;
        }

        .video-section .video-title {
            font-size: 22px;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .video-section .video-description {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.5;
            padding: 0 5px;
        }

        .video-container {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .video-wrapper {
            padding-bottom: 56.25%;
            /* Maintain 16:9 aspect ratio */
        }
    }

    /* Small Mobile Responsive (up to 480px) */
    @media (max-width: 480px) {
        .video-section {
            padding: 15px 3px;
        }

        .video-section .container {
            padding: 0 8px;
        }

        .video-section .section-title {
            font-size: 24px;
            margin-bottom: 12px;
            line-height: 1.5rem;
        }

        .video-section .video-title {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .video-section .video-description {
            font-size: 14px;
            margin-bottom: 12px;
            padding: 0 3px;
        }

        .video-container {
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    }

    /* Extra Small Mobile Responsive (up to 360px) */
    @media (max-width: 360px) {
        .video-section {
            padding: 12px 2px;
        }

        .video-section .container {
            padding: 0 5px;
        }

        .video-section .section-title {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .video-section .video-title {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .video-section .video-description {
            font-size: 13px;
            margin-bottom: 10px;
            padding: 0 2px;
        }

        .video-container {
            border-radius: 6px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }
    }
</style>

@php
$videoUrl = $section->video_url;
$embedUrl = '';

// Convert various YouTube URL formats to embed URL
if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
$videoId = substr($videoUrl, strpos($videoUrl, 'v=') + 2);
$embedUrl = 'https://www.youtube.com/embed/' . $videoId;
} elseif (strpos($videoUrl, 'youtu.be/') !== false) {
$videoId = substr($videoUrl, strpos($videoUrl, 'youtu.be/') + 9);
$embedUrl = 'https://www.youtube.com/embed/' . $videoId;
} elseif (strpos($videoUrl, 'youtube.com/shorts/') !== false) {
$videoId = substr($videoUrl, strpos($videoUrl, 'shorts/') + 7);
if (strpos($videoId, '?') !== false) {
$videoId = substr($videoId, 0, strpos($videoId, '?'));
}
$embedUrl = 'https://www.youtube.com/embed/' . $videoId;
} elseif (strpos($videoUrl, 'youtube.com/embed/') !== false) {
$embedUrl = $videoUrl;
} else {
// If it's already an embed URL or unknown format, use as is
$embedUrl = $videoUrl;
}
@endphp

<section class="video-section">
    <div class="container">
        @if (!empty($section->video_title))
        <h2 class="section-title">
            {{ $section->video_title }}
        </h2>
        @endif

        @if (!empty($section->video_description))
        <p class="video-description">
            {{ $section->video_description }}
        </p>
        @endif

        <div class="video-container">
            <div class="video-wrapper">
                <iframe
                    data-src="{{ $embedUrl }}?autoplay=1&mute=1&controls=0&rel=0&modestbranding=1&showinfo=0&loop=1&playlist={{ substr($embedUrl, strpos($embedUrl, 'embed/') + 6) }}&enablejsapi=1&origin={{ url('/') }}&disablekb=1&fs=0&iv_load_policy=3"
                    allowfullscreen allow="autoplay; encrypted-media" style="pointer-events: auto;"
                    id="video-section-iframe-{{ $section->id }}" class="lazy-video">
                </iframe>
                <div class="unmute-overlay" onclick="unmuteVideo('video-section-iframe-{{ $section->id }}')">
                    <div class="unmute-button">
                        <div class="unmute-icon">🔇</div>
                        <div class="unmute-text">
                            <span class="unmute-title">Click to Unmute</span>
                            <span class="unmute-subtitle">Enable Sound 🔊</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="cta-global">
    <div class="product-cta">
        <a href="#order-section" class="btn primary-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                <path
                    d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            {{ $landingPage->order_button_text ?? 'এখনই কিনুন' }}
        </a>
    </div>
</div>
@endif