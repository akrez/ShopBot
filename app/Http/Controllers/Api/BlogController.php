<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailApiBlog($id);

        return response()->json($this->blogService->getArrayResponse($blog));
    }
}
