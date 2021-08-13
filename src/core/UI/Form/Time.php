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
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'n-time-picker',
            'clearable' => true,
            'placeholder' => $this->attr['placeholder'] ?: '请选择' . $this->name,
        ];
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}
