<?php

use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $product = Product::find(100);
    if ($product) {
        $product->book()->create([
            'product_id' => $product->id,
            'subject' => 'Science Fiction',
            'publisher_id' => 1,
            'isbn' => '978-3-16-148410-0',
            'pages' => 300,
            'cover' => 'hardcover',
            'country' => 'USA',
            'language' => 'English'
        ]);
    }
})->purpose('Display an inspiring quote');

Schedule::command('sheets:sync-products')->everyTwoMinutes()->withoutOverlapping();
