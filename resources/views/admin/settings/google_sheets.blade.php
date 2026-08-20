@extends('layouts.master')
@section('title', 'Google Sheets Synchronization')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Google Sheets Two-Way Sync</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Google Sheets Sync</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-file-excel text-success me-2"></i> Configuration & Credentials</h4>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.google-sheets.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Google Spreadsheet URL or ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="spreadsheet_id" 
                               value="{{ old('spreadsheet_id', $sheetSettings['spreadsheet_id']) }}" 
                               placeholder="e.g. https://docs.google.com/spreadsheets/d/1u09vuETGtxLPjPBujooMfVQpZdI3-B_AlwWzBY15Mvw/edit or 1u09vuETGtxLPjPBujooMfVQpZdI3-B_AlwWzBY15Mvw" required>
                        <div class="form-text">Paste the full Google Sheet share link or spreadsheet ID.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sheet Tab Name</label>
                            <input type="text" class="form-control" name="tab_name" 
                                   value="{{ old('tab_name', $sheetSettings['tab_name']) }}" 
                                   placeholder="Sheet1">
                            <div class="form-text">Name of the tab inside your spreadsheet (Default is <code>Sheet1</code>).</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sync Interval (Minutes)</label>
                            <input type="number" class="form-control" name="sync_frequency" 
                                   value="{{ old('sync_frequency', $sheetSettings['sync_frequency']) }}" 
                                   min="1" max="1440">
                            <div class="form-text">Background auto-sync schedule frequency in minutes.</div>
                        </div>
                    </div>

                    <div class="form-check form-switch form-switch-md mb-4">
                        <input class="form-check-input" type="checkbox" id="autoSyncSwitch" name="auto_sync_enabled" value="1" {{ $sheetSettings['auto_sync_enabled'] ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="autoSyncSwitch">Enable Live Background Sync & Real-Time Stock Updates</label>
                    </div>

                    <hr class="my-4">

                    <h5 class="font-size-15 mb-3"><i class="fas fa-key text-warning me-2"></i> Google Service Account Authentication</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Service Account JSON Key File</label>
                        <input type="file" class="form-control" name="service_account_json_file" accept=".json,.txt">
                        <div class="form-text">Download the Service Account JSON key from Google Cloud Console and upload here.</div>
                    </div>

                    <div class="text-center my-2 text-muted fw-bold">- OR PASTE CREDENTIALS MANUALLY -</div>

                    <div class="mb-3">
                        <label class="form-label">Client Email (Service Account Email)</label>
                        <input type="email" class="form-control" name="client_email" 
                               value="{{ old('client_email', $sheetSettings['client_email']) }}" 
                               placeholder="e.g. inventory-sync@your-project.iam.gserviceaccount.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Private Key</label>
                        <textarea class="form-control" name="private_key" rows="4" placeholder="-----BEGIN PRIVATE KEY-----&#10;...&#10;-----END PRIVATE KEY-----">{{ old('private_key', $sheetSettings['private_key']) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Save Settings
                        </button>
                        <button type="button" class="btn btn-outline-info" id="btnTestConnection">
                            <i class="fas fa-plug me-1"></i> Test Connection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-sync-alt text-primary me-2"></i> Sync Status & Action</h4>

                <div class="mb-3 p-3 bg-light rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge {{ $sheetSettings['auto_sync_enabled'] ? 'bg-success' : 'bg-secondary' }}">
                            {{ $sheetSettings['auto_sync_enabled'] ? 'Active (Auto-Sync On)' : 'Manual Only' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Last Synced:</span>
                        <span class="fw-bold">{{ $sheetSettings['last_synced_at'] }}</span>
                    </div>
                    @if($sheetSettings['last_sync_result'])
                        <div class="mt-2 text-muted small">
                            <strong>Last Log:</strong> {{ $sheetSettings['last_sync_result'] }}
                        </div>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="btnSyncNow">
                        <i class="fas fa-cloud-download-alt me-1"></i> Pull From Sheet (Import / Update)
                    </button>
                    <button type="button" class="btn btn-primary" id="btnPushToSheet">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Push All Soft Products to Sheet
                    </button>
                </div>

                <div id="syncResultAlert" class="mt-3" style="display:none;"></div>

                <div class="card border mt-4 shadow-sm" style="border-radius: 12px; border-color: #e2e8f0;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-book-open text-primary me-2"></i> Complete Setup Instructions</h6>
                        
                        <div class="small">
                            <div class="mb-3">
                                <span class="badge bg-primary me-1">Step 1</span> <strong>Go to Google Cloud Console</strong>
                                <ul class="text-muted ps-3 mt-1 mb-0">
                                    <li>Open <a href="https://console.cloud.google.com/" target="_blank" class="text-decoration-underline fw-bold">Google Cloud Console</a>.</li>
                                    <li>Select or create a new project (e.g. <code>My Store Sync</code>).</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-primary me-1">Step 2</span> <strong>Enable Google Sheets API</strong>
                                <ul class="text-muted ps-3 mt-1 mb-0">
                                    <li>In the top search bar, search for <strong>Google Sheets API</strong>.</li>
                                    <li>Click on it and click the blue <strong>Enable</strong> button.</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-primary me-1">Step 3</span> <strong>Create Service Account & Download Key</strong>
                                <ul class="text-muted ps-3 mt-1 mb-0">
                                    <li>Go to <strong>APIs & Services</strong> &rarr; <strong>Credentials</strong> (or <strong>IAM & Admin &rarr; Service Accounts</strong>).</li>
                                    <li>Click <strong>Create Credentials</strong> &rarr; <strong>Service Account</strong>.</li>
                                    <li>Enter a name (e.g. <code>sheets-sync</code>) and click <strong>Create and Continue</strong> &rarr; <strong>Done</strong>.</li>
                                    <li>Click on the created Service Account email &rarr; Go to <strong>Keys</strong> tab.</li>
                                    <li>Click <strong>Add Key</strong> &rarr; <strong>Create new key</strong> &rarr; Choose <strong>JSON</strong> &rarr; <strong>Create</strong>. (A <code>.json</code> file will download).</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-primary me-1">Step 4</span> <strong>Share Spreadsheet with Service Account</strong>
                                <ul class="text-muted ps-3 mt-1 mb-0">
                                    <li>Open your Google Sheet.</li>
                                    <li>Click the top right <strong>Share</strong> button.</li>
                                    <li>Paste your <strong>Service Account Email</strong> (e.g. <code>sheets-sync@...iam.gserviceaccount.com</code>).</li>
                                    <li>Set permission to <strong>Editor</strong> and click <strong>Share / Done</strong>.</li>
                                </ul>
                            </div>

                            <div>
                                <span class="badge bg-success me-1">Step 5</span> <strong>Upload Key & Sync</strong>
                                <ul class="text-muted ps-3 mt-1 mb-0">
                                    <li>Paste your Google Sheet link/ID on the left.</li>
                                    <li>Choose the downloaded <code>.json</code> key file in <strong>Upload Service Account JSON Key File</strong>.</li>
                                    <li>Check <strong>Enable Live Background Sync</strong> & click <strong>Save Settings</strong>.</li>
                                    <li>Click <strong>Test Connection</strong> to verify, then <strong>Sync Now</strong>!</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnTestConnection').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Testing...');

        $.ajax({
            url: "{{ route('admin.google-sheets.test') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                spreadsheet_id: $('input[name="spreadsheet_id"]').val(),
                tab_name: $('input[name="tab_name"]').val()
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-plug me-1"></i> Test Connection');
                alert(res.message);
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-plug me-1"></i> Test Connection');
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Connection test failed';
                alert('Error: ' + msg);
            }
        });
    });

    $('#btnSyncNow').on('click', function() {
        const btn = $(this);
        const alertBox = $('#syncResultAlert');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Pulling from Sheet...');
        alertBox.hide();

        $.ajax({
            url: "{{ route('admin.google-sheets.sync-now') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-cloud-download-alt me-1"></i> Pull From Sheet (Import / Update)');
                alertBox.removeClass('alert-danger').addClass('alert alert-success').html('<i class="fas fa-check-circle me-1"></i> ' + res.message).show();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-cloud-download-alt me-1"></i> Pull From Sheet (Import / Update)');
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Sync failed';
                alertBox.removeClass('alert-success').addClass('alert alert-danger').html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg).show();
            }
        });
    });

    $('#btnPushToSheet').on('click', function() {
        if (!confirm('This will update/export all software products and stocks directly into your Google Sheet. Continue?')) {
            return;
        }

        const btn = $(this);
        const alertBox = $('#syncResultAlert');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Pushing to Sheet...');
        alertBox.hide();

        $.ajax({
            url: "{{ route('admin.google-sheets.push-to-sheet') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-cloud-upload-alt me-1"></i> Push All Soft Products to Sheet');
                alertBox.removeClass('alert-danger').addClass('alert alert-success').html('<i class="fas fa-check-circle me-1"></i> ' + res.message).show();
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-cloud-upload-alt me-1"></i> Push All Soft Products to Sheet');
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Push failed';
                alertBox.removeClass('alert-success').addClass('alert alert-danger').html('<i class="fas fa-exclamation-triangle me-1"></i> ' + msg).show();
            }
        });
    });
});
</script>
@endpush
