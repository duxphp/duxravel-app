<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Widget\Link;

/**
 * 下拉菜单
 * Class Menu
 * @package Duxravel\Core\UI\Widget
 */
class Menu extends Widget
{

    protected string $name;
    protected array $link = [];

    /**
     * Menu constructor.
     * @param string $name
     * @param callable|null $callback
     */
    public function __construct(string $name, callable $callback = NULL)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    /**
     * @param string $name
     * @param string $route
     * @param array $params
     * @return Link
     */
    public function link(string $name, string $route = '', array $params = []): Link
    {
        $link = new Link($name, $route, $params);
        $link->class('block p-2 hover:bg-gray-200');
        $this->link[] = $link;
        return $link;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $inner = [];
        foreach ($this->link as $class) {
            $inner[] = $class->render();
        }
        $this->class('dropdown mt-1 left-0');
        return <<<HTML
            <divx-data="show.dropdown()">
                <button class="btn-blue" type="button" x-on:click="open = true">$this->name</button>
                <div {$this->toElement()}  x-cloak x-spread="dropdown">
                {$this->mergeArray($inner)}
                </div>
            </div>
        HTML;

    }

}
