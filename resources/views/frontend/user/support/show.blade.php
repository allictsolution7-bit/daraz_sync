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
            gap: 15px;
        }

        .support-card {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: border-top 0.3s ease;
            height: calc(114vh - 70px);
            display: flex;
            flex-direction: column;
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
            padding: 12px 18px;
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
            padding: 10px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
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
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
            flex: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        .message-bubble {
            display: flex;
            gap: 8px;
            padding: 8px 12px;
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

                    <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 6px;">
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

                            <div id="reply-bubble-{{ $reply->id }}" class="message-bubble {{ $isAdminReply ? 'admin' : 'customer' }}">
                                <img src="{{ $profilePhoto }}" alt="{{ $reply->user->name }}" class="avatar" onerror="this.src='/clientside/images/profile.png'">
                                <div class="message-details">
                                    <div class="message-meta">
                                        <span class="message-sender">
                                            {{ $reply->user->name }}
                                            @if ($isAdminReply)
                                                <span style="background-color: #ef4444; color: white; font-size: 9px; padding: 2px 6px; border-radius: 10px; margin-left: 5px; font-weight: 700; vertical-align: middle; text-transform: uppercase;">Staff</span>
                                            @endif
                                        </span>
                                        <span>
                                            {{ $reply->created_at->diffForHumans() }}
                                            @if(!$isAdminReply && $reply->user_id == Auth::id() && $reply->created_at->diffInMinutes(now()) < 10)
                                                <button onclick="openEditModal({{ $reply->id }}, @js($reply->message), @js($reply->attachments ?? []))" style="background: none; border: none; color: #6366f1; font-size: 11px; cursor: pointer; padding: 0; margin-left: 10px; font-weight: 600;">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </button>
                                            @endif
                                        </span>
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
                    <div class="reply-box" style="border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 10px;">
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
    

    <!-- Edit Reply Modal -->
    <div id="editReplyModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
        <div class="modal-content" style="background: #fff; padding: 24px; border-radius: 16px; width: 90%; max-width: 500px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0;">Edit Reply</h3>
                <span style="font-size: 24px; cursor: pointer; color: #64748b; line-height: 1;" onclick="closeEditModal()">&times;</span>
            </div>
            
            <form id="editReplyForm" onsubmit="submitEditReply(event)">
                @csrf
                <input type="hidden" id="edit_reply_id">
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Message</label>
                    <textarea id="edit_message" name="message" class="form-control" rows="4" style="resize: vertical; font-family: inherit; font-size: 14px; width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px;"></textarea>
                </div>

                <!-- Old Attachments Section -->
                <div id="edit_old_attachments_section" style="margin-bottom: 15px; display: none;">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 6px;">Current Attachments (Click to remove)</label>
                    <div id="edit_old_attachments_list" style="display: flex; flex-wrap: wrap; gap: 8px;"></div>
                </div>

                <!-- New Attachments Section -->
                <div class="form-group mb-3" style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #475569;">
                            <i class="fa-solid fa-paperclip" style="color: #6366f1;"></i>
                            <strong>Add Files (Max 3 total)</strong>
                        </div>
                        <button type="button" class="btn-sm" onclick="document.getElementById('edit_reply_attachments').click()" style="padding: 4px 10px; font-size: 12px; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; color: #334155; cursor: pointer; font-weight: 600;">
                            Select Files
                        </button>
                    </div>
                    <input type="file" id="edit_reply_attachments" name="attachments[]" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" style="display: none;">
                    <div id="edit-file-list" class="file-list-preview" style="margin-top: 8px; display: flex; flex-wrap: wrap; gap: 8px;"></div>
                </div>

                <div style="text-align: right;">
                    <button type="button" style="background: #f1f5f9; color: #475569; padding: 8px 16px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 13px; margin-right: 8px;" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" id="editSubmitBtn" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; padding: 8px 16px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 13px;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
let selectedFiles = [];
let editSelectedFiles = [];
let deletedAttachments = [];

document.addEventListener('DOMContentLoaded', function() {
    const thread = document.querySelector('.message-thread');
    if (thread) {
        thread.scrollTop = thread.scrollHeight;
    }

    const fileInput = document.getElementById('reply_attachments');
    const fileList = document.getElementById('file-list');
    const form = fileInput ? fileInput.closest('form') : null;

    if (fileInput && fileList) {
        fileInput.addEventListener('change', function() {
            const files = Array.from(fileInput.files);
            
            if (selectedFiles.length + files.length > 3) {
                alert("You can only upload a maximum of 3 files.");
                fileInput.value = '';
                return;
            }
            
            selectedFiles = selectedFiles.concat(files);
            updateFileList();
        });

        function updateFileList() {
            fileList.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'file-preview-item';
                
                const nameContainer = document.createElement('div');
                nameContainer.className = 'file-preview-name';
                
                const isImage = file.type.startsWith('image/');
                const iconClass = isImage ? 'fa-regular fa-image' : 'fa-regular fa-file-lines';
                nameContainer.innerHTML = `<i class="${iconClass}" style="color: #6366f1;"></i> <span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>`;
                
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.style.border = 'none';
                removeBtn.style.background = 'none';
                removeBtn.style.color = '#ef4444';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '0 0 0 8px';
                removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                removeBtn.addEventListener('click', function() {
                    selectedFiles.splice(index, 1);
                    updateFileList();
                });
                
                item.appendChild(nameContainer);
                item.appendChild(removeBtn);
                fileList.appendChild(item);
            });
        }
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = form.querySelector('textarea[name="message"]');
            const message = messageInput.value.trim();
            const filesToSend = [...selectedFiles];
            
            if (message === '' && filesToSend.length === 0) {
                return;
            }
            
            // 1. Optimistic UI: Append message bubble instantly!
            const tempReplyId = 'temp-' + Date.now();
            const tempReply = {
                id: tempReplyId,
                message: message,
                attachments: filesToSend.map(file => {
                    const isImage = file.type.startsWith('image/');
                    return {
                        url: URL.createObjectURL(file),
                        name: file.name,
                        is_image: isImage
                    };
                }),
                created_at_human: 'Just now',
                user: {
                    name: "{{ Auth::user()->name }}",
                    profile_photo: "{{ !empty(Auth::user()->profile_photo_path) ? \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path) : asset('clientside/images/profile.png') }}",
                    is_admin: false
                }
            };
            
            appendNewReply(tempReply);
            
            // Scroll to bottom
            const thread = document.querySelector('.message-thread');
            if (thread) {
                thread.scrollTop = thread.scrollHeight;
            }
            
            // 2. Clear inputs immediately for the next message
            messageInput.value = '';
            selectedFiles = [];
            updateFileList();
            
            // 3. Send in background
            const formData = new FormData();
            formData.append('_token', form.querySelector('input[name="_token"]').value);
            formData.append('message', message);
            filesToSend.forEach(file => {
                formData.append('attachments[]', file);
            });
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update the temp bubble with real edit triggers and IDs
                    const tempBubble = document.getElementById(`reply-bubble-${tempReplyId}`);
                    if (tempBubble) {
                        tempBubble.id = `reply-bubble-${data.reply.id}`;
                        // Add the Edit button if available
                        const metaContainer = tempBubble.querySelector('.message-meta span');
                        if (metaContainer) {
                            metaContainer.innerHTML = `${data.reply.created_at_human} 
                                <button onclick="openEditModal(${data.reply.id}, '${escapeHtml(data.reply.message)}', [])" style="background: none; border: none; color: #6366f1; font-size: 11px; cursor: pointer; padding: 0; margin-left: 10px; font-weight: 600;">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>`;
                        }
                    }
                } else {
                    console.error('Background send failed:', data.message);
                    alert('Failed to send reply: ' + data.message);
                }
            })
            .catch(err => {
                console.error('Background send error:', err);
            });
        });
    }

    // Handle Edit Form File Selection
    const editFileInput = document.getElementById('edit_reply_attachments');
    const editFileList = document.getElementById('edit-file-list');
    if (editFileInput && editFileList) {
        editFileInput.addEventListener('change', function() {
            const files = Array.from(editFileInput.files);
            const currentTotal = (document.querySelectorAll('.edit-old-item').length) + editSelectedFiles.length;
            
            if (currentTotal + files.length > 3) {
                alert("You can only upload a maximum of 3 files total.");
                editFileInput.value = '';
                return;
            }
            
            editSelectedFiles = editSelectedFiles.concat(files);
            updateEditFileList();
        });
    }
});

