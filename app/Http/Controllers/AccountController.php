<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\Product;
use App\Models\VariationCombination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    /**
     * Show the account profile page.
     *
     * @return \Illuminate\View\View
     */

    public function show()
    {
        $user = auth()->user();
        try {
            $locations = $user->deliveryLocations;
        } catch (\Throwable $e) {
            $locations = collect();
        }
        return view('frontend.user.profile', compact('user', 'locations'));
    }

    /**
     * Store a new delivery location.
     */
    public function storeLocation(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'upazila' => 'nullable|string|max:100',
            'post_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        if ($request->has('is_default') && $request->is_default) {
            $user->deliveryLocations()->update(['is_default' => false]);
        }

        $isDefault = $request->has('is_default') ? (bool)$request->is_default : ($user->deliveryLocations()->count() === 0);

        $user->deliveryLocations()->create([
            'title' => $request->title,
            'address' => $request->address,
            'city' => $request->city,
            'division' => $request->division,
            'district' => $request->district,
            'upazila' => $request->upazila,
            'post_code' => $request->post_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => $isDefault,
        ]);

        return redirect()->back()->with('success', 'Delivery location saved successfully.');
    }

    /**
     * Delete a delivery location.
     */
    public function deleteLocation($id)
    {
        $location = auth()->user()->deliveryLocations()->findOrFail($id);
        $location->delete();

        return redirect()->back()->with('success', 'Delivery location deleted.');
    }

    /**
     * Set a delivery location as default.
     */
    public function setDefaultLocation($id)
    {
        $user = auth()->user();
        $user->deliveryLocations()->update(['is_default' => false]);
        $location = $user->deliveryLocations()->findOrFail($id);
        $location->update(['is_default' => true]);

        return redirect()->back()->with('success', 'Default location updated.');
    }

    /**
     * Show the account edit form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user(); // Get the authenticated user
        return view('frontend.user.edit-profile', compact('user')); // Pass user data to the edit form view
    }

    /**
     * Update the account information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable|required_with:password'
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Check current password if user is trying to change password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Remove password fields from validated data if not being updated
        $updateData = array_filter($validated, function ($key) {
            return !in_array($key, ['current_password', 'password', 'password_confirmation']);
        }, ARRAY_FILTER_USE_KEY);

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $path = $photo->store('profile_photos', 'public');

            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $updateData['profile_photo_path'] = $path;
        }

        // Update user information
        $user->update($updateData);

        return redirect()->route('account.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the user's order history.
     *
     * @return \Illuminate\View\View
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('order_items.product')->latest()->paginate(10);

        return view('frontend.user.orders', compact(['orders', 'user']));
    }
    /**
     * Show the details of a specific order.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = $user->orders()->with('order_items.product')->findOrFail($id);

        return view('frontend.user.order-detail', compact(['order', 'user']));
    }

    /**
     * Download digital product file
     *
     * @param int $order_id
     * @param int $product_id
     * @return \Illuminate\Http\Response
     */
    public function downloadDigitalProduct($order_id, $product_id)
    {
        // Get the order and check if it belongs to the authenticated user
        $order = order::where('id', $order_id)
            ->where('user_id', auth()->id())
            ->where('status', 'ready_for_delivery')
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found or not completed.');
        }

        // Check if the product exists in the order
        $orderItem = $order->order_items()
            ->whereHas('product', function ($query) use ($product_id) {
                $query->where('id', $product_id)
                    ->where('product_type', 'digital');
            })
            ->first();

        if (!$orderItem || !$orderItem->product || !$orderItem->product->digital_file) {
            return redirect()->back()->with('error', 'Digital product not found.');
        }

        // Get the file path
        $filePath = storage_path('app/public/' . $orderItem->product->digital_file);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get original filename from the path
        $originalName = pathinfo($orderItem->product->digital_file, PATHINFO_BASENAME);

        // Return the file as a download
        return response()->download($filePath, $originalName);
    }


    /**
     * Show the order tracking form
     *
     * @return \Illuminate\View\View
     */
    public function trackOrder()
    {
        return view('frontend.user.track');
    }

    /**
     * Track an order using order ID and phone number
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function trackOrderSubmit(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|numeric',
            'phone' => 'required|string',
        ]);

        // $order = order::where('id', $request->order_id)
        //     ->where('phone', $request->phone)
        //     ->first();
        $order = order::where('phone', $request->phone)
            ->first();

        if (!$order) {
            return redirect()->route('order.track')
                ->with('error', 'No order found with the provided information. Please check your Order ID and Phone Number.');
        }

        return view('frontend.user.track', compact('order'));
    }

    /**
     * Cancel order by user
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        // Check if the order is in a cancellable status
        $cancellableStatuses = ['pending', 'phone_not_rcv'];
        if (!in_array(strtolower($order->status), $cancellableStatuses)) {
            return redirect()->back()->with('error', 'This order cannot be cancelled as it is already being processed.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            // Update order status
            $order->status = 'cancelled';
            
            // Add cancellation status log
            $statusUpdates = $order->status_updates ? json_decode($order->status_updates, true) : [];
            $statusUpdates[] = [
                'status' => 'cancelled',
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => auth()->id(),
                'note' => 'Cancelled by Customer'
            ];
            $order->status_updates = json_encode($statusUpdates);
            $order->save();

            // Restore Stock
            $order->loadMissing('order_items');
            foreach ($order->order_items as $orderItem) {
                if ($orderItem->combination_id) {
                    \App\Models\VariationCombination::where('id', $orderItem->combination_id)
                        ->increment('stock_quantity', $orderItem->quantity);
                } else {
                    \App\Models\Product::where('id', $orderItem->product_id)
                        ->increment('quantity', $orderItem->quantity);
                }
            }
        });

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Return order by user
     */
    public function returnOrder(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        // Check if the order is delivered
        if (strtolower($order->status) !== 'delivered') {
            return redirect()->back()->with('error', 'This order is not eligible for return.');
        }

        // Check return window eligibility for at least one item
        $hasReturnableItems = false;
        foreach ($order->order_items as $item) {
            if ($item->product && ($item->product->return_period ?? 0) > 0) {
                $deliveredDate = \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at);
                $expiryDate = $deliveredDate->copy()->addDays($item->product->return_period);
                if (now()->lessThanOrEqualTo($expiryDate)) {
                    $hasReturnableItems = true;
                    break;
                }
            }
        }

        if (!$hasReturnableItems) {
            return redirect()->back()->with('error', 'The return window for this order has expired.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $request) {
            // Update order status to 'return'
            $order->status = 'return';
            
            // Save return reason & note in order or status logs
            $statusUpdates = $order->status_updates ? json_decode($order->status_updates, true) : [];
            $statusUpdates[] = [
                'status' => 'return',
                'updated_at' => now()->toDateTimeString(),
                'updated_by' => auth()->id(),
                'note' => 'Returned by Customer. Reason: ' . $request->reason
            ];
            $order->status_updates = json_encode($statusUpdates);
            
            $order->save();
        });

        return redirect()->back()->with('success', 'Return request submitted successfully. The order status has been updated to return.');
    }
}
