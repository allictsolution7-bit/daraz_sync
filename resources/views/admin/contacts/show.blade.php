@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Message Details</h1>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-envelope me-1"></i>
                    Message from {{ $contact->name }}
                </div>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Contact Information</h5>
                    <p><strong>Name:</strong> {{ $contact->name }}</p>
                    <p><strong>Email:</strong> {{ $contact->email }}</p>
                    <p><strong>Phone:</strong> {{ $contact->phone ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $contact->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <h5>System Information</h5>
                    <p><strong>IP Address:</strong> {{ $contact->ip_address }}</p>
                    <p><strong>Browser:</strong> {{ $contact->browser }}</p>
                    <p><strong>Device:</strong> {{ $contact->device }}</p>
                    <p><strong>Location:</strong> {{ $contact->city }}, {{ $contact->country }}</p>
                </div>
            </div>
            
            <div class="message-content mt-4">
                <h5>Subject</h5>
                <p class="border-bottom pb-2">{{ $contact->subject }}</p>
                
                <h5>Message</h5>
                <div class="border p-3 rounded bg-light">
                    {{ $contact->message }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection