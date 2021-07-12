<?php

namespace Duxravel\Core\Providers;

use Duxravel\Core\Util\Build;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 扩展路由方法
        \Route::macro('manage', function ($class, $name = '') {
            return (new \Duxravel\Core\Util\Route($class, $name));
        });

        // 注册组件
        $this->app->singleton(Build::class);

        // 编译包
        app(Build::class)->getBuild();

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {


    }
}
