<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\VariationCombination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockManagementService
{
    /**
     * Update stock for a simple product
     */
    public function updateSimpleProductStock(
        Product $product,
        int $quantity,
        string $type,
        ?int $referenceId = null,
        ?string $notes = null,
        ?string $referenceType = null
    ): bool {
        if (!$product->manage_stock) {
            return true;
        }

        return DB::transaction(function () use ($product, $quantity, $type, $referenceId, $notes, $referenceType) {
            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'type' => $type,
                'reference_id' => $referenceId,
                'reference_type' => $referenceType,
                'notes' => $notes,
                'created_by' => Auth::id(),
            ]);

            // Update product quantity
            $newQuantity = $product->quantity + $quantity;
            
            // Update stock status based on new quantity
            $stockStatus = 'in_stock';
            if ($newQuantity <= 0) {
                $stockStatus = 'out_of_stock';
            } elseif ($newQuantity <= $product->low_stock_threshold) {
                // You might want to trigger a notification here
            }
            
            $product->update([
                'quantity' => $newQuantity,
                'stock_status' => $stockStatus,
            ]);
            
            return true;
        });
    }

    /**
     * Update stock for a variation combination
     */
    public function updateVariationCombinationStock(
        VariationCombination $combination,
        int $quantity,
        string $type,
        ?int $referenceId = null,
        ?string $notes = null,
        ?string $referenceType = null
    ): bool {
        $product = $combination->product;
        
        if (!$product || !$product->manage_stock) {
            return true;
        }

        return DB::transaction(function () use ($product, $combination, $quantity, $type, $referenceId, $notes, $referenceType) {
            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'variation_combination_id' => $combination->id,
                'quantity' => $quantity,
                'type' => $type,
                'reference_id' => $referenceId,
                'reference_type' => $referenceType,
                'notes' => $notes,
                'created_by' => Auth::id(),
            ]);

            // Update combination stock quantity
            $newQuantity = $combination->stock_quantity + $quantity;
            
            $combination->update([
                'stock_quantity' => $newQuantity,
            ]);
            
            // Check if we need to update the overall product stock status
            $this->updateProductStockStatusFromCombinations($product);
            
            return true;
        });
    }

    /**
     * Update product stock status based on variation combinations
     */
    private function updateProductStockStatusFromCombinations(Product $product): void
    {
        if ($product->product_type !== 'variable' || !$product->manage_stock) {
            return;
        }

        $allCombinations = VariationCombination::where('product_id', $product->id)
            ->where('is_active', true)
            ->get();

        $totalStock = $allCombinations->sum('stock_quantity');
        $anyInStock = $allCombinations->where('stock_quantity', '>', 0)->count() > 0;

        $stockStatus = 'out_of_stock';
        if ($anyInStock) {
            $stockStatus = 'in_stock';
        }

        $product->update([
            'quantity' => $totalStock,
            'stock_status' => $stockStatus,
        ]);
    }

    /**
     * Check if a product or variation is in stock
     */
    public function checkStock(Product $product, ?VariationCombination $combination = null, int $requestedQuantity = 1): bool
    {
        if (!$product->manage_stock) {
            return true;
        }

        // Variable product with combination
        if ($product->product_type === 'variable' && $combination) {
            return $combination->stock_quantity >= $requestedQuantity;
        }

        // Simple product
        return $product->quantity >= $requestedQuantity;
    }

    /**
     * Reserve stock for an order (before payment)
     */
    public function reserveStock(Product $product, ?VariationCombination $combination = null, int $quantity = 1, int $orderId = null): bool
    {
        // Variable product with combination
        if ($product->product_type === 'variable' && $combination) {
            return $this->updateVariationCombinationStock(
                $combination,
                -$quantity,
                'sale',
                $orderId,
                'Stock reserved for order #' . $orderId,
                'Order'
            );
        }

        // Simple product
        return $this->updateSimpleProductStock(
            $product,
            -$quantity,
            'sale',
            $orderId,
            'Stock reserved for order #' . $orderId,
            'Order'
        );
    }

    /**
     * Return stock if order is cancelled
     */
    public function returnStock(Product $product, ?VariationCombination $combination = null, int $quantity = 1, int $orderId = null): bool
    {
        // Variable product with combination
        if ($product->product_type === 'variable' && $combination) {
            return $this->updateVariationCombinationStock(
                $combination,
                $quantity,
                'return',
                $orderId,
                'Stock returned from cancelled order #' . $orderId,
                'Order'
            );
        }

        // Simple product
        return $this->updateSimpleProductStock(
            $product,
            $quantity,
            'return',
            $orderId,
            'Stock returned from cancelled order #' . $orderId,
            'Order'
        );
    }

    /**
     * Process stock reduction for cart items (used in checkout)
     */
    public function processCartStockReduction($cartItems, int $orderId): bool
    {
        return DB::transaction(function () use ($cartItems, $orderId) {
            foreach ($cartItems as $cart) {
                $product = $cart->product;
                
                if (!$product || !$product->manage_stock) {
                    continue;
                }

                // Handle combo cart items
                if ($cart->combo_offer_id && $cart->combo_selections) {
                    $comboSelections = is_string($cart->combo_selections) 
                        ? json_decode($cart->combo_selections, true) 
                        : $cart->combo_selections;
                    
                    if (is_array($comboSelections)) {
                        $this->processComboStockReduction($comboSelections, $orderId, $cart->qunt);
                    }
                }
                // Handle variable products with combinations
                elseif ($product->product_type === 'variable' && $cart->combination_id) {
                    $combination = VariationCombination::find($cart->combination_id);
                    if ($combination) {
                        $this->updateVariationCombinationStock(
                            $combination,
                            -$cart->qunt,
                            'sale',
                            $orderId,
                            'Stock sold for order #' . $orderId,
                            'Order'
                        );
                    }
                }
                // Handle simple products
                else {
                    $this->updateSimpleProductStock(
                        $product,
                        -$cart->qunt,
                        'sale',
                        $orderId,
                        'Stock sold for order #' . $orderId,
                        'Order'
                    );
                }
            }
            
            return true;
        });
    }

    /**
     * Process stock reduction for combo orders by processing combo selections
     */
    public function processComboStockReduction($comboSelections, int $orderId, int $quantity = 1): bool
    {
        return DB::transaction(function () use ($comboSelections, $orderId, $quantity) {
            foreach ($comboSelections as $selection) {
                $product = Product::find($selection['product_id']);
                if (!$product || !$product->manage_stock) {
                    continue;
                }

                if (isset($selection['variation_id']) && $selection['variation_id']) {
                    // Handle variable products with combinations
                    $variation = VariationCombination::find($selection['variation_id']);
                    if ($variation) {
                        $this->updateVariationCombinationStock(
                            $variation,
                            -$quantity,
                            'sale',
                            $orderId,
                            'Stock sold for combo order #' . $orderId,
                            'Combo Order'
                        );
                    }
                } else {
                    // Handle simple products
                    if ($product->product_type === 'simple') {
                        $this->updateSimpleProductStock(
                            $product,
                            -$quantity,
                            'sale',
                            $orderId,
                            'Stock sold for combo order #' . $orderId,
                            'Combo Order'
                        );
                    }
                }
            }
            
            return true;
        });
    }
}