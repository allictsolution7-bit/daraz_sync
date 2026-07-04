@extends('layouts.master')

@section('title', 'Create Backup Schedule')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4><i class="fas fa-plus-circle"></i> Create Backup Schedule</h4>
                    <p class="text-muted">Set up an automated backup schedule</p>
                </div>
                <a href="{{ route('admin.backup.schedules.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Schedules
                </a>
            </div>

            <form action="{{ route('admin.backup.schedules.store') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Schedule Name *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">A descriptive name for this schedule</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <small class="text-muted">Enable or disable this schedule</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Backup Type *</label>
                                <select name="backup_type" class="form-select @error('backup_type') is-invalid @enderror" required>
                                    <option value="database" {{ old('backup_type') === 'database' ? 'selected' : '' }}>Database Only</option>
                                    <option value="files" {{ old('backup_type') === 'files' ? 'selected' : '' }}>Files Only</option>
                                    <option value="both" {{ old('backup_type') === 'both' ? 'selected' : '' }}>Both (Database + Files)</option>
                                </select>
                                @error('backup_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Storage Destination *</label>
                                <select name="storage_destination" class="form-select @error('storage_destination') is-invalid @enderror" required>
                                    <option value="local" {{ old('storage_destination') === 'local' ? 'selected' : '' }}>Local Storage</option>
                                    <option value="google_drive" {{ old('storage_destination') === 'google_drive' ? 'selected' : '' }}>Google Drive</option>
                                    <option value="both" {{ old('storage_destination') === 'both' ? 'selected' : '' }}>Both (Local + Google Drive)</option>
                                </select>
                                @error('storage_destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Cron Expression *</label>
                                <input type="text" name="cron_expression" class="form-control @error('cron_expression') is-invalid @enderror" 
                                    value="{{ old('cron_expression', '0 2 * * *') }}" placeholder="0 2 * * *" required>
                                @error('cron_expression')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Format: minute hour day month weekday<br>
                                    Examples: <code>0 2 * * *</code> (Daily at 2 AM), <code>0 0 * * 0</code> (Weekly on Sunday)
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <input type="text" name="cron_description" class="form-control" 
                                    value="{{ old('cron_description') }}" placeholder="e.g., Daily at 2 AM">
                                <small class="text-muted">Human-readable description of the schedule</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Schedule
                            </button>
                            <a href="{{ route('admin.backup.schedules.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

