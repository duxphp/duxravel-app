<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Editor
 * @package Duxravel\Core\UI\Form
 */
class Editor extends Element implements Component
{
    protected Textarea $object;

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
        $this->object = new Textarea($this->name, $this->field, $this->has);
        $this->object->attr('data-js', 'form-editor');
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        return $this->object->render($this->getValue($value));
    }

}
