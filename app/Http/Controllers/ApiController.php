<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function blog(Request $request, int $blog_id)
    {
        return resolve(ApiService::class)->blogResponse($blog_id);
    }
}
