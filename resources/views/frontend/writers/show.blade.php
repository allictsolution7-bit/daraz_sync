@extends('frontend.app')

@section('title', $writer->name)
@section('styles')
    <link rel="stylesheet" href="/new/style.css">
    <style>
        .writer-detail-container {
            max-width: 900px;
            margin: 32px auto;
            padding: 0 8px;
        }
        .writer-detail-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 32px 24px 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .writer-detail-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
        }
        .writer-detail-img-wrapper {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .writer-detail-img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            background: #fff;
            box-shadow: 0 2px 12px rgba(44,62,80,0.08);
        }
        .writer-detail-info {
            flex: 1;
            min-width: 220px;
        }
        .writer-detail-name {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 10px;
            font-family: inherit;
        }
        .writer-detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .writer-detail-table th {
            text-align: right;
            color: var(--primary-color);
            font-weight: 500;
            padding: 6px 12px 6px 0;
            font-size: 1rem;
            white-space: nowrap;
        }
        .writer-detail-table td {
            text-align: left;
            color: var(--dark-color);
            font-size: 1rem;
            padding: 6px 0 6px 6px;
        }
        .writer-detail-bio {
            margin-top: 18px;
            font-size: 1.08rem;
            color: var(--text-color);
            line-height: 1.7;
            background: var(--light-color);
            border-radius: 8px;
            padding: 18px 16px;
        }
        .writer-back-btn {
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
        .writer-back-btn:hover, .writer-back-btn:focus {
            background: var(--secondary-color);
            color: #fff;
            text-decoration: none;
        }
        @media (max-width: 700px) {
            .writer-detail-header {
                flex-direction: column;
                gap: 18px;
                align-items: flex-start;
            }
            .writer-detail-img {
                width: 110px;
                height: 110px;
            }
            .writer-detail-card {
                padding: 18px 6px 16px 6px;
            }
        }
    </style>
@endsection


@section('content')
    <div class="writer-detail-container">
        <div class="writer-detail-card">
            <div class="writer-detail-header">
                <div class="writer-detail-img-wrapper">
                    <img src="{{ $writer->photo }}" alt="{{ $writer->name }}" class="writer-detail-img">
                </div>
                <div class="writer-detail-info">
                    <div class="writer-detail-name">{{ $writer->name }}</div>
                    <table class="writer-detail-table">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $writer->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $writer->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $writer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $writer->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Popularity Score</th>
                                <td>{{ $writer->popularity_score ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="writer-detail-bio">
                <strong>Bio</strong><br>
                {!! nl2br(e($writer->bio)) !!}
            </div>
            <a href="{{ url()->previous() }}" class="writer-back-btn">&larr; পূর্বের পাতায় ফিরে যান</a>
        </div>
    </div>
@endsection
