@extends('layouts.master')

@section('title', 'Backup Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4><i class="fas fa-database"></i> Backup Settings</h4>
                        <p class="text-muted">Configure backup storage, schedules, and retention policies</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.backup.schedules.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar"></i> Schedules
                        </a>
                        <a href="{{ route('admin.backup.history.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history"></i> History
                        </a>
                    </div>
                </div>

                {{-- Success/Error Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Validation Errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            {{-- Manual Backup Section --}}
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-play"></i> Manual Backup</h5>
                        <small class="text-white-50">Create a backup immediately</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Backup Type</label>
                                <select name="manual_backup_type" id="manual_backup_type" class="form-select">
                                    <option value="database">Database Only</option>
                                    {{-- <option value="files">Files Only</option>
                                    <option value="both">Both (Database + Files)</option> --}}
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Storage</label>
                                <select name="manual_backup_storage" id="manual_backup_storage" class="form-select">
                                    <option value="local">Local</option>
                                    <option value="google_drive">Google Drive</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" id="run-manual-backup">
                            <i class="fas fa-play"></i> Create Backup Now
                        </button>
                        <small class="text-muted d-block mt-2">Configure Google Drive credentials in the Backup Configuration section below before using Drive.</small>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary"></i> Helpful Shortcuts</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.backup.history.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-history"></i> View Backup History
                            </a>
                            <a href="{{ route('admin.backup.schedules.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-calendar-alt"></i> Manage Schedules
                            </a>
                            <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="fab fa-google"></i> Google Cloud Console
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Backup Configuration Form --}}
            <div class="col-12">
                <form id="settingsForm" action="{{ route('admin.backup.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-hdd"></i> Storage Configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Storage Destination</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="storage_destination"
                                            id="storage_local" value="local"
                                            {{ \App\Models\BackupSetting::get('storage_destination', 'local') === 'local' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="storage_local">
                                            <i class="fas fa-server"></i> Local Storage
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="storage_destination"
                                            id="storage_google" value="google_drive"
                                            {{ \App\Models\BackupSetting::get('storage_destination') === 'google_drive' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="storage_google">
                                            <i class="fab fa-google-drive"></i> Google Drive
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="storage_destination"
                                            id="storage_both" value="both"
                                            {{ \App\Models\BackupSetting::get('storage_destination') === 'both' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="storage_both">
                                            <i class="fas fa-cloud"></i> Both (Local + Google Drive)
                                        </label>
                                    </div>
                                </div>
                                <small class="text-muted">Choose where backups should be stored</small>
                            </div>

                            @php
                                $googleDriveClientId = \App\Models\BackupSetting::get('google_drive_client_id');
                                $googleDriveSecretValue = \App\Models\BackupSetting::get('google_drive_client_secret');
                                $hasGoogleDriveSecret = !empty($googleDriveSecretValue);
                                $googleDriveFolderId = \App\Models\BackupSetting::get('google_drive_folder_id');
                                $maskedSecret = $hasGoogleDriveSecret
                                    ? str_repeat('•', max(10, strlen($googleDriveSecretValue)))
                                    : '';
                            @endphp

                            <div class="border rounded p-3 mb-3" id="google-drive-section" style="display: none;">
                                <h6 class="mb-3"><i class="fab fa-google-drive"></i> Google Drive Connection</h6>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Client ID <span class="text-danger">*</span></label>
                                    <input type="text" name="google_drive_client_id"
                                        class="form-control @error('google_drive_client_id') is-invalid @enderror"
                                        value="{{ old('google_drive_client_id', $googleDriveClientId) }}"
                                        placeholder="Enter Google OAuth Client ID">
                                    <small class="text-muted">Create OAuth credentials in Google Cloud Console (Application
                                        type: Web application)</small>
                                    @error('google_drive_client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Client Secret <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="google_drive_client_secret"
                                        class="form-control @error('google_drive_client_secret') is-invalid @enderror"
                                        placeholder="{{ $hasGoogleDriveSecret ? 'Enter a new secret to replace the saved one (optional)' : 'Enter Google OAuth Client Secret' }}">
                                    <small class="text-muted d-block">
                                        {{ $hasGoogleDriveSecret ? 'Leave blank to keep the existing secret. Enter a new one to replace it.' : 'Paste the OAuth client secret from Google Cloud Console.' }}
                                    </small>
                                    @error('google_drive_client_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($hasGoogleDriveSecret)
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Saved Secret</label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" class="form-control secret-display"
                                                    value="{{ $maskedSecret }}" readonly
                                                    data-secret="{{ $googleDriveSecretValue }}"
                                                    data-mask="{{ $maskedSecret }}">
                                                <button type="button"
                                                    class="btn btn-outline-secondary toggle-secret-view"
                                                    title="Show / Hide"><i class="fas fa-eye"></i></button>
                                                <button type="button" class="btn btn-outline-secondary copy-secret-btn"
                                                    title="Copy"><i class="fas fa-copy"></i></button>
                                            </div>
                                            <small class="text-muted">Use the eye icon to view the stored secret or the
                                                copy icon to copy it.</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Folder ID (optional)</label>
                                    <input type="text" name="google_drive_folder_id"
                                        class="form-control @error('google_drive_folder_id') is-invalid @enderror"
                                        value="{{ old('google_drive_folder_id', $googleDriveFolderId) }}"
                                        placeholder="Google Drive folder ID (leave empty to use your Drive root)">
                                    <small class="text-muted">Open the folder in Google Drive and copy the ID from the
                                        URL.</small>
                                    @error('google_drive_folder_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <strong>Redirect URI:</strong>
                                    <code>{{ url('/admin/backup/google-drive/callback') }}</code>
                                    <br>
                                    Add this URI to your OAuth client in Google Cloud Console.
                                </div>

                                @if (\App\Models\BackupSetting::isGoogleDriveEnabled())
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i> Google Drive is connected
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.backup.google-drive.test') }}"
                                            class="btn btn-sm btn-outline-primary" id="test-connection">
                                            <i class="fas fa-vial"></i> Test Connection
                                        </a>
                                        <form action="{{ route('admin.backup.google-drive.disconnect') }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to disconnect Google Drive?')">
                                                <i class="fas fa-unlink"></i> Disconnect
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Google Drive is not connected
                                    </div>
                                    <a href="{{ route('admin.backup.google-drive.connect') }}" class="btn btn-primary">
                                        <i class="fab fa-google"></i> Connect Google Drive
                                    </a>
                                    <small class="d-block text-muted mt-2">
                                        Save your Client ID & Secret above, then click Connect to authorize access.
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-cog"></i> Backup Options</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="backup_db_enabled"
                                            id="backup_db_enabled" value="1"
                                            {{ \App\Models\BackupSetting::get('backup_db_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="backup_db_enabled">
                                            Enable Database Backup
                                        </label>
                                    </div>
                                    <small class="text-muted">Backup database when creating backups</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="backup_files_enabled"
                                            id="backup_files_enabled" value="1"
                                            {{ \App\Models\BackupSetting::get('backup_files_enabled', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="backup_files_enabled">
                                            Enable Files Backup
                                        </label>
                                    </div>
                                    <small class="text-muted">Backup application files when creating backups</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-trash-alt"></i> Retention Policy</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Retention Days</label>
                                    <input type="number" name="retention_days" class="form-control"
                                        value="{{ \App\Models\BackupSetting::get('retention_days', 30) }}" min="1"
                                        max="365" required>
                                    <small class="text-muted">Delete backups older than this many days</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Retention Count</label>
                                    <input type="number" name="retention_count" class="form-control"
                                        value="{{ \App\Models\BackupSetting::get('retention_count', 10) }}"
                                        min="1" max="100" required>
                                    <small class="text-muted">Keep only the most recent N backups</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-bell"></i> Notification Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notification Email</label>
                                <input type="email" name="notification_email" class="form-control"
                                    value="{{ \App\Models\BackupSetting::get('notification_email', '') }}">
                                <small class="text-muted">Email address to receive backup notifications</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="notification_on_success"
                                            id="notification_on_success" value="1"
                                            {{ \App\Models\BackupSetting::get('notification_on_success', false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notification_on_success">
                                            Notify on Success
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="notification_on_failure"
                                            id="notification_on_failure" value="1"
                                            {{ \App\Models\BackupSetting::get('notification_on_failure', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="notification_on_failure">
                                            Notify on Failure
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary" onclick="console.log('Save Settings button clicked');">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Backup Settings page loaded, form handling initialized');
        // Show/hide Google Drive section based on storage selection
        const storageRadios = document.querySelectorAll('input[name="storage_destination"]');
        const googleDriveSection = document.getElementById('google-drive-section');

        function toggleGoogleDriveSection() {
            const selected = document.querySelector('input[name="storage_destination"]:checked').value;
            if (selected === 'google_drive' || selected === 'both') {
                googleDriveSection.style.display = 'block';
            } else {
                googleDriveSection.style.display = 'none';
            }
        }

        storageRadios.forEach(radio => {
            radio.addEventListener('change', toggleGoogleDriveSection);
        });
        if (storageRadios.length) {
            toggleGoogleDriveSection();
        }

        // Secret visibility toggles
        document.querySelectorAll('.toggle-secret-view').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('.secret-display');
                const currentlyPassword = input.type === 'password';

                if (currentlyPassword) {
                    input.type = 'text';
                    input.value = input.dataset.secret;
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    input.value = input.dataset.mask;
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });

        // Copy secret buttons
        document.querySelectorAll('.copy-secret-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('.secret-display');
                const secret = input.dataset.secret;

                navigator.clipboard.writeText(secret).then(() => {
                    alert('Secret copied to clipboard');
                }).catch(() => {
                    alert('Unable to copy secret. Please copy manually.');
                });
            });
        });

        // Manual backup
        const manualBackupBtn = document.getElementById('run-manual-backup');
        if (manualBackupBtn) {
            manualBackupBtn.addEventListener('click', function() {
                const type = document.getElementById('manual_backup_type').value;
                const storage = document.getElementById('manual_backup_storage').value;

                if (!confirm('Are you sure you want to create a backup now? This may take a few minutes.')) {
                    return;
                }

                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Backup...';

                fetch('{{ route('admin.backup.run') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            backup_type: type,
                            storage_destination: storage
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Backup started successfully! Check the history page for status.');
                            window.location.href = '{{ route('admin.backup.history.index') }}';
                        } else {
                            alert('Backup failed: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('An error occurred: ' + error);
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-play"></i> Create Backup Now';
                    });
            });
        }

        // Test Google Drive connection
        const testBtn = document.getElementById('test-connection');
        if (testBtn) {
            testBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

                fetch('{{ route('admin.backup.google-drive.test') }}')
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => {
                        alert('Test failed: ' + error);
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-vial"></i> Test Connection';
                    });
            });
        }
    });
</script>
@endsection
