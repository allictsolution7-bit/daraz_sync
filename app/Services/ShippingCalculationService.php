<?php

namespace App\Services;

use App\Models\BasicShippingSetting;
use App\Models\BasicShippingRule;
use App\Models\Product;
use App\Models\LandingPage;

class ShippingCalculationService
{
    private $cartItems = [];

    /**
     * Set cart items for shipping options calculation.
     */
    public function setCartItems($items)
    {
        $this->cartItems = $items;
        return $this;
    }

    /**
     * Calculate shipping cost for a given context.
     *
     * @param float $subtotal
     * @param array $items Array of cart items or single item
     * @param Product|LandingPage|null $context Specific product or landing page context
     * @return array ['cost' => float, 'method' => string, 'rules_applied' => array]
     */
    public function calculateShipping($subtotal, $items = [], $context = null)
    {
        // Store cart items for later use
        $this->cartItems = $items;
        
        // Step 1: Check for specific shipping rules on the context
        if ($context) {
            $specificRules = $this->getSpecificRules($context);
            if (!empty($specificRules)) {
                return $this->applyRules($specificRules, $subtotal, $items);
            }
        }

        // Step 2: Check for product-specific rules in cart items
        if (!empty($items)) {
            $productRules = $this->getProductRulesFromItems($items);
            if (!empty($productRules)) {
                return $this->applyRules($productRules, $subtotal, $items);
            }
        }

        // Step 3: Fall back to global basic shipping settings (scoped to product owner/vendor)
        $userId = null;
        if ($context && isset($context->vendor_id)) {
            $userId = $context->vendor_id ?: (isset($context->created_by) ? $context->created_by : null);
        } elseif (!empty($items)) {
            $firstItem = reset($items);
            if (isset($firstItem['product_id'])) {
                $p = Product::find($firstItem['product_id']);
                if ($p) {
                    $userId = $p->vendor_id ?: $p->created_by ?: null;
                }
            }
        }
        return $this->getGlobalShipping($subtotal, $userId);
    }

    /**
     * Get shipping rules for a specific model (Product or LandingPage).
     */
    private function getSpecificRules($model)
    {
        return BasicShippingRule::forModel($model)->get();
    }

    /**
     * Get shipping rules from cart items.
     */
    private function getProductRulesFromItems($items)
    {
        $rules = collect();
        
        foreach ($items as $item) {
            if (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $productRules = BasicShippingRule::forModel($product)->get();
                    $rules = $rules->merge($productRules);
                }
            }
        }

