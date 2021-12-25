<?php

namespace Duxravel\Core\Middleware;

class ManageRegister
{
    public function handle($request, \Closure $next)
    {
        $layer = strtolower(app_parsing('layer'));
        $guard = config('auth.guards.'.$layer . '.provider');
        $model = config('auth.providers.' .$guard . '.model');
        $count = $model::count();
        if ($count) {
            app_error('登录失效，请进行登录', 401);
        }
        return $next($request);
    }
}
