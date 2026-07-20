@extends('layouts.master')

@section('title', 'Manage Comments')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .comments-dashboard-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Card styling */
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
            padding: 2rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.25);
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
            padding: 2.5rem;
            border-radius: 24px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(217, 119, 6, 0.3);
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
            border-color: rgba(217, 119, 6, 0.2);
        }

        .stat-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon-primary {
            background: rgba(217, 119, 6, 0.08);
            color: #d97706;
        }

        .stat-icon-success {
            background: rgba(16, 185, 129, 0.08);
            color: #10b981;
        }

        .stat-icon-danger {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0.25rem 0 0 0;
            line-height: 1.2;
        }

        /* Action Buttons */
        .btn-premium-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            cursor: pointer;
        }

        .btn-premium-action:hover {
            transform: translateY(-2px);
            background: #ffffff !important;
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
        }

        .btn-premium-approve:hover {
            color: #10b981 !important;
            border-color: #10b981 !important;
        }

        .btn-premium-warning:hover {
            color: #d97706 !important;
            border-color: #d97706 !important;
        }

        .btn-premium-danger:hover {
            color: #ef4444 !important;
            border-color: #ef4444 !important;
        }

        /* Modernized Table styling */
        .premium-table-container {
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .premium-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            padding: 1.125rem 1.25rem;
            border-bottom: 2px solid #e2e8f0;
            border-top: none;
        }

        .premium-table td {
            padding: 1.25rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .premium-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        /* Avatar representation */
        .author-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d97706;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Status badges */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-pill-approved {
            background-color: rgba(16, 185, 129, 0.08);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .status-pill-pending {
            background-color: rgba(245, 158, 11, 0.08);
            color: #d97706;
            border: 1px solid rgba(245, 158, 11, 0.15);
        }

        .status-pill-spam {
            background-color: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-dot-approved { background-color: #10b981; box-shadow: 0 0 8px #10b981; }
        .status-dot-pending { background-color: #f59e0b; box-shadow: 0 0 8px #f59e0b; }
        .status-dot-spam { background-color: #ef4444; box-shadow: 0 0 8px #ef4444; }

        /* Custom Checkbox */
        .custom-checkbox-premium {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
            vertical-align: middle;
            margin: 0;
        }

        .custom-checkbox-premium:checked {
            background-color: #d97706;
            border-color: #d97706;
        }

        /* Table actions */
        .btn-table-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-right: 0.25rem;
        }

        .btn-table-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-table-action-approve:hover { color: #10b981; background: rgba(16, 185, 129, 0.06); border-color: rgba(16, 185, 129, 0.2); }
        .btn-table-action-spam:hover { color: #f59e0b; background: rgba(245, 158, 11, 0.06); border-color: rgba(245, 158, 11, 0.2); }
        .btn-table-action-edit:hover { color: #2563eb; background: rgba(37, 99, 235, 0.06); border-color: rgba(37, 99, 235, 0.2); }
        .btn-table-action-delete:hover { color: #ef4444; background: rgba(239, 68, 68, 0.06); border-color: rgba(239, 68, 68, 0.2); }

        /* Session alert */
        .alert-toast {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid comments-dashboard-wrapper">
        <!-- Premium Gradient Header -->
        <div class="gradient-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <h1 class="gradient-header-title">Blog Comments Manager</h1>
                    <p class="gradient-header-subtitle">Moderate, approve, spam-mark, and configure user responses to blog posts.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn-premium-action btn-premium-approve" onclick="bulkAction('approve')">
                        <i class="fas fa-check"></i> Approve Selected
                    </button>
                    <button type="button" class="btn-premium-action btn-premium-warning" onclick="bulkAction('spam')">
                        <i class="fas fa-ban"></i> Mark as Spam
                    </button>
                    <button type="button" class="btn-premium-action btn-premium-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Insights Counter Grid -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-primary">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <p class="stat-label">Total catalog comments</p>
                        <h3 class="stat-value">{{ method_exists($comments, 'total') ? $comments->total() : $comments->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-success">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="stat-label">Pending Approval (Page)</p>
                        <h3 class="stat-value">{{ $comments->where('status', 'pending')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-danger">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <p class="stat-label">Spam Comments (Page)</p>
                        <h3 class="stat-value">{{ $comments->where('status', 'spam')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Message Alerts -->
        @if (session('success'))
            <div class="alert alert-toast alert-success d-flex align-items-center gap-3 fade show" role="alert">
                <i class="fas fa-check-circle text-success fs-4"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Comments Listing Card -->
        <div class="premium-card">
            <div class="premium-table-container">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th width="40" style="padding-left: 1.5rem; text-align: center;">
                                <input type="checkbox" id="select-all" class="custom-checkbox-premium">
                            </th>
                            <th>Author Identity</th>
                            <th>Response Text</th>
                            <th style="min-width: 180px;">Reference Post</th>
                            <th>Status Badge</th>
                            <th>Published Date</th>
                            <th style="width: 160px; text-align: right; padding-right: 1.5rem;">Action Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td style="padding-left: 1.5rem; text-align: center;">
                                    <input type="checkbox" class="comment-checkbox custom-checkbox-premium" value="{{ $comment->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="author-avatar">
                                            @if($comment->user)
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            @else
                                                G
                                            @endif
                                        </div>
                                        <div>
                                            @if($comment->user)
                                                <strong style="color: #0f172a;">{{ $comment->user->name }}</strong>
                                                <br><small class="text-muted">{{ $comment->user->email }}</small>
                                            @else
                                                @php
                                                    $lines = explode("\n", $comment->content);
                                                    $guestInfo = $lines[0] ?? '';
                                                    $guestName = str_replace('Guest: ', '', explode(' (', $guestInfo)[0] ?? 'Guest');
                                                    $guestEmail = trim(explode('(', explode(')', $guestInfo)[0] ?? '') ?? '');
                                                @endphp
                                                <strong style="color: #0f172a;">{{ $guestName }}</strong>
                                                <br><small class="text-muted">{{ $guestEmail }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-slate-700" style="font-weight: 500; max-width: 320px;">
                                        @if($comment->user)
                                            {{ Str::limit($comment->content, 100) }}
                                        @else
                                            @php
                                                $lines = explode("\n", $comment->content);
                                                $commentText = implode("\n", array_slice($lines, 2));
                                            @endphp
                                            {{ Str::limit($commentText, 100) }}
                                        @endif
                                    </div>
                                    @if($comment->parent)
                                        <div class="mt-1">
                                            <span class="badge bg-light text-info" style="font-size:0.75rem; border:1px solid rgba(14,165,233,0.12); padding:0.25rem 0.5rem;">
                                                <i class="fas fa-reply mr-1"></i> Reply to: {{ Str::limit($comment->parent->content, 40) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($comment->post)
                                        <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" class="category-badge" style="text-decoration: none; font-size: 0.85rem; max-width: 200px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle;" title="{{ $comment->post->title }}">
                                            <i class="fas fa-link"></i> {{ $comment->post->title }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Post deleted</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($comment->status === 'approved')
                                        <span class="status-pill status-pill-approved">
                                            <span class="status-dot status-dot-approved"></span> Approved
                                        </span>
                                    @elseif ($comment->status === 'pending')
                                        <span class="status-pill status-pill-pending">
                                            <span class="status-dot status-dot-pending"></span> Pending
                                        </span>
                                    @else
                                        <span class="status-pill status-pill-spam">
                                            <span class="status-dot status-dot-spam"></span> Spam
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-slate-600" style="font-weight:600; font-size:0.875rem; white-space: nowrap;">
                                        <i class="far fa-calendar-alt text-muted mr-1"></i>
                                        {{ $comment->created_at->format('M d, Y H:i') }}
                                    </span>
                                </td>
                                <td style="text-align: right; padding-right: 1.5rem; white-space: nowrap;">
                                    <div class="d-inline-flex">
                                        @if($comment->status === 'pending')
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-table-action btn-table-action-approve" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($comment->status !== 'spam')
                                            <form action="{{ route('admin.comments.spam', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-table-action btn-table-action-spam" title="Mark as Spam">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('admin.comments.edit', $comment) }}" class="btn-table-action btn-table-action-edit" title="Edit comment">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-table-action btn-table-action-delete" title="Delete comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-comments fs-1 text-slate-300"></i>
                                    </div>
                                    <h5 class="fw-bold">No comments published yet</h5>
                                    <p class="text-muted small">All user posts comment lists will be shown here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($comments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $comments->links() }}
                </div>
            @endif
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
