<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function __construct(protected BlogService $blogService) {}

    public function index(Request $request, int $id)
    {
        $blog = $this->blogService->findOrFailApiBlog($id);

        return $this->blogService->render($blog);
    }

    public function domain(Request $request, $host)
    {
        $hosts = resolve('Hosts');

        abort_unless($id = $hosts->hostToBlogId($host), 404);
        abort_unless($blog = $this->blogService->findOrFailApiBlog($id), 404);

        return $this->blogService->render($blog);
    }
}
