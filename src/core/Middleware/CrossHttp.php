<?php

namespace Duxravel\Core\Middleware;

use Closure;

class CrossHttp
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*'); //允许所有资源跨域
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept, Authorization, application/json , X-Auth-Token');//允许通过的响应报头
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS, DELETE');//允许的请求方法
        $response->header('Access-Control-Expose-Headers', 'Authorization');//允许axios获取响应头中的Authorization
        $response->header('Allow', 'GET, POST, PATCH, PUT, OPTIONS, delete');//允许的请求方法
        $response->header('Access-Control-Allow-Credentials', 'true');//运行客户端携带证书式访问
        return $response;
    }
}