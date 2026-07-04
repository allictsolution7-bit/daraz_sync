@extends('layouts.master')

@section('content')
    <div class="container">
        <h4 class="mt-2">{{ isset($integration) ? 'Edit' : 'Add' }} Fraud Checker Integration</h4>
        <hr>
        
        {{-- Global error messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.fraud-checker.integration', $integration->id ?? null) }}" method="POST">
            @csrf
            @if (!isset($integration))
                <ul class="nav nav-tabs" id="providerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="hoorin-tab" data-bs-toggle="tab" data-bs-target="#hoorin" type="button" role="tab" aria-controls="hoorin" aria-selected="true">Hoorin</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bdcourier-tab" data-bs-toggle="tab" data-bs-target="#bdcourier" type="button" role="tab" aria-controls="bdcourier" aria-selected="false">BD Courier</button>
                    </li>
                </ul>
            @endif
            
            <div class="tab-content mt-3" id="providerTabsContent">
                @if (!isset($integration) || $integration->provider === 'hoorin')
                    <div class="tab-pane fade show active" id="hoorin" role="tabpanel" aria-labelledby="hoorin-tab">
                        <input type="hidden" name="provider" value="hoorin" id="provider-input-hoorin">
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shield-alt"></i> Hoorin Fraud Checker
                                </h5>
                                <small class="text-muted">Hoorin Courier Search API - Provides courier delivery summaries and fraud detection</small>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="hoorin_api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                                    <input type="text" name="api_key" id="hoorin_api_key" class="form-control" 
                                           value="{{ $integration->credentials['api_key'] ?? '' }}" 
                                           placeholder="Enter your Hoorin API key">
                                    <div class="form-text">
                                        Get your API key from <a href="https://dash.hoorin.com" target="_blank">Hoorin Dashboard</a>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="hoorin_is_active" class="form-check-input" 
                                               {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hoorin_is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (!isset($integration) || $integration->provider === 'bdcourier')
                    <div class="tab-pane fade {{ isset($integration) && $integration->provider === 'bdcourier' ? 'show active' : '' }}" id="bdcourier" role="tabpanel" aria-labelledby="bdcourier-tab">
                        <input type="hidden" name="provider" value="bdcourier" id="provider-input-bdcourier">
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck"></i> BD Courier Fraud Checker
                                </h5>
                                <small class="text-muted">BD Courier API - Courier status checking and fraud detection service</small>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="bdcourier_api_key" class="form-label">API Key <span class="text-danger">*</span></label>
                                    <input type="text" name="api_key" id="bdcourier_api_key" class="form-control" 
                                           value="{{ $integration->credentials['api_key'] ?? '' }}" 
                                           placeholder="Enter your BD Courier API key">
                                    <div class="form-text">
                                        Get your API key from <a href="https://bdcourier.com" target="_blank">BD Courier</a>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="bdcourier_is_active" class="form-check-input" 
                                               {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bdcourier_is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ isset($integration) ? 'Update' : 'Save' }} Integration
                </button>
                <a href="{{ route('admin.fraud-checker.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @if (!isset($integration))
    <script>
        $(document).ready(function() {
            function setTabState() {
                // Disable all tab-pane inputs
                $('.tab-pane input').prop('disabled', true);
                // Enable only active tab inputs
                $('.tab-pane.active input').prop('disabled', false);
                // Set provider input name/value
                if ($('#hoorin').hasClass('active')) {
                    $('#provider-input-hoorin').attr('name', 'provider').val('hoorin');
                    $('#provider-input-bdcourier').removeAttr('name');
                } else if ($('#bdcourier').hasClass('active')) {
                    $('#provider-input-bdcourier').attr('name', 'provider').val('bdcourier');
                    $('#provider-input-hoorin').removeAttr('name');
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
