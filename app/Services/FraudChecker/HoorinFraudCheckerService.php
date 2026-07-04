<?php

namespace App\Services\FraudChecker;

use Illuminate\Support\Facades\Http;

class HoorinFraudCheckerService implements FraudCheckerServiceInterface
{
    protected $credentials;
    protected $baseUrl = 'https://dash.hoorin.com/api';

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function checkFraud(string $phone): array
    {
        try {
            $response = Http::get($this->baseUrl . '/courier/search', [
                'apiKey' => $this->credentials['api_key'] ?? '',
                'searchTerm' => $phone
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->analyzeFraudData($data, $phone);
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch data from Hoorin API',
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
        try {
            $response = Http::get($this->baseUrl . '/courier/sheet', [
                'apiKey' => $this->credentials['api_key'] ?? '',
                'searchTerm' => $phone
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->analyzeTotalSummary($data, $phone);
            }

            return [
                'success' => false,
                'message' => 'Failed to fetch total summary from Hoorin API',
                'risk_score' => 0,
                'risk_level' => 'unknown',
                'details' => []
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception occurred while fetching total summary',
                'risk_score' => 0,
                'risk_level' => 'unknown',
                'details' => []
            ];
        }
    }

    protected function analyzeFraudData(array $data, string $phone): array
    {
        $summaries = $data['Summaries'] ?? [];
        $riskFactors = [];
        $totalParcels = 0;
        $deliveredParcels = 0;
        $canceledParcels = 0;

        foreach ($summaries as $courier => $summary) {
            $totalParcels += $summary['Total Parcels'] ?? $summary['Total Delivery'] ?? 0;
            $deliveredParcels += $summary['Delivered Parcels'] ?? $summary['Successful Delivery'] ?? 0;
            $canceledParcels += $summary['Canceled Parcels'] ?? $summary['Canceled Delivery'] ?? 0;

            // Risk factors
            if (($summary['Canceled Parcels'] ?? $summary['Canceled Delivery'] ?? 0) > 0) {
                $riskFactors[] = "Has canceled orders with {$courier}";
            }
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
            'courier_summaries' => $summaries,
            'details' => $data
        ];
    }

    protected function analyzeTotalSummary(array $data, string $phone): array
    {
        $totalSummary = $data['totalSummary'] ?? [];
        $totalParcels = $totalSummary['Total Parcels'] ?? 0;
        $deliveredParcels = $totalSummary['Delivered Parcels'] ?? 0;
        $canceledParcels = $totalSummary['Canceled Parcels'] ?? 0;

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
            $response = Http::get($this->baseUrl . '/courier/search', [
                'apiKey' => $this->credentials['api_key'] ?? '',
                'searchTerm' => '01700000000'
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
