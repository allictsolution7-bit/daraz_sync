<?php

namespace App\Services;

use App\Models\ComboOffer;
use App\Models\ComboSelection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ComboOfferService
{
    /**
     * Get active combo offers for a product.
     */
    public function getActiveComboOffers($productId)
    {
        return ComboOffer::with(['activeItems.product', 'activeItems.variationCombination'])
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get combo offer with items.
     */
    public function getComboOffer($comboOfferId)
    {
        return ComboOffer::with(['activeItems.product', 'activeItems.variationCombination'])
            ->findOrFail($comboOfferId);
    }

    /**
     * Get current user's session ID.
     */
    private function getSessionId()
    {
        if (!Session::has('combo_session_id')) {
            Session::put('combo_session_id', Str::random(32));
        }
        return Session::get('combo_session_id');
    }

    /**
     * Get current user's selections for a combo offer.
     */
    public function getUserSelections($comboOfferId, $userId = null)
    {
        $query = ComboSelection::with(['product', 'variationCombination'])
            ->where('combo_offer_id', $comboOfferId);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->get();
    }

    /**
     * Add a selection to a combo offer.
     */
    public function addSelection($comboOfferId, $productId, $variationCombinationId = null, $quantity = 1, $userId = null)
    {
        // Validate combo offer
        $comboOffer = $this->getComboOffer($comboOfferId);
        
        if (!$comboOffer->isValid()) {
            throw new \Exception('Invalid combo offer');
        }

        // Check if product is available in this combo
        $comboItem = $comboOffer->activeItems()
            ->where('product_id', $productId)
            ->when($variationCombinationId, function($query) use ($variationCombinationId) {
                return $query->where('variation_combination_id', $variationCombinationId);
            })
            ->first();

        if (!$comboItem) {
            throw new \Exception('Product not available in this combo offer');
        }

        // Check if variation combination is valid
        if ($variationCombinationId) {
            $variationCombination = $comboItem->variationCombination;
            if (!$variationCombination || !$variationCombination->isInStock()) {
                throw new \Exception('Selected variation is not available');
            }
        }

        // Get current selections
        $currentSelections = $this->getUserSelections($comboOfferId, $userId);

        // Check if we can add more selections
        if ($currentSelections->count() >= $comboOffer->items_count) {
            throw new \Exception('Maximum number of items already selected for this combo');
        }

        // Create or update selection
        $selectionData = [
            'combo_offer_id' => $comboOfferId,
            'product_id' => $productId,
            'variation_combination_id' => $variationCombinationId,
            'quantity' => $quantity,
        ];

        if ($userId) {
            $selectionData['user_id'] = $userId;
        } else {
            $selectionData['session_id'] = $this->getSessionId();
        }

        return ComboSelection::create($selectionData);
    }

    /**
     * Update a selection.
     */
    public function updateSelection($selectionId, $data, $userId = null)
    {
        $query = ComboSelection::where('id', $selectionId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        $selection = $query->firstOrFail();
        $selection->update($data);

        return $selection;
    }

    /**
     * Remove a selection.
     */
    public function removeSelection($selectionId, $userId = null)
    {
        $query = ComboSelection::where('id', $selectionId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->delete();
    }

    /**
     * Clear all selections for a combo offer.
     */
    public function clearSelections($comboOfferId, $userId = null)
    {
        $query = ComboSelection::where('combo_offer_id', $comboOfferId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->delete();
    }

    /**
     * Check if combo selection is complete.
     */
    public function isSelectionComplete($comboOfferId, $userId = null)
    {
        $comboOffer = $this->getComboOffer($comboOfferId);
        $selections = $this->getUserSelections($comboOfferId, $userId);

        return $selections->count() >= $comboOffer->items_count;
    }

    /**
     * Get combo offer summary.
     */
    public function getComboSummary($comboOfferId, $userId = null)
    {
        $comboOffer = $this->getComboOffer($comboOfferId);
        $selections = $this->getUserSelections($comboOfferId, $userId);

        $totalOriginalPrice = $selections->sum(function($selection) {
            if ($selection->variationCombination) {
                return $selection->variationCombination->regular_price * $selection->quantity;
            }
            return ($selection->product->old_price ?? 0) * $selection->quantity;
        });

        return [
            'combo_offer' => $comboOffer,
            'selections' => $selections,
            'selected_count' => $selections->count(),
            'required_count' => $comboOffer->items_count,
            'is_complete' => $this->isSelectionComplete($comboOfferId, $userId),
            'combo_price' => $comboOffer->combo_price,
            'original_price' => $totalOriginalPrice,
            'savings' => $totalOriginalPrice - $comboOffer->combo_price,
        ];
    }

    /**
     * Get available products for a combo offer.
     */
    public function getAvailableProducts($comboOfferId)
    {
        $comboOffer = $this->getComboOffer($comboOfferId);
        
        return $comboOffer->activeItems()
            ->with(['product', 'variationCombination'])
            ->get()
            ->filter(function($item) {
                return $item->isAvailable();
            })
            ->values(); // Convert to indexed array
    }
} 