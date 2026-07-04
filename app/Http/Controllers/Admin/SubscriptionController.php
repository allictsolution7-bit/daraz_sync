<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $validated = $validator->validated();
        
        // Get IP address
        $ipAddress = $request->ip();
        
        // Get location data using IP API
        try {
            $response = Http::get("http://ip-api.com/json/{$ipAddress}");
            $locationData = $response->json();
        } catch (\Exception $e) {
            $locationData = [];
        }
        
        // Get device information
        $agent = new Agent();
        
        // Create subscription with additional information
        Subscription::create(array_merge($validated, [
            'ip_address' => $ipAddress,
            'user_agent' => $request->header('User-Agent'),
            'device' => $agent->device(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'country' => $locationData['country'] ?? null,
            'city' => $locationData['city'] ?? null,
            'region' => $locationData['regionName'] ?? null,
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }
    public function index()
    {
        $subscriptions = Subscription::latest()->paginate(15);
        return view('admin.subscripton.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        return view('admin.subscripton.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription deleted successfully');
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids)) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'No subscriptions selected for deletion');
        }

        // Delete the selected subscriptions
        $count = Subscription::whereIn('id', $ids)->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', $count . ' subscriptions deleted successfully');
    }
    public function toggleStatus(Subscription $subscription)
    {
        $subscription->update([
            'status' => !$subscription->status
        ]);

        return redirect()->back()->with('success', 'Subscription status updated successfully');
    }
}
