<?php

namespace App\Supports;

use App\Models\User;

class ActiveBlog
{
    protected $blog;

    public function __construct(?User $user)
    {
        $this->set($user);
    }

    public function set($user)
    {
        return $this->blog = ($user ? $user->activeBlog()->first() : null);
    }

    public function get()
    {
        return $this->blog;
    }

    public function has()
    {
        return $this->get() !== null;
    }

    public function attr($attribute)
    {
        return $this->get() ? $this->get()->$attribute : null;
    }

    public function name()
    {
        return $this->attr('name');
    }
}
