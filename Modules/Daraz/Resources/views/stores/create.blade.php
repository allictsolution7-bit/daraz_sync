@extends('layouts.master')

@section('title', 'Add Daraz Store')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .daraz-create-dashboard {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 18px;
            padding: 6px;
        }
        .premium-card {
            border: 1px solid #f1f5f9 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
            background: #fff;
            overflow: hidden;
        }
        .premium-card .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem 1.5rem !important;
        }
        .premium-card .card-header h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
        }
        .premium-card .card-body {
            padding: 1.5rem !important;
        }
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 10px 14px !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .form-label {
            font-weight: 700;
            color: #475569;
            font-size: 0.8rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 0.88rem !important;
        }
        .setup-step-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .setup-step-num {
            width: 24px;
            height: 24px;
            background: #eff6ff;
            color: #3b82f6;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.72rem;
            margin-right: 12px;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }
        .setup-step-text {
            font-size: 0.82rem;
            color: #475569;
            line-height: 1.5;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-create-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-plus-circle me-1 text-primary"></i> Register Outlet Integration</h4>
            <p class="text-muted small mb-0">Establish connection with a new seller node</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-sm btn-outline-secondary" style="padding: 6px 14px !important;">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card premium-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Outlet Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.stores.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Integration Identifier <span class="text-danger">*</span></label>
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
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">A custom name to label this connection</div>
                        </div>

                        <div class="mb-4">
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

                        <hr class="my-4" style="border-color: #e2e8f0;">
                        <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">API Credentials</h6>
                        <p class="text-muted small mb-3">
                            Retrieve keys from the <a href="https://open.daraz.com" target="_blank" class="text-decoration-none fw-bold text-primary">Marketplace Open Developer Portal</a>.
                            Create a sandbox/production application to fetch integration codes.
                        </p>

                        <div class="mb-4">
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

                        <div class="mb-4">
                            <label for="app_secret" class="form-label">App Secret <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('app_secret') is-invalid @enderror"
                                       id="app_secret"
                                       name="app_secret"
                                       placeholder="Your Daraz App Secret"
                                       required>
                                <button class="btn btn-outline-secondary d-flex align-items-center" type="button" onclick="togglePassword('app_secret')" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important; background: #f8fafc; border-color: #cbd5e1 !important; color: #475569;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('app_secret')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="text-muted small mt-1" style="font-size: 0.75rem;">Security credentials are securely hashed prior to storage</div>
                        </div>

                        <hr class="my-4" style="border-color: #e2e8f0;">
                        <h6 class="fw-bold mb-3 text-dark" style="font-size: 0.88rem;">Sync Settings</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="auto_sync" name="auto_sync" value="1"
                                               {{ old('auto_sync', true) ? 'checked' : '' }}
                                               style="cursor: pointer;">
                                        <label class="form-check-label fw-bold text-dark" for="auto_sync" style="font-size: 0.85rem; cursor: pointer;">Enable Auto-Sync</label>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">Automatically run inventory updates at scheduled intervals</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sync_interval" class="form-label">Sync Interval</label>
                                    <select class="form-select" id="sync_interval" name="sync_interval">
                                        <option value="2" {{ old('sync_interval', 30) == 2 ? 'selected' : '' }}>Every 2 minutes</option>
                                        <option value="5" {{ old('sync_interval', 30) == 5 ? 'selected' : '' }}>Every 5 minutes</option>
                                        <option value="10" {{ old('sync_interval', 30) == 10 ? 'selected' : '' }}>Every 10 minutes</option>
                                        <option value="15" {{ old('sync_interval', 30) == 15 ? 'selected' : '' }}>Every 15 minutes</option>
                                        <option value="30" {{ old('sync_interval', 30) == 30 ? 'selected' : '' }}>Every 30 minutes</option>
                                        <option value="60" {{ old('sync_interval', 30) == 60 ? 'selected' : '' }}>Every hour</option>
                                        <option value="120" {{ old('sync_interval', 30) == 120 ? 'selected' : '' }}>Every 2 hours</option>
                                        <option value="360" {{ old('sync_interval', 30) == 360 ? 'selected' : '' }}>Every 6 hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4 pt-3 border-top" style="border-color: #e2e8f0 !important;">
                            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" style="background: #3b82f6; border-color: #3b82f6;">
                                <i class="fas fa-save"></i> Initialize Integration
                            </button>
                            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card premium-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-1"></i> Setup Guide</h5>
                </div>
                <div class="card-body">
                    <div class="setup-step-row">
                        <div class="setup-step-num">1</div>
                        <div class="setup-step-text">Go to the <a href="https://open.daraz.com" target="_blank" class="fw-bold text-decoration-none">Daraz Open Platform</a>.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">2</div>
                        <div class="setup-step-text">Log in with your seller account credentials.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">3</div>
                        <div class="setup-step-text">Navigate to <strong>Console &gt; App Management</strong>.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">4</div>
                        <div class="setup-step-text">Create a new application or configure an existing one.</div>
                    </div>
                    <div class="setup-step-row">
                        <div class="setup-step-num">5</div>
                        <div class="setup-step-text">Copy the App Key and App Secret here.</div>
                    </div>
                    <div class="setup-step-row mb-0">
                        <div class="setup-step-num">6</div>
                        <div class="setup-step-text">After saving, click <strong>Authorize</strong> to connect.</div>
                    </div>
                </div>
            </div>

            <div class="card premium-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-globe text-primary me-1"></i> Supported Countries</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small text-secondary">
                        @foreach(config('daraz.countries') as $code => $country)
                            <li class="mb-2 d-flex justify-content-between align-items-center">
                                <strong class="text-dark" style="font-size: 0.85rem;">{{ $country['name'] }}</strong>
                                <span class="badge bg-light text-secondary border" style="font-size: 0.72rem; font-weight: 600;">{{ $code }}</span>
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
