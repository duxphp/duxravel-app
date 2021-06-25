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

        // 全局中间件
        $httpKernel = $this->app->make(HttpKernel::class);
        $httpKernel->pushMiddleware(\Duxravel\Core\Middleware\CheckInstall::class);
        $httpKernel->pushMiddleware(\Duxravel\Core\Middleware\VisitorBefore::class);
        $httpKernel->pushMiddleware(\Duxravel\Core\Middleware\VisitorAfter::class);


        // 别名中间件
        //$router->aliasMiddleware();

        // 别名中间件
        $router->middlewareGroup('auth.manage', [
            'web',
            \Duxravel\Core\Middleware\Manage::class,
        ]);
        $router->middlewareGroup('auth.manage.register', [
            'web',
            \Duxravel\Core\Middleware\ManageRegister::class,
        ]);

        // 增加分组中间件
        $router->pushMiddlewareToGroup('web', \Duxravel\Core\Middleware\Web::class);
        $router->pushMiddlewareToGroup('api', \Duxravel\Core\Middleware\Api::class);
        $router->pushMiddlewareToGroup('api', \Duxravel\Core\Middleware\Header::class);

        // 命令行注册
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Duxravel\Core\Console\AppBuild::class,
                \Duxravel\Core\Console\App::class,
                \Duxravel\Core\Console\AppAdmin::class,
                \Duxravel\Core\Console\AppModel::class,
                \Duxravel\Core\Console\Install::class,
                \Duxravel\Core\Console\Uninstall::class,
                \Duxravel\Core\Console\InstallStatic::class,
                \Duxravel\Core\Console\UninstallStatic::class,
                \Duxravel\Core\Console\Operate::class,
                \Duxravel\Core\Console\Visitor::class,
            ]);
            $list = \Duxravel\Core\Util\Cache::globList(base_path('modules') . '/*/Console/*.php');
            foreach ($list as $file) {
                $this->commands[] = file_class($file);
            }
        }

        // 注册公共路由
        $router->group(['prefix' => 'service', 'middleware' => ['web']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Service');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });
        $router->group(['middleware' => ['api']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Api');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });
        $router->group(['middleware' => ['api', 'auth.api']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('AuthApi');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });
        $router->group(['middleware' => ['web']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Web');
            foreach ($list as $file) {
                $this->loadRoutesFrom($file);
            }
        });

        // 注册模板组件
        Blade::component('app-loading', \Duxravel\Core\UI\Components\Loading::class);
        Blade::component('app-nodata', \Duxravel\Core\UI\Components\NoData::class);
        Blade::component('app-trend', \Duxravel\Core\UI\Components\Trend::class);
        Blade::directive('paginate', function ($label) {
            return '<?php echo $pageData ? $pageData->links(' . $label . ') : "" ?>';
        });

        // 扩展数据库方法
        Builder::macro('findInSet', function ($field, $value) {
            return $this->whereRaw("FIND_IN_SET(?, {$field})", $value);
        });
        Builder::macro('orderByWith', function ($relation, $column, $direction = 'asc'): Builder {
            if (is_string($relation)) {
                $relation = $this->getRelationWithoutConstraints($relation);
            }
            return $this->orderBy(
                $relation->getRelationExistenceQuery(
                    $relation->getRelated()->newQueryWithoutRelationships(),
                    $this,
                    $column
                ),
                $direction
            );
        });

        // 注册数据库目录
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../../../database/migrations'));

        // 调用系统扩展
        app_hook('Service', 'App', 'extend');
    }
}
