<?php

namespace App\Services;

use App\DTO\BlogDTO;
use App\Facades\ActiveBlog;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\User;
use App\Support\ResponseBuilder;
use Illuminate\Http\Request;

class BlogService
{
    public function findOrFailApiBlog(int $id)
    {
        $blog = Blog::filterIsActive()->where('id', $id)->first();
        abort_unless($blog, 404);

        return $blog;
    }

    public function getLatestUserBlogs(User $user)
    {
        return $user->blogs()->latest('created_at')->get();
    }

    public function store(User $user, BlogDTO $blogDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($blogDTO);

        $validation = $blogDTO->validate();

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $blog = $user->blogs()->create($validation->getData());

        if (! $blog) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($blog)->message(__(':name is created successfully', [
            'name' => __('Blog'),
        ]));
    }

    public function update(Blog $blog, BlogDTO $blogDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($blogDTO);

        $validation = $blogDTO->validate(false);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $blog->update($validation->getData());

        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->data($blog)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Blog'),
        ]));
    }

    public function findOrFailUserBlog(User $user, int $id)
    {
        $blog = $user->blogs()->where('id', $id)->first();
        abort_unless($user and $blog, 404);

        return $blog;
    }

    public function findOrFailActiveBlog()
    {
        $blog = ActiveBlog::get();
        abort_unless($blog, 404);

        return $blog;
    }

    public function setUserActiveBlog(User $user, Blog $blog)
    {
        resolve(UserService::class)->setActiveBlog($user, $blog);

        return ResponseBuilder::new(200)->data($blog)->message(__(':name is selected successfully', [
            'name' => __('Blog'),
        ]))->status(200);
    }

    public function renderView(Blog $blog)
    {
        return view('fronts.index', [
            'data' => $this->getArrayResponse($blog),
        ]);
    }

    public function getArrayResponse(Blog $blog)
    {
        $request = $this->getRequest();

        $blog->load([
            'products' => function ($query) {
                $query
                    ->filterIsActive()
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

        return json_decode(json_encode((new BlogResource($blog))->toArray($request)), true);
    }

    protected function getRequest(): Request
    {
        return app('request');
    }
}
