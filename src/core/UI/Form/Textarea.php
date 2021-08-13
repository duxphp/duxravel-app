<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;
use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Tools;

/**
 * Class Textarea
 * 多行输入框
 * @package Duxravel\Core\UI\Form
 */
class Textarea extends Element implements Component
{
    protected Text $object;

    /**
     * Text constructor.
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->attr('type', 'textarea');
    }


    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        return $this->object->getRender();
    }

}
