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
            transition: border-top 0.3s ease;
        }

        .support-card.card-status-open {
            border-top: 4px solid #3b82f6;
        }

        .support-card.card-status-pending {
            border-top: 4px solid #f59e0b;
        }

        .support-card.card-status-resolved {
            border-top: 4px solid #10b981;
        }

        .support-card.card-status-closed {
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
            <div class="support-card card-status-{{ $ticket->status }}">
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

                    @if ($errors->any())
                        <div class="alert alert-danger" style="padding: 15px; border-radius: 10px; background-color: #fee2e2; color: #b91c1c; margin-bottom: 20px; border: none; font-weight: 500;">
                            <ul style="margin: 0; padding-left: 20px; font-size: 13.5px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                                                        <i class="fa-solid fa-file-arrow-down"></i>
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

                    <!-- Reply Box -->
                    <div class="reply-box" style="border-top: 1px solid #e2e8f0; padding-top: 20px; margin-top: 20px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 15px;">Post a Reply</h4>
                        
                        @if ($ticket->status === 'resolved' || $ticket->status === 'closed')
                            <div style="font-size: 13px; color: #92400e; background-color: #fffbeb; padding: 10px 15px; border-radius: 8px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; border: 1px solid #fef3c7;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>This ticket is currently closed/resolved. Sending a reply will automatically reopen it.</span>
                            </div>
                        @endif

                        <form action="{{ route('support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 12px; align-items: center; width: 100%;">
                            @csrf
                            <div class="chat-input-wrapper">
                                <textarea name="message" placeholder="Type your message here..." style="flex: 1; border: none; outline: none; background: transparent; padding: 10px 0; resize: none; font-size: 14px; height: 38px; line-height: 20px; font-family: inherit;"></textarea>
                                
                                <button type="button" onclick="document.getElementById('reply_attachments').click()" style="background: none; border: none; color: #64748b; cursor: pointer; padding: 6px; margin-left: 10px; transition: color 0.2s; display: flex; align-items: center; justify-content: center;" title="Attach Files (Max 5MB each)">
                                    <i class="fa-solid fa-paperclip" style="font-size: 18px;"></i>
                                </button>
                                <input type="file" id="reply_attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" style="display: none;">
                            </div>
                            <button type="submit" style="height: 42px; width: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; cursor: pointer; color: white; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2); flex-shrink: 0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="fa-solid fa-paper-plane" style="font-size: 15px; margin: 0;"></i>
                            </button>
                        </form>
                        <div id="file-list" class="file-list-preview" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 8px; width: 100%;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('reply_attachments');
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
                    const iconClass = isImage ? 'fa-regular fa-image' : 'fa-regular fa-file-lines';
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
