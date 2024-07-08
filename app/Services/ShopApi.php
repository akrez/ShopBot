<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShopApi
{
    public function json()
    {
        return Http::get('https://akrez.ir/api/shahabtahrir')->json();
    }
}
