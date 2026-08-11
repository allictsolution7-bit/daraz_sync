@extends('frontend.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('new/user.styles.css') }}">
    <style>
        .support-container {
            margin: 30px auto;
            padding: 0 15px;
            max-width: 1200px;
        }

        .support-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        .support-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .support-header {
            padding: 24px 30px;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .support-header h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .support-body {
            padding: 30px;
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
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-open {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        .status-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .status-resolved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }

        .status-closed {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .badge-priority {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-low {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .priority-medium {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .priority-high {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
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
            align-self: flex-end;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            flex-direction: row-reverse;
        }

        .message-bubble.admin {
            align-self: flex-start;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
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

        @media (max-width: 768px) {
            .support-layout {
                grid-template-columns: 1fr;
            }
            .message-bubble {
                max-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <div class="base-container support-container">
        <div class="support-layout">
            <!-- Sidebar Menu -->
            @include('frontend.user.partials.sidebar')

            <!-- Main Content Card -->
            <div class="support-card">
                <div class="support-header">
                    <h4>
                        <a href="{{ route('support.index') }}" class="btn-back">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                        <span>Ticket #{{ $ticket->id }}</span>
                    </h4>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span class="badge-priority priority-{{ $ticket->priority }}">{{ $ticket->priority }}</span>
                        <span class="badge-status status-{{ $ticket->status }}">{{ $ticket->status }}</span>
                    </div>
                </div>

                <div class="support-body">
                    @if (session('success'))
                        <div class="alert alert-success" style="padding: 15px; border-radius: 10px; background-color: #d1fae5; color: #065f46; margin-bottom: 20px; border: none; font-weight: 500;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 10px;">
                        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">{{ $ticket->subject }}</h2>
                        <span style="font-size: 13px; color: #64748b; font-weight: 500;">
                            Category: <strong style="color: #475569;">{{ $ticket->category }}</strong> &bull; 
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

                    <!-- Reply Box -->
                    <div class="reply-box">
                        <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 15px;">Post a Reply</h4>
                        <form action="{{ route('support.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="form-group" style="margin-bottom: 15px;">
                                <textarea name="message" class="form-control" rows="4" placeholder="Type your message here..." required style="resize: vertical; font-family: inherit; font-size: 14px;"></textarea>
                            </div>
                            
                            @if ($ticket->status === 'resolved' || $ticket->status === 'closed')
                                <div style="font-size: 13px; color: #92400e; background-color: #fffbeb; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; border: 1px solid #fef3c7;">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>This ticket is currently closed/resolved. Sending a reply will automatically reopen the ticket.</span>
                                </div>
                            @endif

                            <button type="submit" class="btn-reply">
                                <i class="fa-solid fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
