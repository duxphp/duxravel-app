<?php

namespace Duxravel\Core\UI\Form;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;

use Duxravel\Core\UI\Tools;

/**
 * Class Layout
 * @package Duxravel\Core\UI\Form
 */
class Layout extends Element implements Component
{
    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
        $this->layout = false;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $this->class('pb-4');
        $innerHtml = is_callable($this->callback) ? call_user_func($this->callback) : $this->callback;
        return <<<HTML
            <div {$this->toElement()}>$innerHtml</div>
        HTML;
    }

}
