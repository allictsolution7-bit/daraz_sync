<div class="comment-form-wrapper">
    <h3 class="section-title">Leave a Comment</h3>
    <div class="section-divider"></div>

    @auth
        <!-- Authenticated User Comment Form -->
        <form id="comment-form" class="comment-form">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" id="reply-to-id" value="">
            
            <div class="form-group">
                <label for="comment-content">Your Comment</label>
                <textarea  style="width: 100%; !important;"
                    id="comment-content" 
                    name="content" 
                    class="form-control" 
                    rows="10" 
                    placeholder="Write your comment here..." 
                    required
                ></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="width: 100%; !important; margin-bottom: 10px;padding: 10px;" >
                    <i class="fas fa-paper-plane"></i>
                    <span class="btn-text">Post Comment</span>
                    <span class="spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                <button type="button" class="btn btn-secondary" id="cancel-reply" style="display: none;">
                    <i class="fas fa-times"></i>
                    Cancel Reply
                </button>
            </div>
        </form>
    @else
        <!-- Guest Comment Form -->
        <form id="guest-comment-form" class="comment-form">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="hidden" name="parent_id" id="guest-reply-to-id" value="">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="guest-name">Name *</label>
                        <input 
                            type="text" 
                            id="guest-name" 
                            name="guest_name" 
                            class="form-control" 
                            placeholder="Your name" 
                            required
                        >
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="guest-email">Email *</label>
                        <input 
                            type="email" 
                            id="guest-email" 
                            name="guest_email" 
                            class="form-control" 
                            placeholder="your@email.com" 
                            required
                        >
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="guest-comment-content">Your Comment</label>
                <textarea 
                    id="guest-comment-content" 
                    name="content" 
                    class="form-control" 
                    rows="5" 
                    placeholder="Write your comment here..." 
                    required
                ></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    <span class="btn-text">Post Comment</span>
                    <span class="spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>
                <button type="button" class="btn btn-secondary" id="guest-cancel-reply" style="display: none;">
                    <i class="fas fa-times"></i>
                    Cancel Reply
                </button>
            </div>

            <div class="guest-notice">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Guest comments are moderated and will appear after approval.
                </small>
            </div>
        </form>
    @endauth
</div>
