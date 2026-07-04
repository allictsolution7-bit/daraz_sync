@extends('layouts.master')

@section('title', 'Combo Offers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Combo Offers</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.combo_offers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Combo Offer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Title</th>
                                    <th>Items Count</th>
                                    <th>Combo Price</th>
                                    <th>Original Price</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comboOffers as $comboOffer)
                                    <tr>
                                        <td>{{ $comboOffer->id }}</td>
                                        <td>{{ $comboOffer->product->title }}</td>
                                        <td>{{ $comboOffer->title }}</td>
                                        <td>{{ $comboOffer->items_count }}</td>
                                        <td>৳{{ number_format($comboOffer->combo_price, 2) }}</td>
                                        <td>৳{{ number_format($comboOffer->original_price, 2) }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $comboOffer->is_active ? '#28a745' : '#dc3545' }};">
                                                {{ $comboOffer->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $comboOffer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                {{-- <a href="{{ route('admin.combo_offers.show', $comboOffer) }}" 
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a> --}}
                                                {{-- <a href="{{ route('admin.combo_offers.edit', $comboOffer) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a> --}}
                                                {{-- <button type="button" 
                                                        class="btn btn-sm btn-{{ $comboOffer->is_active ? 'secondary' : 'success' }} toggle-status"
                                                        data-id="{{ $comboOffer->id }}" 
                                                        title="{{ $comboOffer->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $comboOffer->is_active ? 'pause' : 'play' }}"></i>
                                                </button> --}}
                                                <form action="{{ route('admin.combo_offers.destroy', $comboOffer) }}" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this combo offer?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="margin-left:5px;" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No combo offers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $comboOffers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        const button = $(this);
        const comboId = button.data('id');
        
        $.ajax({
            url: `/admin/combo_offers/${comboId}/toggle-status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Error toggling status');
            }
        });
    });
});
</script>
@endpush
@endsection 