@extends('layouts.master')

@section('title', 'Edit Comment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Comment</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Comments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="content">Comment Content</label>
                                    <textarea 
                                        id="content" 
                                        name="content" 
                                        class="form-control @error('content') is-invalid @enderror" 
                                        rows="6" 
                                        required
                                    >{{ old('content', $comment->content) }}</textarea>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $comment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ old('status', $comment->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="spam" {{ old('status', $comment->status) === 'spam' ? 'selected' : '' }}>Spam</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Comment Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Author</label>
                                            <p class="form-control-static">
                                                @if($comment->user)
                                                    <strong>{{ $comment->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                                @else
                                                    @php
                                                        $lines = explode("\n", $comment->content);
                                                        $guestInfo = $lines[0] ?? '';
                                                        $guestName = str_replace('Guest: ', '', explode(' (', $guestInfo)[0] ?? 'Guest');
                                                        $guestEmail = trim(explode('(', explode(')', $guestInfo)[0] ?? '') ?? '');
                                                    @endphp
                                                    <strong>{{ $guestName }}</strong><br>
                                                    <small class="text-muted">{{ $guestEmail }}</small>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label>Post</label>
                                            <p class="form-control-static">
                                                @if($comment->post)
                                                    <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank">
                                                        {{ $comment->post->title }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Post deleted</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label>Created</label>
                                            <p class="form-control-static">{{ $comment->created_at->format('M d, Y H:i:s') }}</p>
                                        </div>

                                        <div class="form-group">
                                            <label>Updated</label>
                                            <p class="form-control-static">{{ $comment->updated_at->format('M d, Y H:i:s') }}</p>
                                        </div>

                                        @if($comment->parent)
                                            <div class="form-group">
                                                <label>Reply To</label>
                                                <p class="form-control-static">
                                                    <small class="text-muted">
                                                        {{ Str::limit($comment->parent->content, 100) }}
                                                    </small>
                                                </p>
                                            </div>
                                        @endif

                                        @if($comment->replies->count() > 0)
                                            <div class="form-group">
                                                <label>Replies</label>
                                                <p class="form-control-static">
                                                    <span class="badge badge-info">{{ $comment->replies->count() }} replies</span>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Comment
                            </button>
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
