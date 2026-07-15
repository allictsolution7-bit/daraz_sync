<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectTo()
    {
        $user = Auth::user();
        if ($user && ($user->hasRole('admin') || $user->hasRole('super_admin'))) {
            return route('admin.dashboard');
        }
        return '/account/profile';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('email');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $login = $request->input('email');
        $password = $request->input('password');
        
        // Check if input is email or phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        
        return $this->guard()->attempt(
            [$field => $login, 'password' => $password],
            $request->boolean('remember')
        );
    }
}
