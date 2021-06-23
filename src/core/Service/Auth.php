<?php

namespace Duxravel\Core\Service;

/**
 * Class Auth
 * @package Duxravel\Core\Service
 */
class Auth
{
    /**
     * 获取权限列表
     * @param  string  $has
     * @return array
     */
    public function getAuthAll($has = 'admin'): array
    {
        $app = app();
        $routes = $app->routes->getRoutes();
        $data = [];
        foreach ($routes as $vo) {
            if ($vo->action['auth_has'] <> $has || $vo->action['public']) {
                continue;
            }
            if (!$data[$vo->action['auth_app']]) {
                $data[$vo->action['auth_app']] = [
                    'name'  => $vo->action['auth_app'],
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
                'name'  => $vo->action['desc'],
                'value' => $vo->action['as'],
            ];
        }
        return $data;
    }

}
