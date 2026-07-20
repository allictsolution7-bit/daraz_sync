@extends('layouts.master')

@section('title', 'Edit Comment')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .comment-edit-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Card styling */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(217, 119, 6, 0.08);
            border-color: rgba(217, 119, 6, 0.2);
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        .premium-card-body {
            padding: 2rem;
        }

        /* Back Action Button in Header */
        .btn-premium-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            text-decoration: none !important;
            cursor: pointer;
            z-index: 2;
        }

        .btn-premium-back:hover {
            transform: translateY(-2px);
            background: #ffffff !important;
            color: #d97706 !important;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
        }

        /* Form Group Elements */
        .form-group label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-control-premium {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #0f172a;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            width: 100%;
        }

        .form-control-premium:focus {
            background-color: #ffffff;
            border-color: #d97706;
            box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.12);
            outline: none;
        }

        .form-control-premium.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.825rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Buttons styling */
        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(217, 119, 6, 0.4);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            box-shadow: 0 10px 24px -6px rgba(217, 119, 6, 0.5);
            transform: translateY(-1px);
        }

        .btn-premium-secondary {
            background: #f1f5f9;
            color: #475569 !important;
        }

        .btn-premium-secondary:hover {
            background: #e2e8f0;
            color: #1e293b !important;
            transform: translateY(-1px);
        }

        /* Comment Information panel styling */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
        }

        .info-card-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-group {
            margin-bottom: 1.25rem;
        }

        .info-group:last-child {
            margin-bottom: 0;
        }

        .info-group label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
            display: block;
        }

        .info-val {
            color: #1e293b;
            font-weight: 600;
            font-size: 0.925rem;
            word-break: break-all;
        }

        .category-badge {
            background-color: rgba(217, 119, 6, 0.06);
            color: #b45309;
            border: 1px solid rgba(217, 119, 6, 0.12);
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none !important;
        }

        .category-badge:hover {
            background-color: rgba(217, 119, 6, 0.1);
        }

        /* Session alert */
        .alert-toast {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid comment-edit-wrapper">
        <div class="premium-card">
            <!-- Premium Gradient Header -->
            <div class="gradient-header">
                <div>
                    <h1 class="gradient-header-title">Edit Blog Comment</h1>
                    <p class="gradient-header-subtitle">Update response content, status flag, and review comment references.</p>
                </div>
                <a href="{{ route('admin.comments.index') }}" class="btn-premium-back">
                    <i class="fas fa-arrow-left"></i> Back to Comments
                </a>
            </div>

            <div class="premium-card-body">
                <!-- Session Message Alerts -->
                @if (session('success'))
                    <div class="alert alert-toast alert-success d-flex align-items-center gap-3 fade show" role="alert">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Form Inputs -->
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <!-- Content -->
                            <div class="form-group mb-4">
                                <label for="content">Comment Content</label>
                                <textarea id="content" name="content" class="form-control-premium @error('content') is-invalid @enderror" 
                                    rows="8" required placeholder="Type comment text here...">{{ old('content', $comment->content) }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-4">
                                <label for="status">Status Flag</label>
                                <select id="status" name="status" class="form-control-premium @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $comment->status) === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ old('status', $comment->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="spam" {{ old('status', $comment->status) === 'spam' ? 'selected' : '' }}>Spam</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Sidebar Metadata Info -->
                        <div class="col-lg-4">
                            <div class="info-card">
                                <h5 class="info-card-title">
                                    <i class="fas fa-info-circle text-warning"></i> Comment Information
                                </h5>
                                
                                <div class="info-group">
                                    <label><i class="fas fa-user-circle mr-1"></i> Author Identity</label>
                                    <div class="info-val">
                                        @if($comment->user)
                                            <strong>{{ $comment->user->name }}</strong><br>
                                            <small class="text-muted">{{ $comment->user->email }}</small>
                                        @else
                                            @php
                                                $lines = explode("\n", $comment->content);
                                                $guestInfo = $lines[0] ?? '';
                                                $guestName = str_replace('Guest: ', '', explode(' (', $guestInfo)[0] ?? 'Guest');
                                                $guestEmail = trim(explode('(', explode(')', $guestInfo)[0] ?? '') ?? '');
                                            @endphp
                                            <strong>{{ $guestName }}</strong><br>
                                            <small class="text-muted">{{ $guestEmail }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label><i class="fas fa-link mr-1"></i> Reference Post</label>
                                    <div class="info-val mt-1">
                                        @if($comment->post)
                                            <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" class="category-badge">
                                                <i class="fas fa-external-link-alt"></i> {{ Str::limit($comment->post->title, 40) }}
                                            </a>
                                        @else
                                            <span class="text-muted small">Post deleted</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label><i class="far fa-calendar-alt mr-1"></i> Created Date</label>
                                    <div class="info-val text-slate-600">
                                        {{ $comment->created_at->format('M d, Y H:i:s') }}
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label><i class="fas fa-history mr-1"></i> Last Updated</label>
                                    <div class="info-val text-slate-600">
                                        {{ $comment->updated_at->format('M d, Y H:i:s') }}
                                    </div>
                                </div>

                                @if($comment->parent)
                                    <div class="info-group">
                                        <label><i class="fas fa-reply mr-1"></i> Reply To</label>
                                        <div class="info-val" style="font-weight: 500; font-size: 0.85rem; color: #475569; background: #ffffff; padding: 0.75rem; border-radius: 10px; border: 1px solid #e2e8f0; margin-top: 0.35rem;">
                                            {{ Str::limit($comment->parent->content, 100) }}
                                        </div>
                                    </div>
                                @endif

                                @if($comment->replies->count() > 0)
                                    <div class="info-group">
                                        <label><i class="fas fa-comments mr-1"></i> Replies Count</label>
                                        <div class="info-val mt-1">
                                            <span class="badge bg-warning text-dark px-3 py-2" style="font-weight: 700; border-radius: 8px;">
                                                {{ $comment->replies->count() }} replies
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action buttons -->
                    <div class="form-group pt-4 border-top mt-4 d-flex gap-2">
                        <button type="submit" class="btn-premium btn-premium-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.comments.index') }}" class="btn-premium btn-premium-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
