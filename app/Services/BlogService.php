<?php

namespace App\Services;

use App\Facades\ActiveBlog;
use App\Models\Blog;
use App\Models\User;

class BlogService
{
    public function getLatestUserBlogs(User $user)
    {
        return $user->blogs()->latest('created_at')->get();
    }

    public function create(User $user, array $data)
    {
        $user->blogs()->create($data);
    }

    public function update(Blog $blog, array $data)
    {
        $blog->update($data);
    }

    public function findOrFailUserBlog(User $user, int $id)
    {
        $blog = $user->blogs()->where('id', $id)->first();
        abort_unless($user and $blog, 404);

        return $blog;
    }

    public function findOrFailUserActiveBlog()
    {
        $blog = ActiveBlog::get();
        abort_unless($blog, 404);

        return $blog;
    }

    public function setUserActiveBlog(User $user, Blog $blog)
    {
        resolve(UserService::class)->setActiveBlog($user, $blog);
    }
}
