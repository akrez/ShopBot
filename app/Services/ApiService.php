<?php

namespace App\Services;

use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Support\ResponseBuilder;

class ApiService
{
    public function blogResponse(int $blogId)
    {
        $blog = resolve(BlogService::class)->firstApiBlog($blogId);
        if (empty($blog)) {
            return ResponseBuilder::new(404, 'Not Found');
        }

        return ResponseBuilder::new()->data($this->blogResource($blog));
    }

    public function blogArray(Blog $blog)
    {
        return (array) json_decode($this->blogResource($blog)->toJson(), true);
    }

    public function blogResource(Blog $blog)
    {
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

        return new BlogResource($blog);
    }
}