function updateEditFileList() {
    const editFileList = document.getElementById('edit-file-list');
    editFileList.innerHTML = '';
    editSelectedFiles.forEach((file, index) => {
        const item = document.createElement('div');
        item.className = 'file-preview-item';
        
        const nameContainer = document.createElement('div');
        nameContainer.className = 'file-preview-name';
        
        const isImage = file.type.startsWith('image/');
        const iconClass = isImage ? 'fa-regular fa-image' : 'fa-regular fa-file-lines';
        nameContainer.innerHTML = `<i class="${iconClass}" style="color: #6366f1;"></i> <span>${file.name}</span>`;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.style.border = 'none';
        removeBtn.style.background = 'none';
        removeBtn.style.color = '#ef4444';
        removeBtn.style.cursor = 'pointer';
        removeBtn.style.padding = '0 0 0 8px';
        removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        removeBtn.addEventListener('click', function() {
            editSelectedFiles.splice(index, 1);
            updateEditFileList();
        });
        
        item.appendChild(nameContainer);
        item.appendChild(removeBtn);
        editFileList.appendChild(item);
    });
}

function openEditModal(replyId, message, attachments) {
    document.getElementById('edit_reply_id').value = replyId;
    document.getElementById('edit_message').value = message;
    
    editSelectedFiles = [];
    deletedAttachments = [];
    document.getElementById('edit_reply_attachments').value = '';
    document.getElementById('edit-file-list').innerHTML = '';
    
    const oldSec = document.getElementById('edit_old_attachments_section');
    const oldList = document.getElementById('edit_old_attachments_list');
    
    oldList.innerHTML = '';
    if (attachments && attachments.length > 0) {
        oldSec.style.display = 'block';
        attachments.forEach(path => {
            const cleanPath = path.replace('storage/support_attachments/', 'support_attachments/');
            const fileName = cleanPath.split('/').pop();
            
            const item = document.createElement('div');
            item.className = 'file-preview-item edit-old-item';
            item.setAttribute('data-path', path);
            item.style.cursor = 'pointer';
            
            const isImage = /\.(jpeg|jpg|gif|png|webp)$/i.test(cleanPath);
            const iconClass = isImage ? 'fa-regular fa-image' : 'fa-regular fa-file-lines';
            item.innerHTML = `<i class="${iconClass}" style="color: #6366f1;"></i> <span>${fileName}</span> <i class="fa-solid fa-xmark" style="color:#ef4444; margin-left: 5px;"></i>`;
            
            item.addEventListener('click', function() {
                deletedAttachments.push(path);
                item.remove();
                if (oldList.children.length === 0) {
                    oldSec.style.display = 'none';
                }
            });
            
            oldList.appendChild(item);
        });
    } else {
        oldSec.style.display = 'none';
    }
    
    const modal = document.getElementById('editReplyModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editReplyModal').style.display = 'none';
}

