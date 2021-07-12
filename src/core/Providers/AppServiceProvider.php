<?php

namespace Duxravel\Core\Providers;

use Duxravel\Core\Util\Build;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
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
    public function boot()
    {

        // 异常级别
        error_reporting(E_ALL^E_WARNING^E_NOTICE);

        // 注册模板组件
        Blade::component('app-loading', \Duxravel\Core\UI\Components\Loading::class);
        Blade::component('app-nodata', \Duxravel\Core\UI\Components\NoData::class);
        Blade::component('app-trend', \Duxravel\Core\UI\Components\Trend::class);
        Blade::directive('paginate', function ($label) {
            return '<?php echo $pageData ? $pageData->links(' . $label . ') : "" ?>';
        });
        \Duxravel\Core\Util\Blade::loopMake('form', \Duxravel\Core\Service\Blade::class, 'form');

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

    }
}
