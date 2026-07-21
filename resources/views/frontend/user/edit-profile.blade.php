@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .profile-card {
            display: block !important;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 30px;
        }

        .edit-header-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
        }

        .edit-header-title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .edit-header-title i {
            color: #ff6a00;
        }

        .avatar-upload-area {
            display: flex;
            align-items: center;
            gap: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .profile-photo-preview {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group-custom {
            margin-bottom: 20px;
        }

        .form-label-custom {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            color: #0f172a;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .form-control-custom:focus {
            border-color: #ff6a00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 106, 0, 0.15);
        }

        .section-separator {
            margin: 30px 0 20px 0;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
        }

        .section-separator-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-field-wrapper {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748b;
            font-size: 16px;
            transition: color 0.2s ease;
        }

        .password-toggle-icon:hover {
            color: #ff6a00;
        }

        .form-actions-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-save-custom {
            background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);
            color: #ffffff !important;
            border: none;
            padding: 12px 28px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(255, 106, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-save-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 106, 0, 0.35);
        }

        .btn-cancel-custom {
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 20px;
            transition: color 0.2s ease;
        }

        .btn-cancel-custom:hover {
            color: #0f172a;
        }

        @media (max-width: 768px) {
            .form-grid-2 {
                grid-template-columns: 1fr;
            }
            .avatar-upload-area {
                flex-direction: column;
                text-align: center;
            }
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
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Edit Profile Form Card -->
            <div class="profile-card">
                <div class="edit-header-box">
                    <h2 class="edit-header-title">
                        <i class="fa-solid fa-user-pen"></i> Edit Profile & Settings
                    </h2>
                    <a href="{{ route('account.show') }}" class="btn-cancel-custom" style="padding: 0;">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>

                @if (session('success'))
                    <div style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 14px 18px; border-radius: 8px; margin-bottom: 25px; font-weight: 500;">
                        <i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding: 14px 18px; border-radius: 8px; margin-bottom: 25px;">
                        <strong style="display: block; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation"></i> Please check the highlighted errors:</strong>
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

                    <!-- Photo Upload Section -->
                    <div class="avatar-upload-area">
                        <img src="{{ $currentProfileImage }}" alt="{{ $user->name }}" class="profile-photo-preview" id="profile-preview">
                        <div style="flex-grow: 1;">
                            <label for="profile_photo" class="form-label-custom mb-1">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" class="form-control-custom" accept="image/*">
                            <span style="color: #64748b; font-size: 13px; margin-top: 6px; display: block;">Upload a clean square avatar image (Max: 2MB, JPG/PNG/WEBP).</span>
                        </div>
                    </div>

                    <!-- Personal Information Grid -->
                    <div class="form-grid-2">
                        <div class="form-group-custom">
                            <label for="name" class="form-label-custom">Full Name <span style="color: #ef4444;">*</span></label>
                            <input type="text" id="name" name="name" class="form-control-custom"
                                value="{{ old('name', $user->name) }}" required placeholder="Your full name">
                        </div>

                        <div class="form-group-custom">
                            <label for="email" class="form-label-custom">Email Address <span style="color: #ef4444;">*</span></label>
                            <input type="email" id="email" name="email" class="form-control-custom"
                                value="{{ old('email', $user->email) }}" required placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group-custom">
                            <label for="phone" class="form-label-custom">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control-custom"
                                value="{{ old('phone', $user->phone) }}" placeholder="e.g. 017XXXXXXXX">
                        </div>

                        <div class="form-group-custom">
                            <label for="address" class="form-label-custom">Shipping Address</label>
                            <input type="text" id="address" name="address" class="form-control-custom"
                                value="{{ old('address', $user->address) }}" placeholder="House, Road, City address">
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="section-separator">
                        <h4 class="section-separator-title">
                            <i class="fa-solid fa-shield-halved" style="color: #f59e0b;"></i> Security & Password
                        </h4>
                        <span style="color: #64748b; font-size: 13px; display: block; margin-bottom: 20px;">
                            Leave the password fields empty if you do not want to update your current login password.
                        </span>

                        <div class="form-group-custom">
                            <label for="current_password" class="form-label-custom">Current Password</label>
                            <div class="password-field-wrapper">
                                <input type="password" id="current_password" name="current_password" class="form-control-custom" placeholder="••••••••">
                                <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePassword('current_password', this)"></i>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-group-custom">
                                <label for="password" class="form-label-custom">New Password</label>
                                <div class="password-field-wrapper">
                                    <input type="password" id="password" name="password" class="form-control-custom" placeholder="Minimum 6 characters">
                                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePassword('password', this)"></i>
                                </div>
                            </div>

                            <div class="form-group-custom">
                                <label for="password_confirmation" class="form-label-custom">Confirm New Password</label>
                                <div class="password-field-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" placeholder="Repeat new password">
                                    <i class="fa-solid fa-eye password-toggle-icon" onclick="togglePassword('password_confirmation', this)"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Bar -->
                    <div class="form-actions-bar">
                        <a href="{{ route('account.show') }}" class="btn-cancel-custom">
                            Cancel
                        </a>
                        <button type="submit" class="btn-save-custom">
                            <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (!input) return;
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

        const profilePhotoInput = document.getElementById('profile_photo');
        const profilePreview = document.getElementById('profile-preview');
        if (profilePhotoInput && profilePreview) {
            profilePhotoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;
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
