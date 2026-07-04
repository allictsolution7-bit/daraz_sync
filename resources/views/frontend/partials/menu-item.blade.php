{{-- resources/views/frontend/partials/menu-item.blade.php --}}
@php
    $level = isset($level) ? $level : 1;
    $isActive = request()->url() == $item->url;

    // Get children with proper relationships
    $children = $item->children()->where('status', true)->orderBy('order')->get();
    $hasChildren = $children->count() > 0;

    // Check for mega menu (only at level 1 and if globally enabled)
    $hasMegaMenu = $level === 1
        && $item->has_mega_menu
        && \App\Services\SettingsService::isMegaMenuEnabled();

    $megaMenuMenus = $hasMegaMenu ? $item->getMegaMenuMenus() : [];
@endphp

@if ($hasMegaMenu && !empty($megaMenuMenus))
    {{-- Mega Menu Rendering --}}
    <div class="dropdown-parent menu-level-{{ $level }} has-mega-menu"
         data-level="{{ $level }}"
         data-item-id="{{ $item->id }}">
        <a href="{{ $item->url ?: '#' }}"
           class="{{ $isActive ? 'active' : '' }} has-dropdown mega-menu-trigger"
           target="{{ $item->target ?? '_self' }}"
           data-title="{{ $item->title }}">
            @if ($item->icon_class)
                <i class="{{ $item->icon_class }}"></i>
            @endif
            <span>{{ $item->title }}</span>
            <svg class="dropdown-icon" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>

        <div class="mega-menu-dropdown animation-{{ \App\Services\SettingsService::getMegaMenuAnimation() }}">
            <div class="mega-menu-container">
                <div class="mega-menu-row">
                    @foreach(['column_1', 'column_2', 'column_3', 'column_4'] as $column)
                        @if(isset($megaMenuMenus[$column]))
                            @php
                                $columnMenu = $megaMenuMenus[$column];
                                $columnHeader = $item->getMegaMenuHeader($column) ?? $columnMenu->name;
                            @endphp
                            <div class="mega-menu-column">
                                <h4 class="mega-menu-column-header">{{ $columnHeader }}</h4>
                                <ul class="mega-menu-links">
                                    @foreach($columnMenu->menuItems as $link)
                                        <li>
                                            <a href="{{ $link->url ?: '#' }}"
                                               target="{{ $link->target ?? '_self' }}"
                                               class="{{ request()->url() == $link->url ? 'active' : '' }}">
                                                @if($link->icon_class)
                                                    <i class="{{ $link->icon_class }}"></i>
                                                @endif
                                                {{ $link->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="mega-menu-column mega-menu-column-empty"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@elseif ($hasChildren)
    {{-- Standard Dropdown --}}
    <div class="dropdown-parent menu-level-{{ $level }}" data-level="{{ $level }}" data-item-id="{{ $item->id }}">
        <a href="{{ $item->url ?: '#' }}"
           class="{{ $isActive ? 'active' : '' }} has-dropdown"
           target="{{ $item->target ?? '_self' }}"
           data-title="{{ $item->title }}"
           data-children="{{ $children->count() }}">
            @if ($item->icon_class)
                <i class="{{ $item->icon_class }}"></i>
            @endif
            <span>{{ $item->title }}</span>
            @if ($level === 1)
                <svg class="dropdown-icon" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @else
                <svg class="submenu-arrow" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.5 2L6.5 5L3.5 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            @endif
        </a>
        <div class="dropdown-menu submenu-level-{{ $level }}"
             data-level="{{ $level }}"
             data-parent="{{ $item->id }}"
             data-children-count="{{ $children->count() }}">
            @foreach ($children as $index => $child)
                @include('frontend.partials.menu-item', [
                    'item' => $child,
                    'level' => $level + 1,
                    'index' => $index
                ])
            @endforeach
        </div>
    </div>
@else
    {{-- Simple Link --}}
    <a href="{{ $item->url ?: '#' }}"
       class="{{ $isActive ? 'active' : '' }} menu-item-level-{{ $level }}"
       target="{{ $item->target ?? '_self' }}"
       data-level="{{ $level }}"
       data-item-id="{{ $item->id }}">
        @if ($item->icon_class)
            <i class="{{ $item->icon_class }}"></i>
        @endif
        <span>{{ $item->title }}</span>
    </a>
@endif
