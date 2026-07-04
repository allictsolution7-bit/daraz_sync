<?php

namespace App\Services;

use App\Models\BasicShippingRule;
use App\Models\ShippingZone;
use App\Models\ShippingRule;

class ShippingService
{
    public function calculateShipping($city, $orderAmount, $items, $totalWeight = 0)
    {
        $zone = ShippingZone::whereRaw("JSON_CONTAINS(regions, ?)", ['"' . $city . '"'])
                           ->where('is_active', true)
                           ->first();

        if (!$zone) {
            return $this->getGlobalShippingCost($orderAmount, $items, $totalWeight);
        }

        $rules = $zone->shippingRules()
                     ->where('is_active', true)
                     ->orderByDesc('priority')
                     ->get();

        foreach ($rules as $rule) {
            $cost = $this->processRule($rule, $orderAmount, $items, $totalWeight);
            if ($cost !== null) {
                return $cost;
            }
        }

        return $this->getGlobalShippingCost($orderAmount, $items, $totalWeight);
    }

    private function processRule($rule, $orderAmount, $items, $totalWeight)
    {
        switch ($rule->type) {
            case 'location_free':
                return 0;

            case 'location_min_amount':
                return $orderAmount >= $rule->min_amount ? 0 : $rule->shipping_cost;

            case 'location_paid':
                return $rule->shipping_cost;

            case 'product_specific':
                if ($this->hasEligibleProducts($rule, $items)) {
                    return 0;
                }
                break;

            case 'min_items':
                if ($items->sum('quantity') >= $rule->min_items) {
                    return 0;
                }
                return $rule->shipping_cost;

            case 'flat_rate':
                return $rule->shipping_cost;

            case 'global_min_amount':
                return $orderAmount >= $rule->min_amount ? 0 : $rule->shipping_cost;

            case 'weight_based':
                if ($totalWeight >= $rule->min_weight && $totalWeight <= $rule->max_weight) {
                    return $rule->shipping_cost;
                }
                break;

            case 'category_min_amount':
                $categoryTotal = $this->calculateCategoryTotal($rule->category_id, $items);
                return $categoryTotal >= $rule->min_amount ? 0 : $rule->shipping_cost;
        }

        return null;
    }

    private function hasEligibleProducts($rule, $items)
    {
        $eligibleProductIds = $rule->products->pluck('id')->toArray();
        foreach ($items as $item) {
            if (!in_array($item->product_id, $eligibleProductIds)) {
                return false;
            }
        }
        return true;
    }

    private function calculateCategoryTotal($categoryId, $items)
    {
        return $items->sum(function($item) use ($categoryId) {
            return $item->product->category_id == $categoryId 
                ? ($item->price * $item->quantity) 
                : 0;
        });
    }

    private function getGlobalShippingCost($orderAmount, $items, $totalWeight)
    {
        $globalRule = BasicShippingRule::whereNull('zone_id')
                                 ->where('is_active', true)
                                 ->orderByDesc('priority')
                                 ->first();
        
        if ($globalRule) {
            return $this->processRule($globalRule, $orderAmount, $items, $totalWeight) ?? config('shipping.default_cost', 100);
        }

        return config('shipping.default_cost', 100);
    }
}