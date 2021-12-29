<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Widget\Badge;

/**
 * Class Tags
 */
class Tags implements Component
{

    private array $map;
    private array $color;

    /**
     * Tags constructor.
     * @param array $map
     * @param array $color
     */
    public function __construct(array $map, array $color)
    {
        $this->map = $map;
        $this->color = $color;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $tagsArr = [];
        foreach ($this->map as $key => $vo) {
            $tagsArr[$key]['name'] = $vo;
        }
        foreach ($this->color as $key => $vo) {
            $tagsArr[$key]['color'] = $vo;
        }

        $node = [];
        foreach ($tagsArr as $key => $vo) {
            $item = (new Badge($vo['name']))->color($vo['color'])->render();
            $item['vIf'] = "~rowData.record['{$label}'].indexOf(". (is_numeric($key) ? $key : "'$key'") .")";
            $node[] = $item;
        }

        return [
            'nodeName' => 'div',
            'class' => 'flex gap-2',
            'child' => $node
        ];
    }

    public function getData($rowData, $field, $value): array
    {
        return [$field => !is_array($value) ? explode(',', $value) : $value];

    }

}
