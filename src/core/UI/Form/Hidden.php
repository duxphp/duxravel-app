<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Text
 * @package Duxravel\Core\UI\Form
 */
class Hidden extends Element implements Component
{
    /**
     * Text constructor.
     * @param  string  $field
     * @param  string  $has
     */
    public function __construct(string $field, string $has = '')
    {
        $this->layout = false;
        $this->field = $field;
        $this->has = $has;
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render($value): string
    {
        $value = $this->getValue($value);
        $this->attr['name'] = $this->field;
        $this->attr['value'] = $value;
        $this->attr('name', $this->field);
        $this->attr('value', $value);
        return "<input type='hidden' {$this->toElement()}>";
    }

}
