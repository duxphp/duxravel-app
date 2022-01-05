<?php

namespace Duxravel\Core\Manage;

use Illuminate\Http\Request;
use Duxravel\Core\Util\View;

/**
 * 用户登录
 */
trait Login
{

    public function check()
    {
        $layer = strtolower(app_parsing('layer'));
        $guard = config('auth.guards.' . $layer . '.provider');
        $model = config('auth.providers.' . $guard . '.model');
        $count = $model::count();
        return app_success('检测成功', [
            'register' => $count ? false : true
        ]);
    }

    public function submit(Request $request)
    {
        $layer = strtolower(app_parsing('layer'));
        $credentials = $request->only('username', 'password');
        if (auth($layer)->attempt([$this->usernameKey ?: 'username' => $credentials['username'], 'password' => $credentials['password']])) {
            $user = auth($layer)->user();
            $username = $this->usernameKey ? $user->{$this->usernameKey} : $user->username;
            return app_success('登录成功', [
                'userInfo' => [
                    'user_id' => $user->user_id,
                    'avatar' => $user->avatar,
                    'avatar_text' => strtoupper(substr($user->nickname ?: $username, 0, 1)),
                    'username' => $username,
                    'nickname' => $user->nickname,
                    'rolename' => $user->roles[0]['name'],
                ],
                'token' => 'Bearer ' . auth($layer)->tokenById($user->user_id),
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
