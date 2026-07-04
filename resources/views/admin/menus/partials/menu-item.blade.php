<li class="dd-item" data-id="{{ $item->id }}">
    <div class="dd-handle">
        {{ $item->title }}
        @if($item->has_mega_menu)
            <span class="badge badge-info ml-2" style="font-size: 10px;">Mega Menu</span>
        @endif
    </div>
    <div class="menu-item-actions" style="position: absolute; right: 10px; top: 5px; z-index: 10;">
        <button type="button" class="btn btn-link text-info p-0 edit-menu-item-btn"
                onclick="event.stopPropagation(); editMenuItem({{ $item->id }}, '{{ addslashes($item->title) }}', '{{ addslashes($item->url) }}', '{{ $item->target }}', '{{ addslashes($item->icon_class) }}', {{ $item->order }}, {{ $item->status ? 'true' : 'false' }}, {{ $item->parent_id ?? 'null' }}, {{ $item->has_mega_menu ? 'true' : 'false' }}, {{ json_encode($item->mega_menu_config ?? new stdClass()) }}, {{ json_encode($item->mega_menu_headers ?? new stdClass()) }});"
                data-id="{{ $item->id }}"
                data-title="{{ $item->title }}"
                title="Edit menu item">
            <i class="fas fa-edit"></i>
        </button>
        <button type="button" class="btn btn-link text-danger p-0 delete-menu-item-btn"
                onclick="event.stopPropagation(); deleteMenuItem({{ $item->id }}, '{{ addslashes($item->title) }}');"
                data-id="{{ $item->id }}"
                data-title="{{ $item->title }}">
            <i class="fas fa-trash"></i>
        </button>
    </div>
    @if($item->children->count() > 0)
        <ol class="dd-list">
            @foreach($item->children as $child)
                @include('admin.menus.partials.menu-item', ['item' => $child])
            @endforeach
        </ol>
    @endif
</li>