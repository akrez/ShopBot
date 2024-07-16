<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\User;

class ActiveBlog
{
    protected $blog;

    public function __construct(?User $user)
    {
        $this->set($user);
    }

    public function set(?User $user): ?Blog
    {
        return $this->blog = ($user ? $user->activeBlog()->first() : null);
    }

    public function get(): ?Blog
    {
        return $this->blog;
    }

    public function has(): bool
    {
        return $this->get() !== null;
    }

    public function attr(string $attribute): mixed
    {
        return $this->get() ? $this->get()->$attribute : null;
    }

    public function name(): ?string
    {
        return $this->attr('name');
    }
}