        return $rules->sortByDesc('priority');
    }

    /**
     * Apply shipping rules to calculate final cost.
     */
    private function applyRules($rules, $subtotal, $items)
    {
        foreach ($rules as $rule) {
            $result = $this->processRule($rule, $subtotal, $items);
            if ($result !== null) {
                return $result;
            }
        }

        $userId = null;
        if (!empty($items)) {
            $firstItem = reset($items);
            if (isset($firstItem['product_id'])) {
                $p = Product::find($firstItem['product_id']);
                if ($p) {
                    $userId = $p->vendor_id ?: $p->created_by ?: null;
                }
            }
        }
        return $this->getGlobalShipping($subtotal, $userId);
    }

    /**
     * Process individual shipping rule.
     */
    private function processRule($rule, $subtotal, $items)
    {
        // Check conditions
        if ($rule->conditions) {
            foreach ($rule->conditions as $cond) {
                if (!$this->checkCondition($cond, $subtotal, $items)) {
                    return null;
                }
            }
        }

        // Apply rule action
        switch ($rule->rule_type) {
            case BasicShippingRule::RULE_TYPE_OVERRIDE:
                return [
                    'cost' => $rule->rule_value,
                    'method' => 'Rule override cost',
                    'rules_applied' => [$rule->id]
                ];
            case BasicShippingRule::RULE_TYPE_PERCENTAGE:
                return [
                    'cost' => ($subtotal * $rule->rule_value) / 100,
                    'method' => 'Rule percentage cost',
                    'rules_applied' => [$rule->id]
                ];
            // Add more actions as needed
        }

        return null;
    }

    /**
     * Check individual condition.
     */
    private function checkCondition($cond, $subtotal, $items)
    {
        $type = $cond['type'] ?? '';
        $operator = $cond['operator'] ?? '=';
        $value = $cond['value'] ?? '';

        // Add actual check implementation
        switch ($type) {
            case 'total_items':
                $totalQuantity = array_sum(array_column($items, 'quantity'));
                if ($totalQuantity < $value) {
                    return false;
                }
                break;

            case 'min_amount':
                if ($subtotal < $value) {
                    return false;
                }
                break;

            case 'max_items':
                if (count($items) > $value) {
                    return false;
                }
                break;

            // Add more conditions as needed
        }

        return true;
    }

    /**
     * Get global shipping cost from BasicShippingSetting.
     */
    private function getGlobalShipping($subtotal, $userId = null)
    {
        $query = BasicShippingSetting::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }
        $shippingSetting = $query->first();

        // Fallback to first settings or defaults if scoped row not set
        if (!$shippingSetting) {
            $shippingSetting = BasicShippingSetting::first() ?? new BasicShippingSetting([
                'flat_rate' => 80.00,
                'shipping_options' => [
                    'inside_dhaka' => ['name' => 'Inside Dhaka', 'cost' => 80.00, 'active' => true, 'position' => 1],
                    'outside_dhaka' => ['name' => 'Outside Dhaka', 'cost' => 110.00, 'active' => true, 'position' => 2],
                ],
                'free_shipping_threshold' => 1500.00,
            ]);
        }

        // Filter active shipping options and sort by position
        $activeShippingOptions = array_filter($shippingSetting->shipping_options ?? [], fn($option) => $option['active'] ?? false);
        uasort($activeShippingOptions, fn($a, $b) => ($a['position'] ?? 999) <=> ($b['position'] ?? 999));

        // Determine default shipping cost
        $shipping = $shippingSetting->flat_rate;
        if (!empty($activeShippingOptions)) {
            $firstOption = reset($activeShippingOptions);
            $shipping = $firstOption['cost'];
        }

        // Apply free shipping if threshold met
        if ($subtotal >= $shippingSetting->free_shipping_threshold) {
            $shipping = 0;
        }

        return [
            'cost' => $shipping,
            'method' => 'Global shipping settings',
            'rules_applied' => []
        ];
    }

    /**
     * Get available shipping options for display.
     */
    public function getShippingOptions($context = null)
    {
        // If context has specific rules, return those
        if ($context) {
            $rules = $this->getSpecificRules($context);
            if (!empty($rules)) {
                return $this->formatRulesAsOptions($rules);
            }
        }

        // Check for shipping rules in cart items
        if (!empty($this->cartItems)) {
            $allRules = $this->getAllRulesFromItems($this->cartItems);
            if (!empty($allRules)) {
                return $this->formatAllRulesAsOptions($allRules);
            }
        }

        // Retrieve product creator / vendor ID to load appropriate settings options
        $userId = null;
        if ($context) {
            if ($context instanceof Product) {
                $userId = $context->vendor_id ?: $context->created_by ?: null;
            } elseif (isset($context->vendor_id)) {
                $userId = $context->vendor_id ?: (isset($context->created_by) ? $context->created_by : null);
            }
        } elseif (!empty($this->cartItems)) {
            $firstItem = reset($this->cartItems);
            if (isset($firstItem['product_id'])) {
                $p = Product::find($firstItem['product_id']);
                if ($p) {
                    $userId = $p->vendor_id ?: $p->created_by ?: null;
                }
            }
        }

        $query = BasicShippingSetting::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }
        $shippingSetting = $query->first();

        // Fallback to first available setting row if vendor setting row is empty
        if (!$shippingSetting) {
            $shippingSetting = BasicShippingSetting::first();
        }

        if ($shippingSetting && $shippingSetting->shipping_options) {
            $activeOptions = array_filter($shippingSetting->shipping_options, fn($option) => $option['active'] ?? false);
            uasort($activeOptions, fn($a, $b) => ($a['position'] ?? 999) <=> ($b['position'] ?? 999));
            return $activeOptions;
        }

        return [];
    }

    /**
     * Format shipping rules as options for frontend display.
     */
    private function formatRulesAsOptions($rules)
    {
        $options = [];
        foreach ($rules as $rule) {
            $options[$rule->id] = [
                'name' => $this->getRuleDisplayName($rule),
                'cost' => $rule->rule_value ?? 0,
                'active' => true,
                'position' => $rule->priority,
                'rule_type' => $rule->rule_type
            ];
        }
        return $options;
    }

    /**
     * Get display name for shipping rule.
     */
    private function getRuleDisplayName($rule)
    {
        switch ($rule->rule_type) {
            case BasicShippingRule::RULE_TYPE_OVERRIDE:
                return 'Free Shipping';
            case BasicShippingRule::RULE_TYPE_CUSTOM_COST:
                return 'Custom Shipping';
            case BasicShippingRule::RULE_TYPE_PERCENTAGE:
                return $rule->rule_value . '% of Order';
            case 'delivery_area':
                return $rule->delivery_area_name;
            default:
                return 'Special Shipping';
        }
    }

    /**
     * Get all shipping rules from cart items.
     */
    private function getAllRulesFromItems($items)
    {
        $rules = collect();
        
        foreach ($items as $item) {
            if (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $productRules = BasicShippingRule::where('ruleable_type', Product::class)
                        ->where('ruleable_id', $product->id)
                        ->where('is_active', true)
                        ->orderBy('priority', 'asc')
                        ->get();
                    $rules = $rules->merge($productRules);
                }
            }
        }

        return $rules->unique('id');
    }

    /**
     * Get delivery area rules from cart items.
     */
    private function getDeliveryAreaRulesFromItems($items)
    {
        $rules = collect();
        
        foreach ($items as $item) {
            if (isset($item['product_id'])) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $deliveryAreaRules = BasicShippingRule::where('ruleable_type', Product::class)
                        ->where('ruleable_id', $product->id)
                        ->where('rule_type', 'delivery_area')
                        ->where('is_active', true)
                        ->orderBy('priority', 'asc')
                        ->get();
                    $rules = $rules->merge($deliveryAreaRules);
                }
            }
        }

        return $rules->unique('delivery_area_slug');
    }

    /**
     * Format all shipping rules as options for frontend display.
     */
    private function formatAllRulesAsOptions($rules)
    {
        $options = [];
        
        foreach ($rules as $rule) {
            switch ($rule->rule_type) {
                case 'delivery_area':
                    $options[$rule->delivery_area_slug] = [
                        'name' => $rule->delivery_area_name,
                        'cost' => $rule->rule_value,
                        'active' => true,
                        'position' => $rule->priority,
                        'rule_type' => 'delivery_area'
                    ];
                    break;
                    
                case 'override':
                    $options['free_shipping'] = [
                        'name' => 'Free Shipping',
                        'cost' => 0,
                        'active' => true,
                        'position' => $rule->priority,
                        'rule_type' => 'override'
                    ];
                    break;
                    
                case 'free_shipping':
                    $options['free_shipping_threshold'] = [
                        'name' => 'Free Shipping (Above ৳' . $rule->free_shipping_threshold . ')',
                        'cost' => 0,
                        'active' => true,
                        'position' => $rule->priority,
                        'rule_type' => 'free_shipping',
                        'threshold' => $rule->free_shipping_threshold
                    ];
                    break;
                    
                case 'custom_cost':
                    $options['custom_shipping'] = [
                        'name' => 'Custom Shipping',
                        'cost' => $rule->rule_value,
                        'active' => true,
                        'position' => $rule->priority,
                        'rule_type' => 'custom_cost'
                    ];
                    break;
                    
                case 'percentage':
                    $options['percentage_shipping'] = [
                        'name' => 'Percentage Shipping (' . $rule->rule_value . '%)',
                        'cost' => $rule->rule_value,
                        'active' => true,
                        'position' => $rule->priority,
                        'rule_type' => 'percentage'
                    ];
                    break;
            }
        }
        
        // Sort by position
        uasort($options, fn($a, $b) => $a['position'] <=> $b['position']);
        
        return $options;
    }

    /**
     * Format delivery area rules as options for frontend display.
     */
    private function formatDeliveryAreaRulesAsOptions($rules)
    {
        $options = [];
        foreach ($rules as $rule) {
            $options[$rule->delivery_area_slug] = [
                'name' => $rule->delivery_area_name,
                'cost' => $rule->rule_value,
                'active' => true,
                'position' => $rule->priority,
                'rule_type' => 'delivery_area'
            ];
        }
        
        // Sort by position
        uasort($options, fn($a, $b) => $a['position'] <=> $b['position']);
        
        return $options;
    }
}
