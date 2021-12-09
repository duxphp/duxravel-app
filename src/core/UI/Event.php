<?php

namespace Duxravel\Core\UI;

/**
 * 前端事件触发器
 * Class Table
 * @package Duxravel\Core\UI
 */
class Event
{

    public $name = '';
    public $data = [];

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 增加动作
     * @param $type
     * @param string $key
     * @param array $data
     * @param array $attr
     * @return $this
     */
    public function add($type, $key = '', $data = [], $attr = [])
    {
        $this->data[] = array_filter(array_merge([
            'type' => $type,
            'key' => $key,
            'data' => $data
        ], $attr));
        return $this;
    }

    /**
     * 渲染数据
     * @param false $inner
     * @return array|array[]
     */
    public function render($inner = false)
    {
        if ($inner) {
            return [
                'name' => $this->name,
                'data' => $this->data
            ];
        } else {
            return [
                '__event' => [
                    'name' => $this->name,
                    'data' => $this->data
                ]
            ];
        }
    }

}
