<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorCustomerController extends Controller
{
    public function index(Request $request)
    {
        $reseller = Auth::user();
        if (!$reseller || !$reseller->hasRole('reseller')) {
            abort(403, 'Customer list is only available for Reseller accounts.');
        }

        $search = $request->input('search');

        $query = User::where(function ($q) use ($reseller) {
            $q->where('created_by', $reseller->id)
              ->orWhereIn('id', function ($sub) use ($reseller) {
                  $sub->select('orders.user_id')
                      ->from('orders')
                      ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                      ->where('order_items.vendor_id', $reseller->id)
                      ->whereNotNull('orders.user_id');
              });
        })
        ->withCount(['orders' => function ($q) use ($reseller) {
            $q->whereIn('id', function ($sub) use ($reseller) {
                $sub->select('order_id')
                    ->from('order_items')
                    ->where('vendor_id', $reseller->id);
            });
        }])
        ->withSum(['orders as lifetime_spend' => function ($q) use ($reseller) {
            $q->whereNotIn('status', ['cancelled', 'returned'])
              ->whereIn('id', function ($sub) use ($reseller) {
                  $sub->select('order_id')
                      ->from('order_items')
                      ->where('vendor_id', $reseller->id);
              });
        }], 'total');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15);

        // Quick statistics for header cards
        $totalCustomers = User::where(function ($q) use ($reseller) {
            $q->where('created_by', $reseller->id)
              ->orWhereIn('id', function ($sub) use ($reseller) {
                  $sub->select('orders.user_id')
                      ->from('orders')
                      ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                      ->where('order_items.vendor_id', $reseller->id)
                      ->whereNotNull('orders.user_id');
              });
        })->count();
        
        return view('vendor.customers.index', compact('customers', 'totalCustomers', 'search'));
    }
}
