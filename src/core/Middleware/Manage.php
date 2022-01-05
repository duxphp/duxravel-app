<?php

namespace Duxravel\Core\Middleware;

use Duxravel\Core\Facades\Permission;
use Duxravel\Core\Util\View;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class Manage extends BaseMiddleware
{

    public function handle($request, \Closure $next)
    {
        // 渲染前端入口
        if (!request()->expectsJson()) {
            return View::manage();
        }

        // 检查此次请求中是否带有 token
        $this->checkForToken($request);

        // 获取当前授权层
        $layer = strtolower(app_parsing('layer'));

        try {
            if (auth($layer)->payload()) {
                app('auth')->shouldUse($layer);
                $this->checkPurview($layer);
                return $next($request);
            }
        } catch (TokenExpiredException $exception) {
            try{
                $token = auth($layer)->refresh();
                auth($layer)->onceUsingId(
                    auth($layer)->payload()->get('sub')
                );
                $this->checkPurview($layer);
                $response = $next($request);
                $response->headers->set('Authorization', 'Bearer ' . $token);
                return $response;
            }catch(JWTException $exception){
                app_error('登录失效', 401);
            }
        } catch (JWTException $exception) {
            $guard = config('auth.guards.' . $layer . '.provider');
            $model = config('auth.providers.' . $guard . '.model');
            $count = $model::count();
            if ($count) {
                app_error('登录失效', 401);
            } else {
                app_error('请注册用户', 402);
            }
        }
    }

    private function checkPurview($layer)
    {
        $user = auth($layer)->user();
        if (!$user) {
            app_error('登录失效', 401);
        }

        // 权限检测
        $public = request()->route()->getAction('public');
        if ($public) {
            return true;
        }

        $name = request()->route()->getName();
        if (!auth($layer)->user()->can($name)) {
            app_error('没有权限使用该功能', 403);
        }
    }
}
