<?php

use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $product = Product::find(100);
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

})->purpose('Display an inspiring quote');
