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

    public function render()
    {
        $assign = $this->data;
        $parsing = app_parsing();

        if (!$this->tpl) {
            $view = [$parsing['app'], 'View', $parsing['layer'], $parsing['module'], $parsing['action']];
            $this->tpl = implode('.', $view);
        }
        $assign['manage'] = strtolower($parsing['layer']);
        return view($this->tpl, $assign);
    }

    public static function manage()
    {
        $manifest = json_decode(file_get_contents(public_path('static/manage-manifest.json')), true);

        $mainJs = $manifest['src/main.js']['file'];
        $mainCss = $manifest['src/main.js']['css'][0];

        return response()->view('vendor.duxphp.duxravel-app.src.core.Views.manage', [
            'mainJs' => $mainJs,
            'mainCss' => $mainCss
        ]);
    }
}
