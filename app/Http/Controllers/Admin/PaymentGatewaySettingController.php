<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewaySettingController extends Controller
{
    public function index()
    {
        $gateways = PaymentGateway::orderBy('sort_order')->get();
        return view('admin.payment-gateways.index', compact('gateways'));
    }

    public function edit(PaymentGateway $gateway)
    {
        return view('admin.payment-gateways.edit', compact('gateway'));
    }

    public function update(Request $request, PaymentGateway $gateway)
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'is_enabled'              => 'boolean',
            'is_live'                 => 'boolean',
            'public_key'              => 'nullable|string',
            'secret_key'              => 'nullable|string',
            'webhook_secret'          => 'nullable|string',
            'currency'                => 'nullable|string|max:3',
            'transaction_fee_percent' => 'nullable|numeric|min:0',
            'transaction_fee_fixed'   => 'nullable|numeric|min:0',
            'sort_order'              => 'nullable|integer',
        ]);

        // Handle additional_config as individual fields
        $additionalConfig = $gateway->additional_config ?? [];

        if ($gateway->provider === 'eps') {
            $additionalConfig['merchant_id'] = $request->input('merchant_id', '');
            $additionalConfig['store_id']    = $request->input('store_id', '');
            $additionalConfig['username']    = $request->input('username', '');
            $additionalConfig['password']    = $request->input('password', '');
            $additionalConfig['hash_key']    = $request->input('hash_key', '');
        }

        $gateway->update([
            'name'                    => $validated['name'],
            'is_enabled'              => $request->boolean('is_enabled'),
            'is_live'                 => $request->boolean('is_live'),
            'public_key'              => $validated['public_key'] ?? $gateway->public_key,
            'secret_key'              => $validated['secret_key'] ?? $gateway->secret_key,
            'webhook_secret'          => $validated['webhook_secret'] ?? $gateway->webhook_secret,
            'currency'                => $validated['currency'] ?? 'BDT',
            'transaction_fee_percent' => $validated['transaction_fee_percent'] ?? 0,
            'transaction_fee_fixed'   => $validated['transaction_fee_fixed'] ?? 0,
            'sort_order'              => $validated['sort_order'] ?? 0,
            'additional_config'       => $additionalConfig,
        ]);

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', $gateway->name . ' settings updated successfully.');
    }

    public function toggle(PaymentGateway $gateway)
    {
        $gateway->update(['is_enabled' => !$gateway->is_enabled]);

        return response()->json([
            'success'    => true,
            'is_enabled' => $gateway->is_enabled,
            'message'    => $gateway->name . ' has been ' . ($gateway->is_enabled ? 'enabled' : 'disabled'),
        ]);
    }
}
