<?php

namespace App\Http\Controllers;

use App\Models\order;
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
        return view('frontend.user.profile', compact('user'));
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
        $orders = $user->orders()->latest()->paginate(10);

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
}
