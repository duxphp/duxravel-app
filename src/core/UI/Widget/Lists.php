<?php

namespace Duxravel\Core\UI\Widget;

use Duxravel\Core\UI\Tools;

/**
 * Class Lists
 * @package Duxravel\Core\UI\Widget
 */
class Lists extends Widget
{

    private array $data;
    private bool $row = true;

    /**
     * Lists constructor.
     * @param $data
     * @param int $col
     * @param callable|null $callback
     */
    public function __construct($data, callable $callback = NULL)
    {
        $this->data = $data;
        $this->callback = $callback;
    }

    /**
     * @param $bool
     * @return $this
     */
    public function row($bool)
    {
        $this->row = $bool;
        return $this;
    }


    /**
     * @return array
     */
    public function render(): array
    {

        $inner = [];
        $count = count($this->data);
        $i = 0;
        foreach ($this->data as $item) {
            $items = [];
            if ($this->row) {
                if (is_array($item)) {
                    foreach ($item as $vo) {
                        $items[] = [
                            'nodeName' => 'div',
                            'child' => $vo
                        ];
                    }
                    $col = count($item);
                } else {
                    $items[] = [
                        'nodeName' => 'div',
                        'child' => $item
                    ];
                    $col = 1;
                }
                $inner[] = [
                    'nodeName' => 'div',
                    'class' => "grid grid-cols-{$col} gap-4 p-4 bg-white $border shadow rounded",
                    'child' => $items
                ];
            } else {
                $items = [
                    [
                        'nodeName' => 'div',
                        'class' => 'text-gray-900 text-base',
                        'child' => $item['name']
                    ],
                    [
                        'nodeName' => 'div',
                        'class' => 'text-gray-400',
                        'child' => $item['value']
                    ]
                ];
                $inner[] = [
                    'nodeName' => 'div',
                    'class' => "p-4 bg-white $border shadow rounded",
                    'child' => $items
                ];
            }
        }

        return $inner ? [
            'nodeName' => 'div',
            'class' => 'p-4 bg-gray-100 flex flex-col gap-2',
            'child' => $inner
        ] : [
            'nodeName' => 'app-empty',
            'title' => '暂无数据',
            'content' => '当前列表暂无数据记录',
        ];
    }

}
