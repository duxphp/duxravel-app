<?php

namespace Duxravel\Core\Manage;

use Illuminate\Http\Request;
use Duxravel\Core\Util\View;

/**
 * 用户登录
 * @package Modules\System\System
 */
trait Login
{

    public function index()
    {
        return (new View('vendor.duxphp.duxravel-app.src.core.Views.Manage.Login.index'))->render('layout');
    }

    public function submit(Request $request)
    {
        $layer = strtolower(app_parsing('layer'));
        $credentials = $request->only('username', 'password');
        if (auth($layer)->attempt($credentials)) {
            $user = auth($layer)->user();
            return app_success('登录成功', [
                'userInfo' => [
                    'user_id' => $user->user_id,
                    'avatar' => $user->avatar,
                    'username' => $user->username,
                    'nickname' => $user->nickname
                ],
                'token' => 'Bearer ' . auth($layer)->tokenById($user->user_id),
                'menu' => [
                    [
                        'name' => '返回首页',
                        'url' => route('web.index'),
                        'target' => 'new'
                    ]
                ]
            ]);
        }
        app_error('账号密码错误');
    }

    public function logout()
    {
        $layer = strtolower(app_parsing('layer'));
        auth($layer)->logout();
        return redirect(route($layer . '.login'));
    }
}
