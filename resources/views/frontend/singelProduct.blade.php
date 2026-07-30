@extends('frontend.app')

@section('styles')
<link rel="preload" as="image" href="{{ asset('storage/' . $product->thumb_image) }}">
<link rel="stylesheet" href="{{ asset('css/combo-offer.css') }}">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary": "#ffffff",
                        "surface-dim": "#dbdad9",
                        "charcoal": "#222222",
                        "error-ruby": "#EF4444",
                        "surface-container-low": "#f5f3f3",
                        "outline-variant": "#c3c6cf",
                        "inverse-primary": "#abc8f5",
                        "background": "#fbf9f8",
                        "navy-deep": "#113257",
                        "on-tertiary": "#ffffff",
                        "on-primary-container": "#7e9bc6",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#abc8f5",
                        "on-tertiary-fixed": "#1a1c1c",
                        "surface-container": "#efeded",
                        "on-primary-fixed": "#001c39",
                        "surface": "#fbf9f8",
                        "surface-container-highest": "#e4e2e2",
                        "on-tertiary-fixed-variant": "#454747",
                        "tertiary-fixed-dim": "#c6c6c7",
                        "surface-bright": "#fbf9f8",
                        "on-error": "#ffffff",
                        "primary-container": "#113257",
                        "surface-tint": "#436087",
                        "secondary": "#904d00",
                        "tertiary": "#1b1d1d",
                        "primary-fixed": "#d4e3ff",
                        "on-surface-variant": "#43474e",
                        "secondary-fixed": "#ffdcc3",
                        "surface-container-high": "#eae8e7",
                        "error": "#ba1a1a",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed": "#2f1500",
                        "surface-off-white": "#F9FAFB",
                        "energy-orange": "#F68C20",
                        "tertiary-fixed": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-secondary-fixed-variant": "#6e3900",
                        "outline": "#74777f",
                        "on-tertiary-container": "#999a9a",
                        "inverse-on-surface": "#f2f0f0",
                        "on-secondary-container": "#663500",
                        "secondary-container": "#ff9328",
                        "secondary-fixed-dim": "#ffb77d",
                        "on-surface": "#1b1c1c",
                        "inverse-surface": "#303030",
                        "tertiary-container": "#303232",
                        "surface-variant": "#e4e2e2",
                        "on-primary-fixed-variant": "#2a486e",
                        "success-emerald": "#10B981",
                        "primary": "#001d3b",
                        "on-background": "#1b1c1c"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "container-max": "1280px",
                        "gutter": "24px",
                        "stack-md": "16px",
                        "stack-lg": "32px",
                        "margin-mobile": "16px",
                        "margin-desktop": "40px",
                        "stack-sm": "8px"
                    },
                    fontFamily: {
                        "body": ["Inter", "sans-serif"],
                        "headline": ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
<style>
        .zoom { overflow: hidden; cursor: crosshair; }
        .zoom img { transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        .zoom:hover img { transform: scale(1.2); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .tab-active { border-bottom: 2px solid #113257; color: #113257; }

    .pcontainer {
        margin: 1rem auto;
        padding: 1rem;
        background: var(--body-bg);
        margin-top: 0;
        padding-top: 0;
    }


    .description {
        width: 100%;
        max-width: 1340px;
        margin: 3rem auto;
        padding: 2.5rem;
        background-color: var(--body-bg);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
    }

    .product-images {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        justify-content: space-between
    }

    .w-80 {
        width: 100%;
    }

    .w-100 {
        width: 100%;
    }

    .product-images img {
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .image-gallery {
        display: flex;
        gap: 8px;
        width: 100%;
        overflow-x: hidden;
        scroll-behavior: smooth;
        cursor: grab;
        user-select: none;
        /* Prevent text selection while dragging */
    }

    .image-gallery:active {
        cursor: grabbing;
    }


    .mainimage img {
        width: 100%;
        height: auto;
        /*   */
    }


    .product-gallery-image:hover {
        border-color: var(--secondary-color) !important;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.15);
    }

    /* Variation options styling */
    .variation {
        margin-top: 12px;
    }

    .variation h4 {
        font-size: 1.1rem;
        color: #000000;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .variation .flex {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .option-btn {
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        background-color: var(--light-color);
        border: 2px solid var(--border-color);
        color: #334155;
    }

    .option-btn:hover {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: var(--secondary-color);
        transform: translateY(-2px);
    }

    /* Add styles for selected option */
    .option-btn.selected {
        position: relative;
        background-color: var(--secondary-color);
        position: relative;
        color: #fff;
        border: 2px solid var(--secondary-color);
        font-weight: normal;
        box-shadow: 0 0 0 2px #fff;
        z-index: 1;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    /* Daraz variation option styling */
    .variation-option-btn {
        border: 1px solid #dadada !important;
        background: #fff !important;
        color: #212121 !important;
        border-radius: 2px !important;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .variation-option-btn:hover {
        border-color: #f57224 !important;
    }

    .variation-option-btn.selected {
        border: 2px solid #f57224 !important;
        position: relative !important;
    }

    .variation-option-btn.selected::after {
        content: "✓" !important;
        position: absolute !important;
        bottom: -1px !important;
        right: -1px !important;
        background: #f57224 !important;
        color: #fff !important;
        font-size: 8px !important;
        font-weight: bold !important;
        width: 12px !important;
        height: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-top-left-radius: 3px !important;
        top: auto !important;
        box-shadow: none !important;
        transform: none !important;
        border-radius: 0 !important;
    }

    /* Quantity and buttons */
    .sharedQuantityarea {
        margin: 7px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
    }

    .sharedQuantityarea .quanity {
        padding: 9px 0px;
        text-align: center;
        background: var(--light-color);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .sharedQuantityarea button {
        color: #fff;
    }

    .sharedQuantityarea input[type="number"] {
        padding: 0.835rem !important;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        width: 5rem !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        color: var(--text-color);
    }

    .sharedQuantityarea input[type="number"]:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        outline: none;
    }

    .read-more-button {
        border: 1px solid #3b82f6;
        border-radius: 7px;
        padding: 0.875rem 0.5rem;
        border-radius: 8px;
        width: 100%;
        border-radius: 8px;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #424242;
        color: #fff;
    }

    form#cartForm {
        width: 36%;
    }

    form#buyNowForm {
        width: 52%;
    }

    .single-cart-btn {
        padding: 0.875rem 0.7rem;
        border-radius: 8px;
        font-weight: 400;
        width: 100%;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        color: #fff;
        /* background: linear-gradient(135deg, #4f46e5, #3b82f6); */
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .single-cart-btn:hover {
        transform: translateY(-3px);
        background: linear-gradient(135deg, #4338ca, #2563eb);
    }

    .single-buynow-btn {
        padding: 0.875rem 0.6rem;
        border-radius: 8px;
        width: 100%;
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        color: #fff;
        border-radius: 8px;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .single-buynow-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    /* Contact Actions Buttons */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        gap: 0.4rem;
        margin-top: 0rem;
    }

    .action-buttons a {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        width: 98%;
        padding: 14px 25px;
    }

    .action-buttons a i {
        font-size: 1.1rem;
    }

    /* First Button (Green) */
    .action-buttons .whatsapp_action_button {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border-radius: 7px;
        margin-top: 10px;
    }

    .action-buttons .whatsapp_action_button:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2);
    }

    /* Second Button (Blue) */
    .action-buttons .phonecall_action_button {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #fff;
        border-radius: 7px;
        margin-left: 2px;
        margin-top: 10px;
    }

    .action-buttons .phonecall_action_button:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(14, 165, 233, 0.2);
    }


    /* Info area */
    .info-area {
        padding: 0rem;
        font-size: 14px;
    }

    .info-area .info {
        padding: 1.75rem;
        border: 2px dashed var(--secondary-color);
        border-radius: 12px;
        background-color: var(--light-color);
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.08);
    }

    .info-area p {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 6px;
        line-height: 1.6;
        color: var(--text-color);
    }

    .info-area i {
        color: var(--text-color);
        font-size: 1.1rem;
        margin-top: 0.125rem;
    }

    .info-area iframe {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        border: none;
    }

    /* Price area */
    .price-area {
        border-bottom: 1px solid var(--border-color);
    }

    .pricearea {
        display: flex;
        align-items: center;
        gap: 0rem;
        font-size: 1.5rem;
    }

    .oldprice {
        color: #ef4444;
        text-decoration: line-through;
        font-size: 1.5rem;
        opacity: 0.8;
        margin-right: 10px;
        ;
    }

    .text-red {
        color: #ef4444;
        text-decoration: line-through;
        font-size: 1.1rem;
        opacity: 0.8;
    }

    #updateOfferPrice {
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        /* background: linear-gradient(to right, #1e40af, #3b82f6); */
        /* -webkit-background-clip: text; */
        /* -webkit-text-fill-color: transparent; */
    }

    .discount-badge {
        display: inline-block;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .success-container {
        max-width: 1340px;
        margin: 1rem auto;
        padding: 0 1rem;
    }

    .success-container .text-red {
        background-color: #f0fdf4;
        border-left: 4px solid #22c55e;
        color: #166534;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
    }


    /* Contact buttons */
    .contact-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .contact-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        color: white;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        letter-spacing: 0.5px;
    }

    .contact-btn i {
        font-size: 1.25rem;
    }

    .whatsapp-btn {
        background: linear-gradient(135deg, #25D366, #128C7E);
        border: 1px solid #128C7E;
    }

    .whatsapp-btn:hover {
        background: linear-gradient(135deg, #128C7E, #075E54);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(18, 140, 126, 0.3);
    }

    .call-btn {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border: 1px solid #0284c7;
    }

    .call-btn:hover {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(14, 165, 233, 0.3);
    }


    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .pcontainer {
            gap: 1.0rem;
        }

        .image-area,
        .variation-cart-area,
        .info-area {
            width: 30%;
        }

        .success-container {
            padding: 0 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .success-container {
            padding: 0 1rem;
            margin: 0.75rem auto;
        }

        .success-container .text-red {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }

        .pcontainer {
            padding: 0 5px;
            margin: 0 0px;
            flex-direction: column;
            gap: 0.5rem;
        }

        .image-area,
        .variation-cart-area,
        .info-area {
            width: 100%;
        }

        .product-title {
            font-size: 20px;
        }

        #updateOfferPrice {
            font-size: 1.45rem;
        }

        .variation .flex {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .product-images {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.4rem;
        }

        .mainimage img {
            max-width: 100%;
        }

        .image-gallery {
            display: flex;
            gap: 0;
            width: 100%;
        }

        .sharedQuantityarea {
            flex-wrap: wrap;
        }

        .pcontainer button[type="submit"] {
            width: 100%;
            margin-top: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons a {
            width: 100% !important;
            margin-left: 0 !important;
        }

        form#cartForm {
            width: 73%;
            margin-top: -11px;
        }

        form#buyNowForm {
            width: 100%;
        }
    }
</style>

<style>
    /* Short Description Truncation */
    .short-desc-content {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .short-desc-content.expanded {
        display: block;
        -webkit-line-clamp: unset;
    }

    .read-more-btn {
        color: var(--web-primary-color, #007bff);
        cursor: pointer;
        font-weight: 600;
        display: none;
        margin-top: 5px;
        text-decoration: none;
    }

    .read-more-btn:hover {
        text-decoration: underline;
    }

    /* Social Share */
    .social-share {
        display: flex;
        align-items: center;
        margin-top: 1.5rem;
        gap: 1rem;
    }

    .social-share h6 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .share-icons {
        display: flex;
        gap: 0.75rem;
    }

    .share-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.0rem;
        height: 2.0rem;
        border-radius: 50%;
        background-color: var(--light-color);
        color: var(--dark-color);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .share-icon i {
        font-size: 1rem;
    }

    .share-icon:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .share-icon.facebook:hover {
        background-color: #1877f2;
        color: white;
    }

    .share-icon.twitter:hover {
        background-color: #1da1f2;
        color: white;
    }

    .share-icon.pinterest:hover {
        background-color: #e60023;
        color: white;
    }

    .share-icon.whatsapp:hover {
        background-color: #25D366;
        color: white;
    }

    .share-icon.instagram:hover {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        color: white;
    }

    .share-icon.print:hover {
        background-color: #4b5563;
        color: white;
    }

    @media (max-width: 768px) {

        .social-share {
            margin-top: 1.25rem;
            justify-content: center;
        }

        .share-icon {
            width: 2.25rem;
            height: 2.25rem;
        }
    }

    @media (max-width: 480px) {
        .social-share {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .share-icons {
            width: 100%;
            justify-content: start;
        }
    }

    .image-gallery {
        display: flex;
    }
</style>

<style>
    /* Ratings */
    .ratings {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .stars {
        display: flex;
        margin-right: 8px;
    }

    .stars i {
        color: #f59e0b;
        font-size: 1rem;
        margin-right: 2px;
    }

    .rating-count {
        color: var(--text-light);
        font-size: 0.9rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .ratings {
            margin-bottom: 10px;
        }

        .stars i {
            font-size: 0.9rem;
        }

        .rating-count {
            font-size: 0.8rem;
        }
    }
</style>

<style>
    /* Breadcrumb Navigation */
    .breadcrumb-container {
        padding: 0.75rem 1rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
    }

    .breadcrumb a:hover {
        color: #2563eb;
        text-decoration: underline;
    }

    .breadcrumb .separator {
        margin: 0 0.5rem;
        color: #9ca3af;
        font-size: 0.75rem;
    }

    .breadcrumb .current {
        font-weight: 500;
        color: #374151;
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .breadcrumb i {
        margin-right: 0.25rem;
    }

    @media (max-width: 768px) {
        .breadcrumb-container {
            padding: 0.5rem 1rem;
        }

        .breadcrumb {
            font-size: 0.8rem;
        }

        .breadcrumb .current {
            max-width: 200px;
        }
    }
</style>

<style>
    /* Product Gallery Slider */
    .image-gallery-container {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .image-gallery {
        display: flex;
        gap: 8px;
        width: 100%;
        overflow-x: hidden;
        scroll-behavior: smooth;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .image-gallery::-webkit-scrollbar {
        display: none;
    }

    .product-gallery-image {
        min-width: 80px;
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        pointer-events: auto;
        /* Ensure images can be clicked */
    }

    .product-gallery-image.active {
        border-color: #3b82f6 !important;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }

    .gallery-nav {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #e5e7eb;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .gallery-nav:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }

    .prev-btn {
        left: -15px;
    }

    .next-btn {
        right: -15px;
    }

    .gallery-nav.hidden {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .product-gallery-image {
            min-width: 20%;
            min-width: 20%;
            height: auto;
        }

        .gallery-nav {
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }
    }
</style>

<style>
    /* Product Meta Information */
    .product-meta {
        padding-top: 0px;
    }

    .meta {
        display: flex;
        justify-content: flex-start;
    }

    .meta-item {
        margin-top: 10px;
    }

    .meta-item {
        display: flex;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .meta-label {
        font-weight: 600;
        color: #374151;
        width: auto;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: -5px;
    }

    .meta-value {
        color: #4b5563;
    }

    .meta-value.in-stock {
        margin-left: 3px;
        color: #10b981;
        font-weight: 500;
    }

    .sku {
        margin-right: 5px;
    }

    .meta-label i {
        color: #3b82f6;
        font-size: 1rem;
    }

    /* Add responsive styles for the product meta section */
    @media (max-width: 768px) {

        .meta {
            display: flow;
            flex-direction: column;
        }

        .meta-item {
            font-size: 0.9rem;
        }

        .meta-label {
            width: auto;
            margin-left: -5px;
        }
    }

    .mainimage {
        width: 100%;
        height: auto;
        /*   */
    }
</style>

<style>
    /* Product Tabs */
    .product-tab {
        max-width: 1340px;
        margin: 1rem auto 4rem;
        padding: 0 1rem;
    }

    /* Hide empty variation description */
    .variation-description:empty {
        display: none !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
    }

    /* Add styles for variation descriptions */
    .variation-description {
        margin-top: 15px;
        padding: 10px 0;
    }

    /* All variations info styling */
    .all-variations-info {
        margin-bottom: 30px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .all-variations-info h4 {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #dee2e6;
    }

    .variation-info-block {
        margin-bottom: 20px;
    }

    .variation-info-block h5 {
        font-size: 16px;
        color: #495057;
        margin-bottom: 10px;
        font-weight: 600;
    }




    .product-sections {
        margin: 3rem auto 0;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 0 0;
        margin-bottom: 20px;
    }

    .section-navigation {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        background-color: #f8fafc;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .section-nav-button {
        padding: 1.25rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .section-nav-button:hover {
        color: #3b82f6;
    }

    .section-nav-button.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        background-color: #fff;
    }

    .product-description-section,
    .product-ratings-section {
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .product-description-section {
        border-bottom: 1px solid #e5e7eb;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-sections {
            margin: 2rem 0.5rem 0;
            padding: 0 0.5rem;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .section-navigation {
            flex-direction: column;
            position: relative;
        }

        .section-nav-button {
            padding: 1rem;
            font-size: 0.9rem;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-nav-button.active {
            border-bottom-color: #3b82f6;
        }

        .product-description-section,
        .product-ratings-section {
            padding: 1.5rem 1rem;
            margin-bottom: 1rem;
        }

        .product-description h3,
        .product-ratings h3 {
            display: none;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .rating-summary {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .average-rating {
            min-width: auto;
        }

        .rating-bars {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .product-sections {
            margin: 1.5rem 0.25rem 0;
            padding: 0 0.25rem;
            margin-bottom: 15px;
        }

        .section-nav-button {
            padding: 0.75rem;
            font-size: 0.85rem;
        }

        .product-description-section,
        .product-ratings-section {
            padding: 1rem 0.5rem;
        }

        .product-description h3,
        .product-ratings h3 {
            font-size: 1.1rem;
        }
    }



    /* Description Section */
    .product-description h3,
    .product-ratings h3 {
        display: none;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .description-content {
        color: #4b5563;
        line-height: 1.7;
        font-size: 1rem;
    }


    .description-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem 0;
    }

    .description-content p {
        margin-bottom: 1rem;
    }

    /* Ratings Section */
    .rating-summary {
        display: flex;
        gap: 3rem;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .average-rating {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 180px;
    }

    .rating-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 0rem;
    }

    .average-rating .stars {
        margin-bottom: 0.5rem;
    }

    .average-rating .stars i {
        color: #f59e0b;
        font-size: 1.25rem;
        margin-right: 0.25rem;
    }

    .total-reviews {
        color: #64748b;
        font-size: 0.9rem;
    }

    .rating-bars {
        flex: 1;
    }

    .rating-bar-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .rating-label {
        width: 50px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: #64748b;
        font-size: 0.9rem;
    }

    .rating-label i {
        color: #f59e0b;
        font-size: 0.8rem;
    }

    .rating-bar {
        flex: 1;
        height: 8px;
        background-color: #e5e7eb;
        border-radius: 4px;
        margin: 0 1rem;
        overflow: hidden;
    }

    .rating-fill {
        height: 100%;
        background: linear-gradient(to right, #f59e0b, #fbbf24);
        border-radius: 4px;
    }

    .rating-count {
        width: 30px;
        text-align: right;
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Customer Reviews */
    .customer-reviews {
        margin-top: 2rem;
    }

    .review-item {
        padding: 1.5rem;
        border-radius: 8px;
        background-color: #f8fafc;
        margin-bottom: 1.5rem;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        position: relative;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .reviewer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .verified-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: #3b82f6;
        color: white;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid white;
    }

    .reviewer-details {
        display: flex;
        flex-direction: column;
    }

    .reviewer-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .review-date {
        font-size: 0.85rem;
        color: #64748b;
    }

    .review-rating i {
        color: #f59e0b;
        font-size: 0.9rem;
        margin-right: 0.1rem;
    }

    .review-content p {
        color: #4b5563;
        line-height: 1.6;
    }

    .review-images {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }

    .review-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .review-image:hover {
        transform: scale(1.05);
    }

    .no-reviews {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
        font-style: italic;
    }

    /* SVG Star Icons */
    .star-icon {
        margin-right: 2px;
    }

    .star-icon.filled {
        fill: #f59e0b;
    }

    .star-icon.empty {
        fill: #e5e7eb;
    }

    .star-icon.half-filled {
        fill: url(#halfStar);
    }

    .star-icon.small {
        width: 16px;
        height: 16px;
    }

    .review-rating .star-icon {
        margin-right: 2px;
    }

    .stars .star-icon {
        margin-right: 2px;
    }

    .reviewer-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .review-date {
        font-size: 0.85rem;
        color: #64748b;
    }

    .review-rating i {
        color: #f59e0b;
        font-size: 0.9rem;
        margin-right: 0.1rem;
    }

    .review-content p {
        color: #4b5563;
        line-height: 1.6;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .description-content img {
            width: 100%;
            object-fit: contain;
        }

        .tab-button {
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
        }

        .tab-content {
            padding: 1.5rem;
        }

        .rating-summary {
            flex-direction: column;
            gap: 1.5rem;
        }

        .average-rating {
            min-width: auto;
        }
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .tab-button {
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
        }

        .tab-content {
            padding: 1.5rem;
        }

        .rating-summary {
            flex-direction: column;
            gap: 1.5rem;
        }

        .average-rating {
            min-width: auto;
        }
    }

    /* Review Form Styles */
    .review-form-container {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e7eb;
    }

    .review-form-container h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    .review-form {
        background-color: #f8fafc;
        padding: 2rem;
        border-radius: 8px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group.half {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
    }

    .form-group .required {
        color: #ef4444;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .submit-review-btn {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .submit-review-btn:hover {
        background-color: #2563eb;
        transform: translateY(-2px);
    }

    /* Star Rating */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        font-size: 1.75rem;
        color: #d1d5db;
        transition: color 0.2s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #f59e0b;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 1rem;
        }

        .review-form {
            padding: 1.5rem;
        }
    }
</style>

{{-- Buy Now button animation --}}
<style>
    /* buy animation */

    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        11.11% {
            transform: translateX(-5px);
        }

        22.22% {
            transform: translateX(5px);
        }

        33.33% {
            transform: translateX(-5px);
        }

        44.44% {
            transform: translateX(5px);
        }

        55.55% {
            transform: translateX(-5px);
        }

        66.66% {
            transform: translateX(5px);
        }

        77.77% {
            transform: translateX(-5px);
        }

        88.88% {
            transform: translateX(5px);
        }

        100% {
            transform: translateX(0);
        }
    }

    /* Target your button by its existing classes */
    .single-buynow-btn {}
</style>
@endsection

@section('content')

{{-- Success Message --}}
@if (session('success'))
<div class="success-container">
    <div class="text-red">
        {{ session('success') }}
    </div>
</div>
@endif

{{-- Error Message show --}}
@if ($errors->any())
<div class="alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li class="text-red">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Breadcrumb Navigation --}}
<div class="base-container breadcrumb-container">
    <div class="breadcrumb">
        <a href="{{ route('index') }}"><i class="fa-solid fa-house"></i> Home</a>
        <span class="separator">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
            </svg>
        </span>
        @if (isset($product->category) && $product->category)
        <a href="{{ url('shop/' . $product->category->slug) }}">{{ $product->category->name }}</a>
        <span class="separator">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
            </svg>
        </span>
        @endif
        <span class="current">{{ $product->title }}</span>
    </div>
</div>

{{-- Product Container --}}
<main class="py-12 bg-surface font-body text-on-surface">
<div class="max-w-container-max mx-auto px-margin-desktop space-y-16">
<!-- Top Section: 2-Column Grid -->
<div class="grid grid-cols-1 lg:grid-cols-[48%_48%] justify-between gap-gutter pcontainer" data-product-id="{{ $product->id }}" data-category="{{ $product->category->name ?? ($product->additionalCategories->first()->name ?? ($product->getAllCategories()->first()->name ?? '')) }}">
<!-- Left Column: Image Gallery -->
<div class="space-y-6 image-area">
<div class="product-images">
@php
// 1. Build variations array
$variations = [];
if ($product->product_type === 'variable' && $product->variationCombinations) {
    foreach ($product->variationCombinations as $combination) {
        $comboOptions = is_array($combination->variation_options)
            ? $combination->variation_options
            : json_decode($combination->variation_options, true);
        if (!is_array($comboOptions)) continue;

        foreach ($comboOptions as $optionId) {
            $option = \App\Models\VariationOption::find($optionId);
            if ($option && $option->variation) {
                $vId = $option->variation->id;
                $vName = $option->variation->name;
                if (!isset($variations[$vId])) {
                    $variations[$vId] = [
                        'id' => $vId,
                        'name' => $vName,
                        'options' => [],
                    ];
                }
                if (!isset($variations[$vId]['options'][$optionId])) {
                    $variations[$vId]['options'][$optionId] = [
                        'id' => $optionId,
                        'name' => $option->name,
                        'description' => $option->description ?? '',
                        'featured_image' => $option->featured_image ?? '',
                        'images' => $option->images ?? '',
                    ];
                }
            }
        }
    }
}

// 2. Build productImages & imageOptionMap from variation options & combinations
$productImages = [];
$imageOptionMap = []; // image_path => [optionId1, optionId2, ...]
$seenFilenames = [];

if ($product->product_type === 'variable') {
    // Collect from variation options (e.g. Red, Pink, Black option images)
    foreach ($variations as $var) {
        foreach ($var['options'] as $opt) {
            $optId = (int)$opt['id'];
            if (!empty($opt['featured_image'])) {
                $fImg = trim($opt['featured_image']);
                $fn = strtolower(basename($fImg));
                if (!in_array($fn, $seenFilenames)) {
                    $seenFilenames[] = $fn;
                    $productImages[] = $fImg;
                }
                if (!isset($imageOptionMap[$fImg])) {
                    $imageOptionMap[$fImg] = [];
                }
                if (!in_array($optId, $imageOptionMap[$fImg])) {
                    $imageOptionMap[$fImg][] = $optId;
                }
            }
        }
    }

    // Collect from combinations
    if ($product->variationCombinations) {
        foreach ($product->variationCombinations as $combination) {
            $comboOptions = is_array($combination->variation_options)
                ? $combination->variation_options
                : json_decode($combination->variation_options, true);
            $comboOptions = array_map('intval', (array)($comboOptions ?? []));

            if (!empty($combination->featured_image)) {
                $cImg = trim($combination->featured_image);
                $fn = strtolower(basename($cImg));
                if (!in_array($fn, $seenFilenames)) {
                    $seenFilenames[] = $fn;
                    $productImages[] = $cImg;
                }
                if (!isset($imageOptionMap[$cImg])) {
                    $imageOptionMap[$cImg] = [];
                }
                $imageOptionMap[$cImg] = array_unique(array_merge(
                    $imageOptionMap[$cImg], $comboOptions
                ));
            }

            $combGallery = $combination->gallery_images;
            if (is_string($combGallery)) {
                $combGallery = json_decode($combGallery, true);
            }
            if (is_array($combGallery)) {
                foreach ($combGallery as $cgImg) {
                    if (!empty($cgImg)) {
                        $gImg = trim($cgImg);
                        $fn = strtolower(basename($gImg));
                        if (!in_array($fn, $seenFilenames)) {
                            $seenFilenames[] = $fn;
                            $productImages[] = $gImg;
                        }
                        if (!isset($imageOptionMap[$gImg])) {
                            $imageOptionMap[$gImg] = [];
                        }
                        $imageOptionMap[$gImg] = array_unique(array_merge(
                            $imageOptionMap[$gImg], $comboOptions
                        ));
                    }
                }
            }
        }
    }
}

// 3. Fallback if no variation images collected
if (empty($productImages)) {
    $mainImgs = $product->images ?? [];
    if (is_string($mainImgs)) {
        $mainImgs = json_decode($mainImgs, true) ?? [];
    }
    $productImages = is_array($mainImgs) ? $mainImgs : [];
    if ($product->thumb_image && !in_array($product->thumb_image, $productImages)) {
        array_unshift($productImages, $product->thumb_image);
    }
}
@endphp

            <style>
                figure.zoom {
                    background-position: 50% 50%;
                    position: relative;
                    overflow: hidden;
                    cursor: zoom-in;
                }

                figure.zoom img:hover {
                    opacity: 0;
                }

                figure.zoom img {
                    transition: opacity .5s;
                    display: block;
                    width: 100%;
                }

                .image-gallery-container {
                    position: relative;
                    display: flex;
                    align-items: center;
                    width: 100%;
                    margin-top: 12px;
                    padding: 0 40px;
                }

                .image-gallery {
                    display: flex;
                    align-items: center;
                    justify-content: flex-start;
                    gap: 12px;
                    overflow-x: auto;
                    scroll-behavior: smooth;
                    flex: 1;
                    padding: 4px 6px;
                }

                .product-gallery-image {
                    width: 72px;
                    height: 72px;
                    object-fit: cover;
                    border-radius: 12px;
                    border: 2px solid #e2e8f0;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    flex-shrink: 0;
                }

                .product-gallery-image.active,
                .product-gallery-image:hover {
                    border-color: #2563eb !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
                }

                .gallery-nav {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    z-index: 10;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background: #ffffff;
                    border: 1px solid #cbd5e1;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    color: #475569;
                    transition: all 0.2s ease;
                    flex-shrink: 0;
                }

                .gallery-nav.prev-btn {
                    left: 2px;
                }

                .gallery-nav.next-btn {
                    right: 2px;
                }

                .gallery-nav:hover {
                    background: #ffffff;
                    color: #1e293b;
                    border-color: #2563eb;
                    transform: translateY(-50%) scale(1.1);
                    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
                }
            </style>

            <div class="mainimage aspect-square max-h-[420px] w-full bg-white rounded-3xl overflow-hidden border border-surface-container shadow-sm flex items-center justify-center" style="position:relative;">
                @php
                $percentOff = null;
                if (
                isset($product->old_price) &&
                isset($product->offer) &&
                $product->old_price > 0 &&
                $product->offer < $product->old_price
                    ) {
                    $percentOff = round((($product->old_price - $product->offer) / $product->old_price) * 100);
                }
                @endphp
                @if ($percentOff)
                    <div class="starburst-badge z-10" style="position:absolute;top:10px;left:10px;">
                        {{ $percentOff }}%<br>ছাড়
                    </div>
                @endif
                    <figure class="zoom w-full h-full" onmousemove="zoom(event)"
                        style="background-image: url('{{ asset('storage/' . $product->thumb_image) }}')">
                        <img id="main-product-image" class="w-full h-full object-cover" src="{{ asset('storage/' . $product->thumb_image) }}"
                            alt="{{ $product->title }}">
                    </figure>
            </div>

            @if ($productImages && is_array($productImages) && count($productImages) > 0)
            <div class="image-gallery-container">
                <button class="gallery-nav prev-btn" type="button" title="Previous Image">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                    </svg>
                </button>
                <div class="image-gallery scrollbar-hide" id="product-gallery" data-gallery-images='@json($productImages)'>
                    @foreach ($productImages as $index => $img)
                    @php $optIds = $imageOptionMap[$img] ?? []; @endphp
                    <img class="product-gallery-image {{ $index === 0 ? 'active' : '' }}"
                        src="{{ asset('storage/' . $img) }}" alt="{{ $product->title }}"
                        data-option-ids='@json($optIds)'
                        data-img-src="{{ $img }}"
                        onclick="changeMainImage(this, '{{ asset('storage/' . $img) }}')">  
                    @endforeach
                </div>
                <button class="gallery-nav next-btn" type="button" title="Next Image">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" /></svg>
                </button>
            </div>
            @endif
            <script>
                function changeMainImage(thumb, src) {
                    const mainImg = document.getElementById('main-product-image');
                    if (mainImg) mainImg.src = src;
                    const zoomFig = mainImg ? mainImg.closest('figure.zoom') : null;
                    if (zoomFig) zoomFig.style.backgroundImage = `url('${src}')`;
                    document.querySelectorAll('.product-gallery-image').forEach(img => img.classList.remove('active'));
                    if (thumb) thumb.classList.add('active');

                    // Auto-select matching variation option button (Color group)
                    if (thumb) {
                        let tOptIds = [];
                        try {
                            const raw = thumb.dataset.optionIds;
                            if (raw) tOptIds = JSON.parse(raw).map(o => parseInt(o));
                        } catch(e) {}

                        let imgSrc = thumb.dataset.imgSrc;
                        let matchingBtn = null;

                        // Try matching by option IDs — look for a color button (non-size) in tOptIds
                        if (tOptIds.length > 0) {
                            tOptIds.forEach(oid => {
                                if (!matchingBtn) {
                                    const btn = document.querySelector(`.variation-option-btn[data-option-id="${oid}"]`);
                                    if (btn) {
                                        // Only match color buttons (second variation group)
                                        const allGroups = document.querySelectorAll('.variation-group');
                                        if (allGroups.length > 1) {
                                            if (allGroups[1].contains(btn)) matchingBtn = btn;
                                        } else {
                                            matchingBtn = btn;
                                        }
                                    }
                                }
                            });
                        }
                        // Fallback: match by thumb image src
                        if (!matchingBtn && imgSrc) {
                            document.querySelectorAll('.variation-option-btn').forEach(btn => {
                                let thumbImg = btn.dataset.thumbImage;
                                if (thumbImg && (thumbImg === imgSrc || src.includes(thumbImg))) {
                                    matchingBtn = btn;
                                }
                            });
                        }

                        if (matchingBtn && !matchingBtn.classList.contains('selected')) {
                            matchingBtn.click();
                        }
                    }
                }
            </script>
        </div>
    </div>

    {{-- Right Column: Product Details --}}
    <div class="space-y-4 variation-cart-area">



        <div class="space-y-2">
            {{-- Title --}}
            <h1 class="text-xl font-normal text-[#212121] leading-snug product-title">{{ $product->title }}</h1>

            {{-- Ratings, Category & Wishlist Row --}}
            <div class="flex items-center justify-between text-xs py-1 my-1 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    @php
                    $revCount = isset($reviewStats['review_count']) ? $reviewStats['review_count'] : 0;
                    $revAvg = isset($reviewStats['average_rating']) ? $reviewStats['average_rating'] : 0;
                    @endphp
                    @if (setting('single_product', 'enable_rating_summary', '1') == '1' && $revCount > 0)
                    <div class="flex items-center gap-1.5 cursor-pointer" onclick="scrollToReviews()">
                        <div class="flex text-[#f5a623]">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $revAvg)
                                <i class="fa-solid fa-star text-[11px]"></i>
                                @else
                                <i class="fa-regular fa-star text-gray-300 text-[11px]"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-xs text-[#1a9cb7] hover:underline font-medium">Ratings {{ $revCount }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    @endif

                    @if ($product->category)
                    <div class="text-xs">
                        <span class="text-gray-500">Category:</span>
                        <a href="{{ url('shop/' . $product->category->slug) }}" class="text-[#1a9cb7] hover:underline font-medium">{{ $product->category->name }}</a>
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" title="Add to Wishlist" onclick="toggleWishlistProduct({{ $product->id }}, '{{ addslashes($product->title) }}', '{{ $product->old_price ?? $product->price ?? 0 }}', '{{ asset('storage/' . $product->thumb_image) }}', '{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}')" class="text-gray-400 hover:text-rose-500 transition-colors">
                        <i class="fa-regular fa-heart text-lg" id="wishlist-heart-icon-{{ $product->id }}"></i>
                    </button>
                </div>
            </div>

            {{-- Price Display with Stock Status Badge on exact right --}}
            <div class="py-2 flex items-center justify-between gap-3 my-1 border-b border-gray-200">
                <div class="flex items-baseline gap-2.5" id="top-price-area">
                    @if ($product->product_type === 'variable')
                    @php
                    $regularPrices = [];
                    $offerPrices = [];
                    if ($product->variationCombinations && $product->variationCombinations->count() > 0) {
                        foreach ($product->variationCombinations as $combination) {
                            if ($combination->regular_price) $regularPrices[] = $combination->regular_price;
                            $eff = $combination->offer_price ?? ($combination->regular_price ?? $combination->price);
                            if ($eff) $offerPrices[] = $eff;
                        }
                    }
                    $minOffer = !empty($offerPrices) ? min($offerPrices) : ($product->offer ?? $product->old_price ?? 0);
                    $minRegular = !empty($regularPrices) ? max($regularPrices) : ($product->old_price ?? 0);
                    $disc = ($minRegular > 0 && $minOffer < $minRegular) ? round((($minRegular - $minOffer) / $minRegular) * 100) : 0;
                    @endphp
                    <span class="text-sm text-gray-400 line-through oldprice" id="topOldPrice" style="display: {{ $minRegular > $minOffer ? 'inline' : 'none' }};">৳{{ number_format($minRegular) }}</span>
                    <span class="text-3xl font-bold text-[#f57224]" id="topOfferPrice">৳{{ number_format($minOffer) }}</span>
                    <span class="text-xs text-white bg-[#ef4444] px-2 py-1 rounded font-bold uppercase tracking-wider" id="topDiscountBadge" style="display: {{ $disc > 0 ? 'inline-block' : 'none' }};">{{ $disc }}% OFF</span>
                    @else
                    @if ($product->offer && $product->old_price > $product->offer)
                    @php
                    $discountPercentage = round((($product->old_price - $product->offer) / $product->old_price) * 100);
                    @endphp
                    <span class="text-sm text-gray-400 line-through oldprice" id="topOldPrice">৳{{ number_format($product->old_price) }}</span>
                    <span class="text-3xl font-bold text-[#f57224]" id="topOfferPrice">৳{{ number_format($product->offer) }}</span>
                    <span class="text-xs text-white bg-[#ef4444] px-2 py-1 rounded font-bold uppercase tracking-wider" id="topDiscountBadge">{{ $discountPercentage }}% OFF</span>
                    @else
                    <span class="text-sm text-gray-400 line-through oldprice" id="topOldPrice" style="display:none;"></span>
                    <span class="text-3xl font-bold text-[#f57224]" id="topOfferPrice">৳{{ number_format($product->old_price ?? $product->price) }}</span>
                    <span class="text-xs text-white bg-[#ef4444] px-2 py-1 rounded font-bold uppercase tracking-wider" id="topDiscountBadge" style="display:none;"></span>
                    @endif
                    @endif
                </div>

                {{-- Stock Status Badge on exact right side --}}
                <div class="shrink-0">
                    @if (($product->product_type === 'variable' && ($product->quantity ?? 0) <= 0) || ($product->product_type !== 'variable' && $product->quantity !== null && $product->quantity <= 0))
                    <span class="text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-3 py-1 rounded-full" id="topStockBadge">This product is out of stock.</span>
                    @elseif ($product->quantity !== null && $product->quantity > 0)
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full" id="topStockBadge">In Stock ({{ $product->quantity }})</span>
                    @else
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full" id="topStockBadge">In Stock (400)</span>
                    @endif
                </div>
            </div>
        </div>

        @if (setting('general', 'show_short_description_section', '1') == '1')
        <div class="short-description-container">
            <div class="short-desc-content" id="shortDescContent">
                {!! $product->short_description !!}
            </div>
            <a href="javascript:void(0)" id="readMoreBtn" class="read-more-btn">
                {{ setting('general', 'read_more_button_text', 'বিস্তারিত') }}
            </a>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var content = document.getElementById("shortDescContent");
                var btn = document.getElementById("readMoreBtn");
                if (content && btn) {
                    // Check if content overflows
                    if (content.scrollHeight > content.clientHeight) {
                        btn.style.display = "inline-block";
                    }

                    btn.addEventListener("click", function() {
                        content.classList.add("expanded");
                        btn.style.display = "none";
                    });
                }
            });
        </script>
        @endif

        <div class="variation-description" style="display: none;"></div>

        {{-- Combo Offers or Regular Variations --}}
        @if ($hasComboOffers)
        {{-- Show Combo Offers --}}
        <div class="combo-offers-container">
            <!-- Combo offers will be loaded here via JavaScript -->
        </div>
        @elseif (
        $product->product_type === 'variable' &&
        $product->variationCombinations &&
        $product->variationCombinations->count() > 0)
        {{-- Show Regular Variations (Daraz Style) --}}
        <div class="variation-selection-area my-3">
            @php
            // Extract unique variations and their options from combinations
            $variations = [];
            foreach ($product->variationCombinations as $combination) {
            $combinationOptions = is_array($combination->variation_options)
            ? $combination->variation_options
            : json_decode($combination->variation_options, true);

            foreach ($combinationOptions as $optionId) {
            $option = \App\Models\VariationOption::find($optionId);
            if ($option && $option->variation) {
            $variationId = $option->variation->id;
            $variationName = $option->variation->name;

            if (!isset($variations[$variationId])) {
            $variations[$variationId] = [
            'id' => $variationId,
            'name' => $variationName,
            'options' => [],
            ];
            }

            if (!isset($variations[$variationId]['options'][$optionId])) {
            $variations[$variationId]['options'][$optionId] = [
            'id' => $optionId,
            'name' => $option->name,
            'description' => $option->description ?? '',
            'featured_image' => $option->featured_image ?? '',
            'images' => $option->images ?? '',
            ];
            }
            }
            }
            }
            @endphp

            @foreach ($variations as $variation)
            <div class="variation-group my-3">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs text-gray-500 min-w-[90px] font-normal">{{ $variation['name'] }}</span>
                    <span class="text-xs text-gray-800 font-semibold" id="selected-val-{{ $variation['id'] }}"></span>
                </div>
                <div class="variation-options flex flex-wrap gap-2" data-variation-id="{{ $variation['id'] }}">
                    @foreach ($variation['options'] as $option)
                    @if(!empty($option['featured_image']))
                    <button type="button" class="variation-option-btn daraz-thumb-option border border-gray-300 rounded-sm p-0.5 hover:border-[#f57224] transition-all bg-white"
                        data-option-id="{{ $option['id'] }}" data-variation-id="{{ $variation['id'] }}"
                        data-option-name="{{ $option['name'] }}"
                        data-description="{{ $option['description'] }}"
                        data-thumb-image="{{ $option['featured_image'] }}"
                        data-additional-images="{{ $option['images'] }}"
                        onclick="selectVariationOption(this)"
                        title="{{ $option['name'] }}"
                        style="width: 44px; height: 44px; position: relative;">
                        <img src="{{ asset('storage/' . $option['featured_image']) }}" alt="{{ $option['name'] }}" class="w-full h-full object-cover rounded-sm">
                    </button>
                    @else
                    <button type="button" class="variation-option-btn daraz-text-option border border-gray-300 text-gray-700 hover:border-[#f57224] hover:text-[#f57224] px-3.5 py-1 text-xs rounded-sm transition-all bg-white font-medium"
                        data-option-id="{{ $option['id'] }}" data-variation-id="{{ $variation['id'] }}"
                        data-option-name="{{ $option['name'] }}"
                        data-description="{{ $option['description'] }}"
                        data-thumb-image="{{ $option['featured_image'] }}"
                        data-additional-images="{{ $option['images'] }}"
                        onclick="selectVariationOption(this)">
                        {{ $option['name'] }}
                    </button>
                    @endif
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Error Messages -->
            <div id="variation-selection-error"
                style="display: none; margin: 8px 0; padding: 6px 10px; background: #fee2e2; color: #dc2626; border-radius: 4px; border: 1px solid #fecaca; font-size: 11px;">
                This combination is not available.
            </div>
        </div>

        <!-- Hidden input to store selected combination ID -->
        <input type="hidden" id="selected_combination_id" name="combination_id" value="">
        @endif

        <script>
            // Store variation combinations data with images
            window.variationCombinations = @json($variationCombinationsData ?? []);
            window.productStock = @json($product->quantity ?? 0);
            window.productType = @json($product->product_type);
            window.productManageStock = @json($product->manage_stock ?? false);
            window.selectedVariations = {};

            function selectVariationOption(button) {
                const variationId = button.dataset.variationId;
                const optionId = button.dataset.optionId;
                const optionName = button.dataset.optionName;
                const thumbImage = button.dataset.thumbImage;

                // Remove selected class from other buttons in same variation group
                const variationGroup = button.closest('.variation-options');
                if (variationGroup) {
                    variationGroup.querySelectorAll('.variation-option-btn').forEach(btn => {
                        btn.classList.remove('selected');
                    });
                }

                // Add selected class to clicked button
                button.classList.add('selected');

                if (optionName) {
                    const labelSpan = document.getElementById('selected-val-' + variationId);
                    if (labelSpan) labelSpan.textContent = optionName;
                }

                // Filter gallery images based on selected option (Small / Medium / Color)
                filterGalleryImages();

                // Highlight/Active matching gallery thumbnail if available
                if (optionId || thumbImage) {
                    let matchingThumb = document.querySelector(`.product-gallery-image[data-option-id="${optionId}"]`);
                    if (!matchingThumb && thumbImage) {
                        document.querySelectorAll('.product-gallery-image').forEach(tImg => {
                            let imgSrc = tImg.dataset.imgSrc || tImg.src;
                            if (imgSrc && (imgSrc === thumbImage || imgSrc.includes(thumbImage) || thumbImage.includes(imgSrc))) {
                                matchingThumb = tImg;
                            }
                        });
                    }
                    if (matchingThumb && matchingThumb.style.display !== 'none') {
                        const mainImg = document.getElementById('main-product-image');
                        if (mainImg) mainImg.src = matchingThumb.src;
                        const zoomFig = mainImg ? mainImg.closest('figure.zoom') : null;
                        if (zoomFig) zoomFig.style.backgroundImage = `url('${matchingThumb.src}')`;
                        document.querySelectorAll('.product-gallery-image').forEach(img => img.classList.remove('active'));
                        matchingThumb.classList.add('active');
                    }
                }

                // Update selected variations tracking
                window.selectedVariations[variationId] = {
                    optionId: parseInt(optionId),
                    variationId: parseInt(variationId),
                    button: button
                };

                // Update combination info
                updateVariationCombination(false);
            }

            function filterGalleryImages() {
                const galleryImgs = document.querySelectorAll('.product-gallery-image');
                if (galleryImgs.length === 0) return;

                const selectedBtns = document.querySelectorAll('.variation-option-btn.selected');
                if (selectedBtns.length === 0) {
                    galleryImgs.forEach(img => img.style.display = 'inline-block');
                    return;
                }

                // Find Size button (1st category) and Color button (2nd category)
                let selectedSizeBtn = null;
                let selectedColorBtn = null;

                selectedBtns.forEach(btn => {
                    const group = btn.closest('.variation-group');
                    const groupLabel = group ? (group.querySelector('.text-gray-500')?.textContent || '').toLowerCase() : '';
                    if (groupLabel.includes('size')) {
                        selectedSizeBtn = btn;
                    } else if (groupLabel.includes('color') || groupLabel.includes('colour')) {
                        selectedColorBtn = btn;
                    }
                });

                // Fallback to position if labels aren't matched
                if (!selectedSizeBtn && selectedBtns.length > 0) selectedSizeBtn = selectedBtns[0];
                if (!selectedColorBtn && selectedBtns.length > 1) selectedColorBtn = selectedBtns[1];

                const sizeOptionId = selectedSizeBtn ? parseInt(selectedSizeBtn.dataset.optionId) : null;
                const selectedColorId = selectedColorBtn ? parseInt(selectedColorBtn.dataset.optionId) : null;

                if (!sizeOptionId) {
                    galleryImgs.forEach(img => img.style.display = 'inline-block');
                    return;
                }

                // Find all combinations matching ONLY the 1st category (Size)
                const sizeCombos = (window.variationCombinations || []).filter(combo => {
                    let opts = combo.variation_options;
                    if (typeof opts === 'string') { try { opts = JSON.parse(opts); } catch(e) { return false; } }
                    if (!Array.isArray(opts)) return false;
                    return opts.map(o => parseInt(o)).includes(sizeOptionId);
                });

                if (sizeCombos.length === 0) {
                    galleryImgs.forEach(img => img.style.display = 'inline-block');
                    return;
                }

                // Collect valid option IDs and allowed images for the selected Size
                const validOptionIds = new Set();
                const allowedImages = new Set();

                sizeCombos.forEach(combo => {
                    let opts = combo.variation_options;
                    if (typeof opts === 'string') { try { opts = JSON.parse(opts); } catch(e) { opts = []; } }
                    if (Array.isArray(opts)) opts.forEach(o => validOptionIds.add(parseInt(o)));

                    if (combo.featured_image) {
                        const path = combo.featured_image.trim().toLowerCase();
                        allowedImages.add(path);
                        const filename = path.split('/').pop();
                        if (filename) allowedImages.add(filename);
                    }
                    let gImgs = combo.gallery_images;
                    if (typeof gImgs === 'string') { try { gImgs = JSON.parse(gImgs); } catch(e) { gImgs = []; } }
                    if (Array.isArray(gImgs)) {
                        gImgs.forEach(img => {
                            if (img) {
                                const path = img.trim().toLowerCase();
                                allowedImages.add(path);
                                const filename = path.split('/').pop();
                                if (filename) allowedImages.add(filename);
                            }
                        });
                    }
                });

                // Find exact image paths for selected Color
                const colorImages = new Set();
                if (selectedColorId) {
                    const colorCombos = sizeCombos.filter(combo => {
                        let opts = combo.variation_options;
                        if (typeof opts === 'string') { try { opts = JSON.parse(opts); } catch(e) { return false; } }
                        if (!Array.isArray(opts)) return false;
                        return opts.map(o => parseInt(o)).includes(selectedColorId);
                    });
                    colorCombos.forEach(combo => {
                        if (combo.featured_image) {
                            const path = combo.featured_image.trim().toLowerCase();
                            colorImages.add(path);
                            const fn = path.split('/').pop();
                            if (fn) colorImages.add(fn);
                        }
                    });
                }

                let visibleCount = 0;
                let firstVisible = null;
                let activeColorThumb = null;

                // Show all thumbnails belonging to the 1st category (Size)
                galleryImgs.forEach(tImg => {
                    let tOptIds = [];
                    try {
                        const raw = tImg.dataset.optionIds;
                        if (raw) tOptIds = JSON.parse(raw).map(o => parseInt(o));
                    } catch(e) {}

                    const tImgSrc = (tImg.dataset.imgSrc || tImg.src || '').trim().toLowerCase();
                    const tFilename = tImgSrc.split('/').pop();
                    let show = false;

                    if (tOptIds.length > 0 && tOptIds.some(oid => validOptionIds.has(oid))) {
                        show = true;
                    }
                    if (!show && allowedImages.size > 0) {
                        allowedImages.forEach(aImg => {
                            if (tImgSrc && (tImgSrc.includes(aImg) || aImg.includes(tImgSrc) || (tFilename && tFilename === aImg))) {
                                show = true;
                            }
                        });
                    }

                    if (show) {
                        tImg.style.display = 'inline-block';
                        visibleCount++;
                        if (!firstVisible) firstVisible = tImg;

                        if (selectedColorId && tOptIds.includes(selectedColorId)) {
                            activeColorThumb = tImg;
                        } else if (selectedColorId && colorImages.size > 0) {
                            colorImages.forEach(cImg => {
                                if (tImgSrc && (tImgSrc.includes(cImg) || cImg.includes(tImgSrc) || (tFilename && tFilename === cImg))) {
                                    activeColorThumb = tImg;
                                }
                            });
                        }
                    } else {
                        tImg.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    galleryImgs.forEach(img => img.style.display = 'inline-block');
                }

                // Update BIG main image preview to selected Color (or first visible for Size)
                const targetThumb = activeColorThumb || firstVisible;
                if (targetThumb) {
                    const mainImg = document.getElementById('main-product-image');
                    if (mainImg) mainImg.src = targetThumb.src;
                    const zoomFig = mainImg ? mainImg.closest('figure.zoom') : null;
                    if (zoomFig) zoomFig.style.backgroundImage = `url('${targetThumb.src}')`;
                    galleryImgs.forEach(img => img.classList.remove('active'));
                    targetThumb.classList.add('active');
                }
            }

            function updateButtonStates() {
                const hiddenCombo = document.getElementById('selected_combination_id');
                const buyBtn = document.querySelector('.single-buynow-btn') || document.querySelector('#buyNowForm button');
                const cartBtn = document.querySelector('.single-cart-btn') || document.querySelector('#cartForm button');

                const isVariable = window.productType === 'variable';
                const hasValidCombination = hiddenCombo && hiddenCombo.value && hiddenCombo.value.trim() !== '';

                const isEnabled = !isVariable || hasValidCombination;

                [buyBtn, cartBtn].forEach(btn => {
                    if (btn) {
                        btn.disabled = !isEnabled;
                        if (!isEnabled) {
                            btn.style.opacity = '0.35';
                            btn.style.cursor = 'not-allowed';
                            btn.style.pointerEvents = 'none';
                            btn.classList.add('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                        } else {
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                            btn.style.pointerEvents = 'auto';
                            btn.classList.remove('opacity-40', 'cursor-not-allowed', 'pointer-events-none');
                        }
                    }
                });
            }

            function updateVariationCombination(loadImages = false) {
                const errorDiv = document.getElementById('variation-selection-error');
                const variationGroups = document.querySelectorAll('.variation-options');

                let selectedOptions = [];
                let allSelected = true;

                variationGroups.forEach(group => {
                    const selectedBtn = group.querySelector('.variation-option-btn.selected');
                    if (selectedBtn) {
                        selectedOptions.push(parseInt(selectedBtn.dataset.optionId));
                    } else {
                        allSelected = false;
                    }
                });

                if (!allSelected) {
                    if (errorDiv) errorDiv.style.display = 'none';
                    const hiddenCombo = document.getElementById('selected_combination_id');
                    if (hiddenCombo) hiddenCombo.value = '';
                    updateTotalPrice();
                    updateButtonStates();
                    return;
                }

                selectedOptions.sort((a, b) => a - b);

                const combination = window.variationCombinations.find(combo => {
                    let comboOptions = combo.variation_options;
                    if (typeof comboOptions === 'string') {
                        comboOptions = JSON.parse(comboOptions);
                    }
                    if (!Array.isArray(comboOptions)) return false;
                    comboOptions = comboOptions.map(opt => parseInt(opt)).sort((a, b) => a - b);
                    if (comboOptions.length !== selectedOptions.length) return false;
                    for (let i = 0; i < comboOptions.length; i++) {
                        if (comboOptions[i] !== selectedOptions[i]) return false;
                    }
                    return true;
                });

                if (combination) {
                    if (errorDiv) errorDiv.style.display = 'none';

                    const effectivePrice = combination.offer_price || combination.regular_price || combination.price;
                    const regularPrice = combination.regular_price || combination.price;

                    // Update individual price elements
                    const topOldPrice = document.getElementById('topOldPrice');
                    const topOfferPrice = document.getElementById('topOfferPrice');
                    const topDiscountBadge = document.getElementById('topDiscountBadge');

                    if (topOfferPrice) topOfferPrice.textContent = `৳${effectivePrice}`;

                    if (regularPrice && combination.offer_price && combination.offer_price < regularPrice) {
                        const disc = Math.round(((regularPrice - combination.offer_price) / regularPrice) * 100);
                        if (topOldPrice) {
                            topOldPrice.textContent = `৳${regularPrice}`;
                            topOldPrice.style.display = 'inline';
                        }
                        if (topDiscountBadge) {
                            topDiscountBadge.textContent = `${disc}% OFF`;
                            topDiscountBadge.style.display = 'inline-block';
                        }
                    } else {
                        if (topOldPrice) topOldPrice.style.display = 'none';
                        if (topDiscountBadge) topDiscountBadge.style.display = 'none';
                    }

                    // Update main image if combination has a featured_image
                    if (combination.featured_image) {
                        const mainImg = document.getElementById('main-product-image');
                        const fullSrc = combination.featured_image.startsWith('http') ? combination.featured_image : `${window.location.origin}/storage/${combination.featured_image}`;
                        if (mainImg) mainImg.src = fullSrc;
                        const zoomFig = mainImg ? mainImg.closest('figure.zoom') : null;
                        if (zoomFig) zoomFig.style.backgroundImage = `url('${fullSrc}')`;
                    }

                    // Update top stock status badge
                    const topStockBadge = document.getElementById('topStockBadge');
                    if (topStockBadge) {
                        if (combination.stock_quantity > 0) {
                            topStockBadge.textContent = `In Stock (${combination.stock_quantity})`;
                            topStockBadge.className = 'text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full';
                        } else {
                            topStockBadge.textContent = 'This product is out of stock.';
                            topStockBadge.className = 'text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-3 py-1 rounded-full';
                        }
                    }

                    const hiddenCombo = document.getElementById('selected_combination_id');
                    if (hiddenCombo) hiddenCombo.value = combination.id;

                    updateVariationDescriptions();
                    updateTotalPrice();
                    updateButtonStates();
                } else {
                    if (errorDiv) {
                        errorDiv.textContent = 'This combination is not available.';
                        errorDiv.style.display = 'block';
                    }
                    const hiddenCombo = document.getElementById('selected_combination_id');
                    if (hiddenCombo) hiddenCombo.value = '';
                    updateTotalPrice();
                    updateButtonStates();
                }
            }

            function updateVariationDescriptions() {
                const descriptionDiv = document.getElementById('combination-description');
                const hiddenCombo = document.getElementById('selected_combination_id');
                const combinationId = hiddenCombo ? hiddenCombo.value : null;

                if (combinationId && descriptionDiv) {
                    const combination = window.variationCombinations.find(combo => combo.id == combinationId);
                    if (combination && combination.short_description) {
                        descriptionDiv.innerHTML = combination.short_description;
                        descriptionDiv.style.display = 'block';
                        return;
                    }
                }

                const descriptions = [];
                document.querySelectorAll('.variation-option-btn.selected').forEach(btn => {
                    const description = btn.dataset.description;
                    if (description && description.trim()) {
                        descriptions.push(description);
                    }
                });

                if (descriptionDiv) {
                    if (descriptions.length > 0) {
                        descriptionDiv.innerHTML = descriptions.join('<br>');
                        descriptionDiv.style.display = 'block';
                    } else {
                        descriptionDiv.style.display = 'none';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.variation-options').forEach(group => {
                    if (!group.querySelector('.variation-option-btn.selected')) {
                        const firstBtn = group.querySelector('.variation-option-btn');
                        if (firstBtn) firstBtn.classList.add('selected');
                    }
                });
                updateVariationCombination(false);
                filterGalleryImages();
                updateButtonStates();
            });
        </script>



        <!-- Quantity Stepper + Buy Now + Add to Cart ALL IN ONE ROW -->
        <div class="flex items-center gap-1.5 sm:gap-3 my-4" id="productActionsContainer">
            {{-- Quantity Stepper --}}
            <div class="flex items-center bg-gray-50 rounded border border-gray-300 h-10 px-1 shrink-0">
                <button type="button" class="w-6 sm:w-7 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs sm:text-sm font-bold rounded-l transition-colors" onclick="changeQty(-1)">-</button>
                <input class="w-7 sm:w-10 text-center bg-transparent border-none focus:ring-0 text-xs font-bold text-gray-800 quanity px-0" id="sharedQuantity" type="number" value="1" min="1" max="{{ $product->product_type === 'variable' ? '999' : $product->quantity ?? 999 }}">
                <button type="button" class="w-6 sm:w-7 h-full flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs sm:text-sm font-bold rounded-r transition-colors" onclick="changeQty(1)">+</button>
            </div>

            @if ($product->product_type !== 'affiliate')
            {{-- Buy Now Form --}}
            <form id="buyNowForm" action="{{ route('buy.store.post') }}" method="post" class="flex-1 min-w-0">
                @csrf
                <input type="hidden" name="quantity" id="buyQuantity" value="1" />
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="price" id="buyPriceId" value="{{ $product->offer ?? $product->old_price }}" />
                <input name="main_price" type="hidden" id="buyMainPrice" value="{{ $product->offer ?? $product->old_price }}">
                <div id="buyVariationInputs"></div>
                <button type="submit" onclick="updateBuyNowForm(event)" class="w-full bg-[#2bbed6] hover:bg-[#20a4bb] text-white h-10 rounded font-semibold text-[11px] sm:text-sm uppercase tracking-wider transition-colors shadow-sm flex items-center justify-center gap-1 single-buynow-btn px-1.5 whitespace-nowrap"
                    onsubmit="return false;">
                    <i class="fa-solid fa-bolt text-amber-300 text-[10px] sm:text-xs"></i>
                    <span class="truncate">Buy Now</span>
                </button>
            </form>

            {{-- Add to Cart Form --}}
            <form id="cartForm" class="cartFormArea flex-1 min-w-0" action="{{ route('cart.store') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="quantity" id="cartQuantity" value="1" />
                <div id="variationInputs"></div>
                <button type="submit" onclick="updateCartForm(event)" class="w-full bg-[#f57224] hover:bg-[#d85c14] text-white h-10 rounded font-semibold text-[11px] sm:text-sm uppercase tracking-wider transition-colors shadow-sm flex items-center justify-center gap-1 single-cart-btn px-1.5 whitespace-nowrap">
                    <i class="fa-solid fa-cart-shopping text-[10px] sm:text-xs"></i>
                    <span class="truncate">Add to Cart</span>
                </button>
            </form>
            @else
            <a href="{{ $product->external_url }}" target="_blank" class="w-full bg-[#2bbed6] hover:bg-[#20a4bb] text-white h-10 rounded font-semibold text-[11px] sm:text-sm uppercase tracking-wider transition-colors shadow-sm flex items-center justify-center gap-1 single-buynow-btn affiliate-btn px-1.5 whitespace-nowrap" onclick="return checkAffiliateStock(event)">
                <i class="fa-solid fa-external-link text-[10px] sm:text-xs"></i> <span class="truncate">Buy Now</span>
            </a>
            @endif
        </div>

            <style>
                @keyframes periodicNudge {
                    0%, 90%, 100% { transform: translateX(0); }
                    91% { transform: translateX(-6px) rotate(-1deg); }
                    93% { transform: translateX(6px) rotate(1deg); }
                    95% { transform: translateX(-4px) rotate(-1deg); }
                    97% { transform: translateX(4px) rotate(1deg); }
                    99% { transform: translateX(-2px); }
                }
                .animate-periodic-nudge {
                    animation: fireworkShimmer 2.5s infinite ease-in-out, periodicNudge 12s infinite ease-in-out;
                }
            </style>

        {{-- Short book red something --}}
        @if (setting('single_product', 'enable_book_sample', '1') == '1')
        {{-- Read boook some page --}}
        @isset($product->book->sample_path)
        <a href="#" class="read-more-button" id="openSampleModal"
            data-sample="/storage/{{ $product->book->sample_path }}"> <svg xmlns="http://www.w3.org/2000/svg"
                width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="1.25"
                stroke="currentColor" aria-hidden="true" class="bponi-ag bponi-ig bponi-wz">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 005.8 3.8c-1 .02-2.08.22-3 .55v14.1A9 9 0 015.9 18c2.3.05 4.38.95 6.1 2.25m0-14.25a9 9 0 016.1-2.25c1.05 0 2.07.18 2.9.52v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25">
                </path>
            </svg> একটুখানি পড়ুন</a>
        @else
        <a href="#" class="read-more-button" id="openSampleModal" data-sample=""> <svg
                xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" aria-hidden="true"
                class="bponi-ag bponi-ig bponi-wz">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6.042A8.967 8.967 0 005.8 3.8c-1 .02-2.08.22-3 .55v14.1A9 9 0 015.9 18c2.3.05 4.38.95 6.1 2.25m0-14.25a9 9 0 016.1-2.25c1.05 0 2.07.18 2.9.52v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25">
                </path>
            </svg> একটুখানি পড়ুন</a>
        @endisset
        <!---Read part of book section -->
        <style>
            .modal {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.6);
                justify-content: center;
                align-items: center;
            }

            .modal-content {
                background: #fff;
                margin: 5% auto;
                padding: 0;
                border-radius: 8px;
                width: 90%;
                max-width: 900px;
                position: relative;
                box-shadow: 0 2px 16px rgba(0, 0, 0, 0.2);
            }

            .close {
                position: absolute;
                top: 10px;
                right: 20px;
                font-size: 2rem;
                color: #e74c3c;
                cursor: pointer;
                z-index: 10;
            }

            #sampleIframe {
                width: 100%;
                height: 80vh;
                border: none;
                border-radius: 0 0 8px 8px;
                display: block;
            }
        </style>
        <!-- Book Sample Modal -->
        <div id="bookSampleModal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close" id="closeSampleModal">&times;</span>
                <iframe id="sampleIframe" src="" frameborder="0"
                    style="width:100%; height:80vh;"></iframe>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var openBtn = document.getElementById('openSampleModal');
                var modal = document.getElementById('bookSampleModal');
                var closeBtn = document.getElementById('closeSampleModal');
                var iframe = document.getElementById('sampleIframe');

                if (openBtn) {
                    openBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        iframe.src = this.getAttribute('data-sample');
                        modal.style.display = 'flex';
                    });
                }
                if (closeBtn) {
                    closeBtn.onclick = function() {
                        modal.style.display = 'none';
                        iframe.src = '';
                    }
                }
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                        iframe.src = '';
                    }
                }
            });
        </script>
        @endif

        {{-- Short info after main action --}}
        <style>
            .product-meta-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 1rem;
                color: #666;
                background: #fff;
                padding: 6px 0 6px 0;
                margin-bottom: 0px;
                gap: 10px;
            }

            .product-meta-bar .meta-item {
                display: flex;
                align-items: center;
                gap: 5px;
                font-size: 0.9em;
                white-space: nowrap;
            }

            .product-meta-bar .meta-item i {
                font-size: 1.1em;
                color: #888;
            }

            @media (max-width: 600px) {
                .product-meta-bar {
                    font-size: 0.95rem;
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 4px;
                }
            }
        </style>
        @if (setting('single_product', 'enable_short_info', '1') == '1')
        <div class="product-meta-bar">
            <div class="meta-item">
                <svg width="1.1em" height="1.1em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 0.5em;">
                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                        stroke="currentColor" stroke-width="2" />
                    <path d="M16 8L8 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
                ডেলিভারি করা হবে {{ today()->addWeek()->format('l, d M Y') }} পর্যন্ত
            </div>
            <div class="meta-item">
                <svg width="1.3em" height="1.3em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; margin-right: 0.5em;">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"
                        stroke="currentColor" stroke-width="2" />
                    <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor"
                        stroke-width="2" />
                    <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor"
                        stroke-width="2" />
                    <path d="M6 7H18" stroke="currentColor" stroke-width="1.5" />
                    <path d="M6 10H12" stroke="currentColor" stroke-width="1.5" />
                    <circle cx="16" cy="10" r="1" fill="currentColor" />
                </svg>
                ক্যাশ অন ডেলিভারি
            </div>
        </div>
        @endif

        {{-- Order Delivery Timeline (Placed right after Buy Now & Actions) --}}
        @php
        $showTimeline = setting('general', 'show_order_timeline', '1');
        @endphp
        @if ($showTimeline == '1')
        <style>
            .order-time-container {
                width: 100%;
                max-width: 800px;
            }

            .order-time-timeline-container {
                width: 100%;
                position: relative;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                border-radius: 12px;
                margin-top: 8px;
                padding: 10px 4px 6px 4px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
        </style>

        <div class="order-time-container my-2">
            <div class="order-time-timeline-container" id="timeline-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 150" width="100%">
                    <!-- Connection Lines -->
                    <line x1="100" y1="60" x2="400" y2="60" stroke="#113257" stroke-width="2.5" />
                    <line x1="400" y1="60" x2="700" y2="60" stroke="#cbd5e1" stroke-width="2.5" stroke-dasharray="4 4" />

                    <!-- Circle 1: Ordered (Shopping Bag / Checkbox Icon) -->
                    <circle cx="100" cy="60" r="32" fill="#113257" />
                    <!-- Shopping Bag / Order Icon -->
                    <path d="M91 52 C91 48 95 44 100 44 C105 44 109 48 109 52" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                    <rect x="88" y="52" width="24" height="22" rx="3" fill="none" stroke="#ffffff" stroke-width="2.5"/>
                    <path d="M96 63 L99 66 L105 60" fill="none" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Circle 2: Order Ready (Box / Package Prepared Icon) -->
                    <circle cx="400" cy="60" r="32" fill="#113257" />
                    <!-- Box Icon -->
                    <path d="M386 52 L400 45 L414 52 L400 59 Z" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round"/>
                    <path d="M386 52 L386 67 L400 74 L400 59" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round"/>
                    <path d="M414 52 L414 67 L400 74" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round"/>
                    <path d="M393 48.5 L407 55.5" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round"/>

                    <!-- Circle 3: Delivered (Delivery Van / Home Arrival Icon) -->
                    <circle cx="700" cy="60" r="32" fill="#94a3b8" />
                    <!-- Delivery Truck Icon -->
                    <rect x="683" y="50" width="22" height="15" rx="2" fill="none" stroke="#ffffff" stroke-width="2.2"/>
                    <path d="M705 54 L713 54 L717 59 L717 65 L705 65 Z" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round"/>
                    <circle cx="690" cy="67" r="3.5" fill="#94a3b8" stroke="#ffffff" stroke-width="2.2"/>
                    <circle cx="711" cy="67" r="3.5" fill="#94a3b8" stroke="#ffffff" stroke-width="2.2"/>

                    <!-- Text Labels -->
                    <text id="ordered-date" x="100" y="115" font-family="'Inter', sans-serif" font-size="14" text-anchor="middle" font-weight="bold" fill="#113257"></text>
                    <text x="100" y="135" font-family="'Inter', sans-serif" font-size="13" text-anchor="middle" fill="#64748b">Ordered</text>

                    <text id="ready-date" x="400" y="115" font-family="'Inter', sans-serif" font-size="14" text-anchor="middle" font-weight="bold" fill="#113257"></text>
                    <text x="400" y="135" font-family="'Inter', sans-serif" font-size="13" text-anchor="middle" fill="#64748b">Order Ready</text>

                    <text id="delivered-date" x="700" y="115" font-family="'Inter', sans-serif" font-size="14" text-anchor="middle" font-weight="bold" fill="#64748b"></text>
                    <text x="700" y="135" font-family="'Inter', sans-serif" font-size="13" text-anchor="middle" fill="#64748b">Delivered</text>
                </svg>
            </div>
        </div>
        @endif

        {{-- Separate Section: Contact & Instant Support --}}
        <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl my-2">
            <span class="text-xs font-bold text-navy-deep uppercase tracking-wider">Quick Support:</span>
            <div class="flex items-center gap-2">
                @if (setting('general', 'show_whatsapp_button', '1') == '1')
                <a href="https://api.whatsapp.com/send?phone={{ setting('general', 'whatsapp_number') }}&text={{ urlencode('I am interested in: ' . $product->title . ' - ' . url()->current()) }}"
                    target="_blank" title="WhatsApp: {{ setting('general', 'whatsapp_number') }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500 text-white text-xs font-bold hover:bg-emerald-600 hover:scale-105 transition-all shadow-sm">
                    <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                </a>
                @endif

                @if (setting('general', 'show_phone_button', '1') == '1')
                <a href="tel:+{{ setting('general', 'phone_number') }}" title="Call: {{ setting('general', 'phone_number') }}"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-sky-500 text-white text-xs font-bold hover:bg-sky-600 hover:scale-105 transition-all shadow-sm">
                    <i class="fa-solid fa-phone text-xs"></i> Call Us
                </a>
                @endif
            </div>
        </div>

        {{-- Separate Section: Social Share --}}
        @if (setting('single_product', 'enable_social_share', '1') == '1')
        <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl my-2">
            <span class="text-xs font-bold text-navy-deep uppercase tracking-wider">Share Product:</span>
            <div class="flex items-center gap-2">
                <button type="button" title="Copy Product Link" onclick="copyProductLink()"
                    class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center hover:scale-110 hover:bg-slate-300 transition-all shadow-sm">
                    <i class="fa-solid fa-link text-xs"></i>
                </button>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" title="Share on Facebook"
                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-sm">
                    <i class="fa-brands fa-facebook-f text-xs"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->title) }}" target="_blank" title="Share on Twitter"
                    class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center hover:scale-110 transition-transform shadow-sm">
                    <i class="fa-brands fa-x-twitter text-xs"></i>
                </a>
            </div>
        </div>
        <script>
            function copyProductLink() {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Product link copied to clipboard!');
                }).catch(function(err) {
                    let dummy = document.createElement('input');
                    document.body.appendChild(dummy);
                    dummy.value = window.location.href;
                    dummy.select();
                    document.execCommand('copy');
                    document.body.removeChild(dummy);
                    alert('Product link copied to clipboard!');
                });
            }
        </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
                let exists = wishlist.some(item => item.id == {{ $product->id }});
                if (exists) {
                    let heartIcon = document.getElementById('wishlist-heart-icon-{{ $product->id }}');
                    if (heartIcon) heartIcon.classList.add('text-rose-600');
                }
            });

            function toggleWishlistProduct(id, title, price, image, url) {
                let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
                let index = wishlist.findIndex(item => item.id == id);
                let heartIcon = document.getElementById('wishlist-heart-icon-' + id);

                if (index > -1) {
                    wishlist.splice(index, 1);
                    if (heartIcon) heartIcon.classList.remove('text-rose-600');
                    alert('Removed from your Wishlist!');
                } else {
                    wishlist.push({ id: id, title: title, price: price, image: image, url: url });
                    if (heartIcon) heartIcon.classList.add('text-rose-600');
                    alert('Added to your Wishlist!');
                }
                localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
            }
        </script>

        <script>
            // Function to format date as Month Day (e.g., May 20th)
            function formatDate(date) {
                const months = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'
                ];

                const day = date.getDate();
                const month = months[date.getMonth()];

                // Add ordinal suffix to day
                let suffix = 'th';
                if (day === 1 || day === 21 || day === 31) {
                    suffix = 'st';
                } else if (day === 2 || day === 22) {
                    suffix = 'nd';
                } else if (day === 3 || day === 23) {
                    suffix = 'rd';
                }

                return `${month} ${day}${suffix}`;
            }

            // Function to add days to a date
            function addDays(date, days) {
                const newDate = new Date(date);
                newDate.setDate(newDate.getDate() + days);
                return newDate;
            }

            // Update the timeline with dynamic dates
            function updateTimeline() {
                // Current date for "Ordered"
                const orderedDate = new Date();

                // Order Ready: Current date + 1-2 days
                const readyStartDate = addDays(orderedDate, 1);
                const readyEndDate = addDays(orderedDate, 2);

                // Delivered: Current date + 3-5 days
                const deliveredStartDate = addDays(orderedDate, 3);
                const deliveredEndDate = addDays(orderedDate, 5);

                // Update the SVG text elements
                const el1 = document.getElementById('ordered-date');
                const el2 = document.getElementById('ready-date');
                const el3 = document.getElementById('delivered-date');

                if (el1) el1.textContent = formatDate(orderedDate);
                if (el2) el2.textContent = `${formatDate(readyStartDate)} - ${formatDate(readyEndDate)}`;
                if (el3) el3.textContent = `${formatDate(deliveredStartDate)} - ${formatDate(deliveredEndDate)}`;
            }

            // Initialize the timeline when the page loads
            document.addEventListener('DOMContentLoaded', updateTimeline);
        </script>

        {{-- @include('frontend.partials.quickfaq') --}}

    </div>
</div>
<!-- Middle Section: Rules, Related List, Opinion -->
<div class="space-y-12 info-area">

        <style>
            .product-video {
                margin-bottom: 10px;
            }

            .video-wrapper {
                position: relative;
                padding-bottom: 56.25%;
                /* 9/16 = 0.5625 for 16:9 aspect ratio */
                height: 0;
                overflow: hidden;
            }

            .video-wrapper iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .play-sound-button {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: rgba(0, 0, 0, 0.7);
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px 10px;
                font-size: 16px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: background-color 0.3s;
            }

            .play-sound-button:hover {
                background-color: rgba(0, 0, 0, 0.9);
            }

            .play-sound-button span {
                font-size: 20px;
                /* Adjust emoji size */
            }
        </style>

        @php
        $videoUrl = $product->video_url ?: setting('general', 'global_video_url');
        // Convert YouTube URL to embed format with autoplay
        $embedUrl = null;
        if ($videoUrl) {
        if (preg_match('/youtu\.be\/([^\?&]+)/', $videoUrl, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1';
        } elseif (preg_match('/youtube\.com.*v=([^\?&]+)/', $videoUrl, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1';
        } elseif (preg_match('/youtube\.com\/shorts\/([^\?&]+)/', $videoUrl, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1';
        } elseif (preg_match('/youtube\.com\/embed\/([^\?&]+)/', $videoUrl, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&mute=1';
        } else {
        $embedUrl = $videoUrl; // fallback
        }
        }
        @endphp
        @if ($embedUrl)
        <div class="product-video">
            <div class="video-wrapper">
                <iframe src="{{ $embedUrl }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                <button class="play-sound-button"
                    onclick="this.parentNode.querySelector('iframe').src = this.parentNode.querySelector('iframe').src.replace('&mute=1', '')">
                    <span>▶️</span> Play with Sound
                </button>
            </div>
        </div>
        @endif
        <script>
            document.addEventListener('click', function(event) {
                if (event.target.closest('.play-sound-button')) {
                    event.target.closest('.play-sound-button').style.display = 'none';
                }
            });
        </script>

        <style>
            .product-list-sidebar-section {
                margin: 10px 0 10px 0;
            }

            .product-list-sidebar-header {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            .product-list-sidebar-header h2 {
                font-size: 1.4rem;
                font-weight: bold;
                color: #1a2a4f;
                margin: 0;
                letter-spacing: 1px;
            }

            .product-list-sidebar-line {
                flex: 1 1 20px;
                height: 3px;
                background: #2d4379;
                border-radius: 2px;
                max-width: 40px;
            }

            .product-list-sidebar-box {
                background: #fff;
                border: 1.5px solid #e5e7eb;
                border-radius: 16px;
                padding: 12px 10px;
                box-shadow: 0 2px 8px rgba(44, 62, 80, 0.04);
            }

            .product-list-sidebar-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 12px 0;
                border-bottom: 1px solid #f1f1f1;
            }

            .product-list-sidebar-item:last-child {
                border-bottom: none;
            }

            .product-list-sidebar-img {
                width: 56px;
                height: 80px;
                border-radius: 8px;
                overflow: hidden;
                flex-shrink: 0;
                background: #f5f6fa;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .product-list-sidebar-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .product-list-sidebar-info {
                flex: 1 1 auto;
                min-width: 0;
            }

            .product-list-sidebar-title {
                font-size: 1rem;
                font-weight: 400;
                color: #222;
                margin-bottom: 2px;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .product-list-sidebar-author {
                font-size: 0.95rem;
                color: #6b7280;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .product-list-sidebar-pricing {
                font-size: 1rem;
                margin-bottom: 2px;
            }

            .product-list-sidebar-price {
                color: #e74c3c;
                font-weight: 700;
                margin-right: 6px;
            }

            .product-list-sidebar-oldprice {
                color: #888;
                text-decoration: line-through;
                margin-right: 6px;
                font-size: 0.95em;
            }

            .product-list-sidebar-discount {
                color: #e74c3c;
                font-size: 0.95em;
            }

            .product-list-sidebar-rating {
                font-size: 0.95em;
                color: #fbbf24;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .product-list-sidebar-stars {
                font-size: 1em;
                letter-spacing: 0.5px;
            }

            .product-list-sidebar-rating-value {
                color: #888;
                font-size: 0.95em;
                margin-left: 2px;
            }

            .product-list-sidebar-stars .star-icon {
                flex-shrink: 0;
            }

            @media (max-width: 600px) {
                .product-list-sidebar-header h2 {
                    font-size: 1.1rem;
                }

                .product-list-sidebar-box {
                    padding: 8px 12px;
                }

                .product-list-sidebar-img {
                    width: 55px;
                    height: 70px;
                }

                .product-list-sidebar-title {
                    font-size: 0.95rem;
                }

                .product-list-sidebar-author {
                    font-size: 0.85rem;
                }

                .product-list-sidebar-pricing {
                    font-size: 0.95rem;
                }
            }
        </style>
        <!-- Middle Section: Rules + Write Review (Left) & Related Products List (Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter items-start">
            <!-- Left Column: Delivery Rules + Review Form -->
            <div class="space-y-6 flex flex-col h-full justify-between">
                <!-- Delivery Rules & Shipping Info Card -->
                @php
                $showDelivery = setting('general', 'show_delivery_info', '1');
                $deliveryInfo = setting('general', 'delivery_info', '');
                @endphp

                @if ($showDelivery == '1' && $deliveryInfo)
                <div class="bg-white border-2 border-dashed border-navy-deep/20 p-5 lg:p-6 rounded-3xl space-y-4 shadow-sm">
                    <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-navy-deep/10 text-navy-deep flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-truck-fast text-sm"></i>
                        </div>
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-navy-deep font-headline m-0">RULES / শিপিং সংক্রান্ত তথ্য</h4>
                    </div>
                    <div class="space-y-3 text-xs text-slate-700 leading-relaxed overflow-y-auto max-h-[220px] pr-2 custom-scrollbar">
                        {!! $deliveryInfo !!}
                    </div>
                </div>
                @endif

                <!-- Write a Review Form Card (Underneath Rules) -->
                @if (setting('general', 'show_review_form_section', '1') == '1')
                <div class="bg-white border border-slate-200 rounded-3xl p-5 lg:p-6 shadow-sm space-y-3" id="review-form-section">
                    <div class="flex items-center gap-3 pb-2.5 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-amber-400/15 text-amber-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </div>
                        <h4 class="text-sm font-extrabold text-navy-deep font-headline m-0">{{ setting('general', 'review_form_header_title', 'এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন') }}</h4>
                    </div>

                    <form id="product-review-form" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex items-center gap-2">
                            <div class="star-rating-group flex items-center gap-1.5 flex-1">
                                @for ($r = 1; $r <= 5; $r++)
                                <div class="individual-star-item cursor-pointer text-blue-900 text-lg hover:scale-110 transition-transform @if($r == 4) selected-active @endif" data-rating="{{ $r }}" title="{{ $r }} Star">
                                    <i class="@if($r <= 4) fa-solid @else fa-regular @endif fa-star"></i>
                                </div>
                                @endfor
                            </div>
                        </div>
                        <input type="hidden" name="rating" id="selected-rating" value="4">

                        <textarea class="w-full h-20 p-2.5 border border-slate-200 rounded-xl text-xs font-body focus:ring-2 focus:ring-navy-deep/20 focus:border-navy-deep transition-all resize-none text-slate-700" name="review_text"
                            placeholder="{{ setting('general', 'review_form_comment_placeholder', 'Write your comment...') }}" required></textarea>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex-1">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1">Upload Image (Optional)</label>
                                <input type="file" class="text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-navy-deep hover:file:bg-slate-200 cursor-pointer" name="review_images[]" accept="image/*" multiple>
                            </div>
                            <button type="submit" class="bg-navy-deep hover:bg-navy-deep/90 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-md shrink-0 self-end">
                                {{ setting('general', 'review_form_submit_button_text', 'আপনার মতামত সাবমিট করুন') }}
                            </button>
                        </div>

                        @guest
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <input type="text" name="reviewer_name" placeholder="Your Name *" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs" required>
                            <input type="email" name="reviewer_email" placeholder="Your Email *" class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs" required>
                        </div>
                        @endguest
                    </form>
                </div>
                @endif
            </div>

            <!-- Right Column: Related Products List (আরো দেখুন) -->
            @if (setting('single_product', 'enable_related_products', '1') == '1' &&
            setting('general', 'show_related_products_section', '1') == '1')
            <div class="product-list-sidebar-section flex flex-col h-full !m-0 !p-0">
                <div class="flex items-center gap-3 product-list-sidebar-header !mb-2 !mt-0">
                    <div class="w-7 h-7 rounded-lg bg-energy-orange/10 text-energy-orange flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-fire-flame-curved text-xs"></i>
                    </div>
                    <span class="text-sm font-extrabold uppercase tracking-wider text-navy-deep font-headline">{{ setting('general', 'related_products_section_title', 'আরো দেখুন') }}</span>
                    <div class="h-0.5 flex-1 bg-slate-200"></div>
                </div>

                @if (!empty($related_products) && count($related_products) > 0)
                <div class="bg-white border border-slate-200 rounded-3xl p-2.5 shadow-sm overflow-y-auto max-h-[560px] space-y-2 custom-scrollbar product-list-sidebar-box flex-1 scroll-smooth" id="related-products-scroll-container">
                    @foreach ($related_products as $dproduct)
                    @include('frontend.partials.list-product-item', ['product' => $dproduct])
                    @endforeach
                </div>
                @endif

                <style>
                    #related-products-scroll-container {
                        scroll-behavior: smooth;
                    }
                    #related-products-scroll-container .product-list-sidebar-item {
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    }
                    #related-products-scroll-container .product-list-sidebar-item:hover {
                        transform: translateX(4px);
                        background-color: #f8fafc;
                        border-color: #cbd5e1;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
                    }
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 5px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-track {
                        background: #f1f5f9;
                        border-radius: 10px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #cbd5e1;
                        border-radius: 10px;
                    }
                    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                        background: #94a3b8;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const scrollContainer = document.getElementById('related-products-scroll-container');
                        if (!scrollContainer) return;

                        let isHovered = false;
                        let autoScrollTimer = null;

                        scrollContainer.addEventListener('mouseenter', () => { isHovered = true; });
                        scrollContainer.addEventListener('mouseleave', () => { isHovered = false; });

                        // Smooth automatic scrolling interval
                        autoScrollTimer = setInterval(() => {
                            if (!isHovered && scrollContainer.scrollHeight > scrollContainer.clientHeight) {
                                const step = 70; // Scroll by roughly 1 item height
                                if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight - 10) {
                                    // Reset to top smoothly when reaching bottom
                                    scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
                                } else {
                                    scrollContainer.scrollBy({ top: step, behavior: 'smooth' });
                                }
                            }
                        }, 4000); // Auto-scroll every 4 seconds
                    });
                </script>
            </div>
            @endif
        </div>
</main>

{{-- --- product review form section -- --}}

@if (setting('general', 'show_review_form_section', '1') == '1')
<style>
    .ecom-feedback-section {
        background: #ffffff;
        border-radius: 12px;
        padding: 32px 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }

    .ecom-feedback-section .feedback-header-title {
        font-size: 18px;
        font-weight: 400;
        color: #212529;
        margin-bottom: 28px;
        line-height: 1.5;
        letter-spacing: -0.01em;
    }

    .ecom-feedback-section .rating-evaluation-container {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 24px;
    }

    .ecom-feedback-section .user-profile-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .ecom-feedback-section .user-profile-avatar::before {
        content: '';
        width: 42px;
        height: 42px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
        background-size: contain;
        background-repeat: no-repeat;
    }

    .ecom-feedback-section .star-rating-group {
        display: flex;
        gap: 12px;
        flex: 1;
        align-items: flex-start;
    }

    .ecom-feedback-section .individual-star-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: transform 0.15s ease;
    }

    .ecom-feedback-section .individual-star-item:hover {
        transform: translateY(-1px);
    }

    .ecom-feedback-section .star-svg-container {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 2px solid #e0e0e0;
        background: #ffffff;
    }

    .ecom-feedback-section .star-svg-icon {
        width: 24px;
        height: 24px;
        transition: all 0.2s ease;
    }

    .ecom-feedback-section .individual-star-item:not(.selected-active) .star-svg-icon {
        color: #e0e0e0;
    }

    .ecom-feedback-section .individual-star-item:not(.selected-active) .star-svg-container {
        border-color: #e0e0e0;
        background: #ffffff;
    }

    .ecom-feedback-section .individual-star-item.selected-active .star-svg-container {
        background: #ffc107;
        border-color: #ffc107;
    }

    .ecom-feedback-section .individual-star-item.selected-active .star-svg-icon {
        color: #ffffff;
    }

    .ecom-feedback-section .individual-star-item:hover:not(.selected-active) .star-svg-container {
        border-color: #ffb300;
    }

    .ecom-feedback-section .individual-star-item:hover:not(.selected-active) .star-svg-icon {
        color: #ffb300;
    }

    .ecom-feedback-section .star-rating-label {
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        white-space: nowrap;
        font-weight: 400;
        line-height: 1.2;
    }

    .ecom-feedback-section .individual-star-item.selected-active .star-rating-label {
        color: #495057;
        font-weight: 500;
    }

    .ecom-feedback-section .comment-input-textarea {
        width: 100%;
        min-height: 88px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 14px 16px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        margin-bottom: 16px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background: #ffffff;
        color: #495057;
        line-height: 1.5;
    }

    .ecom-feedback-section .image-upload-container {
        margin-bottom: 20px;
    }

    .ecom-feedback-section .image-upload-label {
        display: block;
        font-size: 14px;
        color: #495057;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .ecom-feedback-section .image-upload-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    .ecom-feedback-section .image-upload-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        background: #ffffff;
        color: #495057;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .ecom-feedback-section .image-upload-input:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .ecom-feedback-section .image-upload-input:hover {
        border-color: #adb5bd;
    }

    .ecom-feedback-section .image-preview-container {
        margin-top: 12px;
        display: none;
    }

    .ecom-feedback-section .image-preview-grid {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ecom-feedback-section .image-preview-item {
        position: relative;
        width: 80px;
        height: 80px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .ecom-feedback-section .image-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ecom-feedback-section .image-remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: none;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease;
    }

    .ecom-feedback-section .image-remove-btn:hover {
        background: #c82333;
    }

    .ecom-feedback-section .comment-input-textarea:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .ecom-feedback-section .comment-input-textarea::placeholder {
        color: #adb5bd;
        font-size: 14px;
    }

    .ecom-feedback-section .submit-feedback-button {
        background: #dc3545;
        color: #ffffff;
        border: none;
        padding: 11px 22px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: inherit;
        letter-spacing: 0.02em;
    }

    .ecom-feedback-section .submit-feedback-button:hover {
        background: #c82333;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
    }

    .ecom-feedback-section .submit-feedback-button:active {
        background: #bd2130;
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(220, 53, 69, 0.2);
    }

    .ecom-feedback-section .guest-info-container {
        margin-bottom: 20px;
    }

    .ecom-feedback-section .form-group {
        margin-bottom: 16px;
    }

    .ecom-feedback-section .form-group label {
        display: block;
        font-size: 14px;
        color: #495057;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .ecom-feedback-section .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        background: #ffffff;
        color: #495057;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .ecom-feedback-section .form-control:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .ecom-feedback-section .form-control:hover {
        border-color: #adb5bd;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .ecom-feedback-section {
            width: 100%;
            margin: 5px;
            margin-top: 10px;
            padding: 10px 5px;
        }

        .product-rating-summary {
            align-self: flex-start;
        }
    }

    @media (max-width: 576px) {
        .ecom-feedback-section .feedback-header-title {
            font-size: 15px;
        }

        .ecom-feedback-section {
            width: 100%;
            margin: 5px;
            margin-top: 10px;
            padding: 10px 5px;
        }

        .ecom-feedback-section .rating-evaluation-container {
            flex-direction: column;
            gap: 16px;
            align-items: center;
        }

        .ecom-feedback-section .star-rating-group {
            justify-content: center;
            gap: 8px;
        }

        .product-rating-summary {
            padding: 6px 10px;
        }

        .rating-display {
            gap: 4px;
        }

        /* .rating-number {
                        font-size: 23px;
                    } */

        .review-count {
            font-size: 11px;
        }
    }
</style>

<script>
    class EcomFeedbackRating {
        constructor() {
            this.starItems = document.querySelectorAll('.individual-star-item');
            this.selectedRating = 4; // Default: Good(4/5)
            this.commentTextarea = document.querySelector('.comment-input-textarea');
            this.submitButton = document.querySelector('.submit-feedback-button');
            this.imageInput = document.querySelector('.image-upload-input');
            this.imagePreviewContainer = document.querySelector('.image-preview-container');
            this.imagePreviewGrid = document.querySelector('.image-preview-grid');
            this.uploadedImages = [];
            this.form = document.getElementById('product-review-form');
            this.ratingInput = document.getElementById('selected-rating');

            this.initializeRating();
            this.bindEvents();
        }

        initializeRating() {
            this.updateStarDisplay();
        }

        bindEvents() {
            this.starItems.forEach((starItem, index) => {
                starItem.addEventListener('click', () => {
                    this.selectedRating = index + 1;
                    this.updateStarDisplay();
                });

                starItem.addEventListener('mouseenter', () => {
                    this.previewRating(index + 1);
                });
            });

            document.querySelector('.star-rating-group').addEventListener('mouseleave', () => {
                this.updateStarDisplay();
            });

            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmission();
            });

            this.imageInput.addEventListener('change', (e) => {
                this.handleImageUpload(e);
            });
        }

        updateStarDisplay() {
            this.starItems.forEach((starItem, index) => {
                const icon = starItem.querySelector('i');
                if (index < this.selectedRating) {
                    starItem.classList.add('selected-active');
                    if (icon) {
                        icon.className = 'fa-solid fa-star text-blue-900';
                    }
                } else {
                    starItem.classList.remove('selected-active');
                    if (icon) {
                        icon.className = 'fa-regular fa-star text-slate-300';
                    }
                }
            });
            // Update hidden input
            if (this.ratingInput) {
                this.ratingInput.value = this.selectedRating;
            }
        }

        previewRating(previewRating) {
            this.starItems.forEach((starItem, index) => {
                const icon = starItem.querySelector('i');
                if (index < previewRating) {
                    starItem.classList.add('selected-active');
                    if (icon) {
                        icon.className = 'fa-solid fa-star text-blue-900';
                    }
                } else {
                    starItem.classList.remove('selected-active');
                    if (icon) {
                        icon.className = 'fa-regular fa-star text-slate-300';
                    }
                }
            });
        }

        handleImageUpload(event) {
            const files = Array.from(event.target.files);

            files.forEach(file => {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const imageData = {
                            id: Date.now() + Math.random(),
                            file: file,
                            dataUrl: e.target.result,
                            name: file.name
                        };
                        this.uploadedImages.push(imageData);
                        this.updateImagePreview();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Clear the input
            event.target.value = '';
        }

        updateImagePreview() {
            if (this.uploadedImages.length > 0) {
                this.imagePreviewContainer.style.display = 'block';
                this.imagePreviewGrid.innerHTML = '';

                this.uploadedImages.forEach(imageData => {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'image-preview-item';
                    previewItem.innerHTML = `
                                        <img src="${imageData.dataUrl}" alt="${imageData.name}" class="image-preview-img">
                                        <button type="button" class="image-remove-btn" data-id="${imageData.id}">×</button>
                                    `;

                    const removeBtn = previewItem.querySelector('.image-remove-btn');
                    removeBtn.addEventListener('click', () => {
                        this.removeImage(imageData.id);
                    });

                    this.imagePreviewGrid.appendChild(previewItem);
                });
            } else {
                this.imagePreviewContainer.style.display = 'none';
            }
        }

        removeImage(imageId) {
            this.uploadedImages = this.uploadedImages.filter(img => img.id !== imageId);
            this.updateImagePreview();
        }

        handleSubmission() {
            const userComment = this.commentTextarea.value.trim();

            if (userComment === '') {
                alert('দয়া করে আপনার মতামত লিখুন।');
                this.commentTextarea.focus();
                return;
            }

            // Disable submit button to prevent double submission
            this.submitButton.disabled = true;
            this.submitButton.textContent = 'সাবমিট হচ্ছে...';

            // Create FormData object
            const formData = new FormData(this.form);

            // Add uploaded images to FormData
            this.uploadedImages.forEach((imageData, index) => {
                formData.append(`review_images[${index}]`, imageData.file);
            });

            // Submit review to backend
            fetch('{{ route('product.review.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        this.resetForm();
                        // Reload page to show new review
                        location.reload();
                    } else {
                        alert(data.message || 'কিছু ভুল হয়েছে। আবার চেষ্টা করুন।');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('কিছু ভুল হয়েছে। আবার চেষ্টা করুন।');
                })
                .finally(() => {
                    // Re-enable submit button
                    this.submitButton.disabled = false;
                    this.submitButton.textContent = 'আপনার মতামত সাবমিট করুন';
                });
        }

        resetForm() {
            this.commentTextarea.value = '';
            this.selectedRating = 4;
            this.uploadedImages = [];
            this.updateStarDisplay();
            this.updateImagePreview();
        }
    }

    // Initialize the rating system
    document.addEventListener('DOMContentLoaded', () => {
        new EcomFeedbackRating();
    });
</script>
@endif

@if (setting('general', 'show_product_description_section', '1') == '1' ||
setting('general', 'show_ratings_reviews_section', '1') == '1')
<div class="base-container product-sections max-w-container-max mx-auto px-margin-desktop my-4">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Product Description Card (7 Columns) -->
        @if (setting('general', 'show_product_description_section', '1') == '1' && !empty(trim($product->description)))
        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-4 lg:p-5 shadow-sm relative overflow-hidden" id="description">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 mb-3">
                <div class="w-7 h-7 rounded-lg bg-navy-deep/10 text-navy-deep flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-file-lines text-xs"></i>
                </div>
                <h3 class="text-sm font-extrabold text-navy-deep font-headline m-0">{{ setting('general', 'description_section_title', 'Product Description') }}</h3>
            </div>

            <div class="relative">
                <div id="description-content" class="description-formatted-wrapper text-slate-700 font-body text-xs leading-relaxed max-h-[360px] overflow-hidden transition-all duration-300">
                    {!! $product->description !!}
                </div>
                <div id="description-overlay" class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none"></div>
            </div>

            <div class="text-center pt-2">
                <button type="button" id="toggle-description-btn" class="inline-flex items-center gap-1.5 text-xs font-bold text-navy-deep hover:text-navy-deep/80 bg-slate-100 hover:bg-slate-200 px-4 py-1.5 rounded-full transition-all shadow-xs">
                    <span>Show More</span>
                    <i class="fa-solid fa-chevron-down text-[10px]" id="toggle-description-icon"></i>
                </button>
            </div>

            <style>
                .description-formatted-wrapper {
                    line-height: 1.6;
                    color: #334155;
                }
                .description-formatted-wrapper h1,
                .description-formatted-wrapper h2,
                .description-formatted-wrapper h3,
                .description-formatted-wrapper h4 {
                    color: #113257;
                    font-weight: 800;
                    margin-top: 0.75rem;
                    margin-bottom: 0.35rem;
                }
                .description-formatted-wrapper p {
                    margin-bottom: 0.5rem;
                    color: #475569;
                }
                .description-formatted-wrapper strong {
                    color: #0f172a;
                    font-weight: 700;
                }
                .description-formatted-wrapper hr {
                    border: 0;
                    height: 1px;
                    background: #e2e8f0;
                    margin: 0.75rem 0;
                }
                .description-formatted-wrapper table {
                    width: 100% !important;
                    border-collapse: collapse;
                    margin: 0.75rem 0;
                    border-radius: 8px;
                    overflow: hidden;
                    border: 1px solid #e2e8f0;
                }
                .description-formatted-wrapper table th {
                    background-color: #113257;
                    color: #ffffff;
                    font-weight: 700;
                    text-align: left;
                    padding: 8px 10px;
                    font-size: 11px;
                }
                .description-formatted-wrapper table td {
                    padding: 8px 10px;
                    border-bottom: 1px solid #f1f5f9;
                    font-size: 11px;
                    color: #334155;
                }
                .description-formatted-wrapper ul,
                .description-formatted-wrapper ol {
                    padding-left: 1.25rem;
                    margin: 0.5rem 0;
                }
                .description-formatted-wrapper li {
                    margin-bottom: 0.25rem;
                    font-size: 11px;
                }
            </style>
        </div>
        @endif

        <!-- Customer Ratings & Reviews Card (5 Columns) -->
        @if (setting('general', 'show_ratings_reviews_section', '1') == '1' && $reviewStats['review_count'] > 0)
        <div class="lg:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-4 lg:p-5 shadow-sm space-y-4" id="ratings">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-amber-400/15 text-amber-500 flex items-center justify-center font-bold text-xs">
                    <i class="fa-solid fa-star text-xs"></i>
                </div>
                <h3 class="text-sm font-extrabold text-navy-deep font-headline m-0">{{ setting('general', 'ratings_section_title', 'Ratings & Reviews') }}</h3>
                <span class="ml-auto bg-navy-deep/10 text-navy-deep text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $reviewStats['review_count'] }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                <!-- Average Rating Big Badge -->
                <div class="text-center space-y-0.5 border-b sm:border-b-0 sm:border-r border-slate-200/80 pb-2 sm:pb-0 sm:pr-3">
                    <div class="text-2xl font-black text-navy-deep tracking-tight font-headline">{{ number_format($reviewStats['average_rating'], 1) }}</div>
                    <div class="flex justify-center text-amber-400 gap-0.5 text-xs">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $reviewStats['average_rating'])
                            <i class="fa-solid fa-star"></i>
                            @elseif($i <= $reviewStats['average_rating'] + 0.5)
                            <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                            <i class="fa-regular fa-star text-slate-300"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="text-[10px] font-semibold text-slate-500">Based on {{ $reviewStats['review_count'] }} reviews</div>
                </div>

                <!-- Rating Distribution Bars -->
                <div class="sm:col-span-2 space-y-1">
                    @for ($rating = 5; $rating >= 1; $rating--)
                    @php
                    $count = $reviewStats['rating_distribution'][$rating] ?? 0;
                    $percentage = $reviewStats['review_count'] > 0 ? ($count / $reviewStats['review_count']) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-2 text-[10px]">
                        <span class="w-6 font-bold text-slate-600 flex items-center gap-0.5">
                            {{ $rating }} <i class="fa-solid fa-star text-[8px] text-amber-400"></i>
                        </span>
                        <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="w-5 text-right font-medium text-slate-400 text-[9px]">{{ $count }}</span>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Review Items List -->
            <div class="space-y-2.5 divide-y divide-slate-100 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                @forelse($reviews as $review)
                <div class="pt-2.5 first:pt-0 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full overflow-hidden bg-slate-200 border border-slate-300 shrink-0">
                                @if ($review->reviewer_image)
                                <img class="w-full h-full object-cover" src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}">
                                @else
                                <img class="w-full h-full object-cover" src="{{ asset('assets/img/man.png') }}" alt="{{ $review->reviewer_name }}">
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-xs text-navy-deep flex items-center gap-1">
                                    <span>{{ $review->reviewer_name }}</span>
                                    @if ($review->is_verified_purchase)
                                    <span class="inline-flex items-center text-[8px] font-bold bg-emerald-100 text-emerald-700 px-1 py-0.2 rounded-full">Verified</span>
                                    @endif
                                </div>
                                <div class="text-[9px] text-slate-400">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="flex text-amber-400 text-[10px] gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                <i class="fa-solid fa-star"></i>
                                @else
                                <i class="fa-regular fa-star text-slate-200"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <p class="text-xs text-slate-700 leading-relaxed font-body m-0">{{ $review->review_text }}</p>
                    @if ($review->review_images && is_array($review->review_images) && count($review->review_images) > 0)
                    <div class="flex gap-1.5 pt-0.5">
                        @foreach ($review->review_images as $image)
                        <img src="{{ asset($image) }}" alt="Review Image" class="w-10 h-10 object-cover rounded-md border border-slate-200">
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-4 text-slate-400 text-xs">
                    No reviews yet. Be the first to review this product!
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-description-btn');
        const descContent = document.getElementById('description-content');
        const descOverlay = document.getElementById('description-overlay');
        const toggleIcon = document.getElementById('toggle-description-icon');

        if (toggleBtn && descContent) {
            // Check if content exceeds max height
            if (descContent.scrollHeight <= 360) {
                descOverlay?.classList.add('hidden');
                toggleBtn.parentElement.classList.add('hidden');
            }

            toggleBtn.addEventListener('click', function() {
                const isExpanded = descContent.classList.contains('max-h-none');
                if (isExpanded) {
                    descContent.classList.remove('max-h-none');
                    descContent.classList.add('max-h-[360px]');
                    descOverlay?.classList.remove('hidden');
                    toggleBtn.querySelector('span').textContent = 'Show More';
                    toggleIcon.className = 'fa-solid fa-chevron-down text-[10px]';
                } else {
                    descContent.classList.remove('max-h-[360px]');
                    descContent.classList.add('max-h-none');
                    descOverlay?.classList.add('hidden');
                    toggleBtn.querySelector('span').textContent = 'Show Less';
                    toggleIcon.className = 'fa-solid fa-chevron-up text-[10px]';
                }
            });
        }
    });
</script>
@endif

<style>
    .best-selling-section {
        padding: 30px 50px;
        background-color: var(--body-bg);
    }

    .slider-navigation {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: {
                {
                setting('general', 'section_header_padding', '2px 5px')
            }
        }

        ;

        @if (setting('general', 'section_header_custom_border')) {
            ! ! setting('general', 'section_header_custom_border') ! !
        }

        @else border-bottom: 1px solid var(--border-color);

        @endif border-radius: {
                {
                setting('general', 'section_header_border_radius', '8px')
            }
        }

        ;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: var(--secondary-color);
        letter-spacing: 1.5px;
        position: relative;
        padding-left: 15px;
    }

    .section-header h2:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;

        background-color: {
                {
                setting('general', 'section_header_left_bar_color', 'var(--primary-color)')
            }
        }

        ;

        display: {
                {
                setting('general', 'section_header_left_bar', '0')=='1' ? 'block': 'none'
            }
        }

        ;
    }

    .view-all {
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 1px;
        padding: 8px 15px;
        /* border: 1px solid var(--border-color); */
        border: none;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .view-all:hover {
        background-color: var(--primary-color);
        color: var(--light-color);
        border-color: var(--primary-color);
    }

    .products-slider {
        position: relative;
        display: flex;
        align-items: center;
        padding: 0 5px;
    }

    .slider-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        background: rgba(255, 255, 255, 0.8);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: background 0.2s;
    }

    .slider-arrow:hover,
    .slider-arrow:focus {
        background: #f0f4fa;
    }

    .slider-arrow.prev-arrow {
        left: 10px;
    }

    .slider-arrow.next-arrow {
        right: 10px;
    }

    .prev-arrow {
        margin-right: 15px;
    }

    .next-arrow {
        margin-left: 15px;
    }

    .products-container {
        display: flex !important;
        align-items: stretch !important;
        gap: 15px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 15px 0px 15px 0;
        margin-bottom: 0px;
    }

    .products-container .product-card {
        display: flex !important;
        flex-direction: column !important;
        height: auto !important;
        min-height: 270px !important;
        box-sizing: border-box;
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease, border-color 0.4s ease !important;
    }

    .products-container .product-card .product-info {
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 auto !important;
        gap: 3px !important;
        padding: 6px 8px 8px 8px !important;
    }

    .products-container .product-card .product-title {
        margin: 0 0 2px 0 !important;
        padding: 0 !important;
        line-height: 1.25 !important;
    }

    .products-container .product-card .product-card-rating {
        margin: 0 0 2px 0 !important;
        padding: 0 !important;
    }

    .products-container .product-card .product-price {
        margin: 0 !important;
        padding: 0 !important;
    }

    .products-container .product-card .add-to-cart-btn,
    .products-container .product-card .buy-now-btn {
        background-color: #1e3a8a !important;
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%) !important;
        color: #ffffff !important;
        border-color: #1e3a8a !important;
        margin-top: auto !important;
        transition: all 0.3s ease !important;
    }

    .products-container .product-card .add-to-cart-btn:hover,
    .products-container .product-card .buy-now-btn:hover {
        background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%) !important;
        box-shadow: 0 4px 14px rgba(30, 58, 138, 0.4) !important;
        transform: translateY(-1px) !important;
    }

    .products-container .product-card .product-badge {
        background-color: #1d4ed8 !important;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: #ffffff !important;
    }

    .products-container .product-card .current-price,
    .products-container .product-card .price {
        color: #0f172a !important;
        font-weight: 700 !important;
    }

    .products-container .product-card .product-image {
        height: 160px !important;
        width: 100% !important;
        flex-shrink: 0;
    }

    .products-container .product-card:hover {
        transform: translateY(-8px) scale(1.02) !important;
        box-shadow: 0 14px 28px rgba(17, 50, 87, 0.12) !important;
        border-color: rgba(17, 50, 87, 0.2) !important;
    }

    .view-all-btn {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 30px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border: 2px solid var(--primary-color);
        transform: translateY(20px);
        opacity: 0;
        animation: fadeInUp 0.8s forwards 0.6s;
    }

    @media(max-width:992px) {
        .best-selling-section {
            padding: 5px 0px;
        }

        .section-header h2 {
            font-size: 15px;
        }

        .best-selling-section .section-header h2,
        .customer-reviews .section-header h2,
        .customer-reviews .section-header h2,
        .products-by-category .section-header h2 {
            font-size: 15px !important;
        }

        .section-header {
            padding-top: 5px;
            padding-bottom: 5px;
            margin-left: 5px;
            margin-right: 5px;
        }

        .products-slider {
            padding: 0px 0px;
        }

        .products-container {
            gap: 15px;
            padding: 10px 0 10px 0;
        }

        .slider-arrow {
            display: none;
        }
    }
