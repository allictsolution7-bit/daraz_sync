@extends('layouts.authmaster')

@section('content')
    <div class="container form-container">
        <div class="registration-form">
            <div class="form-logo text-center">
                <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="" class="img-fluid" width="150">
            </div>
            <h5 class=" mt-2 text-center">{{ __('Login Your Account Now!') }}</h5>
            <hr>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email address or Phone Number</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                        placeholder="Enter your email or phone number" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        placeholder="Password" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>



                <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>

                <div class="text-center mt-3">
                    @if (Route::has('register'))
                        <small>
                            <span class="text-muted">Don't have an account?</span>
                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                        </small>
                        <br>
                    @endif
                    @if (Route::has('vendor.register'))
                        <small>
                            <span class="text-muted">Want to sell on our platform?</span>
                            <a href="{{ route('vendor.register') }}">{{ __('Vendor Registration') }}</a>
                        </small>
                        <br>
                    @endif
                    @if (Route::has('password.request'))
                        <small>
                            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </small>
                    @endif
                </div>
                <img src="https://uddoktaecommerce.com/tiny.svg">
            </form>
        </div>
    </div>
@endsection
