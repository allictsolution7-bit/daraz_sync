@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Reviews</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Review
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reviewer</th>
                                    <th>Product</th>
                                    <th>Rating</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($review->reviewer_image)
                                                    <img src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}" class="img-circle mr-2" width="40">
                                                @endif
                                                {{ $review->reviewer_name }}
                                            </div>
                                        </td>
                                        <td>{{ $review->product_name }}</td>
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i - 0.5 <= $review->rating)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>{{ $review->review_date->format('M d, Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.reviews.toggle-status', $review) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $review->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $review->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No reviews found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection