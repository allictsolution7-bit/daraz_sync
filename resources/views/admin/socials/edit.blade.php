@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Banner</li>
            </ol>
        </nav>
        <h5>Add Social Media</h5>
        <form action="{{ route('admin.socials.update',$social->id) }}" method="POST"
              class="mt-1 mb-3">
            @csrf
            @method("PUT")
            <div class="container-fluid border my-2 py-3">
                <a href="{{route ("admin.socials.index")}}" class="btn btn-primary"><- Back</a>
                <div class="row">
                    <div class="col-12 col-md-6 mx-auto">
                        <div class="card p-2">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{$social->name}}">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">URL</label>
                                <input type="text" name="url" class="form-control" value="{{$social->url}}">
                                @error('url')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Class</label>
                                <input type="text" name="class" class="form-control" value="{{$social->class}}">
                                <div class="text-blue">
                                    example : <span class="font-bold">fa-brands fa-facebook</span>
                                </div>
                                @error('class')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="1" {{ $social->status === 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ $social->status === 0 ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Update Social</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
