<?php

namespace Duxravel\Core\UI\Table\Column;


/**
 * Class Hidden
 */
class Hidden implements Component
{

    /**
     * @param $label
     * @return array
     */
    public function render($label): array
    {
        $name = $label . '_show';
        return [
            'nodeName' => 'div',
            'child' => [
                [
                    'nodeName' => 'span',
                    'class' => 'mr-2',
                    'vIf' => "rowData.record['$name']",
                    'child' => "{{rowData.record['$label']}}",
                ],
                [
                    'nodeName' => 'a-button',
                    'shape' => 'round',
                    'size' => 'mini',
                    'type' => 'outline',
                    'vBind:status' => "rowData.record['$name'] ? 'warning' : ''",
                    'class' => 'mr-2',
                    'vOn:click' => "rowData.record['$name'] = !rowData.record['$name']",
                    'child' => "{{rowData.record['$name'] ? '隐藏' : '显示'}}",
                ],
            ]
        ];
    }

}
