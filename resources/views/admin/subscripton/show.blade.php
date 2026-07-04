@extends('layouts.master')

@section('title', 'Subscription Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Subscription Details</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-primary">
                             Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email:</label>
                                <p class="form-control">{{ $subscription->email }}</p>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <p class="form-control">
                                    <span class="badge bg-{{ $subscription->status ? 'success' : 'danger' }} text-white">
                                        {{ $subscription->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>
                            <div class="form-group">
                                <label>IP Address:</label>
                                <p class="form-control">{{ $subscription->ip_address ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>Country:</label>
                                <p class="form-control">{{ $subscription->country ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>City:</label>
                                <p class="form-control">{{ $subscription->city ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>Region:</label>
                                <p class="form-control">{{ $subscription->region ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Device:</label>
                                <p class="form-control">{{ $subscription->device ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>Browser:</label>
                                <p class="form-control">{{ $subscription->browser ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>Platform:</label>
                                <p class="form-control">{{ $subscription->platform ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group">
                                <label>Created At:</label>
                                <p class="form-control">{{ $subscription->created_at->format('M d, Y H:i:s') }}</p>
                            </div>
                            <div class="form-group">
                                <label>Updated At:</label>
                                <p class="form-control">{{ $subscription->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>User Agent:</label>
                        <textarea class="form-control" rows="3" readonly>{{ $subscription->user_agent ?? 'N/A' }}</textarea>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('admin.subscriptions.toggle-status', $subscription) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn {{ $subscription->status ? 'btn-warning' : 'btn-success' }}">
                                {{ $subscription->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection