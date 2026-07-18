@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .delivery-edit-container {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 1rem;
        }
        .page-header-premium {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 2rem;
            border-radius: 1rem;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
            margin-bottom: 2rem;
        }
        .form-section-premium {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .form-section-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-premium {
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-premium-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .btn-premium-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }
        .btn-premium-secondary {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }
        .btn-premium-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
        }
        .nav-tabs-premium {
            border-bottom: 2px solid #e2e8f0;
            gap: 0.5rem;
        }
        .nav-tabs-premium .nav-link {
            border: none;
            color: #64748b;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
            transition: all 0.2s;
            position: relative;
        }
        .nav-tabs-premium .nav-link.active {
            color: #3b82f6;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-bottom-color: white;
        }
        .nav-tabs-premium .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #3b82f6;
        }
    </style>
@endsection

@section('content')
    <div class="delivery-edit-container">
        <!-- Header -->
        <div class="page-header-premium shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 font-weight-bold text-white">{{ isset($integration) ? 'Edit' : 'Add' }} Delivery Integration</h3>
                    <p class="mb-0 text-white-50">Configure credentials to integrate courier systems directly into checkouts.</p>
                </div>
                <div>
                    <a href="{{ route('admin.delivery.index') }}" class="btn btn-light btn-premium text-primary">
                        <i class="fas fa-arrow-left"></i> Back to Listing
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444; border-radius: 0.5rem;">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.delivery.integration', $integration->id ?? null) }}" method="POST">
            @csrf
            
            @if (!isset($integration))
                <ul class="nav nav-tabs nav-tabs-premium mb-4" id="providerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pathao-tab" data-bs-toggle="tab" data-bs-target="#pathao" type="button" role="tab" aria-controls="pathao" aria-selected="true">
                            <i class="fas fa-shipping-fast mr-1"></i> Pathao
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="steadfast-tab" data-bs-toggle="tab" data-bs-target="#steadfast" type="button" role="tab" aria-controls="steadfast" aria-selected="false">
                            <i class="fas fa-truck mr-1"></i> Steadfast
                        </button>
                    </li>
                </ul>
            @endif

            <div class="form-section-premium">
                <div class="form-section-title">
                    <i class="fas fa-key text-primary"></i> API Integration Details
                </div>

                <div class="tab-content" id="providerTabsContent">
                    @if (!isset($integration) || $integration->provider === 'pathao')
                        <div class="tab-pane fade show active" id="pathao" role="tabpanel" aria-labelledby="pathao-tab">
                            <input type="hidden" name="provider" value="pathao" id="provider-input">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Client ID</label>
                                    <input type="text" name="client_id" class="form-control" value="{{ $integration->credentials['client_id'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Client Secret</label>
                                    <input type="text" name="client_secret" class="form-control" value="{{ $integration->credentials['client_secret'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Username</label>
                                    <input type="text" name="username" class="form-control" value="{{ $integration->credentials['username'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Password</label>
                                    <input type="password" name="password" class="form-control" value="{{ $integration->credentials['password'] ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted small font-weight-medium">Base URL</label>
                                    <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://courier-api-sandbox.pathao.com' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted small font-weight-medium">Default Store ID</label>
                                    <input type="text" name="store_id" class="form-control" value="{{ $integration->credentials['store_id'] ?? '' }}" placeholder="Enter your Pathao store ID">
                                    <small class="form-text text-muted">Retrieve your store ID from the Pathao merchant portal.</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!isset($integration) || $integration->provider === 'steadfast')
                        <div class="tab-pane fade show {{ isset($integration) ? 'active' : '' }}" id="steadfast" role="tabpanel" aria-labelledby="steadfast-tab">
                            <input type="hidden" name="provider" value="steadfast" id="provider-input-steadfast">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Api-Key</label>
                                    <input type="text" name="api_key" class="form-control" value="{{ $integration->credentials['api_key'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small font-weight-medium">Secret-Key</label>
                                    <input type="text" name="secret_key" class="form-control" value="{{ $integration->credentials['secret_key'] ?? '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label text-muted small font-weight-medium">Base URL</label>
                                    <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://portal.packzy.com/api/v1' }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section-premium">
                <div class="form-section-title">
                    <i class="fas fa-toggle-on text-primary"></i> Integration Status
                </div>
                <div class="form-check form-switch" style="padding-left: 3.5rem;">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }} style="cursor: pointer; transform: scale(1.2); margin-left: -2.5rem; float: left;">
                    <label class="form-check-label font-weight-medium text-dark pl-1" for="is_active" style="cursor: pointer; user-select: none;">
                        Activate courier network integration
                    </label>
                    <small class="form-text text-muted d-block mt-1">If deactivated, checkout systems won't dispatch courier deliveries dynamically.</small>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="fas fa-save"></i> {{ isset($integration) ? 'Update' : 'Save' }} Integration
                </button>
                <a href="{{ route('admin.delivery.index') }}" class="btn btn-premium btn-premium-secondary">
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
                    // Disable all tab-pane inputs
                    $('.tab-pane input').prop('disabled', true);
                    // Enable only active tab inputs
                    $('.tab-pane.active input').prop('disabled', false);
                    // Set provider input name/value
                    if ($('#pathao').hasClass('active')) {
                        $('#provider-input').attr('name', 'provider').val('pathao');
                        $('#provider-input-steadfast').removeAttr('name');
                    } else if ($('#steadfast').hasClass('active')) {
                        $('#provider-input-steadfast').attr('name', 'provider').val('steadfast');
                        $('#provider-input').removeAttr('name');
                    }
                }
                // On tab shown
                $('#providerTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    setTabState();
                });
                // On page load
                setTabState();
            });
        </script>
    @endif
@endsection