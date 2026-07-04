@extends('layouts.master')

@section('title', 'Add Daraz Store')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-plus-circle"></i> Add Daraz Store</h4>
            <p class="text-muted mb-0">Connect a new Daraz seller account</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Stores
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Store Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.stores.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g., My Daraz BD Store"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">A friendly name to identify this store</small>
                        </div>

                        <div class="mb-3">
                            <label for="country_code" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select @error('country_code') is-invalid @enderror"
                                    id="country_code"
                                    name="country_code"
                                    required>
                                <option value="">Select Country</option>
                                @foreach(config('daraz.countries') as $code => $country)
                                    <option value="{{ $code }}" {{ old('country_code') === $code ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">API Credentials</h6>
                        <p class="text-muted small mb-3">
                            Get your API credentials from <a href="https://open.daraz.com" target="_blank">Daraz Open Platform</a>.
                            Create an application and use the App Key and App Secret.
                        </p>

                        <div class="mb-3">
                            <label for="app_key" class="form-label">App Key <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('app_key') is-invalid @enderror"
                                   id="app_key"
                                   name="app_key"
                                   value="{{ old('app_key') }}"
                                   placeholder="Your Daraz App Key"
                                   required>
                            @error('app_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="app_secret" class="form-label">App Secret <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('app_secret') is-invalid @enderror"
                                       id="app_secret"
                                       name="app_secret"
                                       placeholder="Your Daraz App Secret"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('app_secret')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('app_secret')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">This will be encrypted before storing</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Sync Settings</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="auto_sync" name="auto_sync" value="1"
                                               {{ old('auto_sync', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_sync">Enable Auto-Sync</label>
                                    </div>
                                    <small class="text-muted">Automatically sync stock at regular intervals</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sync_interval" class="form-label">Sync Interval (minutes)</label>
                                    <select class="form-select" id="sync_interval" name="sync_interval">
                                        <option value="15" {{ old('sync_interval', 30) == 15 ? 'selected' : '' }}>Every 15 minutes</option>
                                        <option value="30" {{ old('sync_interval', 30) == 30 ? 'selected' : '' }}>Every 30 minutes</option>
                                        <option value="60" {{ old('sync_interval', 30) == 60 ? 'selected' : '' }}>Every hour</option>
                                        <option value="120" {{ old('sync_interval', 30) == 120 ? 'selected' : '' }}>Every 2 hours</option>
                                        <option value="360" {{ old('sync_interval', 30) == 360 ? 'selected' : '' }}>Every 6 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Store
                            </button>
                            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> How to Get API Credentials</h6>
                </div>
                <div class="card-body">
                    <ol class="small mb-0">
                        <li class="mb-2">Go to <a href="https://open.daraz.com" target="_blank">Daraz Open Platform</a></li>
                        <li class="mb-2">Log in with your seller account</li>
                        <li class="mb-2">Navigate to "Console" > "App Management"</li>
                        <li class="mb-2">Create a new application or use existing one</li>
                        <li class="mb-2">Copy the App Key and App Secret</li>
                        <li class="mb-2">After saving, click "Authorize" to connect</li>
                    </ol>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-globe"></i> Supported Countries</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        @foreach(config('daraz.countries') as $code => $country)
                            <li class="mb-1">
                                <strong>{{ $code }}</strong> - {{ $country['name'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
