<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LicenseService;
use App\Services\UpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UpdateController extends Controller
{
    protected UpdateService $updateService;
    protected LicenseService $licenseService;

    public function __construct(UpdateService $updateService, LicenseService $licenseService)
    {
        $this->updateService = $updateService;
        $this->licenseService = $licenseService;
    }

    public function index(Request $request)
    {
        $forceCheck = $request->boolean('force_check');
        $manifest = $this->updateService->fetchAvailableUpdate($forceCheck);
        $history = $this->updateService->getHistory();
        $licenseStatus = $this->licenseService->getLicenseStatus();
        $currentVersion = $this->updateService->currentVersion();
        $canApplyUpdates = $this->licenseService->canAccessUpdates();

        $latestReleaseSummary = $manifest ?? $this->updateService->fetchLatestReleaseSummary();

        $updateAccessMessage = session('update_access_warning');
        if (!$updateAccessMessage && !$canApplyUpdates) {
            $updateStatus = $licenseStatus['update_status'] ?? [];
            $status = $updateStatus['status'] ?? null;
            $updateAccessMessage = $status === 'expired'
                ? 'Your update period has ended. Please contact +8801779542054 to activate your license.'
                : 'Update access is currently inactive. Please contact +8801779542054 to activate your license.';
        }

        return view('admin.updates.index', compact(
            'manifest',
            'history',
            'licenseStatus',
            'currentVersion',
            'canApplyUpdates',
            'updateAccessMessage',
            'latestReleaseSummary'
        ));
    }

    public function apply(Request $request)
    {
        $manifest = $this->updateService->fetchAvailableUpdate(true);

        if (!$manifest) {
            return redirect()->route('admin.updates.index')->with('error', 'No update is currently available.');
        }

        try {
            $this->updateService->applyUpdate($manifest, Auth::id());
            return redirect()->route('admin.updates.index')->with('success', 'Update applied successfully.');
        } catch (Throwable $e) {
            return redirect()->route('admin.updates.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
}
