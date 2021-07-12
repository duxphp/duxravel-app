<?php

namespace Duxravel\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    public function boot(Router $router)
    {

        // 注册公共路由
        $router->group(['prefix' => 'service', 'middleware' => ['web']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Service');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });
        $router->group(['middleware' => ['api'], 'statis' => true], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Api');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });
        $router->group(['middleware' => ['api', 'auth.api']], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('AuthApi');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });
        $router->group(['middleware' => ['web'], 'statis' => true], function () {
            $list = \Duxravel\Core\Util\Cache::routeList('Web');
            foreach ($list as $file) {
                if (is_file($file)) {
                    $this->loadRoutesFrom($file);
                }
            }
        });

        // 请求频率限制
        /*RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });*/
    }
}
