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
     * @param $layout
     * @return array
     */
    public function getAll($layout)
    {
        $list = app_hook('Menu', 'get' . ucfirst($layout) . 'Menu');
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
        $layout = strtolower($layout);
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

            if ($appList['url']) {
                $public = app('router')->getRoutes()->getByName($appList['url'])->getAction('public');
                if (!$public && $purview && !in_array($appList['url'], $purview)) {
                    continue;
                }
            }
            $url = $appList['url'] ? route($appList['url'], $appList['params'], false) : '';

            $appData = [
                'app' => $app,
                'name' => $appList['name'],
                'icon' => $appList['icon'],
                'url' => $url,
                'route' => $appList['side'] ? route($layout . '.side', ['app' => $app], false) : '',
                'data' => $appList['data'],
                'topic' => $appList['topic'],
                'hidden' => (bool)$appList['hidden'],
                'target' => $appList['target'],
                'cur' => false,
            ];
            if ($appList['url']) {
                if ($this->contrastRoute($url, $ruleName)) {
                    $appData['cur'] = true;
                    $appData['hidden'] = false;
                }
            }
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
                    $public = app('router')->getRoutes()->getByName($subList['url'])->getAction('public');

                    if (!$public && $purview && !in_array($subList['url'], $purview)) {
                        continue;
                    }
                    $url = route($subList['url'], $subList['params'], false);
                    $subData[$sub] = [
                        'name' => $subList['name'],
                        'url' => $url,
                        'target' => $subList['target'],
                        'cur' => false,
                    ];

                    if (!$appData['url']) {
                        $appData['url'] = $url;
                    }

                    if ($this->contrastRoute($url, $ruleName)) {
                        $subData[$sub]['cur'] = true;
                        $appData['cur'] = true;
                        $appData['hidden'] = false;
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
            if (!empty($appData)) {
                $list[$app] = $appData;
            } else {
                unset($list[$app]);
            }
        }
        return $list;

    }

    public function getSide($layout, $app)
    {
        $list = app_hook('Menu', 'get' . ucfirst($layout) . 'Side', [$app]);
        $data = [];
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        return $data;
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

    /**
     * 渲染折叠菜单
     * @param $data
     * @return array [[label, key, children]]
     */
    public static function renderCollapse($title, $icon, $data)
    {

        return [
            'node' => [
                [
                    'nodeName' => 'div',
                    'class' => 'flex items-center p-4',
                    'child' => [
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-none bg-blue-900 rounded-l text-white flex items-center justify-center w-10 h-10',
                            'child' => (new Icon('academic-cap'))->class('flex-none')->size(20)->getRender()
                        ],
                        [
                            'nodeName' => 'div',
                            'class' => 'flex-grow bg-gray-300 h-10 flex rounded-r items-center pl-4',
                            'child' => '新闻分类'
                        ]
                    ]
                ],
                [
                    'nodeName' => 'n-menu',
                    'root-indent' => 20,
                    'indent' => 20,
                    'class' => 'w-48',
                    'vBind:default-value' => 'location.href.substr(location.origin.length)',
                    'render-label:option' => [
                        'nodeName' => 'route',
                        'vBind:href' => 'option.key',
                        'child' => [
                            'nodeName' => 'n-ellipsis',
                            'child' => '{{option.label}}'
                        ]
                    ],
                    'options' => $data
                ]
            ]
        ];
    }

}
