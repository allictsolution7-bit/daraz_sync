<?php

namespace App\Services\FraudChecker;

use Exception;
use App\Models\FraudCheckerIntegration;
use App\Models\FraudCheckResult;

class FraudCheckerServiceManager
{
    public static function resolve($provider, $credentials): FraudCheckerServiceInterface
    {
        return match ($provider) {
            'hoorin' => new HoorinFraudCheckerService($credentials),
            'bdcourier' => new BdCourierFraudCheckerService($credentials),
            null => throw new Exception('Provider not found'),
            default => throw new Exception("Unsupported fraud checker provider: {$provider}")
        };
    }

    /**
     * Resolve a fraud checker service for a given provider
     */
    public static function forProvider($provider): ?FraudCheckerServiceInterface
    {
        $integration = FraudCheckerIntegration::where('provider', $provider)->first();
        if (!isset($integration)) return null;
        return self::resolve($provider, $integration?->credentials ?? []);
    }

    /**
     * Check fraud for a phone number - first check database, then API if needed
     */
    public static function checkFraud(string $phone, bool $forceRefresh = false): ?FraudCheckResult
    {
        // Clean phone number
        $phone = self::cleanPhoneNumber($phone);

        // Check if we have a recent result in database (not older than 30 days)
        if (!$forceRefresh) {
            $existingResult = FraudCheckResult::where('phone', $phone)
                ->where('last_checked_at', '>=', now()->subDays(30))
                ->first();

            if ($existingResult) {
                return $existingResult;
            }
        }

        // No cached result or force refresh - call APIs
        $result = self::checkFraudWithAllProviders($phone);
        
        if (!$result['successful_checks']) {
            return null;
        }

        // Save or update the result in database
                        return FraudCheckResult::updateOrCreate(
                    ['phone' => $phone],
                    [
                        'fraud_check_data' => $result,
                        'risk_score' => $result['average_risk_score'],
                        'risk_level' => $result['risk_level'],
                        'total_parcels' => self::calculateTotalParcels($result),
                        'delivered_parcels' => self::calculateDeliveredParcels($result),
                        'canceled_parcels' => self::calculateCanceledParcels($result),
                        'delivery_success_rate' => self::calculateSuccessRate($result),
                        'risk_factors' => self::extractRiskFactors($result),
                        'provider_results' => $result['provider_results'],
                        'recommendation' => $result['recommendation'],
                        'has_courier_history' => $result['has_courier_history'] ?? false,
                        'last_checked_at' => now()
                    ]
                );
    }

    /**
     * Check fraud using all active providers and return combined results
     */
    public static function checkFraudWithAllProviders(string $phone): array
    {
        $activeIntegrations = FraudCheckerIntegration::where('is_active', true)->get();
        $results = [];
        $combinedRiskScore = 0;
        $totalProviders = 0;
        $hasAnyCourierHistory = false;

        foreach ($activeIntegrations as $integration) {
            try {
                $service = self::resolve($integration->provider, $integration->credentials);
                $result = $service->checkFraud($phone);
                
                $results[$integration->provider] = $result;
                
                if ($result['success']) {
                    // Check if this provider found any courier history
                    if (isset($result['total_parcels']) && $result['total_parcels'] > 0) {
                        $hasAnyCourierHistory = true;
                    }
                    
                    $combinedRiskScore += $result['risk_score'];
                    $totalProviders++;
                }
            } catch (\Exception $e) {
                $results[$integration->provider] = [
                    'success' => false,
                    'message' => 'Service error: ' . $e->getMessage(),
                    'risk_score' => 0,
                    'risk_level' => 'unknown'
                ];
            }
        }

        // If no courier history found, treat as new customer (high risk - unknown)
        if (!$hasAnyCourierHistory) {
            $averageRiskScore = 75; // High risk for new customers (unknown history)
            $riskLevel = 'high';
            $recommendation = 'New customer with no courier history. High risk - proceed with caution and additional verification.';
        } else {
            // Calculate average risk score based on actual courier history
            $averageRiskScore = $totalProviders > 0 ? round($combinedRiskScore / $totalProviders) : 0;
            $riskLevel = self::getRiskLevel($averageRiskScore);
            $recommendation = self::getRecommendation($averageRiskScore);
        }

        return [
            'success' => $totalProviders > 0,
            'phone' => $phone,
            'providers_checked' => count($activeIntegrations),
            'successful_checks' => $totalProviders,
            'has_courier_history' => $hasAnyCourierHistory,
            'average_risk_score' => $averageRiskScore,
            'risk_level' => $riskLevel,
            'provider_results' => $results,
            'recommendation' => $recommendation
        ];
    }

