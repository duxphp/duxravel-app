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

        $layer = strtolower(app_parsing('layer'));
        config(['auth.defaults.guard' => $layer]);

        // 检查此次请求中是否带有 token
        $this->checkForToken($request);
        try {
            // 检测用户的登录状态
            if ($this->auth->parseToken()->authenticate()) {
                $this->checkPurview($layer);
                return $next($request);
            }
            app_error('请先进行登录', 401);
        } catch (TokenExpiredException $exception) {
            try{
                // 刷新token
                $token = $this->auth->refresh();
                $request->headers->set('Authorization', 'Bearer ' . $token);
                //使用一次性登录
                auth($layer)->onceUsingId(
                    $this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']
                );
                $this->checkPurview($layer);
            }catch(JWTException $exception){
                app_error('登录失效', 401);
            }
        }
        return $this->setAuthenticationHeader($next($request), $token);
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
        if (!$user->can($name)) {
            app_error('没有权限使用该功能', 403);
        }
    }
}
