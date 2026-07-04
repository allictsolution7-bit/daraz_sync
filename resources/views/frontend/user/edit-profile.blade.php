@extends('frontend.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .profile-card{
            display: block
        }
        /* Edit profile specific styles */
        .text-muted {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 4px;
            display: block;
        }

        /* Password strength indicator */
        .password-strength {
            height: 5px;
            margin-top: 8px;
            border-radius: 3px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .password-strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }

        .strength-weak {
            background-color: #dc3545;
            width: 25%;
        }

        .strength-medium {
            background-color: #ffc107;
            width: 50%;
        }

        .strength-strong {
            background-color: #28a745;
            width: 100%;
        }

        .profile-photo-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--light-color);
        }
    </style>
@endsection

@section('content')
    @php
        $currentProfileImage = $user->profile_photo_path
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path)
            : asset('clientside/images/profile.png');
    @endphp

    <div class="base-container profile-container">
        <div class="profile-layout">
             <!-- Sidebar Menu (now using the partial) -->
             @include('frontend.user.partials.sidebar')

            <!-- Edit Profile Form -->
            <div class="profile-card">
                <div class="profile-info">
                    <h2 class="settings-title">Edit Profile</h2>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $currentProfileImage }}" alt="Profile photo" class="profile-photo-preview" id="profile-preview">
                                <input type="file" id="profile_photo" name="profile_photo" class="form-control" accept="image/*">
                            </div>
                            <small class="text-muted">Max size 2MB. JPG, PNG, GIF accepted.</small>
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="password-field" style="position: relative;">
                                <input type="password" id="current_password" name="current_password" class="form-control">
                                <i class="fa-solid fa-eye password-toggle"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-color);"
                                    onclick="togglePassword('current_password', this)"></i>
                            </div>
                            <small class="text-muted">Leave blank if you don't want to change password</small>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <div class="password-field" style="position: relative;">
                                <input type="password" id="password" name="password" class="form-control">
                                <i class="fa-solid fa-eye password-toggle"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-color);"
                                    onclick="togglePassword('password', this)"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="password-field" style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control">
                                <i class="fa-solid fa-eye password-toggle"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--text-color);"
                                    onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('account.show') }}"
                                style="margin-left: 10px; text-decoration: none; color: var(--text-color);">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Optional: Add password strength meter
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.querySelector('.password-strength-meter');

            if (password.length === 0) {
                strengthMeter.className = 'password-strength-meter';
                strengthMeter.style.width = '0';
            } else if (password.length < 6) {
                strengthMeter.className = 'password-strength-meter strength-weak';
            } else if (password.length < 10) {
                strengthMeter.className = 'password-strength-meter strength-medium';
            } else {
                strengthMeter.className = 'password-strength-meter strength-strong';
            }
        });

        const profilePhotoInput = document.getElementById('profile_photo');
        const profilePreview = document.getElementById('profile-preview');
        if (profilePhotoInput && profilePreview) {
            profilePhotoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) {
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (event) {
                    if (event.target && event.target.result) {
                        profilePreview.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
