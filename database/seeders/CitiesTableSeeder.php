<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            'Dhaka',
            'Chittagong',
            'Rajshahi',
            'Khulna',
            'Barishal',
            'Sylhet',
            'Rangpur',
            'Mymensingh',
        ];

        foreach ($cities as $city) {
            City::create(['name' => $city]);
        }
    }
}