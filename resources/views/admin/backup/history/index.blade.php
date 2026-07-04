@extends('layouts.master')

@section('title', 'Backup History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4><i class="fas fa-history"></i> Backup History</h4>
                    <p class="text-muted">View and manage all backup executions</p>
                </div>
                <div>
                    <a href="{{ route('admin.backup.settings') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('admin.backup.schedules.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-calendar"></i> Schedules
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

            {{-- Backup History Table --}}
            <div class="card">
                <div class="card-body">
                    @if($backups->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Storage</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>Size</th>
                                        <th>Duration</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>#{{ $backup->id }}</td>
                                            <td>
                                                @if($backup->backup_type === 'database')
                                                    <span class="badge bg-info"><i class="fas fa-database"></i> Database</span>
                                                @elseif($backup->backup_type === 'files')
                                                    <span class="badge bg-secondary"><i class="fas fa-folder"></i> Files</span>
                                                @else
                                                    <span class="badge bg-primary"><i class="fas fa-archive"></i> Both</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($backup->storage_destination === 'local')
                                                    <span class="badge bg-secondary"><i class="fas fa-server"></i> Local</span>
                                                @elseif($backup->storage_destination === 'google_drive')
                                                    <span class="badge bg-success"><i class="fab fa-google-drive"></i> Google Drive</span>
                                                @else
                                                    <span class="badge bg-primary"><i class="fas fa-cloud"></i> Both</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($backup->schedule)
                                                    <a href="{{ route('admin.backup.schedules.edit', $backup->schedule_id) }}">
                                                        {{ $backup->schedule->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Manual</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($backup->status === 'success')
                                                    <span class="badge bg-success"><i class="fas fa-check"></i> Success</span>
                                                @elseif($backup->status === 'failed')
                                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Failed</span>
                                                    @if($backup->error_message)
                                                        <br><small class="text-danger" title="{{ $backup->error_message }}">
                                                            {{ Str::limit($backup->error_message, 30) }}
                                                        </small>
                                                    @endif
                                                @elseif($backup->status === 'running')
                                                    <span class="badge bg-warning"><i class="fas fa-spinner fa-spin"></i> Running</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($backup->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($backup->file_size)
                                                    {{ $backup->file_size_human }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($backup->duration_seconds)
                                                    {{ gmdate('H:i:s', $backup->duration_seconds) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $backup->created_at->diffForHumans() }}<br>
                                                <small class="text-muted">{{ $backup->created_at->format('Y-m-d H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @can('backup.download')
                                                        @if($backup->canDownload())
                                                            <a href="{{ route('admin.backup.history.download', $backup->id) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Download not available">
                                                                <i class="fas fa-download"></i>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="You do not have permission to download">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                    @endcan
                                                    @can('backup.restore')
                                                    @if($backup->canRestore())
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#restoreModal{{ $backup->id }}" 
                                                            title="Restore">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    @endif
                                                    @endcan
                                                    @can('backup.history.delete')
                                                    <form action="{{ route('admin.backup.history.destroy', $backup->id) }}" method="POST" class="d-inline" 
                                                        onsubmit="return confirm('Are you sure you want to delete this backup?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>

                                                {{-- Restore Modal --}}
                                                @can('backup.restore')
                                                @if($backup->canRestore())
                                                <div class="modal fade" id="restoreModal{{ $backup->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('admin.backup.history.restore', $backup->id) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Restore Backup</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-warning">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        <strong>Warning:</strong> Restoring will overwrite your current database and/or files. This action cannot be undone!
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Restore Type</label>
                                                                        <select name="type" class="form-select">
                                                                            @if($backup->backup_type === 'both')
                                                                                <option value="database">Database Only</option>
                                                                                <option value="files">Files Only</option>
                                                                                <option value="both">Both (Database + Files)</option>
                                                                            @else
                                                                                <option value="{{ $backup->backup_type }}">{{ ucfirst($backup->backup_type) }}</option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" name="confirm" id="confirm{{ $backup->id }}" value="1" required>
                                                                        <label class="form-check-label" for="confirm{{ $backup->id }}">
                                                                            I understand this will overwrite my current data
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-undo"></i> Restore Backup
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $backups->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No backup history found.</p>
                            <a href="{{ route('admin.backup.settings') }}" class="btn btn-primary">
                                <i class="fas fa-play"></i> Create Backup
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