</style>
<!-- Product By Category v2 with slide -->
@if (setting('single_product', 'enable_bottom_category_slider', '0') == '1' && !empty($SingleProductSliderCategories))
@foreach ($SingleProductSliderCategories as $i => $catData)
<section class="category-slider-section">
    <div class="base-container section-header">
        <h2>{{ $catData['category']->name }}</h2>
        <a href="{{ route('shop', $catData['category']->slug) }}"
            class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
    </div>
    <div class="base-container products-slider products-slider-category1-{{ $i }}">
        <button class="slider-arrow prev-arrow prev-arrow-category1-{{ $i }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="12" fill="none"></circle>
                <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
        <div class="products-container products-container-category1-{{ $i }}">
            @foreach ($catData['products'] as $cproduct)
            @include('frontend.partials.product-item', [
            'product' => $cproduct,
            'badge' => 'Shop!',
            'buttonText' => 'View Product',
            ])
            @endforeach
        </div>
        <button class="slider-arrow next-arrow next-arrow-category1-{{ $i }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="12" fill="none"></circle>
                <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>
</section>
@endforeach
@endif
<!-- End Product By Category v2 with slide -->

<!-- Additional Related Products Section -->
@if (setting('single_product', 'enable_additional_related_products', '0') == '1' && !empty($additional_related_products) && count($additional_related_products) > 0)
<section class="additional-related-products-section">
    <div class="base-container section-header">
        <h2>{{ setting('general', 'additional_related_products_section_title', 'More Favorites') }}</h2>
        <a href="{{ route('shop', $product->category->slug ?? '') }}" class="view-all">{{ \App\Services\SettingsService::getViewAllButtonText() }}</a>
    </div>
    <div class="base-container products-slider additional-related-products-slider">
        <button class="slider-arrow prev-arrow prev-arrow-additional-related">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="12" fill="none"></circle>
                <path d="M15 6L9 12L15 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
        <div class="products-container products-container-additional-related">
            @foreach ($additional_related_products as $relatedProduct)
            @include('frontend.partials.product-item', [
            'product' => $relatedProduct,
            'badge' => 'Shop!',
            'buttonText' => 'View Product',
            ])
            @endforeach
        </div>
        <button class="slider-arrow next-arrow next-arrow-additional-related">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="12" fill="none"></circle>
                <path d="M9 6L15 12L9 18" stroke="#2d4379" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
    </div>
