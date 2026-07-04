@extends('layouts.master')

@section('title', 'Manage Comments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Blog Comments</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" onclick="bulkAction('approve')">
                            <i class="fas fa-check"></i> Approve Selected
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('spam')">
                            <i class="fas fa-ban"></i> Mark as Spam
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Post</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="comment-checkbox" value="{{ $comment->id }}">
                                        </td>
                                        <td>
                                            @if($comment->user)
                                                <strong>{{ $comment->user->name }}</strong>
                                                <br><small class="text-muted">{{ $comment->user->email }}</small>
                                            @else
                                                @php
                                                    $lines = explode("\n", $comment->content);
                                                    $guestInfo = $lines[0] ?? '';
                                                    $guestName = str_replace('Guest: ', '', explode(' (', $guestInfo)[0] ?? 'Guest');
                                                    $guestEmail = trim(explode('(', explode(')', $guestInfo)[0] ?? '') ?? '');
                                                @endphp
                                                <strong>{{ $guestName }}</strong>
                                                <br><small class="text-muted">{{ $guestEmail }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($comment->user)
                                                {{ Str::limit($comment->content, 100) }}
                                            @else
                                                @php
                                                    $lines = explode("\n", $comment->content);
                                                    $commentText = implode("\n", array_slice($lines, 2));
                                                @endphp
                                                {{ Str::limit($commentText, 100) }}
                                            @endif
                                            @if($comment->parent)
                                                <br><small class="text-info">Reply to: {{ Str::limit($comment->parent->content, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($comment->post)
                                                <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank">
                                                    {{ Str::limit($comment->post->title, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Post deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $comment->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if($comment->status === 'pending')
                                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($comment->status !== 'spam')
                                                    <form action="{{ route('admin.comments.spam', $comment) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Mark as Spam">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No comments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($comments->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Form -->
<form id="bulk-action-form" action="{{ route('admin.comments.bulk-action') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulk-action-type">
    <input type="hidden" name="comments" id="bulk-action-comments">
</form>
@endsection

@section('scripts')
<script>
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.comment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Bulk action functionality
    function bulkAction(action) {
        const selectedComments = document.querySelectorAll('.comment-checkbox:checked');
        
        if (selectedComments.length === 0) {
            alert('Please select at least one comment.');
            return;
        }

        if (!confirm(`Are you sure you want to ${action} the selected comments?`)) {
            return;
        }

        const commentIds = Array.from(selectedComments).map(checkbox => checkbox.value);
        
        document.getElementById('bulk-action-type').value = action;
        document.getElementById('bulk-action-comments').value = JSON.stringify(commentIds);
        document.getElementById('bulk-action-form').submit();
    }

    // Update select all checkbox state
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('comment-checkbox')) {
            const totalCheckboxes = document.querySelectorAll('.comment-checkbox').length;
            const checkedCheckboxes = document.querySelectorAll('.comment-checkbox:checked').length;
            
            const selectAllCheckbox = document.getElementById('select-all');
            selectAllCheckbox.checked = checkedCheckboxes === totalCheckboxes;
            selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
        }
    });
</script>
@endsection
