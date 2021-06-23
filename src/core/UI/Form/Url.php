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
        $this->object->type('text');
        $this->object->attr('data-js', 'form-mask');
        $this->object->attr('data-inputmask-alias' , "url");
        $this->object->afterIcon('<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="4" width="16" height="16" rx="1" /><line x1="4" y1="8" x2="20" y2="8" /><line x1="8" y1="4" x2="8" y2="8" /></svg>');
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
