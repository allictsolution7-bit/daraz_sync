@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Menu Item</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.items.update', [$menu, $menuItem]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $menuItem->title) }}" required>
                            @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $menuItem->url) }}">
                            @error('url')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="parent_id">Parent Item</label>
                            <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">None (Top Level)</option>
                                @foreach($menuItems as $item)
                                <option value="{{ $item->id }}" {{ old('parent_id', $menuItem->parent_id) == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="target">Open In</label>
                            <select name="target" id="target" class="form-control @error('target') is-invalid @enderror">
                                <option value="_self" {{ old('target', $menuItem->target) == '_self' ? 'selected' : '' }}>Same Window</option>
                                <option value="_blank" {{ old('target', $menuItem->target) == '_blank' ? 'selected' : '' }}>New Window</option>
                            </select>
                            @error('target')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="icon_class">Icon Class</label>
                            <input type="text" name="icon_class" id="icon_class" class="form-control @error('icon_class') is-invalid @enderror" value="{{ old('icon_class', $menuItem->icon_class) }}">
                            <small class="form-text text-muted">Example: fas fa-home</small>
                            @error('icon_class')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="order">Order</label>
                            <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $menuItem->order) }}">
                            @error('order')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="status" {{ $menuItem->status ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Active</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Menu Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection