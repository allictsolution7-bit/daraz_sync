@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            border: 3px solid #e2e8f0;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #64748b;
        }

        .profile-actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .edit-profile-btn {
            display: inline-flex;
            align-items: center;
            background-color: #ff6a00;
            color: white !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .edit-profile-btn:hover {
            background-color: #e05d00;
            color: white !important;
        }

        .portal-btn {
            display: inline-flex;
            align-items: center;
            background-color: #4f46e5;
            color: white !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .portal-btn:hover {
            background-color: #4338ca;
            color: white !important;
        }

        .settings-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .settings-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .settings-link {
            color: #334155;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .settings-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .settings-link:hover {
            color: #ff6a00;
        }
    </style>
@endsection

@section('content')
    <div class="base-container profile-container">
        @php
        $profileImageUrl = $user->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path)
            : asset('clientside/images/profile.png');
        @endphp
        <div class="profile-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content Card -->
            <div class="profile-card">
                <!-- Profile Section -->
                <div class="profile-info">
                    <div class="profile-header">
                        <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" class="profile-image">
                        <div class="profile-details">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <p class="profile-email">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="profile-data">
                        <div class="profile-item">
                            <strong class="profile-label">Phone:</strong>
                            <p class="profile-value">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="profile-item">
                            <strong class="profile-label">Address:</strong>
                            <p class="profile-value">{{ $user->address ?? 'Not provided' }}</p>
                        </div>
                        <div class="profile-actions">
                            @auth
                                @if(auth()->user()->isVendor() || auth()->user()->hasRole('vendor'))
                                    <a href="{{ Route::has('vendor.dashboard') ? route('vendor.dashboard') : url('/vendor/dashboard') }}" class="portal-btn">
                                        <i class="fa-solid fa-store" style="margin-right: 8px;"></i> Vendor Dashboard
                                    </a>
                                @elseif(auth()->user()->isAdmin() || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('super admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="portal-btn">
                                        <i class="fa-solid fa-gauge" style="margin-right: 8px;"></i> Admin Dashboard
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('account.edit') }}" class="edit-profile-btn">
                                <i class="fa-solid fa-pen-to-square" style="margin-right: 8px;"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="profile-settings">
                    <h2 class="settings-title">Account Quick Actions</h2>
                    <div class="settings-options">
                        <div class="settings-item">
                            <a href="{{ route('account.edit') }}" class="settings-link">
                                <i class="fa-solid fa-lock text-warning"></i> Change Security Password
                            </a>
                        </div>
                        <div class="settings-item">
                            <a href="{{ route('account.orders') }}" class="settings-link">
                                <i class="fa-solid fa-bag-shopping text-primary"></i> View Order History
                            </a>
                        </div>
                        <div class="settings-item">
                            <a href="{{ route('order.track') }}" class="settings-link">
                                <i class="fa-solid fa-truck-fast text-success"></i> Track Live Shipment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
