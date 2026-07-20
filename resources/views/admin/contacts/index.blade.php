@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Modern Premium Portal Design System */
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-slate: #1e293b;
            --text-main: #334155;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(226, 232, 240, 0.8);
            --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        /* Stat Cards */
        .stat-card-premium {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
        }

        .stat-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 90% 10%, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .stat-card-premium:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        .stat-card-premium.gradient-1 {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-2 {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-3 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.85;
            margin-bottom: 6px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card-icon {
            position: absolute;
            right: 20px;
            bottom: 15px;
            font-size: 4rem;
            opacity: 0.15;
            pointer-events: none;
        }

        /* Workspace Cards */
        .workspace-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            padding: 24px;
            margin-bottom: 40px;
        }

        .premium-actions-bar {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: var(--shadow-premium);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .premium-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
        }

        .premium-table thead th {
            background-color: #f1f5f9 !important;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 14px 18px !important;
            border: none !important;
        }

        .premium-table tbody tr {
            background-color: #ffffff;
            transition: var(--transition-smooth);
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.01);
        }

        .premium-table tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8) !important;
        }

        .premium-table tbody td {
            padding: 16px 18px !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
            color: var(--text-main);
        }

        .premium-table tbody td:first-child {
            border-left: 1px solid #f1f5f9 !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .premium-table tbody td:last-child {
            border-right: 1px solid #f1f5f9 !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Custom Badges */
        .badge-premium {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-premium-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-premium-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* Action Buttons */
        .btn-action-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: var(--text-muted);
        }

        .btn-action-circle:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-action-view:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-action-delete:hover {
            background-color: var(--danger);
            border-color: var(--danger);
        }

        /* Pagination custom wrapper */
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .pagination-wrapper .pagination {
            gap: 5px;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid px-4 pt-3">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">Inbox Messages</li>
        </ol>
    </nav>

    <!-- Dynamic Success Alert -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- KPI Statistics Counters -->
    @php
        $totalMessagesCount = $messages->total();
        $unreadMessagesCount = $messages->filter(fn($m) => !$m->is_read)->count();
        $readMessagesCount = $messages->filter(fn($m) => $m->is_read)->count();
    @endphp
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="stat-card-premium gradient-1">
                <div>
                    <div class="stat-card-title">Total Messages</div>
                    <div class="stat-card-value">{{ $totalMessagesCount }}</div>
                </div>
                <i class="fas fa-envelope-open-text stat-card-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium gradient-2">
                <div>
                    <div class="stat-card-title">Unread (Current Page)</div>
                    <div class="stat-card-value">{{ $unreadMessagesCount }}</div>
                </div>
                <i class="fas fa-envelope stat-card-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium gradient-3">
                <div>
                    <div class="stat-card-title">Read (Current Page)</div>
                    <div class="stat-card-value">{{ $readMessagesCount }}</div>
                </div>
                <i class="fas fa-envelope-open stat-card-icon"></i>
            </div>
        </div>
    </div>

    <!-- Table Workspace Card -->
    <div class="workspace-card">
        <div class="premium-actions-bar">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 font-weight-bold" style="color: var(--dark-slate);">
                    {{ request()->routeIs('admin.contacts.unread') ? 'Unread Messages Log' : 'All Contacts Messages' }}
                </h5>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table premium-table">
                <thead>
                    <tr>
                        <th>Sender Name</th>
                        <th>Email Address</th>
                        <th>Message Subject</th>
                        <th>Date Received</th>
                        <th>Read Status</th>
                        <th style="text-align: center; width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr>
                        <td><strong>{{ $message->name }}</strong></td>
                        <td><span class="text-muted">{{ $message->email }}</span></td>
                        <td>{{ Str::limit($message->subject, 60) }}</td>
                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            @if($message->is_read)
                                <span class="badge-premium badge-premium-success">
                                    <i class="fas fa-check-circle"></i> Read
                                </span>
                            @else
                                <span class="badge-premium badge-premium-warning animate-pulse">
                                    <i class="fas fa-envelope"></i> Unread
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.contacts.show', $message) }}" class="btn-action-circle btn-action-view" title="Read Message">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-circle btn-action-delete" title="Delete Message" onclick="return confirm('Are you sure you want to delete this message?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3" style="opacity: 0.3;"></i>
                            <h5>No messages found</h5>
                            <p class="mb-0">Your contact form inbox is currently empty.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom styled pagination -->
        <div class="pagination-wrapper">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection