@extends('layouts.master')

@section('title', 'Delayed Events History')

@section('styles')
<style>
    .event-table .text-muted {
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4><i class="fas fa-history"></i> Delayed Events History</h4>
                    <p class="text-muted">All delayed purchase events with delivery status and errors.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.delayed-events.settings') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('admin.delayed-events.pending') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-clock"></i> Pending
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle event-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Event</th>
                                        <th>Amount</th>
                                        <th>Fired At</th>
                                        <th>Created</th>
                                        <th>Error</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        @php
                                            $statusLabel = 'Pending';
                                            $statusClass = 'bg-warning';
                                            $statusIcon = 'fa-clock';
                                            if ($event->fire_failed) {
                                                $statusLabel = 'Failed';
                                                $statusClass = 'bg-danger';
                                                $statusIcon = 'fa-exclamation-circle';
                                            } elseif ($event->fired_at) {
                                                $statusLabel = 'Fired';
                                                $statusClass = 'bg-success';
                                                $statusIcon = 'fa-check-circle';
                                            }
                                        @endphp
                                        <tr>
                                            <td>#{{ $event->id }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.edit', $event->order_id) }}">
                                                    Order #{{ $event->order_id }}
                                                </a>
                                                <div class="text-muted">{{ $event->order?->name ?? 'Unknown customer' }}</div>
                                                @if($event->order?->phone)
                                                    <div class="text-muted">{{ $event->order->phone }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $statusClass }}">
                                                    <i class="fas {{ $statusIcon }}"></i> {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ data_get($event->event_data, 'event', 'purchase') }}
                                                <div class="text-muted">{{ data_get($event->event_data, 'event_id') }}</div>
                                            </td>
                                            <td>
                                                {{ number_format((float) data_get($event->event_data, 'value', 0), 2) }}
                                                {{ data_get($event->event_data, 'currency', 'BDT') }}
                                            </td>
                                            <td>
                                                @if($event->fired_at)
                                                    {{ $event->fired_at->diffForHumans() }}
                                                    <div class="text-muted">{{ $event->fired_at->format('Y-m-d H:i') }}</div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $event->created_at?->diffForHumans() }}
                                                <div class="text-muted">{{ $event->created_at?->format('Y-m-d H:i') }}</div>
                                            </td>
                                            <td>
                                                @if($event->fire_failed && $event->fire_error)
                                                    <span class="text-danger" title="{{ $event->fire_error }}">
                                                        {{ \Illuminate\Support\Str::limit($event->fire_error, 60) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.edit', $event->order_id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i> View Order
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <p class="mb-0">No delayed event history found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
