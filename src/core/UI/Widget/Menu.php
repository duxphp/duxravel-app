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
     * @param string        $name
     * @param string        $type
     * @param callable|null $callback
     */
    public function __construct(string $name, string $type = 'primary', callable $callback = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->callback = $callback;
    }

    /**
     * @param string $name
     * @param string $route
     * @param array  $params
     *
     * @return Link
     */
    public function link(string $name, string $route = '', array $params = []): Link
    {
        $link = new Link($name, $route, $params);
        $this->link[] = $link;
        return $link;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $list = [];
        foreach ($this->link as $class) {
            $list[] = [
                'nodeName' => 'a-doption',
                'child' => $class->render(),
            ];
        }
        return [
            'nodeName' => 'a-dropdown',
            'child' => [
                [
                    'nodeName' => 'a-button',
                    'type' => $this->type,
                    'child' => $this->name
                ],
                [
                    'nodeName' => 'div',
                    'vSlot:content' => '',
                    'child' => $list
                ]
            ]
        ];

    }

}
