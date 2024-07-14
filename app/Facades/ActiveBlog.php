<?php

namespace App\Facades;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ?Blog set(?User $user)
 * @method static ?Blog get()
 * @method static bool has()
 * @method static mixed attr(string $attribute)
 * @method static ?string name()
 */
class ActiveBlog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ActiveBlog';
    }
}
