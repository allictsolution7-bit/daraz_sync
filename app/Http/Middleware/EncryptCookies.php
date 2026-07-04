<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        '_fbp',  // Facebook browser ID (set by Meta Pixel)
        '_fbc',  // Facebook click ID (set by Meta Pixel)
    ];
}
