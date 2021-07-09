<?php

namespace Duxravel\Core\Util;

use Jenssegers\Agent\Agent;


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
        $view = '';

        $parsing = app_parsing();
        if ($type === 'base' || $type === 'layout' || $type === 'dialog') {
            if (!$this->tpl) {
                $view = [$parsing['app'], 'View', $parsing['layer'], $parsing['module'], $parsing['action']];
                $this->tpl = implode('.', $view);
            }
            $assign['layout'] = $this->tpl;
            $assign['manage'] = strtolower($parsing['layer']);
        }
        if ($type === 'base') {
            $list = app(\Duxravel\Core\Util\Menu::class)->getManage($parsing['layer'] ?: '', $this->route);
            $list = array_values($list);
            $menuActive = 0;
            foreach ($list as $key => $app) {
                if ($app['cur']) {
                    $menuActive = $key;
                }
            }
            $assign['menuList'] = $list;
            $assign['menuActive'] = $menuActive;
            $view = 'vendor.duxphp.duxravel-app.src.core.UI.View.base';
        }

        if ($type === 'layout') {
            $view = 'vendor.duxphp.duxravel-app.src.core.UI.View.layout';
        }

        if ($type === 'dialog') {
            $view = 'vendor.duxphp.duxravel-app.src.core.UI.View.dialog';
        }

        if ($type === 'web') {
            $agent = new Agent();

            $theme = config('theme.default');
            $mobileTheme = config('theme.mobile');
            if ($mobileTheme && ($agent->isMobile() || $agent->isTablet())) {
                $theme = $mobileTheme;
            }
            \View::share('theme', function ($url) use ($theme) {
                return \URL::asset('themes/' . $theme) . '/' . $url;
            });
            foreach ($assign as $key => $vo) {
                \View::share($key, $vo);
            }
            $view = $this->tpl;
        }
        return view($view ?: $this->tpl, $assign);
    }


}
