<?php

namespace Duxravel\Core\Middleware;

use Closure;
use Illuminate\Http\Request;

define('START_TIME', microtime(true));

class VisitorBefore
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
