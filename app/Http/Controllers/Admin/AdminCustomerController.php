<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of all customers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query all users who are not administrative/vendor roles
        $query = User::query()
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['super_admin', 'super admin', 'admin', 'vendor', 'reseller', 'vendor_staff']);
            })
            ->with(['creator.roles'])
            ->withCount(['orders'])
            ->withSum(['orders as lifetime_spend' => function ($q) {
                $q->whereNotIn('status', ['cancelled', 'returned']);
            }], 'total');

        // Search logic
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest('id')->paginate(15);

        // Calculate general statistics for summary cards
        $totalCustomers = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['super_admin', 'super admin', 'admin', 'vendor', 'reseller', 'vendor_staff']);
        })->count();

        // Total orders placed by customers
        $totalOrders = \App\Models\order::whereIn('user_id', 
            User::select('id')->whereDoesntHave('roles', function ($sub) {
                $sub->whereIn('name', ['super_admin', 'super admin', 'admin', 'vendor', 'reseller', 'vendor_staff']);
            })
        )->count();

        // Lifetime revenue from customers
        $totalSpend = \App\Models\order::whereIn('user_id', 
            User::select('id')->whereDoesntHave('roles', function ($sub) {
                $sub->whereIn('name', ['super_admin', 'super admin', 'admin', 'vendor', 'reseller', 'vendor_staff']);
            })
        )
        ->whereNotIn('status', ['cancelled', 'returned'])
        ->sum('total');

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'totalOrders', 'totalSpend', 'search'));
    }
}
