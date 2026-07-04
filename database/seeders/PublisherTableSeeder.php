<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoItems = [
            ['name' => 'পেঙ্গুইন পাবলিশিং', 'img' => 'https://ui-avatars.com/api/?name=Penguin+Publishing&background=0D8ABC&color=fff'],
            ['name' => 'হারপারকোলিন্স', 'img' => 'https://ui-avatars.com/api/?name=HarperCollins&background=F39C12&color=fff'],
            ['name' => 'র‍্যান্ডম হাউস', 'img' => 'https://ui-avatars.com/api/?name=Random+House&background=27AE60&color=fff'],
            ['name' => 'সাইমন & শুস্টার', 'img' => 'https://ui-avatars.com/api/?name=Simon+%26+Schuster&background=8E44AD&color=fff'],
            ['name' => 'ম্যাকমিলান', 'img' => 'https://ui-avatars.com/api/?name=Macmillan&background=E74C3C&color=fff'],
            ['name' => 'অক্সফোর্ড ইউনিভার্সিটি', 'img' => 'https://ui-avatars.com/api/?name=Oxford+University+Press&background=34495E&color=fff'],
            ['name' => 'ক্যামব্রিজ ইউনিভার্সিটি', 'img' => 'https://ui-avatars.com/api/?name=Cambridge+University+Press&background=16A085&color=fff'],
            ['name' => 'স্কলার্স পাবলিশিং', 'img' => 'https://ui-avatars.com/api/?name=Scholars+Publishing&background=2C3E50&color=fff'],
            ['name' => 'নতুন প্রকাশনী', 'img' => 'https://ui-avatars.com/api/?name=Natun+Prokashoni&background=E67E22&color=fff'],
            ['name' => 'বইঘর', 'img' => 'https://ui-avatars.com/api/?name=Boighor&background=2980B9&color=fff'],
            ['name' => 'প্রকাশনী এক্স', 'img' => 'https://ui-avatars.com/api/?name=Prokashoni+X&background=9B59B6&color=fff'],
            ['name' => 'সৃজনশীল প্রকাশক', 'img' => 'https://ui-avatars.com/api/?name=Srijonshil+Prokashok&background=1ABC9C&color=fff'],
        ];

        foreach ($demoItems as $item) {
            \App\Models\Publisher::create([
                'name' => $item['name'],
                'logo' => $item['img'],
            ]);
        }
    }
}
