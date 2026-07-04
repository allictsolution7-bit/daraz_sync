@extends('layouts.master')

@section('content')
    <div class="container">
        <h4 class="mt-2">{{ isset($integration) ? 'Edit' : 'Add' }} Delivery Integration</h4>
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

        <form action="{{ route('admin.delivery.integration', $integration->id ?? null) }}" method="POST">
            @csrf
            @if (!isset($integration))
                <ul class="nav nav-tabs" id="providerTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pathao-tab" data-bs-toggle="tab" data-bs-target="#pathao" type="button" role="tab" aria-controls="pathao" aria-selected="true">Pathao</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="steadfast-tab" data-bs-toggle="tab" data-bs-target="#steadfast" type="button" role="tab" aria-controls="steadfast" aria-selected="false">Steadfast</button>
                    </li>
                </ul>
            @endif
            <div class="tab-content mt-3" id="providerTabsContent">
                @if (!isset($integration) || $integration->provider === 'pathao')
                    <div class="tab-pane fade show active" id="pathao" role="tabpanel" aria-labelledby="pathao-tab">
                        <input type="hidden" name="provider" value="pathao" id="provider-input">
                        <div class="mb-3">
                            <label>Client ID</label>
                            <input type="text" name="client_id" class="form-control" value="{{ $integration->credentials['client_id'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Client Secret</label>
                            <input type="text" name="client_secret" class="form-control" value="{{ $integration->credentials['client_secret'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ $integration->credentials['username'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" value="{{ $integration->credentials['password'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Base URL</label>
                            <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://courier-api-sandbox.pathao.com' }}">
                        </div>
                        <div class="mb-3">
                            <label>Default Store ID</label>
                            <input type="text" name="store_id" class="form-control" value="{{ $integration->credentials['store_id'] ?? '' }}" placeholder="Enter your Pathao store ID">
                            <small class="text-muted">You can get your store ID from Pathao merchant portal or by calling the stores API</small>
                        </div>
                        <div class="mb-3">
                            <label>
                                <input type="checkbox" name="is_active" {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>
                @endif
                @if (!isset($integration) || $integration->provider === 'steadfast')
                    <div class="tab-pane fade show active" id="steadfast" role="tabpanel" aria-labelledby="steadfast-tab">
                        <input type="hidden" name="provider" value="steadfast" id="provider-input-steadfast">
                        <div class="mb-3">
                            <label>Api-Key</label>
                            <input type="text" name="api_key" class="form-control" value="{{ $integration->credentials['api_key'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Secret-Key</label>
                            <input type="text" name="secret_key" class="form-control" value="{{ $integration->credentials['secret_key'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label>Base URL</label>
                            <input type="text" name="base_url" class="form-control" value="{{ $integration->credentials['base_url'] ?? 'https://portal.packzy.com/api/v1' }}">
                        </div>
                        <div class="mb-3">
                            <label>
                                <input type="checkbox" name="is_active" {{ (!isset($integration) || $integration->is_active) ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($integration) ? 'Update' : 'Save' }}</button>
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