<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FraudCheckerIntegration;

class FraudCheckerIntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample Hoorin integration
        FraudCheckerIntegration::updateOrCreate(
            ['provider' => 'hoorin'],
            [
                'provider' => 'hoorin',
                'credentials' => [
                    'api_key' => 'your_hoorin_api_key_here'
                ],
                'is_active' => false // Set to false by default for security
            ]
        );

        // Create sample BD Courier integration
        FraudCheckerIntegration::updateOrCreate(
            ['provider' => 'bdcourier'],
            [
                'provider' => 'bdcourier',
                'credentials' => [
                    'api_key' => 'bdc_sVAPSgOAcyUo2LxwJl9aRXFoQiN4l1gHKXqU8bp2W0yGaBGt53lsoCB80SE1'
                ],
                'is_active' => false // Set to false by default for security
            ]
        );

        $this->command->info('Fraud checker integrations seeded successfully!');
        $this->command->info('Please update the API keys in the admin panel and activate the integrations.');
    }
}
