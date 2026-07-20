@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Modern Premium Portal Design System */
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-slate: #1e293b;
            --text-main: #334155;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(226, 232, 240, 0.8);
            --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        .workspace-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            padding: 30px;
            margin-bottom: 40px;
        }

        .info-pane-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 16px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
        }

        .pane-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark-slate);
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row strong {
            color: var(--text-muted);
            font-weight: 500;
        }

        .info-row span {
            color: var(--dark-slate);
            font-weight: 600;
        }

        .message-body-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            font-size: 15px;
            line-height: 1.6;
            color: var(--text-main);
            white-space: pre-line;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 pt-3">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}" class="text-decoration-none text-muted">Inbox Messages</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">Message Details</li>
        </ol>
    </nav>

    <div class="workspace-card">
        <!-- Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="mb-1 font-weight-bold" style="color: var(--dark-slate);">Message Conversation</h3>
                <p class="text-muted mb-0">From {{ $contact->name }} &bull; Received {{ $contact->created_at->format('M d, Y H:i') }}</p>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back to Inbox
            </a>
        </div>

        <!-- Info Grid -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="info-pane-card">
                    <div class="pane-title text-primary">
                        <i class="fas fa-user-circle"></i> Sender Profile
                    </div>
                    <div class="info-row">
                        <strong>Sender Name:</strong>
                        <span>{{ $contact->name }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Email Address:</strong>
                        <span><a href="mailto:{{ $contact->email }}" class="text-decoration-none text-primary">{{ $contact->email }}</a></span>
                    </div>
                    <div class="info-row">
                        <strong>Phone Number:</strong>
                        <span>{{ $contact->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Date/Time:</strong>
                        <span>{{ $contact->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-pane-card">
                    <div class="pane-title text-info">
                        <i class="fas fa-network-wired"></i> Delivery & Metadata
                    </div>
                    <div class="info-row">
                        <strong>IP Address:</strong>
                        <span>{{ $contact->ip_address }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Browser/Client:</strong>
                        <span>{{ Str::limit($contact->browser, 35) }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Device/OS:</strong>
                        <span>{{ $contact->device }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Estimated Origin:</strong>
                        <span>{{ $contact->city ?? 'Unknown' }}, {{ $contact->country ?? 'Unknown' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Details Card -->
        <div class="info-pane-card p-4">
            <div class="pane-title border-0 pb-0 mb-3">
                <h5 class="mb-0 font-weight-bold" style="color: var(--dark-slate);">
                    <i class="fas fa-heading text-warning me-2"></i> Subject: <span class="text-muted">{{ $contact->subject }}</span>
                </h5>
            </div>
            
            <div class="pane-title pb-2 mb-3">
                <i class="fas fa-comment-alt text-primary"></i> Message Body
            </div>
            <div class="message-body-box">
                {{ $contact->message }}
            </div>
        </div>
    </div>
</div>
@endsection