</section>
{{-- Additional Related Products Slider JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize additional related products slider
        const additionalSlider = document.querySelector('.additional-related-products-slider');
        if (additionalSlider) {
            const productsContainer = additionalSlider.querySelector('.products-container-additional-related');
            const prevArrow = additionalSlider.querySelector('.prev-arrow-additional-related');
            const nextArrow = additionalSlider.querySelector('.next-arrow-additional-related');
            const productCards = productsContainer.querySelectorAll('.product-card');

            if (productsContainer && productCards.length > 0) {
                const enableInfiniteScroll = productCards.length >= 4;

                if (enableInfiniteScroll) {
                    // Clone the product cards for circular infinite scrolling
                    productCards.forEach(card => {
                        const clone = card.cloneNode(true);
                        productsContainer.appendChild(clone);
                    });

                    const singleSetWidth = productsContainer.scrollWidth / 2;

                    let isHovered = false;
                    let isMouseDown = false;
                    let startX, startScrollLeft;
                    let isDragging = false;
                    const scrollSpeed = 0.8;

                    function stepMarquee() {
                        if (!isHovered && !isMouseDown) {
                            productsContainer.scrollLeft += scrollSpeed;
                            if (productsContainer.scrollLeft >= singleSetWidth) {
                                productsContainer.scrollLeft = 0;
                            } else if (productsContainer.scrollLeft <= 0) {
                                productsContainer.scrollLeft = singleSetWidth;
                            }
                        }
                        requestAnimationFrame(stepMarquee);
                    }

                    requestAnimationFrame(stepMarquee);

                    productsContainer.addEventListener('mouseenter', () => { isHovered = true; });
                    productsContainer.addEventListener('mouseleave', () => { 
                        isHovered = false; 
                        isMouseDown = false; 
                        productsContainer.style.cursor = 'grab';
                    });
                    
                    // Intuitive Drag Scrolling (Drag right -> scroll left, Drag left -> scroll right)
                    productsContainer.addEventListener('mousedown', (e) => {
                        isMouseDown = true;
                        isHovered = true;
                        isDragging = false;
                        startX = e.pageX;
                        startScrollLeft = productsContainer.scrollLeft;
                        productsContainer.style.cursor = 'grabbing';
                        productsContainer.style.userSelect = 'none';
                    });

                    window.addEventListener('mouseup', () => {
                        if (isMouseDown) {
                            isMouseDown = false;
                            productsContainer.style.cursor = 'grab';
                            setTimeout(() => { isDragging = false; }, 50);
                        }
                    });

                    window.addEventListener('mousemove', (e) => {
                        if (!isMouseDown) return;
                        const dist = e.pageX - startX;
                        if (Math.abs(dist) > 5) isDragging = true;
                        
                        // Dragging right moves backward (shows previous items 10,9,8...)
                        let newScroll = startScrollLeft - dist;
                        if (newScroll < 0) {
                            newScroll += singleSetWidth;
                        } else if (newScroll >= singleSetWidth) {
                            newScroll -= singleSetWidth;
                        }
                        productsContainer.scrollLeft = newScroll;
                    });

                    // Prevent click navigation while dragging
                    productsContainer.addEventListener('click', (e) => {
                        if (isDragging) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, true);

                    // Touch Drag support
                    productsContainer.addEventListener('touchstart', (e) => {
                        isHovered = true;
                        startX = e.touches[0].pageX;
                        startScrollLeft = productsContainer.scrollLeft;
                    });

                    productsContainer.addEventListener('touchmove', (e) => {
                        const dist = e.touches[0].pageX - startX;
                        let newScroll = startScrollLeft - dist;
                        if (newScroll < 0) {
                            newScroll += singleSetWidth;
                        } else if (newScroll >= singleSetWidth) {
                            newScroll -= singleSetWidth;
                        }
                        productsContainer.scrollLeft = newScroll;
                    });

                    productsContainer.addEventListener('touchend', () => {
                        isHovered = false;
                    });

                    // Pause auto-scroll when hovering over prev/next navigation arrows
                    [prevArrow, nextArrow].forEach(arrow => {
                        if (arrow) {
                            arrow.addEventListener('mouseenter', () => { isHovered = true; });
                            arrow.addEventListener('mouseleave', () => { isHovered = false; });
                        }
                    });

                    if (nextArrow) {
                        nextArrow.addEventListener('click', () => {
                            let newPos = productsContainer.scrollLeft + 270;
                            if (newPos >= singleSetWidth) {
                                newPos -= singleSetWidth;
                            }
                            productsContainer.scrollTo({ left: newPos, behavior: 'smooth' });
                        });
                    }

                    if (prevArrow) {
                        prevArrow.addEventListener('click', () => {
                            let newPos = productsContainer.scrollLeft - 270;
                            if (newPos < 0) {
                                newPos += singleSetWidth;
                            }
                            productsContainer.scrollTo({ left: newPos, behavior: 'smooth' });
                        });
                    }
                } else {
                    // Simple scrolling for fewer than 4 products
                    const scrollAmount = 270;

                    if (nextArrow) {
                        nextArrow.addEventListener('click', () => {
                            const maxScrollLeft = productsContainer.scrollWidth -
                                productsContainer.clientWidth;
                            const newPosition = Math.min(productsContainer.scrollLeft +
                                scrollAmount, maxScrollLeft);
                            productsContainer.scrollTo({
                                left: newPosition,
                                behavior: 'smooth'
                            });
                        });
                    }

                    if (prevArrow) {
                        prevArrow.addEventListener('click', () => {
                            const newPosition = Math.max(productsContainer.scrollLeft -
                                scrollAmount, 0);
                            productsContainer.scrollTo({
                                left: newPosition,
                                behavior: 'smooth'
                            });
                        });
                    }
                }
            }
        }
    });
</script>
@endif
<!-- End Additional Related Products Section -->

@endsection

@section('scripts')
{{-- Shared quanity button --}}
<script>
    // Function to handle quantity changes and ensure minimum value is 1
    function handleQuantityChange() {
        const quantityInput = document.getElementById('sharedQuantity');
        if (quantityInput.value < 1) {
            quantityInput.value = 1;
        }
    }

    // Add event listener to shared quantity input
    document.getElementById('sharedQuantity').addEventListener('change', handleQuantityChange);

    // Function to update hidden quantity fields before form submission
    function updateQuantityBeforeSubmit(form) {
        const sharedQuantity = document.getElementById('sharedQuantity').value;
        const quantityField = form.querySelector('input[name="quantity"]');
        quantityField.value = sharedQuantity;
    }

    function optionsPrice(price, id) {
        // Update displayed price
        document.getElementById('updateOfferPrice').innerText = price;

        // Update price and option ID for both forms
        document.getElementById('cartPriceId').value = price;
        document.getElementById('buyPriceId').value = price;

        document.getElementById('cart_option_id').value = id;
        document.getElementById('buy_option_id').value = id;
    }

    function updateMainImage(src, btn) {
        const main = document.getElementById('main-product-image');
        if (!main) return;
        const btns = btn.parentElement.querySelectorAll('button, img');
        btns.forEach(b => {
            b.classList.remove('border-navy-deep', 'border-2', 'ring-4', 'ring-navy-deep/5');
            b.classList.add('border-outline-variant', 'border');
        });
        btn.classList.add('border-navy-deep', 'border-2', 'ring-4', 'ring-navy-deep/5');
        btn.classList.remove('border-outline-variant', 'border');
        main.style.opacity = '0.3';
        setTimeout(() => {
            main.src = src;
            main.style.opacity = '1';
        }, 150);
    }

    function changeQty(amount) {
        const input = document.getElementById('sharedQuantity');
        if (!input) return;
        let current = parseInt(input.value) || 1;
        if (current + amount >= 1) {
            input.value = current + amount;
            handleQuantityChange();
        }
    }

    function switchTab(tab) {
        const descTab = document.getElementById('desc-tab');
        const revTab = document.getElementById('rev-tab');
        const descContent = document.getElementById('description');
        const revContent = document.getElementById('ratings');

        if (tab === 'desc') {
            if (descTab) {
                descTab.classList.add('tab-active');
                descTab.classList.remove('text-on-surface-variant/60', 'border-transparent');
            }
            if (revTab) {
                revTab.classList.add('text-on-surface-variant/60', 'border-transparent');
                revTab.classList.remove('tab-active');
            }
            if (descContent) descContent.classList.remove('hidden');
            if (revContent) revContent.classList.add('hidden');
        } else {
            if (revTab) {
                revTab.classList.add('tab-active');
                revTab.classList.remove('text-on-surface-variant/60', 'border-transparent');
            }
            if (descTab) {
                descTab.classList.add('text-on-surface-variant/60', 'border-transparent');
                descTab.classList.remove('tab-active');
            }
            if (revContent) revContent.classList.remove('hidden');
            if (descContent) descContent.classList.add('hidden');
        }
    }
</script>

{{-- Display price --}}
<script>
    function optionsPrice(price, id, element) {
        // Update displayed price
        document.getElementById('updateOfferPrice').innerText = price;

        // Update price and option ID for both forms
        document.getElementById('cartPriceId').value = price;
        document.getElementById('buyPriceId').value = price;

        document.getElementById('cart_option_id').value = id;
        document.getElementById('buy_option_id').value = id;

        // Remove "selected" class from all options
        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add "selected" class to the clicked option
        element.classList.add('selected');
    }
</script>

{{-- Select Variation and cart --}}
<script>
    let selectedVariations = {};
    let basePrice = @json(
        $product->product_type === 'variable' ?
        ($minPrice ?? 0) :
        ($product->offer ?? $product->old_price)
    );
    let originalMainImage = "{{ asset('storage/' . $product->thumb_image) }}";
    let originalGalleryImages = [];

    // Store original gallery images on page load
    document.addEventListener('DOMContentLoaded', function() {
        const galleryImages = document.querySelectorAll('.product-gallery-image');
        galleryImages.forEach(img => {
            originalGalleryImages.push(img.src);
        });
    });

    // Initialize gallery on page load with maximum speed optimization
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize with only main product image for maximum speed
        initializeGalleryWithMainImage();

        // Load only main product gallery images after page load (variation images loaded on-demand only)
        setTimeout(() => {
            loadMainGalleryImages();
        }, 1000); // Load after 1 second

        // For variable products, auto-select first in-stock combination
        if (window.productType === 'variable' && window.variationCombinations && window.variationCombinations
            .length > 0) {
            autoSelectFirstInStockCombination();
        }
    });

    function collectAllVariationImages() {
        // This function is now only used for collecting image paths, not loading them
        // Images will be loaded on-demand when combinations are selected
        allVariationImages = [];

        if (window.variationCombinations && window.variationCombinations.length > 0) {
            window.variationCombinations.forEach(combination => {
                // Add combination featured image
                if (combination.featured_image && combination.featured_image.trim() !== '') {
                    allVariationImages.push(combination.featured_image);
                }

                // Add combination gallery images
                if (combination.gallery_images && Array.isArray(combination.gallery_images)) {
                    allVariationImages = allVariationImages.concat(combination.gallery_images);
                }
            });
        }

        // Remove duplicates
        allVariationImages = [...new Set(allVariationImages)];
        console.log('All variation combination image paths collected (not loaded):', allVariationImages);
    }

    function loadMainGalleryImages() {
        const gallery = document.getElementById('product-gallery');
        if (!gallery) return;

        // Get product gallery images only (not variation images)
        const galleryImagesData = gallery.getAttribute('data-gallery-images') || '[]';
        let productImages = [];

        try {
            productImages = JSON.parse(galleryImagesData);
            if (!Array.isArray(productImages)) {
                console.warn('Product images is not an array:', productImages);
                productImages = [];
            }
        } catch (e) {
            console.error('Error parsing product images:', e);
            productImages = [];
        }

        const featuredImage = "{{ $product->thumb_image }}";

        // Only include main product images (no variation images yet)
        currentGalleryImages = [featuredImage, ...productImages];

        // Remove duplicates
        currentGalleryImages = [...new Set(currentGalleryImages)];

        // Rebuild gallery with main images only
        buildGallery(currentGalleryImages);

        // Re-initialize navigation
        initializeNavigation();

        // If there's an auto-selected combination, load its images after main gallery
        if (window.autoSelectedCombination) {
            console.log('=== LOADING AUTO-SELECTED COMBINATION IMAGES ===');
            setTimeout(() => {
                loadAutoSelectedCombinationImages();
            }, 500); // Load combination images 0.5 seconds after main gallery
        }
    }

    // Auto-select first in-stock combination for variable products
    function autoSelectFirstInStockCombination() {
        console.log('=== AUTO-SELECTING FIRST IN-STOCK COMBINATION ===');

        // Find first in-stock combination
        const firstInStockCombination = window.variationCombinations.find(combo =>
            combo.is_active && combo.stock_quantity > 0
        );

        if (!firstInStockCombination) {
            console.log('No in-stock combinations found, keeping buttons disabled');
            return;
        }

        // Get the variation options for this combination
        const combinationOptions = Array.isArray(firstInStockCombination.variation_options) ?
            firstInStockCombination.variation_options :
            JSON.parse(firstInStockCombination.variation_options);

        // Auto-select each option button
        combinationOptions.forEach(optionId => {
            const optionButton = document.querySelector(`[data-option-id="${optionId}"]`);
            if (optionButton) {
                console.log('Auto-selecting option:', optionId);
                autoSelectVariationOption(optionButton);
            }
        });

        // Store the auto-selected combination for later image loading
        window.autoSelectedCombination = firstInStockCombination;

        console.log('Auto-selection completed');
    }

    // Load images for auto-selected combination
    function loadAutoSelectedCombinationImages() {
        if (!window.autoSelectedCombination) {
            console.log('No auto-selected combination found');
            return;
        }

        console.log('Loading images for auto-selected combination:', window.autoSelectedCombination);

        const combination = window.autoSelectedCombination;

        // Update gallery with combination images
        if (combination.featured_image || (combination.gallery_images && combination.gallery_images.length > 0)) {
            updateGalleryForCombination(combination.featured_image, combination.gallery_images);
        }

        // Clear the auto-selected combination reference
        window.autoSelectedCombination = null;
    }

    // Auto-select variation option (for auto-selection)
    function autoSelectVariationOption(button) {
        const variationId = button.dataset.variationId;
        const optionId = button.dataset.optionId;

        // Remove selected class from other buttons in same variation group
        const variationGroup = button.closest('.variation-options');
        variationGroup.querySelectorAll('.variation-option-btn').forEach(btn => {
            btn.classList.remove('selected');
        });

        // Add selected class to clicked button
        button.classList.add('selected');

        // Update selected variations tracking
        window.selectedVariations[variationId] = {
            optionId: parseInt(optionId),
            variationId: parseInt(variationId),
            button: button
        };

        // Update combination info (images will be loaded later)
        updateVariationCombinationForAutoSelection();
    }

    // Update combination for auto-selection (without immediate image loading)
    function updateVariationCombinationForAutoSelection() {
        const combinationInfo = document.getElementById('combination-info');
        const errorDiv = document.getElementById('variation-selection-error');
        const variationGroups = document.querySelectorAll('.variation-options');

        // Collect selected options
        let selectedOptions = [];
        let allSelected = true;

        // Check each variation group for selection
        variationGroups.forEach(group => {
            const selectedBtn = group.querySelector('.variation-option-btn.selected');
            if (selectedBtn) {
                selectedOptions.push(parseInt(selectedBtn.dataset.optionId));
            } else {
                allSelected = false;
            }
        });

        if (!allSelected) {
            combinationInfo.style.display = 'none';
            errorDiv.style.display = 'none';
            document.getElementById('selected_combination_id').value = '';
            updateTotalPrice(); // Reset price display
            updateButtonStates(); // Update button states
            return;
        }

        // Sort options to match combination key
        selectedOptions.sort((a, b) => a - b);

        // Find matching combination with robust comparison (SAME AS MANUAL SELECTION)
        const combination = window.variationCombinations.find(combo => {
            // Ensure combo.variation_options is an array of integers
            let comboOptions = combo.variation_options;
            if (typeof comboOptions === 'string') {
                comboOptions = JSON.parse(comboOptions);
            }
            if (!Array.isArray(comboOptions)) {
                return false;
            }

            // Convert all to integers and sort
            comboOptions = comboOptions.map(opt => parseInt(opt)).sort((a, b) => a - b);

            // Compare arrays length first
            if (comboOptions.length !== selectedOptions.length) {
                return false;
            }

            // Deep element-by-element comparison
            for (let i = 0; i < comboOptions.length; i++) {
                if (comboOptions[i] !== selectedOptions[i]) {
                    return false;
                }
            }

            return true;
        });

        if (combination) {
            // Display combination info with rich data
            const effectivePrice = combination.offer_price || combination.regular_price || combination.price;
            let priceHTML = `৳${effectivePrice}`;
            let discountHTML = '';

            if (combination.offer_price && combination.regular_price && combination.offer_price < combination
                .regular_price) {
                priceHTML =
                    `<span style="text-decoration: line-through; color: #999;">৳${combination.regular_price}</span> <span style="color: #e74c3c; font-weight: bold;">৳${combination.offer_price}</span>`;

                // Calculate discount percentage
                const discountPercentage = Math.round(((combination.regular_price - combination.offer_price) /
                    combination.regular_price) * 100);
                discountHTML =
                    `<span class="discount-badge" style="margin-left: 10px;">${discountPercentage}% OFF</span>`;
            }

            document.getElementById('combination-price').innerHTML = priceHTML + discountHTML;
            document.getElementById('combination-stock').textContent =
                combination.stock_quantity > 0 ?
                `In Stock: ${combination.stock_quantity} available` :
                'Out of Stock';

            // Store selected combination
            document.getElementById('selected_combination_id').value = combination.id;

            // Update descriptions
            updateVariationDescriptions();

            // Images will be loaded later via loadAutoSelectedCombinationImages()

            // FORCE HIDE ERROR FIRST (multiple methods for reliability)
            errorDiv.style.display = 'none';
            errorDiv.style.visibility = 'hidden';
            errorDiv.style.opacity = '0';
            errorDiv.setAttribute('style', 'display: none !important;');
            errorDiv.classList.add('hidden');

            // FORCE SHOW COMBINATION INFO (multiple methods for reliability)
            combinationInfo.style.display = 'block';
            combinationInfo.style.visibility = 'visible';
            combinationInfo.style.opacity = '1';
            combinationInfo.setAttribute('style', 'display: block !important; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;');
            combinationInfo.classList.remove('hidden');

            // Update main price display
            updateTotalPrice();

            // Update button states based on new combination
            updateButtonStates();
        } else {
            // FORCE HIDE COMBINATION INFO (multiple methods)
            combinationInfo.style.display = 'none';
            combinationInfo.style.visibility = 'hidden';
            combinationInfo.style.opacity = '0';
            combinationInfo.setAttribute('style', 'display: none !important;');
            combinationInfo.classList.add('hidden');

            // FORCE SHOW ERROR (multiple methods)
            errorDiv.textContent = 'This combination is not available.';
            errorDiv.style.display = 'block';
            errorDiv.style.visibility = 'visible';
            errorDiv.style.opacity = '1';
            errorDiv.setAttribute('style', 'display: block !important; margin: 10px 0; padding: 10px; background: #fee2e2; color: #dc2626; border-radius: 6px; border: 1px solid #fecaca;');
            errorDiv.classList.remove('hidden');

            document.getElementById('selected_combination_id').value = '';

            updateButtonStates(); // Update button states
        }
    }

    function initializeGalleryWithMainImage() {
        const gallery = document.getElementById('product-gallery');
        if (!gallery) return;

        const featuredImage = "{{ $product->thumb_image }}";

        // Start with only the main product image for speed
        currentGalleryImages = [featuredImage];

        console.log('Initial gallery with main image only:', currentGalleryImages);

        // Build initial gallery with main image only
        buildGallery(currentGalleryImages);

        // Initialize navigation
        initializeNavigation();
    }



    function initializeGallery() {
        const gallery = document.getElementById('product-gallery');
        if (!gallery) return;

        // Get initial images from data attribute with safe parsing
        const galleryImagesData = gallery.getAttribute('data-gallery-images') || '[]';
        let initialImages = [];

        try {
            initialImages = JSON.parse(galleryImagesData);
            if (!Array.isArray(initialImages)) {
                console.warn('Initial images is not an array:', initialImages);
                initialImages = [];
            }
        } catch (e) {
            console.error('Error parsing initial gallery images:', e);
            console.error('Raw data:', galleryImagesData);
            initialImages = [];
        }

        const featuredImage = "{{ $product->thumb_image }}";

        // Combine featured image, variation images, and product images
        currentGalleryImages = [featuredImage, ...allVariationImages, ...initialImages];

        // Remove duplicates
        currentGalleryImages = [...new Set(currentGalleryImages)];

        console.log('Initial gallery images:', currentGalleryImages);

        // Build initial gallery
        buildGallery(currentGalleryImages);

        // Initialize navigation
        initializeNavigation();
    }

    function buildGallery(images) {
        const gallery = document.getElementById('product-gallery');
        if (!gallery) return;

        console.log('Building gallery with images:', images);

        // Clear existing gallery
        gallery.innerHTML = '';

        // Build new gallery
        images.forEach((image, index) => {
            const img = document.createElement('img');
            img.className = 'product-gallery-image' + (index === 0 ? ' active' : '');
            img.src = '/storage/' + image;
            img.alt = '{{ $product->title }}';
            img.setAttribute('data-index', index);

            // Add click handler for thumbnail
            img.addEventListener('click', function() {
                selectGalleryImage(index);
            });

            gallery.appendChild(img);
        });

        // Update main image to first image
        if (images.length > 0) {
            const firstImage = '/storage/' + images[0];
            updateMainImage(firstImage);
            console.log('Set main image to:', firstImage);
        }

        // Reset current index
        currentImageIndex = 0;
    }

    function initializeNavigation() {
        const gallery = document.getElementById('product-gallery');

        // Check if gallery exists
        if (!gallery) {
            return;
        }

        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        const thumbnails = gallery.querySelectorAll('.product-gallery-image');

        // Always show navigation buttons if there are more than 1 image
        if (thumbnails.length > 1) {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        } else {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        }

        // Remove existing event listeners by cloning
        if (prevBtn) {
            const newPrevBtn = prevBtn.cloneNode(true);
            prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
        }
        if (nextBtn) {
            const newNextBtn = nextBtn.cloneNode(true);
            nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
        }

        // Get fresh references
        const freshPrevBtn = document.querySelector('.prev-btn');
        const freshNextBtn = document.querySelector('.next-btn');

        // Add navigation event listeners
        if (freshPrevBtn) {
            freshPrevBtn.addEventListener('click', function() {
                navigateGallery('prev');
            });
        }

        if (freshNextBtn) {
            freshNextBtn.addEventListener('click', function() {
                navigateGallery('next');
            });
        }

        // Initialize scroll navigation
        initializeScrollNavigation();

        // Update button states
        updateNavButtonStates();

        // Initialize drag functionality
        initializeDragGallery();
    }

    function navigateGallery(direction) {
        const thumbnails = document.querySelectorAll('.product-gallery-image');

        if (direction === 'prev' && currentImageIndex > 0) {
            currentImageIndex--;
        } else if (direction === 'next' && currentImageIndex < currentGalleryImages.length - 1) {
            currentImageIndex++;
        }

        // Update active thumbnail
        thumbnails.forEach((img, i) => {
            img.classList.toggle('active', i === currentImageIndex);
        });

        // Update main image
        if (currentImageIndex >= 0 && currentImageIndex < currentGalleryImages.length) {
            const selectedImage = '/storage/' + currentGalleryImages[currentImageIndex];
            updateMainImage(selectedImage);
        }

        // Scroll to active thumbnail
        const activeThumbnail = document.querySelector('.product-gallery-image.active');
        if (activeThumbnail) {
            activeThumbnail.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        }

        // Update navigation button states
        updateNavButtonStates();
    }

    function initializeScrollNavigation() {
        const gallery = document.getElementById('product-gallery');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');

        if (!gallery) return;

        function updateNavButtons() {
            const scrollLeft = gallery.scrollLeft;
            const maxScrollLeft = gallery.scrollWidth - gallery.clientWidth;

            if (prevBtn) {
                if (scrollLeft <= 0) {
                    prevBtn.classList.add('hidden');
                } else {
                    prevBtn.classList.remove('hidden');
                }
            }

            if (nextBtn) {
                if (scrollLeft >= maxScrollLeft - 5) {
                    nextBtn.classList.add('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                }
            }
        }

        // Update button states on scroll
        gallery.addEventListener('scroll', updateNavButtons);

        // Initial button state
        updateNavButtons();
    }

    function updateNavButtonStates() {
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');

        if (prevBtn) {
            prevBtn.classList.toggle('hidden', currentImageIndex <= 0);
        }

        if (nextBtn) {
            nextBtn.classList.toggle('hidden', currentImageIndex >= currentGalleryImages.length - 1);
        }
    }

    function selectGalleryImage(index) {
        const thumbnails = document.querySelectorAll('.product-gallery-image');

        if (index >= 0 && index < currentGalleryImages.length) {
            // Update active thumbnail
            thumbnails.forEach((img, i) => {
                img.classList.toggle('active', i === index);
            });

            // Update main image
            const selectedImage = '/storage/' + currentGalleryImages[index];
            updateMainImage(selectedImage);

            // Update current index
            currentImageIndex = index;

            // Update navigation button states
            updateNavButtonStates();
        }
    }

    function updateMainImage(imageSrc) {
        const mainImage = document.getElementById('main-product-image');
        if (mainImage) {
            mainImage.src = imageSrc;
            console.log('Updated main image to:', imageSrc);

            // Update zoom background if zoom is enabled
            const zoomFigure = mainImage.closest('figure');
            if (zoomFigure) {
                zoomFigure.style.backgroundImage = "url('" + imageSrc + "')";
            }
        }
    }

    // Function to update gallery for combinations (new system) - loads images on-demand
    function updateGalleryForCombination(combinationFeaturedImage, combinationGalleryImages) {
        const productFeaturedImage = "{{ $product->thumb_image }}";

        // Check if gallery element exists
        const galleryElement = document.getElementById('product-gallery');
        const productImagesData = galleryElement ? (galleryElement.getAttribute('data-gallery-images') || '[]') : '[]';
        let productImages = [];

        try {
            productImages = JSON.parse(productImagesData);
            if (!Array.isArray(productImages)) {
                console.warn('Product images is not an array:', productImages);
                productImages = [];
            }
        } catch (e) {
            console.error('Error parsing product images:', e);
            console.error('Raw data:', productImagesData);
            productImages = [];
        }

        let combinationImageList = [];

        // Add combination images
        if (combinationFeaturedImage && combinationFeaturedImage.trim() !== '') {
            combinationImageList.push(combinationFeaturedImage);
        }

        if (combinationGalleryImages && combinationGalleryImages.length > 0) {
            if (Array.isArray(combinationGalleryImages)) {
                combinationImageList = combinationImageList.concat(combinationGalleryImages);
            }
        }

        // Build new gallery order
        let newImages = [];
        newImages = newImages.concat(combinationImageList);

        if (!newImages.includes(productFeaturedImage)) {
            newImages.push(productFeaturedImage);
        }

        productImages.forEach(img => {
            if (!newImages.includes(img)) {
                newImages.push(img);
            }
        });

        currentGalleryImages = [...new Set(newImages)];

        // Update main product image if combination has featured image
        if (combinationFeaturedImage && combinationFeaturedImage.trim() !== '') {
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                const imagePath = '/storage/' + combinationFeaturedImage;
                mainImage.src = imagePath;

                // Update zoom background if zoom is enabled
                const zoomFigure = mainImage.closest('figure.zoom');
                if (zoomFigure) {
                    zoomFigure.style.backgroundImage = "url('" + imagePath + "')";
                }
            }
        }

        // Rebuild gallery with on-demand loaded images (only if gallery exists)
        if (galleryElement) {
            buildGallery(currentGalleryImages);

            // Re-initialize navigation
            initializeNavigation();

            // Ensure first image (combination image) is active
            if (currentGalleryImages.length > 0) {
                currentImageIndex = 0;
                const thumbnails = document.querySelectorAll('.product-gallery-image');
                thumbnails.forEach((img, i) => {
                    img.classList.toggle('active', i === 0);
                });
                updateNavButtonStates();
            }
        }
    }

    // Function to update gallery for variations (legacy - kept for backward compatibility)
    function updateGalleryForVariation(variationThumb, variationImages) {
        const featuredImage = "{{ $product->thumb_image }}";

        // Check if gallery element exists
        const galleryElement = document.getElementById('product-gallery');
        const productImagesData = galleryElement ? (galleryElement.getAttribute('data-gallery-images') || '[]') : '[]';
        let productImages = [];

        try {
            productImages = JSON.parse(productImagesData);
            if (!Array.isArray(productImages)) {
                console.warn('Product images is not an array:', productImages);
                productImages = [];
            }
        } catch (e) {
            console.error('Error parsing product images:', e);
            console.error('Raw data:', productImagesData);
            productImages = [];
        }

        let variationImageList = [];

        // Add variation thumb image if it exists
        if (variationThumb && variationThumb.trim() !== '') {
            variationImageList.push(variationThumb);
            console.log('Added variation thumb:', variationThumb);
        }

        // Add variation additional images if they exist
        if (variationImages && variationImages.length > 0) {
            let parsedImages = variationImages;
            if (typeof variationImages === 'string') {
                try {
                    parsedImages = JSON.parse(variationImages);
                } catch (e) {
                    parsedImages = [];
                }
            }

            if (Array.isArray(parsedImages)) {
                variationImageList = variationImageList.concat(parsedImages);
            }
        }

        // Build new gallery order
        let newImages = [];
        newImages = newImages.concat(variationImageList);

        if (!newImages.includes(featuredImage)) {
            newImages.push(featuredImage);
        }

        productImages.forEach(img => {
            if (!newImages.includes(img)) {
                newImages.push(img);
            }
        });

        currentGalleryImages = [...new Set(newImages)];

        // Update main product image if variation has thumb
        if (variationThumb && variationThumb.trim() !== '') {
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                const imagePath = '/storage/' + variationThumb;
                mainImage.src = imagePath;

                // Update zoom background if zoom is enabled
                const zoomFigure = mainImage.closest('figure.zoom');
                if (zoomFigure) {
                    zoomFigure.style.backgroundImage = "url('" + imagePath + "')";
                }
            }
        }

        // Rebuild gallery (only if gallery exists)
        if (galleryElement) {
            buildGallery(currentGalleryImages);

            // Re-initialize navigation
            initializeNavigation();

            // Ensure first image (variation image) is active
            if (currentGalleryImages.length > 0) {
                currentImageIndex = 0;
                const thumbnails = document.querySelectorAll('.product-gallery-image');
                thumbnails.forEach((img, i) => {
                    img.classList.toggle('active', i === 0);
                });
                updateNavButtonStates();
            }
        }
    }

    // Function to restore original gallery (call this when no variation is selected)
    function restoreOriginalGallery() {

        const featuredImage = "{{ $product->thumb_image }}";

        // Check if gallery element exists
        const galleryElement = document.getElementById('product-gallery');
        if (!galleryElement) {
            return; // Exit gracefully if no gallery
        }

        const productImagesData = galleryElement.getAttribute('data-gallery-images') || '[]';
        let productImages = [];

        try {
            productImages = JSON.parse(productImagesData);
            if (!Array.isArray(productImages)) {
                console.warn('Product images is not an array:', productImages);
                productImages = [];
            }
        } catch (e) {
            console.error('Error parsing product images:', e);
            console.error('Raw data:', productImagesData);
            productImages = [];
        }

        // Only include main product images
        currentGalleryImages = [featuredImage, ...productImages];
        currentGalleryImages = [...new Set(currentGalleryImages)];

        buildGallery(currentGalleryImages);
        initializeNavigation();
    }

    // Function to check stock availability
    function checkStockAvailability(quantity) {
        if (window.productType === 'variable') {
            const combinationId = document.getElementById('selected_combination_id');
            if (!combinationId || !combinationId.value || combinationId.value.trim() === '') {
                return {
                    available: false,
                    message: 'This combination is not available.'
                };
            }

            const combination = window.variationCombinations.find(combo => combo.id == combinationId.value);
            if (!combination) {
                return {
                    available: false,
                    message: 'This combination is not available.'
                };
            }

            const stockQty = (combination.stock_quantity !== undefined && combination.stock_quantity !== null) ? parseInt(combination.stock_quantity) : 0;
            if (stockQty <= 0) {
                return {
                    available: false,
                    message: 'This combination is out of stock.'
                };
            }

            if (quantity > stockQty) {
                return {
                    available: false,
                    message: `Only ${stockQty} items available for this combination.`
                };
            }

            return {
                available: true,
                message: ''
            };
        } else {
            if (window.productStock <= 0) {
                return {
                    available: false,
                    message: 'This product is out of stock.'
                };
            }

            if (quantity > window.productStock) {
                return {
                    available: false,
                    message: `Only ${window.productStock} items available.`
                };
            }

            return {
                available: true,
                message: ''
            };
        }
    }

    // Function to update button states based on stock and combination validity
    function updateButtonStates() {
        const sharedQtyEl = document.getElementById('sharedQuantity');
        const quantity = sharedQtyEl ? (parseInt(sharedQtyEl.value) || 1) : 1;
        const stockCheck = checkStockAvailability(quantity);
        const cartBtn = document.querySelector('.single-cart-btn') || document.querySelector('#cartForm button');
        const buyNowBtn = document.querySelector('.single-buynow-btn') || document.querySelector('#buyNowForm button');
        const errorDiv = document.getElementById('variation-selection-error');

        if (!stockCheck.available) {
            if (errorDiv) {
                errorDiv.textContent = stockCheck.message || 'This combination is not available.';
                errorDiv.style.display = 'block';
            }

            [cartBtn, buyNowBtn].forEach(btn => {
                if (btn) {
                    if (btn.tagName === 'BUTTON') btn.disabled = true;
                    btn.style.opacity = '0.4';
                    btn.style.cursor = 'not-allowed';
                    btn.style.pointerEvents = 'none';
                }
            });
        } else {
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }

            [cartBtn, buyNowBtn].forEach(btn => {
                if (btn) {
                    if (btn.tagName === 'BUTTON') btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    btn.style.pointerEvents = 'auto';
                }
            });
        }
    }

    // Function to update total price
    function updateTotalPrice() {
        // Check if this is a combo offer product first
        const comboOffer = document.querySelector('.combo-offer');
        if (comboOffer) {
            // This is a combo offer, use combo pricing
            const comboId = comboOffer.dataset.comboId;
            const comboData = window.comboOfferData ? window.comboOfferData[comboId] : null;

            if (comboData) {
                let quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;
                if (quantity < 1) quantity = 1;

                // Use combo price
                const totalPrice = parseFloat(comboData.combo_price) * quantity;

                // Update the price display
                document.getElementById('updateOfferPrice').innerHTML = totalPrice.toFixed(2) + '৳';
                return;
            }
        }

        // Regular product pricing logic
        const combinationId = document.getElementById('selected_combination_id');
        let totalPrice = 0;
        let discountHTML = '';

        if (window.productType === 'variable') {
            if (combinationId && combinationId.value) {
                // Find the selected combination price
                const combination = window.variationCombinations.find(combo => combo.id == combinationId.value);
                if (combination) {
                    // Use effective price (offer price if available, otherwise regular price)
                    totalPrice = parseFloat(combination.offer_price || combination.regular_price || combination.price);

                    // Check for discount
                    if (combination.offer_price && combination.regular_price && combination.offer_price < combination
                        .regular_price) {
                        const discountPercentage = Math.round(((combination.regular_price - combination.offer_price) /
                            combination.regular_price) * 100);
                        discountHTML = `<span class="discount-badge">${discountPercentage}% OFF</span>`;
                    }
                } else {
                    // Show price range if no combination selected
                    const prices = window.variationCombinations.map(combo =>
                        parseFloat(combo.offer_price || combo.regular_price || combo.price)
                    );
                    const minPrice = Math.min(...prices);
                    const maxPrice = Math.max(...prices);

                    if (minPrice === maxPrice) {
                        document.getElementById('updateOfferPrice').innerHTML = minPrice + '৳';
                    } else {
                        document.getElementById('updateOfferPrice').innerHTML = minPrice + '৳ - ' + maxPrice + '৳';
                    }
                    return;
                }
            } else {
                // Show price range for variable products when no combination is selected
                const prices = window.variationCombinations.map(combo =>
                    parseFloat(combo.offer_price || combo.regular_price || combo.price)
                );
                if (prices.length > 0) {
                    const minPrice = Math.min(...prices);
                    const maxPrice = Math.max(...prices);

                    if (minPrice === maxPrice) {
                        document.getElementById('updateOfferPrice').innerHTML = minPrice + '৳';
                    } else {
                        document.getElementById('updateOfferPrice').innerHTML = minPrice + '৳ - ' + maxPrice + '৳';
                    }
                }
                return;
            }
        } else {
            // For simple products, use the base price
            totalPrice = @json($product->offer ?? $product->old_price);
        }

        // Get quantity
        let quantity = parseInt(document.getElementById('sharedQuantity').value);
        if (quantity < 1) quantity = 1;

        // Multiply total price by quantity
        totalPrice *= quantity;

        // Update the price display with discount badge
        document.getElementById('updateOfferPrice').innerHTML = totalPrice.toFixed(2) + '৳' + discountHTML;
    }

    // Function to handle cart form submission
    function updateCartForm(event) {
        event.preventDefault(); // Prevent default form submission

        // Check if this is a combo offer product
        const comboOffer = document.querySelector('.combo-offer');
        console.log('Combo offer element found:', comboOffer);
        console.log('All combo-offer elements:', document.querySelectorAll('.combo-offer'));

        if (comboOffer) {
            // This is a combo offer, handle it differently
            const comboId = comboOffer.dataset.comboId;
            console.log('Combo offer detected, calling addComboToCart for comboId:', comboId);

            if (window.comboOfferSystem && window.comboOfferSystem.addComboToCart) {
                window.comboOfferSystem.addComboToCart(comboId);
                return false;
            } else {
                console.error('Combo offer system not available');
                alert('Combo offer system not available. Please refresh the page and try again.');
                return false;
            }
        }

        // If we reach here, it's a regular product (not a combo)
        console.log('Processing regular product add to cart');

        const errorDiv = document.getElementById('variation-selection-error');
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;

        // Check stock availability
        const stockCheck = checkStockAvailability(quantity);
        if (!stockCheck.available) {
            if (errorDiv) {
                errorDiv.textContent = stockCheck.message || 'This combination is not available.';
                errorDiv.style.display = "block";
                errorDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
            return false;
        }

        // Clear any existing error messages
        if (errorDiv) {
            errorDiv.style.display = "none";
        }

        // Update hidden input
        document.getElementById('cartQuantity').value = quantity;

        // Clear previous hidden inputs
        document.getElementById('variationInputs').innerHTML = '';

        if (window.productType === 'variable') {
            const combinationId = document.getElementById('selected_combination_id');
            if (combinationId && combinationId.value) {
                // Add combination ID to form data
                document.getElementById('variationInputs').innerHTML = `
                        <input type="hidden" name="combination_id" value="${combinationId.value}">
                    `;
            }
        }

        // Submit the form
        document.getElementById('cartForm').submit();
    }

    // Function to handle buy now form submission
    function updateBuyNowForm(event) {
        event.preventDefault(); // Prevent default form submission

        const errorDiv = document.getElementById('variation-selection-error');
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;

        // Check stock availability
        const stockCheck = checkStockAvailability(quantity);
        if (!stockCheck.available) {
            if (errorDiv) {
                errorDiv.textContent = stockCheck.message || 'This combination is not available.';
                errorDiv.style.display = "block";
                errorDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
            return false;
        }

        // Clear any existing error messages
        if (errorDiv) {
            errorDiv.style.display = "none";
        }

        let totalPrice = 0;

        if (window.productType === 'variable') {
            const combinationId = document.getElementById('selected_combination_id');
            if (combinationId && combinationId.value) {
                // Use combination pricing
                const combination = window.variationCombinations.find(combo => combo.id == combinationId.value);
                if (combination) {
                    totalPrice = combination.offer_price || combination.regular_price || combination.price;
                }
            }
        } else {
            // Use simple product pricing
            totalPrice = @json($product->offer ?? $product->old_price);
        }

        // Multiply total price by quantity
        totalPrice *= quantity;

        // Update form fields
        document.getElementById('buyQuantity').value = quantity;
        document.getElementById('buyPriceId').value = totalPrice.toFixed(2);
        document.getElementById('buyMainPrice').value = totalPrice.toFixed(2);

        // Clear previous hidden inputs
        document.getElementById('buyVariationInputs').innerHTML = '';

        if (window.productType === 'variable') {
            const combinationId = document.getElementById('selected_combination_id');
            if (combinationId && combinationId.value) {
                // Add combination ID to form data
                document.getElementById('buyVariationInputs').innerHTML = `
                        <input type="hidden" name="combination_id" value="${combinationId.value}">
                    `;
            }
        }

        // Submit the form
        document.getElementById('buyNowForm').submit();
    }

    // Initialize button states and form handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize button states
        updateButtonStates();

        // Add form submission handlers
        const cartForm = document.getElementById('cartForm');
        const buyNowForm = document.getElementById('buyNowForm');

        if (cartForm) {
            cartForm.addEventListener('submit', updateCartForm);
        }

        if (buyNowForm) {
            buyNowForm.addEventListener('submit', updateBuyNowForm);
        }

        // Listen for quantity changes
        const quantityInput = document.getElementById('sharedQuantity');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                const quantity = parseInt(this.value) || 1;
                const stockCheck = checkStockAvailability(quantity);

                // If quantity exceeds stock, show warning
                if (!stockCheck.available) {
                    this.style.borderColor = '#e74c3c';
                    this.title = stockCheck.message;
                } else {
                    this.style.borderColor = '';
                    this.title = '';
                }

                updateTotalPrice();
                updateButtonStates();
            });
        }
    });

    // Add this to your existing gallery script
    function initializeDragGallery() {
        const gallery = document.getElementById('product-gallery');
        if (!gallery) return;

        let isDragging = false;
        let startPos = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID = 0;
        let startTime = 0;
        let velocity = 0;

        // Touch events for mobile
        gallery.addEventListener('touchstart', dragStart);
        gallery.addEventListener('touchmove', drag);
        gallery.addEventListener('touchend', dragEnd);

        // Mouse events for desktop
        gallery.addEventListener('mousedown', dragStart);
        gallery.addEventListener('mousemove', drag);
        gallery.addEventListener('mouseup', dragEnd);
        gallery.addEventListener('mouseleave', dragEnd);

        // Prevent context menu on right click
        gallery.addEventListener('contextmenu', e => e.preventDefault());

        function dragStart(event) {
            isDragging = true;
            startTime = Date.now();

            // Cancel any ongoing animation
            if (animationID) {
                cancelAnimationFrame(animationID);
            }

            // Get starting position
            if (event.type === 'touchstart') {
                startPos = event.touches[0].clientX;
            } else {
                startPos = event.clientX;
            }

            // Store current scroll position
            prevTranslate = gallery.scrollLeft;

            // Change cursor
            gallery.style.cursor = 'grabbing';
            gallery.style.scrollBehavior = 'auto'; // Disable smooth scroll during drag
        }

        function drag(event) {
            if (!isDragging) return;

            event.preventDefault();

            // Get current position
            let currentPos;
            if (event.type === 'touchmove') {
                currentPos = event.touches[0].clientX;
            } else {
                currentPos = event.clientX;
            }

            // Calculate distance moved
            const diff = currentPos - startPos;

            // Update scroll position (invert direction for natural feel)
            gallery.scrollLeft = prevTranslate - diff;

            // Calculate velocity
            const currentTime = Date.now();
            const timeDiff = currentTime - startTime;
            if (timeDiff > 0) {
                velocity = diff / timeDiff;
            }
        }

        function dragEnd(event) {
            if (!isDragging) return;

            isDragging = false;
            gallery.style.cursor = 'grab';
            gallery.style.scrollBehavior = 'smooth'; // Re-enable smooth scroll

            // Apply momentum scrolling
            if (Math.abs(velocity) > 0.5) {
                applyMomentum(velocity);
            }

            // Update navigation buttons after drag
            setTimeout(() => {
                updateNavButtonStates();
            }, 100);
        }

        function applyMomentum(initialVelocity) {
            let currentVelocity = initialVelocity;
            const friction = 0.95;

            function animate() {
                if (Math.abs(currentVelocity) > 0.1) {
                    gallery.scrollLeft -= currentVelocity * 10;
                    currentVelocity *= friction;
                    animationID = requestAnimationFrame(animate);
                }
            }

            animate();
        }

        // Prevent image clicks when dragging
        gallery.addEventListener('click', function(event) {
            if (isDragging) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    }
</script>

<script>
    function zoom(e) {
        var zoomer = e.currentTarget;
        e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX;
        e.offsetY ? offsetY = e.offsetY : offsetY = e.touches[0].pageY; // Fixed the typo here
        x = offsetX / zoomer.offsetWidth * 100;
        y = offsetY / zoomer.offsetHeight * 100;
        zoomer.style.backgroundPosition = x + '% ' + y + '%';
    }

    // Update zoom when gallery images are clicked
    document.addEventListener('DOMContentLoaded', function() {
        const galleryImages = document.querySelectorAll('.product-gallery-image');
        if (galleryImages.length > 0) {
            galleryImages.forEach(img => {
                img.addEventListener('click', function() {
                    const mainImg = document.getElementById('main-product-image');
                    const zoomFigure = mainImg.parentElement;

                    // Update main image
                    mainImg.src = this.src;

                    // Update zoom background image
                    zoomFigure.style.backgroundImage = "url('" + this.src + "')";

                    // Update active state
                    galleryImages.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }
    });
</script>

{{-- Product Sections Navigation --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Section navigation functionality
        const sectionNavButtons = document.querySelectorAll('.section-nav-button');
        const sections = document.querySelectorAll('.product-description-section, .product-ratings-section');

        sectionNavButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                sectionNavButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                // Smooth scroll to corresponding section
                const sectionId = this.getAttribute('data-section');
                const targetSection = document.getElementById(sectionId);

                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Highlight active section based on scroll position
        function highlightActiveSection() {
            const scrollPosition = window.scrollY + 100; // Offset for better detection

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionBottom = sectionTop + section.offsetHeight;
                const sectionId = section.id;

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    // Remove active class from all buttons
                    sectionNavButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to corresponding button
                    const activeButton = document.querySelector(`[data-section="${sectionId}"]`);
                    if (activeButton) {
                        activeButton.classList.add('active');
                    }
                }
            });
        }

        // Add scroll event listener
        window.addEventListener('scroll', highlightActiveSection);

        // Initial call to set active section
        highlightActiveSection();

        // Review form validation
        const reviewForm = document.querySelector('.review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                const rating = document.querySelector('input[name="rating"]:checked');
                const title = document.getElementById('review_title');
                const content = document.getElementById('review_content');

                let isValid = true;

                // Check if rating is selected
                if (!rating) {
                    isValid = false;
                    alert('Please select a rating');
                }

                // Check if title is entered
                if (title.value.trim() === '') {
                    isValid = false;
                    title.classList.add('error');
                } else {
                    title.classList.remove('error');
                }

                // Check if content is entered
                if (content.value.trim() === '') {
                    isValid = false;
                    content.classList.add('error');
                } else {
                    content.classList.remove('error');
                }

                // If guest user, validate name and email
                const nameInput = document.getElementById('reviewer_name');
                const emailInput = document.getElementById('reviewer_email');

                if (nameInput && emailInput) {
                    if (nameInput.value.trim() === '') {
                        isValid = false;
                        nameInput.classList.add('error');
                    } else {
                        nameInput.classList.remove('error');
                    }

                    if (emailInput.value.trim() === '' || !validateEmail(emailInput.value)) {
                        isValid = false;
                        emailInput.classList.add('error');
                    } else {
                        emailInput.classList.remove('error');
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        }

        // Email validation helper
        function validateEmail(email) {
            const re =
                /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    });
</script>


<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update zoom background image
        function updateZoomBackground() {
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                const zoomContainer = mainImage.closest('figure');
                if (zoomContainer) {
                    zoomContainer.style.backgroundImage = `url('${mainImage.src}')`;
                }
            }
        }

        // Add event listener to all gallery images
        document.querySelectorAll('.product-gallery-image').forEach(img => {
            img.addEventListener('click', function() {
                // Wait a small amount of time for the main image to update
                setTimeout(updateZoomBackground, 50);
            });
        });

        // Add event listener to variation options
        document.querySelectorAll('.option-btn, .variation-option').forEach(option => {
            option.addEventListener('click', function() {
                // Wait a small amount of time for the main image to update
                setTimeout(updateZoomBackground, 50);
            });
        });

        // Run periodically to catch any updates we might have missed
        setInterval(updateZoomBackground, 500);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const productId = {
                {
                    $product - > id
                }
            };
            const key = 'product_seen_' + productId;
            const ttlMs = 24 * 60 * 60 * 1000; // 24h
            const now = Date.now();
            const last = parseInt(localStorage.getItem(key) || '0', 10);
            const isUnique = !last || (now - last) > ttlMs;

            localStorage.setItem(key, now.toString());

            const url = "{{ route('products.view', $product) }}";
            const payload = new FormData();
            payload.append('_token', '{{ csrf_token() }}');
            payload.append('unique', isUnique ? '1' : '0');

            if (navigator.sendBeacon) {
                navigator.sendBeacon(url, payload);
            } else {
                fetch(url, {
                    method: 'POST',
                    body: payload,
                    credentials: 'same-origin'
                });
            }
        } catch (e) {
            // Silent fail: view tracking should not block UX
        }
    });
</script>


@endsection
