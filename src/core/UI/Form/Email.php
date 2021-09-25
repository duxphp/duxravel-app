<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Email
 * @package Duxravel\Core\UI\Form
 */
class Email extends Element implements Component
{
    protected Text $object;

    /**
     * Text constructor.
     * @param  string  $name
     * @param  string  $field
     * @param  string  $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->object = new Text($this->name, $this->field, $this->has);
        $this->object->afterIcon('mail');
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
