@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Post</h1>
    </div>

    <!-- Category Details -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $post->title }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $post->content ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $post->slug }}</td>
                    </tr>
                    <tr>
                        <th>Meta Title</th>
                        <td>{{ $post->meta_title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Description</th>
                        <td>{{ $post->meta_description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Canonical URL</th>
                        <td>{{ $post->canonical_url ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td>
                            @if ($post->image)
                                <img src="{{ asset($post->image) }}" alt="{{ $post->image_alt }}" class="img-thumbnail" width="150">
                            @else
                                No Image
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Image Alt Tag</th>
                        <td>{{ $post->image_alt ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $post->created_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $post->updated_at->format('d M, Y h:i A') }}</td>
                    </tr>

                    <tr>
                        <th>Created By</th>
                        <td>{{ $post->user ? $post->user->name : 'Unknown' }} ,Email : {{ $post->user ? $post->user->email : 'Unknown' }}</td>
                    </tr>


                </tbody>
            </table>
            <div class="mt-3">
                <a href="{{ route('admin.post.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-primary ml-2">Edit Post</a>
            </div>
        </div>
    </div>
</div>
@endsection
