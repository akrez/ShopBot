<?php

namespace App\Services;

use App\Http\Resources\BlogResource;
use App\Support\ResponseBuilder;

class ApiService
{
    public function blogResponse(int $blogId)
    {
        $blog = resolve(BlogService::class)->firstApiActiveBlog($blogId);
        if (empty($blog)) {
            return ResponseBuilder::new(404, 'Not Found');
        }

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
                    ->orderDefault();
            },
            'contacts' => function ($query) {
                $query->orderDefault();
            },
            'logo' => function ($query) {
                $query->orderDefault();
            },
        ]);

        return ResponseBuilder::new()->data(new BlogResource($blog));
    }
}
