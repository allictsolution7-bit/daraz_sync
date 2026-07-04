<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WriterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoItems = [
            ['name' => 'জন ডো', 'img' => 'https://randomuser.me/api/portraits/men/1.jpg'],
            ['name' => 'জেন স্মিথ', 'img' => 'https://randomuser.me/api/portraits/women/2.jpg'],
            ['name' => 'রহিম উদ্দিন', 'img' => 'https://randomuser.me/api/portraits/men/3.jpg'],
            ['name' => 'সাবিনা ইয়াসমিন', 'img' => 'https://randomuser.me/api/portraits/women/4.jpg'],
            ['name' => 'আব্দুল করিম', 'img' => 'https://randomuser.me/api/portraits/men/5.jpg'],
            ['name' => 'মারিয়া ইসলাম', 'img' => 'https://randomuser.me/api/portraits/women/6.jpg'],
            ['name' => 'তানভীর হাসান', 'img' => 'https://randomuser.me/api/portraits/men/7.jpg'],
            ['name' => 'রিমা আক্তার', 'img' => 'https://randomuser.me/api/portraits/women/8.jpg'],
            ['name' => 'রিমা আক্তার', 'img' => 'https://randomuser.me/api/portraits/women/8.jpg'],
            ['name' => 'রিমা আক্তার', 'img' => 'https://randomuser.me/api/portraits/women/8.jpg'],
            ['name' => 'রিমা আক্তার', 'img' => 'https://randomuser.me/api/portraits/women/8.jpg'],
            ['name' => 'রিমা আক্তার', 'img' => 'https://randomuser.me/api/portraits/women/8.jpg'],
        ];

        foreach ($demoItems as $item) {
            \App\Models\Writer::create([
                'name' => $item['name'],
                'photo' => $item['img'],
            ]);
        }
    }
}
