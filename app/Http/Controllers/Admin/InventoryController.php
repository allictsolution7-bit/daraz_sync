<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\VariationCombination;
use App\Services\StockManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    protected $stockService;

    public function __construct(StockManagementService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display inventory overview
     */
    public function index()
    {
        $products = Product::with(['variationCombinations' => function($query) {
                $query->where('is_active', true)
                      ->select('id', 'product_id', 'stock_quantity', 'regular_price', 'offer_price', 'product_cost', 'wholesale_price');
            }])
            ->select([
                'id',
                'product_type',
                'quantity',
                'manage_stock',
                'low_stock_threshold',
                'offer',
                'old_price',
                'product_cost',
                'wholesale_price',
                'stock_status'
            ])
            ->get();

        $computeQuantity = static function (Product $product) {
            if ($product->product_type === 'variable') {
                return (int) $product->variationCombinations->sum('stock_quantity');
            }

            return (int) ($product->quantity ?? 0);
        };

        $determineStatus = static function (Product $product, int $quantity) {
            if ($quantity <= 0) {
                return 'out_of_stock';
            }

            if ($product->manage_stock && $product->low_stock_threshold !== null && $product->low_stock_threshold > 0) {
                if ($quantity <= $product->low_stock_threshold) {
                    return 'low_stock';
                }
            }

            return 'in_stock';
        };

        $stats = [
            'total_products' => $products->count(),
            'in_stock_products' => 0,
            'out_of_stock_products' => 0,
            'low_stock_products' => 0,
            'total_stock_value' => 0,
            'total_cost_value' => 0,
            'total_wholesale_value' => 0,
        ];

        foreach ($products as $product) {
            $quantity = $computeQuantity($product);
            $status = $determineStatus($product, $quantity);

            if ($product->product_type === 'variable') {
                foreach ($product->variationCombinations as $variation) {
                    $varQuantity = (int) $variation->stock_quantity;
                    
                    // Skip if no stock (optional, but good for accuracy if we only care about value of in-stock items)
                    // However, usually we want value of all stock.
                    
                    $varPrice = $variation->offer_price ?? $variation->regular_price ?? 0;
                    $varCost = $variation->product_cost ?? 0;
                    $varWholesale = $variation->wholesale_price ?? 0;

                    $stats['total_stock_value'] += $varPrice * $varQuantity;
                    $stats['total_cost_value'] += $varCost * $varQuantity;
                    $stats['total_wholesale_value'] += $varWholesale * $varQuantity;
                }
            } else {
                $price = $product->offer ?? $product->old_price ?? 0;
                $cost = $product->product_cost ?? 0;
                $wholesale = $product->wholesale_price ?? 0;

                $stats['total_stock_value'] += $price * $quantity;
                $stats['total_cost_value'] += $cost * $quantity;
                $stats['total_wholesale_value'] += $wholesale * $quantity;
            }

            match ($status) {
                'in_stock' => $stats['in_stock_products']++,
                'out_of_stock' => $stats['out_of_stock_products']++,
                'low_stock' => $stats['low_stock_products']++,
                default => null,
            };
        }

        $stats['total_profit'] = $stats['total_stock_value'] - $stats['total_cost_value'];

        return view('admin.inventory.index', compact('stats'));
    }

    /**
     * Get inventory data for DataTables
     */
    public function data(Request $request)
    {
        $variationStockSubquery = VariationCombination::selectRaw('product_id, SUM(stock_quantity) as total_stock')
            ->where('is_active', true)
            ->groupBy('product_id');

        $variableQuantityExpression = 'COALESCE(variation_stock.total_stock, 0)';
        $simpleQuantityExpression = 'COALESCE(products.quantity, 0)';
        $computedQuantityExpression = "CASE WHEN products.product_type = 'variable' THEN $variableQuantityExpression ELSE $simpleQuantityExpression END";
        $lowStockConditionExpression = "products.manage_stock = 1 AND products.low_stock_threshold IS NOT NULL AND products.low_stock_threshold > 0 AND $computedQuantityExpression <= products.low_stock_threshold";
        $computedStatusExpression = "CASE WHEN $computedQuantityExpression <= 0 THEN 'out_of_stock' WHEN $lowStockConditionExpression THEN 'low_stock' ELSE 'in_stock' END";

        $query = Product::with([
                'category',
                'additionalCategories',
                'additionalSubCategories',
                'thirdCategories',
                'variationCombinations' => function($query) {
                    $query->where('is_active', true);
                }
            ])
            ->leftJoinSub($variationStockSubquery, 'variation_stock', function ($join) {
                $join->on('products.id', '=', 'variation_stock.product_id');
            })
            ->select('products.*')
            ->selectRaw("$variableQuantityExpression as variation_stock_total")
            ->selectRaw("$computedQuantityExpression as computed_quantity")
            ->selectRaw("$computedStatusExpression as computed_stock_status");

        return DataTables::eloquent($query)
            ->filter(function ($q) use ($request, $computedStatusExpression) {
                // Multi-category filters
                
                // Filter by primary category
                if ($request->filled('primary_category_id')) {
                    $categoryId = $request->primary_category_id;
                    $q->where(function($subQ) use ($categoryId) {
                        $subQ->where('products.category_id', $categoryId)
                             ->orWhereHas('additionalCategories', function($catQ) use ($categoryId) {
                                 $catQ->where('product_categories.id', $categoryId);
                             });
                    });
                }
                
                // Filter by subcategory (primary or additional)
                if ($request->filled('subcategory_id')) {
                    $subCategoryId = $request->subcategory_id;
                    $q->where(function($subQ) use ($subCategoryId) {
                        $subQ->where('products.sub_category_id', $subCategoryId)
                             ->orWhereHas('additionalSubCategories', function($catQ) use ($subCategoryId) {
                                 $catQ->where('sub_categories.id', $subCategoryId);
                             });
                    });
                }
                
                // Filter by third category
                if ($request->filled('third_category_id')) {
                    $thirdCategoryId = $request->third_category_id;
                    $q->whereHas('thirdCategories', function($subQ) use ($thirdCategoryId) {
                        $subQ->where('third_categories.id', $thirdCategoryId);
                    });
                }
                
                if ($request->filled('stock_status')) {
                    if ($request->stock_status === 'on_backorder') {
                        $q->where('products.stock_status', 'on_backorder');
                    } else {
                        $q->whereRaw("$computedStatusExpression = ?", [$request->stock_status]);
                    }
                }
                
                if ($request->filled('product_type')) {
                    $q->where('product_type', $request->product_type);
                }
                
                if ($request->filled('low_stock_only') && $request->low_stock_only == '1') {
                    $q->whereRaw("$computedStatusExpression = 'low_stock'");
                }

                $searchValue = null;
                if ($request->filled('search') && is_string($request->search)) {
                    $searchValue = $request->search;
                } elseif ($request->filled('search.value')) {
                    $searchValue = $request->input('search.value');
                }

                if ($searchValue) {
                    $q->where(function($query) use ($searchValue) {
                        $query->where('products.title', 'like', "%{$searchValue}%")
                              ->orWhere('products.id', $searchValue);
                    });
                }
            })
            ->addColumn('product_info', function ($product) {
                $image = $product->thumb_image ? asset('storage/' . $product->thumb_image) : asset('assets/img/no-image.png');
                return '<div class="d-flex align-items-center">
                    <img src="' . $image . '" alt="' . e($product->title) . '" class="inventory-thumb me-2">
                    <div>
                        <div class="fw-bold">' . e($product->title) . '</div>
                        <small class="text-muted">ID: ' . $product->id . ' | ' . ucfirst($product->product_type) . '</small>
                    </div>
                </div>';
            })
            ->addColumn('stock_info', function ($product) {
                $stockHtml = '<div class="stock-info">';

                if ($product->product_type === 'variable') {
                    $totalStock = (int) ($product->computed_quantity ?? 0);
                    $inStockCombinations = $product->variationCombinations->where('stock_quantity', '>', 0)->count();
                    $totalCombinations = $product->variationCombinations->count();

                    $stockHtml .= '<div class="fw-bold">' . $totalStock . ' units total</div>';
                    $stockHtml .= '<small class="text-muted">' . $inStockCombinations . '/' . $totalCombinations . ' variations in stock</small>';
                } else {
                    $quantity = (int) ($product->computed_quantity ?? 0);

                    $stockHtml .= '<div class="fw-bold">' . $quantity . ' units</div>';

                    if (($product->computed_stock_status ?? null) === 'low_stock') {
                        $stockHtml .= '<small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Low Stock</small>';
                    }
                }

                $computedStatus = $product->computed_stock_status ?? 'in_stock';

                $statusClass = match($computedStatus) {
                    'in_stock' => 'success',
                    'low_stock' => 'warning',
                    'out_of_stock' => 'danger',
                    'on_backorder' => 'warning',
                    default => 'secondary'
                };

                $stockHtml .= '<br><span class="badge bg-' . $statusClass . '">' . ucfirst(str_replace('_', ' ', $computedStatus)) . '</span>';
                $stockHtml .= '</div>';

                return $stockHtml;
            })
            ->addColumn('value', function ($product) {
                $totalValue = 0;
                $totalCost = 0;
                $totalWholesale = 0;
                $quantity = (int) ($product->computed_quantity ?? 0);
                
                if ($product->product_type === 'variable') {
                    // Calculate from variations
                    foreach ($product->variationCombinations as $variation) {
                        $varQuantity = (int) $variation->stock_quantity;
                        $varPrice = $variation->offer_price ?? $variation->regular_price ?? 0;
                        $varCost = $variation->product_cost ?? 0;
                        $varWholesale = $variation->wholesale_price ?? 0;
                        
                        $totalValue += $varPrice * $varQuantity;
                        $totalCost += $varCost * $varQuantity;
                        $totalWholesale += $varWholesale * $varQuantity;
                    }
                    
                    $avgPrice = $quantity > 0 ? $totalValue / $quantity : 0;
                    $avgCost = $quantity > 0 ? $totalCost / $quantity : 0;
                    $avgWholesale = $quantity > 0 ? $totalWholesale / $quantity : 0;
                } else {
                    // Simple product calculation
                    $price = $product->offer ?? $product->old_price ?? 0;
                    $cost = $product->product_cost ?? 0;
                    $wholesalePrice = $product->wholesale_price ?? 0;
                    
                    $totalValue = $price * $quantity;
                    $totalCost = $cost * $quantity;
                    $totalWholesale = $wholesalePrice * $quantity;
                    
                    $avgPrice = $price;
                    $avgCost = $cost;
                    $avgWholesale = $wholesalePrice;
                }
                
                $profit = $totalValue - $totalCost;
                
                $html = '<div class="text-end">';
                $html .= '<div class="fw-bold text-success">৳' . number_format($totalValue, 2) . '</div>';
                $html .= '<small class="text-muted">Retail @৳' . number_format($avgPrice, 2) . '</small><br>';
                
                if ($totalCost > 0) {
                    $html .= '<small class="text-info">Cost: ৳' . number_format($totalCost, 2) . '</small><br>';
                    $html .= '<small class="' . ($profit > 0 ? 'text-success' : 'text-danger') . '">Profit: ৳' . number_format($profit, 2) . '</small>';
                }
                
                if ($totalWholesale > 0) {
                    $html .= '<br><small class="text-warning">Wholesale @৳' . number_format($avgWholesale, 2) . '</small>';
                }
                
                $html .= '</div>';
                
                return $html;
            })
            ->addColumn('actions', function ($product) {
                $actions = '<div class="btn-group" role="group">';
                
                // Adjust Stock button
                $actions .= '<button type="button" class="btn btn-sm btn-outline-primary adjust-stock-btn" 
                    data-product-id="' . $product->id . '" 
                    data-product-title="' . e($product->title) . '"
                    data-product-type="' . $product->product_type . '"
                    title="Adjust Stock">
                    <i class="fas fa-edit"></i>
                </button>';
                
                // View History button
                $actions .= '<a href="' . route('admin.inventory.product-history', $product->id) . '" 
                    class="btn btn-sm btn-outline-info" title="Stock History">
                    <i class="fas fa-history"></i>
                </a>';
                
                // Edit Product button
                $actions .= '<a href="' . route('admin.products.edit', $product->id) . '" 
                    class="btn btn-sm btn-outline-secondary" title="Edit Product">
                    <i class="fas fa-cog"></i>
                </a>';
                
                $actions .= '</div>';
                
                return $actions;
            })
            ->rawColumns(['product_info', 'stock_info', 'value', 'actions'])
            ->toJson();
    }

    /**
     * Show low stock products
     */
    public function lowStock()
    {
        $lowStockProducts = Product::where('manage_stock', true)
            ->whereRaw('quantity <= low_stock_threshold')
            ->where('stock_status', 'in_stock')
            ->with(['category', 'variationCombinations'])
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        return view('admin.inventory.low-stock', compact('lowStockProducts'));
    }

    /**
     * Show out of stock products
     */
    public function outOfStock()
    {
        $outOfStockProducts = Product::where('stock_status', 'out_of_stock')
            ->with(['category', 'variationCombinations'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.inventory.out-of-stock', compact('outOfStockProducts'));
    }

    /**
     * Show stock adjustment form
     */
    public function adjustForm(Product $product)
    {
        $product->load(['category', 'variationCombinations']);
        
        return view('admin.inventory.adjust-form', compact('product'));
    }

    /**
     * Process stock adjustment
     */
    public function adjust(Request $request, Product $product)
    {
        if ($product->product_type === 'variable') {
            // Filter out empty adjustments
            $validAdjustments = collect($request->adjustments ?? [])->filter(function ($adjustment) {
                return !empty($adjustment['type']) && !empty($adjustment['quantity']) && !empty($adjustment['combination_id']);
            })->toArray();

            if (empty($validAdjustments)) {
                return back()->withErrors(['adjustments' => 'Please adjust at least one variation combination.']);
            }

            $request->merge(['adjustments' => $validAdjustments]);

            $request->validate([
                'adjustments' => 'required|array|min:1',
                'adjustments.*.combination_id' => 'required|exists:variation_combinations,id',
                'adjustments.*.quantity' => 'required|integer',
                'adjustments.*.type' => 'required|in:adjustment,restock,damage,inventory_count',
                'adjustments.*.notes' => 'nullable|string|max:255',
            ]);

            foreach ($validAdjustments as $adjustment) {
                $combination = VariationCombination::findOrFail($adjustment['combination_id']);
                
                $this->stockService->updateVariationCombinationStock(
                    $combination,
                    (int)$adjustment['quantity'],
                    $adjustment['type'],
                    null,
                    $adjustment['notes'] ?? 'Manual stock adjustment',
                    'Manual Adjustment'
                );
            }

            $message = 'Stock adjusted successfully for ' . count($validAdjustments) . ' variations.';
        } else {
            $request->validate([
                'quantity' => 'required|integer',
                'type' => 'required|in:adjustment,restock,damage,inventory_count',
                'notes' => 'nullable|string|max:255',
            ]);

            $this->stockService->updateSimpleProductStock(
                $product,
                (int)$request->quantity,
                $request->type,
                null,
                $request->notes ?? 'Manual stock adjustment',
                'Manual Adjustment'
            );

            $message = 'Stock adjusted successfully.';
        }

        return redirect()->route('admin.inventory.index')->with('success', $message);
    }

    /**
     * Show stock movement history
     */
    public function history(Request $request)
    {
        $query = StockMovement::with(['product', 'variationCombination', 'creator'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->paginate(50);
        
        // Get filter options
        $products = Product::select('id', 'title')->orderBy('title')->get();
        $types = ['initial', 'adjustment', 'sale', 'return', 'restock', 'damage', 'inventory_count'];

        return view('admin.inventory.history', compact('movements', 'products', 'types'));
    }

    /**
     * Show stock movement history for a specific product
     */
    public function productHistory(Product $product)
    {
        $movements = StockMovement::with(['variationCombination', 'creator'])
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.inventory.product-history', compact('product', 'movements'));
    }

    /**
     * Get variation combinations for AJAX
     */
    public function getVariationCombinations(Product $product)
    {
        if ($product->product_type !== 'variable') {
            return response()->json(['error' => 'Product is not variable'], 400);
        }

        $combinations = $product->variationCombinations()
            ->where('is_active', true)
            ->get()
            ->map(function ($combination) {
                return [
                    'id' => $combination->id,
                    'display_name' => $combination->display_name,
                    'stock_quantity' => $combination->stock_quantity,
                    'regular_price' => $combination->regular_price,
                ];
            });

        return response()->json($combinations);
    }

    /**
     * Get filtered statistics for AJAX
     */
    public function getFilteredStats(Request $request)
    {
        // Build the base query with active variations only
        $query = Product::with(['variationCombinations' => function($q) {
            $q->where('is_active', true)
              ->select('id', 'product_id', 'stock_quantity', 'regular_price', 'offer_price', 'product_cost', 'wholesale_price');
        }]);

        // Apply category filters
        if ($request->filled('primary_category_id')) {
            $categoryId = $request->primary_category_id;
            $query->where(function($subQ) use ($categoryId) {
                $subQ->where('category_id', $categoryId)
                     ->orWhereHas('additionalCategories', function($catQ) use ($categoryId) {
                         $catQ->where('product_categories.id', $categoryId);
                     });
            });
        }

        if ($request->filled('subcategory_id')) {
            $subCategoryId = $request->subcategory_id;
            $query->where(function($subQ) use ($subCategoryId) {
                $subQ->where('sub_category_id', $subCategoryId)
                     ->orWhereHas('additionalSubCategories', function($catQ) use ($subCategoryId) {
                         $catQ->where('sub_categories.id', $subCategoryId);
                     });
            });
        }

        if ($request->filled('third_category_id')) {
            $thirdCategoryId = $request->third_category_id;
            $query->whereHas('thirdCategories', function($subQ) use ($thirdCategoryId) {
                $subQ->where('third_categories.id', $thirdCategoryId);
            });
        }

        // Apply product type filter
        if ($request->filled('product_type')) {
            $query->where('product_type', $request->product_type);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Get filtered products
        $products = $query->select([
            'id',
            'product_type',
            'quantity',
            'manage_stock',
            'low_stock_threshold',
            'offer',
            'old_price',
            'product_cost',
            'wholesale_price',
            'stock_status'
        ])->get();

        // Calculate statistics
        $computeQuantity = static function (Product $product) {
            if ($product->product_type === 'variable') {
                return (int) $product->variationCombinations->sum('stock_quantity');
            }
            return (int) ($product->quantity ?? 0);
        };

        $determineStatus = static function (Product $product, int $quantity) {
            if ($quantity <= 0) {
                return 'out_of_stock';
            }
            if ($product->manage_stock && $product->low_stock_threshold !== null && $product->low_stock_threshold > 0) {
                if ($quantity <= $product->low_stock_threshold) {
                    return 'low_stock';
                }
            }
            return 'in_stock';
        };

        $stats = [
            'total_products' => 0,
            'in_stock_products' => 0,
            'out_of_stock_products' => 0,
            'low_stock_products' => 0,
            'total_stock_value' => 0,
            'total_cost_value' => 0,
            'total_wholesale_value' => 0,
        ];

        foreach ($products as $product) {
            $quantity = $computeQuantity($product);
            $status = $determineStatus($product, $quantity);

            // Apply stock status filter
            if ($request->filled('stock_status')) {
                if ($request->stock_status === 'on_backorder') {
                    if ($product->stock_status !== 'on_backorder') {
                        continue;
                    }
                } elseif ($status !== $request->stock_status) {
                    continue;
                }
            }

            // Apply low stock only filter
            if ($request->filled('low_stock_only') && $request->low_stock_only == '1') {
                if ($status !== 'low_stock') {
                    continue;
                }
            }

            // Count this product
            $stats['total_products']++;

            // Calculate values
            if ($product->product_type === 'variable') {
                foreach ($product->variationCombinations as $variation) {
                    $varQuantity = (int) $variation->stock_quantity;
                    $varPrice = $variation->offer_price ?? $variation->regular_price ?? 0;
                    $varCost = $variation->product_cost ?? 0;
                    $varWholesale = $variation->wholesale_price ?? 0;

                    $stats['total_stock_value'] += $varPrice * $varQuantity;
                    $stats['total_cost_value'] += $varCost * $varQuantity;
                    $stats['total_wholesale_value'] += $varWholesale * $varQuantity;
                }
            } else {
                $price = $product->offer ?? $product->old_price ?? 0;
                $cost = $product->product_cost ?? 0;
                $wholesale = $product->wholesale_price ?? 0;

                $stats['total_stock_value'] += $price * $quantity;
                $stats['total_cost_value'] += $cost * $quantity;
                $stats['total_wholesale_value'] += $wholesale * $quantity;
            }

            // Count by status
            match ($status) {
                'in_stock' => $stats['in_stock_products']++,
                'out_of_stock' => $stats['out_of_stock_products']++,
                'low_stock' => $stats['low_stock_products']++,
                default => null,
            };
        }

        $stats['total_profit'] = $stats['total_stock_value'] - $stats['total_cost_value'];

        return response()->json($stats);
    }
}
