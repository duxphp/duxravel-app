<?php

namespace Duxravel\Core\Middleware;

class Manage
{
    public function handle($request, \Closure $next)
    {
        // 登录跳转
        $layer = strtolower(app_parsing('layer'));
        if (!auth($layer)->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                app_error('登录失效', 401, route($layer . '.login'));
            } else {
                $guard = config('auth.guards.' . $layer . '.provider');
                $model = config('auth.providers.' . $guard . '.model');
                $count = $model::count();
                if ($count) {
                    return redirect()->intended(route($layer . '.login'));
                } else {
                    return redirect()->intended(route($layer . '.register'));
                }
            }
        }
        app()->singleton('purview_app', function () use ($layer) {
            return $layer;
        });

        // 权限检测
        $public = request()->route()->getAction('public');
        if (!$public) {
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
                        return $next($request);
                    }
                }
                app_error('没有权限使用该功能', 403);
            }
        }
        return $next($request);
    }
}
