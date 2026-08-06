@extends('vendor.layouts.app')

@section('title', 'Edit POS Order')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit POS Order #{{ $order->id }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('vendor.orders.reseller.update', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small mb-1">Customer Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $order->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small mb-1">Customer Phone *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $order->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small mb-1">Delivery Address *</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $order->address) }}</textarea>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small mb-1">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', $order->city) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small mb-1">Amount Paid (Optional)</label>
                                <input type="number" name="amount_paid" class="form-control" value="{{ old('amount_paid', $order->delivery_data['amount_paid'] ?? 0) }}" min="0" step="any">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small mb-1">Remarks / Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes for admin">{{ old('notes', $order->message) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('vendor.orders.reseller') }}" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i>Back</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle me-1"></i>Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
