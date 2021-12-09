<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Composite;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Tools;

/**
 * Class Html
 * @package Duxravel\Core\UI\Table
 */
class Html extends Element implements Component
{
    protected $callback;

    public function __construct($name, $callback)
    {
        $this->name = $name;
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
        return <<<HTML
            <div>$callback</div>
        HTML;

    }

}
