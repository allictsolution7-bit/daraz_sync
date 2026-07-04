@extends('frontend.app')
@section('title')
    {{ $page->title }} - Thikana
@endsection

@section('styles')
    <style>

        /* Dynamic Pages Styling */
        .page-container {
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .page-underline {
            width: 80px;
            height: 3px;
            background-color: var(--primary-color);
            margin: 0 auto;
            position: relative;
        }

        .page-underline:before {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
            left: -60px;
            top: 0;
        }

        .page-underline:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
            right: -60px;
            top: 0;
        }

        .page-content {
            line-height: 1.8;
            color: var(--text-color);
            font-size: 16px;
        }

        .page-content h2 {
            font-size: 24px;
            margin: 25px 0 15px;
            color: var(--secondary-color);
        }

        .page-content h3 {
            font-size: 20px;
            margin: 20px 0 10px;
            color: var(--secondary-color);
        }

        .page-content p {
            margin-bottom: 15px;
        }

        .page-content ul,
        .page-content ol {
            margin-bottom: 15px;
            padding-left: 20px;
        }

        .page-content li {
            margin-bottom: 8px;
        }

        .page-content img {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: var(--shadow-sm);
        }

        .page-content a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .page-content a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .page-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding: 15px 20px;
            margin: 20px 0;
            background-color: var(--light-color);
            font-style: italic;
        }

        .page-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .page-content table th,
        .page-content table td {
            border: 1px solid var(--border-color);
            padding: 10px;
        }

        .page-content table th {
            background-color: var(--light-color);
            font-weight: 600;
        }

        /* Responsive adjustments for dynamic pages */
        @media (max-width: 768px) {
            .page-container {
                margin: 20px auto;
            }

            .page-title {
                font-size: 26px;
            }

            .page-content {
                font-size: 15px;
            }
        }

    </style>
@endsection

@section('content')
    <div class="base-container page-container">
        <div class="page-header">
            <h1 class="page-title">{{ $page->title }}</h1>
            <div class="page-underline"></div>
        </div>
        <div class="page-content">
            {!! $page->content !!}
        </div>
    </div>
@endsection
