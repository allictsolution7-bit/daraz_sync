@extends('frontend.app')

@section('styles')
    <style>
        /* Sidebar Styles */
        .blog-sidebar {
            margin-bottom: 30px;
        }

        .sidebar-widget {
            background-color: var(--body-bg);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-sm);
        }

        .widget-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .widget-divider {
            height: 2px;
            width: 50px;
            background-color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* Search Widget */
        .search-widget .search-input-wrap {
            position: relative;
        }

        .search-widget input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .search-widget input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
            outline: none;
        }

        .search-widget .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-color);
            color: white;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-widget .search-btn:hover {
            background: var(--secondary-color);
        }

        /* Categories Widget */
        .categories-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .categories-list li {
            border-bottom: 1px solid var(--border-color);
        }

        .categories-list li:last-child {
            border-bottom: none;
        }

        .categories-list a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .categories-list a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        .post-count {
            background-color: var(--light-color);
            color: var(--text-color);
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .categories-list a:hover .post-count {
            background-color: var(--primary-color);
            color: white;
        }

        /* Recent Posts Widget */
        .recent-posts-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .recent-post-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .recent-post-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .post-thumb {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .post-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .post-thumb:hover img {
            transform: scale(1.05);
        }

        .post-info h5 {
            font-size: 14px;
            margin: 0 0 5px;
            line-height: 1.4;
        }

        .post-info h5 a {
            color: var(--dark-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .post-info h5 a:hover {
            color: var(--primary-color);
        }

        .post-date {
            font-size: 12px;
            color: var(--text-light);
        }

        /* Tags Widget */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            display: inline-block;
            background-color: var(--light-color);
            color: var(--text-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .tag:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Banner Styles */
        .post-banner {
            position: relative;
            height: 400px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
        }

        .post-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .post-banner:hover img {
            transform: scale(1.02);
        }

        .post-banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .post-banner-content {
            text-align: center;
            color: #fff;
            max-width: 800px;
            padding: 0 20px;
        }

        .post-banner-content h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .post-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .post-meta span {
            display: flex;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 5px 10px;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }

        .post-meta span:hover {
            background-color: var(--primary-color);
        }

        .post-meta i {
            margin-right: 5px;
        }

        .post-meta a {
            color: #fff;
            text-decoration: none;
        }

        /* Content Styles */
        .post-content-wrapper {
            background-color: var(--body-bg);
            border-radius: 8px;
            padding: 30px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 30px;
        }

        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-color);
        }

        .post-content p {
            margin-bottom: 1.5rem;
        }

        .post-content h2 {
            font-size: 1.8rem;
            margin: 2rem 0 1rem;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .post-content h3 {
            font-size: 1.5rem;
            margin: 1.8rem 0 1rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
            box-shadow: var(--shadow-sm);
        }

        .post-content ul,
        .post-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .post-content li {
            margin-bottom: 0.5rem;
        }

        .post-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin-left: 0;
            color: var(--text-light);
            font-style: italic;
        }

        .post-content a {
            color: var(--primary-color);
            text-decoration: none;
            border-bottom: 1px dotted var(--primary-color);
            transition: all 0.3s ease;
        }

        .post-content a:hover {
            color: var(--secondary-color);
            border-bottom: 1px solid var(--secondary-color);
        }

        /* Section Title Styles */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .section-divider {
            height: 3px;
            width: 100px;
            background-color: var(--primary-color);
            position: relative;
            margin-bottom: 30px;
        }

        .section-divider:after {
            content: '';
            position: absolute;
            height: 1px;
            width: 100%;
            background-color: var(--primary-color);
            bottom: -5px;
            left: 0;
        }

        /* Favorite Button */
        .favorite-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .favorite-btn:hover {
            background: rgba(var(--primary-color-rgb), 0.8);
            transform: scale(1.1);
        }

        /* Related Posts */
        .related-posts {
            background-color: var(--body-bg);
            border-radius: 8px;
            padding: 30px;
            box-shadow: var(--shadow-sm);
        }

        .related-post-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-post-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .related-post-image {
            height: 180px;
            overflow: hidden;
            position: relative;
        }

        .related-post-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .related-post-card:hover .related-post-image::after {
            opacity: 1;
        }

        .related-post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .related-post-card:hover .related-post-image img {
            transform: scale(1.05);
        }

        .related-post-content {
            padding: 15px;
        }

        .related-post-content h4 {
            font-size: 16px;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .related-post-content h4 a {
            color: var(--dark-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .related-post-content h4 a:hover {
            color: var(--primary-color);
        }

        /* Tags */
        .post-tags {
            margin: 30px 0;
        }

        .post-tags a {
            display: inline-block;
            background-color: var(--light-color);
            color: var(--text-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 8px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .post-tags a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Ads Space */
        .ads-space {
            margin: 30px 0;
        }

        .ad-card {
            background-color: var(--light-color);
            border-radius: 8px;
            overflow: hidden;
            text-align: center;
        }

        /* Single Top Ads Space */
        .single-top-ads-space {
            width: 100%;
            margin-bottom: 30px;
        }

        /* Layout */
        .blog-container {
            max-width: 1300px;
            margin: 0 auto;
            background-color: var(--body-bg);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            padding: 30px 0;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-lg-3 {
            width: 25%;
            padding: 0 15px;
        }

        .col-lg-9 {
            width: 75%;
            padding: 0 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {

            .col-lg-3,
            .col-lg-9 {
                width: 100%;
            }

            .col-lg-3 {
                margin-top: 30px;
            }
        }

        @media (max-width: 767px) {
            .post-banner {
                height: 300px;
            }

            .post-banner-content h1 {
                font-size: 1.8rem;
            }

            .post-meta {
                flex-wrap: wrap;
                gap: 10px;
            }

            .post-content-wrapper,
            .related-posts {
                padding: 20px;
            }
        }

        /* Social Share Buttons */
        .social-share {
            display: flex;
            gap: 10px;
            margin: 30px 0;
        }

        .share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .share-btn:hover {
            transform: translateY(-3px);
        }

        .share-facebook {
            background-color: #3b5998;
        }

        .share-twitter {
            background-color: #1da1f2;
        }

        .share-linkedin {
            background-color: #0077b5;
        }

        .share-pinterest {
            background-color: #bd081c;
        }

        /* Author Box */
        .author-box {
            display: flex;
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            align-items: center;
        }

        .author-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info h4 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        .author-info p {
            margin: 0;
            font-size: 14px;
            color: var(--text-light);
        }

        /* Comments Section Styles */
        .comments-section-wrapper {
            margin: 30px 0;
        }

        .comment-form {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .comment-form .form-group {
            margin-bottom: 20px;
        }

        .comment-form label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            display: block;
        }

        .comment-form .form-control {
            border: 1px solid var(--border-color);
            border-radius: 5px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .comment-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(var(--primary-color-rgb), 0.1);
            outline: none;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .guest-notice {
            margin-top: 15px;
            padding: 10px;
            background-color: rgba(var(--primary-color-rgb), 0.1);
            border-radius: 5px;
        }

        /* Comments List Styles */
        .comments-section {
            margin-top: 30px;
        }

        .comments-list {
            margin-top: 20px;
        }

        .comment-item {
            display: flex;
            margin-bottom: 25px;
            padding: 20px;
            background-color: var(--light-color);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            box-shadow: var(--shadow-sm);
        }

        .comment-avatar {
            flex: 0 0 50px;
            margin-right: 15px;
        }

        .avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-author {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .comment-date {
            font-size: 12px;
            color: var(--text-light);
        }

        .comment-text {
            margin-bottom: 15px;
            line-height: 1.6;
            color: var(--text-color);
        }

        .comment-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .comment-actions .btn-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 14px;
            padding: 0;
            border: none;
            background: none;
            transition: color 0.3s ease;
        }

        .comment-actions .btn-link:hover {
            color: var(--primary-color);
        }

        .comment-actions .btn-link i {
            margin-right: 5px;
        }

        /* Replies Styles */
        .replies-container {
            margin-left: 30px;
            margin-top: 20px;
            border-left: 2px solid var(--border-color);
            padding-left: 20px;
        }

        .replies-container .comment-item {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
        }

        .replies-container .replies-container {
            margin-left: 20px;
            border-left: 1px solid var(--border-color);
        }

        /* No Comments */
        .no-comments {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-light);
        }

        .no-comments i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Load More Comments */
        .load-more-comments {
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .load-more-comments:hover {
            transform: translateY(-2px);
        }

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 767px) {
            .comment-item {
                flex-direction: column;
            }

            .comment-avatar {
                margin-bottom: 15px;
                margin-right: 0;
            }

            .replies-container {
                margin-left: 15px;
                padding-left: 15px;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .form-actions .btn {
                width: 100%;
            }

            /* Comment Form Styles */
            .comment-form-wrapper {
                background: #f8f9fa;
                border-radius: 12px;
                padding: 30px;
                margin: 30px 0;
                border: 1px solid #e9ecef;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }

            .comment-form-wrapper .section-title {
                font-size: 24px;
                font-weight: 600;
                color: #333;
                margin-bottom: 15px;
                text-align: center;
            }

            .comment-form-wrapper .section-divider {
                height: 3px;
                width: 60px;
                background: linear-gradient(90deg, #4e9be4, #4cccf3);
                margin: 0 auto 25px;
                border-radius: 2px;
            }

            .comment-form {
                max-width: 100%;
            }

            .comment-form .form-group {
                margin-bottom: 20px;
            }

            .comment-form label {
                display: block;
                font-weight: 600;
                color: #495057;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .comment-form .form-control {
                width: 100%;
                padding: 12px 16px;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                font-size: 14px;
                transition: all 0.3s ease;
                background: #fff;
                resize: vertical;
            }

            .comment-form .form-control:focus {
                border-color: #4e9be4;
                box-shadow: 0 0 0 3px rgba(78, 155, 228, 0.1);
                outline: none;
            }

            .comment-form textarea.form-control {
                min-height: 120px;
                font-family: inherit;
            }

            .comment-form .form-actions {
                display: flex;
                gap: 15px;
                align-items: center;
                flex-wrap: wrap;
            }

            .comment-form .btn {
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .comment-form .btn-primary {
                background: linear-gradient(135deg, #4e9be4, #4cccf3);
                color: white;
            }

            .comment-form .btn-primary:hover {
                background: linear-gradient(135deg, #3d8ad3, #3bbbe2);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(78, 155, 228, 0.3);
            }

            .comment-form .btn-secondary {
                background: #6c757d;
                color: white;
            }

            .comment-form .btn-secondary:hover {
                background: #5a6268;
            }

            .comment-form .guest-notice {
                margin-top: 20px;
                padding: 15px;
                background: #e3f2fd;
                border-radius: 8px;
                border-left: 4px solid #2196f3;
            }

            .comment-form .guest-notice small {
                color: #1976d2;
                font-size: 13px;
            }

            .comment-form .guest-notice i {
                margin-right: 8px;
                color: #2196f3;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .comment-form-wrapper {
                    padding: 20px;
                    margin: 20px 0;
                }
                
                .comment-form .form-actions {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                .comment-form .btn {
                    width: 100%;
                    justify-content: center;
                }
            }
        }
    </style>
@endsection

@section('content')
    <div class="container blog-container">

        <div class="row">
            <!-- Main Content - Right Side -->
            <div class="col-lg-9">
                <!-- Post Banner -->
                <section class="post-banner">
                    @if ($post->image)
                        <img src="{{ asset($post->image) }}" alt="{{ $post->image_alt }}">
                    @else
                        <img src="{{ asset('img/placeholder.jpg') }}" alt="Post Banner">
                    @endif
                    <div class="post-banner-overlay">
                        <div class="post-banner-content">
                            <h1>{{ $post->title }}</h1>
                            <div class="post-meta">
                                @if ($post->postcategory)
                                    <span>
                                        <i class="fas fa-folder"></i>
                                        <a href="{{ route('blog.category', $post->postcategory->slug) }}">
                                            {{ $post->postcategory->name }}
                                        </a>
                                    </span>
                                @endif
                                <span>
                                    <i class="fas fa-user"></i>
                                    {{ $post->user->name ?? 'Admin' }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    {{ $post->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <button class="favorite-btn" aria-label="Add to favorites">
                        <i class="far fa-heart"></i>
                    </button>
                </section>

                <!-- Post Content Section -->
                <div class="post-content-wrapper">
                    <article class="post-content">
                        {!! $post->content !!}
                    </article>

                    <!-- Social Share Buttons -->
                    <div class="social-share">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="share-btn share-facebook" aria-label="Share on Facebook">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="white"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                            target="_blank" class="share-btn share-twitter" aria-label="Share on Twitter">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" fill="white"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}"
                            target="_blank" class="share-btn share-linkedin" aria-label="Share on LinkedIn">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" fill="white"/>
                            </svg>
                        </a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode(asset($post->image)) }}&description={{ urlencode($post->title) }}"
                            target="_blank" class="share-btn share-pinterest" aria-label="Share on Pinterest">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.1.12.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" fill="white"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Tags -->
                    @if (!empty($post->tags))
                        <div class="post-tags">
                            <strong>Tags:</strong>
                            @foreach ($post->tags_array as $tag)
                                <a href="{{ route('blog.tag', $tag) }}">{{ $tag }}</a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Author Box -->
                    <div class="author-box">
                        <div class="author-avatar">
                            <img src="{{ asset('man.svg') }}"  alt="Author">
                        </div>
                        <div class="author-info">
                            <h4>{{ $post->user->name ?? 'Admin' }}</h4>
                            <p>Content Writer</p>
                        </div>
                    </div>

                    <!-- Ads Space -->
                    <div class="ads-space">
                        @if (isset($afterContentAds) && $afterContentAds->count() > 0)
                            @foreach ($afterContentAds as $ad)
                                <div class="ad-card mb-4">
                                    {!! $ad->ad_code !!}
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section-wrapper">
                        @include('frontend.blog.partials.comment-form')
                        
                        <div id="comments-container">
                            @if ($comments->count() > 0)
                                @include('frontend.blog.partials.comments-list', ['comments' => $comments])
                            @else
                                <div class="no-comments">
                                    <p class="text-muted text-center">
                                        <i class="fas fa-comments"></i>
                                        No comments yet. Be the first to comment!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Posts -->
                    @if (isset($relatedPosts) && $relatedPosts->count() > 0)
                        <div class="related-posts">
                            <h3 class="section-title">You might also like</h3>
                            <div class="section-divider"></div>

                            <div class="row">
                                @foreach ($relatedPosts as $relatedPost)
                                    <div class="col-md-4 mb-4">
                                        <div class="related-post-card">
                                            <div class="related-post-image">
                                                @if ($relatedPost->image)
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                                        <img src="{{ asset($relatedPost->image) }}"
                                                            alt="{{ $relatedPost->image_alt }}">
                                                    </a>
                                                @else
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                                        <img src="{{ asset('img/placeholder.jpg') }}" alt="Related Post">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="related-post-content">
                                                <h4>
                                                    <a href="{{ route('blog.show', $relatedPost->slug) }}">
                                                        {{ $relatedPost->title }}
                                                    </a>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Left Side -->
            <div class="col-lg-3">
                @include('frontend.blog.partials.sidebar')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Favorite button functionality
            const favoriteBtn = document.querySelector('.favorite-btn');

            favoriteBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');

                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = 'var(--primary-color)';

                    // Optional: Save to localStorage or send to server
                    localStorage.setItem('favorite_post_' + {{ $post->id }}, 'true');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = 'white';

                    // Optional: Remove from localStorage or send to server
                    localStorage.removeItem('favorite_post_' + {{ $post->id }});
                }
            });

            // Check if post is already favorited
            if (localStorage.getItem('favorite_post_' + {{ $post->id }}) === 'true') {
                const icon = favoriteBtn.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = 'var(--primary-color)';
            }

            // Image lightbox effect for post content images
            const contentImages = document.querySelectorAll('.post-content img');
            contentImages.forEach(img => {
                img.style.cursor = 'pointer';
                img.addEventListener('click', function() {
                    // Simple lightbox effect - you can replace with a proper lightbox library
                    const overlay = document.createElement('div');
                    overlay.style.position = 'fixed';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.backgroundColor = 'rgba(0,0,0,0.9)';
                    overlay.style.display = 'flex';
                    overlay.style.alignItems = 'center';
                    overlay.style.justifyContent = 'center';
                    overlay.style.zIndex = '9999';
                    overlay.style.cursor = 'pointer';

                    const imgClone = document.createElement('img');
                    imgClone.src = this.src;
                    imgClone.style.maxWidth = '90%';
                    imgClone.style.maxHeight = '90%';
                    imgClone.style.objectFit = 'contain';

                    overlay.appendChild(imgClone);
                    document.body.appendChild(overlay);

                    overlay.addEventListener('click', function() {
                        document.body.removeChild(overlay);
                    });
                });
            });

            // Comments System
            initializeCommentsSystem();
        });

        function initializeCommentsSystem() {
            // Comment form submission
            const commentForm = document.getElementById('comment-form');
            const guestCommentForm = document.getElementById('guest-comment-form');

            if (commentForm) {
                commentForm.addEventListener('submit', handleCommentSubmit);
            }

            if (guestCommentForm) {
                guestCommentForm.addEventListener('submit', handleGuestCommentSubmit);
            }

            // Load more comments
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('load-more-comments')) {
                    loadMoreComments(e.target);
                }
            });
        }

        function handleCommentSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner');
            const btnText = submitBtn.querySelector('.btn-text');

            // Validate form
            const content = form.querySelector('[name="content"]').value.trim();
            if (content.length < 3) {
                showNotification('Comment must be at least 3 characters long', 'error');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            if (spinner) spinner.style.display = 'inline-block';
            if (btnText) btnText.style.display = 'none';

            const formData = new FormData(form);

            fetch('{{ route("comments.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new comment to the list
                    const commentsContainer = document.getElementById('comments-container');
                    if (commentsContainer.querySelector('.no-comments')) {
                        commentsContainer.innerHTML = '<div class="comments-section"><h3 class="section-title">Comments</h3><div class="section-divider"></div><div class="comments-list"></div></div>';
                    }
                    
                    let commentsList = commentsContainer.querySelector('.comments-list');
                    if (!commentsList) {
                        const commentsSection = document.createElement('div');
                        commentsSection.className = 'comments-section';
                        commentsSection.innerHTML = '<h3 class="section-title">Comments</h3><div class="section-divider"></div><div class="comments-list"></div>';
                        commentsContainer.appendChild(commentsSection);
                        commentsList = commentsSection.querySelector('.comments-list');
                    }
                    
                    commentsList.insertAdjacentHTML('afterbegin', data.html);

                    // Reset form
                    form.reset();
                    const replyToId = document.getElementById('reply-to-id');
                    const cancelReply = document.getElementById('cancel-reply');
                    if (replyToId) replyToId.value = '';
                    if (cancelReply) cancelReply.style.display = 'none';

                    // Show success message
                    showNotification('Comment posted successfully!', 'success');
                } else {
                    showNotification(data.message || 'Error posting comment', 'error');
                }
            })
            .catch(error => {
                showNotification('Network error: Could not post comment', 'error');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                if (spinner) spinner.style.display = 'none';
                if (btnText) btnText.style.display = 'inline';
            });
        }

        function handleGuestCommentSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner');
            const btnText = submitBtn.querySelector('.btn-text');

            // Validate form
            const content = form.querySelector('[name="content"]').value.trim();
            const guestName = form.querySelector('[name="guest_name"]').value.trim();
            const guestEmail = form.querySelector('[name="guest_email"]').value.trim();
            
            if (content.length < 3) {
                showNotification('Comment must be at least 3 characters long', 'error');
                return;
            }
            if (!guestName) {
                showNotification('Name is required', 'error');
                return;
            }
            if (!guestEmail) {
                showNotification('Email is required', 'error');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            if (spinner) spinner.style.display = 'inline-block';
            if (btnText) btnText.style.display = 'none';

            const formData = new FormData(form);

            fetch('{{ route("comments.storeGuest") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form
                    form.reset();
                    const guestReplyToId = document.getElementById('guest-reply-to-id');
                    const guestCancelReply = document.getElementById('guest-cancel-reply');
                    if (guestReplyToId) guestReplyToId.value = '';
                    if (guestCancelReply) guestCancelReply.style.display = 'none';

                    // Show success message
                    showNotification('Comment submitted successfully and is pending approval!', 'success');
                } else {
                    showNotification(data.message || 'Error submitting comment', 'error');
                }
            })
            .catch(error => {
                showNotification('Network error: Could not submit comment', 'error');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                if (spinner) spinner.style.display = 'none';
                if (btnText) btnText.style.display = 'inline';
            });
        }

        function replyToComment(commentId) {
            const replyToId = document.getElementById('reply-to-id');
            const cancelReplyBtn = document.getElementById('cancel-reply');
            const guestReplyToId = document.getElementById('guest-reply-to-id');
            const guestCancelReplyBtn = document.getElementById('guest-cancel-reply');

            if (replyToId) {
                replyToId.value = commentId;
                if (cancelReplyBtn) cancelReplyBtn.style.display = 'inline-block';
            }

            if (guestReplyToId) {
                guestReplyToId.value = commentId;
                if (guestCancelReplyBtn) guestCancelReplyBtn.style.display = 'inline-block';
            }

            // Scroll to comment form
            const commentFormWrapper = document.querySelector('.comment-form-wrapper');
            if (commentFormWrapper) {
                commentFormWrapper.scrollIntoView({ 
                    behavior: 'smooth' 
                });
            }

            // Focus on textarea
            const textarea = document.querySelector('#comment-content, #guest-comment-content');
            if (textarea) {
                textarea.focus();
                textarea.placeholder = 'Write your reply here...';
            }
        }

        function loadMoreComments(button) {
            const page = button.dataset.page;
            const postId = button.dataset.postId;
            const spinner = button.querySelector('.spinner');
            const btnText = button.querySelector('.btn-text');

            // Show loading state
            button.disabled = true;
            spinner.style.display = 'inline-block';
            btnText.style.display = 'none';

            fetch(`{{ route('comments.get', ':postId') }}`.replace(':postId', postId) + `?page=${page}`, {
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Append new comments
                    const commentsList = document.querySelector('.comments-list');
                    commentsList.insertAdjacentHTML('beforeend', data.html);

                    // Update button
                    button.dataset.page = parseInt(page) + 1;
                    if (parseInt(page) + 1 > data.lastPage) {
                        button.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error loading comments', 'error');
            })
            .finally(() => {
                // Reset loading state
                button.disabled = false;
                spinner.style.display = 'none';
                btnText.style.display = 'inline';
            });
        }

        function likeComment(commentId) {
            fetch(`{{ route('comments.like', ':commentId') }}`.replace(':commentId', commentId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Comment liked successfully!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error liking comment', 'error');
            });
        }

        function reportComment(commentId) {
            const reason = prompt('Please provide a reason for reporting this comment:');
            if (!reason) return;

            fetch(`{{ route('comments.report', ':commentId') }}`.replace(':commentId', commentId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Comment reported successfully!', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error reporting comment', 'error');
            });
        }

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }

        // Cancel reply functionality
        document.addEventListener('click', function(e) {
            if (e.target.id === 'cancel-reply') {
                document.getElementById('reply-to-id').value = '';
                e.target.style.display = 'none';
            }
            
            if (e.target.id === 'guest-cancel-reply') {
                document.getElementById('guest-reply-to-id').value = '';
                e.target.style.display = 'none';
            }
        });
    </script>
@endsection
