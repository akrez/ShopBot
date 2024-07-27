<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use App\Support\ResponseBuilder;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function blog(Request $request, int $blog_id)
    {
        $blog = resolve(BlogService::class)->findOrFailApiActiveBlog($blog_id);

        $blog->load([
            'products' => function ($query) {
                $query
                    ->with('productProperties', function ($query) {
                        $query->orderBy('created_at', 'ASC');
                    })
                    ->with('productTags', function ($query) {
                        $query->orderBy('created_at', 'ASC');
                    })
                    ->with('images', function ($query) {
                        $query->orderDefault();
                    })
                    ->latest('created_at');
            },
            'contacts' => function ($query) {
                $query->orderDefault();
            },
            'logo' => function ($query) {
                $query->orderDefault();
            },
        ]);

        return (new ResponseBuilder)->status(200)->data($blog);
    }
}
