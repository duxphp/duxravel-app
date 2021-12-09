<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Url
 * url输入
 * @package Duxravel\Core\UI\Form
 */
class Url extends Element implements Component
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
        $this->object->beforeText('http(s)');
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render()
    {
        $this->object->attrArray($this->attr);
        return $this->object->getRender();
    }

}
