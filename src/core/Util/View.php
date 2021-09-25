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
        } else {
            if (!$this->tpl) {
                $view = [$parsing['app'], 'View', $parsing['layer'], $parsing['module'], $parsing['action']];
                $this->tpl = implode('.', $view);
            }
            $assign['manage'] = strtolower($parsing['layer']);
            $view = $this->tpl;
        }
        return view($view ?: $this->tpl, $assign);
    }


}
