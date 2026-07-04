{{-- resources/views/frontend/partials/sidebar-menu-item.blade.php --}}
@php
    $level = isset($level) ? $level : 1;
    $isActive = request()->url() == $item->url;
    
    // Get children with proper relationships
    $children = $item->children()->where('status', true)->orderBy('order')->get();
    $hasChildren = $children->count() > 0;
@endphp

<li class="sidebar-menu-item level-{{ $level }}" data-item-id="{{ $item->id }}">
    @if ($hasChildren)
        <a href="javascript:void(0)" 
           class="sidebar-menu-link has-children {{ $isActive ? 'active' : '' }}"
           data-level="{{ $level }}"
           data-item-id="{{ $item->id }}"
           data-has-children="true">
            @if ($item->icon_class)
                <i class="{{ $item->icon_class }}"></i>
            @endif
            <span class="menu-text">{{ $item->title }}</span>
            <svg class="menu-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
        
        <!-- Nested submenu -->
        <ul class="sidebar-submenu level-{{ $level + 1 }}" data-parent="{{ $item->id }}">
            @foreach ($children as $child)
                @include('frontend.partials.sidebar-menu-item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @else
        <a href="{{ $item->url ?: '#' }}" 
           class="sidebar-menu-link {{ $isActive ? 'active' : '' }}"
           data-level="{{ $level }}"
           data-item-id="{{ $item->id }}"
           target="{{ $item->target ?? '_self' }}">
            @if ($item->icon_class)
                <i class="{{ $item->icon_class }}"></i>
            @endif
            <span class="menu-text">{{ $item->title }}</span>
        </a>
    @endif
</li>
