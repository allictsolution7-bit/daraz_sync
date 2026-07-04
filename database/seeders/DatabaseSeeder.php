<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Activities;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'name' => 'Naimul Islam',
             'email' => 'naimul@gmail.com',
             'password' => bcrypt ("naimul0000")
         ]);
        Product::factory (10)->create ();
        Activities::factory (5)->create ();
        $this->call([
            ShippingSeeder::class
        ]);
        $this->call(CitiesTableSeeder::class);
        // \App\Models\User::factory(10)->create();
        $this->call([
            // Other seeders...
            MenuSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