function submitEditReply(e) {
    e.preventDefault();
    
    const replyId = document.getElementById('edit_reply_id').value;
    const message = document.getElementById('edit_message').value;
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('message', message);
    
    deletedAttachments.forEach(path => {
        formData.append('delete_attachments[]', path);
    });
    
    editSelectedFiles.forEach(file => {
        formData.append('attachments[]', file);
    });
    
    const submitBtn = document.getElementById('editSubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
    
    fetch(`/support/reply/${replyId}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(res => res.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Save Changes';
        
        if (data.success) {
            alert('Reply updated successfully!');
            location.reload(); // Reload to show edited messages cleanly
        } else {
            alert(data.message || 'An error occurred.');
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Save Changes';
        console.error(err);
        alert('Failed to update reply.');
    });
}

function appendNewReply(reply) {
    const thread = document.querySelector('.message-thread');
    const bubble = document.createElement('div');
    bubble.id = 'reply-bubble-' + reply.id;
    bubble.className = `message-bubble ${reply.user.is_admin ? 'admin' : 'customer'}`;
    
    let attachmentsHtml = '';
    if (reply.attachments && reply.attachments.length > 0) {
        attachmentsHtml = '<div class="attachment-container">';
        reply.attachments.forEach(att => {
            if (att.is_image) {
                attachmentsHtml += `
                    <a href="${att.url}" target="_blank" title="View full image">
                        <img src="${att.url}" class="attachment-image-preview" alt="Attachment">
                    </a>`;
            } else {
                attachmentsHtml += `
                    <a href="${att.url}" target="_blank" class="attachment-item" title="Download File">
                        <i class="fa-solid fa-file-arrow-down"></i>
                        <span>${att.name}</span>
                    </a>`;
            }
        });
        attachmentsHtml += '</div>';
    }
    
    let messageHtml = '';
    if (reply.message && reply.message.trim() !== '') {
        messageHtml = `<div class="message-content">${escapeHtml(reply.message)}</div>`;
    }

    const editBtnHtml = `
        <button onclick="openEditModal(${reply.id}, '${escapeHtml(reply.message)}', [])" style="background: none; border: none; color: #6366f1; font-size: 11px; cursor: pointer; padding: 0; margin-left: 10px; font-weight: 600;">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </button>
    `;
    
    bubble.innerHTML = `
        <img src="${reply.user.profile_photo}" alt="${reply.user.name}" class="avatar" onerror="this.src='/clientside/images/profile.png'">
        <div class="message-details">
            <div class="message-meta">
                <span class="message-sender">
                    ${reply.user.name}
                    ${reply.user.is_admin ? '<span style="background-color: #ef4444; color: white; font-size: 9px; padding: 2px 6px; border-radius: 10px; margin-left: 5px; font-weight: 700; vertical-align: middle; text-transform: uppercase;">Staff</span>' : ''}
                </span>
                <span>
                    ${reply.created_at_human}
                    ${editBtnHtml}
                </span>
            </div>
            ${messageHtml}
            ${attachmentsHtml}
        </div>
    `;
    
    thread.appendChild(bubble);
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
@endsection
