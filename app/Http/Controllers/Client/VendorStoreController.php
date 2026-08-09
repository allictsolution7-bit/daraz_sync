<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class VendorStoreController extends Controller
{
    /**
     * Display vendor's public store page
     */
    public function show(Request $request, $slug)
    {
        // Get vendor by slug with their settings
        $vendor = User::with('vendorSettings')
            ->whereHas('vendorSettings', function ($query) use ($slug) {
                $query->where('store_slug', $slug)
                      ->where('is_active', true)
                      ->where('is_verified', true);
            })
            ->first();

        if (!$vendor) {
            $vendor = User::with('vendorSettings')
                ->where('id', $slug)
                ->orWhere('name', 'like', str_replace('-', ' ', $slug))
                ->firstOrFail();
        }

        // Base query for seller's products (approved vendor products OR admin products created by this user)
        $vendorProductBaseQuery = function() use ($vendor) {
            return Product::where(function($q) use ($vendor) {
                $q->where(function($sub) use ($vendor) {
                    $sub->where('vendor_id', $vendor->id)
                        ->where('approval_status', 'approved');
                })->orWhere(function($sub) use ($vendor) {
                    $sub->whereNull('vendor_id')
                        ->where('created_by', $vendor->id);
                });
            })->where('status', 1);
        };

        // Get vendor's approved products
        $query = $vendorProductBaseQuery()->with(['category', 'brand']);

        // Category filter (checks both primary and additional categories)
        if ($request->has('category') && $request->category) {
            $query->where(function($q) use ($request) {
                $q->where('category_id', $request->category)
                  ->orWhereHas('additionalCategories', function($subQ) use ($request) {
                      $subQ->where('category_id', $request->category);
                  });
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('offer', 'asc');
                break;
            case 'price_high':
                $query->orderBy('offer', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(20);

        // Get vendor's categories (distinct from their products - includes additional categories)
        $vendorCategoryIds = $vendorProductBaseQuery()
            ->with(['category', 'additionalCategories'])
            ->get()
            ->flatMap(function($product) {
                $categories = collect();
                if ($product->category) {
                    $categories->push($product->category);
                }
                if ($product->additionalCategories) {
                    $categories = $categories->merge($product->additionalCategories);
                }
                return $categories;
            })
            ->unique('id')
            ->filter()
            ->values();
        
        $vendorCategories = $vendorCategoryIds;

        // Get vendor stats
        $stats = [
            'total_products' => $vendorProductBaseQuery()->count(),
            'total_reviews' => 0, // Can be enhanced later
            'rating' => 0, // Can be enhanced later
        ];

        // SEO Data
        $seoTitle = $vendor->name . ' - Vendor Store | ' . SettingsService::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = ($vendor->vendorSettings && $vendor->vendorSettings->business_description) ? $vendor->vendorSettings->business_description : 'Shop products from ' . $vendor->name;
        $seoKeywords = $vendor->name . ', vendor, shop, products';
        $seoImage = ($vendor->vendorSettings && $vendor->vendorSettings->business_logo) 
            ? asset('storage/' . $vendor->vendorSettings->business_logo) 
            : SettingsService::getDefaultOgImage();

        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage);

        return view('frontend.vendor-store', compact(
            'vendor',
            'products',
            'vendorCategories',
            'stats'
        ) + $seoData);
    }

    /**
     * Get SEO data for any page
     */
    private function getSeoData($title = null, $description = null, $keywords = null, $image = null, $type = 'website')
    {
        return [
            'metaTitle' => $title ?? SettingsService::getDefaultMetaTitle(),
            'metaDescription' => $description ?? SettingsService::getDefaultMetaDescription(),
            'metaKeywords' => $keywords ?? SettingsService::getDefaultMetaKeywords(),
            'ogImage' => $image ?? SettingsService::getDefaultOgImage(),
            'ogType' => $type,
            'metaRobots' => SettingsService::getRobotsMeta(),
            'canonicalUrl' => url()->current(),
        ];
    }
}

