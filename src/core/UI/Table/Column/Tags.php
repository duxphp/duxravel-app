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
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        $valueArray = !is_array($value) ? explode(',', $value) : $value;
        $tagsArr = [];
        foreach ($this->map as $key => $vo) {
            $tagsArr[$key]['name'] = $vo;
        }
        foreach ($this->color as $key => $vo) {
            $tagsArr[$key]['color'] = $vo;
        }
        $inner = [];
        foreach ($tagsArr as $key => $vo) {
            if (in_array($key, $valueArray)) {
                $inner[] = (new Badge($vo['name']))->color($vo['color'] ?: 'blue')->render();
            }
        }
        $innerHtml = implode(' ', $inner);
        return <<<HTML
            <div class="flex align-center space-x-2">$innerHtml</div>
        HTML;

    }

}
