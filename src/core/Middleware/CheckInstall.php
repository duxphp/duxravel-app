<?php


namespace Duxravel\Core\Middleware;


use Illuminate\Http\Request;

class CheckInstall
{
    public function handle(Request $request, \Closure $next)
    {

        if (!file_exists(storage_path('installed')) && strpos($request->getRequestUri(), '/install', 0) === false) {
            return redirect(url('/install'));
        }
        return $next($request);
    }
}
