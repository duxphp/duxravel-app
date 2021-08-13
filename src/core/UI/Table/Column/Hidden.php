<?php

namespace Duxravel\Core\UI\Table\Column;


/**
 * Class Hidden
 */
class Hidden implements Component
{

    /**
     * @param $field
     * @return string
     */
    public function render($field): array
    {
        $name = $field . '_show';
        return [
            'nodeName' => 'div',
            'child' => [
                [
                    'nodeName' => 'span',
                    'class' => 'mr-2',
                    'vIf' => "rowData['$name']",
                    'child' => "{{rowData['$field']}}",
                ],
                [
                    'nodeName' => 'n-tag',
                    'vBind:type' => "rowData['$name'] ? 'warning' : 'info'",
                    'class' => 'mr-2',
                    'vOn:click' => "rowData['$name'] = !rowData['$name']",
                    'child' => "{{rowData['$name'] ? '隐藏' : '显示'}}",
                ],
            ]
        ];
    }

}
