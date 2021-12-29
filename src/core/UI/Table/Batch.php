<?php

namespace Duxravel\Core\UI\Table;

use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Form\Select;

/**
 * 操作批量
 * Class Column
 * @package Duxravel\Core\UI\Filter
 */
class Batch
{

    protected array $nodes = [];
    protected array $select = [];
    protected array $url = [];

    /**
     * 按钮
     * @param string $name
     * @param string $type
     * @param string $route
     * @param array  $params
     * @param string $btnType
     * @return $this
     */
    public function button(string $name, string $type = '', string $route = '', array $params = [], string $btnType = 'normal'): self
    {
        $params['bath_type'] = $type;
        $url = route($route, $params);
        $this->nodes[] = [
            'nodeName' => 'a-button',
            'type' => 'secondary',
            'status' => $btnType,
            'child' => $name,
            'vOn:click' => "footer.checkAction('$url', '确定执行$name\操作？')"
        ];
        return $this;
    }

    /**
     * @param string $name
     * @param string $route
     * @param array  $params
     * @return $this
     */
    public function select(string $name, string $route = '', array $params = []): self
    {
        $url = route($route, $params);
        $this->select[] = [
            'nodeName' => 'a-doption',
            'child' => $name,
            'vOn:click' => "footer.checkAction('$url' '确定执行$name\操作？')"
        ];
        return $this;
    }

    /**
     * 渲染组件
     * @return array
     */
    public function render(): array
    {
        if ($this->select) {
            $this->nodes[] = [
                'nodeName' => 'a-dropdown',
                'child' => [
                    [
                        'nodeName' => 'a-button',
                        'type' => 'secondary',
                        'child' => '批量操作',
                    ],
                    [
                        'vSlot:content' => '',
                        'nodeName' => 'div',
                        'child' => $this->select
                    ]
                ],
            ];
        }

        return $this->nodes;
    }

}
