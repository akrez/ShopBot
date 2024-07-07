<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShopApi
{
    public function contactUs()
    {
        $data = Http::get('https://akrez.ir/api/shahabtahrir')->json();

        return $data['contacts'];
    }
}
