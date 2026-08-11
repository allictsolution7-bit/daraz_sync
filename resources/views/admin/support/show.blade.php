@extends('layouts.master')

@section('title', 'Ticket Details - #' . $ticket->id)

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .support-dashboard-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2.5rem;
            padding: 2.5rem;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-open {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-resolved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-closed {
            background-color: #f1f5f9;
            color: #475569;
        }

        .badge-priority {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-low {
            background-color: #f1f5f9;
            color: #475569;
        }

        .priority-medium {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Message Thread Styles */
        .message-thread {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 30px;
        }

        .message-bubble {
            display: flex;
            gap: 15px;
            padding: 20px;
            border-radius: 12px;
            max-width: 85%;
        }

        .message-bubble.customer {
            align-self: flex-start;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .message-bubble.admin {
            align-self: flex-end;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            flex-direction: row-reverse;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .message-details {
            flex: 1;
        }

        .message-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 12px;
            color: #64748b;
        }

        .message-sender {
            font-weight: 700;
            color: #0f172a;
        }

        .message-content {
            font-size: 14.5px;
            color: #334155;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Reply Box Styles */
        .reply-box {
            border-top: 1px solid #e2e8f0;
            padding-top: 25px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 10px;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .btn-reply {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-reply:hover {
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            transform: translateY(-1px);
        }

        .quick-action-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 600;
            outline: none;
        }
    </style>
@endsection

@section('content')
<div class="support-dashboard-wrapper container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.support-tickets.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background-color: #d1fae5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-circle-check me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Ticket Details and Conversation Thread -->
        <div class="col-lg-8 col-md-12">
            <div class="premium-card">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 10px;">
                    <h2 style="font-size: 22px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">{{ $ticket->subject }}</h2>
                    <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                        Category: <strong style="color: #475569;">{{ $ticket->category }}</strong> &bull; 
                        Customer: <strong style="color: #475569;">{{ $ticket->user->name }} ({{ $ticket->user->email }})</strong> &bull;
                        Created: <strong>{{ $ticket->created_at->format('M d, Y h:i A') }}</strong>
                    </span>
                </div>

                <!-- Conversation Thread -->
                <div class="message-thread">
                    @foreach ($ticket->replies as $reply)
                        @php
                            $isAdminReply = $reply->user->role === 'admin' || 
                                            $reply->user->role === 'super_admin' || 
                                            $reply->user->role === 'super admin' || 
                                            $reply->user->role === 'manager' ||
                                            $reply->user->id == 1;
                            $profilePhoto = !empty($reply->user->profile_photo_path)
                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($reply->user->profile_photo_path)
                                : asset('clientside/images/profile.png');
                        @endphp

                        <div class="message-bubble {{ $isAdminReply ? 'admin' : 'customer' }}">
                            <img src="{{ $profilePhoto }}" alt="{{ $reply->user->name }}" class="avatar" onerror="this.src='/clientside/images/profile.png'">
                            <div class="message-details">
                                <div class="message-meta">
                                    <span class="message-sender">
                                        {{ $reply->user->name }}
                                        @if ($isAdminReply)
                                            <span style="background-color: #ef4444; color: white; font-size: 9px; padding: 2px 6px; border-radius: 10px; margin-left: 5px; font-weight: 700; vertical-align: middle; text-transform: uppercase;">Staff</span>
                                        @endif
                                    </span>
                                    <span>{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="message-content">{{ $reply->message }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <div class="reply-box">
                    <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 15px;">Send a Reply</h4>
                    <form action="{{ route('admin.support-tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required style="resize: vertical; font-family: inherit; font-size: 14px;"></textarea>
                        </div>
                        
                        <div class="row align-items-center g-3">
                            <div class="col-auto">
                                <label class="fw-bold text-slate-700" style="font-size: 14px;">Set Status to:</label>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="quick-action-select" required>
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending / Awaiting Customer</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div class="col-auto ms-auto">
                                <button type="submit" class="btn-reply">
                                    <i class="fas fa-paper-plane me-1"></i> Send Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Info and Settings Side Pane -->
        <div class="col-lg-4 col-md-12">
            <div class="premium-card">
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 20px;">Ticket Settings</h3>
                
                <div class="mb-4">
                    <div style="font-size: 13px; color: #64748b; margin-bottom: 6px; font-weight: 600;">Current Status</div>
                    <span class="badge-status status-{{ $ticket->status }}">{{ $ticket->status }}</span>
                </div>

                <div class="mb-4">
                    <div style="font-size: 13px; color: #64748b; margin-bottom: 6px; font-weight: 600;">Current Priority</div>
                    <span class="badge-priority priority-{{ $ticket->priority }}">{{ $ticket->priority }}</span>
                </div>

                <hr class="my-4">

                <form action="{{ route('admin.support-tickets.update-status', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="fw-bold text-slate-700 mb-1" style="font-size: 13px;">Change Ticket Status</label>
                        <select name="status" class="form-control" style="font-size: 14px;">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending / Awaiting Customer</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="fw-bold text-slate-700 mb-1" style="font-size: 13px;">Change Priority</label>
                        <select name="priority" class="form-control" style="font-size: 14px;">
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <button type="submit" class="btn w-100 btn-primary shadow-sm py-2 fw-bold" style="background-color: #4f46e5; border: none; border-radius: 10px;">
                        Update Settings
                    </button>
                </form>
            </div>

            <div class="premium-card">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 15px;">Customer Profile</h3>
                <div style="font-size: 14px;">
                    <div class="mb-2"><strong>Name:</strong> {{ $ticket->user->name }}</div>
                    <div class="mb-2"><strong>Email:</strong> {{ $ticket->user->email }}</div>
                    <div class="mb-2"><strong>Phone:</strong> {{ $ticket->user->phone ?? 'N/A' }}</div>
                    <div class="mb-0"><strong>Joined:</strong> {{ $ticket->user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
