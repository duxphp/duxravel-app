<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * 行布局组件
 * Class Row
 * @package Duxravel\Core\UI\Widget
 */
class Row extends Widget
{

    private array $column = [];

    /**
     * Row constructor.
     * @param callable|null $callback
     */
    public function __construct(callable $callback = NULL)
    {
        $this->callback = $callback;
    }

    /**
     * 设置列
     * @param callable $callback
     * @param int $width
     * @return $this
     */
    public function column(callable $callback, int $width = 0): self
    {
        $this->column[] = [
            'width'  => $width,
            'callback' => $callback,
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {

        $nodes = [];
        foreach ($this->column as $vo) {
            $nodes[] = [
                'nodeName' => 'div',
                'class' => $vo['width'] ? "row-span-{$vo['width']}" : '',
                'child' => call_user_func($vo['callback'])
            ];
        }
        return [
          'nodeName' => 'grid grid-flow-col gap-x-4',
            'child' => $nodes
        ];


    }

}
