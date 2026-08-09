@extends('frontend.app')

@section('content')
<style>
    /* Chat System Layout styling */
    .chat-wrapper {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: 500px;
        min-height: 400px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin: 30px auto 90px auto;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .chat-sidebar {
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }
    
    .chat-sidebar-header {
        padding: 20px;
        font-weight: 700;
        font-size: 1.2rem;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
    }
    
    .chat-list {
        overflow-y: auto;
        flex: 1;
    }
    
    .chat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    
    .chat-item:hover {
        background: #f1f5f9;
    }
    
    .chat-item.active {
        background: #e2e8f0;
    }
    
    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    
    .chat-info {
        flex: 1;
        min-width: 0;
    }
    
    .chat-name {
        font-weight: 600;
        font-size: 0.95rem;
        color: #0f172a;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-last-message {
        font-size: 0.8rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .chat-content {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 0;
        overflow: hidden;
    }

    .chat-room-container {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #fff;
    }
    
    .chat-header {
        padding: 15px 25px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        z-index: 10;
    }
    
    .chat-body {
        flex: 1;
        padding: 25px;
        overflow-y: auto;
        background: #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .message-bubble {
        max-width: 60%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 0.9rem;
        line-height: 1.4;
        position: relative;
        word-wrap: break-word;
    }
    
    .message-bubble.sent {
        align-self: flex-end;
        background: var(--primary-color, #ef4444);
        color: #fff;
        border-bottom-right-radius: 4px;
    }
    
    .message-bubble.received {
        align-self: flex-start;
        background: #fff;
        color: #1e293b;
        border-bottom-left-radius: 4px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    
    .message-time {
        font-size: 0.7rem;
        margin-top: 5px;
        text-align: right;
        opacity: 0.7;
    }
    
    .message-bubble.received .message-time {
        color: #64748b;
    }
    
    .chat-footer {
        padding: 15px 25px;
        border-top: 1px solid #e2e8f0;
        background: #fff;
    }
    
    .chat-input-form {
        display: flex;
        gap: 10px;
    }
    
    .chat-input {
        flex: 1;
        border: 1.5px solid #cbd5e1;
        border-radius: 24px;
        padding: 12px 20px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }
    
    .chat-input:focus {
        border-color: var(--primary-color, #ef4444);
    }
    
    .chat-send-btn {
        background: var(--primary-color, #ef4444);
        color: #fff;
        border: none;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s, background-color 0.2s;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
    }
    
    .chat-send-btn:hover {
        transform: scale(1.05);
        opacity: 0.95;
    }
    
    .chat-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #64748b;
        text-align: center;
        background: #f8fafc;
        padding: 40px;
    }
    
    .chat-empty-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .chat-wrapper {
            grid-template-columns: 1fr;
            height: calc(100vh - 100px);
        }
        
        .chat-sidebar {
            display: {{ $activeRoom ? 'none' : 'flex' }};
        }
        
        .chat-room-container {
            display: {{ $activeRoom ? 'flex' : 'none' }};
        }
    }
</style>

<div class="base-container">
    <div class="chat-wrapper">
        <!-- Sidebar: Conversations List -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-header d-flex justify-content-between align-items-center">
                <span>Conversations</span>
                <i class="fa-regular fa-message text-slate-400"></i>
            </div>
            <div class="chat-list">
                @forelse($chatRooms as $room)
                    <a href="{{ route('chats.index', ['room' => $room->id]) }}" 
                       class="chat-item {{ $activeRoom && $activeRoom->id === $room->id ? 'active' : '' }} d-flex align-items-center justify-content-between" style="gap: 10px;">
                        <div class="d-flex align-items-center gap-3" style="min-width: 0; flex: 1;">
                            <img src="{{ $room->display_logo }}" alt="{{ $room->display_name }}" class="chat-avatar" style="flex-shrink: 0;">
                            <div class="chat-info" style="min-width: 0; flex: 1;">
                                <div class="chat-name">{{ $room->display_name }}</div>
                                <div class="chat-last-message">
                                    {{ $room->lastMessage ? $room->lastMessage->message : 'No messages yet' }}
                                </div>
                            </div>
                        </div>
                        @if(($room->unread_count ?? 0) > 0)
                            <span class="badge bg-danger rounded-circle text-white font-weight-bold d-flex align-items-center justify-content-center" style="font-size: 0.65rem; min-width: 18px; height: 18px; padding: 2px; flex-shrink: 0;">
                                {{ $room->unread_count }}
                            </span>
                        @endif
                    </a>
                @empty
                    <div class="p-5 text-center text-slate-400 text-sm">
                        No conversations yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Window -->
        <div class="chat-content">
            @if($activeRoom)
                <div class="chat-room-container">
                    <div class="chat-header">
                        <a href="{{ route('chats.index') }}" class="d-md-none text-slate-600 me-2">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <img src="{{ $activeRoom->display_logo ?? asset('clientside/images/profile.png') }}" 
                             alt="{{ $activeRoom->display_name }}" 
                             class="chat-avatar" style="width: 40px; height: 40px;">
                        <div>
                            <h4 class="m-0 font-bold text-sm" style="font-size: 1rem; color: #0f172a;">{{ $activeRoom->display_name }}</h4>
                            <span class="text-xs text-green-500 font-medium">Online Support</span>
                        </div>
                    </div>

                    <div class="chat-body" id="chatBody">
                        @forelse($activeRoom->messages as $msg)
                            <div class="message-bubble {{ $msg->sender_id === auth()->id() ? 'sent' : 'received' }}" data-id="{{ $msg->id }}">
                                <div>{{ $msg->message }}</div>
                                <div class="message-time">
                                    {{ str_contains(strtolower($msg->created_at->diffForHumans()), 'second') ? 'Just now' : $msg->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 my-auto" id="noMessagesPlaceholder">
                                Send a message to start the conversation!
                            </div>
                        @endforelse
                    </div>

                    <div class="chat-footer">
                        <form id="chatForm" class="chat-input-form" action="{{ route('chats.send', $activeRoom->id) }}" method="POST">
                            @csrf
                            <input type="text" name="message" id="messageInput" class="chat-input" placeholder="Type your message here..." required autocomplete="off">
                            <button type="submit" class="chat-send-btn">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="chat-empty-state">
                    <div class="chat-empty-icon">
                        <i class="fa-regular fa-comments"></i>
                    </div>
                    <h3>Your Inbox</h3>
                    <p>Select a conversation from the sidebar to start messaging.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($activeRoom)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBody = document.getElementById('chatBody');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    
    // Scroll chat body to bottom initially
    chatBody.scrollTop = chatBody.scrollHeight;
    
    // Submit message via AJAX
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageText = messageInput.value.trim();
        if (!messageText) return;
        
        const url = chatForm.action;
        const formData = new FormData(chatForm);
        
        // Optimistic UI update: append immediately
        const tempId = 'temp-' + Date.now();
        const tempMsg = {
            id: tempId,
            message: messageText,
            created_at: 'Sending...'
        };
        
        // Clear placeholder
        const placeholder = document.getElementById('noMessagesPlaceholder');
        if (placeholder) placeholder.remove();
        
        appendMessage(tempMsg, true);
        messageInput.value = '';
        messageInput.focus();
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Find optimistic bubble and update with real data
                const bubble = chatBody.querySelector(`[data-id="${tempId}"]`);
                if (bubble) {
                    bubble.setAttribute('data-id', data.message.id);
                    const timeEl = bubble.querySelector('.message-time');
                    if (timeEl) timeEl.textContent = data.message.created_at;
                    // Update polling lastMessageId if needed
                    if (parseInt(data.message.id) > lastMessageId) {
                        lastMessageId = parseInt(data.message.id);
                    }
                }
            } else {
                // Mark optimistic bubble as failed
                const bubble = chatBody.querySelector(`[data-id="${tempId}"]`);
                if (bubble) {
                    bubble.style.opacity = '0.7';
                    const timeEl = bubble.querySelector('.message-time');
                    if (timeEl) timeEl.textContent = 'Failed to send';
                    timeEl.style.color = '#ef4444';
                }
            }
        })
        .catch(err => {
            console.error(err);
            const bubble = chatBody.querySelector(`[data-id="${tempId}"]`);
            if (bubble) {
                bubble.style.opacity = '0.7';
                const timeEl = bubble.querySelector('.message-time');
                if (timeEl) timeEl.textContent = 'Failed to send';
                timeEl.style.color = '#ef4444';
            }
        });
    });
    
    function appendMessage(msg, isSent) {
        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${isSent ? 'sent' : 'received'}`;
        bubble.setAttribute('data-id', msg.id);
        
        bubble.innerHTML = `
            <div>${escapeHtml(msg.message)}</div>
            <div class="message-time">${msg.created_at}</div>
        `;
        
        chatBody.appendChild(bubble);
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Polling function for real-time messages
    let lastMessageId = 0;
    const bubbles = chatBody.querySelectorAll('.message-bubble');
    if (bubbles.length > 0) {
        lastMessageId = bubbles[bubbles.length - 1].getAttribute('data-id');
    }

    function pollMessages() {
        const pollUrl = `{{ route('chats.messages', $activeRoom->id) }}?last_id=${lastMessageId}`;
        
        fetch(pollUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                // Clear placeholder
                const placeholder = document.getElementById('noMessagesPlaceholder');
                if (placeholder) placeholder.remove();
                
                data.messages.forEach(msg => {
                    if (parseInt(msg.sender_id) !== {{ auth()->id() }}) {
                        appendMessage(msg, false);
                    }
                    lastMessageId = msg.id;
                });
            }
        })
        .catch(err => console.error('Polling error:', err));
    }

    // Poll every 3 seconds
    setInterval(pollMessages, 3000);
});
</script>
@endif
@endsection
