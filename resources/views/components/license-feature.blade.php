@if($enabled)
    {{ $slot }}
@else
    @if(isset($showDisabled) && $showDisabled)
        <div class="license-disabled-feature" title="{{ $message }}">
            <div class="disabled-overlay">
                <i class="fas fa-lock"></i>
                <span>{{ ucfirst($module) }} Module</span>
                <small>License Required</small>
            </div>
            <div class="disabled-content">
                {{ $slot }}
            </div>
        </div>
    @endif
@endif

@if(!$enabled && (!isset($showDisabled) || !$showDisabled))
    @push('styles')
    <style>
    .license-disabled-feature {
        position: relative;
        opacity: 0.3;
        pointer-events: none;
    }
    
    .disabled-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 10px 15px;
        border-radius: 5px;
        text-align: center;
        z-index: 1000;
        border: 2px solid #dc3545;
        color: #dc3545;
        font-size: 12px;
        font-weight: bold;
    }
    
    .disabled-overlay i {
        display: block;
        font-size: 20px;
        margin-bottom: 5px;
    }
    
    .disabled-content {
        filter: blur(1px);
    }
    </style>
    @endpush
@endif
