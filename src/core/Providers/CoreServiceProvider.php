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
        // 注册核心配置
        foreach (glob(__DIR__ . '/../Config/*.php') as $vo) {
            $this->mergeConfigFrom($vo, basename($vo, '.php'));
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

        // 调用系统扩展
        app_hook('App', 'extend');
    }
}
