<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryIntegration;
use App\Models\order;
use App\Services\Delivery\DeliveryServiceManager;
use Illuminate\Support\Facades\Auth;

class DeliveryIntegrationController extends Controller
{
    public function index()
    {
        $integrations = DeliveryIntegration::all();
        return view('admin.delivery.index', compact('integrations'));
    }

    public function integrationForm(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $provider = $request->input('provider');
            // Only validate and use fields for the selected provider
            if ($provider === 'steadfast') {
                $request->validate([
                    'provider' => 'required|string',
                    'api_key' => 'required|string',
                    'secret_key' => 'required|string',
                    'base_url' => 'required|string',
                ]);
                $credentials = [
                    'api_key' => $request->api_key,
                    'secret_key' => $request->secret_key,
                    'base_url' => $request->base_url,
                ];
            } elseif ($provider === 'pathao') {
                $request->validate([
                    'provider' => 'required|string',
                    'client_id' => 'required|string',
                    'client_secret' => 'required|string',
                    'username' => 'required|string',
                    'password' => 'required|string',
                    'base_url' => 'required|string',
                    'store_id' => 'nullable|string',
                ]);
                $credentials = [
                    'client_id' => $request->client_id,
                    'client_secret' => $request->client_secret,
                    'username' => $request->username,
                    'password' => $request->password,
                    'base_url' => $request->base_url,
                    'store_id' => $request->store_id,
                ];
            } else {
                return back()->withErrors(['provider' => 'Invalid provider selected.']);
            }
            if ($id) {
                $integration = DeliveryIntegration::findOrFail($id);
                $integration->update([
                    'provider' => $provider,
                    'credentials' => $credentials,
                    'is_active' => $request->has('is_active'),
                ]);
                return redirect()->route('admin.delivery.index')->with('success', 'Integration updated!');
            } else {
                DeliveryIntegration::create([
                    'provider' => $provider,
                    'credentials' => $credentials,
                    'is_active' => $request->has('is_active'),
                ]);
                return redirect()->route('admin.delivery.index')->with('success', 'Integration created!');
            }
        } else {
            // Show form
            $integration = $id ? DeliveryIntegration::findOrFail($id) : null;
            return view('admin.delivery.integration', compact('integration'));
        }
    }

    public function destroy($id)
    {
        $integration = DeliveryIntegration::findOrFail($id);
        $integration->delete();
        return redirect()->back()->with('success', 'Integration deleted.');
    }
}
