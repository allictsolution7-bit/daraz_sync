<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/account/profile';

    /**
     * SMS Service instance
     *
     * @var SMSService
     */
    protected $smsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SMSService $smsService)
    {
        $this->middleware('guest');
        $this->smsService = $smsService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:11', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'g-recaptcha-response' => 'required|captcha',
        ];

        // Check if email is enabled and required based on admin settings
        $emailEnabled = setting('registration', 'email_enabled', '1') == '1';
        $emailRequired = setting('registration', 'email_required', '0') == '1';

        if ($emailEnabled) {
            if ($emailRequired) {
                $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            } else {
                // Email is optional but validate if provided
                if (!empty($data['email'])) {
                    $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
                }
            }
        }

        return Validator::make($data, $rules);
    }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Check if phone OTP verification is enabled
        $phoneOtpEnabled = setting('registration', 'phone_otp_enabled', '1') == '1';
        $createAccountAfterOtp = setting('registration', 'create_account_after_otp', '1') == '1';
        $otpExpirationMinutes = 10;

        if ($phoneOtpEnabled) {
            // Generate OTP code
            $otpCode = (string) rand(1000, 9999);
            
            $userRole = in_array($request->role, ['admin', 'super_admin', 'vendor', 'user', 'customer']) ? $request->role : 'customer';

            // Store registration data in session (don't create user yet)
            $registrationData = [
                'name' => $request->name,
                'email' => $request->email ?? User::generateUniqueEmail($request->name, 'user'),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $userRole,
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes($otpExpirationMinutes),
                'otp_resend_count' => 0,
            ];
            
            session(['pending_registration' => $registrationData]);

            // Send OTP via SMS
            try {
                $smsTemplate = setting('registration', 'otp_sms_template', 'Dear {name}\nYour Mobile OTP Verification Code is : {otp_code}\nThank you from the Shop');
                $smsMessage = str_replace(['{name}', '{otp_code}'], [$request->name, $otpCode], $smsTemplate);
                
                $this->smsService->sendSMS($request->phone, $smsMessage);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'otp_sent',
                        'message' => 'OTP sent to your phone for verification.',
                    ]);
                }
                
                return redirect()->back()->with('otp_sent', 'OTP sent to your phone for verification.');
                
            } catch (\Exception $e) {
                \Log::error("SMS sending failed during registration: " . $e->getMessage());
                
                // Clear session data on SMS failure
                session()->forget('pending_registration');
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP. Please try again.',
                    ], 500);
                }
                
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
        } else {
            // Phone OTP is disabled, create user directly
            $userRole = in_array($request->role, ['admin', 'super_admin', 'vendor', 'user', 'customer']) ? $request->role : 'customer';

            if ($userRole === 'admin' || $userRole === 'super_admin') {
                $roleName = 'admin';
            } else if ($userRole === 'vendor') {
                $roleName = 'vendor';
            } else {
                $roleName = 'customer';
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email ?? User::generateUniqueEmail($request->name, 'user'),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp_verified' => 1, // Mark as verified since no OTP required
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_resend_count' => 0,
            ]);

            try {
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole($roleName);
                }
            } catch (\Throwable $e) {
                \Log::warning("Could not assign Spatie role '{$roleName}': " . $e->getMessage());
            }

            // Log the user in
            Auth::login($user);

            $redirectTarget = ($roleName === 'admin') ? url('/admin') : $this->redirectTo;

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registration successful! Welcome!',
                    'redirect' => $redirectTarget,
                ]);
            }

            return redirect($redirectTarget);
        }
    }

    /**
     * Verify OTP for registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:4',
        ]);

        $registrationData = session('pending_registration');
        
        if (!$registrationData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired. Please register again.',
            ], 400);
        }

        // Check if OTP is valid and not expired
        $storedOtp = trim((string) $registrationData['otp_code']);
        $requestOtp = trim((string) $request->otp_code);
        
        if ($storedOtp !== $requestOtp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP code. Please try again.',
            ], 400);
        }

        if (now()->gt($registrationData['otp_expires_at'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP code has expired. Please request a new one.',
            ], 400);
        }

        $userRole = $registrationData['role'] ?? 'customer';
        if ($userRole === 'admin' || $userRole === 'super_admin') {
            $roleName = 'admin';
        } else if ($userRole === 'vendor') {
            $roleName = 'vendor';
        } else {
            $roleName = 'customer';
        }

        // Create user only after successful OTP verification
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'phone' => $registrationData['phone'],
            'password' => $registrationData['password'],
            'otp_verified' => 1, // Mark as verified since OTP was successful
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_resend_count' => 0,
        ]);

        try {
            if (method_exists($user, 'assignRole')) {
                $user->assignRole($roleName);
            }
        } catch (\Throwable $e) {
            \Log::warning("Could not assign Spatie role '{$roleName}': " . $e->getMessage());
        }

        // Clear session
        session()->forget('pending_registration');

        // Log the user in
        Auth::login($user);

        $redirectTarget = ($roleName === 'admin') ? url('/admin') : $this->redirectTo;

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful! Welcome to the Shop.',
            'redirect' => $redirectTarget,
        ]);
    }

    /**
     * Resend OTP for registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOtp(Request $request)
    {
        $registrationData = session('pending_registration');
        
        if (!$registrationData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired. Please register again.',
            ], 400);
        }

        // Get settings
        $maxResendLimit = 5;
        $otpExpirationMinutes = 10;

        // Check resend limit
        if ($registrationData['otp_resend_count'] >= $maxResendLimit) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maximum OTP resend limit reached.',
            ], 429);
        }

        // Generate new OTP
        $newOtpCode = (string) rand(1000, 9999);
        $registrationData['otp_code'] = $newOtpCode;
        $registrationData['otp_expires_at'] = now()->addMinutes($otpExpirationMinutes);
        $registrationData['otp_resend_count'] = $registrationData['otp_resend_count'] + 1;
        
        // Update session with new OTP data
        session(['pending_registration' => $registrationData]);

        // Send new OTP via SMS
        try {
            $smsTemplate = setting('registration', 'otp_resend_sms_template', 'Dear {name}\nYour Mobile New OTP Verification Code is : {otp_code}\nThank you from the Shop');
            $smsMessage = str_replace(['{name}', '{otp_code}'], [$registrationData['name'], $newOtpCode], $smsTemplate);
            
            $this->smsService->sendSMS($registrationData['phone'], $smsMessage);
            
            return response()->json([
                'status' => 'success',
                'message' => 'OTP resent successfully.',
            ]);
            
        } catch (\Exception $e) {
            \Log::error("SMS resend failed during registration: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP. Please try again.',
            ], 500);
        }
    }
}
