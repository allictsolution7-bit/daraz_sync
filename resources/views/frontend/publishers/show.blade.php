@extends('frontend.app')

@section('title', $publisher->name)
@section('styles')
    <link rel="stylesheet" href="/new/style.css">
    <style>
        .publisher-detail-container {
            max-width: 900px;
            margin: 32px auto;
            padding: 0 8px;
        }
        .publisher-detail-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 32px 24px 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .publisher-detail-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
        }
        .publisher-detail-img-wrapper {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .publisher-detail-img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            background: #fff;
            box-shadow: 0 2px 12px rgba(44,62,80,0.08);
        }
        .publisher-detail-info {
            flex: 1;
            min-width: 220px;
        }
        .publisher-detail-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            font-family: inherit;
        }
        .publisher-detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .publisher-detail-table th {
            text-align: right;
            color: var(--primary-color);
            font-weight: 500;
            padding: 6px 12px 6px 0;
            font-size: 1rem;
            white-space: nowrap;
        }
        .publisher-detail-table td {
            text-align: left;
            color: var(--dark-color);
            font-size: 1rem;
            padding: 6px 0 6px 6px;
        }
        .publisher-detail-bio {
            margin-top: 18px;
            font-size: 1.08rem;
            color: var(--text-color);
            line-height: 1.7;
            background: var(--light-color);
            border-radius: 8px;
            padding: 18px 16px;
        }
        .publisher-back-btn {
            display: inline-block;
            margin-top: 24px;
            padding: 8px 28px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 24px;
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .publisher-back-btn:hover, .publisher-back-btn:focus {
            background: var(--secondary-color);
            color: #fff;
            text-decoration: none;
        }
        @media (max-width: 700px) {
            .publisher-detail-header {
                flex-direction: column;
                gap: 18px;
                align-items: flex-start;
            }
            .publisher-detail-img {
                width: 110px;
                height: 110px;
            }
            .publisher-detail-card {
                padding: 18px 6px 16px 6px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="publisher-detail-container">
        <div class="publisher-detail-card">
            <div class="publisher-detail-header">
                <div class="publisher-detail-img-wrapper">
                    <img src="{{ $publisher->logo }}" alt="{{ $publisher->name }}" class="publisher-detail-img">
                </div>
                <div class="publisher-detail-info">
                    <div class="publisher-detail-name">{{ $publisher->name }}</div>
                    <table class="publisher-detail-table">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $publisher->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $publisher->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $publisher->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $publisher->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Popularity Score</th>
                                <td>{{ $publisher->popularity_score ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="publisher-detail-bio">
                <strong>Bio</strong><br>
                {!! nl2br(e($publisher->bio)) !!}
            </div>
            <a href="{{ url()->previous() }}" class="publisher-back-btn">&larr; পূর্বের পাতায় ফিরে যান</a>
        </div>
    </div>
@endsection
