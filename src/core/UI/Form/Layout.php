<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Composite;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Tools;

/**
 * Class Row
 * @package Duxravel\Core\UI\Table
 */
class Layout extends Composite implements Component
{
    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value)
    {
        $callback = is_callable($this->callback) ? call_user_func($this->callback) : $this->callback;
        return [
            'nodeName' => 'div',
            'class' => 'mb-4',
            'child' => $callback
        ];

    }

}
