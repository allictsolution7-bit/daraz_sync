@extends('frontend.app')

@section('styles')
<link rel="preload" as="image" href="{{ asset('storage/' . $product->thumb_image) }}">
<link rel="stylesheet" href="{{ asset('css/combo-offer.css') }}">
<style>
    .pcontainer {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 0rem;
        margin: 1rem auto;
        padding: 1rem;
        background: var(--body-bg);
        margin-top: 0;
        padding-top: 0;
    }

    .image-area {
        width: 30%;
    }

    .product-title-section {
        margin-bottom: 10px;
    }

    .product-title {
        margin-bottom: 0;
        font-size: 25px;
        color: var(--dark-color);
        white-space: wrap;
        flex: 1;
    }

    .product-rating-summary {
        display: flex;
        width: 280px;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 5px;
    }

    .product-rating-summary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .rating-display {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .rating-display .stars {
        display: flex;
        align-items: center;
        gap: 1px;
    }

    .product-ratings .rating-number {
        font-weight: 600;
        color: #1e293b;
        font-size: 2.3rem;
    }

    .review-count {
        color: #64748b;
        font-size: 12px;
    }

    .arrow-icon {
        color: #64748b;
        transition: transform 0.2s ease;
    }

    .product-rating-summary:hover .arrow-icon {
        transform: translateY(1px);
    }


    .variation-cart-area {
        width: 40%;
    }

    .info-area {
        width: 25%;
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

    /* Tik mark styling */
    .option-btn.selected::after {
        content: "✔";
        position: absolute;
        top: 0%;
        right: -4px;
        background-color: var(--primary-color);
        width: 22px;
        height: 22px;
        border-radius: 50%;
        color: #fff;
        font-size: 18px;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transform: translateY(-45%);
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
<div class="base-container pcontainer" data-product-id="{{ $product->id }}" data-category="{{ $product->category->name ?? ($product->additionalCategories->first()->name ?? ($product->getAllCategories()->first()->name ?? '')) }}">

    {{-- Product main image area --}}
    <div class="image-area">
        <div class="product-images">
            @php
            $productImages = $product->images ?? [];
            // Handle the case where images might be a string
            if (is_string($productImages)) {
            $decodedImages = json_decode($productImages, true);
            $productImages = is_array($decodedImages) ? $decodedImages : [];
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
            </style>

            <div class="mainimage" style="position:relative;">
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
                    <div class="starburst-badge" style="position:absolute;top:2px;left:2px;">
                        {{ $percentOff }}%<br>ছাড়
                    </div>
                    @endif
                    <figure class="zoom" onmousemove="zoom(event)"
                        style="background-image: url('{{ asset('storage/' . $product->thumb_image) }}')">
                        <img id="main-product-image" src="{{ asset('storage/' . $product->thumb_image) }}"
                            alt="{{ $product->title }}">
                    </figure>
            </div>



            @if ($productImages && is_array($productImages) && count($productImages) > 0)
            <div class="image-gallery-container">
                <button class="gallery-nav prev-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                    </svg>
                </button>
                <div class="image-gallery" id="product-gallery" data-gallery-images='@json($productImages)'>
                    <img class="product-gallery-image active" id="main-product-image"
                        src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}">
                </div>
                <button class="gallery-nav next-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                    </svg>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Product all meta and others info area --}}
    <div class="variation-cart-area">

        <div id="variation-errors" class="variation-errors"
            style="display: none; margin-bottom: 15px; padding: 10px; border-radius: 6px; background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">
        </div>

        <div class="product-title-section">
            <h1 class="product-title">{{ $product->title }}</h1>
            @if (setting('single_product', 'enable_rating_summary', '1') == '1' && $reviewStats['review_count'] > 0)
            <div class="product-rating-summary" onclick="scrollToReviews()">
                <div class="rating-display">
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <=$reviewStats['average_rating'])
                            <svg class="star-icon filled" viewBox="0 0 24 24" width="20" height="20">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="#f59e0b" />
                            </svg>
                            @elseif($i <= $reviewStats['average_rating'] + 0.5)
                                <svg class="star-icon half-filled" viewBox="0 0 24 24" width="20"
                                height="20">
                                <defs>
                                    <linearGradient id="halfStarTitle">
                                        <stop offset="50%" stop-color="#f59e0b" />
                                        <stop offset="50%" stop-color="#e5e7eb" />
                                    </linearGradient>
                                </defs>
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                    fill="url(#halfStarTitle)" />
                                </svg>
                                @else
                                <svg class="star-icon empty" viewBox="0 0 24 24" width="20" height="20">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#e5e7eb" />
                                </svg>
                                @endif
                                @endfor
                    </div>
                    <span class="rating-number">{{ number_format($reviewStats['average_rating'], 1) }}</span>
                    <span class="review-count">({{ $reviewStats['review_count'] }} reviews)</span>
                </div>
                <svg class="arrow-icon" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M7 10l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            @endif
        </div>

        <!-- Product Details Section -->
        @if (setting('general', 'show_product_details_section', '1') == '1')
        <style>
            .product-details-table {
                margin-top: 10px;
                font-size: 14px;
            }

            .product-details-table div {
                margin-bottom: 3px;
            }
        </style>
        <div class="product-details-table">
            @if($product->brand && setting('general', 'show_product_brand_single', '1') == '1')
            <div><b>Brand:</b> <a href="{{ route('shop') }}?brand={{ $product->brand->id }}"
                    style="color:#e74c3c;">{{ $product->brand->name }}</a></div>
            @endif
            @php
            // Get all categories (primary + additional)
            $allCategories = collect();
            if ($product->category) {
            $allCategories->push($product->category);
            }
            if ($product->additionalCategories) {
            $allCategories = $allCategories->merge($product->additionalCategories);
            }
            $allCategories = $allCategories->unique('id');

            // Get all subcategories (primary + additional)
            $allSubCategories = collect();
            if ($product->subCategory) {
            $allSubCategories->push($product->subCategory);
            }
            if ($product->additionalSubCategories) {
            $allSubCategories = $allSubCategories->merge($product->additionalSubCategories);
            }
            $allSubCategories = $allSubCategories->unique('id');

            // Get all third-level categories
            $allThirdCategories = $product->thirdCategories ?? collect();
            @endphp

            @if ($allCategories->count() > 0 || $allSubCategories->count() > 0 || $allThirdCategories->count() > 0)
            <div><b>{{ setting('general', 'category_label_text', 'বিষয়') }} :</b>
                {{-- Display all primary and additional categories --}}
                @foreach ($allCategories as $category)
                <a href="{{ url('shop/' . $category->slug) }}"
                    style="color:#e74c3c;">{{ $category->name }}</a>@if (!$loop->last || $allSubCategories->count() > 0 || $allThirdCategories->count() > 0), @endif
                @endforeach

                {{-- Display all primary and additional subcategories --}}
                @foreach ($allSubCategories as $subCategory)
                @php
                $subCategoryCategory = $subCategory->category;
                $subCategoryUrl = $subCategoryCategory
                ? url('shop/' . $subCategoryCategory->slug . '/' . $subCategory->slug)
                : url('shop/' . $subCategory->slug);
                @endphp
                <a href="{{ $subCategoryUrl }}"
                    style="color:#e74c3c;">{{ $subCategory->name }}</a>@if (!$loop->last || $allThirdCategories->count() > 0), @endif
                @endforeach

                {{-- Display all third-level categories --}}
                @foreach ($allThirdCategories as $thirdCategory)
                @php
                $thirdSubCategory = $thirdCategory->subCategory;
                $thirdCategoryCategory = $thirdSubCategory ? $thirdSubCategory->category : null;
                $thirdCategoryUrl = ($thirdCategoryCategory && $thirdSubCategory)
                ? url('shop/' . $thirdCategoryCategory->slug . '/' . $thirdSubCategory->slug . '/' . $thirdCategory->slug)
                : ($thirdSubCategory ? url('shop/' . $thirdSubCategory->slug . '/' . $thirdCategory->slug) : '#');
                @endphp
                <a href="{{ $thirdCategoryUrl }}"
                    style="color:#e74c3c;">{{ $thirdCategory->name }}</a>@if (!$loop->last), @endif
                @endforeach
            </div>
            @endif
            @if (is_iterable($product?->book?->writers) && count($product?->book?->writers) > 0)
            <div>
                <b>{{ setting('general', 'writer_label_text', 'লেখক') }} :</b>
                @foreach ($product?->book?->writers as $writer)
                <a href="{{ route('shop', ['writer' => $writer->id]) }}"
                    style="color:#e74c3c;">{{ $writer->name }}</a>
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </div>
            @endif
            @if (!empty($product?->book?->publisher?->name))
            <div>
                <b>{{ setting('general', 'publisher_label_text', 'প্রকাশক') }} :</b>
                <a href="{{ route('shop', ['publisher' => $product->book->publisher->id]) }}"
                    style="color:#e74c3c;">{{ $product->book->publisher->name }}</a>
            </div>
            @endif
            @if (!empty($product?->book?->isbn))
            <div><b>{{ setting('general', 'isbn_label_text', 'আইএসবিএন') }} :</b> <span
                    style="color:#e74c3c;">{{ $product?->book?->isbn }}</span></div>
            @endif
            @if (!empty($product?->book?->edition))
            <div><b>সংস্করণ :</b> <span style="color:#e74c3c;">{{ $product?->book?->edition }}</span></div>
            @endif
            @if (!empty($product?->book?->pages))
            <div><b>{{ setting('general', 'pages_label_text', 'পৃষ্ঠা') }} :</b> <span
                    style="color:#e74c3c;">{{ $product?->book?->pages }}</span></div>
            @endif
            @if (!empty($product?->book?->cover))
            <div><b>কভার :</b> <span style="color:#e74c3c;">{{ $product?->book?->cover }}</span></div>
            @endif
            @if (!empty($product?->book?->language))
            <div><b>{{ setting('general', 'language_label_text', 'ভাষা') }} :</b> <span
                    style="color:#e74c3c;">{{ $product?->book?->language }}</span></div>
            @endif
            @if (!empty($product?->book?->country))
            <div><b>দেশ :</b> <span style="color:#e74c3c;">{{ $product?->book?->country }}</span></div>
            @endif
        </div>
        @endif

        <div class="price-area">
            @if ($product->product_type === 'variable')
            @php
            // Find min and max prices from variation combinations
            $prices = [];
            $hasDiscount = false;
            $maxDiscountPercentage = 0;

            if ($product->variationCombinations && $product->variationCombinations->count() > 0) {
            foreach ($product->variationCombinations as $combination) {
            $effectivePrice =
            $combination->offer_price ?? ($combination->regular_price ?? $combination->price);
            if ($effectivePrice) {
            $prices[] = $effectivePrice;

            // Check for discounts
            if (
            $combination->offer_price &&
            $combination->regular_price &&
            $combination->offer_price < $combination->regular_price
                ) {
                $hasDiscount = true;
                $discountPercentage = round(
                (($combination->regular_price - $combination->offer_price) /
                $combination->regular_price) *
                100,
                );
                if ($discountPercentage > $maxDiscountPercentage) {
                $maxDiscountPercentage = $discountPercentage;
                }
                }
                }
                }
                }
                $minPrice = !empty($prices) ? min($prices) : 0;
                $maxPrice = !empty($prices) ? max($prices) : 0;
                @endphp

                @if ($minPrice === $maxPrice)
                <h1 class="pricearea">
                    <span id="updateOfferPrice">{{ $minPrice }}৳</span>
                </h1>
                @else
                <h1 class="pricearea">
                    <span id="updateOfferPrice">{{ $minPrice }}৳ - {{ $maxPrice }}৳</span>
                </h1>
                @endif
                @else
                @if ($product->offer)
                @php
                $discountPercentage = round(
                (($product->old_price - $product->offer) / $product->old_price) * 100,
                );
                @endphp
                <h1 class="pricearea">
                    <span class="oldprice">{{ $product->old_price }}৳</span>
                    <span id="updateOfferPrice">{{ $product->offer }}৳</span>
                    <span class="discount-badge">{{ $discountPercentage }}% OFF</span>
                </h1>
                @else
                <h1 class="pricearea">
                    <span id="updateOfferPrice">{{ $product->old_price }}৳</span>
                </h1>
                @endif
                @endif
        </div>

        {{-- Product bottom meta --}}
        @if (setting('general', 'show_product_meta_section', '1') == '1')
        <div class="meta">
            @if (setting('general', 'show_sku_field', '1') == '1' && $product->sku !== null)
            <div class="meta-item">
                <span class="meta-label"><i class="fa-solid fa-box-open"></i> SKU:</span>
                <span class="meta-value sku">{{ $product->sku }}</span>
            </div>
            @endif
            <!-- Only show availability if quantity is not null -->
            @if (setting('general', 'show_availability_field', '1') == '1' && $product->quantity !== null)
            <div class="meta-item">
                <span class="meta-label"><i
                        class="fa-solid fa-check-circle"></i>{{ setting('general', 'availability_label_text', 'Availability') }}
                    : </span>
                <span class="meta-value {{ $product->quantity > 0 ? 'in-stock' : 'out-of-stock' }}">
                    @if ($product->quantity > 0)
                    In Stock ({{ $product->quantity }} {{ $product->quantity > 1 ? 'items' : 'item' }})
                    @else
                    Out of Stock
                    @endif
                </span>
            </div>
            @endif
        </div>
        @endif

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
        {{-- Show Regular Variations --}}
        <div class="variation-selection-area">
            <h4 style="margin-bottom: 15px; font-size: 1.2rem; color: #333;">Select Options:</h4>

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
            <div class="variation-group" style="margin-bottom: 25px;">
                <label
                    style="display: block; margin-bottom: 10px; font-weight: 600; color: #333; font-size: 16px;">
                    {{ $variation['name'] }}: <span style="color: red;">*</span>
                </label>
                <div class="variation-options" data-variation-id="{{ $variation['id'] }}"
                    style="display: flex; flex-wrap: wrap; gap: 10px;">
                    @foreach ($variation['options'] as $option)
                    <button type="button" class="variation-option-btn"
                        data-option-id="{{ $option['id'] }}" data-variation-id="{{ $variation['id'] }}"
                        data-description="{{ $option['description'] }}"
                        data-thumb-image="{{ $option['featured_image'] }}"
                        data-additional-images="{{ $option['images'] }}"
                        onclick="selectVariationOption(this)"
                        style="
                                                            padding: 12px 20px;
                                                            border: 2px solid #ddd;
                                                            background: #fff;
                                                            color: #333;
                                                            border-radius: 8px;
                                                            cursor: pointer;
                                                            font-size: 14px;
                                                            font-weight: 500;
                                                            transition: all 0.3s ease;
                                                            min-width: 80px;
                                                            text-align: center;
                                                        ">
                        {{ $option['name'] }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Price and Stock Display -->
            <div id="combination-info"
                style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; display: none;">
                <div id="combination-price"
                    style="font-size: 1.5rem; font-weight: bold; color: #e74c3c; margin-bottom: 10px;"></div>
                <div id="combination-stock" style="font-size: 14px; color: #6c757d;"></div>
                <div id="combination-description" style="margin-top: 10px; font-size: 14px; color: #495057;">
                </div>
            </div>

            <!-- Error Messages -->
            <div id="variation-selection-error"
                style="display: none; margin: 10px 0; padding: 10px; background: #fee2e2; color: #dc2626; border-radius: 6px; border: 1px solid #fecaca;">
                Please select all required options.
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

            // Add CSS for selected state
            const style = document.createElement('style');
            style.textContent = `
                                    .variation-option-btn:hover {
                                        border-color: #007bff !important;
                                        background: #f8f9fa !important;
                                        transform: translateY(-1px);
                                    }
                                    .variation-option-btn.selected {
                                        border-color: #007bff !important;
                                        background: #007bff !important;
                                        color: white !important;
                                        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
                                    }
                                    .variation-option-btn:active {
                                        transform: translateY(0);
                                    }
                                `;
            document.head.appendChild(style);

            function selectVariationOption(button) {
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

                // Update combination info with image loading (manual selection)
                updateVariationCombination(true);
            }

            function updateVariationCombination(loadImages = false) {
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

                    // Restore original gallery when not all variations are selected
                    restoreOriginalGallery();

                    updateTotalPrice(); // Reset price display
                    updateButtonStates(); // Update button states
                    return;
                }

                // Sort options to match combination key (ensure all are integers)
                selectedOptions.sort((a, b) => a - b);

                console.log('Selected options:', selectedOptions);
                console.log('Available combinations:', window.variationCombinations);

                // Find matching combination with robust comparison
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

                    // Compare arrays
                    if (comboOptions.length !== selectedOptions.length) {
                        return false;
                    }

                    // Deep comparison
                    for (let i = 0; i < comboOptions.length; i++) {
                        if (comboOptions[i] !== selectedOptions[i]) {
                            return false;
                        }
                    }

                    console.log('Match found:', combo.id, comboOptions);
                    return true;
                });

                console.log('🔍 BEFORE CHECK - combination result:', combination);

                if (combination) {
                    console.log('✅✅✅ COMBINATION FOUND!', combination);
                    console.log('Price:', combination.offer_price || combination.regular_price || combination.price);
                    console.log('Stock:', combination.stock_quantity);
                    console.log('Elements exist?', {
                        combinationInfo: !!combinationInfo,
                        errorDiv: !!errorDiv,
                        priceElement: !!document.getElementById('combination-price'),
                        stockElement: !!document.getElementById('combination-stock')
                    });

                    // FORCE HIDE ERROR FIRST (multiple methods for reliability)
                    console.log('Hiding error div...');
                    errorDiv.style.display = 'none';
                    errorDiv.style.visibility = 'hidden';
                    errorDiv.style.opacity = '0';
                    errorDiv.setAttribute('style', 'display: none !important;');
                    errorDiv.classList.add('hidden');

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

                    // Update gallery images if combination has them (only for manual selection)
                    if (loadImages) {
                        if (combination.featured_image || (combination.gallery_images && combination.gallery_images.length >
                                0)) {
                            updateGalleryForCombination(combination.featured_image, combination.gallery_images);
                        } else {
                            // If no combination images, restore original gallery
                            restoreOriginalGallery();
                        }
                    }

                    // FORCE SHOW COMBINATION INFO (multiple methods for reliability)
                    console.log('Showing combination info...');
                    combinationInfo.style.display = 'block';
                    combinationInfo.style.visibility = 'visible';
                    combinationInfo.style.opacity = '1';
                    combinationInfo.setAttribute('style', 'display: block !important; margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;');
                    combinationInfo.classList.remove('hidden');

                    console.log('✅✅✅ DISPLAY UPDATED!');
                    console.log('Combination Info Display:', combinationInfo.style.display);
                    console.log('Combination Info Visibility:', combinationInfo.style.visibility);
                    console.log('Combination Info Opacity:', combinationInfo.style.opacity);
                    console.log('Error Div Display:', errorDiv.style.display);
                    console.log('Error Div Visibility:', errorDiv.style.visibility);
                    console.log('Computed styles:', {
                        comboComputed: window.getComputedStyle(combinationInfo).display,
                        errorComputed: window.getComputedStyle(errorDiv).display
                    });

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

                    // Restore original gallery when no combination is found (only for manual selection)
                    if (loadImages) {
                        restoreOriginalGallery();
                    }

                    // Reset price to base price
                    updateTotalPrice();

                    // Update button states
                    updateButtonStates();
                }
            }

            function updateVariationDescriptions() {
                const descriptionDiv = document.getElementById('combination-description');
                const combinationId = document.getElementById('selected_combination_id').value;

                if (combinationId) {
                    const combination = window.variationCombinations.find(combo => combo.id == combinationId);
                    if (combination && combination.short_description) {
                        descriptionDiv.innerHTML = combination.short_description;
                        descriptionDiv.style.display = 'block';
                        return;
                    }
                }

                // Fallback to option descriptions if no combination description
                const descriptions = [];
                document.querySelectorAll('.variation-option-btn.selected').forEach(btn => {
                    const description = btn.dataset.description;
                    if (description && description.trim()) {
                        descriptions.push(description);
                    }
                });

                if (descriptions.length > 0) {
                    descriptionDiv.innerHTML = descriptions.join('<br>');
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.style.display = 'none';
                }
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.option-btn.selected').forEach(btn => {
                    btn.click();
                });
            });
        </script>

        <!-- Stock warning message -->
        <div id="stock-warning" class="stock-warning"
            style="display: @if($product->product_type === 'variable') block @else none @endif; color: #e74c3c; font-size: 14px; margin-bottom: 10px; padding: 8px; background: #fdf2f2; border: 1px solid #fecaca; border-radius: 4px;">
            @if ($product->product_type === 'variable')
            Please select all required options.
            @endif
        </div>

        <!-- Shared quantity input and cart buy now-->
        <div class="sharedQuantityarea flex" id="productActionsContainer">
            @if ($product->product_type !== 'affiliate')
            <input type="number" id="sharedQuantity" class="quanity" value="1" min="1"
                max="{{ $product->product_type === 'variable' ? '999' : $product->quantity ?? 999 }}"
                style="" />
            <!-- Add to Cart Form -->
            <form id="cartForm" class="cartFormArea" action="{{ route('cart.store') }}" method="post">
                @csrf
                <input type="hidden" name="product_id" id="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="quantity" id="cartQuantity" value="1" />

                <div id="variationInputs"></div> <!-- Variations will be added dynamically -->

                <button type="submit" onclick="updateCartForm(event)" class="single-cart-btn"
                    @if ($product->product_type === 'variable') disabled style="opacity: 0.6; cursor: not-allowed;" @endif>
                    <svg width="18" height="18" viewBox="0 0 21 21" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z"
                            fill="#ffffff"></path>
                    </svg>
                    Add To Cart
                </button>

            </form>

            <!-- Buy Now Form -->
            <form id="buyNowForm" action="{{ route('buy.store.post') }}" method="post"
                class="buyNowFormarea inline">
                @csrf
                <input type="hidden" name="quantity" id="buyQuantity" value="1" />
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <input type="hidden" name="price" id="buyPriceId"
                    value="{{ $product->offer ?? $product->old_price }}" />
                <input name="main_price" type="hidden" id="buyMainPrice"
                    value="{{ $product->offer ?? $product->old_price }}">

                <!-- Hidden Inputs for Selected Variations -->
                <div id="buyVariationInputs"></div>

                <button type="submit" onclick="updateBuyNowForm(event)" class="single-buynow-btn"
                    onsubmit="return false;"
                    @if ($product->product_type === 'variable') disabled style="opacity: 0.6; cursor: not-allowed;" @endif>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 2v11h3v9l7-12h-4l4-8z" />
                    </svg> Buy Now
                </button>

            </form>
            @else
            <!-- Affiliate Product External Link -->
            <a href="{{ $product->external_url }}" target="_blank" class="single-buynow-btn affiliate-btn"
                style="display: inline-block; text-align: center; width: 100%;"
                onclick="return checkAffiliateStock(event)">
                <i class="fa-solid fa-external-link"></i> Buy Now
            </a>
            @endif

        </div>

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

        {{-- Bottom Action buttons --}}
        @if (setting('general', 'show_bottom_action_buttons', '1') == '1')
        <div class="action-buttons">
            @if (setting('general', 'show_whatsapp_button', '1') == '1')
            <a href="https://api.whatsapp.com/send?phone={{ setting('general', 'whatsapp_number') }}&text={{ urlencode('I am interested in: ' . $product->title . ' - ' . url()->current()) }}"
                target="_blank" class="whatsapp_action_button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.464 3.488" />
                </svg>
                {{ setting('general', 'whatsapp_number') }}</a>
            @endif
            @if (setting('general', 'show_phone_button', '1') == '1')
            <a href="tel:+{{ setting('general', 'phone_number') }}" class="phonecall_action_button">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                </svg>
                {{ setting('general', 'phone_number') }}</a>
            @endif
        </div>
        @endif

        <!-- Wishlist Button -->
        <div class="wishlist-btn-container" style="margin: 15px 0;">
            <button type="button" class="btn-wishlist-toggle" onclick="toggleWishlistProduct({{ $product->id }}, '{{ addslashes($product->title) }}', '{{ $product->old_price ?? $product->price ?? 0 }}', '{{ asset('storage/' . $product->thumb_image) }}', '{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}')" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 25px; border: 1.5px solid #ef4444; background: #fff0f0; color: #ef4444; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
                <i class="fa-solid fa-heart" id="wishlist-heart-icon-{{ $product->id }}"></i>
                <span id="wishlist-btn-text-{{ $product->id }}">Add to Wishlist</span>
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
                let exists = wishlist.some(item => item.id == {{ $product->id }});
                if (exists) {
                    let btnText = document.getElementById('wishlist-btn-text-{{ $product->id }}');
                    if (btnText) btnText.textContent = 'Saved in Wishlist';
                }
            });

            function toggleWishlistProduct(id, title, price, image, url) {
                let wishlist = JSON.parse(localStorage.getItem('user_wishlist') || '[]');
                let index = wishlist.findIndex(item => item.id == id);
                let btnText = document.getElementById('wishlist-btn-text-' + id);

                if (index > -1) {
                    wishlist.splice(index, 1);
                    if (btnText) btnText.textContent = 'Add to Wishlist';
                    alert('Removed from your Wishlist!');
                } else {
                    wishlist.push({ id: id, title: title, price: price, image: image, url: url });
                    if (btnText) btnText.textContent = 'Saved in Wishlist';
                    alert('Added to your Wishlist!');
                }
                localStorage.setItem('user_wishlist', JSON.stringify(wishlist));
            }
        </script>

        {{-- Social Share --}}
        @if (setting('single_product', 'enable_social_share', '1') == '1')
        <div class="social-share">
            <h6>Share:</h6>
            <div class="share-icons">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                    target="_blank" class="share-icon facebook">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->title) }}"
                    target="_blank" class="share-icon twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($product->title . ' - ' . url()->current()) }}"
                    target="_blank" class="share-icon whatsapp">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.464 3.488" />
                    </svg>
                </a>
                <a href="mailto:?subject={{ urlencode($product->title) }}&body={{ urlencode('Check out this product: ' . url()->current()) }}"
                    class="share-icon email">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z">
                        </path>
                    </svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                    target="_blank" class="share-icon linkedin">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                    </svg>
                </a>
                <a href="#" class="share-icon print" onclick="window.print(); return false;" title="Print">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 9V2h12v7H6zm10-2V4H8v3h8zm2 4h2a2 2 0 012 2v4a2 2 0 01-2 2h-2v3H6v-3H4a2 2 0 01-2-2v-4a2 2 0 012-2h2v2H4v4h16v-4h-2v-2zm-2 10v-5H8v5h8z" />
                    </svg>
                </a>
            </div>
        </div>
        @endif

        {{-- Order Delivery timeline --}}
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
                border: 1px solid #cecece;
                border-radius: 8px;
                margin-top: 15px;
                padding-bottom: 5px;
            }
        </style>

        <div class="order-time-container">
            <div class="order-time-timeline-container" id="timeline-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 150" width="100%">
                    <!-- Connection Lines -->
                    <line x1="100" y1="60" x2="400" y2="60" stroke="#000000"
                        stroke-width="2" />
                    <line x1="400" y1="60" x2="700" y2="60" stroke="#000000"
                        stroke-width="2" />

                    <!-- Circle 1: Ordered -->
                    <circle cx="100" cy="60" r="40" fill="#1a1a1a" />
                    <!-- Shopping Cart Icon -->
                    <path d="M90 60 L85 45 L115 45 L110 60 Z" fill="none" stroke="#ffffff" stroke-width="2" />
                    <rect x="85" y="60" width="30" height="2" fill="#ffffff" />
                    <circle cx="90" cy="65" r="3" fill="#ffffff" />
                    <circle cx="110" cy="65" r="3" fill="#ffffff" />
                    <path d="M100 50 L100 42 M95 47 L105 47" fill="none" stroke="#ffffff" stroke-width="2" />

                    <!-- Circle 2: Order Ready with Improved Truck Icon -->
                    <circle cx="400" cy="60" r="40" fill="#1a1a1a" />
                    <!-- Improved Delivery Truck Icon -->
                    <path d="M375 60 L375 48 L390 48 L390 45 L410 45 L415 50 L425 50 L425 60 Z" fill="#ffffff"
                        stroke="#ffffff" stroke-width="1" />
                    <rect x="375" y="60" width="50" height="5" fill="#ffffff" />
                    <circle cx="385" cy="65" r="4" fill="#1a1a1a" stroke="#ffffff"
                        stroke-width="1" />
                    <circle cx="415" cy="65" r="4" fill="#1a1a1a" stroke="#ffffff"
                        stroke-width="1" />
                    <rect x="390" y="50" width="20" height="10" fill="#1a1a1a" stroke="#ffffff"
                        stroke-width="1" /> <!-- Window -->

                    <!-- Circle 3: Delivered -->
                    <circle cx="700" cy="60" r="40" fill="#1a1a1a" />
                    <!-- Gift Box Icon -->
                    <rect x="685" y="50" width="30" height="20" fill="none" stroke="#ffffff"
                        stroke-width="2" />
                    <line x1="685" y1="50" x2="700" y2="40" stroke="#ffffff"
                        stroke-width="2" />
                    <line x1="700" y1="40" x2="715" y2="50" stroke="#ffffff"
                        stroke-width="2" />
                    <line x1="700" y1="50" x2="700" y2="70" stroke="#ffffff"
                        stroke-width="2" />
                    <path d="M695 45 Q700 40 705 45" fill="none" stroke="#ffffff" stroke-width="1.5" />

                    <!-- Text Labels (will be updated by JS) -->
                    <text id="ordered-date" x="100" y="120" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle" font-weight="bold"></text>
                    <text x="100" y="145" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle">Ordered</text>

                    <text id="ready-date" x="400" y="120" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle" font-weight="bold"></text>
                    <text x="400" y="145" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle">Order
                        Ready</text>

                    <text id="delivered-date" x="700" y="120" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle" font-weight="bold"></text>
                    <text x="700" y="145" font-family="Arial, sans-serif" font-size="16"
                        text-anchor="middle">Delivered</text>
                </svg>
            </div>
        </div>

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
                document.getElementById('ordered-date').textContent = formatDate(orderedDate);
                document.getElementById('ready-date').textContent =
                    `${formatDate(readyStartDate)} - ${formatDate(readyEndDate)}`;
                document.getElementById('delivered-date').textContent =
                    `${formatDate(deliveredStartDate)} - ${formatDate(deliveredEndDate)}`;
            }

            // Initialize the timeline when the page loads
            document.addEventListener('DOMContentLoaded', updateTimeline);
        </script>
        @endif

        {{-- @include('frontend.partials.quickfaq') --}}

    </div>

    {{-- Shop Right sidebar info area --}}
    <div class="info-area">

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
        @if (setting('single_product', 'enable_related_products', '1') == '1' &&
        setting('general', 'show_related_products_section', '1') == '1')
        <div class="product-list-sidebar-section">
            <div class="product-list-sidebar-header">
                <span class="product-list-sidebar-line"></span>
                <h2>{{ setting('general', 'related_products_section_title', 'আরো দেখুন') }}</h2>
                <span class="product-list-sidebar-line"></span>
            </div>
            @if (!empty($related_products) && count($related_products) > 0)
            <div class="product-list-sidebar-box">
                @foreach ($related_products as $dproduct)
                @include('frontend.partials.list-product-item', ['product' => $dproduct])
                @endforeach
            </div>
            @endif
        </div>
        @endif

        @php
        $showDelivery = setting('general', 'show_delivery_info', '1');
        $deliveryInfo = setting('general', 'delivery_info', '');
        @endphp

        @if ($showDelivery == '1' && $deliveryInfo)
        <div class="info">
            {!! $deliveryInfo !!}
        </div>
        @endif

    </div>

</div>

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
<section class="base-container ecom-feedback-section" id="review-form-section">
    <div class="feedback-header-title">
        {{ setting('general', 'review_form_header_title', 'এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন') }}
    </div>

    <form id="product-review-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="rating-evaluation-container">
            <div class="user-profile-avatar"></div>
            <div class="star-rating-group">
                <div class="individual-star-item" data-rating="1">
                    <div class="star-svg-container">
                        <svg class="star-svg-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <span class="star-rating-label">Bad(1/5)</span>
                </div>
                <div class="individual-star-item" data-rating="2">
                    <div class="star-svg-container">
                        <svg class="star-svg-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <span class="star-rating-label">So-so(2/5)</span>
                </div>
                <div class="individual-star-item" data-rating="3">
                    <div class="star-svg-container">
                        <svg class="star-svg-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <span class="star-rating-label">ok(3/5)</span>
                </div>
                <div class="individual-star-item selected-active" data-rating="4">
                    <div class="star-svg-container">
                        <svg class="star-svg-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <span class="star-rating-label">Good(4/5)</span>
                </div>
                <div class="individual-star-item" data-rating="5">
                    <div class="star-svg-container">
                        <svg class="star-svg-icon" viewBox="0 0 24 24" width="24" height="24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="currentColor" />
                        </svg>
                    </div>
                    <span class="star-rating-label">Better(5/5)</span>
                </div>
            </div>
        </div>

        <input type="hidden" name="rating" id="selected-rating" value="4">

        <textarea class="comment-input-textarea" name="review_text"
            placeholder="{{ setting('general', 'review_form_comment_placeholder', 'Write your comment...') }}" required></textarea>

        <div class="image-upload-container">
            <label class="image-upload-label">Upload Images (Optional)</label>
            <div class="image-upload-wrapper">
                <input type="file" class="image-upload-input" name="review_images[]" accept="image/*"
                    multiple>
            </div>
            <div class="image-preview-container">
                <div class="image-preview-grid"></div>
            </div>
        </div>

        @guest
        <div class="guest-info-container">
            <div class="form-group">
                <label for="reviewer_name">{{ setting('general', 'guest_name_label', 'আপনার নাম') }} *</label>
                <input type="text" name="reviewer_name" id="reviewer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="reviewer_email">{{ setting('general', 'guest_email_label', 'আপনার ইমেইল') }} *</label>
                <input type="email" name="reviewer_email" id="reviewer_email" class="form-control" required>
            </div>
        </div>
        @endguest

        <button type="submit"
            class="submit-feedback-button">{{ setting('general', 'review_form_submit_button_text', 'আপনার মতামত সাবমিট করুন') }}</button>
    </form>
