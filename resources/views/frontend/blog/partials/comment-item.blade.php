<div class="comment-item" id="comment-{{ $comment->id }}" data-comment-id="{{ $comment->id }}">
    <div class="comment-avatar">
        @if ($comment->user)
            <img src="{{ asset('man.svg') }}" alt="{{ $comment->user->name }}" class="avatar-img">
        @else
            <img src="{{ asset('man.svg') }}" alt="Guest" class="avatar-img">
        @endif
    </div>
    
    <div class="comment-content">
        <div class="comment-header">
            <h5 class="comment-author">
                @if ($comment->user)
                    {{ $comment->user->name }}
                @else
                    @php
                        $lines = explode("\n", $comment->content);
                        $guestInfo = $lines[0] ?? '';
                        $guestName = str_replace('Guest: ', '', explode(' (', $guestInfo)[0] ?? 'Guest');
                    @endphp
                    {{ $guestName }}
                @endif
            </h5>
            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
        </div>
        
        <div class="comment-text">
            @if ($comment->user)
                {{ $comment->content }}
            @else
                @php
                    $lines = explode("\n", $comment->content);
                    $commentText = implode("\n", array_slice($lines, 2));
                @endphp
                {{ $commentText }}
            @endif
        </div>
        
        <div class="comment-actions">
            <button class="btn btn-sm btn-link reply-btn" onclick="replyToComment({{ $comment->id }})">
                <i class="fas fa-reply"></i> Reply
            </button>
            
            @auth
                <button class="btn btn-sm btn-link like-btn" onclick="likeComment({{ $comment->id }})">
                    <i class="far fa-heart"></i> Like
                </button>
            @endauth
            
            <button class="btn btn-sm btn-link report-btn" onclick="reportComment({{ $comment->id }})">
                <i class="fas fa-flag"></i> Report
            </button>
        </div>
        
        <!-- Replies Container -->
        <div class="replies-container" id="replies-{{ $comment->id }}">
            @if ($comment->hasReplies())
                @foreach ($comment->replies as $reply)
                    @include('frontend.blog.partials.comment-item', ['comment' => $reply])
                @endforeach
            @endif
        </div>
    </div>
</div>
