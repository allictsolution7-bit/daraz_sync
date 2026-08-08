<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorSetting;
use App\Services\VendorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminVendorController extends Controller
{
    protected VendorService $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * Display all vendors created by this admin
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user && (!empty($user->is_super_admin) || $user->id == 1 || in_array($user->type ?? '', ['super_admin', 'super admin']) || in_array($user->role ?? '', ['super_admin', 'super admin']) || (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['super_admin', 'super admin', 'Super Admin', 'super-admin'])));

        $query = User::role('vendor')->with('vendorSettings');

        if (!$isSuperAdmin) {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhereNull('created_by')
                  ->orWhere('created_by', 0);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('vendorSettings', function ($settingsQuery) use ($request) {
                      $settingsQuery->where('business_name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->whereHas('vendorSettings', function ($q) {
                    $q->where('is_active', true);
                });
            } elseif ($request->status === 'inactive') {
                $query->whereHas('vendorSettings', function ($q) {
                    $q->where('is_active', false);
                });
            } elseif ($request->status === 'verified') {
                $query->whereHas('vendorSettings', function ($q) {
                    $q->where('is_verified', true);
                });
            } elseif ($request->status === 'unverified') {
                $query->whereHas('vendorSettings', function ($q) {
                    $q->where('is_verified', false);
                });
            }
        }

        $vendors = $query->latest()->paginate(20);

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Show create vendor form
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store new vendor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_min_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_max_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_min_withdrawal_amount' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'auto_approve_products' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated) {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
            ]);

            // Assign vendor role
            $user->assignRole('vendor');

            // Create vendor settings
            VendorSetting::create([
                'vendor_id' => $user->id,
                'business_name' => $validated['business_name'],
                'business_email' => $validated['business_email'],
                'business_phone' => $validated['business_phone'],
                'business_address' => $validated['business_address'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'default_commission_rate' => $validated['default_commission_rate'] ?? null,
                'custom_min_commission_rate' => $validated['custom_min_commission_rate'] ?? null,
                'custom_max_commission_rate' => $validated['custom_max_commission_rate'] ?? null,
                'custom_min_withdrawal_amount' => $validated['custom_min_withdrawal_amount'] ?? null,
                'is_active' => $validated['is_active'] ?? false,
                'is_verified' => $validated['is_verified'] ?? false,
                'auto_approve_products' => $validated['auto_approve_products'] ?? false,
                'verified_at' => ($validated['is_verified'] ?? false) ? now() : null,
                'verified_by' => ($validated['is_verified'] ?? false) ? auth()->id() : null,
            ]);
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor created successfully!');
    }

    /**
     * Show vendor details
     */
    public function show(User $vendor)
    {
        $vendor->load(['vendorSettings', 'products', 'vendorOrderItems']);
        $vendorSettings = $vendor->vendorSettings;

        $stats = $this->vendorService->getVendorStats($vendor->id);
        
        // Get recent products
        $recentProducts = $vendor->products()
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent withdrawals
        $recentWithdrawals = $vendor->vendorWithdrawals()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.vendors.show', compact('vendor', 'vendorSettings', 'stats', 'recentProducts', 'recentWithdrawals'));
    }

    /**
     * Show edit vendor form
     */
    public function edit(User $vendor)
    {
        $vendor->load('vendorSettings');
        $vendorSettings = $vendor->vendorSettings;

        return view('admin.vendors.edit', compact('vendor', 'vendorSettings'));
    }

    /**
     * Update vendor
     */
    public function update(Request $request, User $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $vendor->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:reseller,vendor,wholeseller',
            'vendor_type' => 'required_if:role,vendor|nullable|in:retailer,wholeseller',
            'business_name' => 'required|string|max:255',
            'business_email' => 'required|email',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_min_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_max_commission_rate' => 'nullable|numeric|min:0|max:100',
            'custom_min_withdrawal_amount' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
            'auto_approve_products' => 'nullable|boolean',
            'is_consignment' => 'nullable|boolean',
            'allow_negative_balance' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $vendor, $request) {
            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $vendor->update($userData);

            // Sync roles dynamically
            $role = $validated['role'];
            if ($role === 'reseller') {
                $vendor->syncRoles(['reseller']);
            } elseif ($role === 'wholeseller') {
                \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'wholeseller', 'guard_name' => 'web']);
                $vendor->syncRoles(['wholeseller', 'vendor']);
            } else {
                $vendor->syncRoles(['vendor']);
            }

            // Update vendor settings
            $vendorSettings = $vendor->vendorSettings;
            
            $additionalConfig = $vendorSettings->additional_config ?? [];
            if ($validated['role'] === 'vendor' && !empty($request->vendor_type)) {
                $additionalConfig['vendor_type'] = $request->vendor_type;
            } else {
                unset($additionalConfig['vendor_type']);
            }

            // Save allow negative balance toggle for wholeseller partners
            $isWholeseller = ($validated['role'] === 'wholeseller') || ($validated['role'] === 'vendor' && ($request->vendor_type ?? '') === 'wholeseller');
            if ($isWholeseller) {
                $additionalConfig['allow_negative_balance'] = $request->has('allow_negative_balance');
            } else {
                unset($additionalConfig['allow_negative_balance']);
            }
            
            $settingsData = [
                'business_name' => $validated['business_name'],
                'business_email' => $validated['business_email'],
                'business_phone' => $validated['business_phone'],
                'business_address' => $validated['business_address'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'default_commission_rate' => $validated['default_commission_rate'] ?? null,
                'custom_min_commission_rate' => $validated['custom_min_commission_rate'] ?? null,
                'custom_max_commission_rate' => $validated['custom_max_commission_rate'] ?? null,
                'custom_min_withdrawal_amount' => $validated['custom_min_withdrawal_amount'] ?? null,
                'is_active' => $validated['is_active'] ?? false,
                'auto_approve_products' => $validated['auto_approve_products'] ?? false,
                'is_consignment' => $validated['is_consignment'] ?? false,
                'additional_config' => $additionalConfig,
            ];

            // Handle verification
            if (($validated['is_verified'] ?? false) && !$vendorSettings->is_verified) {
                $settingsData['is_verified'] = true;
                $settingsData['verified_at'] = now();
                $settingsData['verified_by'] = auth()->id();
            } elseif (!($validated['is_verified'] ?? false) && $vendorSettings->is_verified) {
                $settingsData['is_verified'] = false;
                $settingsData['verified_at'] = null;
                $settingsData['verified_by'] = null;
            }

            $vendorSettings->update($settingsData);
        });

        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Vendor updated successfully!');
    }

    /**
     * Verify vendor
     */
    public function verify(User $vendor)
    {
        $vendor->vendorSettings->verify(auth()->id());

        return redirect()->back()->with('success', 'Vendor verified successfully!');
    }

    /**
     * Toggle vendor active status
     */
    public function toggleStatus(User $vendor)
    {
        $vendorSettings = $vendor->vendorSettings;
        $vendorSettings->update([
            'is_active' => !$vendorSettings->is_active,
        ]);

        $status = $vendorSettings->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()->with('success', "Vendor {$status} successfully!");
    }

    /**
     * Delete vendor
     */
    public function destroy(User $vendor)
    {
        // Check if vendor has orders
        if ($vendor->vendorOrderItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete vendor with existing orders.');
        }

        DB::transaction(function () use ($vendor) {
            // Delete vendor settings
            $vendor->vendorSettings()->delete();
            
            // Delete products
            $vendor->products()->delete();
            
            // Remove role
            $vendor->removeRole('vendor');
            
            // Delete user
            $vendor->delete();
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully!');
    }
}

