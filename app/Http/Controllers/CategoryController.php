<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get main categories for mobile navigation
     */
    public function getMobileCategories(): JsonResponse
    {
        try {
            $categories = ProductCategory::select('id', 'name', 'slug', 'image')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            $formattedCategories = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image ? asset($category->image) : null,
                    'image_url' => $category->image ? asset($category->image) : asset('assets/icons/mobile-nav-icons.svg#icon-grid'),
                    'url' => route('shop', ['category' => $category->slug])
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedCategories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subcategories for a specific category
     */
    public function getMobileSubcategories($categoryId): JsonResponse
    {
        try {
            $subcategories = SubCategory::select('id', 'name', 'slug', 'image', 'product_category_id')
                ->where('product_category_id', $categoryId)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            // Group subcategories by name (you can modify this logic based on your needs)
            $groupedSubcategories = $subcategories->groupBy(function ($subcategory) {
                // For now, we'll create a simple grouping
                // You can modify this to group by a specific field or create custom groups
                return 'All Items';
            });

            $formattedGroups = $groupedSubcategories->map(function ($group, $groupName) {
                $items = $group->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'slug' => $subcategory->slug,
                        'image' => $subcategory->image ? asset($subcategory->image) : null,
                        'image_url' => $subcategory->image ? asset($subcategory->image) : asset('assets/icons/mobile-nav-icons.svg#icon-grid'),
                        'url' => route('shop', [
                            'category' => $subcategory->product_category_id,
                            'sub_category' => $subcategory->slug
                        ])
                    ];
                });

                return [
                    'name' => $groupName,
                    'items' => $items
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedGroups->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
