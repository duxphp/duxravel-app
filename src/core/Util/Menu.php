<?php

namespace Duxravel\Core\Util;

/**
 * 菜单工具
 */
class Menu
{
    private $data = [];

    /**
     * 增加菜单结构
     * @param string $type
     * @param callable $menu
     */
    public function add(string $type, callable $menu)
    {
        $this->data[$type][] = $menu;
    }

    /**
     * 获取菜单结构
     * @param string $type
     * @return array|mixed
     */
    public function get($type = 'admin')
    {
        $data = $this->data[$type] ?: [];
        $list = [];
        foreach ($data as $key => $vo) {
            $list[$key] = $vo();
        }
        return $list ?: [];
    }

    /**
     * 获取所有菜单
     * @param $layout
     * @return array
     */
    public function getAll($layout)
    {
        $list = app_hook('Service', 'Menu', 'get' . ucfirst($layout) . 'Menu');
        $extend = $this->get(strtolower($layout));
        $list = array_merge($list, $extend);
        $data = [];
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        return $data;
    }

    /**
     * 获取所有菜单
     * @param string $layout
     * @param string $route
     * @return array
     */
    public function getManage(string $layout = '', string $route = ''): array
    {
        $data = $this->getAll($layout);
        $ruleName = $route ?: request()->path();
        $ruleArr = explode('/', $ruleName);
        $ruleName = $ruleArr[0] . '/' . $ruleArr[1] . ($ruleArr[2] ? '/' . $ruleArr[2] : '') . '/';

        $data = collect($data)->sortBy('order');
        $roleList = auth(strtolower($layout))->user()->roles()->get();
        $purview = [];
        $roleList->map(function ($item) use (&$purview) {
            $purview = array_merge($purview, (array)$item->purview);
        });
        $purview = array_filter($purview);


        $list = [];
        foreach ($data as $app => $appList) {
            if (empty($appList['menu']) && empty($appList['url'])) {
                continue;
            }
            if ($appList['url'] && $purview && !in_array($appList['url'], $purview)) {
                continue;
            }
            $url = $appList['url'] ? route($appList['url'], $appList['params']) : '';
            $appData = [
                'name' => $appList['name'],
                'icon' => $appList['icon'],
                'url' => $url,
                'topic' => $appList['topic'],
                'hidden' => (bool)$appList['hidden'],
                'target' => $appList['target'],
                'cur' => false,
            ];
            if ($appList['url']) {
                if ($this->contrastRoute($url, $ruleName)) {
                    $appData['cur'] = true;
                }
            } else {
                $parentData = [];
                $appList['menu'] = collect($appList['menu'])->sortBy('order')->values();
                foreach ($appList['menu'] as $parent => $parentList) {
                    $parentData[$parent] = [
                        'name' => $parentList['name'],
                    ];
                    if (empty($parentList['menu'])) {
                        continue;
                    }
                    $subData = [];
                    $parentList['menu'] = collect($parentList['menu'])->sortBy('order')->values();
                    foreach ($parentList['menu'] as $sub => $subList) {
                        if ($purview && !in_array($subList['url'], $purview)) {
                            continue;
                        }
                        $url = route($subList['url'], $subList['params']);
                        $subData[$sub] = [
                            'name' => $subList['name'],
                            'url' => $url,
                            'target' => $subList['target'],
                            'cur' => false,
                        ];

                        if ($this->contrastRoute($url, $ruleName)) {
                            $subData[$sub]['cur'] = true;
                            $appData['cur'] = true;
                        }
                    }
                    if (empty($subData)) {
                        unset($parentData[$parent]);
                    } else {
                        $parentData[$parent]['menu'] = $subData;
                    }
                    if (empty($parentData)) {
                        unset($parentData);
                    } else {
                        $appData['menu'] = $parentData;
                    }
                }
            }
            if (!empty($appData)) {
                $list[$app] = $appData;
            } else {
                unset($list[$app]);
            }
        }
        return $list;

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
        } else {
            return true;
        }
    }

}