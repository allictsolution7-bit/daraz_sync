<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration Form</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layets.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @yield('styles')

    <style>
        .autoforms .form-group {
            margin-bottom: 0.5rem;
        }

        .autoforms .form-group label {
            font-weight: 500;
            font-size: 0.8rem;
        }
    </style>

</head>

<body style="background: linear-gradient(121deg, #0036c0 25%,#640180c2 100%); width: 100%; height: 100vh;"
    class="autoforms">

    @yield('content')

    {{-- <div class="container form-container">
        <div class="registration-form">
            <h2 class="mb-4">Registration Form</h2>
            <form>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control" id="fullname" placeholder="Enter your full name"
                        required>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" placeholder="Confirm Password"
                        required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
        </div>
    </div> --}}


    <!-- ----------------------------js---------------------------------------- -->

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}?v={{ filemtime(public_path('assets/js/bootstrap/bootstrap.bundle.min.js')) }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')

</body>

</html>
