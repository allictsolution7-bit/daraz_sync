<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Page;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function ajaxSearch(Request $request)
    {
        $query = $request->get('query');
        $categoryId = $request->get('category_id');
        
        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'categories' => [],
                'subcategories' => [],
                'pages' => []
            ]);
        }
        
        // Search products with optional category filter
        $productsQuery = Product::where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('short_description', 'like', "%{$query}%")
              ->orWhere('tags', 'like', "%{$query}%");
        })->where('status', true);
        
        // Apply category filter if provided (checks both primary and additional categories)
        if ($categoryId && $categoryId !== '') {
            $productsQuery->where(function($q) use ($categoryId) {
                $q->where('category_id', $categoryId)
                  ->orWhereHas('additionalCategories', function($subQ) use ($categoryId) {
                      $subQ->where('category_id', $categoryId);
                  });
            });
        }
        
        $products = $productsQuery->with(['variationCombinations'])
            ->limit(5)
            ->get();
            
        // Search categories
        $categories = ProductCategory::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->where('status', 'active')
            ->limit(3)
            ->get();
            
        // Search subcategories
        $subcategories = SubCategory::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->where('status', true)
            ->limit(3)
            ->get();
            
        // Search brands
        $brands = Brand::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->where('status', true)
            ->limit(3)
            ->get();
            
        // Search pages
        $pages = Page::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->where('status', true)
            ->limit(3)
            ->get();
            
        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'brands' => $brands,
            'pages' => $pages
        ]);
    }
}