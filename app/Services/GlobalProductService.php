<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SaaSTenant;
use App\Models\SubCategory;
use App\Models\Variation;
use App\Models\VariationCombination;
use App\Models\VariationOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GlobalProductService
{
    /**
     * Connect to tenant database dynamically.
     */
    public function connectToTenantDatabase(string $dbName): void
    {
        $defaultConfig = config('database.connections.mysql');
        $defaultConfig['database'] = $dbName;

        config(['database.connections.tenant_temp' => $defaultConfig]);
        DB::purge('tenant_temp');
        DB::reconnect('tenant_temp');
    }

    /**
     * Get all SaaS Global Wholesale products across all active tenants.
     */
    public function getGlobalProducts(array $filters = []): array
    {
        $currentTab = $filters['tab'] ?? 'admin'; // 'admin' or 'wholeseller'
        $selectedTenantId = $filters['tenant_id'] ?? null;
        $search = $filters['search'] ?? null;

        $globalCommission = 0.0;
        try {
            $setting = DB::table('site_settings')
                ->where('key', 'wholesale_commission')
                ->where(function ($q) {
                    $q->where('group', 'saas')
                      ->orWhere('group', 'like', '%_saas');
                })
                ->orderBy('id', 'desc')
                ->first();

            if ($setting && is_numeric($setting->value) && floatval($setting->value) > 0) {
                $globalCommission = floatval($setting->value);
            } else {
                $alt = DB::table('site_settings')
                    ->where('key', 'saas_wholesale_commission_rate')
                    ->value('value');
                if (is_numeric($alt) && floatval($alt) > 0) {
                    $globalCommission = floatval($alt);
                }
            }
        } catch (\Throwable $e) {
            $globalCommission = 0.0;
        }

        $tenantsQuery = SaaSTenant::where('is_active', true);
        if (!empty($selectedTenantId)) {
            $tenantsQuery->where('id', $selectedTenantId);
        }
        $tenants = $tenantsQuery->get();

        $allProducts = [];
        $totalWholesellerCount = 0;
        $totalAdminCount = 0;
        $errors = [];

        // Check local store product titles to mark already copied products
        $localProductTitles = Product::pluck('title')->map(fn($t) => trim(strtolower($t)))->toArray();
        $localTitlesSet = array_flip($localProductTitles);

        foreach ($tenants as $tenant) {
            try {
                $dbName = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;
                $this->connectToTenantDatabase($dbName);

                // Fetch wholeseller users from tenant DB
                $wholesellerIds = [];
                try {
                    $wUsers = DB::connection('tenant_temp')
                        ->table('users')
                        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                        ->where('roles.name', 'wholeseller')
                        ->pluck('users.id')
                        ->toArray();

                    $wSettings = DB::connection('tenant_temp')
                        ->table('vendor_settings')
                        ->get()
                        ->filter(function ($setting) {
                            $cfg = is_string($setting->additional_config) ? json_decode($setting->additional_config, true) : $setting->additional_config;
                            return is_array($cfg) && ($cfg['vendor_type'] ?? null) === 'wholeseller';
                        })
                        ->pluck('vendor_id')
                        ->toArray();

                    $wholesellerIds = array_unique(array_merge($wUsers, $wSettings));
                } catch (\Throwable $ex) {
                    $wholesellerIds = [];
                }

                // Counts
                $wholesellerCount = !empty($wholesellerIds)
                    ? DB::connection('tenant_temp')->table('products')->whereIn('vendor_id', $wholesellerIds)->count()
                    : 0;

                $adminCount = $tenant->free_promotion
                    ? DB::connection('tenant_temp')
                        ->table('products')
                        ->where(function ($q) {
                            $q->whereNull('vendor_id')->orWhere('vendor_id', 0);
                        })
                        ->count()
                    : 0;

                $totalWholesellerCount += $wholesellerCount;
                $totalAdminCount += $adminCount;

                // Query products for active tab
                $prodQuery = DB::connection('tenant_temp')->table('products');
                if ($currentTab === 'wholeseller') {
                    if (empty($wholesellerIds)) {
                        continue;
                    }
                    $prodQuery->whereIn('vendor_id', $wholesellerIds);
                } else {
                    if (!$tenant->free_promotion) {
                        continue;
                    }
                    $prodQuery->where(function ($q) {
                        $q->whereNull('vendor_id')->orWhere('vendor_id', 0);
                    });
                }

                if (!empty($search)) {
                    $prodQuery->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('id', $search);
                    });
                }

                $products = $prodQuery->get();
                if ($products->isEmpty()) {
                    continue;
                }

                // Commission rate calculation
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

                // Variations
                $productIds = $products->pluck('id')->toArray();
                $variationsByProduct = [];
                $allCombosByProduct = [];

                if (!empty($productIds)) {
                    try {
                        $combos = DB::connection('tenant_temp')
                            ->table('variation_combinations')
                            ->whereIn('product_id', $productIds)
                            ->get();

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

                // Fetch Categories
                $catIds = $products->pluck('category_id')->filter()->unique()->toArray();
                $categories = [];
                if (!empty($catIds)) {
                    try {
                        $categories = DB::connection('tenant_temp')
                            ->table('product_categories')
                            ->whereIn('id', $catIds)
                            ->pluck('name', 'id')
                            ->toArray();
                    } catch (\Throwable $e) {}
                }

                foreach ($products as $prod) {
                    $productVariants = $allCombosByProduct[$prod->id] ?? [];

                    $pGlobal = (isset($prod->global_price) && floatval($prod->global_price) > 0) ? floatval($prod->global_price) : 0;
                    $pReseller = (isset($prod->reseller_price) && floatval($prod->reseller_price) > 0) ? floatval($prod->reseller_price) : 0;
                    $pCost = (isset($prod->product_cost) && floatval($prod->product_cost) > 0) ? floatval($prod->product_cost) : 0;
                    $pWholesale = (isset($prod->wholesale_price) && floatval($prod->wholesale_price) > 0) ? floatval($prod->wholesale_price) : 0;
                    $pOldPrice = (isset($prod->old_price) && floatval($prod->old_price) > 0) ? floatval($prod->old_price) : 0;
                    $pOffer = (isset($prod->offer) && floatval($prod->offer) > 0) ? floatval($prod->offer) : 0;
                    $pPrice = (isset($prod->price) && floatval($prod->price) > 0) ? floatval($prod->price) : 0;

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

                    $productOldPrice = ($pOldPrice > 0) ? $pOldPrice : ($firstVarRegular > 0 ? $firstVarRegular : ($pPrice > 0 ? $pPrice : $firstVarPrice));
                    $productOfferPrice = ($pOffer > 0) ? $pOffer : $firstVarOffer;
                    $sellingPrice = ($productOfferPrice > 0) ? $productOfferPrice : ($productOldPrice > 0 ? $productOldPrice : ($pPrice > 0 ? $pPrice : $firstVarPrice));

                    $globalPrice = ($pGlobal > 0) ? $pGlobal : $firstVarGlobal;
                    $resellerPrice = ($pReseller > 0) ? $pReseller : $firstVarReseller;
                    $productCost = ($pCost > 0) ? $pCost : $firstVarCost;
                    $baseWholesale = ($pWholesale > 0) ? $pWholesale : $firstVarWholesale;

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
                    } else {
                        if ($baseWholesale > 0) {
                            $basePrice = $baseWholesale;
                        } elseif ($hasGlobalPrice) {
                            $basePrice = $globalPrice;
                        } elseif ($productCost > 0) {
                            $basePrice = $productCost;
                        } else {
                            $basePrice = $sellingPrice;
                        }
                    }

                    $commissionAmount = $tenantCommission > 0 ? ($basePrice * ($tenantCommission / 100)) : 0;
                    $finalPrice = $basePrice + $commissionAmount;

                    $variantRetailPrices = array_filter(array_map(fn($v) => floatval($v['price'] ?? 0), $formattedVariants), fn($p) => $p > 0);
                    $variantWholesalePrices = array_filter(array_map(fn($v) => floatval($v['final_wholesale_price'] ?? 0), $formattedVariants), fn($p) => $p > 0);

                    $isAlreadyCopied = isset($localTitlesSet[trim(strtolower($prod->title))]);

                    $allProducts[] = [
                        'tenant_name' => $tenant->name,
                        'tenant_subdomain' => $tenant->subdomain,
                        'tenant_id' => $tenant->id,
                        'id' => $prod->id,
                        'title' => $prod->title,
                        'slug' => $prod->slug ?? Str::slug($prod->title),
                        'category_name' => $categories[$prod->category_id] ?? 'Uncategorized',
                        'thumb_image' => $thumbImageUrl,
                        'raw_thumb_image' => $prod->thumb_image,
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
                        'quantity' => $prod->quantity ?? 0,
                        'status' => $prod->status,
                        'product_type' => $prod->product_type ?? (!empty($formattedVariants) ? 'variable' : 'simple'),
                        'variants' => $formattedVariants,
                        'has_variants' => !empty($formattedVariants),
                        'variant_retail_min' => !empty($variantRetailPrices) ? min($variantRetailPrices) : 0,
                        'variant_retail_max' => !empty($variantRetailPrices) ? max($variantRetailPrices) : 0,
                        'variant_wholesale_min' => !empty($variantWholesalePrices) ? min($variantWholesalePrices) : 0,
                        'variant_wholesale_max' => !empty($variantWholesalePrices) ? max($variantWholesalePrices) : 0,
                        'is_already_copied' => $isAlreadyCopied,
                    ];
                }
            } catch (\Exception $e) {
                Log::error("Failed to fetch products for tenant {$tenant->name}: " . $e->getMessage());
                $errors[] = "Could not connect to database for tenant '{$tenant->name}' ({$tenant->subdomain}).";
            }
        }

        return [
            'products' => $allProducts,
            'tenants' => $tenants,
            'totalWholesellerCount' => $totalWholesellerCount,
            'totalAdminCount' => $totalAdminCount,
            'globalCommission' => $globalCommission,
            'errors' => $errors,
        ];
    }

    /**
     * Copy / duplicate a single SaaS product from a tenant database into the current active store catalog.
     *
     * @param string $sourceSubdomain Subdomain of source tenant
     * @param int $sourceProductId ID of the product in the source tenant database
     * @param string $copyMode 'purchase' or 'copy'
     * @param int $quantity Quantity to purchase if mode is purchase
     * @param int|null $targetVendorId Target vendor ID (null for store admin)
     * @param int|null $currentAdminId Current admin ID
     * @return array
     */
    public function copyProductToStore(
        string $sourceSubdomain, 
        int $sourceProductId, 
        string $copyMode = 'copy', 
        int $quantity = 0, 
        ?int $targetVendorId = null, 
        ?int $currentAdminId = null
    ): array
    {
        $tenant = SaaSTenant::where('subdomain', $sourceSubdomain)->first();
        if (!$tenant) {
            return ['success' => false, 'message' => "Tenant with subdomain [{$sourceSubdomain}] not found."];
        }

        $sourceDb = $tenant->db_name ?: 'purnobd_' . $tenant->subdomain;

        try {
            // 1. Fetch raw product data and relationships from source tenant DB
            $this->connectToTenantDatabase($sourceDb);

            $sourceProduct = DB::connection('tenant_temp')
                ->table('products')
                ->where('id', $sourceProductId)
                ->first();

            if (!$sourceProduct) {
                return ['success' => false, 'message' => "Product #{$sourceProductId} not found in tenant [{$sourceSubdomain}]."];
            }

            // Fetch original creator info from source tenant DB
            $sourceCreatorId = $sourceProduct->created_by ?: ($sourceProduct->vendor_id ?: 1);
            $sourceCreatorName = "Tenant Admin #{$sourceCreatorId}";
            try {
                $creatorUser = DB::connection('tenant_temp')
                    ->table('users')
                    ->where('id', $sourceCreatorId)
                    ->first();
                if ($creatorUser) {
                    $sourceCreatorName = $creatorUser->name . ' (' . $creatorUser->email . ')';
                }
            } catch (\Throwable $e) {}

            // Current admin info
            $currentAdmin = auth()->user();
            $currentAdminId = $currentAdminId ?? ($currentAdmin ? $currentAdmin->id : null);
            $currentAdminName = $currentAdmin ? ($currentAdmin->name . ' (' . $currentAdmin->email . ')') : "Admin #{$currentAdminId}";

            // Fetch source category
            $sourceCategory = null;
            if (!empty($sourceProduct->category_id)) {
                $sourceCategory = DB::connection('tenant_temp')
                    ->table('product_categories')
                    ->where('id', $sourceProduct->category_id)
                    ->first();
            }

            // Fetch source subcategory
            $sourceSubCategory = null;
            if (!empty($sourceProduct->sub_category_id)) {
                $sourceSubCategory = DB::connection('tenant_temp')
                    ->table('sub_categories')
                    ->where('id', $sourceProduct->sub_category_id)
                    ->first();
            }

            // Fetch source brand
            $sourceBrand = null;
            if (!empty($sourceProduct->brand_id)) {
                $sourceBrand = DB::connection('tenant_temp')
                    ->table('brands')
                    ->where('id', $sourceProduct->brand_id)
                    ->first();
            }

            // Fetch variations, options, and variation combinations
            $sourceVariations = DB::connection('tenant_temp')
                ->table('variations')
                ->where('product_id', $sourceProductId)
                ->get();

            $sourceVariationIds = $sourceVariations->pluck('id')->toArray();
            $sourceOptions = [];
            if (!empty($sourceVariationIds)) {
                $sourceOptions = DB::connection('tenant_temp')
                    ->table('variation_options')
                    ->whereIn('variation_id', $sourceVariationIds)
                    ->get();
            }

            $sourceCombinations = DB::connection('tenant_temp')
                ->table('variation_combinations')
                ->where('product_id', $sourceProductId)
                ->get();

            // 2. Insertion into TARGET database
            DB::beginTransaction();

            // Resolve / Map Category in target DB
            $targetCategoryId = null;
            if ($sourceCategory) {
                $cat = ProductCategory::where('name', $sourceCategory->name)->first();
                if (!$cat) {
                    $cat = ProductCategory::create([
                        'name' => $sourceCategory->name,
                        'slug' => Str::slug($sourceCategory->name) . '-' . rand(100, 999),
                        'status' => 'active',
                    ]);
                }
                $targetCategoryId = $cat->id;
            } else {
                $defaultCat = ProductCategory::first();
                $targetCategoryId = $defaultCat ? $defaultCat->id : 1;
            }

            // Resolve / Map SubCategory in target DB
            $targetSubCategoryId = null;
            if ($sourceSubCategory && $targetCategoryId) {
                $subCat = SubCategory::where('name', $sourceSubCategory->name)
                    ->where('product_category_id', $targetCategoryId)
                    ->first();
                if (!$subCat) {
                    $subCat = SubCategory::create([
                        'name' => $sourceSubCategory->name,
                        'slug' => Str::slug($sourceSubCategory->name) . '-' . rand(100, 999),
                        'product_category_id' => $targetCategoryId,
                        'status' => 'active',
                    ]);
                }
                $targetSubCategoryId = $subCat->id;
            }

            // Resolve / Map Brand in target DB
            $targetBrandId = null;
            if ($sourceBrand) {
                $brand = Brand::where('name', $sourceBrand->name)->first();
                if (!$brand) {
                    $brand = Brand::create([
                        'name' => $sourceBrand->name,
                        'slug' => Str::slug($sourceBrand->name) . '-' . rand(100, 999),
                    ]);
                }
                $targetBrandId = $brand->id;
            }

            // Generate unique slug in target store
            $baseSlug = Str::slug($sourceProduct->title);
            $uniqueSlug = $baseSlug;
            $counter = 1;
            while (Product::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $baseSlug . '-' . time() . '-' . $counter;
                $counter++;
            }

            // Prepare source metadata JSON
            $sourceMetadata = [
                'source_tenant_name' => $tenant->name,
                'source_subdomain' => $tenant->subdomain,
                'source_product_id' => $sourceProductId,
                'original_creator_id' => $sourceCreatorId,
                'original_creator_name' => $sourceCreatorName,
                'copy_mode' => $copyMode,
                'purchased_quantity' => ($copyMode === 'purchase') ? $quantity : 0,
                'wholesale_price' => $sourceProduct->global_price ?? $sourceProduct->wholesale_price ?? $sourceProduct->product_cost ?? 0,
                'copied_by_admin_id' => $currentAdminId,
                'copied_by_admin_name' => $currentAdminName,
                'copied_at' => now()->toDateTimeString(),
            ];

            // Filter columns to match current Product schema
            $targetColumns = \Illuminate\Support\Facades\Schema::getColumnListing('products');
            $productData = (array)$sourceProduct;
            $productData = array_intersect_key($productData, array_flip($targetColumns));

            unset($productData['id'], $productData['created_at'], $productData['updated_at']);

            $productData['slug'] = $uniqueSlug;
            $productData['category_id'] = $targetCategoryId;
            $productData['sub_category_id'] = $targetSubCategoryId;
            $productData['brand_id'] = $targetBrandId;
            $productData['vendor_id'] = $targetVendorId;
            $productData['status'] = 1;
            $productData['approval_status'] = 'approved';

            // Source Attribution Tracking Columns
            $productData['created_by'] = $currentAdminId;
            $productData['source_tenant_subdomain'] = $sourceSubdomain;
            $productData['source_product_id'] = $sourceProductId;
            $productData['source_creator_id'] = $sourceCreatorId;
            $productData['source_creator_name'] = $sourceCreatorName;
            $productData['copied_by_admin_id'] = $currentAdminId;
            $productData['source_metadata'] = json_encode($sourceMetadata);

            // If purchase mode with quantity, set stock
            if ($copyMode === 'purchase' && $quantity > 0) {
                $productData['quantity'] = $quantity;
                $productData['manage_stock'] = 1;
                $productData['stock_status'] = 'in_stock';
            }

            // Clean images/description of base64
            if (!empty($productData['description'])) {
                $productData['description'] = preg_replace('/data:image\/[^;]+;base64,[^"\'\s>]+/i', '', $productData['description']);
            }
            if (!empty($productData['short_description'])) {
                $productData['short_description'] = preg_replace('/data:image\/[^;]+;base64,[^"\'\s>]+/i', '', $productData['short_description']);
            }

            // Create target Product
            $newProduct = Product::create($productData);

            // Replicate Variations & Options
            $optionIdMap = []; // old_option_id => new_option_id

            foreach ($sourceVariations as $sVar) {
                $newVar = Variation::create([
                    'product_id' => $newProduct->id,
                    'name' => $sVar->name,
                ]);

                $optsForVar = $sourceOptions->where('variation_id', $sVar->id);
                foreach ($optsForVar as $sOpt) {
                    $newOpt = VariationOption::create([
                        'variation_id' => $newVar->id,
                        'name' => $sOpt->name,
                        'description' => $sOpt->description ?? null,
                        'featured_image' => $sOpt->featured_image ?? null,
                        'images' => !empty($sOpt->images) ? (is_string($sOpt->images) ? json_decode($sOpt->images, true) : $sOpt->images) : null,
                        'stock_quantity' => $sOpt->stock_quantity ?? 0,
                        'price' => $sOpt->price ?? 0,
                    ]);
                    $optionIdMap[$sOpt->id] = $newOpt->id;
                }
            }

            // Replicate Variation Combinations
            foreach ($sourceCombinations as $sComb) {
                $combData = (array)$sComb;
                $combColumns = \Illuminate\Support\Facades\Schema::getColumnListing('variation_combinations');
                $combData = array_intersect_key($combData, array_flip($combColumns));

                unset($combData['id'], $combData['created_at'], $combData['updated_at']);

                // Map old variation_options array IDs to new IDs
                $rawOpts = is_string($sComb->variation_options) ? json_decode($sComb->variation_options, true) : $sComb->variation_options;
                $newOpts = [];
                if (is_array($rawOpts)) {
                    foreach ($rawOpts as $oldOptId) {
                        if (isset($optionIdMap[$oldOptId])) {
                            $newOpts[] = $optionIdMap[$oldOptId];
                        }
                    }
                }

                $combData['product_id'] = $newProduct->id;
                $combData['variation_options'] = json_encode($newOpts);
                $combData['sku'] = !empty($sComb->sku) ? $sComb->sku . '-' . $newProduct->id : 'SKU-' . $newProduct->id . '-' . rand(100, 999);

                VariationCombination::create($combData);
            }

            DB::commit();

            return [
                'success' => true,
                'product_id' => $newProduct->id,
                'title' => $newProduct->title,
                'message' => "Product '{$newProduct->title}' was successfully copied into your store catalog!",
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Failed to copy global product #{$sourceProductId} from tenant {$sourceSubdomain}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Failed to copy product: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Bulk copy multiple products into the target store catalog.
     *
     * @param array $items Array of ['subdomain' => ..., 'product_id' => ...]
     * @param int|null $targetVendorId
     * @return array
     */
    public function bulkCopyProducts(array $items, ?int $targetVendorId = null): array
    {
        $successCount = 0;
        $failedCount = 0;
        $messages = [];

        foreach ($items as $item) {
            $subdomain = $item['subdomain'] ?? null;
            $productId = $item['product_id'] ?? null;

            if (empty($subdomain) || empty($productId)) {
                $failedCount++;
                continue;
            }

            $res = $this->copyProductToStore($subdomain, (int)$productId, $targetVendorId);
            if ($res['success']) {
                $successCount++;
                $messages[] = $res['message'];
            } else {
                $failedCount++;
                $messages[] = $res['message'];
            }
        }

        return [
            'success' => $successCount > 0,
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'messages' => $messages,
        ];
    }
}
