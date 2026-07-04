@extends('layouts.master')

@section('content')
    <div class="container">
        @php
            $adminProfileImage = Auth::user()->profile_photo_path
                ? \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path)
                : asset('assets/img/man.png');
        @endphp
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
        <div class="row users">
            <div class="user-information border mb-4">
                <div class="user-image mb-3">
                    <img src="{{ $adminProfileImage }}" alt="Profile Photo" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                </div>
                {{-- <div class="user-image">
                    <img src="{{ asset('assets/img/Tawfiq.jpeg') }}" alt="">
                </div> --}}
                <div class="user-data">
                    <h2 class="name fs-5">{{ Auth::user()->name }}</h2>
                    <h6>Admin</h6>
                    <p>{{ Auth::user()->email }}</p>
                    <div class="social-link">
                        <a href="#" class="fab fa-facebook"></a>
                        <a href="#" class="fab fa-instagram"></a>
                        <a href="#" class="fab fa-twitter"></a>
                        <a href="#" class="fab fa-linkedin"></a>
                    </div>
                </div>
            </div>

            <nav class="my-4">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link fw-bold text-black active" id="nav-home-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                        aria-selected="true">Profile Details</button>
                    <button class="nav-link fw-bold text-black" id="nav-profile-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                        aria-selected="false">Profile Setting</button>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <!-- Profile Details Tab -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"
                    tabindex="0">
                    <h6>Profile Details</h6>
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <!-- Add more details if necessary -->
                </div>

                <!-- Profile Settings Tab -->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0">
                    <h6>Profile Settings</h6>
                    <form action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
