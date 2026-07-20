<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Display the license settings page
     */
    public function index()
    {
        $licenseStatus = $this->licenseService->getLicenseStatus();
        
        return view('admin.license.index', compact('licenseStatus'));
    }

    /**
     * Activate a new license
     */
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $result = $this->licenseService->activateLicense($request->license_key);

        if ($result['success']) {
            return redirect()
                ->route('admin.verification.index')
                ->with('success', $result['message']);
        } else {
            return redirect()
                ->back()
                ->with('error', $result['message'])
                ->withInput();
        }
    }

    /**
     * Revalidate the current license
     */
    public function revalidate(Request $request)
    {
        $result = $this->licenseService->revalidateLicense();

        // If this is an AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'License revalidated successfully',
                    'data' => $result['data'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to revalidate license'
                ], 400);
            }
        }

        // For regular form submissions, return redirect
        if ($result['success']) {
            return redirect()
                ->route('admin.verification.index')
                ->with('success', 'License revalidated successfully');
        } else {
            return redirect()
                ->route('admin.verification.index')
                ->with('error', $result['message']);
        }
    }

    /**
     * Get license status via AJAX
     */
    public function status()
    {
        $licenseStatus = $this->licenseService->getLicenseStatus();
        
        return response()->json([
            'success' => true,
            'data' => $licenseStatus
        ]);
    }

    /**
     * Check if a specific module is available
     */
    public function checkModule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module' => 'required|string|in:pos,landing_page,core,multi_vendor',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid module specified'
            ], 400);
        }

        $isAllowed = $this->licenseService->isModuleAllowed($request->module);
        
        return response()->json([
            'success' => true,
            'allowed' => $isAllowed,
            'module' => $request->module
        ]);
    }

    /**
     * Check landing page quota
     */
    public function checkLandingPageQuota()
    {
        $canCreate = $this->licenseService->canCreateLandingPage();
        $licenseStatus = $this->licenseService->getLicenseStatus();
        
        return response()->json([
            'success' => true,
            'can_create' => $canCreate,
            'quota' => [
                'limit' => $licenseStatus['landing_page_limit'] ?? 0,
                'used' => $licenseStatus['landing_page_used'] ?? 0,
                'remaining' => $licenseStatus['landing_page_remaining'] ?? 0,
            ]
        ]);
    }
}
