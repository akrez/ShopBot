<?php

namespace App\Providers;

use App\Supports\ActiveBlog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton('ActiveBlog', function () {
            return new ActiveBlog(Auth::user());
        });
    }
}
