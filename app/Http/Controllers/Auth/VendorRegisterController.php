<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorSetting;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VendorRegisterController extends Controller
{
    protected $redirectTo = '/vendor/dashboard';
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->middleware('guest');
        $this->smsService = $smsService;
    }

    /**
     * Show vendor registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.vendor-register');
    }

    /**
     * Get a validator for an incoming vendor registration request.
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:11', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_email' => ['required', 'string', 'email', 'max:255'],
            'business_phone' => ['required', 'string', 'max:20'],
            'business_address' => ['nullable', 'string', 'max:500'],
            'store_slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:vendor_settings,store_slug'],
        ];

        // Check if email is enabled and required
        $emailEnabled = setting('registration', 'email_enabled', '1') == '1';
        $emailRequired = setting('registration', 'email_required', '0') == '1';

        if ($emailEnabled) {
            if ($emailRequired) {
                $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            } else {
                if (!empty($data['email'])) {
                    $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
                }
            }
        }

        return Validator::make($data, $rules, [
            'store_slug.regex' => 'The store slug must only contain lowercase letters, numbers, and hyphens.',
            'store_slug.unique' => 'This store slug is already taken. Please choose another one.',
        ]);
    }

    /**
     * Generate unique email for vendors who don't provide one
     */
    private function generateUniqueEmail()
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?? 'thikana.com';
        do {
            $email = 'vendor_' . uniqid() . '@' . $host;
        } while (User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Handle a vendor registration request
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Check if phone OTP verification is enabled
        $phoneOtpEnabled = setting('registration', 'phone_otp_enabled', '1') == '1';
        $otpExpirationMinutes = 10;

        if ($phoneOtpEnabled) {
            // Generate OTP code
            $otpCode = (string) rand(1000, 9999);
            
            // Store vendor registration data in session
            $registrationData = [
                'name' => $request->name,
                'email' => $request->email ?? $this->generateUniqueEmail(),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'business_name' => $request->business_name,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'store_slug' => $request->store_slug,
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes($otpExpirationMinutes),
                'otp_resend_count' => 0,
            ];
            
            session(['pending_vendor_registration' => $registrationData]);

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
                \Log::error("SMS sending failed during vendor registration: " . $e->getMessage());
                
                session()->forget('pending_vendor_registration');
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP. Please try again.',
                    ], 500);
                }
                
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
        } else {
            // Phone OTP is disabled, create vendor directly
            return $this->createVendor([
                'name' => $request->name,
                'email' => $request->email ?? $this->generateUniqueEmail(),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'business_name' => $request->business_name,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'store_slug' => $request->store_slug,
            ], $request);
        }
    }

    /**
     * Verify OTP for vendor registration
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:4',
        ]);

        $registrationData = session('pending_vendor_registration');
        
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

        // Create vendor after successful OTP verification
        return $this->createVendor($registrationData, $request);
    }

    /**
     * Create vendor user and settings
     */
    protected function createVendor(array $data, Request $request)
    {
        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'otp_verified' => 1,
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_resend_count' => 0,
            ]);

            // Assign roles dynamically
            $role = $data['role'] ?? 'vendor';
            if ($role === 'reseller') {
                $user->assignRole('reseller');
            } elseif ($role === 'wholeseller') {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'wholeseller', 'guard_name' => 'web']);
                $user->assignRole('wholeseller');
                $user->assignRole('vendor');
            } else {
                $user->assignRole('vendor');
            }

            // Create vendor settings
            $additionalConfig = [];
            if ($role === 'vendor' && !empty($data['vendor_type'])) {
                $additionalConfig['vendor_type'] = $data['vendor_type'];
            }

            VendorSetting::create([
                'vendor_id' => $user->id,
                'business_name' => $data['business_name'],
                'business_email' => $data['business_email'],
                'business_phone' => $data['business_phone'],
                'business_address' => $data['business_address'] ?? null,
                'store_slug' => $data['store_slug'],
                'is_active' => false, // Pending admin approval
                'is_verified' => false,
                'additional_config' => $additionalConfig,
            ]);

            DB::commit();

            // Clear session
            session()->forget('pending_vendor_registration');

            // Log the user in
            Auth::login($user);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Partner registration successful! Your account is pending approval.',
                    'redirect' => $this->redirectTo,
                ]);
            }

            return redirect($this->redirectTo)->with('success', 'Your partner account has been created successfully! Please wait for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Vendor/Partner registration failed: " . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Registration failed. Please try again.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Resend OTP for vendor registration
     */
    public function resendOtp(Request $request)
    {
        $registrationData = session('pending_vendor_registration');
        
        if (!$registrationData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session expired. Please register again.',
            ], 400);
        }

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
        
        session(['pending_vendor_registration' => $registrationData]);

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
            \Log::error("SMS resend failed during vendor registration: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP. Please try again.',
            ], 500);
        }
    }

    /**
     * Check if store slug is available
     */
    public function checkSlug(Request $request)
    {
        $slug = $request->slug;
        $exists = VendorSetting::where('store_slug', $slug)->exists();
        
        return response()->json([
            'available' => !$exists,
        ]);
    }

    /**
     * Show partner registration form
     */
    public function showPartnerRegistrationForm()
    {
        return view('auth.partner-register');
    }

    /**
     * Handle a partner registration request
     */
    public function registerPartner(Request $request)
    {
        $request->validate([
            'role' => 'required|in:reseller,vendor,wholeseller',
            'vendor_type' => 'required_if:role,vendor|nullable|in:retailer,wholeseller',
        ]);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:11', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($request->role !== 'reseller') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['business_email'] = ['required', 'string', 'email', 'max:255'];
            $rules['business_phone'] = ['required', 'string', 'max:20'];
            $rules['business_address'] = ['nullable', 'string', 'max:500'];
            $rules['store_slug'] = ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:vendor_settings,store_slug'];
        }

        $emailEnabled = setting('registration', 'email_enabled', '1') == '1';
        $emailRequired = setting('registration', 'email_required', '0') == '1';

        if ($emailEnabled) {
            if ($emailRequired) {
                $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            } else {
                if (!empty($request->email)) {
                    $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
                }
            }
        }

        $validator = Validator::make($request->all(), $rules, [
            'store_slug.regex' => 'The store slug must only contain lowercase letters, numbers, and hyphens.',
            'store_slug.unique' => 'This store slug is already taken. Please choose another one.',
        ]);

        $validator->validate();

        $phoneOtpEnabled = setting('registration', 'phone_otp_enabled', '1') == '1';
        $otpExpirationMinutes = 10;

        $businessName = $request->business_name ?? $request->name . ' Store';
        $businessEmail = $request->business_email ?? ($request->email ?? $this->generateUniqueEmail());
        $businessPhone = $request->business_phone ?? $request->phone;
        $businessAddress = $request->business_address ?? '';
        
        $baseSlug = Str::slug($businessName);
        $storeSlug = $request->store_slug ?? $baseSlug;
        if ($request->role === 'reseller' && !$request->store_slug) {
            $count = 1;
            while (VendorSetting::where('store_slug', $storeSlug)->exists()) {
                $storeSlug = $baseSlug . '-' . $count;
                $count++;
            }
        }

        if ($phoneOtpEnabled) {
            $otpCode = (string) rand(1000, 9999);
            
            $registrationData = [
                'name' => $request->name,
                'email' => $request->email ?? $this->generateUniqueEmail(),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'business_name' => $businessName,
                'business_email' => $businessEmail,
                'business_phone' => $businessPhone,
                'business_address' => $businessAddress,
                'store_slug' => $storeSlug,
                'role' => $request->role,
                'vendor_type' => $request->vendor_type,
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes($otpExpirationMinutes),
                'otp_resend_count' => 0,
            ];
            
            session(['pending_vendor_registration' => $registrationData]);

            try {
                $smsTemplate = setting('registration', 'otp_sms_template', 'Dear {name}\nYour Mobile OTP Verification Code is : {otp_code}\nThank you from the Shop');
                $smsMessage = str_replace(['{name}', '{otp_code}'], [$request->name, $otpCode], $smsTemplate);
                
                $this->smsService->sendSMS($request->phone, $smsMessage);
                
                return response()->json([
                    'status' => 'otp_sent',
                    'message' => 'OTP sent to your phone for verification.',
                ]);
            } catch (\Exception $e) {
                \Log::error("SMS sending failed during partner registration: " . $e->getMessage());
                session()->forget('pending_vendor_registration');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send OTP. Please try again.',
                ], 500);
            }
        } else {
            return $this->createVendor([
                'name' => $request->name,
                'email' => $request->email ?? $this->generateUniqueEmail(),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'business_name' => $businessName,
                'business_email' => $businessEmail,
                'business_phone' => $businessPhone,
                'business_address' => $businessAddress,
                'store_slug' => $storeSlug,
                'role' => $request->role,
                'vendor_type' => $request->vendor_type,
            ], $request);
        }
    }
}
