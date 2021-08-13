<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;
use Duxravel\Core\UI\Widget\Append\Element;

/**
 * Class TreeList
 * @package Duxravel\Core\UI\Widget
 */
class TreeList extends Widget
{

    private $data;
    private $key;
    private $jump;

    public function __construct($default, $data, $field = '')
    {
        $this->data = $data;
        $this->key = $default;
        $this->field = $field;
    }

    private function loop($data, $map) {
        $list = [];
        foreach ($data as $vo) {
            $item = [];
            $item['label'] = $vo[$map['label']];
            $item['key'] = $vo[$map['key']];
            if ($vo['children']) {
                $item['children'] = $this->loop($vo['children'], $map);
            }

            $list[] = $item;
        }
        return $list;
    }

    public function format($map = [])
    {
        $this->data = $this->loop($this->data, $map);
        return $this;
    }

    public function jump($bool)
    {
        $this->jump = $bool;
        return $this;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $tree = [
            'nodeName' => 'n-tree',
            'block-line' => true,
            'vBind:pattern' => 'data.search',
            'class' => 'mt-4',
            'default-selected-keys' => [$this->key ?: 0],
            'data' => $this->data,
        ];

        if ($this->jump) {
            $tree['render-label:info'] = [
                'nodeName' => 'route',
                'href' => 'info.option.url',
                'class' => 'block',
                'child' => [
                    'nodeName' => 'n-ellipsis',
                    'child' => '{{info.option.label}}'
                ]
            ];
        } else {
            $tree['vModel:selected-keys'] = [
                "data.filter['{$this->field}']"
            ];
        }
        return [
            'nodeName' => 'div',
            'class' => 'w-40 p-2',
            'child' => [
                [
                    'nodeName' => 'div',
                    'class' => 'p-2',
                    'child' => [
                        'nodeName' => 'n-input',
                        'placeholder' => '搜索',
                        'vModel:value' => 'data.search',
                        'round' => true,
                        'child' => [
                            'vSlot:prefix' => '',
                            'nodeName' => 'n-icon',
                            'child' => [
                                'nodeName' => 'search-icon'
                            ]
                        ],
                    ],
                ],
                $tree
            ]
        ];
    }

}
