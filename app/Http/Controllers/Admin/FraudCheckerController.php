<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FraudCheckerIntegration;
use App\Models\FraudCheckResult;
use App\Models\order;
use App\Services\FraudChecker\FraudCheckerServiceManager;

class FraudCheckerController extends Controller
{
    public function index()
    {
        $integrations = FraudCheckerIntegration::all();
        $recentResults = FraudCheckResult::recent(7)->orderBy('last_checked_at', 'desc')->limit(10)->get();
        $highRiskResults = FraudCheckResult::highRisk()->orderBy('last_checked_at', 'desc')->limit(10)->get();
        
        $stats = [
            'total_checks' => FraudCheckResult::count(),
            'high_risk_count' => FraudCheckResult::highRisk()->count(),
            'recent_checks' => FraudCheckResult::recent(7)->count(),
            'active_providers' => FraudCheckerIntegration::where('is_active', true)->count(),
            'new_customers' => FraudCheckResult::where('has_courier_history', false)->count()
        ];

        return view('admin.fraud-checker.index', compact('integrations', 'recentResults', 'highRiskResults', 'stats'));
    }

    public function integrationForm(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $provider = $request->input('provider');
            
            if ($provider === 'hoorin') {
                $request->validate([
                    'provider' => 'required|string',
                    'api_key' => 'required|string',
                ]);
                $credentials = [
                    'api_key' => $request->api_key,
                ];
            } elseif ($provider === 'bdcourier') {
                $request->validate([
                    'provider' => 'required|string',
                    'api_key' => 'required|string',
                ]);
                $credentials = [
                    'api_key' => $request->api_key,
                ];
            } else {
                return back()->withErrors(['provider' => 'Invalid provider selected.']);
            }

            if ($id) {
                $integration = FraudCheckerIntegration::findOrFail($id);
                $integration->update([
                    'provider' => $provider,
                    'credentials' => $credentials,
                    'is_active' => $request->has('is_active'),
                ]);
                return redirect()->route('admin.fraud-checker.index')->with('success', 'Integration updated!');
            } else {
                FraudCheckerIntegration::create([
                    'provider' => $provider,
                    'credentials' => $credentials,
                    'is_active' => $request->has('is_active'),
                ]);
                return redirect()->route('admin.fraud-checker.index')->with('success', 'Integration created!');
            }
        } else {
            $integration = $id ? FraudCheckerIntegration::findOrFail($id) : null;
            return view('admin.fraud-checker.integration', compact('integration'));
        }
    }

    public function testConnection($id)
    {
        $integration = FraudCheckerIntegration::findOrFail($id);
        
        try {
            $service = FraudCheckerServiceManager::resolve($integration->provider, $integration->credentials);
            $result = $service->testConnection();
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ]);
        }
    }

    public function checkPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:15'
        ]);

        $phone = $request->phone;
        $forceRefresh = $request->boolean('force_refresh', false);

        try {
            $result = FraudCheckerServiceManager::checkFraud($phone, $forceRefresh);
            
            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active fraud checker providers found or all providers failed'
                ]);
            }

            return response()->json([
                'success' => true,
                'result' => $result,
                'message' => 'Fraud check completed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fraud check failed: ' . $e->getMessage()
            ]);
        }
    }

    public function checkOrderFraud($orderId, Request $request)
    {
        $order = order::findOrFail($orderId);
        $fromCache = $request->boolean('from_cache', false);
        
        try {
            if ($fromCache) {
                // Only check database, don't call API
                $existingResult = FraudCheckResult::where('phone', $order->phone)->first();
                
                if ($existingResult) {
                    // Link the existing database result to the order
                    $order->update([
                        'fraud_check_result_id' => $existingResult->id,
                        'fraud_check_completed' => true,
                        'fraud_check_at' => now()
                    ]);

                    return response()->json([
                        'success' => true,
                        'result' => $existingResult,
                        'message' => 'Fraud check data loaded from database'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No fraud check data found in database for this phone number'
                    ]);
                }
            } else {
                // Normal API check
                $result = FraudCheckerServiceManager::checkFraud($order->phone);
                
                if ($result) {
                    // Link the fraud check result to the order
                    $order->update([
                        'fraud_check_result_id' => $result->id,
                        'fraud_check_completed' => true,
                        'fraud_check_at' => now()
                    ]);

                    return response()->json([
                        'success' => true,
                        'result' => $result,
                        'message' => 'Fraud check completed and linked to order'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No active fraud checker providers found'
                    ]);
                }
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fraud check failed: ' . $e->getMessage()
            ]);
        }
    }

    public function results()
    {
        $results = FraudCheckResult::with('orders')
            ->orderBy('last_checked_at', 'desc')
            ->paginate(20);

        return view('admin.fraud-checker.results', compact('results'));
    }

    public function resultDetails($id)
    {
        $result = FraudCheckResult::with('orders')->findOrFail($id);
        return view('admin.fraud-checker.result-details', compact('result'));
    }

    public function destroy($id)
    {
        $integration = FraudCheckerIntegration::findOrFail($id);
        $integration->delete();
        
        return redirect()->route('admin.fraud-checker.index')
            ->with('success', 'Integration deleted successfully!');
    }

    public function refreshResult($id)
    {
        $result = FraudCheckResult::findOrFail($id);
        
        try {
            $newResult = FraudCheckerServiceManager::checkFraud($result->phone, true);
            
            if ($newResult) {
                return response()->json([
                    'success' => true,
                    'result' => $newResult,
                    'message' => 'Fraud check refreshed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to refresh fraud check'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Refresh failed: ' . $e->getMessage()
            ]);
        }
    }
}
