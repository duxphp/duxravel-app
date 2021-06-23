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
        $status = auth($layer)->attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->has('remember'));
        if ($status) {
            return app_success('登录成功', [], route($layer . '.index'));
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
