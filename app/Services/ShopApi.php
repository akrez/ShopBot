<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ShopApi
{
    public function categories()
    {
        $data = Http::get('https://akrez.ir/api/shahabtahrir')->json();

        return Arr::get($data, 'blog_categories.0.values', []);
    }

    public function contactUs()
    {
        $data = Http::get('https://akrez.ir/api/shahabtahrir')->json();

        return $data['contacts'];
    }
}