    /**
     * Get total summary from all active providers
     */
    public static function getTotalSummaryFromAllProviders(string $phone): array
    {
        $activeIntegrations = FraudCheckerIntegration::where('is_active', true)->get();
        $results = [];

        foreach ($activeIntegrations as $integration) {
            try {
                $service = self::resolve($integration->provider, $integration->credentials);
                $result = $service->getTotalSummary($phone);
                $results[$integration->provider] = $result;
            } catch (\Exception $e) {
                $results[$integration->provider] = [
                    'success' => false,
                    'message' => 'Service error: ' . $e->getMessage()
                ];
            }
        }

        return [
            'phone' => $phone,
            'provider_results' => $results
        ];
    }

    /**
     * Clean phone number for consistent storage
     */
    protected static function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ensure it starts with country code if not already
        if (strlen($phone) === 11 && substr($phone, 0, 2) === '01') {
            return $phone; // Already in correct format
        }
        
        // If it's 10 digits, assume it's missing the leading 0
        if (strlen($phone) === 10) {
            return '0' . $phone;
        }
        
        return $phone;
    }

    /**
     * Calculate total parcels from provider results
     */
    protected static function calculateTotalParcels(array $result): int
    {
        $total = 0;
        foreach ($result['provider_results'] ?? [] as $providerResult) {
            if ($providerResult['success']) {
                $total += $providerResult['total_parcels'] ?? 0;
            }
        }
        return $total;
    }

    /**
     * Calculate delivered parcels from provider results
     */
    protected static function calculateDeliveredParcels(array $result): int
    {
        $total = 0;
        foreach ($result['provider_results'] ?? [] as $providerResult) {
            if ($providerResult['success']) {
                $total += $providerResult['delivered_parcels'] ?? 0;
            }
        }
        return $total;
    }

    /**
     * Calculate canceled parcels from provider results
     */
    protected static function calculateCanceledParcels(array $result): int
    {
        $total = 0;
        foreach ($result['provider_results'] ?? [] as $providerResult) {
            if ($providerResult['success']) {
                $total += $providerResult['canceled_parcels'] ?? 0;
            }
        }
        return $total;
    }

    /**
     * Calculate success rate
     */
    protected static function calculateSuccessRate(array $result): float
    {
        // If no courier history, return 0% success rate for new customers (unknown)
        if (isset($result['has_courier_history']) && !$result['has_courier_history']) {
            return 0.0;
        }
        
        $totalParcels = self::calculateTotalParcels($result);
        $deliveredParcels = self::calculateDeliveredParcels($result);
        
        if ($totalParcels === 0) {
            return 0.0; // No parcels = unknown history = 0% success
        }
        
        return round(($deliveredParcels / $totalParcels) * 100, 2);
    }

    /**
     * Extract risk factors from provider results
     */
    protected static function extractRiskFactors(array $result): array
    {
        $riskFactors = [];
        foreach ($result['provider_results'] ?? [] as $provider => $providerResult) {
            if ($providerResult['success'] && !empty($providerResult['risk_factors'])) {
                $riskFactors = array_merge($riskFactors, $providerResult['risk_factors']);
            }
        }
        return array_unique($riskFactors);
    }

    protected static function getRiskLevel(int $riskScore): string
    {
        if ($riskScore >= 80) return 'high';
        if ($riskScore >= 50) return 'medium';
        if ($riskScore >= 20) return 'low';
        return 'very_low';
    }

    protected static function getRecommendation(int $riskScore): string
    {
        if ($riskScore >= 80) {
            return 'High risk customer. Consider manual review or additional verification.';
        } elseif ($riskScore >= 50) {
            return 'Medium risk customer. Proceed with caution and monitor closely.';
        } elseif ($riskScore >= 20) {
            return 'Low risk customer. Standard processing recommended.';
        } elseif ($riskScore >= 5) {
            return 'Very low risk customer. Safe to proceed with normal processing.';
        } else {
            return 'New customer with no courier history. High risk - proceed with caution and additional verification.';
        }
    }
}
