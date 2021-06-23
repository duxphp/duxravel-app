<?php

namespace Duxravel\Core\Middleware;

class ManageRegister
{
    public function handle($request, \Closure $next)
    {
        $layer = strtolower(app_parsing('layer'));
        $count = auth($layer)->user()->count();
        if ($count) {
            return redirect()->route($layer . '.login');
        }
        return $next($request);
    }
}
