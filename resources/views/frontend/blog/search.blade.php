@extends('frontend.app')

@section('styles')
    <style>
        /* Banner Styles */
        .blog-banner {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('new/blog-banner.jpg') }}');
            background-size: cover;
            background-position: center;
            padding: 120px 0 !important;
            margin-bottom: 30px;
        }

        /* Load More Button */
        .btn-load-more {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: var(--transition);
            margin-top: 20px;
        }

        .btn-load-more:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-load-more:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Hide spinner by default */
        .spinner {
            display: none;
        }

        /* Show spinner when loading */
        .loading .spinner {
            display: inline-block;
        }

        /* Section Title Styles */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .section-divider {
            height: 3px;
            width: 100px;
            background-color: var(--primary-color);
            position: relative;
            margin-bottom: 25px;
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

        /* Blog Card Styles */
        .blog-card {
            background: var(--body-bg);
            border-radius: 5px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            position: relative;
            height: 100%;
            transition: var(--transition);
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .blog-image-link {
            display: block;
            position: relative;
            overflow: hidden;
        }

        .blog-image-link img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-card:hover .blog-image-link img {
            transform: scale(1.05);
        }

        .category-label a {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 3px;
        }

        .blog-content {
            padding: 20px;
        }

        .blog-content h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .blog-content h3 a {
            color: var(--dark-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .blog-content h3 a:hover {
            color: var(--primary-color);
        }

        .blog-content p {
            color: var(--text-color);
            font-size: 14px;
            margin-bottom: 0;
        }

        /* Blog Layout */
        .blog-archive {
            padding: 60px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
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

        .col-md-4 {
            width: 33.333333%;
            padding: 0 15px;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        /* Sidebar Styles */
        .blog-sidebar {
            margin-bottom: 30px;
        }

        .sidebar-widget {
            background: var(--body-bg);
            border-radius: 5px;
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
        .search-input-wrap {
            position: relative;
        }

        .search-input-wrap input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            font-size: 14px;
            transition: var(--transition);
        }

        .search-input-wrap input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(237, 26, 37, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
        }

        /* Categories Widget */
        .categories-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .categories-list li {
            border-bottom: 1px solid var(--border-color);
            padding: 10px 0;
        }

        .categories-list li:last-child {
            border-bottom: none;
        }

        .categories-list a {
            color: var(--text-color);
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .categories-list a:hover {
            color: var(--primary-color);
        }

        .post-count {
            background-color: var(--light-color);
            color: var(--text-color);
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 10px;
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
            flex: 0 0 70px;
            margin-right: 15px;
        }

        .post-thumb img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
        }

        .post-info {
            flex: 1;
        }

        .post-info h5 {
            font-size: 14px;
            margin: 0 0 5px;
            line-height: 1.4;
        }

        .post-info h5 a {
            color: var(--dark-color);
            text-decoration: none;
            transition: var(--transition);
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
            gap: 10px;
        }

        .tag {
            display: inline-block;
            padding: 5px 12px;
            background-color: var(--light-color);
            color: var(--text-color);
            font-size: 12px;
            border-radius: 3px;
            text-decoration: none;
            transition: var(--transition);
        }

        .tag:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 30px 0 0;
            justify-content: center;
        }

        .page-item {
            margin: 0 5px;
        }

        .page-link {
            display: block;
            padding: 8px 15px;
            border-radius: 5px;
            background-color: var(--light-color);
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {

            .col-lg-3,
            .col-lg-9 {
                width: 100%;
            }

            .blog-sidebar {
                margin-top: 30px;
            }
        }

        @media (max-width: 767px) {
            .col-md-4 {
                width: 100%;
            }

            .blog-banner {
                padding: 50px 0 !important;
            }

            .blog-banner h1 {
                font-size: 28px;
            }

            .section-title {
                font-size: 22px;
            }
        }

        @media (max-width: 575px) {
            .container {
                padding: 0 10px;
            }

            .blog-content {
                padding: 15px;
            }

            .sidebar-widget {
                padding: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Blog Banner Section -->
    <section class="blog-banner text-center text-white py-5">
        <div class="container">
            <h1>Search Results</h1>
            <p class="lead">
                Showing results for: "{{ $query }}"
            </p>
        </div>
    </section>

    <section class="blog-archive">
        <div class="container">
            <div class="row">
                <!-- Main Content - Blog Posts -->
                <div class="col-lg-9">
                    <div class="post-area">
                        <h2 class="section-title">Search Results for "{{ $query }}"</h2>
                        <div class="section-divider"></div>

                        <div class="row" id="posts-container">
                            @if ($posts->count() > 0)
                                @foreach ($posts as $post)
                                    <div class="col-md-4 mb-4">
                                        <div class="blog-card">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="blog-image-link">
                                                @if ($post->image)
                                                    <img src="{{ asset($post->image) }}"
                                                        alt="{{ $post->image_alt ?? $post->title }}">
                                                @else
                                                    <img src="{{ asset('img/placeholder.jpg') }}" alt="Placeholder">
                                                @endif
                                            </a>
                                            <div class="blog-content">
                                                <div class="category-label mb-2">
                                                    @if ($post->postcategory)
                                                        <a href="{{ route('blog.category', $post->postcategory->slug) }}">
                                                            {{ $post->postcategory->name }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <h3>
                                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                                </h3>
                                                <p>{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="alert alert-info">No posts found matching "{{ $query }}". Please try
                                        a different search term.</div>
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container">
                            {{ $posts->appends(['q' => $query])->links() }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-3">
                    @include('frontend.blog.partials.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection
