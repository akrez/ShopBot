<?php

namespace App\Support;

use App\Models\Blog;

class ActiveBlog
{
    protected static $blog;

    protected static $finded = false;

    public static function set($user, $force = false)
    {
        if ($force or false === static::$finded) {
            if ($user) {
                static::$blog = Blog::userCreated($user->id)
                    ->where('name', $user->active_blog)
                    ->first();
            }
            static::$finded = true;
        }
    }

    public static function get()
    {
        return static::$blog;
    }

    public static function has()
    {
        $blog = static::get();
        return $blog !== null;
    }

    public static function attr($attribute)
    {
        $blog = static::get();
        if ($blog) {
            return $blog->$attribute;
        }

        return null;
    }

    public static function name()
    {
        return static::attr('name');
    }
}
