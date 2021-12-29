<?php

namespace Duxravel\Core\UI;

use Duxravel\Core\UI\Node\NodeEl;

/**
 * node 生成工具
 */
class Node
{

    public array $nodes = [];

    /**
     * @return array
     */
    public function render(): array
    {
        $data = [];
        foreach ($this->nodes as $vo) {
            $data[] = $vo->render();
        }
        return $data;
    }

    /**
     * @param $method
     * @param $arguments
     * @return NodeEl
     */
    public function __call($method, $arguments)
    {
        $nodeEl = new NodeEl($method, $arguments[0]);
        $this->nodes[] = $nodeEl;
        return $nodeEl;
    }
}
