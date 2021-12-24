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

    private $column = 0;
    private $items = [];

    /**
     * StatsCard constructor.
     *
     * @param callable|null $callback
     * @param int           $column
     */
    public function __construct(callable $callback = null, $column = 4)
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
        $inner = [];
        foreach ($this->items as $vo) {
            $icon = (new Icon($vo['icon']))->class('text-white');
            $inner[] = <<<HTML
                <div class="text-white bg-{$vo['color']}-900 rounded shadow p-4">
                      <div class="flex items-center space-x-4">
                        <div class="flex-none w-10 h-10">$icon</div>
                        <div class="flex-grow">
                          <div class="text-xl font-medium">
                            {$vo['num']}
                          </div>
                          <div class="opacity-80">
                            {$vo['name']}
                          </div>
                        </div>
                    </div>
                </div>
            HTML;
        }
        $childs = [];
        foreach ($this->items as $vo) {
            $childs[] = [
                'nodeName' => 'div',
                'class' => "text-white bg-{$vo['color']}-600 rounded shadow p-4",
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
