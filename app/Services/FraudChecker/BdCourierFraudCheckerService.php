<?php

namespace App\Services\FraudChecker;

use Illuminate\Support\Facades\Http;

class BdCourierFraudCheckerService implements FraudCheckerServiceInterface
{
    protected $credentials;
    protected $baseUrl = 'https://bdcourier.com/api';

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function checkFraud(string $phone): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . ($this->credentials['api_key'] ?? '')
            ])->post($this->baseUrl . '/courier-check', [
                'phone' => $phone
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->analyzeFraudData($data, $phone);
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch data from BD Courier API',
                'risk_score' => 0,
                'risk_level' => 'unknown',
                'details' => []
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred while checking fraud',
                'risk_score' => 0,
                'risk_level' => 'unknown',
                'details' => []
            ];
        }
    }

    public function getTotalSummary(string $phone): array
    {
        // BD Courier doesn't have a separate total summary endpoint
        // So we'll use the same courier-check endpoint
        return $this->checkFraud($phone);
    }

    protected function analyzeFraudData(array $data, string $phone): array
    {
        // Analyze the BD Courier response structure
        $riskFactors = [];
        $totalParcels = 0;
        $deliveredParcels = 0;
        $canceledParcels = 0;

        // BD Courier response structure may vary, so we'll handle it flexibly
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $courierData) {
                $totalParcels += $courierData['total_parcels'] ?? $courierData['total'] ?? 0;
                $deliveredParcels += $courierData['delivered_parcels'] ?? $courierData['delivered'] ?? 0;
                $canceledParcels += $courierData['canceled_parcels'] ?? $courierData['canceled'] ?? 0;

                // Risk factors
                if (($courierData['canceled_parcels'] ?? $courierData['canceled'] ?? 0) > 0) {
                    $courierName = $courierData['courier_name'] ?? $courierData['name'] ?? 'Unknown';
                    $riskFactors[] = "Has canceled orders with {$courierName}";
                }
            }
        }

        // If no structured data, try to extract from raw response
        if ($totalParcels === 0) {
            $totalParcels = $data['total_parcels'] ?? $data['total'] ?? 0;
            $deliveredParcels = $data['delivered_parcels'] ?? $data['delivered'] ?? 0;
            $canceledParcels = $data['canceled_parcels'] ?? $data['canceled'] ?? 0;
        }

        // Calculate risk score
        $riskScore = $this->calculateRiskScore($totalParcels, $deliveredParcels, $canceledParcels);

        return [
            'success' => true,
            'phone' => $phone,
            'risk_score' => $riskScore,
            'risk_level' => $this->getRiskLevel($riskScore),
            'total_parcels' => $totalParcels,
            'delivered_parcels' => $deliveredParcels,
            'canceled_parcels' => $canceledParcels,
            'delivery_success_rate' => $totalParcels > 0 ? round(($deliveredParcels / $totalParcels) * 100, 2) : 0.0,
            'risk_factors' => $riskFactors,
            'courier_data' => $data['data'] ?? [],
            'details' => $data
        ];
    }

    protected function calculateRiskScore(int $totalParcels, int $deliveredParcels, int $canceledParcels): int
    {
        if ($totalParcels === 0) {
            return 0; // No history
        }

        $successRate = $deliveredParcels / $totalParcels;
        $cancelRate = $canceledParcels / $totalParcels;

        // Base score calculation
        $score = 0;

        // Success rate factor (0-50 points)
        $score += (1 - $successRate) * 50;

        // Cancel rate factor (0-30 points)
        $score += $cancelRate * 30;

        // Volume factor (0-20 points)
        if ($totalParcels > 20) {
            $score += 20; // High volume customer
        } elseif ($totalParcels > 10) {
            $score += 10; // Medium volume customer
        }

        return min(100, max(0, round($score)));
    }

    protected function getRiskLevel(int $riskScore): string
    {
        if ($riskScore >= 80) return 'high';
        if ($riskScore >= 50) return 'medium';
        if ($riskScore >= 20) return 'low';
        return 'very_low';
    }

    public function testConnection(): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . ($this->credentials['api_key'] ?? '')
            ])->post($this->baseUrl . '/courier-check', [
                'phone' => '01700000000'
            ]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Connection successful' : 'Connection failed',
                'status_code' => $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'status_code' => 0
            ];
        }
    }
}
