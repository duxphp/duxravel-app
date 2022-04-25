<?php

namespace Duxravel\Core\UI\Table\Column;

use Duxravel\Core\UI\Widget\Badge;

/**
 * Class Images
 */
class Images implements Component
{

    private int $size;

    /**
     * Tags constructor.
     */
    public function __construct(int $size)
    {
        $this->size = $size;
    }

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $node = [
            'nodeName' => 'div',
            'vFor' => "item in rowData.record['$label']",
            'class' => "flex-none bg-cover  w-{$this->size} h-{$this->size}",
            'vBind:style' => "{'background-image': 'url(' + item + ')'}"
        ];

        return [
            'nodeName' => 'div',
            'class' => 'flex gap-2',
            'child' => $node
        ];
    }

    public function getData($rowData, $field, $value): array
    {
        return [$field => !is_array($value) ? json_decode($value, true) : $value];

    }

}
