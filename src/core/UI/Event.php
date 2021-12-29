<?php

namespace Duxravel\Core\UI;

/**
 * 前端事件触发器
 * Class Table
 * @package Duxravel\Core\UI
 */
class Event
{

    public string $name = '';
    public array $data = [];

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 增加动作
     * @param        $type
     * @param string $key
     * @param array  $data
     * @param array  $attr
     * @return $this
     */
    public function add($type, string $key = '', array $data = [], array $attr = []): self
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
     * @return array
     */
    public function render(bool $inner = false): array
    {
        if ($inner) {
            return [
                'name' => $this->name,
                'data' => $this->data
            ];
        }

        return [
            '__event' => [
                'name' => $this->name,
                'data' => $this->data
            ]
        ];
    }

}
