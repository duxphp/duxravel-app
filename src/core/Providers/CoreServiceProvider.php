<?php

namespace Duxravel\Core\Providers;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        $this->commands([
            \Duxravel\Core\Console\App::class,
            \Duxravel\Core\Console\AppAdmin::class,
            \Duxravel\Core\Console\AppModel::class,
            \Duxravel\Core\Console\Install::class,
            \Duxravel\Core\Console\Operate::class,
            \Duxravel\Core\Console\Uninstall::class,
            \Duxravel\Core\Console\Visitor::class,
        ]);

        $router->get('/', [Duxravel\Core\Web\Index::class, 'index'])->name('web.index');
        $router->get('service/image/placeholder/{w}/{h}/{t}', [Duxravel\Core\Web\Image::class, 'placeholder'])->name('service.image.placeholder');
        $router->get('service/area', [Duxravel\Core\Web\Area::class, 'index'])->name('service.area');

        \Route::macro('manage', function ($class, $name = '') {
            return (new \Duxravel\Core\Util\Route($class, $name));
        });

        Blade::component('app-loading', \Duxravel\Core\UI\Components\Loading::class);
        Blade::component('app-nodata', \Duxravel\Core\UI\Components\NoData::class);
        Blade::component('app-trend', \Duxravel\Core\UI\Components\Trend::class);
        Blade::directive('paginate', function ($label) {
            return '<?php echo $pageData ? $pageData->links('.$label.') : "" ?>';
        });


        Builder::macro('findInSet', function ($field, $value) {
            return $this->whereRaw("FIND_IN_SET(?, {$field})", $value);
        });
        Builder::macro('orderByWith', function ($relation, $column, $direction = 'asc'): Builder {
            /** @var Builder $this */
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

        app_hook('Service', 'App', 'extend');
    }
}
