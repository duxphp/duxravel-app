<?php

namespace Duxravel\Core\Util;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

/**
 * 权限管理
 */
class Permission
{

    public string $guerd = 'admin';
    public bool $cache = false;
    public array $routePurviews = [];
    public array $allPurviews = [];

    /**
     * 获取守护器
     * @return string
     */
    public function getGuerd()
    {
        return $this->guerd;
    }

    /**
     * 注册权限验证器
     * @param string $has
     */
    public function register($guerd = '')
    {
        $this->guerd = strtolower($guerd);
        $routes = \Route::getRoutes();
        $data = [];
        foreach ($routes as $vo) {
            if ($vo->action['auth_list']) {
                foreach ($vo->action['auth_list'] as $k => $v) {
                    $data[] = $vo->action['as'] . '|' . $k;
                }
            }
            $data[] = $vo->action['as'];
        }

        foreach ($data as $vo) {
            Gate::define($vo, fn($user) => \Duxravel\Core\Facades\Permission::checkPermissions($user, $vo));
        }
    }

    /**
     * 设置用户权限
     * @param $user
     * @return bool|void
     */
    public function setPermissions($user)
    {
        if ($this->cache) {
            return true;
        }
        // 获取用户权限
        $roleList = $user->roles()->get();

        // 合并多角色权限
        $roleList->map(function ($item) {
            foreach ($item->purview as $vo) {
                $arr = explode('|', $vo);
                $this->routePurviews[] = $arr[0];
                $this->allPurviews[] = $vo;
            }
        });
        $this->routePurviews = array_filter($this->routePurviews);
        $this->allPurviews = array_filter($this->allPurviews);
        $this->cache = true;
    }

    /**
     * 验证当前权限
     * @param $user
     * @param $rule
     */
    public function checkPermissions($user, $rule)
    {
        $this->setPermissions($user);

        if (!$this->allPurviews) {
            return true;
        }

        if (strpos($rule, '|') !== false) {
            if (in_array($rule, $this->allPurviews)) {
                return true;
            } else {
                return false;
            }
        }
        if (in_array($rule, $this->routePurviews)) {
            return true;
        }
        return false;
    }

    /**
     * 获取树形权限
     * @param string $has
     * @return array
     */
    public function getPermissions(): array
    {
        $routes = \Route::getRoutes();
        $data = [];
        foreach ($routes as $vo) {
            if ($vo->action['auth_has'] <> $this->guerd || $vo->action['public']) {
                continue;
            }
            if (!$data[$vo->action['auth_app']]) {
                $data[$vo->action['auth_app']] = [
                    'name' => $vo->action['auth_app'],
                    'group' => []
                ];
            }
            if (!$data[$vo->action['auth_app']]['group'][$vo->action['auth_group']]) {
                $data[$vo->action['auth_app']]['group'][$vo->action['auth_group']] = [
                    'name' => $vo->action['auth_group'],
                    'list' => []
                ];
            }
            $data[$vo->action['auth_app']]['group'][$vo->action['auth_group']]['list'][] = [
                'name' => $vo->action['desc'],
                'value' => $vo->action['as'],
                'auth_list' => $vo->action['auth_list']
            ];
        }
        return $data;
    }


}
