@extends('frontend.app')

@section('title', 'লেখকগণ')

@section('styles')
    
    <style>
        .writers-container{
            margin:10px auto;
        }
        .writers-header {
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
        .writers-search {
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
        .writers-search input {
            border: none;
            outline: none;
            font-size: 1.1rem;
            flex: 1;
            font-family: inherit;
            background: transparent;
        }
        .writers-search button {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.5rem;
            cursor: pointer;
        }
        .writers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            padding: 0 16px 32px 16px;
        }
        .writer-card {
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
        .writer-card:hover, .writer-card:focus-within {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px) scale(1.02);
        }
        .writer-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            margin-bottom: 14px;
            background: #fff;
        }
        .writer-name {
            font-size: 1.08rem;
            font-weight: 600;
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 10px;
            font-family: inherit;
            word-break: break-word;
        }
        .writer-profile-link {
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
        .writer-profile-link:hover, .writer-profile-link:focus {
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
            .writers-container{
                width:100%;
            }
            .writers-header {
                font-size: 1.3rem;
                padding: 14px 10px 10px 10px;
            }
            .writers-grid {
                gap: 12px;
                padding: 0 4px 24px 4px;
            }
        }
        @media (max-width: 600px) {
            .writers-container{
                width:100%;
            }
            .writers-header, .writers-search, .writers-grid {
                padding-left: 4px;
                padding-right: 4px;
            }
            .writers-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .writers-search {
                margin: 10px 0 0 0;
                width: 100%;
                max-width: 100%;
            }
            .writers-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="base-container writers-container">
        <div class="writers-header">
            লেখকগণ
            <form class="writers-search" method="GET" action="">
                <input type="text" name="q" placeholder="লেখক অনুসন্ধান করুন" value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="writers-grid">
            @foreach ($writers as $writer)
                <div class="writer-card">
                    <img src="{{ $writer->photo }}" class="writer-img" alt="{{ $writer->name }}">
                    <div class="writer-name">{{ $writer->name }}</div>
                    <a href="{{ route('frontend.writers.show', $writer->id) }}" class="writer-profile-link">প্রোফাইল দেখুন</a>
                </div>
            @endforeach
        </div>
        <!-- <div class="d-flex justify-content-center mt-5">
            <div class="custom-pagination">
                {{ $writers->links() }}
            </div>
        </div> -->
    </div>
@endsection
