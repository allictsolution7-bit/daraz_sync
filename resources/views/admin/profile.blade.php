@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        #profile-dashboard {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.15);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
        }

        .breadcrumb-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 0;
            padding: 0;
            list-style: none;
        }

        .breadcrumb-custom a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-custom a:hover {
            color: #6366f1;
        }

        .breadcrumb-separator {
            color: #64748b;
        }

        .breadcrumb-active {
            color: #94a3b8;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
        }

        .profile-sidebar-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 30px 24px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .profile-sidebar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 90px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            z-index: 0;
        }

        .avatar-container {
            position: relative;
            z-index: 1;
            margin-top: 15px;
            margin-bottom: 18px;
            display: inline-block;
        }

        .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-sidebar-card:hover .avatar-img {
            transform: scale(1.05);
        }

        .profile-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.025em;
        }

        .profile-role {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 15px;
            border: 1px solid #dbeafe;
        }

        .profile-email {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 24px;
            word-break: break-all;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .social-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
        }

        /* Profile Tabs & Form */
        .content-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .nav-tabs-custom {
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 30px;
            gap: 24px;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            background: none;
            padding: 0 0 16px 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #64748b;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #0f172a;
        }

        .nav-tabs-custom .nav-link.active {
            color: #4f46e5;
        }

        .nav-tabs-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #4f46e5;
            border-radius: 2px;
        }

        .info-group {
            display: flex;
            align-items: center;
            padding: 16px;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            margin-bottom: 16px;
            background: #f8fafc;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(79, 70, 229, 0.08);
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 12px 16px;
            font-size: 0.95rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-update {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-update:hover {
            box-shadow: 0 6px 14px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4" id="profile-dashboard">
        @php
            $adminProfileImage = Auth::user()->profile_photo_path
                ? \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path)
                : asset('assets/img/man.png');
        @endphp

        <!-- Breadcrumb & Header Card -->
        <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title">Admin Account Profile</h1>
            </div>
            <ul class="breadcrumb-custom">
                <li><a href="{{ route('admin') }}"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="breadcrumb-separator"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i></li>
                <li class="breadcrumb-active">Profile</li>
            </ul>
        </div>

        <div class="row">
            <!-- Left Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="profile-sidebar-card">
                    <div class="avatar-container">
                        <img src="{{ $adminProfileImage }}" alt="Profile Photo" class="avatar-img">
                    </div>
                    <h2 class="profile-name">{{ Auth::user()->name }}</h2>
                    <span class="profile-role">Super Admin</span>
                    <p class="profile-email">{{ Auth::user()->email }}</p>
                    
                    <div class="social-links">
                        <a href="#" class="social-btn" id="btn_social_facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn" id="btn_social_instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-btn" id="btn_social_twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn" id="btn_social_linkedin"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- Right Profile Content Tabs -->
            <div class="col-lg-8 mb-4">
                <div class="content-card">
                    <nav class="nav nav-tabs nav-tabs-custom" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                            aria-selected="true">Profile Details</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                            aria-selected="false">Profile Settings</button>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <!-- Profile Details Tab -->
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                            tabindex="0">
                            
                            <div class="info-group">
                                <div class="info-icon">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <div>
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ Auth::user()->name }}</div>
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ Auth::user()->email }}</div>
                                </div>
                            </div>

                            <div class="info-group">
                                <div class="info-icon">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div>
                                    <div class="info-label">Account Role</div>
                                    <div class="info-value">Super Admin</div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Settings Tab -->
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                            tabindex="0">
                            <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data" id="profileSettingsForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="profile_photo" class="form-label">Upload Profile Photo</label>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                                    <small class="form-text text-muted">Supports JPG, PNG, GIF up to 2MB.</small>
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                </div>
                                <div class="mb-4">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Required if changing password">
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                                </div>

                                <hr class="my-4">
                                <h6 class="mb-3 font-weight-bold" style="color: #0f172a;"><i class="fa-solid fa-share-nodes text-primary me-1"></i> Social Media Links</h6>

                                <div class="mb-3">
                                    <label for="social_facebook" class="form-label">Facebook Profile Link</label>
                                    <input type="url" class="form-control" id="social_facebook" placeholder="https://facebook.com/username">
                                </div>
                                <div class="mb-3">
                                    <label for="social_instagram" class="form-label">Instagram Profile Link</label>
                                    <input type="url" class="form-control" id="social_instagram" placeholder="https://instagram.com/username">
                                </div>
                                <div class="mb-3">
                                    <label for="social_twitter" class="form-label">Twitter / X Profile Link</label>
                                    <input type="url" class="form-control" id="social_twitter" placeholder="https://twitter.com/username">
                                </div>
                                <div class="mb-4">
                                    <label for="social_linkedin" class="form-label">LinkedIn Profile Link</label>
                                    <input type="url" class="form-control" id="social_linkedin" placeholder="https://linkedin.com/in/username">
                                </div>
                                
                                <button type="submit" class="btn btn-update px-4 py-2">
                                    <i class="fa-regular fa-floppy-disk me-1"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load social links from localStorage
            const socials = ['facebook', 'instagram', 'twitter', 'linkedin'];
            socials.forEach(platform => {
                const url = localStorage.getItem('social_' + platform);
                if (url) {
                    // Fill inputs
                    const input = document.getElementById('social_' + platform);
                    if (input) input.value = url;
                    
                    // Update profile card links
                    const linkBtn = document.getElementById('btn_social_' + platform);
                    if (linkBtn) {
                        linkBtn.href = url;
                        linkBtn.target = '_blank';
                    }
                }
            });

            // Handle Form Submit (save socials to localStorage)
            const form = document.getElementById('profileSettingsForm');
            if (form) {
                form.addEventListener('submit', function() {
                    socials.forEach(platform => {
                        const input = document.getElementById('social_' + platform);
                        if (input) {
                            localStorage.setItem('social_' + platform, input.value.trim());
                        }
                    });
                });
            }
        });
    </script>
@endsection
