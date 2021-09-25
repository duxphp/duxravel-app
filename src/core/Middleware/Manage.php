<?php

namespace Duxravel\Core\Middleware;

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
            return response()->view('manage');
        }

        // 检查此次请求中是否带有 token
        $this->checkForToken($request);

        // 获取当前授权层
        $layer = strtolower(app_parsing('layer'));

        // 权限app
        app()->singleton('purview_app', function () use ($layer) {
            return $layer;
        });

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

        // 权限检测
        $public = request()->route()->getAction('public');
        if ($public) {
            return true;
        }

        // 非公共方法进行验证
        $name = request()->route()->getName();
        $roleList = auth($layer)->user()->roles()->get();

        // 合并多角色权限
        $purview = [];
        $roleList->map(function ($item) use (&$purview) {
            $purview = array_merge($purview, (array)$item->purview);
        });
        $purview = array_filter($purview);
        app()->singleton('purview', function () use ($purview) {
            return $purview;
        });

        // 权限存在判断
        if ($purview) {
            foreach ($purview as $vo) {
                $arr = explode('|', $vo);
                if ($arr[0] === $name) {
                    return true;
                }
            }
            app_error('没有权限使用该功能', 403);
        }
    }
}
