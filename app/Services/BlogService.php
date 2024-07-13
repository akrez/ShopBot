<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\User;

class BlogService
{
    public function setUserActiveBlog(User $user, Blog $blog)
    {
        $user->active_blog = $blog->id;
        $user->save();
    }
}
