@extends('frontend.app')

@section('title', 'প্রকাশকগণ')

@section('styles')
    <style>
        .container{
            margin:10px auto;
        }
        .publishers-header {
            background: var(--primary-color);
            color: #fff;
            padding: 18px 32px 12px 32px;
            border-radius: 12px 12px 0 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .publishers-search {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 8px;
            padding: 6px 12px;
            margin: 18px 0 0 0;
            box-shadow: var(--shadow-sm);
            max-width: 400px;
            width: 100%;
        }
        .publishers-search input {
            border: none;
            outline: none;
            font-size: 1.1rem;
            flex: 1;
            font-family: inherit;
            background: transparent;
        }
        .publishers-search button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        .publishers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            padding: 0 16px 32px 16px;
        }
        .publisher-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 10px 20px 10px;
            transition: box-shadow 0.2s, transform 0.2s;
            min-height: 160px;
            position: relative;
        }
        .publisher-card:hover, .publisher-card:focus-within {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px) scale(1.02);
        }
        .publisher-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            margin-bottom: 14px;
            background: #fff;
        }
        .publisher-name {
            font-size: 1.08rem;
            font-weight: 600;
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 10px;
            font-family: inherit;
            word-break: break-word;
        }
        .publisher-profile-link {
            display: inline-block;
            margin-top: 6px;
            padding: 6px 18px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 20px;
            font-size: 0.98rem;
            font-weight: 500;
            text-align: center;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .publisher-profile-link:hover, .publisher-profile-link:focus {
            background: var(--secondary-color);
            color: #fff;
            text-decoration: none;
        }
        .custom-pagination nav {
            font-size: 1rem !important;
        }
        .custom-pagination .pagination {
            font-size: 1rem !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px;
        }
        .custom-pagination .page-link {
            font-size: 1rem !important;
            padding: 0.5rem 1rem;
            border-radius: 50% !important;
            margin: 0 3px;
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            background: #fff;
            transition: background 0.2s, color 0.2s, border 0.2s;
        }
        .custom-pagination .page-link.active, .custom-pagination .page-link:focus, .custom-pagination .page-link:hover {
            background: var(--primary-color);
            color: #fff;
            border: 1px solid var(--primary-color);
        }
        .custom-pagination .pagination svg {
            width: 20px !important;
            height: 20px !important;
        }
        @media (max-width: 900px) {
            .container{
                width:100%
            }
            .publishers-header {
                font-size: 1.3rem;
                padding: 14px 10px 10px 10px;
            }
            .publishers-grid {
                gap: 12px;
                padding: 0 4px 24px 4px;
            }
        }
        @media (max-width: 600px) {
            .container{
                width:100%
            }
            .publishers-header, .publishers-search, .publishers-grid {
                padding-left: 4px;
                padding-right: 4px;
            }
            .publishers-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .publishers-search {
                margin: 10px 0 0 0;
                width: 100%;
                max-width: 100%;
            }
            .publishers-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="base-container container py-5">
        <div class="publishers-header">
            প্রকাশকগণ
            <form class="publishers-search" method="GET" action="">
                <input type="text" name="q" placeholder="প্রকাশক অনুসন্ধান করুন" value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="publishers-grid">
            @foreach ($publishers as $publisher)
                <div class="publisher-card">
                    <img src="{{ $publisher->logo }}" class="publisher-img" alt="{{ $publisher->name }}">
                    <div class="publisher-name">{{ $publisher->name }}</div>
                    <a href="{{ route('frontend.publishers.show', $publisher->id) }}" class="publisher-profile-link">প্রোফাইল দেখুন</a>
                </div>
            @endforeach
        </div>
        <!-- <div class="d-flex justify-content-center mt-5">
            <div class="custom-pagination">
                {{ $publishers->links() }}
            </div>
        </div> -->

    </div>
@endsection
