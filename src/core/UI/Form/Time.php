<?php

namespace Duxravel\Core\UI\Form;

/**
 * Class Time
 * 时间选择器
 * @package Duxravel\Core\UI\Form
 */
class Time extends Element implements Component
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
        $this->object->attr('data-js', 'form-date');
        $this->object->attr('data-type', 'time');
        $this->object->type('text');
        $this->object->attr('placeholder', '请选择'.$this->name);
        $this->object->afterIcon('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>');
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
