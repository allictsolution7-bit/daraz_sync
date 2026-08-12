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
            border-radius: 12px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 1rem;
            padding: 10px 15px;
        }

        .col-lg-8 .premium-card {
            height: calc(114vh - 70px);
            display: flex;
            flex-direction: column;
        }

        .premium-card.card-status-open {
            border-top: 4px solid #3b82f6;
        }

        .premium-card.card-status-pending {
            border-top: 4px solid #f59e0b;
        }

        .premium-card.card-status-resolved {
            border-top: 4px solid #10b981;
        }

        .premium-card.card-status-closed {
            border-top: 4px solid #64748b;
        }

        /* Attachment Styles */
        .attachment-container {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            border-top: 1px dashed #f1f5f9;
            padding-top: 8px;
        }

        .attachment-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .attachment-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .attachment-image-preview {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .attachment-image-preview:hover {
            transform: scale(1.05);
        }

        /* Custom Chat Upload Styles */
        .chat-input-wrapper {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 24px;
            background: #fff;
            padding: 2px 16px;
            transition: all 0.2s ease-in-out;
        }

        .chat-input-wrapper:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .file-list-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            width: 100%;
        }

        .file-preview-item {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .file-preview-item:hover {
            background-color: #e2e8f0;
            border-color: #94a3b8;
        }

        .file-preview-name {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: #475569;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
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
            gap: 12px;
            margin-top: 15px;
            margin-bottom: 15px;
            flex: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        .message-bubble {
            display: flex;
            gap: 10px;
            padding: 10px 14px;
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
            width: 32px;
            height: 32px;
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
            font-size: 13.5px;
            color: #334155;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        /* Reply Box Styles */
        .reply-box {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid rgba(226, 232, 240, 1);
            border-radius: 8px;
            font-size: 13px;
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
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
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

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4" style="background-color: #fee2e2; color: #b91c1c;">
            <div class="d-flex align-items-start">
                <i class="fas fa-circle-xmark me-2 mt-1"></i>
                <ul class="mb-0 ps-3" style="font-size: 13.5px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Ticket Details and Conversation Thread -->
        <div class="col-lg-8 col-md-12">
            <div class="premium-card card-status-{{ $ticket->status }}">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 6px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">{{ $ticket->subject }}</h2>
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
                                @if(!empty(trim($reply->message)))
                                    <div class="message-content">{{ $reply->message }}</div>
                                @endif
                                @if(!empty($reply->attachments))
                                    <div class="attachment-container">
                                        @foreach($reply->attachments as $path)
                                            @php
                                                $cleanPath = str_replace('storage/support_attachments/', 'support_attachments/', $path);
                                                $isImage = in_array(strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                                                $fileName = basename($cleanPath);
                                            @endphp
                                            @if($isImage)
                                                <a href="{{ asset($cleanPath) }}" target="_blank" title="View full image">
                                                    <img src="{{ asset($cleanPath) }}" class="attachment-image-preview" alt="Attachment">
                                                </a>
                                            @else
                                                <a href="{{ asset($cleanPath) }}" target="_blank" class="attachment-item" title="Download File">
                                                    <i class="fas fa-file-arrow-down"></i>
                                                    <span>{{ $fileName }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <div class="reply-box" style="border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 10px;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px;">Send a Reply</h4>
                    <form action="{{ route('admin.support-tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                        @csrf
                        <div style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            <div class="chat-input-wrapper">
                                <textarea name="message" placeholder="Write your message here..." style="flex: 1; border: none; outline: none; background: transparent; padding: 10px 0; resize: none; font-size: 14px; height: 38px; line-height: 20px; font-family: inherit;"></textarea>
                                
                                <button type="button" onclick="document.getElementById('attachments').click()" style="background: none; border: none; color: #64748b; cursor: pointer; padding: 6px; margin-left: 10px; transition: color 0.2s; display: flex; align-items: center; justify-content: center;" title="Attach Files (Max 5MB each)">
                                    <i class="fas fa-paperclip" style="font-size: 18px;"></i>
                                </button>
                                <input type="file" id="attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" style="display: none;">
                            </div>
                            <button type="submit" style="height: 42px; width: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; cursor: pointer; color: white; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2); flex-shrink: 0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="fas fa-paper-plane" style="font-size: 15px; margin: 0;"></i>
                            </button>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; margin-top: 5px;">
                            <label class="fw-bold text-slate-700" style="margin: 0;">Set Status to:</label>
                            <select name="status" class="quick-action-select" required style="padding: 4px 8px; font-size: 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff;">
                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending / Awaiting Customer</option>
                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </form>
                    <div id="file-list" class="file-list-preview" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 8px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Quick Info and Settings Side Pane -->
        <div class="col-lg-4 col-md-12">
            <div class="premium-card card-status-{{ $ticket->status }}">
                <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 15px;">Ticket Settings</h3>
                
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

                    <button type="submit" class="btn w-100 btn-primary shadow-sm py-2 fw-bold" style="background-color: #4f46e5; border: none; border-radius: 8px; font-size: 13px;">
                        Update Settings
                    </button>
                </form>
            </div>

            <div class="premium-card">
                <h3 style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 12px;">Customer Profile</h3>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const thread = document.querySelector('.message-thread');
    if (thread) {
        thread.scrollTop = thread.scrollHeight;
    }

    const fileInput = document.getElementById('attachments');
    const fileList = document.getElementById('file-list');

    if (fileInput && fileList) {
        // Handle file selection
        fileInput.addEventListener('change', updateFileList);

        function updateFileList() {
            fileList.innerHTML = '';
            const files = fileInput.files;
            
            if (files.length > 3) {
                alert("You can only upload a maximum of 3 files at a time.");
                fileInput.value = ''; // Reset input
                return;
            }
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const item = document.createElement('div');
                    item.className = 'file-preview-item';
                    
                    const nameContainer = document.createElement('div');
                    nameContainer.className = 'file-preview-name';
                    
                    const isImage = file.type.startsWith('image/');
                    const iconClass = isImage ? 'far fa-image' : 'far fa-file-alt';
                    nameContainer.innerHTML = `<i class="${iconClass}" style="color: #6366f1;"></i> <span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>`;
                    
                    item.appendChild(nameContainer);
                    fileList.appendChild(item);
                }
            }
        }
    }
});
</script>
@endsection
