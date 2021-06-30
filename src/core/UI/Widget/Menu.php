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
    protected string $type;
    protected array $link = [];

    /**
     * Menu constructor.
     * @param string $name
     * @param callable|null $callback
     */
    public function __construct(string $name, string $type = 'blue', callable $callback = NULL)
    {
        $this->name = $name;
        $this->type = $type;
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
        $link->restClass('flex p-2 hover:bg-gray-200');
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
            $inner[] = '<div>' . $class->render() . '</div>';
        }
        $this->class('shadow absolute right-0 w-40 pt-1 pb-1 mt-2 rounded-sm bg-white');
        return <<<HTML
            <div x-data="{open: false}" class="relative">
                <button class="btn-$this->type" type="button" @click="open = !open">$this->name</button>
                <div {$this->toElement()}  x-cloak @click.outside="open = false" x-show="open">
                {$this->mergeArray($inner)}
                </div>
            </div>
        HTML;

    }

}
