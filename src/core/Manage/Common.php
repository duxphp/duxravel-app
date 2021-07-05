<?php

namespace Duxravel\Core\Manage;

use Duxravel\Core\Util\View;

/**
 * 管理端基础接口
 * @package Duxravel\Core\Model
 */
trait Common
{

    protected array $assign = [];

    /**
     * 模板赋值
     * @param $name
     * @param $value
     */
    public function assign($name, $value): void
    {
        $this->assign[$name] = $value;
    }

    public function systemView(string $tpl = '', string $route = '')
    {
        return (new View($tpl, $this->assign))->route($route)->render();
    }

    public function layoutView(string $tpl = '')
    {
        return (new View($tpl, $this->assign))->render('layout');
    }

    public function dialogView(string $tpl = '')
    {
        return (new View($tpl, $this->assign))->render('dialog');
    }

    public function isAuth($name)
    {
        $route = request()->route()->getName();
        $purview = app()->make('purview');
        if ($purview && !in_array($route . '|' . $name, $purview)) {
            app_error('没有权限使用该功能', 403);
        }
    }

}
