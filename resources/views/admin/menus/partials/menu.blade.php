<ul class="menu-list">
    @foreach($menuItems as $item)
        <li class="menu-item {{ $item->children->count() > 0 ? 'has-submenu' : '' }}">
            <a href="{{ $item->url }}" target="{{ $item->target }}" class="menu-link">
                @if($item->icon_class)
                    <i class="{{ $item->icon_class }}"></i>
                @endif
                <span>{{ $item->title }}</span>
                @if($item->children->count() > 0)
                    <i class="fas fa-chevron-down submenu-indicator"></i>
                @endif
            </a>
            
            @if($item->children->count() > 0)
                <ul class="submenu">
                    @foreach($item->children as $child)
                        <li class="submenu-item {{ $child->children->count() > 0 ? 'has-submenu' : '' }}">
                            <a href="{{ $child->url }}" target="{{ $child->target }}" class="submenu-link">
                                @if($child->icon_class)
                                    <i class="{{ $child->icon_class }}"></i>
                                @endif
                                <span>{{ $child->title }}</span>
                                @if($child->children->count() > 0)
                                    <i class="fas fa-chevron-right submenu-indicator"></i>
                                @endif
                            </a>
                            
                            @if($child->children->count() > 0)
                                <ul class="sub-submenu">
                                    @foreach($child->children as $grandchild)
                                        <li class="sub-submenu-item">
                                            <a href="{{ $grandchild->url }}" target="{{ $grandchild->target }}" class="sub-submenu-link">
                                                @if($grandchild->icon_class)
                                                    <i class="{{ $grandchild->icon_class }}"></i>
                                                @endif
                                                <span>{{ $grandchild->title }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>