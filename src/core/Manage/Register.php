<?php

namespace Duxravel\Core\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Duxravel\Core\Util\View;
use Modules\System\Model\SystemUser;

/**
 * 注册用户
 * @package Modules\System\System
 */
trait Register
{

    public function index()
    {
        return (new View('vendor.duxphp.duxravel-app.src.core.Views.Manage.Register.index'))->render('layout');
    }

    public function submit(Request $request)
    {

        Validator::make($request->input(), [
            'username' => ['required', 'string', 'max:255', 'unique:system_user'],
            'password' => ['required', 'string', 'min:4', 'max:20', 'confirmed'],
        ], [
            'username.required' => '用户名输入错误',
            'username.unique' => '用户名不能重复',
            'password.required' => '请输入4~20位密码',
            'password.confirmed' => '确认密码输入不正确',
        ])->validate();

        $parsing = app_parsing();
        $app = $parsing['app'];
        $layer = strtolower($parsing['layer']);

        $model = '\\Modules\\' . $app . '\\Model\\' . $app . 'User';
        $user = new $model();

        $user->username = $request->input('username');
        $user->password = $request->input('password');
        $user->user_id = 1;
        $user->roles()->attach(1);
        $user->save();

        return app_success('创建账号成功，请进行登录', [], route($layer . '.login'));
    }
}
