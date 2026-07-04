@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        /* Profile-specific styles */
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
            border: 3px solid var(--light-color);
        }

        .profile-name {
            font-size: 24px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .profile-email {
            color: var(--text-color);
        }

        .profile-actions {
            margin-top: 25px;
        }

        .edit-profile-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: var(--transition);
        }

        .edit-profile-btn:hover {
            background-color: var(--secondary-color);
        }

        .settings-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .settings-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .settings-link {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .settings-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .settings-link:hover {
            color: var(--primary-color);
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

            <!-- Main Content -->
            <div class="profile-card">
                <!-- Profile Section -->
                <div class="profile-info">
                    <div class="profile-header">
                        <img src=" {{ $profileImageUrl }}" alt="Profile Image"
                            class="profile-image">
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
                            <a href="{{ route('account.edit') }}" class="edit-profile-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18">
                                    <path fill="currentColor" d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/>
                                  </svg> <span style="margin-left: 7px;">Edit Profile</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div class="profile-settings">
                    <h2 class="settings-title">Account Settings</h2>
                    <div class="settings-options">
                        <div class="settings-item">
                            <a href="{{ route('account.edit') }}" class="settings-link">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="18" height="18">
                                    <path fill="currentColor" d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/>
                                  </svg> <span style="margin-left: 7px;">Change Password</span>
                            </a>
                        </div>
                            {{-- <div class="settings-item">
                                <a href="{{ route('account.edit') }} class="settings-link">
                                    <i class="fa-solid fa-bell"></i> Notification Settings
                                </a>
                            </div> --}}
                        {{-- <div class="settings-item">
                            <a href="{{ route('account.edit') }}" class="settings-link">
                                <i class="fa-solid fa-credit-card"></i> Payment Methods
                            </a>
                        </div> --}}
                        <div class="settings-item">
                            <a href="{{ route('account.edit') }}" class="settings-link">
                                <svg width="24" height="24" viewBox="0 0 422.518 422.518" fill="currentColor">
                                    <path d="M422.512,215.424c0-0.079-0.004-0.158-0.005-0.237c-0.116-5.295-4.368-9.514-9.727-9.514h-2.554l-39.443-76.258
                                                                c-1.664-3.22-4.983-5.225-8.647-5.226l-67.34-0.014l2.569-20.364c0.733-8.138-1.783-15.822-7.086-21.638
                                                                c-5.293-5.804-12.683-9.001-20.81-9.001h-209c-5.255,0-9.719,4.066-10.22,9.308l-2.095,16.778h119.078
                                                                c7.732,0,13.836,6.268,13.634,14c-0.203,7.732-6.635,14-14.367,14H126.78c0.007,0.02,0.014,0.04,0.021,0.059H10.163
                                                                c-5.468,0-10.017,4.432-10.16,9.9c-0.143,5.468,4.173,9.9,9.641,9.9H164.06c7.168,1.104,12.523,7.303,12.326,14.808
                                                                c-0.216,8.242-7.039,14.925-15.267,14.994H54.661c-5.523,0-10.117,4.477-10.262,10c-0.145,5.523,4.215,10,9.738,10h105.204
                                                                c7.273,1.013,12.735,7.262,12.537,14.84c-0.217,8.284-7.109,15-15.393,15H35.792v0.011H25.651c-5.523,0-10.117,4.477-10.262,10
                                                                c-0.145,5.523,4.214,10,9.738,10h8.752l-3.423,35.818c-0.734,8.137,1.782,15.821,7.086,21.637c5.292,5.805,12.683,9.001,20.81,9.001
                                                                h7.55C69.5,333.8,87.3,349.345,109.073,349.345c21.773,0,40.387-15.545,45.06-36.118h94.219c7.618,0,14.83-2.913,20.486-7.682
                                                                c5.172,4.964,12.028,7.682,19.514,7.682h1.55c3.597,20.573,21.397,36.118,43.171,36.118c21.773,0,40.387-15.545,45.06-36.118h6.219
                                                                c16.201,0,30.569-13.171,32.029-29.36l6.094-67.506c0.008-0.091,0.004-0.181,0.01-0.273c0.01-0.139,0.029-0.275,0.033-0.415
                                                                C422.52,215.589,422.512,215.508,422.512,215.424z M109.597,329.345c-13.785,0-24.707-11.214-24.346-24.999
                                                                c0.361-13.786,11.87-25.001,25.655-25.001c13.785,0,24.706,11.215,24.345,25.001C134.89,318.131,123.382,329.345,109.597,329.345z
                                                                 M333.597,329.345c-13.785,0-24.706-11.214-24.346-24.999c0.361-13.786,11.87-25.001,25.655-25.001
                                                                c13.785,0,24.707,11.215,24.345,25.001C358.89,318.131,347.382,329.345,333.597,329.345z M396.457,282.588
                                                                c-0.52,5.767-5.823,10.639-11.58,10.639h-6.727c-4.454-19.453-21.744-33.882-42.721-33.882c-20.977,0-39.022,14.429-44.494,33.882
                                                                h-2.059c-2.542,0-4.81-0.953-6.389-2.685c-1.589-1.742-2.337-4.113-2.106-6.676l12.609-139.691l28.959,0.006l-4.59,50.852
                                                                c-0.735,8.137,1.78,15.821,7.083,21.637c5.292,5.806,12.685,9.004,20.813,9.004h56.338L396.457,282.588z"></path>
                                </svg> <span style="margin-left: 7px;">Shipping Addresses</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