</section>
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
                if (index < this.selectedRating) {
                    starItem.classList.add('selected-active');
                } else {
                    starItem.classList.remove('selected-active');
                }
            });
            // Update hidden input
            if (this.ratingInput) {
                this.ratingInput.value = this.selectedRating;
            }
        }

        previewRating(previewRating) {
            this.starItems.forEach((starItem, index) => {
                if (index < previewRating) {
                    starItem.classList.add('selected-active');
                } else {
                    starItem.classList.remove('selected-active');
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

    // Function to scroll to reviews section
    function scrollToReviews() {
        const reviewsSection = document.getElementById('ratings');
        if (reviewsSection) {
            reviewsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>
@endif


@if (setting('general', 'show_product_description_section', '1') == '1' ||
setting('general', 'show_ratings_reviews_section', '1') == '1')
<div class="base-container product-sections">
    <div class="section-navigation">
        @if (setting('general', 'show_product_description_section', '1') == '1' && !empty(trim($product->description)))
        <button class="section-nav-button"
            data-section="description">{{ setting('general', 'description_section_title', 'Product Description') }}</button>
        @endif
        @if (setting('general', 'show_ratings_reviews_section', '1') == '1' && $reviewStats['review_count'] > 0)
        <button class="section-nav-button"
            data-section="ratings">{{ setting('general', 'ratings_section_title', 'Ratings & Reviews') }}</button>
        @endif
    </div>

    @if (setting('general', 'show_product_description_section', '1') == '1' && !empty(trim($product->description)))
    <div class="product-description-section" id="description">
        <div class="product-description">
            <h3>{{ setting('general', 'description_section_title', 'Product Description') }}</h3>
            <div class="description-content">
                {!! $product->description !!}
            </div>
        </div>
    </div>
    @endif

    @if (setting('general', 'show_ratings_reviews_section', '1') == '1' && $reviewStats['review_count'] > 0)
    <div class="product-ratings-section" id="ratings">
        <div class="product-ratings">
            <h3>{{ setting('general', 'ratings_section_title', 'Customer Ratings & Reviews') }}</h3>
            <div class="rating-summary">
                <div class="average-rating">
                    <div class="rating-number">{{ number_format($reviewStats['average_rating'], 1) }}</div>
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <=$reviewStats['average_rating'])
                            <svg class="star-icon filled" viewBox="0 0 24 24" width="20"
                            height="20">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                fill="#f59e0b" />
                            </svg>
                            @elseif($i <= $reviewStats['average_rating'] + 0.5)
                                <svg class="star-icon half-filled" viewBox="0 0 24 24" width="20"
                                height="20">
                                <defs>
                                    <linearGradient id="halfStar">
                                        <stop offset="50%" stop-color="#f59e0b" />
                                        <stop offset="50%" stop-color="#e5e7eb" />
                                    </linearGradient>
                                </defs>
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                    fill="url(#halfStar)" />
                                </svg>
                                @else
                                <svg class="star-icon empty" viewBox="0 0 24 24" width="20"
                                    height="20">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#e5e7eb" />
                                </svg>
                                @endif
                                @endfor
                    </div>
                    <div class="total-reviews">Based on {{ $reviewStats['review_count'] }} reviews</div>
                </div>
                <div class="rating-bars">
                    @for ($rating = 5; $rating >= 1; $rating--)
                    @php
                    $count = $reviewStats['rating_distribution'][$rating] ?? 0;
                    $percentage =
                    $reviewStats['review_count'] > 0
                    ? ($count / $reviewStats['review_count']) * 100
                    : 0;
                    @endphp
                    <div class="rating-bar-item">
                        <span class="rating-label">{{ $rating }}
                            <svg class="star-icon small" viewBox="0 0 24 24" width="16"
                                height="16">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                    fill="#f59e0b" />
                            </svg>
                        </span>
                        <div class="rating-bar">
                            <div class="rating-fill" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="rating-count">{{ $count }}</span>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="customer-reviews">
                @forelse($reviews as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">
                                @if ($review->reviewer_image)
                                <img src="{{ asset($review->reviewer_image) }}"
                                    alt="{{ $review->reviewer_name }}">
                                @else
                                <img src="{{ asset('assets/img/man.png') }}"
                                    alt="{{ $review->reviewer_name }}">
                                @endif
                                @if ($review->is_verified_purchase)
                                <span class="verified-badge" title="Verified Purchase">
                                    <svg viewBox="0 0 24 24" width="12" height="12">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"
                                            fill="#ffffff" />
                                    </svg>
                                </span>
                                @endif
                            </div>
                            <div class="reviewer-details">
                                <div class="reviewer-name">{{ $review->reviewer_name }}</div>
                                <div class="review-date">{{ $review->created_at->format('F d, Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="review-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <=$review->rating)
                                <svg class="star-icon filled" viewBox="0 0 24 24" width="16"
                                    height="16">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#f59e0b" />
                                </svg>
                                @else
                                <svg class="star-icon empty" viewBox="0 0 24 24" width="16"
                                    height="16">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#e5e7eb" />
                                </svg>
                                @endif
                                @endfor
                        </div>
                    </div>
                    <div class="review-content">
                        <p>{{ $review->review_text }}</p>
                        @if ($review->review_images && is_array($review->review_images) && count($review->review_images) > 0)
                        <div class="review-images">
                            @foreach ($review->review_images as $image)
                            <img src="{{ asset($image) }}" alt="Review Image"
                                class="review-image">
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
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
        display: flex;
        gap: 15px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
        padding: 15px 0px 15px 0;
        /* Added bottom padding for arrows */
        margin-bottom: 0px;
    }

    products-container::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
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
                    // Clone the product cards for infinite scrolling
                    productCards.forEach(card => {
                        const clone = card.cloneNode(true);
                        productsContainer.appendChild(clone);
                    });

                    // Set the amount to scroll by (width of one product card + gap)
                    const scrollAmount = 270; // Adjust as needed

                    // Auto-sliding functionality
                    let autoSlideInterval;
                    let currentPosition = 0;
                    const totalWidth = productCards.length * scrollAmount;

                    function infiniteScroll() {
                        if (currentPosition >= totalWidth) {
                            productsContainer.scrollTo({
                                left: 0,
                                behavior: 'auto'
                            });
                            currentPosition = 0;
                        }
                        currentPosition += scrollAmount;
                        productsContainer.scrollTo({
                            left: currentPosition,
                            behavior: 'smooth'
                        });
                    }

                    function startAutoSlide() {
                        autoSlideInterval = setInterval(infiniteScroll, 3000);
                    }

                    function stopAutoSlide() {
                        clearInterval(autoSlideInterval);
                    }

                    startAutoSlide();

                    productsContainer.addEventListener('mouseenter', stopAutoSlide);
                    productsContainer.addEventListener('touchstart', stopAutoSlide);
                    productsContainer.addEventListener('mouseleave', startAutoSlide);
                    productsContainer.addEventListener('touchend', startAutoSlide);

                    if (nextArrow) {
                        nextArrow.addEventListener('click', () => {
                            infiniteScroll();
                            stopAutoSlide();
                            startAutoSlide();
                        });
                    }

                    if (prevArrow) {
                        prevArrow.addEventListener('click', () => {
                            currentPosition -= scrollAmount;
                            if (currentPosition < 0) {
                                currentPosition = totalWidth - scrollAmount;
                                productsContainer.scrollTo({
                                    left: currentPosition,
                                    behavior: 'auto'
                                });
                            } else {
                                productsContainer.scrollTo({
                                    left: currentPosition,
                                    behavior: 'smooth'
                                });
                            }
                            stopAutoSlide();
                            startAutoSlide();
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
        console.log('Checking stock availability:', {
            productType: window.productType,
            productStock: window.productStock,
            productManageStock: window.productManageStock,
            quantity: quantity
        });

        // For variable products, check if combination is selected
        if (window.productType === 'variable') {
            const combinationId = document.getElementById('selected_combination_id');
            if (!combinationId || !combinationId.value) {
                return {
                    available: false,
                    message: 'Please select all required options.'
                };
            }

            const combination = window.variationCombinations.find(combo => combo.id == combinationId.value);
            if (!combination) {
                return {
                    available: false,
                    message: 'Selected combination not found.'
                };
            }

            if (combination.stock_quantity <= 0) {
                return {
                    available: false,
                    message: 'Selected combination is out of stock.'
                };
            }

            if (quantity > combination.stock_quantity) {
                return {
                    available: false,
                    message: `Only ${combination.stock_quantity} items available for this combination.`
                };
            }

            return {
                available: true,
                message: ''
            };
        } else {
            // Simple product
            console.log('Simple product stock check:', {
                productStock: window.productStock,
                quantity: quantity,
                isOutOfStock: window.productStock <= 0,
                exceedsStock: quantity > window.productStock
            });

            // For simple products, check stock even if manage_stock is false but quantity is 0
            if (window.productStock <= 0) {
                console.log('Simple product with zero stock, blocking purchase');
                return {
                    available: false,
                    message: 'This product is out of stock.'
                };
            }

            if (quantity > window.productStock) {
                console.log('Quantity exceeds available stock');
                return {
                    available: false,
                    message: `Only ${window.productStock} items available.`
                };
            }

            console.log('Stock check passed');
            return {
                available: true,
                message: ''
            };
        }
    }

    // Function to update button states based on stock
    function updateButtonStates() {
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;
        const stockCheck = checkStockAvailability(quantity);
        const cartBtn = document.querySelector('.single-cart-btn');
        const buyNowBtn = document.querySelector('.single-buynow-btn');
        const stockWarning = document.getElementById('stock-warning');

        console.log('Updating button states:', {
            quantity: quantity,
            stockCheck: stockCheck,
            cartBtnFound: !!cartBtn,
            buyNowBtnFound: !!buyNowBtn,
            stockWarningFound: !!stockWarning
        });

        if (!stockCheck.available) {
            console.log('Stock not available, disabling buttons');
            // Show stock warning
            if (stockWarning) {
                stockWarning.textContent = stockCheck.message;
                stockWarning.style.display = 'block';
            }

            // Disable buttons
            if (cartBtn) {
                cartBtn.disabled = true;
                cartBtn.style.opacity = '0.6';
                cartBtn.style.cursor = 'not-allowed';
                console.log('Cart button disabled');
            }
            if (buyNowBtn) {
                // Handle both button and anchor elements
                if (buyNowBtn.tagName === 'BUTTON') {
                    buyNowBtn.disabled = true;
                }
                buyNowBtn.style.opacity = '0.6';
                buyNowBtn.style.cursor = 'not-allowed';
                buyNowBtn.style.pointerEvents = 'none';
                console.log('Buy now button disabled');
            }
        } else {
            console.log('Stock available, enabling buttons');
            // Hide stock warning
            if (stockWarning) {
                stockWarning.style.display = 'none';
            }

            // Enable buttons
            if (cartBtn) {
                cartBtn.disabled = false;
                cartBtn.style.opacity = '1';
                cartBtn.style.cursor = 'pointer';
                console.log('Cart button enabled');
            }
            if (buyNowBtn) {
                // Handle both button and anchor elements
                if (buyNowBtn.tagName === 'BUTTON') {
                    buyNowBtn.disabled = false;
                }
                buyNowBtn.style.opacity = '1';
                buyNowBtn.style.cursor = 'pointer';
                buyNowBtn.style.pointerEvents = 'auto';
                console.log('Buy now button enabled');
            }
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

        const errorDiv = document.getElementById('variation-errors');
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;

        // Check stock availability
        const stockCheck = checkStockAvailability(quantity);
        if (!stockCheck.available) {
            errorDiv.textContent = stockCheck.message;
            errorDiv.style.display = "block";
            errorDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }

        // Clear any existing error messages
        errorDiv.style.display = "none";

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

        const errorDiv = document.getElementById('variation-errors');
        const quantity = parseInt(document.getElementById('sharedQuantity').value) || 1;

        // Check stock availability
        const stockCheck = checkStockAvailability(quantity);
        if (!stockCheck.available) {
            errorDiv.textContent = stockCheck.message;
            errorDiv.style.display = "block";
            errorDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }

        // Clear any existing error messages
        errorDiv.style.display = "none";

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

{{-- Buy now animation --}}
<script>
    const buyanibutton = document.querySelector('.single-buynow-btn');

    function triggerShake() {
        buyanibutton.style.animation = 'shake 0.8s ease';

        // Remove animation after it finishes so we can re-apply it again later
        setTimeout(() => {
            buyanibutton.style.animation = '';
        }, 3000); // match the animation duration
    }

    // Shake immediately when page loads
    window.addEventListener('load', () => {
        triggerShake();
        setInterval(triggerShake, 4000); // every 3 seconds
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
