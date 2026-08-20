<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaaSTenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaaSTenantController extends Controller
{
    /**
     * Display a listing of tenants.
     */
    public function index()
    {
        $tenants = SaaSTenant::orderBy('id', 'desc')->paginate(15);
        $globalCommission = floatval(\App\Services\SettingsService::get('saas', 'wholesale_commission', 0));

        foreach ($tenants as $tenant) {
            $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
            try {
                $this->connectToTenantDatabase($dbName);
                $productCount = DB::connection('tenant_temp')->table('products')->count();
                $tenant->db_status = 'connected';
                $tenant->product_count = $productCount;
            } catch (\Throwable $e) {
                $tenant->db_status = 'error';
                $tenant->db_error_message = $e->getMessage();
                $tenant->product_count = 0;
            }
        }

        return view('admin.saas_tenants.index', compact('tenants', 'globalCommission'));
    }

    /**
     * Save the global platform wholesale commission.
     */
    public function saveGlobalCommission(Request $request)
    {
        $request->validate([
            'global_commission' => 'required|numeric|min:0|max:100',
        ]);

        \App\Services\SettingsService::set('saas', 'wholesale_commission', $request->global_commission);
        \App\Models\SiteSetting::updateOrCreate(
            ['group' => 'saas', 'key' => 'wholesale_commission'],
            ['value' => $request->global_commission]
        );

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Global wholesale commission updated to ' . $request->global_commission . '% successfully!');
    }

    /**
     * Store a newly created tenant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'nullable|string|max:255|alpha_dash|unique:saas_tenants,subdomain',
            'custom_domain' => 'nullable|string|max:255|unique:saas_tenants,custom_domain',
            'db_name' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        if (empty($request->subdomain) && empty($request->custom_domain)) {
            return redirect()->back()->with('error', 'Please provide either a Subdomain Prefix or a Custom Domain.');
        }

        $customDomain = $request->filled('custom_domain') ? strtolower(trim(preg_replace('#^https?://#i', '', rtrim($request->custom_domain, '/')))) : null;
        $subdomain = $request->subdomain;
        if (empty($subdomain) && $customDomain) {
            $parts = explode('.', $customDomain);
            $subdomain = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', $parts[0]));
        }

        try {
            $provisioner = new \App\Services\TenantProvisioningService();
            $tenant = $provisioner->provision(
                $request->name,
                $subdomain,
                $request->db_name,
                auth()->user(),
                $customDomain
            );

            $updateData = [
                'is_active' => $request->has('is_active'),
                'free_promotion' => $request->has('free_promotion'),
            ];

            if ($request->filled('commission_rate')) {
                $updateData['commission_rate'] = floatval($request->commission_rate);
            }

            $tenant->update($updateData);

            return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant database, domain & subdomain created & provisioned successfully!');
        } catch (\Throwable $e) {
            Log::error("Manual tenant provisioning failed: " . $e->getMessage());
            return redirect()->route('admin.saas-tenants.index')->with('error', 'Failed to provision tenant database: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified tenant.
     */
    public function update(Request $request, $id)
    {
        $tenant = SaaSTenant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|max:255|alpha_dash|unique:saas_tenants,subdomain,' . $tenant->id,
            'custom_domain' => 'nullable|string|max:255|unique:saas_tenants,custom_domain,' . $tenant->id,
            'db_name' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $customDomain = $request->filled('custom_domain') ? strtolower(trim(preg_replace('#^https?://#i', '', rtrim($request->custom_domain, '/')))) : null;

        $tenant->update([
            'name' => $request->name,
            'subdomain' => strtolower($request->subdomain),
            'custom_domain' => $customDomain,
            'db_name' => $request->db_name ?: 'purnobd_' . strtolower($request->subdomain),
            'is_active' => $request->has('is_active'),
            'free_promotion' => $request->has('free_promotion'),
            'commission_rate' => $request->filled('commission_rate') ? floatval($request->commission_rate) : null,
        ]);

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant updated successfully!');
    }

    /**
     * Toggle free promotion status for a tenant.
     */
    public function toggleFreePromotion(Request $request, $id)
    {
        $tenant = SaaSTenant::findOrFail($id);
        
        $newStatus = $request->has('free_promotion') 
            ? filter_var($request->input('free_promotion'), FILTER_VALIDATE_BOOLEAN)
            : !$tenant->free_promotion;

        $tenant->update([
            'free_promotion' => $newStatus,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Free promotion " . ($tenant->free_promotion ? 'enabled' : 'disabled') . " for tenant '{$tenant->name}'!",
                'free_promotion' => $tenant->free_promotion,
            ]);
        }

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Free promotion status updated successfully!');
    }

    /**
     * Remove the specified tenant.
     */
    public function destroy($id)
    {
        $tenant = SaaSTenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.saas-tenants.index')->with('success', 'Tenant deleted successfully!');
    }

    /**
     * Re-provision or repair a tenant's database schema.
     */
    public function reprovision($id)
    {
        $tenant = SaaSTenant::findOrFail($id);
        try {
            $provisioner = new \App\Services\TenantProvisioningService();
            $provisioner->reprovisionExisting($tenant, auth()->user());

            return redirect()->route('admin.saas-tenants.index')->with('success', "Tenant database '{$tenant->name}' provisioned & synchronized with all tables successfully!");
        } catch (\Throwable $e) {
            Log::error("Tenant reprovisioning failed: " . $e->getMessage());
            return redirect()->route('admin.saas-tenants.index')->with('error', 'Failed to provision tenant database: ' . $e->getMessage());
        }
    }

    /**
     * View all wholeselling products from all tenants.
     */
    public function wholesaleProducts(Request $request)
    {
        $tenants = SaaSTenant::where('is_active', true)->get();
        $selectedTenantId = $request->input('tenant_id');
        $currentTab = $request->input('tab', 'wholeseller'); // 'wholeseller' or 'admin'
        $globalCommission = floatval(\App\Services\SettingsService::get('saas', 'wholesale_commission', 0));
        
        $allProducts = [];
        $errors = [];
        $totalWholesellerCount = 0;
        $totalAdminCount = 0;

        foreach ($tenants as $tenant) {
            // If a specific tenant filter is applied, skip other tenants
            if ($selectedTenantId && $tenant->id != $selectedTenantId) {
                continue;
            }

            try {
                // Setup dynamic connection for this tenant
                $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                $this->connectToTenantDatabase($dbName);

                // Fetch wholesellers role user IDs
                $wholesellerRoleIds = DB::connection('tenant_temp')
                    ->table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('roles.name', 'wholeseller')
                    ->pluck('model_has_roles.model_id')
                    ->toArray();

                // Also check vendor_settings additional_config for vendor_type = wholeseller
                $vendorSettingWholesellers = DB::connection('tenant_temp')
                    ->table('vendor_settings')
                    ->get()
                    ->filter(function($vs) {
                        if (!empty($vs->additional_config)) {
                            $cfg = is_string($vs->additional_config) ? json_decode($vs->additional_config, true) : $vs->additional_config;
                            return is_array($cfg) && ($cfg['vendor_type'] ?? '') === 'wholeseller';
                        }
                        return false;
                    })
                    ->pluck('vendor_id')
                    ->toArray();

                $wholesellerIds = array_unique(array_merge($wholesellerRoleIds, $vendorSettingWholesellers));

                // Count for both tabs
                $wholesellerCount = !empty($wholesellerIds)
                    ? DB::connection('tenant_temp')->table('products')->whereIn('vendor_id', $wholesellerIds)->count()
                    : 0;
                
                // Admin products are only included if the tenant has free_promotion enabled
                $adminCount = $tenant->free_promotion
                    ? DB::connection('tenant_temp')
                        ->table('products')
                        ->where(function ($q) {
                            $q->whereNull('vendor_id')
                              ->orWhere('vendor_id', 0);
                        })
                        ->count()
                    : 0;

                $totalWholesellerCount += $wholesellerCount;
                $totalAdminCount += $adminCount;

                // Fetch products based on active tab
                if ($currentTab === 'wholeseller') {
                    $products = !empty($wholesellerIds)
                        ? DB::connection('tenant_temp')->table('products')->whereIn('vendor_id', $wholesellerIds)->get()
                        : collect();
                } else {
                    // Admin products - only fetched if tenant has free_promotion enabled
                    $products = $tenant->free_promotion
                        ? DB::connection('tenant_temp')
                            ->table('products')
                            ->where(function ($q) {
                                $q->whereNull('vendor_id')
                                  ->orWhere('vendor_id', 0);
                            })
                            ->get()
                        : collect();
                }

                // Fetch vendor settings and details for the selected products
                $vendorIds = $products->pluck('vendor_id')->filter()->unique()->toArray();
                $vendors = collect();
                if (!empty($vendorIds)) {
                    $vendors = DB::connection('tenant_temp')
                        ->table('users')
                        ->leftJoin('vendor_settings', 'users.id', '=', 'vendor_settings.vendor_id')
                        ->whereIn('users.id', $vendorIds)
                        ->select('users.id', 'users.name', 'users.email', 'vendor_settings.business_name')
                        ->get()
                        ->keyBy('id');
                }

                // Calculate tenant commission & markup BEFORE variant loop (used inside it)
                $tenantCommission = ($tenant->commission_rate !== null && $tenant->commission_rate !== '') 
                    ? floatval($tenant->commission_rate) 
                    : $globalCommission;
                $isCustomCommission = ($tenant->commission_rate !== null && $tenant->commission_rate !== '');

                $tenantGlobalMarkupPercent = 10.0;
                try {
                    $gSetting = DB::connection('tenant_temp')
                        ->table('site_settings')
                        ->where('group', 'single_product')
                        ->where('key', 'global_price_percent')
                        ->first();
                    if ($gSetting && is_numeric($gSetting->value)) {
                        $tenantGlobalMarkupPercent = floatval($gSetting->value);
                    }
                } catch (\Throwable $e) {
                    $tenantGlobalMarkupPercent = 10.0;
                }

                // Fetch variation combinations & option names in batch to resolve prices and variants for products
                $productIds = $products->pluck('id')->toArray();
                $variationsByProduct = [];
                $allCombosByProduct = [];
                if (!empty($productIds)) {
                    try {
                        $combos = DB::connection('tenant_temp')
                            ->table('variation_combinations')
                            ->whereIn('product_id', $productIds)
                            ->get();

                        // Collect all variation option IDs
                        $allOptionIds = [];
                        foreach ($combos as $combo) {
                            $opts = is_string($combo->variation_options) ? json_decode($combo->variation_options, true) : $combo->variation_options;
                            if (is_array($opts)) {
                                foreach ($opts as $optId) {
                                    $allOptionIds[] = $optId;
                                }
                            }
                        }
                        $allOptionIds = array_unique(array_filter($allOptionIds));

                        // Fetch option and variation names safely
                        $optionNames = [];
                        if (!empty($allOptionIds)) {
                            try {
                                $varOptions = DB::connection('tenant_temp')
                                    ->table('variation_options')
                                    ->join('variations', 'variation_options.variation_id', '=', 'variations.id')
                                    ->whereIn('variation_options.id', $allOptionIds)
                                    ->select('variation_options.id', 'variation_options.name as option_name', 'variations.name as variation_name')
                                    ->get();
                                foreach ($varOptions as $vo) {
                                    $optionNames[$vo->id] = $vo->variation_name . ': ' . $vo->option_name;
                                }
                            } catch (\Throwable $voEx) {
                                Log::warning("Could not fetch variation_options names: " . $voEx->getMessage());
                            }
                        }

                        foreach ($combos as $combo) {
                            if (!isset($variationsByProduct[$combo->product_id])) {
                                $variationsByProduct[$combo->product_id] = $combo;
                            }
                            if (!isset($allCombosByProduct[$combo->product_id])) {
                                $allCombosByProduct[$combo->product_id] = [];
                            }

                            // Format variant name
                            $opts = is_string($combo->variation_options) ? json_decode($combo->variation_options, true) : $combo->variation_options;
                            $comboNameParts = [];
                            if (is_array($opts)) {
                                foreach ($opts as $optId) {
                                    if (isset($optionNames[$optId])) {
                                        $comboNameParts[] = $optionNames[$optId];
                                    }
                                }
                            }
                            $comboDisplayName = !empty($comboNameParts) ? implode(' • ', $comboNameParts) : ($combo->combination_key ?: 'Variant #' . $combo->id);

                            $comboImage = $combo->featured_image ?? null;
                            $comboImageUrl = null;
                            if (!empty($comboImage)) {
                                if (filter_var($comboImage, FILTER_VALIDATE_URL) || str_starts_with($comboImage, 'http://') || str_starts_with($comboImage, 'https://')) {
                                    $comboImageUrl = $comboImage;
                                } elseif (str_starts_with($comboImage, 'storage/')) {
                                    $comboImageUrl = asset($comboImage);
                                } elseif (str_starts_with($comboImage, '/')) {
                                    $comboImageUrl = asset(ltrim($comboImage, '/'));
                                } else {
                                    $comboImageUrl = asset('storage/' . $comboImage);
                                }
                            }

                            // Variant pricing calculations
                            $varRegularPrice = floatval($combo->regular_price ?? $combo->price ?? 0);
                            $varOfferPrice = floatval($combo->offer_price ?? 0);
                            $varSellingPrice = ($varOfferPrice > 0) ? $varOfferPrice : $varRegularPrice;
                            $varGlobalPrice = floatval($combo->global_price ?? 0);
                            $varResellerPrice = floatval($combo->reseller_price ?? 0);
                            $varProductCost = floatval($combo->product_cost ?? 0);
                            $varBaseWholesale = floatval($combo->wholesale_price ?? 0);

                            if ($currentTab === 'admin') {
                                if ($varGlobalPrice > 0) {
                                    $varBasePrice = $varGlobalPrice;
                                } elseif ($varProductCost > 0) {
                                    $varBasePrice = $varProductCost * (1 + ($tenantGlobalMarkupPercent / 100));
                                } elseif ($varBaseWholesale > 0) {
                                    $varBasePrice = $varBaseWholesale;
                                } elseif ($varResellerPrice > 0) {
                                    $varBasePrice = $varResellerPrice;
                                } else {
                                    $varBasePrice = $varSellingPrice;
                                }
                            } else {
                                if ($varBaseWholesale > 0) {
                                    $varBasePrice = $varBaseWholesale;
                                } elseif ($varGlobalPrice > 0) {
                                    $varBasePrice = $varGlobalPrice;
                                } elseif ($varProductCost > 0) {
                                    $varBasePrice = $varProductCost;
                                } else {
                                    $varBasePrice = $varSellingPrice;
                                }
                            }

                            $varCommissionAmount = $tenantCommission > 0 ? ($varBasePrice * ($tenantCommission / 100)) : 0;
                            $varFinalWholesalePrice = $varBasePrice + $varCommissionAmount;

                            $allCombosByProduct[$combo->product_id][] = [
                                'id' => $combo->id,
                                'display_name' => $comboDisplayName,
                                'sku' => $combo->sku ?? '',
                                'image' => $comboImageUrl,
                                'regular_price' => $varRegularPrice,
                                'offer_price' => $varOfferPrice,
                                'price' => $varSellingPrice,
                                'base_price' => $varBasePrice,
                                'commission_amount' => $varCommissionAmount,
                                'final_wholesale_price' => $varFinalWholesalePrice,
                                'stock_quantity' => $combo->stock_quantity ?? 0,
                                'is_active' => isset($combo->is_active) ? (bool)$combo->is_active : true,
                            ];
                        }
                    } catch (\Throwable $ex) {
                        Log::error("Failed to load variation combinations for tenant {$tenant->subdomain}: " . $ex->getMessage());
                    }
                }

                // (tenantCommission & tenantGlobalMarkupPercent already set above before variant loop)

                foreach ($products as $prod) {
                    $vendor = isset($vendors[$prod->vendor_id]) ? $vendors[$prod->vendor_id] : null;
                    $productVariants = $allCombosByProduct[$prod->id] ?? [];
                    
                    // Product raw fields
                    $pGlobal = (isset($prod->global_price) && floatval($prod->global_price) > 0) ? floatval($prod->global_price) : 0;
                    $pReseller = (isset($prod->reseller_price) && floatval($prod->reseller_price) > 0) ? floatval($prod->reseller_price) : 0;
                    $pCost = (isset($prod->product_cost) && floatval($prod->product_cost) > 0) ? floatval($prod->product_cost) : 0;
                    $pWholesale = (isset($prod->wholesale_price) && floatval($prod->wholesale_price) > 0) ? floatval($prod->wholesale_price) : 0;
                    $pOldPrice = (isset($prod->old_price) && floatval($prod->old_price) > 0) ? floatval($prod->old_price) : 0;
                    $pOffer = (isset($prod->offer) && floatval($prod->offer) > 0) ? floatval($prod->offer) : 0;
                    $pPrice = (isset($prod->price) && floatval($prod->price) > 0) ? floatval($prod->price) : 0;

                    // Check if any variant combination has a non-zero price to use as fallback
                    $firstVarPrice = 0;
                    $firstVarRegular = 0;
                    $firstVarOffer = 0;
                    $firstVarWholesale = 0;
                    $firstVarGlobal = 0;
                    $firstVarCost = 0;
                    $firstVarReseller = 0;

                    foreach ($productVariants as $pv) {
                        if ($firstVarPrice == 0 && !empty($pv['price']) && floatval($pv['price']) > 0) {
                            $firstVarPrice = floatval($pv['price']);
                        }
                        if ($firstVarRegular == 0 && !empty($pv['regular_price']) && floatval($pv['regular_price']) > 0) {
                            $firstVarRegular = floatval($pv['regular_price']);
                        }
                        if ($firstVarOffer == 0 && !empty($pv['offer_price']) && floatval($pv['offer_price']) > 0) {
                            $firstVarOffer = floatval($pv['offer_price']);
                        }
                    }

                    if (!empty($variationsByProduct[$prod->id])) {
                        $vc = $variationsByProduct[$prod->id];
                        $firstVarGlobal = (isset($vc->global_price) && floatval($vc->global_price) > 0) ? floatval($vc->global_price) : 0;
                        $firstVarReseller = (isset($vc->reseller_price) && floatval($vc->reseller_price) > 0) ? floatval($vc->reseller_price) : 0;
                        $firstVarCost = (isset($vc->product_cost) && floatval($vc->product_cost) > 0) ? floatval($vc->product_cost) : 0;
                        $firstVarWholesale = (isset($vc->wholesale_price) && floatval($vc->wholesale_price) > 0) ? floatval($vc->wholesale_price) : 0;
                        if ($firstVarRegular == 0 && isset($vc->regular_price) && floatval($vc->regular_price) > 0) {
                            $firstVarRegular = floatval($vc->regular_price);
                        }
                        if ($firstVarOffer == 0 && isset($vc->offer_price) && floatval($vc->offer_price) > 0) {
                            $firstVarOffer = floatval($vc->offer_price);
                        }
                        if ($firstVarPrice == 0 && isset($vc->price) && floatval($vc->price) > 0) {
                            $firstVarPrice = floatval($vc->price);
                        }
                    }

                    // Compute authoritative old & selling prices
                    $productOldPrice = ($pOldPrice > 0) ? $pOldPrice : ($firstVarRegular > 0 ? $firstVarRegular : ($pPrice > 0 ? $pPrice : $firstVarPrice));
                    $productOfferPrice = ($pOffer > 0) ? $pOffer : $firstVarOffer;
                    $sellingPrice = ($productOfferPrice > 0) ? $productOfferPrice : ($productOldPrice > 0 ? $productOldPrice : ($pPrice > 0 ? $pPrice : $firstVarPrice));

                    $globalPrice = ($pGlobal > 0) ? $pGlobal : $firstVarGlobal;
                    $resellerPrice = ($pReseller > 0) ? $pReseller : $firstVarReseller;
                    $productCost = ($pCost > 0) ? $pCost : $firstVarCost;
                    $baseWholesale = ($pWholesale > 0) ? $pWholesale : $firstVarWholesale;

                    // Also recalculate productVariants with fallbacks
                    $formattedVariants = [];
                    foreach ($productVariants as $v) {
                        $vReg = ($v['regular_price'] > 0) ? $v['regular_price'] : $productOldPrice;
                        $vOffer = ($v['offer_price'] > 0) ? $v['offer_price'] : $productOfferPrice;
                        $vSell = ($vOffer > 0) ? $vOffer : ($vReg > 0 ? $vReg : $sellingPrice);
                        $vBase = ($v['base_price'] > 0) ? $v['base_price'] : (($currentTab === 'admin') ? ($globalPrice > 0 ? $globalPrice : ($productCost > 0 ? ($productCost * (1 + ($tenantGlobalMarkupPercent / 100))) : ($baseWholesale > 0 ? $baseWholesale : ($resellerPrice > 0 ? $resellerPrice : $vSell)))) : ($baseWholesale > 0 ? $baseWholesale : ($globalPrice > 0 ? $globalPrice : ($productCost > 0 ? $productCost : $vSell))));
                        $vComm = $tenantCommission > 0 ? ($vBase * ($tenantCommission / 100)) : 0;
                        $vFinalWholesale = $vBase + $vComm;

                        $formattedVariants[] = array_merge($v, [
                            'regular_price' => $vReg,
                            'offer_price' => $vOffer,
                            'price' => $vSell,
                            'base_price' => $vBase,
                            'commission_amount' => $vComm,
                            'final_wholesale_price' => $vFinalWholesale,
                        ]);
                    }

                    $thumbImage = $prod->thumb_image;
                    $thumbImageUrl = null;
                    if (!empty($thumbImage)) {
                        if (filter_var($thumbImage, FILTER_VALIDATE_URL) || str_starts_with($thumbImage, 'http://') || str_starts_with($thumbImage, 'https://')) {
                            $thumbImageUrl = $thumbImage;
                        } elseif (str_starts_with($thumbImage, 'storage/')) {
                            $thumbImageUrl = asset($thumbImage);
                        } elseif (str_starts_with($thumbImage, '/')) {
                            $thumbImageUrl = asset(ltrim($thumbImage, '/'));
                        } else {
                            $thumbImageUrl = asset('storage/' . $thumbImage);
                        }
                    }

                    $hasGlobalPrice = ($globalPrice > 0);
                    $hasResellerPrice = ($resellerPrice > 0);

                    if ($currentTab === 'admin') {
                        if ($hasGlobalPrice) {
                            $basePrice = $globalPrice;
                        } elseif ($productCost > 0) {
                            $basePrice = $productCost * (1 + ($tenantGlobalMarkupPercent / 100));
                        } elseif ($baseWholesale > 0) {
                            $basePrice = $baseWholesale;
                        } elseif ($hasResellerPrice) {
                            $basePrice = $resellerPrice;
                        } else {
                            $basePrice = $sellingPrice;
                        }

                        $commissionAmount = $tenantCommission > 0 ? ($basePrice * ($tenantCommission / 100)) : 0;
                        $finalPrice = $basePrice + $commissionAmount;
                    } else {
                        // Wholesellers tab
                        if ($baseWholesale > 0) {
                            $basePrice = $baseWholesale;
                        } elseif ($hasGlobalPrice) {
                            $basePrice = $globalPrice;
                        } elseif ($productCost > 0) {
                            $basePrice = $productCost;
                        } else {
                            $basePrice = $sellingPrice;
                        }

                        $commissionAmount = $tenantCommission > 0 ? ($basePrice * ($tenantCommission / 100)) : 0;
                        $finalPrice = $basePrice + $commissionAmount;
                    }

                    // Calculate variant retail price range for display (for variable products)
                    $variantRetailPrices = array_filter(array_map(fn($v) => floatval($v['price'] ?? 0), $formattedVariants), fn($p) => $p > 0);
                    $variantWholesalePrices = array_filter(array_map(fn($v) => floatval($v['final_wholesale_price'] ?? 0), $formattedVariants), fn($p) => $p > 0);

                    $allProducts[] = [
                        'tenant_name' => $tenant->name,
                        'tenant_subdomain' => $tenant->subdomain,
                        'id' => $prod->id,
                        'title' => $prod->title,
                        'thumb_image' => $thumbImageUrl,
                        'is_admin_tab' => ($currentTab === 'admin'),
                        'has_global_price' => $hasGlobalPrice,
                        'global_price' => $globalPrice,
                        'has_reseller_price' => $hasResellerPrice,
                        'reseller_price' => $resellerPrice,
                        'product_cost' => $productCost,
                        'base_price' => $basePrice,
                        'wholesale_price' => $baseWholesale,
                        'commission_percent' => $tenantCommission,
                        'is_custom_commission' => $isCustomCommission,
                        'commission_amount' => $commissionAmount,
                        'final_wholesale_price' => $finalPrice,
                        'price' => $sellingPrice,
                        'old_price' => $productOldPrice,
                        'offer' => $productOfferPrice,
                        'quantity' => $prod->quantity,
                        'status' => $prod->status,
                        'variants' => $formattedVariants,
                        'has_variants' => !empty($formattedVariants),
                        'variant_retail_min' => !empty($variantRetailPrices) ? min($variantRetailPrices) : 0,
                        'variant_retail_max' => !empty($variantRetailPrices) ? max($variantRetailPrices) : 0,
                        'variant_wholesale_min' => !empty($variantWholesalePrices) ? min($variantWholesalePrices) : 0,
                        'variant_wholesale_max' => !empty($variantWholesalePrices) ? max($variantWholesalePrices) : 0,
                        'vendor_name' => $vendor ? ($vendor->business_name ?: $vendor->name) : ($currentTab === 'admin' ? 'Tenant Admin' : 'N/A'),
                        'vendor_email' => $vendor ? $vendor->email : ($currentTab === 'admin' ? 'Store Owner' : 'N/A'),
                    ];
                }

            } catch (\Exception $e) {
                Log::error("Failed to fetch products for tenant {$tenant->name}: " . $e->getMessage());
                $errors[] = "Could not connect to database for tenant '{$tenant->name}' ({$tenant->subdomain}).";
            }
        }

        // Paginate manually since it's merged from multiple DB connections
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $currentItems = array_slice($allProducts, ($currentPage - 1) * $perPage, $perPage);
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            count($allProducts),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.saas_tenants.products', compact(
            'paginatedProducts',
            'tenants',
            'selectedTenantId',
            'currentTab',
            'globalCommission',
            'totalWholesellerCount',
            'totalAdminCount',
            'errors'
        ));
    }

    /**
     * Dynamically connect to the tenant database using the tenant_temp connection.
     */
    private function connectToTenantDatabase($dbName)
    {
        $defaultConfig = config('database.connections.mysql');
        $defaultConfig['database'] = $dbName;

        config(['database.connections.tenant_temp' => $defaultConfig]);

        DB::purge('tenant_temp');
    }
}
