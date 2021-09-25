<?php

namespace Duxravel\Core\UI\Node;


use Duxravel\Core\UI\Node;

/**
 * node 属性
 */
class NodeEl
{

    public $name = 'div';
    public $callback;

    public $attr = [];

    public function __construct($name, $callback = null)
    {
        $this->name = $name;
        if ($callback instanceof \Closure) {
            $node = new Node();
            call_user_func($callback, $node);
            $this->callback = $node;
        }else {
            $this->callback = $callback;
        }
    }

    public function render()
    {
        $data = [
            'nodeName' => $this->name,
        ];
        if ($this->callback instanceof Node) {
            $data['child'] = $this->callback->render();
        } else if ($this->callback) {
            $data['child'] = $this->callback;
        }
        return array_merge($data, $this->attr);
    }


    public function __call($method, $arguments)
    {
        $this->attr[$method] = $arguments[0];
        return $this;
    }
}
