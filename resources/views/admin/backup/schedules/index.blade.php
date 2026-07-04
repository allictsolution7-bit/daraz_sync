@extends('layouts.master')

@section('title', 'Backup Schedules')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4><i class="fas fa-calendar-alt"></i> Backup Schedules</h4>
                    <p class="text-muted">Manage automated backup schedules</p>
                </div>
                <div>
                    @can('backup.schedules.create')
                    <a href="{{ route('admin.backup.schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Schedule
                    </a>
                    @endcan
                    <a href="{{ route('admin.backup.settings') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('admin.backup.history.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history"></i> History
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
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

            {{-- Schedules Table --}}
            <div class="card">
                <div class="card-body">
                    @if($schedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Storage</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Last Run</th>
                                        <th>Next Run</th>
                                        <th>Stats</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $schedule)
                                        <tr>
                                            <td>
                                                <strong>{{ $schedule->name }}</strong>
                                                @if($schedule->cron_description)
                                                    <br><small class="text-muted">{{ $schedule->cron_description }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($schedule->backup_type === 'database')
                                                    <span class="badge bg-info"><i class="fas fa-database"></i> Database</span>
                                                @elseif($schedule->backup_type === 'files')
                                                    <span class="badge bg-secondary"><i class="fas fa-folder"></i> Files</span>
                                                @else
                                                    <span class="badge bg-primary"><i class="fas fa-archive"></i> Both</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($schedule->storage_destination === 'local')
                                                    <span class="badge bg-secondary"><i class="fas fa-server"></i> Local</span>
                                                @elseif($schedule->storage_destination === 'google_drive')
                                                    <span class="badge bg-success"><i class="fab fa-google-drive"></i> Google Drive</span>
                                                @else
                                                    <span class="badge bg-primary"><i class="fas fa-cloud"></i> Both</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $schedule->cron_expression }}</code>
                                            </td>
                                            <td>
                                                @if($schedule->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                                @if($schedule->last_status === 'running')
                                                    <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Running</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($schedule->last_run_at)
                                                    {{ $schedule->last_run_at->diffForHumans() }}
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($schedule->next_run_at)
                                                    {{ $schedule->next_run_at->diffForHumans() }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="fas fa-check text-success"></i> {{ $schedule->success_count }}<br>
                                                    <i class="fas fa-times text-danger"></i> {{ $schedule->failure_count }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @can('backup.schedules.run')
                                                    <form action="{{ route('admin.backup.schedules.run-now', $schedule->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Run Now">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                    @can('backup.schedules.edit')
                                                    <a href="{{ route('admin.backup.schedules.edit', $schedule->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @endcan
                                                    @can('backup.schedules.edit')
                                                    <form action="{{ route('admin.backup.schedules.toggle', $schedule->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $schedule->is_active ? 'warning' : 'success' }}" title="{{ $schedule->is_active ? 'Disable' : 'Enable' }}">
                                                            <i class="fas fa-{{ $schedule->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                    @can('backup.schedules.delete')
                                                    <form action="{{ route('admin.backup.schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No backup schedules found.</p>
                            @can('backup.schedules.create')
                            <a href="{{ route('admin.backup.schedules.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Schedule
                            </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

