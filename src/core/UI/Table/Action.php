<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Widget\Menu;

/**
 * 操作动作
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Action
{

    protected array $button = [];
    protected array $menu = [];

    /**
     * 按钮
     * @param string $name
     * @param string $route
     * @param array  $params
     * @param string $type
     * @return Link
     */
    public function button(string $name, string $route = '', array $params = [], string $type = 'primary'): Link
    {
        $link = new Link($name, $route, $params);
        $link->button($type);
        $this->button[] = $link;
        return $link;
    }

    /**
     * 菜单按钮
     * @param string $name
     * @param string $type
     * @return Menu
     */
    public function menu(string $name, string $type = 'default'): Menu
    {
        $menu = new Menu($name, $type);
        $this->menu[] = $menu;
        return $menu;
    }

    /**
     * 渲染组件
     * @return array
     */
    public function render(): array
    {
        $node = [];
        foreach ($this->menu as $menu) {
            $node[] = $menu->getRender();
        }
        foreach ($this->button as $class) {
            $node[] = $class->getRender();
        }
        return $node;
    }

}
