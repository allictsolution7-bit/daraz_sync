@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .delivery-edit-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            padding: 1.75rem;
            min-height: 100vh;
        }
        .header-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #2563eb 100%);
            border-radius: 1.25rem;
            padding: 2.25rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
            margin-bottom: 2rem;
        }
        .header-banner::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .form-section-card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
        }
        .form-section-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.05rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 0.65rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }
        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }
        .action-btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 0.65rem;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.7rem 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }
        .action-btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }
        .action-btn-subtle {
            background: #ffffff;
            color: #475569 !important;
            border: 1px solid #cbd5e1;
            border-radius: 0.65rem;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.7rem 1.4rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .action-btn-subtle:hover {
            background: #f8fafc;
            color: #0f172a !important;
            border-color: #94a3b8;
        }
        .nav-pills-custom {
            gap: 0.75rem;
            background: #f1f5f9;
            padding: 0.4rem;
            border-radius: 0.85rem;
            display: inline-flex;
        }
        .nav-pills-custom .nav-link {
            border-radius: 0.65rem;
            color: #64748b;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 0.65rem 1.35rem;
            border: none;
            transition: all 0.2s ease;
        }
        .nav-pills-custom .nav-link.active {
            background: #ffffff;
            color: #2563eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

@section('content')
    <div class="delivery-edit-container">
        <!-- Header Banner -->
        <div class="header-banner">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
                <div>
                    <h2 class="fw-extrabold text-white mb-2" style="font-size: 1.75rem; letter-spacing: -0.02em;">
                        {{ isset($integration) ? 'Edit' : 'Configure' }} {{ isset($integration) ? ucfirst($integration->provider) : 'Courier' }} Integration
                    </h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem; max-width: 650px;">
                        Set API keys, secrets, and store parameters for automated courier dispatches.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.delivery.index') }}" class="action-btn-subtle text-white bg-white bg-opacity-10 border-light" style="color: white !important;">
                        <i class="fas fa-arrow-left"></i> Back to Listing
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444; border-radius: 0.85rem; background-color: #fef2f2;">
                <h6 class="fw-bold text-danger mb-2"><i class="fas fa-exclamation-circle me-1"></i> Validation Error</h6>
                <ul class="mb-0 small text-danger ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.delivery.integration', $integration->id ?? null) }}" method="POST">
            @csrf
            
            @if (!isset($integration))
                <div class="mb-4">
                    <ul class="nav nav-pills nav-pills-custom" id="providerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pathao-tab" data-bs-toggle="tab" data-bs-target="#pathao" type="button" role="tab" aria-controls="pathao" aria-selected="true">
                                <i class="fas fa-paper-plane me-1"></i> Pathao Express
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="steadfast-tab" data-bs-toggle="tab" data-bs-target="#steadfast" type="button" role="tab" aria-controls="steadfast" aria-selected="false">
                                <i class="fas fa-truck-loading me-1"></i> Steadfast Courier
                            </button>
                        </li>
                    </ul>
                </div>
            @endif

            <div class="form-section-card">
                <div class="form-section-title">
                    <i class="fas fa-key text-primary"></i> API Credentials & Configuration
                </div>

                <div class="tab-content" id="providerTabsContent">
                    @if (!isset($integration) || $integration->provider === 'pathao')
                        <div class="tab-pane fade show active" id="pathao" role="tabpanel" aria-labelledby="pathao-tab">
                            <input type="hidden" name="provider" value="pathao" id="provider-input">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Client ID <span class="text-danger">*</span></label>
                                    <input type="text" name="client_id" class="form-control" value="{{ $integration->credentials['client_id'] ?? '' }}" placeholder="Pathao OAuth Client ID">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Client Secret <span class="text-danger">*</span></label>
                                    <input type="text" name="client_secret" class="form-control" value="{{ $integration->credentials['client_secret'] ?? '' }}" placeholder="Pathao OAuth Client Secret">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Username / Email <span class="text-danger">*</span></label>
                                    <input type="text" name="username" class="form-control" value="{{ $integration->credentials['username'] ?? '' }}" placeholder="Pathao merchant account email">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" value="{{ $integration->credentials['password'] ?? '' }}" placeholder="••••••••••••">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Base API URL <span class="text-danger">*</span></label>
                                    <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://api-hermes.pathao.com' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Default Store ID</label>
                                    <input type="text" name="store_id" class="form-control" value="{{ $integration->credentials['store_id'] ?? '' }}" placeholder="Pathao Store ID">
                                    <small class="text-muted d-block mt-1">Available under Pathao Merchant Panel -> Settings -> Stores.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!isset($integration) || $integration->provider === 'steadfast')
                        <div class="tab-pane fade show {{ isset($integration) ? 'active' : '' }}" id="steadfast" role="tabpanel" aria-labelledby="steadfast-tab">
                            <input type="hidden" name="provider" value="steadfast" id="provider-input-steadfast">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">API Key <span class="text-danger">*</span></label>
                                    <input type="text" name="api_key" class="form-control" value="{{ $integration->credentials['api_key'] ?? '' }}" placeholder="Steadfast API Key">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Secret Key <span class="text-danger">*</span></label>
                                    <input type="text" name="secret_key" class="form-control" value="{{ $integration->credentials['secret_key'] ?? '' }}" placeholder="Steadfast Secret Key">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Base API URL <span class="text-danger">*</span></label>
                                    <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://portal.packzy.com/api/v1' }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <i class="fas fa-toggle-on text-primary"></i> Enable Connection
                </div>
                <div class="form-check form-switch ps-0 d-flex align-items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input ms-0" id="is_active" {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }} style="width: 2.75rem; height: 1.5rem; cursor: pointer;">
                    <div>
                        <label class="form-check-label fw-bold text-dark mb-0" for="is_active" style="cursor: pointer;">
                            Enable {{ isset($integration) ? ucfirst($integration->provider) : 'Courier' }} API Connection
                        </label>
                        <small class="text-muted d-block">When active, orders can be dispatched directly to this courier network from order detail screens.</small>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="action-btn-primary">
                    <i class="fas fa-check-circle"></i> Save Integration Settings
                </button>
                <a href="{{ route('admin.delivery.index') }}" class="action-btn-subtle">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if (!isset($integration))
        <script>
            $(document).ready(function() {
                function setTabState() {
                    $('.tab-pane input').prop('disabled', true);
                    $('.tab-pane.active input').prop('disabled', false);
                    if ($('#pathao').hasClass('active')) {
                        $('#provider-input').attr('name', 'provider').val('pathao');
                        $('#provider-input-steadfast').removeAttr('name');
                    } else if ($('#steadfast').hasClass('active')) {
                        $('#provider-input-steadfast').attr('name', 'provider').val('steadfast');
                        $('#provider-input').removeAttr('name');
                    }
                }
                $('#providerTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    setTabState();
                });
                setTabState();
            });
        </script>
    @endif
@endsection