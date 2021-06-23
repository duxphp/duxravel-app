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
     * Status constructor.
     * @param array $map
     * @param array $color
     * @param string $type
     */
    public function __construct(array $map, array $color, string $type = 'badge')
    {
        $this->map = $map;
        $this->color = $color;
        $this->type = $type;
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function render($value, $data): string
    {
        $statusArr = [];
        foreach ($this->map as $key => $vo) {
            $statusArr[$key]['name'] = $vo;
        }
        foreach ($this->color as $key => $vo) {
            $statusArr[$key]['color'] = $vo;
        }
        $statusRaw = $statusArr[(int)$value];
        $statusColor = $statusRaw['color'] ?: 'blue';
        if ($this->type === 'badge') {
            return (new Badge($statusRaw['name']))->color($statusColor)->render();
        }
        if ($this->type === 'text') {
            return '<span class="text-' . $statusColor . '-900">' . $statusRaw['name'] . '</span>';
        }
    }

}
