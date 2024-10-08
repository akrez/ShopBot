<?php

namespace App\Providers;

use App\Support\ActiveBlog;
use App\Support\ArrayHelper;
use App\Support\Hosts;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
        $this->app->singleton('ArrayHelper', function () {
            return new ArrayHelper;
        });
        $this->app->singleton('Hosts', function () {
            return new Hosts(storage_path('hosts.json'));
        });
        $this->app->alias('Arr', Arr::class);
        //
        Blade::directive('spaceless', function () {
            return '<?php ob_start(); ob_implicit_flush(false); ?>';
        });
        Blade::directive('endspaceless', function () {
            return "<?php echo trim(preg_replace('/>\s+</', '><', ob_get_clean())); ?>";
        });
        //
        Paginator::useBootstrapFive();
    }
}
