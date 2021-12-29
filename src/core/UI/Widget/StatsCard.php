<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * 统计卡片
 * Class StatsCard
 * @package Duxravel\Core\UI\Widget
 */
class StatsCard extends Widget
{

    private int $column;
    private array $items = [];

    /**
     * @param callable|null $callback
     * @param int           $column
     */
    public function __construct(callable $callback = null, int $column = 4)
    {
        $this->callback = $callback;
        $this->column = $column;
    }

    /**
     * @param $name
     * @param $num
     *
     * @return $this
     */
    public function item($name, $num): self
    {
        $this->items[] = [
            'name' => $name,
            'num' => $num,
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $childs = [];
        foreach ($this->items as $vo) {
            $childs[] = [
                'nodeName' => 'div',
                'child' => [
                    'nodeName' => 'a-statistic',
                    'title' => $vo['name'],
                    'value' => $vo['num'],
                ]
            ];
        }

        return [
            'nodeName' => 'div',
            'class' => 'grid gap-4 grid-cols-' . $this->column,
            'child' => $childs
        ];

    }

}
