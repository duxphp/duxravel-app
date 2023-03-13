<?php

namespace Duxravel\Core\Util;

use Duxravel\Core\UI\Widget\Icon;

/**
 * 菜单工具
 */
class Menu
{
    /**
     * 获取所有菜单
     * @param string $layout
     * @return array
     */
    public function getAll(string $layout): array
    {
        $layout = ucfirst($layout);
        $serviceList = app(Build::class)->getData('menu.' . $layout);
        $vendor = base_path();
        foreach ($serviceList as $key => $vo) {
            require_once $vendor . '/' . $vo;
        }
        return \Duxravel\Core\Facades\Menu::getData();
    }

    /**
     * 获取所有菜单
     * @param string        $layout
     * @param \Closure|null $check
     * @return array
     */
    public function getManage(string $layout = '', ?\Closure $check = null): array
    {
        $layout = strtolower($layout);
        $data = $this->getAll($layout);

        $data = collect($data)->sortBy('order');

        $user = auth(strtolower($layout))->user();
        if ($user->roles) {
            $roleList = auth(strtolower($layout))->user()->roles()->get();
        } else {
            $roleList = collect();
        }
        $purview = [];
        $roleList->map(function ($item) use (&$purview) {
            $purview = array_merge($purview, (array)$item->purview);
        });
        $purview = array_filter($purview);

        $list = [];
        foreach ($data as $app => $appList) {
            if (empty($appList['menu']) && empty($appList['route'])) {
                continue;
            }

            if ($appList['route']) {
                $routeItem = app('router')->getRoutes()->getByName($appList['route']);
                // 非公共路由检测权限
                $public = $routeItem ? $routeItem->getAction('public') : false;
                if (!$public && $purview && !in_array($appList['route'], $purview)) {
                    continue;
                }

                // 自定义检测权限路由
                $roteCheck = $routeItem ? $routeItem->getAction('auth_check') : false;
                if ($roteCheck && $check && !$check($appList, $routeItem)) {
                    continue;
                }
            }
            $url = $appList['route'] ? route($appList['route'], $appList['params'], false) : '';

            $appData = [
                'app' => $app,
                'name' => $appList['name'],
                'title' => $appList['title'],
                'icon' => $appList['icon'],
                'url' => $url,
                'route' => $appList['side'] ? route($layout . '.side', ['app' => $app], false) : '',
                'topic' => $appList['topic'],
                'hidden' => (bool)$appList['hidden'],
                'target' => $appList['target'],
            ];
            $parentData = [];
            $appList['menu'] = collect($appList['menu'])->sortBy('order')->values();
            foreach ($appList['menu'] as $parent => $parentList) {
                $parentData[$parent] = [
                    'name' => $parentList['name'],
                    'title' => $parentList['title'],
                ];
                if (empty($parentList['menu'])) {
                    continue;
                }
                $subData = [];
                $parentList['menu'] = collect($parentList['menu'])->sortBy('order')->values();
                foreach ($parentList['menu'] as $sub => $subList) {
                    $routeItem = app('router')->getRoutes()->getByName($subList['route']);
                    // 非公共路由检测权限
                    $public = $routeItem ? $routeItem->getAction('public') : false;
                    if (!$public && $purview && !in_array($subList['route'], $purview)) {
                        continue;
                    }
                    // 自定义检测权限路由
                    $roteCheck = $routeItem ? $routeItem->getAction('auth_check') : false;
                    if ($roteCheck && $check && !$check($subList, $routeItem)) {
                        continue;
                    }

                    $url = route($subList['route'], $subList['params'], false);
                    $subData[$sub] = [
                        'name' => $subList['name'],
                        'title' => $subList['title'],
                        'url' => $url,
                        'target' => $subList['target'],
                    ];

                    if (!$appData['url']) {
                        $appData['url'] = $url;
                    }

                }
                if (empty($subData)) {
                    unset($parentData[$parent]);
                } else {
                    $parentData[$parent]['menu'] = array_values($subData);
                }
                if (empty($parentData)) {
                    unset($parentData);
                } else {
                    $appData['menu'] = array_values($parentData);
                }
            }
            if (!empty($appData['url']) || !empty($appData['menu'])) {
                $list[$app] = $appData;
            } else {
                unset($list[$app]);
            }
        }
        return $list;

    }

    /**
     * 获取静态资源
     * @param string        $layout
     * @return array
     */
    public function getStatic(string $layout = ''): array
    {
        $data = $this->getAll(strtolower($layout));

        $static = [];
        $staticType = [
            'style' => 'string',
            'css' => 'array',
            'scriptString' => 'string',
            'script' => 'array'
        ];
        foreach ($data as $appList) {
            // 处理静态资源返回
            foreach ($staticType as $key => $type) {
                if ($appList['static'][$key]) {
                    if (!$static[$key]) {
                        $static[$key] = $type === 'string' ? '' : [];
                    }
                    if ($type === 'string') {
                        $static[$key] .= "\n" . $appList['static'][$key];
                    } else {
                        $static[$key] = array_merge($static[$key], $appList['static'][$key]);
                    }
                }
            }
            
        }
        return $static;

    }

    /**
     * 获取应用菜单
     * @return array
     */
    public function getApps(): array
    {
        $apps = \Duxravel\Core\Facades\Menu::getApps();
        foreach ($apps as $key => $vo) {
            $apps[$key]['url'] = $vo['route'] ? route($vo['route'], $vo['params'], false) : '';
        }
        return $apps;
    }

    /**
     * 菜单路由转换
     * @param $url
     * @param $value
     * @return bool
     */
    public function contrastRoute($url, $value): bool
    {
        $path = trim(parse_url($url, PHP_URL_PATH), '/') . '/';
        if (strpos($path, $value, 0) === false) {
            return false;
        }

        return true;
    }

}
