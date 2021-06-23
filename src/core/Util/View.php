<?php

namespace Duxravel\Core\Util;


class View
{

    private string $tpl;
    private array $data;
    private string $route = '';
    private $menuClass;

    public function __construct($tpl = '', $data = [])
    {
        $this->tpl = $tpl;
        $this->data = $data;
    }

    public function route(string $route = ''): self
    {
        $this->route = $route;
        return $this;
    }

    public function menu($class): self
    {
        if (!method_exists($class, 'getData')) {
            abort(500, '菜单类方法不存在');
        }
        $this->menuClass = $class;
        return $this;
    }

    public function render($type = 'base')
    {
        $assign = $this->data;

        $parsing = app_parsing();
        if ($type === 'base' || $type === 'layout' || $type === 'dialog') {
            if (!$this->tpl) {
                $view = [$parsing['app'], 'View', $parsing['layer'], $parsing['module'], $parsing['action']];
                $this->tpl = implode('.', $view);
            }
            $assign['layout'] = $this->tpl;
        }
        if ($type === 'base') {
            $list = $this->getMenu($parsing['layer'] ?: '', $this->route);
            $list = array_values($list);
            $menuActive = 0;
            foreach ($list as $key => $app) {
                if ($app['cur']) {
                    $menuActive = $key;
                }
            }
            $assign['menuList'] = $list;
            $assign['menuActive'] = $menuActive;
            $view = 'Common.UI.View.base';
        }

        if ($type === 'layout') {
            $view = 'Common.UI.View.layout';
        }

        if ($type === 'dialog') {
            $view = 'Common.UI.View.dialog';
        }

        if ($type === 'web') {
            $theme = config('theme.default');
            \View::share('theme', function ($url) use ($theme) {
                return \URL::asset('themes/' . $theme) . '/' . $url;
            });
            foreach ($assign as $key => $vo) {
                \View::share($key, $vo);
            }
            $view = $this->tpl;
        }
        return view($view, $assign);
    }


    public function getMenu(string $layout = '', string $route = ''): array
    {
        $list = app_hook('Service', 'Menu', 'get' . ucfirst($layout) . 'Menu');

        $data = [];
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }

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
