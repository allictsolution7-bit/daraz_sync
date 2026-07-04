<?php

namespace App\Services\FraudChecker;

interface FraudCheckerServiceInterface
{
    /**
     * Check fraud risk for a given phone number
     */
    public function checkFraud(string $phone): array;

    /**
     * Get total summary for a phone number
     */
    public function getTotalSummary(string $phone): array;

    /**
     * Test the connection to the service
     */
    public function testConnection(): array;
}
