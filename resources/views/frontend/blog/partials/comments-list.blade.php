<div class="comments-section">
    <h3 class="section-title">
        Comments ({{ method_exists($comments, 'total') ? $comments->total() : $comments->count() }})
    </h3>
    <div class="section-divider"></div>

    @if ($comments->count() > 0)
        <div class="comments-list">
            @foreach ($comments as $comment)
                @include('frontend.blog.partials.comment-item', ['comment' => $comment])
            @endforeach
        </div>

        <!-- Load More Comments -->
        @if (method_exists($comments, 'hasMorePages') && $comments->hasMorePages())
            <div class="text-center mt-4">
                <button class="btn btn-outline-primary load-more-comments" 
                        data-page="{{ method_exists($comments, 'currentPage') ? $comments->currentPage() + 1 : 1 }}"
                        data-post-id="{{ $comments->first() ? $comments->first()->post_id : 0 }}">
                    <span class="btn-text">Load More Comments</span>
                    <span class="spinner" style="display: none;"></span>
                </button>
            </div>
        @endif

        <!-- Pagination -->
        @if (method_exists($comments, 'hasPages') && $comments->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $comments->links() }}
            </div>
        @endif
    @else
        <div class="no-comments">
            <p class="text-muted text-center">
                <i class="fas fa-comments"></i>
                No comments yet. Be the first to comment!
            </p>
        </div>
    @endif
</div>
