<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Widget\Badge;

/**
 * Class Status
 */
class Status implements Component
{

    private array $map;
    private array $color;
    private string $type;

    /**
     * @param  array  $map
     * @param  array  $color
     * @param  string $type
     */
    public function __construct(array $map, array $color, string $type = 'badge')
    {
        $this->map   = $map;
        $this->color = $color;
        $this->type  = $type;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $statusArr = [];
        foreach ($this->map as $key => $vo) {
            $statusArr[$key]['name']  = $vo;
            $statusArr[$key]['color'] = $this->color[$key] ?? 'blue';
        }

        // 可自定义label
        $labelIf = count(explode('.', $label)) > 1 ? $label : "rowData.record['{$label}']";

        $node = [];
        foreach ($statusArr as $key => $vo) {
            if ($this->type === 'badge') {
                $item = (new Badge($vo['name']))->color($vo['color'])->render();
            } else {
                $item = [
                    'nodeName' => 'div',
                    'class'    => 'text-' . $vo['color'] . '-900',
                    'child'    => $vo['name'],
                ];
            }
            $item['vIf'] = $labelIf . " == " . (is_numeric($key) ? $key : "'$key'");

            $node[] = $item;
        }
        return $node;
    }

}
