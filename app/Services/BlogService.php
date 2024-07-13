<?php

namespace App\Services;

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

    public function getUserBlog(User $user, int $id)
    {
        $blog = $user->blogs()->where('id', $id)->first();
        abort_unless($user and $blog, 404);
        return $blog;
    }

    public function setUserActiveBlog(User $user, Blog $blog)
    {
        $user->active_blog = $blog->id;
        $user->save();
    }
}
