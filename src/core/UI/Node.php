<?php

namespace Duxravel\Core\UI;

use Duxravel\Core\UI\Node\NodeEl;

/**
 * node 生成工具
 */
class Node
{

    public $nodes = [];

    public function render()
    {
        $data = [];
        foreach ($this->nodes as $vo) {
            $data[] = $vo->render();
        }
        return $data;
    }

    public function __call($method, $arguments)
    {
        $nodeEl = new NodeEl($method, $arguments[0]);
        $this->nodes[] = $nodeEl;
        return $nodeEl;
    }
}
