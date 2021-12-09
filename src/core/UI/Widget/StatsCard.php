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
     * @param callable|null $callback
     * @param int $column
     */
    public function __construct(callable $callback = NULL, $column = 4)
    {
        $this->callback = $callback;
        $this->column = $column;
    }

    /**
     * @param $name
     * @param $num
     * @param $icon
     * @param string $color
     * @return $this
     */
    public function item($name, $num, $icon, $color = 'blue'): self
    {
        $this->items[] = [
            'name' => $name,
            'num' => $num,
            'icon' => $icon,
            'color' => $color
        ];
        return $this;
    }

    public function type($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $this->class('grid gap-4 grid-cols-1 lg:grid-cols-' . $this->column);
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
        return <<<HTML
            <div>
                {$this->mergeArray($inner)}
            </div>
        HTML;

    }

}
