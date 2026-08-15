@extends('layouts.master')

@section('title', 'Manage Support Tickets')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .support-dashboard-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 0.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 14px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.25);
        }

        .gradient-header {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 50%, #312e81 100%);
            padding: 1.25rem 1.75rem;
            border-radius: 16px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 15px 30px -10px rgba(79, 70, 229, 0.25);
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

        .table-modern {
            border-collapse: separate;
            border-spacing: 0 4px;
            width: 100%;
        }

        .table-modern th {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748b;
            border: none;
            padding: 0.5rem 0.75rem;
        }

        .table-modern tbody tr {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.6);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
            transition: all 0.2s ease;
        }

        .table-modern tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -4px rgba(0, 0, 0, 0.03);
            border-color: rgba(99, 102, 241, 0.25);
        }

        .table-modern td {
            padding: 0.65rem 0.75rem;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .table-modern td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table-modern td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .badge-status {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
        }

        .status-open {
            background-color: #dbeafe !important;
            color: #1e40af !important;
        }

        .status-pending {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .status-resolved {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
        }

        .status-closed {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .badge-priority {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-low {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .priority-medium {
            background-color: #e0f2fe !important;
            color: #0369a1 !important;
        }

        .priority-high {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
        }

        .filter-select {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 8px;
            font-size: 12.5px;
            outline: none;
            background-color: white;
            min-width: 130px;
        }

        .filter-select:focus {
            border-color: #4f46e5;
        }
    </style>
@endsection

@section('content')
<div class="support-dashboard-wrapper container-fluid">
    <!-- Gradient Header -->
    <div class="gradient-header">
        <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
            <div>
                <h1 class="h5 fw-bold mb-0 text-white">Customer Support Tickets</h1>
                <p class="mb-0 text-white-50 opacity-75 small">View, reply, and take action on client complaints & queries</p>
            </div>
            <div>
                <span class="badge bg-white fw-bold px-2.5 py-1.5 rounded-pill shadow-sm" style="font-size: 11px; color: #4f46e5 !important;">
                    {{ \App\Models\SupportTicket::where('status', 'open')->count() }} Open Tickets
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" style="background-color: #d1fae5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-circle-check me-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Filters and Ticket List -->
    <div class="premium-card">
        <form action="{{ route('admin.support-tickets.index') }}" method="GET" class="row g-2 align-items-center mb-3">
            <div class="col-auto">
                <label class="fw-semibold text-slate-700 me-2" style="font-size: 13px;">Filter by:</label>
            </div>
            <div class="col-auto">
                <select name="status" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="priority" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="category" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <option value="Order Issues" {{ request('category') === 'Order Issues' ? 'selected' : '' }}>Order Issues</option>
                    <option value="Payment Issues" {{ request('category') === 'Payment Issues' ? 'selected' : '' }}>Payment Issues</option>
                    <option value="Product Quality" {{ request('category') === 'Product Quality' ? 'selected' : '' }}>Product Quality</option>
                    <option value="Vendor Complain" {{ request('category') === 'Vendor Complain' ? 'selected' : '' }}>Vendor Complain</option>
                    <option value="Others" {{ request('category') === 'Others' ? 'selected' : '' }}>Others</option>
                </select>
            </div>
            @if(request()->anyFilled(['status', 'priority', 'category']))
                <div class="col-auto">
                    <a href="{{ route('admin.support-tickets.index') }}" class="btn btn-light btn-sm rounded-3 px-2 py-1 border" style="font-size: 12px;">Reset Filters</a>
                </div>
            @endif
        </form>

        @if($tickets->count() > 0)
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="font-size: 11px; padding: 6px 10px;">Ticket ID</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Customer Name</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Subject</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Category</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Priority</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Status</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Last Activity</th>
                            <th style="font-size: 11px; padding: 6px 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td class="fw-bold text-slate-500" style="font-size: 12.5px; padding: 8px 10px;">#{{ $ticket->id }}</td>
                                <td style="padding: 8px 10px;">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-indigo-50 text-indigo-700 fw-bold rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 26px; height: 26px; font-size: 10px; background-color: #e0e7ff; color: #4338ca; flex-shrink: 0;">
                                            {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-800" style="font-size: 12.5px; line-height: 1.2;">{{ $ticket->user->name }}</div>
                                            <small class="text-slate-500" style="font-size: 11px;">{{ $ticket->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 8px 10px;">
                                    <span class="fw-semibold text-slate-800" style="font-size: 13px;">{{ $ticket->subject }}</span>
                                </td>
                                <td style="padding: 8px 10px;">
                                    <span class="text-slate-600 fw-medium" style="font-size: 12.5px;">{{ $ticket->category }}</span>
                                </td>
                                <td style="padding: 8px 10px;">
                                    <span class="badge-priority priority-{{ $ticket->priority }}" style="font-size: 9.5px; padding: 2px 5px;">{{ $ticket->priority }}</span>
                                </td>
                                <td style="padding: 8px 10px;">
                                    <span class="badge-status status-{{ $ticket->status }}" style="font-size: 9.5px; padding: 3px 6px;">{{ $ticket->status }}</span>
                                </td>
                                <td class="text-slate-500" style="font-size: 12px; padding: 8px 10px;">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td style="padding: 8px 10px;">
                                    <a href="{{ route('admin.support-tickets.show', $ticket->id) }}" class="btn btn-indigo btn-sm shadow-sm text-white px-2 py-1" style="background-color: #4f46e5; border-radius: 6px; font-size: 11.5px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-comment-dots" style="font-size: 11px;"></i> Reply
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-clipboard-question text-slate-300 mb-3" style="font-size: 48px; color: #cbd5e1;"></i>
                <h3 class="h5 fw-bold text-slate-800 mb-1">No Support Tickets Found</h3>
                <p class="text-slate-500" style="max-width: 400px; margin: 0 auto;">There are no complaints or support requests submitted matching the selected filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
