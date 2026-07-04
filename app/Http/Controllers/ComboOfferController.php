<?php

namespace App\Http\Controllers;

use App\Models\ComboOffer;
use App\Models\Product;
use App\Services\ComboOfferService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ComboOfferController extends Controller
{
    protected $comboOfferService;

    public function __construct(ComboOfferService $comboOfferService)
    {
        $this->comboOfferService = $comboOfferService;
    }

    /**
     * Helper method to generate correct image URLs
     */
    private function getImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // If it starts with http, return as is
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }
        
        // If it starts with /, it's a relative path
        if (str_starts_with($imagePath, '/')) {
            return asset($imagePath);
        }
        
        // If it's a storage path, add storage prefix
        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }
        
        // Default: assume it's a storage path
        return asset('storage/' . $imagePath);
    }

    /**
     * Get combo offers for a product with multiple related products.
     */
    public function getComboOffers($productId): JsonResponse
    {
        try {
            // Get combo offers for this product
            $comboOffers = \App\Models\ComboOffer::where('product_id', $productId)
                ->where('is_active', true)
                ->with(['items.product.variationCombinations'])
                ->get();

            // Transform the data for frontend use
            $transformedOffers = $comboOffers->map(function ($comboOffer) {
                return [
                    'id' => $comboOffer->id,
                    'title' => $comboOffer->title,
                    'description' => $comboOffer->description,
                    'items_count' => $comboOffer->items_count,
                    'combo_price' => $comboOffer->combo_price,
                    'original_price' => $comboOffer->original_price,
                    'discount_amount' => $comboOffer->original_price - $comboOffer->combo_price,
                    'discount_percentage' => round((($comboOffer->original_price - $comboOffer->combo_price) / $comboOffer->original_price) * 100),
                    'available_items' => $comboOffer->activeItems->map(function ($item) {
                        $variations = [];
                        
                        if ($item->product && $item->product->variationCombinations) {
                            $variations = $item->product->variationCombinations->filter(function ($variation) {
                                return $variation->isInStock();
                            })->map(function ($variation) {
                                // Get the correct image URL
                                $imageUrl = null;
                                
                                // Check for variation image first
                                if ($variation->featured_image_url) {
                                    $imageUrl = $this->getImageUrl($variation->featured_image_url);
                                } 
                                // Fallback to product image
                                elseif ($variation->product && $variation->product->thumb_image) {
                                    $imageUrl = $this->getImageUrl($variation->product->thumb_image);
                                }
                                
                                return [
                                    'id' => $variation->id,
                                    'display_name' => $variation->display_name,
                                    'price' => $variation->effective_price,
                                    'stock_quantity' => $variation->stock_quantity,
                                    'is_in_stock' => $variation->isInStock(),
                                    'variation_options' => $variation->variation_options,
                                    'image' => $imageUrl,
                                ];
                            })->values();
                        }
                        
                        // Get the correct product image URL
                        $productImageUrl = null;
                        if ($item->product && $item->product->thumb_image) {
                            $productImageUrl = $this->getImageUrl($item->product->thumb_image);
                        }
                        
                        return [
                            'product_id' => $item->product_id,
                            'product_title' => $item->product->title ?? 'Unknown Product',
                            'product_image' => $productImageUrl,
                            'product_slug' => $item->product->slug ?? '',
                            'variations' => $variations,
                            'is_active' => $item->is_active,
                            'sort_order' => $item->sort_order,
                        ];
                    })->filter(function ($item) {
                        // Only include items that are active and have variations
                        return $item['is_active'] && count($item['variations']) > 0;
                    })->values(),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $transformedOffers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get combo offer details with available products.
     */
    public function getComboOfferDetails($comboOfferId): JsonResponse
    {
        try {
            $comboOffer = $this->comboOfferService->getComboOffer($comboOfferId);
            $availableProducts = $this->comboOfferService->getAvailableProducts($comboOfferId);
            $userId = auth()->id() ?? null;
            $summary = $this->comboOfferService->getComboSummary($comboOfferId, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'combo_offer' => $comboOffer,
                    'available_products' => $availableProducts,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a selection to combo offer.
     */
    public function addSelection(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'combo_offer_id' => 'required|exists:combo_offers,id',
                'product_id' => 'required|exists:products,id',
                'variation_combination_id' => 'nullable|exists:variation_combinations,id',
                'quantity' => 'integer|min:1|max:10'
            ]);

            $userId = auth()->id() ?? null;
            $selection = $this->comboOfferService->addSelection(
                $request->combo_offer_id,
                $request->product_id,
                $request->variation_combination_id,
                $request->quantity ?? 1,
                $userId
            );

            $summary = $this->comboOfferService->getComboSummary($request->combo_offer_id, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'selection' => $selection,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update a selection.
     */
    public function updateSelection(Request $request, $selectionId): JsonResponse
    {
        try {
            $request->validate([
                'variation_combination_id' => 'nullable|exists:variation_combinations,id',
                'quantity' => 'integer|min:1|max:10'
            ]);

            $userId = auth()->id() ?? null;
            $selection = $this->comboOfferService->updateSelection(
                $selectionId,
                $request->only(['variation_combination_id', 'quantity']),
                $userId
            );

            $summary = $this->comboOfferService->getComboSummary($selection->combo_offer_id, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'selection' => $selection,
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove a selection.
     */
    public function removeSelection($selectionId): JsonResponse
    {
        try {
            $userId = auth()->id() ?? null;
            $selection = \App\Models\ComboSelection::where('id', $selectionId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $comboOfferId = $selection->combo_offer_id;
            
            $this->comboOfferService->removeSelection($selectionId, $userId);
            
            $summary = $this->comboOfferService->getComboSummary($comboOfferId, $userId);

            return response()->json([
                'success' => true,
                'data' => [
                    'summary' => $summary
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Clear all selections for a combo offer.
     */
    public function clearSelections($comboOfferId): JsonResponse
    {
        try {
            $userId = auth()->id() ?? null;
            $this->comboOfferService->clearSelections($comboOfferId, $userId);

            return response()->json([
                'success' => true,
                'message' => 'All selections cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get combo offer summary.
     */
    public function getSummary($comboOfferId): JsonResponse
    {
        try {
            $userId = auth()->id() ?? null;
            $summary = $this->comboOfferService->getComboSummary($comboOfferId, $userId);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get available products for a combo offer.
     */
    public function getAvailableProducts($comboOfferId): JsonResponse
    {
        try {
            $availableProducts = $this->comboOfferService->getAvailableProducts($comboOfferId);

            return response()->json([
                'success' => true,
                'data' => $availableProducts->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get combo offers for a specific product with variation combinations
     */
    public function getProductComboOffers(Product $product)
    {
        $comboOffers = ComboOffer::where('product_id', $product->id)
            ->where('is_active', true)
            ->with(['items.product', 'items.variationCombination'])
            ->get();

        // Transform the data for frontend use
        $transformedOffers = $comboOffers->map(function ($comboOffer) {
            return [
                'id' => $comboOffer->id,
                'title' => $comboOffer->title,
                'description' => $comboOffer->description,
                'items_count' => $comboOffer->items_count,
                'combo_price' => $comboOffer->combo_price,
                'original_price' => $comboOffer->original_price,
                'discount_amount' => $comboOffer->discount_amount,
                'discount_percentage' => $comboOffer->discount_percentage,
                'available_items' => $comboOffer->activeItems->map(function ($item) {
                    $variationData = null;
                    
                    if ($item->variationCombination) {
                        $variationData = [
                            'id' => $item->variationCombination->id,
                            'display_name' => $item->variationCombination->display_name,
                            'price' => $item->variationCombination->effective_price,
                            'stock_quantity' => $item->variationCombination->stock_quantity,
                            'is_in_stock' => $item->variationCombination->isInStock(),
                            'variation_options' => $item->variationCombination->variation_options,
                        ];
                    }
                    
                    return [
                        'product_id' => $item->product_id,
                        'product_title' => $item->product->title,
                        'product_image' => $item->product->thumb_image,
                        'variation_combination' => $variationData,
                        'is_active' => $item->is_active,
                        'sort_order' => $item->sort_order,
                    ];
                })->filter(function ($item) {
                    // Only include items that are in stock
                    if ($item['variation_combination']) {
                        return $item['variation_combination']['is_in_stock'];
                    }
                    return $item['product_id'] && $item['is_active'];
                })->values(),
            ];
        });

        return response()->json($transformedOffers);
    }
} 