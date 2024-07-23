<?php

namespace App\Services;

use App\DTO\BlogDTO;
use App\Facades\ActiveBlog;
use App\Facades\ResponseBuilder;
use App\Models\Blog;
use App\Models\User;

class BlogService
{
    public function getLatestUserBlogs(User $user)
    {
        return $user->blogs()->latest('created_at')->get();
    }

    public function store(User $user, BlogDTO $blogDto)
    {
        $validation = $blogDto->validate();

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::status(402)->errors($validation->errors()->toArray());
        }

        $isSuccessful = $user->blogs()->create($validation->getData());

        if (! $isSuccessful) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::status(201)->message(__(':name is created successfully', [
            'name' => __('Blog'),
        ]));
    }

    public function update(Blog $blog, BlogDTO $blogDto)
    {
        $validation = $blogDto->validate(false);

        if ($validation->errors()->isNotEmpty()) {
            return ResponseBuilder::data($blog)->status(402)->errors($validation->errors()->toArray());
        }

        $isSuccessful = $blog->update($validation->getData());

        if (! $isSuccessful) {
            return ResponseBuilder::status(500)->message('Internal Server Error');
        }

        return ResponseBuilder::data($blog)->status(200);
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

        return ResponseBuilder::data($blog)->status(200);
    }
}
