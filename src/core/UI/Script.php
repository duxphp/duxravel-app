<?php

namespace Duxravel\Core\UI;

/**
 * 前端脚本触发器
 * Class Script
 * @package Duxravel\Core\UI
 */
class Script
{
    public array $data = [];

    /**
     * 增加动作
     * @param string $script
     * @return $this
     */
    public function add(string $script): self
    {
        $this->data[] = $script;
        return $this;
    }

    /**
     * 渲染数据
     * @param bool $inner
     * @return array|string
     */
    public function render(bool $inner = false)
    {
        $script = implode("\n", $this->data);
        if ($inner) {
            return $script;
        }

        return [
            '__script' => $script
        ];
    }

}
