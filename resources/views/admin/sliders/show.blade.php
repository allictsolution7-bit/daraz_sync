@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Slider Details</h1>
        <div>
            <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sliders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Slider Preview</h6>
                </div>
                <div class="card-body">
                    <div class="slider-preview" style="position: relative; height: 300px; overflow: hidden; border-radius: 8px;">
                        <img src="{{ asset($slider->image) }}" alt="{{ $slider->image }}" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: {{ $slider->overlay_color }}; z-index: 1;"></div>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; z-index: 2; width: 80%;">
                            <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 10px;">{{ $slider->title }}</h2>
                            <p style="font-size: 14px; margin-bottom: 15px;">{{ $slider->description }}</p>
                            @if($slider->button_text)
                            <a href="{{ $slider->button_url }}" target="_blank" style="display: inline-block; padding: 8px 20px; background-color: #4e73df; color: white; border-radius: 4px; text-decoration: none;">
                                {{ $slider->button_text }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Slider Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{ $slider->id }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $slider->title }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $slider->description }}</td>
                        </tr>
                        <tr>
                            <th>Button</th>
                            <td>
                                @if($slider->button_text)
                                <a href="{{ $slider->button_url }}" target="_blank">{{ $slider->button_text }}</a>
                                @else
                                <span class="text-muted">No button</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td>{{ $slider->position }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge {{ $slider->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $slider->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $slider->creator ? $slider->creator->name : 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $slider->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $slider->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection